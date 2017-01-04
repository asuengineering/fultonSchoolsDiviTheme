;(function($) {

	var WPFormsConditionals = {

		/**
		 * Start the engine.
		 *
		 * @since 1.0.0
		 */
		init: function() {

			WPFormsConditionals.bindUIActions();
		},

		/**
		 * Element bindings.
		 *
		 * @since 1.0.0
		 */
		bindUIActions: function() {

			// Conditional support toggle
			$(document).on('change', '.wpforms-conditionals-enable-toggle input', function(e) {
				WPFormsConditionals.conditionalToggle(this, e);
			});

			// Conditional process field select
			$(document).on('change', '.wpforms-conditional-field', function(e) {
				WPFormsConditionals.conditionalField(this, e);
			});

			// Conditional add new rule
			$(document).on('click', '.wpforms-conditional-rule-add', function(e) {
				WPFormsConditionals.conditionalRuleAdd(this, e);
			});

			// Conditional delete rule
			$(document).on('click', '.wpforms-conditional-rule-delete', function(e) {
				WPFormsConditionals.conditionalRuleDelete(this, e);
			});

			// Conditional add new group
			$(document).on('click', '.wpforms-conditional-groups-add', function(e) {
				WPFormsConditionals.conditionalGroupAdd(this, e);
			});

			// Conditional logic update/refresh
			$(document).on('wpformsFieldUpdate', WPFormsConditionals.conditionalUpdateOptions);
		},

		/**
		 * Update/refresh the conditional logic fields and associated options.
		 *
		 * @since 1.0.0
		 */
		conditionalUpdateOptions: function(e, allFields) {

			var fields     = $.extend({}, allFields),
				allowed    = ['text', 'textarea', 'select', 'radio', 'email', 'url', 'checkbox', 'number', 'payment-multiple', 'payment-select', 'hidden'],
				changed    = [];

			if (wpf.empty(fields))
				return;

			// Remove field types that are not allowed and whitelested
			for(var key in fields) {
				if ( $.inArray(fields[key].type, allowed) == '-1' ){
					delete fields[key];
				} else if (typeof fields[key].dynamic_choices !== 'undefined' && fields[key].dynamic_choices !== '' ) {
					delete fields[key];
				}
			}

			// Now go through each conditional rule in the builder
			$('.wpforms-conditional-row').each(function(index, ele) {

				var $this          = $(this),
					fieldID        = $this.attr('data-field-id'),
					$fields        = $this.find('.wpforms-conditional-field'),
					fieldSelected  = $fields.find('option:selected').val(),
					$value         = $this.find('.wpforms-conditional-value'),
					valueSelected  = '';

				// Empty the field select box, re-add placeholder option
				$fields.empty().append($('<option>', { value: '', text : wpforms_builder.select_field }));

				// Add appropriate options for each field. Reference using the
				// field label (if provided) or fallback to the field ID.
				for(var key in fields) {
					if (fields[key].label.length) {
						var label = wpf.sanitizeString(fields[key].label);
					} else {
						var label = wpforms_builder.field + ' #' + fields[key].id;
					}
					if (fieldID && fieldID == fields[key].id) {
						continue;
					} else {
						$fields.append($('<option>', { value: fields[key].id, text : label }));
					}
				}

				if ( !fieldSelected ) {
					return true;
				}

				// Check if previous selected field exists in the new options added
				if ( $fields.find('option[value="'+fieldSelected+'"]').length) {

					// Exists, so restore previous selected value
					$fields.find('option[value="'+fieldSelected+'"]').prop('selected', true);

					// Since the field exist and was selected, now we must proceed
					// to updating the field values. Luckily, we only have to do
					// this for fields that leverage a select element.
					if ($value.length && $value.is('select')) {

						// Grab the currently selected value to restore later
						valueSelected = $value.find('option:selected').val();

						// Remove all current options
						$value.empty();

						// Add new options, in the correct order
						$value.append($('<option>', { value: '', text : wpforms_builder.select_choice }));
						for(var key in fields['field_'+fieldSelected].choices) {
							var label = wpf.sanitizeString(fields['field_'+fieldSelected].choices[key].label);
							$value.append($('<option>', { value: fields['field_'+fieldSelected].choices[key].key, text : label }));
						}

						// Check if previous selected calue exists in the new options added
						if ($value.find('option[value="'+valueSelected+'"]').length) {

							$value.find('option[value="'+valueSelected+'"]').prop('selected', true);

						} else {

							// Old value does not exist in the new options, likely
							// deleted. Add the field ID to the charged variable,
							// which will let the user know the fields conditional
							// logic has been altered.
							if (valueSelected.length > 0) {
								changed.push($this.closest('.wpforms-conditional-group').data('reference'));
							}
						}
					}

				} else {

					// Old field does not exist in the new options, likely deleted.
					// Add the field ID to the charged variable, which will let
					// the user know the fields conditional logic has been altered.
					changed.push($this.closest('.wpforms-conditional-group').data('reference'));

					// Since previously selected field no longer exists, this
					// means this rule is now invalid. So the rule gets
					// deleted as long as it isn't the only rule remaining.
					$group = $this.closest('.wpforms-conditional-group');
					if ($group.find('table >tbody >tr').length === 1) {
						var $groups = $this.closest('.wpforms-conditional-groups');
						if ($groups.find('.wpforms-conditional-group').length > 1) {
							$group.remove();
						} else {
							$this.find('.wpforms-conditional-value').remove();
							$this.find('.value').append('<select>');
						}
					} else {
						$this.remove();
					}
				}
			});

			// If conditional rules have been altered due to form updates then
			// we alert the user.
			if ( changed.length > 0 ) {

				// Remove dupes
				var changedUnique = changed.reduce(function(a,b){if(a.indexOf(b)<0)a.push(b);return a;},[]);

				// Build and trigger alert
				var alert = wpforms_builder.conditionals_change;

				for(var key in changedUnique) {
					if (wpf.isNumber(changedUnique[key]) ) {
						// Field
						if (allFields['field_'+changedUnique[key]].label.length ) {
							alert += '<br/>'+wpf.sanitizeString(allFields['field_'+changedUnique[key]].label) + ' ('+wpforms_builder.field+' #'+changedUnique[key]+')';
						} else {
							alert += '<br>'+wpforms_builder.field+' #'+changedUnique[key];
						}
					} else {
						// Panel
						alert += '<br>'+changedUnique[key];
					}
				}

				$.alert({
					title: wpforms_builder.heads_up,
					content: alert
				});
			}

			//console.log('Conditional logic options updated');
		},

		/**
		 * Toggle conditional support.
		 *
		 * @since 1.0.0
		 */
		conditionalToggle: function(el, e) {

			e.preventDefault();

			var $this = $(el);
			if ($this.is(':checked')) {
				$this.closest('.wpforms-conditional-block').find('.wpforms-conditional-groups').slideDown();
			} else {
				$this.closest('.wpforms-conditional-block').find('.wpforms-conditional-groups').slideUp();
			}
		},

		/**
		 * Process conditional field.
		 *
		 * @since 1.0.0
		 */
		conditionalField: function(el, e) {

			e.preventDefault();

			var $this     = $(el),
				$rule     = $this.parent().parent(),
				$operator = $rule.find('.wpforms-conditional-operator'),
				data      = WPFormsConditionals.conditionalData($this),
				name      = data.inputName+'['+data.groupID+']['+data.ruleID+'][value]',
				$element;

			if ( !data.field ) {

				// Placeholder has been selected
				$element = $('<select>');

			} else if ( data.field.type == 'select' || data.field.type == 'radio' || data.field.type == 'checkbox' || data.field.type == 'payment-multiple' || data.field.type == 'payment-select' ) {

				// Selector type fields use select elements
				$element = $('<select>').attr({ name: name, class: 'wpforms-conditional-value' });
				$element.append($('<option>', { value: '', text : wpforms_builder.select_choice }));
				if (data.field.choices){
					for(var key in data.field.choices) {
						$element.append($('<option>', { value: data.field.choices[key].key, text : wpf.sanitizeString(data.field.choices[key].label) }));
					}
				}
				$operator.find("option:not([value='=='],[value='!='])").prop('disabled', true).prop('selected', false);

			} else {

				// Text type fields (everything else) use text inputs
				$element = $('<input>').attr({ type: 'text', name: name, class: 'wpforms-conditional-value' });
				$operator.find('option').prop('disabled', false);
			}
			$rule.find('.value').empty().append($element);
		},

		/**
		 * Add new conditional rule.
		 *
		 * @since 1.0.0
		 */
		conditionalRuleAdd: function(el, e) {

			e.preventDefault();

			var $this     = $(el),
				$group    = $this.closest('.wpforms-conditional-group'),
				$rule     = $group.find('tr').last(),
				$newRule  = $rule.clone(),
				$field    = $newRule.find('.wpforms-conditional-field'),
				$operator = $newRule.find('.wpforms-conditional-operator'),
				data      = WPFormsConditionals.conditionalData($field),
				ruleID    = Number(data.ruleID)+1,
				name      = data.inputName+'['+data.groupID+']['+ruleID+']';

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
				$group = $this.closest('.wpforms-conditional-group'),
				$rows  = $group.find('table >tbody >tr');

			if ($rows && $rows.length === 1) {
				var $groups = $this.closest('.wpforms-conditional-groups');
				if ( $groups.find('.wpforms-conditional-group').length > 1 ) {
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
				$groupLast = $this.parent().find('.wpforms-conditional-group').last(),
				$newGroup  = $groupLast.clone();
				$newGroup.find('tr').not(':first').remove();
			var	$field     = $newGroup.find('.wpforms-conditional-field'),
				$operator  = $newGroup.find('.wpforms-conditional-operator'),
				data       = WPFormsConditionals.conditionalData($field),
				groupID    = Number(data.groupID)+1,
				ruleID     = 0,
				name       = data.inputName+'['+groupID+']['+ruleID+']';

			$newGroup.find('option:selected').prop('selected', false);
			$newGroup.find('.value').empty().append( $('<select>') );
			$field.attr('name', name+'[field]').attr('data-ruleid', ruleID).attr('data-groupid', groupID);
			$operator.attr('name', name+'[operator]');
			$this.before($newGroup);
		},


		//--------------------------------------------------------------------//
		// Helper functions
		//--------------------------------------------------------------------//

		/**
		 * Return various data for the conditional field.
		 *
		 * @since 1.0.0
		 */
		conditionalData: function(el) {

			var $this = $(el);
			var data  = {
				fields     : wpf.getFields(),
				inputBase  : $this.closest('.wpforms-conditional-row').attr('data-input-name'),
				fieldID    : $this.closest('.wpforms-conditional-row').attr('data-field-id'),
				ruleID     : $this.attr('data-ruleid'),
				groupID    : $this.attr('data-groupid'),
				selectedID : $this.find(':selected').val()
			}

			data.inputName = data.inputBase+'[conditionals]';
			if (data.selectedID.length) {
				data.field = data.fields['field_'+data.selectedID];
			} else {
				data.field = false;
			}
			return data;
		}
	};

	WPFormsConditionals.init();
})(jQuery);