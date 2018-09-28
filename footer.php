<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif; ?>

		<div id="main-footer" class="footer">
			<?php get_sidebar( 'footer' ); ?>

			<!-- Begin ASU Footer -->
			<?php asuwp_load_global_footer(); ?>
			<!-- END ASU Footer -->

		</div> <!-- #main-footer -->

	</div> <!-- #et-main-area -->

</div> <!-- #page-container -->

<?php wp_footer(); ?>
	
</body>
</html>