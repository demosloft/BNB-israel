<?php

///////////////////////////////////////////////////////////////////////////////////////////
/////// register shortcodes
///////////////////////////////////////////////////////////////////////////////////////////

function wpestate_shortcodes(){
    wpestate_register_shortcodes();
    wpestate_tiny_short_codes_register();
    add_filter('widget_text', 'do_shortcode');
}

///////////////////////////////////////////////////////////////////////////////////////////
// register tiny plugins functions
///////////////////////////////////////////////////////////////////////////////////////////

function wpestate_tiny_short_codes_register() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'wpestate_add_plugin');
        add_filter('mce_buttons_3', 'wpestate_register_button');
    }

}

/////////////////////////////////////////////////////////////////////////////////////////
/////// push the code into Tiny buttons array
///////////////////////////////////////////////////////////////////////////////////////////

function wpestate_register_button($buttons) {
    array_push($buttons, "|", "slider_recent_items");

    array_push($buttons, "|", "testimonials");
    array_push($buttons, "|", "recent_items");
    array_push($buttons, "|", "featured_agent");
    array_push($buttons, "|", "featured_article");
    array_push($buttons, "|", "featured_property");
    array_push($buttons, "|", "list_items_by_id");
    array_push($buttons, "|", "login_form");
    array_push($buttons, "|", "register_form");
    array_push($buttons, "|", "advanced_search");
    array_push($buttons, "|", "font_awesome");
    array_push($buttons, "|", "spacer");
    array_push($buttons, "|", "icon_container");
    array_push($buttons, "|", "places_list");
    array_push($buttons, "|", "featured_place");
    array_push($buttons, "|", "places_slider");
    return $buttons;
}



///////////////////////////////////////////////////////////////////////////////////////////
/////// poins to the right js
///////////////////////////////////////////////////////////////////////////////////////////

function wpestate_add_plugin($plugin_array) {
    $plugin_array['slider_recent_items']        = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['testimonials']               = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['recent_items']               = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['featured_agent']             = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['featured_article']           = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['featured_property']          = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['login_form']                 = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['register_form']              = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['list_items_by_id']           = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['advanced_search']            = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['font_awesome']               = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['spacer']                     = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['icon_container']             = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['places_list']                = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['featured_place']             = get_template_directory_uri() . '/js/shortcodes.js';
    $plugin_array['places_slider']              = get_template_directory_uri() . '/js/shortcodes.js';
    return $plugin_array;
}

///////////////////////////////////////////////////////////////////////////////////////////
/////// register shortcodes
///////////////////////////////////////////////////////////////////////////////////////////


function wpestate_register_shortcodes() {
    add_shortcode('slider_recent_items', 'wpestate_slider_recent_posts_pictures');
    add_shortcode('spacer', 'wpestate_spacer_shortcode_function');
    add_shortcode('recent-posts', 'wpestate_recent_posts_function');
    add_shortcode('testimonial', 'wpestate_testimonial_function');
    add_shortcode('recent_items', 'wpestate_recent_posts_pictures');
    add_shortcode('featured_agent', 'wpestate_featured_agent');
    add_shortcode('featured_article', 'wpestate_featured_article');
    add_shortcode('featured_property', 'wpestate_featured_property');
    add_shortcode('login_form', 'wpestate_login_form_function');
    add_shortcode('register_form', 'wpestate_register_form_function');
    add_shortcode('list_items_by_id', 'wpestate_list_items_by_id_function');
    add_shortcode('advanced_search', 'wpestate_advanced_search_function');
    add_shortcode('font_awesome', 'wpestate_font_awesome_function');
    add_shortcode('icon_container', 'wpestate_icon_container_function');
    add_shortcode('places_list', 'wpestate_places_list_function');
    add_shortcode('featured_place', 'wpestate_featured_place');
    add_shortcode('places_slider','wpestate_places_slider');
    add_shortcode('simple_term_list','wpestate_simple_term_list');
    add_shortcode('booking_form','wpestate_booking_form');
    add_shortcode('full_map','wpestate_full_map_shortcode');
    
    add_shortcode('availability_calendar','wpestate_availability_calendar');
}


add_action( 'init', 'wprentals_autocomplete_populate',1 );

