<?php

namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Properties Widget.
 * @since 2.0
 */
class WpRentals_Contact_Form_Builder extends Widget_Base {

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
        return 'WpRentals_Contact_Form_Builder';
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
        return esc_html__( 'Contact Form Builder', 'rentals-elementor' );
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
        return 'eicon-form-horizontal';
    }

		public function get_categories() {
			return [ 'wprentals' ];
		}


    protected function register_controls() {

        $repeater = new Repeater();

        $form_fields = array(
            'name' => esc_html__( 'Full Name', 'rentals-elementor' ),
            'first_name' => esc_html__( 'First Name', 'rentals-elementor' ),
            'last_name' => esc_html__( 'Last Name', 'rentals-elementor' ),
            'email' => esc_html__( 'Email', 'rentals-elementor' ),
            'mobile' => esc_html__( 'Mobile', 'rentals-elementor' ),
					  'phone' => esc_html__( 'Phone', 'rentals-elementor' ),
					  'address' => esc_html__( 'Address', 'rentals-elementor' ),
            'message' => esc_html__( 'Message', 'rentals-elementor' ), //textarea
            'booking_from_date'=>esc_html__( 'Booking from date', 'rentals-elementor' ), //datepicker
            'booking_to_date'=>esc_html__( 'Booking to date', 'rentals-elementor' ), //datepicker
            'guests' => esc_html__( 'Guests', 'rentals-elementor' ),
            'country' => esc_html__( 'Country', 'rentals-elementor' ),
            'city' => esc_html__( 'City', 'rentals-elementor' ),
            'state' => esc_html__( 'State', 'rentals-elementor' ),
            'zip' => esc_html__( 'Zip/Postal Code', 'rentals-elementor' ),
					);

        /**
         * Forms field types.
         */


        $repeater->add_control(
            'field_type',
            [
                'label' => esc_html__( 'Form Fields', 'rentals-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => $form_fields,
                'default' => 'text',
            ]
        );

        $repeater->add_control(
            'field_label',
            [
                'label' => esc_html__( 'Form Fields Label', 'rentals-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'placeholder',
            [
                'label' => esc_html__( 'Form Fields Placeholder', 'rentals-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'field_type',
                            'operator' => '!in',
                            'value' => [
                            ],
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'required',
            [
                'label' => esc_html__( 'Required', 'rentals-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => '',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'field_type',
                            'operator' => '!in',
                            'value' => [
                            ],
                        ],
                    ],
                ],
            ]
        );



        $repeater->add_responsive_control(
            'width',
            [
                'label' => esc_html__( 'Column Width', 'rentals-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'rentals-elementor' ),
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
                ],
                'default' => '100',
            ]
        );

        $repeater->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'rentals-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 4,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'field_type',
                            'operator' => 'in',
                            'value' => [
                                'message'
                            ],
                        ],
                    ],
                ],
            ]
        );



        $this->start_controls_section(
            'wpresidence_area_form_fields',
            [
                'label' => esc_html__( 'Form Fields', 'rentals-elementor' ),
            ]
        );



        $this->add_control(
            'form_fields',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        '_id' => 'name',
                        'field_type' => 'name',
                        'field_label' => esc_html__( 'Name', 'rentals-elementor' ),
                        'placeholder' => esc_html__( 'Name', 'rentals-elementor' ),
                        'width' => '100',
                    ],
                    [
                        '_id' => 'email',
                        'field_type' => 'email',
                        'required' => 'true',
                        'field_label' => esc_html__( 'Email', 'rentals-elementor' ),
                        'placeholder' => esc_html__( 'Email', 'rentals-elementor' ),
                        'width' => '100',
                    ],
                    [
                        '_id' => 'booking_from_date',
                        'field_type' => 'booking_from_date',
                        'required' => 'true',
                        'field_label' => esc_html__( 'booking_from_date', 'rentals-elementor' ),
                        'placeholder' => esc_html__( 'booking_from_date', 'rentals-elementor' ),
                        'width' => '100',
                    ],
                    [
                        '_id' => 'message',
                        'field_type' => 'message',
                        'field_label' => esc_html__( 'Message', 'rentals-elementor' ),
                        'placeholder' => esc_html__( 'Message', 'rentals-elementor' ),
                        'width' => '100',
                    ],
                ],
                'title_field' => '{{{ field_label }}}',
            ]
        );

        $this->add_control(
            'form_field_input_size',
            [
                'label' => esc_html__( 'Input Size', 'rentals-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'xs' => esc_html__( 'Extra Small', 'rentals-elementor' ),
                    'sm' => esc_html__( 'Small', 'rentals-elementor' ),
                    'md' => esc_html__( 'Medium', 'rentals-elementor' ),
                    'lg' => esc_html__( 'Large', 'rentals-elementor' ),
                    'xl' => esc_html__( 'Extra Large', 'rentals-elementor' ),
                ],
                'default' => 'sm',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'form_field_show_labels',
            [
                'label' => esc_html__( 'Labels', 'rentals-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'rentals-elementor' ),
                'label_off' => esc_html__( 'Hide', 'rentals-elementor' ),
                'return_value' => 'true',
                'default' => 'true',
                'separator' => 'before',
            ]
        );




        $this->add_control(
            'has_gdpr_agreement',
            [
                'label' => esc_html__( 'GDPR Agreement', 'rentals-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'rentals-elementor' ),
                'label_off' => esc_html__( 'Hide', 'rentals-elementor' ),
                'default' => 'false',
            ]
        );




        $this->add_control(
            'gdpr_text',
            [
                'label' => esc_html__( 'GDPR Agreement Text', 'rentals-elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'I consent to the GDPR Terms', 'rentals-elementor' ),
                'description' => '',
                'condition' => [
                    'has_gdpr_agreement' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();


        /*
        *-------------------------------------------------------------------------------------------------
        * Button settings
        */


        $this->start_controls_section(
            'wpresidence_area_submit_button',
            [
                'label' => esc_html__( 'Submit Button', 'rentals-elementor' ),
            ]
        );

        $this->add_control(
            'submit_button_text',
            [
                'label' => esc_html__( 'Text', 'rentals-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Send Email', 'rentals-elementor' ),
                'placeholder' => esc_html__( 'Send Email', 'rentals-elementor' ),
            ]
        );

        $this->add_control(
            'submit_button_size',
            [
                'label' => esc_html__( 'Submit Button Size', 'rentals-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'md',
                'options' => array(
                    'xs' => esc_html__( 'Extra Small', 'rentals-elementor' ),
                    'sm' => esc_html__( 'Small', 'rentals-elementor' ),
                    'md' => esc_html__( 'Medium', 'rentals-elementor' ),
                    'lg' => esc_html__( 'Large', 'rentals-elementor' ),
                    'xl' => esc_html__( 'Extra Large', 'rentals-elementor' ),
                )
            ]
        );

        $this->add_responsive_control(
            'submit_button_width',
            [
                'label' => esc_html__( 'Submit Button Width', 'rentals-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'rentals-elementor' ),
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
                'default' => '100',
            ]
        );

        $this->add_responsive_control(
            'submit_button_align',
            [
                'label' => esc_html__( 'Button Alignment', 'rentals-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Left', 'rentals-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'rentals-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Right', 'rentals-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'stretch' => [
                        'title' => esc_html__( 'Justified', 'rentals-elementor' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'stretch',
                'prefix_class' => 'elementor%s-button-align-',
            ]
        );

        $this->add_control(
            'wpresidence_submit_button_elementor',
            [
                'label' => esc_html__( 'Button ID', 'rentals-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => false,
                'description' => esc_html__( 'Use a unique name without spaces or special characters','rentals-elementor' ),
                'separator' => 'before',

            ]
        );

        $this->end_controls_section();


				/*
				*-------------------------------------------------------------------------------------------------
				* END Button settings
				*/


				/*
				*-------------------------------------------------------------------------------------------------
				* Email settings
				*/
        $this->start_controls_section(
            'wpresidence_area_email_settings',
            [
                'label' => esc_html__( 'Email Settings', 'rentals-elementor' ),
            ]
        );



        $email_subject = sprintf( esc_html__( 'New email from "%s" ', 'rentals-elementor' ), get_option( 'blogname' ) );
        $this->add_control(
            'email_subject',
            [
                'label' => esc_html__( 'Email Subject', 'rentals-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => $email_subject,
                'placeholder' => $email_subject,
                'label_block' => true,
                'render_type' => 'none',
            ]
        );



        $this->end_controls_section();

				/*
				*-------------------------------------------------------------------------------------------------
				* End Button settings
				*/



        $this->start_controls_section(
            'wpresidence_area_form_style',
            [
                'label' => esc_html__( 'Form', 'rentals-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wpersidence_form_column_gap',
            [
                'label' => esc_html__( 'Form Columns Gap', 'rentals-elementor' ),
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
            'wpersidence_form_row_gap',
            [
                'label' => esc_html__( 'Rows Gap', 'rentals-elementor' ),
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
            'wpresidence_form_heading_label',
            [
                'label' => esc_html__( 'Form Label', 'rentals-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'wpresidence_form_label_spacing',
            [
                'label' => esc_html__( 'Form Label Spacing', 'rentals-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    'body.rtl {{WRAPPER}} .elementor-labels-inline .elementor-field-group > label' => 'padding-left: {{SIZE}}{{UNIT}};',
                    'body {{WRAPPER}} .elementor-labels-above .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
										'body:not(.rtl) {{WRAPPER}} .elementor-labels-inline .elementor-field-group > label' => 'padding-right: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_control(
            'wpresidence_form_label_color',
            [
                'label' => esc_html__( 'Label Text Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group > label'  => 'color: {{VALUE}};',
										'{{WRAPPER}} .elementor-field-subgroup label' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type'  => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_3,
                ],
            ]
        );



        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'wpresidence_form_label_typography',
                'selector' => '{{WRAPPER}} .elementor-field-group > label',
                'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
            ]
        );

				/*-------------------------------------------------------------------------------------------------
				* End Form  settings
				*/


				/*-------------------------------------------------------------------------------------------------
				*  Form Fields settings
				*/

        $this->end_controls_section();

        $this->start_controls_section(
            'wpresidence_field_style',
            [
                'label' => esc_html__( 'Field Style', 'rentals-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wpresidence_field_text_color',
            [
                'label' => esc_html__( 'Field Text Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_3,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'wpresidence_field_typography',
                'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field, {{WRAPPER}} .elementor-field-subgroup label',
                'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
            ]
        );

        $this->add_control(
            'wpresidence_field_background_color',
            [
                'label' => esc_html__( 'Field Background Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
									  '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',

                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wpresidence_field_border_color',
            [
                'label' => esc_html__( 'Border Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#eee',
                'selectors' => [
									'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
									'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
                  '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',


                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'field_border_width',
            [
                'label' => esc_html__( 'Border Width', 'rentals-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'placeholder' => '1',
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rentals-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
									'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                  ],
            ]
        );

        $this->end_controls_section();

				/*-------------------------------------------------------------------------------------------------
				*  END Form Fields settings
				*/


				/*-------------------------------------------------------------------------------------------------
				*  GDpr Style settings
				*/
        $this->start_controls_section(
            'wpresidence_area_gdpr_style',
            [
                'label' => esc_html__( 'GDPR', 'rentals-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_gdpr_color',
            [
                'label' => esc_html__( 'Text Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gpr_wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gpr_wrapper a' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_3,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'gdpr_typography',
                'selector' => '{{WRAPPER}} .gpr_wrapper a',
                'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                'fields_options' => [
                    'font_weight' => [
                        'default' => '300',
                    ],
                ],
            ]
        );


        $this->end_controls_section();

				/*-------------------------------------------------------------------------------------------------
				*  END GDpr Style settings
				*/
        $this->start_controls_section(
            'wpresidence_area_button_style',
            [
                'label' => esc_html__( 'Button', 'rentals-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal State', 'rentals-elementor' ),
            ]
        );

        $this->add_control(
            'submit_button_background_color',
            [
                'label' => esc_html__( 'Submit Button Background Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_4,
                ],
                'default' => '#b881fc',
                'selectors' => [
										'{{WRAPPER}} .wprentals_button_elementor ' => ' background-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'submit_button_text_color',
            [
                'label' => esc_html__( 'Submit Button Text Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submit_button_typography',
                'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'submit_button_border',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->add_responsive_control(
            'submit_ button_border_radius',
            [
                'label' => esc_html__( 'Submit Button Border Radius', 'rentals-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_button_text_padding',
            [
                'label' => esc_html__( 'Submit Button Text Padding', 'rentals-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover State', 'rentals-elementor' ),
            ]
        );

        $this->add_control(
            'submit_button_background_hover_color',
            [
                'label' => esc_html__( 'Submit Button Background Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#c18eff',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_button_hover_color',
            [
                'label' => esc_html__( 'Submit Button Text Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
								'default'=>'#fff',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_button_hover_border_color',
            [
                'label' => esc_html__( 'Submit Button Border Color', 'rentals-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_border_border!' => '',
                ],
            ]
        );



        $this->end_controls_tab();

        $this->end_controls_tabs();
				/*-------------------------------------------------------------------------------------------------
				*  End Button Style settings
				*/

        $this->end_controls_section();

    }







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


				$email_to = '';
				if(!empty($settings['email_to'])){
						$email_to = $settings['email_to'] ;
				}

				$email_subject = '';
				if(!empty($settings['email_subject'])){
						$email_subject = $settings['email_subject'] ;
				}


				$send_copy_to = '';
				if(!empty($settings['send_copy_to'])){
						$send_copy_to = $settings['send_copy_to'] ;
				}

				$send_ccopy_to = '';
				if(!empty($settings['send_ccopy_to'])){
						$send_ccopy_to = $settings['send_ccopy_to'] ;
				}


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
                'wpresidence_submit_wrapper' => [
                    'class' => [
                        'elementor-field-group',
                        'elementor-column',
                        'elementor-field-type-submit',
                    ],
                ],
                'button' => [
                    'class' => [
                        'agent_submit_class_elementor',
                        'wpresidence_button',
												'wprentals_button_elementor',
                        'elementor-button',
                    ]
                ],
            ]
        );

        if ( empty( $settings['submit_button_width'] ) ) {
            $settings['submit_button_width'] = '100';
        }
        $this->add_render_attribute( 'wpresidence_submit_wrapper', 'class', 'elementor-col-' . $settings['submit_button_width'] );
    		//$this->add_render_attribute( 'wpresidence_submit_wrapper', 'class', ' elementor-button-align-' . $settings['submit_button_align'] );

        if ( ! empty( $settings['submit_button_width_tablet'] ) ) {
            $this->add_render_attribute( 'wpresidence_submit_wrapper', 'class', 'elementor-md-' . $settings['submit_button_width_tablet'] );
        }

        if ( ! empty( $settings['submit_button_width_mobile'] ) ) {
            $this->add_render_attribute( 'wpresidence_submit_wrapper', 'class', 'elementor-sm-' . $settings['submit_button_width_mobile'] );
        }

        if ( ! empty( $settings['submit_button_size'] ) ) {
            $this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['submit_button_size'] );
        }

        if ( ! empty( $settings['button_type'] ) ) {
            $this->add_render_attribute( 'button', 'class', 'elementor-button-' . $settings['button_type'] );
        }


        if ( ! empty( $settings['form_id'] ) ) {
            $this->add_render_attribute( 'form', 'id', $settings['form_id'] );
        }


        if ( ! empty( $settings['wpresidence_submit_button_elementor'] ) ) {
            $this->add_render_attribute( 'button', 'id', $settings['wpresidence_submit_button_elementor'] );
        }

				/*
				/	END add attributes to html classes
				*/
        ?>





			<form class="elementor-form wprentals_elementor_form"  id="wprentals-elementor-form-<?php echo $this->get_id(); ?>" method="post" <?php echo $this->get_render_attribute_string( 'form' ); ?>>
            <div id="booking_form_request_mess_modal"></div>

            <input name="prop_id" type="hidden"  id="contact_form_elementor" value="1">
            <input type="hidden" name="contact_ajax_nonce" id="agent_property_ajax_nonce"  value="<?php echo wp_create_nonce( 'ajax-property-contact' );?>" />

            <input type="hidden" id="elementor_email_subject" name="email_suject" value="<?php echo esc_attr($email_subject); ?>" />

						<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
								<?php
								  foreach ( $settings['form_fields'] as $key => $item ) :
													$item['form_field_input_size'] = $settings['form_field_input_size'];
													$this->residence_render_attributes( $key, $item ,$settings );

											    print '<div '.$this->get_render_attribute_string( 'field-group' . $key ).'>';
													if ( $item['field_label'] ) {
															echo '<label ' . $this->get_render_attribute_string( 'label' . $key ).'>'.$item['field_label'];
                              if($item['required']){
                                echo '*';
                              }
                              echo'</label>';
													}

 													$this->wpresidence_render_field($item, $key);


													print '</div>';

									endforeach;




								?>

								<div <?php echo $this->get_render_attribute_string( 'wpresidence_submit_wrapper' ); ?>>
									<?php
									// add gdpr check if is the case
									if( isset($settings['has_gdpr_agreement']) && $settings['has_gdpr_agreement'] == 'yes') {
											if(function_exists('wpestate_check_gdpr_case')):
												print'<div class="gpr_wrapper"><input type="checkbox" id="wpestate_agree_gdpr" class="wpestate_agree_gdpr" name="wpestate_agree_gdpr" />
														<label for="wpestate_agree_gdpr"><a target="_blank" href="'.wpestate_get_template_link('gdpr_terms.php').'">'.wp_kses($settings['gdpr_text'],$allowed_html).'</a></label></div>
												';
											endif;
									}
									?>
									<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
											<?php if ( ! empty( $settings['submit_button_text'] ) ) : ?>
													<?php echo $settings['submit_button_text']; ?>
											<?php endif; ?>
									</button>

								</div>
						</div>



				</form>
        <script>

            jQuery(document).ready(function() {
                var in_date =jQuery('input[name="booking_from_date"]') .attr('id');
                var out_date =jQuery('input[name="booking_to_date"]') .attr('id');
                console.log('in_date'+in_date +" out_date "+out_date);
                wpestaste_check_in_out_enable_new(in_date, out_date);
                wprentals_elementor_submit_form();
            });
        </script>



    <?php
    }



		/*
		*		Render required
		*/
    private function wpresidence_required_attribute( $element ) {
        $this->add_render_attribute( $element, 'required', 'required' );
    }


		/*
		*		Render fields
		*/

		protected function wpresidence_render_field($item, $key){

					if($item['field_type']=='message'){
						// we have textarea
						 echo $this->wpresidence_render_textarea( $item, $key );
					}else if($item['field_type']=='email'){
						//we have email
						$this->add_render_attribute( 'input' . $key, 'class', 'elementor-field-textual' );
						echo '<input type="email" ' . $this->get_render_attribute_string( 'input' . $key ) . '>';
					}else{
						$this->add_render_attribute( 'input' . $key, 'class', 'elementor-field-textual' );
						echo '<input type="text" ' . $this->get_render_attribute_string( 'input' . $key ) . '>';
					}


		}


		/*
		*		Render fields attributes
		*/
		protected function residence_render_attributes( $key, $item ,$settings ){

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
										'name' 	=> $item['field_type'],
										'id' 		=> 'rentals_contact_builder_'.$item['field_type'],
										'class' => [
												'elementor-field',
												'form-control',
												'elementor-size-' . $item['form_field_input_size'],
										],
								],
								'label' . $key => [
										'for' => 'form-field-' . $item['_id'],
										'class' => 'elementor-field-label',
								],
						]
				);

				if ( empty( $item['width'] ) ) {
						$item['width'] = '100';
				}

        if ( ! empty( $item['required'] ) ) {
          $class = 'elementor-field-required';
          if ( ! empty( $instance['mark_required'] ) ) {
              $class .= ' elementor-mark-required';
          }
          $this->add_render_attribute( 'field-group' . $key, 'class', $class );
          $this->wpresidence_required_attribute( 'input' . $key );
      }

				$this->add_render_attribute( 'field-group' . $key, 'class', 'elementor-col-' . $item['width'] );

				if ( ! empty( $item['width_tablet'] ) ) {
						$this->add_render_attribute( 'field-group' .$key, 'class', 'elementor-md-' . $item['width_tablet'] );
				}

				if ( ! empty( $item['width_mobile'] ) ) {
						$this->add_render_attribute( 'field-group' . $key, 'class', 'elementor-sm-' . $item['width_mobile'] );
				}

				if ( ! empty( $item['placeholder'] ) ) {
						$this->add_render_attribute( 'input' . $key, 'placeholder', $item['placeholder'] );
				}

				if ( ! empty( $item['field_value'] ) ) {
						$this->add_render_attribute( 'input' .$key, 'value', $item['field_value'] );
				}

				if ( ! $settings['form_field_show_labels'] ) {
						$this->add_render_attribute( 'label' . $key, 'class', 'elementor-screen-only' );
				}



		}



		/*
		/  render textarea
		*/

    protected function wpresidence_render_textarea( $item, $key ) {
        $this->add_render_attribute( 'textarea' . $key, [
            'class' => [
								'form-control',
                'elementor-field-textual',
                'elementor-field',
                'elementor-size-' . $item['form_field_input_size'],
            ],
            'name' => $item['field_type'],
            'id' => 'form-field-' . $item['_id'],
            'rows' => $item['rows'],
        ] );

				if ( $item['required'] ) {
						$this->wpresidence_required_attribute( 'textarea' . $key );
				}

        if ( $item['placeholder'] ) {
            $this->add_render_attribute( 'textarea' . $key, 'placeholder', $item['placeholder'] );
        }



				$value ='';
				if(!empty( $item['field_value']) ) {
			 		$value =	$item['field_value'];
				}

        return '<textarea '.$this->get_render_attribute_string( 'textarea'.$key ).'>'.$value.'</textarea>';
    }



} //end class
