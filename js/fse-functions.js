/**
 * Additional javascript items to include in the Fulton Schools Divi Theme.
 * Requires jQuery.
 *
 * @summary   Additional Fulton Schools of Engineering Functions.
 *
 * @since     FSDT 1.8
 */

jQuery(document).ready(function($) {

	// Add external link icons to all <a> tags that open in new browsers.
	// Immediately remove the icon from any <a> element that contains an image.
	// Also immediately remove the icon from social media footer elements
	$('a[target="_blank"]').addClass('external-link');
	$('a:has(img)').removeClass('external-link');
	$('.et-social-icon a').removeClass('external-link');

	// Add an <a> class for CSS styling that identifies that the link contains an image.
	$('a:has(img)').addClass('linked-image');

	// Remove the class from the endorsed footer logo, if present.
	$('#fse-endorsed-logo a').removeClass('linked-image');

});