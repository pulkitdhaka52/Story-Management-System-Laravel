<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Stories;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    #Function to view the story for all the users on frontend
    public function view($user_name, $slug)
    {
        //Query to check the user exists with username
        $users = User::select('id')
            ->where('username', '=', $user_name)
            ->first(); 
        $stories_array = array();

        if (isset($users->id) && $users->id != 0) {
            //Query to check the story exists with slug
            $where[] = ['slug', '=', $slug];
            $where[] = ['status', '=', 'Active'];
            $where[] = ['user_id', '=', $users->id];
            $stories = Stories::where($where)->first();
            if (isset($stories->id) && $stories->id != 0) {
                return view('story', compact('stories'));
            } else {
                return view('errors.404');
            }
        } else {
            return view('errors.404');
        }
    }
}
