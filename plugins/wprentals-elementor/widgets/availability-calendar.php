<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Avalability_Calendar extends Widget_Base {

    
        public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);

            wp_register_script( 'avalability_control_elementor', trailingslashit( get_template_directory_uri() ).'js/avalability_control_elementor.js', [ 'elementor-frontend' ], '1.0.0', true );
        }
   
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
		return 'Wprentals_Avalability_Calendar';
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
		return __( 'WpRentals Availability Calendar', 'rentals-elementor' );
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
		return 'eicon-post';
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
	return [ 'avalability_control_elementor' ];
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
                    'article_id',
                    [
                        'label' => __( 'Id of the property', 'rentals-elementor' ),
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
                        'selector' => '{{WRAPPER}} .property_page_container',
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
                          '{{WRAPPER}} .property_page_container,{{WRAPPER}} .month-title,{{WRAPPER}} .all-front-calendars,{{WRAPPER}} .calendar-legend,{{WRAPPER}}  .all-front-calendars .booking-calendar-wrapper' => 'background-color: {{VALUE}}',

                   
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
                             'label'    => esc_html__( 'Section Title', 'residence-elementor' ),
                             'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                             'selector' => '{{WRAPPER}} #listing_calendar',
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
                $attributes['id']    =   $settings['article_id'];
                echo wpestate_property_show_avalability( $settings['article_id']);
       
          
	}


}
