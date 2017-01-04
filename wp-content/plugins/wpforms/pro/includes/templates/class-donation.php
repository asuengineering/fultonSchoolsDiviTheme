<?php
/**
 * Donation form template.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Template_Donation extends WPForms_Template {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->name        = __( 'Donation Form', 'wpforms' );
		$this->slug        = 'donation';
		$this->description = __( 'Start collecting donation payments on your website with this ready-made Donation form. You can add and remove fields as needed.', 'wpforms' );
		$this->includes    = '';
		$this->icon        = '';
		$this->modal       = array(
			'title'   => __( 'Don&#39;t Forget', 'wpforms' ),
			'message' => __( 'Click the Payments tab to configure your payment provider', 'wpforms' ),
		);
		$this->data        = array(
			'field_id' => '4',
			'fields'   => array(
				'0'  => array(
					'id'          => '0',
					'type'        => 'name',
					'label'       => __( 'Name', 'wpforms' ),
					'required'    => '1',
					'size'        => 'medium',
				),
				'1'  => array(
					'id'          => '1',
					'type'        => 'email',
					'label'       => __( 'Email', 'wpforms' ),
					'required'    => '1',
					'size'        => 'medium',
				),
				'2'  => array(
					'id'          => '2',
					'type'        => 'payment-single',
					'label'       => __( 'Donation Amount', 'wpforms' ),
					'format'      => 'user',
					'required'    => '1',
					'size'        => 'medium',
				),
				'3'  => array(
					'id'          => '3',
					'type'        => 'textarea',
					'label'       => __( 'Comment or Message', 'wpforms' ),
					'required'    => '1',
					'size'        => 'medium',
				),
			),
			'settings' => array(
				'honeypot'                    => '1',
				'confirmation_message_scroll' => '1',
				'submit_text_processing'      => __( 'Sending...', 'wpforms' ),
			),
			'meta'     => array(
				'template' => $this->slug,
			),
		);
	}

	/**
	 * Conditional to determine if the template informational modal screens
	 * should display.
	 *
	 * @since 1.0.0
	 * @param array $form_data
	 * @return boolean
	 */
	function template_modal_conditional( $form_data ) {

		// If we do not have payment data, then we can assume a payment
		// method has not yet been configured, so we display the modal to
		// remind the user they need to set it up for the form to work
		// correctly.
		if ( empty( $form_data['payments'] ) ) {
			return true;
		} else {
			return false;
		}
	}
}
new WPForms_Template_Donation;