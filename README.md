# Laravel Acl package v1.0

Laravel Acl is a PHP package for Laravel Framework, used for manipulation of access control list. Package is providing an easier way to control roles and permissions of users on your site.

## Requirements

- PHP >=7.0

## Install

1) Type next command in your terminal:

```bash
composer require LuizHenriqueBK/LaravelAcl
```

2) Add the service provider to your config/app.php file in section providers:

> Laravel 5.5 uses Package Auto-Discovery, so does not require you to manually add the ServiceProvider.

```php
'providers' => [
    // ...
    LuizHenriqueBK\LaravelAcl\LaravelAclServiceProvider::class,
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

use LuizHenriqueBK\LaravelAcl\Models\Traits\HasRoles;
 
class User extends Authenticatable
{
    use HasRoles;
    
    // ... Your User Model Code
}
```

### Using in code

Check role
```php
if ($user->hasRole('admin')) {
    // User is admin
}
// or
if ($user->hasRole('admin', 'writer')) {
    // User is admin or writer
}
// or
if ($user->hasRole(['admin', 'writer'])) {
    // User is admin or writer
}
```

Attach role 
```php
$user->attachRole(1);

//or
$user->attachRole('admin');

//or
$user->attachRole(Role::find(1));

//or
$user->attachRole(1, 2);

//or
$user->attachRole('admin', 'writer');

//or
$user->attachRole(Role::find(1), Role::find(2));

//or
$user->attachRole(1, 'writer', Role::find(3));

//or
$user->attachRole([1]);

//or
$user->attachRole(['admin']);

//or
$user->attachRole([Role::find(1)]);

//or
$user->attachRole([1, 2]);

//or
$user->attachRole(['admin', 'writer']);

//or
$user->attachRole([Role::find(1), Role::find(2)]);

//or
$user->attachRole([1, 'writer', Role::find(3)]);

```

The same function, Detach role
```php
$user->detachRole('writer');
// ...
$user->detachRole(2, 'writer', Role::find(2));
// ...
$user->detachRole([2, 'writer', Role::find(2)]);
```

Clear all roles
```php
$user->detachRole();
```

Check permission
```php
if ($user->hasPermission('create-post')) {
    // User has permission "create post"
}
// or
if ($user->hasPermission('create-post', 'update-post')) {
    // User has permission "create post" or "update post"
}
// or
if ($user->hasPermission(['create-post', 'update-post'])) {
    // User has permission "create post" or "update post"
}
```

Attach permissions
```php
$role->attachPermission(1);

//or
$role->attachPermission('create-post');

//or
$role->attachPermission(Permission::find(1));

//or
$role->attachPermission(1, 2);

//or
$role->attachPermission('create-post', 'update-post');

//or
$role->attachPermission(Permission::find(1), Permission::find(2));

//or
$role->attachPermission(1, 'update-post', Permission::find(3));

//or
$role->attachPermission([1]);

//or
$role->attachPermission(['create-post']);

//or
$role->attachPermission([Permission::find(1)]);

//or
$role->attachPermission([1, 2]);

//or
$role->attachPermission(['create-post', 'update-post']);

//or
$role->attachPermission([Permission::find(1), Permission::find(2)]);

//or
$role->attachPermission([1, 'update-post', Permission::find(3)]);

```

The same function, Detach permissions
```php
$role->detachPermission('create-post');
// ...
$role->detachPermission(1, 'update-post', Permission::find(3));
// ...
$role->detachPermission([1, 'update-post', Permission::find(3)]);
```

Clear all permissions
```php
$role->detachPermission();
```

See the code for more information... =)

### Commands for manipulation

#### Permissions

Create new permission
```bash
php artisan permission:add "create post"
```

Rename permission
```bash
php artisan permission:rename "create post" create.post
```

Remove permission
```bash
php artisan permission:remove "create post"
```

Show all permissions
```bash
php artisan permission:list
```

#### Roles

Create new role
```bash
php artisan role:add admin
```

Rename role
```bash
php artisan role:rename admin superuser
```

Remove role
```bash
php artisan role:remove admin
```

View all roles
```bash
php artisan role:list
```

Attach permissions to role
```bash
php artisan role:attach admin --id=2 --id=3 --name="create post"
```

Detach permissions from role
```bash
php artisan role:detach admin --id=3 --name="destroy user"
```

Clear all attached permissions
```bash
php artisan role:clear
```

View information about role and show all attached permissions
```bash
php artisan role:view admin
```

#### Users

Attach roles
```bash
php artisan user:role-attach 5 --id=2 --name=moderator
```

Detach roles
```bash
php artisan user:role-detach 5 --id=2 --name=admin
```

Detached all roles from user
```bash
php artisan user:role-clear
```

Attach permissions
```bash
php artisan user:permission-attach 5 --id=7 --name="remove comment"
```

Detach permissions
```bash
php artisan user:permission-detach 5 --id=2 --name="read secret post"
```

Detached all permission from user
```bash
php artisan user:permission-clear
```

View information about user, all attached roles and permissions
```bash
php artisan user:view 5
```
where 5 is ID of user.

### Using blade directives

You also can use directives to verify the currently logged in user has any roles or permissions.

Check roles:

 ```blade
 @role('admin')
    <!-- User has role admin -->
 @elserole('writer')   
    <!-- User has role writer -->
    <!-- ... -->
 @else
    <!-- User with other roles -->
 @endrole
 ```

or check more roles in one directive:

```blade
 @role(['admin', 'writer'])
    <!-- User has next roles: admin, writer -->
 @endrole
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
