<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

use App\Activity;
use App\Activity_date;
use App\Activity_time;

use Storage;
use Carbon\Carbon;

class AdminActivityModule extends Controller
{
    /**
	 *	@var array response
	 */
    protected $response = array();

    /**
     * RentingModule constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
    	$this->response['code']       = 200; // default code for success
        $this->response['status']     = 1;  // default status for success
        $this->response['message']    = "success"; // message success
        $this->response['time']       = date('Y-m-d H:i:s'); // time when incoming request
        $this->response['url']        = $request->url(); // current url
        $this->response['method']     = $request->method(); // current http method
        $this->response['action']     = Route::currentRouteAction(); // current action controller handler
        $this->response['parameter']  = json_encode($request->all());
    }

    public function getAddActivity(Request $request)
    {
    	$this->response['result'] = json_encode(array());
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function createActivity(Request $request)
    {
    	// validation request
        $results = array();

        $validator  = Validator::make($request->all(),[
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

	    if ($validator->fails()) {
            $this->response['code']   = 400;
            $this->response['status'] = 0;
            $this->response['message']= "Error in validation request.";
            $results = $validator->errors();
        } else {
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
        	// create new activity
        	for($i=1; $i<=$request['date_count']; $i++){
		    	$new_activity_date = new Activity_date();
		    	$new_activity_date->id_activity = $new_activity->id_activity;
		    	$new_activity_date->max_participants = $request['max_participants'];

		    	//change date from format
		    	$req_name = 'date_from' . $i;
		    	$date_from = Carbon::createFromFormat("Y-m-d", $request[$req_name], "Asia/Jakarta");
	        	$date_from = $date_from->format('Y-m-d');
		    	$new_activity_date->date = $date_from;

		    	$new_activity_date->created_at = Carbon::now('Asia/Jakarta');
		    	$new_activity_date->updated_at = Carbon::now('Asia/Jakarta');
		    	$new_activity_date->save();

		    	for($j=1; $j<=$request['duration']; $j++){
		    		$new_activity_time = new Activity_time();
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

		    $results = array(
		    	'activity' => $new_activity,
		    );
        }

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }
}
