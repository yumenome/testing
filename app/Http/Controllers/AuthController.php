<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SinginRequest;
use App\Http\Requests\SingupRequest;
use App\Http\Requests\SMSRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class AuthController extends Controller
{

    public function sendOTP($phone, $otp_code)
    {

        $user = User::where('phone', $phone)->first();

        $client = new Client();

        $token = 'ZugHBnKarsVKdlii2GBW0FcWBedUlxLmiW2c8Kdsvmr1bLF2G9AdvaThtsdRKGoV';

        $base_url = 'https://smspoh.com/api/v2/send';

        $headers = [
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $token,
        ];

        $data = [
            'to' => $phone,
            'message' => $otp_code .  "is your OTP!",
            'sender' => "abacus_mm"
        ];

        $postResponse = $client->post($base_url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        if ($postResponse->getStatusCode() == 200) {
            // $response = $postResponse->getBody();
            return $postResponse->getStatusCode();
        }
    }

    public function signup(SingupRequest $request)
    {

        $user = new User;
        $img_url = time() . '_' . $request->img->getClientOriginalName();
        $request->img->move(public_path('storage/avatars'), $img_url);
        $otp_code = rand(111111, 999999);

        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->otp_code = $otp_code;
        $user->img = $img_url;
        $user->password = $request->password;
        $user->otp_expired = Carbon::now()->addMinutes(5);

        $user->save();

        $this->sendOTP($request->phone, $otp_code);

        return response()->json(['user' => $user, 'message' => 'successfully created! verfiy with OTP to ativate your account']);
    }

    public function sms_verification(SMSRequest $request)
    {

        if ($request->otp_code == null) {
            return response()->json(['??' => "nice try!"]);
        }

        $user = User::where('otp_code', $request->otp_code)->first();

        $user->update([
            'status' => 1,
        ]);
        $token = $user->createToken($user->password . 'AUTH TOKEN')->plainTextToken;

        $now = Carbon::now();

        if ($user && $now->isBefore($user->otp_expired)) {
            $user->update([
                'otp_code' => null,
            ]);
            return response()->json(['token' => $token, 'user' => $user, 'message' => 'successfully activated your account!'], 200);
        }
        return response()->json(['error' => 'your OTP expired!'], 422);
    }

    public function resendOTP($id)
    {

        $user = User::find($id);
        $otp_code = rand(111111, 999999);
        $user->update([
            'otp_code' => $otp_code,
            'otp_expired' => Carbon::now()->addMinutes(5),
        ]);

        $this->sendOTP($user->phone, $user->otp_code);

        return response()->json([$user->otp_code . ' is your new OTP, be careful this time!']);
    }

    public function signin(SinginRequest $request)
    {

        try {
            if (
                Auth::attempt(['password' => $request->password, 'phone' => $request->resource, 'status' => 1]) ||
                Auth::attempt(['password' => $request->password, 'username' => $request->resource, 'status' => 1])
            ) {

                $user = Auth::user();

                /** @var \App\Models\User $user */
                $token = $user->createToken($request->password . 'ATUH_TOKEN')->plainTextToken;

                return response()->json(['token' => $token, 'user' => $user], 200);
            } else {
                return response()->json(['message' => 'The given data was invalid.'], 401);
            }
        } catch (Exception $e) {
            return ApiHelper::responseWithBadRequest($e->getMessage());
        }
    }

    public function forgot_password(ForgotPasswordRequest $request)
    {

        $user = User::where('phone', $request->phone)->first();

        $otp_code = rand(111111, 999999);
        $user->update([
            'otp_code' => $otp_code,
            'otp_expired' => Carbon::now()->addMinutes(5),
        ]);

        $this->sendOTP($request->phone, $otp_code);

        return response()->json(['use this OTP {' . $user->otp_code . '} to change your password']);
    }

    public function reset_password(ResetPasswordRequest $request)
    {

        if ($request->otp_code == null) {
            return response()->json(['??' => "nice try!"]);
        }

        $user = User::where('otp_code', $request->otp_code)->first();

        $now = Carbon::now();

        if ($user && $now->isBefore($user->otp_expired)) {
            $user->update([
                'otp_code' => null,
                'password' => $request->new_password,
            ]);
            return response()->json(['message' => 'successfully changed your password'], 200);
        }

        return response()->json(['error' => 'your OTP expired!'], 422);
    }
}
