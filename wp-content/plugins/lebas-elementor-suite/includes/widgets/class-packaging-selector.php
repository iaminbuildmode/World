<?php
/**
 * Widget: Lebas Packaging Selector (checkout).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Packaging_Selector extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-packaging-selector';
    }

    public function get_title() {
        return esc_html__( 'Lebas Packaging Selector', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control( 'option1_title', [ 'label' => 'Option 1 title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'بسته‌بندی امضا‌شده لـبـاس' ] );
        $this->add_control( 'option1_desc', [ 'label' => 'Option 1 desc', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'جعبه اختصاصی با روبان ساتن و پلمپ بازرسی کیفی — تحت پوشش لـبـاس' ] );
        $this->add_control( 'option1_img', [ 'label' => 'Option 1 image', 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->add_control( 'option2_title', [ 'label' => 'Option 2 title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'بسته‌بندی بهینه‌سازی‌شده و محرمانه' ] );
        $this->add_control( 'option2_desc', [ 'label' => 'Option 2 desc', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'بسته‌بندی مینیمال و دوستدار محیط زیست — تحت پوشش لـبـاس' ] );
        $this->add_control( 'option2_img', [ 'label' => 'Option 2 image', 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $img1 = ! empty( $s['option1_img']['url'] ) ? $s['option1_img']['url'] : '';
        $img2 = ! empty( $s['option2_img']['url'] ) ? $s['option2_img']['url'] : '';
        ?>
        <div class="lebas-packaging">
            <div class="lebas-packaging__option is-selected" data-value="signature">
                <?php if ( $img1 ) : ?><img src="<?php echo esc_url( $img1 ); ?>" alt="" /><?php endif; ?>
                <h4><?php echo esc_html( $s['option1_title'] ); ?></h4>
                <p><?php echo esc_html( $s['option1_desc'] ); ?></p>
            </div>
            <div class="lebas-packaging__option" data-value="confidential">
                <?php if ( $img2 ) : ?><img src="<?php echo esc_url( $img2 ); ?>" alt="" /><?php endif; ?>
                <h4><?php echo esc_html( $s['option2_title'] ); ?></h4>
                <p><?php echo esc_html( $s['option2_desc'] ); ?></p>
            </div>
        </div>
        <input type="hidden" name="lebas_packaging_type" value="signature" />
        <?php
    }
}