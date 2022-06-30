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

/**
 * Elementor Properties Widget.
 * @since 2.0
 */

class WpRentals_Testimonial_Slider extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'WpRentals_Testimonial_Slider';
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
        return esc_html__('Testimonial Slider', 'rentals-elementor');
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
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['wprentals'];
    }

    protected function register_controls() {

        
        
        $this->start_controls_section(
            'content_section', [
            'label' => esc_html__('Content', 'rentals-elementor'),
                ]
        );
        
        $repeater = new Repeater();


        $repeater->add_control(
            'testimonial_title', [
            'label' => esc_html__('Title', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
        $repeater->add_control(
                'testimonial_name', [
            'label' => esc_html__('Person Name', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
        
        $repeater->add_control(
                'testimonial_job', [
            'label' => esc_html__('Person Position', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
          
         $repeater->add_control(
                'testimonial_stars', [
            'label' => esc_html__('Stars (1 to 5)', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
          
        
        $repeater->add_control(
            'testimonial_text', [
            'label' => esc_html__('Testimonial Text', 'rentals-elementor'),
            'type' => \Elementor\Controls_Manager::WYSIWYG,

            'default' => '',
                ]
        );

        $repeater->add_control(
                'testimonial_image',
                [
                        'label' => __( 'Choose Image', 'plugin-domain' ),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                                'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                ]
        );

        
        
        $this->add_control(
			'list',
			[
				'label' => __( 'Repeater List', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'testimonial_title' => __( 'Testimonial #1', 'rentals-elementor' ),
						'testimonial_text' => __( 'Testimonial content. Click the edit button to change this text.', 'rentals-elementor' ),
					],
					[
						'testimonial_title' => __( 'Testimonial #2', 'rentals-elementor' ),
						'testimonial_text' => __( 'Testimonial content. Click the edit button to change this text.', 'rentals-elementor'),
					],
				],
				'title_field' => '{{{ testimonial_title }}}',
			]
		);
        
        
        
        
        
        
        




        $this->end_controls_section();
        
        
        
        
        
        /*
        * -------------------------------------------------------------------------------------------------
        * Start typography section
       */
        $this->start_controls_section(
            'typography_section', [
            'label' => esc_html__('Style', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'testimonial_title',
            'label' => esc_html__('Title Typography', 'rentals-elementor'),
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .item_testimonial_title',
                ]
        );
        
          $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'testimonial_content',
            'label' => esc_html__('Content Typography', 'rentals-elementor'),
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .item_testimonial_text',
                ]
        );

          
            $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'testimonial_name',
            'label' => esc_html__('Name Typography', 'rentals-elementor'),
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .item_testimonial_name',
                ]
        );

            
              $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'testimonial_postion',
            'label' => esc_html__('Position Typography', 'rentals-elementor'),
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .item_testimonial_job',
                ]
        );

        
        $this->end_controls_section();

       

        
         /*
         * -------------------------------------------------------------------------------------------------
         * Start color section
         */
        $this->start_controls_section(
                'section_grid_colors', [
            'label' => esc_html__('Colors', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'unit_backgorund', [
            'label' => esc_html__('Background', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .item_testimonial_content' => 'background-color: {{VALUE}}',
            ],
                ]
        );

        $this->add_control(
                'title_color', [
            'label' => esc_html__('Title Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .item_testimonial_title' => 'color: {{VALUE}}',
            ],
                ]
        );

        $this->add_control(
                'content_color', [
            'label' => esc_html__('Content Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .item_testimonial_text' => 'color: {{VALUE}}',
            ],
                ]
        );

        $this->add_control(
                'name_color', [
            'label' => esc_html__('Name Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .item_testimonial_name' => 'color: {{VALUE}}',
            ],
                ]
        );

        $this->add_control(
                'item_testimonial_job', [
            'label' => esc_html__('Position Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .item_testimonial_job' => 'color: {{VALUE}}',
            ],
                ]
        );


       

        $this->end_controls_section();
        /*
         * -------------------------------------------------------------------------------------------------
         * End color section
         */
        
       
         $this->start_controls_section(
                'arrow_section', [
            'label' => esc_html__('Arrows Style', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        
           $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rentals-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpestate_testimonial_slider button.slick-prev.slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpestate_testimonial_slider button.slick-next.slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                  ],
            ]
        );
        
            $this->add_control(
                   'arrow_color',
                   [
                       'label'     => esc_html__( 'Arrow Color', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                           '{{WRAPPER}} .wpestate_testimonial_slider button.slick-prev.slick-arrow:before' => 'color: {{VALUE}}',
                           '{{WRAPPER}} .wpestate_testimonial_slider button.slick-next.slick-arrow:before' => 'color: {{VALUE}}',
                       ],
                   ]
            );
            
              $this->add_control(
                   'arrow_bck_color',
                   [
                       'label'     => esc_html__( 'Arrow Background Color', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                           '{{WRAPPER}} .wpestate_testimonial_slider button.slick-prev.slick-arrow' => 'background-color: {{VALUE}}',
                           '{{WRAPPER}} .wpestate_testimonial_slider button.slick-next.slick-arrow' => 'background-color: {{VALUE}}',
                       ],
                   ]
            );
           
           
            
            $this->add_control(
                   'arrow_color_hover',
                   [
                       'label'     => esc_html__( 'Arrow Color Hover', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                            '{{WRAPPER}} .wpestate_testimonial_slider button.slick-prev.slick-arrow:hover:before' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .wpestate_testimonial_slider button.slick-next.slick-arrow:hover:before' => 'color: {{VALUE}}',
                       ],
                   ]
               );
            
             $this->add_control(
                   'arrow_bck_color_hover',
                   [
                       'label'     => esc_html__( 'Arrow Background Color Hover', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                            '{{WRAPPER}} .wpestate_testimonial_slider button.slick-prev.slick-arrow:hover' => 'background-color: {{VALUE}}',
                            '{{WRAPPER}} .wpestate_testimonial_slider button.slick-next.slick-arrow:hover' => 'background-color: {{VALUE}}',
                       ],
                   ]
               );

             
               $this->add_control(
                   'bullet_color',
                   [
                       'label'     => esc_html__( 'Dot Color', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                            '{{WRAPPER}} .wpestate_testimonial_slider .slick-dots li ' => 'background-color: {{VALUE}}',
                        
                       ],
                   ]
               );

               
                 $this->add_control(
                   'bullet_color_active',
                   [
                       'label'     => esc_html__( 'Dot Color Active', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                            '{{WRAPPER}} .wpestate_testimonial_slider .slick-dots li.slick-active ' => 'background-color: {{VALUE}}',

                       ],
                   ]
               );


           $this->end_controls_section();
        
        
        /*
         * -------------------------------------------------------------------------------------------------
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
            'selector' => '{{WRAPPER}} .wpestate_testimonial_slider ',
                ]
        );

        $this->end_controls_section();
        /*
         * -------------------------------------------------------------------------------------------------
         * End shadow section
         */
     
    }

    protected function render() {
        global $post;
        $settings = $this->get_settings_for_display();
     

        print   wpestate_testimonial_slider( $settings);
        

    }

   

  

}

//end class
