<?php
namespace App\Repositories;
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

use App\Notifications\PasswordReset;
use App\User;
use Carbon\Carbon;
use App\Events\UserLogin;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthRepository
{
    protected $user;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Authenticate an user.
     *
     * @param array $params
     * @return array
     * @throws ValidationException
     */
    public function auth($params = array())
    {
        $token = $this->validateLogin($params);

        $auth_user = $this->user->findByEmail($params['email']);

        $this->validateStatus($auth_user);

        event(new UserLogin($auth_user));

        $auth_user = $this->user->findByEmail($auth_user->email);

        return [
            'token' => $token,
            'user'  => $auth_user,
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    /**
     * Validate auth token.
     *
     * @return array
     * @throws ValidationException
     */
    public function check()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return ['authenticated' => false];
        }

        $authenticated  = true;
        $auth_user      = $this->user->findOrFail(auth()->user()->id);
        return [
            'authenticated' => $authenticated,
            'user'          => $auth_user
        ];
    }

    /**
     * Request password reset token of user.
     *
     * @param array
     * @return null
     * @throws ValidationException
     */
    public function password($params = array())
    {
        $this->validateResetPasswordStatus();

        $user = $this->validateUserAndStatusForResetPassword($params['email']);

        $token = Str::uuid();
        \DB::table('password_resets')->insert([
            'email' => $params['email'],
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $user->notify(new PasswordReset($user, $token));
    }

    /**
     * Validate login credentials.
     *
     * @param array $params
     * @return string
     * @throws ValidationException
     */
    public function validateLogin($params = array())
    {
        $email = isset($params['email']) ? $params['email'] : null;
        $password = isset($params['password']) ? $params['password'] : null;

        if (!$token = auth()->attempt(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages(['email' => 'Authentication failed.']);
        }

        return $token;
    }

    /**
     * Reset password of user.
     *
     * @param array
     * @return null
     * @throws ValidationException
     */
    public function reset($params = array())
    {
        $this->validateResetPasswordStatus();

        $user = $this->validateUserAndStatusForResetPassword($params['email']);

        $this->validateResetPasswordToken($params['token'], $params['email']);

        $this->resetPassword($params['password'], $user);

        \DB::table('password_resets')->where('email', '=', $params['email'])->where('token', '=', $params['token'])->delete();
    }

    /**
     * Update user password.
     *
     * @param string $password
     * @param User $user
     * @return null
     */
    public function resetPassword($password, $user = null)
    {
        $user = ($user) ? : \Auth::user();
        $user->password = bcrypt($password);
        $user->save();
    }

    /**
     * Activate user's account.
     *
     * @param string $activation token
     * @return null
     * @throws ValidationException
     */
    public function activate($activation_token = null)
    {
        $this->validateRegistrationStatus();

        $this->validateEmailVerificationStatus();

        $user = $this->user->findByActivationToken($activation_token);

        if (!$user) {
            throw ValidationException::withMessages(['message' => 'Invalid token.']);
        }

        if ($user->status === 'activated') {
            throw ValidationException::withMessages(['message' => 'Account already activated.']);
        }

        if ($user->status != 'pending_activation') {
            throw ValidationException::withMessages(['message' => 'Invalid token.']);
        }

        $user->status = (config('config.account_approval') ? 'pending_approval' : 'activated');
        $user->save();
    }

    /**
     * Validate current password of user.
     *
     * @param string $password
     * @return null
     * @throws ValidationException
     */
    public function validateCurrentPassword($password)
    {
        if (!\Hash::check($password, \Auth::user()->password)) {
            throw ValidationException::withMessages(['message' => 'Old password does not match. Please try again.']);
        }
    }

    /**
     * Validate reset password token.
     *
     * @param string $token
     * @param string $email
     * @return null
     * @throws ValidationException
     */
    public function validateResetPasswordToken($token, $email = null)
    {
        if ($email) {
            $reset = \DB::table('password_resets')->where('email', '=', $email)->where('token', '=', $token)->first();
        } else {
            $reset = \DB::table('password_resets')->where('token', '=', $token)->first();
        }

        if (!$reset) {
            throw ValidationException::withMessages(['message' => 'This password reset token is invalid.']);
        }

        if (date("Y-m-d H:i:s", strtotime($reset->created_at . "+" . config('config.reset_password_token_lifetime')." minutes")) < date('Y-m-d H:i:s')) {
            throw ValidationException::withMessages(['email' => 'Password reset token is expired. Please request reset password again.']);
        }
    }

    /**
     * Validate authenticated user status.
     *
     * @param authenticated user
     * @return null
     * @throws ValidationException
     */
    public function validateStatus($auth_user)
    {
        if ($auth_user->status === 'pending_activation') {
            throw ValidationException::withMessages(['email' => 'Pending activation.']);
        }

        if ($auth_user->status === 'pending_approval') {
            throw ValidationException::withMessages(['email' => 'Pending approval.']);
        }

        if ($auth_user->status === 'disapproved') {
            throw ValidationException::withMessages(['email' => 'Not activated.']);
        }

        if ($auth_user->status === 'banned') {
            throw ValidationException::withMessages(['email' => 'Account banned.']);
        }

        if ($auth_user->status != 'activated') {
            throw ValidationException::withMessages(['email' => 'Not activated.']);
        }
    }

    /**
     * Validate user for reset password.
     *
     * @param string $email
     * @return User
     * @throws ValidationException
     */
    public function validateUserAndStatusForResetPassword($email = null)
    {
        $user = $this->user->findByEmail($email);

        if (!$user) {
            throw ValidationException::withMessages(['email' => 'We can\'t find a user with that e-mail address.']);
        }

        if ($user->status != 'activated') {
            throw ValidationException::withMessages(['email' => 'Account not activated.']);
        }

        return $user;
    }

    /**
     * Check for registration availability.
     *
     * @return null
     * @throws ValidationException
     */
    public function validateRegistrationStatus()
    {
        if (!config('config.registration')) {
            throw ValidationException::withMessages(['message' => 'Registration not available.']);
        }
    }

    /**
     * Check for email verification availability.
     *
     * @return null
     * @throws ValidationException
     */
    public function validateEmailVerificationStatus()
    {
        if (!config('config.email_verification')) {
            throw ValidationException::withMessages(['message' => 'Email verification not available.']);
        }
    }

    /**
     * Check for account approval availability.
     *
     * @return null
     * @throws ValidationException
     */
    public function validateAccountApprovalStatus()
    {
        if (!config('config.account_approval')) {
            throw ValidationException::withMessages(['message' => 'Account approval not available.']);
        }
    }

    /**
     * Check for reset password availability.
     *
     * @return null
     * @throws ValidationException
     */
    public function validateResetPasswordStatus()
    {
        if (!config('config.reset_password')) {
            throw ValidationException::withMessages(['message' => 'Reset password not available.']);
        }
    }
}
