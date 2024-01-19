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

    public function big_friend(Request $request){

        $validator = validator($request->all(), [
            'line' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return ($validator->errors());
        }

        $first_num = rand(1, 99);
        // echo('top first_num: ' . $first_num);
        // $first_num = 5;
        $questions = [$first_num];

        for($i = 0; $i < ($request->line -1); $i++){

            if(($first_num >= 40 && $first_num < 50) || ($first_num > 90 && $first_num <= 99)){
                $question = $this->getMinus($first_num);
                array_push($questions, $question);
            }
            elseif($first_num % 10 == 9){
                $question = $this->getPlus($first_num);
                array_push($questions, $question);
            }
            else{
                if(rand(1,0) == 1){
                    $question = $this->getPlus($first_num);
                    array_push($questions, $question);
                }
                elseif(rand(1,0) == 0 && $first_num > 10){
                    $question = $this->getMinus($first_num);
                    array_push($questions, $question);
                }
                else{
                    $question = $this->getPlus($first_num);
                    array_push($questions, $question);
                }
            }

            $first_num = array_reduce($questions, function ($a, $b) {return $a + $b;});
        }
        $answer = array_sum($questions);
        return response(['questions' => $questions,'answer' => $answer], 200);
    }

    public function randomNum($input_array){
        $random_value = array_rand($input_array);
        $rand_num = $input_array[$random_value];
        return $rand_num;
    }

    public function getPlus($num){
        $byTen = $num % 10;
        // $byTen = 1;

        switch ($byTen) {
            case 1:
                $result = 9;
                break;
            case 2:
                $result = $this->randomNum([9, 8]);
                break;
            case 3:
                $result = $this->randomNum([9, 8, 7]);
                break;
            case 4:
                $result = $this->randomNum([9, 8, 7, 6]);
                break;
            case 5:
                $result = 5;
                break;
            case 6:
                $result = $this->randomNum([9, 5, 4]);
                break;
            case 7:
                $result = $this->randomNum([9, 8, 5, 4, 3]);
                break;
            case 8:
                $result = $this->randomNum([9, 8, 7, 5, 4, 3, 2]);
                break;
            case 9:
                $result = $this->randomNum([9, 8, 7, 6, 5, 4, 3, 2, 1]);
                break;
            case 0:
                $result = $this->randomNum([9, 8, 7, 6, 5, 4, 3, 2, 1]);
                break;

        }
        return $result;
    }

    public function getMinus($num){
        $byTen = $num % 10;
        // $byTen = 9;

        switch ($byTen) {
            case 1:
                $result = $this->randomNum([-9, -8, -7, -5, -4, -3, -2]);
                break;
            case 2:
                $result = $this->randomNum([-9, -8, -5, -4, -3]);
                break;
            case 3:
                $result = $this->randomNum([-9, -5, -4]);
                break;
            case 4:
                $result = -5;
                break;
            case 5:
                $result = $this->randomNum([-9, -8, -7, -6]);
                break;
            case 6:
                $result = $this->randomNum([-9, -8, -7]);
                break;
            case 7:
                $result = $this->randomNum([-9, -8]);
                break;
            case 8:
                $result = -9;
                break;
            case 9:
                $result = rand(-1, -9);
                break;
            case 0:
                if ($num >= 10) {
                    $result = -rand(1, 9);
                } else {
                    $result = rand(1, 9);
                }
                break;
        }
        return $result;
    }



}
