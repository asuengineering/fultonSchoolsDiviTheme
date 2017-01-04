;(function($) {

	var s;

	var WPFormsAddons = {

		settings: {
			spinner: '<i class="fa fa-spinner fa-spin"></i>'
		},

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			s = this.settings;

			WPFormsAddons.bindUIActions();
		},

		/**
		 * Element bindings.
		 *
		 * @since 1.0.0
		 */
		bindUIActions: function() {
			
			// Deactivate
			$(document).on( 'click', '.wpforms-addon-status-active button', function(e) {
				e.preventDefault();
				WPFormsAddons.deactivate($(this));
			});

			// Activate
			$(document).on( 'click', '.wpforms-addon-status-inactive button', function(e) {
				e.preventDefault();
				WPFormsAddons.activate($(this));
			});

			// Install
			$(document).on( 'click', '.wpforms-addon-status-download button', function(e) {
				WPFormsAddons.install($(this));
			});
		},

		/**
		 * Deactivate addon.
		 *
		 * @since 1.0.0
		 */
		deactivate: function(el) {

			var $this  = $(el),
				$item  = $this.closest('.wpforms-addon-item'),
				plugin = $this.attr('data-plugin');

			$('#wpforms-addons button').prop('disabled', true);
			$this.html(s.spinner);
			WPFormsAddons.clearMessages();

			var data = {
				action: 'wpforms_deactivate_addon',
				nonce : wpforms_addons.nonce,
				plugin: plugin
			}
			$.post(wpforms_addons.ajax_url, data, function(res) {
				$item.find('.wpforms-addon-text .desc').hide();
				if (res.success){	
					$item.find('.wpforms-addon-text').append('<div class="wpforms-success">'+res.data+'</div>');
					$item.removeClass('wpforms-addon-status-active').addClass('wpforms-addon-status-inactive');
					$this.text(wpforms_addons.activate);
				} else {
					$item.find('.wpforms-addon-text').append('<div class="wpforms-error">'+res.data+'</div>');
					$this.text(wpforms_addons.deactivate);
				}
				setTimeout(WPFormsAddons.clearMessages, 4000);
				$('#wpforms-addons button').prop('disabled', false);
			}).fail(function(xhr, textStatus, e) {
				console.log(xhr.responseText);
			});
		},

		/**
		 * Activate addon.
		 *
		 * @since 1.0.0
		 */
		activate: function(el) {

			var $this  = $(el),
				$item  = $this.closest('.wpforms-addon-item'),
				plugin = $this.attr('data-plugin');

			$('#wpforms-addons button').prop('disabled', true);
			$this.html(s.spinner);
			WPFormsAddons.clearMessages();

			var data = {
				action: 'wpforms_activate_addon',
				nonce : wpforms_addons.nonce,
				plugin: plugin
			}
			$.post(wpforms_addons.ajax_url, data, function(res) {
				$item.find('.wpforms-addon-text .desc').hide();
				if (res.success){	
					$item.find('.wpforms-addon-text').append('<div class="wpforms-success">'+res.data+'</div>');
					$item.removeClass('wpforms-addon-status-inactive').addClass('wpforms-addon-status-active');
					$this.text(wpforms_addons.deactivate);
				} else {
					$item.find('.wpforms-addon-text').append('<div class="wpforms-error">'+res.data+'</div>');
					$this.text(wpforms_addons.activate);
				}
				setTimeout(WPFormsAddons.clearMessages, 4000);
				$('#wpforms-addons button').prop('disabled', false);
			}).fail(function(xhr, textStatus, e) {
				console.log(xhr.responseText);
			});
		},

		/**
		 * Install addon.
		 *
		 * @since 1.0.0
		 */
		install: function(el) {

			var $this  = $(el),
				$item  = $this.closest('.wpforms-addon-item'),
				plugin = $this.attr('data-plugin');

			$('#wpforms-addons button').prop('disabled', true);
			$this.html(s.spinner);
			WPFormsAddons.clearMessages();

			var data = {
				action: 'wpforms_install_addon',
				nonce : wpforms_addons.nonce,
				plugin: plugin
			}
			$.post(wpforms_addons.ajax_url, data, function(res) {
				$item.find('.wpforms-addon-text .desc').hide();
				if (res.success){	
					$item.find('.wpforms-addon-text').append('<div class="wpforms-success">'+res.data.msg+'</div>');
					$item.removeClass('wpforms-addon-status-download').addClass('wpforms-addon-status-inactive');
					$this.text(wpforms_addons.activate);
					$this.attr('data-plugin',res.data.basename);
				} else {
					$item.find('.wpforms-addon-text').append('<div class="wpforms-error">'+res.data+'</div>');
					$this.text(wpforms_addons.install);
				}
				setTimeout(WPFormsAddons.clearMessages, 3500);
				$('#wpforms-addons button').prop('disabled', false);
			}).fail(function(xhr, textStatus, e) {
				console.log(xhr.responseText);
			});
		},

		clearMessages: function() {
			$('.wpforms-error, .wpforms-success').remove();
			$('.wpforms-addon-text .desc').show();
		}
	}

	WPFormsAddons.init();
})(jQuery);