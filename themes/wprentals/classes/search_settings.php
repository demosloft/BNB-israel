<?php

global $search_defaults;

$search_defaults = array(
                    'newtype'   => array(
                                        'adv_search_what'       => array(
                                                                        'Location',
                                                                        'check_in',
                                                                        'check_out',
                                                                        'guest_no'
                                                                        ),
                                        'adv_search_how'        => array(
                                                                        'like',
                                                                        'date bigger',
                                                                        'date smaller',
                                                                        'bigger'
                                                                        ),
                                        'adv_search_label'      => array(
                                                                        esc_html__('Where do you want to go ?','wprentals'),
                                                                        esc_html__('Check-In','wprentals'),
                                                                        esc_html__('Check-Out','wprentals'),
                                                                        esc_html__('Guests','wprentals')
                                                                        ),
                                        'search_field_label'    => array(
                                                                        '',
                                                                        '',
                                                                        '',
                                                                        ''),
                                        'field_size'            => array(
                                                                        'col-md-4',
                                                                        'col-md-2',
                                                                        'col-md-2',
                                                                        'col-md-2',
                                                                        )
                                    ),
                    'oldtype'   => array(
                                        'adv_search_what'       => array(
                                                                        'Location',
                                                                        'check_in',
                                                                        'check_out',
                                                                        'guest_no'
                                                                        ),
                                        'adv_search_how'        => array(
                                                                        'like',
                                                                        'date bigger',
                                                                        'date smaller',
                                                                        'bigger'
                                                                        ),
                                        'adv_search_label'      => array(
                                                                        esc_html__('Where do you want to go ?','wprentals'),
                                                                        esc_html__('Check-In','wprentals'),
                                                                        esc_html__('Check-Out','wprentals'),
                                                                        esc_html__('Guests','wprentals')
                                                                        ),
                                        'search_field_label'    => array(
                                                                        '',
                                                                        '',
                                                                        '',
                                                                        ''),
                                        'field_size'            => array(
                                                                        'col-md-4',
                                                                        'col-md-2',
                                                                        'col-md-2',
                                                                        'col-md-2',
                                                                        )
                                    ),
                    'type4'   => array(
                                        'adv_search_what'       => array(
                                                                        'keyword_search',
                                                                        'check_in',
                                                                        'check_out',
                                                                        'guest_no'
                                                                        ),
                                        'adv_search_how'        => array(
                                                                        'like',
                                                                        'date bigger',
                                                                        'date smaller',
                                                                        'bigger'
                                                                        ),
                                        'adv_search_label'      => array(
                                                                        esc_html__('Type Keyword','wprentals'),
                                                                        esc_html__('Check-In','wprentals'),
                                                                        esc_html__('Check-Out','wprentals'),
                                                                        esc_html__('Guests','wprentals')
                                                                        ),
                                        'search_field_label'    => array(
                                                                        '',
                                                                        '',
                                                                        '',
                                                                        ''),
                                        'field_size'            => array(
                                                                        'col-md-4',
                                                                        'col-md-2',
                                                                        'col-md-2',
                                                                        'col-md-2',
                                                                        )
                                    )
);

$search_defaults2 =array(
    // search type defaults
    'oldtype'   =>  array(
                        'location'  =>  array(
                                        'label' => 'col-md-4',
                                        'size' => esc_html__('Where do you want to go ?','wprentals'),
                                    ),
                        'check-in'  =>  array(
                                        'label' => 'col-md-2',
                                        'size' => esc_html__('Check-In','wprentals'),
                                    ),
                        'check_out'  =>  array(
                                        'label' => 'col-md-2',
                                        'size' => esc_html__('Check-Out','wprentals'),
                                    ),
                        'guest_no'  =>  array(
                                        'label' => 'col-md-2',
                                        'size' => esc_html__('Guests','wprentals'),
                                    ),
                ),

    // type 2 defaults
    'newtype'   =>  array(
                        'location'  =>  array(
                                        'label' => 'col-md-4',
                                        'size' => esc_html__('Where do you want to go ?','wprentals'),
                                    ),
                        'check-in'  =>  array(
                                        'label' => 'col-md-2',
                                        'size' => esc_html__('Check-In','wprentals'),
                                    ),
                        'check_out'  =>  array(
                                        'label' => 'col-md-2',
                                        'size' => esc_html__('Check-Out','wprentals'),
                                    ),
                        'guest_no'  =>  array(
                                        'label' => 'col-md-2',
                                        'size' => esc_html__('Guests','wprentals'),
                                    ),
                ),




    'type3'     => esc_html__( 'Type 3','wprentals'),
    'type4'     => esc_html__( 'Type 4','wprentals'),
    'type5'     => esc_html__( 'Type 5','wprentals')

);




