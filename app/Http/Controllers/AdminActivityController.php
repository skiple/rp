<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\ActivityDate;
use App\ActivityTime;

use Storage;
use Carbon\Carbon;
use DB;

class AdminActivityController extends Controller
{
    public function __construct(){
    	$this->middleware('isAdmin');
    }
    
    //view list activity for admin only
    public function viewListActivity(Request $request){
    	$all_activity = Activity::orderBy('updated_at', 'desc')->get();
    	$data = array(
    		'all_activity' => $all_activity,
    	);
    	return view('admin.list_activity')->with($data);
    }

    //view detail activity for admin only
    public function viewDetailActivity(Request $request, $id){
    	$activity = Activity::where('id_activity', $id)->first();
    	if($activity){
    		$data = array(
	    		'activity' => $activity,
	    	);
	    	return view('admin.detail_activity')->with($data);
    	}
    	else{
    		return "Activity tidak ditemukan";
    	}
    }

    //post edit activity
    public function postEditActivity(Request $request){
    	$this->validate($request, [
	        'activity_name' => ['required','regex:/^[a-zA-Z ]*$/','max:128'],
	        'host_name' 	=> ['required','regex:/^[a-zA-Z ]*$/','max:64'],
	        'host_profile' 	=> 'required',
	        'description' 	=> 'required',
	        'photo1' 		=> 'image',
	        'photo2' 		=> 'image',
	        'photo3' 		=> 'image',
	        'photo4' 		=> 'image',
	        'provide' 		=> 'required',
	        'location' 		=> 'required|max:100',
	        'itinerary' 	=> 'required',
	        'id_activity' 	=> 'required|numeric'
	    ]);

	    for($i=1; $i<=$request['date_count']; $i++){
	    	$this->validate($request, [
	        	'date_from' . $i => 'required',
	    	]);
	    	for($j=1; $j<=$request['duration']; $j++){
	    		$this->validate($request, [
		        	'time_start' . $i . '-' . $j => 'required',
		        	'time_end' . $i . '-' . $j => 'required',
		    	]);
	    	}
	    }

    	$id = $request["id_activity"];
    	
	    $activity = Activity::where('id_activity', $id)->first();
	    $activity->activity_name	= $request['activity_name'];
	    $activity->host_name 		= $request['host_name'];
	    $activity->host_profile 	= $request['host_profile'];
	    $activity->description 		= $request['description'];
	    $activity->provide 			= $request['provide'];
	    $activity->location 		= $request['location'];
	    $activity->itinerary 		= $request['itinerary'];
	    if($request["price"]){
	    	$activity->price 		= $request['price'];
	    }
	    $activity->save();

	    for($i=1; $i<=$request['date_count']; $i++){
	    	$new_activity_date = new ActivityDate();
	    	$new_activity_date->id_activity = $id;

	    	$req_name = 'max_participants' . $i;
	    	$new_activity_date->max_participants = $request[$req_name];

	    	//change date from format
	    	$req_name = 'date_from' . $i;
	    	$date_from = Carbon::createFromFormat("d F Y", $request[$req_name], "Asia/Jakarta");
        	$date_from = $date_from->format('Y-m-d');
	    	$new_activity_date->date = $date_from;

	    	$new_activity_date->created_at = Carbon::now('Asia/Jakarta');
	    	$new_activity_date->updated_at = Carbon::now('Asia/Jakarta');
	    	$new_activity_date->save();

	    	for($j=1; $j<=$activity->duration; $j++){
	    		$new_activity_time = new ActivityTime();
	    		$new_activity_time->id_activity_date = $new_activity_date->id_activity_date;
	    		$new_activity_time->day = $j;

	    		//change time from format
	    		$req_name = 'time_start' . $i . '-' . $j;
		    	$time_start = Carbon::createFromFormat("H:i", $request[$req_name], "Asia/Jakarta");
        		$time_start = $time_start->format('H:i:s');
	    		$new_activity_time->time_start = $time_start;

	    		//change time from format
	    		$req_name = 'time_end' . $i . '-' . $j;
		    	$time_end = Carbon::createFromFormat("H:i", $request[$req_name], "Asia/Jakarta");
        		$time_end = $time_end->format('H:i:s');
	    		$new_activity_time->time_end = $time_end;

	    		$new_activity_time->created_at = Carbon::now('Asia/Jakarta');
	    		$new_activity_time->updated_at = Carbon::now('Asia/Jakarta');
	    		$new_activity_time->save();
	    	}
	    }

	    //file name
	    $ext 	= ".jpg";

	    //save the picture
	    if($request->file('photo1')){
		    $photo1 = $request->file('photo1')->storeAs('public/images/activities', 
		    	$id . "-1" . $ext);
	    }
	    if($request->file('photo2')){
	    	$photo2 = $request->file('photo2')->storeAs('public/images/activities', 
	    		$id . "-2" . $ext);
	    }
	    if($request->file('photo3')){
		    $photo3 = $request->file('photo3')->storeAs('public/images/activities', 
		    	$id . "-3" . $ext);
		}
		if($request->file('photo4')){
		    $photo4 = $request->file('photo4')->storeAs('public/images/activities', 
		    	$id . "-4" . $ext);
		}

	    return redirect('/list_activity');
    }

    //view detail activity date for admin only
    public function viewEditActivityDate(Request $request, $id){
    	$activity_date = ActivityDate::where('id_activity_date', $id)->first();
    	if($activity_date){
    		$data = array(
	    		'activity_date' => $activity_date,
	    	);
	    	return view('admin.edit_activity_date')->with($data);
    	}
    	else{
    		return "Activity date tidak ditemukan";
    	}
    }

