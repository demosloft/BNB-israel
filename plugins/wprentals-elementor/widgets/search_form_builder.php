<?php

namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Properties Widget.
 * @since 2.0
 */

class Wprentals_Search_Form_Builder extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Wprentals_Search_Form_Builder';
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
        return esc_html__('Search Form Builder', 'rentals-elementor');
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
        return 'eicon-site-search';
    }

    public function get_categories() {
        return ['wprentals'];
    }

    protected function register_controls() {


        $repeater = new Repeater();


        $form_fields = wpestate_elementor_search_form_builder_items_array();
        /**
         * Forms field types.
         */
        $repeater->add_control(
                'field_type', [
            'label' => esc_html__('Form Fields', 'rentals-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => $form_fields,
            'default' => 'text',
                ]
        );

        $repeater->add_control(
                'field_how', [
            'label' => esc_html__('How it will Compare', 'rentals-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' =>  array(
                            'equal'=>  'equal',
                            'greater'=>'greater',
                            'smaller'=>'smaller',
                            'like'=>'like',
                            'date bigger'=>'date bigger',
                            'date smaller'=>'date smaller'),
            'default' => 'like',
                ]
        );

        $repeater->add_control(
                'field_label', [
            'label' => esc_html__('Form Fields Label', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );

        $repeater->add_control(
                'placeholder', [
            'label' => esc_html__('Form Fields Placeholder', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );

        $repeater->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
			]
		);

        $repeater->add_responsive_control(
                'width', [
            'label' => esc_html__('Column Width', 'rentals-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => esc_html__('Default', 'rentals-elementor'),
                '100' => '100%',
                '80' => '80%',
                '75' => '75%',
                '66' => '66%',
                '60' => '60%',
                '50' => '50%',
                '40' => '40%',
                '33' => '33%',
                '25' => '25%',
                '20' => '20%',
                '10' => '10%',
            ],
            'default' => '33',
                ]
        );


       

        $this->start_controls_section(
                'wprentals_area_form_fields', [
            'label' => esc_html__('Form Fields', 'rentals-elementor'),
                ]
        );



        $this->add_control(
                'form_fields', [
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    '_id' => 'name',
                    'field_type' => 'property_category',
                    'field_label' => esc_html__('Property Category', 'rentals-elementor'),
                    'placeholder' => esc_html__('Property Category', 'rentals-elementor'),
                    'width' => '50',
                ],
                [
                    '_id' => 'message',
                    'field_type' => 'property_city',
                    'field_label' => esc_html__('Property City', 'rentals-elementor'),
                    'placeholder' => esc_html__('Property City', 'rentals-elementor'),
                    'width' => '50',
                ],
            ],
            'title_field' => '{{{ field_label }}}',
                ]
        );



        $this->add_control(
                'form_field_show_labels', [
            'label' => esc_html__('Show Labels', 'rentals-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'rentals-elementor'),
            'label_off' => esc_html__('Hide', 'rentals-elementor'),
            'return_value' => 'true',
            'default' => 'true',
            'separator' => 'before',
                ]
        );

        $this->add_control(
                'form_field_show_exra_details', [
            'label' => esc_html__('Show Amenities and Features fields?', 'rentals-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'rentals-elementor'),
            'label_off' => esc_html__('Hide', 'rentals-elementor'),
            'return_value' => 'true',
            'default' => 'true',
            'separator' => 'before',
                ]
        );



        $this->add_control(
                'form_field_show_section_title', [
            'label' => esc_html__('Show Section Title', 'rentals-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Show', 'rentals-elementor'),
            'label_off' => esc_html__('Hide', 'rentals-elementor'),
            'return_value' => 'true',
            'default' => 'true',
            'separator' => 'before',
                ]
        );

        $this->add_control(
                'form_field_section_title_text', [
            'label' => esc_html__('Section Title Text', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Advanced Search', 'rentals-elementor'),
            'label_block' => false,
            'description' => esc_html__('Search form Title', 'rentals-elementor'),
            'separator' => 'before',
            'condition' => [
                'form_field_show_section_title' => 'true'
            ],
                ]
        );

        $this->end_controls_section();


        /*
         * -------------------------------------------------------------------------------------------------
         * Button settings
         */


        $this->start_controls_section(
                'wprentals_area_submit_button', [
            'label' => esc_html__('Submit Button', 'rentals-elementor'),
                ]
        );

        $this->add_control(
                'submit_button_text', [
            'label' => esc_html__('Text', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Search Properties', 'rentals-elementor'),
            'placeholder' => esc_html__('Search Properties', 'rentals-elementor'),
                ]
        );

        ;

        $this->add_responsive_control(
                'submit_button_width', [
            'label' => esc_html__('Submit Button Width', 'rentals-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => esc_html__('Default', 'rentals-elementor'),
                '100' => '100%',
                '80' => '80%',
                '75' => '75%',
                '66' => '66%',
                '60' => '60%',
                '50' => '50%',
                '40' => '40%',
                '33' => '33%',
                '25' => '25%',
                '20' => '20%',
                '10' => '10%',
                '1' => 'auto'
            ],
            'default' => '100',
                ]
        );


        $this->add_control(
                'search_icon_button', [
            'label' => __('Icon', 'text-domain'),
            'type' => \Elementor\Controls_Manager::ICONS,
           
                ]
        );


        $this->end_controls_section();


        /*
         * -------------------------------------------------------------------------------------------------
         * END Button settings
         */





        $this->start_controls_section(
                'wprentals_area_form_style', [
            'label' => esc_html__('Form', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'wpersidence_form_column_gap', [
            'label' => esc_html__('Form Columns Gap', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
                '{{WRAPPER}} 	.elementor-form-fields-wrapper' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
            ],
                ]
        );

        $this->add_responsive_control(
                'wpersidence_form_row_gap', [
            'label' => esc_html__('Rows Gap', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->add_control(
                'wprentals_form_heading_label', [
            'label' => esc_html__('Form Label', 'rentals-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'wprentals_form_label_spacing', [
            'label' => esc_html__('Form Label Margin Bottom', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 5,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .search_wr_elementor .elementor-field-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .adv_search_slider  .wprentals_slider_price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .adv_search_slider  label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
                ]
        );
        



        $this->add_control(
                'wprentals_form_label_color', [
            'label' => esc_html__('Label Text Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .search_wr_elementor .elementor-field-label' => 'color: {{VALUE}};',
                '{{WRAPPER}} .adv_search_slider  .wprentals_slider_price' => 'color: {{VALUE}};',
                '{{WRAPPER}} .adv_search_slider  label' => 'color: {{VALUE}};',
                '{{WRAPPER}} #amount' => 'color: {{VALUE}}!important;',
                '{{WRAPPER}} .adv_extended_options_text'=> 'color: {{VALUE}}!important;',
                '{{WRAPPER}} .extended_search_checker label'=> 'color: {{VALUE}}!important;',
            ],
            'scheme' => [
                'type' => \Elementor\Core\Schemes\Color::get_type(),
                'value' => \Elementor\Core\Schemes\Color::COLOR_3
            ],
                ]
        );



        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'wprentals_form_label_typography',
            'selector' =>
            '{{WRAPPER}} .elementor-field-group > label,{{WRAPPER}} .adv_search_slider  .wprentals_slider_price,{{WRAPPER}} .adv_search_slider  label,{{WRAPPER}} #amount',
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );


        $this->add_control(
                'wprentals_form_back_color', [
            'label' => esc_html__('Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .search_wr_elementor' => 'background-color: {{VALUE}};',
            ],
            'scheme' => [
                'type' => \Elementor\Core\Schemes\Color::get_type(),
                'value' => \Elementor\Core\Schemes\Color::COLOR_3
            ],
                ]
        );


      
        $this->add_responsive_control(
            'form_wrapper-content_padding', [
            'label' => esc_html__('Form Padding ', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
           
            'selectors' => [
                '{{WRAPPER}} .search_wr_elementor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
        
           $this->add_responsive_control(
                'form_border_radius', [
            'label' => esc_html__('Form Border Radius', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .search_wr_elementor' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
  
            ],
                ]
        );

        $this->add_control(
                'icon_padding', [
            'label' => __('Padding', 'elementor'),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
            ],
            'range' => [
                'em' => [
                    'min' => 0,
                    'max' => 5,
                ],
            ],
            'condition' => [
                'view!' => 'default',
            ],
                ]
        );



        $this->end_controls_section();

        /* -------------------------------------------------------------------------------------------------
         * End Form  settings
         */

        /*

         * -------------------------------------------------------------------------------------------------
         * Start shadow section
         * {{WRAPPER}} .adv_search_tab_item 
         */
        $this->start_controls_section(
                'section_grid_box_shadow', [
            'label' => esc_html__('Box Shadow', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow_form',
            'label' => esc_html__('Box Shadow Form', 'rentals-elementor'),
            'selector' => '{{WRAPPER}} .search_wr_elementor ',
                ]
        );
 
        $this->end_controls_section();
     

        /* -------------------------------------------------------------------------------------------------
         *  Form Fields settings
         */



        $this->start_controls_section(
                'wprentals_field_style', [
            'label' => esc_html__('Field Style', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'wprentals_field_text_color1', [
            'label' => esc_html__('Field Text Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .form-control' => 'color:{{VALUE}}!important;',
                '{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
                '{{WRAPPER}} .filter_menu_trigger' => 'color: {{VALUE}};',
                '{{WRAPPER}} .wpestate_guest_no_control_info'=> 'color: {{VALUE}};',
                '{{WRAPPER}} .form-control::placeholder ' => 'color: {{VALUE}}!important;',
                '{{WRAPPER}} .filter_menu' => 'color:{{VALUE}}'
 
 
            ],
            'scheme' => [
                'type' => \Elementor\Core\Schemes\Color::get_type(),
                'value' => \Elementor\Core\Schemes\Color::COLOR_3
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'wprentals_field_typography',
            'selector' => '{{WRAPPER}} .form-control, {{WRAPPER}} input.form-control,{{WRAPPER}} .filter_menu_trigger',
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );
        
          $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'wprentals_field_typography_dropdown',
                    'label' => esc_html__('Dropdown Typography', 'rentals-elementor'),
            'selector' => '{{WRAPPER}} .filter_menu li',
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );
        
          
        $this->add_control(
                'wprentals_field_background_icon_color', [
            'label' => esc_html__('Field icon Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
        
            'selectors' => [
                '{{WRAPPER}} .elementor_search_builder_field_wrapper i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .elementor_search_builder_field_wrapper svg'=> 'fill: {{VALUE}};',
                '{{WRAPPER}} .adv_extended_close_button i'               => 'color: {{VALUE}};',
            ],
            'separator' => 'before',
                ]
        );

        $this->add_control(
                'wprentals_field_background_color', [
            'label' => esc_html__('Field Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .form-control' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} #search_location'=> 'background-color: {{VALUE}};',
            ],
            'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'tab-wprentals_field_padding-color', [
            'label' => esc_html__('Field Padding', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                
               // '{{WRAPPER}} .caret::after' => 'right:{{RIGHT}}{{UNIT}};left:auto;',
                '{{WRAPPER}} #search_location' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .search_wr_elementor .filter_menu_trigger'=> 'padding: 0px;position: relative;',
                  '{{WRAPPER}} .dropdown>#search_location' =>'padding:0px;'
            ],
                ]
        );

        $this->add_control(
                'wprentals_field_slider_color', [
            'label' => esc_html__('Price slider Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#0073e6',
            'selectors' => [
                '{{WRAPPER}} .search_wr_elementor .ui-widget-header' => 'background-color: {{VALUE}}!important;',
                '{{WRAPPER}} .search_wr_elementor .wprentals_slider_price' => 'color: {{VALUE}};',
            ],
            'separator' => 'before',
                ]
        );

        $this->add_control(
                'wprentals_field_slider_track_color', [
            'label' => esc_html__('Price slider Track Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#0073e6',
            'selectors' => [
                '{{WRAPPER}}  .adv_search_slider #slider_price' => 'background-color: {{VALUE}}!important;',
            ],
            'separator' => 'before',
                ]
        );



        $this->add_control(
                'wprentals_field_border_color', [
            'label' => esc_html__('Border Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#eee',
            'selectors' => [
                '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
                '{{WRAPPER}} .form-control' => 'border-color: {{VALUE}};',
            ],
            'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'field_border_width', [
            'label' => esc_html__('Border Width', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'placeholder' => '1',
            'size_units' => ['px'],
            'selectors' => [
                '{{WRAPPER}} .form-control' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->add_responsive_control(
                'field_border_radius', [
            'label' => esc_html__('Border Radius', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}}  .form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->end_controls_section();

        /* -------------------------------------------------------------------------------------------------
         *  END Form Fields settings
         */



        $this->start_controls_section(
                'wprentals_area_button_style', [
            'label' => esc_html__('Button', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        


        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal', [
            'label' => esc_html__('Normal State', 'rentals-elementor'),
                ]
        );

        $this->add_control(
            'submit_button_background_color', [
            'label' => esc_html__('Submit Button Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'scheme' => [
                'type' => \Elementor\Core\Schemes\Color::get_type(),
                'value' => \Elementor\Core\Schemes\Color::COLOR_4
            ],
            'selectors' => [
                '{{WRAPPER}} .advanced_search_submit_button' => 'background-color:  {{VALUE}}!important;background:none;',
            ],
                ]
        );

        $this->add_control(
            'submit_button_text_color', [
            'label' => esc_html__('Submit Button Text Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .advanced_search_submit_button' => 'color: {{VALUE}}!important;',
            ],
                ]
        );
        $this->add_control(
                'icon_primary_color', [
            'label' => __('icon Color', 'elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .elementor-icon, {{WRAPPER}} .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                '{{WRAPPER}} .elementor-icon, {{WRAPPER}} .elementor-icon svg' => 'fill: {{VALUE}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'submit_button_typography',
            'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
            'selector' => '{{WRAPPER}} .advanced_search_submit_button',
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'submit_button_border',
            'selector' => '{{WRAPPER}} .advanced_search_submit_button',
                ]
        );

        $this->add_responsive_control(
                'submit_ button_border_radius', [
            'label' => esc_html__('Submit Button Border Radius', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .advanced_search_submit_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->add_responsive_control(
                'submit_button_text_padding', [
            'label' => esc_html__('Submit Button Text Padding', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .advanced_search_submit_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_button_hover', [
            'label' => esc_html__('Hover State', 'rentals-elementor'),
                ]
        );

        $this->add_control(
                'submit_button_background_hover_color', [
            'label' => esc_html__('Submit Button Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF00',
            'selectors' => [
                '{{WRAPPER}} .advanced_search_submit_button:hover' => 'background-color:  {{VALUE}}!important;background:none;',
            ],
                ]
        );

        $this->add_control(
                'submit_button_hover_color', [
            'label' => esc_html__('Submit Button Text Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#0073e6',
            'selectors' => [
                '{{WRAPPER}} .advanced_search_submit_button:hover' => 'color: {{VALUE}}!important;',
            ],
                ]
        );


        $this->add_control(
                'hover_icon_color', [
            'label' => __('Hover Color icon', 'elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .advanced_search_submit_button:hover .elementor-icon, {{WRAPPER}} .advanced_search_submit_button:hover .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                '{{WRAPPER}} .advanced_search_submit_button:hover .elementor-icon, {{WRAPPER}} .advanced_search_submit_button:hover  .elementor-icon svg' => 'fill: {{VALUE}};',
            ],
                ]
        );


        $this->add_control(
                'submit_button_hover_border_color', [
            'label' => esc_html__('Submit Button Border Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .advanced_search_submit_button:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'button_border_border!' => '',
            ],
                ]
        );





        $this->end_controls_tab();



        $this->end_controls_tabs();
        /* -------------------------------------------------------------------------------------------------
         *  End Button Style settings
         */



        $this->add_responsive_control(
                'size', [
            'label' => __('Icon Size', 'elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 6,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .advanced_search_submit_button svg'=> 'height: {{SIZE}}{{UNIT}};',
            ],
            'separator' => 'before',
                ]
        );



        $this->add_responsive_control(
                'search_icon_padding', [
            'label' => esc_html__('Icon Size Padding', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .elementor-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );


        $this->end_controls_section();
    }

    /*

     *  return option for tabs dropdown
     * 
     * 
     * 
     *  */

    protected function custom_serve() {

         global $post;

        $return = get_post_meta($post->ID,'wpstream_elementor_search_form', true);
        return $return;
    }

    /*

     *  Render the shortcode 
     * 
     * 
     * 
     *  */

    protected function render() {
        global $post;

        $settings = $this->get_settings_for_display();
      
        





        $allowed_html = array(
            'a' => array(
                'href' => array(),
                'title' => array(),
                'target' => array()
            ),
            'strong' => array(),
            'th' => array(),
            'td' => array(),
            'span' => array(),
        );






        /*
          /	add attributes to html classes
         */

        $this->add_render_attribute(
                [
                    'wrapper' => [
                        'class' => [
                            'elementor-form-fields-wrapper',
                            'elementor-labels-above',
                        ],
                    ],
                    'wprentals_submit_wrapper' => [
                        'class' => [
                            'elementor-field-group',
                            'elementor-column',
                            'elementor-field-type-submit',
                        ],
                    ],
                    'button' => [
                        'class' => [
                            'agent_submit_class_elementor',
                            'wprentals_button',
                            'wprentals_button_elementor',
                            'elementor-button',
                        ]
                    ],
                ]
        );

        if (empty($settings['submit_button_width'])) {
            $settings['submit_button_width'] = '100';
        }
        $this->add_render_attribute('wprentals_submit_wrapper', 'class', 'elementor-col-' . $settings['submit_button_width']);
        //$this->add_render_attribute( 'wprentals_submit_wrapper', 'class', ' elementor-button-align-' . $settings['submit_button_align'] );

        if (!empty($settings['submit_button_width_tablet'])) {
            $this->add_render_attribute('wprentals_submit_wrapper', 'class', 'elementor-md-' . $settings['submit_button_width_tablet']);
        }

        if (!empty($settings['submit_button_width_mobile'])) {
            $this->add_render_attribute('wprentals_submit_wrapper', 'class', 'elementor-sm-' . $settings['submit_button_width_mobile']);
        }

        if (!empty($settings['submit_button_size'])) {
            $this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['submit_button_size']);
        }

        if (!empty($settings['button_type'])) {
            $this->add_render_attribute('button', 'class', 'elementor-button-' . $settings['button_type']);
        }


        if (!empty($settings['form_id'])) {
            $this->add_render_attribute('form', 'id', $settings['form_id']);
        }


        if (!empty($settings['wprentals_submit_button_elementor'])) {
            $this->add_render_attribute('button', 'id', $settings['wprentals_submit_button_elementor']);
        }

        /*
          /	END add attributes to html classes
         */


        if (!empty($settings['wprentals_form_id'])) {
            $wprentals_form_id = $settings['wprentals_form_id'];
        }




        $temp_what = array();
        $temp_how = array();
        $temp_label = array();

        foreach ($settings['form_fields'] as $key => $item):
            
            $temp_what[] = $item['field_type'];
            $temp_how[] = $item['field_how'];
            $temp_label[] = $item['field_label'];

           
        endforeach;

        $elementor_search_name_how = "elementor_search_how_" . $post->ID;
        $elementor_search_name_what = "elementor_search_what_" . $post->ID;
        $elementor_search_name_label = "elementor_search_label_" . $post->ID;

        
        update_option($elementor_search_name_how, $temp_how);
        update_option($elementor_search_name_what, $temp_what);
        update_option($elementor_search_name_label, $temp_label);

      //  $render_output = wpestate_render_elementor_search($settings, $this,$post->ID);
        // $search_object = new WpRentalsSearch();
        //global $search_object;
         //$render_output= trim($search_object->wpstate_display_search_form_elementor($settings, $this,$post->ID));
            
        
        $render_output =wprentals_elementor_search_helper($settings, $this,$post->ID);
        echo $render_output;
    }

    /*
     * 
     * 	Render fields attributes
     * 
     * 
     * 
     * 
     * 
     */

    public function wpestate_render_attributes($key, $item, $settings) {

        $this->add_render_attribute(
                [
                    'field-group' . $key => [
                        'class' => [
                            'elementor-field-group',
                            'elementor-column',
                            'form-group',
                            'elementor-field-group-' . $item['_id'],
                        ],
                    ],
                    'input' . $key => [
                        'name' => $item['field_type'],
                        'id' => 'form-field-' . $item['_id'],
                        'class' => [
                            'elementor-field',
                            'form-control',
                            'elementor-size',
                        ],
                    ],
                    'label' . $key => [
                        'for' => 'form-field-' . $item['_id'],
                        'class' => 'elementor-field-label',
                    ],
                ]
        );

        if (empty($item['width'])) {
            $item['width'] = '100';
        }



        $this->add_render_attribute('field-group' . $key, 'class', 'elementor-col-' . $item['width']);

        if (!empty($item['width_tablet'])) {
            $this->add_render_attribute('field-group' . $key, 'class', 'elementor-md-' . $item['width_tablet']);
        }

        if (!empty($item['width_mobile'])) {
            $this->add_render_attribute('field-group' . $key, 'class', 'elementor-sm-' . $item['width_mobile']);
        }

        if (!empty($item['placeholder'])) {
            $this->add_render_attribute('input' . $key, 'placeholder', $item['placeholder']);
        }

        if (!empty($item['field_value'])) {
            $this->add_render_attribute('input' . $key, 'value', $item['field_value']);
        }

        if (!$settings['form_field_show_labels']) {
            $this->add_render_attribute('label' . $key, 'class', 'elementor-screen-only');
        }
    }

}

//end class


