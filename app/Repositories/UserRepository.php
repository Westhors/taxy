<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\VerifyOtpMail;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository extends CrudRepository implements UserRepositoryInterface
{
    protected Model $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    // Create new user
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'gender' => $data['gender'],
        ]);
    }

    public function login(string $emailOrPhone, string $password): ?User
    {
        $user = User::where(function ($query) use ($emailOrPhone) {
            $query->where('email', $emailOrPhone)
                ->orWhere('phone', $emailOrPhone);
        })->first();

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public function updateFCMToken(User $user, ?string $fcm_token): bool
    {
        if ($fcm_token) {
            $user->fcm_token = $fcm_token;
            $user->save();
            return true;
        }
        return false;
    }


    // Generate OTP and send to email
    public function generateEmailOtp(string $email): string
    {
        $otp = random_int(10000, 99999);

        $userOtp = UserOtp::updateOrCreate(
            ['email' => $email],
            ['otp' => $otp]
        );

        $userOtp->created_at = now();
        $userOtp->updated_at = now();
        $userOtp->save();

        Mail::to($email)->queue(new VerifyOtpMail($otp));

        return $otp;
    }

    // Verify the OTP for the given email
    public function verifyEmailOtp(string $email, string $otp): bool
    {
        $otpRecord = UserOtp::where('email', $email)
            ->where('otp', $otp)
            ->first();
        $isVerify = $otpRecord && $otpRecord->created_at->gt(Carbon::now()->subMinutes(10));

        if ($isVerify) {
            $user = User::where('email', $email)->first();
            $user->email_verified_at = now();
            $user->save();

            $otpRecord->delete();
        }

        return $isVerify;
    }

    // Generate OTP and send to phone
    public function generatePhoneOtp(string $phone): string
    {
        // $otp = random_int(10000, 99999);
        $otp = "12345";

        $userOtp = UserOtp::updateOrCreate(
            ['phone' => $phone],
            ['otp' => $otp]
        );

        $userOtp->created_at = now();
        $userOtp->updated_at = now();
        $userOtp->save();

        //TODO: Send OTP to phone job

        return $otp;
    }

    // Verify the OTP for the given phone
    public function verifyPhoneOtp(string $phone, string $otp): bool
    {
        $otpRecord = UserOtp::where('phone', $phone)
            ->where('otp', $otp)
            ->first();
        $isVerify = $otpRecord && $otpRecord->created_at->gt(Carbon::now()->subMinutes(10));

        if ($isVerify) {
            $user = User::where('phone', $phone)->first();
            $user->phone_verified_at = now();
            $user->save();

            $otpRecord->delete();
        }

        return $isVerify;
    }

    public function setPassword(array $data): ?User
    {
        $email = $data['email'];
        $newPassword = $data['password'];

        $user = User::where('email', $email)->first();

        if (!$user || $user->password) {
            return null;
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return $user;
    }

    public function completeProfile(array $data): User
    {
        /** @var \App\Models\User $user **/
        $user = Auth::guard('user')->user();

        if (isset($data['avatar'])) {
            $data['avatar'] = storeFile($data['avatar'], 'avatars');
        }

        $user->update($data);

        return $user;
    }

    public function logout(): bool
    {
        /** @var \App\Models\User $user **/
        $user = Auth::guard('user')->user();

        if ($user) {
            // $user->currentAccessToken()->delete();
            $user->tokens()->delete();


            return true;
        }

        return false;
    }

    public function findByEmailOrPhone(string $emailOrPhone): ?User
    {
        return User::where('email', $emailOrPhone)
            ->orWhere('phone', $emailOrPhone)
            ->first();
    }

    public function sendOtpEmailOrPhone(string $emailOrPhone): string
    {
        if (filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL)) {
            // Send by Email
            return $this->generateEmailOtp($emailOrPhone);
        } else {
            // Send by Phone
            return  $this->generatePhoneOtp($emailOrPhone);
        }
    }

    public function verifyEmailOrPhoneOtp(string $emailOrPhone, string $otp): bool
    {
        $field = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        return UserOtp::where($field, $emailOrPhone)
            ->where('otp', $otp)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();
    }

    public function setNewPassword(User $user, string $emailOrPhone, string $newPassword): bool
    {
        $user->forceFill([
            'password' => Hash::make($newPassword),
        ])->save();

        $field = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        UserOtp::where($field, $emailOrPhone)->delete();

        return true;
    }

    public function updateProfile(User $user, array $data): User
    {
        $data = array_filter($data, function ($value) {
            return !is_null($value) && $value !== '';
        });

        if (isset($data['avatar'])) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = storeFile($data['avatar'], 'avatars');
        }

        $user->update($data);
        return $user;
    }


    public function changePassword(User $user, string $newPassword): bool
    {
        $user->password = Hash::make($newPassword);
        $user->save();
        return true;
    }
}
