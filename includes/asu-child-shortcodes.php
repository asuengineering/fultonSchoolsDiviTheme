<?php

function asu_et_advanced_buttons(){
	global $themename; ?>
	<script type="text/javascript">
		var defaultSettings = {},
			outputOptions = '',
			selected ='',
			content = '',
			et_quicktags_strings = {
				learn_more : "<?php esc_html_e( 'Add ET Learn more block', $themename ); ?>",
				box        : "<?php esc_html_e( 'Add ET Box', $themename ); ?>",
				button     : "<?php esc_html_e( 'Add ET Button', $themename ); ?>",
				tabs       : "<?php esc_html_e( 'Add ET Tabs', $themename ); ?>",
				author     : "<?php esc_html_e( 'Add Author Bio', $themename ); ?>",
				shortcodes : "<?php esc_html_e( 'Shortcodes', $themename ); ?>",
			};

		defaultSettings['learn_more'] = {
			caption: {
				name: '<?php esc_html_e( 'Caption', $themename ); ?>',
				defaultvalue: '<?php esc_html_e( 'Caption goes here', $themename ); ?>',
				description: '<?php esc_html_e( 'Caption title goes here', $themename ); ?>',
				type: 'text'
			},
			state: {
				name: '<?php esc_html_e( 'State', $themename ); ?>',
				defaultvalue: 'close',
				description: '<?php esc_html_e( 'Select between expanded and closed state', $themename ); ?>',
				type: 'select',
				options: 'open|close'
			},
			content: {
				name: '<?php esc_html_e( 'Content', $themename ); ?>',
				defaultvalue: '<?php esc_html_e( 'Content goes here', $themename ); ?>',
				description: '<?php esc_html_e( 'Content text or html', $themename ); ?>',
				type: 'textarea'
			}
		};

		defaultSettings['box'] = {
			type: {
				name: '<?php esc_html_e( 'Type', $themename ); ?>',
				defaultvalue: 'shadow',
				description: '<?php esc_html_e( 'Type of the box', $themename ); ?>',
				type: 'select',
				options: 'info|warning|download|bio|shadow'
			},
			content: {
				name: '<?php esc_html_e( 'Content', $themename ); ?>',
				defaultvalue: '<?php esc_html_e( 'Content goes here', $themename ); ?>',
				description: '<?php esc_html_e( 'Content text or html', $themename ); ?>',
				type: 'textarea'
			}
		};

		defaultSettings['button'] = {
			link: {
				name: '<?php esc_html_e( 'Link', $themename ); ?>',
				defaultvalue: '#',
				description: '<?php esc_html_e( 'URL', $themename ); ?>',
				type: 'text'
			},
			type: {
				name: '<?php esc_html_e( 'Type', $themename ); ?>',
				defaultvalue: 'small',
				description: '<?php esc_html_e( 'Choose button type', $themename ); ?>',
				type: 'select',
				options: 'small|big|icon'
			},
			color: {
				name: '<?php esc_html_e( 'Color', $themename ); ?>',
				defaultvalue: 'blue',
				description: '<?php esc_html_e( 'Choose button color', $themename ); ?>',
				type: 'select',
				options: 'maroon|gold|light grey|asu blue|asu green|asu orange|asu grey'
			},
			content: {
				name: '<?php esc_html_e( 'Content', $themename ); ?>',
				defaultvalue: '<?php esc_html_e( 'Link text', $themename ); ?>',
				description: '<?php esc_html_e( 'Content text or html', $themename ); ?>',
				type: 'textarea'
			},
			icon: {
				name: '<?php esc_html_e( 'Icon', $themename ); ?>',
				defaultvalue: 'download',
				description: '<?php esc_html_e( 'Used for icon button type', $themename ); ?>',
				type: 'select',
				options: 'download|search|refresh|question|people|warning|mail|heart|paper|notice|stats|rss'

			},
			newwindow: {
				name: '<?php esc_html_e( 'Open link in new window', $themename ); ?>',
				defaultvalue: 'no',
				description: '<?php esc_html_e( 'Select yes if the link should be opened in a new window', $themename ); ?>',
				type: 'select',
				options: 'yes|no'
			}
		};

		defaultSettings['tabs'] = {
			slidertype: {
				name: '<?php esc_html_e( 'Slider Type', $themename ); ?>',
				defaultvalue: 'fade',
				description: '<?php esc_html_e( 'Select Slider Type here', $themename ); ?>',
				type: 'select',
				options: 'top tabs|left tabs|simple|images'
			},
			fx: {
				name: '<?php esc_html_e( 'Effect', $themename ); ?>',
				defaultvalue: 'fade',
				description: '<?php esc_html_e( 'Select Animation Effect', $themename ); ?>',
				type: 'select',
				options: 'fade|slide'
			},
			auto: {
				name: '<?php esc_html_e( 'Auto', $themename ); ?>',
				defaultvalue: 'no',
				description: '<?php esc_html_e( 'Choose yes if you want for automatic slider animation', $themename ); ?>',
				type: 'select',
				options: 'no|yes'
			},
			autospeed: {
				name: '<?php esc_html_e( 'Auto Speed', $themename ); ?>',
				defaultvalue: '5000',
				description: '<?php esc_html_e( 'Automattic slider speed (works only if Auto is set to yes)', $themename ); ?>',
				type: 'text'
			},
			tabtext: {
				name: '<?php esc_html_e( 'Tab Text', $themename ); ?>',
				defaultvalue: '',
				description: '',
				type: 'text',
				clone: 'cloned'
			},
			tabcontent: {
				name: '<?php esc_html_e( 'Tab Content', $themename ); ?>',
				defaultvalue: '<?php esc_html_e( 'Content goes here', $themename ); ?>',
				description: '<?php esc_html_e( 'Paste image url here, if you chose "images" slider type', $themename ); ?>',
				type: 'textarea',
				clone: 'cloned'
			}
		}

		defaultSettings['author'] = {
			imageurl: {
				name: '<?php esc_html_e( 'Image Url', $themename ); ?>',
				defaultvalue: '',
				description: '<?php esc_html_e( 'Author Image URL', $themename ); ?>',
				type: 'text'
			},
			timthumb: {
				name: '<?php esc_html_e( 'Use resizing', $themename ); ?>',
				defaultvalue: 'on',
				description: '',
				type: 'select',
				options: 'on|off'
			},
			content: {
				name: '<?php esc_html_e( 'Content', $themename ); ?>',
				defaultvalue: '<?php esc_html_e( 'Content goes here', $themename ); ?>',
				description: '',
				type: 'textarea'
			}
		}

		function CustomButtonClick(tag){

			var index = tag;

				for (var index2 in defaultSettings[index]) {
					if (defaultSettings[index][index2]['clone'] === 'cloned')
						outputOptions += '<tr class="cloned">\n';
					else if (index === 'button' && index2 === 'icon')
						outputOptions += '<tr class="hidden">\n';
					else
						outputOptions += '<tr>\n';
					outputOptions += '<th><label for="et-' + index2 + '">'+ defaultSettings[index][index2]['name'] +'</label></th>\n';
					outputOptions += '<td>';

					if (defaultSettings[index][index2]['type'] === 'select') {
						var optionsArray = defaultSettings[index][index2]['options'].split('|');

						outputOptions += '\n<select name="et-'+index2+'" id="et-'+index2+'">\n';

						for (var index3 in optionsArray) {
							selected = (optionsArray[index3] === defaultSettings[index][index2]['defaultvalue']) ? ' selected="selected"' : '';
							outputOptions += '<option value="'+optionsArray[index3]+'"'+ selected +'>'+optionsArray[index3]+'</option>\n';
						}

						outputOptions += '</select>\n';
					}

					if (defaultSettings[index][index2]['type'] === 'text') {
						cloned = '';
						if (defaultSettings[index][index2]['clone'] === 'cloned') cloned = "[]";
						outputOptions += '\n<input type="text" name="et-'+index2+cloned+'" id="et-'+index2+'" value="'+defaultSettings[index][index2]['defaultvalue']+'" />\n';
					}

					if (defaultSettings[index][index2]['type'] === 'textarea') {
						cloned = '';
						if (defaultSettings[index][index2]['clone'] === 'cloned') cloned = "[]";
						outputOptions += '<textarea name="et-'+index2+cloned+'" id="et-'+index2+'" cols="40" rows="10">'+defaultSettings[index][index2]['defaultvalue']+'</textarea>';
					}

					outputOptions += '\n<br/><small>'+ defaultSettings[index][index2]['description'] +'</small>';
					outputOptions += '\n</td>';

				}


			var width = jQuery(window).width(),
				tbHeight = jQuery(window).height(),
				tbWidth = ( 720 < width ) ? 720 : width;

			tbWidth = tbWidth - 80;
			tbHeight = tbHeight - 84;

			var tbOptions = "<div id='et_shortcodes_div'><form id='et_shortcodes'><table id='shortcodes_table' class='form-table et-"+ tag +"'>";
			tbOptions += outputOptions;
			tbOptions += '</table>\n<p class="submit">\n<input type="button" id="shortcodes-submit" class="button-primary" value="Ok" name="submit" /></p>\n</form></div>';

			var form = jQuery(tbOptions);

			var table = form.find('table');
			form.appendTo('body').hide();


			if (tag === 'tabs') {
				$moreTabs = jQuery('<p><a href="#" id="et_add_more_tabs"><?php esc_html_e( '+ Add One More Tab', $themename ); ?></a></p>').appendTo('form#et_shortcodes tbody');
				$moreTabsLink = jQuery('a#et_add_more_tabs');

				$moreTabsLink.bind('click',function() {
					var clonedElements = jQuery('form#et_shortcodes .cloned');

					newElements = clonedElements.slice(0,2).clone();

					var cloneNumber = clonedElements.length,
						labelNum = cloneNumber / 2;

					newElements.each(function(index){
						if ( index === 0 ) jQuery(this).css({'border-top':'1px solid #eeeeee'});

						var label = jQuery(this).find('label').attr('for'),
							newLabel = label + labelNum;

						jQuery(this).find('label').attr('for',newLabel);
						jQuery(this).find('input, textarea').attr('id',newLabel);
					});

					newElements.appendTo('form#et_shortcodes tbody');
					$moreTabs.appendTo('form#et_shortcodes tbody');
					return false;
				});
			}


			form.find('#shortcodes-submit').click(function(){

				var shortcode = '['+tag;

				for( var index in defaultSettings[tag]) {
					var value = table.find('#et-' + index).val();
					if (index === 'content') {
						content = value;
						continue;
					}

					if (defaultSettings[tag][index]['clone'] !== undefined) {
						content = 'cloned';
						continue;
					}

					if ( value !== defaultSettings[tag][index]['defaultvalue'] )
						shortcode += ' ' + index + '="' + value + '"';

				}

				var $et_slidertype = jQuery('#et-slidertype').val();

				shortcode += '] ' + "\n";

				if (content != '') {

					if (tag === 'tabs') {

						var $et_form = jQuery('form#et_shortcodes'),
							tabsOutput = '',
							$et_slidertype = jQuery('#et-slidertype').val();

						if ($et_slidertype === 'images') {
							prefix = 'image';
							dimensions = ' width="' + jQuery('#et-imagewidth').val() + '"'+' height="' + jQuery('#et-imageheight').val() + '"';
						} else {
							prefix = '';
							dimensions = '';
						}

						tabsOutput += '['+prefix+'tabcontainer]\n';
						$et_form.find("input[name='et-tabtext[]']").each(function(){
							tabsOutput += '['+prefix+'tabtext]'+jQuery(this).val()+'[/'+prefix+'tabtext]\n';
						});
						tabsOutput += '[/'+prefix+'tabcontainer]\n';

						if ($et_slidertype === 'simple' || $et_slidertype === 'images') tabsOutput = '';

						if ($et_slidertype != 'simple' && $et_slidertype != 'images') tabsOutput += '[tabcontent]\n';
						$et_form.find("textarea[name='et-tabcontent[]']").each(function(){
							tabsOutput += '['+prefix+'tab'+dimensions+']'+jQuery(this).val()+'[/'+prefix+'tab]'+"\n";
						});

						if ($et_slidertype != 'simple' && $et_slidertype != 'images') tabsOutput += '[/tabcontent]\n';

						content = tabsOutput;
					}

					if (tag === 'author') {
						var $et_form = jQuery('form#et_shortcodes');

						imageurl = $et_form.find('#et-imageurl').val();
						timthumb = $et_form.find('#et-timthumb').val();
						content = $et_form.find('#et-content').val();

						shortcode = "[author]\n[author_image timthumb='"+timthumb+"']"+imageurl+"[/author_image]\n[author_info]"+content+"[/author_info]\n";
						content = '';
					}

					shortcode += content;
					shortcode += '[/'+tag+'] ' + "\n";
				}

				tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode + ' ');

				tb_remove();
			});

			tb_show( 'ET ' + tag + ' Shortcode', '#TB_inline?width=' + tbWidth + '&height=' + tbHeight + '&inlineId=et_shortcodes_div' );
			jQuery('#et_shortcodes_div').remove();
			outputOptions = '';
		}

		jQuery(document).ready(function(){
			var buttonTypeField = jQuery('table.et-button select#et-type');

			buttonTypeField.live('change',function() {
				var optionsSmallButton = ['maroon','gold','light grey','asu blue','asu green','asu orange','asu grey'],
					optionsBigButton = ['maroon','gold','light grey','asu blue','asu green','asu orange','asu grey'],
					options = '';
				if (jQuery(this).val() === 'big') {
					for (var i = 0; i < optionsBigButton.length; i++) {
						options += '<option value="' + optionsBigButton[i] + '">' + optionsBigButton[i] + '</option>';
					}

					if (!jQuery('select#et-icon').parents('tr.hidden').length) jQuery('select#et-icon').parents('tr').addClass('hidden');
					if (jQuery('select#et-color').parents('tr.hidden').length) jQuery('select#et-color').parents('tr').removeClass('hidden');
				}

				if (jQuery(this).val() === 'small') {
					for (var i = 0; i < optionsSmallButton.length; i++) {
						options += '<option value="' + optionsSmallButton[i] + '">' + optionsSmallButton[i] + '</option>';
					}
					if (!jQuery('select#et-icon').parents('tr.hidden').length) jQuery('select#et-icon').parents('tr').addClass('hidden');
					if (jQuery('select#et-color').parents('tr.hidden').length) jQuery('select#et-color').parents('tr').removeClass('hidden');
				}

				if (jQuery(this).val() === 'icon') {
					if (jQuery('select#et-icon').parents('tr.hidden').length) jQuery('select#et-icon').parents('tr').removeClass('hidden');

					if (!jQuery('select#et-color').parents('tr.hidden').length) jQuery('select#et-color').parents('tr').addClass('hidden');
				}

				if (options !== '') jQuery(this).parents('tbody').find('select#et-color').html(options);
			});

			var tabTypeField = jQuery('table.et-tabs select#et-slidertype');
			tabTypeField.live('change',function() {
				if (jQuery(this).val() === 'images') {
					if (!jQuery('.et-tabs #et-imagewidth').length) {
						$heightImage = jQuery('<tr><th><label for="et-imageheight"><?php esc_html_e( 'Image Height', $themename ); ?></label></th><td><input type="text" value="" id="et-imageheight" name="et-imageheight"><br><small></small></td></tr>').prependTo('form#et_shortcodes tbody');
						$widthImage = jQuery('<tr><th><label for="et-imagewidth"><?php esc_html_e( 'Image Width', $themename ); ?></label></th><td><input type="text" value="" id="et-imagewidth" name="et-imagewidth"><br><small></small></td></tr>').prependTo('form#et_shortcodes tbody');
					}

					if (typeof $heightImage != 'undefined') $heightImage.show();
					if (typeof $widthImage != 'undefined') $widthImage.show();

					jQuery('input[name^="et-tabtext"]').parents('tr.cloned').hide(); //hide tab text
				} else {
					if (typeof $heightImage != 'undefined') $heightImage.hide();
					if (typeof $widthImage != 'undefined') $widthImage.hide();

					if(jQuery(this).val() != 'simple') jQuery('input[name^="et-tabtext"]').parents('tr.cloned:hidden').show(); //show tab text
					else jQuery('input[name^="et-tabtext"]').parents('tr.cloned').hide();
				}
			});
		});
	</script>
