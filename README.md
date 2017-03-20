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
Full documentation here [Bitbucket repository](https://bibucket.org).

##### Add credetials to your project

File .env
```ini

FB_ID =<FacebookID>
FB_SECRET =<FacebookSecret>
FB_REDIRECT =<your domain>/social/handle/facebook

TW_ID =<TwitterID>
TW_SECRET =<TwitterSecret>
TW_REDIRECT =<your domain>/social/handle/twitter

GOOGLE_ID =<GoogleID>
GOOGLE_SECRET =<GoogleSecret>
GOOGLE_REDIRECT =<your domain>/social/handle/google

GITHUB_ID =<GithubID>
GITHUB_SECRET =<GithubSecret>
GITHUB_REDIRECT =<your domain>/social/handle/github

```

#####File config/services.php
```ini
    'facebook' => [
        'client_id'     => env('FB_ID'),
        'client_secret' => env('FB_SECRET'),
        'redirect'      => env('FB_REDIRECT')
    ],

    'twitter' => [
        'client_id'     => env('TW_ID'),
        'client_secret' => env('TW_SECRET'),
        'redirect'      => env('TW_REDIRECT')
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

#####Include social buttons to your templates
```php
 <p class="or-social">Or Use Social Login</p>

 @include('vendor.social.buttons')
```

#####Add UserSocialite trait to your User model
```php

use Social\Traits\UserSocialite;

class User { ...

use  UserSocialite;
.
.
.
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

##### Migrations & Seeds

Run migration and seed.
```php
php artisan Social:migrate
php artisan Social:seed

```
 
##### Customize for your project

###### Custom User Model
User model we takes from the  config('auth.users.model');

###### User Fields Mapping
Trait UserSocial contains method mapSocialFields for mapping cisoal fields for user model
If you need yuo can redefine this method for ypur project in your UserModel 

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
