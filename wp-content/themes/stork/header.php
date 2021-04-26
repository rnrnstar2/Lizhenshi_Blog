<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="site">
	<header class="site-header">
		<div class="inner">
			<?php
			if ( has_custom_logo() ) {
				$custom_logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
				$logo_width = jgtstork_get_option( 'logo_retina' ) ? floor( $custom_logo[1]/2 ) : $custom_logo[1];
				printf( '<a href="%1$s" class="custom-logo-link" rel="home"><img src="%2$s" width="%3$s" alt="%4$s" /></a>',
					esc_url( home_url( '/' ) ),
					esc_url( $custom_logo[0] ),
					esc_attr( $logo_width ),
					esc_attr( get_bloginfo( 'name', 'display' ) )
				);
			}
			?>
			<?php if ( is_front_page() && is_home() ) : ?>
			<h1 class="site-title<?php if ( ! jgtstork_get_option( 'show_title' ) ) echo ' screen-reader-text'; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
			<p class="site-title<?php if ( ! jgtstork_get_option( 'show_title' ) ) echo ' screen-reader-text'; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>
			<?php if ( jgtstork_get_option( 'show_tagline' ) ) { ?>
			<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			<?php } ?>
		</div> <!-- .inner -->
	</header><!-- .site-header -->
	<nav class="site-navigation" aria-label="<?php esc_attr_e( 'Menu', 'stork' ); ?>">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu-wrap', 'depth' => 1 ) ); ?>
		<a href="#" id="menu-toggle" title="<?php _e( 'Show Menu', 'stork' ); ?>"><i aria-hidden="true" class="icon-plus"></i></a>
	</nav><!-- .site-navigation -->