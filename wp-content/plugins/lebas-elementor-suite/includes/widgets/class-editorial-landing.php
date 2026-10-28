<?php
/**
 * Widget: Lebas Editorial Landing (department landing, 5 sections).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lebas_Widget_Editorial_Landing extends Lebas_Widget_Base {

    public function get_name() {
        return 'lebas-editorial-landing';
    }

    public function get_title() {
        return esc_html__( 'Lebas Editorial Landing', 'lebas-elementor-suite' );
    }

    protected function register_controls() {

        // Hero
        $this->start_controls_section(
            'hero',
            [ 'label' => esc_html__( 'Hero Banner', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $this->add_control( 'hero_image', [ 'label' => 'Image', 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $this->add_control( 'hero_title', [ 'label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'جدیدترین‌ها' ] );
        $this->add_control( 'hero_cta_text', [ 'label' => 'CTA text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'مشاهده همه' ] );
        $this->add_control( 'hero_cta_link', [ 'label' => 'CTA link', 'type' => \Elementor\Controls_Manager::URL ] );
        $this->end_controls_section();

        // Two topics
        $this->start_controls_section(
            'topics',
            [ 'label' => esc_html__( 'Two Topics', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $rep = new \Elementor\Repeater();
        $rep->add_control( 'image', [ 'label' => 'Image', 'type' => \Elementor\Controls_Manager::MEDIA ] );
        $rep->add_control( 'title', [ 'label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'عنوان' ] );
        $rep->add_control( 'link', [ 'label' => 'Link', 'type' => \Elementor\Controls_Manager::URL ] );
        $this->add_control(
            'topics_list',
            [
                'label'       => 'Topics',
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $rep->get_controls(),
                'default'     => [ [ 'title' => 'پوشاک' ], [ 'title' => 'کفش' ] ],
                'title_field' => '{{{ title }}}',
            ]
        );
        $this->end_controls_section();

        // Entry doors
        $this->start_controls_section(
            'doors',
            [ 'label' => esc_html__( 'Entry Doors', 'lebas-elementor-suite' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
        );
        $rep2 = new \Elementor\Repeater();
        $rep2->add_control( 'label', [ 'label' => 'Label', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'پوشاک' ] );
        $rep2->add_control( 'link', [ 'label' => 'Link', 'type' => \Elementor\Controls_Manager::URL ] );
        $this->add_control(
            'doors_list',
            [
                'label'       => 'Doors',
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $rep2->get_controls(),
                'default'     => [
                    [ 'label' => 'پوشاک' ],
                    [ 'label' => 'کفش' ],
                    [ 'label' => 'اکسسوری' ],
                    [ 'label' => 'برندها' ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $heroImg = ! empty( $s['hero_image']['url'] ) ? $s['hero_image']['url'] : '';
        $ctaUrl = ! empty( $s['hero_cta_link']['url'] ) ? $s['hero_cta_link']['url'] : '#';
        ?>
        <div class="lebas-editorial">
            <!-- 1. Big Editorial Hero -->
            <section class="lebas-editorial__hero">
                <?php if ( $heroImg ) : ?>
                    <img src="<?php echo esc_url( $heroImg ); ?>" alt="" loading="lazy" />
                <?php endif; ?>
                <?php if ( ! empty( $s['hero_title'] ) ) : ?>
                    <h1 class="lebas-editorial__hero-title"><?php echo esc_html( $s['hero_title'] ); ?></h1>
                <?php endif; ?>
            </section>

            <!-- 2. Runway Fresh Carousel placeholder -->
            <section>
                <h2 class="lebas-editorial__section-title">جدیدترین‌ها</h2>
                <a class="lebas-editorial__view-all" href="<?php echo esc_url( $ctaUrl ); ?>"><?php echo esc_html( $s['hero_cta_text'] ); ?></a>
            </section>

            <!-- 3. Two Topics -->
            <section class="lebas-editorial__two-topics">
                <?php foreach ( $s['topics_list'] as $t ) :
                    $tUrl = ! empty( $t['link']['url'] ) ? $t['link']['url'] : '#';
                    $tImg = ! empty( $t['image']['url'] ) ? $t['image']['url'] : '';
                    ?>
                    <a class="lebas-editorial__topic" href="<?php echo esc_url( $tUrl ); ?>">
                        <?php if ( $tImg ) : ?>
                            <img src="<?php echo esc_url( $tImg ); ?>" alt="<?php echo esc_attr( $t['title'] ); ?>" loading="lazy" />
                        <?php endif; ?>
                        <h3 class="lebas-editorial__topic-title"><?php echo esc_html( $t['title'] ); ?></h3>
                    </a>
                <?php endforeach; ?>
            </section>

            <!-- 4. Finest Edit placeholder -->
            <section>
                <h2 class="lebas-editorial__section-title">منتخب سردبیر</h2>
            </section>

            <!-- 5. Entry Doors -->
            <section class="lebas-editorial__entry-doors">
                <?php foreach ( $s['doors_list'] as $d ) :
                    $dUrl = ! empty( $d['link']['url'] ) ? $d['link']['url'] : '#';
                    ?>
                    <a class="lebas-editorial__door" href="<?php echo esc_url( $dUrl ); ?>">
                        <?php echo esc_html( $d['label'] ); ?>
                    </a>
                <?php endforeach; ?>
            </section>
        </div>
        <?php
    }
}