<?php

namespace Yormy\LaravelFootsteps\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'footsteps';

    protected $fillable = [
        'user_id',
        'user_type',
        'route',
        'url',
        'ip',
        'location',
        'request_id',
        'request_start',
        'payload_base64',
        'user_agent',
        'log_date',
        'table_name',
        'log_type',
        'data'
    ];

    public function getDateHumanizeAttribute()
    {
        return $this->log_date->diffForHumans();
    }

    public function getJsonDataAttribute()
    {
        return json_decode($this->data,true);
    }

    public function user()
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
