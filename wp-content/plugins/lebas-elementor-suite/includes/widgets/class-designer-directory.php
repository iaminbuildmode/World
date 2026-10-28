<?php
/**
 * Widget: Lebas A-Z Designer Directory.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Designer_Directory extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-designer-directory';
    }

    public function get_title() {
        return esc_html__( 'Lebas A-Z Designer Directory', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control(
            'taxonomy',
            [ 'label' => 'Brand taxonomy', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'product_brand' ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $tax = $s['taxonomy'] ? $s['taxonomy'] : 'product_brand';
        $terms = get_terms( [ 'taxonomy' => $tax, 'hide_empty' => false ] );
        if ( is_wp_error( $terms ) ) {
            $terms = [];
        }

        // Group by first latin letter.
        $groups = [];
        foreach ( $terms as $t ) {
            $name = $t->name;
            $first = strtoupper( substr( preg_replace( '/[^A-Za-z]/', '', $name ), 0, 1 ) );
            if ( '' === $first ) {
                $first = '#';
            }
            $groups[ $first ][] = $t;
        }
        ksort( $groups );

        $letters = array_merge( range( 'A', 'Z' ), [ '#' ] );
        ?>
        <div class="lebas-designers">
            <div class="lebas-designers__jump">
                <?php foreach ( $letters as $L ) : ?>
                    <?php if ( isset( $groups[ $L ] ) ) : ?>
                        <a href="#lebas-designer-<?php echo esc_attr( $L ); ?>"><?php echo esc_html( $L ); ?></a>
                    <?php else : ?>
                        <span style="opacity:.35;padding:.25rem .5rem;"><?php echo esc_html( $L ); ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php foreach ( $groups as $letter => $list ) : ?>
                <div class="lebas-designers__group" id="lebas-designer-<?php echo esc_attr( $letter ); ?>">
                    <h3><?php echo esc_html( $letter ); ?></h3>
                    <ul class="lebas-designers__list">
                        <?php foreach ( $list as $t ) : ?>
                            <li><a href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}