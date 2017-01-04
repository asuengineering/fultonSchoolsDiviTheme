<?php
/**
 * Handles plugin upgrades.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Upgrades {

	/**
	 * Have we upgraded?
	 *
	 * @since 1.0.0
	 * @var boolean
	 */
	private $upgraded = false;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'init' ), -9999 );
	}

	/**
	 * Checks if a new version is detected, if so perform update.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Retreive last known version
		$version = get_option( 'wpforms_version' );

		if ( ! $version )
			return;

		if ( version_compare( $version, '1.1.6', '<' ) ) {
			$this->v116_upgrade();
		}
		
		// If upgrade has occured, update version options in database 
		if ( $this->upgraded ) {
			update_option( 'wpforms_version_upgraded_from', $version );
			update_option( 'wpforms_version', WPFORMS_VERSION );
		}
	}

	/**
	 * Perform database upgrades for version 1.1.6
	 *
	 * @since 1.1.6
	 */
	private function v116_upgrade() {

		wpforms()->entry_meta->create_table();

		$this->upgraded = true;
	}
}
new WPForms_Upgrades;