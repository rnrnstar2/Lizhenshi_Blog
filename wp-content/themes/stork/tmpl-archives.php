<?php
/**
 * Template Name: Archives
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
						<?php the_content(); ?>
						<h2 class="archive-list-title"><?php _e( 'Latest Posts', 'stork' ); ?></h2>
						<ul class="archive-list">
							<?php
							wp_get_archives( array(
								'type'  => 'postbypost',
								'limit' => 15
							) );
							?>
						</ul>
						<h2 class="archive-list-title"><?php _e( 'By Subject', 'stork' ); ?></h2>
						<ul class="archive-list">
							<?php
							wp_list_categories( array(
								'show_count' => 1,
								'title_li'   => ''
							) );
							?>
						</ul>
						<h2 class="archive-list-title"><?php _e( 'By Month', 'stork' ); ?></h2>
						<ul class="archive-list">
							<?php
							wp_get_archives( array(
								'type' => 'monthly'
							) );
							?>
						</ul>
					</div><!-- .entry-content -->
				</article><!-- #post -->
			<?php endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .site-content -->
<?php get_footer(); ?>