<?php

namespace Yormy\LaravelFootsteps\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LogItemResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'xid' => $this->xid,
            'log_type' => $this->log_type,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'location' => $this->location,
            'created_at' => $this->created_at,
        ];

        $dataLocation = json_decode($this->location, true);

        $merged = array_merge($data, $dataLocation);

        if (array_key_exists('ip', $merged)) {
            unset($merged['ip']);       // remove the ip retrieved from the location and use the custom ip
        }

        return $merged;
    }

    private function get()
    {

    }
}
