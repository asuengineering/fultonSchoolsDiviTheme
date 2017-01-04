<?php
/**
 * Billing / Order form template.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Template_Order extends WPForms_Template {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->name        = __( 'Billing / Order Form', 'wpforms' );
		$this->slug        = 'order';
		$this->description = __( 'Collect Payments for product and service orders with this ready-made form template. You can add and remove fields as needed.', 'wpforms' );
		$this->includes    = '';
		$this->icon        = '';
		$this->modal       = array(
			'title'   => __( 'Don&#39;t Forget', 'wpforms' ),
			'message' => __( 'Click the payments tab to configure your payment provider', 'wpforms' ),
		);
		$this->data        = array(
			'field_id' => '7',
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
					'type'        => 'phone',
					'label'       => __( 'Phone', 'wpforms' ),
					'format'      => 'us',
					'required'    => '1',
					'size'        => 'medium',
				),
				'3'  => array(
					'id'          => '3',
					'type'        => 'address',
					'label'       => __( 'Address', 'wpforms' ),
					'required'    => '1',
					'size'        => 'medium',
					'country_default' => 'US',
				),

				'4'  => array(
					'id'          => '4',
					'type'        => 'payment-multiple',
					'label'       => __( 'Available Items', 'wpforms' ),
					'required'    => '1',
					'choices'     => array(
						'1' => array(
							'label' => __( 'First Item', 'wpforms' ),
							'value' => '$10.00',
						),
						'2' => array(
							'label' => __( 'Second Item', 'wpforms' ),
							'value' => '$20.00',
						),
						'3' => array(
							'label' => __( 'Third Item', 'wpforms' ),
							'value' => '$30.00',
						),
					),
				),
				'5'  => array(
					'id'          => '5',
					'type'        => 'payment-total',
					'label'       => __( 'Total Amount', 'wpforms' ),
				),
				'6'  => array(
					'id'          => '6',
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
new WPForms_Template_Order;