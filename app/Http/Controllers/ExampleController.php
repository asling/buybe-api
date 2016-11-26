<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function jsonp_test(Request $request){
        $resp = array(
            ['id'=>'1','title'=>"test1",'url'=>"http://baidu.com"],
            ['id'=>'2','title'=>"test2",'url'=>"http://baidu.com"],
            ['id'=>'3','title'=>"test3",'url'=>"http://baidu.com"],
            ['id'=>'4','title'=>"test4",'url'=>"http://baidu.com"],
        );
        return response()->json($resp)->setCallback($request->query("callback"));
    }

    public function json_test(Request $request){
      
        $resp = array(
            ['id'=>'1','title'=>"test1",'url'=>"http://baidu.com"],
            ['id'=>'2','title'=>"test2",'url'=>"http://baidu.com"],
            ['id'=>'3','title'=>"test3",'url'=>"http://baidu.com"],
            ['id'=>'4','title'=>"test4",'url'=>"http://baidu.com"],
        );
        //return response()->json($resp);
        return $request->query("callback");  
    }

    //
}
