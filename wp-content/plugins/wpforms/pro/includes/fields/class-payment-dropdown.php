<?php
/**
 * Dropdown payment field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.3.1
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_Payment_Select extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.3.1
	 */
	public function init() {

		// Define field type information
		$this->name     = __( 'Dropdown Items', 'wpforms' );
		$this->type     = 'payment-select';
		$this->icon     = 'fa-caret-square-o-down';
		$this->order    = 7;
		$this->group    = 'payment';
		$this->defaults = array(
			1 => array(
				'label' => __( 'First Item', 'wpforms' ),
				'value' => '10.00',
				'default' => '',
			),
			2 => array(
				'label' => __( 'Second Item', 'wpforms' ),
				'value' => '25.00',
				'default' => '',
			),
			3 => array(
				'label' => __( 'Third Item', 'wpforms' ),
				'value' => '50.00',
				'default' => '',
			),
		);
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.3.1
	 * @param array $field
	 */
	public function field_options( $field ) {

		//--------------------------------------------------------------------//
		// Basic field options
		//--------------------------------------------------------------------//

		// Options open markup
		$this->field_option( 'basic-options', $field, array( 'markup' => 'open' ) );

		// Label
		$this->field_option( 'label', $field );

		// Choices option
		$this->field_option( 'choices_payments', $field );

		// Description
		$this->field_option( 'description', $field );

		// Required toggle
		$this->field_option( 'required', $field );

		// Options close markup
		$this->field_option( 'basic-options', $field, array( 'markup' => 'close' ) );

		//--------------------------------------------------------------------//
		// Advanced field options
		//--------------------------------------------------------------------//

		// Options open markup
		$this->field_option( 'advanced-options', $field, array( 'markup' => 'open' ) );

		// Size
		$this->field_option( 'size', $field );

		// Placeholder
		$this->field_option( 'placeholder', $field );

		// Hide label
		$this->field_option( 'label_hide', $field );

		// Custom CSS classes
		$this->field_option( 'css', $field );

		// Options close markup
		$this->field_option( 'advanced-options', $field, array( 'markup' => 'close' ) );
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.3.1
	 * @param array $field
	 */
	public function field_preview( $field ) {

		$placeholder = !empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
		$values      = !empty( $field['choices'] ) ? $field['choices'] : $this->defaults;
		$dynamic     = !empty( $field['dynamic_choices'] ) ? $field['dynamic_choices'] : false;

		// Label
		$this->field_preview_option( 'label', $field );

		// Field select element
		echo '<select class="primary-input" disabled>';

			// Optional placeholder
			if ( !empty( $placeholder ) ) {
				printf( '<option value="" class="placeholder">%s</option>', $placeholder );
			}

			// Build the select options (even though user can only see 1st option)
			foreach ( $values as $key => $value ) {

				$default  = isset( $value['default'] ) ? $value['default'] : '';
				$selected = !empty( $placeholder ) ? '' : selected( '1', $default, false );

				printf( '<option %s>%s</option>', $selected, $value['label'] );
			}

		echo '</select>';

		// Description
		$this->field_preview_option( 'description', $field );
	}

	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.3.1
	 * @param array $field
	 * @param array $form_data
	 */
	public function field_display( $field, $field_atts, $form_data ) {

		// Setup and sanitize the necessary data
		$field             = apply_filters( 'wpforms_payment_select_field_display', $field, $field_atts, $form_data );
		$field_placeholder = !empty( $field['placeholder']) ? esc_attr( $field['placeholder'] ) : '';
		$field_required    = !empty( $field['required'] ) ? ' required' : '';
		$field_class       = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id          = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_id'] ) );
		$field_data        = '';
		$choices           = $field['choices'];
		$has_default       = false;

		if ( !empty( $field_atts['input_data'] ) ) {
			foreach ( $field_atts['input_data'] as $key => $val ) {
			  $field_data .= ' data-' . $key . '="' . $val . '"';
			}
		}

		// Check to see if any of the options have selected by default
		foreach ( $choices as $choice ) {
			if ( isset( $choice['default'] ) ) {
				$has_default = true;
				break;
			}
		}

		// Primary select field
		printf( '<select name="wpforms[fields][%d]" id="%s" class="wpforms-payment-price %s" %s %s>',
			$field['id'],
			$field_id,
			$field_class,
			$field_required,
			$field_data
		);

			// Optional placeholder
			if ( !empty( $field_placeholder ) ) {
				printf('<option value="" class="placeholder" disabled %s>%s</option>', selected( false, $has_default, true ), $field_placeholder );
			}

			// Build the select options
			foreach ( $choices as $key => $choice ) {

				$selected = isset( $choice['default'] ) && empty( $field_placeholder ) ? '1' : '0' ;
				$amount   = wpforms_format_amount( wpforms_sanitize_amount( $choice['value'] ) );

				printf( '<option value="%s" data-amount="%s" %s>%s</option>', $key, $amount, selected( '1', $selected, false ), $choice['label'] );
			}

		echo '</select>';
	}

	/**
	 * Validates field on form submit.
	 *
	 * @since 1.3.1
	 * @param int $field_id
	 * @param array $field_submit
	 * @param array $form_data
	 */
	public function validate( $field_id, $field_submit, $form_data ) {

		// Basic required check - If field is marked as required, check for entry data
		if ( !empty( $form_data['fields'][$field_id]['required'] ) && empty( $field_submit ) ) {

			wpforms()->process->errors[$form_data['id']][$field_id] = apply_filters( 'wpforms_required_label', __( 'This field is required', 'wpforms' ) );
		}

		// Validate that the option selected is real
		if ( !empty( $field_submit ) && empty( $form_data['fields'][$field_id]['choices'][$field_submit] )  ) {

			wpforms()->process->errors[$form_data['id']][$field_id] = __( 'Invalid payment option', 'wpforms' );
		}
	}

	/**
	 * Formats and sanitizes field.
	 *
	 * @since 1.3.1
	 * @param int $field_id
	 * @param array $field_submit
	 * @param array $form_data
	 */
	public function format( $field_id, $field_submit, $form_data ) {

		$choice_label = '';
		$field        = $form_data['fields'][$field_id];
		$name         = !empty( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';

		// Fetch the amount
		if ( !empty( $field['choices'][$field_submit]['value'] ) ) {
			$amount = wpforms_sanitize_amount( $field['choices'][$field_submit]['value'] );
		} else {
			$amount = 0;
		}

		$value = wpforms_format_amount( $amount, true );

		if ( empty( $field_submit ) ) {
			$value = '';
		} elseif ( !empty( $field['choices'][$field_submit]['label'] ) ) {
			$choice_label = sanitize_text_field( $field['choices'][$field_submit]['label'] );
			$value        = $choice_label . ' - ' . $value;
		}

		wpforms()->process->fields[$field_id] = array(
			'name'         => $name,
			'value'        => $value,
			'value_choice' => $choice_label,
			'value_raw'    => sanitize_text_field( $field_submit ),
			'amount'       => wpforms_format_amount( $amount ),
			'amount_raw'   => $amount,
			'currency'     => wpforms_setting( 'currency', 'USD' ),
			'id'           => absint( $field_id ),
			'type'         => $this->type,
		);
	}
}
new WPForms_Field_Payment_Select;