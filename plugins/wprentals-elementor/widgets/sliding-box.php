<?php

namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;


if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Sliding_Box extends Widget_Base {

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Wprentals_Sliding_Box';
    }

    public function get_categories() {
        return ['wprentals'];
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Wprentals Sliding Box', 'rentals-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return '   eicon-email-field';
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends() {
        return [''];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    public function elementor_transform($input) {
        $output = array();
        if (is_array($input)) {
            foreach ($input as $key => $tax) {
                $output[$tax['value']] = $tax['label'];
            }
        }
        return $output;
    }

    protected function register_controls() {


        $repeater = new Repeater();

        $repeater->add_control(
                'title', [
            'label' => esc_html__('Title', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );

        $repeater->add_control(
                'show_open', [
            'label' => __('Open Box', 'rentals-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'rentals-elementor'),
            'label_off' => __('No', 'rentals-elementor'),
            'return_value' => 'yes',
            'default' => 'no',
                ]
        );


        $repeater->add_control(
                'read_me', [
            'label' => esc_html__('Read Me Text', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );

        $repeater->add_control(
                'read_me_link', [
            'label' => esc_html__('Read Me Link text', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );

        $repeater->add_control(
                'content', [
            'label' => esc_html__('Content', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );

        $repeater->add_control(
                'image', [
            'label' => __('Choose Image', 'plugin-domain'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [
                'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
                ]
        );



        $this->start_controls_section(
                'wpresidence_area_form_fields', [
            'label' => esc_html__('Boxes', 'rentals-elementor'),
                ]
        );

        $this->add_control(
                'form_fields', [
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    '_id' => 'name',
                    'title' => 'Title Here',
                    'read_me' => esc_html__('Learn More', 'rentals-elementor'),
                    'content' => esc_html__('', 'rentals-elementor'),
                    'image' => '',
                    'width' => '100',
                ],
            ],
            'title_field' => '{{{ title }}}',
                ]
        );



        $this->end_controls_section();


        $this->start_controls_section(
                'wpresidence_field_style', [
            'label' => esc_html__('Style', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'content_padding', [
            'label' => esc_html__('Padding', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .sliding-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );



        $this->add_control(
                'wpresidence_back_color', [
            'label' => esc_html__('Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .sliding-content-wrapper' => 'background-color: {{VALUE}};',
            ],
            'default' => '#fff',
            'scheme' => [
                'type' => \Elementor\Core\Schemes\Color::get_type(),
                'value' => \Elementor\Core\Schemes\Color::COLOR_3
            ],
                ]
        );

        $this->add_control(
                'wpresidence_title_color', [
            'label' => esc_html__('Title Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wpestate_sliding_box h4' => 'color: {{VALUE}};',
            ],
            'default' => '#484848',
            'scheme' => [
                'type' => \Elementor\Core\Schemes\Color::get_type(),
                'value' => \Elementor\Core\Schemes\Color::COLOR_3
            ],
                ]
        );


        $this->add_control(
                'wpresidence_font_color', [
            'label' => esc_html__('Font Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#5d6475',
            'selectors' => [
                '{{WRAPPER}} .wpestate_sliding_box p' => 'color: {{VALUE}};',
            ],
            'scheme' => [
                'type' => \Elementor\Core\Schemes\Color::get_type(),
                'value' => \Elementor\Core\Schemes\Color::COLOR_3
            ],
                ]
        );


        $this->add_control(
                'wpresidence_readme_color', [
            'label' => esc_html__('Read Me Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#b881fc',
            'selectors' => [
                '{{WRAPPER}} .sliding-content-action a' => 'color: {{VALUE}};',
            ],
            'scheme' => [
                'type' => \Elementor\Core\Schemes\Color::get_type(),
                'value' => \Elementor\Core\Schemes\Color::COLOR_3
            ],
                ]
        );


        $this->add_responsive_control(
                'content_border_radius', [
            'label' => esc_html__('Border Radius', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .wpestate_sliding_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );


        $this->end_controls_section();
        
        
         /* -------------------------------------------------------------------------------------------------
         * Start shadow section
         */
        $this->start_controls_section(
                'section_typografy', [
            'label' => esc_html__('Typography', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'title_typo',
            'label' => esc_html__('Title', 'rentals-elementor'),
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .wpestate_sliding_box h4',
            'fields_options' => [
                // Inner control name
                'font_weight' => [
                    // Inner control settings
                    'default' => '300',
                ],
                'font_family' => [
                    'default' => 'Roboto',
                ],
                'font_size' => ['default' => ['unit' => 'px', 'size' => 14]],
            ],
                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'content_typo',
            'label' => esc_html__('Content', 'rentals-elementor'),
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .wpestate_sliding_box p',
            'fields_options' => [
                // Inner control name
                'font_weight' => [
                    // Inner control settings
                    'default' => '300',
                ],
                'font_family' => [
                    'default' => 'Roboto',
                ],
                'font_size' => ['default' => ['unit' => 'px', 'size' => 14]],
            ],
                ]
        );
        
         $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'readme_typo',
            'label' => esc_html__('Read Me', 'rentals-elementor'),
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .sliding-content-action a',
            'fields_options' => [
                // Inner control name
                'font_weight' => [
                    // Inner control settings
                    'default' => '300',
                ],
                'font_family' => [
                    'default' => 'Roboto',
                ],
                'font_size' => ['default' => ['unit' => 'px', 'size' => 14]],
            ],
                ]
        );
        
        $this->end_controls_section();
        /*
         * -------------------------------------------------------------------------------------------------
         * End shadow section
         */
        

        /* -------------------------------------------------------------------------------------------------
         * Start shadow section
         */
        $this->start_controls_section(
                'section_grid_box_shadow', [
            'label' => esc_html__('Box Shadow', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow',
            'label' => esc_html__('Box Shadow', 'rentals-elementor'),
            'selector' => '{{WRAPPER}} .wpestate_sliding_box',
                ]
        );

        $this->end_controls_section();
        /*
         * -------------------------------------------------------------------------------------------------
         * End shadow section
         */
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        echo wpestate_sliding_box_shortcode($settings);
    }

}
