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
use Illuminate\Support\Facades\Hash;

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
        $user = User::where('email', $emailOrPhone)
            ->orWhere('phone', $emailOrPhone)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }
        return null;
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

        // Send OTP to phone job

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

        if (!$user) {
            return null;
        }

        if ($user->password) {
            return null;
        }
        $user->password = Hash::make($newPassword);
        $user->save();

        return $user;
    }
}