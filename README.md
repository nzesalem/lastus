# Lastus

Easy status addition and management for your Eloquent models in Laravel.

<p>
<a href="https://travis-ci.org/nzesalem/lastus"><img src="https://travis-ci.org/nzesalem/lastus.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/nzesalem/lastus"><img src="https://poser.pugx.org/nzesalem/lastus/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/nzesalem/lastus"><img src="https://poser.pugx.org/nzesalem/lastus/license.svg" alt="License"></a>
</p>

## What is lastus/status?

Consider you are building a forum app, you would typically have a `User` model/class. A `User` can be in different states or have different statuses at different points in time. For example when a user first registers for the forum you may want his/her status to be `unverified` indicating that the user have not verified his/her email. When the user verifies his/her email, he/she may become `active`. If the user violates one or more of the forum rules, he/she may become `suspended`, and so on.

**Lastus** package for Laravel 5 aims to handle all of this for you automatically, with minimal configuration.

* Install the package via Composer:

    ```sh
    $ composer require nzesalem/lastus
    ```

    The package will automatically register itself with Laravel 5.5 and later.

## Database migrations

Your database tables should have a `status` column defined as the `tinyInteger` type. **Lastus** automatically adds this column to your `users` table (if it doesn't already exist) when you run the `php artisan migrate` command after adding **Lastus** to your project.

You should manually generate migrations for other of your tables as needed. Below is an example of how you would add the `status` column to a `posts` table.

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

Your models should use the `LastusTrait` and define a `STATUSES` array constant.

The keys of the `STATUSES` array should be in all caps and multiple words should be separated with underscore. Ideally it should follow the same naming rules and convention as [PHP constants](http://php.net/manual/en/language.constants.php).

The values of the `STATUSES` array can be any number within the `TINYINT` range.

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Nzesalem\Lastus\Traits\LastusTrait;

class User extends Authenticatable
{
    use LastusTrait;

    // Key value pair of the statuses you want your model to have
    const STATUSES = [
        'UNVERIFIED' => 0,
        'ACTIVE' => 1,
        'SUSPENDED' => 2,
        'BLOCKED' => 3,
        'PENDING_APPROVAL' => 7,
    ];

    //...
}

```

And that's it ...

## Usage

When saving models, just set its status property to one of the keys you have defined above, but in all lower case, multiple words should be separated by a hyphen. E.g `PENDING_APPROVAL` becomes `pending-approval`. This is the format you will use most of the time when working with statuses.

```php
$user = User::create([
    'name' => 'Salem Nzeukwu',
    'email' => 'email@domain.com',
    'password' => bcrypt('secret'),
    'status' => 'active', // This will be saved as '1' in the database
]);
```

Retrieving a model status:

```php
echo $user->status; // This prints 'active'

// Sometime later
$user->status = 'pending-approval';
$user->save();

echo $user->status; // This now prints 'pending-approval'
```

You can get the status code whenever you need it. For example, status key strings will not work if you try to perform raw queries with them. So, in those cases you need the status codes instead:

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
    'status' => User::getStatusCode('suspended'),
]);
// Raw select query
$user = User::whereRaw('status = ' . User::getStatusCode('suspended'))->first();

$user->status == 'suspended' // true
```

Getting all the defined statuses for a given model is also easy as the snippet below. We get all the defined statuses for the `User` model and display them in a select element:

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

### Blade templates

You can use the `@status()` blade directive to control the visibility of elements based on the status of the currently logged in user:

```php
@status('active')
    <p>This is only visible to users with an 'active' status.</p>
@endstatus
```

### Middleware

You can use a middleware to filter routes and route groups by status:

```php
Route::group(['prefix' => 'dashboard', 'middleware' => ['status:active']], function() {
    Route::get('/', 'DashboardController@index');
});
```

## License

[Lastus](https://github.com/nzesalem/lastus) is released under the [MIT License](LICENSE).
