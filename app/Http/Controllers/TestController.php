<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        return response()->json([
            'msg'=> 'We should return only'
        ]);
    }
    public function testTag(){
        // return "it is working";
        return response()->json([
            'msg' => 'some error occured'
        ],422);
    }
}
