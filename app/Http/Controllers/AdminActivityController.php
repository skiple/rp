<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use Storage;

class AdminActivityController extends Controller
{
    public function __construct(){
    	$this->middleware('isAdmin');
    }
    //view add activity for admin only
    public function viewAddActivity(Request $request){
    	return view('add_activity');
    }

    //post add activity
    public function postAddActivity(Request $request){
    	$this->validate($request, [
	        'activity_name' => ['required','regex:/^[a-zA-Z ]*$/','max:128'],
	        'host_name' => ['required','regex:/^[a-zA-Z ]*$/','max:64'],
	        'host_profile' => 'required',
	        'duration' => 'required|numeric',
	        'description' => 'required',
	        'max_participants' => 'required|numeric',
	        'photo1' => 'required|image',
	        'photo2' => 'required|image',
	        'photo3' => 'required|image',
	        'photo4' => 'required|image',
	        'price' => 'required|numeric',
	        'provide' => 'required',
	        'location' => 'required|max:100',
	        'itinerary' => 'required',
	    ]);

	    $new_activity = new Activity();
	    $new_activity->activity_name	= $request['activity_name'];
	    $new_activity->host_name 		= $request['host_name'];
	    $new_activity->host_profile 	= $request['host_profile'];
	    $new_activity->duration 		= $request['duration'];
	    $new_activity->description 		= $request['description'];
	    $new_activity->max_participants = $request['max_participants'];
	    $new_activity->photo1 			= "temp";
	    $new_activity->photo2 			= "temp";
	    $new_activity->photo3 			= "temp";
	    $new_activity->photo4 			= "temp";
	    $new_activity->price 			= $request['price'];
	    $new_activity->provide 			= $request['provide'];
	    $new_activity->location 		= $request['location'];
	    $new_activity->itinerary 		= $request['itinerary'];

	    $new_activity->save();

	    //file name
	    $id 	= $new_activity->id_activity;
	    $ext 	= ".jpg";

	    //save the picture
	    $photo1 = $request->file('photo1')->storeAs('public/images/activities', 
	    	$id . "-1" . $ext);
	    $photo2 = $request->file('photo2')->storeAs('public/images/activities', 
	    	$id . "-2" . $ext);
	    $photo3 = $request->file('photo3')->storeAs('public/images/activities', 
	    	$id . "-3" . $ext);
	    $photo4 = $request->file('photo4')->storeAs('public/images/activities', 
	    	$id . "-4" . $ext);

	    //save to database
	    $new_activity->photo1 = $photo1;
	    $new_activity->photo2 = $photo2;
	    $new_activity->photo3 = $photo3;
	    $new_activity->photo4 = $photo4;
	    $new_activity->save();

	    return redirect('/list_activity');
    }
}