function wprentals_autocomplete_populate () {
    global $all_tax;
    global $wprentals_property_category_values;
    global $wprentals_all_tax_labels;
    global $wprentals_property_action_category_values;
    global $wprentals_property_city_values;
    global $wprentals_property_area_values;

    $city_array=array();
    $area_array=array();
    $all_places=array();
    $all_tax=array();
    $global_categories=array();
    $category_array=array();
    $action_array=array();

    $wprentals_property_city_values=array();
    $wprentals_property_area_values =array();

    $wprentals_property_category_values = array();
    $wprentals_property_action_category_values = array();


    $terms_city = get_terms( array(
            'taxonomy' => 'property_city',
            'hide_empty' => false,
        ) );

    foreach($terms_city as $term){
        $places[$term->name]= $term->term_id;
        $temp_array=array();
        $temp_array['label'] = $term->name;
        $temp_array['value'] = $term->term_id;

        $all_tax[]                      =   $temp_array;
        $wprentals_all_tax_labels[$term->term_id] =   $term->name;
        $wprentals_property_city_values[]         =   $temp_array;
    }



    $terms_city = get_terms( array(
            'taxonomy' => 'property_area',
            'hide_empty' => false,
    ) );

    foreach($terms_city as $term){
        $places[$term->name]= $term->term_id;
        $temp_array=array();
        $temp_array['label'] = $term->name;
        $temp_array['value'] = $term->term_id;
        $all_places[]=$temp_array;
        $area_array[]=$temp_array;

        $all_tax[]                      =   $temp_array;
        $wprentals_all_tax_labels[$term->term_id] =   $term->name;
        $wprentals_property_area_values[]         =   $temp_array;

    }


    $terms_category = get_terms( array(
            'taxonomy' => 'property_category',
            'hide_empty' => false,
    ) );

    foreach($terms_category as $term){
        $temp_array=array();
        $temp_array['label'] = $term->name;
        $temp_array['value'] = $term->term_id;
        $category_array[]=$temp_array;
        $global_categories[]=$temp_array;

        $all_tax[]                      =   $temp_array;
        $wprentals_all_tax_labels[$term->term_id] =   $term->name;
        $wprentals_property_category_values[]     =   $temp_array;
    }


    $terms_category = get_terms(array(
            'taxonomy' => 'property_action_category',
            'hide_empty' => false,
                ));

    foreach ($terms_category as $term) {

        $temp_array = array();
        $temp_array['label'] = $term->name;
        $temp_array['value'] = $term->term_id;

        $all_tax[]                          =   $temp_array;
        $action_array[]                     =   $temp_array;
        $wprentals_all_tax_labels[$term->term_id]     =   $term->name;
        $wprentals_property_action_category_values[]  =   $temp_array;
    }
}












