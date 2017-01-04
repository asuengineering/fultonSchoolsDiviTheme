<?php
function asu_add_divi_menu() {
	add_submenu_page( 'et_divi_options', esc_html__( 'ASU Social Icons', 'Divi' ), esc_html__( 'ASU Social Icons', 'Divi' ), 'manage_options', 'asu_social_media_editor', 'asu_display_social_media_editor' );
}

function asu_display_social_media_editor() {
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( 'You are not allowed to perform this action.' );
	}

	if ( isset ( $_REQUEST ) && !empty ( $_REQUEST ) && isset( $_REQUEST['action'] ) && !empty ( $_REQUEST['action'] ) && $_REQUEST['action'] == 'asu_social_media_update' ) {
		update_option( 'asu_social_linkedin', $_REQUEST['asu_social_linkedin'] );
		update_option( 'asu_social_youtube', $_REQUEST['asu_social_youtube'] );
		update_option( 'asu_social_vimeo', $_REQUEST['asu_social_vimeo'] );
		update_option( 'asu_social_instagram', $_REQUEST['asu_social_instagram'] );
		update_option( 'asu_social_flikr', $_REQUEST['asu_social_flikr'] );
		update_option( 'asu_social_pinterest', $_REQUEST['asu_social_pinterest'] );

		echo '<div id="message" class="updated fade"><p><strong>Additional Social Media accounts saved.</strong></p></div>';
	}

	?>
		<div class="wrap" id="asu_social_media_editor">
			<h2>Additional Social Media Icons</h2>
			
			<div style="background-color:#e0e0e0; padding:.5em 1.5em;">
				<h3><strong style="color:red;">WARNING: </strong>The content on this page has been depreciated as of the release of FSDT 1.8.</h3>
				<p>Social media information entered here will continue to be embedded within the original (depreciated) <strong>ASU Footer Info</strong> widget. But the widget and this page will be deactivated in a forthcomming release of the FSDT.</p>
				<p>For a better user experience, please add the new <strong>ASU Engineering Footer Widget</strong> and the new <strong>ASU Social Media Icons</strong> widget to the footer instead.</p>
				<ul style="list-style-type: disc; padding-left:25px;">
					<li>The URL's for most popular social media channels can be added directly to the widget, instead of keeping them in a separate page.</li>
					<li>Using the new <strong>ASU Social Media Icons</strong> widget will also mean that Facebook, Twitter, RSS and Google+ information entered into the native <a href="wp-admin/admin.php?page=et_divi_options">Divi Theme Options page</a> will no longer be rendered in the theme directly.</li>
				</ul>
				<p>If you have any questions about the approach outlined above, please send a message to <a href="mailto:steve.ryan@asu.edu">Steve Ryan</a> with a request to keep this feature activated. Otherwise, it will be deleted in a future release of the FSDT. </p>
			</div>

			<form method="post" action="admin.php?page=asu_social_media_editor" enctype="multipart/form-data">
				<input type="hidden" name="action" value="asu_social_media_update">
				<?php wp_nonce_field('asu_display_social_media_editor', 'asu_social_media_editor'); ?>
				<table class="form-table">
					<tbody>
					
					<tr>
						<th scope="row">LinkedIn</th>
						<td><input id="asu_social_linkedin" type="text" name="asu_social_linkedin" placeholder="https://www.linkedin.com/" value="<?php echo get_option( 'asu_social_linkedin', '' ) ?>">
							<label for="text_field">
								<span class="description">Enter the LinkedIn url, or leave blank to ignore.</span>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">YouTube</th>
						<td><input id="asu_social_youtube" type="text" name="asu_social_youtube" placeholder="https://www.youtube.com/" value="<?php echo get_option( 'asu_social_youtube', '' ) ?>">
							<label for="text_field">
								<span class="description">Enter the YouTube url, or leave blank to ignore.</span>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">Vimeo</th>
						<td><input id="asu_social_vimeo" type="text" name="asu_social_vimeo" placeholder="https://www.vimeo.com/" value="<?php echo get_option( 'asu_social_vimeo', '' ) ?>">
							<label for="text_field">
								<span class="description">Enter the Vimeo url, or leave blank to ignore.</span>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">Instagram</th>
						<td><input id="asu_social_instagram" type="text" name="asu_social_instagram" placeholder="https://www.instagram.com/" value="<?php echo get_option( 'asu_social_instagram', '' ) ?>">
							<label for="text_field">
								<span class="description">Enter the Instagram url, or leave blank to ignore.</span>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">Flikr</th>
						<td><input id="asu_social_flikr" type="text" name="asu_social_flikr" placeholder="https://www.flikr.com/" value="<?php echo get_option( 'asu_social_flikr', '' ) ?>">
							<label for="text_field">
								<span class="description">Enter the Flikr url, or leave blank to ignore.</span>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">Pinterest</th>
						<td><input id="asu_social_pinterest" type="text" name="asu_social_pinterest" placeholder="https://www.pinterest.com/" value="<?php echo get_option( 'asu_social_pinterest', '' ) ?>">
							<label for="text_field">
								<span class="description">Enter the Pinterest url, or leave blank to ignore.</span>
							</label>
						</td>
					</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="hidden" name="tab" value="">
					<input name="Submit" type="submit" class="button-primary" value="Save Settings">
				</p>
			</form>
		</div>
	<?php
}
add_action( 'admin_menu', 'asu_add_divi_menu', 11 );

function asu_register_social_icons() {
	register_setting( 'asu_social_icons', 'asu_linkedin', 'intval' );
}
add_action( 'admin_init', 'asu_register_social_icons' );