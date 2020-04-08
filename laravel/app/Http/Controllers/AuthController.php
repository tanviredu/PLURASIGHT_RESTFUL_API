<?php

namespace App\Http\Controllers;
use App\User;

## this import must be here to work
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
class AuthController extends Controller
{
    public function store(Request $request){
        ## we take name email and password
        ##  auth controller id for sign up the user
        // first validate the user
        // this will validate the whole request
        // and the special crieria
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // if the data is coming properly
        // them create the user object

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $user = new User([

            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)

        ]);

        if($user->save()){
            $user->signin = [
                'href' => 'api/user/signin',
                'method' => 'POST',
                'params' => 'email,password'
            ];


            // the response will send allt the information
            // including the attribute we just created
            //user->signin 
            $response = [
                'msg' => 'User Created',
                'user' =>$user
            ];
            return response()->json($response,201);
        }

        $response = [
            'msg' => 'An error occured'
        ];
        return response()->json($response,404);
    }

    public function signin(Request $request){


################################################################3


## REMEMBER YOU NEED TO CHANGE THE USER MODEL 
## TOO DO THIS THREE THINGS

#1) import this in User.php
#use Tymon\JWTAuth\Contracts\JWTSubject;

# then this
#class User extends Authenticatable implements JWTSubject{}

## now add two method

#public function getJWTIdentifier()
#{
#    return $this->getKey();
#}

#and this


#public function getJWTCustomClaims()
#{
#    return [];
#}


## then it will work fine






##################################################################3

        // first validate
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required'
        ]);


        $credentials = $request->only('email','password');
        

        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['msg'=>'invalid credentials'],401);
            }
        }catch(JWTException $e){
            return response()->json(['msg'=>'unknown problem'],500);
            
        }

        return response()->json(['token' => $token]);





        
        // // if validated then we found the user
        // // fetch the email and password
        // $email = $request->input('email');
        // $password = $request->input('password');

        // $user = [
        //     'name' => 'Name',
        //     'email' => $email,
        //     'password' => $password
        // ];

        // $response = [
        //     'msg' => 'User signed In',
        //     'user' => $user
        // ];

        // return response()->json($response,200);
    }
}
