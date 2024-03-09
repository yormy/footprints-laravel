<?php
namespace Yormy\FootprintsLaravel\Services;

use Illuminate\Http\Request;
use Yormy\FootprintsLaravel\Exceptions\CacheTagSupportException;

class RequestTransformerService
{
    public function __construct(private Request $request) {}

    public function get(): array
    {
        $requestFields = [];
        $requestFields['request_id'] = (string) $this->request->get('request_id');

        $payload = (string) $this->request->getContent();
        $payload = $this->cleanPayload($payload);

        $props['payload_base64'] = $payload;

        $remoteFields = $this->getRemoteDetails($this->request);
        $data = array_merge($props, $requestFields, $remoteFields);

        $fingerprintCookieName = config('footprints.browser_fingerprint_cookie_name','browser_fingerprint');
        $data['browser_fingerprint'] = $this->request->cookie($fingerprintCookieName);

        $data['impersonator_id'] = $this->request->get('impersonator_id');

        return $data;
    }

    private static function cleanPayload(string $payload): string
    {
        if (! config('footprints.content.payload.enabled')) {
            return '';
        }

        $truncated = substr($payload, 0, (int) config('footprints.payload.max_characters'));

        return base64_encode($truncated);  // base64 encode to prevent sqli
    }

    private function getRemoteDetails(Request $request): array
    {
        $data = [];

        if (config('footprints.content.ip')) {
            $data['ip_address'] = $request->ip();
        }

        if (config('footprints.content.user_agent')) {
            $data['user_agent'] = $request->userAgent();
        }

        if (config('footprints.content.geoip')) {
            $supportsTags = cache()->supportsTags();
            if (! $supportsTags) {
                throw new CacheTagSupportException;
            }

            $location = geoip()->getLocation($request->ip());
            $data['location'] = json_encode($location->toArray());
        }

        return $data;
    }
}
