<?php
/**
 * The template for displaying single posts.
 */
?>
<?php get_header(); ?>
	<div id="content" class="site-content">
		<main id="main" class="site-main inner">
			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();
				get_template_part( 'content' );
				if ( is_singular( 'post' ) ) {
					// Previous/next post navigation.
					the_post_navigation( array(
						'prev_text' => '&larr; %title',
						'next_text' => '%title &rarr;'
					) );
				}
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
			?>
		</main><!-- .site-main -->
	</div><!-- .site-content -->
<?php get_footer(); ?>