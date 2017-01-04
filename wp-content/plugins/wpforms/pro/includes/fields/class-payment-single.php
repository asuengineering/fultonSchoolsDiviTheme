<?php
/**
 * Single item payment field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_Payment_Single extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name  = __( 'Single Item', 'wpforms' );
		$this->type  = 'payment-single';
		$this->icon  = 'fa-file-o';
		$this->order = 3;
		$this->group = 'payment';

		add_filter( 'wpforms_field_atts', array( $this, 'front_visiblity' ), 10, 3 );
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
		
		//$this->field_option( 'meta',        $field );
		$this->field_option( 'basic-options', $field, array( 'markup' => 'open' ) );
		$this->field_option( 'label',         $field );
		$this->field_option( 'description',   $field );

		// Item Price
		$price   = !empty( $field['price'] ) ? wpforms_format_amount( wpforms_sanitize_amount( $field['price'] ) ) : '';
		$tooltip = __( 'Enter the price of the item, without a currency symbol.', 'wpforms' );
		$output  = $this->field_element( 'label', $field, array( 'slug' => 'price', 'value' => __( 'Item Price', 'wpforms' ), 'tooltip' => $tooltip ), false );
		$output .= $this->field_element( 'text',  $field, array( 'slug' => 'price', 'value' => $price, 'class' => 'wpforms-money-input', 'placeholder' => wpforms_format_amount( 0 ) ), false );
		$this->field_element( 'row', $field, array( 'slug' => 'price', 'content' => $output ) );

		// Item Format option
		$format  = !empty( $field['format'] ) ? esc_attr( $field['format'] ) : 'date-time';
		$tooltip = __( 'Select the item type.', 'wpforms' );
		$options = array(
			'single' => __( 'Single Item', 'wpforms' ),
			'user'   => __( 'User Defined', 'wpforms' ),
			'hidden' => __( 'Hidden', 'wpforms' ),
		);
		$output  = $this->field_element( 'label',  $field, array( 'slug' => 'format', 'value' => __( 'Item Type', 'wpforms' ), 'tooltip' => $tooltip ), false );
		$output .= $this->field_element( 'select', $field, array( 'slug' => 'format', 'value' => $format, 'options' => $options ), false );
		$this->field_element( 'row',    $field, array( 'slug' => 'format', 'content' => $output ) );

		$this->field_option( 'required',      $field );
		$this->field_option( 'basic-options', $field, array( 'markup' => 'close' ) );
	
		//--------------------------------------------------------------------//
		// Advanced field options
		//--------------------------------------------------------------------//
	
		$this->field_option( 'advanced-options', $field, array( 'markup' => 'open' ) );
		$this->field_option( 'size',             $field );
		$this->field_option( 'placeholder',      $field );
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

		$price       = !empty( $field['price'] ) ? wpforms_format_amount( wpforms_sanitize_amount( $field['price'] ), true ) : wpforms_format_amount( 0, true );
		$placeholder = !empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : wpforms_format_amount( 0 );
		$format      = !empty( $field['format'] ) ? esc_html( $field['format'] ) : 'single';
		$value       = !empty( $field['price'] ) ? wpforms_format_amount( wpforms_sanitize_amount( $field['price'] ) ) : '';

		echo '<div class="format-selected-' . $format . ' format-selected">';

			$this->field_preview_option( 'label', $field );

			printf( '<p class="item-price">%s: <span class="price">%s</span></p>', __( 'Price', 'wpforms' ), $price );
			printf( '<input type="text" placeholder="%s" class="primary-input" value="%s" disabled>', $placeholder, $value );

			$this->field_preview_option( 'description', $field );

			echo '<p class="item-price-hidden">';
				_e( 'Note: Item type is set to hidden and will not be visible when viewing the form.', 'wpforms' );
			echo '</p>';

		echo '</div>';
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
		$field_atts['input_class'][] = 'wpforms-payment-price';
		$field                 = apply_filters( 'wpforms_payment_single_field_display', $field, $field_atts, $form_data );
		$field_placeholder     = !empty( $field['placeholder']) ? esc_attr( $field['placeholder'] ) : '';
		$field_required        = !empty( $field['required'] ) ? ' required' : '';
		$field_class           = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id              = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_id'] ) );
		$field_value           = !empty( $field['price'] ) ? wpforms_sanitize_amount( $field['price'] ) : '';
		$field_value_formatted = !empty( $field_value ) ? wpforms_format_amount( $field_value ) : ''; 
		$field_data            = '';
		$field_format          = !empty( $field['format'] ) ? $field['format'] : 'single';

		if ( !empty( $field_atts['input_data'] ) ) {
			foreach ( $field_atts['input_data'] as $key => $val ) {
			  $field_data .= ' data-' . $key . '="' . $val . '"';
			}
		}

		if ( $field_format == 'single' || $field_format == 'hidden'  ) :

			if ( $field_format == 'single' ) {
				printf(
					'<div class="wpforms-single-item-price">%s: <span class="wpforms-price">%s</span></div>',
					__( 'Price', 'wpforms' ),
					wpforms_format_amount( $field_value, true )
				);
			}

			// Primary price field
			printf( 
				'<input type="hidden" name="wpforms[fields][%d]" id="%s" class="%s" value="%s">',
				$field['id'],
				$field_id,
				$field_class,
				$field_value_formatted
			);

		elseif ( $field_format == 'user' ) :

			// Max file size
			$field_data .= ' data-rule-currency=\'["$",false]\''; 

			// Primary text field
			printf( 
				'<input type="text" name="wpforms[fields][%d]" id="%s" class="%s wpforms-payment-user-input" value="%s" placeholder="%s" %s %s>',
				$field['id'],
				$field_id,
				$field_class,
				$field_value_formatted,
				$field_placeholder,
				$field_required,
				$field_data
			);

		endif;
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

		// If field is required, check for data
		if ( !empty( $form_data['fields'][$field_id]['required'] ) && empty( $field_submit ) ) {
			
			wpforms()->process->errors[$form_data['id']][$field_id] = apply_filters( 'wpforms_required_label', __( 'This field is required', 'wpforms' ) );
			return;
		}

		// If field format is not user provided, validate the amount posted
		if ( !empty( $field_submit ) && $form_data['fields'][$field_id]['format'] != 'user' ) {

			$price  = wpforms_sanitize_amount( $form_data['fields'][$field_id]['price'] );
			$submit = wpforms_sanitize_amount( $field_submit );
			if ( $price !== $submit ) {
				wpforms()->process->errors[$form_data['id']][$field_id] =  __( 'Amount mismatch', 'wpforms' );
			}
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

		$field = $form_data['fields'][$field_id];
		$name  = !empty( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '';

		// Only trust the value if the field is user format
		if ( $field['format'] == 'user' ) { 
			$amount = wpforms_sanitize_amount( $field_submit );
		} else {
			$amount = wpforms_sanitize_amount( $field['price'] );
		}

		wpforms()->process->fields[$field_id] = array(
			'name'       => $name,
			'value'      => wpforms_format_amount( $amount, true ),
			'amount'     => wpforms_format_amount( $amount ),
			'amount_raw' => $amount,
			'currency'   => wpforms_setting( 'currency', 'USD' ),
			'id'         => absint( $field_id ),
			'type'       => $this->type,
		);
	}

	/**
	 * This filter is used to toggle the visbility on the field when displaying
	 * on the front-end. 
	 * 
	 * If the format is set to hidden make sure the field does not show.
	 *
	 * @since 1.0.0
	 * @param array $field_atts
	 * @param array $field
	 * @param array $form_data
	 * @return array
	 */
	public function front_visiblity( $field_atts, $field, $form_data ) {

		if ( !empty( $field['format'] ) && $field['format'] == 'hidden' ) {
			$field_atts['field_class'][] = 'wpforms-field-hidden';
		}
		return $field_atts;
	}
}
new WPForms_Field_Payment_Single;