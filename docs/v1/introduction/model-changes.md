# Model changes
To record changes to your model you need to use the Footprints trait

```
use Yormy\FootprintsLaravel\Traits\FootprintsTrait;

class MyModel 
{
    use FootprintsTrait;
```

## Filtering
By default it logs all the changed fields to the database. If you only want specific fields then create a function
```getFootprintsFields``` that returns the fields you want to log if changed

```
    public function getFootprintsFields(): array
    {
        return ['first_name'];
    }
```
