<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WordController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {
        //
    }

    public function httpCallToJsonPlaceHolder(){

        $response = Http::get('https://jsonplaceholder.typicode.com/posts')->json();

        return $response;

    }

    //function for counting the number of times the word appear, counting double beeing on title
    public function post_word_counter($word,$post){

        $title = explode(' ',$post['title']);

        $description = explode(' ',$post['body']);

        $times = 0;

        for($i = 0; $i < count($title); $i++){

            if($title[$i] == $word){

                $times+=2;

            }

        }

        for($i = 0; $i < count($description); $i++){

            if($description[$i] == $word){

                $times++;

            }

        }

        return $times;
    }

    //function analyzing the response
    public function analyze($word,$response){

        $userId = 1;

        $userValue = 0;

        $totalUsersValues = [];

        for($i = 0; $i < count($response) ; $i++){

            if($response[$i]['userId'] == $userId){

                $userValue += $this->post_word_counter($word,$response[$i]);

            }else{

                $totalUserValues[$userId] = $userValue;

                $userValue = 0;

                $userId++;
            }

        }

        return $totalUserValues;
    }

    //function for receiving the word, launch http request and return the response
    public function treatment(Request $request){

        $word = request('data');

        $httpCall = $this->httpCallToJsonPlaceHolder();

        $array = $this->analyze($word,$httpCall);

        return response()->json(['data' => $array]);
    }
}
