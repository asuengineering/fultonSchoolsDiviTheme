<?php class ASU_Sidebar_Widget extends WP_Widget
{
    function __construct(){
        $widget_ops = array( 'description' => esc_html__( 'Displays something called the "Fulton Sidebar Widget." Depreciated as of FSDT 1.8.', 'ASU' ) );
        $control_ops = array( 'width' => 300, 'height' => 300 );
        parent::__construct( false, $name = esc_html__( '(Depreciated) ASU Fulton Sidebar Widget', 'ASU' ), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ){
        extract($args);
        $logo = empty( $instance['logo'] ) ? '' : esc_url( $instance['logo'] );
        $title = empty( $instance['title'] ) ? '' : esc_html( $instance['title'] );
        ?>
        <div class="clearfix fulton-sidebar">
            <div class="logo">
                <img src="<?php echo $logo; ?>" id="logo-image" alt="<?php echo $title ?> Logo" />
            </div>
            <div class="header">
                <h2><?php echo $title ?></h2>
            </div>
        </div>
        <?php
    }

    /*Saves the settings. */
    function update( $new_instance, $old_instance ){
        $instance = $old_instance;
        $instance['logo'] = esc_url( $new_instance['logo'] );
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        return $instance;
    }

    /*Creates the form for the widget in the back-end. */
    function form( $instance ){
        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
            'logo' => '',
            'title'=>'',
        ) );

        $logo = esc_url( $instance['logo'] );
        $title = esc_attr( $instance['title'] );

        # Logo
        echo '<p><label for="' . $this->get_field_id('logo') . '">' . esc_html__( 'Logo Image', 'ASU' ) . ':' . '</label><textarea cols="20" rows="2" class="widefat" id="' . $this->get_field_id('logo') . '" name="' . $this->get_field_name('logo') . '" >'. $logo .'</textarea></p>';
        # College Name
        echo '<p><label for="' . $this->get_field_id('title') . '">' . esc_html__( 'Sidebar Title', 'ASU' ) . ':' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';

    }

}// end ASU_Sidebar_Widget class

function asu_sidebar_widget_init() {
    register_widget('ASU_Sidebar_Widget');
}

add_action('widgets_init', 'asu_sidebar_widget_init');