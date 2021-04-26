	<?php
	$socnet = array(
		'twitter'    => __( 'Twitter', 'stork' ),
		'facebook'   => __( 'Facebook', 'stork' ),
		'googleplus' => __( 'Google+', 'stork' ),
		'pinterest'  => __( 'Pinterest', 'stork' ),
		'tumblr'     => __( 'Tumblr', 'stork' ),
		'instagram'  => __( 'Instagram', 'stork' ),
		'flickr'     => __( 'Flickr', 'stork' ),
		'linkedin'   => __( 'LinkedIn', 'stork' ),
		'dribbble'   => __( 'Dribbble', 'stork' ),
		'github'     => __( 'GitHub', 'stork' ),
		'vimeo'      => __( 'Vimeo', 'stork' ),
		'youtube'    => __( 'YouTube', 'stork' ),
		'rss'        => __( 'RSS', 'stork' )
	);
	$socnet_links = '';
	foreach ( $socnet as $key => $val ) {
		if ( jgtstork_get_option( $key ) != '' )
			$socnet_links .= '<a href="' . esc_url( jgtstork_get_option( $key ) ) . '" class="socnet-link" title="' . $val . '"><i aria-hidden="true" class="icon-' . $key . '"></i><span class="screen-reader-text">' . $val . '</span></a>';
	}
	if ( $socnet_links != '' ) { ?>
	<aside class="social-links">
		<div class="inner">
			<?php 
			if ( jgtstork_get_option( 'social_title' ) != '' )
				echo '<h3 class="social-title">' . esc_html( jgtstork_get_option( 'social_title' ) ) . '</h3>';
			echo $socnet_links; 
			?>
		</div><!-- .inner -->
	</aside><!-- .social links -->
	<?php
	} ?>
	<footer class="site-footer">
		<div class="inner">
			<?php get_sidebar(); ?>
			<div class="site-info">
				<p class="copyright">
					<?php
					if ( jgtstork_get_option( 'footer_text' ) ) :
						echo wp_kses_post( jgtstork_get_option( 'footer_text' ) );
					else :
						printf( __( '&copy; %1$s %2$s. Stork Theme by %3$s.', 'stork' ), date( 'Y' ), get_bloginfo( 'name', 'display' ), sprintf( '<a href="%s">JustGoodThemes</a>', esc_url( 'http://justgoodthemes.com/' ) ) );
					endif;
					?>
				</p>
				<a href="#" id="back-to-top" title="<?php _e( 'Back To Top', 'stork' ); ?>"><i aria-hidden="true" class="icon-chevron-up"></i></a>
			</div><!-- .site-info -->
		</div><!-- .inner -->
	</footer><!-- .site-footer -->
</div><!-- .site -->
<?php wp_footer(); ?>
</body>
</html>