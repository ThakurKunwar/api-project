<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GithubController extends Controller
{
    //

    public  function profile($username)
    {


        $response = Http::withToken(env('GITHUB_TOKEN'))
            ->get('https://api.github.com/users/' . $username);




        return response()->json($response->json());


        // return [
        //     'name' => $response['login'],
        //     'avatar' => $response['avatar_url'],
        //     'profile' => $response['html_url'],
        //     'followers_api' => $response['followers_url'],
        //     'repos_api' => $response['repos_url'],
        // ];
    }
}
