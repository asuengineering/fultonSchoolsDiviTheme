<?php
/**
 * HTML block text field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_HTML extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name  = __( 'HTML', 'wpforms' );
		$this->type  = 'html';
		$this->icon  = 'fa-code';
		$this->order = 15;
		$this->group = 'fancy';
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
		$this->field_option( 'code',          $field );
		// Disable label on frontend
		$this->field_element( 'text', $field, array( 'type' => 'hidden', 'slug' => 'label_disable', 'value' => '1' ) );
		$this->field_option( 'basic-options', $field, array( 'markup' => 'close' ) );

		//--------------------------------------------------------------------//
		// Advanced field options
		//--------------------------------------------------------------------//
	
		$this->field_option( 'advanced-options', $field, array( 'markup' => 'open' ) );
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

		echo '<div class="code-block">';
			echo '<label class="label-title">';
			 	echo '<i class="fa fa-code"></i> ';
				_e( 'HTML / Code Block', 'wpforms' );
			echo '</label>';
			echo '<div class="description">';
				_e( 'Contents of this field are not displayed in the admin area.', 'wpforms' );
			echo '</div>';
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
		$field             = apply_filters( 'wpforms_html_field_display', $field, $field_atts, $form_data );
		$field_class       = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id          = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_id'] ) );
		$field_value       = !empty( $field['code' ] ) ? $field['code'] : '';
		$field_data        = '';

		if ( !empty( $field_atts['input_data'] ) ) {
			foreach ( $field_atts['input_data'] as $key => $val ) {
			  $field_data .= ' data-' . $key . '="' . $val . '"';
			}
		}

		// Primary code field
		printf( 
			'<div id="%s" class="%s" %s>%s</div>',
			$field_id,
			$field_class,
			$field_data,
			$field_value
		);
	}

	/**
	 * Formats field.
	 *
	 * @since 1.0.0
	 * @param int $field_id
	 * @param array $field_submit
	 * @param array $form_data
	 */
	public function format( $field_id, $field_submit, $form_data ) {

		return;
	}
}
new WPForms_Field_HTML;