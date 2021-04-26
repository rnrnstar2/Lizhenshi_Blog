<?php
/**
 * The template for displaying comments.
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title"><?php
			printf( _nx( '%s comment', '%s comments', get_comments_number(), 'comments title', 'stork' ), number_format_i18n( get_comments_number() ) );
			if ( comments_open() ) {
				echo ' &#47; <a href="#respond">' . __( 'Add your comment below', 'stork' ) . '</a>';
			}
		?></h2>
		<ol class="comment-list">
			<?php
			wp_list_comments( array( 
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 80
			) );
			?>
		</ol><!-- .commentlist -->
		<?php
		the_comments_navigation( array(
			'prev_text' => __( '&larr; Older comments', 'stork' ),
			'next_text' => __( 'Newer comments &rarr;', 'stork' )
		) );
		?>
	<?php
	endif;
	// If comments are closed and there are comments, let's leave a little note.
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
	<p class="comments-closed"><?php esc_html_e( 'Comments are closed.', 'stork' ); ?></p>
	<?php
	endif;
	comment_form( array(
		'comment_notes_after' => '',
	) );
	?>
</div><!-- .comments-area -->