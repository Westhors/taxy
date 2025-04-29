<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Users\Auth\CompleteProfileRequest;
use App\Http\Requests\Users\Auth\LoginRequest;
use App\Http\Requests\Users\Auth\RegisterRequest;
use App\Http\Requests\Users\Auth\SendEmailOTPRequest;
use App\Http\Requests\Users\Auth\SendPhoneOTPRequest;
use App\Http\Requests\Users\Auth\SetPasswordRequest;
use App\Http\Requests\Users\Auth\VerifyEmailOTPRequest;
use App\Http\Requests\Users\Auth\VerifyPhoneOTPRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends  BaseController
{
    use HttpResponses;
    protected mixed $userRepository;

    public function __construct(UserRepositoryInterface $pattern)
    {
        $this->userRepository = $pattern;
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = $this->userRepository->createUser($validated);

            return $this->success(new UserResource($user), 'User registered successfully');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->userRepository->login($request->email_or_phone, $request->password);

            if ($user) {
                $token = $user->createToken('user-api-token', ['user'])->plainTextToken;

                return $this->success([
                    'user' => new UserResource($user),
                    'token' => $token,
                ], 'Login successful');
            }

            // Return error response if login fails
            return $this->error('Invalid credentials', 401);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    // Send OTP to the user's email
    public function sendEmailOtp(SendEmailOTPRequest $request)
    {
        try {
            $otp = $this->userRepository->generateEmailOtp($request->email);

            return $this->success(null, 'OTP sent to email');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function verifyEmailOtp(VerifyEmailOTPRequest $request)
    {
        try {
            $isValidOtp = $this->userRepository->verifyEmailOtp($request->email, $request->otp);

            if ($isValidOtp) {
                return $this->success(null, 'Email verified successfully');
            }

            return $this->error('Invalid or expired OTP', 400);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    // Send OTP to the user's phone
    public function sendPhoneOtp(SendPhoneOTPRequest $request)
    {
        try {
            $otp = $this->userRepository->generatePhoneOtp($request->phone);

            return $this->success(null, 'OTP sent to phone');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function verifyPhoneOtp(VerifyPhoneOTPRequest $request)
    {
        try {
            $isValidOtp = $this->userRepository->verifyPhoneOtp($request->phone, $request->otp);

            if ($isValidOtp) {
                return $this->success(null, 'Phone verified successfully');
            }

            return $this->error('Invalid or expired OTP', 400);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function setPassword(SetPasswordRequest $request)
    {
        try {
            $user = $this->userRepository->setPassword($request->validated());

            if (!$user) {
                return $this->error("Error Occured", 422);
            }

            return $this->success(new UserResource($user), 'Password set successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function completeProfile(CompleteProfileRequest $request)
    {
        try {
            $user = $this->userRepository->completeProfile($request->validated());

            return $this->success(new UserResource($user), 'Profile completed successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function checkAuth(Request $request)
    {
        if (Auth::check()) {
            return $this->success(new UserResource(Auth::guard('user')->user()), 'User logged in successfully');
        } else {
            return $this->error(null, 'User is not authenticated');
        }
    }

    public function logout()
    {
        try {
            $success = $this->userRepository->logout();
            if ($success) {
                return $this->success(null, 'logout successfully.');
            } else {
                return $this->error(null, 'User is not authenticated');
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}