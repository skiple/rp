<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /**
     * Table name in database
     * @var string
     */
    protected $table = "tb_payment_methods";

    /**
     * The Primary Key in this table
     * @var string
     */
    protected $primaryKey = 'id_payment_method';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'payment_method_name', 'payment_method_photo', 'description', 'account_number', 'account_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /** RELATIONSHIPS METHOD **/

    /**
     *  Returns the transaction payments with this payment method
     *  @return App\TransactionPayment
     */
    public function transaction_payments()
    {
        return $this->hasMany('App\TransactionPayment', 'id_payment_method');
    }
}
