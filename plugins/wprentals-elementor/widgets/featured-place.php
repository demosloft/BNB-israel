<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Featured_Place extends Widget_Base {

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
		return 'WpRentals_Featured_Place';
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
            return '<div class="wpestate_elementor_widget_title">'.__( 'WpRentals Featured Category', 'rentals-elementor' ).'</div>';
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
		return 'eicon-info-box';
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
                $featured_places_array =
                array(
                    'type1' =>__('type1','rentals-elementor'),
                    'type2' =>__('type2','rentals-elementor'),
                    'type3' =>__('type3', 'rentals-elementor'),
                );
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'rentals-elementor' ),
			]
		);




              $this->add_control(
			'place_id',
			[
                            'label' => __( 'Type the category name you want to show', 'rentals-elementor' ),
                            'label_block'=>true,
                            'type' => \Elementor\Controls_Manager::SELECT2,
                            'multiple' => false,
                            'options' => $all_tax_elemetor,
			]
		);

               $this->add_control(
			'type',
			[
                            'label' => __('Design Type', 'rentals-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                             'options' => $featured_places_array
			]
		);

                $this->add_control(
			'places_label',
			[
                            'label' => __( 'Featured label', 'rentals-elementor' ),
                            'type' => Controls_Manager::TEXT,
                             'label_block'=>true,
			]
		);



		$this->end_controls_section();


		/*
		*-------------------------------------------------------------------------------------------------
		* Start Sizes
		*/

		$this->start_controls_section(
				'size_section',
				[
										 'label'     => esc_html__('Item Settings', 'rentals-elementor'),
										 'tab'       => Controls_Manager::TAB_STYLE,
								 ]
		);


		$this->add_responsive_control(
				'places_height',
				[
							'label' => esc_html__('Item Height', 'rentals-elementor'),
							'type' => Controls_Manager::SLIDER,
							'range' => [
									'px' => [
											'min' => 150,
											'max' => 500,
									],
							],
							'devices' => [ 'desktop', 'tablet', 'mobile' ],
							'desktop_default' => [
									'size' => 300,
									'unit' => 'px',
							],
							'tablet_default' => [
									'size' => '',
									'unit' => 'px',
							],
							'mobile_default' => [
									'size' => '',
									'unit' => 'px',
							],
							'selectors' => [
																		'{{WRAPPER}} .places1.featuredplace' => 'height: {{SIZE}}{{UNIT}}!important;',

											],
					]
		);

		$this->add_responsive_control(
				'item_border_radius',
				[
																'label' => esc_html__('Border Radius', 'rentals-elementor'),
																'type' => Controls_Manager::DIMENSIONS,
																'size_units' => [ 'px', '%' ],
																'selectors' => [
																		'{{WRAPPER}} .places_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

																		],
												]
		);






		$this->end_controls_section();

		/*
		*-------------------------------------------------------------------------------------------------
		* Start Typografy
		*/

		$this->start_controls_section(
				'typography_section',
				[
												 'label'     => esc_html__('Typography', 'rentals-elementor'),
												 'tab'       => Controls_Manager::TAB_STYLE,
										 ]
		);

		$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
												 'name'     => 'tax_title',
												 'label'    => esc_html__('Title Typography', 'rentals-elementor'),
												 'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
												 'selector' => '{{WRAPPER}} .featured_listing_title',
												 'fields_options' => [
														 // Inner control name
														 'font_weight' => [
																 // Inner control settings
																 'default' => '500',
														 ],
														 'font_family' => [
																 'default' => 'Roboto',
														 ],
														 'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 24 ] ],
												 ],
										 ]
		);
		$this->add_responsive_control(
				'property_title_margin_bottom',
				[
																				 'label' => esc_html__('Title Margin Bottom(px)', 'rentals-elementor'),
																				 'type' => Controls_Manager::SLIDER,
																				 'range' => [
																								 'px' => [
																												 'min' => 0,
																												 'max' => 100,
																								 ],
																				 ],
																				 'devices' => [ 'desktop', 'tablet', 'mobile' ],
																				 'desktop_default' => [
																								 'size' => '',
																								 'unit' => 'px',
																				 ],
																				 'tablet_default' => [
																								 'size' => '',
																								 'unit' => 'px',
																				 ],
																				 'mobile_default' => [
																								 'size' => '',
																								 'unit' => 'px',
																				 ],
																				 'selectors' => [
																								 '{{WRAPPER}} .featured_listing_title' => 'margin-bottom: {{SIZE}}{{UNIT}};display:inline-block;',
																				 ],

																 ]
		);

		$this->add_responsive_control(
				'property_tagline_margin_bottom',
				[
																														'label' => esc_html__('Tagline Margin Bottom(px)', 'rentals-elementor'),
																														'type' => Controls_Manager::SLIDER,
																														'range' => [
																																						'px' => [
																																														'min' => 0,
																																														'max' => 100,
																																						],
																														],
																														'devices' => [ 'desktop', 'tablet', 'mobile' ],
																														'desktop_default' => [
																																						'size' => '',
																																						'unit' => 'px',
																														],
																														'tablet_default' => [
																																						'size' => '',
																																						'unit' => 'px',
																														],
																														'mobile_default' => [
																																						'size' => '',
																																						'unit' => 'px',
																														],
																														'selectors' => [
																																						'{{WRAPPER}} .category_tagline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
																														],
																						]
		);



		$this->add_responsive_control(
				'property_listings_margin_bottom',
				[
									'label' => esc_html__('Listings Number margin Bottom', 'rentals-elementor'),
									'type' => Controls_Manager::SLIDER,
									'range' => [
																	'px' => [
																									'min' => -200,
																									'max' => 200,
																	],
									],
									'devices' => [ 'desktop', 'tablet', 'mobile' ],
									'desktop_default' => [
																	'size' => '',
																	'unit' => 'px',
									],
									'tablet_default' => [
																	'size' => '',
																	'unit' => 'px',
									],
									'mobile_default' => [
																	'size' => '',
																	'unit' => 'px',
									],
									'selectors' => [
																	'{{WRAPPER}}  .featured_place_count' => 'top: {{SIZE}}{{UNIT}};',
																	'{{WRAPPER}} .type_3_class  .featured_place_count'=> 'margin-bottom: {{SIZE}}{{UNIT}};',
									],
				]
		);

		$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
												 'name'     => 'tax_tagline',
												 'label'    => esc_html__('Tagline Typography ', 'rentals-elementor'),
												 'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
												 'selector' => '{{WRAPPER}} .category_tagline','{{WRAPPER}} .places_label',
												 'fields_options' => [
														 // Inner control name
														 'font_weight' => [
																 // Inner control settings
																 'default' => '300',
														 ],
														 'font_family' => [
																 'default' => 'Roboto',
														 ],
														 'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ],
												 ],
										 ]
		);



		$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
												 'name'     => 'tax_listings',
												 'label'    => esc_html__('Listings Text Typography', 'rentals-elementor'),
												 'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
												 'selector' => '{{WRAPPER}} .featured_place_count',
												 'fields_options' => [
														 // Inner control name
														 'font_weight' => [
																 // Inner control settings
																 'default' => '300',
														 ],
														 'font_family' => [
																 'default' => 'Roboto',
														 ],
														 'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ],
												 ],
										 ]
		);


		$this->add_control(
				'tax_title_color',
				[
												 'label'     => esc_html__('Title Color', 'rentals-elementor'),
												 'type'      => Controls_Manager::COLOR,
												 'default'   => '',
												 'selectors' => [
														 '{{WRAPPER}} .featured_listing_title' => 'color: {{VALUE}}!important;',


												 ],
										 ]
		);

		$this->add_control(
				'tax_tagline_color',
				[
												 'label'     => esc_html__('Tagline Color', 'rentals-elementor'),
												 'type'      => Controls_Manager::COLOR,
												 'default'   => '',
												 'selectors' => [
														 '{{WRAPPER}}  .category_tagline' => 'color: {{VALUE}}!important;',
													    '{{WRAPPER}}  .places_label' => 'color: {{VALUE}}!important;',
												 ],
										 ]
		);

		$this->add_control(
				'tax_listings_color',
				[
										 'label'     => esc_html__('Listings text Color', 'rentals-elementor'),
										 'type'      => Controls_Manager::COLOR,
										 'default'   => '',
										 'selectors' => [
																		 '{{WRAPPER}}  .featured_place_count' => 'color: {{VALUE}}',

										 ],
		 ]
		);


		$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
												 'name'     => 'discover_listings',
												 'label'    => esc_html__('Discover Text Typography', 'rentals-elementor'),
												 'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
												 'selector' => '{{WRAPPER}} .featured_more a',
												 'fields_options' => [
														 // Inner control name
														 'font_weight' => [
																 // Inner control settings
																 'default' => '300',
														 ],
														 'font_family' => [
																 'default' => 'Roboto',
														 ],
														 'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ],
												 ],
										 ]
		);


		$this->add_control(
				'discover_color',
				[
										 'label'     => esc_html__('Discover text Color', 'rentals-elementor'),
										 'type'      => Controls_Manager::COLOR,
										 'default'   => '',
										 'selectors' => [
										 '{{WRAPPER}}  .featured_more a' => 'color: {{VALUE}}!important',
										 '{{WRAPPER}}  .featured_more i' => 'color: {{VALUE}}!important',

										 ],
		 ]
		);


		$this->add_control(
				'ovarlay_color_back',
				[
																											 'label'     => esc_html__('Image Overlay Backgorund Color', 'rentals-elementor'),
																											 'type'      => Controls_Manager::COLOR,
																											 'default'   => '',
																											 'selectors' => [
																																			 '{{WRAPPER}}  .listing-hover-gradient' => 'background: {{VALUE}};opacity: 1;background-image:none;height:100%;',

																											 ],
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

                $attributes['id']           =   $settings['place_id'] ;
                $attributes['type']         =   $settings['type'];
                $attributes['places_label'] =   $settings['places_label'];
                $attributes['places_height'] =   $settings['places_height'];





              echo  wpestate_featured_place($attributes);
	}


}
