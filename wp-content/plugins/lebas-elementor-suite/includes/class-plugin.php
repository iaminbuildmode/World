<?php
/**
 * Main plugin bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Elementor_Suite_Plugin {

    /** @var self|null */
    private static $instance = null;

    /** @var array<string,string> widget file => class */
    private $widgets = [
        'class-widget-base.php'            => null, // base only, not registered
        'class-price-tag.php'              => 'Lebas_Widget_Price_Tag',
        'class-product-card.php'           => 'Lebas_Widget_Product_Card',
        'class-gateway-three-col.php'      => 'Lebas_Widget_Gateway_Three_Col',
        'class-editorial-landing.php'      => 'Lebas_Widget_Editorial_Landing',
        'class-product-grid-switcher.php'  => 'Lebas_Widget_Product_Grid_Switcher',
        'class-search-underline.php'       => 'Lebas_Widget_Search_Underline',
        'class-storefront-header.php'      => 'Lebas_Widget_Storefront_Header',
        'class-storefront-footer.php'      => 'Lebas_Widget_Storefront_Footer',
        'class-cart-urgency-bar.php'       => 'Lebas_Widget_Cart_Urgency_Bar',
        'class-size-concierge-bottomsheet.php' => 'Lebas_Widget_Size_Concierge_Bottomsheet',
        'class-restock-alert.php'          => 'Lebas_Widget_Restock_Alert',
        'class-designer-directory.php'     => 'Lebas_Widget_Designer_Directory',
        'class-smart-size-guide.php'       => 'Lebas_Widget_Smart_Size_Guide',
        'class-packaging-selector.php'     => 'Lebas_Widget_Packaging_Selector',
    ];

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'elementor/dynamic_tags/register', [ $this, 'register_dynamic_tags' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_assets' ] );
        add_action( 'init', [ $this, 'load_textdomain' ] );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'lebas-widgets',
            [
                'title' => esc_html__( 'ویجت‌های اختصاصی لـبـاس', 'lebas-elementor-suite' ),
                'icon'  => 'eicon-products',
            ]
        );
    }

    public function register_widgets( $widgets_manager ) {
        $base = LEBAS_ELEMENTOR_SUITE_PATH . 'includes/widgets/class-widget-base.php';
        if ( file_exists( $base ) ) {
            require_once $base;
        }
        foreach ( $this->widgets as $file => $class ) {
            if ( null === $class ) {
                continue;
            }
            $path = LEBAS_ELEMENTOR_SUITE_PATH . 'includes/widgets/' . $file;
            if ( ! file_exists( $path ) ) {
                continue;
            }
            require_once $path;
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function register_dynamic_tags( $dynamic_tags_manager ) {
        $base = LEBAS_ELEMENTOR_SUITE_PATH . 'includes/dynamic-tags/class-lebas-dynamic-tag-base.php';
        if ( file_exists( $base ) ) {
            require_once $base;
        }
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'lebas-widgets',
            LEBAS_ELEMENTOR_SUITE_URL . 'assets/css/lebas-widgets.css',
            [],
            LEBAS_ELEMENTOR_SUITE_VERSION
        );
        wp_enqueue_script(
            'lebas-widgets',
            LEBAS_ELEMENTOR_SUITE_URL . 'assets/js/lebas-widgets.js',
            [ 'jquery' ],
            LEBAS_ELEMENTOR_SUITE_VERSION,
            true
        );
    }

    public function enqueue_editor_assets() {
        $this->enqueue_frontend_assets();
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'lebas-elementor-suite', false, dirname( LEBAS_ELEMENTOR_SUITE_BASENAME ) . '/languages' );
    }
}