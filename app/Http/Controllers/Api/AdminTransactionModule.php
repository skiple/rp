<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

use App\Transaction;

use Carbon\Carbon;

class AdminTransactionModule extends Controller
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
    	$transactions = Transaction::orderBy('updated_at', 'desc')->get();
    	$transactions_result = array();

        // get all dependencies
        foreach ($transactions as $transaction) {
            $activity = $transaction->activity;
            $activity_date = $transaction->activity_date;

            $transaction_result = array(
                'activity'      => $activity,
                'activity_date' => $activity_date,
                'quantity'      => $transaction->quantity,
                'total_price'   => $transaction->total_price,
                'status'        => $transaction->status,
            );

            $transactions_result[] = $transaction_result;
        }

        $results = array(
            'transactions' => $transactions_result,
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
            $activity = $transaction->activity;
            $activity_date = $transaction->activity_date;

            $transaction_result = array(
                'activity'      => $activity,
                'activity_date' => $activity_date,
                'quantity'      => $transaction->quantity,
                'total_price'   => $transaction->total_price,
                'status'        => $transaction->status,
            );

            $results = array(
                'transaction' => $transaction_result,
            );
        }

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function acceptPayment(Request $request, $id)
    {
        $results = array();

        $transaction = Transaction::where('id_transaction', $id)->first();
        if ($transaction == NULL) {
            // activity data not found
            $this->response['code']   = 404;
            $this->response['status'] = -1;
            $this->response['message']= "No transaction found with the specified Transaction ID.";
        } else {
            // update the transaction status
            $transaction->status = 2;
            $transaction->updated_at = Carbon::now('Asia/Jakarta');
            $transaction->save();

            $results = array(
                'transaction' => $transaction,
            );
        }

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }

    public function rejectPayment(Request $request, $id)
    {
        $results = array();

        $transaction = Transaction::where('id_transaction', $id)->first();
        if ($transaction == NULL) {
            // activity data not found
            $this->response['code']   = 404;
            $this->response['status'] = -1;
            $this->response['message']= "No transaction found with the specified Transaction ID.";
        } else {
            // update the transaction status
            $transaction->status = 0;
            $transaction->updated_at = Carbon::now('Asia/Jakarta');
            $transaction->save();
            
            $transaction->payment->forceDelete();

            $results = array(
                'transaction' => $transaction,
            );
        }

    	$this->response['result'] = json_encode($results);
        $json = $this->logResponse($this->response);

        return response()->json($json,$this->response['code']);
    }
}