function wpestate_convert_regular_to_half(){

    $defaults_old_half= array();

    $defaults_old_half=array(
                'oldtype'=> array(
                    'what'  =>  array("Location","check_in","check_out","guest_no","property_rooms","property_category","property_action_category","property_bedrooms","property_bathrooms","property_price"),
                    'how'   =>  array("like","like","like","greater","greater","like","like","greater","greater","between"),
                    'label' =>  array(
                            esc_html__('Where do you want to go ?','wprentals'),
                            esc_html__('Check-In','wprentals'),
                            esc_html__('Check-Out','wprentals'),
                            esc_html__('Guests','wprentals'),
                            esc_html__('Rooms','wprentals'),
                            esc_html__('All Types','wprentals'),
                            esc_html__('All Sizes','wprentals'),
                            esc_html__('Bedrooms','wprentals'),
                            esc_html__('Baths','wprentals'),
                            esc_html__('Price Range','wprentals'),


                        ),
                ),
    );
        global $wprentals_admin;
    $adv_search_type    =   $wprentals_admin['wp_estate_adv_search_type'];


    if( $adv_search_type=='newtype' || $adv_search_type=='oldtype'){

        Redux::setOption('wprentals_admin','wp_estate_adv_search_what_half_map',  $defaults_old_half['oldtype']['what'] );
        Redux::setOption('wprentals_admin','wp_estate_adv_search_how_half_map',   $defaults_old_half['oldtype']['how'] );
        Redux::setOption('wprentals_admin','wp_estate_adv_search_label_half_map', $defaults_old_half['oldtype']['label'] );

        $wpestate_set_search_half_map=array(
            'adv_search_what'       =>      $defaults_old_half['oldtype']['what'],
            'adv_search_how'        =>      $defaults_old_half['oldtype']['how'] ,
            'adv_search_label'      =>      $defaults_old_half['oldtype']['label'],
            'search_field_label'    =>      array(),
        );


        Redux::setOption('wprentals_admin','wpestate_set_search_half_map',  $wpestate_set_search_half_map);
        Redux::setOption('wprentals_admin','wp_estate_search_fields_no_per_row_half_map',  3);
        Redux::setOption('wprentals_admin','wp_estate_adv_search_fields_no_half_map',  count( $defaults_old_half['oldtype']['what'] ) );

    }else{

        Redux::setOption('wprentals_admin','wp_estate_adv_search_what_half_map',   $wprentals_admin['wpestate_set_search']['adv_search_what'] );
        Redux::setOption('wprentals_admin','wp_estate_adv_search_how_half_map',    $wprentals_admin['wpestate_set_search']['adv_search_how'] );
        Redux::setOption('wprentals_admin','wp_estate_adv_search_label_half_map',  $wprentals_admin['wpestate_set_search']['adv_search_label'] );





        $wpestate_set_search_half_map=array(
            'adv_search_what'       =>  $wprentals_admin['wpestate_set_search']['adv_search_what'],
            'adv_search_how'        =>  $wprentals_admin['wpestate_set_search']['adv_search_how'],
            'adv_search_label'      =>  $wprentals_admin['wpestate_set_search']['adv_search_label'],
            'search_field_label'    =>  array(),
        );

        Redux::setOption('wprentals_admin','wpestate_set_search_half_map',  $wpestate_set_search_half_map);
        Redux::setOption('wprentals_admin','wp_estate_search_fields_no_per_row_half_map',  3);
        
        $count_elements = 0 ;
        if( is_array( $wprentals_admin['wpestate_set_search']['adv_search_what'] ) ){
            $count_elements = count( $wprentals_admin['wpestate_set_search']['adv_search_what']);
        }
        Redux::setOption('wprentals_admin','wp_estate_adv_search_fields_no_half_map',  $count_elements );

    }


}
