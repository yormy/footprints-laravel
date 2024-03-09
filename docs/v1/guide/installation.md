# Installation

Promocodes can be installed through composer
```bash
composer require yormy/footprints-laravel
```

# Preparing the database

You need to run the migrations
```bash
php artisan migrate
```


# Kernel changes

By adding ```AddTracking``` every request will get a new identifier that makes tracking accross the entire request lifecycle easier
```
use Yormy\FootprintsLaravel\Http\Middleware\AddTracking;

protected $middleware = [
    AddTracking::class,
```
