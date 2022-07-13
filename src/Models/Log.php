<?php

namespace Yormy\LaravelFootsteps\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Log extends Model
{
    use Prunable;

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
        'data',
        'model_old',
        'model_changes',
    ];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function prunable(): \Illuminate\Database\Eloquent\Builder
    {
        $days = (int)config('footsteps.prune_logs_after_days', 100);

        return static::where('created_at', '<=', now()->subDays($days));
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
