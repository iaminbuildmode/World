<?php
/**
 * Widget: Lebas Price Tag (million rials badge).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Price_Tag extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-price-tag';
    }

    public function get_title() {
        return esc_html__( 'Lebas Price Tag (میلیون ریال)', 'lebas-elementor-suite' );
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content',
            [
                'label' => esc_html__( 'Content', 'lebas-elementor-suite' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'amount',
            [
                'label'       => esc_html__( 'Amount', 'lebas-elementor-suite' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '0',
                'placeholder' => '0',
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'top_label',
            [
                'label'   => esc_html__( 'Top unit label', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'میلیون',
            ]
        );

        $this->add_control(
            'bottom_label',
            [
                'label'   => esc_html__( 'Bottom unit label', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'ریال',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label'   => esc_html__( 'Alignment', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'right'  => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'left'   => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .lebas-price-tag' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style',
            [
                'label' => esc_html__( 'Style', 'lebas-elementor-suite' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'amount_color',
            [
                'label'     => esc_html__( 'Amount color', 'lebas-elementor-suite' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .lebas-price-tag__amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'unit_color',
            [
                'label'     => esc_html__( 'Unit color', 'lebas-elementor-suite' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .lebas-price-tag__unit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'amount_typography',
                'selector' => '{{WRAPPER}} .lebas-price-tag__amount',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $amount   = $settings['amount'];
        $top      = $settings['top_label'];
        $bottom   = $settings['bottom_label'];
        ?>
        <div class="lebas-price-tag">
            <span class="lebas-price-tag__amount"><?php echo esc_html( $amount ); ?></span>
            <span class="lebas-price-tag__unit">
                <span><?php echo esc_html( $top ); ?></span>
                <span><?php echo esc_html( $bottom ); ?></span>
            </span>
        </div>
        <?php
    }
}