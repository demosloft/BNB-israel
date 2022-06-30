<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


class Wprentals_Grids extends Widget_Base
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
        return 'Wprentals_Grids';
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
        return __('WpRentals Grids', 'rentals-elementor');
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
        $this->start_controls_section(
            'content_section',
            [
                                    'label'     => esc_html__('Content', 'rentals-elementor'),
                                    'tab'       => Controls_Manager::TAB_CONTENT,
                            ]
        );

        $this->add_control(
            'wprentals_grid_type',
            [
                                 'label'     => esc_html__('Select Grid Type', 'rentals-elementor'),
                                 'type'      => Controls_Manager::SELECT,
                                 'options'   => [
                                     1    => esc_html__('Type 1', 'rentals-elementor'),
                                     2    => esc_html__('Type 2', 'rentals-elementor'),
                                     3    => esc_html__('Type 3', 'rentals-elementor'),
                                     4    => esc_html__('Type 4', 'rentals-elementor'),
                                     5    => esc_html__('Type 5', 'rentals-elementor'),
                                     6    => esc_html__('Type 6', 'rentals-elementor'),
                                 ],
                                 'description' => '',
                                 'default' => 1,
                             ]
        );

        $this->add_control(
            'wprentals_design_type',
            [
                                        'label'     => esc_html__('Select Design Type', 'rentals-elementor'),
                                        'type'      => Controls_Manager::SELECT,
                                        'options'   => [
                                                1    => esc_html__('Design Type 1', 'rentals-elementor'),
                                                2    => esc_html__('Design Type 2', 'rentals-elementor'),
                          3    => esc_html__('Design Type 3', 'rentals-elementor'),
                                        ],
                                        'description' => '',
                                        'default' => 2,
                                ]
        );

        $this->add_control(
            'grid_taxonomy',
            [
                                 'label'     => esc_html__('Pick Categories', 'rentals-elementor'),
                                 'type'      => Controls_Manager::SELECT,
                                 'options'   => [
                                     'property_category' 				=>  'Category',
                                     'property_action_category'	=>	'Type',
                                     'property_city' 						=> 	'City',
                                     'property_area' 						=> 	'Area',
                                 ],
                                 'description' => '',
                                 'default' => 'property_category',
                             ]
        );


        $all_taxonomies = get_object_taxonomies('estate_property', 'objects');


        if (! empty($all_taxonomies) && ! is_wp_error($all_taxonomies)) {
            foreach ($all_taxonomies as $taxonomy_item) {
                $options_array = array();
                $terms   = get_terms($taxonomy_item->name);

                if (! empty($terms) && ! is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        $options_array[ $term->slug ] = $term->name;
                    }
                }

                $this->add_control(
                    $taxonomy_item->name,
                    [
                         'label'    => $taxonomy_item->label,
                         'type'     => Controls_Manager::SELECT2,
                         'multiple' => true,
                         'label_block' => true,
                         'options'  => $options_array,
                         'condition' => [
                             'grid_taxonomy' => $taxonomy_item->name,
                         ],
                     ]
                );
            }
        }



        $this->add_control(
            'hide_empty_taxonomy',
            [
                                     'label'     => esc_html__('Hide Empty', 'rentals-elementor'),
                                     'type'      => Controls_Manager::SELECT,
                                     'options'   => [
                                         '0'  => esc_html__('No', 'rentals-elementor'),
                                         '1'    => esc_html__('Yes', 'rentals-elementor')
                                     ],
                                     'description' => '',
                                     'default' => '1',
                                 ]
        );

        $this->add_control(
            'orderby',
            [
                'label'     => esc_html__('Order By', 'rentals-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'name'  => esc_html__('Name', 'rentals-elementor'),
                                  'id'    => esc_html__('ID', 'rentals-elementor'),
                    'count'    => esc_html__('Count', 'rentals-elementor'),

                ],
                'description' => '',
                'default' => 'name',
            ]
        );

        $this->add_control(
            'order',
            [
                'label'     => esc_html__('Order', 'rentals-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'ASC'  	=> esc_html__('ASC', 'rentals-elementor'),
                    'DESC'  => esc_html__('DESC', 'rentals-elementor')
                ],
                'default' => 'ASC',
            ]
        );


        $this->add_control(
            'items_no',
            [
                'label'     => esc_html__(' Number of Items to Show', 'rentals-elementor'),
                'type'      => Controls_Manager::TEXT,
                'default' => 9,
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
            'item_height',
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
                                        '{{WRAPPER}} .places1.featuredplace' => 'height: {{SIZE}}{{UNIT}};',

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



        $this->add_responsive_control(
            'wpersidence_item_column_gap',
            [
                                    'label' => esc_html__('Form Columns Gap', 'rentals-elementor'),
                                    'type' => Controls_Manager::SLIDER,
                                    'default' => [
                                            'size' => 15,
                                    ],
                                    'range' => [
                                            'px' => [
                                                    'min' => 0,
                                                    'max' => 100,
                                            ],
                                    ],
                                    'selectors' => [
                                            '{{WRAPPER}} .elementor_rentals_grid' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
                                    ],
                            ]
        );

        $this->add_responsive_control(
            'wpersidence_item_row_gap',
            [
                                    'label' => esc_html__('Rows Gap', 'rentals-elementor'),
                                    'type' => Controls_Manager::SLIDER,
                                    'default' => [
                                        'size' => 15,
                                    ],
                                    'range' => [
                                        'px' => [
                                            'min' => 0,
                                            'max' => 100,
                                        ],
                                    ],
                                    'selectors' => [
                                        '{{WRAPPER}} .places_wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                      ],
            ]
      );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                             'name'     => 'tax_tagline',
                             'label'    => esc_html__('Tagline Typography ', 'rentals-elementor'),
                             'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                             'selector' => '{{WRAPPER}} .category_tagline',
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


    public function wpestate_drop_posts($post_type)
    {
        $args = array(
              'numberposts' => -1,
              'post_type'   => $post_type
            );

        $posts = get_posts($args);
        $list = array();
        foreach ($posts as $cpost) {
            $list[$cpost->ID] = $cpost->post_title;
        }
        return $list;
    }


    public function wprentals_send_to_shortcode($input)
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
        $settings 												= $this->get_settings_for_display();
        $args['type'] 										= $settings['wprentals_grid_type'];
        $args['wprentals_design_type']	=	$settings['wprentals_design_type'];
        $args['grid_taxonomy']						= $settings['grid_taxonomy'];
        $args[$settings['grid_taxonomy']] = $settings[$settings['grid_taxonomy']];
        $args['order']										= $settings['order'];
        $args['orderby']									= $settings['orderby'];
        $args['items_no']									= $settings['items_no'];
        $args['hide_empty_taxonomy']			=	$settings['hide_empty_taxonomy'];


        echo wprentals_display_grids($args);
    }
}
