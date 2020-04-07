<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;






## with except key word we will show 
## which they cant
## this route can't do the update and post
## only get





Route::resource('/meeting','MeetingController',[
    'except' => ['edit','create']
]);



## add another route
## can only do the post and delete

Route::resource('meeting/registration','RegistrationController',[
    'only' => ['store','destroy']
]);

## add route for users only the post nothing else
## we can define the controller using 'uses'
## this is the default uses of the post

## this is the registration post
Route::post('user',[
    'uses' => 'AuthController@store'
]);


## now the sign in endpoint

Route::post('user/signin',[
    'uses' => 'AuthController@signin'
]);