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
    	$results = array(
    		'activities' => $all_activity,
    	);

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function getActivity(Request $request, $id)
    {
    	$activity = Activity::where('id_activity', $id)->first();
    	$results = array(
    		'activity' => $activity,
    	);

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }
}
