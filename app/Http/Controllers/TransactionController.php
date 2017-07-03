<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Activity_date;
use App\Transaction;
use App\Transaction_payment;

use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct(){
    	$this->middleware('isLoggedIn');
    }

    public function viewTransactions(Request $request){
    	$current_user_id = $request->user()->id_user;
    	$all_transactions = Transaction::where('id_user', $current_user_id)->orderBy('updated_at', 'desc')->get();
    	$data = array(
    		'all_transactions' => $all_transactions,
    	);
    	return view('user.transaction_list')->with($data);
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

	    //substract the max participants
	    $activity_date = Activity_date::where('id_activity_date', $request['date'])->first();
	    $activity_date->max_participants -= $request['quantity'];
	    $activity_date->save();

	    $price = Activity::where('id_activity', $request['activity_id'])->first()->price;
	    $total_price = $price * $request['quantity'];
	    $new_transaction->total_price = $total_price;

	    $new_transaction->status = 0;
	    $new_transaction->created_at = Carbon::now('Asia/Jakarta');
	    $new_transaction->save();

	    return redirect('transactions');
    }

    public function viewConfirmPayment($id){
    	$data = array(
    		'id_transaction' => $id,
    	);
    	return view('user.confirm_payment')->with($data);
    }

    public function postCreatePayment(Request $request){
    	$this->validate($request, [
	        'name' 			 => 'required|alpha|max:64',
	        'email' 		 => 'required|email|max:64',
	        'phone' 		 => 'required|numeric',
	        'amount' 		 => 'required',
	        'bank' 			 => 'required',
	        'id_transaction' => 'required|numeric',
	    ]);

	    $new_transaction_payment = new Transaction_payment();
	    $new_transaction_payment->id_transaction = $request['id_transaction'];
	    $new_transaction_payment->name = $request['name'];
	    $new_transaction_payment->email = $request['email'];
	    $new_transaction_payment->phone = $request['phone'];
	    $new_transaction_payment->amount = $request['amount'];
	    $new_transaction_payment->bank = $request['bank'];

	    $new_transaction_payment->created_at = Carbon::now('Asia/Jakarta');
	    $new_transaction_payment->save();

	    $transaction = Transaction::where('id_transaction', $request['id_transaction'])->first();
	    $transaction->status = 1;
	    $transaction->save();

	    return redirect('transactions');
    }
}
