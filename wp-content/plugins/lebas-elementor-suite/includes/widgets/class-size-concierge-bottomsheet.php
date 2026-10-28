<?php
/**
 * Widget: Lebas Size Concierge Bottom Sheet.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Size_Concierge_Bottomsheet extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-size-concierge-bottomsheet';
    }

    public function get_title() {
        return esc_html__( 'Lebas Size Concierge Bottom Sheet', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control( 'title', [ 'label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'مدل‌های مشابه' ] );
        $this->add_control( 'bs_id', [ 'label' => 'Bottom sheet ID', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'lebas-bs-sizes' ] );
        $this->add_control( 'category', [ 'label' => 'Product category slug', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '' ] );
        $this->add_control( 'size', [ 'label' => 'Size (pa_size slug)', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '' ] );
        $this->add_control( 'posts_per_page', [ 'label' => 'Posts per page', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 8 ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $bsId = $s['bs_id'] ? $s['bs_id'] : 'lebas-bs-sizes';
        $args = [
            'status'  => 'publish',
            'limit'   => (int) $s['posts_per_page'],
            'orderby' => 'date',
            'order'   => 'DESC',
        ];
        if ( ! empty( $s['category'] ) ) {
            $args['category'] = [ $s['category'] ];
        }
        $products = function_exists( 'wc_get_products' ) ? wc_get_products( $args ) : [];
        ?>
        <div id="<?php echo esc_attr( $bsId ); ?>" class="lebas-bottomsheet" role="dialog" aria-modal="true">
            <div class="lebas-bottomsheet__handle"></div>
            <div class="lebas-bottomsheet__title"><?php echo esc_html( $s['title'] ); ?></div>
            <div class="lebas-bottomsheet__grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
                <?php foreach ( $products as $product ) :
                    $img = wp_get_attachment_image_url( $product->get_image_id(), 'medium' );
                    ?>
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>" style="display:block;">
                        <?php if ( $img ) : ?>
                            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" style="width:100%;aspect-ratio:3/4;object-fit:cover;" loading="lazy" />
                        <?php endif; ?>
                        <div style="font-size:.85rem;margin-top:.4rem;"><?php echo esc_html( $product->get_name() ); ?></div>
                        <div style="font-size:.8rem;color:#555;"><?php echo esc_html( $product->get_price() ); ?> ریال</div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="lebas-bottomsheet__backdrop" style="display:none;"></div>
        <?php
    }
}