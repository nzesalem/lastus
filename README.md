# Lastus

Easy status addition and management for your Eloquent models in Laravel.

**Lastus** package for Laravel 5 aims to handle all of your Eloquent model statuses for you 
automatically, with minimal configuration.

* Install the package via Composer:

    ```sh
    $ composer require nzesalem/lastus
    ```

    The package will automatically register itself with Laravel 5.5.

## Updating your Eloquent Models

Your models should use the `LastusTrait` and define a `STATUSES` array constant:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Nzesalem\Lastus\Traits\LastusTrait;

class User extends Authenticatable
{
    use LastusTrait;

    // Define the statuses you want your model to have
    const STATUSES = [
        'UNVERIFIED' => 0,
        'ACTIVE' => 1,
        'SUSPENDED' => 2,
        'BLOCKED' => 3,
    ];

    //...
}

```

**Lastus** stores statuses as **tinyInteger** type in the database.

And That's it ...

## Usage

Saving a model:

```php
$user = User::create([
    'name' => 'Salem Nzeukwu',
    'email' => 'email@domain.com',
    'password' => bcrypt('secret'),
    'status' => 'active', // This will be saved as `1` in the database
]);
```

Retrieving the status:

```php
echo $user->status; // This returns `active`
```

If you need to get the status code:

```php
echo User::statusCode('suspended'); // This returns `2`

// or

echo Lastus::statusCode(App\User::class, 'suspended');
```

Get all the defined statuses for a given model:

```php
print_r(User::statuses());
// This returns and array of statuses

// or

print_r(Lastus::statuses(App\User::class));
```

## License

[Lastus](https://github.com/Nzesalem/lastus) is released under the [MIT License](LICENSE).