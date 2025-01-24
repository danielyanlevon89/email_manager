<?php

namespace App\Http\Controllers;

use http\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function test()
    {
        $curl = curl_init();    // create cURL session

        $API_KEY = "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"; // login to https://beta.openai.com/account/api-keys and create an API KEY

        $url = "https://api.openai.com/v1/completions";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        $headers = array( // cUrl headers (-H)
            "Content-Type: application/json",
            "Authorization: Bearer $API_KEY"
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = array( // cUrl data
            "model" => "gpt-3.5-turbo-instruct", // choose your designated model
            "prompt" => "What's the weather in Vienna today? only metrics", // choose your prompt (what you ask the AI)
            "temperature" => 0.5,   // temperature = creativity (higher value => greater creativity in your promts)
            "max_tokens" => 100     // max amount of tokens to use per request
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($curl);               // execute cURL
        $response = json_decode($response, true);   // extract json from response
dd($response);
        $generated_text = $response['choices'][0]['text'];  // extract first response from json

        echo $generated_text;   // output response

        curl_close($curl);      // close cURL session

    }
}
