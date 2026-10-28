<?php
/**
 * Widget: Lebas Search Underline (header search).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Search_Underline extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-search-underline';
    }

    public function get_title() {
        return esc_html__( 'Lebas Search Underline', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control(
            'placeholder',
            [ 'label' => 'Placeholder', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'جستجو...' ]
        );
        $this->add_control(
            'action_url',
            [ 'label' => 'Search URL', 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => home_url( '/' ) ] ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $url = ! empty( $s['action_url']['url'] ) ? $s['action_url']['url'] : home_url( '/' );
        ?>
        <form class="lebas-search-underline" action="<?php echo esc_url( $url ); ?>" method="get" role="search">
            <input type="search" name="s" placeholder="<?php echo esc_attr( $s['placeholder'] ); ?>" />
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><line x1="16.5" y1="16.5" x2="21" y2="21"></line></svg>
        </form>
        <?php
    }
}