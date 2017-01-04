# Change Log
All notable changes to this project will be documented in this file, formatted via [this recommendation](http://keepachangelog.com/).

## [1.1.6] - 2016-12-08
### Added
- Support for Dropdown Items payment field

### Fixed
- Errors if conditional logic was enabled but incomplete

## [1.1.5] - 2016-10-24
### Changed
- Reset fields hidden by conditionals immediately instead of waiting for form submit

## [1.1.4] - 2016-10-04
### Changed
- Exclude fields configured to use dynamic choices

## [1.1.3] - 2016-08-31
### Changed
- Restrict operators available for non-text based fields

### Fixed
- Issue with front-end javascript incorrectly handling multiple rule validation
- Conditonal logic processing when using checkbox field

## [1.1.2] - 2016-06-24
### Added
- Support for Hidden fields
- Support for payment addons (Stripe and PayPayl standard)

### Changed
- Improved error logging

### Fixed
- Rogue PHP notices
- Javascript errors inside the builder

## [1.1.1] - 2016-06-24
### Fixed
- Front-end javascript issue trying to convert non-strings to lowercase

## [1.1.0] - 2016-06-23
### Added
- Conditional Logic for form notifications
- New comparisons: contains, does not contain, starts with, and ends with

### Changed
- Conditional Logic can no longer be applied to itself
- Many form builder javascript improvements

## [1.0.6] - 2016-05-19
### Fixed
- Error when encountering null value

## [1.0.5] - 2016-05-05
### Fixed
- In some use cases special characters could break processing

## [1.0.4] - 2016-04-29
### Fixed
- In some use cases "Hide" conditionals were not processing correctly

## [1.0.3] - 2016-04-26
### Fixed
- &amp; character preventing logic validation

## [1.0.2] - 2016-03-29
### Fixed
- PHP notices inside the builder

## [1.0.1] - 2016-03-16
### Added
- Support for Multiple Payments field

## [1.0.0] - 2016-03-11
- Initial release