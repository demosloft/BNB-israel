<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Categories_Slider extends Widget_Base {

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
            return 'Wprentals_Categories_Slider';
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
		return __( 'WpRentals Categories Slider', 'rentals-elementor' );
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
		return 'eicon-slider-album';
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

                $all_tax_elementor                                      =   $this->elementor_transform($all_tax);


                $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'rentals-elementor' ),
			]
		);




                $this->add_control(
			'place_list',
			[
                            'label' => __( 'Type Categories, Actions, Cities or Areas (Neighborhoods) you want to show', 'rentals-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => true,
                            'options' => $all_tax_elementor,
			]
		);

                $this->add_control(
			'place_per_row',
			[
                            'label'     =>  __( 'Items per row', 'rentals-elementor' ),
                            'type'      =>  Controls_Manager::TEXT,
                            'defaults'  =>  3
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

				            $attributes['place_list']       =   $this -> wpresidence_send_to_shortcode( $settings['place_list'] );
				            $attributes['place_per_row']    =   $settings['place_per_row'];

				            echo  wpestate_places_slider($attributes);

				            if (   \Elementor\Plugin::instance()->editor->is_edit_mode() ) : ?>
				            <script>

				                jQuery('.estate_places_slider').each(function(){
				                    var items   = jQuery(this).attr('data-items-per-row');
				                    var auto    = parseInt(  jQuery(this).attr('data-auto') );
				                    var slick=jQuery(this).slick({
				                        infinite: true,
				                        slidesToShow: items,
				                        slidesToScroll: 1,
				                        dots: false,

				                        responsive: [
				                            {
				                             breakpoint:1025,
				                             settings: {
				                               slidesToShow: 2,
				                               slidesToScroll: 1
				                             }
				                            },
				                            {
				                              breakpoint: 480,
				                              settings: {
				                                slidesToShow: 1,
				                                slidesToScroll: 1
				                              }
				                            }
				                        ]
				                    });
				                    if(control_vars.is_rtl==='1'){
				                        jQuery(this).slick('slickSetOption','rtl',true,true);
				                        jQuery(this).slick('slidesToScroll','-1');
				                    }
				                });
				            </script>
				            <?php endif;

					}

	
}
