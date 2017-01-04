;(function($) {

	var s;

	var WPFormsProviders = {

		settings: {
			form  : $('#wpforms-builder-form'),
			spinner: '<i class="fa fa-circle-o-notch fa-spin wpforms-button-icon" />'
		},

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			s = this.settings;

			// Document ready
			$(document).ready(WPFormsProviders.ready);

			WPFormsProviders.bindUIActions();
		},

		/**
		 * Document ready.
		 *
		 * @since 1.1.1
		 */
		ready: function() {

			// Setup/cache some vars not available before
			s.formID = $('#wpforms-builder-form').data('id');
		},

		/**
		 * Element bindings.
		 *
		 * @since 1.0.0
		 */
		bindUIActions: function() {

			// Delete connection
			$(document).on('click', '.wpforms-provider-connection-delete', function(e) {
				WPFormsProviders.connectionDelete(this, e);
			});

			// Add new connection
			$(document).on('click', '.wpforms-provider-connections-add', function(e) {
				WPFormsProviders.connectionAdd(this, e);
			});

			// Add new provider account
			$(document).on('click', '.wpforms-provider-account-add button', function(e) {
				WPFormsProviders.accountAdd(this, e);
			});

			// Select provider account
			$(document).on('change', '.wpforms-provider-accounts select', function(e) {
				WPFormsProviders.accountSelect(this, e);
			});

			// Select account list
			$(document).on('change', '.wpforms-provider-lists select', function(e) {
				WPFormsProviders.accountListSelect(this, e);
			});

			// Conditional support toggle
			$(document).on('change', '.wpforms-provider-conditionals .toggle', function(e) {
				WPFormsProviders.conditionalToggle(this, e);
			});

			// Conditional logic real updates
			$(document).on('wpformsFieldUpdate', WPFormsProviders.conditionalUpdateOptions);

			// Conditional process field
			$(document).on('change', '.wpforms-provider-conditionals .field select', function(e) {
				WPFormsProviders.conditionalField(this, e);
			});

			// Conditional add new rule
			$(document).on('click', '.wpforms-provider-conditionals-rule-add', function(e) {
				WPFormsProviders.conditionalRuleAdd(this, e);
			});

			// Conditional delete rule
			$(document).on('click', '.wpforms-provider-conditionals-rule-delete', function(e) {
				WPFormsProviders.conditionalRuleDelete(this, e);
			});

			// Conditional add new group
			$(document).on('click', '.wpforms-provider-conditionals-groups-add', function(e) {
				WPFormsProviders.conditionalGroupAdd(this, e);
			});

			$(document).on('wpformsPanelSwitch', function(e, targetPanel) {
				WPFormsProviders.providerPanelConfirm(targetPanel);
			});
		},

		/**
		 * Delete provider connection
		 *
		 * @since 1.0.0
		 */
		connectionDelete: function(el, e) {
			e.preventDefault();

			var $this = $(el);
			$.confirm({
				title: false,
				content: wpforms_builder_providers.confirm_connection,
				backgroundDismiss: false,
				closeIcon: false,
				confirm: function(){
					$this.closest('.wpforms-provider-connection').remove();
				}
			});	
		},

		/**
		 * Add new provider connection.
		 *
		 * @since 1.0.0
		 */
		connectionAdd: function(el, e) {
			e.preventDefault();

			var $this        = $(el),
				$connections = $this.parent().parent(),
				$container   = $this.parent(),
				provider     = $this.data('provider'),
				type         = $this.data('type'),
				namePrompt   = wpforms_builder_providers.prompt_connection,
				nameField    = '<input autofocus="" type="text" id="provider-connection-name" placeholder="'+wpforms_builder_providers.prompt_placeholder+'">',
				nameError    = '<p class="error">'+wpforms_builder_providers.error_name+'</p>',
				modalContent = namePrompt+nameField+nameError;

			modalContent = modalContent.replace(/%type%/g,type);

			$.confirm({
				title: false,
				content: modalContent,
				backgroundDismiss: false,
				closeIcon: false,
				confirm: function () {
					var input = this.$b.find('input#provider-connection-name');
					var error = this.$b.find('.error');
					if (input.val() == '') {
						error.show();
						return false;
					} else {
						
						var name = input.val();

						// Disable button
						WPFormsProviders.inputToggle($this, 'disable');

						// Fire AJAX
						var data =  {
							action  : 'wpforms_provider_ajax_'+provider,
							provider: provider,
							task    : 'new_connection',
							name    : name,
							id      : s.form.data('id'),
							nonce   : wpforms_builder.nonce
						}
						WPFormsProviders.fireAJAX($this, data, function(res) {
							if (res.success) {
								$connections.find('.wpforms-provider-connections').prepend(res.data.html);
								// Process and load the accounts if they exist
								var $connection = $connections.find('.wpforms-provider-connection:first');
								if ($connection.find( '.wpforms-provider-accounts option:selected')) {
									$connection.find( '.wpforms-provider-accounts option:first').prop('selected', true);
									$connection.find('.wpforms-provider-accounts select').trigger('change');
								}
							} else {
								WPFormsProviders.errorDisplay(res.data.error, $container);
							}
						});
					}
				}
			});
		},

		/**
		 * Add and authorize provider account.
		 *
		 * @since 1.0.0
		 */
		accountAdd: function(el, e) {
			e.preventDefault();

			var $this       = $(el),
				provider    = $this.data('provider'),
				$connection = $this.closest('.wpforms-provider-connection'),
				$container  = $this.parent(),
				$fields     = $container.find(':input'),
				errors      = WPFormsProviders.requiredCheck($fields, $container);

			// Disable button
			WPFormsProviders.inputToggle($this, 'disable');

			// Bail if we have any errors
			if (errors) {
				$this.prop('disabled', false).find('i').remove();
				return false;
			}

			// Fire AJAX
			data = {
				action       : 'wpforms_provider_ajax_'+provider,
				provider     : provider,
				connection_id: $connection.data('connection_id'),
				task         : 'new_account',
				data         : WPFormsProviders.fakeSerialize($fields),
			}
			WPFormsProviders.fireAJAX($this, data, function(res) {
				if (res.success) {
					$container.nextAll('.wpforms-connection-block').remove();
					$container.after(res.data.html);
					$container.slideUp();
					$connection.find('.wpforms-provider-accounts select').trigger('change'); 
				} else {
					WPFormsProviders.errorDisplay(res.data.error, $container);
				}
			});
		},

		/**
		 * Selecting a provider account
		 *
		 * @since 1.0.0
		 */
		accountSelect: function(el, e) {
			e.preventDefault();

			var $this       = $(el),
				$connection = $this.closest('.wpforms-provider-connection'),
				$container  = $this.parent(),
				provider    = $connection.data('provider');

			// Disable select, show loading
			WPFormsProviders.inputToggle($this, 'disable');

			// Remove any blocks that might exist as we prep for new account
			$container.nextAll('.wpforms-connection-block').remove();

			if (!$this.val()) {

				// User selected to option to add new account
				$connection.find('.wpforms-provider-account-add input').val('');
				$connection.find('.wpforms-provider-account-add').slideDown();
				WPFormsProviders.inputToggle($this, 'enable');
			
			} else {

				$connection.find('.wpforms-provider-account-add').slideUp();

				// Fire AJAX
				data = {
					action       : 'wpforms_provider_ajax_'+provider,
					provider     : provider,
					connection_id: $connection.data('connection_id'),
					task         : 'select_account',
					account_id   : $this.find(':selected').val(),
				}
				WPFormsProviders.fireAJAX($this, data, function(res) {
					if (res.success) {
						$container.after(res.data.html);
						// Process first list found
						$connection.find('.wpforms-provider-lists option:first').prop('selected', true);
						$connection.find('.wpforms-provider-lists select').trigger('change');
					} else {
						WPFormsProviders.errorDisplay(res.data.error, $container);
					}
				});
			}
		},

		/**
		 * Selecting a provider account list.
		 *
		 * @since 1.0.0
		 */
		accountListSelect: function(el, e) {
			e.preventDefault();

			var $this       = $(el),
				$connection = $this.closest('.wpforms-provider-connection'),
				$container  = $this.parent(),
				provider    = $connection.data('provider');

			// Disable select, show loading
			WPFormsProviders.inputToggle($this, 'disable');

			// Remove any blocks that might exist as we prep for new account
			$container.nextAll('.wpforms-connection-block').remove();

			data = {
				action       : 'wpforms_provider_ajax_'+provider,
				provider     : provider,
				connection_id: $connection.data('connection_id'),
				task         : 'select_list',
				account_id   : $connection.find('.wpforms-provider-accounts option:selected').val(),
				list_id      : $this.find(':selected').val(),
				form_id      : s.formID
			}
			console.log(s.formID);
			WPFormsProviders.fireAJAX($this, data, function(res) {
				if (res.success) {
					$container.after(res.data.html);
				} else {
					WPFormsProviders.errorDisplay(res.data.error, $container);
				}
			});
		},

		/**
		 * Toggle conditional field support.
		 *
		 * @since 1.0.0
		 */
		conditionalToggle: function(el, e) {
			e.preventDefault();

			var $this = $(el);
			if ($this.is(':checked')) {
				$this.parent().parent().find('.wpforms-provider-conditionals-groups').slideDown();
			} else {
				$this.parent().parent().find('.wpforms-provider-conditionals-groups').slideUp();
			}
		},

		/**
		 * Update/refresh the conditional logic fields and associated options.
		 *
		 * @since 1.0.4
		 */
		conditionalUpdateOptions: function(e, fields) {

			// @toodo
			return;
			var formFields = $.extend({}, fields);
				allowed    = ['text', 'textarea', 'select', 'radio', 'checkbox', 'number' ],
				changed    = false;

			// Remove field types that are not allowed and whitelested
			for(var key in formFields) {
				if ($.inArray(formFields[key].type, allowed) == '-1' ){
						delete formFields[key];
				}
			}

			// Now go through each conditional rule in the builder
			$('.wpforms-provider-conditionals-group-row').each(function(index, ele) {
		
				var $this          = $(this),
					fieldID        = $this.data('field-id'),
					$fields        = $this.find('.wpforms-provider-conditionals-field'),
					fieldSelected  = $fields.find('option:selected').val(),
					$value         = $this.find('.wpforms-provider-conditionals-value'),
					valueSelected  = '',
					choiceOrder    = [];
	
				// Empty the field select box, re-add placeholder option	
				$fields.empty().append($('<option>', { value: '', text : '-- Select field --' }));

				// Add appropriate options for each field. Reference using the
				// field label (if provided) or fallback to the field ID.
				for(var key in formFields) {
					if (formFields[key].label.length) {
						var label = wpf.sanitizeString(formFields[key].label);
					} else {
						var label = 'Field #' + formFields[key].val;
					}
					$fields.append($('<option>', { value: fields[key].id, text : label }));
				}

				// Check if previous selected field exists in the new options added
				if ($fields.find('option[value="'+fieldSelected+'"]').length) {

					// Exists, so restore previous selected value
					$fields.find('option[value="'+fieldSelected+'"]').prop('selected', true);

					// Since the field exist and was selected, now we must proceed
					// to updating the field values. Luckily, we only have to do
					// this for fields that leverage a select element.
					if ( $value.length && $value.is('select') ) {

						// Define the order of the choices
						$('#wpforms-field-option-row-'+fieldSelected+'-choices li').each(function(index, ele) {
							choiceOrder.push($(ele).data('key'));
						});
	
						// Grab the currently selected value to restore later
						valueSelected = $value.find('option:selected').val();

						// Remove all current options
						$value.empty();

						// Add new options, in the correct order
						$.each(choiceOrder, function(index, val) {
							var label = wpf.sanitizeString(fields['field_'+fieldSelected].choices[val].label);
							$value.append($('<option>', { value: val, text : label }));
						});

						// Check if previous selected calue exists in the new options added
						if ($value.find('option[value="'+valueSelected+'"]').length) {

							$value.find('option[value="'+valueSelected+'"]').prop('selected', true);

						} else {

							// Add temporary placeholder value since the old one
							// doesn't exist
							$value.prepend($('<option>', { value: '', text : '-- Select Choice --' }));

							// Old value does not exist in the new options, likely
							// deleted. Add the field ID to the charged variable,
							// which will let the user know the fields conditional
							// logic has been altered.
							if (valueSelected.length > 0) {
								changed = true;
							}
						}
					}

				} else {

					// Old field does not exist in the new options, likely deleted.
					// Add the field ID to the charged variable, which will let 
					// the user know the fields conditional logic has been altered.
					changed = true;

					// Since previously selected field no longer exists, this
					// means this rule is now invalid. So the rule gets
					// deleted as long as it isn't the only rule remaining.
					$group = $this.closest('.wpforms-provider-conditionals-group');
					if ($group.find('table >tbody >tr').length === 1) {
						var $groups = $this.closest('.wpforms-provider-conditionals-groups');
						if ($groups.find('.wpforms-provider-conditionals-group').length > 1) {
							$group.remove();
						} else {
							$this.find('.wpforms-provider-conditionals-value').remove();
							$this.find('.value').append('<select>');
						}
					} else {
						$this.remove();
					}
				}
			});

			// If conditional rules have been altered due to form updates then
			// we alert the user.
			if ( changed  ) {

				// Build and trigger alert
				var alert = 'Due to form changes, Marketing Provider conditional logic rules have been removed or updated.';
				$.alert({
					title: 'Heads Up!',
					content: alert
				});	
			}

			//console.log('Conditional logic options updated');
		},

		/**
		 * Process conditional field
		 *
		 * @since 1.0.0
		 */
		conditionalField: function(el, e) {
			e.preventDefault();

			var $this    = $(el),
				$rule    = $this.parent().parent(),
				data     = WPFormsProviders.conditionalData($this),
				name     = data.name+'['+data.groupID+']['+data.ruleID+'][value]',
				element;

			if ( !data.field ) {
				// Placeholder has been selected
				element = $('<select>');
			} else if (data.field.type == 'text' || data.field.type == 'number' || data.field.type == 'textarea') {
				var element = $('<input>').attr({ type: 'text', name: name, class: 'wpforms-provider-conditionals-value'  });
			} else {
				var element = $('<select>').attr({ name: name, class: 'wpforms-provider-conditionals-value' });
				$.each(data.field.choices, function( key, ele) {
					if (ele) {
						element.append($('<option>', { value: key, text : wpf.sanitizeString(ele.label) }));
					}
				});
			}
			$rule.find('.value').empty().append(element);
		},

		/**
		 * Add new conditional rule.
		 *
		 * @since 1.0.0
		 */
		conditionalRuleAdd: function(el, e) {
			e.preventDefault();

			var $this     = $(el),
				$group    = $this.closest('.wpforms-provider-conditionals-group'),
				$rule     = $group.find('tr').last(),
				$newRule  = $rule.clone(),
				$field    = $newRule.find('.field select'),
				$operator = $newRule.find('.operator select'),
				data      = WPFormsProviders.conditionalData($field),
				ruleID    = Number(data.ruleID)+1,
				name      = data.name+'['+data.groupID+']['+ruleID+']';

			$newRule.find('option:selected').prop('selected', false);
			$newRule.find('.value').empty().append( $('<select>') );
			$field.attr('name', name+'[field]').attr('data-ruleid', ruleID);
			$operator.attr('name', name+'[operator]');
			$rule.after($newRule);
		},

		/**
		 * Delete conditional rule. If the only rule in group then group will
		 * also be removed.
		 *
		 * @since 1.0.0
		 */
		conditionalRuleDelete: function(el, e) {
			e.preventDefault();

			var $this = $(el),
				$group = $this.closest('.wpforms-provider-conditionals-group');
				$rows  = $group.find('table >tbody >tr');

			if ($rows && $rows.length === 1) {
				var $groups = $this.closest('.wpforms-provider-conditionals-groups');
				if ( $groups.find('.wpforms-provider-conditionals-group').length > 1 ) {
					$group.remove();
				} else {
					return;
				}
			} else {
				$this.parent().parent().remove();
			}
		},

		/**
		 * Add new conditional group.
		 *
		 * @since 1.0.0
		 */
		conditionalGroupAdd: function(el, e) {
			e.preventDefault();

			var $this = $(el),
				$groupLast = $this.parent().find('.wpforms-provider-conditionals-group').last(),
				$newGroup  = $groupLast.clone();
				$newGroup.find('tr').not(':first').remove();
			var	$field     = $newGroup.find('.field select'),
				$operator  = $newGroup.find('.operator select'),
				data       = WPFormsProviders.conditionalData($field),
				groupID    = Number(data.groupID)+1,
				ruleID     = 0,
				name       = data.name+'['+groupID+']['+ruleID+']';

			$newGroup.find('option:selected').prop('selected', false);
			$newGroup.find('.value').empty().append( $('<select>') );
			$field.attr('name', name+'[field]').attr('data-ruleid', ruleID).attr('data-groupid', groupID);
			$operator.attr('name', name+'[operator]');
			$this.before($newGroup);
		},

		/**
		 * Confirm form save before loading Provider panel.
		 * If confirmed, save and reload panel.
		 *
		 * @since 1.0.0
		 */
		providerPanelConfirm: function(targetPanel) {

			wpforms_panel_switch = true;
			if (targetPanel =='providers') {
				var currentState = $('#wpforms-builder-form').serializeJSON();
				if ( currentState != wpforms_builder.saved_state ) {
					wpforms_panel_switch = false;
					$.confirm({
						title: false,
						content: wpforms_builder_providers.confirm_save,
						backgroundDismiss: false,
						closeIcon: false,
						confirm: function(){
							$('#wpforms-save').trigger('click');
							$(document).on('wpformsSaved', function() {
								window.location.href = wpforms_builder_providers.url;
							});
						},
					});
				}
			}
		},

		//--------------------------------------------------------------------//
		// Helper functions
		//--------------------------------------------------------------------//

		/**
		 * Fire AJAX call.
		 *
		 * @since 1.0.0
		 */
		fireAJAX: function(el, d, success) {
			var $this = $(el);
			var data = {
				id    : $('#wpforms-builder-form').data('id'),
				nonce : wpforms_builder.nonce
			}
			$.extend(data, d);
			$.post(wpforms_builder.ajax_url, data, function(res) {
				success(res);
				WPFormsProviders.inputToggle($this, 'enable');
			}).fail(function(xhr, textStatus, e) {
				console.log(xhr.responseText);
			});
		},

		/**
		 * Return various data for the conditional field.
		 *
		 * @since 1.0.0
		 */
		conditionalData: function(el) {

			var $this = $(el);
			var data = {
				formData    : $('#wpforms-builder-form').serializeObject(),
				fieldID     : $this.find(':selected').val(),
				ruleID      : $this.attr('data-ruleid'),
				groupID     : $this.attr('data-groupid'),
				connectionID: $this.attr('data-connectionid'),
				provider    : $this.attr('data-provider'),
				selectedID : $this.find(':selected').val()
			}
			if (data.selectedID.length) {
				data.field = data.formData.fields[data.selectedID];
			} else {
				data.field = false;
			}
			data.name = 'providers['+data.provider+']['+data.connectionID+'][conditionals]';
			return data;
		},

		/**
		 * Toggle input with loading indicator.
		 *
		 * @since 1.0.0
		 */
		inputToggle: function(el, status) {
			var $this = $(el);
			if (status == 'enable') {
				if ($this.is('select')) {
					$this.prop('disabled', false).next('i').remove();
				} else {
					$this.prop('disabled', false).find('i').remove();
				}
			} else if (status == 'disable'){
				if ($this.is('select')) {
					$this.prop('disabled', true).after(s.spinner);
				} else {
					$this.prop('disabled', true).prepend(s.spinner);
				}
			}
		},

		/**
		 * Display error.
		 *
		 * @since 1.0.0
		 */
		errorDisplay: function(msg, location) {
			location.find('.wpforms-error-msg').remove();
			location.prepend('<p class="wpforms-alert-danger wpforms-alert wpforms-error-msg">'+msg+'</p>');
		},

		/**
		 * Check for required fields.
		 *
		 * @since 1.0.0
		 */
		requiredCheck: function(fields, location) {
			var error = false;

			// Remove any previous errors
			location.find('.wpforms-alert-required').remove();

			// Loop through input fields and check for values
			fields.each(function(index, el) {
				if ( $(el).hasClass('wpforms-required') && $(el).val().length === 0 ) {
					$(el).addClass('wpforms-error');
					error = true;
				} else {
					$(el).removeClass('wpforms-error');
				}
			});
			if (error) {
				location.prepend('<p class="wpforms-alert-danger wpforms-alert wpforms-alert-required">'+wpforms_builder_providers.required_field+'</p>');
			}
			return error;
		},

		/**
		 * Psuedo serializing. Fake it until you make it.
		 *
		 * @since 1.0.0
		 */
		fakeSerialize: function(els) {
			var fields = els.clone();

			fields.each(function(index, el){
				if ($(el).data('name')) {
					$(el).attr('name', $(el).data('name'));
				}
			});
			return fields.serialize();
		}
	};

	WPFormsProviders.init();
})(jQuery);