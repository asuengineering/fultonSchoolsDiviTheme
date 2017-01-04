<?php
/**
 * Addons class.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Addons {

	/**
	 * WPForms addons
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $addons;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Maybe load addons page
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	/**
	 * Determing if the user is viewing the settings page, if so, party on.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Check what page we are on
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';

		// Only load if we are actually on the settings page
		if ( $page == 'wpforms-addons' ) {

			add_action( 'admin_enqueue_scripts',  array( $this, 'enqueues' ) );
			add_action( 'wpforms_admin_page',     array( $this, 'output'   ) );
		}
	}

	/**
	 * Enqueue assets for the addons page.
	 *
	 * @since 1.0.0
	 */
	public function enqueues() {

		// CSS
		wp_enqueue_style(
			'font-awesome',
			WPFORMS_PLUGIN_URL . 'assets/css/font-awesome.min.css',
			null,
			'4.4.0'
		);
		wp_enqueue_style(
			'wpforms-addons',
			WPFORMS_PLUGIN_URL . 'assets/css/admin-addons.css',
			null,
			WPFORMS_VERSION
		);

		// JS
		wp_enqueue_script(
			'wpforms-addons',
			WPFORMS_PLUGIN_URL . 'pro/assets/js/admin-addons.js',
			array( 'jquery' ),
			WPFORMS_VERSION,
			false
		);
		wp_localize_script(
			'wpforms-addons',
			'wpforms_addons',
			array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'wpforms-addons' ),
				'install'     => __( 'Install Addon', 'wpforms' ),
				'deactivate'  => __( 'Deactivate', 'wpforms' ),
				'activate'    => __( 'Activate', 'wpforms' ),
			)
		);
	}

	/**
	 * Build the output for the plugin addons page.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		$refresh      = isset( $_GET['wpforms_refresh_addons'] );
		$errors       = wpforms()->license->get_errors();
		$type         = wpforms()->license->type();
		$this->addons = wpforms()->license->addons( $refresh );

		echo '<div id="wpforms-addons" class="wrap">';

			echo '<h1 class="page-title">';
				echo esc_html( get_admin_page_title() );
				echo ' <a href="' . esc_url_raw( add_query_arg( array( 'wpforms_refresh_addons' => '1' ) ) ) . '" class="add-new-h2">' . __( 'Refresh Addons', 'wpforms' ) . '</a>';
			echo '</h1>';

			if ( empty( $this->addons ) ) :

				echo '<div class="error below-h2">';
					echo '<p>' . __( 'There was an issue retrieving the addons for this site. Please click on the button above the refresh the addons data.', 'wpforms' ) . '</p>';
				echo '</div>';

			elseif ( !empty( $errors ) ) :

				echo '<div class="error below-h2">';
					echo '<p>' . __( 'In order to get access to Addons, you need to resolve your license key errors.', 'wpforms' ) . '</p>';
				echo '</div>';

			elseif ( empty( $type ) ) :

				echo '<div class="error below-h2">';
					echo '<p>' . __( 'In order to get access to Addons, you need to verify your license key for WPForms.', 'wpforms' ) . '</p>';
				echo '</div>';

			else:

				echo '<p class="intro">' . __( 'Improve your forms with our premium addons. Missing an addon that you think you should be able to see? Click the Refresh Addons button above.', 'wpforms' ) . '</p>';

				if ( $refresh ) :

					echo '<div class="updated below-h2">';
						echo '<p>' . __( 'Addons have successfully been refreshed.', 'wpforms' ) . '</p>';
					echo '</div>';

				endif;

				if ( 'basic' == $type ) :

					echo '<div class="wpforms-addons-basic-message">';
						echo '<h5>' . __( 'No Addons Available for WPForms Basic License', 'wpforms' ) . '</h5>';
						echo '<p>' . __( 'WPForms Basic license does not come with premium extensions at this point. Please upgrade your account to unlock the features below.', 'wpforms' ) . '</p>';
					echo '</div>';

					$this->addon_grid( $this->addons, $type, array( 'basic', 'plus', 'pro' ), true );

				elseif( 'plus' == $type ) :

					$this->addon_grid( $this->addons, $type, array( 'plus' ) );

					$this->addon_grid( $this->addons, $type, array( 'pro' ), true );

				elseif( 'ultimate' == $type || 'pro' == $type ) :

					$this->addon_grid( $this->addons, $type, array( 'basic', 'plus', 'pro' ) );

				endif;

			endif;

		echo '</div>';
	}

	/**
	 * Renders grid of addons.
	 *
	 * @since 1.0.0
	 * @param object $addons
	 * @param string $type_current
	 * @param array $type_show
	 * @param bool $unlock
	 */
	function addon_grid( $addons, $type_current, $type_show , $unlock = false ) {

		$count   = 0;
		$plugins = get_plugins();

		if ( $unlock ) {
			echo '<div class="wpforms-addons-unlock">';
				echo '<h4>' . __( 'Unlock More Features...', 'wpforms' ) . '</h4>';
				echo '<p>' . __( 'Want to get even more features? Upgrade your WPForms account and unlock the following extensions.', 'wpforms' ) . '</p>';
			echo '</div>';
		}

		// Ultimate is the same level pro
		if ( $type_current == 'ultimate' ) {
			$type_current = 'pro';
		}

		foreach( $addons as $id => $addon ) {

			$addon           = (array) $addon;
			$found           = false;
			$position        = ( $count % 2 != 0 ) ? 'second' : 'first';
			$plugin_basename = $this->get_plugin_basename_from_slug( $addon['slug'], $plugins );

			foreach( $addon['types'] as $type ) {
				if ( in_array( $type, $type_show ) ) {
					$found = true;
				}
			}

			if ( ! $found ) {
				continue;
			}

			if ( !in_array( $type_current, $addon['types'] ) ) {
				$status = 'upgrade';
			} elseif ( is_plugin_active( $plugin_basename ) ) {
				$status = 'active';
			} elseif( ! isset( $plugins[$plugin_basename] ) ) {
				$status = 'download';
			} elseif( is_plugin_inactive( $plugin_basename ) ) {
				$status = 'inactive';
			} else {
				$status = 'upgrade';
			}

			$image = !empty( $addon['image'] ) ? $addon['image'] :  WPFORMS_PLUGIN_URL . 'assets/images/sullie.png';

			echo '<div class="wpforms-addon-item wpforms-addon-status-' . $status . ' wpforms-' . $position . '">';

				echo '<div class="wpforms-addon-image">';
					echo '<img src="' . esc_url( $image ) . '">';
				echo '</div>';

				echo '<div class="wpforms-addon-text">';
					echo '<h4>' . esc_html( $addon['title'] ) . '</h5>';
					echo '<p class="desc">' . esc_html( $addon['excerpt'] ) . '</p>';
				echo '</div>';

				echo '<div class="wpforms-addon-action">';

					if ( $status == 'active' ) {
						echo '<button data-plugin="' . esc_attr( $plugin_basename ) . '">' . __( 'Deactivate', 'wpforms' ) . '</button>';
					} elseif( $status == 'inactive' ) {
						echo '<button data-plugin="' . esc_attr( $plugin_basename ) . '">' . __( 'Activate', 'wpforms' ) . '</button>';
					} elseif( $status == 'download' ) {
						echo '<button data-plugin="' . esc_url( $addon['url'] ) . '">' . __( 'Install Addon', 'wpforms' ) . '</button>';
					} else {
						echo '<a href="https://wpforms.com/account/" target="_blank" rel="noopener">' . __( 'Upgrade Now', 'wpforms' ) . '</a>';
					}

				echo '</div>';

			echo '</div>';

			$count++;

			if ( !empty( $this->addons[$id] ) ) {
				unset( $this->addons[$id] );
			}
		}
		echo '<div style="clear:both"></div>';
	}

	/**
	 * Retrieve the plugin basename from the plugin slug.
	 *
	 * @since 1.0.0
	 * @param string $slug The plugin slug.
	 * @return string      The plugin basename if found, else the plugin slug.
	 */
	public function get_plugin_basename_from_slug( $slug, $plugins ) {

		$keys = array_keys( $plugins );
		foreach ( $keys as $key ) {
			if ( preg_match( '|^' . $slug . '|', $key ) ) {
				return $key;
			}
		}
		return $slug;
	}
}
new WPForms_Addons;