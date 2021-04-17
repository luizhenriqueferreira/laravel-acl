# Laravel Acl package v1.0

Laravel Acl is a PHP package for Laravel Framework, used for manipulation of access control list. Package is providing an easier way to control roles and permissions of users on your site.

## Requirements

- PHP >=7.0

## Install

1) Type next command in your terminal:

```bash
composer require luizhenriqueferreira/LaravelAcl
```

2) Add the service provider to your config/app.php file in section providers:

> Laravel 5.5 uses Package Auto-Discovery, so does not require you to manually add the ServiceProvider.

```php
'providers' => [
    // ...
    LuizHenriqueFerreira\LaravelAcl\LaravelAclServiceProvider::class,
    // ...
],
```

3) Run the migrations:

```bash
php artisan migrate
```

## Usage

### Use the following traits on your User model:

```php
// ...

use LuizHenriqueFerreira\LaravelAcl\Models\Traits\HasRoles;
 
class User extends Authenticatable
{
    use HasRoles;
    
    // ... Your User Model Code
}
```

### Using in code

Check role
```php
if ($user->hasRoles'('admin')) {
    // User is admin
}
// or
if ($user->hasRoles('admin', 'writer')) {
    // User is admin or writer
}
// or
if ($user->hasRoles(['admin', 'writer'])) {
    // User is admin or writer
}
```

Attach role 
```php
$user->attachRoles(1);

//or
$user->attachRoles('admin');

//or
$user->attachRoles(Role::find(1));

//or
$user->attachRoles(1, 2);

//or
$user->attachRoles('admin', 'writer');

//or
$user->attachRoles(Role::find(1), Role::find(2));

//or
$user->attachRoles(1, 'writer', Role::find(3));

//or
$user->attachRoles([1]);

//or
$user->attachRoles(['admin']);

//or
$user->attachRoles([Role::find(1)]);

//or
$user->attachRoles([1, 2]);

//or
$user->attachRoles(['admin', 'writer']);

//or
$user->attachRoles([Role::find(1), Role::find(2)]);

//or
$user->attachRoles([1, 'writer', Role::find(3)]);

```

The same function, Detach role
```php
$user->detachRoles('writer');
// ...
$user->detachRoles(2, 'writer', Role::find(2));
// ...
$user->detachRoles([2, 'writer', Role::find(2)]);
```

Clear all roles
```php
$user->detachRoles();
```

Check permission
```php
if ($user->hasPermissions('create-post')) {
    // User has permission "create post"
}
// or
if ($user->hasPermissions('create-post', 'update-post')) {
    // User has permission "create post" or "update post"
}
// or
if ($user->hasPermissions(['create-post', 'update-post'])) {
    // User has permission "create post" or "update post"
}
```

Attach permissions
```php
$role->attachPermissions(1);

//or
$role->attachPermissions('create-post');

//or
$role->attachPermissions(Permission::find(1));

//or
$role->attachPermissions(1, 2);

//or
$role->attachPermissions('create-post', 'update-post');

//or
$role->attachPermissions(Permission::find(1), Permission::find(2));

//or
$role->attachPermissions(1, 'update-post', Permission::find(3));

//or
$role->attachPermissions([1]);

//or
$role->attachPermissions(['create-post']);

//or
$role->attachPermissions([Permission::find(1)]);

//or
$role->attachPermissions([1, 2]);

//or
$role->attachPermissions(['create-post', 'update-post']);

//or
$role->attachPermissions([Permission::find(1), Permission::find(2)]);

//or
$role->attachPermissions([1, 'update-post', Permission::find(3)]);

```

The same function, Detach permissions
```php
$role->detachPermissions('create-post');
// ...
$role->detachPermissions(1, 'update-post', Permission::find(3));
// ...
$role->detachPermissions([1, 'update-post', Permission::find(3)]);
```

Clear all permissions
```php
$role->detachPermissions();
```

See the code for more information... =)


### Using blade directives

You also can use directives to verify the currently logged in user has any roles or permissions.

Check roles:

 ```blade
 @hasroles('admin')
    <!-- User has role admin -->
 @elsehasrole('writer')   
    <!-- User has role writer -->
    <!-- ... -->
 @else
    <!-- User with other roles -->
 @endrole
 ```

or check more roles in one directive:

```blade
 @hasroles(['admin', 'writer'])
    <!-- User has next roles: admin, writer -->
 @endhasrole
```

Check permissions:

```blade
@can('create-post')
    <!-- User can create post -->
@elsecan('edit-post')
    <!-- User can edit post  -->
@endcan
```

### Using middlewares

You can use role middleware for check access to some routes

```php
Route::middleware(['role:admin'])->group(function() {
    
    // Only for user with role admin
    Route::get('/admin', function() {
        // some code
    });

});
```

also you can use permission middleware

```php
Route::middleware(['permission:create-post'])->group(function() {
    
    // Only for user with permission create post
    Route::get('/admin/post', function() {
        // some code
    });
    
});
```

or use role and permission middleware together

```php
Route::middleware(['role:admin,moderator', 'permission:remove-post'])->group(function() {
    
    // Only for user with role moderator and with permission create post
    Route::get('/admin/post/remove', function() {
        // some code
    });
    
});
```

## License

Laravel Acl package is licensed under the [MIT License](http://opensource.org/licenses/MIT).
