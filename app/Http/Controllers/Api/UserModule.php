<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\Rules\CheckPassword;

use App\Mail\ForgotPassword;

use Carbon\Carbon;
use Hash;

class UserModule extends Controller
{   
	/**
	 *	@var array response
	 */
    protected $response = array();

    /**
     * UserModule constructor.
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

    private function generateRandomString($length){
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $result;
    }
    private function generateForgotPasswordToken($id_user){
        $token = $this->generateRandomString(3);

        //Add id user for making sure its unique
        $token .= $id_user;

        $token .= $this->generateRandomString(3);

        //Add timestamps
        $token .= Carbon::now("Asia/Jakarta")->format('dmYHis');

        $token .= $this->generateRandomString(6);
        return $token;
    }

    public function signUp(Request $request)
    {
	    // validations for all the variables needed
        $validator  = Validator::make($request->all(),[
	        'first_name' => 'required|alpha|max:32',
	        'last_name' => 'required|alpha|max:32',
	        'email' => 'required|unique:tb_user,email|email|max:64',
	        'phone' => 'required|numeric',
	        'birthday' => 'required|date',
	        'password' => 'required|min:8|confirmed',
	    ]);

        if ($validator->fails()) {
            // validation fails:
            // create error response
            $this->response['code']   = 400;
            $this->response['status']   = 0;
            $this->response['message']= "Error in validation request";
            $results = $validator->errors();
        } else {
        	// create new user
	    	$new_user = new User();
	    	$new_user->first_name = $request['first_name'];
	    	$new_user->last_name = $request['last_name'];
	    	$new_user->email = $request['email'];
	    	$new_user->phone = $request['phone'];

	        $birthdate = Carbon::createFromFormat("Y-m-d", $request['birthday'], "Asia/Jakarta");
	        $birthdate = $birthdate->format('Y-m-d');
	    	$new_user->birthdate = $birthdate;
	    	$new_user->password = bcrypt($request['password']);
	    	$new_user->created_at = Carbon::now('Asia/Jakarta');
	        $new_user->updated_at = Carbon::now('Asia/Jakarta');
	        $new_user->api_token = str_random(255);

	    	$new_user->save();

	    	$results = array(
                'api_token'  => $new_user->api_token,
                'token_type' => "Bearer",
	    		'user' 		 => $new_user,
	    	);
	    }

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function signIn(Request $request)
    {
        $results = array();
    	// validations for all the variables needed
        $validator  = Validator::make($request->all(),[
	        'email' => 'required|email',
	        'password' => 'required',
	    ]);

        if ($validator->fails()) {
            // validation fails:
            // create error response
            $this->response['code']   = 400;
            $this->response['status']   = 0;
            $this->response['message']= "Error in validation request";
            $results = $validator->errors();
        } else {
        	// Authenticate
        	if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
        		// Authentication passed: get data
        		$api_token = str_random(255);

        		$user = User::find($request->user()->id_user);
                $user->api_token	= $api_token;
                $user->updated_at	= date('Y-m-d H:i:s');

                // Save the entered data
                $user->save();

                $results = array(
                    'api_token'  => $api_token,
                    'token_type' => "Bearer",
                    'user'    	 => $user,
                );

        	} else {
                // Authentication failed
                $this->response['code']     = 404;
                $this->response['status']   = -1;
                $this->response['message']  = "Email and Password did not match.";
            }
        }

        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json, $this->response['code']);
    }

    public function signOut(Request $request)
    {
        // define variable
        $results = array();

        $check = User::where('api_token',$request->user()->api_token)->first();
        if (count($check) == 0) {
            // The token is not found in any of the user data
            // Invalid token, force logout
            $this->response['code']     = 404;
            $this->response['status']   = -1;
            $this->response['message']  = "Invalid token.";
        } else {
            // Token found: set to null
            $user = User::find($check->id_user);
            $user->api_token = null;
            $user->save();
        }

        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    // Method to change password
    public function changePassword(Request $request){
        // define variable
        $results = array();

        // validations for all the variables needed
        $validator  = Validator::make($request->all(),[
            'old_password' => ['required', new CheckPassword],
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            // validation fails:
            // create error response
            $this->response['code']   = 400;
            $this->response['status']   = 0;
            $this->response['message']= "Error in validation request";
            $results = $validator->errors();
        } else {
            $user = $request->user();
            $user->password = bcrypt($request["new_password"]);
            $user->save();    
        }

        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    // Method to send forgot password email
    public function forgotPassword(Request $request){
        // define variable
        $results = array();

        // validations for all the variables needed
        $validator  = Validator::make($request->all(),[
            'email' => ['required', 'email', 'exists:tb_user,email'],
        ]);

        if ($validator->fails()) {
            // validation fails:
            // create error response
            $this->response['code']   = 400;
            $this->response['status']   = 0;
            $this->response['message']= "Error in validation request";
            $results = $validator->errors();
        } else {
            $user = User::where('email', $request["email"])->first();
            $generatedToken = $this->generateForgotPasswordToken($user->id_user);
            
            // Save token to DB
            $user->forgot_password_token = $generatedToken;
            $user->save();
            
            // Concat user's name
            $name = $user->first_name . " " . $user->last_name;

            // Send mail
            Mail::to($user->email, $name)->send(new ForgotPassword($user, $generatedToken));
        }
        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    // Method for reset password
    public function resetPassword(Request $request){
        // define variable
        $results = array();

        // validations for all the variables needed
        $validator  = Validator::make($request->all(),[
            'new_password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            // validation fails:
            // create error response
            $this->response['code']   = 400;
            $this->response['status']   = 0;
            $this->response['message']= "Error in validation request";
            $results = $validator->errors();
        } else {
            $token = $request["token"];
            $newPassword = $request["new_password"];

            // Check if token is expired (15 mins already)
            $token_time = substr($token, -20, 14);
            $token_time = Carbon::createFromFormat('dmYHis', $token_time, 'Asia/Jakarta');
            $now = Carbon::now('Asia/Jakarta');
            if ($now->diffInMinutes($token_time)>15){
                // The token is expired
                $this->response['code']     = 404;
                $this->response['status']   = -1;
                $this->response['message']  = "Expired token.";
            }

            $user = User::where('forgot_password_token', $token)->first();
            if($user){
                $user->password = bcrypt($newPassword);
                $user->forgot_password_token = "";
                $user->save();

                $results = array(
                    'new_password'  => $newPassword,
                );
            }
            else{
                // Token isn't found in any of the user data
                $this->response['code']     = 404;
                $this->response['status']   = -1;
                $this->response['message']  = "Invalid token.";
            }
        }

        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    // Method for get user data profile
    public function getProfile(Request $request){
        // define variable
        $results = array();

        $user = $request->user();

        $results = array(
            'user'  => $user,
        );

        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    // Method for update user profile
    public function editProfile(Request $request){
        // define variable
        $results = array();

        // validations for all the variables needed
        $validator  = Validator::make($request->all(),[
            'first_name'    => 'required|alpha|max:32',
            'last_name'     => 'required|alpha|max:32',
            'phone'         => 'required|numeric',
        ]);

        if ($validator->fails()) {
            // validation fails:
            // create error response
            $this->response['code']   = 400;
            $this->response['status']   = 0;
            $this->response['message']= "Error in validation request";
            $results = $validator->errors();
        } else {
            $user = $request->user();
            $user->first_name   = $request["first_name"];
            $user->last_name    = $request["last_name"];
            $user->phone        = $request["phone"];
            $user->save();
            
            $results = array(
                'user' => $user,
            );
        }
        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }
}
