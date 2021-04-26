<?php
/**
 * The template for displaying image attachments.
 */
?>
<?php get_header(); ?>
	<div id="content" class="site-content">
		<main id="main" class="site-main inner">
			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						<div class="entry-meta">
							<?php
							jgtstork_posted_on();
							edit_post_link( esc_html__( 'Edit', 'stork' ), '<span class="edit-link">', '</span>' );
							?>
						</div>
					</header><!-- .entry-header -->
					<div class="entry-content">
						<div class="entry-attachment">
							<?php echo wp_get_attachment_image( get_the_ID(), 'post-thumbnail' );?>
							<?php if ( has_excerpt() ) : ?>
							<div class="entry-caption">
								<?php the_excerpt(); ?>
							</div>
							<?php endif; ?>
						</div><!-- .entry-attachment -->
						<?php
						the_content( __( 'Read More', 'stork' ) );
						wp_link_pages( array(
							'before'      => '<p class="page-links"><span class="page-links-title">' . __( 'Pages:', 'stork' ) . '</span>',
							'after'       => '</p>',
							'link_before' => '<span class="page-link">',
							'link_after'  => '</span>'
						) );
						?>
					</div><!-- .entry-content -->
					<footer class="entry-footer">
						<?php jgtstork_entry_footer(); ?>
					</footer><!-- .entry-footer -->
				</article><!-- #post-## -->
				<?php
				// Show navigation if there is more than one attachment
				$attachments = array_values( get_children( array(
					'post_parent'    => $post->post_parent,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => 'ASC',
					'orderby'        => 'menu_order ID'
				) ) );
				if ( count( $attachments ) > 1 ) :
				?>
				<nav id="image-navigation" class="navigation image-navigation">
					<div class="nav-links">
						<div class="nav-previous"><?php previous_image_link( false, '&larr; ' . __( 'Previous Image', 'stork' ) ); ?></div>
						<div class="nav-next"><?php next_image_link( false, __( 'Next Image', 'stork' ) . ' &rarr;' ); ?></div>
					</div><!-- .nav-links -->
				</nav><!-- #image-navigation -->
				<?php
				endif;
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			endwhile;
			?>
		</main><!-- .site-main -->
	</div><!-- .site-content -->
<?php get_footer(); ?>