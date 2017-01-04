<?php
/**
 * Single line text field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_Payment_Total extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name  = __( 'Total', 'wpforms' );
		$this->type  = 'payment-total';
		$this->icon  = 'fa-money';
		$this->order = 11;
		$this->group = 'payment';

		add_filter( 'wpforms_process_filter', array( $this, 'calculate_total' ), 10, 3 );
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

		$this->field_option( 'basic-options', $field, array( 'markup' => 'open' ) );
		$this->field_option( 'label',         $field );
		$this->field_option( 'description',   $field );
		$this->field_option( 'basic-options', $field, array( 'markup' => 'close' ) );

		//--------------------------------------------------------------------//
		// Advanced field options
		//--------------------------------------------------------------------//

		$this->field_option( 'advanced-options', $field, array( 'markup' => 'open' ) );
		$this->field_option( 'label_hide',       $field );
		$this->field_option( 'css',              $field );
		$this->field_option( 'advanced-options', $field, array( 'markup' => 'close' ) );
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 * @param array $field
	 */
	public function field_preview( $field ) {

		$this->field_preview_option( 'label', $field );

		echo '<div>' . wpforms_format_amount( 0, true ) . '</div>';

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

		$field_id = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_id'] ) );

		// Primary total display
		echo '<div class="wpforms-payment-total">' . wpforms_format_amount( 0, true ) . '</div>';

		printf( '<input type="hidden" name="wpforms[fields][%d]" id="%s" class="wpforms-payment-total" value="0">', $field['id'], $field_id );
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

		$name   = !empty( $form_data['fields'][$field_id]['label'] ) ? sanitize_text_field( $form_data['fields'][$field_id]['label'] ) : '';
		$amount = wpforms_sanitize_amount( $field_submit );

		wpforms()->process->fields[$field_id] = array(
			'name'       => $name,
			'value'      => wpforms_format_amount( $amount, true ),
			'amount'     => wpforms_format_amount( $amount ),
			'amount_raw' => $amount,
			'id'         => absint( $field_id ),
			'type'       => $this->type,
		);
	}

	/**
	 * Do not trust the posted total since that relies on javascript
	 *
	 * Instead we re-calculate server side.
	 *
	 * @since 1.0.0
	 * @param array $fields
	 * @param array $entry
	 * @param array $form_data
	 * @return array
	 */
	public function calculate_total( $fields, $entry, $form_data ) {

		// At this point we have passed processing and validation, so we know
		// the amounts in $fields are safe to use.
		$total  = wpforms_get_total_payment( $fields );
		$amount = wpforms_sanitize_amount( $total );

		foreach( $fields as $id => $field ) {
			if ( $field['type'] == 'payment-total' ) {
				$fields[$id]['value']      = wpforms_format_amount( $amount, true );
				$fields[$id]['amount']     = wpforms_format_amount( $amount );
				$fields[$id]['amount_raw'] = $amount;
			}
		}

		return $fields;
	}
}
new WPForms_Field_Payment_Total;