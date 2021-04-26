<?php
/**
 * Custom template tags for this theme.
 */

if ( ! function_exists( 'jgtstork_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 */
function jgtstork_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() )
		return;
	if ( is_singular() ) :
	?>
	<div class="post-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div><!-- .post-thumbnail -->
	<?php else : ?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?></a>
	<?php endif;
}
endif;

if ( ! function_exists( 'jgtstork_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post.
 */
function jgtstork_posted_on() {
	if ( 'post' == get_post_type() ) {
		jgtstork_entry_date();
		printf( '<span class="entry-author"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
			_x( 'Author', 'Used before post author name.', 'stork' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
		$categories_list = get_the_category_list( ', ' );
		if ( $categories_list ) {
			printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Posted in', 'Used before category links', 'stork' ),
				$categories_list
			);
		}
	} elseif ( 'attachment' == get_post_type() ) {
		jgtstork_entry_date();
		if ( wp_attachment_is_image() ) {
			$metadata = wp_get_attachment_metadata();
			printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
				_x( 'Full size', 'Used before full size attachment link.', 'stork' ),
				esc_url( wp_get_attachment_url() ),
				absint( $metadata['width'] ),
				absint( $metadata['height'] )
			);
		}
	}
	if ( comments_open() || get_comments_number() ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a Reply', 'stork' ), esc_html__( '1 Reply', 'stork' ), esc_html__( '% Replies', 'stork' ) );
		echo '</span>';
	}
}
endif;

if ( ! function_exists( 'jgtstork_entry_date' ) ) :
/**
 * Prints HTML with date information for the current post.
 */
function jgtstork_entry_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date(),
		esc_attr( get_the_modified_date( 'c' ) ),
		get_the_modified_date()
	);
	printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
		esc_html_x( 'Posted on', 'Used before publish date.', 'stork' ),
		esc_url( get_permalink() ),
		$time_string
	);
}
endif;

if ( ! function_exists( 'jgtstork_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the tags or parent post link.
 */
function jgtstork_entry_footer() {
	if ( 'post' == get_post_type() ) {
		$tags_list = get_the_tag_list( __( 'Tagged: ', 'stork' ) );
		if ( $tags_list ) {
			echo '<div class="tag-links">' . $tags_list . '</div>';
		}
	} elseif ( 'attachment' == get_post_type() ) {
		previous_post_link( '<div class="parent-post-link">' . __( 'Published in:', 'stork' ) . ' %link</div>', '%title' );
	}
}
endif;

if ( ! function_exists( 'jgtstork_loop_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function jgtstork_loop_navigation() {
	the_posts_pagination( array(
		'prev_text'          => __( '&larr; Previous', 'stork' ),
		'next_text'          => __( 'Next &rarr;', 'stork' ),
		'before_page_number' => '<span class="screen-reader-text">' . __( 'Page', 'stork' ) . ' </span>'
	) );
}
endif;
