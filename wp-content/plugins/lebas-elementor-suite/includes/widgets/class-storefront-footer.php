<?php
/**
 * Widget: Lebas Storefront Footer.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Storefront_Footer extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-storefront-footer';
    }

    public function get_title() {
        return esc_html__( 'Lebas Storefront Footer', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control( 'newsletter_title', [ 'label' => 'Newsletter title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'عضویت در خبرنامه لـبـاس' ] );
        $this->add_control( 'enamad_url', [ 'label' => 'Enamad page URL', 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/enamad' ] ] );
        $this->add_control( 'enamad_img', [ 'label' => 'Enamad logo image', 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $enamadUrl = ! empty( $s['enamad_url']['url'] ) ? $s['enamad_url']['url'] : '/enamad';
        $enamadImg = ! empty( $s['enamad_img']['url'] ) ? $s['enamad_img']['url'] : '';
        ?>
        <footer class="lebas-footer">
            <div class="lebas-footer__newsletter">
                <h3><?php echo esc_html( $s['newsletter_title'] ); ?></h3>
                <form method="post" action="#">
                    <input type="email" placeholder="ایمیل شما" required />
                    <button type="submit">عضویت</button>
                </form>
            </div>

            <div class="lebas-footer__benefits">
                <div class="lebas-footer__benefit">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7l9-4 9 4-9 4-9-4z"></path><path d="M3 12l9 4 9-4"></path><path d="M3 17l9 4 9-4"></path></svg>
                    <div>تحت پوشش لـبـاس</div>
                </div>
                <div class="lebas-footer__benefit">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg>
                    <div>ارسال سریع</div>
                </div>
                <div class="lebas-footer__benefit">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"></path></svg>
                    <div>کیفیت تضمین‌شده</div>
                </div>
                <div class="lebas-footer__benefit">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 9h18"></path></svg>
                    <div>پرداخت امن</div>
                </div>
            </div>

            <div class="lebas-footer__links">
                <div class="lebas-footer__col">
                    <h4>خرید</h4>
                    <ul>
                        <li><a href="/women">بانوان</a></li>
                        <li><a href="/men">آقایان</a></li>
                        <li><a href="/kids">کودکان</a></li>
                        <li><a href="/new-arrivals">جدیدترین‌ها</a></li>
                    </ul>
                </div>
                <div class="lebas-footer__col">
                    <h4>خدمات</h4>
                    <ul>
                        <li><a href="/track">پیگیری سفارش</a></li>
                        <li><a href="/returns-policy">سیاست مرجوعی</a></li>
                        <li><a href="/gift-card">کارت هدیه</a></li>
                        <li><a href="/profile">حساب کاربری</a></li>
                    </ul>
                </div>
                <div class="lebas-footer__col">
                    <h4>درباره</h4>
                    <ul>
                        <li><a href="/about">درباره لـبـاس</a></li>
                        <li><a href="/terms">شرایط و قوانین</a></li>
                        <li><a href="/privacy">حریم خصوصی</a></li>
                        <li><a href="/contact">تماس با ما</a></li>
                    </ul>
                </div>
                <div class="lebas-footer__col">
                    <h4>پیگیری</h4>
                    <ul>
                        <li><a href="/designers">برندها</a></li>
                        <li><a href="/clothing">پوشاک</a></li>
                        <li><a href="/shoes">کفش</a></li>
                        <li><a href="/accessories">اکسسوری</a></li>
                    </ul>
                </div>
            </div>

            <div class="lebas-footer__enamad">
                <a href="<?php echo esc_url( $enamadUrl ); ?>" target="_blank" rel="noopener">
                    <?php if ( $enamadImg ) : ?>
                        <img src="<?php echo esc_url( $enamadImg ); ?>" alt="Enamad" />
                    <?php else : ?>
                        <span>اینماد</span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="lebas-footer__copyright">
                © <?php echo esc_html( date( 'Y' ) ); ?> لـبـاس — تمامی حقوق محفوظ است.
            </div>
        </footer>
        <?php
    }
}