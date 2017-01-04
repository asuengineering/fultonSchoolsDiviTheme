;(function($){
	$(function(){

		// Switch forms
		$(document).on('change', '.form-details-action-switch', function(e) {
			e.preventDefault();
			var url = $(this).val();
			if (url) {
				window.location = url;
			}
		});

		// Confirm entry deletion
		$(document).on('click', '#wpforms-entries .wp-list-table .delete', function(e) {
			if ( confirm( wpforms_entries.delete_confirm ) ) {
				return true;
			}
			return false;
		});

		// Toggle entry stars
		$(document).on('click', '#wpforms-entries .wp-list-table .indicator-star', function(e) {
			e.preventDefault();
			var $this = $(this),
				task  = '',
				total = Number($('#wpforms-entries .starred-num').text()),
				id    = $this.data('id');

			if ( $this.hasClass('star') ) {
				task = 'star';
				total++;
				$this.attr('title', wpforms_entries.unstar);
			} else {
				task = 'unstar';
				total--;
				$this.attr('title', wpforms_entries.star);
			}
			$this.toggleClass('star unstar');
			$('#wpforms-entries .starred-num').text(total);

			var data = {
				task    : task,
				action  : 'wpforms_entry_list_star',
				nonce   : wpforms_entries.nonce,
				entry_id: id
			}
			$.post(wpforms_entries.ajax_url, data);
		});

		// Toggle entry read state
		$(document).on('click', '#wpforms-entries .wp-list-table .indicator-read', function(e) {
			e.preventDefault();
			var $this = $(this),
				task  = '',
				total = Number($('#wpforms-entries .unread-num').text()),
				id    = $this.data('id');

			if ( $this.hasClass('read') ) {
				task = 'read';
				total--;
				$this.attr('title', wpforms_entries.unread);
			} else {
				task = 'unread';
				total++;
				$this.attr('title', wpforms_entries.read);
			}
			$this.toggleClass('read unread');
			$('#wpforms-entries .unread-num').text(total);

			var data = {
				task    : task,
				action  : 'wpforms_entry_list_read',
				nonce   : wpforms_entries.nonce,
				entry_id: id
			}
			$.post(wpforms_entries.ajax_url, data);
		});

	});
}(jQuery));