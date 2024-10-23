<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Yormy\FootprintsLaravel\Interfaces\FootprintInterface;
use Yormy\FootprintsLaravel\Traits\PackageFactoryTrait;
use Yormy\Xid\Models\Traits\Xid;

/**
 * Class Footprint
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property float $page_visit_sec
 */
class Footprint extends Model implements FootprintInterface
{
    use PackageFactoryTrait;
    use Prunable;
    use Xid;

    protected $fillable = [
        'impersonator_id',
        'impersonator_id_cookie',
        'method',
        'user_id',
        'user_id_cookie',
        'user_type',
        'route',
        'url',
        'ip_address',
        'location',
        'request_id',
        'session_id',
        'payload',
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
        'custom_cookies',
    ];

    public function __construct(array $attributes = [])
    {
        /**
         * @psalm-suppress RedundantPropertyInitializationCheck
         */
        if (! isset($this->table)) {
            $this->setTable((string) config('footprints.table_name', '')); // @phpstan-ignore-line
        }

        parent::__construct($attributes);
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     */
    public function prunable(): Builder
    {
        $days = (int) config('footprints.delete_records_older_than_days', 100); // @phpstan-ignore-line

        return static::where('created_at', '<=', now()->subDays($days));
    }

    public function user(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
