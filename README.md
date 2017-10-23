# Lastus

<p>
<a href="https://travis-ci.org/nzesalem/lastus"><img src="https://travis-ci.org/nzesalem/lastus.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/nzesalem/lastus"><img src="https://poser.pugx.org/nzesalem/lastus/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/nzesalem/lastus"><img src="https://poser.pugx.org/nzesalem/lastus/license.svg" alt="License"></a>
</p>

Easy status addition and management for your Eloquent models in Laravel.

**Lastus** package for Laravel 5 aims to handle all of your Eloquent model statuses for you 
automatically, with minimal configuration.

1. Install the package via Composer:

    ```sh
    $ composer require nzesalem/lastus
    ```

    The package will automatically register itself with Laravel 5.5.

## Database migrations

Your database tables should have a `status` field defined as the `tinyInteger` type. **Lastus** automatically adds this field to your `users` table (if it doesn't already exist) when you run the `php artisan migrate` command after adding **Lastus** to your project.

You should manually generate migrations for other of your tables as needed. Below is an example of how you would add the `status` field to a `posts` table.

First run:

```sh
$ php artisan make:migration add_status_field_to_posts_table --table=posts
```
Then edit the generated migration file like so (usually located at `database/migrations`):

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldToPostsTable extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            //...
            $table->tinyInteger('status')->default(0);
        });
        
        //...
    }
}
```
And finally run:
```sh
$ php artisan migrate
```

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

So, That's it ...

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

Retrieving a model status:

```php
echo $user->status; // This returns `active`
```

You can get the status code whenever you need it. For example, status strings will not work if you try to perform raw queries with them. So, in those cases you need the status codes instead:

```php
$now = Carbon::now();

// Raw insert query
DB::table('users')->insert([
    'name' => 'Firstname Lastname',
    'email' => 'fake@example.com',
    'password' => bcrypt('secret'),
    'created_at' => $now,
    'updated_at' => $now,
    // Get the status code.
    'status' => User::statusCode('suspended'),
]);
// Raw select query
$user = User::whereRaw('status = ' . User::statusCode('suspended'))->first();

$user->status == 'suspended' // true
```

Getting all the defined statuses for a given model is also as easy. The example below gets all the defined statuses for the `User` model and displays them in an select input:

```php
<select id="status" class="form-control" name="status" required>
    <option>Select a status</option>
    @foreach (App\User::statuses() as $status)
    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
    @endforeach
</select>
```

Or you can use the `Lastus` facade:

```php
print_r(Lastus::statuses(App\User::class));
```

## License

[Lastus](https://github.com/nzesalem/lastus) is released under the [MIT License](LICENSE).