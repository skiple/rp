<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Rules\CheckPassword;


use Carbon\Carbon;
use Hash;
use Mail;

class UserController extends Controller
{
    private function generateRandomString($length){
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $result;
    }
    private function generateForgotPasswordToken($id_user){
        $token .= $this->generateRandomString(3);

        //Add id user for making sure its unique
        $token .= $id_user;

        $token .= $this->generateRandomString(3);

        //Add timestamps
        $token .= Carbon::now()->format('dmYHis');

        $token .= $this->generateRandomString(6);
        return $token;
    }

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
        $new_user->updated_at = Carbon::now('Asia/Jakarta');

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

    // View change password
    public function viewChangePassword(){
        return view('user.change_password');
    }

    // Post change password request
    public function postChangePassword(Request $request){
        $this->validate($request, [
            'old_password' => ['required', new CheckPassword],
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request["new_password"]);
        $user->save();

        return redirect('/transactions');
    }

    // View forgot password
    public function viewForgotPassword(){
        return view('user.forgot_password');
    }

    // Post forgot password request
    public function postForgotPassword(Request $request){
        $this->validate($request, [
            'email' => ['required', 'email', 'exists:tb_user,email'],
        ]);

        $user = User::where('email', $request["email"])->first();
        $generatedToken = $this->generateForgotPasswordToken($user->id_user);
        
        // Save token to DB
        $user->forgot_password_token = $generatedToken;
        $user->save();
        
        return Mail::send('emails.forgot_password', ['user' => $user, 'token' => $generatedToken], function ($m) use ($user, $generatedToken) {
            $m->from('noreply@rentuff.id', 'Rentuff Admin');

            $name = $user->first_name . " " . $user->last_name;
            $m->to($user->email, $name)->subject('Forgot password request');
        });

        return "Silahkan cek email anda";
    }

    // View reset password
    public function viewResetPassword($token){
        $user = User::where('forgot_password_token', $token)->first();
        if($user){
            $newPassword = $this->generateRandomString(8);
            
            $user->password = bcrypt($newPassword);
            $user->forgot_password_token = "";
            $user->save();

            $data = array(
                'password' => $newPassword,
            );
            return view('user.forgot_password')->with($data);
        }
        else{
            return "token salah";
        }
    }
    
}