////////////////////////////////////////////////////////////////////////////////
// add shortcodes to visual composer
////////////////////////////////////////////////////////////////////////////////
add_action( 'vc_before_init', 'wpestate_vc_shortcodes' );
if( function_exists('vc_map') ):
     if( !function_exists('wpestate_vc_shortcodes')):
        function wpestate_vc_shortcodes(){

        global $all_tax;
        global $wprentals_property_category_values;
        global $wprentals_all_tax_labels;
        global $wprentals_property_action_category_values;
        global $wprentals_property_city_values;
        global $wprentals_property_area_values;

        wp_register_script( 'avalability_control_elementor', trailingslashit( get_template_directory_uri() ).'js/avalability_control_elementor.js', '', '1.0.0', true );
   


            $map_shortcode_for=array('listings','contact');
            $map_shorcode_show_contact_form=array('yes','no');

    vc_map(
               array(
                   "name" => esc_html__("WpRentals Map with Listings","wprentals-core"),//done
                   "base" => "full_map",
                   "class" => "",
                   "category" => esc_html__('Content','wprentals-core'),
                   'admin_enqueue_js' => array(WPESTATE_PLUGIN_DIR_URL.'/vc_extend/bartag.js'),
                   'admin_enqueue_css' => array(WPESTATE_PLUGIN_DIR_URL.'/vc_extend/bartag.css'),
                   'weight'=>100,
                   'icon'   =>'wpestate_vc_logo',
                   'description'=>esc_html__('Map with Listings','wprentals-core'),

                   "params" => array(


                       array(
                           "type" => "textfield",
                           "holder" => "div",
                           "class" => "",
                           "heading" => esc_html__("Map Height","wprentals-core"),
                           "param_name" => "map_height",
                           "value" => "",
                           "description" => esc_html__("Map Height","wprentals-core")
                       ),



                       array(
                           "type" => "autocomplete",
                           "holder" => "div",
                           "class" => "",
                           "heading" => esc_html__("Category Id's","wprentals-core"),
                           "param_name" => "category_ids",
                           "value" => "",
                           "dependency" => array(
                               "element" => "map_shortcode_for",
                               "value" => "listings"
                           ),
                           "description" => esc_html__("list of category id's sepearated by comma (*only for properties)","wprentals-core"),

                           'settings' => array(
                                       'multiple' => true,
                                       'sortable' => true,
                                       'min_length' => 1,
                                       'no_hide' => true,
                                       'groups' => false,
                                       'unique_values' => true,
                                       'display_inline' => true,
                                       'values' => $wprentals_property_category_values,
                           )
                       ),
                       array(
                           "type" => "autocomplete",
                           "holder" => "div",
                           "class" => "",
                           "heading" => esc_html__("Action Id's","wprentals-core"),
                           "param_name" => "action_ids",
                           "value" => "",
                           "dependency" => array(
                               "element" => "map_shortcode_for",
                               "value" => "listings"
                           ),
                           "description" => esc_html__("list of action ids separated by comma (*only for properties)","wprentals-core"),

                           'settings' => array(
                               'multiple' => true,
                               'sortable' => true,
                               'min_length' => 1,
                               'no_hide' => true,
                               'groups' => false,
                               'unique_values' => true,
                               'display_inline' => true,
                               'values' => $wprentals_property_action_category_values,
                           )
                       ),
                       array(
                           "type" => "autocomplete",
                           "holder" => "div",
                           "class" => "",
                           "heading" => esc_html__("City Id's ","wprentals-core"),
                           "param_name" => "city_ids",
                           "value" => "",
                           "dependency" => array(
                               "element" => "map_shortcode_for",
                               "value" => "listings"
                           ),
                           "description" => esc_html__("list of city ids separated by comma (*only for properties)","wprentals-core"),

                           'settings' => array(
                               'multiple' => true,
                               'sortable' => true,
                               'min_length' => 1,
                               'no_hide' => true,
                               'groups' => false,
                               'unique_values' => true,
                               'display_inline' => true,
                               'values' => $wprentals_property_city_values,
                               ),
                           ),
                           array(
                               "type" => "autocomplete",
                               "holder" => "div",
                               "class" => "",
                               "heading" => esc_html__("Area Id's","wprentals-core"),
                               "param_name" => "area_ids",
                               "value" => "",
                               "dependency" => array(
                                   "element" => "map_shortcode_for",
                                   "value" => "listings"
                               ),
                              "description" => esc_html__("list of area ids separated by comma (*only for properties)","wprentals-core"),

                               'settings' => array(
                                   'multiple' => true,
                                   'sortable' => true,
                                   'min_length' => 1,
                                   'no_hide' => true,
                                   'groups' => false,
                                   'unique_values' => true,
                                   'display_inline' => true,
                                   'values' => $wprentals_property_area_values,

                               ),
                           ),


                           array(
                              "type" => "textarea_raw_html",
                              "holder" => "div",
                              "class" => "",
                              "heading" => esc_html__("Map Style","wprentals-core"),
                              "param_name" => "map_snazy",
                              "value" => "",
                              "description" => esc_html__("Map Style from snazy maps","wprentals-core")
                          ),

                       )
               )
       );


        vc_map(
            array(
            "name" => esc_html__( "Availability Calendar for a single Property","wprentals-core"),
            "base" => "availability_calendar",
            "class" => "",
            "category" => esc_html__( 'Content','wprentals-core'),
            'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
            'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
         
            //'front_enqueue_js' => get_theme_file_uri( '/js/avalability_control_elementor.js' ),
          
                   
            'weight'=>100,
            'icon'   =>'wpestate_vc_logo',
            'description'=>esc_html__( 'Avalability Calendar for a single Property','wprentals-core'),
            "params" => array(
                 array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__( "Id of the property","wprentals"),
                    "param_name" => "id",
                    "value" => "",
                    "description" => esc_html__( "The id of the property","wprentals")
                 ),

            )
         )
        );



        vc_map(
            array(
            "name" => esc_html__( "Booking Form for a single Property","wprentals-core"),
            "base" => "booking_form",
            "class" => "",
            "category" => esc_html__( 'Content','wprentals-core'),
            'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
            'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
            'weight'=>100,
            'icon'   =>'wpestate_vc_logo',
            'description'=>esc_html__( 'Booking Form for a single property','wprentals-core'),
            "params" => array(
                 array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__( "Id of the property","wprentals"),
                    "param_name" => "id",
                    "value" => "",
                    "description" => esc_html__( "The id of the property","wprentals")
                 ),

            )
         )
        );






        $featured_places_array =   array(
                1 =>__('type1','wprentals-core'),
                2 =>__('type2','wprentals-core'),
                3 =>__('type3', 'wprentals-core'),
        );




    vc_map(
    array(
       "name" => esc_html__( "Featured Category","wprentals"),
       "base" => "featured_place",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Featured Category Shortcode','wprentals-core'),
       "params" => array(
            array(
                "type" => "autocomplete",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Category Name","wprentals"),
                "param_name" => "id",
                "value" => "",
                "description" => esc_html__( "Type the category name you want to show","wprentals"),
                'settings' => array(
                            'multiple' => true,
                            'sortable' => true,
                            'min_length' => 1,
                            'no_hide' => true, // In UI after select doesn't hide an select list
                            'groups' => false, // In UI show results grouped by groups
                            'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
                            'display_inline' => true, // In UI show results inline view
                            'values' => $all_tax,

                        )  ,
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type","wprentals"),
                "param_name" => "type",
                "value" => $featured_places_array,
                "description" => esc_html__( "Select type1,type2 or type3","wprentals")
            ),

            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => __("Featured label", "wprentals"),
                "param_name" => "places_label",
                "value" => "",
                "description" => __("Featured_label text (use only for design type 3)", "wprentals")
            ),

            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => __("Image Height in px", "wprentals"),
                "param_name" => "places_height",
                "value" => "",
                "description" => __("Image Height in px", "wprentals")
            )

        )
    )
    );

    $categories =   array(
        'property_action_category'=>'Property Category',
        'property_category'=>'Property type',
        'property_city'=>'Property City',
        'property_area'=>'Property Area',
        'property_features'=>'Property Features',
        'property_status'=>'Property Status');


    $list_type  =   array('horizontal','vertical');
    $term_show_count=array('yes','no');
    vc_map( array(
        "name" => esc_html__( "Simple Term List","wprentals"),//done
        "base" => "simple_term_list",
        "class" => "",
        "category" => esc_html__( 'Content','wprentals-core'),
        'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
        'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
        'weight'=>100,
        'icon'   =>'wpestate_vc_logo',
        'description'=>esc_html__( 'Simple Term List','wprentals-core'),
        "params" => array(

            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Category","wprentals"),
                "param_name" => "term_list_category",

                "value" => array_flip($categories),
                "description" => esc_html__( "Terms from what category","wprentals")
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "List style type","wprentals"),
                "param_name" => "term_list_type",
                "value" => $list_type,
                "description" => esc_html__( "List style type","wprentals")
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Show Term Count","wprentals"),
                "param_name" => "term_show_count",
                "value" => $term_show_count,
                "description" => esc_html__( "Show Term Count","wprentals")
            ),

        )
    )
    );



    vc_map( array(
        "name" => esc_html__( "Categories List","wprentals"),//done
        "base" => "places_list",
        "class" => "",
        "category" => esc_html__( 'Content','wprentals-core'),
        'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
        'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
        'weight'=>100,
        'icon'   =>'wpestate_vc_logo',
        'description'=>esc_html__( 'Categories List','wprentals-core'),
        "params" => array(
            array(
                "type" => "autocomplete",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type the category name you want to show","wprentals"),
                "param_name" => "place_list",
                "value" => "",
                "description" => esc_html__( "Type the category name you want to show","wprentals"),
                'settings' => array(
                            'multiple' => true,
                            'sortable' => true,
                            'min_length' => 1,
                            'no_hide' => true, // In UI after select doesn't hide an select list
                            'groups' => false, // In UI show results grouped by groups
                            'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
                            'display_inline' => true, // In UI show results inline view
                            'values' => $all_tax,
                        ),
            )  ,
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Categories per row","wprentals"),
                "param_name" => "place_per_row",
                "value" => "4",
                "description" => esc_html__( "How many items listed per row?","wprentals")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Space between units","wprentals"),
                "param_name" => "spaces_unit",
                "value" => "8",
                "description" => esc_html__( "Only numbers,in pixels","wprentals")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wprentals"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wprentals")
            )
        )
    )
    );



    $featured_listings=array('no','yes');
    vc_map(
    array(
       "name" => esc_html__( "Recent Items Slider","wprentals"),//done
       "base" => "slider_recent_items",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Recent Items Slider Shortcode','wprentals-core'),
       "params" => array(
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Title","wprentals"),
             "param_name" => "title",
             "value" => "",
             "description" => esc_html__( "Section Title","wprentals")
          ),

           array(
             "type" => "autocomplete",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Type Category names","wprentals"),
             "param_name" => "category_ids",
             "value" => "",
             "description" => esc_html__( "list of category names (*only for properties)","wprentals"),
                            "dependency" => array(
                                "element" => "type",
                                "value" => "properties"
                            ),
                            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'min_length' => 1,
                                'no_hide' => true,
                                'groups' => false,
                                'unique_values' => true,
                                'display_inline' => true,
                                'values' => $wprentals_property_category_values,
                        ),

          ),
             array(
             "type" => "autocomplete",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Type Action names","wprentals"),
             "param_name" => "action_ids",
             "value" => "",
             "description" => esc_html__( "list of action names (*only for properties)","wprentals"),
                            "dependency" => array(
                                "element" => "type",
                                "value" => "properties"
                            ),
                            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'min_length' => 1,
                                'no_hide' => true,
                                'groups' => false,
                                'unique_values' => true,
                                'display_inline' => true,
                                'values' => $wprentals_property_action_category_values,
                            ),
            ),
           array(
             "type" => "autocomplete",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Type City names ","wprentals"),
             "param_name" => "city_ids",
             "value" => "",
             "description" => esc_html__( "list of city names (*only for properties)","wprentals"),
                            "dependency" => array(
                                "element" => "type",
                                "value" => "properties"
                            ),
                            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'min_length' => 1,
                                'no_hide' => true,
                                'groups' => false,
                                'unique_values' => true,
                                'display_inline' => true,
                                'values' => $wprentals_property_city_values,
                            ),
            ),
            array(
             "type" => "autocomplete",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Type Area names","wprentals"),
             "param_name" => "area_ids",
             "value" => "",
             "description" => esc_html__( "list of area names (*only for properties)","wprentals"),
                            "dependency" => array(
                                "element" => "type",
                                "value" => "properties"
                            ),
                            'settings' => array(
                                'multiple' => true,
                                'sortable' => true,
                                'min_length' => 1,
                                'no_hide' => true,
                                'groups' => false,
                                'unique_values' => true,
                                'display_inline' => true,
                                'values' => $wprentals_property_area_values,
                            ),
            ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "No of items","wprentals"),
             "param_name" => "number",
             "value" => 4,
             "description" => esc_html__( "how many items","wprentals")
          ),array(
             "type" => "dropdown",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Show featured listings only?","wprentals"),
             "param_name" => "show_featured_only",
             "value" => $featured_listings,
             "description" => esc_html__( "Show featured listings only? (yes/no)","wprentals")
          ), array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wprentals"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wprentals")
            ) ,array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Auto scroll period","wprentals"),
             "param_name" => "autoscroll",
             "value" => "0",
             "description" => esc_html__( "Auto scroll period in seconds - 0 for manual scroll, 1000 for 1 second, 2000 for 2 seconds and so on.","wprentals")
          )
        )
    )
    );







    $icon_position  =array('left','central');
      vc_map( array(
       "name" => esc_html__( "Icon content box","wprentals"),//done
       "base" => "icon_container",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
        'icon'   =>'wpestate_vc_logo',
        'description'=>esc_html__( 'Icon Content Box Shortcode','wprentals-core'),
       "params" => array(
          array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Box Title","wprentals"),
             "param_name" => "title",
             "value" => "Title",
             "description" => esc_html__( "Box Title goes here","wprentals")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Image url","wprentals"),
             "param_name" => "image",
             "value" => "",
             "description" => esc_html__( "Image or Icon url","wprentals")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Content of the box","wprentals"),
             "param_name" => "content_box",
             "value" => "Content of the box goes here",
             "description" => esc_html__( "Content of the box goes here","wprentals")
          )
          ,

           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Link","wprentals"),
             "param_name" => "link",
             "value" => "",
             "description" => esc_html__( "The link with http:// in front","wprentals")
          ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Icon/Image Postion","wprentals"),
                "param_name" => "icon_type",
                "value" => $icon_position,
                "description" => esc_html__( "left or central","wprentals")
             ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Title Font Size","wprentals"),
                "param_name" => "title_font_size",
                "value" => "24",
                "description" => esc_html__( "Title Font Size","wprentals")
            )

       )
    ) );



    $spacer_type                    = array(1,2);
      vc_map(
           array(
           "name" => esc_html__( "Spacer","wprentals"),
           "base" => "spacer",
           "class" => "",
           "category" => esc_html__( 'Content','wprentals-core'),
           'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
           'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
           'weight'=>102,
            'icon'   =>'wpestate_vc_logo',
            'description'=>esc_html__( 'Spacer Shortcode','wprentals-core'),
           "params" => array(
               array(
                 "type" => "dropdown",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Spacer Type","wprentals"),
                 "param_name" => "type",
                 "value" => $spacer_type,
                 "description" => esc_html__( "Space Type : 1 with no middle line, 2 with middle line","wprentals")
              )   ,
               array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Space height","wprentals"),
                 "param_name" => "height",
                 "value" => "40",
                 "description" => esc_html__( "Space height in px","wprentals")
              )
           )
        )
    );


    $items_type                    = array('properties','articles');
    vc_map( array(
       "name" => esc_html__( "List items by ID","wprentals"),//done
       "base" => "list_items_by_id",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
        'icon'   =>'wpestate_vc_logo',
        'description'=>esc_html__( 'List Items by ID Shortcode','wprentals-core'),
       "params" => array(
            array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Title","wprentals"),
             "param_name" => "title",
             "value" => "",
             "description" => esc_html__( "Section Title","wprentals")
          ),
          array(
             "type" => "dropdown",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "What type of items","wprentals"),
             "param_name" => "type",
             "value" => $items_type,
             "description" => esc_html__( "List properties or articles","wprentals")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Items IDs","wprentals"),
             "param_name" => "ids",
             "value" => "",
             "description" => esc_html__( "List of IDs separated by comma","wprentals")
          ),
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "No of items","wprentals"),
             "param_name" => "number",
             "value" => "3",
             "description" => esc_html__( "How many items do you want to show ?","wprentals")
          ) ,

           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "No of items per row","wprentals"),
             "param_name" => "rownumber",
             "value" => 4,
             "description" => esc_html__( "The number of items per row","wprentals")
          ) ,

           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Link to global listing","wprentals"),
             "param_name" => "link",
             "value" => "#",
             "description" => esc_html__( "link to global listing with http","wprentals")
          ) ,array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wprentals"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wprentals")
            )
       )
    ) );


  $testimonials_type=array(1,2);
    vc_map(
           array(
           "name" => esc_html__( "Testimonial",'wprentals-core'),
           "base" => "testimonial",
           "class" => "",
           "category" => esc_html__( 'Content','wprentals-core'),
           'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
           'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
           'weight'=>102,
           'icon'   =>'wpestate_vc_logo',
           'description'=>esc_html__( 'Testiomonial Shortcode','wprentals-core'),
           "params" => array(
              array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Client Name","wprentals"),
                 "param_name" => "client_name",
                 "value" => "Name Here",
                 "description" => esc_html__( "Client name here","wprentals")
              ),
               array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Title Client","wprentals"),
                 "param_name" => "title_client",
                 "value" => "happy client",
                 "description" => esc_html__( "title or client postion ","wprentals")
              ),
               array(
                 "type" => "textfield",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Image","wprentals"),
                 "param_name" => "imagelinks",
                 "value" => "",
                 "description" => esc_html__( "Path to client picture, (best size 120px  x 120px) ","wprentals")
              ) ,
               array(
                 "type" => "textarea",
                 "holder" => "div",
                 "class" => "",
                 "heading" => esc_html__( "Testimonial Text Here.","wprentals"),
                 "param_name" => "testimonial_text",
                 "value" => "",
                 "description" => esc_html__( "Testimonial Text Here. ","wprentals")
              ),

                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__("Testimonial Type","wprentals-core"),
                    "param_name" => "testimonial_type",
                    "value" => $testimonials_type,
                    "description" => esc_html__("Select 1,2,3 or 4","wprentals-core")
                ),

               array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => esc_html__("Stars for Type 2","wprentals-core"),
                    "param_name" => "stars_client",
                    "value" => "5",
                    "description" => esc_html__("Only for type 2: no of stars for reviews (from 1 to 5, increment by 0.5) ","wprentals-core")
                ),
               array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wprentals"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wprentals")
                ),

           )
        )
    );

    $recent_items_space            = array('yes','no');
    $recent_show_feat_only         = array('no','yes');
    $random_pick                   = array('no','yes');

    vc_map(
    array(
       "name" => esc_html__( "Recent Items","wprentals"),//done
       "base" => "recent_items",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Recent Items Shortcode','wprentals-core'),
       "params" => array(
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Use without spaces between listings? (If yes, title or link to global listing will not show)","wprentals"),
                "param_name" => "full_row",
                "value" => $recent_items_space,
                "description" => esc_html__( "Use without spaces between listings? (If yes, title or link to global listing will not show)","wprentals")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Title","wprentals"),
                "param_name" => "title",
                "value" => "",
                "description" => esc_html__( "Section Title","wprentals")
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "What type of items","wprentals"),
                "param_name" => "type",
                "value" => $items_type,
                "description" => esc_html__( "List properties or articles","wprentals")
            ),
            array(
                "type" => "autocomplete",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type Category names","wprentals"),
                "param_name" => "category_ids",
                "value" => "",
                "description" => esc_html__( "list of category names","wprentals"),
                                "dependency" => array(
                                    "element" => "type",
                                    "value" => "properties"
                                ),
                                'settings' => array(
                                    'multiple' => true,
                                    'sortable' => true,
                                    'min_length' => 1,
                                    'no_hide' => true,
                                    'groups' => false,
                                    'unique_values' => true,
                                    'display_inline' => true,
                                    'values' => $wprentals_property_category_values,
                                ),
            ),
            array(
                "type" => "autocomplete",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type Action names","wprentals"),
                "param_name" => "action_ids",
                "value" => "",
                "description" => esc_html__( "list of action names(*only for properties)","wprentals"),
                                "dependency" => array(
                                    "element" => "type",
                                    "value" => "properties"
                                ),
                                'settings' => array(
                                    'multiple' => true,
                                    'sortable' => true,
                                    'min_length' => 1,
                                    'no_hide' => true,
                                    'groups' => false,
                                    'unique_values' => true,
                                    'display_inline' => true,
                                    'values' => $wprentals_property_action_category_values,
                                ),
            ),
            array(
              "type" => "autocomplete",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "Type City names ","wprentals"),
              "param_name" => "city_ids",
              "value" => "",
              "description" => esc_html__( "list of city names (*only for properties)","wprentals"),
                                "dependency" => array(
                                    "element" => "type",
                                    "value" => "properties"
                                ),
                                'settings' => array(
                                    'multiple' => true,
                                    'sortable' => true,
                                    'min_length' => 1,
                                    'no_hide' => true,
                                    'groups' => false,
                                    'unique_values' => true,
                                    'display_inline' => true,
                                    'values' => $wprentals_property_city_values,
                                ),
                            ),
            array(
              "type" => "autocomplete",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "Type Area Names","wprentals"),
              "param_name" => "area_ids",
              "value" => "",
              "description" => esc_html__( "list of area names (*only for properties)","wprentals"),
                                "dependency" => array(
                                    "element" => "type",
                                    "value" => "properties"
                                ),
                                'settings' => array(
                                    'multiple' => true,
                                    'sortable' => true,
                                    'min_length' => 1,
                                    'no_hide' => true,
                                    'groups' => false,
                                    'unique_values' => true,
                                    'display_inline' => true,
                                    'values' => $wprentals_property_area_values,
                                ),
                            ),
            array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "No of items","wprentals"),
              "param_name" => "number",
              "value" => 4,
              "description" => esc_html__( "how many items","wprentals")
            ) ,
            array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "No of items per row","wprentals"),
              "param_name" => "rownumber",
              "value" => 4,
              "description" => esc_html__( "The number of items per row","wprentals")
            ) ,

            array(
              "type" => "textfield",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "Link to global listing","wprentals"),
              "param_name" => "link",
              "value" => "",
              "description" => esc_html__( "link to global listing","wprentals")
            ),array(
              "type" => "dropdown",
              "holder" => "div",
              "class" => "",
              "heading" => esc_html__( "Show featured listings only?","wprentals"),
              "param_name" => "show_featured_only",
              "value" => $recent_show_feat_only,
              "description" => esc_html__( "Show featured listings only?","wprentals")
            ) ,
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Extra Class Name","wprentals"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__( "Extra Class Name","wprentals")
            ),array(
             "type" => "dropdown",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Random Pick","wprentals"),
             "param_name" => "random_pick",
             "value" => $random_pick,
             "description" => esc_html__( "Choose if properties should display randomly on page refresh. (*only for properties). The yes option may cause the site to load slowly so use it cautiously!","wprentals")
          )
        )
    )
    );


      $design_types=array(1=>1,2=>2);
    vc_map(
    array(
       "name" => esc_html__( "Featured Owner","wprentals"),
       "base" => "featured_agent",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Featured Owner Shortcode','wprentals-core'),
       "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Owner Id","wprentals"),
                "param_name" => "id",
                "value" => "0",
                "description" => esc_html__( "Owner Id","wprentals")
            ),
            array(
                "type" => "textarea",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Notes","wprentals"),
                "param_name" => "notes",
                "value" => "",
                "description" => esc_html__( "Notes for featured owner","wprentals")
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type","wprentals"),
                "param_name" => "design_type",
                "value" => $design_types,
                "description" => esc_html__( "Design Type 1 or 2","wprentals")
            )
       )
    )
    );

    $featured_article_type=array(
            1=>__("type1","wprentals"),
            2=>__("type2","wprentals"),
            3=>__("type3","wprentals")
        );
    vc_map(
       array(
       "name" => esc_html__( "Featured Article","wprentals"),
       "base" => "featured_article",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Featured Article Shortcode','wprentals-core'),
       "params" => array(
            array(
               "type" => "textfield",
               "holder" => "div",
               "class" => "",
               "heading" => esc_html__( "Id of the article","wprentals"),
               "param_name" => "id",
               "value" => "",
               "description" => esc_html__( "The id of the article","wprentals")
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type","wprentals"),
                "param_name" => "type",
                "value" => $featured_article_type,
                "description" => esc_html__( "Design Type 1,2 or 3","wprentals")
            )
        )
    )
    );

    $featured_prop_type=array(
            1=>__("type1","wprentals"),
            2=>__("type2","wprentals"),
            3=>__("type3","wprentals")
        );
    vc_map(
    array(
       "name" => esc_html__( "Featured Listing","wprentals"),
       "base" => "featured_property",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Featured Listing Shortcode','wprentals-core'),
       "params" => array(
            array(
               "type" => "textfield",
               "holder" => "div",
               "class" => "",
               "heading" => esc_html__( "Listing id","wprentals"),
               "param_name" => "id",
               "value" => "",
               "description" => esc_html__( "Listing id","wprentals")
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__( "Type","wprentals"),
                "param_name" => "type",
                "value" => $featured_prop_type,
                "description" => esc_html__( "Design Type 1, 2 or 3","wprentals")
            )
        )
    )
    );


    vc_map(array(
       "name" => esc_html__( "Login Form","wprentals"),
       "base" => "login_form",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Login Form Shortcode','wprentals-core'),
       "params" => array( array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Register link text","wprentals"),
             "param_name" => "register_label",
             "value" => "",
             "description" => esc_html__( "Register link text","wprentals")
            )     ,
            array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Register page url","wprentals"),
             "param_name" => "register_url",
             "value" => "",
             "description" => esc_html__( "Register page url","wprentals")
          )      )
    )
    );


    vc_map(
     array(
       "name" => esc_html__( "Register Form","wprentals"),
       "base" => "register_form",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Register Form Shortcode','wprentals-core'),
       "params" => array()
    )

    );




    vc_map(
        array(
       "name" => esc_html__( "Advanced Search","wprentals"),
       "base" => "advanced_search",
       "class" => "",
       "category" => esc_html__( 'Content','wprentals-core'),
       'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
       'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
       'weight'=>100,
       'icon'   =>'wpestate_vc_logo',
       'description'=>esc_html__( 'Advanced Search Shortcode','wprentals-core'),
       "params" => array(
           array(
             "type" => "textfield",
             "holder" => "div",
             "class" => "",
             "heading" => esc_html__( "Title","wprentals"),
             "param_name" => "title",
             "value" => "",
             "description" => esc_html__( "Section Title","wprentals")
          ))
    )


    );



     vc_map(array(
        "name" => esc_html__("Categories Slider", "wprentals"), //done
        "base" => "places_slider",
        "class" => "",
        "category" => esc_html__('Content', 'wprentals-core'),
        'admin_enqueue_js' => array(get_template_directory_uri() . '/vc_extend/bartag.js'),
        'admin_enqueue_css' => array(get_template_directory_uri() . '/vc_extend/bartag.css'),
        'weight' => 100,
        'icon' => 'wpestate_vc_logo',
        'description' => esc_html__('Categories Slider Shortcode', 'wprentals-core'),
        "params" => array(
            array(
                "type" => "autocomplete",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__("Type Categories, Actions, Cities or Areas (Neighborhoods) you want to show", "wprentals"),
                "param_name" => "place_list",
                "value" => "",
                "description" => esc_html__("Type Categories, Actions, Cities or Areas (Neighborhoods) you want to show", "wprentals"),
                'settings' => array(
                    'multiple' => true,
                    'sortable' => true,
                    'min_length' => 1,
                    'no_hide' => true, // In UI after select doesn't hide an select list
                    'groups' => false, // In UI show results grouped by groups
                    'unique_values' => true, // In UI show results except selected. NB! You should manually check values in backend
                    'display_inline' => true, // In UI show results inline view
                    'values' => $all_tax,
                ),
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__("Items per row", "wprentals"),
                "param_name" => "place_per_row",
                "value" => "3",
                "description" => esc_html__("How many items listed per row?", "wprentals")
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => esc_html__("Extra Class Name", "wprentals"),
                "param_name" => "extra_class_name",
                "value" => "",
                "description" => esc_html__("Extra Class Name", "wprentals")
            )
        )
        )
    );

    }



