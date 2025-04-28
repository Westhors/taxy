<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\DriverRequest;
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

class DriverController extends BaseController
{
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
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                "result" => "Success",
                'data' => new DriverResource($driver),
                'message' => 'Driver registered successfully',
                'status' => true,
            ]);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function verifyEmail(Request $request)
    {
        try {
            $driver = Driver::where('email', $request->email)->first();

            if (!$driver) {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'Driver not found',
                    'status' => 404,
                ], 404);
            }
            if ($driver->email_verified_at == null) {
                $verificationCode = mt_rand(100000, 999999);
                $expiryTime = Carbon::now()->addHours(2);

                $driver->update([
                    'code_verify' => $verificationCode,
                    'expiry_time_code_verify' => $expiryTime,
                ]);

                Notification::send($driver, new VerifyBusinessEmail($verificationCode));

                return response()->json([
                    "result" => "Success",
                    'data' => ["id" => $driver->id],
                    'message' => 'Verification code sent to email. This code is valid for 2 hours.',
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'Your email is already verified. If you forgot your password, please use the reset password option or contact support.',
                    'status' => 403,
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'result' => "Error",
                'data' => null,
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function checkVerificationCode(Request $request)
    {
        try {
            $driver = Driver::where('id', $request->id)->first();
            if (!$driver) {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'Driver not found',
                    'status' => 404,
                ], 404);
            }
            if ($driver->code_verify != $request->code) {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'Invalid verification code',
                    'status' => 400,
                ], 400);
            }

            if (Carbon::now()->diffInHours($driver->expiry_time_code_verify) >= 2) {
                return response()->json([
                    'result' => "Error",
                    'data' => null,
                    'message' => 'Verification code expired',
                    'status' => 400,
                ], 400);
            }

            $driver->update([
                'code_verify' => null,
                'expiry_time_code_verify' => null,
                'email_verified_at' => Carbon::now()->addHours(2),
            ]);
            $token = $driver->createToken('authToken')->plainTextToken;
            return response()->json([
                'result' => "Success",
                'data' => new DriverResource($driver),
                'message' => 'Email verified successfully',
                'status' => 200,
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json([
                'result' => "Error",
                'data' => null,
                'message' => $e->getMessage(),
                'status' => 500,
            ], 500);
        }
    }
}
