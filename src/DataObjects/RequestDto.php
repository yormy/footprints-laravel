<?php

namespace Yormy\FootprintsLaravel\DataObjects;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Yormy\FootprintsLaravel\Services\BlacklistFilter;
use Yormy\FootprintsLaravel\Services\Resolvers\ImpersonatorResolver;
use Yormy\FootprintsLaravel\Services\Resolvers\UserResolver;

class RequestDto
{
    private ?string $url = null;

    private ?string $route = null;

    private ?string $payload = null;

    private ?array $data = null;

    private ?string $requestId = null;

    private ?string $sessionId = null;

    private string|int|null $impersonatorId = null;

    private ?string $browserFingerprint = null;

    private ?string $ipAddress = null;

    private ?string $userAgent = null;

    private ?string $geoLocation = null;

    private ?string $method = null;

    private ?Authenticatable $user = null;

    private ?array $customCookies = null;

    private function __construct()
    {
        // ...
    }

    public static function fromRequest(Request $request): self
    {
        $model = new self;

        /** @var int $maxCharacters */
        $maxCharacters = (int) config('footprints.max_characters', 200); //@phpstan-ignore-line
        $model->url = BlacklistFilter::truncateField($request->fullUrl(), $maxCharacters);

        if (is_object($request->route())) {
            $model->route = BlacklistFilter::truncateField($request->route()->getName(), $maxCharacters); // @phpstan-ignore-line
        }

        $model->ipAddress = $request->ip();

        $model->userAgent = BlacklistFilter::truncateField($request->userAgent(), $maxCharacters);

        $model->data = $model->getData($request);

        $model->payload = $model->getPayload($request);

        $model->requestId = (string) $request->get('request_id'); // @phpstan-ignore-line

        $loginSessionIdCookieName = (string) config('footprints.cookies.login_session_id', false);  // @phpstan-ignore-line
        if ($loginSessionIdCookieName) {
            /** @var string $loginSessionIdCookieName */
            $sessionId = (string) $request->cookie($loginSessionIdCookieName); // @phpstan-ignore-line

            /** @var int $maxCharacters */
            $maxCharacters = (int) config('footprints.cookies.max_characters'); // @phpstan-ignore-line

            $model->sessionId = BlacklistFilter::truncateField($sessionId, $maxCharacters);
        }

        $model->browserFingerprint = $model->getBrowserFingerprint($request);

        $model->geoLocation = $model->getGeoLocation($request);

        if (is_object($request->route())) {
            $model->method = self::determineMethod($request->route()?->methods); // @phpstan-ignore-line
        }

        $model->user = self::determineUser();

        $model->impersonatorId = self::determineImpersonator();

        $model->customCookies = self::getCustomCookies($request);

        return $model;
    }

    private static function determineUser(): ?Authenticatable
    {
        $userResolverClass = config('footprints.resolvers.user');
        $userResolver = new $userResolverClass;

        /** @var UserResolver $userResolver */
        return $userResolver->getCurrent();
    }

    private static function determineImpersonator(): string|int|null
    {
        $impersonatorResolverClass = config('footprints.resolvers.impersonator');
        $impersonatorResolver = new $impersonatorResolverClass;

        /** @var ImpersonatorResolver $impersonatorResolver */
        return $impersonatorResolver->getImpersonatorId();
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

        $data['user_id'] = $this->user?->id; // @phpstan-ignore-line
        $data['user_type'] = $this->user ? get_class($this->user) : null;

        $data['browser_fingerprint'] = $this->browserFingerprint;

        // @phpstan-ignore-next-line
        if (config('footprints.content.ip')) {
            $data['ip_address'] = $this->ipAddress;
        }

        // @phpstan-ignore-next-line
        if (config('footprints.content.user_agent')) {
            $data['user_agent'] = $this->userAgent;
        }

        // @phpstan-ignore-next-line
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
        /** @var array $customCookies */
        $customCookies = config('footprints.cookies.custom', []);

        $cookies = [];
        foreach ($customCookies as $cookieName) {
            /** @var string $cookieName */
            $cookies[$cookieName] = $request->cookie($cookieName);
        }

        $cookies = BlacklistFilter::filterBlacklist($cookies);

        /** @var int $maxCharacters */
        $maxCharacters = (int) config('footprints.cookies.max_characters'); // @phpstan-ignore-line

        return BlacklistFilter::truncateData($cookies, $maxCharacters);

    }

    private function getBrowserFingerprint(Request $request): string
    {
        /** @var string $fingerprintCookieName */
        $fingerprintCookieName = (string) config('footprints.cookies.browser_fingerprint', 'browser_fingerprint'); // @phpstan-ignore-line

        if (! $fingerprintCookieName) {
            return '';
        }

        /** @var string $fingerprint */
        $fingerprint = (string) $request->cookie($fingerprintCookieName); // @phpstan-ignore-line

        // @phpstan-ignore-next-line
        $maxCharacters = (int) config('footprints.cookies.max_characters');

        return BlacklistFilter::truncateField($fingerprint, $maxCharacters);
    }

    private function getPayload(Request $request): string
    {
        $payload = (string) $request->getContent();

        return $this->cleanPayload($payload);
    }

    private function getGeoLocation(Request $request): ?string // @phpstan-ignore-line
    {
        return (string) json_encode(['disabled']);
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

        return (string) json_encode($payloadArray);
    }

    private static function maskFields(string $payload): array
    {
        parse_str($payload, $payloadArray);

        return BlacklistFilter::filterBlacklist($payloadArray);
    }

    private static function truncateFields(array $payloadArray): array
    {
        /** @var int $maxCharacters */
        $maxCharacters = (int) config('footprints.content.payload.max_characters_per_field'); // @phpstan-ignore-line

        return BlacklistFilter::truncateData($payloadArray, $maxCharacters);
    }
}