endif;
function custom_css_wpestate($class_string, $tag) {
    if ($tag =='vc_row' ) {
        $class_string .= ' wpestate_row';
    }

    if ($tag =='vc_row_inner' ) {
        $class_string .= ' wpestate_row_inner';
    }


    if ($tag =='vc_tabs' ) {
      $class_string .= ' wpestate_tabs';
    }

    if ($tag =='vc_tour' ) {
      $class_string .= ' wpestate_tour';
    }

    if ($tag =='vc_accordion' ) {
      $class_string .= ' wpestate_accordion';
    }

    if ($tag =='vc_accordion_tab' ) {
      $class_string .= ' wpestate_accordion_tab';
    }

    if ($tag =='vc_carousel' ) {
      $class_string .= ' wpestate_carousel';
    }

    if ($tag =='vc_progress_bar' ) {
      $class_string .= ' wpestate_progress_bar';
    }

    if ($tag =='vc_toggle' ) {
      $class_string .= ' wpestate_toggle';
    }

    if ($tag =='vc_message' ) {
      $class_string .= ' wpestate_message';
    }

    if ($tag =='vc_posts_grid' ) {
      $class_string .= ' wpestate_posts_grid';
    }

    if ($tag =='vc_cta_button' ) {
      $class_string .= ' wpestate_cta_button ';
    }

    if ($tag =='vc_cta_button2' ) {
      $class_string .= ' wpestate_cta_button2 ';
    }

    if ($tag =='vc_button' ) {
      $class_string .= ' wpestate_vc_button ';
    }

  return $class_string.' '.$tag;
}
endif;

// Filter to Replace default css class for vc_row shortcode and vc_column
add_filter('vc_shortcodes_css_class', 'custom_css_wpestate', 10,2);


?>
