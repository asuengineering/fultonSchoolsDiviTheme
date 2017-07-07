<?php
define('ASU_CHILD_THEME_DIRECTORY', get_stylesheet_directory());

/* include extended child theme modules */
include(ASU_CHILD_THEME_DIRECTORY . '/includes/modules/asu-child-et-builder-modules.php');
/* include customiser settings */
include(ASU_CHILD_THEME_DIRECTORY . '/includes/asu-customiser-settings.php');
/* include admin forms */
include(ASU_CHILD_THEME_DIRECTORY . '/includes/asu-admin-social-media.php');
include(ASU_CHILD_THEME_DIRECTORY . '/includes/asu-admin-sidebar-widget.php');
/* include shortcodes */
include(ASU_CHILD_THEME_DIRECTORY . '/includes/asu-child-shortcodes.php');
/* include widgets */
include(ASU_CHILD_THEME_DIRECTORY . '/widgets/asu-widget-sidebar.php');
include(ASU_CHILD_THEME_DIRECTORY . '/widgets/asu-widget-footer.php');
include(ASU_CHILD_THEME_DIRECTORY . '/widgets/asufse-endorsed-footer-widget.php');
include(ASU_CHILD_THEME_DIRECTORY . '/widgets/asufse-socialicons-footer-widget.php');

/* --------------------- 
Register, enqueue scripts, execute action */
function fsdt_enqueue_scripts() {

    wp_deregister_style( 'font-awesome' );

    wp_register_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0' );
    wp_register_style( 'parent-style', get_template_directory_uri() . '/style.css');
    wp_register_style( 'asu-header-css', get_stylesheet_directory_uri() . '/assets/asu-header/css/asu-nav.css', array(), false, 'all' );
    wp_register_style( 'roboto-font', 'https://fonts.googleapis.com/css?family=Roboto:400,700,400italic,700italic', array(), '1' );
    
    wp_register_script( 'functions', get_stylesheet_directory_uri() . '/js/functions.js', array(), false, true );
    wp_register_script( 'fse-functions', get_stylesheet_directory_uri() . '/js/fse-functions.js', array(), false, true );
    wp_register_script( 'asu-header', get_stylesheet_directory_uri() . '/assets/asu-header/js/asu-header.min.js', array() , '4.0', true );
    wp_register_script( 'asu-header-config', get_stylesheet_directory_uri() . '/assets/asu-header/js/asu-header-config.js', array( 'asu-header' ) , '4.0', true );
    
    wp_enqueue_style( 'parent-style' );
    wp_enqueue_style( 'asu-header-css' );
    wp_enqueue_style( 'font-awesome' );
    wp_enqueue_style( 'roboto-font' );
    
    wp_enqueue_script( 'functions' );
    wp_enqueue_script( 'fse-functions' );
    wp_enqueue_script( 'asu-header' );
    wp_enqueue_script( 'asu-header-config' );

}
add_action( 'wp_enqueue_scripts', 'fsdt_enqueue_scripts' );
/* -------------------------- */

/* Portfolio Item Enhancements
 -- Move Portfolio Items to Child Theme (As CPT)
 -- Add excerpt to ouutput of module.

From: https://github.com/kary4/divituts/wiki/Moving-Filterable-Portfolio-module-to-child-theme
Includes: custom-modules/cfwpm.php
---------------------------------- */ 
function divi_child_theme_setup() {
    if ( ! class_exists('ET_Builder_Module') ) {
        return;
    }

    get_template_part( 'custom-modules/cfwpm' );

    $cfwpm = new Custom_ET_Builder_Module_Filterable_Portfolio();

    remove_shortcode( 'et_pb_filterable_portfolio' );
    
    add_shortcode( 'et_pb_filterable_portfolio', array($cfwpm, '_shortcode_callback') );
    
}

add_action( 'wp', 'divi_child_theme_setup', 9999 );

/* Admin scripts 
---------------------------------- */ 
function asu_enqueue_admin_scripts() {
    wp_register_style('fontawesome', get_stylesheet_directory_uri() . '/fontawesome.css' );

}
add_action( 'admin_enqueue_scripts', 'asu_enqueue_admin_scripts');
/* ------------------------------- */

