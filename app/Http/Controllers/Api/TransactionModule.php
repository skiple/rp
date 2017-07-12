<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use App\Activity;
use App\Activity_date;
use App\Transaction;
use App\Transaction_payment;

use Carbon\Carbon;

class TransactionModule extends Controller
{
    /**
	 *	@var array response
	 */
    protected $response = array();

    /**
     * RentingModule constructor.
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

    public function getAllTransactions(Request $request)
    {
    	$current_user_id = $request->user()->id_user;
    	$all_transactions = Transaction::where('id_user', $current_user_id)->orderBy('updated_at', 'desc')->get();

    	$results = array(
    		'transactions' => $all_transactions,
    	);

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function getTransaction(Request $request, $id)
    {
        $results = array();

        $transaction = Transaction::where('id_transaction', $id)->first();
        if ($transaction == NULL) {
            // transaction data not found
            $this->response['code']   = 404;
            $this->response['status'] = -1;
            $this->response['message']= "No transaction found with the specified Transaction ID.";
        } else {
            // get the transaction to our results
            $results = array(
                'transaction' => $transaction,
            );
        }

        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function createTransaction(Request $request)
    {
    	// validation request
        $results = array();

        $validator  = Validator::make($request->all(),[
	        'quantity' 		=> 'required|numeric',
	        'date' 			=> 'required|numeric',
	        'id_activity' 	=> 'required|numeric',
        ]);

        if ($validator->fails()) {
            $this->response['code']   = 400;
            $this->response['status'] = 0;
            $this->response['message']= "Error in validation request.";
            $results = $validator->errors();
        } else {
            // validate activity id first
            $activity = Activity::find($request['id_activity']);
            if ($activity == NULL) {
                // transaction data not found
                $this->response['code']   = 404;
                $this->response['status'] = -1;
                $this->response['message']= "No activity found with the specified Activity ID.";
            } else {
            	// validation success: create new transaction
    		    $new_transaction = new Transaction();
    		    $new_transaction->id_activity = $request['id_activity'];
    		    $new_transaction->id_activity_date = $request['date'];
    		    $new_transaction->id_user = $request->user()->id_user;
    		    $new_transaction->quantity = $request['quantity'];

    		    //subtract the max participants
    		    $activity_date = Activity_date::where('id_activity_date', $request['date'])->first();
    		    $activity_date->max_participants -= $request['quantity'];
    		    $activity_date->save();

    		    $price = $activity->price;
    		    $total_price = $price * $request['quantity'];
    		    $new_transaction->total_price = $total_price;

    		    $new_transaction->status = 0;
    		    $new_transaction->created_at = Carbon::now('Asia/Jakarta');
    		    $new_transaction->save();

    		   	$results = array(
    		   		'transaction' => $new_transaction,
    		   	);
            }
        }

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function getPayment(Request $request, $id)
    {
        $results = array(
            'id_transaction' => $id,
        );

        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function createPayment(Request $request)
    {
        // validation request
        $results = array();

        $validator  = Validator::make($request->all(),[
            'account_name'   => ['required','regex:/^[a-zA-Z ]*$/','max:64'],
            'from_bank'      => 'required|max:64',
            'phone'          => 'required|numeric',
            'amount'         => 'required',
            'bank'           => 'required',
            'transfer_date'  => 'required|date',
            'id_transaction' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $this->response['code']   = 400;
            $this->response['status'] = 0;
            $this->response['message']= "Error in validation request.";
            $results = $validator->errors();
        } else {
            // validate id_transaction first
            $transaction = Transaction::find($request['id_transaction']);
            if ($transaction == NULL) {
                // transaction data not found
                $this->response['code']   = 404;
                $this->response['status'] = -1;
                $this->response['message']= "No transaction found with the specified Transaction ID.";
            } else {
                // create a new transaction payment
                $new_transaction_payment = new Transaction_payment();
                $new_transaction_payment->id_transaction = $request['id_transaction'];
                $new_transaction_payment->account_name = $request['account_name'];
                $new_transaction_payment->from_bank = $request['from_bank'];
                $new_transaction_payment->phone = $request['phone'];
                $new_transaction_payment->amount = $request['amount'];
                $new_transaction_payment->bank = $request['bank'];

                //Change format of transfer date
                $transfer_date = Carbon::createFromFormat("Y-m-d", $request['transfer_date'], "Asia/Jakarta");
                $transfer_date = $transfer_date->format('Y-m-d');
                $new_transaction_payment->transfer_date = $transfer_date;

                $new_transaction_payment->created_at = Carbon::now('Asia/Jakarta');
                $new_transaction_payment->updated_at = Carbon::now('Asia/Jakarta');
                $new_transaction_payment->save();

                $transaction->updated_at = Carbon::now('Asia/Jakarta');
                $transaction->status = 1;
                $transaction->save();

                $results = array(
                    'transaction' => $transaction,
                    'payment'     => $new_transaction_payment,
                );
            }
        }

        $this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }
}
