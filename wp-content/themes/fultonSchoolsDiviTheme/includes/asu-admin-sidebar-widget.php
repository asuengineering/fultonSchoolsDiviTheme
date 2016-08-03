<?php
function asu_sidebar_add_divi_menu() {
	add_submenu_page( 'et_divi_options', esc_html__( 'ASU Sidebar Settings', 'Divi' ), esc_html__( 'ASU Sidebar Settings', 'Divi' ), 'manage_options', 'asu_sidebar_editor', 'asu_display_sidebar_editor' );
}

function asu_display_sidebar_editor() {
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( 'You are not allowed to perform this action.' );
	}

	if ( isset ( $_REQUEST ) && !empty ( $_REQUEST ) && isset( $_REQUEST['action'] ) && !empty ( $_REQUEST['action'] ) && $_REQUEST['action'] == 'asu_sidebar_update' ) {
		update_option( 'asu_sidebar_logo', $_REQUEST['asu_sidebar_logo'] );
		update_option( 'asu_sidebar_title', $_REQUEST['asu_sidebar_title'] );
		echo '<div id="message" class="updated fade"><p><strong>ASU sidebar settings saved.</strong></p></div>';
	}

	?>
		<div class="wrap" id="asu_sidebar_editor">
			<h2>ASU Sidebar Elements</h2>
			<form method="post" action="admin.php?page=asu_sidebar_editor" enctype="multipart/form-data">
				<input type="hidden" name="action" value="asu_sidebar_update">
				<?php wp_nonce_field('asu_display_sidebar_editor', 'asu_sidebar_editor'); ?>
				<h2>Sidebar Elements</h2>
				<p>Edit the ASU Sidebar Title and Logo.</p>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Title</th>
							<td><input id="asu_sidebar_title" type="text" name="asu_sidebar_title" value="<?php echo get_option( 'asu_sidebar_title', '' ) ?>">
								<label for="text_field">
									<span class="description">Enter the ASU Sidebar Title.</span>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">Logo</th>
							<td><textarea id="asu_sidebar_logo" name="asu_sidebar_logo" rows="4" cols="50"><?php echo get_option( 'asu_sidebar_logo', '' ) ?></textarea>
								<label for="text_field">
									<span class="description">Enter the URL for the ASU Sidebar Logo.</span>
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
add_action( 'admin_menu', 'asu_sidebar_add_divi_menu', 11 );