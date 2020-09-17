<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CoverController;
use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Requests\UserRequest;
// use App\Mail\ForgotPassword;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use Reminder;
use Sentinel;
use stdClass;
use URL;
use Validator;
use View;


class AuthController extends CoverController
{
    /**
     * Account sign in.
     *
     * @return View
     */
    public function getSignin()
    {
        // Is the user logged in?
        if (Sentinel::check()) {
            return Redirect::route('admin.dashboard');
        }
        // Show the page
        return view('admin.login');
    }

    /**
     * Account sign in form processing.
     * @param Request $request
     * @return Redirect
     */
    public function postSignin(Request $request)
    {

        try {
            // Try to log the user in
            if ($user = Sentinel::authenticate($request->only(['email', 'password']), $request->get('remember-me', false))) {
                // Redirect to the dashboard page
                return Redirect::route("admin.dashboard")->with('success', trans('auth/message.signin.success'));
            }
            $this->messageBag->add('email', trans('auth/message.account_not_found'));

        } catch (NotActivatedException $e) {
            $this->messageBag->add('email', trans('auth/message.account_not_activated'));
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            $this->messageBag->add('email', trans('auth/message.account_suspended', compact('delay')));
        }

        // Ooops.. something went wrong
        return Redirect::back()->withInput()->withErrors($this->messageBag);
    }

    /**
     * Forgot password form processing page.
     * @param Request $request
     *
     * @return Redirect
     */
    public function postForgotPassword(Request $request)
    {
        try {
            // Get the user password recovery code
            $user = Sentinel::findByCredentials(['email' => $request->get('email_forgot')]);
            if (!$user) {
                return back()->with('success', trans('auth/message.forgot-password.success'));
            }

            $reminder = Reminder::exists($user) ? : Reminder::create($user);

            // Send the activation code through email
            $this->sendEmail($user, $reminder->code);
            return back()->with('success', trans('auth/message.forgot-password.success'));

        } catch (UserNotFoundException $e) {
            return back()->with('success', trans('auth/message.forgot-password.success'));
        }

        //  Redirect to the forgot password
        return back()->with('success', trans('auth/message.forgot-password.success'));
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

    /**
     * Forgot Password Confirmation page.
     *
     * @param number $userId
     * @param  string $passwordResetCode
     * @return View
     */
    public function getForgotPasswordConfirm($email,$resetCode = null)
    {
        $user = Sentinel::findByCredentials(['email' => $email]);
        // Find the user using the password reset code
        if(!$user) {
            // Redirect to the forgot password page
            abort(404);
            // return Redirect::route('forgot-password')->with('error', trans('auth/message.account_not_found'));
        }
        if($reminder = Reminder::exists($user)) {
            if($resetCode == $reminder->code) {
                return view('forgotpwd-confirm');
            } else{
                return Redirect::route('signin')->with('error', trans('auth/message.forgot-password-confirm.error'));
            }
        } else {
            return Redirect::route('signin')->with('error', trans('auth/message.forgot-password-confirm.error'));
        }
    }

    /**
     * Forgot Password Confirmation form processing page.
     *
     * @param Request $request
     * @param number $userId
     * @param  string   $passwordResetCode
     * @return Redirect
     */
    public function postForgotPasswordConfirm(Request $request, $email, $resetCode = null)
    {
        $user = Sentinel::findByCredentials(['email' => $email]);
        // Find the user using the password reset code
        if(!$user) {
            // Redirect to the forgot password page
            abort(404);
            // return Redirect::route('forgot-password')->with('error', trans('auth/message.account_not_found'));
        }
        if($reminder = Reminder::exists($user)) {
            if($resetCode == $reminder->code) {
                Reminder::complete($user, $resetCode, $request->get('password'));
                return Redirect::route('signin')->with('success', trans('auth/message.forgot-password-confirm.success'));
            } else{
                return Redirect::route('signin')->with('error', trans('auth/message.forgot-password-confirm.error'));
            }
        } else {
            return Redirect::route('signin')->with('error', trans('auth/message.forgot-password-confirm.error'));
        }
    }

    /**
     * Logout page.
     *
     * @return Redirect
     */
    public function getLogout()
    {

        if (Sentinel::check()) {
            //Activity log
            $user = Sentinel::getuser();
            // Log the user out
            Sentinel::logout();
            // Redirect to the users page
            return Redirect::route('signin')->with('success', trans('__title.notifications.logout_success'));
        } else {
        // Redirect to the users page
            return Redirect::route('signin')->with('error', trans('__title.notifications.must_login'));
        }
    }
}
