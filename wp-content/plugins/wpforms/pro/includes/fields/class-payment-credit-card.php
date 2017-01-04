<?php
/**
 * Name text field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_CreditCard extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name  = __( 'Credit Card', 'wpforms' );
		$this->type  = 'credit-card';
		$this->icon  = 'fa-credit-card';
		$this->order = 9;
		$this->group = 'payment';

		// Set field to required by default
		add_filter( 'wpforms_field_new_required', array( $this, 'default_required' ), 10, 2 );
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
		$this->field_option( 'required',      $field, array( 'default' => 1 ) );
		$this->field_option( 'basic-options', $field, array( 'markup' => 'close' ) );

		//--------------------------------------------------------------------//
		// Advanced field options
		//--------------------------------------------------------------------//

		$this->field_option( 'advanced-options', $field, array( 'markup' => 'open' ) );
		$this->field_option( 'size',             $field );

		// Card Number
		$cardnumber_placeholder = !empty( $field['cardnumber_placeholder'] ) ? esc_attr( $field['cardnumber_placeholder'] ) : '';
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-cardnumber" id="wpforms-field-option-row-%d-cardnumber" data-subfield="cardnumber" data-field-id="%d">', $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'cardnumber_placeholder', 'value' => __( 'Card Number Placeholder Text', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder-update" id="wpforms-field-option-%d-cardnumber_placeholder" name="fields[%d][cardnumber_placeholder]" value="%s" data-field-id="%d" data-subfield="credit-card-cardnumber">', $field['id'], $field['id'], $cardnumber_placeholder, $field['id'] );
			echo '</div>';
		echo '</div>';

		// CVC/Secuity Code
		$cardcvc_placeholder = !empty( $field['cardcvc_placeholder'] ) ? esc_attr( $field['cardcvc_placeholder'] ) : '';
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-cvc" id="wpforms-field-option-row-%d-cvc" data-subfield="cvc" data-field-id="%d">', $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'cardcvc_placeholder', 'value' => __( 'Security Code Placeholder Text', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder-update" id="wpforms-field-option-%d-cardcvc_placeholder" name="fields[%d][cardcvc_placeholder]" value="%s" data-field-id="%d" data-subfield="credit-card-cardcvc">', $field['id'], $field['id'], $cardcvc_placeholder, $field['id'] );
			echo '</div>';
		echo '</div>';

		// Card Name
		$cardname_placeholder = !empty( $field['cardname_placeholder'] ) ? esc_attr( $field['cardname_placeholder'] ) : '';
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-cardname" id="wpforms-field-option-row-%d-cardname" data-subfield="cardname" data-field-id="%d">', $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'cardname_placeholder', 'value' => __( 'Name on Card Placeholder Text', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder-update" id="wpforms-field-option-%d-cardname_placeholder" name="fields[%d][cardname_placeholder]" value="%s" data-field-id="%d" data-subfield="credit-card-cardname">', $field['id'], $field['id'], $cardname_placeholder, $field['id'] );
			echo '</div>';
		echo '</div>';

		$this->field_option( 'label_hide',       $field );
		$this->field_option( 'sublabel_hide',    $field );
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

		$cardnumber_placeholder = !empty( $field['cardnumber_placeholder'] ) ? esc_attr( $field['cardnumber_placeholder'] ) : '';
		$cardcvc_placeholder    = !empty( $field['cardcvc_placeholder'] ) ? esc_attr( $field['cardcvc_placeholder'] ) : '';
		$cardname_placeholder   = !empty( $field['cardname_placeholder'] ) ? esc_attr( $field['cardname_placeholder'] ) : '';

		$this->field_preview_option( 'label', $field );

			echo '<div class="format-selected format-selected-full">';

				// Card Number
				echo '<div class="wpforms-field-row">';

					echo '<div class="wpforms-credit-card-cardnumber">';
						printf( '<label class="wpforms-sub-label">%s</label>', __( 'Card Number', 'wpforms') );
						printf( '<input type="text" placeholder="%s" disabled>', $cardnumber_placeholder );
					echo '</div>';

					echo '<div class="wpforms-credit-card-cardcvc">';
						printf( '<label class="wpforms-sub-label">%s</label>', __( 'Security Code', 'wpforms') );
						printf( '<input type="text" placeholder="%s" disabled>', $cardcvc_placeholder );
					echo '</div>';

				echo '</div>';

				echo '<div class="wpforms-field-row">';

					echo '<div class="wpforms-credit-card-cardname">';
						printf( '<label class="wpforms-sub-label">%s</label>', __( 'Name on Card', 'wpforms') );
						printf( '<input type="text" placeholder="%s" disabled>', $cardname_placeholder );
					echo '</div>';

					echo '<div class="wpforms-credit-card-expiration">';
						printf( '<label class="wpforms-sub-label">%s</label>', __( 'Expiration', 'wpforms') );

						echo '<div class="wpforms-credit-card-cardmonth">';
							echo '<select disabled><option>MM</option></select>';
						echo '</div>';

						echo '<span>/</span>';

						echo '<div class="wpforms-credit-card-cardyear">';
							echo '<select disabled><option>YY</option></select>';
						echo '</div>';

					echo '</div>';

				echo '</div>';

			echo '</div>';

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
		$field                  = apply_filters( 'wpforms_address_creditcard_display', $field, $field_atts, $form_data );
		$field_required         = !empty( $field['required'] ) ? ' required' : '';
		$field_class            = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id               = absint( $field['id'] );
		$field_sublabel         = !empty( $field['sublabel_hide'] ) ? 'wpforms-sublabel-hide' : '';
		$cardnumber_placeholder = !empty( $field['cardnumber_placeholder'] ) ? esc_attr( $field['cardnumber_placeholder'] ) : '';
		$cardcvc_placeholder    = !empty( $field['cardcvc_placeholder'] ) ? esc_attr( $field['cardcvc_placeholder'] ) : '';
		$cardname_placeholder   = !empty( $field['cardname_placeholder'] ) ? esc_attr( $field['cardname_placeholder'] ) : '';
		$form_id              	= absint( $form_data['id'] );

		// If the field is displayed on a non-SSL site we need to use
		if ( !is_ssl() ) {
			echo '<div class="wpforms-cc-warning wpforms-error-alert">';
			_e( 'This page is insecure. Credit Card field should be used for testing purposes only.', 'wpforms' );
			echo '</div>';
		}

		// CC Number
		printf( '<div class="wpforms-field-row %s">', $field_class );

			echo '<div class="wpforms-field-credit-card-number">';

				$cardnumber_class  = 'wpforms-field-credit-card-cardnumber';
				$cardnumber_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
				$cardnumber_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['cardnumber'] ) ? ' wpforms-error' : '';

				printf( '<label for="wpforms-%d-field_%d" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, __( 'Card Number', 'wpforms' ) );

				printf(
					'<input type="text" id="%s" class="%s" placeholder="%s" value="" data-rule-creditcard="true" %s>',
					"wpforms-{$form_id}-field_{$field['id']}",
					$cardnumber_class,
					$cardnumber_placeholder,
					$field_required
				);

				if ( !empty( wpforms()->process->errors[$form_id][$field_id]['cardnumber'] ) ) {
					printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['cardnumber'] ) );
				}

			echo '</div>';

			echo '<div class="wpforms-field-credit-card-code">';

				$cardcvc_class  = 'wpforms-field-credit-card-cardcvc';
				$cardcvc_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
				$cardcvc_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['cardcvc'] ) ? ' wpforms-error' : '';

				printf( '<label for="wpforms-%d-field_%d-cardcvc" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, __( 'Security Code', 'wpforms' ) );

				printf(
					'<input type="text" id="%s" maxlength="4" class="%s" placeholder="%s" autocomplete="off" %s>',
					"wpforms-{$form_id}-field_{$field['id']}-cardcvc",
					$cardcvc_class,
					$cardcvc_placeholder,
					$field_required
				);

				if ( !empty( wpforms()->process->errors[$form_id][$field_id]['cardcvc'] ) ) {
					printf( '<label id="wpforms-%d-field_%d-cardcvc-error" class="wpforms-error" for="wpforms-field_%d-cardcvc">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['cardcvc'] ) );
				}

			echo '</div>';

		echo '</div>';

		printf( '<div class="wpforms-field-row %s">', $field_class );

			// Name
			echo '<div class="wpforms-field-credit-card-name">';

				$cardname_class  = 'wpforms-field-credit-card-cardname';
				$cardname_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
				$cardname_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['cardname'] ) ? ' wpforms-error' : '';

				printf( '<label for="wpforms-%d-field_%d-cardname" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, __( 'Name on Card', 'wpforms' ) );

				printf(
					'<input type="text" id="%s" class="%s" placeholder="%s" %s>',
					"wpforms-{$form_id}-field_{$field_id}-cardname",
					$cardname_class,
					$cardname_placeholder,
					$field_required
				);

				if ( !empty( wpforms()->process->errors[$form_id][$field_id]['cardname'] ) ) {
					printf( '<label id="wpforms-%d-field_%d-cardname-error" class="wpforms-error" for="wpforms-field_%d-cardname">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['cardname'] ) );
				}

			echo '</div>';

			// Name
			echo '<div class="wpforms-field-credit-card-expiration">';

				printf( '<label for="wpforms-%d-field_%d-cardmonth" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, __( 'Expiration', 'wpforms' ) );

				$cardmonth_class  = 'wpforms-field-credit-card-cardmonth';
				$cardmonth_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
				$cardmonth_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['cardmonth'] ) ? ' wpforms-error' : '';

				printf(
					'<select id="%s" class="%s" %s>',
					"wpforms-{$form_id}-field_{$field['id']}-cardmonth",
					$cardmonth_class,
					$field_required
				);
					echo '<option class="placeholder" selected disabled>' . __( 'MM', 'wpforms' ) . '</option>';
					for ($i=1; $i < 13; $i++) {
						printf( '<option value="%d">%d</option>', $i, $i );
					}
				echo '</select>';

				echo '<span>/</span>';

				$cardyear_class  = 'wpforms-field-credit-card-cardyear';
				$cardyear_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
				$cardyear_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['cardyear'] ) ? ' wpforms-error' : '';

				printf(
					'<select id="%s" class="%s" %s>',

					"wpforms-{$form_id}-field_{$field['id']}-cardyear",
					$cardyear_class,
					$field_required
				);
					echo '<option class="placeholder" selected disabled>' . __( 'YY', 'wpforms' ) . '</option>';
					for ($i=date('y'); $i < date('y')+11; $i++) {
						printf( '<option value="%d">%d</option>', $i, $i );
					}
				echo '</select>';

				if ( !empty( wpforms()->process->errors[$form_id][$field_id]['cardmonth'] ) ) {
					printf( '<label id="wpforms-%d-field_%d-cardmonth-error" class="wpforms-error" for="wpforms-field_%d-cardmonth">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['cardmonth'] ) );
				}
				if ( !empty( wpforms()->process->errors[$form_id][$field_id]['cardyear'] ) ) {
					printf( '<label id="wpforms-%d-field_%d-cardyear-error" class="wpforms-error" for="wpforms-field_%d-cardyear">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['cardyear'] ) );
				}

			echo '</div>';

		echo '</div>';
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

		// Currently validation happens on the front end. We do not do
		// generic server-side validaton because we do not allow the card
		// details to POST to the server.
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

		$name = !empty( $form_data['fields'][$field_id]['label'] ) ? $form_data['fields'][$field_id]['label'] : '';

		wpforms()->process->fields[$field_id] = array(
			'name'     => sanitize_text_field( $name ),
			'value'    => '',
			'id'       => absint( $field_id ),
			'type'     => $this->type,
		);
	}

	/**
	 * Default to required
	 *
	 * @since 1.0.9
	 * @param bool $field_required
	 * @param array $field
	 * @return bool
	 */
	public function default_required( $field_required, $field ) {

		if ( $field['type'] == 'credit-card' ) {
			return true;
		}
		return $field_required;
	}
}
new WPForms_Field_CreditCard;