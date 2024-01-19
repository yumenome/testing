<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestingController extends Controller
{
    public function direct_method(Request $request)
    {
        try{
            $validator = validator($request->all(), [
                'line' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return ($validator->errors());
            }

            $first_num = rand(0, 9);
            $possible_result = [];
            $questions = [$first_num];


            for($i = 0; $i < ($request->line -1); $i++){
                switch ($first_num) {
                    case 0:
                        $possible_result = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                        break;
                    case 1:
                        $possible_result = [1, 2, 3, 5, 6, 7, 8, -1];
                        break;
                    case 2:
                        $possible_result = [1, 2, 5, 6, 7, -1, -2];
                        break;
                    case 3:
                        $possible_result = [1, 5, 6, -1, -2, -3];
                        break;
                    case 4:
                        $possible_result = [5, -1, -2, -3, -4];
                        break;
                    case 5:
                        $possible_result = [1, 2, 3, 4, -5];
                        break;
                    case 6:
                        $possible_result = [1, 2, 3, -1, -5, -6];
                        break;
                    case 7:
                        $possible_result = [1, 2, -1, -2, -5, -6, -7];
                        break;
                    case 8:
                        $possible_result = [1, -1, -2, -3, -5, -6, -7, -8];
                        break;
                    case 9:
                        $possible_result = [-1, -2, -3, -4, -5, -6, -7, -8, -9];
                        break;
                };
                $random_value = array_rand($possible_result);
                $question = $possible_result[$random_value];
                array_push($questions, $question);

                $first_num = array_reduce($questions, function ($a, $b) {return $a + $b;});
            }
            $answer = array_sum($questions);
            return response(['questions' => $questions,'answer' => $answer], 200);
        }catch (\Exception $e){
            Log::channel('just_error')->error('Driect Method Error: ' . $e->getMessage());
            return response('Internal Server Error', 500);
        }
    }

    public function little_friend(Request $request)
    {
        try{
            $validator = validator($request->all(), [
                'line' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return ($validator->errors());
            }

            $first_num = rand(0, 9);
            $possible_result = [];
            $questions = [$first_num];

            for($i = 0; $i < ($request->line -1); $i++){
                switch ($first_num) {
                    case 0:
                        $possible_result = [1, 2, 3, 4, 5, 6, 7, 8, 9];
                        break;
                    case 1:
                        $possible_result = [4];
                        break;
                    case 2:
                        $possible_result = [4,3];
                        break;
                    case 3:
                        $possible_result = [4,3,2];
                        break;
                    case 4:
                        $possible_result = [4, 3, 2, 1];
                        break;
                    case 5:
                        $possible_result = [-4, -3, -2, -1];
                        break;
                    case 6:
                        $possible_result = [-4, -3, -2];
                        break;
                    case 7:
                        $possible_result = [-4, -3];
                        break;
                    case 8:
                        $possible_result = [-4];
                        break;
                    case 9:
                        $possible_result = [-1, -2, -3, -4, -5, -6, -7, -8, -9];
                        break;
                };
                $random_value = array_rand($possible_result);
                $question = $possible_result[$random_value];
                array_push($questions, $question);
                $first_num = array_reduce($questions, function ($a, $b) {return $a + $b;});
            }
            $answer = array_sum($questions);
            return response(['questions' => $questions,'answer' => $answer], 200);
        }catch (\Exception $e) {
            Log::channel('just_error')->error('Little Friend Error: ' . $e->getMessage());
            return response($e->getMessage(), 500);
        }
    }



}
