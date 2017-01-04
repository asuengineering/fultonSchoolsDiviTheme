<?php
/**
 * Multiple item payment field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_Payment_Multiple extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name     = __( 'Multiple Items', 'wpforms' );
		$this->type     = 'payment-multiple';
		$this->icon     = 'fa-list-ul';
		$this->order    = 5;
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
	 * @since 1.0.0
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

		// Input columns
		$this->field_option( 'input_columns', $field );

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
	 * @since 1.0.0
	 * @param array $field
	 */
	public function field_preview( $field ) {

		$values = !empty( $field['choices'] ) ? $field['choices'] : $this->defaults;

		$this->field_preview_option( 'label', $field );

		echo '<ul class="primary-input">';
		foreach ( $values as $key => $value ) {
			$value['default'] = isset( $value['default'] ) ? $value['default'] : '';
			printf( '<li><input type="radio" %s disabled>%s</li>', checked( '1', $value['default'], false ), $value['label'] );
		}
		echo '</ul>';

		$this->field_preview_option( 'description', $field );
	}

	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.0.0
	 * @param array $field
	 * @param array $form_data
	 */
	public function field_display( $field, $field_atts, $form_data ) {

		// Setup and sanitize the necessary data
		$field             = apply_filters( 'wpforms_payment_multiple_field_display', $field, $field_atts, $form_data );
		$field_required    = !empty( $field['required'] ) ? ' required' : '';
		$field_class       = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id          = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_id'] ) );
		$field_data        = '';
		$form_id           = absint( $form_data['id'] );

		if ( !empty( $field_atts['input_data'] ) ) {
			foreach ( $field_atts['input_data'] as $key => $val ) {
			  $field_data .= ' data-' . $key . '="' . $val . '"';
			}
		}

		// Primary radio button field
		printf( '<ul id="%s" class="%s" %s>', $field_id, $field_class, $field_data );
			foreach( $field['choices'] as $key => $choice ) {
				$selected = isset( $choice['default'] ) ? '1' : '0';
				$amount   = wpforms_format_amount( wpforms_sanitize_amount( $choice['value'] ) );
				printf( '<li class="choice-%d">', $key );
					printf( '<input type="radio" id="wpforms-%d-field_%d_%d" class="wpforms-payment-price" name="wpforms[fields][%d]" value="%s" data-amount="%s" %s %s>',
						$form_id,
						$field['id'],
						$key,
						$field['id'],
						$key,
						$amount,
						checked( '1', $selected, false ),
						$field_required
					);
					printf( '<label class="wpforms-field-label-inline" for="wpforms-%d-field_%d_%d">%s</label>', $form_id, $field['id'], $key, esc_html( $choice['label'] ) );
				echo '</li>';
			}
		echo '</ul>';
	}

	/**
	 * Validates field on form submit.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
new WPForms_Field_Payment_Multiple;