<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\UserRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\UserResource;
use App\Interfaces\CountryRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Mail\ResetPasswordUserMail;
use App\Models\Country;
use App\Models\User;
use App\Models\WalletTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends  BaseController
{
    protected mixed $crudRepository;

    public function __construct(UserRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

}
