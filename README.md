# XenForo [bd] Api Provider for Laravel Socialite

## Installation and config
Install Larvel Socialite (see here: https://github.com/laravel/socialite/blob/2.0/readme.md)

Install the [bd] Api to your XenForo installation (see here: https://github.com/xfrocks/bdApi)

Install the XenForo [bd] Api socialite provider

```
composer require sas1024/socialite-xenforo-bdapi
```

## Add the Event and Listener
Add SocialiteProviders\Manager\SocialiteWasCalled::class event to your listen[] array in <app_name>/Providers/EventServiceProvider like this:
```php
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        SocialiteWasCalled::class => [
            'Sas1024\Socialite\XenForo\XenForoExtendSocialite@handle',
        ],
    ];
```

## Services Array and .env:
Add to config/services.php:
```php
    'xenforo' => [
        'client_id' => env('XENFORO_CLIENT_ID'),
        'client_secret' => env('XENFORO_CLIENT_SECRET'),
        'redirect' => env('XENFORO_CALLBACK_URL'),
        'xenforo_url' => env('XENFORO_URL'),
    ],
```
Append provider values to your .env file: **Note: Add both public and secret keys!**
```
XENFORO_CLIENT_ID=
XENFORO_CLIENT_SECRET=
XENFORO_CALLBACK_URL=
XENFORO_URL=
```

Example:
```
XENFORO_CLIENT_ID=kaupfd1fscx
XENFORO_CLIENT_SECRET=oAnW4NUK1iHLl58PjpQI
XENFORO_CALLBACK_URL=${APP_URL}/login/xenforo/callback
XENFORO_URL=http://xenforo-with-bd.local/forum/api/
```


## Usage

```php
<?php

namespace App\Http\Controllers\Auth;

use Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the XenForo [bd] Api authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('xenforo')->redirect();
    }

    /**
     * Obtain the user information from XenForo [bd] Api.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('xenforo')->user();
    }
}
```

## Retrieving User Details

Once you have a user instance, you can grab a few more details about the user:

```php
$user = Socialite::driver('xenforo')->user();

$token = $user->token;
$expiresIn = $user->expiresIn;
$user->id;
$user->avatar;
$user->nickname;
$user->email;
```