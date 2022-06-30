<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Recent_Items_Slider extends Widget_Base {

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
            return 'Wprentals_Recent_Items_Slider';
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
		return __( 'WpRentals Recent Item Slider', 'rentals-elementor' );
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
		return 'eicon-post-slider';
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
                global $wprentals_property_category_values;
                global $wprentals_property_action_category_values;
                global $wprentals_property_city_values;
                global $wprentals_property_area_values;

                $wprentals_property_category_values_elementor           =   $this->elementor_transform($wprentals_property_category_values);
                $wprentals_property_action_category_values_elementor    =   $this->elementor_transform($wprentals_property_action_category_values);
		$wprentals_property_city_values_elementor               =   $this->elementor_transform($wprentals_property_city_values);
                $wprentals_property_area_values_elementor               =   $this->elementor_transform($wprentals_property_area_values);
                $featured_listings  =   array('no'=>'no','yes'=>'yes');

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
			]
		);

                $this->add_control(
			'category_ids',
			[
                            'label' => __( 'List of category names (*only for properties)', 'rentals-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $wprentals_property_category_values_elementor,
			]
		);
                $this->add_control(
			'action_ids',
			[
                            'label' => __( 'List of action names (*only for properties)', 'rentals-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $wprentals_property_action_category_values_elementor,
			]
		);
                $this->add_control(
			'city_ids',
			[
                            'label' => __( 'List of city names (*only for properties)', 'rentals-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $wprentals_property_city_values_elementor,
			]
		);
                $this->add_control(
			'area_ids',
			[
                            'label' => __( 'List of area names (*only for properties)', 'rentals-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $wprentals_property_area_values_elementor,
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
			'show_featured_only',
			[
                            'label' => __( 'Show featured listings only?', 'rentals-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'no',
                            'options' => $featured_listings
			]
		);
                $this->add_control(
			'autoscroll',
			[
				'label' => __( 'Auto scroll period', 'rentals-elementor' ),
                          	'type' => Controls_Manager::TEXT,
                                'default' => '0',

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
            $attributes['title']              =   $settings['title'];
            $attributes['category_ids']       =   $this -> wpresidence_send_to_shortcode( $settings['category_ids'] );
            $attributes['action_ids']         =   $this -> wpresidence_send_to_shortcode( $settings['action_ids'] );
            $attributes['city_ids']           =   $this -> wpresidence_send_to_shortcode( $settings['city_ids'] );
            $attributes['area_ids']           =   $this -> wpresidence_send_to_shortcode( $settings['area_ids'] );
            $attributes['show_featured_only'] =   $settings['show_featured_only'];
            $attributes['autoscroll']         =   $settings['autoscroll'];
						$attributes['number']         =   $settings['number'];

            echo  wpestate_slider_recent_posts_pictures($attributes);
	}


}
