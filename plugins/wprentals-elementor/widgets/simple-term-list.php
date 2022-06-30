<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Simple_Term_list extends Widget_Base {

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
            return 'Wprentals_Simple_Term_list';
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
		return __( 'WpRentals Simple Term List', 'rentals-elementor' );
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
		return 'eicon-editor-list-ul';
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



            $categories =   array(
                'property_action_category'=>'Property Category',
                'property_category'=>'Property type',
                'property_city'=>'Property City',
                'property_area'=>'Property Area',
                'property_features'=>'Property Features',
                'property_status'=>'Property Status');

                $list_type  =   array(
                                    'horizontal'=>'horizontal',
                                    'vertical'=>'vertical');
                $term_show_count=array('yes'=>'yes','no'=>'no');



                $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'rentals-elementor' ),
			]
		);




                $this->add_control(
			'term_list_category',
			[
                            'label' => __( 'Category', 'rentals-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'multiple' => false,
                            'options' => $categories,
			]
		);

                $this->add_control(
			'term_list_type',
			[
                            'label'     =>  __( 'List style type', 'rentals-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => $list_type,
			]
		);

                $this->add_control(
			'term_show_count',
			[
                            'label'     =>  __( 'Show Term Count', 'rentals-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => $term_show_count,
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

         public function wpresidence_send_to_shortcode($input){
            $output='';
            if($input!==''){
                $numItems = count($input);
                $i = 0;

                foreach ($input as $key=>$value){
                    $output.=$value;
                    if(++$i !== $numItems) {
                      $output.=', ';
                    }
                }
            }
            return $output;
        }

	protected function render() {
            $settings = $this->get_settings_for_display();

            $attributes['term_list_category']       =   $settings['term_list_category'] ;
            $attributes['term_list_type']           =   $settings['term_list_type'];
            $attributes['term_show_count']          =   $settings['term_show_count'];
            echo  wpestate_simple_term_list($attributes);
	}


}
