<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function DemoAction1(Request $request)
    {
        return "Hello World 1";
    }
    public function DemoAction2(Request $request)
    {
        return "Hello World 2";
    }
}
