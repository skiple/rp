<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity_date extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_activity_date';

    protected $primaryKey = 'id_activity_date';
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id_activity_date'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Get the activity of the date.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity', 'id_activity', 'id_activity');
    }

    /**
     * Get the times for the date.
     */
    public function times()
    {
        return $this->hasMany('App\Activity_time', 'id_activity_date');
    }
}
