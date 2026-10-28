<?php
/**
 * Widget: Lebas Cart Urgency Bar (15-min countdown).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Cart_Urgency_Bar extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-cart-urgency-bar';
    }

    public function get_title() {
        return esc_html__( 'Lebas Cart Urgency Bar', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control(
            'text',
            [ 'label' => 'Text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'سبد خرید شما برای مدت محدودی رزرو شده است:' ]
        );
        $this->add_control(
            'expires',
            [
                'label'       => 'Expires (Unix timestamp)',
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => (string) ( time() + 900 ),
                'description' => 'Set from server-side session in production.',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <div class="lebas-cart-urgency-bar" data-expires="<?php echo esc_attr( $s['expires'] ); ?>">
            <span><?php echo esc_html( $s['text'] ); ?></span>
            <span class="lebas-cart-urgency-bar__timer">15:00</span>
        </div>
        <?php
    }
}