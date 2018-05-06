<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use App\Mail\PaymentReminder;

use App\Activity;
use App\ActivityDate;
use App\PaymentMethod;
use App\Transaction;
use App\TransactionPayment;

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
    	$transactions = Transaction::where('id_user', $current_user_id)->orderBy('updated_at', 'desc')->get();
        $transactions_result = array();

        // get all dependencies
        foreach ($transactions as $transaction) {
            $activity = $transaction->activity;
            $activity_date = $transaction->activity_date;

            // picture for activity
            $activity->photo1 = $activity->photo1 == NULL ? "" : url('storage/app/' . $activity->photo1);
            $activity->photo2 = $activity->photo2 == NULL ? "" : url('storage/app/' . $activity->photo2);
            $activity->photo3 = $activity->photo3 == NULL ? "" : url('storage/app/' . $activity->photo3);
            $activity->photo4 = $activity->photo4 == NULL ? "" : url('storage/app/' . $activity->photo4);

            $transaction_result = array(
                'id_transaction' => $transaction->id_transaction,
                'created_at'     => date("Y-m-d H:i:s", $transaction->created_at->timestamp),
                'activity'       => $activity,
                'activity_date'  => $activity_date,
                'quantity'       => $transaction->quantity,
                'total_price'    => $transaction->total_price,
                'status'         => $transaction->status,
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

            // picture for activity
            $activity->photo1 = $activity->photo1 == NULL ? "" : url('storage/app/' . $activity->photo1);
            $activity->photo2 = $activity->photo2 == NULL ? "" : url('storage/app/' . $activity->photo2);
            $activity->photo3 = $activity->photo3 == NULL ? "" : url('storage/app/' . $activity->photo3);
            $activity->photo4 = $activity->photo4 == NULL ? "" : url('storage/app/' . $activity->photo4);

            $transaction_result = array(
                'id_transaction' => $transaction->id_transaction,
                'created_at'     => date("Y-m-d H:i:s", $transaction->created_at->timestamp),
                'activity'       => $activity,
                'activity_date'  => $activity_date,
                'quantity'       => $transaction->quantity,
                'total_price'    => $transaction->total_price,
                'status'         => $transaction->status,
            );

            $results = array(
                'transaction' => $transaction_result,
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
                // activity data not found
                $this->response['code']   = 404;
                $this->response['status'] = -1;
                $this->response['message']= "No activity found with the specified Activity ID.";
            } else {
                $activity_date = ActivityDate::where('id_activity_date', $request['date'])->first();
                if ($activity_date == NULL){
                    // activity date data not found
                    $this->response['code']   = 404;
                    $this->response['status'] = -1;
                    $this->response['message']= "No activity date found with the specified Activity Date ID.";
                }
                else if($activity_date->max_participants < $request['quantity']){
                    // quantity is more than maximum participants of the event
                    $this->response['code']   = 404;
                    $this->response['status'] = -1;
                    $this->response['message']= "Your desired quantity number exceed the number of maximum participants of the event.";
                }
                else{
                    // validation success: create new transaction
                    $new_transaction = new Transaction();
                    $new_transaction->id_activity = $request['id_activity'];
                    $new_transaction->id_activity_date = $request['date'];
                    $new_transaction->id_user = $request->user()->id_user;
                    $new_transaction->quantity = $request['quantity'];

                    //subtract the max participants
                    $activity_date->max_participants -= $request['quantity'];
                    $activity_date->save();

                    $price = $activity->price;
                    $total_price = $price * $request['quantity'];
                    $new_transaction->total_price = $total_price;

                    $new_transaction->status = 0;
                    $new_transaction->save();

                    $user = $request->user();
                    // Send mail
                    Mail::to($user->email)->send(new PaymentReminder($user, $new_transaction));

                    $results = array(
                        'transaction' => $new_transaction,
                    );
                }
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

    public function getPaymentMethod(Request $request)
    {
        $results = array(
            'payment_methods' => PaymentMethod::all(),
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
            'account_name'      => ['required','regex:/^[a-zA-Z ]*$/','max:64'],
            'from_bank'         => 'required|max:64',
            'phone'             => 'required|numeric',
            'amount'            => 'required',
            'bank'              => 'required',     // TODO soon to be deleted
            'transfer_date'     => 'required|date',
            'id_payment_method' => 'required',
            'id_transaction'    => 'required|numeric',
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
                
                // verify if payment method exists
                $payment_method = PaymentMethod::findOrFail($request['id_payment_method']);

                $new_transaction_payment = new TransactionPayment();
                $new_transaction_payment->id_transaction = $request['id_transaction'];
                $new_transaction_payment->account_name = $request['account_name'];
                $new_transaction_payment->from_bank = $request['from_bank'];
                $new_transaction_payment->phone = $request['phone'];
                $new_transaction_payment->amount = $request['amount'];
                $new_transaction_payment->bank = $request['bank'];
                $new_transaction_payment->id_payment_method = $payment_method->id_payment_method;

                //Change format of transfer date
                $transfer_date = Carbon::createFromFormat("Y-m-d", $request['transfer_date']);
                $transfer_date = $transfer_date->format('Y-m-d');
                $new_transaction_payment->transfer_date = $transfer_date;
                $new_transaction_payment->save();

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
