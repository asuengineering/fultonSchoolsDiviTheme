<?php
/**
 * Generates the table on the entries overview page.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Entries_Table extends WP_List_Table {

	/**
	 * Number of entries to show per page.
	 *
	 * @since 1.0.0
	 */
	public $per_page;

	/**
	 * Form data as an array
	 *
	 * @since 1.0.0
	 */
	public $form_data;

	/**
	 * Form id.
	 *
	 * @since 1.0.0
	 */
	public $form_id;

	/**
	 * Number of different entry types
	 *
	 * @since 1.0.0
	 */
	public $counts;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Bring globals into scope for parent.
		global $status, $page;

		// Utilize the parent constructor to build the main class properties.
		parent::__construct(
			array(
				'singular' => 'entry',
				'plural'   => 'entries',
				'ajax'     => false,
			)
		);

		// Default number of forms to show per page
		$this->per_page = apply_filters( 'wpforms_entries_per_page', 30 );
	}

	/**
	 * Get the entry counts for varios types of entries.
	 *
	 * @since 1.0.0
	 */
	public function get_counts() {

		$this->counts = array();
		$this->counts['total']   = wpforms()->entry->get_entries( array( 'form_id' => $this->form_id ), true );
		$this->counts['unread']  = wpforms()->entry->get_entries( array( 'form_id' => $this->form_id, 'viewed' => '0' ), true );
		$this->counts['starred'] = wpforms()->entry->get_entries( array( 'form_id' => $this->form_id, 'starred' => '1' ), true );
	}

	/**
	 * Retrieve the view types.
	 *
	 * @since 1.1.6
	 */
	public function get_views() {

		$base = add_query_arg(
			array(
				'page'    => 'wpforms-entries',
				'view'    => 'list',
				'form_id' => $this->form_id,
			),
			admin_url( 'admin.php' )
		);

		$current = isset( $_GET['type'] ) ? $_GET['type'] : '';
		$total   = '&nbsp;<span class="count">(<span class="total-num">' . $this->counts['total'] . '</span>)</span>';
		$unread  = '&nbsp;<span class="count">(<span class="unread-num">' . $this->counts['unread'] . '</span>)</span>';
		$starred = '&nbsp;<span class="count">(<span class="starred-num">' . $this->counts['starred']  . '</span>)</span>';

		$views = array(
			'all'		=> sprintf( '<a href="%s"%s>%s</a>', esc_url( remove_query_arg( 'type', $base ) ), $current === 'all' || $current == '' ? ' class="current"' : '', __( 'All', 'wpforms' ) . $total ),
			'unread'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'type', 'unread', $base ) ), $current === 'unread' ? ' class="current"' : '', __( 'Unread', 'wpforms' ) . $unread ),
			'starred'	=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( 'type', 'starred', $base ) ), $current === 'starred' ? ' class="current"' : '', __( 'Starred', 'wpforms' ) . $starred ),
		);

		return $views;
	}

	/**
	 * Retrieve the table columns
	 *
	 * @since 1.0.0
	 * @return array $columns Array of all the list table columns
	 */
	public function get_columns() {

		$has_payments  = wpforms_has_payment( 'form', $this->form_data );
		$field_columns = $has_payments ? 2 : 3;

		$columns               = array();
		$columns['cb']         = '<input type="checkbox" />';
		$columns['indicators'] = __( '', 'wpforms' );
		$columns               = $this->get_columns_form_fields( $columns, $field_columns );

		// Additional columns for forms that contain payments
		if ( $has_payments ) {
			$columns['payment_status'] = __( 'Status', 'wpforms' );
			$columns['payment_total']  = __( 'Total', 'wpforms' );
		}

		$columns['date']       = __( 'Date', 'wpforms' );
		$columns['actions']    = __( 'Actions', 'wpforms' );

		return apply_filters( 'wpforms_entries_table_columns', $columns );
	}

	/**
	 * Retrieve the table's sortable columns.
	 *
	 * @since 1.2.6
	 * @return array Array of all the sortable columns
	 */
	public function get_sortable_columns() {

		return array(
			'id'             => array( 'title', false ),
			'date'           => array( 'date', false ),
			'payment_status' => array( 'payment_status', false ),
			'payment_total'  => array( 'payment_total', false ),
		);
	}

	/**
	 * Logic to determine which fields are displayed in the table columns.
	 *
	 * @since 1.0.0
	 * @param array $columns
	 * @return array
	 */
	public function get_columns_form_fields( $columns = array(), $display = 3 ) {

		$field_columns = wpforms()->form->get_meta( $this->form_id, 'field_columns' );

		if ( ! $field_columns && !empty( $this->form_data['fields'] ) ) {
			$x = 0;
			foreach ( $this->form_data['fields'] as $id => $field ) {
				$disallow = apply_filters( 'wpforms_entries_table_fields_disallow', array( 'divider', 'html', 'pagebreak', 'captcha' ) );
				if ( !in_array( $field['type'], $disallow ) && $x < $display ) {
					$name = !empty( $field['label'] ) ? wp_strip_all_tags( $field['label'] ) : __( 'Field', 'wpforms' );
					$columns['wpforms_field_' . $id] = $name;
					$x++;
				}
			}
		}

		return $columns;
	}

	/**
	 * Render the checkbox column.
	 *
	 * @since 1.0.0
	 * @param array
	 * @return string
	 */
	public function column_cb( $entry ) {

		return '<input type="checkbox" name="entry_id[]" value="' . absint( $entry->entry_id ) . '" />';
	}

	/**
	 * Show specific form fields.
	 *
	 * @since 1.0.0
	 * @param object $entry
	 * @param string $column_name
	 * @return string
	 */
	public function column_form_field( $entry, $column_name ) {

		$field_id     = str_replace( 'wpforms_field_', '', $column_name );
		$entry_fields = wpforms_decode( $entry->fields );

		if ( !empty( $entry_fields[$field_id] ) && !empty( $entry_fields[$field_id]['value'] ) ) {

			$value = $entry_fields[$field_id]['value'];

			// Limit to 5 lines
			$lines = explode( "\n", $value );
			$value = array_slice( $lines, 0, 4 );
			$value = implode( "\n", $value );

			if ( count( $lines ) > 5 ) {
				$value .= '&hellip;';
			} elseif ( strlen( $value ) > 75 ) {
				$value = substr( $value , 0, 75 ). '&hellip;';
			}

			$value = nl2br( wp_strip_all_tags( trim( $value ) ) );

			return apply_filters( 'wpforms_html_field_value', $value, $entry_fields[$field_id], $this->form_data, 'entry-table' );

		} else {
			return '-';
		}
	}

	/**
	 * Renders the columns.
	 *
	 * @since 1.0.0
	 * @param object $entry
	 * @param string $column_name
	 * @return string
	 */
	public function column_default( $entry, $column_name ) {

		$entry_meta =  json_decode( $entry->meta, true );

		switch( $column_name ) {

			case 'id':
				$value = absint( $entry->entry_id );
				break;

			case 'date':
				$value = date_i18n( get_option( 'date_format' ), strtotime( $entry->date ) + ( get_option( 'gmt_offset' ) * 3600 ) );
				break;

			case 'payment_status':
				if ( 'payment' == $entry->type ) {
					if ( !empty( $entry->status ) ) {
						$value = ucwords( sanitize_text_field( $entry->status ) );
					} else {
						$value = __( 'Unknown', 'wpforms' );
					}
				} else {
					$value = '-';
				}
				break;

			case 'payment_total':
				if ( 'payment' == $entry->type && isset( $entry_meta['payment_total'] ) ) {
					$amount = wpforms_sanitize_amount( $entry_meta['payment_total'], $entry_meta['payment_currency'] );
					$total  = wpforms_format_amount( $amount, true, $entry_meta['payment_currency'] );
					$value = $total;
				} else {
					$value = '-';
				}
				break;

			default:
				if ( false !== strpos( $column_name, 'wpforms_field_' )  ) {
					$value = $this->column_form_field( $entry, $column_name );
				} else {
					$value = '';
				}
		}

		return apply_filters( 'wpforms_entry_table_column_value', $value, $entry, $column_name );
	}

	/**
	 * Render the indicators column.
	 *
	 * @since 1.1.6
	 * @param array $entry
	 * @return string
	 */
	public function column_indicators( $entry ) {

		// Stars
		$star_action = !empty( $entry->starred ) ? 'unstar' : 'star';
		$star_title  = !empty( $entry->starred ) ? __( 'Unstar entry', 'wpforms' ) : __( 'Star entry', 'wpforms' );
		$star_icon   = '<a href="#" class="indicator-star ' . $star_action . '" data-id="' . absint( $entry->entry_id ) . '" title="' . esc_attr( $star_title ) . '"><span class="dashicons dashicons-star-filled"></span></a>';

		// Viewed
		$read_action = !empty( $entry->viewed ) ? 'unread' : 'read';
		$read_title  = !empty( $entry->viewed ) ? __( 'Mark entry unread', 'wpforms' ) : __( 'Mark entry read', 'wpforms' );
		$read_icon   = '<a href="#" class="indicator-read ' . $read_action . '" data-id="' . absint( $entry->entry_id ) . '" title="' . esc_attr( $read_title ) . '"><span class="dashicons dashicons-marker"></span></a>';

		return $star_icon . $read_icon;
	}

	/**
	 * Render the actions column.
	 *
	 * @since 1.0.0
	 * @param array $entry
	 * @return string
	 */
	public function column_actions( $entry ) {

		$actions = array();

		// View
		$actions[] = sprintf( '<a href="%s" title="%s" class="view">%s</a>',
			add_query_arg( array( 'view' => 'details', 'entry_id' => $entry->entry_id ), admin_url( 'admin.php?page=wpforms-entries' ) ),
			__( 'View Form Entry', 'wpforms' ),
			__( 'View', 'wpforms' )
		);

		// Delete
		$actions[] = sprintf( '<a href="%s" title="%s" class="delete">%s</a>',
			wp_nonce_url( add_query_arg( array( 'view' => 'list', 'action' => 'delete', 'form_id' => $this->form_id, 'entry_id' => $entry->entry_id ) ), 'bulk-entries' ),
			__( 'Delete Form Entry', 'wpforms' ),
			__( 'Delete', 'wpforms' )
		);

		return implode( ' <span class="sep">|</span> ', apply_filters( 'wpforms_entry_table_actions', $actions, $entry ) );
	}

	/**
	 * Define bulk actions available for our table listing
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_bulk_actions() {

		$actions = array(
			'read'   => __( 'Mark Read', 'wpforms' ),
			'unread' => __( 'Mark Unread', 'wpforms' ),
			'star'   => __( 'Star', 'wpforms' ),
			'unstar' => __( 'Unstar', 'wpforms' ),
			//'export' => __( 'Export', 'wpforms' ), @todo
			'null'   => __( '----------', 'wpforms' ),
			'delete' => __( 'Delete', 'wpforms' ),
		);
		return $actions;
	}

	/**
	 * Process the bulk actions
	 *
	 * @since 1.0.0
	 */
	public function process_bulk_actions() {

		if ( empty( $_REQUEST['_wpnonce'] ) ) {
			return;
		}

		if ( !current_user_can( apply_filters( 'wpforms_manage_cap', 'manage_options' ) ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-entries' ) && ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-entries-nonce' ) ) {
			return;
		}

		$ids = isset( $_GET['entry_id'] ) ? $_GET['entry_id'] : false;

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$ids    = array_map( 'absint', $ids );
		$action = ! empty( $_REQUEST['action'] ) ? $_REQUEST['action'] : false;

		if ( empty( $ids ) || empty( $action ) ) {
			return;
		}

		// Mark as read
		if ( 'read' === $this->current_action() ) {

			foreach ( $ids as $id ) {
				wpforms()->entry->update( $id, array( 'viewed' => '1' ) );
			}
			printf( '<div class="updated"><p>%s</p></div>', _n( 'Entry marked as read.', 'Entries marked as read.', count( $ids ), 'wpforms' ) );
		}

		// Mark as unread
		if ( 'unread' === $this->current_action() ) {

			foreach ( $ids as $id ) {
				wpforms()->entry->update( $id, array( 'viewed' => '0' ) );
			}
			printf( '<div class="updated"><p>%s</p></div>', _n( 'Entry marked as unread.', 'Entries marked as unread.', count( $ids ), 'wpforms' ) );
		}

		// Star entry
		if ( 'star' === $this->current_action() ) {

			foreach ( $ids as $id ) {
				wpforms()->entry->update( $id, array( 'starred' => '1' ) );
			}
			printf( '<div class="updated"><p>%s</p></div>', _n( 'Entry starred.', 'Entries starred.', count( $ids ), 'wpforms' ) );
		}

		// Star entry
		if ( 'unstar' === $this->current_action() ) {

			foreach ( $ids as $id ) {
				wpforms()->entry->update( $id, array( 'starred' => '0' ) );
			}
			printf( '<div class="updated"><p>%s</p></div>', _n( 'Entry unstarred.', 'Entries unstarred.', count( $ids ), 'wpforms' ) );
		}

		// Delete entries
		if ( 'delete' === $this->current_action() ) {

			foreach ( $ids as $id ) {
				wpforms()->entry->delete( $id );
			}
			printf( '<div class="updated"><p>%s</p></div>', _n( 'Entry successfully deleted.', 'Entries successfully deleted.', count( $ids ), 'wpforms' ) );
		}

		// Update counts
		//$this->get_counts();
	}

	/**
	 * Message to be displayed when there are no entries.
	 *
	 * @since 1.0.0
	 */
	public function no_items() {

		_e( 'Whoops, it appears you do not have any form entries yet.', 'wpforms' );
	}

	/**
	 * Fetch and setup the final data for the table
	 *
	 * @since 1.0.0
	 */
	public function prepare_items() {

		// Process bulk actions if found
		$this->process_bulk_actions();

		// Retrieve count
		$this->get_counts();

		// Setup the columns
		$columns = $this->get_columns();

		// Hidden columns (none)
		$hidden = array();

		// Define which columns can be sorted
		$sortable = $this->get_sortable_columns();

		// Set column headers
		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Get entries
		$total_items = $this->counts['total'];
		$page        = $this->get_pagenum();
		$order       = isset( $_GET['order'] ) ? $_GET['order'] : 'DESC';
		$orderby     = isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'entry_id';
		$per_page    = $this->get_items_per_page( 'wpforms_entries_per_page', $this->per_page );
		$data_args   = array(
			'form_id' => $this->form_id,
			'number'  => $per_page,
			'offset'  => $per_page * ( $page - 1 ),
			'order'   => $order,
			'orderby' => $orderby,
		);

		if ( !empty( $_GET['type'] ) && 'starred' ==  $_GET['type'] ) {
			$data_args['starred'] = '1';
			$total_items = $this->counts['starred'];
		}
		if ( !empty( $_GET['type'] ) && 'unread' ==  $_GET['type'] ) {
			$data_args['viewed'] = '0';
			$total_items = $this->counts['unread'];
		}

		$data = wpforms()->entry->get_entries( $data_args );

		// Maybe sort by payment total
		if ( 'payment_total' == $orderby ) {
			usort( $data, array( $this, 'payment_total_sort' ) );
			if ( 'DESC' == strtoupper( $order ) ) {
				$data = array_reverse( $data );
			}
		}

		// Giddy up
		$this->items = $data;

		// Finalize pagination
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}

	/**
	 * Sort by payment total.
	 *
	 * @since 1.2.6
	 * @param object $a
	 * @param object $b
	 * @return int
	 */
	public function payment_total_sort( $a, $b ) {

		$a_meta  = json_decode( $a->meta, true );
		$a_total = !empty( $a_meta['payment_total'] ) ? wpforms_sanitize_amount( $a_meta['payment_total'] ) : 0;
		$b_meta  = json_decode( $b->meta, true );
		$b_total = !empty( $b_meta['payment_total'] ) ? wpforms_sanitize_amount( $b_meta['payment_total'] ) : 0;

		if ( $a_total == $b_total ) {
			return 0;
		}
		return ( $a_total < $b_total ) ? -1 : 1;
	}
}