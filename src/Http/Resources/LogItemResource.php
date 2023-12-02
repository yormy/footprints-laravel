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
            'ip' => $this->ip,
            'user_agent' => $this->user_agent,
            'location' => $this->location,
            'created_at' => $this->created_at,
        ];

        $dataLocation = json_decode($this->location, true);

        return array_merge($data, $dataLocation);;
    }

    private function get()
    {

    }
}
