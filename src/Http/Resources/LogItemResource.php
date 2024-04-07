<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Http\Resources;

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

        if (isset($this->browser_fingerprint)) {
            $data['browser_fingerprint'] = $this->browser_fingerprint;
        }

        if (isset($this->data)) {
            $data['data'] = $this->data;
        }

        if (isset($this->impersonator_id)) {
            $data['impersonator_id'] = $this->impersonator_id;
        }

        if (isset($this->route)) {
            $data['route'] = $this->route;
        }

        if (isset($this->url)) {
            $data['url'] = $this->url;
        }

        if (isset($this->method)) {
            $data['method'] = $this->method;
        }

        $dataLocation = json_decode($this->location, true);

        $merged = array_merge($data, $dataLocation);

        if (array_key_exists('ip', $merged)) {
            unset($merged['ip']);       // remove the ip retrieved from the location and use the custom ip
        }

        return $merged;
    }

    private function get(): void
    {
    }
}
