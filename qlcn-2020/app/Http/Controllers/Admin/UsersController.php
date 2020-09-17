<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CoverController;
use App\Http\Requests\UserRequest;
use App\Mail\Register;
use App\Mail\Restore;
use App\User;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Sentinel;
use URL;
use View;
use Yajra\DataTables\DataTables;


class UsersController extends CoverController
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index()
    { 
        // Show the page
        return view('admin.users.index');
    }

    /*
     * Pass data through ajax call
     */
    /**
     * @return mixed
     */
    public function data()
    {
        $users = User::get(['id', 'name', 'email','created_at']);
        return DataTables::of($users)
            ->editColumn('created_at',function(User $user) {
                return $user->created_at->diffForHumans();
            })
            ->addColumn('status',function($user){
                $role = $user->roles[0]->name;
                if($role == 'Admin'){
                    return trans('__title.users.role_admin');
                }
                else
                    return trans('__title.users.role_staff');
            })
            ->addColumn('actions',function($user) {
                $actions = '<a href='. route('admin.users.edit', $user->id) .'><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="'.trans('__title.users.title_edit').'"></i></a>';
                if ((Sentinel::getUser()->id != $user->id) && ($user->id != 1)) {
                    $actions .= '<a href='. route('admin.users.confirm-delete', $user->id) .' data-toggle="modal" data-target="#delete_confirm"><i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="'.trans('__title.users.title_delete').'"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Create new user
     *
     * @return View
     */
    public function create()
    {
        // Get all the available groups
        $roles = Sentinel::getRoleRepository()->all();
        // Show the page
        return view('admin.users.create', compact('roles'));
    }

    /**
     * User create form processing.
     *
     * @return Redirect
     */
    public function store(UserRequest $request) //=Register new user
    {
        try {
            // Register the user and active immediately
            $user = Sentinel::registerAndActivate([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);

            //add user to 'User' role
            $role = Sentinel::findRoleById($request->role);
            $role->users()->attach($user);

            // Redirect to the home page with success menu
            return Redirect::route('admin.users.index')->with('success', trans('users/message.success.create'));

        } catch (UserExistsException $e) {
            $this->messageBag->add('email', trans('auth/message.account_already_exists'));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
        // return Redirect::back()->withInput()->with('error', $error);
    }

    /**
     * User update.
     *
     * @param  int $id
     * @return View
     */
    public function edit(User $user)
    {

        // Get this user role
        $userRoles = $user->getRoles()->pluck('name', 'id')->all();

        // Get a list of all the available roles
        $roles = Sentinel::getRoleRepository()->all();
        // $status = Activation::completed($user);

        // Show the page
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * User update form processing page.
     *
     * @param  User $user
     * @param UserRequest $request
     * @return Redirect
     */
    public function update(User $user, UserRequest $request)
    {
        try {
            $user->update($request->except('roles'));
            //save record
            $user->save();

            // Get the current user role
            $userRoles = $user->roles()->pluck('id')->all();

            // Get the selected role
            $selectedRoles = $request->get('roles');

            // Groups comparison between the role the user currently
            // have and the role the user wish to have.
            $rolesToAdd = array_diff($selectedRoles, $userRoles);
            $rolesToRemove = array_diff($userRoles, $selectedRoles);

            // Assign the user to roles
            foreach ($rolesToAdd as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->attach($user);
            }

            // Remove the user from roles
            foreach ($rolesToRemove as $roleId) {
                $role = Sentinel::findRoleById($roleId);
                $role->users()->detach($user);
            }

            // Was the user updated?
            if ($user->save()) {
                // Prepare the success message
                $success = trans('users/message.success.update');
                // Redirect to the user page
                return Redirect::route('admin.users.edit', $user)->with('success', $success);
            }

            // Prepare the error message
            $error = trans('users/message.error.update');
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', $error);
        }

        // Redirect to the user page
        return Redirect::route('admin.users.edit', $user)->withInput()->with('error', $error);
    }

    /**
     * Delete Confirm
     *
     * @param   int $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $model = 'users';
        $method = 'delete';
        $confirm_route = $error = null;
        try {
            // Get user information
            $user = Sentinel::findById($id);
            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('users/message.error.delete');
                return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
            }
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
        $confirm_route = route('admin.users.destroy', ['id' => $user->id]);
        return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route', 'method'));
    }

    /**
     * Delete the given user.
     *
     * @param  int $id
     * @return Redirect
     */
    public function destroy($id)
    {
        try {
            // Get user information
            $user = Sentinel::findById($id);
            // Check if we are not trying to delete ourselves
            if ($user->id === Sentinel::getUser()->id) {
                // Prepare the error message
                $error = trans('users/message.error.delete');
                // Redirect to the user management page
                return Redirect::route('admin.users.index')->with('error', $error);
            }
            //Delete the user
            //to allow soft deleted, we are performing query on users model instead of Sentinel model
            User::destroy($id);
            Activation::where('user_id',$user->id)->delete();
            // Prepare the success message
            $success = trans('users/message.success.delete');
            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('success', $success);
        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = trans('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', $error);
        }
    }

    public function passwordreset(Request $request)
    {
        $user = Sentinel::getUser();
        $hasher = Sentinel::getHasher();
        $password = $request->password;
        $current_pw = $request->current_pw;
        if(!$hasher->check($current_pw,$user->password)){
            return response()->json(['status' => 'error', 'message' => trans('__title.users.alert_error_current_pw')]);
        }
        $user->password = Hash::make($password);
        $user->save();
        return response()->json(['status' => 'success', 'message' => trans('__title.users.alert_success_pw')]);
    }

    public function show_edit_profile()
    {
        $user = Sentinel::getUser();
        return view('admin.users.profile', compact('user'));
    }
    public function update_profile(UserRequest $request)
    {
        $user = Sentinel::getUser();
        //update values
        $user->update($request->except('email'));

        // Was the user updated?
        if ($user->save()) {
            // Prepare the success message
            $success = trans('users/message.success.update');
            // Redirect to the user page
            return Redirect::route('admin.users.show_edit_profile')->with('success', $success);
        }

        // Prepare the error message
        $error = trans('users/message.error.update');

        // Redirect to the user page
        return Redirect::route('admin.users.show_edit_profile')->withInput()->with('error', $error);
    }
}
