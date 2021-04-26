<?php
/**
 * The template for displaying 404 pages
 */
?>
<?php get_header(); ?>
	<div id="content" class="site-content">
		<main id="main" class="site-main inner">
			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( '404 - Page Not Found!', 'stork' ); ?></h1>
				</header><!-- .page-header -->
				<div class="page-content">
					<p><?php esc_html_e( 'Sorry, the page you were looking for doesn&rsquo;t exist anymore or has been moved. Maybe try one of the links below or a search?', 'stork' ); ?></p>
					<?php get_search_form(); ?>
					<h2 class="archive-list-title"><?php _e( 'Latest Posts', 'stork' ); ?></h2>
					<ul class="archive-list">
						<?php
						wp_get_archives( array(
							'type'  => 'postbypost',
							'limit' => 15
						) );
						?>
					</ul>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</main><!-- .site-main -->
	</div><!-- .site-content -->
<?php get_footer(); ?>