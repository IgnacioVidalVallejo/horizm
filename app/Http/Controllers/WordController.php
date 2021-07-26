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

    public function treatment(Request $request){

        $word = request('data');

        $response = Http::get('https://jsonplaceholder.typicode.com/posts')->json();

        return response()->json(['data' => $response]);
    }

    public function post_word_counter($word,$post){

        $title = explode(' ',$post->title);
        $description = explode(' ',$post->body);

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
}
