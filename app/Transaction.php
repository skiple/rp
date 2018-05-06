<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_transaction';

    protected $primaryKey = 'id_transaction';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id_transaction'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Get the activity of the transaction.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity', 'id_activity');
    }

    /**
     * Get the activity date of the transaction.
     */
    public function activity_date()
    {
        return $this->belongsTo('App\ActivityDate', 'id_activity_date');
    }

    /**
     * Get the user of the transaction.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }

    /**
     * Get the payment of the transaction.
     */
    public function payment()
    {
        return $this->hasOne('App\TransactionPayment', 'id_transaction');
    }
}