/* activation/deactivation functions */
function asu_theme_activate () {
    //wp_schedule_event(time(), 'daily', 'asu_check_includes');

    //Add Customise Options
    $customise_options = array (
        'boxed_layout' => false,
        'content_width' => 1080,
        'gutter_width' => 3,
        'section_padding' => 4,
        'row_padding' => 2,
        'school_font_size' => 24,
        'school_name' => 'School Name 1',
        'school_uri' => 'http://www.asu.edu/1',
        'secondary_school_name' => 'Secondary School Name 1',
        'secondary_school_uri' => 'http://www.asu.edu/1',
        'nav_fullwidth' => true,
        'hide_primary_logo' => true,
        'primary_nav_dropdown_animation' => 'expand',
        'menu_height' => 57,
        'primary_nav_bg' => '#353535',
        'primary_nav_font_size' => 16,
        'primary_nav_font_style' => 'bold',
        'menu_link' => '#ededed',
        'menu_link_active' => '#ffb204',
        'primary_nav_dropdown_line_color' => '#353535',
        'primary_nav_dropdown_link_color' => '#ededed',
        'minimized_menu_height' => 57,
        'fixed_primary_nav_font_size' => 16,
        'show_search_icon' => false,
        'use_sidebar_width' => true,
        'sidebar_width' => 33,
        'divi_logo' => '',
        'divi_favicon' => 'https://www.asu.edu/sites/all/themes/asu_home/favicon.ico',
        'divi_fixed_nav' => 'on',
        'divi_gallery_layout_enable' => 'false',
        'divi_color_palette' => '#000000|#ffffff|#e02b20|#e09900|#edf000|#7cda24|#0c71c3|#8300e9',
        'accent_color' => '#00a3e0',
    );

    update_option('et_divi', $customise_options);

    // Add social media icons
    add_option( 'asu_social_linkedin', '' );
    add_option( 'asu_social_youtube', '' );
    add_option( 'asu_social_vimeo', '' );
    add_option( 'asu_social_instagram', '' );
    add_option( 'asu_social_flikr', '' );

    // Create Menu
    $menu_name = 'Fulton Schools Default Menu';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    // If it doesn't exist, let's create it.
    if( !$menu_exists){
        $menu_id = wp_create_nav_menu($menu_name);

        // create top level menu item
        $top_level_menu_id = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __('Fulton Schools'),
            'menu-item-url' => home_url( '#' ),
            'menu-item-status' => 'publish')
        );

        // create sub level menu items
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-parent-id' => $top_level_menu_id,
            'menu-item-title' =>  __('Get to Know the Fulton Schools'),
            'menu-item-url' => 'https://engineering.asu.edu/factbook/',
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-parent-id' => $top_level_menu_id,
            'menu-item-title' =>  __('Faculty Hiring'),
            'menu-item-url' => 'https://engineering.asu.edu/hiring/',
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-parent-id' => $top_level_menu_id,
            'menu-item-title' =>  __('Fulton Schools Research'),
            'menu-item-url' => 'https://engineering.asu.edu/research/',
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-parent-id' => $top_level_menu_id,
            'menu-item-title' =>  __('Fulton Schools News'),
            'menu-item-url' => 'https://fullcircle.asu.edu/',
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-parent-id' => $top_level_menu_id,
            'menu-item-title' =>  __('Degree Programs'),
            'menu-item-url' => 'https://explore.engineering.asu.edu/',
            'menu-item-status' => 'publish'));

        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-parent-id' => $top_level_menu_id,
            'menu-item-title' =>  __('Contacts'),
            'menu-item-url' => 'https://engineering.asu.edu/contacts/',
            'menu-item-status' => 'publish'));

        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['primary-menu'] = $top_level_menu_id;

    }
}
function asu_theme_deactivate () {
    //wp_clear_scheduled_hook('asu_check_includes');
}
function asu_activation_options() {
    try {
        $url_to_include_file = '';
        $local_file = '';
        $content = file_get_contents($url_to_include_file);
        file_put_contents($local_file, $content);
    } catch (Exception $e) {
        wp_die($e);
    }
}

add_action('after_switch_theme', 'asu_theme_activate');

function asu_get_school_names()
{
    $school_name = et_get_option('school_name');
    $school_uri = et_get_option('school_uri');
    $secondary_school_name = et_get_option('secondary_school_name');
    $secondary_school_uri = et_get_option('secondary_school_uri');

    $html = '';

    if (empty($school_name)) {
        return $html;
    }

    if (!empty($school_uri)) {
        $html .= '<a class="school" href="' . $school_uri . '" title="' . $school_name . '">'
            . '<span>' . $school_name . '</span></a>';
    }

    if (empty($secondary_school_name)) {
        return asu_wrap_school_names($html);
    }

    if (!empty($secondary_school_uri)) {
        $html .= ' | <a class="secondary-school" href="' . $secondary_school_uri . '" title="' . $secondary_school_name . '">'
            . '<span>' . $secondary_school_name . '</span></a>';
    }

    return asu_wrap_school_names($html);
}

function asu_wrap_school_names($html)
{
    $school_text_size = et_get_option('school_font_size');
    return '<h1 class="header-schoolname" style="font-size:' . $school_text_size . 'px">' . $html . '</h1>';
}

function asu_get_home_menu_item (){
    return '<a class="asu_home_url" href="' . get_site_url() . '">home</a>';
}

function asu_widgets_init() {
    unregister_sidebar( 'sidebar-5' );
}
add_action( 'widgets_init', 'asu_widgets_init', 11 );

