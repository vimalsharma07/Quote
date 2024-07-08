<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;

class FrontController extends Controller
{
    public  function  index(){
        $quotes= Quote::all();
        return view('front.index', ['quotes'=>$quotes]);

    }
}
