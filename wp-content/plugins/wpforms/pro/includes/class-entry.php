<?php
/**
 * Entry DB class
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Entry_Handler extends WPForms_DB {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'wpforms_entries';
		$this->primary_key = 'entry_id';
	}

	/**
	 * Get table columns.
	 *
	 * @since 1.0.0
	 */
	public function get_columns() {

		return array(
			'entry_id'      => '%d',
			'form_id'       => '%d',
			'post_id'       => '%d',
			'user_id'       => '%d',
			'status'        => '%s',
			'type'          => '%s',
			'viewed'        => '%d',
			'starred'       => '%d',
			'fields'        => '%s',
			'meta'          => '%s',
			'date'          => '%s',
			'date_modified' => '%s',
			'ip_address'    => '%s',
			'user_agent'    => '%s',
		);
	}

	/**
	 * Default column values.
	 *
	 * @since 1.0.0
	 */
	public function get_column_defaults() {

		return array(
			'date' => date( 'Y-m-d H:i:s' ),
		);
	}

	/**
	 * Deletes an entry from the database, also removes entry meta.
	 *
	 * Please note: successfully deleting a record flushes the cache.
	 *
	 * @since 1.1.6
	 * @param int|string $row_id Row ID.
	 * @return bool False if the record could not be deleted, true otherwise.
	 */
	public function delete( $row_id = 0 ) {

		parent::delete( $row_id );
		wpforms()->entry_meta->delete_by( 'entry_id', $row_id );
	}

	/**
	 * Get next entry.
	 * 
	 * @since 1.1.5
	 * @param int $row_id
	 * @param int $form_id
	 * @return mixed object or null
	 */
	public function get_next( $row_id, $form_id ) {

		global $wpdb;

		if ( empty( $row_id ) || empty( $form_id ) ) {
			return false;
		}

		$next = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE `form_id` = %d AND {$this->primary_key} > %d ORDER BY {$this->primary_key} LIMIT 1;",
				absint( $form_id ),
				absint( $row_id )
			)
		);

		return $next;
	}

	/**
	 * Get previous entry.
	 * 
	 * @since 1.1.5
	 * @param int $row_id
	 * @param int $form_id
	 * @return mixed object or null
	 */
	public function get_prev( $row_id, $form_id ) {

		global $wpdb;

		if ( empty( $row_id ) || empty( $form_id ) ) {
			return false;
		}

		$prev = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->table_name} WHERE `form_id` = %d AND {$this->primary_key} < %d ORDER BY {$this->primary_key} DESC LIMIT 1;",
				absint( $form_id ),
				absint( $row_id )
			)
		);

		return $prev;
	}

	/**
	 * Mark all entries read for a form.
	 * 
	 * @since 1.1.6
	 * @param int $form_id
	 * @return bool
	 */
	public function mark_all_read( $form_id = 0 ) {

		global $wpdb;

		if ( empty( $form_id ) ) {
			return false;
		}

		if ( false === $wpdb->query( $wpdb->prepare( "UPDATE $this->table_name SET `viewed` = '1' WHERE `form_id` = %d", $form_id ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get previous entries count.
	 * 
	 * @since 1.1.5
	 * @param int $row_id
	 * @param int $form_id
	 * @return mixed object or null
	 */
	public function get_prev_count( $row_id, $form_id ) {

		global $wpdb;

		if ( empty( $row_id ) || empty( $form_id ) ) {
			return false;
		}

		$prev_count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT({$this->primary_key}) FROM {$this->table_name} WHERE `form_id` = %d AND {$this->primary_key} < %d ORDER BY {$this->primary_key};",
				absint( $form_id ),
				absint( $row_id )
			)
		);

		return absint( $prev_count );
	}

	/**
	 * Get entries from the database.
	 *
	 * @since 1.0.0
	 * @param array $args
	 * @param bool $count
	 */
	public function get_entries( $args = array(), $count = false ) {

		global $wpdb;

		$defaults = array(
			'number'        => 30,
			'offset'        => 0,
			'form_id'       => 0,
			'entry_id'      => 0,
			'post_id'       => '',
			'user_id'       => '',
			'status'        => '',
			'type'          => '',
			'viewed'        => '',
			'starred'       => '',
			//'date'          => '', @todo
			//'date_modified' => '', @todo
			'ip_address'    => '',
			'orderby'       => 'entry_id',
			'order'         => 'DESC',
			'search'        => false
		);

		$args  = wp_parse_args( $args, $defaults );

		if ( $args['number'] < 1 ) {
			$args['number'] = 999999999999;
		}

		$where = '';

		// Allowed int arg items
		$keys = array( 'entry_id', 'form_id', 'post_id', 'user_id' );
		foreach ( $keys as $key ) {

			if ( ! empty( $args[$key] ) ) {
				if ( is_array( $args[$key] ) ) {
					$ids = implode( ',', array_map( 'intval', $args[$key] ) );
				} else {
					$ids = intval( $args[$key] );
				}
				$where .= empty( $where ) ? "WHERE" : "AND";
				$where .= " `{$key}` IN( {$ids} ) ";
			}
		}

		// Allowed string arg items
		$keys = array( 'status', 'type', 'viewed', 'starred' );
		foreach( $keys as $key ) {

			if ( $args[$key] != '' ) {
				$where .= empty( $where ) ? "WHERE" : "AND";
				$where .= " `{$key}` = '" . esc_sql( $args[$key] ) ."' ";
			}
		}

		// Orderby
		$orderby = ! array_key_exists( $args['orderby'], $this->get_columns() ) ? $this->primary_key : $args['orderby'];

		// Order
		if ( 'ASC' === strtoupper( $args['order'] ) ) {
			$order = 'ASC';
		} else {
			$order = 'DESC';
		}

		// Check for primed cache
		if ( true === $count ) {
			$cache_key = md5( 'wpforms_entries_count' . serialize( $args ) );
		} else {
			$cache_key = md5( 'wpforms_entries_' . serialize( $args ) );
		}
		$results = wp_cache_get( $cache_key, 'wpforms_entries' );

		if ( false === $results ) {

			if ( true === $count ) {

				$results = absint( $wpdb->get_var( "SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};" ) );

			} else {

				$results = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM {$this->table_name} {$where} ORDER BY {$orderby} {$order} LIMIT %d, %d;",
						absint( $args['offset'] ),
						absint( $args['number'] )
					)
				);
			}

			wp_cache_set( $cache_key, $results, 'wpforms_entries', 3600 );
		}

		return $results;
	}

	/** 
	 * Create custom entry database table.
	 *
	 * @since 1.0.0
	 */
	public function create_table() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = '';

		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate .= "DEFAULT CHARACTER SET {$wpdb->charset}";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE {$wpdb->collate}";
		}

		$sql = "CREATE TABLE {$this->table_name} (
			entry_id bigint(20) NOT NULL AUTO_INCREMENT,
			form_id bigint(20) NOT NULL,
			post_id bigint(20) NOT NULL,
			user_id bigint(20) NOT NULL,
			status varchar(30) NOT NULL,
			type varchar(30) NOT NULL,
			viewed tinyint(1) DEFAULT 0,
			starred tinyint(1) DEFAULT 0,
			fields longtext NOT NULL,
			meta longtext NOT NULL,
			date datetime NOT NULL,
			date_modified datetime NOT NULL,
			ip_address varchar(128) NOT NULL,
			user_agent varchar(256) NOT NULL,
			PRIMARY KEY  (entry_id),
			KEY form_id (form_id)
		) {$charset_collate};";

		$db = dbDelta( $sql );	
	}
}