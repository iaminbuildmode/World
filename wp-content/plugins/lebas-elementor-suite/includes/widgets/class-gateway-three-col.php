<?php
/**
 * Widget: Lebas 3-Column Gateway (home).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Gateway_Three_Col extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-gateway-three-col';
    }

    public function get_title() {
        return esc_html__( 'Lebas 3-Column Gateway', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [
                'label' => esc_html__( 'Columns', 'lebas-elementor-suite' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'lebas-elementor-suite' ),
                'type'  => \Elementor\Controls_Manager::MEDIA,
            ]
        );
        $repeater->add_control(
            'label',
            [
                'label'   => esc_html__( 'Label', 'lebas-elementor-suite' ),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'بانوان',
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'lebas-elementor-suite' ),
                'type'  => \Elementor\Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label'       => esc_html__( 'Columns', 'lebas-elementor-suite' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [ 'label' => 'بانوان', 'link' => [ 'url' => '/women' ] ],
                    [ 'label' => 'کودکان', 'link' => [ 'url' => '/kids' ] ],
                    [ 'label' => 'آقایان', 'link' => [ 'url' => '/men' ] ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <nav class="lebas-gateway" aria-label="Departments">
            <?php foreach ( $s['columns'] as $col ) :
                $url = ! empty( $col['link']['url'] ) ? $col['link']['url'] : '#';
                $img = ! empty( $col['image']['url'] ) ? $col['image']['url'] : '';
                ?>
                <a class="lebas-gateway__col" href="<?php echo esc_url( $url ); ?>">
                    <?php if ( $img ) : ?>
                        <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $col['label'] ); ?>" loading="lazy" />
                    <?php endif; ?>
                    <span class="lebas-gateway__label"><?php echo esc_html( $col['label'] ); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <?php
    }
}