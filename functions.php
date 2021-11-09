<?php 
/**
 * Twenty Fifteen KASIMIR functions and definitions
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen_KASIMIR
 * @since Twenty Fifteen KASIMIR 1.0
 */

// Enqueue parent and child them stylesheet
function twentyfifteen_kasimir_enqueue_styles() {
	$parent_style = 'parent-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style )
	);
}
add_action( 'wp_enqueue_scripts', 'twentyfifteen_kasimir_enqueue_styles' );


// Remove customizer panel(s) 
function twentyfifteen_kasimir_customize_register( $wp_customize ) {

	$wp_customize->remove_control( 'color_scheme' );
	$wp_customize->remove_control( 'background_color' );
	
	
	// Add custom header and sidebar background color setting and control.
	$wp_customize->add_setting(
		'headline_color',
		array(
			'default'           => '#000000',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'headline_color',
			array(
				'label'       => __( 'Headline Color', 'twentyfifteenkasimir' ),
				'description' => __( 'Applied to headlines (Click "Publish to see the changes").', 'twentyfifteenkasimir' ),
				'section'     => 'colors',
			)
		)
	);
}
add_action( 'customize_register', 'twentyfifteen_kasimir_customize_register', 12 );


/**
 * Returns CSS for the color schemes.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param array $colors Color scheme colors.
 * @return string Color scheme CSS.
 */
function twentyfifteen_kasimir_get_color_scheme_css( $colors ) {
	$colors = wp_parse_args(
		$colors,
		array(
			'headline_color'            => '',
			'sidebar_background_color'  => '',
			'sidebar_textcolor'			=> ''
		)
	);

	$css = <<<CSS
	/* Color Scheme */

	/* TESTING Headline Color */
	h1, h2  {
		color: {$colors['headline_color']};
	}
	
	#secondary.toggled-on {
		background-color: {$colors['sidebar_background_color']};
	}
	
	body div.cb-map-filters form div.cb-map-button-wrapper button {
		background-color: {$colors['sidebar_background_color']};
	}
	
	.menu-item a {
		color:  {$colors['sidebar_textcolor']}
	}
	
	CSS;

	return $css;
}


/**
 * Enqueues front-end CSS for color scheme.
 *
 * @since Twenty Fifteen 1.0
 *
 * @see wp_add_inline_style()
 */
function twentyfifteen_kasimir_color_scheme_css() {
	
	$headline_color = get_theme_mod( 'headline_color', '#000000' );
	$sidebar_background_color = get_theme_mod( 'header_background_color', '#000000' );
	$sidebar_textcolor = get_theme_mod( 'sidebar_textcolor', '#000000' );
	
	$colors                      = array(
		'headline_color'            => $headline_color,
		'sidebar_background_color'     => $sidebar_background_color,
		'sidebar_textcolor'            => $sidebar_textcolor
		
	);
	
	$color_scheme_css = twentyfifteen_kasimir_get_color_scheme_css( $colors );

	wp_add_inline_style( 'twentyfifteen-style', $color_scheme_css );
}
add_action( 'wp_enqueue_scripts', 'twentyfifteen_kasimir_color_scheme_css', 99 );




/**
 * Register additional widget area
 */
function twentyfifteen_kasimir_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'twentyfifteenkasimir' ),
			'id'            => 'sidebar-footer',
			'description'   => __( 'Add widgets here to appear in your footer.', 'twentyfifteenkasimir' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'twentyfifteen_kasimir_widgets_init' );