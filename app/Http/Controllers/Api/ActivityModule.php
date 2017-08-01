<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Activity;

class ActivityModule extends Controller
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

    public function getAllActivity(Request $request)
    {
    	$all_activity = Activity::orderBy('updated_at', 'desc')->get();

        $activities = array();

        foreach ($all_activity as $activity) {
            $dates = $activity->dates;

            $removable = true;
            foreach ($dates as $date) {
                $cur_date = date("Y-m-d");
                $n_date = date("Y-m-d", strtotime($date->date));
                if ($cur_date < $n_date) {
                    $removable = false;
                }
            }

            // check if the activity is valid to be removed from our results:
            // valid if the date is not expired
            if (!$removable) {
                $activity->photo1 = $activity->photo1 == NULL ? "" : url('storage/app/' . $activity->photo1);
                $activity->photo2 = $activity->photo2 == NULL ? "" : url('storage/app/' . $activity->photo2);
                $activity->photo3 = $activity->photo3 == NULL ? "" : url('storage/app/' . $activity->photo3);
                $activity->photo4 = $activity->photo4 == NULL ? "" : url('storage/app/' . $activity->photo4);

                $activities[] = $activity;
            }
        }

    	$results = array(
    		'activities' => $activities,
    	);

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function getActivity(Request $request, $id)
    {
        $results = array();
        
    	$activity = Activity::where('id_activity', $id)->first();

        if ($activity == NULL) {
            // activity data not found
            $this->response['code']   = 404;
            $this->response['status'] = -1;
            $this->response['message']= "No activity found with the specified Activity ID.";
        } else {
            // append the results
            $activity->photo1 = $activity->photo1 == NULL ? "" : url('storage/app/' . $activity->photo1);
            $activity->photo2 = $activity->photo2 == NULL ? "" : url('storage/app/' . $activity->photo2);
            $activity->photo3 = $activity->photo3 == NULL ? "" : url('storage/app/' . $activity->photo3);
            $activity->photo4 = $activity->photo4 == NULL ? "" : url('storage/app/' . $activity->photo4);
            
            $dates = $activity->dates()->whereDate('date', '>', date("Y-m-d"));
            foreach ($dates as $date) {
                $times = $date->times;
                $date['times'] = $times->toArray();
                $date['participant_left'] = $date->max_participants - $date->transactions()->sum('quantity');
            }

        	$results = array(
        		'activity' => $activity,
        	);
        }

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }
}
