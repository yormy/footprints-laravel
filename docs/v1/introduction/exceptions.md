# Logging Exceptions

Update your main exception handler
and throw an event in the report function
```
use Yormy\FootprintsLaravel\Observers\Events\ExceptionEvent;

    public function report(Throwable $exception)
    {
        event(new ExceptionEvent($exception, request()));

        parent::report($exception);
    }
    
```
