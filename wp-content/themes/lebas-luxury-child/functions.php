<?php
/**
 * Lebas Luxury Child — functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'hello-elementor', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'lebas-child', get_stylesheet_uri(), [ 'hello-elementor' ], '0.1.0' );
    wp_enqueue_style( 'lebas-fonts', get_stylesheet_directory_uri() . '/assets/css/fonts.css', [], '0.1.0' );
}, 20 );

// Disable Google Fonts entirely.
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

// Register local fonts (paths) — Elementor Pro Custom Fonts still need manual upload,
// but we enqueue fallback CSS here.
add_action( 'wp_head', function () {
    echo '<link rel="preconnect" href="' . esc_url( home_url() ) . '" crossorigin>' . "\n";
}, 1 );