<?php

namespace App\Interfaces;

use App\Models\User;
use App\Repositories\ICrudRepository;

interface UserRepositoryInterface extends ICrudRepository
{
    public function createUser(array $data): User;

    public function login(string $emailOrPhone, string $password): ?User;

    public function generateEmailOtp(string $email): string;

    public function verifyEmailOtp(string $email, string $otp): bool;

    public function generatePhoneOtp(string $phone): string;

    public function verifyPhoneOtp(string $phone, string $otp): bool;

    public function setPassword(array $data): ?User;
}