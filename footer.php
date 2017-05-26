<?php if ( 'on' == et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif; ?>

		<div id="main-footer" class="footer">
			<?php get_sidebar( 'footer' ); ?>

			<div id="innovation-footer">
				<div id="innovation-bar">
					<div class="innovation-status">
						<a href="https://yourfuture.asu.edu/rankings"><span>ASU is #1 in the U.S. for Innovation</span></a>
					</div>
					<div class="innovation-hidden">
						<a href="http://yourfuture.asu.edu/rankings" target="_blank" id="best-colleges-us-news-bage-icon">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/asu-web-standards/img/footer/best-colleges-us-news-badge.png" alt="Best Colleges U.S. News Most Innovative 2017">
						</a>
					</div>
				</div>
				<div class="footer-menu">
					<ul class="default">
						<li><a href="http://www.asu.edu/copyright/" id="copyright-trademark-legal-footer">Copyright &amp; Trademark</a></li>
		                <li><a href="http://www.asu.edu/accessibility/" id="accessibility-legal-footer">Accessibility</a></li>
		                <li><a href="http://www.asu.edu/privacy/" id="privacy-legal-footer">Privacy</a></li>
		                <li><a href="http://www.asu.edu/asujobs" id="jobs-legal-footer">Jobs</a></li>
		                <li><a href="https://cfo.asu.edu/emergency" id="emergency-legal-footer">Emergency</a></li>
		                <li><a href="https://contact.asu.edu/" id="contact-asu-legal-footer">Contact ASU</a></li>
					</ul>
				</div>
			</div>

		</div> <!-- #main-footer -->

	</div> <!-- #et-main-area -->

</div> <!-- #page-container -->

<?php wp_footer(); ?>
	
</body>
</html>