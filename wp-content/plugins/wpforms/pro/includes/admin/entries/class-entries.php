<?php
/**
 * Primary entries page inside the admin.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Entries {

	/**
	 * Current view
	 *
	 * @since 1.0.0
	 */
	public $view;

	/**
	 * Holds admin alerts.
	 *
	 * @since 1.1.6
	 * @var array
	 */
	public $alerts;

	/**
	 * Abort. Bail on proceeding to process the page.
	 *
	 * @since 1.1.6
	 * @var bool
	 */
	public $abort;

	/**
	 * Form ID.
	 *
	 * @since 1.1.6
	 * @var int
	 */
	public $form_id;

	/**
	 * Form object.
	 *
	 * @since 1.1.6
	 * @var object
	 */
	public $form;

	/**
	 * Forms object.
	 *
	 * @since 1.1.6
	 * @var object
	 */
	public $forms;

	/**
	 * Entry object.
	 *
	 * @since 1.1.6
	 * @var object
	 */
	public $entry;

	/**
	 * Entries object.
	 *
	 * @since 1.1.6
	 * @var object
	 */
	public $entries;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->alerts = array();
		$this->abort  = false;

		// Maybe load entries page
		add_action( 'admin_init', array( $this, 'init' ) );

		// Setup screen options - this needs to run early.
		add_action( 'load-wpforms_page_wpforms-entries', array( $this, 'screen_options' ) );
		add_filter( 'set-screen-option', array( $this, 'screen_options_set' ), 10, 3 );
	}

	/**
	 * Determing if the user is viewing the entries page, if so, party on.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Check what page we are on
		$page = !empty( $_GET['page'] ) ? $_GET['page'] : '';

		// Only load if we are actually on the overview page
		if ( $page == 'wpforms-entries' ) {

			// Determine what manage page view to load, defaults to new form
			$this->view = !empty( $_GET['view'] ) ? $_GET['view'] : 'list';

			// Global actions
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );

			//----------------------------------------------------------------//
			// Entries List view
			//----------------------------------------------------------------//
			if ( 'list' == $this->view ) {

				// Load the classes that builds the entries table
				if ( ! class_exists( 'WP_List_Table' ) ) {
					require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
				}
				require_once WPFORMS_PLUGIN_DIR . 'pro/includes/admin/entries/class-entries-table.php';

				// Preview page check
				wpforms()->preview->form_preview_check();

				// Processing and setup
				add_action( 'wpforms_entries_init', array( $this, 'entry_list_process_export'),  8, 1 );
				add_action( 'wpforms_entries_init', array( $this, 'entry_list_process_read'  ),  8, 1 );
				add_action( 'wpforms_entries_init', array( $this, 'entry_list_init'          ), 10, 1 );

				// Output
				add_action( 'wpforms_admin_page',       array( $this, 'entry_list'              )        );
				add_action( 'wpforms_entry_list_title', array( $this, 'entry_list_form_actions' ), 10, 1 );
			}

			//----------------------------------------------------------------//
			// Entries Details (Single) view
			//----------------------------------------------------------------//
			if ( 'details' == $this->view ) {

				// Entry processing and setup
				add_action( 'wpforms_entries_init',          array( $this, 'entry_details_process_export'        ),  8, 1 );
				add_action( 'wpforms_entries_init',          array( $this, 'entry_details_process_star'          ),  8, 1 );
				add_action( 'wpforms_entries_init',          array( $this, 'entry_details_process_unread'        ),  8, 1 );
				add_action( 'wpforms_entries_init',          array( $this, 'entry_details_process_note_delete'   ),  8, 1 );
				add_action( 'wpforms_entries_init',          array( $this, 'entry_details_process_note_add'      ),  8, 1 );
				add_action( 'wpforms_entries_init',          array( $this, 'entry_details_init'                  ), 10, 1 );
				add_action( 'wpforms_entries_init',          array( $this, 'entry_details_process_notifications' ), 15, 1 );

				// Entry content and metaboxes
				add_action( 'wpforms_admin_page',            array( $this, 'entry_details'         )        );
				add_action( 'wpforms_entry_details_content', array( $this, 'entry_details_fields'  ), 10, 2 );
				add_action( 'wpforms_entry_details_content', array( $this, 'entry_details_notes'   ), 10, 2 );
				add_action( 'wpforms_entry_details_content', array( $this, 'entry_details_debug'   ), 50, 2 );
				add_action( 'wpforms_entry_details_sidebar', array( $this, 'entry_details_meta'    ), 10, 2 );
				add_action( 'wpforms_entry_details_sidebar', array( $this, 'entry_details_payment' ), 15, 2 );
				add_action( 'wpforms_entry_details_sidebar', array( $this, 'entry_details_actions' ), 20, 2 );
			}

			// Provide hook for add-ons
			do_action( 'wpforms_entries_init', $this->view );
		}
	}

	/**
	 * Add per-page screen option to the Entries table.
	 *
	 * @since 1.0.0
	 */
	public function screen_options() {

		if ( 'list' != $this->view ) {
			return;
		}

		$screen = get_current_screen();

		if ( $screen->id !== 'wpforms_page_wpforms-entries' ) {
			return;
		}

		add_screen_option(
			'per_page',
			array(
				'label'   => __( 'Number of entries per page:', 'wpforms' ),
				'option'  => 'wpforms_entries_per_page',
				'default' => apply_filters( 'wpforms_entries_per_page', 30 ),
			)
		);
	}

	/**
	 * Entries table per-page screen option value
	 *
	 * @since 1.0.0
	 * @param mixed $status
	 * @param string $option
	 * @param mixed $value
	 * @return mixed
	 */
	public function screen_options_set( $status, $option, $value ) {

		if ( 'wpforms_entries_per_page' === $option ) {
			return $value;
		}

		return $status;
	}

	/**
	 * Enqueue assets for the entries pages.
	 *
	 * @since 1.0.0
	 */
	public function enqueues() {

		wp_enqueue_style(
			'wpforms-entries',
			WPFORMS_PLUGIN_URL . 'pro/assets/css/admin-entries.css',
			null,
			WPFORMS_VERSION
		);

		// Conditionall load CSS based on view
		if ( 'list' == $this->view ) {

			wp_enqueue_script(
				'wpforms-entries-list',
				WPFORMS_PLUGIN_URL . 'pro/assets/js/admin-entries-list.js',
				array( 'jquery' ),
				WPFORMS_VERSION,
				false
			);

			wp_localize_script(
				'wpforms-entries-list',
				'wpforms_entries',
				array(
					'ajax_url'	     => admin_url( 'admin-ajax.php' ),
					'nonce'          => wp_create_nonce( 'wpforms-entries-list' ),
					'delete_confirm' => __( 'Are you sure you want to delete this entry?', 'wpforms' ),
					'unstar'         => __( 'Unstar entry', 'wpforms' ),
					'star'           => __( 'Star entry', 'wpforms' ),
					'read'           => __( 'Mark entry read', 'wpforms' ),
					'unread'         => __( 'Mark entry unread', 'wpforms' ),
				)
			);
		}

		if ( 'details' == $this->view ) {

			wp_enqueue_media();

			wp_enqueue_script(
				'wpforms-entries-details',
				WPFORMS_PLUGIN_URL . 'pro/assets/js/admin-entries-details.js',
				array( 'jquery' ),
				WPFORMS_VERSION,
				false
			);

			wp_localize_script(
				'wpforms-entries-details',
				'wpforms_entries',
				array(
					'delete_confirm'      => __( 'Are you sure you want to delete this entry?', 'wpforms' ),
					'empty_fields_hide'   => __( 'Hide Empty Fields', 'wpforms' ),
					'empty_fields_show'   => __( 'Show Empty Fields', 'wpforms' ),
					'note_delete_confirm' => __( 'Are you sure you want to delete this note?', 'wpforms' ),
				)
			);
		}

		// Hook for add-ons
		do_action( 'wpforms_entries_enqueue', $this->view );
	}

	/**
	 * Display admin notices and errors.
	 *
	 * @since 1.1.6
	 */
	function display_alerts( $display = '', $wrap = false ) {

		if ( empty( $this->alerts ) ) {
			return;

		} else {

			if ( empty( $display ) ) {
				$display = array( 'error', 'info', 'warning', 'success' );
			} else {
				$display = (array) $display;
			}

			foreach( $this->alerts as $alert ) {

				$type = !empty( $alert['type'] ) ? $alert['type'] : 'info';

				if ( in_array( $type, $display ) ) {
					$class  = 'notice-' . $type;
					$class .= !empty( $alert['dismiss'] ) ? ' is-dismissible' : '';
					$output = '<div class="notice ' . $class . '"><p>' . $alert['message'] . '</p></div>';
					if ( $wrap ) {
						echo '<div class="wrap">' . $output . '</div>';
					} else {
						echo $output;
					}
					if ( !empty( $alert['abort'] ) ) {
						$this->abort = true;
						break;
					}
				}
			}
		}
	}

	//------------------------------------------------------------------------//
	//
	//  Entries List view
	//
	//------------------------------------------------------------------------//

	/**
	 * Watches for and runs complete form exports.
	 *
	 * @since 1.1.6
	 */
	public function entry_list_process_export() {

		// Check for run switch
		if ( empty( $_GET['export'] ) || empty( $_GET['form_id'] ) || 'all' != $_GET['export'] )
			return;

		// Security check
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'wpforms_entry_list_export' ) )
			return;

		require_once WPFORMS_PLUGIN_DIR . 'pro/includes/admin/entries/class-entries-export.php';

		$export = new WPForms_Entries_Export();
		$export->entry_type = 'all';
		$export->form_id    =  absint( $_GET['form_id'] );
		$export->export();
	}

	/**
	 * Watches for and runs complete marking all entries as read.
	 *
	 * @since 1.1.6
	 */
	public function entry_list_process_read() {

		// Check for run switch
		if ( empty( $_GET['action'] ) || empty( $_GET['form_id'] ) || 'markread' != $_GET['action'] )
			return;

		// Security check
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'wpforms_entry_list_markread' ) )
			return;

		wpforms()->entry->mark_all_read( $_GET['form_id'] );

		$this->alerts[] = array(
			'type'    => 'success',
			'message' => __( 'All entries marked as read.', 'wpforms' ),
			'dismiss' => true,
		);
	}

	/**
	 * Initilize entry list page.
	 *
	 * This function does the error checking and variable setup.
	 *
	 * @since 1.1.6
	 */
	public function entry_list_init() {

		// Fetch all forms
		$this->forms = wpforms()->form->get( '',  array( 'orderby' => 'ID', 'order' => 'ASC' ) );

		// Check that that user has created at least one form
		if ( empty( $this->forms ) ) {

			$this->alerts[] = array(
				'type'    => 'info',
				'message' =>  sprintf( __( 'Whoops, you haven\'t created a form yet. Want to <a href="%s">give it a go</a>?', 'wpforms' ), admin_url( 'admin.php?page=wpforms-builder' ) ),
				'abort'   =>  true,
			);

		} else {
			$this->form_id = !empty( $_GET['form_id'] ) ? absint( $_GET['form_id'] ) : absint( $this->forms[0]->ID );
			$this->form    = wpforms()->form->get( $this->form_id );
		}
	}

	/**
	 * List all entries in a specific form.
	 *
	 * @since 1.0.0
	 */
	public function entry_list() {

		$form_data = !empty( $this->form->post_content ) ? wpforms_decode( $this->form->post_content ) : '';
		?>
		<div id="wpforms-entries" class="wrap list">

			<h1 class="page-title"><?php _e( 'Entries', 'wpforms' ); ?></h1>

			<?php
			// Admin notices
			$this->display_alerts();
			if ( $this->abort ) {
				echo '</div>'; // close wrap
				return;
			}

			$this->entries = new WPForms_Entries_Table;
			$this->entries->form_id   = $this->form_id;
			$this->entries->form_data = $form_data;
			$this->entries->prepare_items();
			?>

			<?php do_action( 'wpforms_entry_list_title', $form_data, $this ); ?>

			<form id="wpforms-entries-table" method="get" action="<?php echo admin_url( 'admin.php?page=wpforms-entries' ); ?>">

					<input type="hidden" name="page" value="wpforms-entries" />
					<input type="hidden" name="view" value="list" />
					<input type="hidden" name="form_id" value="<?php echo $this->form_id; ?>" />

					<?php $this->entries->views(); ?>
					<?php $this->entries->display(); ?>

			</form>
		</div>
		<?php
	}

	/**
	 * Entry list form actions.
	 *
	 * @since 1.1.6
	 */
	public function entry_list_form_actions( $form_data ) {

		$base = add_query_arg(
			array(
				'page'    => 'wpforms-entries',
				'view'    => 'list',
				'form_id' => absint( $this->form_id ),
			),
			admin_url( 'admin.php' )
		);

		// Edit Form URL
		$edit_url = add_query_arg(
			array(
				'page'    => 'wpforms-builder',
				'view'    => 'fields',
				'form_id' => absint( $this->form_id ),
			 ),
			admin_url( 'admin.php' )
		);

		// Preview Entry URL
		$preview_url = esc_url( wpforms()->preview->form_preview_url( $this->form_id ) );

		// Export Entry URL
		$export_url = wp_nonce_url(
			add_query_arg(
				array(
					'export' => 'all',
				),
				$base
			),
			'wpforms_entry_list_export'
		);

		// Mark Read URL
		$read_url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'markread',
				),
				$base
			),
			'wpforms_entry_list_markread'
		);
		?>

		<div class="form-details">

			<h3 class="form-details-title">
				<?php echo wp_strip_all_tags( $form_data['settings']['form_title'] ); ?>
			</h3>

			<div class="form-details-actions wpforms-clear">

				<div class="form-details-action-links">

					<a href="<?php echo $edit_url; ?>" class="form-details-actions-edit">
						<span class="dashicons dashicons-edit"></span>
						<?php _e( 'Edit This Form', 'wpforms' ); ?>
					</a>

					<a href="<?php echo $preview_url; ?>" class="form-details-actions-preview" target="_blank" rel="noopener">
						<span class="dashicons dashicons-visibility"></span>
						<?php _e( 'Preview Form', 'wpforms' ); ?>
					</a>

					<a href="<?php echo $export_url; ?>" class="form-details-actions-export">
						<span class="dashicons dashicons-migrate"></span>
						<?php _e( 'Download Export (CSV)', 'wpforms' ); ?>
					</a>

					<a href="<?php echo $read_url; ?>" class="form-details-actions-read">
						<span class="dashicons dashicons-marker"></span>
						<?php _e( 'Mark All Read', 'wpforms' ); ?>
					</a>

				</div>

				<select class="form-details-action-switch"> style="width:300px;">
					<option value=""><?php _e( 'Select a different form', 'wpforms' ); ?>
					<?php
					foreach( $this->forms as $key => $form ) {
						$form_url = add_query_arg(
							array(
								'page'    => 'wpforms-entries',
								'view'    => 'list',
								'form_id' => absint( $form->ID ),
							),
							admin_url( 'admin.php' )
						);
						echo '<option value="' . $form_url . '">' . esc_html( $form->post_title ) . '</option>';
					}
					?>
				</select>

			</div>

		</div>
		<?php
	}

	/**
	 * Modal containing form to edit table column display.
	 *
	 * @since 1.1.6
	 */
	public function entry_list_column_modal() {

		wpforms_modal( $title, $content, $args );
	}

	//------------------------------------------------------------------------//
	//
	//  Entries Details view
	//
	//------------------------------------------------------------------------//

	/**
	 * Watches for and runs single entry exports.
	 *
	 * @since 1.1.6
	 */
	public function entry_details_process_export() {

		// Check for run switch
		if ( empty( $_GET['export'] ) || !is_numeric( $_GET['export'] ) )
			return;

		// Security check
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'wpforms_entry_details_export' ) )
			return;

		require_once WPFORMS_PLUGIN_DIR . 'pro/includes/admin/entries/class-entries-export.php';

		$export = new WPForms_Entries_Export();
		$export->entry_type = absint( $_GET['export'] );
		$export->export();
	}

	/**
	 * Watches for and runs starring/unstarring entry.
	 *
	 * @since 1.1.6
	 */
	public function entry_details_process_star() {

		// Security check
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'wpforms_entry_details_star' ) )
			return;

		// Check for starring
		if ( !empty( $_GET['entry_id'] ) && !empty( $_GET['action'] ) && 'star' === $_GET['action'] ) {

			wpforms()->entry->update( absint( $_GET['entry_id'] ), array( 'starred' => '1' ) );

			$this->alerts[] = array(
				'type'    => 'success',
				'message' => __( 'This entry has been starred.', 'wpforms' ),
				'dismiss' => true,
			);
		}

		// Check for starring
		if ( !empty( $_GET['entry_id'] ) && !empty( $_GET['action'] ) && 'unstar' === $_GET['action'] ) {

			wpforms()->entry->update( absint( $_GET['entry_id'] ), array( 'starred' => '0' ) );

			$this->alerts[] = array(
				'type'    => 'success',
				'message' => __( 'This entry has been unstarred.', 'wpforms' ),
				'dismiss' => true,
			);
		}
	}

	/**
	 * Watches for and entry unread toggle.
	 *
	 * @since 1.1.6
	 */
	public function entry_details_process_unread() {

		// Check for run switch
		if ( empty( $_GET['entry_id'] ) || empty( $_GET['action'] ) || 'unread' !== $_GET['action'] )
			return;

		// Security check
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'wpforms_entry_details_unread' ) )
			return;

		wpforms()->entry->update( absint( $_GET['entry_id'] ), array( 'viewed' => '0' ) );

		$this->alerts[] = array(
			'type'    => 'success',
			'message' => __( 'This entry has been marked unread.', 'wpforms' ),
			'dismiss' => true,
		);
	}

	/**
	 * Watches for and runs entry note deletion.
	 *
	 * @since 1.1.6
	 */
	public function entry_details_process_note_delete() {

		// Check for run switch
		if ( empty( $_GET['note_id'] ) || empty( $_GET['action'] ) || 'delete_note' !== $_GET['action'] )
			return;

		// Security check
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'wpforms_entry_details_deletenote' ) )
			return;

		wpforms()->entry_meta->delete( absint( $_GET['note_id'] ) );

		$this->alerts[] = array(
			'type'    => 'success',
			'message' => __( 'Note deleted.', 'wpforms' ),
			'dismiss' => true,
		);
	}

	/**
	 * Watches for and runs creating entry notes.
	 *
	 * @since 1.1.6
	 */
	public function entry_details_process_note_add() {

		// Check for post trigger and required vars
		if ( empty( $_POST['wpforms_add_note'] ) || empty( $_POST['entry_id'] ) || empty( $_POST['entry_id'] ) )
			return;

		// Security check
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'wpforms_entry_details_addnote' ) )
			return;

		$note = array(
			'entry_id' => absint( $_POST['entry_id'] ),
			'form_id'  => absint( $_POST['form_id'] ),
			'user_id'  => get_current_user_id(),
			'type'     => 'note',
			'data'     => wpautop( wp_kses_post( $_POST['entry_note'] ) ),
		);
		$note_id = wpforms()->entry_meta->add( $note, 'entry_meta' );

		$this->alerts[] = array(
			'type'    => 'success',
			'message' => __( 'Note added.', 'wpforms' ),
			'dismiss' => true,
		);
	}

	/**
	 * Initilize entry details page.
	 *
	 * This function does the error checking and variable setup.
	 *
	 * @since 1.1.6
	 */
	public function entry_details_init() {

		// No entry ID was provided, error.
		if ( empty( $_GET['entry_id'] ) ) {
			$this->alerts[] = array(
				'type'    => 'error',
				'message' => __( 'Invalid entry ID.', 'wpforms' ),
				'abort'   => true,
			);
			return;
		}

		// Find the entry
		$entry = wpforms()->entry->get( absint( $_GET['entry_id'] ) );

		// No entry was found, error.
		if ( ! $entry || empty( $entry ) ) {
			$this->alerts[] = array(
				'type'    => 'error',
				'message' => __( 'Entry not found.', 'wpforms' ),
				'abort'   => true,
			);
			return;
		}

		// Find the form information
		$form = wpforms()->form->get( $entry->form_id );

		// No form was found, error.
		if ( ! $form || empty( $form ) ) {
			$this->alerts[] = array(
				'type'    => 'error',
				'message' => __( 'Form not found.', 'wpforms' ),
				'abort'   => true,
			);
			return;
		}

		// Form details
		$form_data       = wpforms_decode( $form->post_content );
		$form->form_url  = add_query_arg( array( 'page' => 'wpforms-entries', 'view' => 'list', 'form_id' => absint( $form_data['id'] ) ), admin_url( 'admin.php' ) );

		// Define other entry details
		$entry->entry_next       = wpforms()->entry->get_next( $entry->entry_id, $form_data['id'] );
		$entry->entry_next_url   = !empty( $entry->entry_next ) ? add_query_arg( array( 'page' => 'wpforms-entries', 'view' => 'details', 'entry_id' => absint( $entry->entry_next->entry_id ) ), admin_url( 'admin.php' ) ) : '#';
		$entry->entry_next_class = !empty( $entry->entry_next ) ? '' : 'inactive';
		$entry->entry_prev       = wpforms()->entry->get_prev( $entry->entry_id, $form_data['id'] );
		$entry->entry_prev_url   = !empty( $entry->entry_prev ) ? add_query_arg( array( 'page' => 'wpforms-entries', 'view' => 'details', 'entry_id' => absint( $entry->entry_prev->entry_id ) ), admin_url( 'admin.php' ) ) : '#';
		$entry->entry_prev_class = !empty( $entry->entry_prev ) ? '' : 'inactive';
		$entry->entry_prev_count = wpforms()->entry->get_prev_count( $entry->entry_id, $form_data['id'] );
		$entry->entry_count      = wpforms()->entry->get_entries( array( 'form_id' =>  $form_data['id'] ), true );
		$entry->entry_notes      = wpforms()->entry_meta->get_meta( array( 'entry_id' => $entry->entry_id, 'type' => 'note' ) );

		// Make public
		$this->entry = $entry;
		$this->form  = $form;

		// Lastly, mark entry as read if needed
		if ( $entry->viewed !== '1' && empty( $_GET['action'] ) ) {
			wpforms()->entry->update( $entry->entry_id, array( 'viewed' => '1' ) );
			$this->entry->viewed = '1';
		}

		do_action( 'wpforms_entry_details_init', $this );
	}

	/**
	 * Watches for and runs single entry notifcations.
	 *
	 * @since 1.1.6
	 */
	public function entry_details_process_notifications() {

		// Check for run switch
		if ( empty( $_GET['action'] ) || 'notifications' !== $_GET['action'] )
			return;

		// Security check
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'wpforms_entry_details_notifications' ) )
			return;

		// Check for existing errors
		if ( $this->abort || empty( $this->entry ) || empty( $this->form ) )
			return;

		$fields    = wpforms_decode( $this->entry->fields );
		$form_data = wpforms_decode( $this->form->post_content );

		wpforms()->process->entry_email( $fields, array(), $form_data );

		$this->alerts[] = array(
			'type'    => 'success',
			'message' => __( 'Notifications sent!', 'wpforms' ),
			'dismiss' => true,
		);
	}

	/**
	 * Entry Details page.
	 *
	 * @since 1.0.0
	 */
	public function entry_details() {

		// Check for blocking errors to display
		$this->display_alerts( 'error', true );

		if ( $this->abort ) {
			return;
		}

		$entry     = $this->entry;
		$form_data = wpforms_decode( $this->form->post_content );
 		?>

		<div id="wpforms-entries" class="wrap details">

			<h1 class="page-title">

				<?php _e( 'View Entry', 'wpforms' ); ?>

				<a href="<?php echo esc_url( $this->form->form_url ); ?>" class="add-new-h2 wpforms-entry-back"><?php _e( 'Back to All Entries', 'wpforms' ); ?></a>

				<div class="wpforms-entry-navigation">
					<span class="wpforms-entry-navigation-text">
						<?php printf( __( 'Entry %s of %s', 'wpforms' ), $entry->entry_prev_count+1, $entry->entry_count ); ?>
					</span>
					<span class="wpforms-entry-navigation-buttons">
						<a href="<?php echo esc_url( $entry->entry_prev_url ); ?>" title="<?php esc_attr_e( 'Previous form entry', 'wpforms' ); ?>" class="add-new-h2 wpforms-entry-prev <?php echo $entry->entry_prev_class; ?>"><span class="dashicons dashicons-arrow-left-alt2"></span></a>
						<span class="wpforms-entry-current" title="<?php esc_attr_e( 'Current form entry', 'wpforms' ); ?>"><?php echo $entry->entry_prev_count+1; ?></span>
						<a href="<?php echo esc_url( $entry->entry_next_url ); ?>" title="<?php esc_attr_e( 'Next form entry', 'wpforms' ); ?>" class=" add-new-h2 wpforms-entry-next <?php echo $entry->entry_next_class; ?>"><span class="dashicons dashicons-arrow-right-alt2"></span></a>
					</span>&nbsp;
				</div>

			</h1>

			<?php $this->display_alerts(); ?>

			<div id="poststuff">

				<div id="post-body" class="metabox-holder columns-2">

					<!-- Left column -->
					<div id="post-body-content" style="position: relative;">
						<?php do_action( 'wpforms_entry_details_content', $entry, $form_data, $this ); ?>
					</div>

					<!-- Right column -->
					<div id="postbox-container-1" class="postbox-container">
						<?php do_action( 'wpforms_entry_details_sidebar', $entry, $form_data, $this ); ?>
					</div>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Entry fields metabox.
	 *
	 * @since 1.1.5
	 * @param object $entry
	 * @param array $form_data
	 */
	public function entry_details_fields( $entry, $form_data ) {

		$hide_empty = isset( $_COOKIE['wpforms_entry_hide_empty'] ) && 'true' === $_COOKIE['wpforms_entry_hide_empty'] ;
		?>
		<!-- Entry Fields metabox -->
		<div id="wpforms-entry-fields" class="postbox">

			<h2 class="hndle">
				<?php echo $entry->starred == '1' ? '<span class="dashicons dashicons-star-filled"></span>' : ''; ?>
				<span><?php echo esc_html( $form_data['settings']['form_title'] ); ?></span>
				<a href="#" class="wpforms-empty-field-toggle">
					<?php echo $hide_empty ? __( 'Show Empty Fields', 'wpforms' ) : __( 'Hide Empty Fields', 'wpforms' ) ?>
				</a>
			</h2>

			<div class="inside">

				<?php
				$fields = apply_filters( 'wpforms_entry_single_data', wpforms_decode( $entry->fields ), $entry, $form_data );

				if ( empty( $fields ) ) {

					// Whoops, no fields! This shouldn't happen under normal use cases.
					echo '<p class="no-fields">' . __( 'This entry does not have any fields', 'wpforms' ) . '</p>';

				} else {

					// Display the fields and their values
					foreach ( $fields as $key => $field ) {

						$field_value  = apply_filters( 'wpforms_html_field_value', wp_strip_all_tags( $field['value'] ), $field, $form_data, 'entry-single' );
						$field_class  = sanitize_html_class( 'wpforms-field-' . $field['type'] );
						$field_class .= empty( $field_value ) ? ' empty' : '';
						$field_style  = $hide_empty &&  empty( $field_value ) ? 'display:none;' : '';

						echo '<div class="wpforms-entry-field ' . $field_class . '" style="' . $field_style . '">';

							// Field name
							echo '<p class="wpforms-entry-field-name">';
								echo !empty( $field['name'] ) ? wp_strip_all_tags( $field['name'] ) : sprintf( __( 'Field ID #%d', 'wpforms' ), absint( $field['id'] ) );
							echo '</p>';

							// Field value
							echo '<p class="wpforms-entry-field-value">';
								echo !empty( $field_value ) ? nl2br( make_clickable( $field_value ) ) : __( 'Empty', 'wpforms' );
							echo '</p>';

						echo '</div>';
					}
				}
	 			?>

			</div>

		</div>
		<?php
	}

	/**
	 * Entry notes metabox.
	 *
	 * @since 1.1.6
	 * @param object $entry
	 * @param array $form_data
	 */
	public function entry_details_notes( $entry, $form_data ) {

		$action_url = add_query_arg(
			array(
				'page'     => 'wpforms-entries',
				'view'     => 'details',
				'entry_id' => absint( $entry->entry_id ),
			),
			admin_url( 'admin.php' )
		);
		?>
		<!-- Entry Notes metabox -->
		<div id="wpforms-entry-notes" class="postbox">

			<h2 class="hndle"><span><?php _e( 'Notes', 'wpforms' ); ?></span></h2>

			<div class="inside">

				<div class="wpforms-entry-notes-new">

					<a href="#" class="button add"><?php _e( 'Add Note', 'wpforms' ); ?></a>

					<form action="<?php echo $action_url; ?>" method="post">
						<?php
						$args = array(
							'media_buttons' => false,
							'editor_height' => 50,
							'teeny'         => true,
						);
						wp_editor( '', 'entry_note', $args );
						wp_nonce_field( 'wpforms_entry_details_addnote' );
						?>
						<input type="hidden" name="entry_id" value="<?php echo absint( $entry->entry_id ); ?>">
						<input type="hidden" name="form_id" value="<?php echo absint( $form_data['id'] ); ?>">
						<div class="btns">
							<input type="submit" name="wpforms_add_note" class="save button-primary alignright" value="<?php _e( 'Add Note', 'wpforms' ); ?>">
							<a href="#" class="cancel button-secondary alignleft"><?php _e( 'Cancel', 'wpforms' ); ?></a>
						</div>
					</form>

				</div>

				<?php
				if ( empty( $entry->entry_notes ) ) {
					echo '<p class="no-notes">' . __( 'No notes.', 'wpforms' ) . '</p>';
				} else {
					echo '<div class="wpforms-entry-notes-list">';
					$count = 1;
					foreach ( $entry->entry_notes as $note ) {
						$user        = get_userdata( $note->user_id );
						$user_name   = esc_html( !empty( $user->display_name ) ? $user->display_name : $user->user_login );
						$user_url    = esc_url( add_query_arg( array( 'user_id' => absint( $user->ID ) ), admin_url( 'user-edit.php' ) ) );
						$gravatar    = get_avatar( $note->user_id, 30 );
						$date_format = sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) );
						$date        = date( $date_format, strtotime( $note->date ) + ( get_option( 'gmt_offset' ) * 3600 ) );
						$class       = $count % 2 == 0 ? 'even' : 'odd';
						$delete_url  = wp_nonce_url(
							add_query_arg(
								array(
									'page'     => 'wpforms-entries',
									'view'     => 'details',
									'entry_id' => absint( $entry->entry_id ),
									'note_id'  => absint( $note->id ),
									'action'   => 'delete_note',
								),
								admin_url( 'admin.php' )
							),
							'wpforms_entry_details_deletenote'
						);
						?>
						<div class="wpforms-entry-notes-single <?php echo $class; ?>">
							<div class="wpforms-entry-notes-byline">
								<?php _e( 'Added by', 'wpforms'); ?> <a href="<?php echo $user_url; ?>" class="note-user"><?php echo $user_name; ?></a> <?php _e( 'on', 'wpforms' ); ?> <?php echo $date; ?> <span class="sep">|</span> <a href="<?php echo $delete_url; ?>" class="note-delete"><?php _e( 'Delete', 'wpforms'); ?></a>
							</div>
							<?php echo wp_kses_post( $note->data ); ?>
						</div>
						<?php
						$count++;
					}
					echo '</div>';
				}
				?>

			</div>

		</div>
		<?php
	}

	/**
	 * Entry debug metabox. Hidden by default obviously/
	 *
	 * @since 1.1.6
	 * @param object $entry
	 * @param array $form_data
	 */
	public function entry_details_debug( $entry, $form_data ) {

		if ( ! wpforms_debug() )
			return;

		?>
		<!-- Entry Debug metabox -->
		<div id="wpforms-entry-debug" class="postbox">

			<h2 class="hndle"><span><?php _e( 'Debug Information', 'wpforms' ); ?></span></h2>

			<div class="inside">

				<?php wpforms_debug_data( $entry ); ?>
				<?php wpforms_debug_data( $form_data ); ?>

			</div>

		</div>
		<?php
	}

	/**
	 * Entry Meta Details metabox.
	 *
	 * @since 1.1.5
	 * @param object $entry
	 * @param array $form_data
	 */
	public function entry_details_meta( $entry, $form_data ) {

		$date_format = sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) );
		?>
		<!-- Entry Details metabox -->
		<div id="wpforms-entry-details" class="postbox">

			<h2 class="hndle"><span><?php _e( 'Entry Details' ,'wpforms' ); ?></span></h2>

			<div class="inside">

				<div class="wpforms-entry-details-meta">

					<p class="wpforms-entry-id">
						<span class="dashicons dashicons-admin-network"></span></span>
						<?php _e( 'Entry ID:', 'wpforms' ); ?>
						<strong><?php echo absint( $entry->entry_id ); ?></strong>
					</p>

					<p class="wpforms-entry-date">
						<span class="dashicons dashicons-calendar"></span>
						<?php _e( 'Submitted on:', 'wpforms' ); ?>
						<strong><?php echo date_i18n( __( 'M j, Y @ g:ia' ), strtotime( $entry->date ) + ( get_option( 'gmt_offset' ) * 3600 ) ); ?> </strong>
					</p>

					<?php if ( '0000-00-00 00:00:00' != $entry->date_modified ) : ?>
					<p class="wpforms-entry-modified">
						<span class="dashicons dashicons-calendar-alt"></span>
						<?php _e( 'Modified on:', 'wpforms' ); ?>
						<strong><?php echo date_i18n( __( 'M j, Y @ H:i' ), strtotime( $entry->date_modified ) + ( get_option( 'gmt_offset' ) * 3600 ) ); ?> </strong>
					</p>
					<?php endif; ?>

					<?php if ( !empty( $entry->user_id ) && '0' != $entry->user_id ) : ?>
					<p class="wpforms-entry-user">
						<span class="dashicons dashicons-admin-users"></span>
						<?php
						_e( 'User:', 'wpforms' );
						$user      = get_userdata( $entry->user_id );
						$user_name = esc_html( !empty( $user->display_name ) ? $user->display_name : $user->user_login );
						$user_url  = esc_url( add_query_arg( array( 'user_id' => absint( $user->ID ) ), admin_url( 'user-edit.php' ) ) );
						?>
						<strong><a href="<?php echo $user_url; ?>"><?php echo $user_name; ?></a></strong>
					</p>
					<?php endif; ?>

					<?php if ( !empty( $entry->ip_address ) ) : ?>
					<p class="wpforms-entry-ip">
						<span class="dashicons dashicons-location"></span>
						<?php _e( 'User IP:', 'wpforms' ); ?>
						<strong><?php echo esc_html( $entry->ip_address ); ?></strong>
					</p>
					<?php endif; ?>

					<?php do_action( 'wpforms_entry_details_sidebar_details', $entry, $form_data ); ?>

				</div>

				<div id="major-publishing-actions">

					<div id="delete-action">
						<a class="submitdelete deletion" href="<?php echo wp_nonce_url( add_query_arg( array( 'view' => 'list', 'action' => 'delete', 'form_id' => $form_data['id'], 'entry_id' => $entry->entry_id ) ), 'bulk-entries' ); ?>">Delete Entry</a>
					</div>

					<!-- Phase 2 <div id="publishing-action">
						<input name="" type="submit" class="button button-primary button-large" value="Edit">
					</div> -->

					<div class="clear"></div>
				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Entry Payment Details metabox.
	 *
	 * @since 1.2.6
	 * @param object $entry
	 * @param array $form_data
	 */
	public function entry_details_payment( $entry, $form_data ) {

		if ( empty( $entry->type ) || 'payment' != $entry->type )
			return;

		$entry_meta  = json_decode( $entry->meta, true );
		$status      = !empty( $entry->status ) ? ucwords( sanitize_text_field( $entry->status ) ) : __( 'Unknown', 'wpforms' );
		$currency    = !empty( $entry_meta['payment_currency'] ) ? $entry_meta['payment_currency'] : wpforms_setting( 'currency', 'USD' );
		$total       = isset( $entry_meta['payment_total'] ) ? wpforms_format_amount( wpforms_sanitize_amount( $entry_meta['payment_total'], $currency ), true, $currency ) : '-';
		$note        = !empty( $entry_meta['payment_note'] ) ? esc_html( $entry_meta['payment_note'] ) : '';
		$gateway     = esc_html( apply_filters( 'wpforms_entry_details_payment_gateway', '-', $entry_meta, $entry, $form_data ) );
		$transaction = esc_html( apply_filters( 'wpforms_entry_details_payment_transaction', '-', $entry_meta, $entry, $form_data ) );
		$mode        = !empty( $entry_meta['payment_mode'] ) && 'test' == $entry_meta['payment_mode'] ? 'test' : 'production';

		switch ( $entry_meta['payment_type']) {
			case 'stripe':
				$gateway = __( 'Stripe', 'wpforms' );
				if ( !empty( $entry_meta['payment_transaction'] )  ) {
					$transaction = sprintf( '<a href="https://dashboard.stripe.com/payments/%s" target="_blank" rel="noopener">%s</a>', $entry_meta['payment_transaction'], $entry_meta['payment_transaction'] );
				}
				break;
			case 'paypal_standard':
				$gateway = __( 'PayPal Standard', 'wpforms' );
				if ( !empty( $entry_meta['payment_transaction'] )  ) {
					$type = 'production' == $mode ? '' : 'sandbox.';
					$transaction = sprintf( '<a href="https://www.%spaypal.com/webscr?cmd=_history-details-from-hub&id=%s" target="_blank" rel="noopener">%s</a>', $type, $entry_meta['payment_transaction'], $entry_meta['payment_transaction'] );
				}
				break;
		}
		?>

		<!-- Entry Payment details metabox -->
		<div id="wpforms-entry-payment" class="postbox">

			<h2 class="hndle"><span><?php _e( 'Payment Details' ,'wpforms' ); ?></span></h2>

			<div class="inside">

				<div class="wpforms-entry-payment-meta">

					<p class="wpforms-entry-payment-status">
						<?php echo __( ' Status:', 'wpforms' ) . sprintf( ' <strong>%s</strong>', $status ); ?>
					</p>

					<p class="wpforms-entry-payment-total">
						<?php echo __( ' Total:', 'wpforms' ) . sprintf( ' <strong>%s</strong>', $total ); ?>
					</p>

					<p class="wpforms-entry-payment-gateway">
						<?php
						echo __( ' Gateway:', 'wpforms' ) . sprintf( ' <strong>%s</strong>', $gateway );
						if ( 'test' == $mode ) {
							printf( ' (%s)', __( 'Test', 'wpforms' ) );
						}
						?>
					</p>

					<p class="wpforms-entry-payment-transaction">
						<?php echo __( 'Transaction ID:', 'wpforms' ) . sprintf( ' <strong>%s</strong>', $transaction ); ?>
					</p>

					<?php if ( ! empty( $note ) ) : ?>
					<p class="wpforms-entry-payment-note">
						<?php echo __( 'Note:', 'wpforms' ) . '<br>' . esc_html( $note ); ?>
					</p>
					<?php endif; ?>

					<?php do_action( 'wpforms_entry_payment_sidebar_actions', $entry, $form_data ); ?>

				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Entry Actions metabox.
	 *
	 * @since 1.1.5
	 * @param object $entry
	 * @param array $form_data
	 */
	public function entry_details_actions( $entry, $form_data ) {

		$base = add_query_arg(
			array(
				'page'     => 'wpforms-entries',
				'view'     => 'details',
				'entry_id' => absint( $entry->entry_id ),
			),
			admin_url( 'admin.php' )
		);

		// Print Entry URL
		$print_url = add_query_arg(
			array(
				'wpforms_preview' => 'print',
				'entry_id'        => absint( $entry->entry_id ),
			),
			home_url()
		);

		// Export Entry URL
		$export_url = wp_nonce_url(
			add_query_arg(
				array(
					'form_id'  => absint( $form_data['id'] ),
					'export'   => absint( $entry->entry_id ),
				),
				$base
			),
			'wpforms_entry_details_export'
		);

		// Resend Entry Notifications URL
		$notifications_url = wp_nonce_url(
			add_query_arg(
				array(
					'action'   => 'notifications',
				),
				$base
			),
			'wpforms_entry_details_notifications'
		);

		// Star Entry URL
		$star_url = wp_nonce_url(
			add_query_arg(
				array(
					'action'   => $entry->starred == '1' ? 'unstar' : 'star',
				),
				$base
			),
			'wpforms_entry_details_star'
		);
		$star_icon = $entry->starred == '1' ? 'dashicons-star-empty' : 'dashicons-star-filled';
		$star_text = $entry->starred == '1' ? __( 'Unstar', 'wpforms' ) : __( 'Star', 'wpforms' );

		// Unread URL
		$unread_url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'unread',
				),
				$base
			),
			'wpforms_entry_details_unread'
		);
		?>

		<!-- Entry Actions metabox -->
		<div id="wpforms-entry-actions" class="postbox">

			<h2 class="hndle"><span><?php _e( 'Actions', 'wpforms' ); ?></span></h2>

			<div class="inside">

				<div class="wpforms-entry-actions-meta">

					<p class="wpforms-entry-print">
						<a href="<?php echo esc_url( $print_url ); ?>" target="_blank" rel="noopener">
							<span class="dashicons dashicons-media-text"></span>
							<?php _e( 'Print', 'wpforms' ); ?>
						</a>
					</p>

					<p class="wpforms-entry-export">
						<a href="<?php echo esc_url( $export_url ); ?>">
							<span class="dashicons dashicons-migrate"></span>
							<?php _e( 'Export (CSV)', 'wpforms' ); ?>
						</a>
					</p>

					<p class="wpforms-entry-notifications">
						<a href="<?php echo esc_url( $notifications_url ); ?>">
							<span class="dashicons dashicons-email-alt"></span>
							<?php _e( 'Resend Notifications', 'wpforms' ); ?>
						</a>
					</p>

					<?php if ( $entry->viewed == '1') : ?>
					<p class="wpforms-entry-read">
						<a href="<?php echo $unread_url; ?>">
							<span class="dashicons dashicons-hidden"></span>
							<?php _e( 'Mark Unread', 'wpforms' ); ?>
						</a>
					</p>
					<?php endif; ?>

					<p class="wpforms-entry-star">
						<a href="<?php echo $star_url; ?>">
							<span class="dashicons <?php echo $star_icon; ?>"></span>
							<?php echo $star_text; ?>
						</a>
					</p>

					<?php do_action( 'wpforms_entry_details_sidebar_actions', $entry, $form_data ); ?>

				</div>

			</div>

		</div>
		<?php
	}
}
new WPForms_Entries;