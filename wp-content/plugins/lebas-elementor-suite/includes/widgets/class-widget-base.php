<?php
/**
 * Base class for all Lebas custom Elementor widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
    return;
}

abstract class Lebas_Widget_Base extends \Elementor\Widget_Base {

    public function get_categories() {
        return [ 'lebas-widgets' ];
    }

    public function get_icon() {
        return 'eicon-code';
    }

    public function get_keywords() {
        return [ 'lebas', 'luxury', 'fashion' ];
    }

    /**
     * Helper: add a standard content section with a heading.
     */
    protected function start_content_section( $label = 'Content' ) {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html( $label ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
    }

    /**
     * Helper: add a standard style section.
     */
    protected function start_style_section( $label = 'Style' ) {
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html( $label ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    }

    protected function render_text( $settings_key, $settings ) {
        if ( empty( $settings[ $settings_key ] ) ) {
            return '';
        }
        return wp_kses_post( $settings[ $settings_key ] );
    }
}