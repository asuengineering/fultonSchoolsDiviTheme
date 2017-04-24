<?php
/* register customiser settings */
function asu_customize_register($wp_customize) {

    /* Extra Header section*/
    $wp_customize->add_section('et_divi_header_extra', array(
        'title' => esc_html__('Header School Names', 'Divi'),
        'panel' => 'et_divi_header_panel',
    ));


    /* School Name settings */
    $wp_customize->add_setting('et_divi[school_name]', array(
        'default' => 'School Name',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('et_divi[school_name]', array(
        'label' => __('Enter School Name', 'Divi'),
        'section' => 'et_divi_header_extra',
        'type' => 'text',
    ));

    $wp_customize->add_setting('et_divi[school_uri]', array(
        'default' => 'http://www.asu.edu/',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('et_divi[school_uri]', array(
        'label' => __('School URL (including http)', 'Divi'),
        'section' => 'et_divi_header_extra',
        'type' => 'text',
    ));

    /* Secondary School Name settings */
    $wp_customize->add_setting('et_divi[secondary_school_name]', array(
        'default' => 'Secondary School Name',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('et_divi[secondary_school_name]', array(
        'label' => __('Enter Secondary School Name', 'Divi'),
        'section' => 'et_divi_header_extra',
        'type' => 'text',
    ));

    $wp_customize->add_setting('et_divi[secondary_school_uri]', array(
        'default' => 'http://www.asu.edu/',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control('et_divi[secondary_school_uri]', array(
        'label' => __('Secondary School URL (including http)', 'Divi'),
        'section' => 'et_divi_header_extra',
        'type' => 'text',
    ));

    $wp_customize->add_setting('et_divi[school_font_size]', array(
        'default' => '21',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new ET_Divi_Range_Option ($wp_customize, 'et_divi[school_font_size]', array(
        'label' => esc_html__('School Name Header Text Size', 'Divi'),
        'section' => 'et_divi_header_extra',
        'type' => 'range',
        'input_attrs' => array(
            'min' => 21,
            'max' => 24,
            'step' => 1
        ),
    )));
}

add_action('customize_register', 'asu_customize_register', 11);