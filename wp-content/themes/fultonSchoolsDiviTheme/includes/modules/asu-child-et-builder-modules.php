<?php

function asu_divi_child_theme_setup() {

	if ( class_exists( 'ET_Builder_Module' ) ) {

		class ASU_ET_Builder_Module_Fullwidth_Slider extends ET_Builder_Module {
			function init() {
				$this->name            = esc_html__( 'Fullwidth Slider', 'et_builder' );
				$this->slug            = 'et_pb_fullwidth_slider';
				$this->fullwidth       = true;
				$this->child_slug      = 'et_pb_slide';
				$this->child_item_text = esc_html__( 'Slide', 'et_builder' );

				$this->whitelisted_fields = array(
					'show_arrows',
					'show_pagination',
					'auto',
					'auto_speed',
					'auto_ignore_hover',
					'parallax',
					'parallax_method',
					'remove_inner_shadow',
					'background_position',
					'background_size',
					'admin_label',
					'module_id',
					'module_class',
					'top_padding',
					'bottom_padding',
					'hide_content_on_mobile',
					'hide_cta_on_mobile',
					'show_image_video_mobile',
					'bottom_padding_tablet',
					'bottom_padding_phone',
					'top_padding_tablet',
					'top_padding_phone',

				);

				$this->fields_defaults = array(
					'show_arrows'             => array( 'on' ),
					'show_pagination'         => array( 'on' ),
					'auto'                    => array( 'off' ),
					'auto_speed'              => array( '7000' ),
					'auto_ignore_hover'       => array( 'off' ),
					'parallax'                => array( 'off' ),
					'parallax_method'         => array( 'off' ),
					'remove_inner_shadow'     => array( 'off' ),
					'background_position'     => array( 'default' ),
					'background_size'         => array( 'default' ),
					'hide_content_on_mobile'  => array( 'off' ),
					'hide_cta_on_mobile'      => array( 'off' ),
					'show_image_video_mobile' => array( 'off' ),
				);

				$this->main_css_element = '%%order_class%%.et_pb_slider';
				$this->advanced_options = array(
					'fonts' => array(
						'header' => array(
							'label'    => esc_html__( 'Header', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} .et_pb_slide_description .et_pb_slide_title",
								'important' => array(
									'color',
								),
							),
						),
						'body'   => array(
							'label'    => esc_html__( 'Body', 'et_builder' ),
							'css'      => array(
								'main'        => "{$this->main_css_element} .et_pb_slide_content",
								'line_height' => "{$this->main_css_element} p",
							),
						),
					),
					'button' => array(
						'button' => array(
							'label' => esc_html__( 'Button', 'et_builder' ),
						),
					),
				);
				$this->custom_css_options = array(
					'slide_description' => array(
						'label'    => esc_html__( 'Slide Description', 'et_builder' ),
						'selector' => '.et_pb_slide_description',
					),
					'slide_title' => array(
						'label'    => esc_html__( 'Slide Title', 'et_builder' ),
						'selector' => '.et_pb_slide_description .et_pb_slide_title',
					),
					'slide_button' => array(
						'label'    => esc_html__( 'Slide Button', 'et_builder' ),
						'selector' => 'a.et_pb_more_button',
					),
					'slide_controllers' => array(
						'label'    => esc_html__( 'Slide Controllers', 'et_builder' ),
						'selector' => '.et-pb-controllers',
					),
					'slide_active_controller' => array(
						'label'    => esc_html__( 'Slide Active Controller', 'et_builder' ),
						'selector' => '.et-pb-controllers .et-pb-active-control',
					),
					'slide_image' => array(
						'label'    => esc_html__( 'Slide Image', 'et_builder' ),
						'selector' => '.et_pb_slide_image',
					),
					'slide_arrows' => array(
						'label'    => esc_html__( 'Slide Arrows', 'et_builder' ),
						'selector' => '.et-pb-slider-arrows a',
					),
				);
			}

			function get_fields() {
				$fields = array();
				return $fields;
			}

			function pre_shortcode_content() {
				global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon, $et_pb_slider_item_num;

				$et_pb_slider_item_num = 0;

				$parallax        = $this->shortcode_atts['parallax'];
				$parallax_method = $this->shortcode_atts['parallax_method'];
				$hide_content_on_mobile  = $this->shortcode_atts['hide_content_on_mobile'];
				$hide_cta_on_mobile      = $this->shortcode_atts['hide_cta_on_mobile'];
				$button_custom           = $this->shortcode_atts['custom_button'];
				$custom_icon             = $this->shortcode_atts['button_icon'];

				$et_pb_slider_has_video = false;

				$et_pb_slider_parallax = $parallax;

				$et_pb_slider_parallax_method = $parallax_method;

				$et_pb_slider_hide_mobile = array(
					'hide_content_on_mobile'  => $hide_content_on_mobile,
					'hide_cta_on_mobile'      => $hide_cta_on_mobile,
				);

				$et_pb_slider_custom_icon = 'on' === $button_custom ? $custom_icon : '';

			}

			function shortcode_callback( $atts, $content = null, $function_name ) {
				$module_id               = $this->shortcode_atts['module_id'];
				$module_class            = $this->shortcode_atts['module_class'];
				$show_arrows             = $this->shortcode_atts['show_arrows'];
				$show_pagination         = $this->shortcode_atts['show_pagination'];
				$parallax                = $this->shortcode_atts['parallax'];
				$parallax_method         = $this->shortcode_atts['parallax_method'];
				$auto                    = $this->shortcode_atts['auto'];
				$auto_speed              = $this->shortcode_atts['auto_speed'];
				$auto_ignore_hover       = $this->shortcode_atts['auto_ignore_hover'];
				$top_padding             = $this->shortcode_atts['top_padding'];
				$bottom_padding          = $this->shortcode_atts['bottom_padding'];
				$top_padding_tablet      = $this->shortcode_atts['top_padding_tablet'];
				$bottom_padding_tablet   = $this->shortcode_atts['bottom_padding_tablet'];
				$top_padding_phone       = $this->shortcode_atts['top_padding_phone'];
				$bottom_padding_phone    = $this->shortcode_atts['bottom_padding_phone'];
				$remove_inner_shadow     = $this->shortcode_atts['remove_inner_shadow'];
				$show_image_video_mobile = $this->shortcode_atts['show_image_video_mobile'];
				$background_position     = $this->shortcode_atts['background_position'];
				$background_size         = $this->shortcode_atts['background_size'];

				global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon;

				$content = $this->shortcode_content;

				$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

				if ( '' !== $top_padding || '' !== $top_padding_tablet || '' !== $top_padding_phone ) {
					$padding_values = array(
						'desktop' => $top_padding,
						'tablet'  => $top_padding_tablet,
						'phone'   => $top_padding_phone,
					);

					et_pb_generate_responsive_css( $padding_values, '%%order_class%% .et_pb_slide_description', 'padding-top', $function_name );
				}

				if ( '' !== $bottom_padding || '' !== $bottom_padding_tablet || '' !== $bottom_padding_phone ) {
					$padding_values = array(
						'desktop' => $bottom_padding,
						'tablet'  => $bottom_padding_tablet,
						'phone'   => $bottom_padding_phone,
					);

					et_pb_generate_responsive_css( $padding_values, '%%order_class%% .et_pb_slide_description', 'padding-bottom', $function_name );
				}

				if ( 'default' !== $background_position && 'off' === $parallax ) {
					$processed_position = str_replace( '_', ' ', $background_position );

					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%% .et_pb_slide',
						'declaration' => sprintf(
							'background-position: %1$s;',
							esc_html( $processed_position )
						),
					) );
				}

				if ( 'default' !== $background_size && 'off' === $parallax ) {
					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%% .et_pb_slide',
						'declaration' => sprintf(
							'-moz-background-size: %1$s;
					-webkit-background-size: %1$s;
					background-size: %1$s;',
							esc_html( $background_size )
						),
					) );
				}

				$fullwidth = 'et_pb_fullwidth_slider' === $function_name ? 'on' : 'off';

				$class  = '';
				$class .= 'off' === $fullwidth ? ' et_pb_slider_fullwidth_off' : '';
				$class .= 'off' === $show_arrows ? ' et_pb_slider_no_arrows' : '';
				$class .= 'off' === $show_pagination ? ' et_pb_slider_no_pagination' : '';
				$class .= 'on' === $parallax ? ' et_pb_slider_parallax' : '';
				$class .= 'on' === $auto ? ' et_slider_auto et_slider_speed_' . esc_attr( $auto_speed ) : '';
				$class .= 'on' === $auto_ignore_hover ? ' et_slider_auto_ignore_hover' : '';
				$class .= 'on' === $remove_inner_shadow ? ' et_pb_slider_no_shadow' : '';
				$class .= 'on' === $show_image_video_mobile ? ' et_pb_slider_show_image' : '';

				$output = sprintf(
					'<div%4$s class="et_pb_module et_pb_slider%1$s%3$s%5$s">
				<div class="et_pb_slides et_pb_asu_slides">
					%2$s
				</div> <!-- .et_pb_slides -->
			</div> <!-- .et_pb_slider -->
			',
					$class,
					$content,
					( $et_pb_slider_has_video ? ' et_pb_preload' : '' ),
					( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
					( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
				);

				return $output;
			}
		}
		$asu_et_builder_module_fullwidth_slider = new ASU_ET_Builder_Module_Fullwidth_Slider();
		remove_shortcode( 'et_pb_fullwidth_slider' );
		add_shortcode( 'et_pb_fullwidth_slider', array( $asu_et_builder_module_fullwidth_slider, '_shortcode_callback' ) );

		class ASU_ET_Builder_Module_Slider extends ET_Builder_Module {
			function init() {
				$this->name            = esc_html__( 'Slider', 'et_builder' );
				$this->slug            = 'et_pb_slider';
				$this->child_slug      = 'et_pb_slide';
				$this->child_item_text = esc_html__( 'Slide', 'et_builder' );

				$this->whitelisted_fields = array(
					'show_arrows',
					'show_pagination',
					'auto',
					'auto_speed',
					'auto_ignore_hover',
					'parallax',
					'parallax_method',
					'remove_inner_shadow',
					'background_position',
					'background_size',
					'admin_label',
					'module_id',
					'module_class',
					'top_padding',
					'bottom_padding',
					'hide_content_on_mobile',
					'hide_cta_on_mobile',
					'show_image_video_mobile',
					'top_padding_tablet',
					'top_padding_phone',
					'bottom_padding_tablet',
					'bottom_padding_phone',
				);

				$this->fields_defaults = array(
					'show_arrows'             => array( 'on' ),
					'show_pagination'         => array( 'on' ),
					'auto'                    => array( 'off' ),
					'auto_speed'              => array( '7000' ),
					'auto_ignore_hover'       => array( 'off' ),
					'parallax'                => array( 'off' ),
					'parallax_method'         => array( 'off' ),
					'remove_inner_shadow'     => array( 'off' ),
					'background_position'     => array( 'default' ),
					'background_size'         => array( 'default' ),
					'hide_content_on_mobile'  => array( 'off' ),
					'hide_cta_on_mobile'      => array( 'off' ),
					'show_image_video_mobile' => array( 'off' ),
				);

				$this->main_css_element = '%%order_class%%.et_pb_slider';
				$this->advanced_options = array(
					'fonts' => array(
						'header' => array(
							'label'    => esc_html__( 'Header', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} .et_pb_slide_description .et_pb_slide_title",
							),
						),
						'body'   => array(
							'label'    => esc_html__( 'Body', 'et_builder' ),
							'css'      => array(
								'line_height' => "{$this->main_css_element}",
								'main' => "{$this->main_css_element} .et_pb_slide_content",
							),
						),
					),
					'button' => array(
						'button' => array(
							'label' => esc_html__( 'Button', 'et_builder' ),
						),
					),
				);
				$this->custom_css_options = array(
					'slide_description' => array(
						'label'    => esc_html__( 'Slide Description', 'et_builder' ),
						'selector' => '.et_pb_slide_description',
					),
					'slide_title' => array(
						'label'    => esc_html__( 'Slide Title', 'et_builder' ),
						'selector' => '.et_pb_slide_description .et_pb_slide_title',
					),
					'slide_button' => array(
						'label'    => esc_html__( 'Slide Button', 'et_builder' ),
						'selector' => 'a.et_pb_more_button',
					),
					'slide_controllers' => array(
						'label'    => esc_html__( 'Slide Controllers', 'et_builder' ),
						'selector' => '.et-pb-controllers',
					),
					'slide_active_controller' => array(
						'label'    => esc_html__( 'Slide Active Controller', 'et_builder' ),
						'selector' => '.et-pb-controllers .et-pb-active-control',
					),
					'slide_image' => array(
						'label'    => esc_html__( 'Slide Image', 'et_builder' ),
						'selector' => '.et_pb_slide_image',
					),
					'slide_arrows' => array(
						'label'    => esc_html__( 'Slide Arrows', 'et_builder' ),
						'selector' => '.et-pb-slider-arrows a',
					),
				);
			}

			function get_fields() {
				$fields = array ();
				return $fields;
			}

			function pre_shortcode_content() {
				global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon, $et_pb_slider_item_num;

				$et_pb_slider_item_num = 0;

				$parallax                = $this->shortcode_atts['parallax'];
				$parallax_method         = $this->shortcode_atts['parallax_method'];
				$hide_content_on_mobile  = $this->shortcode_atts['hide_content_on_mobile'];
				$hide_cta_on_mobile      = $this->shortcode_atts['hide_cta_on_mobile'];
				$button_custom           = $this->shortcode_atts['custom_button'];
				$custom_icon             = $this->shortcode_atts['button_icon'];

				$et_pb_slider_has_video = false;

				$et_pb_slider_parallax = $parallax;

				$et_pb_slider_parallax_method = $parallax_method;

				$et_pb_slider_hide_mobile = array(
					'hide_content_on_mobile'  => $hide_content_on_mobile,
					'hide_cta_on_mobile'      => $hide_cta_on_mobile,
				);

				$et_pb_slider_custom_icon = 'on' === $button_custom ? $custom_icon : '';

			}

			function shortcode_callback( $atts, $content = null, $function_name ) {
				$module_id               = $this->shortcode_atts['module_id'];
				$module_class            = $this->shortcode_atts['module_class'];
				$show_arrows             = $this->shortcode_atts['show_arrows'];
				$show_pagination         = $this->shortcode_atts['show_pagination'];
				$parallax                = $this->shortcode_atts['parallax'];
				$parallax_method         = $this->shortcode_atts['parallax_method'];
				$auto                    = $this->shortcode_atts['auto'];
				$auto_speed              = $this->shortcode_atts['auto_speed'];
				$auto_ignore_hover       = $this->shortcode_atts['auto_ignore_hover'];
				$top_padding             = $this->shortcode_atts['top_padding'];
				$body_font_size 		 = $this->shortcode_atts['body_font_size'];
				$bottom_padding          = $this->shortcode_atts['bottom_padding'];
				$top_padding_tablet      = $this->shortcode_atts['top_padding_tablet'];
				$top_padding_phone       = $this->shortcode_atts['top_padding_phone'];
				$bottom_padding_tablet   = $this->shortcode_atts['bottom_padding_tablet'];
				$bottom_padding_phone    = $this->shortcode_atts['bottom_padding_phone'];
				$remove_inner_shadow     = $this->shortcode_atts['remove_inner_shadow'];
				$hide_content_on_mobile  = $this->shortcode_atts['hide_content_on_mobile'];
				$hide_cta_on_mobile      = $this->shortcode_atts['hide_cta_on_mobile'];
				$show_image_video_mobile = $this->shortcode_atts['show_image_video_mobile'];
				$background_position     = $this->shortcode_atts['background_position'];
				$background_size         = $this->shortcode_atts['background_size'];

				global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon;

				$content = $this->shortcode_content;

				$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

				if ( '' !== $top_padding || '' !== $top_padding_tablet || '' !== $top_padding_phone ) {
					$padding_values = array(
						'desktop' => $top_padding,
						'tablet'  => $top_padding_tablet,
						'phone'   => $top_padding_phone,
					);

					et_pb_generate_responsive_css( $padding_values, '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description', 'padding-top', $function_name );
				}

				if ( '' !== $bottom_padding || '' !== $bottom_padding_tablet || '' !== $bottom_padding_phone ) {
					$padding_values = array(
						'desktop' => $bottom_padding,
						'tablet'  => $bottom_padding_tablet,
						'phone'   => $bottom_padding_phone,
					);

					et_pb_generate_responsive_css( $padding_values, '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description', 'padding-bottom', $function_name );
				}

				if ( '' !== $bottom_padding || '' !== $top_padding ) {
					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%% .et_pb_slide_description, .et_pb_slider_fullwidth_off%%order_class%% .et_pb_slide_description',
						'declaration' => 'padding-right: 0; padding-left: 0;',
					) );
				}

				if ( 'default' !== $background_position && 'off' === $parallax ) {
					$processed_position = str_replace( '_', ' ', $background_position );

					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%% .et_pb_slide',
						'declaration' => sprintf(
							'background-position: %1$s;',
							esc_html( $processed_position )
						),
					) );
				}

				if ( 'default' !== $background_size && 'off' === $parallax ) {
					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '%%order_class%% .et_pb_slide',
						'declaration' => sprintf(
							'-moz-background-size: %1$s;
					-webkit-background-size: %1$s;
					background-size: %1$s;',
							esc_html( $background_size )
						),
					) );
				}

				$fullwidth = 'et_pb_fullwidth_slider' === $function_name ? 'on' : 'off';

				$class  = '';
				$class .= 'off' === $fullwidth ? ' et_pb_slider_fullwidth_off' : '';
				$class .= 'off' === $show_arrows ? ' et_pb_slider_no_arrows' : '';
				$class .= 'off' === $show_pagination ? ' et_pb_slider_no_pagination' : '';
				$class .= 'on' === $parallax ? ' et_pb_slider_parallax' : '';
				$class .= 'on' === $auto ? ' et_slider_auto et_slider_speed_' . esc_attr( $auto_speed ) : '';
				$class .= 'on' === $auto_ignore_hover ? ' et_slider_auto_ignore_hover' : '';
				$class .= 'on' === $remove_inner_shadow ? ' et_pb_slider_no_shadow' : '';
				$class .= 'on' === $show_image_video_mobile ? ' et_pb_slider_show_image' : '';

				$output = sprintf(
					'<div%4$s class="et_pb_module et_pb_slider%1$s%3$s%5$s">
				<div class="et_pb_slides et_pb_asu_slides">
					%2$s
				</div> <!-- .et_pb_slides -->
			</div> <!-- .et_pb_slider -->
			',
					$class,
					$content,
					( $et_pb_slider_has_video ? ' et_pb_preload' : '' ),
					( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
					( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
				);

				return $output;
			}
		}
		$asu_et_builder_module_slider = new ASU_ET_Builder_Module_Slider();
		remove_shortcode( 'et_pb_slider' );
		add_shortcode( 'et_pb_slider', array( $asu_et_builder_module_slider, '_shortcode_callback' ) );

		class ASU_ET_Builder_Module_Slide extends ET_Builder_Module {
			function init() {
				$this->name                     = esc_html__( 'Slide', 'et_builder' );
				$this->slug                     = 'et_pb_slide';
				$this->type                     = 'child';
				$this->child_title_var          = 'admin_title';
				$this->child_title_fallback_var = 'heading';

				$this->whitelisted_fields = array(
					'heading',
					'admin_title',
					'button_text',
					'button_link',
					'button_type',
					'button_color',
					'background_image',
					'background_position',
					'background_size',
					'background_color',
					'image',
					'alignment',
					'video_url',
					'image_alt',
					'background_layout',
					'video_bg_mp4',
					'video_bg_webm',
					'video_bg_width',
					'video_bg_height',
					'allow_player_pause',
					'content_new',
					'arrows_custom_color',
					'dot_nav_custom_color',
					'use_bg_overlay',
					'use_text_overlay',
					'bg_overlay_color',
					'text_overlay_color',
					'text_border_radius',
				);

				$this->fields_defaults = array(
					'button_link'         => array( '#' ),
					'button_type'         => array( 'small'),
					'button_color'        => array( 'primary'),
					'background_position' => array( 'default' ),
					'background_size'     => array( 'default' ),
					'background_color'    => array( '#ffffff', 'only_default_setting' ),
					'alignment'           => array( 'center' ),
					'background_layout'   => array( 'dark' ),
					'allow_player_pause'  => array( 'off' ),
				);

				$this->advanced_setting_title_text = esc_html__( 'New Slide', 'et_builder' );
				$this->settings_text               = esc_html__( 'Slide Settings', 'et_builder' );
				$this->main_css_element            = '%%order_class%%';
				$this->advanced_options            = array(
					'fonts'  => array(
						'header' => array(
							'label'       => esc_html__( 'Header', 'et_builder' ),
							'css'         => array(
								'main'      => ".et_pb_slider {$this->main_css_element} .et_pb_slide_description .et_pb_slide_title",
								'important' => 'all',
							),
							'line_height' => array(
								'range_settings' => array(
									'min'  => '1',
									'max'  => '100',
									'step' => '0.1',
								),
							),
						),
						'body'   => array(
							'label'       => esc_html__( 'Body', 'et_builder' ),
							'css'         => array(
								'main'        => "{$this->main_css_element} .et_pb_slide_content",
								'line_height' => "{$this->main_css_element} p",
								'important'   => 'all',
							),
							'line_height' => array(
								'range_settings' => array(
									'min'  => '1',
									'max'  => '100',
									'step' => '0.1',
								),
							),
						),
					),
					'button' => array(
						'button' => array(
							'label' => esc_html__( 'Button', 'et_builder' ),
							'css'   => array(
								'main' => ".et_pb_slider {$this->main_css_element}.et_pb_slide .btn",
								//.et_pb_button
							),
						),
					),
				);

				$this->custom_css_options = array(
					'slide_title'       => array(
						'label'    => esc_html__( 'Slide Title', 'et_builder' ),
						'selector' => '.et_pb_slide_description h2',
					),
					'slide_description' => array(
						'label'    => esc_html__( 'Slide Description', 'et_builder' ),
						'selector' => '.et_pb_slide_description',
					),
					'slide_button'      => array(
						'label'    => esc_html__( 'Slide Button', 'et_builder' ),
						'selector' => 'a.et_pb_more_button',
					),
					'slide_image'       => array(
						'label'    => esc_html__( 'Slide Image', 'et_builder' ),
						'selector' => '.et_pb_slide_image',
					),
				);
			}

			function get_fields() {
				$fields = array(
					'heading'              => array(
						'label'           => esc_html__( 'Heading', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'Define the title text for your slide.', 'et_builder' ),
					),
					'button_text'          => array(
						'label'           => esc_html__( 'Button Text', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'Define the text for the slide button', 'et_builder' ),
					),
					'button_link'          => array(
						'label'           => esc_html__( 'Button URL', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'Input a destination URL for the slide button.', 'et_builder' ),
					),
					'background_image'     => array(
						'label'              => esc_html__( 'Background Image', 'et_builder' ),
						'type'               => 'upload',
						'option_category'    => 'basic_option',
						'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
						'choose_text'        => esc_attr__( 'Choose a Background Image', 'et_builder' ),
						'update_text'        => esc_attr__( 'Set As Background', 'et_builder' ),
						'description'        => esc_html__( 'If defined, this image will be used as the background for this module. To remove a background image, simply delete the URL from the settings field. Image size should be at least 1170px wide', 'et_builder' ),
					),
					'background_position'  => array(
						'label'           => esc_html__( 'Background Image Position', 'et_builder' ),
						'type'            => 'select',
						'option_category' => 'layout',
						'options'         => array(
							'default'       => esc_html__( 'Default', 'et_builder' ),
							'center'        => esc_html__( 'Center', 'et_builder' ),
							'top_left'      => esc_html__( 'Top Left', 'et_builder' ),
							'top_center'    => esc_html__( 'Top Center', 'et_builder' ),
							'top_right'     => esc_html__( 'Top Right', 'et_builder' ),
							'center_right'  => esc_html__( 'Center Right', 'et_builder' ),
							'center_left'   => esc_html__( 'Center Left', 'et_builder' ),
							'bottom_left'   => esc_html__( 'Bottom Left', 'et_builder' ),
							'bottom_center' => esc_html__( 'Bottom Center', 'et_builder' ),
							'bottom_right'  => esc_html__( 'Bottom Right', 'et_builder' ),
						),
					),
					'background_size'      => array(
						'label'           => esc_html__( 'Background Image Size', 'et_builder' ),
						'type'            => 'select',
						'option_category' => 'layout',
						'options'         => array(
							'default' => esc_html__( 'Default', 'et_builder' ),
							'cover'   => esc_html__( 'Cover', 'et_builder' ),
							'contain' => esc_html__( 'Fit', 'et_builder' ),
							'initial' => esc_html__( 'Actual Size', 'et_builder' ),
						),
					),
					'background_color'     => array(
						'label'       => esc_html__( 'Background Color', 'et_builder' ),
						'type'        => 'color-alpha',
						'description' => esc_html__( 'Use the color picker to choose a background color for this module.', 'et_builder' ),
					),
					'image'                => array(
						'label'              => esc_html__( 'Slide Image', 'et_builder' ),
						'type'               => 'upload',
						'option_category'    => 'configuration',
						'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
						'choose_text'        => esc_attr__( 'Choose a Slide Image', 'et_builder' ),
						'update_text'        => esc_attr__( 'Set As Slide Image', 'et_builder' ),
						'description'        => esc_html__( 'If defined, this slide image will appear to the left of your slide text. Upload an image, or leave blank for a text-only slide.', 'et_builder' ),
					),
					'use_bg_overlay'       => array(
						'label'           => esc_html__( 'Use Background Overlay', 'et_builder' ),
						'type'            => 'yes_no_button',
						'option_category' => 'configuration',
						'options'         => array(
							'off' => esc_html__( 'No', 'et_builder' ),
							'on'  => esc_html__( 'yes', 'et_builder' ),
						),
						'affects'         => array(
							'#et_pb_bg_overlay_color',
						),
						'description'     => esc_html__( 'When enabled, a custom overlay color will be added above your background image and behind your slider content.', 'et_builder' ),
					),
					'bg_overlay_color'     => array(
						'label'           => esc_html__( 'Background Overlay Color', 'et_builder' ),
						'type'            => 'color-alpha',
						'custom_color'    => true,
						'depends_show_if' => 'on',
						'description'     => esc_html__( 'Use the color picker to choose a color for the background overlay.', 'et_builder' ),
					),
					'use_text_overlay'     => array(
						'label'           => esc_html__( 'Use Text Overlay', 'et_builder' ),
						'type'            => 'yes_no_button',
						'option_category' => 'configuration',
						'options'         => array(
							'off' => esc_html__( 'No', 'et_builder' ),
							'on'  => esc_html__( 'yes', 'et_builder' ),
						),
						'affects'         => array(
							'#et_pb_text_overlay_color',
						),
						'description'     => esc_html__( 'When enabled, a background color is added behind the slider text to make it more readable atop background images.', 'et_builder' ),
					),
					'text_overlay_color'   => array(
						'label'           => esc_html__( 'Text Overlay Color', 'et_builder' ),
						'type'            => 'color-alpha',
						'custom_color'    => true,
						'depends_show_if' => 'on',
						'description'     => esc_html__( 'Use the color picker to choose a color for the text overlay.', 'et_builder' ),
					),
					'alignment'            => array(
						'label'           => esc_html__( 'Slide Image Vertical Alignment', 'et_builder' ),
						'type'            => 'select',
						'option_category' => 'layout',
						'options'         => array(
							'center' => esc_html__( 'Center', 'et_builder' ),
							'bottom' => esc_html__( 'Bottom', 'et_builder' ),
						),
						'description'     => esc_html__( 'This setting determines the vertical alignment of your slide image. Your image can either be vertically centered, or aligned to the bottom of your slide.', 'et_builder' ),
					),
					'video_url'            => array(
						'label'           => esc_html__( 'Slide Video', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'If defined, this video will appear to the left of your slide text. Enter youtube or vimeo page url, or leave blank for a text-only slide.', 'et_builder' ),
					),
					'image_alt'            => array(
						'label'           => esc_html__( 'Image Alternative Text', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'If you have a slide image defined, input your HTML ALT text for the image here.', 'et_builder' ),
					),
					'background_layout'    => array(
						'label'           => esc_html__( 'Text Color', 'et_builder' ),
						'type'            => 'select',
						'option_category' => 'color_option',
						'options'         => array(
							'dark'  => esc_html__( 'Light', 'et_builder' ),
							'light' => esc_html__( 'Dark', 'et_builder' ),
						),
						'description'     => esc_html__( 'Here you can choose whether your text is light or dark. If you have a slide with a dark background, then choose light text. If you have a light background, then use dark text.', 'et_builder' ),
					),
					'video_bg_mp4'         => array(
						'label'              => esc_html__( 'Background Video MP4', 'et_builder' ),
						'type'               => 'upload',
						'option_category'    => 'basic_option',
						'data_type'          => 'video',
						'upload_button_text' => esc_attr__( 'Upload a video', 'et_builder' ),
						'choose_text'        => esc_attr__( 'Choose a Background Video MP4 File', 'et_builder' ),
						'update_text'        => esc_attr__( 'Set As Background Video', 'et_builder' ),
						'description'        => et_get_safe_localization( __( 'All videos should be uploaded in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers. Upload the .MP4 version here. <b>Important Note: Video backgrounds are disabled from mobile devices. Instead, your background image will be used. For this reason, you should define both a background image and a background video to ensure best results.</b>', 'et_builder' ) ),
					),
					'video_bg_webm'        => array(
						'label'              => esc_html__( 'Background Video Webm', 'et_builder' ),
						'type'               => 'upload',
						'option_category'    => 'basic_option',
						'data_type'          => 'video',
						'upload_button_text' => esc_attr__( 'Upload a video', 'et_builder' ),
						'choose_text'        => esc_attr__( 'Choose a Background Video WEBM File', 'et_builder' ),
						'update_text'        => esc_attr__( 'Set As Background Video', 'et_builder' ),
						'description'        => et_get_safe_localization( __( 'All videos should be uploaded in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers. Upload the .WEBM version here. <b>Important Note: Video backgrounds are disabled from mobile devices. Instead, your background image will be used. For this reason, you should define both a background image and a background video to ensure best results.</b>', 'et_builder' ) ),
					),
					'video_bg_width'       => array(
						'label'           => esc_html__( 'Background Video Width', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'In order for videos to be sized correctly, you must input the exact width (in pixels) of your video here.', 'et_builder' ),
					),
					'video_bg_height'      => array(
						'label'           => esc_html__( 'Background Video Height', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'In order for videos to be sized correctly, you must input the exact height (in pixels) of your video here.', 'et_builder' ),
					),
					'allow_player_pause'   => array(
						'label'           => esc_html__( 'Pause Video', 'et_builder' ),
						'type'            => 'yes_no_button',
						'option_category' => 'configuration',
						'options'         => array(
							'off' => esc_html__( 'No', 'et_builder' ),
							'on'  => esc_html__( 'Yes', 'et_builder' ),
						),
						'description'     => esc_html__( 'Allow video to be paused by other players when they begin playing', 'et_builder' ),
					),
					'content_new'          => array(
						'label'           => esc_html__( 'Content', 'et_builder' ),
						'type'            => 'tiny_mce',
						'option_category' => 'basic_option',
						'description'     => esc_html__( 'Input your main slide text content here.', 'et_builder' ),
					),
					'arrows_custom_color'  => array(
						'label'        => esc_html__( 'Arrows Custom Color', 'et_builder' ),
						'type'         => 'color',
						'custom_color' => true,
						'tab_slug'     => 'advanced',
					),
					'dot_nav_custom_color' => array(
						'label'        => esc_html__( 'Dot Nav Custom Color', 'et_builder' ),
						'type'         => 'color',
						'custom_color' => true,
						'tab_slug'     => 'advanced',
					),
					'admin_title'          => array(
						'label'       => esc_html__( 'Admin Label', 'et_builder' ),
						'type'        => 'text',
						'description' => esc_html__( 'This will change the label of the slide in the builder for easy identification.', 'et_builder' ),
					),
					'text_border_radius'   => array(
						'label'           => esc_html__( 'Text Overlay Border Radius', 'et_builder' ),
						'type'            => 'range',
						'option_category' => 'layout',
						'default'         => '3',
						'range_settings'  => array(
							'min'  => '0',
							'max'  => '100',
							'step' => '1',
						),
						'tab_slug'        => 'advanced',
					),
				);
				return $fields;
			}

			function shortcode_callback( $atts, $content = null, $function_name ) {
				$alignment            = $this->shortcode_atts['alignment'];
				$heading              = $this->shortcode_atts['heading'];
				$button_text          = $this->shortcode_atts['button_text'];
				$button_link          = $this->shortcode_atts['button_link'];
				$button_type          = $this->shortcode_atts['button_type'];
				$button_color         = $this->shortcode_atts['button_color'];
				$background_color     = $this->shortcode_atts['background_color'];
				$background_image     = $this->shortcode_atts['background_image'];
				$image                = $this->shortcode_atts['image'];
				$image_alt            = $this->shortcode_atts['image_alt'];
				$background_layout    = $this->shortcode_atts['background_layout'];
				$video_bg_webm        = $this->shortcode_atts['video_bg_webm'];
				$video_bg_mp4         = $this->shortcode_atts['video_bg_mp4'];
				$video_bg_width       = $this->shortcode_atts['video_bg_width'];
				$video_bg_height      = $this->shortcode_atts['video_bg_height'];
				$video_url            = $this->shortcode_atts['video_url'];
				$allow_player_pause   = $this->shortcode_atts['allow_player_pause'];
				$dot_nav_custom_color = $this->shortcode_atts['dot_nav_custom_color'];
				$arrows_custom_color  = $this->shortcode_atts['arrows_custom_color'];
				$custom_icon          = $this->shortcode_atts['button_icon'];
				$button_custom        = $this->shortcode_atts['custom_button'];
				$background_position  = $this->shortcode_atts['background_position'];
				$background_size      = $this->shortcode_atts['background_size'];
				$use_bg_overlay       = $this->shortcode_atts['use_bg_overlay'];
				$bg_overlay_color     = $this->shortcode_atts['bg_overlay_color'];
				$use_text_overlay     = $this->shortcode_atts['use_text_overlay'];
				$text_overlay_color   = $this->shortcode_atts['text_overlay_color'];
				$text_border_radius   = $this->shortcode_atts['text_border_radius'];

				global $et_pb_slider_has_video, $et_pb_slider_parallax, $et_pb_slider_parallax_method, $et_pb_slider_hide_mobile, $et_pb_slider_custom_icon, $et_pb_slider_item_num;

				$background_video = '';

				$et_pb_slider_item_num ++;

				$hide_on_mobile_class = self::HIDE_ON_MOBILE;

				$first_video = false;

				$custom_slide_icon = 'on' === $button_custom && '' !== $custom_icon ? $custom_icon : $et_pb_slider_custom_icon;

				if ( '' !== $video_bg_mp4 || '' !== $video_bg_webm ) {
					if ( ! $et_pb_slider_has_video ) {
						$first_video = true;
					}

					$background_video = sprintf(
						'<div class="et_pb_section_video_bg%2$s%3$s">
                                            %1$s
                                    </div>',
						do_shortcode( sprintf( '
                                            <video loop="loop" autoplay="autoplay"%3$s%4$s>
                                                    %1$s
                                                    %2$s
                                            </video>',
							( '' !== $video_bg_mp4 ? sprintf( '<source type="video/mp4" src="%s" />', esc_url( $video_bg_mp4 ) ) : '' ),
							( '' !== $video_bg_webm ? sprintf( '<source type="video/webm" src="%s" />', esc_url( $video_bg_webm ) ) : '' ),
							( '' !== $video_bg_width ? sprintf( ' width="%s"', esc_attr( intval( $video_bg_width ) ) ) : '' ),
							( '' !== $video_bg_height ? sprintf( ' height="%s"', esc_attr( intval( $video_bg_height ) ) ) : '' ),
							( '' !== $background_image ? sprintf( ' poster="%s"', esc_url( $background_image ) ) : '' )
						) ),
						( $first_video ? ' et_pb_first_video' : '' ),
						( 'on' === $allow_player_pause ? ' et_pb_allow_player_pause' : '' )
					);

					$et_pb_slider_has_video = true;

					wp_enqueue_style( 'wp-mediaelement' );
					wp_enqueue_script( 'wp-mediaelement' );
				}

				if ( '' !== $heading ) {
					if ( '#' !== $button_link ) {
						$heading = sprintf( '<a href="%1$s">%2$s</a>',
							esc_url( $button_link ),
							$heading
						);
					}

					$heading = '<h1 class="et_pb_slide_title">' . $heading . '</h1>';
				}

				$button = '';
				if ( '' !== $button_text ) {

					$button_size = ' ';
					if ($button_type == 'small'){
						$button_size = ' btn-sm ';
					}
					if ($button_type == 'big'){
						$button_size = ' btn-lg ';
					}

					$button = sprintf( '<a href="%1$s" class="et_pb_more_button btn btn-%7$s%6$s%3$s%5$s"%4$s>%2$s</a>', //et_pb_button
						esc_url( $button_link ),
						esc_html( $button_text ),
						( 'on' === $et_pb_slider_hide_mobile['hide_cta_on_mobile'] ? esc_attr( " {$hide_on_mobile_class}" ) : '' ),
						'' !== $custom_slide_icon ? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $custom_slide_icon ) )
						) : '',
						'' !== $custom_slide_icon ? ' et_pb_custom_button_icon' : '',
						$button_size,
						$button_color
					);
				}

				$style = $class = '';

				if ( '' !== $background_color ) {
					$style .= sprintf( 'background-color:%s;',
						esc_attr( $background_color )
					);
				}

				if ( '' !== $background_image && 'on' !== $et_pb_slider_parallax ) {
					$style .= sprintf( 'background-image:url(%s);',
						esc_attr( $background_image )
					);
				}

				if ( 'on' === $use_bg_overlay && '' !== $bg_overlay_color ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%%.et_pb_slide .et_pb_slide_overlay_container',
						'declaration' => sprintf(
							'background-color: %1$s;',
							esc_html( $bg_overlay_color )
						),
					) );
				}

				if ( 'on' === $use_text_overlay && '' !== $text_overlay_color ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%%.et_pb_slide .et_pb_slide_title, %%order_class%%.et_pb_slide .et_pb_slide_content',
						'declaration' => sprintf(
							'background-color: %1$s;',
							esc_html( $text_overlay_color )
						),
					) );
				}

				if ( '' !== $text_border_radius ) {
					$border_radius_value = et_builder_process_range_value( $text_border_radius );
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%%.et_pb_slider_with_text_overlay h2.et_pb_slide_title',
						'declaration' => sprintf(
							'-webkit-border-top-left-radius: %1$s;
                                            -webkit-border-top-right-radius: %1$s;
                                            -moz-border-radius-topleft: %1$s;
                                            -moz-border-radius-topright: %1$s;
                                            border-top-left-radius: %1$s;
                                            border-top-right-radius: %1$s;',
							esc_html( $border_radius_value )
						),
					) );
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%%.et_pb_slider_with_text_overlay .et_pb_slide_content',
						'declaration' => sprintf(
							'-webkit-border-bottom-right-radius: %1$s;
                                            -webkit-border-bottom-left-radius: %1$s;
                                            -moz-border-radius-bottomright: %1$s;
                                            -moz-border-radius-bottomleft: %1$s;
                                            border-bottom-right-radius: %1$s;
                                            border-bottom-left-radius: %1$s;',
							esc_html( $border_radius_value )
						),
					) );
				}

				$style = '' !== $style ? " style='{$style}'" : '';

				$image = '' !== $image
					? sprintf( '<div class="et_pb_slide_image"><img src="%1$s" alt="%2$s" /></div>',
						esc_url( $image ),
						esc_attr( $image_alt )
					)
					: '';

				if ( '' !== $video_url ) {
					global $wp_embed;

					$video_embed = apply_filters( 'the_content', $wp_embed->shortcode( '', esc_url( $video_url ) ) );

					$video_embed = preg_replace( '/<embed /', '<embed wmode="transparent" ', $video_embed );
					$video_embed = preg_replace( '/<\/object>/', '<param name="wmode" value="transparent" /></object>', $video_embed );

					$image = sprintf( '<div class="et_pb_slide_video">%1$s</div>',
						$video_embed
					);
				}

				if ( '' !== $image ) {
					$class = ' et_pb_slide_with_image';
				}

				if ( '' !== $video_url ) {
					$class .= ' et_pb_slide_with_video';
				}

				$class .= " et_pb_bg_layout_{$background_layout}";

				$class .= 'on' === $use_bg_overlay ? ' et_pb_slider_with_overlay' : '';
				$class .= 'on' === $use_text_overlay ? ' et_pb_slider_with_text_overlay' : '';

				if ( 'bottom' !== $alignment ) {
					$class .= " et_pb_media_alignment_{$alignment}";
				}

				$data_dot_nav_custom_color = '' !== $dot_nav_custom_color
					? sprintf( ' data-dots_color="%1$s"', esc_attr( $dot_nav_custom_color ) )
					: '';

				$data_arrows_custom_color = '' !== $arrows_custom_color
					? sprintf( ' data-arrows_color="%1$s"', esc_attr( $arrows_custom_color ) )
					: '';

				if ( 'default' !== $background_position && 'off' === $et_pb_slider_parallax ) {
					$processed_position = str_replace( '_', ' ', $background_position );

					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '.et_pb_slider %%order_class%%',
						'declaration' => sprintf(
							'background-position: %1$s;',
							esc_html( $processed_position )
						),
					) );
				}

				if ( 'default' !== $background_size && 'off' === $et_pb_slider_parallax ) {
					ET_Builder_Module::set_style( $function_name, array(
						'selector'    => '.et_pb_slider %%order_class%%',
						'declaration' => sprintf(
							'-moz-background-size: %1$s;
                                            -webkit-background-size: %1$s;
                                            background-size: %1$s;',
							esc_html( $background_size )
						),
					) );
				}

				$class = ET_Builder_Element::add_module_order_class( $class, $function_name );

				if ( 1 === $et_pb_slider_item_num ) {
					$class .= " et-pb-active-slide";
				}

				$output = sprintf(
					'<div class="et_pb_slide%6$s"%4$s%10$s%11$s>
                                    %8$s
                                    %12$s
                                    <div class="et_pb_container clearfix">
                                            %5$s
                                            <div class="et_pb_slide_description">
                                                    %1$s
                                                    <h4 class="et_pb_slide_content%9$s">%2$s</h4>
                                                    %3$s
                                            </div> <!-- .et_pb_slide_description -->
                                    </div> <!-- .et_pb_container -->
                                    %7$s
                            </div> <!-- .et_pb_slide -->
                            ',
					$heading,
					$this->shortcode_content,
					$button,
					$style,
					$image,
					esc_attr( $class ),
					( '' !== $background_video ? $background_video : '' ),
					( '' !== $background_image && 'on' === $et_pb_slider_parallax ? sprintf( '<div class="et_parallax_bg%2$s" style="background-image: url(%1$s);"></div>', esc_attr( $background_image ), ( 'off' === $et_pb_slider_parallax_method ? ' et_pb_parallax_css' : '' ) ) : '' ),
					( 'on' === $et_pb_slider_hide_mobile['hide_content_on_mobile'] ? esc_attr( " {$hide_on_mobile_class}" ) : '' ),
					$data_dot_nav_custom_color,
					$data_arrows_custom_color,
					'on' === $use_bg_overlay ? '<div class="et_pb_slide_overlay_container"></div>' : ''
				);

				return $output;
			}
		}
		$asu_et_builder_module_slide = new ASU_ET_Builder_Module_Slide();
		remove_shortcode( 'et_pb_slide' );
		add_shortcode( 'et_pb_slide', array( $asu_et_builder_module_slide, '_shortcode_callback' ) );

		class ASU_ET_Builder_Module_Button extends ET_Builder_Module {
			function init() {
				$this->name = esc_html__( 'Button', 'et_builder' );
				$this->slug = 'et_pb_button';

				$this->whitelisted_fields = array(
					'button_url',
					'url_new_window',
					'button_text',
					'icon_button',
					'button_type',
					'button_color',
					'button_icon',
					'admin_label',
					'module_id',
					'module_class',
				);

				$this->fields_defaults = array(
					'url_new_window'    => array( 'off' ),
					'icon_button'    => array( 'off' ),
					'button_type'     => array( 'small'),
					'button_color'     => array( 'default'),
					'button_icon'      => array( 'download' ),
				);

				$this->main_css_element = '%%order_class%%';
			}

			function get_fields() {
				$fields = array();
				return $fields;
			}

			function shortcode_callback( $atts, $content = null, $function_name ) {
				$id         = $this->shortcode_atts['module_id'];
				$class      = $this->shortcode_atts['module_class'];
				$link       = $this->shortcode_atts['button_url'];
				$content    = $this->shortcode_atts['button_text'];
				$icon       = $this->shortcode_atts['button_icon'];
				$type       = $this->shortcode_atts['button_type'];
				$color      = $this->shortcode_atts['button_color'];
				$new_window  = $this->shortcode_atts['url_new_window'];
				$is_icon    = $this->shortcode_atts['icon_button'];

				if ( $is_icon == 'on' ) $type = 'icon';

				$output = '';
				$target = ($new_window == 'on') ? ' target="_blank"' : '';

				$content = et_content_helper($content);

				$id = ($id <> '') ? " id='" . esc_attr( $id ) . "'" : '';

				if ($type == 'small')
					$output .= "<a{$id} href='" . esc_url( $link ) . "' class='" . esc_attr( "btn btn-sm btn-{$color}{$class}" ) . "'{$target}>{$content}</a>";

				if ($type == 'big')
					$output .= "<a{$id} href='" . esc_url( $link ) . "' class='" . esc_attr( "btn btn-lg btn-{$color}{$class}" ) . "'{$target}>{$content}</a>";

				if ($type == 'icon'){
					switch ($icon){
						case'question':
							$icon = 'question-circle';
							break;
						case'people':
							$icon = 'user';
							break;
						case'mail':
							$icon = 'envelope-square';
							break;
						case'paper':
							$icon = 'pencil-square-o';
							break;
						case 'notice':
							$icon = 'warning';
							break;
						case 'stats':
							$icon = 'pie-chart';
							break;
						case 'rss':
							$icon = 'rss-square';
							break;
					}
					$output .= "<a{$id} href='" . esc_url( $link ) . "' class='" . esc_attr( "icon-button {$icon}-icon{$class}" ) . "'{$target}><span class='fa fa-{$icon}'></span>{$content}</a>";
				}

				$output = '<div class="et_pb_asu_button et_pb_module">'.$output.'</div>';

				//if ( $br == 'yes' ) $output .= '<br class="clear"/>';

				return $output;

			}
		}
		$asu_et_builder_module_button = new ASU_ET_Builder_Module_Button();
		remove_shortcode( 'et_pb_button' );
		add_shortcode( 'et_pb_button', array( $asu_et_builder_module_button, '_shortcode_callback' ) );

		class ASU_ET_Builder_Module_Team_Member extends ET_Builder_Module {
			function init() {
				$this->name = esc_html__( 'Person', 'et_builder' );
				$this->slug = 'et_pb_team_member';

				$this->whitelisted_fields = array(
					'name',
					'position',
					'image_url',
					'animation',
					'background_layout',
					'facebook_url',
					'twitter_url',
					'google_url',
					'google_scholar_url',
					'linkedin_url',
					'content_new',
					'admin_label',
					'module_id',
					'module_class',
					'icon_color',
					'icon_hover_color',
				);

				$this->fields_defaults = array(
					'animation'         => array( 'off' ),
					'background_layout' => array( 'light' ),
				);

				$this->main_css_element = '%%order_class%%.et_pb_team_member';
				$this->advanced_options = array(
					'fonts' => array(
						'header' => array(
							'label'    => esc_html__( 'Header', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} h4",
							),
						),
						'body'   => array(
							'label'    => esc_html__( 'Body', 'et_builder' ),
							'css'      => array(
								'main' => "{$this->main_css_element} *",
							),
						),
					),
					'background' => array(
						'settings' => array(
							'color' => 'alpha',
						),
					),
					'border' => array(),
					'custom_margin_padding' => array(
						'css' => array(
							'important' => 'all',
						),
					),
				);
				$this->custom_css_options = array(
					'member_image' => array(
						'label'    => esc_html__( 'Member Image', 'et_builder' ),
						'selector' => '.et_pb_team_member_image',
					),
					'member_description' => array(
						'label'    => esc_html__( 'Member Description', 'et_builder' ),
						'selector' => '.et_pb_team_member_description',
					),
					'title' => array(
						'label'    => esc_html__( 'Title', 'et_builder' ),
						'selector' => '.et_pb_team_member_description h4',
					),
					'member_position' => array(
						'label'    => esc_html__( 'Member Position', 'et_builder' ),
						'selector' => '.et_pb_member_position',
					),
					'member_social_links' => array(
						'label'    => esc_html__( 'Member Social Links', 'et_builder' ),
						'selector' => '.et_pb_member_social_links',
					),
				);
			}

			function get_fields() {
				$fields = array();
				return $fields;
			}

			function shortcode_callback( $atts, $content = null, $function_name ) {
				$module_id         = $this->shortcode_atts['module_id'];
				$module_class      = $this->shortcode_atts['module_class'];
				$name              = $this->shortcode_atts['name'];
				$position          = $this->shortcode_atts['position'];
				$image_url         = $this->shortcode_atts['image_url'];
				$animation         = $this->shortcode_atts['animation'];
				$facebook_url      = $this->shortcode_atts['facebook_url'];
				$twitter_url       = $this->shortcode_atts['twitter_url'];
				$google_url        = $this->shortcode_atts['google_url'];
				$google_scholar_url        = $this->shortcode_atts['google_scholar_url'];
				$linkedin_url      = $this->shortcode_atts['linkedin_url'];
				$background_layout = $this->shortcode_atts['background_layout'];
				$icon_color        = $this->shortcode_atts['icon_color'];
				$icon_hover_color  = $this->shortcode_atts['icon_hover_color'];

				$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

				$image = $social_links = '';

				if ( '' !== $icon_color ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .et_pb_member_social_links a',
						'declaration' => sprintf(
							'color: %1$s;',
							esc_html( $icon_color )
						),
					) );
				}

				if ( '' !== $icon_hover_color ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .et_pb_member_social_links a:hover',
						'declaration' => sprintf(
							'color: %1$s;',
							esc_html( $icon_hover_color )
						),
					) );
				}

				if ( '' !== $facebook_url ) {
					$social_links .= sprintf(
						'<li><a href="%1$s" class="et_pb_font_icon et_pb_facebook_icon"><span>%2$s</span></a></li>',
						esc_url( $facebook_url ),
						esc_html__( 'Facebook', 'et_builder' )
					);
				}

				if ( '' !== $twitter_url ) {
					$social_links .= sprintf(
						'<li><a href="%1$s" class="et_pb_font_icon et_pb_twitter_icon"><span>%2$s</span></a></li>',
						esc_url( $twitter_url ),
						esc_html__( 'Twitter', 'et_builder' )
					);
				}

				if ( '' !== $google_url ) {
					$social_links .= sprintf(
						'<li><a href="%1$s" class="et_pb_font_icon et_pb_google_icon"><span>%2$s</span></a></li>',
						esc_url( $google_url ),
						esc_html__( 'Google+', 'et_builder' )
					);
				}

				if ( '' !== $google_scholar_url ) {
					$social_links .= sprintf(
						'<li><a href="%1$s" class="et_pb_font_icon et_pb_google_scholar_icon"><span>%2$s</span></a></li>',
						esc_url( $google_scholar_url ),
						esc_html__( 'Google Scholar', 'et_builder' )
					);
				}

				if ( '' !== $linkedin_url ) {
					$social_links .= sprintf(
						'<li><a href="%1$s" class="et_pb_font_icon et_pb_linkedin_icon"><span>%2$s</span></a></li>',
						esc_url( $linkedin_url ),
						esc_html__( 'LinkedIn', 'et_builder' )
					);
				}

				if ( '' !== $social_links ) {
					$social_links = sprintf( '<ul class="et_pb_member_social_links">%1$s</ul>', $social_links );
				}

				if ( '' !== $image_url ) {
					$image = sprintf(
						'<div class="et_pb_team_member_image et-waypoint%3$s">
							<img src="%1$s" alt="%2$s" />
						</div>',
						esc_url( $image_url ),
						esc_attr( $name ),
						esc_attr( " et_pb_animation_{$animation}" )
					);
				}

				$output = sprintf(
					'<div%3$s class="et_pb_module et_pb_team_member%4$s%9$s et_pb_bg_layout_%8$s clearfix">
						%2$s
						<div class="et_pb_team_member_description">
							%5$s
							%6$s
							%1$s
							%7$s
						</div> <!-- .et_pb_team_member_description -->
					</div> <!-- .et_pb_team_member -->',
					$this->shortcode_content,
					( '' !== $image ? $image : '' ),
					( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
					( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
					( '' !== $name ? sprintf( '<h4>%1$s</h4>', esc_html( $name ) ) : '' ),
					( '' !== $position ? sprintf( '<p class="et_pb_member_position">%1$s</p>', esc_html( $position ) ) : '' ),
					$social_links,
					$background_layout,
					( '' === $image ? ' et_pb_team_member_no_image' : '' )
				);

				return $output;
			}
		}
		$asu_et_builder_module_team_member = new ASU_ET_Builder_Module_Team_Member();
		remove_shortcode( 'et_pb_team_member' );
		add_shortcode( 'et_pb_team_member', array( $asu_et_builder_module_team_member, '_shortcode_callback' ) );
	}
}
add_action( 'et_builder_ready', 'asu_divi_child_theme_setup' );

