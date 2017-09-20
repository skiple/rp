<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transaction;

class Activity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_activity';

    protected $primaryKey = 'id_activity';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id_activity'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Get the dates for the activity.
     */
    public function dates()
    {
        return $this->hasMany('App\Activity_date', 'id_activity');
    }

    /**
     * Get the transactions for the activity.
     */
    public function transactions()
    {
        return $this->hasMany('App\Transaction', 'id_activity');
    }

    /**
     * Get the activity state
     * Locked state means activity can't be deleted or edited (price, duration)
     */
    public function isLocked(){
        $transactions = Transaction::where('id_activity', $this->id_activity)->where('status', '<>', 3)->where('status', '<>', -1)->get();
        if(count($transactions)>0){
            return true;
        }
        else{
            return false;
        }
    }
}
