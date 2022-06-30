<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Box_Shadow;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


class Wprentals_Recent_Items_v2 extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'Wprentals_Recent_Items_v2';
    }

    public function get_categories()
    {
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
    public function get_title()
    {
        return __('WpRentals Recent Items v2', 'rentals-elementor');
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
    public function get_icon()
    {
        return 'eicon-posts-masonry';
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
    public function get_script_depends()
    {
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

    public function elementor_transform($input)
    {
        $output=array();
        if (is_array($input)) {
            foreach ($input as $key=>$tax) {
                $output[$tax['value']]=$tax['label'];
            }
        }
        return $output;
    }

    protected function register_controls()
    {
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
        $items_type         =   array('properties'=>'properties','articles'=>'articles');
        $recent_items_space            = array('yes'=>'yes','no'=>'no');

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'rentals-elementor'),
            ]
        );



        $this->add_control(
            'title',
            [
                            'label' => __('Title', 'rentals-elementor'),
                            'type' => Controls_Manager::TEXT,
            ]
        );



        $this->add_control(
            'number',
            [
                            'label' => __('No of items', 'rentals-elementor'),
                            'type' => Controls_Manager::TEXT,
                             'default' =>3,
            ]
        );

        $this->add_control(
            'rownumber',
            [
                            'label' => __('No of items per row', 'rentals-elementor'),
                            'type' => Controls_Manager::TEXT,
                            'default' =>3,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link to global listing', 'rentals-elementor'),
                              'type' => Controls_Manager::TEXT,
                                'Label Block'

            ]
        );


        $this->add_control(
            'random_pick',
            [
                            'label' => __('Random Pick ?', 'rentals-elementor'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'no',
                            'options' => $featured_listings
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'filters_section',
            [
                                'label'     => esc_html__('Filters', 'rentals-elementor'),
                                'tab'       => Controls_Manager::TAB_CONTENT,
                        ]
        );

        $this->add_control(
            'category_ids',
            [
                                                        'label' => __('List of category names (*only for properties)', 'rentals-elementor'),
                                                        'label_block'=>true,
                                                        'type' => \Elementor\Controls_Manager::SELECT2,
                                                        'multiple' => true,
                                                        'options' => $wprentals_property_category_values_elementor,
                ]
        );
        $this->add_control(
            'action_ids',
            [
                                                        'label' => __('List of action names (*only for properties)', 'rentals-elementor'),
                                                        'label_block'=>true,
                                                        'type' => \Elementor\Controls_Manager::SELECT2,
                                                        'multiple' => true,
                                                        'options' => $wprentals_property_action_category_values_elementor,
                ]
        );
        $this->add_control(
            'city_ids',
            [
                                                        'label' => __('List of city names (*only for properties)', 'rentals-elementor'),
                                                        'label_block'=>true,
                                                        'type' => \Elementor\Controls_Manager::SELECT2,
                                                        'multiple' => true,
                                                        'options' => $wprentals_property_city_values_elementor,
                ]
        );

        $this->add_control(
            'area_ids',
            [
                                                        'label' => __('List of area names (*only for properties)', 'rentals-elementor'),
                                                        'label_block'=>true,
                                                        'type' => \Elementor\Controls_Manager::SELECT2,
                                                        'multiple' => true,
                                                        'options' => $wprentals_property_area_values_elementor,
                ]
        );


        $this->add_control(
            'show_featured_only',
            [
                                            'label' => __('Show featured listings only?', 'rentals-elementor'),
                                            'type' => \Elementor\Controls_Manager::SELECT,
                                            'default' => 'no',
                                            'options' => $featured_listings
                            ]
        );
        $this->end_controls_section();



        /*
        *-------------------------------------------------------------------------------------------------
        * Start Hide sections
        */
        $this->start_controls_section(
            'hide_show_section',
            [
                       'label'     => esc_html__('Show/Hide Data', 'rentals-elementor'),
                       'tab'       => Controls_Manager::TAB_CONTENT,
                   ]
        );



        $this->add_control(
            'hide_favorites',
            [
                                         'label' => esc_html__('Hide Add to Favorites', 'rentals-elementor'),
                                         'type' => Controls_Manager::SWITCHER,
                                         'label_on' => esc_html__('Yes', 'rentals-elementor'),
                                         'label_off' => esc_html__('No', 'rentals-elementor'),
                                         'return_value' => 'none',
                                         'default' => '',
                                         'selectors' => [
                                                 '{{WRAPPER}}  .property_unit_action' => 'display: {{VALUE}};',
                                         ],
                                 ]
        );

        $this->add_control(
            'hide_featured',
            [
                                     'label' => esc_html__('Hide Featured Label', 'rentals-elementor'),
                                     'type' => Controls_Manager::SWITCHER,
                                     'label_on' => esc_html__('Yes', 'rentals-elementor'),
                                     'label_off' => esc_html__('No', 'rentals-elementor'),
                                     'return_value' => 'none',
                                     'default' => '',
                                     'selectors' => [
                                             '{{WRAPPER}}  .featured_div' => 'display: {{VALUE}};',
                                     ],
                             ]
        );

        $this->add_control(
            'hide_title',
            [
                                        'label' => esc_html__('Hide Title', 'rentals-elementor'),
                                        'type' => Controls_Manager::SWITCHER,
                                        'label_on' => esc_html__('Yes', 'rentals-elementor'),
                                        'label_off' => esc_html__('No', 'rentals-elementor'),
                                        'return_value' => 'none',
                                        'default' => '',
                                        'selectors' => [
                                                '{{WRAPPER}}  .category_name .listing_title_unit' => 'display: {{VALUE}};',
                                        ],
                                ]
        );

        $this->add_control(
            'hide_location',
            [
                                     'label' => esc_html__('Hide Location', 'rentals-elementor'),
                                     'type' => Controls_Manager::SWITCHER,
                                     'label_on' => esc_html__('Yes', 'rentals-elementor'),
                                     'label_off' => esc_html__('No', 'rentals-elementor'),
                                     'return_value' => 'none',
                                     'default' => '',
                                     'selectors' => [
                                             '{{WRAPPER}}  .category_tagline.map_icon' => 'display: {{VALUE}};',
                                     ],
                             ]
        );


        $this->add_control(
            'hide_categories',
            [
                                    'label' => esc_html__('Hide Categories', 'rentals-elementor'),
                                    'type' => Controls_Manager::SWITCHER,
                                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                                    'label_off' => esc_html__('No', 'rentals-elementor'),
                                    'return_value' => 'none',
                                    'default' => '',
                                    'selectors' => [
                                            '{{WRAPPER}}  .category_tagline.actions_icon' => 'display: {{VALUE}};',
                                    ],
                            ]
        );

        $this->add_control(
            'hide_price',
            [
                                'label' => esc_html__('Hide Price', 'rentals-elementor'),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => esc_html__('Yes', 'rentals-elementor'),
                                'label_off' => esc_html__('No', 'rentals-elementor'),
                                'return_value' => 'none',
                                'default' => '',
                                'selectors' => [
                                        '{{WRAPPER}}  .price_unit' => 'display: {{VALUE}};',
                                ],
                        ]
        );

        $this->add_control(
            'hide_review',
            [
                            'label' => esc_html__('Hide Review', 'rentals-elementor'),
                            'type' => Controls_Manager::SWITCHER,
                            'label_on' => esc_html__('Yes', 'rentals-elementor'),
                            'label_off' => esc_html__('No', 'rentals-elementor'),
                            'return_value' => 'none',
                            'default' => '',
                            'selectors' => [
                                    '{{WRAPPER}}  .property-rating' => 'display: {{VALUE}};',
                            ],
                    ]
        );



        $this->end_controls_section();

        /*
        *-------------------------------------------------------------------------------------------------
        * End hide section
        */



        /*
          *-------------------------------------------------------------------------------------------------
          * Start typography section
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
                          'name'     => 'property_title',
                          'label'    => esc_html__('Property Title', 'rentals-elementor'),
                          'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                          'selector' => '{{WRAPPER}} .category_name .listing_title_unit',
                      ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                              'name'     => 'property_category',
                              'label'    => esc_html__('Property location & category', 'rentals-elementor'),
                              'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                              'selector' => '{{WRAPPER}} .category_tagline  ,{{WRAPPER}} .category_tagline a',
                          ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                          'name'     => 'property_price',
                          'label'    => esc_html__('Property Price', 'rentals-elementor'),
                          'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                          'selector' => '{{WRAPPER}} .price_unit ,{{WRAPPER}} .pernight',
                      ]
        );

        $this->end_controls_section();

        /*
        *-------------------------------------------------------------------------------------------------
        * End typography section
        */




        /*
        *-------------------------------------------------------------------------------------------------
        * Start Spacing section
        */
        $this->start_controls_section(
            'section_spacing_margin_section',
            [
                                             'label'     => esc_html__('Spaces & Sizes', 'rentals-elementor'),
                                             'tab'       => Controls_Manager::TAB_STYLE,
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
                                 '{{WRAPPER}} .listing_title_unit' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                             ],
                         ]
        );


        $this->add_responsive_control(
            'property_location_margin_bottom',
            [
                     'label' => esc_html__('Property Location Margin Bottom(px)', 'rentals-elementor'),
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
                         '{{WRAPPER}} .category_tagline.map_icon ' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                     ],
                 ]
        );

        $this->add_responsive_control(
            'property_category_margin_bottom',
            [
                                     'label' => esc_html__('Property Categories Margin Bottom(px)', 'rentals-elementor'),
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
                                             '{{WRAPPER}} .category_tagline.actions_icon ' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                     ],
                             ]
        );

        $this->add_responsive_control(
            'add_to_fav_padding',
            [
                                                    'label'      => esc_html__('Add to favorites position(only bottom & right)', 'rentals-elementor'),
                                                    'type'       => Controls_Manager::DIMENSIONS,
                                                    'size_units' => [ 'px', '%', 'em' ],
                                                    'selectors'  => [

                                                            '{{WRAPPER}} .property_unit_v2 .property_unit_action' => 'bottom: {{BOTTOM}}{{UNIT}};right: {{RIGHT}}{{UNIT}};',

                                                        ]
                                            ]
        );
        $this->add_responsive_control(
            'property_content_padding',
            [
                             'label'      => esc_html__('Property Price position(only left & bottom)', 'rentals-elementor'),
                             'type'       => Controls_Manager::DIMENSIONS,
                             'size_units' => [ 'px', '%', 'em' ],
                             'selectors'  => [

                                                                 '{{WRAPPER}} .price_unit' => 'left: {{LEFT}}{{UNIT}};bottom: {{BOTTOM}}{{UNIT}};',

                                                             ]
                         ]
        );


        $this->add_responsive_control(
            'property_icon_size',
            [
                                                 'label' => esc_html__('Icons size', 'rentals-elementor'),
                                                 'type' => Controls_Manager::SLIDER,
                                                 'range' => [
                                                     'px' => [
                                                         'min' => 0,
                                                         'max' => 100,
                                                     ],
                                                 ],
                                                 'devices' => [ 'desktop', 'tablet', 'mobile' ],
                                                 'desktop_default' => [
                                                     'size' => '16',
                                                     'unit' => 'px',
                                                 ],
                                                 'tablet_default' => [
                                                     'size' => '16',
                                                     'unit' => 'px',
                                                 ],
                                                 'mobile_default' => [
                                                     'size' => '16',
                                                     'unit' => 'px',
                                                 ],
                                                 'selectors' => [
                                                     '{{WRAPPER}} .category_tagline.map_icon:after,{{WRAPPER}} .category_tagline.actions_icon:after' => 'font-size: {{SIZE}}{{UNIT}};',
                                                 ],
                                             ]
        );


        $this->end_controls_section();

        /*
        *-------------------------------------------------------------------------------------------------
        * End Spacing section
        */


        /*
        *-------------------------------------------------------------------------------------------------
        * Start shadow section
        */
        $this->start_controls_section(
            'section_grid_box_shadow',
            [
                  'label' => esc_html__('Box Shadow', 'rentals-elementor'),
                  'tab'   => Controls_Manager::TAB_STYLE,
              ]
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                                                'name'     => 'box_shadow',
                                                'label'    => esc_html__('Box Shadow', 'rentals-elementor'),
                                                'selector' => '{{WRAPPER}} .property_listing',
                                            ]
        );
        $this->end_controls_section();

        /*
         *-------------------------------------------------------------------------------------------------
         * End shadow section
         */

        /*
         *-------------------------------------------------------------------------------------------------
         * Start color section
         */
        $this->start_controls_section(
            'section_grid_colors',
            [
                                     'label' => esc_html__('Colors', 'rentals-elementor'),
                                     'tab'   => Controls_Manager::TAB_STYLE,
                             ]
        );

        $this->add_control(
            'unit_color',
            [
                                         'label'     => esc_html__('Unit Background', 'rentals-elementor'),
                                         'type'      => Controls_Manager::COLOR,
                                         'default'   => '',
                                         'selectors' => [
                                                 '{{WRAPPER}} .property_listing' => 'background-color: {{VALUE}}',

                                         ],
                                 ]
        );
        $this->add_control(
            'title_color',
            [
                                                'label'     => esc_html__('Title', 'rentals-elementor'),
                                                'type'      => Controls_Manager::COLOR,
                                                'default'   => '',
                                                'selectors' => [
                                                        '{{WRAPPER}} .listing_title_unit' => 'color: {{VALUE}}',
                                                ],
                                        ]
        );

        $this->add_control(
            'category_color',
            [
                                                    'label'     => esc_html__('Location & Category Color', 'rentals-elementor'),
                                                    'type'      => Controls_Manager::COLOR,
                                                    'default'   => '',
                                                    'selectors' => [
                                                            '{{WRAPPER}} .category_tagline ,
															 {{WRAPPER}} .category_tagline a,
															 {{WRAPPER}} .category_tagline.map_icon:after,
															 {{WRAPPER}} .category_tagline.actions_icon:after' => 'color: {{VALUE}}',
                                                    ],
                                            ]
        );
        $this->add_control(
            'price_color',
            [
                           'label'     => esc_html__('Price', 'rentals-elementor'),
                           'type'      => Controls_Manager::COLOR,
                           'default'   => '',
                           'selectors' => [
                               '{{WRAPPER}} .price_unit' => 'color: {{VALUE}}',
                           ],
                       ]
        );

        $this->add_control(
            'featured_color',
            [
                                                        'label'     => esc_html__('Featured Label Color', 'rentals-elementor'),
                                                        'type'      => Controls_Manager::COLOR,
                                                        'default'   => '',
                                                        'selectors' => [
                                                                '{{WRAPPER}} .featured_div' => 'color: {{VALUE}}',
                                                              	'{{WRAPPER}} .property_status' => 'color: {{VALUE}}',
                                                        ],
                                                ]
        );
        $this->add_control(
            'featured_back_color',
            [
                                                             'label'     => esc_html__('Featured Label Background Color', 'rentals-elementor'),
                                                             'type'      => Controls_Manager::COLOR,
                                                             'default'   => '',
                                                             'selectors' => [
                                                                     '{{WRAPPER}} .featured_div' => 'background-color: {{VALUE}}',
                                                                      '{{WRAPPER}} .property_status '=> 'background: {{VALUE}}',
                                                             ],
                                                     ]
        );

        $this->add_control(
            'review_color',
            [
                                                                    'label'     => esc_html__('Review Stars Color', 'rentals-elementor'),
                                                                    'type'      => Controls_Manager::COLOR,
                                                                    'default'   => '',
                                                                    'selectors' => [
                                                                            '{{WRAPPER}} .property-rating i' => 'color: {{VALUE}}',
                                                                    ],
                                                            ]
        );

        $this->end_controls_section();
        /*
        *-------------------------------------------------------------------------------------------------
        * End color section
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

    public function wpresidence_send_to_shortcode($input)
    {
        $output='';
        if ($input!=='') {
            $numItems = count($input);
            $i = 0;

            foreach ($input as $key=>$value) {
                $output.=$value;
                if (++$i !== $numItems) {
                    $output.=', ';
                }
            }
        }
        return $output;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $attributes['title']              =   $settings['title'];
        $attributes['full_row']           =   'no';
        $attributes['type']               =   'properties';
        $attributes['category_ids']       =   $this -> wpresidence_send_to_shortcode($settings['category_ids']);
        $attributes['action_ids']         =   $this -> wpresidence_send_to_shortcode($settings['action_ids']);
        $attributes['city_ids']           =   $this -> wpresidence_send_to_shortcode($settings['city_ids']);
        $attributes['area_ids']           =   $this -> wpresidence_send_to_shortcode($settings['area_ids']);
        $attributes['number']               =   $settings['number'];
        $attributes['rownumber']            =   $settings['rownumber'];
        $attributes['link']                 =   $settings['link'];
        $attributes['show_featured_only']   =   $settings['show_featured_only'];
        $attributes['random_pick']          =   $settings['random_pick'];
        $attributes['unit_type']=2;

        echo  wpestate_recent_posts_pictures($attributes);
    }
}
