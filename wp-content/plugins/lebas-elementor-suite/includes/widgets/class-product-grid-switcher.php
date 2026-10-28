<?php
/**
 * Widget: Lebas Product Grid Column Switcher (4 <-> 2).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Product_Grid_Switcher extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-product-grid-switcher';
    }

    public function get_title() {
        return esc_html__( 'Lebas Grid Column Switcher', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control(
            'label_4',
            [ 'label' => 'Label 4-col', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '۴ ستون' ]
        );
        $this->add_control(
            'label_2',
            [ 'label' => 'Label 2-col', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '۲ ستون' ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <div class="lebas-grid-switcher" style="display:flex;gap:.5rem;justify-content:flex-end;margin-bottom:1rem;">
            <button type="button" class="lebas-grid-switcher__btn is-active" data-target="4"><?php echo esc_html( $s['label_4'] ); ?></button>
            <button type="button" class="lebas-grid-switcher__btn" data-target="2"><?php echo esc_html( $s['label_2'] ); ?></button>
        </div>
        <?php
    }
}