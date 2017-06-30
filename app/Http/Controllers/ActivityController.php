<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    //view activity catalog
    public function viewActivityCatalog(){
    	return view('home');
    }
}
