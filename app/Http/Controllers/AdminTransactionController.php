<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class AdminTransactionController extends Controller
{
	public function __construct(){
    	$this->middleware('isAdmin');
    }
    
    public function viewTransactions(){
    	$all_transactions = Transaction::orderBy('updated_at', 'desc')->get();
    	$data = array(
    		'all_transactions' => $all_transactions,
    	);
    	return view('admin.transaction_list')->with($data);
    }
}
