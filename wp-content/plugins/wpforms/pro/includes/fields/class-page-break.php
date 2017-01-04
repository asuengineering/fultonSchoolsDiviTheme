<?php
/**
 * Seciton Divider field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_Page_Break extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name     = __( 'Page Break', 'wpforms' );
		$this->type     = 'pagebreak';
		$this->icon     = 'fa-files-o';
		$this->order    = 17;
		$this->group    = 'fancy';

		add_filter( 'wpforms_field_preview_class', array( $this, 'preview_field_class' ), 10, 2 );
		add_filter( 'wpforms_field_new_class',     array( $this, 'preview_field_class' ), 10, 2 );
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.0.0
	 * @param array $field
	 */
	public function field_options( $field ) {

		$position       = !empty( $field['position'] ) ? esc_attr( $field['position'] ) : '';
		$position_class = !empty( $field['position'] ) ? 'wpforms-pagebreak-' . $field['position'] : '';

		$this->field_element( 'text', $field, array( 'type' => 'hidden', 'slug' => 'position', 'value' => $position, 'class' => 'position' ) );

		//--------------------------------------------------------------------//
		// Basic field options
		//--------------------------------------------------------------------//
		
		$this->field_option( 'basic-options', $field, array( 'markup' => 'open', 'class' => $position_class ) );

		if ( $position == 'top' ) :
			// Page Indicator
			$format  = !empty( $field['indicator'] ) ? esc_attr( $field['indicator'] ) : 'progress';
			$tooltip = __( 'Select theme for Page Indicator which is displayed at the top of the form.', 'wpforms' );
			$options = array(
				'progress'  => __( 'Progress Bar', 'wpforms' ),
				'circles'   => __( 'Circles', 'wpforms' ),
				'connector' => __( 'Connector', 'wpforms' ),
				'none'      => __( 'None', 'wpforms' ),
			);
			$output  = $this->field_element( 'label',  $field, array( 'slug' => 'indicator', 'value' => __( 'Progress Indicator', 'wpforms' ), 'tooltip' => $tooltip ), false );
			$output .= $this->field_element( 'select', $field, array( 'slug' => 'indicator', 'value' => $format, 'options' => $options ), false );
			$this->field_element( 'row', $field, array( 'slug' => 'indicator', 'content' => $output ) );

			// Page Indicator Color
			$value   = !empty( $field['indicator_color'] ) ? esc_attr( $field['indicator_color'] ) : '#72b239';
			$tooltip = __( 'Select the primary color for the Page Indicator theme.', 'wpforms' );
			$output  = $this->field_element( 'label', $field, array( 'slug' => 'indicator_color', 'value' => __( 'Page Indicator Color', 'wpforms' ), 'tooltip' => $tooltip ), false );
			$output .= $this->field_element( 'text',  $field, array( 'slug' => 'indicator_color', 'value' => $value, 'class' => 'wpforms-color-picker' ), false );
			$this->field_element( 'row', $field, array( 'slug' => 'indicator_color', 'content' => $output, 'class' => 'color-picker-row' ) );
		endif;

		// Page Title
		if ( $position !== 'bottom' ) :
			$tooltip = __( 'Enter text for the page title.', 'wpforms' );
			$title    = !empty( $field['title'] ) ? esc_attr( $field['title'] ) : '';
			$output  = $this->field_element( 'label', $field, array( 'slug' => 'title', 'value' => __( 'Page Title', 'wpforms' ), 'tooltip' => $tooltip ), false );
			$output .= $this->field_element( 'text',  $field, array( 'slug' => 'title', 'value' => $title ), false );
			$this->field_element( 'row', $field, array( 'slug' => 'title', 'content' => $output ) );
		endif;

		// Next label
		if ( empty( $position ) ) :
			$tooltip = __( 'Enter text for Next page navigation button.', 'wpforms' );
			$next    = !empty( $field['next'] ) ? esc_attr( $field['next'] ) : __( 'Next', 'wpforms' );
			$output  = $this->field_element( 'label', $field, array( 'slug' => 'next', 'value' => __( 'Next Label', 'wpforms' ), 'tooltip' => $tooltip ), false );
			$output .= $this->field_element( 'text',  $field, array( 'slug' => 'next', 'value' => $next ), false );
			$this->field_element( 'row', $field, array( 'slug' => 'next', 'content' => $output ) );
		endif;

		// Previous label
		if ( $position !== 'top' ) :

			$tooltip = __( 'Enter text for Previous page navigation button.', 'wpforms' );
			$value    = !empty( $field['prev_toggle'] ) || !empty( $field['prev'] ) ? true : false;
			$output  = $this->field_element( 'label', $field, array( 'slug' => 'prev_toggle', 'value' => __( 'Display Previous', 'wpforms' ), 'tooltip' => $tooltip ), false );
			$output .= $this->field_element( 'toggle',  $field, array( 'slug' => 'prev_toggle', 'value' => $value ), false );
			$this->field_element( 'row', $field, array( 'slug' => 'prev_toggle', 'content' => $output ) );

			$tooltip = __( 'Enter text for Previous page navigation button.', 'wpforms' );
			$prev    = !empty( $field['prev'] ) ? esc_attr( $field['prev'] ) : '';
			$output  = $this->field_element( 'label', $field, array( 'slug' => 'prev', 'value' => __( 'Previous Label', 'wpforms' ), 'tooltip' => $tooltip ), false );
			$output .= $this->field_element( 'text',  $field, array( 'slug' => 'prev', 'value' => $prev ), false );
			$class   = empty( $field['prev_toggle'] ) ? 'wpforms-hidden' : '';
			$this->field_element( 'row', $field, array( 'slug' => 'prev', 'content' => $output, 'class' => $class ) );
		endif;

		$this->field_option( 'basic-options', $field, array( 'markup' => 'close' ) );

		//--------------------------------------------------------------------//
		// Advanced field options
		//--------------------------------------------------------------------//
		if ( $position != 'bottom' ) :
			$this->field_option( 'advanced-options', $field, array( 'markup' => 'open', 'class' => $position_class ) );

			if ( $position == 'top' ) :
				// Page navigation alignment
				$format  = !empty( $field['nav_align'] ) ? esc_attr( $field['nav_align'] ) : '';
				$tooltip = __( 'Select the alignment for the Next/Previous page navigation buttons', 'wpforms' );
				$options = array(
					'left'   => __( 'Left', 'wpforms' ),
					'right'  => __( 'Right', 'wpforms' ),
					''       => __( 'Center', 'wpforms' ),
					'split'  => __( 'Split', 'wpforms' ),
				);
				$output  = $this->field_element( 'label',  $field, array( 'slug' => 'nav_align', 'value' => __( 'Page Navigation Alignment', 'wpforms' ), 'tooltip' => $tooltip ), false );
				$output .= $this->field_element( 'select', $field, array( 'slug' => 'nav_align', 'value' => $format, 'options' => $options ), false );
				$this->field_element( 'row', $field, array( 'slug' => 'nav_align', 'content' => $output ) );
			endif;

			$this->field_option( 'css',              $field );
			$this->field_option( 'advanced-options', $field, array( 'markup' => 'close' ) );
		endif;
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 * @param array $field
	 */
	public function field_preview( $field ) {

		$nav_align  = 'wpforms-pagebreak-buttons-left';
		$prev       = !empty( $field['prev'] ) ? esc_html( $field['prev'] ) : __( 'Previous', 'wpforms' );
		$prev_class = empty( $field['prev'] ) && empty( $field['prev_toggle'] ) ? 'wpforms-hidden' : '';
		$next       = !empty( $field['next'] ) ? esc_html( $field['next'] ) : __( 'Next', 'wpforms' );
		$next_class = empty( $next ) ? 'wpforms-hidden' : '';
		$position   = !empty( $field['position'] ) ? esc_html( $field['position'] ) : 'normal';
		$title      = !empty( $field['title'] ) ? '(' . esc_html( $field['title'] ) . ')' : '';

		if ( $position != 'top' ) :
			if ( empty( $this->form_data ) ) {
				$this->form_data = wpforms()->form->get( $this->form_id, array( 'content_only' => true ) );		
			}
			$pagebreak_top = wpforms_get_pagebreak( $this->form_data, 'top' );
			if ( !empty( $pagebreak_top['nav_align'] ) ) {
				$nav_align = 'wpforms-pagebreak-buttons-' . sanitize_html_class( $pagebreak_top['nav_align'] );
			}
			echo '<div class="wpforms-pagebreak-buttons ' . $nav_align . '">';
				echo '<button class="wpforms-pagebreak-button wpforms-pagebreak-prev ' . $prev_class . '">' . $prev . '</button>';
				if ( $position != 'bottom' ):
					echo '<button class="wpforms-pagebreak-button wpforms-pagebreak-next ' . $next_class . '">' . $next . '</button>';
				endif;
			echo '</div>';
		endif;
		
		echo '<div class="wpforms-pagebreak-divider">';
			if ( $position == 'top' ) {
				echo '<span class="pagebreak-label"> ' . __( 'First Page', 'wpforms' ) . ' <span class="wpforms-pagebreak-title">' . $title . '</span></span>';
			} elseif( $position == 'normal' ) {
				echo '<span class="pagebreak-label"> ' . __( 'Page Break', 'wpforms' ) . ' <span class="wpforms-pagebreak-title">' . $title . '</span></span>';
			}
			echo '<span class="line"></span>';
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
		$field   = apply_filters( 'wpforms_pagedivider_field_display', $field, $field_atts, $form_data );
		$total   = $form_data['page_total'];
		$current = $form_data['page_current'];
		$next    = !empty( $field['next'] ) ? esc_html( $field['next'] ) : '';
		$prev    = !empty( $field['prev'] ) ? esc_html( $field['prev'] ) : '';
		$align   = 'wpforms-pagebreak-center';
		$pbt     = wpforms_get_pagebreak( $form_data, 'top' );

		if ( !empty( $pbt['nav_align'] ) ) {
			$align = 'wpforms-pagebreak-' . sanitize_html_class( $pbt['nav_align'] );
		}
		
		echo '<div class="wpforms-clear ' . $align . '">';

			if ( $current > 1 && !empty( $prev ) ) {
				printf( 
					'<button class="wpforms-page-button wpforms-page-prev" data-action="prev" data-page="%d" data-formid="%s">%s</button>',
					$current,
					$form_data['id'],
					$prev
				);
			}

			if ( $current < $total && !empty( $next ) ) {
				printf( 
					'<button class="wpforms-page-button wpforms-page-next" data-action="next" data-page="%d" data-formid="%s">%s</button>',
					$current,
					$form_data['id'],
					$next
				);
			}

		echo '</div>';
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

	/**
	 * Adds class to the builder field preview.
	 *
	 * @since 1.2.0
	 * @param sting $css
	 * @param array $field
	 * @return string
	 */
	public function preview_field_class( $css, $field ) {

		if ( 'pagebreak' == $field['type'] ) {
			
			if ( !empty( $field['position'] ) && 'top' == $field['position'] ) {
				$css .= ' wpforms-field-stick wpforms-pagebreak-top';
			} elseif ( !empty( $field['position'] ) && 'bottom' == $field['position'] ) {
				$css .= ' wpforms-field-stick wpforms-pagebreak-bottom';
			} else {
				$css .= ' wpforms-pagebreak-normal';
			}
		}

		return $css;
	}
}
new WPForms_Field_Page_Break;