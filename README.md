# Laravel Pretty Pagination

Pretty pagination URLs for Laravel routes.

Instead of query-string URLs such as `/users?page=2`, this package generates
URLs like `/users/page/2` and registers the matching pagination route for you.

## Based on

This package is based on the original
[`ctsoft/laravel-pretty-pagination`](https://github.com/ctsoft-de/laravel-pretty-pagination)
package by CTSoft. It keeps the original package's functionality and namespace
while continuing development under the `romansh/laravel-pretty-pagination`
package name.

## Requirements

- PHP 7.2 or newer
- Laravel 6 through 12

## Installation

Install the package with Composer:

```bash
composer require romansh/laravel-pretty-pagination
```

The service provider is registered automatically through Laravel package
discovery.

## Usage

Give the route a name and call the `paginate` macro:

```php
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index'])
    ->name('users')
    ->paginate();
```

The route above supports:

```text
/users
/users/page/2
```

In the controller, use Laravel's paginator as usual:

```php
use App\Models\User;

public function index()
{
    return view('users.index', [
        'users' => User::query()->paginate(15),
    ]);
}
```

Pagination links preserve appended query parameters and fragments through the
normal Laravel paginator API.

## Custom page prefix

Pass a custom prefix as the first argument:

```php
Route::get('/users', [UserController::class, 'index'])
    ->name('users')
    ->paginate('p');
```

This generates URLs such as `/users/p/2`.

To omit the prefix entirely, pass `null`:

```php
Route::get('/users', [UserController::class, 'index'])
    ->name('users')
    ->paginate(null);
```

This generates URLs such as `/users/2`.

## Trailing slashes

Pass `true` as the second argument to add a trailing slash to generated
pagination URLs:

```php
Route::get('/users', [UserController::class, 'index'])
    ->name('users')
    ->paginate('page', true);
```

The generated URL is `/users/page/2/`. The default is `false`.

## Notes

- The route must have a name.
- The `paginate()` macro should be called last in the route definition.
- The package uses Laravel's standard pagination views and paginator methods.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## Support

Please report bugs and feature requests through the
[GitHub issue tracker](https://github.com/romansh/laravel-pretty-pagination/issues).
