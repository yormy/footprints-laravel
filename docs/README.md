# Footprints

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
