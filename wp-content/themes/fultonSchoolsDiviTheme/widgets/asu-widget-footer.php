<?php class ASU_Footer_Widget extends WP_Widget
{
    function __construct(){
        $widget_ops = array( 'description' => esc_html__( 'Displays ASU Footer info', 'ASU' ) );
        $control_ops = array( 'width' => 300, 'height' => 300 );
        parent::__construct( false, $name = esc_html__( 'ASU Footer info', 'ASU' ), $widget_ops, $control_ops );
    }

    /* Displays the Widget in the front-end */
    function widget( $args, $instance ){
        extract($args);
        $logo = empty( $instance['logo'] ) ? '' : esc_url( $instance['logo'] );
        $college_name = empty( $instance['college_name'] ) ? '' : esc_html( $instance['college_name'] );
        $address = empty( $instance['address'] ) ? '' : $instance['address'];
        $phone = empty( $instance['phone'] ) ? '' : $instance['phone'];
        $fax = empty( $instance['fax'] ) ? '' : $instance['fax'];
        $contact_us = empty( $instance['contact_us'] ) ? '' : $instance['contact_us'];
        $contribute_link = empty( $instance['contribute_link'] ) ? '' : $instance['contribute_link'];

        ?>
        <div class="clearfix">

            <?php if ( !empty($logo) ): ?>
            <div>
                <img src="<?php echo et_new_thumb_resize( et_multisite_thumbnail($logo), 300, 68, '', true ); ?>" id="logo-image" alt="<?php echo $college_name ?> Logo" />
            </div><br>

            <?php endif; ?>

            <?php if ( !empty($college_name) ): ?>
                <?php echo $college_name ?>
            <?php endif; ?>

            <?php if ( !empty($address) ): ?>
                <address>
                    <?php echo nl2br($address); ?>
                </address>
                <br>
            <?php endif; ?>

            <?php if ( !empty($phone) ): ?>
                <p>
                    Phone: <a class="phone-link" href="tel:<?php echo $phone; ?>" id="phone-link-in-footer"><?php echo $phone; ?></a><br>
                <?php if ( empty ( $fax ) ): ?>
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( !empty($fax) ): ?>
                <?php if ( empty ( $phone ) ): ?>
                    <p>
                <?php endif; ?>
                    Fax: <a class="phone-link" href="tel:<?php echo $fax; ?>" id="phone-link-in-footer"><?php echo $fax; ?></a><br>
                </p>
            <?php else: ?>
                <br>
            <?php endif; ?>

            <?php if ( !empty($contact_us) ): ?>
                <a class="contact-link" href="mailto:<?php echo $contact_us; ?>" id="contact-us-link-in-footer">Contact Us</a><br><br>
            <?php endif; ?>

            <?php if ( false !== et_get_option( 'show_footer_social_icons', true ) ) { ?>
                <?php include ASU_CHILD_THEME_DIRECTORY . '/includes/social_icons.php'; ?>
            <?php } ?>

            <?php if ( !empty($contribute_link) ): ?>
                <p><a class="btn btn-primary" href="<?php echo $contribute_link; ?>" id="contribute-button-in-footer">Contribute</a></p>
            <?php endif; ?>

        </div>
        <?php
        //echo $after_widget;
    }

    /*Saves the settings. */
    function update( $new_instance, $old_instance ){
        $instance = $old_instance;
        $instance['logo'] = esc_url( $new_instance['logo'] );
        $instance['college_name'] = sanitize_text_field( $new_instance['college_name'] );
        $instance['address'] = current_user_can('unfiltered_html') ? $new_instance['address'] : stripslashes( wp_filter_post_kses( addslashes($new_instance['address']) ) );
        $instance['phone'] = sanitize_text_field( $new_instance['phone'] );
        $instance['fax'] = sanitize_text_field( $new_instance['fax'] );
        $instance['contact_us'] = sanitize_text_field( $new_instance['contact_us'] );
        $instance['contribute_link'] = esc_url( $new_instance['contribute_link'] );

        return $instance;
    }

    /*Creates the form for the widget in the back-end. */
    function form( $instance ){
        //Defaults
        $instance = wp_parse_args( (array) $instance, array(
            'logo' => '',
            'college_name'=>'',
            'address'=>'',
            'phone'=>'',
            'fax'=>'',
            'contact_us'=>'',
            'contribute_link'=>'',
        ) );

        $logo = esc_url( $instance['logo'] );
        $college_name = esc_attr( $instance['college_name'] );
        $address = esc_textarea( $instance['address'] );
        $phone = esc_attr( $instance['phone'] );
        $fax = esc_attr( $instance['fax'] );
        $contact_us = esc_attr( $instance['contact_us'] );
        $contribute_link = esc_attr( $instance['contribute_link'] );

        # Logo
        echo '<p><label for="' . $this->get_field_id('logo') . '">' . esc_html__( 'Logo Image', 'ASU' ) . ':' . '</label><textarea cols="20" rows="2" class="widefat" id="' . $this->get_field_id('logo') . '" name="' . $this->get_field_name('logo') . '" >'. $logo .'</textarea></p>';
        # College Name
        echo '<p><label for="' . $this->get_field_id('college_name') . '">' . esc_html__( 'College Name', 'ASU' ) . ':' . '</label><input class="widefat" id="' . $this->get_field_id('college_name') . '" name="' . $this->get_field_name('college_name') . '" type="text" value="' . $college_name . '" /></p>';
        # Address
        echo '<p><label for="' . $this->get_field_id('address') . '">' . esc_html__( 'Address', 'ASU' ) . ':' . '</label><textarea cols="20" rows="5" class="widefat" id="' . $this->get_field_id('address') . '" name="' . $this->get_field_name('address') . '" >'. $address .'</textarea></p>';
        # Phone
        echo '<p><label for="' . $this->get_field_id('phone') . '">' . esc_html__( 'Phone', 'ASU' ) . ':' . '</label><input class="widefat" id="' . $this->get_field_id('phone') . '" name="' . $this->get_field_name('phone') . '" type="text" value="' . $phone . '" /></p>';
        # Fax
        echo '<p><label for="' . $this->get_field_id('fax') . '">' . esc_html__( 'Fax', 'ASU' ) . ':' . '</label><input class="widefat" id="' . $this->get_field_id('fax') . '" name="' . $this->get_field_name('fax') . '" type="text" value="' . $fax . '" /></p>';
        # Contact Us (email)
        echo '<p><label for="' . $this->get_field_id('contact_us') . '">' . esc_html__( 'Contact Us (email)', 'ASU' ) . ':' . '</label><input class="widefat" id="' . $this->get_field_id('contact_us') . '" name="' . $this->get_field_name('contact_us') . '" type="text" value="' . $contact_us . '" /></p>';
        # Contribute Us (url)
        echo '<p><label for="' . $this->get_field_id('contribute_link') . '">' . esc_html__( 'Contribute link (url)', 'ASU' ) . ':' . '</label><input class="widefat" id="' . $this->get_field_id('contribute_link') . '" name="' . $this->get_field_name('contribute_link') . '" type="text" value="' . $contribute_link . '" /></p>';


    }

}// end ASU_Footer_Widget class

function asu_footer_widget_init() {
    register_widget('ASU_Footer_Widget');
}

add_action('widgets_init', 'asu_footer_widget_init');