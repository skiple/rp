<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\User;

use Carbon\Carbon;

class ActivityController extends Controller
{
    //view activity catalog
    public function viewActivityCatalog(){
    	$all_activity = Activity::orderBy('updated_at', 'desc')->get();
    	$data = array(
    		'all_activity' => $all_activity,
    	);
    	return view('user.home')->with($data);
    }

    //view detail activity
    public function viewDetailActivity($id){
    	$activity = Activity::where('id_activity', $id)->first();
    	$data = array(
    		'activity' => $activity,
    	);
    	return view('user.detail_activity')->with($data);
    }
}
