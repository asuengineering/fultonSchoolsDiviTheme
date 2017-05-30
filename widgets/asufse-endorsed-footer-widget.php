<?php class asufse_EndorsedLogo_Footer_Widget extends WP_Widget
{
    // Create the widget. Give it a title & a description.
    public function __construct() {
        $widget_options = array( 
          'classname' => 'asu-engineering-footer-widget',
          'description' => 'Displays ASU Engineering Endorsed Logo & related info. Place as first widget in left column of the footer.',
        );
        parent::__construct( 'asu-endorsed-footer-widget', 'ASU Engineering Footer Widget', $widget_options );
    }

    // Define the widget output that will be displayed on the front end.    
    public function widget( $args, $instance ) {

        $summarytext = empty( $instance['summarytext'] ) ? '' : $instance['summarytext'];
        $parentorg = empty( $instance['parentorg'] ) ? '' : $instance['parentorg'];
        $contactLink = empty( $instance['contactLink'] ) ? '' : $instance['contactLink'];
        $phone = empty( $instance['phone'] ) ? '' : $instance['phone'];
        $fax = empty( $instance['fax'] ) ? '' : $instance['fax'];
        $contributeLink = empty( $instance['contributeLink'] ) ? '' : $instance['contributeLink'];
        
        echo $args['before_widget'] ?>

        <div id="fse-endorsed-logo">
            <a href="https://engineering.asu.edu/" title="Ira A. Fulton Schools of Engineering, Arizona State University" target="_blank">
                <!-- <img class="color-logo" src="<?php echo get_stylesheet_directory_uri() . '/assets/endorsed-footer/img/asu_fultonengineering_horiz_colorlogo.png' ?>"  alt="Ira A. Fulton Schools of Engineering @ Arizona State University" /> -->
                <img class="white-logo" src="<?php echo get_stylesheet_directory_uri() . '/assets/endorsed-footer/img/asu_fultonengineering_horiz_white.png' ?>"  alt="Ira A. Fulton Schools of Engineering @ Arizona State University" />
            </a>
        </div>

        <?php if ( !empty($summarytext) ): ?><p><?php echo $summarytext ?></p><?php endif; ?>
        <?php if ( !empty($parentorg) ): ?><p><?php echo $parentorg ?></p><?php endif; ?>

        <?php if ( !empty($contactLink) ): ?>
            <p class="contact-link"><a href="<?php echo $contactLink; ?>" id="contact-us-link-in-footer">Contact Us</a></p>
        <?php endif; ?>        

        <?php if ( !empty($phone) ): ?>
            <p class="phone-link"><i class="fa fa-fw fa-phone" aria-hidden="true"></i>Phone: <a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a></p>
        <?php endif; ?>          

        <?php if ( !empty($fax) ): ?>
            <p class="phone-link" ><i class="fa fa-fw fa-fax" aria-hidden="true"></i>Fax: <a href="tel:<?php echo $fax; ?>"><?php echo $fax; ?></a></p>
        <?php endif; ?>        

        <?php if ( !empty($contributeLink) ): ?>
            <p class="contribute"><a class="btn btn-primary" href="<?php echo $contributeLink; ?>" id="contribute-button-in-footer">Contribute</a></p>
        <?php endif; ?>

        <?php echo $args['after_widget'];
    }

    // Define the form to gather the data on the admin side.
    public function form( $instance ) {

        $summarytext = isset( $instance['summarytext'] ) ? esc_textarea( $instance['summarytext'] ) : '';
        $parentorg = isset($instance['parentorg']) ? esc_textarea( $instance['parentorg'] ) : '';
        $contactLink = isset($instance['contactLink']) ? esc_url( $instance['contactLink'] ) : '';
        $phone = isset($instance['phone']) ? sanitize_text_field( $instance['phone'] ) : '';
        $fax = isset($instance['fax']) ? sanitize_text_field( $instance['fax']) : '';
        $contributeLink = isset($instance['contributeLink']) ? esc_url($instance['contributeLink']) : '';

        ?>

        <p>
          <label for="<?php echo $this->get_field_id('summarytext'); ?>"><?php _e('Summary (Accepts HTML)'); ?></label> 
          <textarea cols="20" rows="3" class="widefat" id="<?php echo $this->get_field_id('summarytext'); ?>" name="<?php echo $this->get_field_name('summarytext'); ?>" type="text"><?php echo $summarytext; ?></textarea>
        </p>        

        <p>
          <label for="<?php echo $this->get_field_id('parentorg'); ?>"><?php _e('Parent Organization (Accepts HTML)'); ?></label> 
          <textarea cols="20" rows="3" class="widefat" id="<?php echo $this->get_field_id('parentorg'); ?>" name="<?php echo $this->get_field_name('parentorg'); ?>" type="text"><?php echo $parentorg; ?></textarea>
        </p>        

        <p>
          <label for="<?php echo $this->get_field_id('contactLink'); ?>"><?php _e('Contact Link (URL or Mailto)'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('contactLink'); ?>" name="<?php echo $this->get_field_name('contactLink'); ?>" type="text" value="<?php echo $contactLink; ?>" />
        </p>        

        <p>
          <label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo $phone; ?>" />
        </p>        

        <p>
          <label for="<?php echo $this->get_field_id('fax'); ?>"><?php _e('Fax'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" type="text" value="<?php echo $fax; ?>" />
        </p>        

        <p>
          <label for="<?php echo $this->get_field_id('contributeLink'); ?>"><?php _e('Contribute Link (Req. URL)'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('contributeLink'); ?>" name="<?php echo $this->get_field_name('contributeLink'); ?>" type="text" value="<?php echo $contributeLink; ?>" />
        </p>

        <?php 
    }

    // Saves the settings to the DB
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['summarytext'] = current_user_can('unfiltered_html') ? $new_instance['summarytext'] : stripslashes( wp_filter_post_kses( addslashes($new_instance['summarytext']) ) );
        $instance['parentorg'] = current_user_can('unfiltered_html') ? $new_instance['parentorg'] : stripslashes( wp_filter_post_kses( addslashes($new_instance['parentorg']) ) );
        $instance['contactLink'] = esc_url( $new_instance['contactLink'] );   
        $instance['phone'] = sanitize_text_field( $new_instance['phone'] );
        $instance['fax'] = sanitize_text_field( $new_instance['fax'] );
        $instance['contributeLink'] = esc_url( $new_instance['contributeLink'] );   

        return $instance;
    }

} // end asufse_EndorsedLogo_Footer_Widget class

function asufse_EndorsedLogo_Footer_Widget_init() {
    register_widget('asufse_EndorsedLogo_Footer_Widget');
}

add_action('widgets_init', 'asufse_EndorsedLogo_Footer_Widget_init');