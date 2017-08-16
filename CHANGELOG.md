# Changelog

All Notable changes to `laravel-social-auth` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 2.0.0 - 2017-08-16

### Added
- Console command for creating social providers in the database `artisan social-auth:add`
- Added _laravel-_ prefix to the package name

### Changed
- Added _override_scopes_ field to mass assignment
- Moved config and route files from resources folder to root
- Routes now registers by `loadRoutesFrom($dir)` method

## 1.0.1 - 2017-08-02

### Added
- Ability to set additional scopes and parameters
- Ability to user stateless login
- Configuration property to set user model
- Unit tests for events

### Fixed
- Setting table name for SocialProvider model
