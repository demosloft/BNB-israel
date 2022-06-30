<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Box_Shadow;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Advanced_Search extends Widget_Base {

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
		return 'Wprentals_Advanced_Search';
	}

        public function get_categories() {
		return [ 'wprentals' ];
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
		return __( 'WpRentals Advanced Search', 'rentals-elementor' );
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
		return 'eicon-search';
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
	return [ '' ];
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

    public function elementor_transform($input){
            $output=array();
            if( is_array($input) ){
                foreach ($input as $key=>$tax){
                    $output[$tax['value']]=$tax['label'];
                }
            }
            return $output;
        }

        protected function register_controls() {



            $this->start_controls_section(
                    'section_content',
                    [
                            'label' => __( 'Content', 'rentals-elementor' ),
                    ]
            );


            $this->add_control(
                    'title',
                    [
                        'label' => __( 'Title', 'rentals-elementor' ),
                        'label_block'=>true,
                        'type' => Controls_Manager::TEXT,
                    ]
            );


            $this->end_controls_section();



            
             /*
              *-------------------------------------------------------------------------------------------------
              * Start shadow section
              */
              $this->start_controls_section(
                'section_grid_box_shadow',
                [
                    'label' => esc_html__( 'Box Shadow', 'residence-elementor' ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name'     => 'box_shadow',
                        'label'    => esc_html__( 'Box Shadow', 'residence-elementor' ),
                        'selector' => '{{WRAPPER}} .advanced_search_shortcode',
                    ]
                );

                $this->end_controls_section();
              /*
              *-------------------------------------------------------------------------------------------------
              * End shadow section
              */

                /*-------------------------------------------------------------------------------------------------
              * Start color section
              */
              $this->start_controls_section(
                  'section_grid_colors',
                  [
                      'label' => esc_html__( 'Colors', 'residence-elementor' ),
                      'tab'   => Controls_Manager::TAB_STYLE,
                  ]
              );

              $this->add_control(
                  'unit_color',
                  [
                      'label'     => esc_html__( 'Background Color', 'residence-elementor' ),
                      'type'      => Controls_Manager::COLOR,
                      'default'   => '',
                      'selectors' => [
                          '{{WRAPPER}} .advanced_search_shortcode' => 'background-color: {{VALUE}}',

                      ],
                  ]
              );

              $this->add_control(
                  'unit_border_color',
                  [
                      'label'     => esc_html__( 'Border Color', 'residence-elementor' ),
                      'type'      => Controls_Manager::COLOR,
                      'default'   => '',
                      'selectors' => [
                          '{{WRAPPER}} .advanced_search_shortcode' => 'border-color: {{VALUE}}',
                      ],
                  ]
              );
              
                $this->add_responsive_control(
                'unit_border_width', [
                'label' => esc_html__('Border Width', 'residence-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'placeholder' => '1',
                'size_units' => ['px'],
                'selectors' => [
                     '{{WRAPPER}} .advanced_search_shortcode' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );

            $this->add_responsive_control(
                'field_border_radius', [
                'label' => esc_html__('Border Radius', 'residence-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                      '{{WRAPPER}} .advanced_search_shortcode' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                ]
            );

              
            $this->add_control(
                  'buttons_back_color',
                  [
                      'label'     => esc_html__( 'Button Background Color', 'residence-elementor' ),
                      'type'      => Controls_Manager::COLOR,
                      'default'   => '',
                      'selectors' => [
                            '{{WRAPPER}} .advanced_search_submit_button' => 'background: {{VALUE}}',
                    
                      ],
                  ]
              );

             $this->add_control(
                  'buttons_font_color',
                  [
                      'label'     => esc_html__( 'Button  Color', 'residence-elementor' ),
                      'type'      => Controls_Manager::COLOR,
                      'default'   => '',
                      'selectors' => [
                          '{{WRAPPER}} .advanced_search_submit_button' => 'color: {{VALUE}}',
                      ],
                  ]
              );
             
             $this->add_control(
                  'buttons_back_color_hover',
                  [
                      'label'     => esc_html__( 'Button Background Color Hover', 'residence-elementor' ),
                      'type'      => Controls_Manager::COLOR,
                      'default'   => '',
                      'selectors' => [
                            '{{WRAPPER}} .advanced_search_submit_button:hover' => 'background: {{VALUE}}',
                       ],
                  ]
              );

             $this->add_control(
                  'buttons_font_color_hover',
                  [
                      'label'     => esc_html__( 'Button  Color', 'residence-elementor' ),
                      'type'      => Controls_Manager::COLOR,
                      'default'   => '',
                      'selectors' => [
                          '{{WRAPPER}} .advanced_search_submit_button:hover' => 'color: {{VALUE}}',
                      ],
                  ]
              );
            
            
              $this->end_controls_section();
              /*
              *-------------------------------------------------------------------------------------------------
              * End color section
              */
                
                $this->start_controls_section(
                         'typography_section',
                         [
                             'label'     => esc_html__( 'Typography', 'residence-elementor' ),
                             'tab'       => Controls_Manager::TAB_STYLE,
                         ]
                     );

                     $this->add_group_control(
                         Group_Control_Typography::get_type(),
                         [
                             'name'     => 'property_title',
                             'label'    => esc_html__( 'Property Title', 'residence-elementor' ),
                             'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                             'selector' => '{{WRAPPER}} .advanced_search_shortcode .shortcode_title_adv',
                         ]
                     );

                $this->end_controls_section();
                
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
                $attributes['title'] =   $settings['title'];
                echo  wpestate_advanced_search_function($attributes);
	}


}
