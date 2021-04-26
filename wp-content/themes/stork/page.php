<?php
/**
 * The template for displaying pages.
 */
?>
<?php get_header(); ?>
	<div id="content" class="site-content">
		<main id="main" class="site-main inner">
			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php jgtstork_post_thumbnail(); ?>
					<header class="entry-header">
						<?php
						the_title( '<h1 class="entry-title">', '</h1>' );
						edit_post_link( __( 'Edit', 'stork' ), '<div class="entry-meta">', '</div>' );
						?>
					</header><!-- .entry-header -->
					<div class="entry-content">
						<?php
						the_content();
						wp_link_pages( array(
							'before'      => '<p class="page-links"><span class="page-links-title">' . __( 'Pages:', 'stork' ) . '</span>',
							'after'       => '</p>',
							'link_before' => '<span class="page-link">',
							'link_after'  => '</span>'
						) );
						?>
					</div><!-- .entry-content -->
				</article><!-- #post -->
				<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
			?>
		</main><!-- .site-main -->
	</div><!-- .site-content -->
<?php get_footer(); ?>