/* easy fontawesome icons */
if ( ! function_exists( 'et_pb_get_font_icon_symbols' ) ) :
    function et_pb_get_font_icon_symbols() {
        $symbols = array('&amp;#x21;', '&amp;#x22;', '&amp;#x23;', '&amp;#x24;', '&amp;#x25;', '&amp;#x26;', '&amp;#x27;', '&amp;#x28;', '&amp;#x29;', '&amp;#x2a;', '&amp;#x2b;', '&amp;#x2c;', '&amp;#x2d;', '&amp;#x2e;', '&amp;#x2f;', '&amp;#x30;', '&amp;#x31;', '&amp;#x32;', '&amp;#x33;', '&amp;#x34;', '&amp;#x35;', '&amp;#x36;', '&amp;#x37;', '&amp;#x38;', '&amp;#x39;', '&amp;#x3a;', '&amp;#x3b;', '&amp;#x3c;', '&amp;#x3d;', '&amp;#x3e;', '&amp;#x3f;', '&amp;#x40;', '&amp;#x41;', '&amp;#x42;', '&amp;#x43;', '&amp;#x44;', '&amp;#x45;', '&amp;#x46;', '&amp;#x47;', '&amp;#x48;', '&amp;#x49;', '&amp;#x4a;', '&amp;#x4b;', '&amp;#x4c;', '&amp;#x4d;', '&amp;#x4e;', '&amp;#x4f;', '&amp;#x50;', '&amp;#x51;', '&amp;#x52;', '&amp;#x53;', '&amp;#x54;', '&amp;#x55;', '&amp;#x56;', '&amp;#x57;', '&amp;#x58;', '&amp;#x59;', '&amp;#x5a;', '&amp;#x5b;', '&amp;#x5c;', '&amp;#x5d;', '&amp;#x5e;', '&amp;#x5f;', '&amp;#x60;', '&amp;#x61;', '&amp;#x62;', '&amp;#x63;', '&amp;#x64;', '&amp;#x65;', '&amp;#x66;', '&amp;#x67;', '&amp;#x68;', '&amp;#x69;', '&amp;#x6a;', '&amp;#x6b;', '&amp;#x6c;', '&amp;#x6d;', '&amp;#x6e;', '&amp;#x6f;', '&amp;#x70;', '&amp;#x71;', '&amp;#x72;', '&amp;#x73;', '&amp;#x74;', '&amp;#x75;', '&amp;#x76;', '&amp;#x77;', '&amp;#x78;', '&amp;#x79;', '&amp;#x7a;', '&amp;#x7b;', '&amp;#x7c;', '&amp;#x7d;', '&amp;#x7e;', '&amp;#xe000;', '&amp;#xe001;', '&amp;#xe002;', '&amp;#xe003;', '&amp;#xe004;', '&amp;#xe005;', '&amp;#xe006;', '&amp;#xe007;', '&amp;#xe008;', '&amp;#xe009;', '&amp;#xe00a;', '&amp;#xe00b;', '&amp;#xe00c;', '&amp;#xe00d;', '&amp;#xe00e;', '&amp;#xe00f;', '&amp;#xe010;', '&amp;#xe011;', '&amp;#xe012;', '&amp;#xe013;', '&amp;#xe014;', '&amp;#xe015;', '&amp;#xe016;', '&amp;#xe017;', '&amp;#xe018;', '&amp;#xe019;', '&amp;#xe01a;', '&amp;#xe01b;', '&amp;#xe01c;', '&amp;#xe01d;', '&amp;#xe01e;', '&amp;#xe01f;', '&amp;#xe020;', '&amp;#xe021;', '&amp;#xe022;', '&amp;#xe023;', '&amp;#xe024;', '&amp;#xe025;', '&amp;#xe026;', '&amp;#xe027;', '&amp;#xe028;', '&amp;#xe029;', '&amp;#xe02a;', '&amp;#xe02b;', '&amp;#xe02c;', '&amp;#xe02d;', '&amp;#xe02e;', '&amp;#xe02f;', '&amp;#xe030;', '&amp;#xe031;', '&amp;#xe032;', '&amp;#xe033;', '&amp;#xe034;', '&amp;#xe035;', '&amp;#xe036;', '&amp;#xe037;', '&amp;#xe038;', '&amp;#xe039;', '&amp;#xe03a;', '&amp;#xe03b;', '&amp;#xe03c;', '&amp;#xe03d;', '&amp;#xe03e;', '&amp;#xe03f;', '&amp;#xe040;', '&amp;#xe041;', '&amp;#xe042;', '&amp;#xe043;', '&amp;#xe044;', '&amp;#xe045;', '&amp;#xe046;', '&amp;#xe047;', '&amp;#xe048;', '&amp;#xe049;', '&amp;#xe04a;', '&amp;#xe04b;', '&amp;#xe04c;', '&amp;#xe04d;', '&amp;#xe04e;', '&amp;#xe04f;', '&amp;#xe050;', '&amp;#xe051;', '&amp;#xe052;', '&amp;#xe053;', '&amp;#xe054;', '&amp;#xe055;', '&amp;#xe056;', '&amp;#xe057;', '&amp;#xe058;', '&amp;#xe059;', '&amp;#xe05a;', '&amp;#xe05b;', '&amp;#xe05c;', '&amp;#xe05d;', '&amp;#xe05e;', '&amp;#xe05f;', '&amp;#xe060;', '&amp;#xe061;', '&amp;#xe062;', '&amp;#xe063;', '&amp;#xe064;', '&amp;#xe065;', '&amp;#xe066;', '&amp;#xe067;', '&amp;#xe068;', '&amp;#xe069;', '&amp;#xe06a;', '&amp;#xe06b;', '&amp;#xe06c;', '&amp;#xe06d;', '&amp;#xe06e;', '&amp;#xe06f;', '&amp;#xe070;', '&amp;#xe071;', '&amp;#xe072;', '&amp;#xe073;', '&amp;#xe074;', '&amp;#xe075;', '&amp;#xe076;', '&amp;#xe077;', '&amp;#xe078;', '&amp;#xe079;', '&amp;#xe07a;', '&amp;#xe07b;', '&amp;#xe07c;', '&amp;#xe07d;', '&amp;#xe07e;', '&amp;#xe07f;', '&amp;#xe080;', '&amp;#xe081;', '&amp;#xe082;', '&amp;#xe083;', '&amp;#xe084;', '&amp;#xe085;', '&amp;#xe086;', '&amp;#xe087;', '&amp;#xe088;', '&amp;#xe089;', '&amp;#xe08a;', '&amp;#xe08b;', '&amp;#xe08c;', '&amp;#xe08d;', '&amp;#xe08e;', '&amp;#xe08f;', '&amp;#xe090;', '&amp;#xe091;', '&amp;#xe092;', '&amp;#xe093;', '&amp;#xe094;', '&amp;#xe095;', '&amp;#xe096;', '&amp;#xe097;', '&amp;#xe098;', '&amp;#xe099;', '&amp;#xe09a;', '&amp;#xe09b;', '&amp;#xe09c;', '&amp;#xe09d;', '&amp;#xe09e;', '&amp;#xe09f;', '&amp;#xe0a0;', '&amp;#xe0a1;', '&amp;#xe0a2;', '&amp;#xe0a3;', '&amp;#xe0a4;', '&amp;#xe0a5;', '&amp;#xe0a6;', '&amp;#xe0a7;', '&amp;#xe0a8;', '&amp;#xe0a9;', '&amp;#xe0aa;', '&amp;#xe0ab;', '&amp;#xe0ac;', '&amp;#xe0ad;', '&amp;#xe0ae;', '&amp;#xe0af;', '&amp;#xe0b0;', '&amp;#xe0b1;', '&amp;#xe0b2;', '&amp;#xe0b3;', '&amp;#xe0b4;', '&amp;#xe0b5;', '&amp;#xe0b6;', '&amp;#xe0b7;', '&amp;#xe0b8;', '&amp;#xe0b9;', '&amp;#xe0ba;', '&amp;#xe0bb;', '&amp;#xe0bc;', '&amp;#xe0bd;', '&amp;#xe0be;', '&amp;#xe0bf;', '&amp;#xe0c0;', '&amp;#xe0c1;', '&amp;#xe0c2;', '&amp;#xe0c3;', '&amp;#xe0c4;', '&amp;#xe0c5;', '&amp;#xe0c6;', '&amp;#xe0c7;', '&amp;#xe0c8;', '&amp;#xe0c9;', '&amp;#xe0ca;', '&amp;#xe0cb;', '&amp;#xe0cc;', '&amp;#xe0cd;', '&amp;#xe0ce;', '&amp;#xe0cf;', '&amp;#xe0d0;', '&amp;#xe0d1;', '&amp;#xe0d2;', '&amp;#xe0d3;', '&amp;#xe0d4;', '&amp;#xe0d5;', '&amp;#xe0d6;', '&amp;#xe0d7;', '&amp;#xe0d8;', '&amp;#xe0d9;', '&amp;#xe0da;', '&amp;#xe0db;', '&amp;#xe0dc;', '&amp;#xe0dd;', '&amp;#xe0de;', '&amp;#xe0df;', '&amp;#xe0e0;', '&amp;#xe0e1;', '&amp;#xe0e2;', '&amp;#xe0e3;', '&amp;#xe0e4;', '&amp;#xe0e5;', '&amp;#xe0e6;', '&amp;#xe0e7;', '&amp;#xe0e8;', '&amp;#xe0e9;', '&amp;#xe0ea;', '&amp;#xe0eb;', '&amp;#xe0ec;', '&amp;#xe0ed;', '&amp;#xe0ee;', '&amp;#xe0ef;', '&amp;#xe0f0;', '&amp;#xe0f1;', '&amp;#xe0f2;', '&amp;#xe0f3;', '&amp;#xe0f4;', '&amp;#xe0f5;', '&amp;#xe0f6;', '&amp;#xe0f7;', '&amp;#xe0f8;', '&amp;#xe0f9;', '&amp;#xe0fa;', '&amp;#xe0fb;', '&amp;#xe0fc;', '&amp;#xe0fd;', '&amp;#xe0fe;', '&amp;#xe0ff;', '&amp;#xe100;', '&amp;#xe101;', '&amp;#xe102;', '&amp;#xe103;', '&amp;#xe104;', '&amp;#xe105;', '&amp;#xe106;', '&amp;#xe107;', '&amp;#xe108;', '&amp;#xe109;', '&amp;#xe600;', '&amp;#xe601;', '&amp;#xe602;', '&amp;#xe603;', '&amp;#xe604;', '&amp;#xe605;', '&amp;#xe606;', '&amp;#xe607;', '&amp;#xe608;', '&amp;#xe609;', '&amp;#xe60a;', '&amp;#xe60b;', '&amp;#xe60c;', '&amp;#xe60d;', '&amp;#xe60e;', '&amp;#xe60f;', '&amp;#xe610;', '&amp;#xe611;', '&amp;#xe612;', '&amp;#xe613;', '&amp;#xe614;', '&amp;#xe615;', '&amp;#xe616;', '&amp;#xe617;', '&amp;#xe618;', '&amp;#xe619;', '&amp;#xe61a;', '&amp;#xe61b;', '&amp;#xe61c;', '&amp;#xe61d;', '&amp;#xe61e;', '&amp;#xe61f;', '&amp;#xe620;', '&amp;#xe621;', '&amp;#xe622;', '&amp;#xe623;', '&amp;#xe624;', '&amp;#xe625;', '&amp;#xe626;', '&amp;#xf000;', '&amp;#xf001;', '&amp;#xf002;', '&amp;#xf003;', '&amp;#xf004;', '&amp;#xf005;', '&amp;#xf006;', '&amp;#xf007;', '&amp;#xf008;', '&amp;#xf009;', '&amp;#xf00a;', '&amp;#xf00b;', '&amp;#xf00c;', '&amp;#xf00d;', '&amp;#xf00e;', '&amp;#xf010;', '&amp;#xf011;', '&amp;#xf012;', '&amp;#xf013;', '&amp;#xf014;', '&amp;#xf015;', '&amp;#xf016;', '&amp;#xf017;', '&amp;#xf018;', '&amp;#xf019;', '&amp;#xf01a;', '&amp;#xf01b;', '&amp;#xf01c;', '&amp;#xf01d;', '&amp;#xf01e;', '&amp;#xf021;', '&amp;#xf022;', '&amp;#xf023;', '&amp;#xf024;', '&amp;#xf025;', '&amp;#xf026;', '&amp;#xf027;', '&amp;#xf028;', '&amp;#xf029;', '&amp;#xf02a;', '&amp;#xf02b;', '&amp;#xf02c;', '&amp;#xf02d;', '&amp;#xf02e;', '&amp;#xf02f;', '&amp;#xf030;', '&amp;#xf031;', '&amp;#xf032;', '&amp;#xf033;', '&amp;#xf034;', '&amp;#xf035;', '&amp;#xf036;', '&amp;#xf037;', '&amp;#xf038;', '&amp;#xf039;', '&amp;#xf03a;', '&amp;#xf03b;', '&amp;#xf03c;', '&amp;#xf03d;', '&amp;#xf03e;', '&amp;#xf040;', '&amp;#xf041;', '&amp;#xf042;', '&amp;#xf043;', '&amp;#xf044;', '&amp;#xf045;', '&amp;#xf046;', '&amp;#xf047;', '&amp;#xf048;', '&amp;#xf049;', '&amp;#xf04a;', '&amp;#xf04b;', '&amp;#xf04c;', '&amp;#xf04d;', '&amp;#xf04e;', '&amp;#xf050;', '&amp;#xf051;', '&amp;#xf052;', '&amp;#xf053;', '&amp;#xf054;', '&amp;#xf055;', '&amp;#xf056;', '&amp;#xf057;', '&amp;#xf058;', '&amp;#xf059;', '&amp;#xf05a;', '&amp;#xf05b;', '&amp;#xf05c;', '&amp;#xf05d;', '&amp;#xf05e;', '&amp;#xf060;', '&amp;#xf061;', '&amp;#xf062;', '&amp;#xf063;', '&amp;#xf064;', '&amp;#xf065;', '&amp;#xf066;', '&amp;#xf067;', '&amp;#xf068;', '&amp;#xf069;', '&amp;#xf06a;', '&amp;#xf06b;', '&amp;#xf06c;', '&amp;#xf06d;', '&amp;#xf06e;', '&amp;#xf070;', '&amp;#xf071;', '&amp;#xf072;', '&amp;#xf073;', '&amp;#xf074;', '&amp;#xf075;', '&amp;#xf076;', '&amp;#xf077;', '&amp;#xf078;', '&amp;#xf079;', '&amp;#xf07a;', '&amp;#xf07b;', '&amp;#xf07c;', '&amp;#xf07d;', '&amp;#xf07e;', '&amp;#xf080;', '&amp;#xf081;', '&amp;#xf082;', '&amp;#xf083;', '&amp;#xf084;', '&amp;#xf085;', '&amp;#xf086;', '&amp;#xf087;', '&amp;#xf088;', '&amp;#xf089;', '&amp;#xf08a;', '&amp;#xf08b;', '&amp;#xf08c;', '&amp;#xf08d;', '&amp;#xf08e;', '&amp;#xf090;', '&amp;#xf091;', '&amp;#xf092;', '&amp;#xf093;', '&amp;#xf094;', '&amp;#xf095;', '&amp;#xf096;', '&amp;#xf097;', '&amp;#xf098;', '&amp;#xf099;', '&amp;#xf09a;', '&amp;#xf09b;', '&amp;#xf09c;', '&amp;#xf09d;', '&amp;#xf09e;', '&amp;#xf0a0;', '&amp;#xf0a1;', '&amp;#xf0a2;', '&amp;#xf0a3;', '&amp;#xf0a4;', '&amp;#xf0a5;', '&amp;#xf0a6;', '&amp;#xf0a7;', '&amp;#xf0a8;', '&amp;#xf0a9;', '&amp;#xf0aa;', '&amp;#xf0ab;', '&amp;#xf0ac;', '&amp;#xf0ad;', '&amp;#xf0ae;', '&amp;#xf0b0;', '&amp;#xf0b1;', '&amp;#xf0b2;', '&amp;#xf0c0;', '&amp;#xf0c1;', '&amp;#xf0c2;', '&amp;#xf0c3;', '&amp;#xf0c4;', '&amp;#xf0c5;', '&amp;#xf0c6;', '&amp;#xf0c7;', '&amp;#xf0c8;', '&amp;#xf0c9;', '&amp;#xf0ca;', '&amp;#xf0cb;', '&amp;#xf0cc;', '&amp;#xf0cd;', '&amp;#xf0ce;', '&amp;#xf0d0;', '&amp;#xf0d1;', '&amp;#xf0d2;', '&amp;#xf0d3;', '&amp;#xf0d4;', '&amp;#xf0d5;', '&amp;#xf0d6;', '&amp;#xf0d7;', '&amp;#xf0d8;', '&amp;#xf0d9;', '&amp;#xf0da;', '&amp;#xf0db;', '&amp;#xf0dc;', '&amp;#xf0dd;', '&amp;#xf0de;', '&amp;#xf0e0;', '&amp;#xf0e1;', '&amp;#xf0e2;', '&amp;#xf0e3;', '&amp;#xf0e4;', '&amp;#xf0e5;', '&amp;#xf0e6;', '&amp;#xf0e7;', '&amp;#xf0e8;', '&amp;#xf0e9;', '&amp;#xf0ea;', '&amp;#xf0eb;', '&amp;#xf0ec;', '&amp;#xf0ed;', '&amp;#xf0ee;', '&amp;#xf0f0;', '&amp;#xf0f1;', '&amp;#xf0f2;', '&amp;#xf0f3;', '&amp;#xf0f4;', '&amp;#xf0f5;', '&amp;#xf0f6;', '&amp;#xf0f7;', '&amp;#xf0f8;', '&amp;#xf0f9;', '&amp;#xf0fa;', '&amp;#xf0fb;', '&amp;#xf0fc;', '&amp;#xf0fd;', '&amp;#xf0fe;', '&amp;#xf100;', '&amp;#xf101;', '&amp;#xf102;', '&amp;#xf103;', '&amp;#xf104;', '&amp;#xf105;', '&amp;#xf106;', '&amp;#xf107;', '&amp;#xf108;', '&amp;#xf109;', '&amp;#xf10a;', '&amp;#xf10b;', '&amp;#xf10c;', '&amp;#xf10d;', '&amp;#xf10e;', '&amp;#xf110;', '&amp;#xf111;', '&amp;#xf112;', '&amp;#xf113;', '&amp;#xf114;', '&amp;#xf115;', '&amp;#xf118;', '&amp;#xf119;', '&amp;#xf11a;', '&amp;#xf11b;', '&amp;#xf11c;', '&amp;#xf11d;', '&amp;#xf11e;', '&amp;#xf120;', '&amp;#xf121;', '&amp;#xf122;', '&amp;#xf123;', '&amp;#xf124;', '&amp;#xf125;', '&amp;#xf126;', '&amp;#xf127;', '&amp;#xf128;', '&amp;#xf129;', '&amp;#xf12a;', '&amp;#xf12b;', '&amp;#xf12c;', '&amp;#xf12d;', '&amp;#xf12e;', '&amp;#xf130;', '&amp;#xf131;', '&amp;#xf132;', '&amp;#xf133;', '&amp;#xf134;', '&amp;#xf135;', '&amp;#xf136;', '&amp;#xf137;', '&amp;#xf138;', '&amp;#xf139;', '&amp;#xf13a;', '&amp;#xf13b;', '&amp;#xf13c;', '&amp;#xf13d;', '&amp;#xf13e;', '&amp;#xf140;', '&amp;#xf141;', '&amp;#xf142;', '&amp;#xf143;', '&amp;#xf144;', '&amp;#xf145;', '&amp;#xf146;', '&amp;#xf147;', '&amp;#xf148;', '&amp;#xf149;', '&amp;#xf14a;', '&amp;#xf14b;', '&amp;#xf14c;', '&amp;#xf14d;', '&amp;#xf14e;', '&amp;#xf150;', '&amp;#xf151;', '&amp;#xf152;', '&amp;#xf153;', '&amp;#xf154;', '&amp;#xf155;', '&amp;#xf156;', '&amp;#xf157;', '&amp;#xf158;', '&amp;#xf159;', '&amp;#xf15a;', '&amp;#xf15b;', '&amp;#xf15c;', '&amp;#xf15d;', '&amp;#xf15e;', '&amp;#xf160;', '&amp;#xf161;', '&amp;#xf162;', '&amp;#xf163;', '&amp;#xf164;', '&amp;#xf165;', '&amp;#xf166;', '&amp;#xf167;', '&amp;#xf168;', '&amp;#xf169;', '&amp;#xf16a;', '&amp;#xf16b;', '&amp;#xf16c;', '&amp;#xf16d;', '&amp;#xf16e;', '&amp;#xf170;', '&amp;#xf171;', '&amp;#xf172;', '&amp;#xf173;', '&amp;#xf174;', '&amp;#xf175;', '&amp;#xf176;', '&amp;#xf177;', '&amp;#xf178;', '&amp;#xf179;', '&amp;#xf17a;', '&amp;#xf17b;', '&amp;#xf17c;', '&amp;#xf17d;', '&amp;#xf17e;', '&amp;#xf180;', '&amp;#xf181;', '&amp;#xf182;', '&amp;#xf183;', '&amp;#xf184;', '&amp;#xf185;', '&amp;#xf186;', '&amp;#xf187;', '&amp;#xf188;', '&amp;#xf189;', '&amp;#xf18a;', '&amp;#xf18b;', '&amp;#xf18c;', '&amp;#xf18d;', '&amp;#xf18e;', '&amp;#xf190;', '&amp;#xf191;', '&amp;#xf192;', '&amp;#xf193;', '&amp;#xf194;', '&amp;#xf195;', '&amp;#xf196;', '&amp;#xf197;', '&amp;#xf198;', '&amp;#xf199;', '&amp;#xf19a;', '&amp;#xf19b;', '&amp;#xf19c;', '&amp;#xf19d;', '&amp;#xf19e;', '&amp;#xf1a0;', '&amp;#xf1a1;', '&amp;#xf1a2;', '&amp;#xf1a3;', '&amp;#xf1a4;', '&amp;#xf1a5;', '&amp;#xf1a6;', '&amp;#xf1a7;', '&amp;#xf1a8;', '&amp;#xf1a9;', '&amp;#xf1aa;', '&amp;#xf1ab;', '&amp;#xf1ac;', '&amp;#xf1ad;', '&amp;#xf1ae;', '&amp;#xf1b0;', '&amp;#xf1b1;', '&amp;#xf1b2;', '&amp;#xf1b3;', '&amp;#xf1b4;', '&amp;#xf1b5;', '&amp;#xf1b6;', '&amp;#xf1b7;', '&amp;#xf1b8;', '&amp;#xf1b9;', '&amp;#xf1ba;', '&amp;#xf1bb;', '&amp;#xf1bc;', '&amp;#xf1bd;', '&amp;#xf1be;', '&amp;#xf1c0;', '&amp;#xf1c1;', '&amp;#xf1c2;', '&amp;#xf1c3;', '&amp;#xf1c4;', '&amp;#xf1c5;', '&amp;#xf1c6;', '&amp;#xf1c7;', '&amp;#xf1c8;', '&amp;#xf1c9;', '&amp;#xf1ca;', '&amp;#xf1cb;', '&amp;#xf1cc;', '&amp;#xf1cd;', '&amp;#xf1ce;', '&amp;#xf1d0;', '&amp;#xf1d1;', '&amp;#xf1d2;', '&amp;#xf1d3;', '&amp;#xf1d4;', '&amp;#xf1d5;', '&amp;#xf1d6;', '&amp;#xf1d7;', '&amp;#xf1d8;', '&amp;#xf1d9;', '&amp;#xf1da;', '&amp;#xf1db;', '&amp;#xf1dc;', '&amp;#xf1dd;', '&amp;#xf1de;', '&amp;#xf1e0;', '&amp;#xf1e1;', '&amp;#xf1e2;', '&amp;#xf1e3;', '&amp;#xf1e4;', '&amp;#xf1e5;', '&amp;#xf1e6;', '&amp;#xf1e7;', '&amp;#xf1e8;', '&amp;#xf1e9;', '&amp;#xf1ea;', '&amp;#xf1eb;', '&amp;#xf1ec;', '&amp;#xf1ed;', '&amp;#xf1ee;', '&amp;#xf1f0;', '&amp;#xf1f1;', '&amp;#xf1f2;', '&amp;#xf1f3;', '&amp;#xf1f4;', '&amp;#xf1f5;', '&amp;#xf1f6;', '&amp;#xf1f7;', '&amp;#xf1f8;', '&amp;#xf1f9;', '&amp;#xf1fa;', '&amp;#xf1fb;', '&amp;#xf1fc;', '&amp;#xf1fd;', '&amp;#xf1fe;', '&amp;#xf200;', '&amp;#xf201;', '&amp;#xf202;', '&amp;#xf203;', '&amp;#xf204;', '&amp;#xf205;', '&amp;#xf206;', '&amp;#xf207;', '&amp;#xf208;', '&amp;#xf209;', '&amp;#xf20a;', '&amp;#xf20b;', '&amp;#xf20c;', '&amp;#xf20d;', '&amp;#xf20e;', '&amp;#xf210;', '&amp;#xf211;', '&amp;#xf212;', '&amp;#xf213;', '&amp;#xf214;', '&amp;#xf215;', '&amp;#xf216;', '&amp;#xf217;', '&amp;#xf218;', '&amp;#xf219;', '&amp;#xf21a;', '&amp;#xf21b;', '&amp;#xf21c;', '&amp;#xf21d;', '&amp;#xf21e;', '&amp;#xf221;', '&amp;#xf222;', '&amp;#xf223;', '&amp;#xf224;', '&amp;#xf225;', '&amp;#xf226;', '&amp;#xf227;', '&amp;#xf228;', '&amp;#xf229;', '&amp;#xf22a;', '&amp;#xf22b;', '&amp;#xf22c;', '&amp;#xf22d;', '&amp;#xf230;', '&amp;#xf231;', '&amp;#xf232;', '&amp;#xf233;', '&amp;#xf234;', '&amp;#xf235;', '&amp;#xf236;', '&amp;#xf237;', '&amp;#xf238;', '&amp;#xf239;', '&amp;#xf23a;', '&amp;#xf23b;', '&amp;#xf23c;', '&amp;#xf23d;', '&amp;#xf23e;', '&amp;#xf240;', '&amp;#xf241;', '&amp;#xf242;', '&amp;#xf243;', '&amp;#xf244;', '&amp;#xf245;', '&amp;#xf246;', '&amp;#xf247;', '&amp;#xf248;', '&amp;#xf249;', '&amp;#xf24a;', '&amp;#xf24b;', '&amp;#xf24c;', '&amp;#xf24d;', '&amp;#xf24e;', '&amp;#xf250;', '&amp;#xf251;', '&amp;#xf252;', '&amp;#xf253;', '&amp;#xf254;', '&amp;#xf255;', '&amp;#xf256;', '&amp;#xf257;', '&amp;#xf258;', '&amp;#xf259;', '&amp;#xf25a;', '&amp;#xf25b;', '&amp;#xf25c;', '&amp;#xf25d;', '&amp;#xf25e;', '&amp;#xf260;', '&amp;#xf261;', '&amp;#xf262;', '&amp;#xf263;', '&amp;#xf264;', '&amp;#xf265;', '&amp;#xf266;', '&amp;#xf267;', '&amp;#xf268;', '&amp;#xf269;', '&amp;#xf26a;', '&amp;#xf26b;', '&amp;#xf26c;', '&amp;#xf26d;', '&amp;#xf26e;', '&amp;#xf270;', '&amp;#xf271;', '&amp;#xf272;', '&amp;#xf273;', '&amp;#xf274;', '&amp;#xf275;', '&amp;#xf276;', '&amp;#xf277;', '&amp;#xf278;', '&amp;#xf279;', '&amp;#xf27a;', '&amp;#xf27b;', '&amp;#xf27c;', '&amp;#xf27d;', '&amp;#xf27e;', '&amp;#xf280;', '&amp;#xf281;', '&amp;#xf282;', '&amp;#xf283;', '&amp;#xf284;', '&amp;#xf285;', '&amp;#xf286;', '&amp;#xf287;', '&amp;#xf288;', '&amp;#xf289;', '&amp;#xf28a;', '&amp;#xf28b;', '&amp;#xf28c;', '&amp;#xf28d;', '&amp;#xf28e;', '&amp;#xf290;', '&amp;#xf291;', '&amp;#xf292;', '&amp;#xf293;', '&amp;#xf294;', '&amp;#xf295;',);
        $symbols = apply_filters( 'w', $symbols );
        return $symbols;
    }
endif;
/* easy fontawesome icons */