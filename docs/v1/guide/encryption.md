# Encryption

It is a good practice to encrypt sensitive information.

Create a new Footprint class that extends from ```Yormy\FootprintsLaravel\Models\Footprint```

```
use Yormy\FootprintsLaravel\Models\Footprint as BaseFootprint;

class Footprint extends BaseFootprint
{
    use EncryptModelTrait;

    // These fields should be encrypted
    protected $encrypts = [
        'data',
        'model_old',
        'model_changes',
        'ip_address',
        'payload',
        'response',
        'custom_cookies',
    ];
}
```

And in that class use your encryption provider
