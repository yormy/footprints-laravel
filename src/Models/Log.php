<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Yormy\CoreToolsLaravel\Traits\Factories\PackageFactoryTrait;
use Yormy\FootprintsLaravel\Interfaces\FootprintInterface;
use Yormy\Xid\Models\Traits\Xid;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class Log extends Model implements FootprintInterface
{
    use PackageFactoryTrait;
    use Prunable;
    use Xid;

    protected $fillable = [
        'impersonator_id',
        'method',
        'user_id',
        'user_type',
        'route',
        'url',
        'ip_address',
        'location',
        'request_id',
        'payload_base64',
        'user_agent',
        'log_date',
        'table_name',
        'model_type',
        'model_id',
        'log_type',
        'data',
        'model_old',
        'model_changes',
        'browser_fingerprint',
    ];

    public function __construct(array $attributes = [])
    {
        /**
         * @psalm-suppress RedundantPropertyInitializationCheck
         */
        if (! isset($this->table)) {
            $this->setTable((string) config('footprints.table_name'));
        }

        parent::__construct($attributes);
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function prunable(): Builder
    {
        $days = (int) config('footprints.delete_records_older_than_days', 100);

        return static::where('created_at', '<=', now()->subDays($days));
    }

    public function user(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }

    //    public function scopeInLog(Builder $query, ...$logNames): Builder
    //    {
    //        if (is_array($logNames[0])) {
    //            $logNames = $logNames[0];
    //        }
    //
    //        return $query->whereIn('log_name', $logNames);
    //    }
    //
    //    public function scopeCausedBy(Builder $query, Model $causer): Builder
    //    {
    //        return $query
    //            ->where('causer_type', $causer->getMorphClass())
    //            ->where('causer_id', $causer->getKey());
    //    }
}
