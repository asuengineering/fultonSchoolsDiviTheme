<?php
/**
 * Payments panel.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Builder_Panel_Payments extends WPForms_Builder_Panel {

	/**
	 * All systems go.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define panel information
		$this->name    = __( 'Payments', 'wpforms' );
		$this->slug    = 'payments';
		$this->icon    = 'fa-usd';
		$this->order   = 10;
		$this->sidebar = true;
	}

	/**
	 * Outputs the Payments panel sidebar.
	 *
	 * @since 1.0.0
	 */
	public function panel_sidebar() {
		
		// Sidebar contents are not valid unless we have a form
		if ( !$this->form ) {
			return;
		}

		$this->panel_sidebar_section( 'Default', 'default' );

		do_action( 'wpforms_payments_panel_sidebar', $this->form );
	}

	/**
	 * Outputs the Payments panel primary content.
	 *
	 * @since 1.0.0
	 */
	public function panel_content() {

		// An array of all the active provider add-ons
		$payments_active = apply_filters( 'wpforms_payments_available', '' );

		if ( !$this->form ) {

			// Check if there is a form created. When no form has been created
			// yet let the user know we need a form to setup a payment.
			echo '<div class="wpforms-alert wpforms-alert-info">';
				_e( 'You need to <a href="#" class="wpforms-panel-switch" data-panel="setup">setup your form</a> before you can manage these settings.', 'wpforms' );
			echo '</div>';
			return;
		} if ( empty( $payments_active ) ) {

			// Check for active payment add-ons. When no payment add-ons are
			// activated let the user know they need to install/activate an
			// add-on to setup a payment
			echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-info">';
				echo '<h5>' . __( 'Install Your Payment Integration', 'wpforms' ) . '</h5>';
				echo '<p>' . sprintf( __( 'It seems you do not have any payment add-ons activated. You can head over to the <a href="%s">Add-Ons page</a> to install and activate the add-on for your payment service.', 'wpforms' ), admin_url( 'admin.php?page=wpforms-addons' ) ) . '</p>';
			echo '</div>';
		}  else {

			// Everything is good - display default instructions
			echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-default">';
				echo '<h5>' . __( 'Select Your Payment Integration', 'wpforms' ) . '</h5>';
				echo '<p>' . __( 'Select your email payment provider provider from the options on the left. If you don\'t see your payment service listed, then let us know and we\'ll do our best to get it added as fast as possible', 'wpforms' ) . '</p>';
			echo '</div>';
		}
		
		do_action( 'wpforms_payments_panel_content', $this->form );
	}
}
new WPForms_Builder_Panel_Payments;