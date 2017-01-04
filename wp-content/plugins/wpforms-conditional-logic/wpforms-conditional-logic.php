<?php
/**
 * Plugin Name: WPForms Conditional Logic
 * Plugin URI:  https://wpforms.com
 * Description: Conditional logic for WPForms.
 * Author:      WPForms
 * Author URI:  https://wpforms.com
 * Version:     1.1.6
 * Text Domain: wpforms_conditionals
 * Domain Path: languages
 *
 * WPForms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WPForms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WPForms. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    WPFormsConditionals
 * @since      1.1.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WP Forms LLC
 */

final class WPForms_Conditional_Logic {

	/**
	 * One is the loneliest number that you'll ever do.
	 *
	 * @since 1.1.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Add-on version.
	 *
	 * @since 1.0.0
	 * @var sting
	 */
	private $version = '1.1.6';

	/**
	 * Add-on name.
	 *
	 * @since 1.0.0
	 * @var sting
	 */
	public $name = 'WPForms Conditional Logic';

	/**
	 * Add-on name in slug format.
	 *
	 * @since 1.0.0
	 * @var sting
	 */
	public $slug = 'wpforms-conditional-logic';

	/**
	 * Main Instance.
	 *
	 * @since 1.1.0
	 * @return WPForms_Conditional_Logic
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WPForms_Conditional_Logic ) ) {

			self::$instance = new WPForms_Conditional_Logic;
			self::$instance->load_textdomain();

			add_action( 'wpforms_loaded', array( self::$instance, 'init' ), 10 );
		}
		return self::$instance;
	}

	/**
	 * Loads the plugin language files.
	 *
	 * @since 1.1.0
	 */
	public function load_textdomain() {

		load_plugin_textdomain( 'wpforms_conditionals', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// WPForms Pro is required
		if ( !class_exists( 'WPForms_Pro' ) ) {
			return;
		}

		add_action( 'wpforms_builder_enqueues',                     array( $this, 'builder_assets'             )        );
		add_action( 'wpforms_frontend_js',                          array( $this, 'frontend_assets'            )        );
		add_filter( 'wpforms_field_atts',                           array( $this, 'frontend_field_atts'        ), 10, 3 );
		add_action( 'wpforms_wp_footer',                            array( $this, 'frontend_footer'            )        );
		add_action( 'wpforms_field_options_after_advanced-options', array( $this, 'field_conditionals'         ), 10, 2 );
		add_filter( 'wpforms_entry_email_process',                  array( $this, 'notification_conditionals'  ), 10, 4 );
		add_action( 'wpforms_updater',                              array( $this, 'updater'                    )        );
	}

	/**
	 * Enqueue assets for the builder.
	 *
	 * @since 1.0.0
	 */
	public function builder_assets() {

		// CSS
		wp_enqueue_style(
			'wpforms-builder-conditionals',
			plugin_dir_url( __FILE__ ) . 'assets/css/admin-builder-conditionals.css',
			array(),
			$this->version
		);

		// JS
		wp_enqueue_script(
			'wpforms-builder-conditionals',
			plugin_dir_url( __FILE__ ) . 'assets/js/admin-builder-conditionals.js',
			array( 'jquery', 'serialize-object' ),
			$this->version,
			false
		);
	}

	/**
	 * Enqueue assets for the frontend.
	 *
	 * @since 1.0.0
	 */
	public function frontend_assets( $forms ) {

		// JS
		wp_enqueue_script(
			'wpforms-builder-conditionals',
			plugin_dir_url( __FILE__ ) . 'assets/js/wpforms-conditionals.js',
			array( 'jquery' ),
			$this->version,
			true
		);
	}

	/**
	 * Filter front-end field attributes.
	 *
	 * If a field has conditional logic apply the needed classes to it.
	 *
	 * @since 1.0.0
	 * @param array $field_atts
	 * @param array $field
	 * @param array $form_data
	 * @return array
	 */
	public function frontend_field_atts( $field_atts, $field, $form_data ) {

		$conditional = $this->field_is_conditional( $field );
		if ( $conditional ) {
			$field_atts['field_class'][] = 'wpforms-conditional-field';
			$field_atts['field_class'][] = 'wpforms-conditional-' . sanitize_html_class(  $field['conditional_type'] );

			if ( $field['conditional_type'] == 'show' ) {
				$field_atts['field_style'] = 'display:none;';
			}
		}

		$trigger = $this->field_is_trigger( $field, $form_data );
		if ( $trigger ) {
			$field_atts['field_class'][] = 'wpforms-conditional-trigger';
		}

		return $field_atts;
	}

	/**
	 * Checks if a provided from has conditional logic
	 *
	 * @since 1.0.0
	 * @param array $field
	 * @return boolean
	 */
	public function field_is_conditional( $field ) {

		$conditional = false;

		// Even though the user indicates they would like to enable
		// conditional logic on a field, we loop through the conditional
		// logic array to double check that at least one rule has been set.
		if ( !empty( $field['conditional_logic'] ) && !empty( $field['conditionals'] ) && $field['conditional_logic'] == '1' ) {
			foreach( $field['conditionals'] as $group_id => $group ) {
				foreach( $group as $rule_id => $rule ) {
					if ( !empty( $rule['field'] ) && !empty( $rule['operator'] ) && !empty( $rule['value'] ) ) {
						$conditional = true;
						break;
					}
				}
				if ( $conditional )
					break;
			}
		}

		return $conditional;
	}

	/**
	 * Checks if a provided from is a conditional logic trigger.
	 *
	 * @since 1.0.0
	 * @param array $field
	 * @return boolean
	 */
	public function field_is_trigger( $field, $form_data ) {

		$trigger  = false;
		$field_id = $field['id'];

		foreach( $form_data['fields'] as $field ) {
			if ( !empty( $field['conditional_logic'] ) && !empty( $field['conditionals'] ) && $field['conditional_logic'] == '1' ) {
				foreach( $field['conditionals'] as $group_id => $group ) {
					foreach( $group as $rule_id => $rule ) {
						if ( !empty( $rule['field'] ) && !empty( $rule['operator'] ) && !empty( $rule['value'] ) && $rule['field'] == $field_id ) {
							$trigger = true;
							break;
						}
					}
					if ( $trigger )
						break;
				}
			}
		}

		return $trigger;
	}

	/**
	 * Output conditional data in the site footer if necessary.
	 *
	 * @since 1.0.0
	 * @param array $forms
	 * @return string
	 */
	public function frontend_footer( $forms ) {

		$conditionals = array();

		foreach ( $forms as $form ) {

			if ( empty( $form['fields'] ) ) {
				continue;
			}

			foreach( $form['fields'] as $field ) {

				if ( !empty( $field['conditional_logic'] ) && !empty( $field['conditionals'] ) && $field['conditional_logic'] == '1' ) {
					foreach( $field['conditionals'] as $group_id => $group ) {
						foreach( $group as $rule_id => $rule ) {
							if ( !empty( $rule['field'] ) && !empty( $rule['operator'] ) && !empty( $rule['value'] ) ) {

								// Valid conditional!

								if ( in_array( $form['fields'][$rule['field']]['type'] , array( 'select', 'checkbox', 'radio', 'payment-multiple', 'payment-select' ) ) ) {

									if ( in_array( $form['fields'][$rule['field']]['type'], array( 'payment-multiple', 'payment-select' ) ) ) {

										// Payment multiple items values are different, they are the actual ID
										$val = $rule['value'];

									} else {

										// For rules referring to fields with choices
										// we need to replace the choice key with the
										// choice value.
										if ( !empty( $form['fields'][$rule['field']]['choices'][$rule['value']]['value'] ) ) {
											$val = esc_attr( $form['fields'][$rule['field']]['choices'][$rule['value']]['value'] );
										} else {
											$val = esc_attr( $form['fields'][$rule['field']]['choices'][$rule['value']]['label'] );
										}
									}

									$field['conditionals'][$group_id][$rule_id]['value'] = $val;
								}

								// Also for easy processing include the target field type
								$field['conditionals'][$group_id][$rule_id]['type'] = $form['fields'][$rule['field']]['type'];

								$conditionals[$form['id']][$field['id']]['logic']  = $field['conditionals'];
								$conditionals[$form['id']][$field['id']]['action'] = $field['conditional_type'];
							}
						}
					}
				}
			}
		}

		if ( !empty( $conditionals ) ) {

			echo '<script type="text/javascript">var wpforms_conditional_logic=' . json_encode( $conditionals ) . '</script>';
		}
	}

	/**
	 * Builds the conditional field settings to display in the field options.
	 *
	 * @since 1.1.0
	 * @param array $args
	 * @param bool $echo;
	 */
	public function conditionals_block( $args = array(), $echo = true ) {

		if ( !empty( $args['form'] ) ) {
			$form_fields = wpforms_get_form_fields( $args['form'], array( 'text', 'textarea', 'select', 'radio', 'email', 'url', 'checkbox', 'number', 'payment-multiple', 'payment-select', 'hidden' ) );
		} else {
			$form_fields = array();
		}

		$type        = !empty( $args['type'] ) ? $args['type'] : 'field';
		$panel       = !empty( $args['panel'] ) ? $args['panel'] : false; // notifications
		$parent      = !empty( $args['parent'] ) ? $args['parent'] : false; // settings
		$subsection  = !empty( $args['subsection'] ) ? $args['subsection'] : false; // 1
		$actions     = !empty( $args['actions'] ) ? $args['actions'] : array( 'show' => __( 'Show', 'wpforms_conditionals'), 'hide' => __( 'Hide', 'wpforms_conditionals' ) );
		$action_desc = !empty( $args['action_desc'] ) ? $args['action_desc'] : __( 'this field if', 'wpforms_conditionals' );
		$field       = !empty( $args['field'] ) ? $args['field'] : false;
		$reference   = !empty( $args['reference'] ) ? $args['reference'] : '';
		$data_attrs  = '';

		ob_start();

		echo '<div class="wpforms-conditional-block wpforms-conditional-block-' . $type . '" data-type="' . $type . '">';

			if ( 'field' == $type ) {

				$fields_instance = $args['instance'];
				$field_name      = sprintf( 'fields[%s]', absint( $field['id'] ) );
				$action_selected = !empty( $field['conditional_type'] ) ? $field['conditional_type'] : '';
				$conditionals    = !empty( $field['conditionals'] ) ? $field['conditionals'] : array( array( array() ) );
				$data_attrs      = 'data-field-id="' . absint( $field['id'] ) . '" ';
				$reference       = absint( $field['id'] );

				// Conditional Logic toggle checkbox field option /
				$enabled = isset( $field['conditional_logic'] ) ? $field['conditional_logic'] : false;
				$tooltip = __( 'Check this option to enable conditional logic on this field.', 'wpforms_conditionals' );
				$output  = $fields_instance->field_element( 'checkbox', $field, array( 'slug' => 'conditional_logic', 'value' => $enabled, 'desc' => __( 'Enable conditional logic', 'wpforms_conditionals' ), 'tooltip' => $tooltip ), false );
				$output  = $fields_instance->field_element( 'row',      $field, array( 'slug' => 'conditional_logic', 'content' => $output, 'class' => 'wpforms-conditionals-enable-toggle' ), false );
				echo $output;

				// Prevent conditional logic from being applied to itself
				if ( !empty( $form_fields[$field['id']] ) ) {
					unset( $form_fields[$field['id']] );
				}

			} elseif ( 'panel' == $type ) {

				$form_data = $args['form'];

				if ( !empty( $parent ) ) {
					if ( !empty( $subsection ) ) {
						$field_name      = sprintf( '%s[%s][%s]', $parent, $panel, $subsection );
						$enabled         = !empty( $form_data[$parent][$panel][$subsection]['conditional_logic'] ) ? true : false;
						$action_selected = !empty( $form_data[$parent][$panel][$subsection]['conditional_type'] ) ? $form_data[$parent][$panel][$subsection]['conditional_type'] : '';
						$conditionals    = !empty( $form_data[$parent][$panel][$subsection]['conditionals'] ) ? $form_data[$parent][$panel][$subsection]['conditionals'] : array( array( array() ) );
					} else {
						$field_name      = sprintf( '%s[%s]', $parent, $panel );
						$enabled         = !empty( $form_data[$parent][$panel]['conditional_logic'] ) ? true : false;
						$action_selected = !empty( $form_data[$parent][$panel]['conditional_type'] ) ? $form_data[$parent][$panel]['conditional_type'] : '';
						$conditionals    = !empty( $form_data[$parent][$panel]['conditionals'] ) ? $form_data[$parent][$panel]['conditionals'] : array( array( array() ) );
					}
				} else {
					$field_name      = sprintf( '%s', $panel );
					$enabled         = !empty( $form_data[$panel]['conditional_logic'] ) ? true : false;
					$action_selected = !empty( $form_data[$panel]['conditional_type'] ) ? $form_data[$panel]['conditional_type'] : '';
					$conditionals    = !empty( $form_data[$panel]['conditionals'] ) ? $form_data[$panel]['conditionals'] : array( array( array() ) );
				}

				// Conditional Logic toggle checkbox panel setting
				wpforms_panel_field(
					'checkbox',
					$panel,
					'conditional_logic',
					$args['form'],
					__( 'Enable conditional logic', 'wpforms_conditionals' ),
					array(
						'tooltip'    => __( 'Check this option to enable conditional logic.', 'wpforms_conditionals' ),
						'parent'     => $parent,
						'subsection' => $subsection,
						'class'      => 'wpforms-conditionals-enable-toggle'
					)
				);
			}

			$data_attrs .= 'data-input-name="' . $field_name . '"';
			$style       = $enabled ? '' : 'display:none;';

			echo '<div class="wpforms-conditional-groups" style="' . $style . '">';

				echo '<h4>';

					echo '<select name="' . $field_name . '[conditional_type]">';

						foreach( $actions as $key => $label ) {
							 printf( '<option value="%s" %s>%s</option>',
							 	esc_attr( $key ),
							 	selected( $key, $action_selected, false ),
							 	esc_html( $label )
							);
						}

					echo '</select>';

					echo $action_desc;

				echo '</h4>';


				foreach( $conditionals as $group_id => $group ) :

					echo '<div class="wpforms-conditional-group" data-reference="' . $reference . '">';

						echo'<table><tbody>';

							foreach( $group as $rule_id => $rule ) :

								echo '<tr class="wpforms-conditional-row" ' . $data_attrs . '>';

									// Fields
									echo '<td class="field">';

										printf(
											'<select name="%s[conditionals][%d][%d][field]" class="wpforms-conditional-field" data-groupid="%d" data-ruleid="%d">',
											$field_name,
											$group_id,
											$rule_id,
											$group_id,
											$rule_id
										);

											echo '<option value="">' . __( '-- Select Field --', 'wpforms_conditionals' ) . '</option>';

											if ( !empty( $form_fields ) ) {
												foreach( $form_fields as $form_field ) {

													if ( !empty( $form_field['dynamic_choices'] ) ) {
														continue;
													}

													$selected = isset( $rule['field'] ) ? $rule['field'] : false;
													$selected = selected( $selected, $form_field['id'], false );
													printf( '<option value="%s" %s>%s</option>', absint( $form_field['id'] ), $selected, esc_html( $form_field['label'] ) );
												}
											}

										echo '</select>';

									echo '</td>';

									// Operator
									echo '<td class="operator">';

										printf( '<select name="%s[conditionals][%s][%s][operator]" class="wpforms-conditional-operator">', $field_name, $group_id, $rule_id );

											$operator = !empty( $rule['operator'] ) ? $rule['operator'] : false;
											printf( '<option value="==" %s>%s</option>', selected( $operator, '==', false ), __( 'is', 'wpforms_conditionals' ) );
											printf( '<option value="!=" %s>%s</option>', selected( $operator, '!=', false ), __( 'is not', 'wpforms_conditionals' ) );
											printf( '<option value="c" %s>%s</option>', selected( $operator, 'c', false ), __( 'contains', 'wpforms_conditionals' ) );
											printf( '<option value="!c" %s>%s</option>', selected( $operator, '!c', false ), __( 'does not contain', 'wpforms_conditionals' ) );
											printf( '<option value="^" %s>%s</option>', selected( $operator, '^', false ), __( 'starts with', 'wpforms_conditionals' ) );
											printf( '<option value="~" %s>%s</option>', selected( $operator, '~', false ), __( 'ends with', 'wpforms_conditionals' ) );

										echo '</select>';

									echo '</td>';

									// Values
									echo '<td class="value">';

										if ( isset( $rule['field'] ) ) {

											if ( isset( $form_fields[$rule['field']]['type'] ) && in_array( $form_fields[$rule['field']]['type'], array( 'text', 'textarea', 'email', 'url', 'number', 'hidden' ) ) ) {

												printf( '<input type="text" name="%s[conditionals][%s][%s][value]" value="%s" class="wpforms-conditional-value">', $field_name, $group_id, $rule_id, esc_attr( $rule['value'] ) );

											} else {

												printf( '<select name="%s[conditionals][%s][%s][value]" class="wpforms-conditional-value">', $field_name, $group_id, $rule_id );

													echo '<option value="">' . __( '-- Select Choice --', 'wpforms_conditionals' ) . '</option>';
													if ( !empty( $form_fields[$rule['field']]['choices'] ) ) :
													foreach( $form_fields[$rule['field']]['choices'] as $option_id => $option ) {
														$value    = isset( $rule['value'] ) ? $rule['value'] : '';
														$selected = selected( $option_id, $value, false );
														printf( '<option value="%s" %s>%s</option>', $option_id, $selected, esc_html( $option['label'] ) );
													}
													endif;

												echo '</select>';
											}

										} else {
											echo '<select></select>';
										}

									echo '</td>';

									// Actions
									echo '<td class="actions">';

										echo '<button class="wpforms-conditional-rule-add" title="' . __( 'Create new rule', 'wpforms_conditionals' ) . '">' . __( 'AND', 'wpforms') . '</button>';

										echo '<button class="wpforms-conditional-rule-delete" title="' . __( 'Delete rule', 'wpforms_conditionals' ) . '"><i class="fa fa-times-circle" aria-hidden="true"></i></button>';

									echo '</td>';

								echo '</tr>';

							endforeach;

						echo '</tbody></table>';

						echo '<h5>or</h5>';

					echo '</div>';

				endforeach;

				echo '<button class="wpforms-conditional-groups-add">' . __( 'Add rule group', 'wpforms_conditionals' ) . '</button>';

			echo '</div>';

		echo '</div>';

		$output = ob_get_clean();

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}


	/**
	 * Process conditional fields.
	 *
	 * @since 1.0.0
	 * @param array $fields
	 * @param array $form_data
	 * @param array $connection
	 */
	public function conditionals_process( $fields, $form_data, $conditionals ) {

		if ( empty( $conditionals ) ) {
			return true;
		}

		$pass = false;

		foreach ( $conditionals as $group_id => $group ) {

			$pass_group = true;

			if ( !empty( $group ) ) {

				foreach( $group as $rule_id => $rule ) {

					if ( !isset( $rule['field'] ) || !isset( $rule['operator'] ) || !isset( $rule['value'] ) ) {
						continue;
					}

					$rule_field    = $rule['field'];
					$rule_operator = $rule['operator'];;
					$rule_value    = $rule['value'];

					if ( in_array( $fields[$rule_field]['type'], array( 'text', 'textarea', 'email', 'url', 'number', 'hidden' ) ) ) {

						// Text based fields
						$left  = trim( strtolower( $fields[$rule_field]['value'] ) );
						$right = trim( strtolower( $rule_value ) );

						switch ( $rule_operator ) {
							case '==' :
								$pass_rule = ( $left == $right );
							break;
							case '!=' :
								$pass_rule = ( $left != $right );
							break;
							case 'c' :
								$pass_rule = ( strpos( $left, $right ) !== false );
							break;
							case '!c' :
								$pass_rule = ( strpos( $left, $right ) === false );
							break;
							case '^' :
								$pass_rule = ( strrpos( $left, $right, -strlen( $left ) ) !== false );
							break;
							case '~' :
								$pass_rule = ( ($temp = strlen( $left ) - strlen( $right )) >= 0 && strpos( $left, $right, $temp ) !== false );
							break;
							default :
								$pass_rule = apply_filters( 'wpforms_process_conditional_logic', false, $rule_operator, $left, $right );
							break;
						}

					} else {

						// Selector based fields
						$provided_id = false;

						if (  in_array( $fields[$rule_field]['type'], array( 'payment-multiple', 'payment-select' ) ) && isset( $fields[$rule_field]['value_raw'] ) && '' != $fields[$rule_field]['value_raw']  ) {

							// Payment Multiple field stores the option key, so
							// we can reference that easily
							$provided_id = $fields[$rule_field]['value_raw'];

						} elseif ( isset( $fields[$rule_field]['value'] ) && '' != $fields[$rule_field]['value'] ) {

							// Other select type fields we don't store the option
							// key so we have to do the logic to locate it ourselves
							$provided_id = array();

							if ( in_array( $fields[$rule_field]['type'], array( 'checkbox' ) ) ) {
								$values = explode( "\n", $fields[$rule_field]['value'] );
							} else {
								$values = (array) $fields[$rule_field]['value'];
							}

							foreach( $form_data['fields'][$rule_field]['choices'] as $key => $choice ) {

								foreach( $values as $value ) {

									if ( in_array( $value, $choice ) ) {
										$provided_id[] = $key;
									}
								}
							}
						}

						$left  = (array) $provided_id;
						$right = trim( strtolower( (int) $rule_value ) );

						switch ( $rule_operator ) {
							case '==' :
							case 'c'  : // BC, no longer availabile
							case '^'  : // BC, no longer availabile
							case '~'  : // BC, no longer availabile
							case '~'  : // BC, no longer availabile
								$pass_rule = in_array( $right, $left );
								//$pass_rule = ( $left == $right );
							break;
							case '!=' :
							case '!c' : // BC, no longer availabile
								//$pass_rule = ( $left != $right );
								$pass_rule = ! in_array( $right, $left );
							break;
							default :
								$pass_rule = apply_filters( 'wpforms_process_conditional_logic', false, $rule_operator, $left, $right );
							break;
						}
					}

					if ( !$pass_rule ) {
						$pass_group = false;
						break;
					}
				}
			}

			if ( $pass_group ) {
				$pass = true;
			}
		}

		return $pass;
	}

	/**
	 * Builds the conditional field settings to display in the field options.
	 *
	 * @since 1.0.0
	 * @param array $field
	 * @param object $field_intance
	 */
	public function field_conditionals( $field, $fields_instance ) {

		if ( in_array( $field['type'], array( 'pagebreak', 'divider', 'hidden' ) ) ) {
			return;
		}

		echo '<div class="wpforms-conditional-fields wpforms-field-option-group wpforms-field-option-group-conditionals wpforms-hide" id="wpforms-field-option-conditionals-' . absint( $field['id'] ) . '">';

			echo '<a href="#" class="wpforms-field-option-group-toggle">' . __( 'Conditionals', 'wpforms_conditionals ') . ' <i class="fa fa-angle-right"></i></a>';

			echo '<div class="wpforms-field-option-group-inner">';

				// Conditional Logic
				$this->conditionals_block( array(
					'form'     => $fields_instance->form_id,
					'field'    => $field,
					'instance' => $fields_instance,
				) );

			echo '</div>';

		echo '</div>';
	}

	/**
	 * Process conditional logic for form entry notifications.
	 *
	 * @since 1.1.0
	 * @param boolean $process
	 * @param array $fields
	 * @param array $form_data
	 * @param int $id
	 * @return boolean
	 */
	public function notification_conditionals( $process, $fields, $form_data, $id ) {

		// Require various conditional logic data to proceed
		if ( empty( $form_data['settings']['notifications'][$id]['conditional_logic'] ) ) {
			return $process;
		}
		if ( empty( $form_data['settings']['notifications'][$id]['conditional_type'] ) ) {
			return $process;
		}
		if ( empty( $form_data['settings']['notifications'][$id]['conditionals'] ) ) {
			return $process;
		}

		$type    = $form_data['settings']['notifications'][$id]['conditional_type'];
		$process = $this->conditionals_process( $fields, $form_data, $form_data['settings']['notifications'][$id]['conditionals'] );

		if ( 'stop' == $type ) {
			$process = !$process;
		}

		// If preventing the notification, log it
		if ( ! $process ) {
			wpforms_log(
				'Entry Notification stopped by conditional logic',
				$form_data['settings']['notifications'][$id],
				array(
					'type'    => array( 'entry', 'conditional_logic' ),
					'parent'  => wpforms()->process->entry_id,
					'form_id' => $form_data['id'],
				)
			);
		}

		return $process;
	}

	/**
	 * Load plugin updater.
	 *
	 * @since 1.0.0
	 */
	public function updater( $key ) {

		// Go ahead and initialize the updater.
		$args = array(
			'plugin_name' => $this->name,
			'plugin_slug' => $this->slug,
			'plugin_path' => plugin_basename( __FILE__ ),
			'plugin_url'  => trailingslashit( plugin_dir_url( __FILE__ ) ),
			'remote_url'  => WPFORMS_UPDATER_API,
			'version'     => $this->version,
			'key'         => $key,
		);
		$updater = new WPForms_Updater( $args );
	}
}

/**
 * The function which returns the one WPForms_Conditional_Logic instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wpforms_conditional_logic = wpforms_conditional_logic(); ?>
 *
 * @since 1.1.0
 * @return object
 */
function wpforms_conditional_logic() {

	return WPForms_Conditional_Logic::instance();
}
wpforms_conditional_logic();