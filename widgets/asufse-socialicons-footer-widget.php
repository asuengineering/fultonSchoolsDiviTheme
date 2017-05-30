<?php class asufse_SocialIcons_Footer_Widget extends WP_Widget
{
    // Create the widget. Give it a title & a description.
    public function __construct() {
        $widget_options = array( 
          'classname' => 'asu-socialicons-footer-widget',
          'description' => 'Displays links to social media icons formatted to be consistent with the ASU Brand Guide. Works well as the second widget in left column of the footer.',
        );
        parent::__construct( 'asu-socialicons-footer-widget', 'ASU Social Media Icons Footer Widget', $widget_options );
    }

    // Define the widget output that will be displayed on the front end.    
    public function widget( $args, $instance ) {

        $facebook = empty( $instance['facebook'] ) ? '' : $instance['facebook'];
        $twitter = empty( $instance['twitter'] ) ? '' : $instance['twitter'];
        $linkedin = empty( $instance['linkedin'] ) ? '' : $instance['linkedin'];
        $youtube = empty( $instance['youtube'] ) ? '' : $instance['youtube'];   
        $vimeo = empty( $instance['vimeo'] ) ? '' : $instance['vimeo'];
        $instagram = empty( $instance['instagram'] ) ? '' : $instance['instagram'];
        $flikr = empty( $instance['flikr'] ) ? '' : $instance['flikr'];
        $pinterest = empty( $instance['pinterest'] ) ? '' : $instance['pinterest'];
        $snapchat = empty( $instance['snapchat'] ) ? '' : $instance['snapchat'];
        $googleplus = empty( $instance['googleplus'] ) ? '' : $instance['googleplus'];
        $github = empty( $instance['github'] ) ? '' : $instance['github'];
        $rss = $instance[ 'rss' ] ? 'true' : 'false';
        $contributeLink = empty( $instance['contributeLink'] ) ? '' : $instance['contributeLink'];
        
        echo $args['before_widget'] ?>

        <ul class="et-social-icons">

        <?php if ( !empty ( $facebook ) ) : ?>
            <li class="et-social-icon et-social-facebook">
                <a href="<?php echo esc_url( $facebook ); ?>" class="icon" target="_blank">
                    <i class="fa fa-facebook-square"></i>
                    <span><?php esc_html_e( 'Facebook', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>        

        <?php if ( !empty ( $twitter ) ) : ?>
            <li class="et-social-icon et-social-twitter">
                <a href="<?php echo esc_url( $twitter ); ?>" class="icon" target="_blank">
                    <i class="fa fa-twitter-square"></i>
                    <span><?php esc_html_e( 'Twitter', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>        

        <?php if ( !empty ( $linkedin ) ) : ?>
            <li class="et-social-icon et-social-linkedin">
                <a href="<?php echo esc_url( $linkedin ); ?>" class="icon" target="_blank">
                    <i class="fa fa-linkedin-square"></i>
                    <span><?php esc_html_e( 'LinkedIn', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( !empty ( $youtube ) ) : ?>
            <li class="et-social-icon et-social-youtube">
                <a href="<?php echo esc_url( $youtube ); ?>" class="icon" target="_blank">
                    <i class="fa fa-youtube-square"></i>
                    <span><?php esc_html_e( 'YouTube', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( !empty ( $vimeo ) ) : ?>
            <li class="et-social-icon et-social-vimeo">
                <a href="<?php echo esc_url( $vimeo ); ?>" class="icon" target="_blank">
                    <i class="fa fa-vimeo-square"></i>
                    <span><?php esc_html_e( 'Vimeo', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( !empty ( $instagram ) ) : ?>
            <li class="et-social-icon et-social-instagram">
                <a href="<?php echo esc_url( $instagram ); ?>" class="icon" target="_blank">
                    <i class="fa fa-instagram"></i>
                    <span><?php esc_html_e( 'Instagram', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( !empty ( $flikr ) ) : ?>
            <li class="et-social-icon et-social-flickr">
                <a href="<?php echo esc_url( $flikr ); ?>" class="icon" target="_blank">
                    <i class="fa fa-flickr"></i>
                    <span><?php esc_html_e( 'Flickr', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( !empty ( $pinterest ) ) : ?>
            <li class="et-social-icon et-social-pinterest">
                <a href="<?php echo esc_url( $pinterest ); ?>" class="icon" target="_blank">
                    <i class="fa fa-pinterest"></i>
                    <span><?php esc_html_e( 'Pinterest', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( !empty ( $snapchat ) ) : ?>
            <li class="et-social-icon et-social-snapchat">
                <a href="<?php echo esc_url( $snapchat ); ?>" class="icon" target="_blank">
                    <i class="fa fa-snapchat-square"></i>
                    <span><?php esc_html_e( 'Snapchat', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( !empty ( $googleplus ) ) : ?>
            <li class="et-social-icon et-social-google-plus">
                <a href="<?php echo esc_url( $googleplus ); ?>" class="icon" target="_blank">
                    <i class="fa fa-google-plus-square"></i>
                    <span><?php esc_html_e( 'Google+', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( !empty ( $github ) ) : ?>
            <li class="et-social-icon et-social-github">
                <a href="<?php echo esc_url( $github ); ?>" class="icon" target="_blank">
                    <i class="fa fa-github"></i>
                    <span><?php esc_html_e( 'GitHub', 'Divi' ); ?></span>
                </a>
            </li>
        <?php endif; ?>

        <?php
            $et_rss_url = '' !== et_get_option( 'divi_rss_url' )
                ? et_get_option( 'divi_rss_url' )
                : get_bloginfo( 'rss2_url' );
            ?>
            <li class="et-social-icon et-social-rss">
                <a href="<?php echo esc_url( $et_rss_url ); ?>" class="icon" target="_blank">
                    <i class="fa fa-rss"></i>
                    <span><?php esc_html_e( 'RSS', 'Divi' ); ?></span>
                </a>
            </li>

        </ul>

        <?php if ( !empty($contributeLink) ): ?>
            <p class="contribute"><a class="btn btn-primary" href="<?php echo $contributeLink; ?>" id="contribute-button-in-footer">Contribute</a></p>
        <?php endif; ?>

        <?php echo $args['after_widget'];
    }

    // Define the form to gather the data on the admin side.
    public function form( $instance ) {

        $facebook = isset($instance['facebook']) ? esc_url($instance['facebook']) : '';
        $twitter = isset($instance['twitter']) ? esc_url($instance['twitter']) : '';
        $linkedin = isset($instance['linkedin']) ? esc_url($instance['linkedin']) : '';
        $youtube = isset($instance['youtube']) ? esc_url($instance['youtube']) : '';
        $vimeo = isset($instance['vimeo'] ) ? esc_url($instance['vimeo'] ) : '';
        $instagram = isset($instance['instagram']) ? esc_url($instance['instagram']) : '';
        $flikr = isset($instance['flikr']) ? esc_url($instance['flikr']) : '';
        $pinterest = isset($instance['pinterest']) ? esc_url($instance['pinterest']) : '';
        $snapchat = isset($instance['snapchat']) ? esc_url($instance['snapchat']) : '';
        $googleplus = isset($instance['googleplus']) ? esc_url($instance['googleplus']) : '';
        $github = isset($instance['github']) ? esc_url($instance['github']) : '';
        $contributeLink = isset($instance['contributeLink']) ? esc_url($instance['contributeLink']) : '';

        ?>  

        <p>
          <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo $facebook; ?>" />
        </p>        

        <p>
          <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('LinkedIn'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo $linkedin; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('YouTube'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo $youtube; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('vimeo'); ?>"><?php _e('Vimeo'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('vimeo'); ?>" name="<?php echo $this->get_field_name('vimeo'); ?>" type="text" value="<?php echo $vimeo; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('instagram'); ?>"><?php _e('Instagram'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" name="<?php echo $this->get_field_name('instagram'); ?>" type="text" value="<?php echo $instagram; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('flikr'); ?>"><?php _e('Flickr'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('flikr'); ?>" name="<?php echo $this->get_field_name('flikr'); ?>" type="text" value="<?php echo $flikr; ?>" />
        </p>        

        <p>
          <label for="<?php echo $this->get_field_id('pinterest'); ?>"><?php _e('Pinterest'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('pinterest'); ?>" name="<?php echo $this->get_field_name('pinterest'); ?>" type="text" value="<?php echo $pinterest; ?>" />
        </p>
        
        <p>
          <label for="<?php echo $this->get_field_id('snapchat'); ?>"><?php _e('Snapchat'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('snapchat'); ?>" name="<?php echo $this->get_field_name('snapchat'); ?>" type="text" value="<?php echo $snapchat; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google Plus'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" type="text" value="<?php echo $googleplus; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('github'); ?>"><?php _e('GitHub'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('github'); ?>" name="<?php echo $this->get_field_name('github'); ?>" type="text" value="<?php echo $github; ?>" />
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

        $instance['facebook'] = esc_url( $new_instance['facebook'] );   
        $instance['twitter'] = esc_url( $new_instance['twitter'] );   
        $instance['linkedin'] = esc_url( $new_instance['linkedin'] );   
        $instance['youtube'] = esc_url( $new_instance['youtube'] );   
        $instance['vimeo'] = esc_url( $new_instance['vimeo'] );   
        $instance['instagram'] = esc_url( $new_instance['instagram'] );   
        $instance['flikr'] = esc_url( $new_instance['flikr'] );   
        $instance['pinterest'] = esc_url( $new_instance['pinterest'] );   
        $instance['snapchat'] = esc_url( $new_instance['snapchat'] );   
        $instance['googleplus'] = esc_url( $new_instance['googleplus'] );   
        $instance['github'] = esc_url( $new_instance['github'] );    
        $instance['rss'] = $new_instance[ 'rss' ]; 
        $instance['contributeLink'] = esc_url( $new_instance['contributeLink'] );   

        return $instance;
    }

} // end asufse_EndorsedLogo_Footer_Widget class

function asufse_SocialIcons_Footer_Widget_init() {
    register_widget('asufse_SocialIcons_Footer_Widget');
}

add_action('widgets_init', 'asufse_SocialIcons_Footer_Widget_init');