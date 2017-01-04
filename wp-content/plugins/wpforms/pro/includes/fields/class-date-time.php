<?php
/**
 * Date / Time field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_Date_Time extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name  = __( 'Date / Time', 'wpforms' );
		$this->type  = 'date-time';
		$this->icon  = 'fa-calendar-o';
		$this->order = 11;
		$this->group = 'fancy';

		// Set custom option wrapper classes
		add_filter( 'wpforms_builder_field_option_class', array( $this, 'field_option_class' ), 10, 2 );
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
		$this->field_option( 'label',$field );

		// Format option
		$format       = !empty( $field['format'] ) ? esc_attr( $field['format'] ) : 'date-time';
		$format_label = $this->field_element( 
			'label',
			$field, 
			array( 
				'slug'    => 'format', 
				'value'   => __( 'Format', 'wpforms' ), 
				'tooltip' => __( 'Select format for the date field.', 'wpforms' )
			),
			false
		);
		$format_select = $this->field_element( 
			'select', 
			$field, 
			array( 
				'slug'    => 'format',
				'value'   => $format,
				'options' => array(
					'date-time' => __( 'Date and Time', 'wpforms' ),
					'date'      => __( 'Date', 'wpforms' ),
					'time'      => __( 'Time', 'wpforms' ),
				),
			),
			false
		);
		$this->field_element( 'row', $field, array( 'slug' => 'format', 'content' => $format_label . $format_select ) );
		
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

		// Custom options
		echo '<div class="format-selected-' . $format . ' format-selected">';

			// Date
			$date_placeholder = !empty( $field['date_placeholder'] ) ? esc_attr( $field['date_placeholder'] ) : '';
			$date_format      = !empty( $field['date_format'] ) ? esc_attr( $field['date_format'] ) : 'm/d/Y';
			$date_type        = !empty( $field['date_type'] ) ? esc_attr( $field['date_type'] ) : 'datepicker';
			// Backwards compatibility with old datepicker format
			if ( 'mm/dd/yyyy' == $date_format ) {
				$date_format = 'm/d/Y';
			} elseif ( 'dd/mm/yyyy' == $date_format ) {
				$date_format = 'd/m/Y';
			} elseif ( 'mmmm d, yyyy' == $date_format ) {
				$date_format = 'F j, Y';
			}
			$date_formats = apply_filters( 'wpforms_datetime_date_formats', array(
				'm/d/Y'  => 'm/d/Y',
				'd/m/Y'  => 'd/m/Y',
				'F j, Y' => 'F j, Y',
			) );
			printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-date" id="wpforms-field-option-row-%d-date" data-subfield="date" data-field-id="%d">', $field['id'], $field['id'] );
				$this->field_element( 'label', $field, array( 'slug' => 'date_placeholder', 'value' => __( 'Date', 'wpforms' ), 'tooltip' => __( 'Advanced date options.', 'wpforms' ) ) );
				echo '<div class="placeholder">';
					printf( '<input type="text" class="placeholder" id="wpforms-field-option-%d-date_placeholder" name="fields[%d][date_placeholder]" value="%s">', $field['id'], $field['id'], $date_placeholder );
					printf( '<label for="wpforms-field-option-%d-date_placeholder" class="sub-label">%s</label>', $field['id'], __( 'Placeholder', 'wpforms' ) );
				echo '</div>';
				echo '<div class="format">';
					printf( '<select id="wpforms-field-option-%d-date_format" name="fields[%d][date_format]">', $field['id'], $field['id'] );
						foreach ( $date_formats as $key => $value ) {
							if ( in_array( $key, array( 'm/d/Y', 'd/m/Y' ) ) ) {
								printf( '<option value="%s" %s>%s (%s)</option>', $key, selected( $date_format, $key, false ), date( $value ), $key );
							} else {
								printf( '<option value="%s" class="datepicker-only" %s>%s</option>', $key, selected( $date_format, $key, false ), date( $value ) );
							}
						}
					echo '</select>';
					printf( '<label for="wpforms-field-option-%d-date_format" class="sub-label">%s</label>', $field['id'], __( 'Format', 'wpforms' ) );
				echo '</div>';
				echo '<div class="type">';
					printf( '<select id="wpforms-field-option-%d-date_type" name="fields[%d][date_type]">', $field['id'], $field['id'] );
						printf( '<option value="datepicker" %s>%s</option>', selected( $date_type, 'datepicker', false ), __( 'Date Picker', 'wpforms') );
						printf( '<option value="dropdown" %s>%s</option>', selected( $date_type, 'dropdown', false ), __( 'Date Dropdown', 'wpforms' ) );
					echo '</select>';
					printf( '<label for="wpforms-field-option-%d-date_type" class="sub-label">%s</label>', $field['id'], __( 'Type', 'wpforms' ) );
				echo '</div>';
			echo '</div>';

			// Time
			$time_placeholder = !empty( $field['time_placeholder'] ) ? esc_attr( $field['time_placeholder'] ) : '';
			$time_format      = !empty( $field['time_format'] ) ? esc_attr( $field['time_format'] ) : 'g:i A';
			$time_formats     = array(
				'g:i A' => '12 H',
				'H:i'   => '24 H',
			);
			$time_interval      = !empty( $field['time_interval'] ) ? esc_attr( $field['time_interval'] ) : '30';
			$time_intervals     = array(
				'15'   => __( '15 minutes' ),
				'30'   => __( '30 minute' ),
				'60'   => __( '1 hour' ),
			);
			printf( '<div class="wpforms-clear wpforms-field-option-row wpforms-field-option-row-time" id="wpforms-field-option-row-%d-time" data-subfield="time" data-field-id="%d">', $field['id'], $field['id'] );
				$this->field_element( 'label', $field, array( 'slug' => 'time_placeholder', 'value' => __( 'Time', 'wpforms' ), 'tooltip' => __( 'Advanced time options', 'wpforms' ) ) );
				echo '<div class="placeholder">';
					printf( '<input type="text"" class="placeholder" id="wpforms-field-option-%d-time_placeholder" name="fields[%d][time_placeholder]" value="%s">', $field['id'], $field['id'], $time_placeholder );
					printf( '<label for="wpforms-field-option-%d-time_placeholder" class="sub-label">%s</label>', $field['id'], __( 'Placeholder', 'wpforms' ) );
				echo '</div>';
				echo '<div class="format">';
					printf( '<select id="wpforms-field-option-%d-time_format" name="fields[%d][time_format]">', $field['id'], $field['id'] );
						foreach ( $time_formats as $key => $value ) {
							printf( '<option value="%s" %s>%s</option>', $key, selected( $time_format, $key, false ), $value );
						}
					echo '</select>';
					printf( '<label for="wpforms-field-option-%d-time_format" class="sub-label">%s</label>', $field['id'], __( 'Format', 'wpforms' ) );
				echo '</div>';
				echo '<div class="interval">';
					printf( '<select id="wpforms-field-option-%d-time_interval" name="fields[%d][time_interval]">', $field['id'], $field['id'] );
						foreach ( $time_intervals as $key => $value ) {
							printf( '<option value="%s" %s>%s</option>', $key, selected( $time_interval, $key, false ), $value );
						}
					echo '</select>';
					printf( '<label for="wpforms-field-option-%d-time_interval" class="sub-label">%s</label>', $field['id'], __( 'Interval', 'wpforms' ) );
				echo '</div>';
			echo '</div>';

		echo '</div>';

		// Hide label
		$this->field_option( 'label_hide', $field );

		// Hide sublabels
		$this->field_option( 'sublabel_hide', $field );

		// Custom CSS classes
		$this->field_option( 'css', $field );

		// Options close markup
		$this->field_option( 'advanced-options', $field, array( 'markup' => 'close' ) );
	}

	/**
	 * Add class to field options wrapper to indicate if field confirmation is enabled.
	 *
	 * @since 1.3.0
	 * @param string $class
	 * @param array $field
	 * @return string
	 */
	function field_option_class( $class, $field ) {

		if ( 'date-time' == $field['type'] ) {

			$date_type = !empty( $field['date_type'] ) ? sanitize_html_class( $field['date_type'] ) : 'datepicker';
			$class     = "wpforms-date-type-$date_type";
		}
		return $class;
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 * @param array $field
	 */
	public function field_preview( $field ) {

		$date_placeholder = !empty( $field['date_placeholder'] ) ? esc_attr( $field['date_placeholder'] ) : '';
		$time_placeholder = !empty( $field['time_placeholder'] ) ? esc_attr( $field['time_placeholder'] ) : '';
		$format           = !empty( $field['format'] ) ? esc_attr( $field['format'] ) : 'date-time';
		$date_type        = !empty( $field['date_type'] ) ? esc_attr( $field['date_type'] ) : 'datepicker';
		$date_format      = !empty( $field['date_format'] ) ? esc_attr( $field['date_format'] ) : 'm/d/Y';

		if ( 'mm/dd/yyyy' == $date_format || 'm/d/Y' == $date_format ) {
			$date_first_select  = __( 'MM', 'wpforms' );
			$date_second_select = __( 'DD', 'wpforms' );
		} else {
			$date_first_select  = __( 'DD', 'wpforms' );
			$date_second_select = __( 'MM', 'wpforms' );
		}

		// Label
		$this->field_preview_option( 'label', $field );
		
		echo '<div class="format-selected-' . $format . ' format-selected">';
			
			// Date
			printf( '<div class="wpforms-date wpforms-date-type-%s">', $date_type );
				echo '<div class="wpforms-date-datepicker">';
					printf( '<input type="text" placeholder="%s" class="primary-input" disabled>', $date_placeholder );
					printf( '<label class="wpforms-sub-label">%s</label>', __( 'Date', 'wpforms' ) );
				echo '</div>';
				echo '<div class="wpforms-date-dropdown">';
					printf( '<select disabled class="first"><option>%s</option></select>', $date_first_select );
					echo '<span>/</span>';
					printf( '<select disabled class="second"><option>%s</option></select>', $date_second_select );
					echo '<span>/</span>';
					echo '<select disabled><option>YYYY</option></select>';
					printf( '<label class="wpforms-sub-label">%s</label>', __( 'Date', 'wpforms' ) );
				echo '</div>';
			echo '</div>';
			
			// Time
			echo '<div class="wpforms-time">';
				printf( '<input type="text" placeholder="%s" class="primary-input" disabled>', $time_placeholder );
				printf( '<label class="wpforms-sub-label">%s</label>', __( 'Time', 'wpforms' ) );
			echo '</div>';
		echo '</div>';

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
		$field             = apply_filters( 'wpforms_datetime_field_display', $field, $field_atts, $form_data );
		$field_required    = !empty( $field['required'] ) ? ' required' : '';
		$field_format      = !empty( $field['format'] ) ? $field['format'] : 'date-time';
		$field_class       = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id          = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_id'] ) );
		$field_sublabel    = !empty( $field['sublabel_hide'] ) ? 'wpforms-sublabel-hide' : '';
		$date_placeholder  = !empty( $field['date_placeholder'] ) ? esc_attr( $field['date_placeholder'] ) : '';
		$date_format       = !empty( $field['date_format'] ) ? esc_attr( $field['date_format'] ) : 'm/d/Y';
		$date_type         = !empty( $field['date_type'] ) ? esc_attr( $field['date_type'] ) : 'datepicker';
		$time_placeholder  = !empty( $field['time_placeholder'] ) ? esc_attr( $field['time_placeholder'] ) : '';
		$time_format       = !empty( $field['time_format'] ) ? esc_attr( $field['time_format'] ) : 'g:i A';
		$time_interval     = !empty( $field['time_interval'] ) ? esc_attr( $field['time_interval'] ) : '30';
		$form_id           = $form_data['id'];

		if ( !empty( $field['time_format'] ) && ( 'H:i' == $field['time_format'] || 'H:i A' == $field['time_format'] ) ) {
			$time_validation = 'time24h';
		} else {
			$time_validation = 'time12h';
		}

		// Backwards compatibility with old datepicker format
		if ( 'mm/dd/yyyy' == $date_format ) {
			$date_format = 'm/d/Y';
		} elseif ( 'dd/mm/yyyy' == $date_format ) {
			$date_format = 'd/m/Y';
		} elseif ( 'mmmm d, yyyy' == $date_format ) {
			$date_format = 'F j, Y';
		}

		// Date and Time format fields
		if ( $field_format == 'date-time' ) :

			printf( '<div class="wpforms-field-row %s">', $field_class );

				echo '<div class="wpforms-field-row-block wpforms-one-half wpforms-first">';

					if ( 'dropdown' == $date_type ) {

						$this->field_display_date_dropdowns( $date_format, $field, $field_required, $form_id );

					} else {

						$date_class  = 'wpforms-field-date-time-date wpforms-datepicker';
						$date_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
						$date_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['date'] ) ? ' wpforms-error' : '';

						printf( 
							'<input type="text" name="wpforms[fields][%d][date]" id="%s" class="%s" placeholder="%s" data-date-format="%s" %s>',
							$field['id'],
							"wpforms-{$form_id}-field_{$field['id']}",
							$date_class,
							$date_placeholder,
							$date_format,
							$field_required
						);
					}

					if ( !empty( wpforms()->process->errors[$form_id][$field['id']]['date'] ) ) {
						printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field['id'], $field['id'], esc_html( wpforms()->process->errors[$form_id][$field['id']]['date'] ) );
					}

					printf( '<label for="wpforms-%d-field_%d" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, __( 'Date', 'wpforms' ) );

				echo '</div>';

				echo '<div class="wpforms-field-row-block wpforms-one-half">';

					$time_class  = 'wpforms-field-date-time-time wpforms-timepicker';
					$time_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
					$time_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['time'] ) ? ' wpforms-error' : '';

					printf( 
						'<input type="text" name="wpforms[fields][%d][time]" id="%s" class="%s wpforms-field-date-time-time wpforms-timepicker" placeholder="%s" data-rule-%s="true" data-time-format="%s" data-step="%s" %s>',
						$field['id'],
						"wpforms-{$form_id}-field_{$field['id']}-time",
						$time_class,
						$time_placeholder,
						$time_validation,
						$time_format,
						$time_interval,
						$field_required
					);
					
					if ( !empty( wpforms()->process->errors[$form_id][$field['id']]['time'] ) ) {
						printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field['id'], $field['id'], esc_html( wpforms()->process->errors[$form_id][$field['id']]['time'] ) );
					}

					printf( '<label for="wpforms-%d-field_%d" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, __( 'Time', 'wpforms' ) );

				echo '</div>';

			echo '</div>';

		// Date only field
		elseif ( $field_format == 'date' ) :

			if ( 'dropdown' == $date_type ) {

				$this->field_display_date_dropdowns( $date_format, $field, $field_required, $form_id );

			} else {

				printf( 
					'<input type="text" name="wpforms[fields][%d][date]" id="%s" class="%s wpforms-field-date-time-date wpforms-datepicker" placeholder="%s" data-date-format="%s" %s>',
					$field['id'],
					$field_id,
					$field_class,
					$date_placeholder,
					$date_format,
					$field_required
				);

			}
		
		// Time only field
		else:

			printf( 
				'<input type="text" name="wpforms[fields][%d][time]" id="%s" class="%s wpforms-field-date-time-time wpforms-timepicker" placeholder="%s" data-rule-%s="true" %s data-time-format="%s" data-step="%s" %s>',
				$field['id'],
				$field_id,
				$field_class,
				$time_placeholder,
				$time_validation,
				$time_validation == 'time24h' ? 'data-show2400="true"' : '',
				$time_format,
				$time_interval,
				$field_required
			);
		endif;
	}

	/**
	 * Display the date field using dropdowns.
	 *
	 * @since 1.3.0
	 * @param string $format
	 * @param array $field
	 * @param string $field_required
	 * @param int $form_id
	 */
	function field_display_date_dropdowns( $format, $field, $field_required, $form_id ) {

		if ( 'm/d/Y' == $format ) {

			// Month
			$month_class  = 'wpforms-field-date-time-date-month';
			$month_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
			$month_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['date'] ) ? ' wpforms-error' : '';
			printf( 
				'<select name="wpforms[fields][%d][date][m]" id="%s" class="%s" %s>',
				$field['id'],
				"wpforms-field_{$field['id']}-month",
				$month_class,
				$field_required
			);
				echo '<option class="placeholder" selected disabled>' . __( 'MM', 'wpforms' ) . '</option>';
				for ($i=1; $i <= 12; $i++) { 
					printf( '<option value="%d">%d</option>', $i, $i );
				}
			echo '</select>';

			echo '<span class="wpforms-field-date-time-date-sep">/</span>';

			// Day
			$day_class  = 'wpforms-field-date-time-date-day';
			$day_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
			$day_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['date'] ) ? ' wpforms-error' : '';
			printf( 
				'<select name="wpforms[fields][%d][date][d]" id="%s" class="%s" %s>',
				$field['id'],
				"wpforms-field_{$field['id']}-day",
				$month_class,
				$field_required
			);
				echo '<option class="placeholder" selected disabled>' . __( 'DD', 'wpforms' ) . '</option>';
				for ($i=1; $i <= 31; $i++) { 
					printf( '<option value="%d">%d</option>', $i, $i );
				}
			echo '</select>';

		} else {

			// Day
			$day_class  = 'wpforms-field-date-time-date-day';
			$day_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
			$day_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['date'] ) ? ' wpforms-error' : '';
			printf( 
				'<select name="wpforms[fields][%d][date][d]" id="%s" class="%s" %s>',
				$field['id'],
				"wpforms-field_{$field['id']}-day",
				$day_class,
				$field_required
			);
				echo '<option class="placeholder" selected disabled>' . __( 'DD', 'wpforms' ) . '</option>';
				for ($i=1; $i <= 31; $i++) { 
					printf( '<option value="%d">%d</option>', $i, $i );
				}
			echo '</select>';

			echo '<span class="wpforms-field-date-time-date-sep">/</span>';
			
			// Month
			$month_class  = 'wpforms-field-date-time-date-month';
			$month_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
			$month_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['date'] ) ? ' wpforms-error' : '';
			printf( 
				'<select name="wpforms[fields][%d][date][m]" id="%s" class="%s" %s>',
				$field['id'],
				"wpforms-field_{$field['id']}-month",
				$month_class,
				$field_required
			);
				echo '<option class="placeholder" selected disabled>' . __( 'MM', 'wpforms' ) . '</option>';
				for ($i=1; $i <= 12; $i++) { 
					printf( '<option value="%d">%d</option>', $i, $i );
				}
			echo '</select>';
		}

		echo '<span class="wpforms-field-date-time-date-sep">/</span>';

		// Year
		$year_class  = 'wpforms-field-date-time-date-year';
		$year_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
		$year_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['date'] ) ? ' wpforms-error' : '';
		printf( 
			'<select name="wpforms[fields][%d][date][y]" id="%s" class="%s" %s>',
			$field['id'],
			"wpforms-field_{$field['id']}-year",
			$year_class,
			$field_required
		);
			echo '<option class="placeholder" selected disabled>' . __( 'YYYY', 'wpforms' ) . '</option>';
			for ($i=date('Y'); $i >= 1920; $i--) { 
				printf( '<option value="%d">%d</option>', $i, $i );
			}
		echo '</select>';
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

		// Extended validation needed for the different address fields
		if ( !empty( $form_data['fields'][$field_id]['required'] ) ) {

			$form_id  = $form_data['id'];
			$format   = $form_data['fields'][$field_id]['format'];
			$required = apply_filters( 'wpforms_required_label', __( 'This field is required', 'wpforms' ) );

			if ( !empty( $form_data['fields'][$field_id]['date_type'] ) && 'dropdown' == $form_data['fields'][$field_id]['date_type'] ) {
				if ( ( empty( $field_submit['date']['m'] ) || empty( $field_submit['date']['d'] ) || empty( $field_submit['date']['y'] ) ) && ( 'date' == $format || 'date-time' == $format ) ) {
					wpforms()->process->errors[$form_id][$field_id]['date'] = $required;
				}
			} else {
				if ( empty( $field_submit['date'] ) && ( 'date' == $format || 'date-time' == $format ) ) {
					wpforms()->process->errors[$form_id][$field_id]['date'] = $required;
				}
			}

			if ( empty( $field_submit['time'] ) && ( 'time' == $format || 'date-time' == $format ) ) {
				wpforms()->process->errors[$form_id][$field_id]['time'] = $required;
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

		$name        = !empty( $form_data['fields'][$field_id]['label'] ) ? $form_data['fields'][$field_id]['label'] : '';
		$format      = $form_data['fields'][$field_id]['format'];
		$date_format = $form_data['fields'][$field_id]['date_format'];
		$time_format = $form_data['fields'][$field_id]['time_format'];
		$value       = '';
		$date        = '';
		$time        = '';
		$unix        = '';

		if ( !empty( $field_submit['date'] ) ) {
			if ( is_array( $field_submit['date'] ) && !empty( $field_submit['date']['m'] ) && !empty( $field_submit['date']['d'] ) && !empty( $field_submit['date']['y'] ) ){
				if ( ( 'dd/mm/yyyy' == $date_format || 'd/m/Y' == $date_format ) ) {
					$date = $field_submit['date']['d'] . '/' . $field_submit['date']['m'] . '/' . $field_submit['date']['y'];
				} else {
					$date = $field_submit['date']['m'] . '/' . $field_submit['date']['d'] . '/' . $field_submit['date']['y'];
				}
			} else {
				$date = $field_submit['date'];
			}
		}

		if ( !empty( $field_submit['time'] ) ) {
			$time = $field_submit['time'];
		}

		if ( 'date-time' == $format && !empty( $field_submit ) ) {
			$value = trim( "$date $time" );
		} elseif ( 'date' == $format ) {
			$value = $date;
		} elseif( 'time' == $format ) {
			$value = $time;
		}

		// Always store the raw time in 12H format
		if ( ( 'H:i A' == $time_format || 'H:i' == $time_format ) && !empty( $time ) ) {
			$time = date( "g:i A", strtotime( $time ) );
		}

		// Always store the date in m/d/Y format so it is strtotime compatible
		if ( ( 'dd/mm/yyyy' == $date_format || 'd/m/Y' == $date_format ) && !empty( $date ) ) {
			list( $d, $m, $y ) = explode( '/', $date );
			$date = "$m/$d/$y";
		}

		// Calculate unix time if we have a date
		if ( !empty( $date ) ) {
			$unix = strtotime( trim( "$date $time" ) );
		}
	
		wpforms()->process->fields[$field_id] = array(
			'name'     => sanitize_text_field( $name ),
			'value'    => sanitize_text_field( $value ),
			'id'       => absint( $field_id ),
			'type'     => $this->type,
			'date'     => sanitize_text_field( $date ),
			'time'     => sanitize_text_field( $time ),
			'unix'     => $unix,
		);
	}
}
new WPForms_Field_Date_Time;