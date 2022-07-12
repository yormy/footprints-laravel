<?php declare(strict_types=1);

namespace Yormy\LaravelFootsteps\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Yormy\Dateformatter\Services\DateHelper;

class FootstepsResource extends JsonResource
{
    /**
     * @psalm-suppress MixedPropertyFetch
     */
    public function toArray($request)
    {
        /**
         * @var string $displayNameField
         */
        $displayNameField = config('footsteps.models.user.display_name');

        return [
            'id' => $this->id,
            'title' => $this->getTitleWithValues(),
            'description' => $this->getDescriptionWithValues(),
            'values_old' => $this->values_old,
            'user_name' => $this->createdBy ? $this->createdBy->{$displayNameField} : '',
            'created_at' => Datehelper::formatDateTime($this->created_at),
        ];
    }
}
