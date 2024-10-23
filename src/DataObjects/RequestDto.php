<?php

namespace Yormy\FootprintsLaravel\DataObjects;

use Illuminate\Http\Request;
use Yormy\FootprintsLaravel\Services\BlacklistFilter;

class RequestDto
{
    private ?string $url;

    private ?string $route;

    private ?string $payload;

    private ?array $data;

    private ?string $requestId;

    private ?string $sessionId;

    private ?string $impersonatorId;

    private ?string $browserFingerprint;

    private ?string $ipAddress;

    private ?string $userAgent;

    private ?string $geoLocation;

    private ?string $method;

    private $user;

    private function __construct()
    {
        // ...
    }

    public static function fromRequest(Request $request): self
    {
        $model = new self;

        $model->url = $request->fullUrl();

        if (is_object($request->route())) {
            $model->route = $request->route()->getName();
        }

        $model->ipAddress = $request->ip();

        $model->userAgent = $request->userAgent();

        $model->data = $model->getData($request);

        $model->payload = $model->getPayload($request);

        $model->requestId = (string) $request->get('request_id');

        $loginSessionIdCookieName = config('footprints.cookies.login_session_id', false);
        if ($loginSessionIdCookieName) {
            $sessionId = (string) $request->cookie($loginSessionIdCookieName);
            $model->sessionId = BlacklistFilter::truncateField($sessionId, (int) config('footprints.cookies.max_characters'));
        }

        $model->browserFingerprint = $model->getBrowserFingerprint($request);

        $model->geoLocation = $model->getGeoLocation($request);

        $model->method = self::determineMethod($request->route()?->methods);

        $model->user = self::determineUser();

        $model->impersonatorId = self::determineImpersonator();

        $model->customCookies = self::getCustomCookies($request);

        return $model;
    }

    private static function determineUser()
    {
        $userResolverClass = config('footprints.resolvers.user');
        $userResolver = new $userResolverClass;

        return $userResolver->getCurrent();
    }

    private static function determineImpersonator()
    {
        $impersonatorResolverClass = config('footprints.resolvers.impersonator');
        $impersonatorResolver = new $impersonatorResolverClass;

        return $impersonatorResolver->getImpersonator();
    }

    private function getData(Request $request): array
    {
        $data = $request->all();

        return BlacklistFilter::filterBlacklist($data);
    }

    public function toArray(): array
    {
        $data['request_id'] = $this->requestId;
        $data['method'] = $this->method;
        $data['route'] = $this->route;
        $data['url'] = $this->url;

        $data['session_id'] = $this->sessionId;

        $data['impersonator_id'] = $this->impersonatorId;

        $data['user_id'] = $this->user?->id;
        $data['user_type'] = $this->user ? get_class($this->user) : null;

        $data['browser_fingerprint'] = $this->browserFingerprint;

        if (config('footprints.content.ip')) {
            $data['ip_address'] = $this->ipAddress;
        }

        if (config('footprints.content.user_agent')) {
            $data['user_agent'] = $this->userAgent;
        }
        if (config('footprints.content.geoip')) {
            $data['location'] = $this->geoLocation;

        }

        $data['payload'] = $this->payload;

        $data['custom_cookies'] = json_encode($this->customCookies);

        $data['data'] = json_encode($this->data);

        return $data;
    }

    private static function determineMethod(?array $methods = []): string
    {
        $method = '';
        if (! $methods) {
            return $method;
        }

        if (in_array(Method::GET, $methods)) {
            $method = Method::GET;
        } elseif (in_array(Method::PUT, $methods)) {
            $method = Method::PUT;
        } elseif (in_array(Method::PATCH, $methods)) {
            $method = Method::PATCH;
        } elseif (in_array(Method::POST, $methods)) {
            $method = Method::POST;
        } elseif (in_array(Method::DELETE, $methods)) {
            $method = Method::DELETE;
        }

        return $method;
    }

    private static function getCustomCookies(Request $request): array
    {
        $customCookies = config('footprints.cookies.custom', []);

        $cookies = [];
        foreach ($customCookies as $cookieName) {
            $cookies[$cookieName] = $request->cookie($cookieName);
        }

        $cookies = BlacklistFilter::filterBlacklist($cookies);

        return BlacklistFilter::truncateData($cookies, (int) config('footprints.cookies.max_characters'));

    }

    private function getBrowserFingerprint(Request $request): ?string
    {
        $fingerprintCookieName = config('footprints.cookies.browser_fingerprint', 'browser_fingerprint');

        $fingerprint = $request->cookie($fingerprintCookieName);

        return BlacklistFilter::truncateField($fingerprint, (int) config('footprints.cookies.max_characters'));
    }

    private function getPayload(Request $request): string
    {
        $payload = (string) $request->getContent();

        return $this->cleanPayload($payload);
    }

    private function getGeoLocation(Request $request): ?string
    {
        return json_encode(['disabled']);
        //        $supportsTags = cache()->supportsTags();
        //        if (! $supportsTags) {
        //            throw new CacheTagSupportException;
        //        }
        //
        //        $location = geoip()->getLocation($request->ip());
        //        return json_encode($location->toArray());
    }

    private static function cleanPayload(string $payload): string
    {
        if (! config('footprints.content.payload.enabled')) {
            return '';
        }

        $payloadArray = self::maskFields($payload);
        $payloadArray = self::truncateFields($payloadArray);

        return json_encode($payloadArray);
    }

    private static function maskFields(string $payload): array
    {
        parse_str($payload, $payloadArray);

        return BlacklistFilter::filterBlacklist($payloadArray);
    }

    private static function truncateFields(array $payloadArray): array
    {
        return BlacklistFilter::truncateData($payloadArray, (int) config('footprints.content.payload.max_characters_per_field'));
    }
}
