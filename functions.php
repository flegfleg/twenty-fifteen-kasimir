<?php 
/**
 * Twenty Fifteen KASIMIR functions and definitions
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen_Kasimir
 * @since Twenty Fifteen KASIMIR 1.0
 */

/**
 * Enqueue Parent & Child theme style sheets
 *
 * @since Twenty Fifteen KASIMIR 1.0
 *
 */
function twentyfifteen_kasimir_enqueue_styles() {
	$parent_style = 'parent-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style )
	);
}
add_action( 'wp_enqueue_scripts', 'twentyfifteen_kasimir_enqueue_styles' );


/**
 * Modify the default thumbnail size 
 *
 * @link https://www.winwar.co.uk/2017/11/one-pitfall-avoid-setting-child-themes-thumbnail-size/?utm_source=codesnippet
 * 
 * @since Twenty Fifteen KASIMIR 1.0
 *
 */
function twentyfifteen_kasimir_register_setup_theme() {
	set_post_thumbnail_size( 1400, 600, true );
} 
add_action( 'after_setup_theme', 'twentyfifteen_kasimir_register_setup_theme', 100 );



/**
 * Remove some partent theme Customizer panels.
 *
 * @since Twenty Fifteen KASIMIR 1.0
 *
 */
function twentyfifteen_kasimir_customize_register( $wp_customize ) {

	$wp_customize->remove_control( 'color_scheme' ); // we are not using these
	// $wp_customize->remove_control( 'background_color' ); // content is fixed to #FFFFFF
	
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
	
	// Add custom site-info text control.
	$wp_customize->add_setting(
		'site_info_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_filter_nohtml_kses', //removes all HTML
		)
	);
	
		
	$wp_customize->add_control( 'site_info_text', array(
		'label'       => __( 'Footer info text', 'twentyfifteenkasimir' ),
		'description' => __( 'Shown in the footer (click "Publish" to see the changes).', 'twentyfifteenkasimir' ),
		'section'     => 'title_tagline'
		)

	);	
}
add_action( 'customize_register', 'twentyfifteen_kasimir_customize_register', 12 );



/**
 * Returns CSS.
 *
 * @since Twenty Fifteen KASIMIR 1.0
 *
 * @param array $colors Color scheme colors.
 * @return string Color scheme CSS.
 *
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
	/* KASIMIR Color Scheme */

	/* Headline Color */
	h1, h2, h2.entry-title {
		color: {$colors['headline_color']};
	}
	
	/* Sidebar ------------------------------*/	
	#secondary {
		background-color: {$colors['sidebar_background_color']};
	}
	
	.sidebar .widget-area, 
	.sidebar .widget-area .textwidget, 
	.sidebar .widget-area a {
		color:  {$colors['sidebar_textcolor']};
	}
	
	.dropdown-toggle:after {
		color:  {$colors['sidebar_textcolor']};
	}
	
	.menu-item a {
		color:  {$colors['sidebar_textcolor']};
	}
	
	/* Buttons ------------------------------*/	
	.cb-button, .cb-button-container a, .cb-wrapper .cb-action input, .cb-wrapper #booking-form input, .cb-map-filters.cb-wrapper .cb-map-button-wrapper button,
	a.wp-block-button__link,
	#wpmem_reg input[type="submit"],
	#wpmem_login_form input[type="submit"],	#wpmem_login input[type="submit"] {
		border-radius: 8px;
		font-size: 18px;
		line-height: 18px;
		background-color: {$colors['headline_color']};
		border: none !important;
		text-decoration: none !important;
		word-break: normal !important;
		white-space: nowrap;
		color: #FFF;
		padding: 10px 15px;
		font-weight: bold;
	}
	
	.sidebar .widget-area .widget_commonsbooking-user-widget a {
		border-radius: 8px;
		font-size: 14px;
		line-height: 14px;
		background-color: {$colors['headline_color']};
		border: none !important;
		text-decoration: none !important;
		word-break: normal !important;
		white-space: nowrap;
		color: #FFF;
		padding: 6px 11px;
		font-weight: bold;	
	}
	
	/* Plugin: CommonsBooking */
		
	.cb-map-popup-item-link b a  {
		color: {$colors['headline_color']} !important;
	}
	
	.cb-wrapper .cb-title {
		color: {$colors['headline_color']};	
	}
	
	body div.cb-map-filters form div.cb-map-button-wrapper button {
		background-color: {$colors['sidebar_background_color']};
	}
	
	/* Plugin: Complianz | GDPR/CCPA Cookie Consent */
	
	#cc-window {
		background-color: {$colors['sidebar_background_color']};
		color:  {$colors['sidebar_textcolor']};
	}
	
	#cc-window a {
		color:  {$colors['sidebar_textcolor']};
	}
	
	#cc-window .cc-btn.cc-dismiss,
	#cc-window .cc-btn.cc-save.cc-show-settings {
		border-radius: 8px;
		border: 0 !important;
	}
	
	#cc-window.cc-window .cc-compliance .cc-btn.cc-accept-all {
		background-color: {$colors['headline_color']} !important;
		border: 0 !important;
	}
	
	CSS;

	return $css;
}


/**
 * Enqueues front-end CSS for color scheme.
 *
 * @since Twenty Fifteen KASIMIR 1.0
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
add_action( 'wp_enqueue_scripts', 'twentyfifteen_kasimir_color_scheme_css', 99 ); // high priority to overwrite parent theme injected css 


/**
 * Register additional Footer Widget area
 *
 * @since Twenty Fifteen KASIMIR 1.0
 *
 * @see register_sidebar()
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


/**
 * Remove Google fonts, we are using local font files
 *
 * @since Twenty Fifteen KASIMIR 1.0
 *
 */
function twentyfifteen_kasimir_remove_google_fonts() {
	wp_dequeue_style('twentyfifteen-fonts');
	wp_deregister_style('twentyfifteen-fonts');
}

add_action('wp_enqueue_scripts', 'twentyfifteen_kasimir_remove_google_fonts', 100);