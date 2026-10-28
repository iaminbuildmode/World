<?php
/**
 * Plugin Name:       Lebas Elementor Suite
 * Plugin URI:        https://leb.as
 * Description:       Custom Elementor Pro widgets for the Lebas storefront: PriceTag, Product Card, Editorial Landing, Gateway, Header, Footer, and more.
 * Version:           0.1.0
 * Author:            Lebas
 * Author URI:        https://leb.as
 * Text Domain:       lebas-elementor-suite
 * Domain Path:       /languages
 * Requires at least: 6.7
 * Requires PHP:      8.2
 * Requires Plugins:  elementor, elementor-pro, woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'LEBAS_ELEMENTOR_SUITE_VERSION', '0.1.0' );
define( 'LEBAS_ELEMENTOR_SUITE_FILE', __FILE__ );
define( 'LEBAS_ELEMENTOR_SUITE_PATH', plugin_dir_path( __FILE__ ) );
define( 'LEBAS_ELEMENTOR_SUITE_URL', plugin_dir_url( __FILE__ ) );
define( 'LEBAS_ELEMENTOR_SUITE_BASENAME', plugin_basename( __FILE__ ) );

require_once LEBAS_ELEMENTOR_SUITE_PATH . 'includes/class-plugin.php';

add_action( 'plugins_loaded', [ 'Lebas_Elementor_Suite_Plugin', 'instance' ] );