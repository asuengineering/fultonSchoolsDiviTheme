# Change Log
All notable changes to this project will be documented in this file, formatted via [this recommendation](http://keepachangelog.com/).

## [1.3.1.2] - 2016-12-12
### Fixed
- Plugin name to correctly indicate Lite for Lite release

## [1.3.1.1] - 2016-12-12
### Fixed
- Error with 1.3.1 Lite release

## [1.3.1] - 2016-12-08
### Added
- Dropdown Items payment field
- Smart Tags for author ID, email, and name
- Carbon Copy (CC) support for form notifications; enable in WPForms Settings

### Changed
- Form data and fields publicly accessible in email class

### Fixed
- Field duplication issues
- Total payment field error when only using Multiple Items payment field
- TinyMCE "Add Form" button not opening modal with dynamic TinyMCE instances
- Email formatting issues when using plain text formatting
- Number field validation tripping when number submitted is zero
- reCAPTCHA validation passing when reCAPTCHA left blank
- Dropdown field size not reflecting in builder
- File Upload field offering Size option but not supported (option removed)
- File uploads configured to go to the media library not working
- Server-side file upload errors not displaying correct due to a type

## [1.3.0.1] - 2016-11-10
### Added
- Context usage param to `wpforms_html_field_value` filter
- New filter, `wpforms_plaintext_field_value`, for plaintext email values

### Fixed
- Bug with date picker limiting date selection to current year
- PHP notice when uploading non-media library files
- Issue with form title/description being toggled with shortcode
- Secured `target=_blank` usage

## [1.3.0] - 2016-10-24
### Added
- Email field confirmantion
- Password field confirmation
- Support for Visual Composer
- Additional date field type, dropdowns
- Field class to force elements to full-width on mobile devices, `wpforms-mobile-full`

### Changed
- Datepicker library
- Timepicker library
- Placeholders are added/updated in real-time for Dropdown fields in the form builder
- Add empty value to select element placeholders when displaying form for better markup validation

### Fixed
- Multiple instances of reCAPTCHA on a page not correctly loading
- Field choice defaults not restoring in form builder
- Field alignment issues in the form builder when dragging field more than once
- PHP fatal erroring if form notification email address provided is not valid upon sending
- Date field Datepicker allows empty submit when marked as required
- Compatibility issuses when network activated on a Multisite install

## [1.2.9.1] - 2016-10-07
### Fixed
- Compatibility issue with Stripe addon

## [1.2.9] - 2016-10-04
### Added
- Individual fields can be duplicated in the form builder

### Changed
- How data is stored for fields using Dynanic Choices
- File Upload contents can (optionally) be stored in the WordPress media library

### Fixed
- CSV exports not handling new lines well
- Global assets setting causing errors in some cases
- Writing setting ("correct invalidly nested XHTML") breaking forms containing HTML
- Forms being displayed/included on the native WordPress Export page
- Dynamic Choices erroring when used with Post Types
- Form labels including blank IDs

## [1.2.8.1] - 2016-09-19
### Fixed
- Form javascript email validation being too strict (introducted in 1.2.8)
- Provider sub-group IDs not correctly stored with connection information

## [1.2.8] - 2016-09-15
### Added
- Dynamic choice feature for Dropdown, Multiple Choice, and Checkbox fields

### Changed
- Loading order of templates and field classes - moved to `init`
- Form javascript email validation requires domain TLD to pass
- File Upload file size setting now allows non-whole numbers, eg 0.5

### Fixed
- HTML email notification templates uses site locale text-direction
- Javascript in the form builder conflicting with certain locales
- Datepicker overflowing off screen on small devices

## [1.2.7] - 2016-08-31
### Added
- Store intial plugin activation date
- Input mask for US zip code within Address field, supports both 5 and 9 digit formats
- Duplicate form submit protection

### Changed
- Entry dates includes GMT offset defined in WordPress settings
- Entry export now includes both local and GMT dates
- Improved Address field to allow for new schemes/formats to be create and better customizations

### Fixed
- Provider conditonal logic processing when using checkbox field
- Strip slashes from entry data before processing
- Single Item field price not live updating inside form builder

## [1.2.6] - 2016-08-24
### Added
- Expanded support for additional currencies
- Display payment status and total column on entry list screen as allow sorting with these new columns
- Display payment details on single entry screen
- Miscellaneous internal improvements

### Changed
- Added month/year selector to date picker for better accessibility
- Payment validation methods

### Fixed
- Incorrectly named variables in the front-end javascript preventing features from properly being extendable

## [1.2.5] - 2016-08-03
### Added
- Setting for Email template background color
- Form setting for form wrapper CSS class

### Changed
- Multiple Payment field stores Choice label text
- reCAPTCHA tweaks and added filter
- Improved IP detection

### Fixed
- Mapped select fields in builder triggering JS error

## [1.2.4] - 2016-07-07
### Added
- Form import and exporting
- Additional logging and error reporting

### Changed
- Footer asset detection priority, for improved capatibility with other services
- Refactored and refined front-end javascript

### Fixed
- Restored form notification defaults for Blank template
- Default field validation considered 0 value as empty
- Rogue PHP notices

## [1.2.3] - 2016-06-23
### Added
- Multiple form notifications capability
- Form notification message setting
- Form notification conditional logic (via add-on)
- Additional Smart Tags available inside Form Settings panels
- Process Smart Tags inside form confirmation messages and URLs
- Hide WPForms Preview page from WordPress dashboard
- System Details tab to WPForms Settings, to display debug information, etc

### Changed
- Center align text inside page break navigation buttons
- Scroll to top most validation error when using form pagination
- Many form builder javascript improvements
- Improved internal logging and debugging tools
- Don't show Page Break fields in Entry Tables

### Fixed
- Form select inside modal window overflowing when a form exists with a long title
- Large forms not always saving because of max_input_vars PHP setting
- Entry Read/Unread count incorrect after AJAX toggle
- Single Payment field failed validation if configured for user input and amount contained a comma

## [1.2.2.1] - 2016-06-13
### Fixed
- Entry ID not always correctly passing to hooks

## [1.2.2] - 2016-06-03
### Added
- Page Break navigation buttons now have an alignment setting
- Page Break previous navigation button is togglable and defaults to off

### Changed
- Improved styling of Page Break fields in the builder
- Choice Layouts now use flexbox instead of CSS columns for better rendering

### Fixed
- Class name typo in a CSS column class introduced with 1.2.1
- PHP notice on Entries page when there are no forms

## [1.2.1] - 2016-05-30
### Added
- Drag and drop field buttons - simply drag the desired field to the form!
- Page Break progress indicator themes, with optional page titles
- Choice Layout option for Checkboxes and Multiple Choice fields (under Advanced Options)
- Full and expanded column class/grid support

### Changed
- Refactored Page Break field, fully backwards compatible with previous version
- Page Break navigation buttons with without a label do not display
- Refactored CSS column classes, previous classes are deprecated
- Improved field and column gutter consistency

### Fixed
- Form ending with column classes not closing correctly
- reCAPTCHA button overlaying submit button preventing it from being clicked

## [1.2] - 2016-05-19
### Added
- Column classes for Checkbox and Multiple choice inputs

### Changed
- Improved file upload text format inside entry tables

### Fixed
- Removed nonce verification
- Issue with Address fields not processing correctly when using international format

## [1.1.9.1] - 2016-05-06
### Fixed
- Payment calculations incorrect with large values

## [1.1.9] - 2016-05-06
### Added
- Form preview
- Other small misc. updates

### Changed
- reCAPTCHA settings description to include link to how-to article
- Some fields did not have the correct (unique) CSS ID, this has been corrected, which means custom styling may need to be adjusted
- Form notification settings hide if set to Off

### Fixed
- Issue with submit button position when form ends with columns classes
- PHP warnings inside the form builder

## [1.1.8] - 2016-04-29
### Added
- "WPForm" to new-content admin bar menu item

### Changed
- Removed "New" field name prefix
- Moved email related settings into email settings group

### Fixed
- Incorrect i18n strings
- Load order causing add-on update conflicts

## [1.1.7] - 2016-04-26
### Added
- Smart Tag for Dropdown/Multiple choice raw values, allowing for conditional email addres notifications ([link](https://wpforms.com/docs/how-to-create-conditional-form-notifications-in-wpforms/))
- HTML/Code field Conditional Logic support
- HTML/Code field CSS class support
- Three column CSS field classes ([link](https://wpforms.com/docs/how-to-create-multi-column-form-layouts-in-wpforms/))
- Support for WordPress Zero Spam plugin (https://wordpress.org/plugins/zero-spam/)

### Changed
- Checkbox/Multiple Choice fields allow certain HTML to display in choice labels

### Fixed
- Issue when stacking fields with 2 column classes

## [1.1.6] - 2016-04-22
### Added
- Entry starring
- Entry read/unread tracking
- Entry filtering by stars/read state
- Entry notes
- Entry exports (csv) for all entries in a form

### Changed
- Improved entries table overview page
- Email Header Image setting description to include recommended sizing

### Fixed
- reCAPTCHA cutting off with full form theme
- Debug output from wpforms.js
- Conflict between confirmation action and filter

## [1.1.5] - 2016-04-15
### Added
- Print entry for single entries
- Export (CSV) for single entries
- Resend notifications for single entries
- Store user ID, IP address, and user agent for entries

### Changed
- Improved single entry page (more improvements soon!)
- HTML Email template footer text appearance

### Fixed
- Form builder textareas not displaying full width
- HTML emails not displaying correctly in Thunderbird

## [1.1.4] - 2016-04-12
### Added
- Form general setting for "Submit Button CSS Class"
- Duplicate forms from the Forms Overview page (All Forms)
- Suggestion form template

### Changed
- Improved error logging for providers, now writes to CPT error log
- Adjusted field display inside the Form Builder to better resemble full theme

### Fixed
- Firefox CSS issue in form base theme
- Don't allow inserting shortcode via modal if there are no forms
- Issue limiting Total field display amount

## [1.1.3] - 2016-04-06
### Added
- New class that handles sending/processing emails
- Form notification setting for "From Address", defaults to site administrator's email address
- HTML email template for sleek emails (enabled by default, see more below)
- General setting to configure email notification format
- General setting to optionally configure email notification header image

### Changed
- Default email notification format is now HTML, can go back to plain text format via option on WPForms > Settings page
- File Upload field now saves original file name
- Empty fields are no longer included in email notifications

### Fixed
- Various issues with File Upload field in different configurations
- Address field saving select values when empty
- Issue with Checkbox field when empty

## [1.1.2] - 2016-04-01
### Added
- Form option to scroll page to form after submit, defaults on for new forms

### Changed
- Revamped "Full" form theme to be more consistent across different themes, browsers, and devices
- Full theme and bare theme separated

### Fixed
- File upload required message when not set to required

## [1.1.1] - 2016-03-29
### Fixed
- Settings page typo
- Providers issue causing AJAX to fail

## [1.1] - 2016-03-28
### Added
- Credit Card payment field

### Changed
- CSS updates to improve compatibility

### Fixed
- PHP notices when saving plugin Settings

## [1.0.9] - 2016-03-25
### Changed
- Email field defaulting to Required

## [1.0.8] - 2016-03-24
### Fixed
- Name field setting always showing Required
- Debug function incorrectly requiring WP_DEBUG

## [1.0.7] - 2016-03-22
## Changed
- CSS tweaks

### Fixed
- Issue with File Upload field returning incorrect file URL
- Filter (wpforms_manage_cap) incorrectly named in some instances

## [1.0.6] - 2016-03-21
### Added
- Embed button inside the Form Builder
- Basic two column CSS class support
- French translation

## Changed
- Form names are no longer required, if no form name is provided the template name is used
- Inputmask script, for better broad device support
- Field specific assets are now conditionally loaded
- CSS tweaks for form display

### Fixed
- Issue with Date/Time field
- Issue Address field preventing Country select from hiding in some configurations
- Localization string errors

## [1.0.5] - 2016-03-18
### Added
- Pagination for Entries table

## Changed
- Checkboxes/Dropdown/Multiple Choice fields always show choice label value in e-mail notifications

### Fixed
- PHP notices inside the Form Builder
- Typo inside Form Builder tooltip

## [1.0.4.1] - 2016-03-17
### Added
- Check for TinyMCE in the builder before triggering TinyMCE save

### Fixed
- Sub labels showing when configured to hide
- Forms pagination number screen setting not saving
- Email notification setting always displaying "On"

## [1.0.4] - 2016-03-16
### Changed
- Improved marketing provider conditional logic
- Addons page [Lite]

### Fixed
- Variable assignment in the builder

## [1.0.3] - 2016-03-15
### Added
- Basic TinyMCE editor for form confirmation messages

### Changed
- Removed form ID from form overview table, ID still visible in shortcode column

### Fixed
- Checkbox/radio form elements alignment
- Quotation slashes in email notification text
- SSL verification preventing proper API calls on some servers

## [1.0.2] - 2016-03-13
### Added
- Widget to display form
- Function to display form, `wpforms_display( $form_id )`

## Changed
- Default notification settings for Contact form template
- Success message styling for full form theme

## [1.0.1] - 2016-03-12
### Added
- "From Name" and "Reply To" Setting>Notification fields
- Smart Tags feature to all Setting>Notification fields

## [1.0.0] - 2016-03-11
- Initial release