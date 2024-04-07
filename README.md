# Footprints

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yormy/footprints-laravel.svg?style=flat-square)](https://packagist.org/packages/yormy/footprints-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/yormy/footprints-laravel.svg?style=flat-square)](https://packagist.org/packages/yormy/footprints-laravel)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/facade/ignition/run-php-tests?label=Tests)
![Alt text](./coverage.svg)

# Goal
This package allows you to track a users journey through your system.
* Login/Logout Events
* Model edit events
* page visits


## Model Pruning
in your footprints config specify the number of days on that needs to be kept
The prune runs daily to check if items need to be deleted


## Installation

You can install the package via composer:

```bash
composer require yormy/footprints-laravel
```

### Model changes tracking
add to your model 
```use Footprints;```

## Exception logging
Exceptions can be logged
add tyo your Exceptions/handler.php
```
use use Yormy\FootprintsLaravel\Observers\Events\ExceptionEvent;

    public function report(Throwable $exception)
    {
        event(new ExceptionEvent($exception, request()));
        ...
    }
```

### Response tracking
To allow response tracking, and response timing tracking you need to do the following
- add to your app http\kernel

Add the add tracking middleware to the beginning of your request
```
    use Yormy\FootprintsLaravel\Http\Middleware\AddTracking;

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
