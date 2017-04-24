<ul class="et-social-icons">

    <?php if ( 'on' === et_get_option( 'divi_show_facebook_icon', 'on' ) ) : ?>
        <li class="et-social-icon et-social-facebook">
            <a href="<?php echo esc_url( et_get_option( 'divi_facebook_url', '#' ) ); ?>" title="Facebook" class="icon">
                <i class="fa fa-facebook-square"></i>
                <span><?php esc_html_e( 'Facebook', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ( 'on' === et_get_option( 'divi_show_twitter_icon', 'on' ) ) : ?>
        <li class="et-social-icon et-social-twitter">
            <a href="<?php echo esc_url( et_get_option( 'divi_twitter_url', '#' ) ); ?>" class="icon">
                <i class="fa fa-twitter-square"></i>
                <span><?php esc_html_e( 'Twitter', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php $asu_social_linkedin = get_option( 'asu_social_linkedin', '' ); ?>
    <?php if ( !empty ( $asu_social_linkedin ) ) : ?>
        <li class="et-social-icon et-social-linkedin">
            <a href="<?php echo esc_url( $asu_social_linkedin ); ?>" class="icon">
                <i class="fa fa-linkedin-square"></i>
                <span><?php esc_html_e( 'LinkedIn', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php $asu_social_youtube = get_option( 'asu_social_youtube', '' ); ?>
    <?php if ( !empty ( $asu_social_youtube ) ) : ?>
        <li class="et-social-icon et-social-youtube">
            <a href="<?php echo esc_url( $asu_social_youtube ); ?>" class="icon">
                <i class="fa fa-youtube-square"></i>
                <span><?php esc_html_e( 'YouTube', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php $asu_social_vimeo = get_option( 'asu_social_vimeo', '' ); ?>
    <?php if ( !empty ( $asu_social_vimeo ) ) : ?>
        <li class="et-social-icon et-social-vimeo">
            <a href="<?php echo esc_url( $asu_social_vimeo ); ?>" class="icon">
                <i class="fa fa-vimeo-square"></i>
                <span><?php esc_html_e( 'Vimeo', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php $asu_social_instagram = get_option( 'asu_social_instagram', '' ); ?>
    <?php if ( !empty ( $asu_social_instagram ) ) : ?>
        <li class="et-social-icon et-social-instagram">
            <a href="<?php echo esc_url( $asu_social_instagram ); ?>" class="icon">
                <i class="fa fa-instagram"></i>
                <span><?php esc_html_e( 'Instagram', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php $asu_social_flikr = get_option( 'asu_social_flikr', '' ); ?>
    <?php if ( !empty ( $asu_social_flikr ) ) : ?>
        <li class="et-social-icon et-social-flickr">
            <a href="<?php echo esc_url( $asu_social_flikr ); ?>" class="icon">
                <i class="fa fa-flickr"></i>
                <span><?php esc_html_e( 'Flickr', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php $asu_social_pinterest = get_option( 'asu_social_pinterest', '' ); ?>
    <?php if ( !empty ( $asu_social_pinterest ) ) : ?>
        <li class="et-social-icon et-social-pinterest">
            <a href="<?php echo esc_url( $asu_social_pinterest ); ?>" class="icon">
                <i class="fa fa-pinterest"></i>
                <span><?php esc_html_e( 'Pinterest', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ( 'on' === et_get_option( 'divi_show_rss_icon', 'on' ) ) : ?>
        <?php
        $et_rss_url = '' !== et_get_option( 'divi_rss_url' )
            ? et_get_option( 'divi_rss_url' )
            : get_bloginfo( 'rss2_url' );
        ?>
        <li class="et-social-icon et-social-rss">
            <a href="<?php echo esc_url( $et_rss_url ); ?>" class="icon">
                <i class="fa fa-rss-square"></i>
                <span><?php esc_html_e( 'RSS', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ( 'on' === et_get_option( 'divi_show_google_icon', 'on' ) ) : ?>
        <li class="et-social-icon et-social-google-plus">
            <a href="<?php echo esc_url( et_get_option( 'divi_google_url', '#' ) ); ?>" class="icon">
                <i class="fa fa-google-plus-square"></i>
                <span><?php esc_html_e( 'Google Scholar', 'Divi' ); ?></span>
            </a>
        </li>
    <?php endif; ?>

</ul>