# Blocky Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 0.0.1 - 2020-06-03
### Added
- Initial release

## 0.0.2 - 2020-06-03
### Added
- Cleaned up code and fixed some code style issues

## 0.1.0 - 2020-06-05
### Added
- Added `{% blocks %}` Twig tag to simplify templating

### Changed
- Moved the main block parser functionality so that it is now available at `Blocky::$plugin->parseBlocks()`
  - This is consumed by the new `{% blocks %}` Twig tag and `{% craft.blocky.blockparser() %}`
