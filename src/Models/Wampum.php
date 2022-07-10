<?php

namespace Yormy\LaravelFootsteps\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Schema;
use Yormy\LaravelFootsteps\Services\CleanHtml;
use Yormy\LaravelFootsteps\Services\FootstepsService;

/**
 * @property string $title
 * @property string $description
 * @property string $values_old
 * @property string $values_new
 * @property string $values_diff
 * @property string $type
 * @property int $created_by
 * @property int $needs_translation
 */

class Footsteps extends Model
{
   // use QueryCacheable;

    // Cachables
//    public $cacheFor = 4 * (60 * 60); // cache time, in seconds
//    protected static $flushCacheOnUpdate = true;

    //use HasPackageFactoryTrait;
    protected $table = "footsteps";

    protected $fillable = [
        'title',
        'description',
        'needs_translation',
        'mainable_type',
        'mainable_id',
        'relatable_type',
        'relatable_id',
        'values_old',
        'values_new',
        'values_diff',
        'type',
        'created_by',
    ];

    public function mainable(): MorphTo
    {
        return $this->morphTo();
    }

    public function relatable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @psalm-suppress PossiblyInvalidCast
     * @psalm-suppress MixedArgument
     */
    public function createdBy(): belongsTo
    {
        return $this->belongsTo(
            config('footsteps.models.user.class'),
            'created_by',
            config('footsteps.models.user.key')
        );
    }

    public function getTitleWithValues(): string
    {
        if (!$this->needs_translation) {
            return $this->title;
        }

        return $this->getTextWithValues($this->title);
    }

    /**
     * @psalm-suppress MixedAssignment
     */
    public function getDescriptionWithValues(bool $stripEmptyValues = true): string
    {
        if (!$this->needs_translation) {
            $description = $this->description;

            foreach (json_decode($this->values_new, true) as $key => $value) {
                $description .= "<br>$key : $value";
            }
            return $description;
        }

        $text = $this->getTextWithValues($this->description);

        if ($stripEmptyValues) {
            $text = $this->removeEmptyFromTranslated($text);
        }
        return $text;
    }

    /**
     * @psalm-suppress PossiblyInvalidCast
     * @psalm-suppress PossiblyInvalidArgument
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedAssignment
     */
    protected function getTextWithValues(?string $text): string
    {
        if (!$text) {
            return '';
        }

        $updatedValues = [];
        if ($this->type === FootstepsService::CREATED) {
            $updatedValues = json_decode($this->values_new, true);
        }
        if ($this->type === FootstepsService::DELETED) {
            $updatedValues = json_decode($this->values_old, true);
        }
        if ($this->type === FootstepsService::UPDATED) {
            $updatedValues = json_decode($this->values_diff, true);
        }
        if ($this->type === FootstepsService::NOTE) {
            $updatedValues = json_decode($this->values_new, true);
        }

        $values = array_merge($this->buildEmptyAttributes(), $updatedValues);

        $text = CleanHtml::cleanup(__($text, $values));

        // keep unchanged values as default to still show the label of the value if needed
        return $text;

        //return $this->removeEmptyFromTranslated($text);
    }

    /**
     * @psalm-suppress MixedOperand
     */
    protected function removeEmptyFromTranslated(string $text): string
    {
        $text = str_replace('<br />'. config('footsteps.display.empty_attribute'), '', $text);
        $text = str_replace('<br /><br />', '<br />', $text);

        $text = str_replace('<strong>'. config('footsteps.display.empty_attribute') ."</strong>", '', $text);
        $text = str_replace('<strong></strong>', '', $text);

        return $text;
    }

    /**
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArrayOffset
     * @psalm-suppress MixedMethodCall
     * @psalm-suppress UndefinedThisPropertyFetch
     */
    protected function buildEmptyAttributes(): array
    {
        if (!$this->relatable_type) {
            return [];
        }

        /**
         * @var Model $model
         */
        $model = new $this->relatable_type();
        $attributes = Schema::getColumnListing($model->getTable());

        foreach ($attributes as $attribute) {
            /**
             * @var string $attribute
             */
            $attributes[$attribute] = config('footsteps.display.empty_attribute');
        }

        return $attributes;
    }
}
