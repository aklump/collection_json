# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.9.0] - 2023-01-24

### Changed

- Composer is now autoloading the remaining loft-php-lib classes (only 2)
- Updated web_package configuration.

### Removed

- All non-used aklump/loft-php-lib code

## [0.8.0] - 2015-11-27

### Changed

- Corrected misspelling in the name of the Interface from ContentTypeTranslaterInterface to ContentTypeTranslatorInterface. You will need to update any references to this interface name.

## [0.5.0] - 2014-05-15

### Changed

- The order of arguments to the _Link_ object has changed, please update your scripts to reflect the new order which is `__construct($href, $rel, $name = '', $render = 'link', $prompt = '')`
  
