<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Driver\Auth\DriverCarRequest;
use App\Http\Requests\Driver\Auth\DriverCompleteProfileRequest;
use App\Http\Requests\Driver\Auth\DriverRequest;
use App\Http\Requests\Driver\Auth\DriverSetNewPasswordRequest;
use App\Http\Requests\Driver\Auth\DriverUpdateProfileRequest;
use App\Http\Requests\Driver\Auth\MakePasswordRequest;
use App\Http\Resources\DriverCarResource;
use App\Http\Resources\DriverResource;
use Illuminate\Support\Carbon;
use App\Interfaces\DriverRepositoryInterface;
use App\Models\Driver;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Notifications\VerifyBusinessEmail;
use Illuminate\Support\Facades\Notification;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DriverController extends BaseController
{
    use HttpResponses;

    protected mixed $crudRepository;

    public function __construct(DriverRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }


    //////////////////////////////////////////////////////////   Dashboard   ///////////////////////////////////////////////////////




    public function index()
    {
        try {
            $driver = DriverResource::collection($this->crudRepository->all(
                [],
                [],
                ['*']
            ));
            return $driver->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function store(DriverRequest $request)
    {
        try {
            $driver = $this->crudRepository->create($request->validated());
            return new DriverResource($driver);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Driver $driver): ?\Illuminate\Http\JsonResponse
    {
        try {
            return JsonResponse::respondSuccess('Item Fetched Successfully', new DriverResource($driver));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function update(DriverRequest $request, Driver $driver)
    {
        try {
            $data = $request->validated();
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $this->crudRepository->update($data, $driver->id);
            activity()->performedOn($driver)->withProperties(['attributes' => $driver])->log('update');
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function destroy(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->deleteRecords('drivers', $request['ids']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->crudRepository->restoreItem(Driver::class, $request['ids']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_RESTORED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }




    public function forceDelete(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $this->crudRepository->deleteRecordsFinial(Driver::class, $request['ids']);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_FORCE_DELETED_SUCCESSFULLY));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //////////////////////////////////////////////////////////   mobile   //////////////////////////////////////////////////////////



    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
                'remember' => 'boolean',
            ]);

            $driver = Driver::where('email', $request->email)->first();
            if (!$driver || !Hash::check($request->password, $driver->password)) {
                activity('driver-login')
                    ->withProperties(['email' => $request->email])
                    ->log('Failed login attempt for driver.');

                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
            if (is_null($driver->first_login_at)) {
                $driver->first_login_at = now();
                $driver->save();
            }
            $token = $driver->createToken('driver-api-token', ['driver'])->plainTextToken;
            activity('driver-login')->causedBy($driver)->log('Driver logged in successfully.');
            return response()->json([
                "result" => "Success",
                'data' => new DriverResource($driver),
                'message' => 'User Logged In Successfully',
                'status' => true,
                'token' => $token
            ]);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }



    public function register(DriverRequest $request)
    {
        try {
            $driver = Driver::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);
            return $this->success(new DriverResource($driver), 'driver registered successfully');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function verifyEmail(Request $request)
    {
        try {
            $driver = Driver::where('email', $request->email)->first();
            if ($driver->email_verified_at == null) {
                $verificationCode = mt_rand(100000, 999999);
                $expiryTime = Carbon::now()->addHours(2);
                $driver->update([
                    'code_verify' => $verificationCode,
                    'expiry_time_code_verify' => $expiryTime,
                ]);
                Notification::send($driver, new VerifyBusinessEmail($verificationCode));
                return $this->success(null, 'OTP sent to email. This code is valid for 2 hours.');
            } else {
                return $this->error('Your email is already verified. If you forgot your password, please use the reset password option or contact support.', 403);
            }
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function checkVerificationCode(Request $request)
    {
        try {
            $driver = Driver::where('email', $request->email)->first();
            if ($driver->code_verify != $request->code) {
                return $this->error('Invalid verification code', 400);
            }
            if (Carbon::now()->diffInHours($driver->expiry_time_code_verify) >= 2) {
                return $this->error('Verification code expired', 400);
            }
            $driver->update([
                'code_verify' => null,
                'expiry_time_code_verify' => null,
                'email_verified_at' => Carbon::now()->addHours(2),
            ]);
            $token = $driver->createToken('authToken')->plainTextToken;
            return $this->success(null, 'Email verified successfully');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function setPassword(MakePasswordRequest $request)
    {
        try {
            $driver = $this->crudRepository->setPassword($request->validated());
            if ($driver) {
                $token = $driver->createToken('driver-api-token', ['user'])->plainTextToken;
                return $this->success([
                    'driver' => new DriverResource($driver),
                    'token' => $token,
                ], 'Password set successfully.');
            }
            return $this->error("Password can't set", 422);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }






public function completeProfile(DriverCompleteProfileRequest $request)
{
    try {
        $data = $request->validated();
        if (isset($data['avatar'])) {
            $data['avatar'] = storeFile($data['avatar'], 'avatar');
        }
        $driver = $this->crudRepository->completeProfile($data);
        return $this->success(new DriverResource($driver), 'completed car DETAILS successfully.');
    } catch (Exception $e) {
        return JsonResponse::respondError($e->getMessage());
    }
}






    public function checkAuth(Request $request)
    {
        if (Auth::check()) {
            return $this->success(new DriverResource(Auth::guard('driver')->user()), 'Driver logged in successfully');
        } else {
            return $this->error(null, 'Driver is not authenticated');
        }
    }

    public function logout()
    {
        try {
            Auth::guard('driver')->user()->tokens()->delete();
            return JsonResponse::respondSuccess('Successfully logged out');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function forgotPassword(Request $request)
    {
        try {
            $driver = Driver::where('email', $request->email)->first();
            $verificationCode = mt_rand(100000, 999999);
            $expiryTime = Carbon::now()->addHours(2);
            $driver->update([
                'code_verify' => $verificationCode,
                'expiry_time_code_verify' => $expiryTime,
            ]);
            Notification::send($driver, new VerifyBusinessEmail($verificationCode));
            return $this->success(null, 'OTP sent to email. This code is valid for 2 hours.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function verifyEmailOrPhoneOtp(Request $request)
    {
        try {
            $driver = Driver::where('email', $request->email)->first();
            if ($driver->code_verify != $request->code) {
                return $this->error('Invalid verification code', 400);
            }
            if (Carbon::now()->diffInHours($driver->expiry_time_code_verify) >= 2) {
                return $this->error('Verification code expired', 400);
            }
            $driver->update([
                'code_verify' => null,
                'expiry_time_code_verify' => null,
                'email_verified_at' => Carbon::now()->addHours(2),
            ]);
            $token = $driver->createToken('authToken')->plainTextToken;
            return $this->success(null, 'Email verified successfully');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function setNewPassword(DriverSetNewPasswordRequest $request)
    {
        try {
            $driver = Driver::where('email', $request->email)->first();
            if (!$driver) {
                return $this->error('Driver not found', 400);
            }
            $driver->password = Hash::make($request->password);
            $driver->save();
            return $this->success(null, 'Password updated successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function uploadLicenseFront(Request $request)
    {
        try {
            $request->validate([
                'license_front' => ['required', 'image', 'max:2048'],
            ]);

            $driver = auth('driver')->user();
            $driver->license_front = storeFile($request->file('license_front'), 'license_fronts');
            $driver->save();
            return $this->success(null, 'License front uploaded successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function uploadLicenseBack(Request $request)
    {
        try {
            $request->validate([
                'license_back' => ['required', 'image', 'max:2048'],
            ]);

            $driver = auth('driver')->user();
            $driver->license_back = storeFile($request->file('license_back'), 'license_back');
            $driver->save();
            return $this->success(null, 'License back uploaded successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function uploadCriminalRecord(Request $request)
    {
        try {
            $request->validate([
                'criminal_record' => ['required', 'image', 'max:2048'],
            ]);

            $driver = auth('driver')->user();
            $driver->criminal_record = storeFile($request->file('criminal_record'), 'criminal_record');
            $driver->save();
            return $this->success(null, 'Criminal record uploaded successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function uploadCarDetails(DriverCarRequest $request)
    {
        try {
            $data = $request->validated();
            if (isset($data['photo_car'])) {
                $data['photo_car'] = storeFile($data['photo_car'], 'photo_car');
            }
            $driver = $this->crudRepository->catDetails($data);
            return $this->success(new DriverCarResource($driver), 'completed car DETAILS successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function updateProfile(DriverUpdateProfileRequest $request)
    {
        try {
            $driver = $this->crudRepository->completeProfile($request->validated());
            if (isset($data['avatar'])) {
                $data['avatar'] = storeFile($data['avatar'], 'avatars'); // نحذفه
            }
            return $this->success(new DriverResource($driver), 'Profile completed successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


}
