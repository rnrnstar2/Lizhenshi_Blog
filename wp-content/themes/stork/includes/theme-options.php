<?php

/**
 * Theme settings sections.
 */
function jgtstork_theme_settings_sections() {
	return array(
		'general' => array(
			'name'   => 'jgtstork_general_settings',
			'title'  => __( 'General Settings', 'stork' ),
			'priority' => 140
		),
		'social' => array(
			'name'  => 'jgtstork_social_settings',
			'title' => __( 'Social Links Settings', 'stork' ),
			'priority' => 150
		)
	);
}

/**
 * Theme settings fields.
 */
function jgtstork_theme_settings_fields() {
	return array(
		'primary_color' => array(
			'name'     => 'primary_color',
			'title'    => __( 'Primary Color', 'stork' ),
			'type'     => 'color',
			'sanitize' => 'sanitize_hex_color',
			'default'  => '#3366c8',
			'section'  => 'colors',
			'priority' => 1
		),
		'secondary_color' => array(
			'name'     => 'secondary_color',
			'title'    => __( 'Secondary Color', 'stork' ),
			'type'     => 'color',
			'sanitize' => 'sanitize_hex_color',
			'default'  => '#ff5148',
			'section'  => 'colors',
			'priority' => 2
		),
		'logo_retina' => array(
			'name'     => 'logo_retina',
			'title'    => __( 'Check if you use double sized logo.', 'stork' ),
			'type'     => 'checkbox',
			'sanitize' => 'jgtstork_sanitize_checkbox',
			'default'  => false,
			'section'  => 'title_tagline',
			'priority' => 9
		),
		'show_title' => array(
			'name'     => 'show_title',
			'title'    => __( 'Show Title', 'stork' ),
			'type'     => 'checkbox',
			'sanitize' => 'jgtstork_sanitize_checkbox',
			'default'  => true,
			'section'  => 'title_tagline',
			'priority' => 10
		),
		'show_tagline' => array(
			'name'     => 'show_tagline',
			'title'    => __( 'Show Tagline', 'stork' ),
			'type'     => 'checkbox',
			'sanitize' => 'jgtstork_sanitize_checkbox',
			'default'  => true,
			'section'  => 'title_tagline',
			'priority' => 10
		),
		'animated_nav' => array(
			'name'     => 'animated_nav',
			'title'    => __( 'Enable Animated Navigation Menu.', 'stork' ),
			'type'     => 'checkbox',
			'sanitize' => 'jgtstork_sanitize_checkbox',
			'default'  => true,
			'section'  => 'jgtstork_general_settings',
			'priority' => 1
		),
		'footer_text' => array(
			'name'     => 'footer_text',
			'title'    => __( 'Footer Text.', 'stork' ),
			'type'     => 'textarea',
			'sanitize' => 'jgtstork_sanitize_html',
			'default'  => '',
			'section'  => 'jgtstork_general_settings',
			'priority' => 2
		),
		'social_title' => array(
			'name'     => 'social_title',
			'title'    => __( 'Social Networks Section Title', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'jgtstork_sanitize_nohtml',
			'default'  => 'Say Hello',
			'section'  => 'jgtstork_social_settings',
			'priority' => 1
		),
		'twitter' => array(
			'name'     => 'twitter',
			'title'    => __( 'Twitter URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 2
		),
		'facebook' => array(
			'name'     => 'facebook',
			'title'    => __( 'Facebook URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 3
		),
		'googleplus' => array(
			'name'     => 'googleplus',
			'title'    => __( 'Google+ URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 4
		),
		'pinterest' => array(
			'name'     => 'pinterest',
			'title'    => __( 'Pinterest URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 5
		),
		'tumblr' => array(
			'name'     => 'tumblr',
			'title'    => __( 'Tumblr URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 6
		),
		'instagram' => array(
			'name'     => 'instagram',
			'title'    => __( 'Instagram URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 7
		),
		'flickr' => array(
			'name'     => 'flickr',
			'title'    => __( 'Flickr URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 8
		),
		'linkedin' => array(
			'name'     => 'linkedin',
			'title'    => __( 'LinkedIn URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 9
		),
		'dribbble' => array(
			'name'     => 'dribbble',
			'title'    => __( 'Dribbble URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 10
		),
		'github' => array(
			'name'     => 'github',
			'title'    => __( 'GitHub URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 11
		),
		'vimeo' => array(
			'name'     => 'vimeo',
			'title'    => __( 'Vimeo URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 12
		),
		'youtube' => array(
			'name'     => 'youtube',
			'title'    => __( 'YouTube URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 13
		),
		'rss' => array(
			'name'     => 'rss',
			'title'    => __( 'RSS URL', 'stork' ),
			'type'     => 'text',
			'sanitize' => 'esc_url_raw',
			'default'  => '',
			'section'  => 'jgtstork_social_settings',
			'priority' => 14
		)
	);
}

/**
 * Return default theme options.
 */
function jgtstork_default_theme_options() {
	$fields = jgtstork_theme_settings_fields();
	$defaults = array();
	foreach( $fields as $field ){
		$defaults[$field['name']] = $field['default'];
	}
	return $defaults;
}

/**
 * Return theme options array.
 */
function jgtstork_theme_options() {
	$defaults = jgtstork_default_theme_options();
	$options = wp_parse_args( get_option( 'jgtstork_options', array() ), $defaults );
	return $options;
}

/**
 * Helper function to return the theme option value.
 */
function jgtstork_get_option( $name ) {
	$options = jgtstork_theme_options();
	if ( array_key_exists( $name, $options ) )
		return $options[$name];
	return false;
}
