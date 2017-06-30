<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminActivityController extends Controller
{
    public function __construct(){
    	$this->middleware('isAdmin');
    }
    //view add activity for admin only
    public function viewAddActivity(Request $request){
    	return view('add_activity');
    }
}
