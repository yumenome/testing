<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function signin(Request $request){

        try{

            $validator = validator($request->all(),[
                'name_phone' => 'required',
                'password' => 'required',
            ]);

            if($validator->fails()) {
                return response()->json(['messgage' => 'need to fill all required fields'],404);
            }

            if(Auth::attempt(['password' => $request->password, 'phone' => $request->name_phone]) ||
               Auth::attempt(['password' => $request->password, 'username' => $request->name_phone])){

                $user = Auth::user();

                /** @var \App\Models\User $user */
                $token = $user->createToken($request->password . 'ATUH_TOKEN')->plainTextToken;

                return response()->json(['token' => $token, 'user' => $user]);
            }
            else{
                return response()->json(['message' => 'something went wrong, i can feel it!'],401);
            }

        }catch(Exception $e){

        }
    }

    public function forgot_password(Request $request){

        $user = User::where('phone', $request->phone)->first();

        if(!$user){
            return response()->json(['message' => 'Could not process a user with that phone number.'], 401);
        };

        $client = new Client();

        $base_url = 'https://smspoh.com/api/v2/send';

        $token = 'ZugHBnKarsVKdlii2GBW0FcWBedUlxLmiW2c8Kdsvmr1bLF2G9AdvaThtsdRKGoV';

        $headers = [
            'Content-Type' => 'application/json',
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $token,
        ];

        $forgot_code = rand(111111, 999999);
        $user->forgot_code = $forgot_code;
        $user->save();

        $data = [
            'to' => $request->phone,
            'message' => $forgot_code .  "is your verification code for fatty application login.",
            'sender' => "abacus_mm"
        ];

        $postResponse = $client->post($base_url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        $response = $postResponse->getBody();
        return $response;
    }

    public function test(){
        sleep(5);
        echo('testing');
    }
}
