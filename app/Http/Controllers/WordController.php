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
        /*$postId = 0;
        $postValue = 0;*/

        $totalUsersValues = [];

        for($i = 0; $i < count($response) ; $i++){

            if($response[$i]['userId'] == $userId){

                $userValue += $this->post_word_counter($word,$response[$i]);
                $postId = $response[$i]['id'];
                $postValue = $this->post_word_counter($word,$response[$i]);

            }else{

            $totalUserValues[] = ['userId'=>$userId, 'userValue'=>$userValue/*,'postId'=>$postId,'postValue'=>$postValue*/];

                $userValue = 0;
                /*$postId = 0;
                $postValue = 0;*/

                $userId++;
            }

        }

        return $totalUserValues;
    }

    //function for csv ceration
    function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w');
        // loop over the input array
        foreach ($array as $line) {
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter);
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }

    //function for receiving the word, launch http request and makes the csv downloadable
    public function treatment(Request $request){

        $word = request('data');

        $httpCall = $this->httpCallToJsonPlaceHolder();

        $array = $this->analyze($word,$httpCall);

        usort($array, function($object1, $object2) {

            return $object1['userId'] < $object2['userId'];

        });

        $this->array_to_csv_download($array, $filename = "export.csv", $delimiter=";");

    }

}
