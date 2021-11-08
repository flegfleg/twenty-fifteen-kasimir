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

}
add_action( 'customize_register', 'twentyfifteen_kasimir_customize_register', 12 );

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