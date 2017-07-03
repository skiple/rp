<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

use Carbon\Carbon;

class AdminTransactionController extends Controller
{
	public function __construct(){
    	$this->middleware('isAdmin');
    }
    
    //view all transaction
    public function viewTransactions(){
    	$all_transactions = Transaction::orderBy('updated_at', 'desc')->get();
    	$data = array(
    		'all_transactions' => $all_transactions,
    	);
    	return view('admin.transaction_list')->with($data);
    }

    //view detail transaction
    public function viewDetailTransaction($id){
        $transaction = Transaction::where('id_transaction', $id)->first();
        $data = array(
            'transaction' => $transaction,
        );
        return view('admin.detail_transaction')->with($data);
    }

    //accept payment
    public function acceptPayment($id){
        $transaction = Transaction::where('id_transaction', $id)->first();
        $transaction->status = 2;
        $transaction->updated_at = Carbon::now('Asia/Jakarta');
        $transaction->save();

        return back();
    }

    //reject payment
    public function rejectPayment($id){
        $transaction = Transaction::where('id_transaction', $id)->first();
        $transaction->status = 0;
        $transaction->updated_at = Carbon::now('Asia/Jakarta');
        $transaction->save();
        
        $transaction->payment->forceDelete();
        return back();
    }
}
