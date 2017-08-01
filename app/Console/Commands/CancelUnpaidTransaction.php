<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Transaction;

class CancelUnpaidTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:cancel_unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel unpaid transaction for expired activities.';

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
        $cur_date = date("Y-m-d");
        $this->info('Current datetime: ' . $cur_date);

        $counter = 0; // affected transaction counter

        $transactions = Transaction::where('status', 0)->get();
        foreach ($transactions as $transaction) {
            $date = $transaction->activity_date()->first();
            $transaction_date = date("Y-m-d H:i:s", strtotime($date->date));

            // check if the newly created transaction has passed the waiting time limit (1 hour)
            if ($transaction_date < $cur_date) {
                $counter++;

                // set the transaction status to CANCELLED (-1)
                $this->info('Transaction ' . $transaction->id_transaction . ' is automatically cancelled (Date: ' . $transaction_date . ')');
                $transaction->status = -1;
                $transaction->save();
            }
        }

        // success log
        $this->info('CancelUnpaidTransaction Command Run successfully!');
        $this->info('Affected rows: ' . $counter);
    }
}
