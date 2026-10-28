<?php
/**
 * Widget: Lebas Product Card.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Product_Card extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-product-card';
    }

    public function get_title() {
        return esc_html__( 'Lebas Product Card', 'lebas-elementor-suite' );
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
            'primary_image',
            [
                'label'   => esc_html__( 'Primary image', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'secondary_image',
            [
                'label'   => esc_html__( 'Secondary image (hover)', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'brand',
            [
                'label'   => esc_html__( 'Brand', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => esc_html__( 'Title', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'       => esc_html__( 'Link', 'lebas-elementor-suite' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => 'https://',
            ]
        );

        $this->add_control(
            'price',
            [
                'label'   => esc_html__( 'Price (million rials)', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => '0',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'department',
            [
                'label'   => esc_html__( 'Department (for wishlist icon)', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'women',
                'options' => [
                    'women' => esc_html__( 'Women (heart)', 'lebas-elementor-suite' ),
                    'kids'  => esc_html__( 'Kids (heart)', 'lebas-elementor-suite' ),
                    'men'   => esc_html__( 'Men (star)', 'lebas-elementor-suite' ),
                ],
            ]
        );

        $this->add_control(
            'concierge_bs',
            [
                'label'       => esc_html__( 'Concierge bottom sheet ID (optional)', 'lebas-elementor-suite' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'lebas-bs-sizes',
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'size_label',
            [
                'label'   => esc_html__( 'Size label', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'M',
            ]
        );
        $repeater->add_control(
            'available',
            [
                'label'        => esc_html__( 'Available', 'lebas-elementor-suite' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => 'Yes',
                'label_off'    => 'No',
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'sizes',
            [
                'label'       => esc_html__( 'Sizes', 'lebas-elementor-suite' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ size_label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $primary = ! empty( $s['primary_image']['url'] ) ? $s['primary_image']['url'] : '';
        $secondary = ! empty( $s['secondary_image']['url'] ) ? $s['secondary_image']['url'] : '';
        $link = ! empty( $s['link']['url'] ) ? $s['link']['url'] : '#';
        $target = ! empty( $s['link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
        $isMen = ( 'men' === $s['department'] );
        $wishlistIcon = $isMen ? '☆' : '♡';
        ?>
        <article class="lebas-product-card" data-concierge-bs="<?php echo esc_attr( $s['concierge_bs'] ); ?>">
            <a class="lebas-product-card__media" href="<?php echo esc_url( $link ); ?>"<?php echo $target; ?>>
                <?php if ( $primary ) : ?>
                    <img class="lebas-product-card__img lebas-product-card__img--primary" src="<?php echo esc_url( $primary ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" loading="lazy" />
                <?php endif; ?>
                <?php if ( $secondary ) : ?>
                    <img class="lebas-product-card__img lebas-product-card__img--secondary" src="<?php echo esc_url( $secondary ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" loading="lazy" />
                <?php endif; ?>
            </a>
            <button type="button" class="lebas-product-card__wishlist" aria-label="Wishlist">
                <?php echo esc_html( $wishlistIcon ); ?>
            </button>
            <div class="lebas-product-card__info">
                <?php if ( ! empty( $s['brand'] ) ) : ?>
                    <div class="lebas-product-card__brand"><?php echo esc_html( $s['brand'] ); ?></div>
                <?php endif; ?>
                <?php if ( ! empty( $s['title'] ) ) : ?>
                    <div class="lebas-product-card__title"><?php echo esc_html( $s['title'] ); ?></div>
                <?php endif; ?>
                <?php if ( ! empty( $s['sizes'] ) ) : ?>
                    <div class="lebas-product-card__sizes">
                        <?php foreach ( $s['sizes'] as $size ) : ?>
                            <span class="lebas-product-card__size <?php echo ( 'yes' !== $size['available'] ) ? 'is-unavailable' : ''; ?>">
                                <?php echo esc_html( $size['size_label'] ); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="lebas-product-card__price-row">
                    <div class="lebas-price-tag">
                        <span class="lebas-price-tag__amount"><?php echo esc_html( $s['price'] ); ?></span>
                        <span class="lebas-price-tag__unit">
                            <span>میلیون</span>
                            <span>ریال</span>
                        </span>
                    </div>
                </div>
            </div>
        </article>
        <?php
    }
}