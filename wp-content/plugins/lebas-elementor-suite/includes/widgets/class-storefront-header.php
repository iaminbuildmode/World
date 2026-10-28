<?php
/**
 * Widget: Lebas Storefront Header.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Storefront_Header extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-storefront-header';
    }

    public function get_title() {
        return esc_html__( 'Lebas Storefront Header', 'lebas-elementor-suite' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content',
            [ 'label' => esc_html__( 'Content', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control( 'logo_text', [ 'label' => 'Logo text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'لـبـاس' ] );
        $this->add_control( 'dept_women_url', [ 'label' => 'Women URL', 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/women' ] ] );
        $this->add_control( 'dept_kids_url', [ 'label' => 'Kids URL', 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/kids' ] ] );
        $this->add_control( 'dept_men_url', [ 'label' => 'Men URL', 'type' => \Elementor\Controls_Manager::URL, 'default' => [ 'url' => '/men' ] ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $dw = ! empty( $s['dept_women_url']['url'] ) ? $s['dept_women_url']['url'] : '/women';
        $dk = ! empty( $s['dept_kids_url']['url'] ) ? $s['dept_kids_url']['url'] : '/kids';
        $dm = ! empty( $s['dept_men_url']['url'] ) ? $s['dept_men_url']['url'] : '/men';
        ?>
        <header class="lebas-header">
            <div class="lebas-header__row1">
                <nav class="lebas-header__dept-switcher" aria-label="Departments">
                    <a href="<?php echo esc_url( $dw ); ?>">بانوان</a>
                    <a href="<?php echo esc_url( $dk ); ?>">کودکان</a>
                    <a href="<?php echo esc_url( $dm ); ?>">آقایان</a>
                </nav>
                <div class="lebas-header__logo"><?php echo esc_html( $s['logo_text'] ); ?></div>
                <div class="lebas-header__actions">
                    <a href="/account" aria-label="Account">حساب</a>
                    <a href="/wishlist" aria-label="Wishlist">علاقه‌مندی‌ها</a>
                    <a href="/cart" aria-label="Cart">سبد خرید</a>
                </div>
            </div>
            <div class="lebas-header__row2">
                <nav class="lebas-header__menu" aria-label="Categories">
                    <a href="/clothing">پوشاک</a>
                    <a href="/shoes">کفش</a>
                    <a href="/accessories">اکسسوری</a>
                    <a href="/designers">برندها</a>
                    <a href="/new-arrivals">جدیدترین‌ها</a>
                </nav>
                <div class="lebas-header__search">
                    <form class="lebas-search-underline" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
                        <input type="search" name="s" placeholder="<?php echo esc_attr__( 'جستجو...', 'lebas-elementor-suite' ); ?>" />
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><line x1="16.5" y1="16.5" x2="21" y2="21"></line></svg>
                    </form>
                </div>
            </div>
        </header>
        <?php
    }
}