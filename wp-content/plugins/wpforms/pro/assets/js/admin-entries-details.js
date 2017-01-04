;(function($){
	$(function(){

	// Confirm entry deletion
	$(document).on('click', '.submitdelete', function(e) {
		if ( confirm( wpforms_entries.delete_confirm ) ) {
			return true;
		}
		return false;
	});

	// Open Print preview in new window
	$(document).on('click', '.wpforms-entry-print a', function(e) {
		e.preventDefault();
		window.open($(this).prop('href'));
	});

	// Toggle displaying empty fields
	$(document).on('click', '.wpforms-empty-field-toggle', function(e) {
		e.preventDefault();

		if (wpCookies.get('wpforms_entry_hide_empty') == 'true') {
			// User was hiding empty fields, so now display them
			wpCookies.remove('wpforms_entry_hide_empty');
			$(this).text(wpforms_entries.empty_fields_hide);
		} else {
			// User was seeing empty fields, so now hide them
			wpCookies.set('wpforms_entry_hide_empty','true',2592000); // 1month
			$(this).text(wpforms_entries.empty_fields_show);
		}
		$('.wpforms-entry-field.empty').toggle();
	});

	// Display notes editor
	$(document).on('click', '.wpforms-entry-notes-new .add', function(e) {
		e.preventDefault();
		$(this).next('form').slideToggle();
		$(this).hide();
	});

	// Cancel note
	$(document).on('click', '.wpforms-entry-notes-new .cancel', function(e) {
		e.preventDefault();
		$(this).closest('form').slideToggle();
		$('.wpforms-entry-notes-new .add').show();
	});

	// Delete note
	$(document).on('click', '.wpforms-entry-notes-byline .note-delete', function(e) {
		if ( confirm( wpforms_entries.note_delete_confirm ) ) {
			return true;
		}
		return false;
	});

	});
}(jQuery));