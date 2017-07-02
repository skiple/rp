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

        $birthdate = Carbon::createFromFormat("d F Y", $request['birthday'], "Asia/Jakarta");
        $birthdate = $birthdate->format('Y-m-d');
    	$new_user->birthdate = $birthdate;
    	$new_user->password = bcrypt($request['password']);
    	$new_user->created_at = Carbon::now('Asia/Jakarta');

    	$new_user->save();

    	//try login using new account
    	if (Auth::loginUsingId($new_user->id_user)) {
		    return back();
		}
		else{
			return redirect('/');
		}
    }

    public function postSignIn(Request $request){
    	$this->validate($request, [
	        'email' => 'required|email',
	        'password' => 'required',
	    ]);

    	if (Auth::attempt([
    			'email' => $request['email'], 
    			'password' => $request['password']
    		])) {
            // Authentication passed...
            return back();
        }
        else{
        	return "Username / Password salah";
        }
    }

    public function logout(Request $request){
    	Auth::logout();
    	return back();
    }
}
