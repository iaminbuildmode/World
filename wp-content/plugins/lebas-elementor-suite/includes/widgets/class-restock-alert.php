<?php
/**
 * Widget: Lebas Restock Alert Modal.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Restock_Alert extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-restock-alert';
    }

    public function get_title() {
        return esc_html__( 'Lebas Restock Alert Modal', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control( 'modal_id', [ 'label' => 'Modal ID', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'lebas-restock-modal' ] );
        $this->add_control( 'title', [ 'label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'اطلاع‌رسانی شارژ مجدد' ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $modalId = $s['modal_id'] ? $s['modal_id'] : 'lebas-restock-modal';
        ?>
        <div id="<?php echo esc_attr( $modalId ); ?>" class="lebas-restock-modal" role="dialog" aria-modal="true">
            <div class="lebas-restock-modal__box">
                <button type="button" class="lebas-restock-modal__close" style="float:left;border:none;background:none;cursor:pointer;">×</button>
                <h3 class="lebas-restock-modal__title"><?php echo esc_html( $s['title'] ); ?></h3>
                <p style="font-size:.85rem;margin-bottom:1rem;">شماره تلفن یا ایمیل خود را وارد کنید:</p>
                <input type="text" placeholder="0912..." style="width:100%;padding:.6rem;border:1px solid #e5e5e5;margin-bottom:1rem;" />
                <div class="lebas-restock-modal__channels">
                    <button type="button" class="lebas-restock-modal__channel is-active" data-channel="whatsapp">واتساپ</button>
                    <button type="button" class="lebas-restock-modal__channel" data-channel="telegram">تلگرام</button>
                    <button type="button" class="lebas-restock-modal__channel" data-channel="email">ایمیل</button>
                </div>
                <button type="button" style="width:100%;padding:.75rem;background:#000;color:#fff;border:none;cursor:pointer;">ثبت</button>
            </div>
        </div>
        <?php
    }
}