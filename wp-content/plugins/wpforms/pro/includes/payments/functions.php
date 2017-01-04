<?php
/**
 * Payment related functions.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */

/**
 * Get supported currencies.
 *
 * @since 1.2.4
 * @return array
 */
function wpforms_get_currencies() {

	$currencies = array(
		'USD' => array( 'name' => __( 'U.S. Dollar', 'wpforms' ),        'symbol' => '&#36;',        'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'GBP' => array( 'name' => __( 'Pound Sterling', 'wpforms' ),     'symbol' => '&pound;',      'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'EUR' => array( 'name' => __( 'Euro', 'wpforms' ),               'symbol' => '&euro;',       'symbol_pos' => 'right',  'thousands_separator' => '.',  'decimal_separator' => ',', 'decimals' => 2 ),
		'AUD' => array( 'name' => __( 'Australian Dollar', 'wpforms' ),  'symbol' => '&#36;',        'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'BRL' => array( 'name' => __( 'Brazilian Real', 'wpforms' ),     'symbol' => 'R$',           'symbol_pos' => 'left',   'thousands_separator' => '.',  'decimal_separator' => ',', 'decimals' => 2 ),
		'CAD' => array( 'name' => __( 'Canadian Dollar', 'wpforms' ),    'symbol' => '&#36;',        'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'CZK' => array( 'name' => __( 'Czech Koruna', 'wpforms' ),       'symbol' => '&#75;&#269;',  'symbol_pos' => 'right',  'thousands_separator' => '.',  'decimal_separator' => ',', 'decimals' => 2 ),
		'DKK' => array( 'name' => __( 'Danish Krone', 'wpforms' ),       'symbol' => 'kr.',          'symbol_pos' => 'right',  'thousands_separator' => '.',  'decimal_separator' => ',', 'decimals' => 2 ),
		'HKD' => array( 'name' => __( 'Hong Kong Dollar', 'wpforms' ),   'symbol' => '&#36;',        'symbol_pos' => 'right',  'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'HUF' => array( 'name' => __( 'Hungarian Forint', 'wpforms' ),   'symbol' => 'Ft',           'symbol_pos' => 'right',  'thousands_separator' => '.',  'decimal_separator' => ',', 'decimals' => 2 ),
		'ILS' => array( 'name' => __( 'Israeli New Sheqel', 'wpforms' ), 'symbol' => '&#8362;',      'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		//'JPY' => array( 'name' => __( 'Japanese Yen', 'wpforms' ),       'symbol' => '&yen;',        'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '',  'decimals' => 0 ),
		'MYR' => array( 'name' => __( 'Malaysian Ringgit', 'wpforms' ),  'symbol' => '&#82;&#77;',   'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'MXN' => array( 'name' => __( 'Mexican Peso', 'wpforms' ),       'symbol' => '&#36;',        'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'NOK' => array( 'name' => __( 'Norwegian Krone', 'wpforms' ),    'symbol' => 'Kr',           'symbol_pos' => 'left',   'thousands_separator' => '.',  'decimal_separator' => ',', 'decimals' => 2 ),
		'NZD' => array( 'name' => __( 'New Zealand Dollar', 'wpforms' ), 'symbol' => '&#36;',        'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'PHP' => array( 'name' => __( 'Philippine Peso', 'wpforms' ),    'symbol' => 'Php',          'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'PLN' => array( 'name' => __( 'Polish Zloty', 'wpforms' ),       'symbol' => '&#122;&#322;', 'symbol_pos' => 'left',   'thousands_separator' => '.',  'decimal_separator' => ',', 'decimals' => 2 ),
		'RUB' => array( 'name' => __( 'Russian Ruble', 'wpforms' ),      'symbol' => 'pyб',          'symbol_pos' => 'right',  'thousands_separator' => ' ',  'decimal_separator' => '.', 'decimals' => 2 ),
		'SGD' => array( 'name' => __( 'Singapore Dollar', 'wpforms' ),   'symbol' => '&#36;',        'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'ZAR' => array( 'name' => __( 'South African Rand', 'wpforms' ), 'symbol' => 'R',            'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'SEK' => array( 'name' => __( 'Swedish Krona', 'wpforms' ),      'symbol' => 'Kr',           'symbol_pos' => 'right',  'thousands_separator' => '.',  'decimal_separator' => ',', 'decimals' => 2 ),
		'CHF' => array( 'name' => __( 'Swiss Franc', 'wpforms' ),        'symbol' => 'CHF',          'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'TWD' => array( 'name' => __( 'Taiwan New Dollar', 'wpforms' ),  'symbol' => '&#36;',        'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
		'THB' => array( 'name' => __( 'Thai Baht', 'wpforms' ),          'symbol' => '&#3647;',      'symbol_pos' => 'left',   'thousands_separator' => ',',  'decimal_separator' => '.', 'decimals' => 2 ),
	);

	return apply_filters( 'wpforms_currencies', $currencies );
}

/**
 * Sanitize Amount.
 *
 * Returns a sanitized amount by stripping out thousands separators.
 *
 * @since 1.2.6
 * @link https://github.com/easydigitaldownloads/easy-digital-downloads/blob/master/includes/formatting.php#L24
 * @param string $amount
 * @param string $currency
 * @return string $amount
 */
function wpforms_sanitize_amount( $amount, $currency = '' ) {

	if ( empty( $currency ) ) {
		$currency  = wpforms_setting( 'currency', 'USD' );
	}
	$currency      = strtoupper( $currency );
	$currencies    = wpforms_get_currencies();
	$thousands_sep = $currencies[ $currency ]['thousands_separator'];
	$decimal_sep   = $currencies[ $currency ]['decimal_separator'];
	$is_negative   = false;

	// Sanitize the amount
	if ( $decimal_sep == ',' && false !== ( $found = strpos( $amount, $decimal_sep ) ) ) {
		if ( ( $thousands_sep == '.' || $thousands_sep == ' ' ) && false !== ( $found = strpos( $amount, $thousands_sep ) ) ) {
			$amount = str_replace( $thousands_sep, '', $amount );
		} elseif( empty( $thousands_sep ) && false !== ( $found = strpos( $amount, '.' ) ) ) {
			$amount = str_replace( '.', '', $amount );
		}
		$amount = str_replace( $decimal_sep, '.', $amount );
	} elseif( $thousands_sep == ',' && false !== ( $found = strpos( $amount, $thousands_sep ) ) ) {
		$amount = str_replace( $thousands_sep, '', $amount );
	}

	if( $amount < 0 ) {
		$is_negative = true;
	}

	$amount   = preg_replace( '/[^0-9\.]/', '', $amount );
	$decimals = apply_filters( 'wpforms_sanitize_amount_decimals', 2, $amount );
	$amount   = number_format( (double) $amount, $decimals, '.', '' );

	if( $is_negative ) {
		$amount *= -1;
	}

	return $amount;
}

/**
 * Returns a nicely formatted amount.
 *
 * @since 1.2.6
 * @link https://github.com/easydigitaldownloads/easy-digital-downloads/blob/master/includes/formatting.php#L83
 * @param string $amount
 * @param boolean $symbol
 * @param string $currency
 * @return string $amount Newly formatted amount or Price Not Available
 */
function wpforms_format_amount( $amount, $symbol = false, $currency = '' ) {

	if ( empty( $currency ) ) {
		$currency  = wpforms_setting( 'currency', 'USD' );
	}
	$currency      = strtoupper( $currency );
	$currencies    = wpforms_get_currencies();
	$thousands_sep = $currencies[ $currency ]['thousands_separator'];
	$decimal_sep   = $currencies[ $currency ]['decimal_separator'];

	// Format the amount
	if ( $decimal_sep == ',' && false !== ( $sep_found = strpos( $amount, $decimal_sep ) ) ) {
		$whole = substr( $amount, 0, $sep_found );
		$part = substr( $amount, $sep_found + 1, ( strlen( $amount ) - 1 ) );
		$amount = $whole . '.' . $part;
	}

	// Strip , from the amount (if set as the thousands separator)
	if ( $thousands_sep == ',' && false !== ( $found = strpos( $amount, $thousands_sep ) ) ) {
		$amount = floatval( str_replace( ',', '', $amount ) );
	}

	if ( empty( $amount ) ) {
		$amount = 0;
	}

	$decimals = apply_filters( 'wpforms_sanitize_amount_decimals', 2, $amount );
	$number   = number_format( $amount, $decimals, $decimal_sep, $thousands_sep );

	if ( $symbol ) {
		$symbol_padding = apply_filters( 'wpforms_currency_symbol_padding', ' ' );
		if ( 'right' ==  $currencies[ $currency ]['symbol_pos'] ) {
			$number = $number . $symbol_padding . $currencies[ $currency ]['symbol'];
		} else {
			$number = $currencies[ $currency ]['symbol'] . $symbol_padding . $number;
		}
	}

	return $number;
}

/**
 * Return recognized payment field types.
 *
 * @since 1.0.0
 * @return array
 */
function wpforms_payment_fields() {

	$fields = array( 'payment-single', 'payment-multiple', 'payment-select' );
	return apply_filters( 'wpforms_payment_fields', $fields );
}

/**
 * Check if form or entry contains payment
 *
 * @since 1.0.0
 * @param string $form
 * @param array $data
 * @return bool
 */
function wpforms_has_payment( $type = 'entry', $data = '' ) {

	$payment        = false;
	$payment_fields = wpforms_payment_fields();

	if ( !empty( $data['fields'] ) ) {
		$data = $data['fields'];
	}

	if ( empty( $data ) ) {
		return false;
	}

	foreach( $data as $field ) {
		if ( in_array( $field['type'], $payment_fields ) ) {

			// For entries, only return true if the payment field has an amount
			if ( $type == 'form' || ( $type == 'entry' && !empty( $field['amount'] ) && $field['amount'] != wpforms_sanitize_amount( '0' ) ) ) {
				$payment = true;
				break;
			}
		}
	}

	return $payment;
}

/**
 * Get payment total amount from entry.
 *
 * @since 1.0.0
 * @return float
 */
function wpforms_get_total_payment( $fields = '' ) {

	$fields = wpforms_get_payment_items( $fields );
	$total  = 0;

	if ( empty( $fields ) ) {
		return false;
	}

	foreach ( $fields as $field ) {
		if ( !empty( $field['amount'] ) ) {
			$amount = wpforms_sanitize_amount( $field['amount'] );
			$total = $total+$amount;
		}
	}

	return wpforms_sanitize_amount( $total );
}

/**
 * Get payment fields in an entry.
 *
 * @since 1.0.0
 * @array $fields
 * @return mixed array or false
 */
function wpforms_get_payment_items( $fields = '' ) {

	if ( empty( $fields ) ) {
		return false;
	}

	$payment_fields = wpforms_payment_fields();

	foreach ( $fields as $id => $field ) {
		if ( !in_array( $field['type'], $payment_fields ) || empty( $field['amount'] ) || $field['amount'] == wpforms_sanitize_amount( '0' ) ) {
			// Remove all non-payment fields as well as payment fields with no amount
			unset( $fields[$id] );
		}
	}

	return $fields;
}

/**
 * Scrub credit card number except for the last 4 digits.
 *
 * @since 1.0.9
 * @param string $number
 * @return string
 */
function wpforms_scrub_creditcard( $number = '' ) {

	$cc_length = strlen( $number );
	$cc_last   = $substr( $number, $cc_length-4, 4 );
	$cc        = str_pad( $cc_four, $cc_length, '*', STR_PAD_LEFT );
	return $cc;
}