<?php } 

function asu_et_init_shortcodes(){
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		if ( in_array(basename($_SERVER['PHP_SELF']), array('post-new.php', 'page-new.php', 'post.php', 'page.php') ) ) {
			remove_action('edit_form_advanced', 'et_advanced_buttons');
			add_action('edit_form_advanced', 'asu_et_advanced_buttons');
			remove_action('edit_page_form', 'et_advanced_buttons');
			add_action('edit_page_form', 'asu_et_advanced_buttons');
		}
	}
}
add_action('admin_init', 'asu_et_init_shortcodes', 11);

function asu_et_button($atts, $content = null) {

	extract(shortcode_atts(array(
				"link" => "#",
				"color" => "maroon",
				"type" => "small",
				"icon" => "download",
				"newwindow" => "no",
				"id" => '',
				"class" => '',
				"br" => 'no'
			), $atts, 'button'));

	$output = '';
	$target = ($newwindow == 'yes') ? ' target="_blank"' : '';


	switch ($color){
		case'maroon':
			$color = 'primary';
			break;
		case'gold':
			$color = 'gold';
			break;
		case'light grey':
			$color = 'secondary';
			break;
		case'asu blue':
			$color = 'blue';
			break;
		case'asu green':
			$color = 'success';
			break;
		case 'asu orange':
			$color = 'danger';
			break;
		case 'asu grey':
			//$color = 'default';
			$color = 'grey';
			break;
	}

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

	if ( $br == 'yes' ) $output .= '<br class="clear"/>';

	return $output;
}

function asu_child_theme_setup () {
    remove_shortcode('button');
    add_shortcode('button', 'asu_et_button');    
}
add_action( 'wp_loaded', 'asu_child_theme_setup' );
?>
