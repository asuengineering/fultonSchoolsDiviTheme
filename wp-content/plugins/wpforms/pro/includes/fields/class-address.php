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
class WPForms_Field_Address extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name    = __( 'Address', 'wpforms' );
		$this->type    = 'address';
		$this->icon    = 'fa-map-marker';
		$this->order   = 7;
		$this->group   = 'fancy';
		$this->schemes = array(
			'us' => array(
				'label'          => __( 'US', 'wpforms' ),
				'address1_label' => __( 'Address Line 1', 'wpforms' ),
				'address2_label' => __( 'Address Line 2', 'wpforms' ),
				'city_label'     => __( 'City', 'wpforms' ),
				'state_label'    => __( 'State', 'wpforms' ),
				'postal_label'   => __( 'Zip Code', 'wpforms' ),
				'state_label'    => __( 'State', 'wpforms' ),
				'states'         => wpforms_us_states(),
			),
			'international' => array(
				'label'          => __( 'International', 'wpforms' ),
				'address1_label' => __( 'Address Line 1', 'wpforms' ),
				'address2_label' => __( 'Address Line 2', 'wpforms' ),
				'city_label'     => __( 'City', 'wpforms' ),
				'state_label'    => __( 'State', 'wpforms' ),
				'postal_label'   => __( 'Postal Code', 'wpforms' ),
				'state_label'    => __( 'State / Province / Region', 'wpforms' ),
				'states'         => '',
				'country_label'  => __( 'Country', 'wpforms' ),
				'countries'      => wpforms_countries(),
			),
		);

		// Allow for additional or customizing address schemes
		$this->schemes = apply_filters( 'wpforms_address_schemes', $this->schemes );
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

		// Address Scheme - was "format" key prior to 1.2.7
		$scheme = !empty( $field['scheme'] ) ? esc_attr( $field['scheme'] ) : 'us';
		if ( empty( $scheme ) && !empty( $field['format'] ) ) {
			$scheme = esc_attr( $field['format'] );
		}
		$tooltip = __( 'Select scheme format for the address field.', 'wpforms' );
		$options = array();
		foreach( $this->schemes as $slug => $s ) {
			$options[$slug] = $s['label'];
		}
		$output  = $this->field_element( 'label',  $field, array( 'slug' => 'scheme', 'value' => __( 'Scheme', 'wpforms' ), 'tooltip' => $tooltip ), false );
		$output .= $this->field_element( 'select', $field, array( 'slug' => 'scheme', 'value' => $scheme, 'options' => $options ), false );
		$this->field_element( 'row', $field, array( 'slug' => 'scheme', 'content' => $output ) );

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

		// Address Line 1
		$address1_placeholder = !empty( $field['address1_placeholder'] ) ? esc_attr( $field['address1_placeholder'] ) : '';
		$address1_default     = !empty( $field['address1_default'] ) ? esc_attr( $field['address1_default'] ) : '';
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-address1" id="wpforms-field-option-row-%d-address1" data-subfield="address-1" data-field-id="%d">', $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'address1_placeholder', 'value' => __( 'Address Line 1', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder" id="wpforms-field-option-%d-address1_placeholder" name="fields[%d][address1_placeholder]" value="%s">', $field['id'], $field['id'], $address1_placeholder );
				printf( '<label for="wpforms-field-option-%d-address1_placeholder" class="sub-label">%s</label>', $field['id'], __( 'Placeholder', 'wpforms' ) );
			echo '</div>';
			echo '<div class="default">';
				printf( '<input type="text" class="default" id="wpforms-field-option-%d-address1_default" name="fields[%d][address1_default]" value="%s">', $field['id'], $field['id'], $address1_default );
				printf( '<label for="wpforms-field-option-%d-address1_default" class="sub-label">%s</label>', $field['id'], __( 'Default Value', 'wpforms' ) );
			echo '</div>';
		echo '</div>';

		// Address Line 2
		$address2_placeholder = !empty( $field['address2_placeholder'] ) ? esc_attr( $field['address2_placeholder'] ) : '';
		$address2_default     = !empty( $field['address2_default'] ) ? esc_attr( $field['address2_default'] ) : '';
		$address2_hide        = !empty( $field['address2_hide'] ) ? true : false;
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-address2" id="wpforms-field-option-row-%d-address2" data-subfield="address-2" data-field-id="%d">', $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'address2_placeholder', 'value' => __( 'Address Line 2', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder" id="wpforms-field-option-%d-address2_placeholder" name="fields[%d][address2_placeholder]" value="%s">', $field['id'], $field['id'], $address2_placeholder );
				printf( '<label for="wpforms-field-option-%d-address2_placeholder" class="sub-label">%s</label>', $field['id'], __( 'Placeholder', 'wpforms' ) );
			echo '</div>';
			echo '<div class="default">';
				printf( '<input type="text" class="default" id="wpforms-field-option-%d-address2_default" name="fields[%d][address2_default]" value="%s">', $field['id'], $field['id'], $address2_default );
				printf( '<label for="wpforms-field-option-%d-address2_default" class="sub-label">%s</label>', $field['id'], __( 'Default Value', 'wpforms' ) );
			echo '</div>';
			echo '<div class="hide">';
				printf(' <input type="checkbox" class="hide" name="fields[%d][address2_hide]" value="1" %s>', $field['id'], checked( true, $address2_hide, false ) );
			echo '</div>';
		echo '</div>';

		// City
		$city_placeholder = !empty( $field['city_placeholder'] ) ? esc_attr( $field['city_placeholder'] ) : '';
		$city_default     = !empty( $field['city_default'] ) ? esc_attr( $field['city_default'] ) : '';
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-city" id="wpforms-field-option-row-%d-city" data-subfield="city" data-field-id="%d">', $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'city_placeholder', 'value' => __( 'City', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder" id="wpforms-field-option-%d-city_placeholder" name="fields[%d][city_placeholder]" value="%s">', $field['id'], $field['id'], $city_placeholder );
				printf( '<label for="wpforms-field-option-%d-city_placeholder" class="sub-label">%s</label>', $field['id'], __( 'Placeholder', 'wpforms' ) );
			echo '</div>';
			echo '<div class="default">';
				printf( '<input type="text" class="default" id="wpforms-field-option-%d-city_default" name="fields[%d][city_default]" value="%s">', $field['id'], $field['id'], $city_default );
				printf( '<label for="wpforms-field-option-%d-city_default" class="sub-label">%s</label>', $field['id'], __( 'Default Value', 'wpforms' ) );
			echo '</div>';
		echo '</div>';

		// State
		$state_placeholder = !empty( $field['state_placeholder'] ) ? esc_attr( $field['state_placeholder'] ) : '';
		$state_default     = !empty( $field['state_default'] ) ? esc_attr( $field['state_default'] ) : '';
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-state" id="wpforms-field-option-row-%d-state" data-subfield="state" data-field-id="%d">', $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'state_placeholder', 'value' => __( 'State / Province / Region', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder" id="wpforms-field-option-%d-state_placeholder" name="fields[%d][state_placeholder]" value="%s">', $field['id'], $field['id'], $state_placeholder );
				printf( '<label for="wpforms-field-option-%d-state_placeholder" class="sub-label">%s</label>', $field['id'], __( 'Placeholder', 'wpforms' ) );
			echo '</div>';
			echo '<div class="default">';
				printf( '<input type="text" class="default" id="wpforms-field-option-%d-state_default" name="fields[%d][state_default]" value="%s">', $field['id'], $field['id'], $state_default );
				printf( '<label for="wpforms-field-option-%d-state_default" class="sub-label">%s</label>', $field['id'], __( 'Default Value', 'wpforms' ) );
			echo '</div>';
		echo '</div>';

		// ZIP/Postal
		$postal_placeholder = !empty( $field['postal_placeholder'] ) ? esc_attr( $field['postal_placeholder'] ) : '';
		$postal_default     = !empty( $field['postal_default'] ) ? esc_attr( $field['postal_default'] ) : '';
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-postal" id="wpforms-field-option-row-%d-postal" data-subfield="postal" data-field-id="%d">', $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'postal_placeholder', 'value' => __( 'ZIP / Postal', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder" id="wpforms-field-option-%d-postal_placeholder" name="fields[%d][postal_placeholder]" value="%s">', $field['id'], $field['id'], $postal_placeholder );
				printf( '<label for="wpforms-field-option-%d-postal_placeholder" class="sub-label">%s</label>', $field['id'], __( 'Placeholder', 'wpforms' ) );
			echo '</div>';
			echo '<div class="default">';
				printf( '<input type="text" class="default" id="wpforms-field-option-%d-postal_default" name="fields[%d][postal_default]" value="%s">', $field['id'], $field['id'], $postal_default );
				printf( '<label for="wpforms-field-option-%d-postal_default" class="sub-label">%s</label>', $field['id'], __( 'Default Value', 'wpforms' ) );
			echo '</div>';
		echo '</div>';

		// Country
		$country_placeholder = !empty( $field['country_placeholder'] ) ? esc_attr( $field['country_placeholder'] ) : '';
		$country_default     = !empty( $field['country_default'] ) ? esc_attr( $field['country_default'] ) : '';
		$country_hide        = !empty( $field['country_hide'] ) ? true : false;
		$country_visibility  = !isset( $this->schemes[$scheme]['countries'] ) ? 'wpforms-hidden' : '';
		printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-country %s" id="wpforms-field-option-row-%d-country" data-subfield="country" data-field-id="%d">', $country_visibility, $field['id'], $field['id'] );
			$this->field_element( 'label', $field, array( 'slug' => 'country_placeholder', 'value' => __( 'Country', 'wpforms' ) ) );
			echo '<div class="placeholder">';
				printf( '<input type="text" class="placeholder" id="wpforms-field-option-%d-country_placeholder" name="fields[%d][country_placeholder]" value="%s">', $field['id'], $field['id'], $country_placeholder );
				printf( '<label for="wpforms-field-option-%d-country_placeholder" class="sub-label">%s</label>', $field['id'], __( 'Placeholder', 'wpforms' ) );
			echo '</div>';
			echo '<div class="default">';
				printf( '<input type="text" class="default" id="wpforms-field-option-%d-country_default" name="fields[%d][country_default]" value="%s">', $field['id'], $field['id'], $country_default );
				printf( '<label for="wpforms-field-option-%d-country_default" class="sub-label">%s</label>', $field['id'], __( 'Default Value', 'wpforms' ) );
			echo '</div>';
			echo '<div class="hide">';
				printf(' <input type="checkbox" class="hide" name="fields[%d][country_hide]" value="1" %s>', $field['id'], checked( '1', $address2_hide, false ) );
			echo '</div>';
		echo '</div>';

		// Hide label
		$this->field_option( 'label_hide', $field );

		// Hide subel
		$this->field_option( 'sublabel_hide', $field );

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

		$address1_placeholder = !empty( $field['address1_placeholder'] ) ? esc_attr( $field['address1_placeholder'] ) : '';
		$address2_placeholder = !empty( $field['address2_placeholder'] ) ? esc_attr( $field['address2_placeholder'] ) : '';
		$address2_hide        = !empty( $field['address2_hide'] ) ? 'wpforms-hide' : '';
		$city_placeholder     = !empty( $field['city_placeholder'] ) ? esc_attr( $field['city_placeholder'] ) : '';
		$state_placeholder    = !empty( $field['state_placeholder'] ) ? esc_attr( $field['state_placeholder'] ) : '';
		$state_default        = !empty( $field['state_default'] ) ? esc_attr( $field['state_default'] ) : '';
		$postal_placeholder   = !empty( $field['postal_placeholder'] ) ? esc_attr( $field['postal_placeholder'] ) : '';
		$country_placeholder  = !empty( $field['country_placeholder'] ) ? esc_attr( $field['country_placeholder'] ) : '';
		$country_default      = !empty( $field['country_default'] ) ? esc_attr( $field['country_default'] ) : '';
		$country_hide         = !empty( $field['country_hide'] ) ? 'wpforms-hide' : '';
		$format               = !empty( $field['format'] ) ? esc_attr( $field['format'] ) : 'us';
		$scheme_selected      = !empty( $field['scheme'] ) ? esc_attr( $field['scheme'] ) : $format;

		// Label
		$this->field_preview_option( 'label', $field );

		// Field elements
		foreach( $this->schemes as $slug => $scheme ) {

			$active = $slug != $scheme_selected ? 'wpforms-hide' : '';

			printf ('<div class="wpforms-address-scheme wpforms-address-scheme-%s %s">', $slug, $active );

				// Row 1 - Address Line 1
				echo '<div class="wpforms-field-row wpforms-address-1">';
					printf( '<input type="text" placeholder="%s" disabled>', $address1_placeholder );
					printf( '<label class="wpforms-sub-label">%s</label>', $scheme['address1_label'] );
				echo '</div>';

				// Row 2 - Address Line 2
				printf( '<div class="wpforms-field-row wpforms-address-2 %s">', $address2_hide );
					printf( '<input type="text" placeholder="%s" disabled>', $address2_placeholder );
					printf( '<label class="wpforms-sub-label">%s</label>', $scheme['address2_label'] );
				echo '</div>';

				// Row 3 - City & State
				echo '<div class="wpforms-field-row">';

					// City
					echo '<div class="wpforms-city wpforms-one-half ">';
						printf( '<input type="text" placeholder="%s" disabled>', $city_placeholder );
						printf( '<label class="wpforms-sub-label">%s</label>', $scheme['city_label'] );
					echo '</div>';

					// State / Providence / Region
					echo '<div class="wpforms-state wpforms-one-half last">';

						if ( isset( $scheme['states'] ) && empty( $scheme['states'] ) ) {
							
							// State text input
							printf( '<input type="text" placeholder="%s" disabled>', $state_placeholder );
						
						} elseif ( !empty( $scheme['states'] ) && is_array( $scheme['states'] ) ) {
							
							// State select
							echo '<select disabled>';
							if ( !empty( $state_placeholder ) ) {
								printf( '<option value="" class="placeholder" selected>%s</option>', $state_placeholder );
							}
							foreach ( $scheme['states'] as $key => $state ) {
								$select = false;
								if ( !empty( $state_default ) && ( $key == $state_default || $state == $state_default ) ) {
									$select = true;
								}
								$selected = selected( $select, true, false );
								printf('<option %s>%s</option>', $selected, $state );
							}
							echo '</select>';
						}

						printf( '<label class="wpforms-sub-label">%s</label>', $scheme['state_label'] );
					echo '</div>';

				echo '</div>';

				// Row 4 - Zip & Country
				echo '<div class="wpforms-field-row">';

					// ZIP / Postal
					echo '<div class="wpforms-postal wpforms-one-half">';
						printf( '<input type="text" placeholder="%s" disabled>', $postal_placeholder );
						printf( '<label class="wpforms-sub-label">%s</label>', $scheme['postal_label'] );
					echo '</div>';

					// Country
					printf( '<div class="wpforms-country wpforms-one-half last %s">', $country_hide );

						if ( isset( $scheme['countries'] ) && empty( $scheme['countries'] ) ) {
							
							// Country text input
							printf( '<input type="text" placeholder="%s" disabled>', $state_placeholder );
							printf( '<label class="wpforms-sub-label">%s</label>', $scheme['country_label'] );
						
						} elseif ( !empty( $scheme['countries'] ) && is_array( $scheme['countries'] ) ) {
							
							// Country select
							echo '<select disabled>';
							if ( !empty( $country_placeholder ) ) {
								printf( '<option value="" class="placeholder" selected>%s</option>', $country_placeholder );
							}
							foreach ( $scheme['countries'] as $key => $country ) {
								$select = false;
								if ( !empty( $country_default ) && ( $key == $country_default || $country == $country_default ) ) {
									$select = true;
								}
								$selected = selected( $select, true, false );
								printf('<option %s>%s</option>', $selected, $country );
							}
							echo '</select>';
							printf( '<label class="wpforms-sub-label">%s</label>', $scheme['country_label'] );
						}
						
					echo '</div>';

				echo '</div>';

			echo '</div>'; 
		}

		// Description
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
		$field                = apply_filters( 'wpforms_address_field_display', $field, $field_atts, $form_data );
		$field_required       = !empty( $field['required'] ) ? ' required' : '';
		$field_format         = !empty( $field['format'] ) ? esc_attr( $field['format'] ) : 'us';
		$field_scheme         = !empty( $field['scheme'] ) ? esc_attr( $field['scheme'] ) : $field_format;
		$field_class          = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id             = absint( $field['id'] );
		$field_sublabel       = !empty( $field['sublabel_hide'] ) ? 'wpforms-sublabel-hide' : '';
		$address1_placeholder = !empty( $field['address1_placeholder'] ) ? esc_attr( $field['address1_placeholder'] ) : '';
		$address1_default     = !empty( $field['address1_default'] ) ? esc_attr( apply_filters( 'wpforms_process_smart_tags', $field['address1_default'], $form_data ) ) : '';
		$address2_placeholder = !empty( $field['address2_placeholder'] ) ? esc_attr( $field['address2_placeholder'] ) : '';
		$address2_default     = !empty( $field['address2_default'] ) ? esc_attr( apply_filters( 'wpforms_process_smart_tags', $field['address2_default'], $form_data ) ) : '';
		$address2_hide        = !empty( $field['address2_hide'] ) ? true : false;
		$city_placeholder     = !empty( $field['city_placeholder'] ) ? esc_attr( $field['city_placeholder'] ) : '';
		$city_default         = !empty( $field['city_default'] ) ? esc_attr( apply_filters( 'wpforms_process_smart_tags', $field['city_default'], $form_data ) ) : '';
		$region_placeholder   = !empty( $field['region_placeholder'] ) ? esc_attr( $field['region_placeholder'] ) : '';
		$region_default       = !empty( $field['region_default'] ) ? esc_attr( apply_filters( 'wpforms_process_smart_tags', $field['region_default'], $form_data ) ) : '';
		$state_placeholder    = !empty( $field['state_placeholder'] ) ? esc_attr( $field['state_placeholder'] ) : $region_placeholder;
		$state_default        = !empty( $field['state_default'] ) ? esc_attr( apply_filters( 'wpforms_process_smart_tags', $field['state_default'], $form_data ) ) : $region_default;
		$postal_placeholder   = !empty( $field['postal_placeholder'] ) ? esc_attr( $field['postal_placeholder'] ) : '';
		$postal_default       = !empty( $field['address1_default'] ) ? esc_attr( apply_filters( 'wpforms_process_smart_tags', $field['postal_default'], $form_data ) ) : '';
		$country_placeholder  = !empty( $field['country_placeholder'] ) ? esc_attr( $field['country_placeholder'] ) : '';
		$country_default      = !empty( $field['country_default'] ) ? esc_attr( $field['country_default'] ) : '';
		$country_hide         = !empty( $field['country_hide'] ) ? true : false;
		$form_id              = $form_data['id'];
		$scheme               = $this->schemes[$field_scheme];

		// Address Line 1
		printf( '<div class="wpforms-field-row %s">', $field_class );

			$address1_class  = 'wpforms-field-address-address1';
			$address1_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
			$address1_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['address1'] ) ? ' wpforms-error' : '';

			printf( 
				'<input type="text" name="wpforms[fields][%d][address1]" id="%s" class="%s" placeholder="%s" value="%s" %s>',
				$field['id'],
				"wpforms-{$form_id }-field_{$field['id']}",
				$address1_class,
				$address1_placeholder,
				$address1_default,
				$field_required
			);

			if ( !empty( wpforms()->process->errors[$form_id][$field_id]['address1'] ) ) {
				printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['address1'] ) );
			}

			printf( '<label for="wpforms-%d-field_%d" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, $scheme['address1_label']  );

		echo '</div>';

		// Address Line 2

		if ( false == $address2_hide ) :

		printf( '<div class="wpforms-field-row %s">', $field_class );

			printf( 
				'<input type="text" name="wpforms[fields][%d][address2]" id="%s" class="%s" placeholder="%s" value="%s">',
				$field['id'],
				"wpforms-{$form_id}-field_{$field['id']}-address2",
				'wpforms-field-address-address2',
				$address2_placeholder,
				$address2_default
			);
			
			printf( '<label for="wpforms-%d-field_%d-address2" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, $scheme['address2_label']  );

		echo '</div>';

		endif;

		// City and State / Province / Region
		printf( '<div class="wpforms-field-row %s">', $field_class );

			$city_class  = 'wpforms-field-address-city';
			$city_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
			$city_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['city'] ) ? ' wpforms-error' : '';

			// City
			echo '<div class="wpforms-field-row-block wpforms-one-half wpforms-first">';

				printf( 
					'<input type="text" name="wpforms[fields][%d][city]" id="%s" class="%s" placeholder="%s" value="%s" %s>',
					$field['id'],
					"wpforms-{$form_id}-field_{$field['id']}-city",
					$city_class,
					$city_placeholder,
					$city_default,
					$field_required
				);

				if ( !empty( wpforms()->process->errors[$form_id][$field_id]['city'] ) ) {
					printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['city'] ) );
				}
				
				printf( '<label for="wpforms-%d-field_%d-city" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, $scheme['city_label']  );

			echo '</div>';

			// State / Province / Region
			echo '<div class="wpforms-field-row-block wpforms-one-half">';

				$state_class  = 'wpforms-field-address-state';
				$state_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
				$state_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['state'] ) ? ' wpforms-error' : '';

				if ( isset( $scheme['states'] ) && empty( $scheme['states'] ) ) {

					printf( 
						'<input type="text" name="wpforms[fields][%d][state]" id="%s" class="%s" placeholder="%s" value="%s" %s>',
						$field['id'],
						"wpforms-{$form_id}-field_{$field['id']}-state",
						$state_class,
						$state_placeholder,
						$state_default,
						$field_required
					);

				} elseif ( !empty( $scheme['states'] ) && is_array( $scheme['states'] ) ) {

					printf( '<select name="wpforms[fields][%d][state]" id="%s" class="%s" %s>',
						$field['id'],
						"wpforms-{$form_id}-field_{$field['id']}-state",
						$state_class,
						$field_required
					);

					if ( !empty( $state_placeholder ) && empty( $state_default ) ) {
						printf( '<option class="placeholder" selected disabled>%s</option>', $state_placeholder );
					}
					$states = $scheme['states'];

					foreach ( $states as $key => $state ) {

						$select = false;
						if ( !empty( $state_default ) && ( $key == $state_default || $state == $state_default ) ) {
							$select = true;
						}

						$selected = selected( $select, true, false );
						printf('<option value="%s" %s>%s</option>', $key, $selected, $state );
					}

					echo '</select>';
				}

				if ( !empty( wpforms()->process->errors[$form_id][$field_id]['state'] ) ) {
					printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['state'] ) );
				}

				printf( '<label for="wpforms-%d-field_%d-state" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, $scheme['state_label']  );

			echo '</div>';

		echo '</div>';

		// ZIP / Postal and Country
		printf( '<div class="wpforms-field-row %s">', $field_class );

			// ZIP / Postal
			echo '<div class="wpforms-field-row-block wpforms-one-half wpforms-first">';

				$postal_class  = 'wpforms-field-address-postal';
				$postal_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
				$postal_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['postal'] ) ? ' wpforms-error' : '';
				$postal_data   = '';

				// Input mask for US Zip codes
				if ( 'us' == $field_scheme ) {
					$postal_class .= ' wpforms-masked-input';
					$postal_data   = 'data-inputmask="' . "'mask': '99999[-9999]', 'greedy' : false" . '"';
				}

				printf( 
					'<input type="text" name="wpforms[fields][%d][postal]" id="%s" class="%s" placeholder="%s" value="%s" %s %s>',
					$field['id'],
					"wpforms-{$form_id}-field_{$field['id']}-postal",
					$postal_class,
					$postal_placeholder,
					$postal_default,
					$postal_data,
					$field_required
				);

				if ( !empty( wpforms()->process->errors[$form_id][$field_id]['postal'] ) ) {
					printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['postal'] ) );
				}

				printf( '<label for="wpforms-%d-field_%d-postal" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, $scheme['postal_label'] );	

			echo '</div>';

			// Country
			if ( isset( $scheme['countries'] ) && ! $country_hide ) :

				echo '<div class="wpforms-field-row-block wpforms-one-half wpforms-last">';

					$country_class  = 'wpforms-field-address-country';
					$country_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
					$country_class .= !empty( wpforms()->process->errors[$form_id][$field_id]['country'] ) ? ' wpforms-error' : '';

					if ( isset( $scheme['countries'] ) && empty( $scheme['countries'] ) ) {

						printf( 
							'<input type="text" name="wpforms[fields][%d][country]" id="%s" class="%s" placeholder="%s" value="%s" %s>',
							$field['id'],
							"wpforms-{$form_id}-field_{$field['id']}-state",
							$country_class,
							$country_placeholder,
							$country_default,
							$field_required
						);

					} elseif ( !empty( $scheme['countries'] ) && is_array( $scheme['countries'] ) ) {

						printf( '<select name="wpforms[fields][%d][country]" id="%s" class="%s" %s>',
							$field['id'],
							"wpforms-{$form_id}-field_{$field['id']}-country",
							'wpforms-field-address-country',
							$field_required
						);

							if ( !empty( $country_placeholder ) && empty( $country_default ) ) {
								printf( '<option class="placeholder" selected disabled>%s</option>', $country_placeholder );
							}
							$countries = $scheme['countries'];
							foreach ( $countries as $key => $country ) {

								$select = false;
								if ( !empty( $country_default ) && ( $key == $country_default || $country == $country_default ) ) {
									$select = true;
								}
								$selected = selected( $select, true, false );
								printf('<option value="%s" %s>%s</option>', $key, $selected, $country );
							}

						echo '</select>';

					}

					if ( !empty( wpforms()->process->errors[$form_id][$field_id]['country'] ) ) {
						printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field_id, $field_id, esc_html( wpforms()->process->errors[$form_id][$field_id]['country'] ) );
					}

					printf( '<label for="wpforms-%d-field_%d-country" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, $scheme['country_label'] );

				echo '</div>';

			endif;
			
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

		$form_id  = $form_data['id'];
		$required = apply_filters( 'wpforms_required_label', __( 'This field is required', 'wpforms' ) );
		$scheme   = !empty( $form_data['fields'][$field_id]['scheme'] ) ? $form_data['fields'][$field_id]['scheme'] : $form_data['fields'][$field_id]['format'];

		// Extended required validation needed for the different address fields
		if ( !empty( $form_data['fields'][$field_id]['required'] ) ) {

			// Require Address Line 1
			if ( empty( $field_submit['address1'] ) ) {
				wpforms()->process->errors[$form_id][$field_id]['address1'] = $required;
			}

			// Require City
			if ( empty( $field_submit['city'] ) ) {
				wpforms()->process->errors[$form_id][$field_id]['city'] = $required;
			}

			// Require ZIP/Postal
			if ( empty( $field_submit['postal'] ) ) {
				wpforms()->process->errors[$form_id][$field_id]['postal'] = $required;
			}

			// Required State @todo
			if ( empty( $field_submit['state'] ) ) {
				wpforms()->process->errors[$form_id][$field_id]['state'] = $required;
			}

			if ( empty( $form_data['fields'][$field_id]['country_hide'] ) && isset( $this->schemes[$scehene]['countries'] ) && empty( $field_submit['country'] ) ) {
				wpforms()->process->errors[$form_id][$field_id]['country'] = $required;
			}
		}
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

		$name     = !empty( $form_data['fields'][$field_id]['label'] ) ? $form_data['fields'][$field_id]['label'] : '';
		$address1 = !empty( $field_submit['address1'] ) ? $field_submit['address1'] : '';
		$address2 = !empty( $field_submit['address2'] ) ? $field_submit['address2'] : '';
		$city     = !empty( $field_submit['city'] ) ? $field_submit['city'] : '';
		$state    = !empty( $field_submit['state'] ) ? $field_submit['state'] : '';
		$region   = !empty( $field_submit['region'] ) ? $field_submit['region'] : '';
		$postal   = !empty( $field_submit['postal'] ) ? $field_submit['postal'] : '';
		$country  = !empty( $field_submit['country'] ) ? $field_submit['country'] : '';
		$scheme   = !empty( $form_data['fields'][$field_id]['scheme'] ) ? $form_data['fields'][$field_id]['scheme'] : $form_data['fields'][$field_id]['format'];

		$value  = '';
		$value .= !empty( $address1 ) ? "$address1\n" : '';
		$value .= !empty( $address2 ) ? "$address2\n" : '';
		if ( !empty( $city ) && !empty( $state ) ) {
			$value .= "$city, $state\n";
		} elseif( !empty( $state ) ) {
			$value .= "$state\n";
		} elseif ( !empty( $city ) )  {
			$value .= "$city\n";
		}
		$value .= !empty( $postal ) ? "$postal\n" : '';
		$value .= !empty( $country ) ? "$country\n" : '';
		$value = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $value ) ) );

		if ( empty( $city ) && empty( $address1 ) ) {
			$value = '';
		}

		wpforms()->process->fields[$field_id] = array(
			'name'     => sanitize_text_field( $name ),
			'value'    => $value,
			'id'       => absint( $field_id ),
			'type'     => $this->type,
			'address1' => sanitize_text_field( $address1 ),
			'address2' => sanitize_text_field( $address2 ),
			'city'     => sanitize_text_field( $city ),
			'state'    => sanitize_text_field( $state ),
			'postal'   => sanitize_text_field( $postal ),
			'country'  => sanitize_text_field( $country ),
		);
	}
}
new WPForms_Field_Address;