<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    protected $request;
    protected $repo;
    protected $user;
    protected $module = 'user';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, AuthRepository $repo, UserRepository $user)
    {
        $this->request = $request;
        $this->repo = $repo;
        $this->user = $user;
    }

    /**
     * Used to authenticate user
     * @post ("/api/auth/login")
     * @param ({
     * @Parameter("email", type="email", required="true", description="Email of User"),
     * @Parameter("password", type="password", required="true", description="Password of User"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $auth = $this->repo->auth($this->request->all());

        $auth_user  = $auth['user'];
        $token      = $auth['token'];

        return $this->success([
            'message' => 'Logged in.',
            'token'   => $token,
            'user'    => $auth_user
        ]);
    }

    /**
     * Used to check user authenticated or not
     * @post ("/api/auth/check")
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function check()
    {
        return $this->success($this->repo->check());
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function me()
    {
        return $this->success(auth()->user());
    }

    /**
     * Refresh a token.
     *
     * @return Response
     */
    public function refresh()
    {
        return $this->success(auth()->refresh());
    }

    /**
     * Used to logout user
     * @post ("/api/auth/logout")
     * @return Response
     */
    public function logout()
    {
        $auth_user = auth()->user();

        auth()->logout();

        return $this->success(['message' => 'Logged out.']);
    }

    /**
     * Used to create user
     * @post ("/api/auth/register")
     * @param ({
     * @Parameter("first_name", type="text", required="true", description="First Name of User"),
     * @Parameter("last_name", type="text", required="true", description="Last Name of User"),
     * @Parameter("email", type="email", required="true", description="Email of User"),
     * @Parameter("password", type="password", required="true", description="Password of User"),
     * @Parameter("password_confirmation", type="password", required="true", description="Confirm Password of User"),
     * @Parameter("image_url", type="text", required="optional", description="Profile Image URL"),
     * @Parameter("phone", type="text", required="optional", description="Phone Number"),
     * @Parameter("tnc", type="checkbox", required="optional", description="Accept Terms & Conditions"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request)
    {
        $this->repo->validateRegistrationStatus();

        $new_user = $this->user->create($this->request->all(), 1);

        return $this->success(['message' => 'Account created.']);
    }

    /**
     * Used to activate new user
     * @get ("/api/auth/activate/{token}")
     * @param ({
     * @Parameter("token", type="string", required="true", description="Activation Token of User"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function activate($activation_token)
    {
        $this->repo->activate($activation_token);

        return $this->success(['message' => 'Account activated.']);
    }

    /**
     * Used to request password reset token for user
     * @post ("/api/auth/forgot-password")
     * @param ({
     *      @Parameter("email", type="email", required="true", description="Registered Email of User"),
     * })
     * @return Response
     */
    public function password(PasswordRequest $request)
    {
        $this->repo->password($this->request->all());

        return $this->success(['message' => 'Password reset link sent. The reset link is valid for ' . config('config.reset_password_token_lifetime') . ' minutes']);
    }

    /**
     * Used to validate user password
     * @post ("/api/auth/validate-password-reset")
     * @param ({
     * @Parameter("token", type="string", required="true", description="Reset Password Token"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validatePasswordReset()
    {
        $this->repo->validateResetPasswordToken(request('token'));

        return $this->success(['message' => 'Password reset token is valid.']);
    }

    /**
     * Used to reset user password
     * @post ("/api/auth/reset-password")
     * @param ({
     * @Parameter("token", type="string", required="true", description="Reset Password Token"),
     * @Parameter("email", type="email", required="true", description="Email of User"),
     * @Parameter("password", type="password", required="true", description="New Password of User"),
     * @Parameter("password_confirmation", type="password", required="true", description="New Confirm Password of User"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reset(ResetPasswordRequest $request)
    {
        $this->repo->reset($this->request->all());

        return $this->success(['message' => 'Your password has been reset.']);
    }

    /**
     * Used to change user password
     * @post ("/api/change-password")
     * @param ({
     * @Parameter("current_password", type="password", required="true", description="Current Password of User"),
     * @Parameter("new_password", type="password", required="true", description="New Password of User"),
     * @Parameter("new_password_confirmation", type="password", required="true", description="New Confirm Password of User"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $this->repo->validateCurrentPassword(request('current_password'));

        $this->repo->resetPassword(request('new_password'));

        return $this->success(['message' => 'Your password has been changed successfully.']);
    }

    /**
     * Used to verify password during Screen Lock
     * @post ("/api/auth/lock")
     * @param ({
     * @Parameter("password", type="password", required="true", description="Password of User"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function lock(LoginRequest $request)
    {
        $this->repo->validateCurrentPassword(request('password'));

        return $this->success(['message' => 'You are redirected to home page.']);
    }
}
