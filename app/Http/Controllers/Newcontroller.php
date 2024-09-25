<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Newcontroller extends Controller
{
    public function singlePostView(){

        return view('single-post');
    }
}