function asu_et_pb_asu_fullwidth_slider_get_fields (){
	$fields = array(
		'show_arrows' => array(
			'label'           => esc_html__( 'Arrows', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'configuration',
			'options'         => array(
				'on'  => esc_html__( 'Show Arrows', 'et_builder' ),
				'off' => esc_html__( 'Hide Arrows', 'et_builder' ),
			),
			'description'        => esc_html__( 'This setting allows you to turn the navigation arrows on or off.', 'et_builder' ),
		),
		'show_pagination' => array(
			'label'           => esc_html__( 'Controls', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'configuration',
			'options'         => array(
				'on'  => esc_html__( 'Show Slider Controls', 'et_builder' ),
				'off' => esc_html__( 'Hide Slider Controls', 'et_builder' ),
			),
			'description'        => esc_html__( 'Disabling this option will remove the circle button at the bottom of the slider.', 'et_builder' ),
		),
		'auto' => array(
			'label'             => esc_html__( 'Automatic Animation', 'et_builder' ),
			'type'              => 'yes_no_button',
			'option_category'   => 'configuration',
			'options'           => array(
				'off'  => esc_html__( 'Off', 'et_builder' ),
				'on' => esc_html__( 'On', 'et_builder' ),
			),
			'affects'           => array(
				'#et_pb_auto_speed, #et_pb_auto_ignore_hover',
			),
			'description'        => esc_html__( 'If you would like the slider to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
		),
		'auto_speed' => array(
			'label'             => esc_html__( 'Automatic Animation Speed (in ms)', 'et_builder' ),
			'type'              => 'text',
			'option_category'   => 'configuration',
			'depends_default'   => true,
			'description'       => esc_html__( "Here you can designate how fast the slider fades between each slide, if 'Automatic Animation' option is enabled above. The higher the number the longer the pause between each rotation.", 'et_builder' ),
		),
		'auto_ignore_hover' => array(
			'label'           => esc_html__( 'Continue Automatic Slide on Hover', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'depends_default' => true,
			'options' => array(
				'off' => esc_html__( 'Off', 'et_builder' ),
				'on'  => esc_html__( 'On', 'et_builder' ),
			),
			'description' => esc_html__( 'Turning this on will allow automatic sliding to continue on mouse hover.', 'et_builder' ),
		),
		'parallax' => array(
			'label'           => esc_html__( 'Use Parallax effect', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off'  => esc_html__( 'No', 'et_builder' ),
				'on' => esc_html__( 'Yes', 'et_builder' ),
			),
			'affects'           => array(
				'#et_pb_parallax_method',
			),
			'description'        => esc_html__( 'If enabled, your background images will have a fixed position as your scroll, creating a fun parallax-like effect.', 'et_builder' ),
		),
		'parallax_method' => array(
			'label'           => esc_html__( 'Parallax method', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'CSS', 'et_builder' ),
				'on'  => esc_html__( 'True Parallax', 'et_builder' ),
			),
			'depends_show_if'   => 'on',
			'description'       => esc_html__( 'Define the method, used for the parallax effect.', 'et_builder' ),
		),
		'remove_inner_shadow' => array(
			'label'           => esc_html__( 'Remove Inner Shadow', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
		),
		'background_position' => array(
			'label'           => esc_html__( 'Background Image Position', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'layout',
			'options'         => array(
				'default'       => esc_html__( 'Default', 'et_builder' ),
				'top_left'      => esc_html__( 'Top Left', 'et_builder' ),
				'top_center'    => esc_html__( 'Top Center', 'et_builder' ),
				'top_right'     => esc_html__( 'Top Right', 'et_builder' ),
				'center_right'  => esc_html__( 'Center Right', 'et_builder' ),
				'center_left'   => esc_html__( 'Center Left', 'et_builder' ),
				'bottom_left'   => esc_html__( 'Bottom Left', 'et_builder' ),
				'bottom_center' => esc_html__( 'Bottom Center', 'et_builder' ),
				'bottom_right'  => esc_html__( 'Bottom Right', 'et_builder' ),
			),
			'depends_show_if'   => 'off',
		),
		'background_size' => array(
			'label'           => esc_html__( 'Background Image Size', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'layout',
			'options'         => array(
				'default' => esc_html__( 'Default', 'et_builder' ),
				'contain' => esc_html__( 'Fit', 'et_builder' ),
				'initial' => esc_html__( 'Actual Size', 'et_builder' ),
			),
			'depends_show_if'   => 'off',
		),
		'top_padding' => array(
			'label'           => esc_html__( 'Top Padding', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'layout',
			'tab_slug'        => 'advanced',
			'mobile_options'  => true,
			'validate_unit'   => true,
		),
		'bottom_padding' => array(
			'label'           => esc_html__( 'Bottom Padding', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'layout',
			'tab_slug'        => 'advanced',
			'mobile_options'  => true,
			'validate_unit'   => true,
		),
		'top_padding_tablet' => array(
			'type' => 'skip',
		),
		'top_padding_phone' => array(
			'type' => 'skip',
		),
		'bottom_padding_tablet' => array(
			'type' => 'skip',
		),
		'bottom_padding_phone' => array(
			'type' => 'skip',
		),
		'hide_content_on_mobile' => array(
			'label'           => esc_html__( 'Hide Content On Mobile', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'layout',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'tab_slug'          => 'advanced',
		),
		'hide_cta_on_mobile' => array(
			'label'           => esc_html__( 'Hide CTA On Mobile', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'layout',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'tab_slug'          => 'advanced',
		),
		'show_image_video_mobile' => array(
			'label'            => esc_html__( 'Show Image / Video On Mobile', 'et_builder' ),
			'type'             => 'yes_no_button',
			'option_category'  => 'layout',
			'options'          => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'tab_slug'          => 'advanced',
		),
		'disabled_on' => array(
			'label'           => esc_html__( 'Disable on', 'et_builder' ),
			'type'            => 'multiple_checkboxes',
			'options'         => array(
				'phone'   => esc_html__( 'Phone', 'et_builder' ),
				'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
				'desktop' => esc_html__( 'Desktop', 'et_builder' ),
			),
			'additional_att'  => 'disable_on',
			'option_category' => 'configuration',
			'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
		),
		'admin_label' => array(
			'label'       => esc_html__( 'Admin Label', 'et_builder' ),
			'type'        => 'text',
			'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
		),
		'module_id' => array(
			'label'           => esc_html__( 'CSS ID', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'tab_slug'        => 'custom_css',
			'option_class'    => 'et_pb_custom_css_regular',
		),
		'module_class' => array(
			'label'           => esc_html__( 'CSS Class', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'tab_slug'        => 'custom_css',
			'option_class'    => 'et_pb_custom_css_regular',
		),
	);
	return $fields;
}
add_filter( 'et_builder_module_fields_et_pb_fullwidth_slider', 'asu_et_pb_asu_fullwidth_slider_get_fields' );

function asu_et_pb_asu_slider_get_fields (){
	$fields = array(
		'show_arrows'         => array(
			'label'           => esc_html__( 'Arrows', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'configuration',
			'options'         => array(
				'on'  => esc_html__( 'Show Arrows', 'et_builder' ),
				'off' => esc_html__( 'Hide Arrows', 'et_builder' ),
			),
			'description'     => esc_html__( 'This setting will turn on and off the navigation arrows.', 'et_builder' ),
		),
		'show_pagination' => array(
			'label'             => esc_html__( 'Show Controls', 'et_builder' ),
			'type'              => 'yes_no_button',
			'option_category'   => 'configuration',
			'options'           => array(
				'on'  => esc_html__( 'Yes', 'et_builder' ),
				'off' => esc_html__( 'No', 'et_builder' ),
			),
			'description'       => esc_html__( 'This setting will turn on and off the circle buttons at the bottom of the slider.', 'et_builder' ),
		),
		'auto' => array(
			'label'           => esc_html__( 'Automatic Animation', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'Off', 'et_builder' ),
				'on'  => esc_html__( 'On', 'et_builder' ),
			),
			'affects' => array(
				'#et_pb_auto_speed, #et_pb_auto_ignore_hover',
			),
			'description'        => esc_html__( 'If you would like the slider to slide automatically, without the visitor having to click the next button, enable this option and then adjust the rotation speed below if desired.', 'et_builder' ),
		),
		'auto_speed' => array(
			'label'             => esc_html__( 'Automatic Animation Speed (in ms)', 'et_builder' ),
			'type'              => 'text',
			'option_category'   => 'configuration',
			'depends_default'   => true,
			'description'       => esc_html__( "Here you can designate how fast the slider fades between each slide, if 'Automatic Animation' option is enabled above. The higher the number the longer the pause between each rotation.", 'et_builder' ),
		),
		'auto_ignore_hover' => array(
			'label'           => esc_html__( 'Continue Automatic Slide on Hover', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'depends_default' => true,
			'options'         => array(
				'off' => esc_html__( 'Off', 'et_builder' ),
				'on'  => esc_html__( 'On', 'et_builder' ),
			),
			'description' => esc_html__( 'Turning this on will allow automatic sliding to continue on mouse hover.', 'et_builder' ),
		),
		'parallax' => array(
			'label'           => esc_html__( 'Use Parallax effect', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'affects'           => array(
				'#et_pb_parallax_method',
				'#et_pb_background_position',
				'#et_pb_background_size',
			),
			'description'        => esc_html__( 'Enabling this option will give your background images a fixed position as you scroll.', 'et_builder' ),
		),
		'parallax_method' => array(
			'label'           => esc_html__( 'Parallax method', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'CSS', 'et_builder' ),
				'on'  => esc_html__( 'True Parallax', 'et_builder' ),
			),
			'depends_show_if'   => 'on',
			'description'       => esc_html__( 'Define the method, used for the parallax effect.', 'et_builder' ),
		),
		'remove_inner_shadow' => array(
			'label'           => esc_html__( 'Remove Inner Shadow', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
		),
		'background_position' => array(
			'label'           => esc_html__( 'Background Image Position', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'layout',
			'options' => array(
				'default'       => esc_html__( 'Default', 'et_builder' ),
				'top_left'      => esc_html__( 'Top Left', 'et_builder' ),
				'top_center'    => esc_html__( 'Top Center', 'et_builder' ),
				'top_right'     => esc_html__( 'Top Right', 'et_builder' ),
				'center_right'  => esc_html__( 'Center Right', 'et_builder' ),
				'center_left'   => esc_html__( 'Center Left', 'et_builder' ),
				'bottom_left'   => esc_html__( 'Bottom Left', 'et_builder' ),
				'bottom_center' => esc_html__( 'Bottom Center', 'et_builder' ),
				'bottom_right'  => esc_html__( 'Bottom Right', 'et_builder' ),
			),
			'depends_show_if'   => 'off',
		),
		'background_size' => array(
			'label'           => esc_html__( 'Background Image Size', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'layout',
			'options'         => array(
				'default' => esc_html__( 'Default', 'et_builder' ),
				'contain' => esc_html__( 'Fit', 'et_builder' ),
				'initial' => esc_html__( 'Actual Size', 'et_builder' ),
			),
			'depends_show_if'   => 'off',
		),
		'top_padding' => array(
			'label'           => esc_html__( 'Top Padding', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'layout',
			'tab_slug'        => 'advanced',
			'mobile_options'  => true,
			'validate_unit'   => true,
		),
		'bottom_padding' => array(
			'label'           => esc_html__( 'Bottom Padding', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'layout',
			'tab_slug'        => 'advanced',
			'mobile_options'  => true,
			'validate_unit'   => true,
		),
		'hide_content_on_mobile' => array(
			'label'           => esc_html__( 'Hide Content On Mobile', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'layout',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'tab_slug'          => 'advanced',
		),
		'hide_cta_on_mobile' => array(
			'label'           => esc_html__( 'Hide CTA On Mobile', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'layout',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'tab_slug'          => 'advanced',
		),
		'show_image_video_mobile' => array(
			'label'           => esc_html__( 'Show Image / Video On Mobile', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'layout',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'tab_slug'        => 'advanced',
		),
		'top_padding_tablet' => array(
			'type' => 'skip',
		),
		'top_padding_phone' => array(
			'type' => 'skip',
		),
		'bottom_padding_tablet' => array(
			'type' => 'skip',
		),
		'bottom_padding_phone' => array(
			'type' => 'skip',
		),
		'disabled_on' => array(
			'label'           => esc_html__( 'Disable on', 'et_builder' ),
			'type'            => 'multiple_checkboxes',
			'options'         => array(
				'phone'   => esc_html__( 'Phone', 'et_builder' ),
				'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
				'desktop' => esc_html__( 'Desktop', 'et_builder' ),
			),
			'additional_att'  => 'disable_on',
			'option_category' => 'configuration',
			'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
		),
		'admin_label' => array(
			'label'       => esc_html__( 'Admin Label', 'et_builder' ),
			'type'        => 'text',
			'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
		),
		'module_id' => array(
			'label'           => esc_html__( 'CSS ID', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'tab_slug'        => 'custom_css',
			'option_class'    => 'et_pb_custom_css_regular',
		),
		'module_class' => array(
			'label'           => esc_html__( 'CSS Class', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'tab_slug'        => 'custom_css',
			'option_class'    => 'et_pb_custom_css_regular',
		),
	);
	return $fields;
}
add_filter( 'et_builder_module_fields_et_pb_slider', 'asu_et_pb_asu_slider_get_fields' );

function asu_et_pb_asu_slide_get_fields (){
	$fields = array(
		'heading'              => array(
			'label'           => esc_html__( 'Heading', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Define the title text for your slide.', 'et_builder' ),
		),
		'button_text'          => array(
			'label'           => esc_html__( 'Button Text', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Define the text for the slide button', 'et_builder' ),
		),
		'button_link'          => array(
			'label'           => esc_html__( 'Button URL', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input a destination URL for the slide button.', 'et_builder' ),
		),
		'button_type' => array(
			'label'           => esc_html__( 'Button type', 'et_builder' ),
			'type'            => 'select',
			'class'               => array( 'et_pb_button_type' ),
			'option_category' => 'basic_option',
			'options'         => array(
				'small'   => esc_html__( 'Small Button', 'et_builder' ),
				'big'   => esc_html__( 'Large Button', 'et_builder' ),
			),
			'description'     => esc_html__( 'Here you can define button type', 'et_builder' ),
		),
		'button_color' => array(
			'label'           => esc_html__( 'Button color', 'et_builder' ),
			'type'            => 'select',
			'class'           => array( 'et_pb_button_color' ),
			'option_category' => 'basic_option',
			'options'         => array(
				'primary'     => esc_html__( 'Maroon', 'et_builder' ),
				'gold'        => esc_html__( 'Gold', 'et_builder' ),
				'secondary'   => esc_html__( 'Light Grey', 'et_builder' ),
				'blue'        => esc_html__( 'ASU Blue', 'et_builder' ),
				'success'     => esc_html__( 'ASU Green', 'et_builder' ),
				'danger'      => esc_html__( 'ASU Orange', 'et_builder' ),
				'grey'     	  => esc_html__( 'ASU Grey', 'et_builder' ),
				/*'default'     => esc_html__( 'ASU Grey', 'et_builder' ),*/
			),
			'description'     => esc_html__( 'Here you can define the color of the button', 'et_builder' ),
		),
		'background_image'     => array(
			'label'              => esc_html__( 'Background Image', 'et_builder' ),
			'type'               => 'upload',
			'option_category'    => 'basic_option',
			'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
			'choose_text'        => esc_attr__( 'Choose a Background Image', 'et_builder' ),
			'update_text'        => esc_attr__( 'Set As Background', 'et_builder' ),
			'description'        => esc_html__( 'If defined, this image will be used as the background for this module. To remove a background image, simply delete the URL from the settings field. Image size should be at least 1170px wide', 'et_builder' ),
		),
		'background_position'  => array(
			'label'           => esc_html__( 'Background Image Position', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'layout',
			'options'         => array(
				'default'       => esc_html__( 'Default', 'et_builder' ),
				'center'        => esc_html__( 'Center', 'et_builder' ),
				'top_left'      => esc_html__( 'Top Left', 'et_builder' ),
				'top_center'    => esc_html__( 'Top Center', 'et_builder' ),
				'top_right'     => esc_html__( 'Top Right', 'et_builder' ),
				'center_right'  => esc_html__( 'Center Right', 'et_builder' ),
				'center_left'   => esc_html__( 'Center Left', 'et_builder' ),
				'bottom_left'   => esc_html__( 'Bottom Left', 'et_builder' ),
				'bottom_center' => esc_html__( 'Bottom Center', 'et_builder' ),
				'bottom_right'  => esc_html__( 'Bottom Right', 'et_builder' ),
			),
		),
		'background_size'      => array(
			'label'           => esc_html__( 'Background Image Size', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'layout',
			'options'         => array(
				'default' => esc_html__( 'Default', 'et_builder' ),
				'cover'   => esc_html__( 'Cover', 'et_builder' ),
				'contain' => esc_html__( 'Fit', 'et_builder' ),
				'initial' => esc_html__( 'Actual Size', 'et_builder' ),
			),
		),
		'background_color'     => array(
			'label'       => esc_html__( 'Background Color', 'et_builder' ),
			'type'        => 'color-alpha',
			'description' => esc_html__( 'Use the color picker to choose a background color for this module.', 'et_builder' ),
		),
		'image'                => array(
			'label'              => esc_html__( 'Slide Image', 'et_builder' ),
			'type'               => 'upload',
			'option_category'    => 'configuration',
			'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
			'choose_text'        => esc_attr__( 'Choose a Slide Image', 'et_builder' ),
			'update_text'        => esc_attr__( 'Set As Slide Image', 'et_builder' ),
			'description'        => esc_html__( 'If defined, this slide image will appear to the left of your slide text. Upload an image, or leave blank for a text-only slide.', 'et_builder' ),
		),
		'use_bg_overlay'       => array(
			'label'           => esc_html__( 'Use Background Overlay', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'yes', 'et_builder' ),
			),
			'affects'         => array(
				'#et_pb_bg_overlay_color',
			),
			'description'     => esc_html__( 'When enabled, a custom overlay color will be added above your background image and behind your slider content.', 'et_builder' ),
		),
		'bg_overlay_color'     => array(
			'label'           => esc_html__( 'Background Overlay Color', 'et_builder' ),
			'type'            => 'color-alpha',
			'custom_color'    => true,
			'depends_show_if' => 'on',
			'description'     => esc_html__( 'Use the color picker to choose a color for the background overlay.', 'et_builder' ),
		),
		'use_text_overlay'     => array(
			'label'           => esc_html__( 'Use Text Overlay', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'yes', 'et_builder' ),
			),
			'affects'         => array(
				'#et_pb_text_overlay_color',
			),
			'description'     => esc_html__( 'When enabled, a background color is added behind the slider text to make it more readable atop background images.', 'et_builder' ),
		),
		'text_overlay_color'   => array(
			'label'           => esc_html__( 'Text Overlay Color', 'et_builder' ),
			'type'            => 'color-alpha',
			'custom_color'    => true,
			'depends_show_if' => 'on',
			'description'     => esc_html__( 'Use the color picker to choose a color for the text overlay.', 'et_builder' ),
		),
		'alignment'            => array(
			'label'           => esc_html__( 'Slide Image Vertical Alignment', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'layout',
			'options'         => array(
				'center' => esc_html__( 'Center', 'et_builder' ),
				'bottom' => esc_html__( 'Bottom', 'et_builder' ),
			),
			'description'     => esc_html__( 'This setting determines the vertical alignment of your slide image. Your image can either be vertically centered, or aligned to the bottom of your slide.', 'et_builder' ),
		),
		'video_url'            => array(
			'label'           => esc_html__( 'Slide Video', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'If defined, this video will appear to the left of your slide text. Enter youtube or vimeo page url, or leave blank for a text-only slide.', 'et_builder' ),
		),
		'image_alt'            => array(
			'label'           => esc_html__( 'Image Alternative Text', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'If you have a slide image defined, input your HTML ALT text for the image here.', 'et_builder' ),
		),
		'background_layout'    => array(
			'label'           => esc_html__( 'Text Color', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'color_option',
			'options'         => array(
				'dark'  => esc_html__( 'Light', 'et_builder' ),
				'light' => esc_html__( 'Dark', 'et_builder' ),
			),
			'description'     => esc_html__( 'Here you can choose whether your text is light or dark. If you have a slide with a dark background, then choose light text. If you have a light background, then use dark text.', 'et_builder' ),
		),
		'video_bg_mp4'         => array(
			'label'              => esc_html__( 'Background Video MP4', 'et_builder' ),
			'type'               => 'upload',
			'option_category'    => 'basic_option',
			'data_type'          => 'video',
			'upload_button_text' => esc_attr__( 'Upload a video', 'et_builder' ),
			'choose_text'        => esc_attr__( 'Choose a Background Video MP4 File', 'et_builder' ),
			'update_text'        => esc_attr__( 'Set As Background Video', 'et_builder' ),
			'description'        => et_get_safe_localization( __( 'All videos should be uploaded in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers. Upload the .MP4 version here. <b>Important Note: Video backgrounds are disabled from mobile devices. Instead, your background image will be used. For this reason, you should define both a background image and a background video to ensure best results.</b>', 'et_builder' ) ),
		),
		'video_bg_webm'        => array(
			'label'              => esc_html__( 'Background Video Webm', 'et_builder' ),
			'type'               => 'upload',
			'option_category'    => 'basic_option',
			'data_type'          => 'video',
			'upload_button_text' => esc_attr__( 'Upload a video', 'et_builder' ),
			'choose_text'        => esc_attr__( 'Choose a Background Video WEBM File', 'et_builder' ),
			'update_text'        => esc_attr__( 'Set As Background Video', 'et_builder' ),
			'description'        => et_get_safe_localization( __( 'All videos should be uploaded in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers. Upload the .WEBM version here. <b>Important Note: Video backgrounds are disabled from mobile devices. Instead, your background image will be used. For this reason, you should define both a background image and a background video to ensure best results.</b>', 'et_builder' ) ),
		),
		'video_bg_width'       => array(
			'label'           => esc_html__( 'Background Video Width', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'In order for videos to be sized correctly, you must input the exact width (in pixels) of your video here.', 'et_builder' ),
		),
		'video_bg_height'      => array(
			'label'           => esc_html__( 'Background Video Height', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'In order for videos to be sized correctly, you must input the exact height (in pixels) of your video here.', 'et_builder' ),
		),
		'allow_player_pause'   => array(
			'label'           => esc_html__( 'Pause Video', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'description'     => esc_html__( 'Allow video to be paused by other players when they begin playing', 'et_builder' ),
		),
		'content_new'          => array(
			'label'           => esc_html__( 'Content', 'et_builder' ),
			'type'            => 'tiny_mce',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input your main slide text content here.', 'et_builder' ),
		),
		'arrows_custom_color'  => array(
			'label'        => esc_html__( 'Arrows Custom Color', 'et_builder' ),
			'type'         => 'color',
			'custom_color' => true,
			'tab_slug'     => 'advanced',
		),
		'dot_nav_custom_color' => array(
			'label'        => esc_html__( 'Dot Nav Custom Color', 'et_builder' ),
			'type'         => 'color',
			'custom_color' => true,
			'tab_slug'     => 'advanced',
		),
		'admin_title'          => array(
			'label'       => esc_html__( 'Admin Label', 'et_builder' ),
			'type'        => 'text',
			'description' => esc_html__( 'This will change the label of the slide in the builder for easy identification.', 'et_builder' ),
		),
		'text_border_radius'   => array(
			'label'           => esc_html__( 'Text Overlay Border Radius', 'et_builder' ),
			'type'            => 'range',
			'option_category' => 'layout',
			'default'         => '3',
			'range_settings'  => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'tab_slug'        => 'advanced',
		),
	);
	return $fields;
}
add_filter( 'et_builder_module_fields_et_pb_slide', 'asu_et_pb_asu_slide_get_fields' );

function asu_et_pb_asu_button_get_fields (){
	$fields = array(
		'button_url' => array(
			'label'           => esc_html__( 'Button URL', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input the destination URL for your button.', 'et_builder' ),
		),
		'url_new_window' => array(
			'label'           => esc_html__( 'Url Opens', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'In The Same Window', 'et_builder' ),
				'on'  => esc_html__( 'In The New Tab', 'et_builder' ),
			),
			'description'       => esc_html__( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
		),
		'button_text' => array(
			'label'           => esc_html__( 'Button Text', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input your desired button text.', 'et_builder' ),
		),
		'icon_button' => array(
			'label'           => esc_html__( 'Icon Button', 'et_builder' ),
			'type'            => 'yes_no_button',
			'option_category' => 'configuration',
			'options'         => array(
				'off' => esc_html__( 'No', 'et_builder' ),
				'on'  => esc_html__( 'Yes', 'et_builder' ),
			),
			'affects'     => array(
				'#et_pb_button_type',
				'#et_pb_button_color',
				'#et_pb_button_icon',
			),
			'description' => esc_html__( 'Here you can choose whether icon set below should be used.', 'et_builder' ),
		),
		'button_type' => array(
			'label'           => esc_html__( 'Button type', 'et_builder' ),
			'type'            => 'select',
			'class'               => array( 'et_pb_button_type' ),
			'option_category' => 'configuration',
			'options'         => array(
				'small'   => esc_html__( 'Small Button', 'et_builder' ),
				'big'   => esc_html__( 'Large Button', 'et_builder' ),
			),
			'description'     => esc_html__( 'Here you can define button type', 'et_builder' ),
			'depends_show_if'    => 'off',
		),

		'button_color' => array(
			'label'           => esc_html__( 'Button color', 'et_builder' ),
			'type'            => 'select',
			'class'           => array( 'et_pb_button_color' ),
			'option_category' => 'configuration',
			'options'         => array(
				'primary'     => esc_html__( 'Maroon', 'et_builder' ),
				'gold'        => esc_html__( 'Gold', 'et_builder' ),
				'secondary'   => esc_html__( 'Light Grey', 'et_builder' ),
				'blue'        => esc_html__( 'ASU Blue', 'et_builder' ),
				'success'     => esc_html__( 'ASU Green', 'et_builder' ),
				'danger'      => esc_html__( 'ASU Orange', 'et_builder' ),
				'grey'     	  => esc_html__( 'ASU Grey', 'et_builder' ),
				/*'default'     => esc_html__( 'ASU Grey', 'et_builder' ),*/
			),
			'description'     => esc_html__( 'Here you can define the color of the button', 'et_builder' ),
			'depends_show_if'    => 'off',
		),

		'button_icon' => array(
			'label'           => esc_html__( 'Button icon', 'et_builder' ),
			'type'            => 'select',
			'class'               => array( 'et_pb_button_icon' ),
			'option_category' => 'configuration',
			'options'         => array(
				'download'   => esc_html__( 'Download', 'et_builder' ),
				'search'   => esc_html__( 'Search', 'et_builder' ),
				'refresh'   => esc_html__( 'Refresh', 'et_builder' ),
				'question'   => esc_html__( 'Question', 'et_builder' ),
				'people'   => esc_html__( 'People', 'et_builder' ),
				'warning'   => esc_html__( 'Warning', 'et_builder' ),
				'mail'   => esc_html__( 'Mail', 'et_builder' ),
				'heart'   => esc_html__( 'Heart', 'et_builder' ),
				'paper'   => esc_html__( 'Paper', 'et_builder' ),
				'notice' => esc_html__( 'Notice', 'et_builder' ),
				'stats' => esc_html__( 'Stats', 'et_builder' ),
				'rss' => esc_html__( 'RSS', 'et_builder' ),

			),
			'description'     => esc_html__( 'Here you can define the alignment of Button', 'et_builder' ),
			'depends'    => true,
		),
		'admin_label' => array(
			'label'       => esc_html__( 'Admin Label', 'et_builder' ),
			'type'        => 'text',
			'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
		),
		'module_id' => array(
			'label'           => esc_html__( 'CSS ID', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'tab_slug'        => 'custom_css',
			'option_class'    => 'et_pb_custom_css_regular',
		),
		'module_class' => array(
			'label'           => esc_html__( 'CSS Class', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'tab_slug'        => 'custom_css',
			'option_class'    => 'et_pb_custom_css_regular',
		),
	);
	return $fields;
}
add_filter( 'et_builder_module_fields_et_pb_button', 'asu_et_pb_asu_button_get_fields' );

function asu_et_pb_team_member_get_fields (){
	$fields = array(
		'name' => array(
			'label'           => esc_html__( 'Name', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input the name of the person', 'et_builder' ),
		),
		'position' => array(
			'label'           => esc_html__( 'Position', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( "Input the person's position.", 'et_builder' ),
		),
		'image_url' => array(
			'label'              => esc_html__( 'Image URL', 'et_builder' ),
			'type'               => 'upload',
			'option_category'    => 'basic_option',
			'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
			'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
			'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
			'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
		),
		'animation' => array(
			'label'             => esc_html__( 'Animation', 'et_builder' ),
			'type'              => 'select',
			'option_category'   => 'configuration',
			'options'           => array(
				'off'     => esc_html__( 'No Animation', 'et_builder' ),
				'fade_in' => esc_html__( 'Fade In', 'et_builder' ),
				'left'    => esc_html__( 'Left To Right', 'et_builder' ),
				'right'   => esc_html__( 'Right To Left', 'et_builder' ),
				'top'     => esc_html__( 'Top To Bottom', 'et_builder' ),
				'bottom'  => esc_html__( 'Bottom To Top', 'et_builder' ),
			),
			'description'       => esc_html__( 'This controls the direction of the lazy-loading animation.', 'et_builder' ),
		),
		'background_layout' => array(
			'label'           => esc_html__( 'Text Color', 'et_builder' ),
			'type'            => 'select',
			'option_category' => 'color_option',
			'options'           => array(
				'light' => esc_html__( 'Dark', 'et_builder' ),
				'dark'  => esc_html__( 'Light', 'et_builder' ),
			),
			'description' => esc_html__( 'Here you can choose the value of your text. If you are working with a dark background, then your text should be set to light. If you are working with a light background, then your text should be dark.', 'et_builder' ),
		),
		'facebook_url' => array(
			'label'           => esc_html__( 'Facebook Profile Url', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input Facebook Profile Url.', 'et_builder' ),
		),
		'twitter_url' => array(
			'label'           => esc_html__( 'Twitter Profile Url', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input Twitter Profile Url', 'et_builder' ),
		),
		'google_url' => array(
			'label'           => esc_html__( 'Google+ Profile Url', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input Google+ Profile Url', 'et_builder' ),
		),
		'google_scholar_url' => array(
			'label'           => esc_html__( 'Google Scholar Url', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input Google Scholar Url', 'et_builder' ),
		),
		'linkedin_url' => array(
			'label'           => esc_html__( 'LinkedIn Profile Url', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input LinkedIn Profile Url', 'et_builder' ),
		),
		'content_new' => array(
			'label'           => esc_html__( 'Description', 'et_builder' ),
			'type'            => 'tiny_mce',
			'option_category' => 'basic_option',
			'description'     => esc_html__( 'Input the main text content for your module here.', 'et_builder' ),
		),
		'icon_color' => array(
			'label'             => esc_html__( 'Icon Color', 'et_builder' ),
			'type'              => 'color',
			'custom_color'      => true,
			'tab_slug'          => 'advanced',
		),
		'icon_hover_color' => array(
			'label'             => esc_html__( 'Icon Hover Color', 'et_builder' ),
			'type'              => 'color',
			'custom_color'      => true,
			'tab_slug'          => 'advanced',
		),
		'disabled_on' => array(
			'label'           => esc_html__( 'Disable on', 'et_builder' ),
			'type'            => 'multiple_checkboxes',
			'options'         => array(
				'phone'   => esc_html__( 'Phone', 'et_builder' ),
				'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
				'desktop' => esc_html__( 'Desktop', 'et_builder' ),
			),
			'additional_att'  => 'disable_on',
			'option_category' => 'configuration',
			'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
		),
		'admin_label' => array(
			'label'       => esc_html__( 'Admin Label', 'et_builder' ),
			'type'        => 'text',
			'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
		),
		'module_id' => array(
			'label'           => esc_html__( 'CSS ID', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'tab_slug'        => 'custom_css',
			'option_class'    => 'et_pb_custom_css_regular',
		),
		'module_class' => array(
			'label'           => esc_html__( 'CSS Class', 'et_builder' ),
			'type'            => 'text',
			'option_category' => 'configuration',
			'tab_slug'        => 'custom_css',
			'option_class'    => 'et_pb_custom_css_regular',
		),
	);
	return $fields;
}
add_filter( 'et_builder_module_fields_et_pb_team_member', 'asu_et_pb_team_member_get_fields' );

