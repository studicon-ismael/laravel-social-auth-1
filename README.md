# social-auth

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This package give ability to 
 * Sign In 
 * Register
 * link user account with social network
 
This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practises by being named the following.

```
bin/        
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require zfort/social-auth
```

Now add the service provider in config/app.php file:
```php
'providers' => [
    // ...
    ZFort\SocialAuth\SocialAuthServiceProvider::class,
];
```

You can publish the migration with:
```bash
$ php artisan vendor:publish --provider="ZFort\SocialAuth\SocialAuthServiceProvider" --tag="migrations"
```

The package assumes that your users table name is called "users". If this is not the case you should manually edit the published migration to use your custom table name.

After the migration has been published you can create the social_providers table for storing supported 
providers and social_user pivot table by running the migrations:
```bash
$ php artisan migrate
```

You can publish the config-file with:
```bash
$ php artisan vendor:publish --provider="ZFort\SocialAuth\SocialAuthServiceProvider" --tag="config"
```

Also you can publish and modify view templates with:
```bash
$ php artisan vendor:publish --provider="ZFort\SocialAuth\SocialAuthServiceProvider" --tag="views"
```

##### Add credetials to your project

File .env
```ini
FB_ID = <FacebookID>
FB_SECRET = <FacebookSecret>
FB_REDIRECT = <your.domain>/social/callback/facebook

GOOGLE_ID = <GoogleID>
GOOGLE_SECRET = <GoogleSecret>
GOOGLE_REDIRECT = <your.domain>/social/callback/google

GITHUB_ID = <GithubID>
GITHUB_SECRET = <GithubSecret>
GITHUB_REDIRECT = <your.domain>/social/callback/github
```

##### File config/services.php
```ini
    'facebook' => [
        'client_id'     => env('FB_ID'),
        'client_secret' => env('FB_SECRET'),
        'redirect'      => env('FB_REDIRECT')
    ],

    'google' => [
        'client_id'     => env('GOOGLE_ID'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT')
    ],
    
    'github' => [
        'client_id'     => env('GITHUB_ID'),
        'client_secret' => env('GITHUB_SECRET'),
        'redirect'      => env('GITHUB_REDIRECT')
    ]
```

##### Include social buttons into your templates
```php
 @include('social-auth::attach') // for authenticated user to attach another socials
 @include('social-auth::buttons') // for guests to login via
 // or for published views
 @include('vendor.social.buttons')
 @include('vendor.social.attach')
```

##### Add UserSocialite trait to your User model
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ZFort\SocialAuth\Traits\UserSocialite;

class User extends Model
{
    use UserSocialite;
   ...
}
```
##### Routes

If you need do some custom with social flow, you should define yourself controllers and 
put your custom url into routes file.

For example 
```php
Route::get('social/{social}', 'Auth\SocialAuthController@getAccount');
Route::get('social/callback/{social}', 'Auth\SocialAuthController@callback');
Route::get('social/unlink/{social}', 'SocialAuthController@deleteAccount');
```

In case if you no need any special functionality ypu can use our default controllers

##### Customize for your project

###### Custom User Model
User model we takes from the  config('auth.users.model');

###### User Fields Mapping
Trait UserSocial contains method ```mapSocialData``` for mapping social fields for user model
If you need yuo can redefine this method for your preferences project in your UserModel 

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email developer@zfort.com instead of using the issue tracker.

## Credits

- [zFort][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/zfort/social-auth.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/zfort/social-auth/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/zfort/social-auth.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/zfort/social-auth.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/zfort/social-auth.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/zfort/social-auth
[link-travis]: https://travis-ci.org/zfort/social-auth
[link-scrutinizer]: https://scrutinizer-ci.com/g/zfort/social-auth/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/zfort/social-auth
[link-downloads]: https://packagist.org/packages/zfort/social-auth
[link-author]: https://github.com/zfort
[link-contributors]: ../../contributors
