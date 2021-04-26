<?php
/**
 * The template for displaying Author archive pages.
 */
?>
<?php get_header(); ?>
	<div id="content" class="site-content">
		<main id="main" class="site-main inner">
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<?php
					/*
					 * Queue the first post, that way we know what author
					 * we're dealing with (if that is the case).
					 */
					the_post();
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					if ( get_the_author_meta( 'description' ) ) :
					?>
					<div class="author-box">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>
						<h3 class="author-title"><?php printf( __( 'About %s', 'stork' ), get_the_author() ); ?></h3>
						<p class="author-bio"><?php the_author_meta( 'description' ); ?></p>
					</div><!-- .author-box -->
					<?php endif; ?>
				</header><!-- .page-header -->
				<?php
				// Rewind the loop back to the beginning.
				rewind_posts();
				// Start the Loop.
				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'list' );
				endwhile;
				jgtstork_loop_navigation();
			else:
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );
			endif;
			?>
		</main><!-- .site-main -->
	</div><!-- .site-content -->
<?php get_footer(); ?>