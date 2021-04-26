<?php
/**
 * The template for displaying Archive pages.
 */
?>
<?php get_header(); ?>
	<div id="content" class="site-content">
		<main id="main" class="site-main inner">
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				</header><!-- .page-header -->
				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
					get_template_part( 'content' );
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