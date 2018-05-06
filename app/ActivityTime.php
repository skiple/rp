<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityTime extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_activity_time';

    protected $primaryKey = 'id_activity_time';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id_activity_time'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Get the date of the time.
     */
    public function date()
    {
        return $this->belongsTo('App\ActivityDate', 'id_activity_date');
    }
}
