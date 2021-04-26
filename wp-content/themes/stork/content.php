<?php
/**
 * The default template for displaying content.
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php jgtstork_post_thumbnail(); ?>
	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
		<div class="entry-meta">
			<?php
			jgtstork_posted_on();
			edit_post_link( esc_html__( 'Edit', 'stork' ), '<span class="edit-link">', '</span>' );
			?>
		</div>
	</header><!-- .entry-header -->
	<div class="entry-content">
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
	<?php if ( is_single() ) : ?>
	<footer class="entry-footer">
		<?php if ( is_multi_author() && ! is_attachment() && get_the_author_meta( 'description' ) ) { ?>
		<div class="author-box">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>
			<h3 class="author-title"><?php printf( __( 'About %s', 'stork' ), get_the_author() ); ?></h3>
			<p class="author-bio"><?php the_author_meta( 'description' ); ?><br /><a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php printf( __( 'View all posts by %s &rarr;', 'stork' ), get_the_author() ); ?></a></p>
		</div><!-- .author-box -->
		<?php } ?>
		<?php jgtstork_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post -->