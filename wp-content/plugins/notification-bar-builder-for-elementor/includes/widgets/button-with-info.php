<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class NBBFEML_Button_With_Info extends Widget_Base {

    public function get_name() {
        return 'nbbfem_button_with_info';
    }

    public function get_title() {
        return esc_html__( 'Button With Info', 'nbbfem' );
    }

    public function get_icon() {
        return 'nbbfem_icon eicon-button';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return [ 'nbb', 'button', 'info' ];
    }

    public static function get_button_sizes() {
        return [
            'xs' => esc_html__( 'Extra Small', 'nbbfem' ),
            'sm' => esc_html__( 'Small', 'nbbfem' ),
            'md' => esc_html__( 'Medium', 'nbbfem' ),
            'lg' => esc_html__( 'Large', 'nbbfem' ),
            'xl' => esc_html__( 'Extra Large', 'nbbfem' ),
        ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'General Options', 'nbbfem' ),
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'nbbfem' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => esc_html__( 'Left', 'nbbfem' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'nbbfem' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'nbbfem' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
                'default' => 'center',
            ]
        );

        $this->add_control(
            'swap_elements',
            [
                'label' => esc_html__( 'Swap Elements', 'nbbfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('If you need button placed left & the info placed to the right. Enable this feature.', 'nbbfem'),
                'label_on' => esc_html__( 'Yes', 'nbbfem' ),
                'label_off' => esc_html__( 'No', 'nbbfem' ),
                'return_value' => 'yes',
                'default' => '',
                'separator' => 'after',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_contents' );
        $this->start_controls_tab(
         'tab_info_text',
         [
             'label' => esc_html__( 'Info Text', 'nbbfem' ),
         ]
        );

        $this->add_control(
            'info_text',
            [
                'label' => esc_html__( 'Text', 'nbbfem' ),
                'description' => esc_html__( 'HTML tags are allowed', 'nbbfem' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Upcoming latest shop update notice', 'nbbfem' ),
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
         'tab_button',
         [
             'label' => esc_html__( 'Button', 'nbbfem' ),
         ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__( 'Type', 'nbbfem' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'nbbfem' ),
                    'info' => esc_html__( 'Info', 'nbbfem' ),
                    'success' => esc_html__( 'Success', 'nbbfem' ),
                    'warning' => esc_html__( 'Warning', 'nbbfem' ),
                    'danger' => esc_html__( 'Danger', 'nbbfem' ),
                ],
                'prefix_class' => 'elementor-button-',
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => esc_html__( 'Text', 'nbbfem' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Click here', 'nbbfem' ),
                'placeholder' => esc_html__( 'Click here', 'nbbfem' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'nbbfem' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'nbbfem' ),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => esc_html__( 'Size', 'nbbfem' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'sm',
                'options' => self::get_button_sizes(),
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Icon', 'nbbfem' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label' => esc_html__( 'Icon Position', 'nbbfem' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Before', 'nbbfem' ),
                    'right' => esc_html__( 'After', 'nbbfem' ),
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_indent',
            [
                'label' => esc_html__( 'Icon Spacing', 'nbbfem' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => esc_html__( 'View', 'nbbfem' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->add_control(
            'button_css_id',
            [
                'label' => esc_html__( 'Button ID', 'nbbfem' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'nbbfem' ),
                'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'nbbfem' ),
                'separator' => 'before',

            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_info_text',
            [
                'label' => esc_html__( 'Info Text', 'nbbfem' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'info_text_color',
            [
                'label' => esc_html__( 'Text Color', 'nbbfem' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .nbbfem_info_text_wrapper' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'info_text_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .nbbfem_info_text_wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'info_text_shadow',
                'selector' => '{{WRAPPER}} .nbbfem_info_text_wrapper',
            ]
        );

        $this->add_responsive_control(
            'info_text_padding',
            [
                'label' => esc_html__( 'Padding', 'nbbfem' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .nbbfem_info_text_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_text_margin',
            [
                'label' => esc_html__( 'Margin', 'nbbfem' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .nbbfem_info_text_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Button', 'nbbfem' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'nbbfem' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'nbbfem' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__( 'Background Color', 'nbbfem' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_ACCENT,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'nbbfem' ),
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => esc_html__( 'Text Color', 'nbbfem' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => esc_html__( 'Background Color', 'nbbfem' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'nbbfem' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'nbbfem' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .elementor-button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'nbbfem' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__( 'Padding', 'nbbfem' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__( 'Margin', 'nbbfem' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render button widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'button', $settings['link'] );
            $this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
        }

        $this->add_render_attribute( 'button', 'class', 'elementor-button' );
        $this->add_render_attribute( 'button', 'role', 'button' );

        if ( ! empty( $settings['button_css_id'] ) ) {
            $this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
        }

        if ( ! empty( $settings['size'] ) ) {
            $this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
        }

        if ( $settings['hover_animation'] ) {
            $this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>

            <?php if( $settings['swap_elements'] != 'yes' ): ?>
                <div class="nbbfem_info_text_wrapper"><?php echo wp_kses_post( $settings['info_text'] ) ?></div>
            <?php endif; ?>

            <?php if( $settings['link']['url'] ): ?>
            <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                <?php $this->render_text(); ?>
            </a>
            <?php endif; ?>

            <?php if($settings['swap_elements'] == 'yes'): ?>
                <div class="nbbfem_info_text_wrapper"><?php echo wp_kses_post( $settings['info_text'] ) ?></div>
            <?php endif; ?>

        </div>
        <?php
    }

    /**
     * Render button text.
     */
    protected function render_text() {
        $settings = $this->get_settings_for_display();

        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

        if ( ! $is_new && empty( $settings['icon_align'] ) ) {
            //old default
            $settings['icon_align'] = $this->get_settings( 'icon_align' );
        }

        $this->add_render_attribute( [
            'content-wrapper' => [
                'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align' => [
                'class' => [
                    'elementor-button-icon',
                    'elementor-align-icon-' . $settings['icon_align'],
                ],
            ],
            'text' => [
                'class' => 'elementor-button-text',
            ],
        ] );
        ?>
        <span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
            <?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
            <span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
                <?php if ( $is_new || $migrated ) :
                    Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
                <?php endif; ?>
            </span>
            <?php endif; ?>
            <span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></span>
        </span>
        <?php
    }
}