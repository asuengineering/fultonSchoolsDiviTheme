<?php
/**
 * Request A Quote form template.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Template_Request_Quote extends WPForms_Template {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->name        = __( 'Request A Quote Form', 'wpforms' );
		$this->slug        = 'request-quote';
		$this->description = __( 'Start collecting leads with this pre-made Request a quote form. You can add and remove fields as needed.', 'wpforms' );
		$this->includes    = '';
		$this->icon        = '';
		$this->modal       = '';
		$this->data        = array(
			'field_id' => '5',
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
					'type'        => 'text',
					'label'       => __( 'Business / Organization', 'wpforms' ),
					'required'    => '1',
					'size'        => 'medium',
				),
				'2'  => array(
					'id'          => '2',
					'type'        => 'email',
					'label'       => __( 'Email', 'wpforms' ),
					'required'    => '1',
					'size'        => 'medium',
				),
				'3'  => array(
					'id'          => '3',
					'type'        => 'phone',
					'label'       => __( 'Phone', 'wpforms' ),
					'format'      => 'us',
					'required'    => '1',
					'size'        => 'medium',
				),
				'4'  => array(
					'id'          => '4',
					'type'        => 'textarea',
					'label'       => __( 'Request', 'wpforms' ),
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
}
new WPForms_Template_Request_Quote;