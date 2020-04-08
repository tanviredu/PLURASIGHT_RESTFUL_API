<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "it worked";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "it worked";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ## this will make meeting 
        ## registration
        ## not user registration
        ## it take

        
        ## first validate
        $this->validate($request,[
            // this will validate the meeting and user_id
            'meeting_id' => 'required',
            'user_id' => 'required'
        ]);

        // now get the meeting the id and the user_id

        $meeting_id  = $request->input('meeting_id');
        $user_id  = $request->input('user_id');

        // now find if the meeting exists or 
        // the meeting exists
        // find the meeting and the user with the id
        $meeting = Meeting::findOrFail($meeting_id);
        $user = User::findOrFail($user_id);


        ### create the message

        $message = [
            'msg' => "User is already registered",
            'user' => $user,
            'meeting' => $meeting,
            'unregister' => [
                'href' => 'api/meeting/registration/' .$meeting->id,
                'method' => 'DELETE'
            ]
        ];

        if($meeting->users()->where('users.id',$user->id)->first()){
            // if you find the user in the join query
            return response()->json($message,404);
        }

        // if not attach to the meeting
        // you can do it with reverse order too
        $user->meetings()->attach($meeting);


        // create the response

        $message = [
            'msg' => "User is registered for the meeting",
            'user' => $user,
            'meeting' => $meeting,
            'unregister' => [
                'href' => 'api/meeting/registration/' .$meeting->id,
                'method' => 'DELETE'
            ]
        ];

        return response()->json($message,201);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "it worked";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return "it worked";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return "it worked";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        // do not delete the meeting just 
        // delete the user from the meeting
        $meeting->users()->detach();
        $response = [
            'msg' => "User unregistered from the meeting",
            'meeting' => $meeting,
        ];

        return response()->json($response,200);
    }
}
