<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SinginRequest;
use App\Http\Requests\SMSRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function signin(SinginRequest $request){

        try{
            if(Auth::attempt(['password' => $request->password, 'phone' => $request->resource]) ||
               Auth::attempt(['password' => $request->password, 'username' => $request->resource])){

                $user = Auth::user();

                /** @var \App\Models\User $user */
                $token = $user->createToken($request->password . 'ATUH_TOKEN')->plainTextToken;

                return response()->json(['token' => $token, 'user' => $user],200);
            }
            else{
                return response()->json(['message' => 'invalid data!'],401);
            }

        }catch(Exception $e){

        }
    }

    public function sendOTP($phone,$otp_code){
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
            'message' => $otp_code .  " is your OTP to change your current password, don't share with others!",
            'sender' => "abacus_mm"
        ];

        $postResponse = $client->post($base_url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        if($postResponse->getStatusCode() == 200) {
            $response = $postResponse->getBody();
            return $response;
        }
    }

    public function sms_verification(SMSRequest $request){

         if($request->otp_code == null) {
            return response()->json(['??' => "nice try!"]);
        }

        $user = User::where('otp_code', $request->otp_code)->first();

        $now = Carbon::now();

        if($user && $now->isBefore($user->otp_expired)){
            $user->update([
                'otp_code' => null,
            ]);
            return response()->json(['message' => 'successfully sign-up!'], 200);
        }

        return response()->json(['error' => 'your OTP expired!'], 422);
    }

    public function forgot_password(ForgotPasswordRequest $request){

        $user = User::where('phone', $request->phone)->first();

        $otp_code = rand(111111, 999999);
        $user->update([
            'otp_code' => $otp_code,
            'otp_expired' => Carbon::now()->addMinutes(5),
        ]);

        $result = $this->sendOTP($request->phone, $otp_code);

        return $result;


    }

    public function reset_password(ResetPasswordRequest $request){

        if($request->otp_code == null) {
            return response()->json(['??' => "nice try!"]);
        }

        $user = User::where('otp_code', $request->otp_code)->first();

        $now = Carbon::now();

        if($user && $now->isBefore($user->otp_expired)){
            $user->update([
                'otp_code' => null,
                'password' => $request->new_password,
            ]);
            return response()->json(['message' => 'successfully changed your password'], 200);
        }

        return response()->json(['error' => 'your OTP expired!'], 422);
    }
}
