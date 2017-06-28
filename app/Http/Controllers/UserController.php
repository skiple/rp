<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

use Carbon\Carbon;

class UserController extends Controller
{
    //
    public function postSignUp(Request $request){
    	$this->validate($request, [
	        'first_name' => 'required|alpha|max:32',
	        'last_name' => 'required|alpha|max:32',
	        'email' => 'required|unique:tb_user,email|email|max:64',
	        'phone' => 'required|numeric',
	        'birthday' => 'required|date',
	        'password' => 'required|min:8|confirmed',
	    ]);

    	$new_user = new User();
    	$new_user->first_name = $request['first_name'];
    	$new_user->last_name = $request['last_name'];
    	$new_user->email = $request['email'];
    	$new_user->phone = $request['phone'];
    	$new_user->birthdate = $request['birthday'];
    	$new_user->password = bcrypt($request['password']);
    	$new_user->created_at = Carbon::now();

    	$new_user->save();

    	if (Auth::loginUsingId($new_user->id_user)) {
		    // The user is active, not suspended, and exists.
		    return $request->user();
		    return back();
		}
		else{
			return 1;
		}
    }
}
