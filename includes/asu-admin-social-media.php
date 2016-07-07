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
			<form method="post" action="admin.php?page=asu_social_media_editor" enctype="multipart/form-data">
				<input type="hidden" name="action" value="asu_social_media_update">
				<?php wp_nonce_field('asu_display_social_media_editor', 'asu_social_media_editor'); ?>
				<h2>Standard</h2>
				<p> These are fairly standard form input fields.</p>
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