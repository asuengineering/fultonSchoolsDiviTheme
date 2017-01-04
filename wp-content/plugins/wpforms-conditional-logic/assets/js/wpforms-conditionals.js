;(function($) {

	var WPFormsConditionals = {

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			// Document ready
			$(document).ready(WPFormsConditionals.ready);

			WPFormsConditionals.bindUIActions();
		},

		/**
		 * Document ready.
		 *
		 * @since 1.1.2
		 */
		ready: function() {

			$('.wpforms-form').each(function() {
				WPFormsConditionals.processConditionals($(this));
			});
		},

		/**
		 * Element bindings.
		 *
		 * @since 1.0.0
		 */
		bindUIActions: function() {

			$(document).on('change', '.wpforms-conditional-trigger input, .wpforms-conditional-trigger select', function() {
				WPFormsConditionals.processConditionals($(this));
			});

			$(document).on('input', '.wpforms-conditional-trigger input[type=text], .wpforms-conditional-trigger textarea', function() {
				WPFormsConditionals.processConditionals($(this));
			});

			$('.wpforms-form').submit(function() {
				WPFormsConditionals.resetHiddenFields($(this));
			});
		},

		/**
		 * Reset any form elements that are inside hidden conditional fields.
		 *
		 * @since 1.0.0
		 * @param element $el the form
		 */
		resetHiddenFields: function(el) {

			var $form = $(el);
			$form.find('.wpforms-conditional-hide :input').each(function() {
				switch ($(this).attr('type')) {
					case 'button':
					case 'submit':
					case 'reset':
					case 'hidden':
						break;
					case 'checkbox':
					case 'radio':
						if ($(this).is(':checked')){
							$(this).prop('checked', false).trigger('change');
						}
						break;
					case 'select':
							$(this).find('option:selected').prop('selected', 'false').trigger('change');
						break;
					default:
						if ($(this).val() != '') {
							$(this).val('').trigger('input');
						}
						break;
				}
			});
		},

		/**
		 * Process conditionals for a form.
		 *
		 * @since 1.0.0
		 * @param element $el any element inside the targeted form
		 */
		processConditionals: function(el) {

			var $this  = $(el),
				$form  = $this.closest('.wpforms-form'),
				formID = $form.data('formid');

			if (typeof wpforms_conditional_logic === 'undefined' || typeof wpforms_conditional_logic[formID] === 'undefined') {
				return false;
			}

			var fields = wpforms_conditional_logic[formID];

			// Fields
			for(var fieldID in fields) {

				if (window.location.hash && '#wpformsdebug' === window.location.hash) {
					console.log('Processing conditionals for Field #'+fieldID+'...');
				}

				var field  = fields[fieldID].logic,
					action = fields[fieldID].action,
					pass   = false,
					hidden = false;

				// Groups
				for(var groupID in field) {

					var group      = field[groupID],
						pass_group = true;

					// Rules
					for(var ruleID in group) {

						var rule      = group[ruleID],
							val       = false,
							pass_rule = false;

						if (window.location.hash && '#wpformsdebug' === window.location.hash) {
							console.log(rule);
						}

						if (rule.type === 'radio' || rule.type === 'checkbox' || rule.type === 'payment-multiple' ) {
							var $check = $form.find('#wpforms-'+formID+'-field_'+rule.field+'-container input:checked');
							if ($check.length) {
								$.each($check, function() {
									var escapeVal = WPFormsConditionals.escapeText($(this).val());
									if (rule.value === escapeVal) {
										val = escapeVal;
									}
								});
							}
						} else {
							// text, textarea, number, select
							val = $form.find('#wpforms-'+formID+'-field_'+rule.field).val();
							if (rule.type === 'select' || rule.type === 'payment-select' ) {
								val = WPFormsConditionals.escapeText(val);
							}
						}

						var left  = $.trim(val.toString().toLowerCase()),
							right = $.trim(rule.value.toString().toLowerCase());

						switch ( rule.operator ) {
							case '==' :
								pass_rule = ( left == right );
							break;
							case '!=' :
								pass_rule = ( left != right );
							break;
							case 'c' :
								pass_rule = ( left.indexOf(right) > -1 && left.length > 0 );
							break;
							case '!c' :
								pass_rule = ( left.indexOf(right) === -1 && right.length > 0 );
							break;
							case '^' :
								pass_rule = ( left.lastIndexOf(right, 0) === 0 );
							break;
							case '~' :
								pass_rule = ( left.indexOf(right, left.length - right.length) !== -1 );
							break;
						}

						if (!pass_rule) {
							pass_group = false;
							break;
						}
					}

					if (pass_group) {
						pass = true;
					}
				}

				if (window.location.hash && '#wpformsdebug' === window.location.hash) {
					console.log('Result: ' + pass);
				}

				if ((pass && action === 'hide') || (!pass && action !== 'hide')) {
					$form.find('#wpforms-'+formID+'-field_'+fieldID+'-container').hide().addClass('wpforms-conditional-hide').removeClass('wpforms-conditional-show');
					hidden = true;
				} else {
					$form.find('#wpforms-'+formID+'-field_'+fieldID+'-container').show().removeClass('wpforms-conditional-hide').addClass('wpforms-conditional-show');
				}

				$(document).trigger('wpformsProcessConditionalsField', [formID, fieldID, pass, action]);
			}

			if (hidden) {
				WPFormsConditionals.resetHiddenFields($form);
			}

			$(document).trigger('wpformsProcessConditionals', [$this, $form, formID]);
		},

		/**
		 * Escape text similiar to PHP htmlspecialchars.
		 *
		 * @since 1.0.5
		 * @param string $text
		 * @return string
		 */
		escapeText: function(text) {

			if ( !text ){
				return false;
			}

			var map = {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				"'": '&#039;'
			};

			return text.replace(/[&<>"']/g, function(m) { return map[m]; });
		}
	};

	WPFormsConditionals.init();

	window.wpformsconditionals = WPFormsConditionals;

})(jQuery);