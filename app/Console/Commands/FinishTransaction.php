<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Transaction;

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
        
    }
}
