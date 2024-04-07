<?php

namespace Yormy\FootprintsLaravel\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Yormy\FootprintsLaravel\Models\Log;

class LogFactory extends Factory
{
    protected $model = Log::class;

    public function definition()
    {
        return [
            'log_type' => 'AUTH_FAILED',
            'method' => 'SEED',
            'table_name' => '',
            'model_id' => null,
            'model_type' => null,
            'route' => '-seeded- test.admin.nu',
            'url' => '-seeded- https://www.example.com',
            'data' => null,
            'model_old' => null,
            'model_changes' => null,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'browser_fingerprint' => '',
            'location' => $this->getLocation(),
            'payload_base64' => '',
            'response_base64' => '',
            'request_id' => 'b466d31dba3c7654fe54c32ee0a69d70a3565953',
            'request_duration_sec' => 1,
            'created_at' => $this->faker->dateTime(),
        ];
    }

    public function forMember($member): Factory
    {
        return $this->state(function (array $attributes) use ($member) {
            return [
                'user_id' => $member->id,
                'user_type' => 'Mexion\\TestappCore\\Domain\\User\\Models\\Member',
                'user_agent' => "(member $member->id) ".$this->faker->userAgent,
            ];
        });
    }

    public function forAdmin($admin): Factory
    {
        return $this->state(function (array $attributes) use ($admin) {
            return [
                'user_id' => $admin->id,
                'user_type' => 'Mexion\\TestappCore\\Domain\\User\\Models\\Admin',
                'user_agent' => "(admin $admin->id) ".$this->faker->userAgent,
            ];
        });
    }

    public function loginFailed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'log_type' => 'AUTH_FAILED',
            ];
        });
    }

    public function loginSuccess(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'log_type' => 'AUTH_LOGIN',
            ];
        });
    }

    public function routeVisit(): Factory
    {
        //        INSERT INTO testapp.footprints (id, user_id, user_type, log_type, table_name, model_id, model_type, route, url, data, model_old, model_changes, ip, user_agent, browser_fingerprint, location, payload_base64, response_base64, request_id, request_duration_sec, created_at, updated_at) VALUES (71209, 1, 'Mexion\\TestappCore\\Domain\\User\\Models\\Member', 'ROUTE_VISIT', null, null, null, 'api.v1.member.login', 'http://testapp.local/login', '{"methods": "POST"}', null, null, '172.18.0.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'e097f15fffc2eb6a17677f260b00b891', '{"ip": "127.0.0.0", "lat": 41.31, "lon": -72.92, "city": "New Haven", "state": "CT", "cached": false, "country": "United States", "default": true, "currency": "USD", "iso_code": "US", "timezone": "America/New_York", "continent": "NA", "state_name": "Connecticut", "postal_code": "06510"}', '', '', '8ab4b316c7f2244af70689fd424fa5b10bb51dac', 0.426, '2023-12-01 18:45:12', '2023-12-01 18:45:12');

        return $this->state(function (array $attributes) {
            return [
                'log_type' => 'AUTH_LOGIN',
            ];
        });
    }

    private function getLocation(): string
    {
        return '{"ip": "127.0.0.0", "lat": 41.31, "lon": -72.92, "city": "New Haven", "state": "CT", "cached": false, "country": "United States", "default": true, "currency": "USD", "iso_code": "US", "timezone": "America/New_York", "continent": "NA", "state_name": "Connecticut", "postal_code": "06510"}';
    }
}
