<?php

namespace App\Http\Controllers;

## this import must be here to work
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(Request $request){
        return "it worked";

    }

    public function signin(Request $request){
        return "it worked";
    }
}
