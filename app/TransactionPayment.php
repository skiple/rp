<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_transaction_payment';

    protected $primaryKey = 'id_transaction_payment';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id_transaction_payment'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Get the transaction of the payment.
     */
    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'id_transaction');
    }

    /**
     * Get the transaction of the payment.
     */
    public function payment_method()
    {
        return $this->belongsTo('App\PaymentMethod', 'id_payment_method');
    }
}
