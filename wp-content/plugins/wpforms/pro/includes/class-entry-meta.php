<?php
/**
 * Entry meta DB class.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.1.6
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Entry_Meta_Handler extends WPForms_DB {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.1.6
	 */
	public function __construct() {

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'wpforms_entry_meta';
		$this->primary_key = 'id';
	}

	/**
	 * Get table columns.
	 *
	 * @since 1.1.6
	 */
	public function get_columns() {

		return array(
			'id'       => '%d',
			'entry_id' => '%d',
			'form_id'  => '%d',
			'user_id'  => '%d',
			'type'     => '%s',
			'status'   => '%s',
			'data'     => '%s',
			'date'     => '%s',
		);
	}

	/**
	 * Default column values.
	 *
	 * @since 1.1.6
	 */
	public function get_column_defaults() {

		return array(
			'date' => date( 'Y-m-d H:i:s' ),
		);
	}

	/**
	 * Get entry meta rows from the database.
	 *
	 * @since 1.1.6
	 * @param array $args
	 * @param bool $count
	 */
	public function get_meta( $args = array(), $count = false ) {

		global $wpdb;

		$defaults = array(
			'number'        => 30,
			'offset'        => 0,
			'id'            => 0,
			'entry_id'      => 0,
			'form_id'       => 0,
			'user_id'       => '',
			'status'        => '',
			'type'          => '',
			//'date'          => '', @todo
			'orderby'       => 'id',
			'order'         => 'DESC',
		);

		$args  = wp_parse_args( $args, $defaults );

		if ( $args['number'] < 1 ) {
			$args['number'] = 999999999999;
		}

		$where = '';

		// Allowed int arg items
		$keys = array( 'id', 'entry_id', 'form_id', 'user_id' );
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
		$keys = array( 'status', 'type' );
		foreach( $keys as $key ) {

			if ( !empty( $args[$key] ) ) {
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
			$cache_key = md5( 'wpforms_entry_meta_count' . serialize( $args ) );
		} else {
			$cache_key = md5( 'wpforms_entry_meta_' . serialize( $args ) );
		}
		$results = wp_cache_get( $cache_key, 'wpforms_entry_meta' );

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

			wp_cache_set( $cache_key, $results, 'wpforms_entry_meta', 3600 );
		}

		return $results;
	}

	/** 
	 * Create custom entry meta database table.
	 *
	 * @since 1.1.6
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
			id bigint(20) NOT NULL AUTO_INCREMENT,
			entry_id bigint(20) NOT NULL,
			form_id bigint(20) NOT NULL,
			user_id bigint(20) NOT NULL,
			status varchar(30) NOT NULL,
			type varchar(30) NOT NULL,
			data longtext NOT NULL,
			date datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY entry_id (entry_id)
		) {$charset_collate};";

		$db = dbDelta( $sql );	
	}
}