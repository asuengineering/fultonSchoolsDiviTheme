<?php
/**
 * Exports entries to CSV.
 *
 * Inspired by Easy Digital Download's EDD_Export class.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.1.5
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Entries_Export {

	/**
	 * Entries to export.
	 *
	 * Accepted values:
	 * "all"   - all entries are exported
	 * (int)   - ID of specific entry to export
	 * (array) - an array of IDs to export
	 *
	 * @since 1.1.5
	 * @var string
	 */
	public $entry_type = 'all';

	/**
	 * Entry object, when exporting a single entry/
	 *
	 * @since 1.1.5
	 * @var object
	 */
	public $entry;

	/**
	 * Specific fields to export.
	 *
	 * Default is blank which exports all fields.
	 * Also accepts array of field IDs.
	 *
	 * @since 1.1.5
	 * @var mixed
	 */
	public $fields = '';

	/**
	 * Form ID.
	 *
	 * @since 1.1.5
	 * @var int
	 */
	public $form_id;

	/**
	 * Form data.
	 *
	 * @since 1.1.5
	 * @var int
	 */
	public $form_data;

	/**
	 * Field types that are allowed in entry exports.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function allowed_fields() {

		$fields = apply_filters( 'wpforms_export_fields_allowed',
			array(
				'text',
				'textarea',
				'select',
				'radio',
				'checkbox',
				'email',
				'address',
				'url',
				'name',
				'hidden',
				'date-time',
				'phone',
				'number',
				'file-upload',
				'payment-single',
				'payment-multiple',
				'payment-select',
				'payment-total',
			)
		);
		return $fields;
	}

	/**
	 * Are we exporting a single entry or multipe.
	 *
	 * @since 1.1.5
	 * @return boolean
	 */
	public function is_single_entry() {

		if ( 'all' == $this->entry_type || is_array( $this->entry_type ) ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Set the export headers.
	 *
	 * @since 1.1.5
	 */
	public function headers() {

		$this->form_id = absint( $_GET['form_id'] );

		ignore_user_abort( true );

		if ( ! in_array( 'set_time_limit', explode( ',', ini_get( 'disable_functions' ) ) ) )
			set_time_limit( 0 );

		if ( ! $this->is_single_entry() ) {
			$file_name = 'wpforms-' . sanitize_file_name( get_the_title( $this->form_id ) ) . '-' . date( 'm-d-Y' ) . '.csv';
		} else {
			$file_name = 'wpforms-' . sanitize_file_name( get_the_title( $this->form_id ) ) . '-entry' . absint( $this->entry_type ) . '-' . date( 'm-d-Y' ) . '.csv';
		}

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $file_name );
		header( "Expires: 0" );
	}

	/**
	 * Set the CSV columns.
	 *
	 * @since 1.1.5
	 * @return array $cols All the columns
	 */
	public function csv_cols() {

		$cols = array();

		// If we are exporting a single entry we do not need to reference the
		// form and can export by looking at the field contained within the
		// entry object. For multiple entry export we get the fields from the
		// form.
		if ( $this->is_single_entry() ) {
			$this->entry     = wpforms()->entry->get( $this->entry_type );
			$this->fields    = wpforms_decode( $this->entry->fields );
		} else {
			$this->form_data = wpforms()->form->get( $this->form_id, array( 'content_only' => true ) );
			$this->fields    = $this->form_data['fields'];
		}

		// Get field types now allowed (eg exclude page break, divider, etc)
		$allowed = $this->allowed_fields();

		// Add whitelisted fields to export columns
		foreach( $this->fields as $id => $field ) {
			if ( in_array( $field['type'], $allowed ) ) {
				if ( $this->is_single_entry() ) {
					$cols[$field['id']] = sanitize_text_field( $field['name'] );
				} else {
					$cols[$field['id']] = sanitize_text_field( $field['label'] );
				}
			}
		}

		$cols['date']     = __( 'Date', 'wpforms' );
		$cols['date_gmt'] = __( 'Date GMT', 'wpforms' );
		$cols['entry_id'] = __( 'ID', 'wpforms' );

		return $cols;
	}

	/**
	 * Retrieve the CSV columns.
	 *
	 * @since 1.1.5
	 * @return array $cols Array of the columns
	 */
	public function get_csv_cols() {

		$cols = $this->csv_cols();
		return $cols;
	}

	/**
	 * Output the CSV columns.
	 *
	 * @since 1.1.5
	 */
	public function csv_cols_out() {

		$cols = $this->get_csv_cols();
		$i = 1;
		foreach( $cols as $col_id => $column ) {
			echo '"' . addslashes( $column ) . '"';
			echo $i == count( $cols ) ? '' : ',';
			$i++;
		}
		echo "\r\n";
	}

	/**
	 * Get the data being exported.
	 *
	 * @since 1.1.5
	 * @return array $data Data for Export
	 */
	public function get_data() {

		$allowed = $this->allowed_fields();
		$data    = array();

		if ( $this->is_single_entry() ) {

			// For single entry exports we have the needed fields already
			// and no more queries are necessary
			foreach( $this->fields as $id => $field ) {
				if ( in_array( $field['type'], $allowed ) ) {
					$data[1][$field['id']] = $field['value'];
				}
			}
			$date_format         = sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) );
			$data[1]['date']     = date( $date_format, strtotime( $this->entry->date ) + ( get_option( 'gmt_offset' ) * 3600 ) );
			$data[1]['date_gmt'] = date( $date_format, strtotime( $this->entry->date ) );
			$data[1]['entry_id'] = absint( $this->entry->entry_id );

		} else {

			// All or multiple entry export
			$args = array(
				'number'   => -1,
				//'entry_id' => is_array( $this->entry_type ) ? $this->entry_type : '', @todo
				'form_id' => $this->form_id,
			);
			$entries     = wpforms()->entry->get_entries( $args );
			$form_fields = $this->form_data['fields'];

			foreach( $entries as $entry ) {

				$fields = wpforms_decode( $entry->fields );

				foreach( $form_fields as $form_field ) {
					if ( in_array( $form_field['type'], $allowed ) ) {
						$data[$entry->entry_id][$form_field['id']] = $fields[$form_field['id']]['value'];
					}
				}
				$date_format                        = sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) );
				$data[$entry->entry_id]['date']     = date( $date_format, strtotime( $entry->date ) + ( get_option( 'gmt_offset' ) * 3600 )  );
				$data[$entry->entry_id]['date_gmt'] = date( $date_format, strtotime( $entry->date ) );
				$data[$entry->entry_id]['entry_id'] = absint( $entry->entry_id );
			}
		}

		$data = apply_filters( 'wpforms_export_get_data', $data, $this->entry_type );

		return $data;
	}

	/**
	 * Output the CSV rows.
	 *
	 * @since 1.1.5
	 */
	public function csv_rows_out() {

		$data = $this->get_data();
		$cols = $this->get_csv_cols();

		// Output each row
		foreach ( $data as $row ) {
			$i = 1;
			foreach ( $row as $col_id => $column ) {
				// Make sure the column is valid
				if ( array_key_exists( $col_id, $cols ) ) {
					$data = str_replace("\n", "\r\n", trim( $column ) );
					echo '"' . addslashes( $data ) . '"';
					echo $i == count( $cols ) ? '' : ',';
					$i++;
				}
			}
			echo "\r\n";
		}
	}

	/**
	 * Perform the export.
	 *
	 * @since 1.1.5
	 */
	public function export() {

		if ( !current_user_can( apply_filters( 'wpforms_manage_cap', 'manage_options' ) ) ) {
			wp_die( __( 'You do not have permission to export entries.', 'wpforms' ), __( 'Error', 'wpforms' ), array( 'response' => 403 ) );
		}

		// Set headers
		$this->headers();

		// Output CSV columns (headers)
		$this->csv_cols_out();

		// Output CSV rows
		$this->csv_rows_out();

		die();
	}
}