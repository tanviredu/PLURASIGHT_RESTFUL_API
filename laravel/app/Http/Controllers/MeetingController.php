<?php

namespace App\Http\Controllers;
use Carbon\Carbon;  ## carbon is used for time
use Illuminate\Http\Request;
use App\Meeting;

class MeetingController extends Controller
{

    ## when the class start executing we have to
    ## add the middleware so lets create a constructor function
    ## so when any method is called we can middleware will
    ## automatically added

    public function __construct()
    {
        #$this->middleware('name');
    }







    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ## this will return all the meeting
        // get all the meetings
        $meetings = Meeting::all();

        foreach($meetings as $meeting){
            // create  a new attribute
            $meeting->view_meeting = [
                'href' => 'api/meeting/' . $meeting->id,
                'method' => 'GET'
            ];

            
        }

        $response = [
            'msg' => 'List of all Meetings',
            'meetings' => $meetings
        ];
        
        return response()->json($response,200);
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            // store the meeting

            $this->validate($request,[
                'title' => 'required',
                'description' => 'required',
                'time' => 'required',
                'user_id' => 'required'
            ]);

            // if validated then
            $title = $request->input('title');
            $description = $request->input('description');
            $time = $request->input('time');
            $user_id = $request->input('user_id');
            
            // create the meeting object

            $meeting = new Meeting([
                'time' => Carbon::createFromFormat('Y-m-d H',$time),
                'title' => $title,
                'description' => $description
            ]);

            if ($meeting->save()){
                // remeber in the 
                // Meeting Model
                // there are onr to many relationship
                // with the user
                // so one meeting object or one single meeting 
                // has multiple user
                // so we can attach many user 
                // in one meeting object
                $meeting->users()->attach($user_id);
                // so we save the meeting time
                // time,title,description
                // we attach the user
                $meeting->view_meeting = [
                    'href' => 'api/meeting/' .$meeting->id,
                    'method' => 'GET'
                ];

                $messgae = [
                    'msg' => 'Meeting created',
                    'meeting' =>$meeting
                ];
                return response()->json($messgae,201);
            }

            $response = [
                'msg' => "Error during creation"
            ];
            return response()->json($response,404);
    }


#################################################################
# VERY VERY IMPORTANT
# WHEN YOU WORK WITH THE MANY TO MANY RELSTIONSHIP
# AND WHEN YOU INSERT DATA LIKE ADDING A USER IN POST TABLE
# WHICH HAS MANY TO MANY RELATIONSHIP WITH USERS TABLE
#  YOU NEED A PIVOT TABLE
# WHICH HOLDS THE ID OR THE FOREIGH KEY OF BOTH IF YOU DO



# TO DO IT VERY EASILY WHAT YOU DO IS

# CREATE THE OBJECT LIKE
# $user = new User(data)  // this is the user object 
# THEN YOU ATTACH THE USER WITH THE $post OBJECT BUT IN A DIFFERENT WAY
# YOU ADD POST THEN THE METHOD USERS THEN attach command
# $post->users()->attach($user)
#  IT WILL NOT ONLY ADD THE USER BUT ALSO ADD AN ENTRY IN THE PIVOT TABLE
# AUTOMATICALLY
# TO REMOVE THE ENTRY IN THE PIVOT TABLE USE deatach
# $post->users()->deatch()



















##################################################################










    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // we have to filter the meeting with id
        // this is a join query
        $meeting = Meeting::with('users')->where('id',$id)->firstOrFail();
        $meeting->view_meetings = [
            'href' => 'api/meeting',
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'Meeting information',
            'meeting' => $meeting
        ];

        return response()->json($response,200);

    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // this take a id for input for update
    public function update(Request $request, $id)
    {

        //validate
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'time' => 'required|date_format:YmdHie',
            'user_id' => 'required'
        ]);

        // if validated then
        $title = $request->input('title');
        $description = $request->input('description');
        $time = $request->input('time');
        $user_id = $request->input('user_id');

        $meeting = Meeting::with('users')->findOrFail($id);

        if(!$meeting->users()->where('users.id',$user_id)->first()){
            // the specfic meeting  users and the input user id 
            // do not match
            $response = [
                'msg' => 'User is not registered for meeting'
            ];
    
            return response()->json($response,401);
    

        }

        $meeting->time = Carbon::createFromFormat('YmdHie',$time);
        $meeting->title = $title;
        $meeting->description = $description;

        if(!$meeting->update()){
            return response()->json([
                'msg' => 'Error during update'
            ],404);
        }

        $meeting->view_meeting =[
            'href' => 'api/meeting/' . $meeting->id,
            'meeting' => $meeting
        ];

        return response()->json($response,200);




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
        ## find the meeting
        ## now find all the user related to the meeting
        $users = $meeting->users;
        $meeting->users()->deatch();
        if(!$meeting->delete()){
            // meeting is not delete so attach again
            foreach($users as $user){
                $meeting->users()->attach($user);
            }
            return response()->json([
                'msg' => 'deletion failed'
            ],404);
        }

        $response = [
            'msg' => 'Meeting Deleted',
            'create' => [
                'href' => 'api/meeting',
                'method' => 'POST',
                'params' => 'title,description,time'
            ]
        ];

        return response()->json($response,200);
    }
}
