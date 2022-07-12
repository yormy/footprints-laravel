# footsteps

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yormy/laravel-footsteps.svg?style=flat-square)](https://packagist.org/packages/yormy/laravel-footsteps)
[![Total Downloads](https://img.shields.io/packagist/dt/yormy/laravel-footsteps.svg?style=flat-square)](https://packagist.org/packages/yormy/laravel-footsteps)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/facade/ignition/run-php-tests?label=Tests)
![Alt text](./coverage.svg)

# Goal
This package allows you to track a users journey through your system.
* Login/Logout Events
* Model edit events
* page visits








## Installation

You can install the package via composer:

```bash
composer require yormy/laravel-footsteps
```

### Model changes tracking
add to your model 
```use Footsteps;```


### Response tracking
To allow response tracking, and response timing tracking you need to do the following

- add to your app http\kernel
  ```use Yormy\LaravelFootsteps\Http\Middleware\AddTracking;```

Add the add tracking middleware to the beginning of your request
```
    protected $middleware = [
        AddTracking::class,
        ...
```


## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Yormy](https://gitlab.com/yormy)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
