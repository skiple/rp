<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Exception;

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
        $transactions = $this->transactions;
        if(count($transactions)>0){
            return true;
        }
        else{
            return false;
        }
    }

    public function deleteDetails(){
        try{
            $id = $this->id_activity;
            $success = DB::transaction(function ($id) use ($id) {
                if($this->isLocked()==false){
                    $dates = $this->dates;
                    foreach($dates as $date){
                        $times = $date->times;
                        foreach($times as $time){
                            $time->forceDelete();
                        }
                        $date->forceDelete();
                    }
                    return true;
                } 
                else{
                    return false;
                }
            });
            return $success;
        }
        catch (Exception $e){
            return $e;
        }
    }
}
