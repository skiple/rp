<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Transaction;
use App\Mail\FinishTransaction;

use Carbon\Carbon;

class FinishTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish transaction for finished activity';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current_time = Carbon::now("Asia/Jakarta");
        $this->info('Current datetime: ' . $current_time);
        
        $counter = 0;
        $transactions = Transaction::where('status', 2)->get();
        foreach($transactions as $transaction){
            $activity_date = $transaction->activity_date;
            $activity_time = $activity_date->times->last();

            $transaction_date   = $activity_date->date; // Activity date (first day)
            $time_end           = $activity_time->time_end; // Activity end time (last day)
            $transaction_date = $transaction_date . " " . $time_end; // Append date and time for creating Carbon

            $transaction_carbon_time = Carbon::createFromFormat("Y-m-d H:i:s", $transaction_date, "Asia/Jakarta");

            $activity_duration  = $activity_time->day - 1; // Activity duration (excluding first day)

            //Finish transaction date --> transaction's activity last day
            //Finish transaction time --> the end time of its activity's last day + 1 hour
            $transaction_carbon_time->addDays($activity_duration);
            $transaction_carbon_time->addHour();
            
            // Compare with current time
            $isTransactionFinished = $current_time->gt($transaction_carbon_time);

            if($isTransactionFinished){
                $counter++;

                // set the transaction status to FINISHED (3)
                $this->info('Transaction ' . $transaction->id_transaction . ' is finished (Date: ' . $transaction_carbon_time . ')');
                $transaction->status = 3;
                $transaction->save();

                // Send thank you email
                $user = $transaction->user;
                $activity = $transaction->activity;
                Mail::to($user->email)->send(new FinishTransaction($user, $activity));
            }
        }
        
        // success log
        $this->info('FinishTransaction Command Run successfully!');
        $this->info('Affected rows: ' . $counter);
    }
}
