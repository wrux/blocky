# Blocky Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this
project adheres to [Semantic Versioning](http://semver.org/).

## 1.0.1 - 2020-06-22

## Added

- Superlinter with Markdown support

## Updated

- Set minimum PHP version to 7.4.0.

## 1.0.0 - 2020-06-16

### Added

- Skip empty blocks in the blocks tag using the `skip empty` keyword
- Variables `context`, `template`, `type` are accessible inside the blocks tag
- PHPCS PSR2 rules
- PHPStan rules
- Github actions to test PHPCS and PHPStan

## 0.1.2 - 2020-07-07

### Changed

- Fixed `block.template` not accessible in the template because Twig was trying
  to access the protected `block.template` property instead of
  `block.getTemplate()`
- Fixed wrong order in CHANGELOG.md

## 0.1.1 - 2020-07-07

### Changed

- Typo in class name `InvalicBlockException`
- The class Blocky is not correctly importing the exception classes

### Removed

- Translation files, as these only contained exception translations
- All instances of `Craft::t()` in exceptions

## 0.1.0 - 2020-06-05

### Added

- Added `{% blocks %}` Twig tag to simplify templating

### Changed

- Moved the main block parser functionality so that it is now available at
  `Blocky::$plugin->parseBlocks()`
  - This is consumed by the new `{% blocks %}` Twig tag and
    `{% craft.blocky.blockparser() %}`

## 0.0.2 - 2020-06-03

### Added

- Cleaned up plugin code and fixed some code style issues

## 0.0.1 - 2020-06-03

### Added

- Initial release
