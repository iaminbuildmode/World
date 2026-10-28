<?php
/**
 * Widget: Lebas Smart Size Guide.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Smart_Size_Guide extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-smart-size-guide';
    }

    public function get_title() {
        return esc_html__( 'Lebas Smart Size Guide', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control( 'title', [ 'label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'راهنمای هوشمند سایز' ] );
        $this->add_control( 'chart', [ 'label' => 'Size chart (JSON)', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => '{}' ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <div class="lebas-size-guide">
            <h4 class="lebas-size-guide__title"><?php echo esc_html( $s['title'] ); ?></h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:.75rem;">
                <input type="number" placeholder="دور سینه (cm)" />
                <input type="number" placeholder="دور کمر (cm)" />
                <input type="number" placeholder="دور باسن (cm)" />
                <input type="number" placeholder="عرض شانه (cm)" />
            </div>
            <button type="button" style="padding:.6rem 1rem;background:#000;color:#fff;border:none;cursor:pointer;">محاسبه سایز</button>
            <div class="lebas-size-guide__result">سایز پیشنهادی: —</div>
        </div>
        <?php
    }
}