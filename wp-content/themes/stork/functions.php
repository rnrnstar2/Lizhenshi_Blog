<?php

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 780;

if ( ! function_exists( 'jgtstork_setup' ) ) :
/**
 * Run jgtstork_setup() when the after_setup_theme hook is run.
 */
function jgtstork_setup() {

	// Make theme available for translation.
	load_theme_textdomain( 'stork', get_template_directory() . '/languages' );

	// Style the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', jgtstork_font_url() ) );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Register a menu location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'stork' ) );

	// Add support for featured images.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1560, 9999 );

	// Enable support for custom logo.
	add_theme_support( 'custom-logo', array(
		'height'      => '240',
		'width'       => '400',
		'flex-width' => true,
		'flex-height' => true,
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption'
	) );

}
endif;
add_action( 'after_setup_theme', 'jgtstork_setup' );

/**
 * Register four widget areas in the footer.
 */
function jgtstork_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 1', 'stork' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears in the footer section of the site', 'stork' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>'
	) );
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 2', 'stork' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears in the footer section of the site', 'stork' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>'
	) );
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 3', 'stork' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears in the footer section of the site', 'stork' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>'
	) );
	register_sidebar( array(
		'name' => __( 'Footer Widget Area 4', 'stork' ),
		'id' => 'sidebar-4',
		'description' => __( 'Appears in the footer section of the site', 'stork' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>'
	) );
}
add_action( 'widgets_init', 'jgtstork_widgets_init' );

/**
 * Register Karla Google font.
 */
function jgtstork_font_url() {
	$font_url = add_query_arg( 'family', urlencode( 'Karla:400,400i,700,700i' ), "https://fonts.googleapis.com/css" );
	return $font_url;
}

/**
 * Handle JavaScript detection.
 */
function jgtstork_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'jgtstork_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 */
function jgtstork_scripts_styles() {

	// Add Karla font, used in the main stylesheet.
	wp_enqueue_style( 'jgtstork-fonts', jgtstork_font_url(), array(), null );

	// Load the main stylesheet.
	wp_enqueue_style( 'jgtstork-style', get_stylesheet_uri() );

	// Load the IE specific stylesheet.
	wp_enqueue_style( 'jgtstork-ie', get_template_directory_uri() . '/css/ie.css', array( 'jgtstork-style' ), '1.5' );
	wp_style_add_data( 'jgtstork-ie', 'conditional', 'lt IE 9' );

	// Load the html5 shiv.
	wp_enqueue_script( 'jgtstork-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'jgtstork-html5', 'conditional', 'lt IE 9' );

	// Add JS to pages with the comment form to support sites with threaded comments (when in use).
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Add custom scripts.
	wp_enqueue_script( 'jgtstork-script', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), '1.5', true );
}
add_action( 'wp_enqueue_scripts', 'jgtstork_scripts_styles' );

/**
 * Change wp_nav_menu() fallback, wp_page_menu(), container class and depth.
 */
function jgtstork_page_menu_args( $args ) {
	$args['depth'] = 1;
	$args['menu_class'] = 'menu-wrap';
	return $args;
}
add_filter( 'wp_page_menu_args', 'jgtstork_page_menu_args' );

/**
 * Add custom classes to the array of body classes.
 */
function jgtstork_body_class( $classes ) {
	// Check if it is a single author blog.
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	// Check if animated navigation option is checked.
	if ( jgtstork_get_option( 'animated_nav' ) )
		$classes[] = 'animated-navigation';

	// Add a class of no-avatars if avatars are disabled.
	if ( ! get_option( 'show_avatars' ) ) {
		$classes[] = 'no-avatars';
	}

	// Add a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'jgtstork_body_class' );

/**
 * Customize the archive title.
 */
function jgtstork_archive_title( $title ) {
	if ( is_category() ) {
		$title = sprintf( __( 'All posts in %s', 'stork' ), '<span class="highlight">' . single_cat_title( '', false ) . '</span>' );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'All posts tagged %s', 'stork' ), '<span class="highlight">' . single_tag_title( '', false ) . '</span>' );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'All posts by %s', 'stork' ), '<span class="vcard highlight">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'All posts in %s', 'stork' ), '<span class="highlight">' . get_the_date( _x( 'Y', 'yearly archives date format', 'stork' ) ) . '</span>' );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'All posts in %s', 'stork' ), '<span class="highlight">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'stork' ) ) . '</span>' );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'All posts dated %s', 'stork' ), '<span class="highlight">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'stork' ) ) . '</span>' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'jgtstork_archive_title' );

/**
 * Customize tag cloud widget.
 */
function jgtstork_custom_tag_cloud_widget( $args ) {
	$args['number'] = 0;
	$args['largest'] = 14;
	$args['smallest'] = 14;
	$args['unit'] = 'px';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'jgtstork_custom_tag_cloud_widget' );

/**
 * Wrap "Read more" link.
 */
function jgtstork_wrap_more_link( $more ) {
	return '<span class="read-more">' . $more . '</span>';
}
add_filter( 'the_content_more_link','jgtstork_wrap_more_link' );

if ( ! function_exists( 'jgtstork_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Read More' link.
 */
function jgtstork_excerpt_more( $more ) {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		__( 'Read More', 'stork' )
	);
	return '&hellip; ' . $link;
}
add_filter( 'excerpt_more', 'jgtstork_excerpt_more' );
endif;

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Theme options.
 */
require get_template_directory() . '/includes/theme-options.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/includes/jetpack.php';
