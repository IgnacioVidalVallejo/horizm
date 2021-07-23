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
}
