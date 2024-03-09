# Basic setup
installation

<!--@include: ../../definitions.md-->



http/Kernel.php
Add the ```AddTracking``` middleware to your kernel.
This will add a unique requestId to track the user in their journey

It will also inject the cookie ```browser_fingerprint_cookie_name``` in the request for tracking

```
    protected $middleware = [
        AddTracking::class,
        ... other middleware
```

Browser fingerprinting
With javascript you can create a hash of the browser setup. This has is a unique way of tracking visitors.
When you set this in the cookie, then the backend can track this browser


Impersonation,
Tracking if an admin impersonates a user and what do they do.

