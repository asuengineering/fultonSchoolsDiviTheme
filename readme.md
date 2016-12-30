# Fulton Schools Divi Theme #

A child theme for [WordPress](http://wordpress.org) in use by various publications from the [Ira A. Fulton Schools of Engineering](http://engineering.asu.edu) at [Arizona State University](http://asu.edu). 

The theme has several enhancements that make it specific to the Fulton Schools of Engineering. It has been made public in the hopes that other organizations within ASU will benefit from the work as WordPress adoption on campus continues to increase.

## Requirements ##

The parent theme for the FSDT is the excellent [Divi Theme from Elegant Themes](http://www.elegantthemes.com/gallery/divi/). If you intend to use the theme to build your own WordPress site, a separate license for the Divi Theme is necessary. 

Please also note that this repository contains a child theme and not a stand-alone theme designed for use with [The Divi Builder](http://www.elegantthemes.com/plugins/divi-builder/) plugin also from Elegant Themes. 

## Enhancements ##

#### Version 1.8 (Balthasar) ####

Two new widgets for the super-footer were created with this release to accommodate the new Fulton Schools of Engineering logo and more closely conform to [ASU Brand Guide](https://brandguide.asu.edu/web-standards/enterprise/super-footer) standards.
* The new **ASU Engineering Footer Widget** automatically includes the new FSE endorsed logo, complete with link to the Engineering home page. No need to add it to the media library.
* The new **ASU Social Media Icons Footer Widget** centralizes the location of all URL entries for social media channels to one widget.

This release also depreciates two older widgets from an older FSDT release. See wp-admin/admin.php?page=asu_social_media_editor and wp-admin/admin.php?page=asu_social_media_editor within your install for more details.

Further improvements also include:
* Proper handling/styling of linked images and external links. 
* Improvements to the Divi Portfolio module to align the filter bar and other module elements to ASU Brand Standards.

> "Sigh no more, ladies, sigh no more,
 Men were deceivers ever,
 One foot in sea and one on shore,
 To one thing constant never."
-- *Balthasar, Much Ado About Nothing (882-885)*

#### Version 1.7 (Portia) ####

* Changes to Mega Menu formatting now includes a column header format for each column of links within the menu.
* Introduced new experimental "compact" formatting for sidebar elements. Reduces padding/elements within sidebars build within Divi. Adds differentiation among H3/H4/H5 elements used as headers within.
* Introduced GitHub Issue Tracking as a way to keep track of future enhancements. [Feedback always welcome](https://github.com/fsoe-asu/fultonSchoolsDiviTheme/issues).
* Nifty new documentation and version naming.
* Enables updates to the theme via [GihHub Updater](https://github.com/afragen/github-updater) plugin.

> "How far that little candle throws his beams! So shines a good deed in a weary world."
-- *Portia, The Merchant of Venice (2547)*

#### Version 1.6 (Costard) ####

Version 1.6 focused on the proper implementation of ASU Global Header and Footer assets. All assets are included with the the theme, and we continue to watch the ASU Web Standards page (and eventual repo) for changes and apply them as needed.

Much credit given to the (Global Institute of Sustainability)[https://github.com/gios-asu] and the associated authors of the [ASU Web Standards WordPress Theme](https://github.com/gios-asu/ASU-Web-Standards-Wordpress-Theme), from which most of the code for this release was borrowed.

> "I will not fight with a pole, like a northern man: 
I'll slash; I'll do it by the sword. I bepray you, 
let me borrow my arms again."
-- *Costard, Love's Labours' Lost (2633-2635)*

#### Version 1.5 ####

The following enhancements are available within the theme.
* The school name fields are available via the Customizer. (Appearance -> Customize -> Header & Navigation -> Header School Names)
* Additional social media icons and icon language consistent with the ASU Web Standards guide have been added to the theme. The social media settings by default are the main ASU Engineering channels.
* The ASU footer widget is available in the widgets area. To use it:
  * Please assign it to the Footer Area #1 sidebar 
  * Populate the widget fields with the correct school information.
* The Main Navigation for the Fulton Schools of Engineering also comes pre-populated within the theme. To use it, please assign , please assign the menu called **Main Nav** to the Primary Menu location.
* Additional ASU specific modules have been added to the Divi Page Builder:
  * ASU Fullwidth Slider
  * ASU Slider
  * ASU Buttons
Please use these instead of their default counterparts to ensure ASU standard elements are implemented.

