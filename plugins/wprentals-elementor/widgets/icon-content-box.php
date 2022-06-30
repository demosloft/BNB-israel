<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Icon_Content_Box extends Widget_Base {

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
		return 'Wprentals_Icon_Content_Box';
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
		return __( 'WpRentals Icon Content Box', 'rentals-elementor' );
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
		return 'eicon-product-price';
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



        protected function register_controls() {
                $icon_position  =array('left'=>'left','central'=>'central');
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'rentals-elementor' ),
			]
		);


                  $this->add_control(
			'title',
			[
                            'label' => __( 'Box Title', 'rentals-elementor' ),
                            'type' => Controls_Manager::TEXT,
			]
		);

                $this->add_control(
			'image',
			[
                            'label' => __( 'Image', 'rentals-elementor' ),
                            'type' => Controls_Manager::MEDIA,
			]
		);


                $this->add_control(
			'content_box',
			[
                            'label' => __( 'Content of the box', 'rentals-elementor' ),
                            'type' => Controls_Manager::TEXTAREA,
			]
		);

                $this->add_control(
			'link',
			[
                            'label' => __( 'The link with http:// in front', 'rentals-elementor' ),
                            'type' => Controls_Manager::TEXT,
			]
		);

                $this->add_control(
			'icon_type',
			[
                            'label' => __('Icon/Image Postion', 'rentals-elementor' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => $icon_position
			]
		);


                $this->add_control(
			'title_font_size',
			[
                            'label' => __( 'Title Font Size', 'rentals-elementor' ),
                            'type' => Controls_Manager::TEXT,
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

                $attributes['title']                =   $settings['title'];
                $attributes['image']                =   $settings['image']['url'];
                $attributes['content_box']          =   $settings['content_box'];
                $attributes['link']                 =   $settings['link'];
                $attributes['icon_type']            =   $settings['icon_type'];
                $attributes['title_font_size']      =   $settings['title_font_size'];
                echo  wpestate_icon_container_function($attributes);
	}

	
}