    //post edit activity date
    public function postEditActivityDate(Request $request){
    	$this->validate($request, [
	        'max_participants' 	=> ['required','numeric'],
	        'id_activity_date' 	=> ['required'],
	    ]);

	    $activity_date = ActivityDate::where('id_activity_date', $request['id_activity_date'])->first();
	    if($activity_date->isLocked() == false){
			$this->validate($request, [
		        'date' 	=> 'required',
		    ]);

		    $time_count = count($activity_date->times);
		    for($j=1; $j<=$time_count; $j++){
		    	$this->validate($request, [
		        	'time_start' . $j 	=> 'required',
		        	'time_end' . $j 	=> 'required',
		        	'time_id' . $j 		=> 'required',
		    	]);
	    	}

	    	//change input date format
	    	$date = Carbon::createFromFormat("d F Y", $request['date'], "Asia/Jakarta");
        	$date = $date->format('Y-m-d');
	    	$activity_date->date = $date;

	    	// Save new activity time
	    	for($j=1; $j<=$time_count; $j++){
	    		$reqname = 'time_id' . $j;
	    		$activity_time = ActivityTime::where('id_activity_time', $request[$reqname])->first();

	    		$reqname = 'time_start' . $j;
	    		$activity_time->time_start = $request[$reqname];

	    		$reqname = 'time_end' . $j;
	    		$activity_time->time_end = $request[$reqname];
	    		$activity_time->save();
	    	}
	    }

	    $activity_date->max_participants = $request["max_participants"];
	    $activity_date->save();

	    // Return to its activity detail
	    return redirect('/admin/detail/activity/' . $activity_date->id_activity);
    }

    // Delete activity
    public function deleteActivity($id){
    	$activity = Activity::where('id_activity', $id)->first();
    	if($activity->deleteDetails()){
    		$activity->forceDelete();
    		return redirect('/list_activity');
    	}
    	else{
    		return "Delete Failed";
    	}
    }

    //view add activity for admin only
    public function viewAddActivity(Request $request){
    	return view('admin.add_activity');
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
	        'photo1' => 'image',
	        'photo2' => 'image',
	        'photo3' => 'image',
	        'photo4' => 'image',
	        'price' => 'required',
	        'provide' => 'required',
	        'location' => 'required|max:100',
	        'itinerary' => 'required',
	    ]);

	    for($i=1; $i<=$request['date_count']; $i++){
	    	$this->validate($request, [
	        	'date_from' . $i => 'required',
	    	]);
	    	for($j=1; $j<=$request['duration']; $j++){
	    		$this->validate($request, [
		        	'time_start' . $i . '-' . $j => 'required',
		        	'time_end' . $i . '-' . $j => 'required',
		    	]);
	    	}
	    }

	    $new_activity = new Activity();
	    $new_activity->activity_name	= $request['activity_name'];
	    $new_activity->host_name 		= $request['host_name'];
	    $new_activity->host_profile 	= $request['host_profile'];
	    $new_activity->duration 		= $request['duration'];
	    $new_activity->description 		= $request['description'];

	    $price = str_replace('.', '', $request['price']);
	    $new_activity->price 			= $price;

	    $new_activity->provide 			= $request['provide'];
	    $new_activity->location 		= $request['location'];
	    $new_activity->itinerary 		= $request['itinerary'];

	    $new_activity->created_at = Carbon::now('Asia/Jakarta');
	    $new_activity->updated_at = Carbon::now('Asia/Jakarta');
	    $new_activity->save();

	    for($i=1; $i<=$request['date_count']; $i++){
	    	$new_activity_date = new ActivityDate();
	    	$new_activity_date->id_activity = $new_activity->id_activity;
	    	$new_activity_date->max_participants = $request['max_participants'];

	    	//change date from format
	    	$req_name = 'date_from' . $i;
	    	$date_from = Carbon::createFromFormat("d F Y", $request[$req_name], "Asia/Jakarta");
        	$date_from = $date_from->format('Y-m-d');
	    	$new_activity_date->date = $date_from;

	    	$new_activity_date->created_at = Carbon::now('Asia/Jakarta');
	    	$new_activity_date->updated_at = Carbon::now('Asia/Jakarta');
	    	$new_activity_date->save();

	    	for($j=1; $j<=$request['duration']; $j++){
	    		$new_activity_time = new ActivityTime();
	    		$new_activity_time->id_activity_date = $new_activity_date->id_activity_date;
	    		$new_activity_time->day = $j;

	    		//change time from format
	    		$req_name = 'time_start' . $i . '-' . $j;
		    	$time_start = Carbon::createFromFormat("H:i", $request[$req_name], "Asia/Jakarta");
        		$time_start = $time_start->format('H:i:s');
	    		$new_activity_time->time_start = $time_start;

	    		//change time from format
	    		$req_name = 'time_end' . $i . '-' . $j;
		    	$time_end = Carbon::createFromFormat("H:i", $request[$req_name], "Asia/Jakarta");
        		$time_end = $time_end->format('H:i:s');
	    		$new_activity_time->time_end = $time_end;

	    		$new_activity_time->created_at = Carbon::now('Asia/Jakarta');
	    		$new_activity_time->updated_at = Carbon::now('Asia/Jakarta');
	    		$new_activity_time->save();
	    	}
	    }

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
