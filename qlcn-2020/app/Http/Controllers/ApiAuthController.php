<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserAPI;
use JWTAuth;
use JWTAuthException;
use Hash;
use Mail;
use DB;
use Reminder;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Capsule\Manager as Capsule;

class APIAuthController extends Controller
{
    private $user;
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserAPI $user)
    {
       
        $this->middleware('auth:api', ['except' => ['login','postForgotPassword','refresh']]);
        $this->user = $user;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        $token = null;
        try {
            // $myTTL = 360000; //minutes

            // JWTAuth::factory()->setTTL($myTTL);
           if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message'=>'invalid_email_or_password'], 422);
           }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        
        return response()->json(['message' => 'Successfully',
            'token' => $token,
            'account'=>auth()->user(),
            'role' => DB::table('role_users')->where("user_id",auth()->user()->id)->first()->role_id
        ]);
        
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL()
        ]);
    }
    public function resgiter(AuthRequest $request){
        
        $roles = DB::table('role_users')->where("user_id",auth()->user()->id)->first();
     
        if($roles->role_id == 1){
            $user = Sentinel::registerAndActivate([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'), 
            ]);
            $role = Sentinel::findRoleById($request->role);
            $role->users()->attach($user);
              return response()->json([
                  'status'=> 200,
                  'message'=> 'successfully',
                  'data'=>$user,
              ]);
             
        }else{
            return response()->json([
                'status'=> 200,
                'message'=> 'fails',
                ]);
        }   
    }
    public function getAll(){
        $user = $this->user->all();
        return $use;
    }
    // public function forgotPassword(){
        
    // }
    public function changePassword(AuthRequest $request)
    {
        $mail =auth()->user()->email;
        $credentials = [
            'email'    => auth()->user()->email,
            'password' => $request->password,
        ];
        if( Sentinel::authenticate($credentials)){
            $user = Sentinel::getUser();
            Sentinel::update($user, array('password' => $request->new_password ));
                        $credentials = [
                'email' =>$mail,
                'password' => $request->new_password
            ];
    
            return response()->json(['message' => 'Successfully',
            'token' =>JWTAuth::attempt($credentials)]);

        }else{
            return response()->json(['message' => 'Fails']);
        }
        //Sentinel::authenticate($credentials);

    }
    public function postForgotPassword(Request $request)
    {
        try {
            // Get the user password recovery code
            $user = Sentinel::findByCredentials(['email' => $request->get('email_forgot')]);
            if (!$user) {
                // return back()->with('success', trans('auth/message.forgot-password.success'));
                return response()->json(['message' => 'Error']);
            }

            $reminder = Reminder::exists($user) ? : Reminder::create($user);

            // Send the activation code through email
            $this->sendEmail($user, $reminder->code);
            return response()->json(['message' => 'Successfully']);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => 'Error']);
        }
        //  Redirect to the forgot password
        return back()->with('success', 'Successfully');
    }




    private function sendEmail($user, $code){
        Mail::send('admin.emails.forgot-password', [
            'user' => $user,
            'code' => $code
        ], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject("Hello $user->name reset your password.");
        });
    }

    public function getAllUsser(){    
        $role = DB::table('role_users')->where("user_id",auth()->user()->id)->first();
        if($role->role_id == 1){
            $users =  UserAPI::all();
           foreach($users as $user){
            $user->permissions =  DB::table('role_users')->where("user_id",$user->id)->first();
           }
           return $users;
        }
    }

    public function ChangeRole(Request $request)
    {
        $role = DB::table('role_users')->where("user_id",auth()->user()->id)->first();
        $obj =  UserAPI::where('email', $request->email)->first();
        $credentials = [
            'email'    => auth()->user()->email,
            'password' => $request->password,
        ];
        if($role->role_id == 1 ){
            if(Sentinel::authenticate($credentials)){
                 if(DB::table('role_users')->where("user_id",$obj->id)->update(['role_id' => $request->role])){
                    return response()->json(['message' => 'Successfully']);
                 }
                return response()->json(['message' => 'Fails']);
            }
            else  
                {
                    return response()->json(['message' => 'Logout']);
                }
            }
        else {
            return response()->json(['message' => 'ErrorAu']);
        }
    }
}
