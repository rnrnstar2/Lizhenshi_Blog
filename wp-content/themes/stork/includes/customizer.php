<?php
/**
 * Stork Theme Customizer.
 */

/**
 * Implement Theme Customizer additions and adjustments.
 */
function jgtstork_customize_register( $wp_customize ) {
	// Remove the core display site title and tagline control.
	$wp_customize->remove_control( 'display_header_text' );

	/**
	 * Add sections.
	 */
	$stork_sections = jgtstork_theme_settings_sections();
	foreach ( $stork_sections as $section ) {
		$wp_customize->add_section( $section['name'], array( 
			'title'    => $section['title'],
			'priority' => $section['priority']
		) );
	}

	/**
	 * Add settings and controls.
	 */
	$stork_settings = jgtstork_theme_settings_fields();
	foreach ( $stork_settings as $option ) {
		$wp_customize->add_setting( 'jgtstork_options[' . $option['name'] . ']', array(
			'default'           => $option['default'],
			'type'              => 'option',
			'sanitize_callback' => $option['sanitize']
		) );

		if ( $option['type'] == 'color' ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'jgtstork_' . $option['name'], array(
				'label'    => $option['title'],
				'section'  => $option['section'],
				'settings' => 'jgtstork_options[' . $option['name'] . ']',
				'priority' => $option['priority']
			) ) );
		} else {
			$wp_customize->add_control( 'jgtstork_' . $option['name'], array(
				'label'    => $option['title'],
				'section'  => $option['section'],
				'settings' => 'jgtstork_options[' . $option['name'] . ']',
				'type'     => $option['type'],
				'priority' => $option['priority']
			) );
		}
	}
}

add_action( 'customize_register', 'jgtstork_customize_register' );

/**
 * Checkbox sanitization callback.
 */
function jgtstork_sanitize_checkbox( $input ) {
	return ( ( isset( $input ) && true == $input ) ? true : false );
}

/**
 * HTML sanitization callback.
 */
function jgtstork_sanitize_html( $input ) {
	return wp_filter_post_kses( $input );
}

/**
 * No HTML sanitization callback.
 */
function jgtstork_sanitize_nohtml( $input ) {
	return wp_filter_nohtml_kses( $input );
}

/**
 * Nonnegative integer sanitization callback.
 */
function jgtstork_sanitize_number( $input ) {
	$input = absint( $input );
	if ( ! $input )
		$input = '';
	return $input;
}

/**
 * Enqueue front-end CSS for the custom colors.
 */
function jgtstork_custom_colors_css() {
	$primary_color = jgtstork_get_option( 'primary_color' );
	$secondary_color = jgtstork_get_option( 'secondary_color' );
	$css = '';

	if ( $primary_color !== '#3366c8' )
		$css .= 'a,.entry-meta,.social-title,.bypostauthor > .comment-body .fn:after {color: ' . esc_attr( $primary_color ) . ';} button,input[type="submit"],input[type="button"],input[type="reset"] {background: ' . esc_attr( $primary_color ) . ';border-color: ' . esc_attr( $primary_color ) . ';} blockquote {border-color: ' . esc_attr( $primary_color ) . ';} .site-header,.site-navigation .menu-wrap,#menu-toggle,.read-more .more-link,.author-box,#cancel-comment-reply-link,.site-footer,.social-links a {background: ' . esc_attr( $primary_color ) . ';}';

	if ( $secondary_color !== '#ff5148' )
		$css .= 'a:hover,.site-navigation a:hover,.author-box a:hover,.comment-meta a:hover,.navigation a:hover,.pagination .current,.copyright a:hover,.supplementary a:hover,#back-to-top:hover,.infinite-scroll #infinite-handle span:hover {color: ' . esc_attr( $secondary_color ) . ';} .read-more .more-link:hover,#cancel-comment-reply-link:hover,.tag-links a:hover,.social-links a:hover {background: ' . esc_attr( $secondary_color ) . ';} input[type="text"]:focus,input[type="email"]:focus,input[type="url"]:focus,input[type="password"]:focus,input[type="search"]:focus,textarea:focus,.widget button:hover,.widget button:focus,.widget button:active,.widget input[type="submit"]:hover,.widget input[type="submit"]:focus,.widget input[type="submit"]:active,.widget input[type="button"]:hover,.widget input[type="button"]:focus,.widget input[type="button"]:active,.widget input[type="reset"]:hover,.widget input[type="reset"]:focus,.widget input[type="reset"]:active {border-color: ' . esc_attr( $secondary_color ) . ';} button:hover,button:focus,button:active,input[type="submit"]:hover,input[type="submit"]:focus,input[type="submit"]:active,input[type="button"]:hover,input[type="button"]:focus,input[type="button"]:active,input[type="reset"]:hover,input[type="reset"]:focus,input[type="reset"]:active,.site-title a:hover,#wp-calendar tbody a:hover,.supplementary .tagcloud a:hover {background: ' . esc_attr( $secondary_color ) . ';border-color: ' . esc_attr( $secondary_color ) . ';}';

	if ( empty( $css ) )
		return;

	wp_add_inline_style( 'jgtstork-style', $css );
}
add_action( 'wp_enqueue_scripts', 'jgtstork_custom_colors_css', 11 );
