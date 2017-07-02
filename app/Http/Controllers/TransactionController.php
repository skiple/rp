<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Transaction;

use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct(){
    	$this->middleware('isLoggedIn');
    }

    public function viewTransactions(){
    	return 1;
    }

    public function postCreateTransaction(Request $request){
    	$this->validate($request, [
	        'quantity' 		=> 'required|numeric',
	        'date' 			=> 'required|numeric',
	        'activity_id' 	=> 'required|numeric',
	    ]);

	    $new_transaction = new Transaction();
	    $new_transaction->id_activity = $request['activity_id'];
	    $new_transaction->id_activity_date = $request['date'];
	    $new_transaction->id_user = $request->user()->id_user;
	    $new_transaction->quantity = $request['quantity'];

	    $price = Activity::where('id_activity', $request['activity_id'])->first()->price;
	    $total_price = $price * $request['quantity'];
	    $new_transaction->total_price = $total_price;

	    $new_transaction->status = 0;
	    $new_transaction->created_at = Carbon::now('Asia/Jakarta');
	    $new_transaction->save();

	    return redirect('transactions');
    }
}
