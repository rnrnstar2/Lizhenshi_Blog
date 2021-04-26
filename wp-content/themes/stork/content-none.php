<?php
/**
 * Template part for displaying a message that posts cannot be found.
 */
?>
<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'stork' ); ?></h1>
	</header><!-- .page-header -->
	<div class="page-content">
		<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Make sure all words are spelled correctly or try again with some different keywords.', 'stork' ); ?></p>
		<?php else : ?>
		<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'stork' ); ?></p>
		<?php endif; ?>
		<?php get_search_form(); ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->