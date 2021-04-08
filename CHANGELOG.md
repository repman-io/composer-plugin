# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

On next release:
- [ ] update src/Repman.php (VERSION)

## [1.1.1] - 2021-04-08
### Fixed
- Using int for priority in event subscriber - Avoid PHP 8 Runtime exception (#14 thanks @pedro-stanaka)

## [1.1.0] - 2021-03-18
### Added
- Allowing installation of plugin from PHP 7.2+ (incl 8.0) (#12 thanks @pedro-stanaka)

## [1.0.0] - 2020-11-13
### Added
- support for Composer V2

### Removed
- support for Composer V1 (older version of this plugin still supports composer v1)

## [0.1.3] - 2020-04-16
### Changed
- minimum php version downgraded to 7.2

## [0.1.2] - 2020-04-08
### Added
- set notification url for packages to collect downloads data 

## [0.1.1] - 2020-03-16
### Fixed
- warning with non packagist packages, now will be skipped

## [0.1.0] - 2020-03-15
### First release :tada:
- fetch packages from repman.io proxy
- no configuration required
- faster and global CDN for all packagist.org packages
- speed up build by 97%
- works with your local Repman instance
