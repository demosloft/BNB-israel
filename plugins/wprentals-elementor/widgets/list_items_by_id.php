<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_List_Items_By_Id extends Widget_Base {

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
		return 'Wprentals_List_Items_By_Id';
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
		return __( 'WpRentals List Items by Id', 'rentals-elementor' );
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
		return 'eicon-post-list';
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
                global $all_tax;
                $all_tax_elemetor=$this->elementor_transform($all_tax);

                $items_type         =   array('properties'=>'properties','articles'=>'articles');

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
                          	'type' => Controls_Manager::TEXT,
                                'Label Block'

			]
		);

                $this->add_control(
			'type',
			[
                            'label' => __( 'What type of items', 'rentals-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'properties',
                            'options' => $items_type
			]
		);

                $this->add_control(
			'ids',
			[
				'label' => __( 'Items IDs', 'rentals-elementor' ),
                          	'type' => Controls_Manager::TEXT,
                                'Label Block'

			]
		);

                $this->add_control(
			'number',
			[
                            'label' => __( 'No of items', 'rentals-elementor' ),
                            'type' => Controls_Manager::TEXT,
			]
		);

                $this->add_control(
			'rownumber',
			[
                            'label' => __( 'No of items per row', 'rentals-elementor' ),
                            'type' => Controls_Manager::TEXT,
			]
		);




                $this->add_control(
			'link',
			[
				'label' => __( 'Link to global listing', 'rentals-elementor' ),
                          	'type' => Controls_Manager::TEXT,
                                'Label Block'

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

            $attributes['title']          =   $settings['title'];
            $attributes['type']           =   $settings['type'];
            $attributes['ids']            =   $settings['ids'];
            $attributes['number']         =   $settings['number'];
            $attributes['rownumber']      =   $settings['rownumber'];
            $attributes['link']           =   $settings['link'];


            echo  wpestate_list_items_by_id_function($attributes);
	}

	
}
