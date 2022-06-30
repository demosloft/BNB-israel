<?php

///////////////////////////////////////////////////////////////////////////////////////////
/////// Js & Css include on front site
///////////////////////////////////////////////////////////////////////////////////////////



if( !function_exists('wpestate_scripts') ):
function wpestate_scripts() {
    global $post;
    $page_template='';
    if(isset($post->ID)){
        $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
    }

    $custom_image               =   '';
    $use_idx_plugins            =   0;
    $wpestate_header_type                =   '';

    $adv_search_type_status     =   intval   ( wprentals_get_option('wp_estate_adv_search_type',''));
    $home_small_map_status      =   esc_html ( wprentals_get_option('wp_estate_home_small_map','') );



    if( isset($post->ID) ) {
        $wpestate_header_type            =   get_post_meta ( $post->ID, 'header_type', true);
    }

    $wpestate_global_header_type         =   wprentals_get_option('wp_estate_header_type','');
    if(is_singular('estate_agent')){
        $wpestate_global_header_type     =   wprentals_get_option('wp_estate_user_header_type','');
    }

    $listing_map                =   'internal';

    if( $wpestate_header_type==5 || $wpestate_global_header_type==4 ){
        $listing_map            =   'top';
    }


    $slugs=array();
    $hows=array();
    $show_price_slider          =   'no';
    $slider_price_position      =   0;
    $custom_advanced_search='no';
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the css files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    wp_enqueue_style('bootstrap',get_template_directory_uri().'/css/bootstrap.css', array(), '1.0', 'all');
    wp_enqueue_style('bootstrap-theme',get_template_directory_uri().'/css/bootstrap-theme.css', array(), '1.0', 'all');
    wp_enqueue_style('wpestate_style',get_stylesheet_uri(), array('bootstrap','bootstrap-theme'), '1.0', 'all');
    wp_enqueue_style('wpestate_media',get_template_directory_uri().'/css/my_media.css', array('bootstrap','bootstrap-theme'), '1.0', 'all');

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the general js files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    wp_enqueue_script("jquery-ui-slider");
    wp_enqueue_script("jquery-ui-datepicker");
    wp_enqueue_script("jquery-ui-autocomplete");
    wp_enqueue_script('slick-slider', get_template_directory_uri().'/js/slick.min.js',array(), '1.0', true);
    wp_enqueue_script('bootstrap.min', trailingslashit( get_template_directory_uri() ).'js/bootstrap.min.js',array(), '1.0',true);
    wp_enqueue_script('query.viewport.mini', trailingslashit( get_template_directory_uri() ).'js/jquery.viewport.mini.js',array(), '1.0', true);
    wp_enqueue_script('modernizr', trailingslashit( get_template_directory_uri() ).'js/modernizr.custom.62456.js',array(), '1.0', false);
    wp_enqueue_script('placeholders.min', trailingslashit( get_template_directory_uri() ).'js/placeholders.min.js',array('jquery'), '1.0', true);
    wp_enqueue_script('slideout.min', get_template_directory_uri().'/js/slideout.min.js',array(), '1.0', true);


    wp_enqueue_script('dense', trailingslashit( get_template_directory_uri() ).'js/dense.js',array('jquery'), '1.0', true);
    wp_enqueue_script('jquery.ui.touch-punch.min', trailingslashit( get_template_directory_uri() ).'js/jquery.ui.touch-punch.min.js',array('jquery'), '1.0', true);
    wp_enqueue_script('jquery.lazyload.min', trailingslashit( get_template_directory_uri() ).'js/jquery.lazyload.min.js',array('jquery'), '1.0', true);
    wp_enqueue_style('jquery-ui.min', trailingslashit( get_template_directory_uri() ) . 'css/jquery-ui.min.css');
    wp_enqueue_script('latinise.min_', get_template_directory_uri().'/js/latinise.min_.js',array('jquery'), '1.0', true);
    wp_enqueue_script('moment', get_template_directory_uri().'/js/moment.min.js',array('jquery'), '1.0', true);


    wp_enqueue_script('daterangepicker', get_template_directory_uri().'/js/daterangepicker.js',array('jquery','moment'), '1.0', true);
    wp_enqueue_style('daterangepicker', trailingslashit( get_template_directory_uri() ) . 'css/daterangepicker.css');

    wp_register_script('venobox.min', get_template_directory_uri().'/js/venobox.min.js',array('jquery'), '1.0', true);
    wp_register_style('venobox', get_theme_file_uri ('/css/venobox.css') );





    wp_register_script('stripe','https://js.stripe.com/v3/',array('jquery'), '1.0', true);
    wp_register_script('wpestate-stripe', get_template_directory_uri().'/js/wpestate-stripe.js',array('stripe'), '1.0', true);
    wp_localize_script('wpestate-stripe', 'wpestate_stripe_vars',
          array(  'pub_key'         =>  esc_html( trim( wprentals_get_option('wp_estate_stripe_publishable_key','') ) ),
                    'pay_failed'    =>  esc_html__('payment failed','wprentals'),
                    'redirect'      =>  wpestate_get_template_link('user_dashboard_my_reservations.php'),
                    'redirect_book'      =>  wpestate_get_template_link('user_dashboard_my_bookings.php'),
                    'redirect_list' =>  wpestate_get_template_link('user_dashboard.php'),
                    'admin_url'     =>  get_admin_url(),
              ));

    if( wpestate_ready_to_load_stripe() ){
        wp_enqueue_script('stripe');
        wp_enqueue_script('wpestate-stripe');
    }

    $book_type = intval( wprentals_get_option('wp_estate_booking_type') );


    $what_place = intval( wprentals_get_option('wp_estate_kind_of_places') );
    if($what_place==2||$what_place==3){
        wp_enqueue_script('places.js@1.13.0',trailingslashit( get_template_directory_uri() ).'js/openstreet/places.js@1.13.0',array('jquery'), '1.0', true);
        wp_enqueue_script('algoliasearch.min',trailingslashit( get_template_directory_uri() ).'js/openstreet/algoliasearch.min.js',array('jquery'), '1.0', true);
    }







    if ( $page_template=='user_dashboard_edit_listing.php' ||  $page_template=='user_dashboard_profile.php' ){
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_script("jquery-ui-sortable");
    }

    $use_generated_pins =   0;
    $load_extra         =   0;
    $post_type          =   get_post_type();

    if( $page_template=='advanced_search_results.php' || 
        $page_template=='property_list_half.php' || 
        $page_template=='property_list.php' || 
        is_tax() || 
        $post_type=='estate_agent' ){    // search results -> pins are added  from template
            $use_generated_pins=1;
            $json_string=array();
            $json_string=json_encode($json_string);
    }else{
        // google maps pins
        if ( wprentals_get_option('wp_estate_readsys','') =='yes' ){

            $path= wpestate_get_pin_file_path_read();
            $request = wp_remote_get($path);
            $json_string = wp_remote_retrieve_body( $request );
        }else{

            $json_string= wpestate_listing_pins();
        }
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // load the Google Maps js files
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $show_g_search_status= esc_html ( wprentals_get_option('wp_estate_show_g_search','') );

    $what_map = intval( wprentals_get_option('wp_estate_kind_of_map') );

    if($what_map==1){
        if( wpestate_check_google_maps_avalability($wpestate_header_type,$wpestate_global_header_type) ){
            wpestate_load_google_map();
        }
    }else if($what_map==2){

        wp_enqueue_script('leaflet',trailingslashit( get_template_directory_uri() ).'js/openstreet/leaflet.js',array('jquery'), '1.0', true);
        wp_enqueue_style('leaflet', trailingslashit( get_template_directory_uri() ).'js/openstreet/leaflet.css', array(), '1.0', 'all');

        wp_enqueue_script('leaflet.markercluster',trailingslashit( get_template_directory_uri() ).'js/openstreet/leaflet.markercluster.js',array('jquery'), '1.0', true);
        wp_enqueue_style('MarkerCluster', trailingslashit( get_template_directory_uri() ).'js/openstreet/MarkerCluster.css', array(), '1.0', 'all');
        wp_enqueue_style('MarkerCluster.Default', trailingslashit( get_template_directory_uri() ).'js/openstreet/MarkerCluster.Default.css', array(), '1.0', 'all');
    }


    $pin_images=wpestate_pin_images();
    $geolocation_radius =   esc_html ( wprentals_get_option('wp_estate_geolocation_radius','') );
    if ($geolocation_radius==''){
          $geolocation_radius =1000;
    }
    $pin_cluster_status =   esc_html ( wprentals_get_option('wp_estate_pin_cluster','') );
    $zoom_cluster       =   esc_html ( wprentals_get_option('wp_estate_zoom_cluster','') );
    $show_adv_search    =   esc_html ( wprentals_get_option('wp_estate_show_adv_search_map_close','') );

    if( isset($post->ID) ){
        $page_lat           =   wpestate_get_page_lat($post->ID);
        $page_long          =   wpestate_get_page_long($post->ID);
        $page_custom_zoom   =   wpestate_get_page_zoom($post->ID);
        $page_custom_zoom_prop   =   get_post_meta($post->ID,'page_custom_zoom',true);
        $closed_height      =   wpestate_get_current_map_height($post->ID);
        $open_height        =   wpestate_get_map_open_height($post->ID);
        $open_close_status  =   wpestate_get_map_open_close_status($post->ID);
    }else{
        $page_lat           =   esc_html( wprentals_get_option('wp_estate_general_latitude','') );
        $page_long          =   esc_html( wprentals_get_option('wp_estate_general_longitude','') );
        $page_custom_zoom   =   esc_html( wprentals_get_option('wp_estate_default_map_zoom','') );
        $page_custom_zoom_prop  =   15;
        $closed_height      =   intval (wprentals_get_option('wp_estate_min_height',''));
        $open_height        =   wprentals_get_option('wp_estate_max_height','');
        $open_close_status  =   esc_html( wprentals_get_option('wp_estate_keep_min','' ) );
    }









    wp_register_script('fullcalendar', get_template_directory_uri().'/js/fullcalendar56.js',array('jquery','moment'), '1.0', true);
    wp_register_style('fullcalendar', trailingslashit( get_template_directory_uri() ) . 'css/fullcalendar56.css');

    $date_lang_status= esc_html ( wprentals_get_option('wp_estate_date_lang','') );

    if($date_lang_status!='xx'){
        $handle="datepicker-".$date_lang_status;
        $name="datepicker-".$date_lang_status.".js";
        wp_enqueue_script($handle, trailingslashit( get_template_directory_uri() ).'js/i18n/'.$name,array('jquery'), '1.0', true);
        wp_register_script('wprentals_per_hour_locale', trailingslashit( get_template_directory_uri() ).'js/locales/'.strtolower($date_lang_status).'.js',array('jquery','fullcalendar'), '1.0', true);
          wp_localize_script('wprentals_per_hour_locale', 'wprentals_per_hour_locale_vars',
                  array(  'date_lang_status'  =>  esc_html($date_lang_status ),
                ));
    }

    if( get_post_type() === 'estate_property' && !is_tax() && !is_search() && !is_tag() ){
        $load_extra =   1;
        $google_camera_angle           =   intval( esc_html(get_post_meta($post->ID, 'google_camera_angle', true)) );
        $wpestate_header_type          =   get_post_meta ( $post->ID, 'header_type', true);
        $wpestate_global_header_type   =   wprentals_get_option('wp_estate_header_type','');
        $small_map=0;
        if ( $wpestate_header_type == 0 ){ // global
            if ($wpestate_global_header_type != 4){
                $small_map=1;
            }
        }else{
            if($wpestate_header_type!=5){
                $small_map=1;
            }
        }




        $book_type= wprentals_return_booking_type($post->ID);
        if($book_type==2 || $book_type==3  ){
            wp_enqueue_script('fullcalendar');
            wp_enqueue_script('wprentals_per_hour_locale');
            wp_enqueue_style('fullcalendar');
        }


        $single_json_string= wpestate_single_listing_pins($post->ID);

        wp_enqueue_script('wpestate_googlecode_property',trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code_listing.js',array('jquery','wpestate_mapfunctions_base'), '1.0', true);
        wp_localize_script('wpestate_googlecode_property', 'googlecode_property_vars',
              array(  'general_latitude'  =>  esc_html( wprentals_get_option('wp_estate_general_latitude','') ),
                      'general_longitude' =>  esc_html( wprentals_get_option('wp_estate_general_longitude','') ),
                      'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                      'markers'           =>  $json_string,
                      'single_marker'     =>  $single_json_string,
                      'single_marker_id'  =>  $post->ID,
                      'camera_angle'      =>  $google_camera_angle,
                      'idx_status'        =>  $use_idx_plugins,
                      'page_custom_zoom'  =>  $page_custom_zoom_prop,
                      'current_id'        =>  $post->ID,
                      'generated_pins'    =>  0,
                      'small_map'          => $small_map
                   )
          );




    }else if( $page_template=='contact_page.php'  ){
        $load_extra =   1;
        if($custom_image    ==  ''){
            wp_enqueue_script('wpestate_googlecode_contact', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code_contact.js',array('jquery','wpestate_mapfunctions_base'), '1.0', true);
            $hq_latitude =  esc_html( wprentals_get_option('wp_estate_hq_latitude','') );
            $hq_longitude=  esc_html( wprentals_get_option('wp_estate_hq_longitude','') );

            if($hq_latitude==''){
                $hq_latitude='40.781711';
            }

            if($hq_longitude==''){
                $hq_longitude='-73.955927';
            }
            $json_string=wpestate_contact_pin();

        wp_localize_script('wpestate_googlecode_contact', 'googlecode_contact_vars',
            array(  'hq_latitude'       =>  $hq_latitude,
                    'hq_longitude'      =>  $hq_longitude,
                    'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                    'markers'           =>  $json_string,
                    'page_custom_zoom'  =>  $page_custom_zoom,
                    'address'           =>  esc_html( wprentals_get_option('wp_estate_co_address', '') )  ,
                    'title'             =>  esc_html( wprentals_get_option('wp_estate_company_name', '') )
                   )
          );
        }

    }else {

        $load_extra =   1;

        wp_enqueue_script('wpestate_googlecode_regular', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_code.js',array('jquery','wpestate_mapfunctions_base'), '1.0', true);
        wp_localize_script('wpestate_googlecode_regular', 'googlecode_regular_vars',
            array(  'general_latitude'  =>  $page_lat,
                    'general_longitude' =>  $page_long,
                    'path'              =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                    'markers'           =>  $json_string,
                    'idx_status'        =>  $use_idx_plugins,
                    'page_custom_zoom'  =>  $page_custom_zoom,
                    'generated_pins'    =>  $use_generated_pins,
                    'page_custom_zoom'  =>  $page_custom_zoom,
                    'on_demand_pins'    =>   esc_html ( wprentals_get_option('wp_estate_ondemandmap','') )
                 )
        );


    }


    $measure_sys             = wprentals_get_option('wp_estate_measure_sys','');

    $is_tax=0;
    if( is_tax() ){
        $is_tax=1;
    }

    $wp_estate_property_list_type_adv = wprentals_get_option('wp_estate_property_list_type_adv','');
    $is_property_list=0;
    if (    $page_template=='property_list_half.php' || 
            $page_template=='advanced_search_results.php' && 
            $wp_estate_property_list_type_adv==2 ){
        
                $is_property_list=1;
    }



if ( $page_template=='user_dashboard_edit_listing.php' || 
     $page_template=='property_list_half.php'  ){
        $load_extra =   1;
    }

    if($load_extra ==   1){
            $slugs                  =   array();
            $hows                   =   array();
            $adv_search_what        =   wprentals_get_option('wp_estate_adv_search_what');
            $adv_search_label       =   wprentals_get_option('wp_estate_adv_search_label');
            $adv_search_how         =   wprentals_get_option('wp_estate_adv_search_how');


            $slider_price_position  =   0;
            $counter                =   0;
            if(is_array($adv_search_what)){
                foreach($adv_search_what as $key=>$search_field){
                    $counter++;
                    if($search_field=='types'){
                        $slugs[]='adv_actions';
                    }
                    else if($search_field=='categories'){
                        $slugs[]='adv_categ';
                    }
                    else if($search_field=='cities'){
                        $slugs[]='advanced_city';
                    }
                    else if($search_field=='areas'){
                        $slugs[]='advanced_area';
                    }
                    else if($search_field=='county / state'){
                        $slugs[]='county-state';
                    }
                    else if($search_field=='property country'){
                        $slugs[]='property-country';
                    }else if (  $search_field=='property price'  ){
                        $slugs[]='property_price';
                        $slider_price_position=$counter ;
                    }
                    else {
                        $string       =    wpestate_limit45( sanitize_title ($adv_search_label[$key]) );
                        $slug         =   sanitize_key($string);
                        $slugs[]=$slug;
                    }
                }
            }

            if(is_array($adv_search_how)){
                foreach($adv_search_how as $key=>$search_field){
                    $hows[]= $adv_search_how[$key];
                }
            }



        $bypass_fit_bounds=0;
        if( isset($post->ID)    ){
            $bypass_fit_bounds=intval(get_post_meta($post->ID,'bypass_fit_bounds',true));
        }

        //if(is_tax()) {
        //    $bypass_fit_bounds=1;
        //}



        wp_enqueue_script('wpestate_mapfunctions', trailingslashit( get_template_directory_uri() ).'js/google_js/mapfunctions.js',array('jquery'), '1.0', true);
        wp_localize_script('wpestate_mapfunctions', 'mapfunctions_vars',
            array(  'path'                 =>  trailingslashit( get_template_directory_uri() ).'/css/css-images',
                    'pin_images'           =>  $pin_images ,
                    'use_single_image_pin' =>  wprentals_get_option('wp_estate_use_single_image_pin',''),
                    'geolocation_radius'   =>  $geolocation_radius,
                    'adv_search'           =>  $adv_search_type_status,
                    'in_text'              =>  esc_html__( ' in ','wprentals'),
                    'zoom_cluster'         =>  intval($zoom_cluster),
                    'user_cluster'         =>  $pin_cluster_status,
                    'open_close_status'    =>  $open_close_status,
                    'open_height'          =>  $open_height,
                    'closed_height'        =>  $closed_height,
                    'generated_pins'       =>  $use_generated_pins,
                    'geo_no_pos'           =>  esc_html__( 'The browser couldn\'t detect your position!','wprentals'),
                    'geo_no_brow'          =>  esc_html__( 'Geolocation is not supported by this browser.','wprentals'),
                    'geo_message'          =>  esc_html__( 'm radius','wprentals'),
                    'show_adv_search'      =>  $show_adv_search,
                    'custom_search'        =>  $custom_advanced_search,
                    'listing_map'          =>  $listing_map,
                    'slugs'                =>  $slugs,
                    'hows'                 =>  $hows,
                    'measure_sys'          =>  $measure_sys,
                    'close_map'            =>  esc_html__( 'close map','wprentals'),
                    'show_g_search_status' =>  $show_g_search_status,
                    'slider_price'         =>  $show_price_slider,
                    'slider_price_position'=>  $slider_price_position,
                    'map_style'            =>  stripslashes (  wprentals_get_option('wp_estate_map_style','') ),
                    'is_tax'               =>  $is_tax,
                    'is_property_list'     =>  $is_property_list,
                    'bypass_fit_bounds'    =>  $bypass_fit_bounds,
                    'useprice'              =>  esc_html ( wprentals_get_option('wp_estate_use_price_pins','')),
                    'use_price_pins_full_price' =>  esc_html ( wprentals_get_option('wp_estate_use_price_pins_full_price','')),
                    'adv_search_type'           =>  wprentals_get_option('wp_estate_adv_search_type',''),
                    'fields_no'                 =>  intval( wprentals_get_option('wp_estate_adv_search_fields_no')),
                    'slugs'                     =>  $slugs,
                    'hows'                      =>  $hows,
                    'hidden_map'                =>  wpestate_check_show_address_user_rent_property(),
                    )
            );



        wp_enqueue_script('wpestate_mapfunctions_base', trailingslashit( get_template_directory_uri() ).'js/google_js/maps_base.js',array('jquery','wpestate_mapfunctions','wpestate_ajaxcalls'), '1.0', true);
        wp_localize_script('wpestate_mapfunctions_base', 'mapbase_vars',
            array(  'wprentals_map_type'        =>  wprentals_get_option('wp_estate_kind_of_map') ,
                    'wprentals_places_type'     =>  wprentals_get_option('wp_estate_kind_of_places'),
                    'wp_estate_mapbox_api_key'  =>  wprentals_get_option('wp_estate_mapbox_api_key'),
                    'wp_estate_algolia_app_id'  =>  wprentals_get_option('wp_estate_algolia_app_id'),
                    'wp_estate_algolia_api_key' =>  wprentals_get_option('wp_estate_algolia_api_key'),
                    'wp_estate_mapbox_api_key'  =>  wprentals_get_option('wp_estate_mapbox_api_key'),
                )
        );

   } // end load extra





    $login_redirect =   wpestate_get_template_link('user_dashboard_profile.php');
    $show_adv_search_map_close          =   esc_html ( wprentals_get_option('wp_estate_show_adv_search_map_close','') );
    $max_file_size  = 100 * 1000 * 1000;
    $current_user = wp_get_current_user();
    $userID                     =   $current_user->ID;


    $booking_array                  =   array();
    $custom_price                   =   '';
    $default_price                  =   '';
    $cleaning_fee_per_day           =   '';
    $city_fee_per_day               =   '';
    $price_per_guest_from_one       =   '';
    $checkin_change_over            =   '';
    $checkin_checkout_change_over   =   '';
    $min_days_booking               =   '';
    $extra_price_per_guest          =   '';
    $price_per_weekeend             =   '';
    $mega_details                   =   '';
    $booking_start_hour             =   '';
    $booking_end_hour               =   '';
    $rtl_book_hours_calendar        =   false;
    if(isset($post->ID)){
        $custom_price    =  json_encode(  wpml_custom_price_adjust($post->ID));
        $booking_array   =   json_encode(get_post_meta($post->ID, 'booking_dates',true  ));
        $default_price   =   get_post_meta($post->ID,'property_price',true);

        $cleaning_fee_per_day           =   intval  ( get_post_meta($post->ID,  'cleaning_fee_per_day', true) );
        $city_fee_per_day               =   intval   ( get_post_meta($post->ID, 'city_fee_per_day', true) );
        $price_per_guest_from_one       =   intval   ( get_post_meta($post->ID, 'price_per_guest_from_one', true) );
        $checkin_change_over            =   intval   ( get_post_meta($post->ID, 'checkin_change_over', true) );
        $checkin_checkout_change_over   =   intval   ( get_post_meta($post->ID, 'checkin_checkout_change_over', true) );
        $min_days_booking               =   intval   ( get_post_meta($post->ID, 'min_days_booking', true) );
        $extra_price_per_guest          =   intval   ( get_post_meta($post->ID, 'extra_price_per_guest', true) );
        $price_per_weekeend             =   intval   ( get_post_meta($post->ID, 'price_per_weekeend', true) );

        $booking_start_hour             =   get_post_meta($post->ID, 'booking_start_hour', true);
        if($booking_start_hour==''){
            $booking_start_hour='1:00';
        }
        $booking_end_hour               =   get_post_meta($post->ID, 'booking_end_hour',true);
        if  ($booking_end_hour==''){
            $booking_end_hour='24:00';
        }


        if ( is_rtl() ){
             $rtl_book_hours_calendar        =   true;
        }
        $mega_details                   =   json_encode( wpml_mega_details_adjust($post->ID));
    }

    $week_days_control=array(
        '0'=>esc_html__('None','wprentals'),
        '1'=>esc_html__('Monday','wprentals'),
        '2'=>esc_html__('Tuesday','wprentals'),
        '3'=>esc_html__('Wednesday','wprentals'),
        '4'=>esc_html__('Thursday','wprentals'),
        '5'=>esc_html__('Friday','wprentals'),
        '6'=>esc_html__('Saturday','wprentals'),
        '7'=>esc_html__('Sunday','wprentals')
    );

    $submission_curency = wpestate_curency_submission_pick();


    //$direct_payment_details         =   wp_kses( wprentals_get_option('wp_estate_direct_payment_details','') ,$argsx);
    if (function_exists('icl_translate') ){
        $mes =  stripslashes ( esc_html( wprentals_get_option('wp_estate_direct_payment_details','') ) );
        $direct_payment_details      =   icl_translate('wprentals','wp_estate_property_direct_payment_text', $mes );
    }else{
        $direct_payment_details = stripslashes ( esc_html( wprentals_get_option('wp_estate_direct_payment_details','') ) );
    }



    $wp_estate_book_down                =   floatval ( wprentals_get_option('wp_estate_book_down', '') );
    $wp_estate_book_down_fixed_fee      =   floatval ( wprentals_get_option('wp_estate_book_down_fixed_fee', '') );
    $include_expeses                    =   esc_html ( wprentals_get_option('wp_estate_include_expenses','') );

    $dates_types=array(
                '0' =>'yy-mm-dd',
                '1' =>'yy-dd-mm',
                '2' =>'dd-mm-yy',
                '3' =>'mm-dd-yy',
                '4' =>'dd-yy-mm',
                '5' =>'mm-yy-dd',

    );
    $sticky_search = wprentals_get_option('wp_estate_sticky_search');
    if( wpestate_is_user_dashboard() ){
        $sticky_search = 'no';
    }

    wp_enqueue_script('wpestate_control', trailingslashit( get_template_directory_uri() ).'js/control.js',array('jquery'), '1.0', true);
    wp_localize_script('wpestate_control', 'control_vars',
            array(  'searchtext'            =>   esc_html__( 'SEARCH','wprentals'),
                    'searchtext2'           =>   esc_html__( 'Search here...','wprentals'),
                    'path'                  =>   get_template_directory_uri(),
                    'search_room'           =>  esc_html__( 'Type Bedrooms No.','wprentals'),
                    'search_bath'           =>  esc_html__( 'Type Bathrooms No.','wprentals'),
                    'search_min_price'      =>  esc_html__( 'Type Min. Price','wprentals'),
                    'search_max_price'      =>  esc_html__( 'Type Max. Price','wprentals'),
                    'contact_name'          =>  esc_html__( 'Your Name','wprentals'),
                    'contact_email'         =>  esc_html__( 'Your Email','wprentals'),
                    'contact_phone'         =>  esc_html__( 'Your Phone','wprentals'),
                    'contact_comment'       =>  esc_html__( 'Your Message','wprentals'),
                    'zillow_addres'         =>  esc_html__( 'Your Address','wprentals'),
                    'zillow_city'           =>  esc_html__( 'Your City','wprentals'),
                    'zillow_state'          =>  esc_html__( 'Your State Code (ex CA)','wprentals'),
                    'adv_contact_name'      =>  esc_html__( 'Your Name','wprentals'),
                    'adv_email'             =>  esc_html__( 'Your Email','wprentals'),
                    'adv_phone'             =>  esc_html__( 'Your Phone','wprentals'),
                    'adv_comment'           =>  esc_html__( 'Your Message','wprentals'),
                    'adv_search'            =>  esc_html__( 'Send Message','wprentals'),
                    'admin_url'             =>  get_admin_url(),
                    'login_redirect'        =>  $login_redirect,
                    'login_loading'         =>  esc_html__( 'Sending user info, please wait...','wprentals'),
                    'street_view_on'        =>  esc_html__( 'Street View','wprentals'),
                    'street_view_off'       =>  esc_html__( 'Close Street View','wprentals'),
                    'userid'                =>  $userID,
                    'show_adv_search_map_close'=>$show_adv_search_map_close,
                    'close_map'             =>  esc_html__( 'close map','wprentals'),
                    'open_map'              =>  esc_html__( 'open map','wprentals'),
                    'fullscreen'            =>  esc_html__( 'Fullscreen','wprentals'),
                    'default'               =>  esc_html__( 'Default','wprentals'),
                    'addprop'               =>  esc_html__( 'Please wait while we are processing your submission!','wprentals'),
                    'deleteconfirm'         =>  esc_html__( 'Are you sure you wish to delete?','wprentals'),
                    'terms_cond'            =>  esc_html__( 'You must to agree with terms and conditions!','wprentals'),
                    'slider_min'            =>  floatval(wprentals_get_option('wp_estate_show_slider_min_price','')),
                    'slider_max'            =>  floatval(wprentals_get_option('wp_estate_show_slider_max_price','')),
                    'bookconfirmed'         =>  esc_html__( 'Booking request sent. Please wait for owner\'s confirmation!','wprentals'),
                    'bookdenied'            =>  esc_html__( 'The selected period is already booked. Please choose a new one!','wprentals'),
                    'to'                    =>  esc_html__( 'to','wprentals'),
                    'curency'               =>  esc_html( wprentals_get_option('wp_estate_currency_label_main', '') ),
                    'where_curency'         =>  esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') ),
                    'price_separator'       =>  esc_html( wprentals_get_option('wp_estate_prices_th_separator', '') ),
                    'datepick_lang'         =>  esc_html ( wprentals_get_option('wp_estate_date_lang','') ),
                    'transparent_logo'      =>   wprentals_get_option('wp_estate_transparent_logo_image', 'url'),
                    'normal_logo'                       =>    wprentals_get_option('wp_estate_logo_image', 'url'),
                    'setup_weekend_status'              =>   esc_html ( wprentals_get_option('wp_estate_setup_weekend','') ),
                    'mindays'                           =>   esc_html__( 'The selected period is shorter than the minimum required period!','wprentals'),
                    'weekdays'                          =>   json_encode($week_days_control),
                    'stopcheckin'                       =>   esc_html__( 'Check-in date is not correct','wprentals'),
                    'stopcheckinout'                    =>   esc_html__( 'Check-in/Check-out dates are not correct','wprentals'),
                    'from'                              =>   esc_html__('from','wprentals'),
                    'separate_users'                    =>   esc_html ( wprentals_get_option('wp_estate_separate_users','') )  ,
                    'captchakey'                        =>   wprentals_get_option('wp_estate_recaptha_sitekey',''),
                    'usecaptcha'                        =>   wprentals_get_option('wp_estate_use_captcha',''),
                    'unavailable_check'                 =>   esc_html__('Unavailable/Only Check Out','wprentals'),
                    'unavailable'                       =>   esc_html__('Unavailable','wprentals'),
                    'submission_curency'                =>   $submission_curency,
                    'direct_price'                      =>   esc_html__('To be paid','wprentals'),
                    'send_invoice'                      =>   esc_html__('Send me the invoice','wprentals'),
                    'direct_pay'                        =>   $direct_payment_details,
                    'direct_title'                      =>   esc_html__('Direct payment instructions','wprentals'),
                    'direct_thx'                        =>   esc_html__('Thank you. Please check your email for payment instructions.','wprentals'),
                    'guest_any'                         =>   esc_html__('any','wprentals'),
                    'my_reservations_url'               =>   wpestate_get_template_link('user_dashboard_my_reservations.php'),
                    'pls_wait'                          =>   esc_html__('processing, please wait...','wprentals'),
                    'wp_estate_book_down'               =>   $wp_estate_book_down,
                    'wp_estate_book_down_fixed_fee'     =>   $wp_estate_book_down_fixed_fee,
                    'include_expeses'                   =>   $include_expeses,
                    'date_format'                       =>   $dates_types[ intval( wprentals_get_option('wp_estate_date_format','')  )],
                    'stiky_search'                      =>   $sticky_search,
                    'geo_radius_measure'                =>  wprentals_get_option('wp_estate_geo_radius_measure',''),
                    'initial_radius'                    =>  wprentals_get_option('wp_estate_initial_radius',''),
                    'min_geo_radius'                    =>  wprentals_get_option('wp_estate_min_geo_radius',''),
                    'max_geo_radius'                    =>  wprentals_get_option('wp_estate_max_geo_radius',''),
                    'wp_estate_slider_cycle'            =>  wprentals_get_option('wp_estate_slider_cycle',''),
                    'stripe_pay'                        =>  esc_html__('Pay','wprentals'),
                    'stripe_pay_for'                    =>  esc_html__('Payment for package','wprentals'),
                    'new_tab'                           => wprentals_get_option('wp_estate_prop_page_new_tab',''),
                    'gdpr_terms'                        =>    esc_html__('You must agree to GDPR Terms','wprentals'),
                    'send_mess'                         =>    esc_html__('Send Message','wprentals'),
                    'single_guest'                      =>    esc_html__('Guest','wprentals'),
                    'multiple_guest'                    =>    esc_html__('Guests','wprentals'),
                    'single_infant'                     =>    esc_html__('Infant','wprentals'),
                    'multiple_infants'                  =>    esc_html__('Infants','wprentals'),
                    'single_child'                      =>    esc_html__('Child','wprentals'),
                    'multiple_child'                    =>    esc_html__('Children','wprentals'),
                    'guests_max'                        =>    esc_html__('Guests Maximum','wprentals'),

                )
     );

    wp_localize_script('wpestate_control', 'control_vars_property',array(
        'custom_price'          =>  $custom_price,
        'booking_array'         =>  $booking_array,
        'default_price'         =>  $default_price ,
        'mega_details'          =>   $mega_details,
        'cleaning_fee_per_day'              =>   $cleaning_fee_per_day,
        'city_fee_per_day'                  =>   $city_fee_per_day,
        'price_per_guest_from_one'          =>   $price_per_guest_from_one,
        'checkin_change_over'               =>   $checkin_change_over,
        'checkin_checkout_change_over'      =>   $checkin_checkout_change_over,
        'min_days_booking'                  =>   $min_days_booking,
        'booking_start_hour'                =>   $booking_start_hour,
        'booking_end_hour'                  =>   $booking_end_hour,
        'rtl_book_hours_calendar'           =>   $rtl_book_hours_calendar,
        'extra_price_per_guest'             =>   $extra_price_per_guest,
        'price_per_weekeend'                =>   $price_per_weekeend,
    ));


    $adv_search_type                 =   wprentals_get_option('wp_estate_adv_search_type');
    $adv_search_what_half            =   wprentals_get_option('wp_estate_adv_search_what');
    $adv_search_how_half             =   wprentals_get_option('wp_estate_adv_search_how');

    if($adv_search_type=='newtype' || $adv_search_type=='oldtype' ){
        $adv_search_what_half   =   wprentals_get_option('wp_estate_adv_search_what_half');
        $adv_search_how_half    =   wprentals_get_option('wp_estate_adv_search_how_half');

    }  else if($adv_search_type=='type4' ){
        $adv_search_what_half[]='property_category';
        $adv_search_how_half[]='like';

        $adv_search_what_half[]='property_action_category';
        $adv_search_how_half[]='like';
    }

   // print'mumummmm';
    global $wprentals_admin;
//
   $adv_search_what_half           =   wprentals_get_option('wp_estate_adv_search_what_half_map') ;
     $adv_search_how_half         =   wprentals_get_option('wp_estate_adv_search_how_half_map');
//

    wp_enqueue_script('wpestate_ajaxcalls', trailingslashit( get_template_directory_uri() ).'js/ajaxcalls.js',array('jquery'), '1.0', true);
    wp_localize_script('wpestate_ajaxcalls', 'ajaxcalls_vars',
            array(  'contact_name'          =>  esc_html__( 'Your Name','wprentals'),
                    'contact_email'         =>  esc_html__( 'Your Email','wprentals'),
                    'contact_phone'         =>  esc_html__( 'Your Phone','wprentals'),
                    'contact_comment'       =>  esc_html__( 'Your Message','wprentals'),
                    'adv_contact_name'      =>  esc_html__( 'Your Name','wprentals'),
                    'adv_email'             =>  esc_html__( 'Your Email','wprentals'),
                    'adv_phone'             =>  esc_html__( 'Your Phone','wprentals'),
                    'adv_comment'           =>  esc_html__( 'Your Message','wprentals'),
                    'adv_search'            =>  esc_html__( 'Send Message','wprentals'),
                    'admin_url'             =>  get_admin_url(),
                    'login_redirect'        =>  $login_redirect,
                    'login_loading'         =>  esc_html__( 'Sending user info, please wait...','wprentals'),
                    'userid'                =>  $userID,
                    'prop_featured'         =>  esc_html__( 'Property is featured','wprentals'),
                    'no_prop_featured'      =>  esc_html__( 'You have used all the "Featured" listings in your package.','wprentals'),
                    'favorite'              =>  esc_html__( 'Favorite','wprentals').'<i class="fas fa-heart"></i>',
                    'add_favorite'          =>  esc_html__( 'Add to Favorites','wprentals'),
                    'remove_favorite'       =>  esc_html__( 'remove from favorites','wprentals'),
                    'add_favorite_unit'     =>  esc_html__( 'add to favorites','wprentals'),
                    'saving'                =>  esc_html__( 'saving..','wprentals'),
                    'sending'               =>  esc_html__( 'sending message..','wprentals'),
                    'reserve'               =>  esc_html__( 'Reserve Period','wprentals'),
                    'paypal'                =>  esc_html__( 'Connecting to Paypal! Please wait...','wprentals'),
                    'stripecancel'          =>  esc_html__( 'subscription will be cancelled at the end of the current period','wprentals'),
                    'max_month_no'          =>  intval   ( wprentals_get_option('wp_estate_month_no_show','') ),
                    'processing'            =>  esc_html__( 'processing..','wprentals'),
                    'home'                  =>  get_site_url(),
                    'delete_account'        =>  esc_html__('Confirm your ACCOUNT DELETION request! Clicking the button below will result your account data will be deleted. This means you will no longer be able to login to your account and access your account information: My Profile, My Reservations, My bookings, Invoices. This operation CAN NOT BE REVERSED!','wprentals'),
                    'adv_search_what_half'  =>  $adv_search_what_half,
                    'adv_search_how_half'   =>  $adv_search_how_half,
                    'adv_search_type'       =>  $adv_search_type,
                    'redirect_users'        =>   esc_html   ( wprentals_get_option('wp_estate_redirect_users','') ),
                    'redirect_custom_link'  =>   esc_html   ( wprentals_get_option('wp_estate_redirect_custom_link','') ),
                    'use_gdpr'              =>    wprentals_get_option('wp_estate_use_gdpr'),
                    'gdpr_terms'            =>    esc_html__('You must agree to GDPR Terms','wprentals'),
                    'send_mess'             =>    esc_html__('Send Message','wprentals')

                )
     );



    if( $page_template =='user_dashboard_edit_listing.php' || 
        $page_template =='user_dashboard_add_step1.php'   ){

        $page_lat   = esc_html( wprentals_get_option('wp_estate_general_latitude','') );
        $page_long  = esc_html( wprentals_get_option('wp_estate_general_longitude','') );
        wp_enqueue_script('wpestate_google_map_submit', trailingslashit( get_template_directory_uri() ).'js/google_js/google_map_submit.js',array('jquery'), '1.0', true);
        wp_localize_script('wpestate_google_map_submit', 'google_map_submit_vars',
            array(  'general_latitude'  =>  $page_lat,
                    'general_longitude' =>  $page_long,
                    'geo_fails'        =>  esc_html__( 'Geolocation was not successful for the following reason:','wprentals')
                 )
        );
    }


    if( ( isset($post->ID) && wpestate_check_if_admin_page($post->ID) )  ||  ( 'estate_property' == get_post_type() )  ||  ( 'estate_agent' == get_post_type() )  ){

        $custom_fields = wpestate_reverse_convert_redux_wp_estate_custom_fields();
        usort($custom_fields, function($a, $b) {
            return $a['3'] - $b['3'];
        });

        $tranport_custom_array  =   array();
        $i=0;
        if( !empty($custom_fields)){
            while($i< count($custom_fields) ){

                $data= wprentals_prepare_non_latin($custom_fields[$i][0],$custom_fields[$i][1]);
                $i++;
                $tranport_custom_array[]= $data['key'];
           }
        }



        wp_enqueue_script('fancybox', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox.pack.js',array('jquery'), '1.0', true);
        wp_enqueue_style('jquery.fancybox', trailingslashit( get_template_directory_uri() ).'css/jquery.fancybox.css', array(), '1.0', 'all');


        wp_enqueue_script('wpestate_ajaxcalls_add', trailingslashit( get_template_directory_uri() ).'js/ajaxcalls_add.js',array('jquery'), '1.0', true);
        wp_localize_script('wpestate_ajaxcalls_add', 'ajaxcalls_add_vars',
            array(  'admin_url'                 =>  get_admin_url(),
                    'tranport_custom_array'     =>  json_encode($tranport_custom_array),
                    'wpestate_autocomplete'     =>  wprentals_get_option('wp_estate_wpestate_autocomplete',''),
                    'mandatory_fields'          =>  wprentals_get_option('wp_estate_mandatory_page_fields') ,
                    'mandatory_fields_label'    =>  wpestate_return_all_fields(1),
                    'pls_fill'                  =>  esc_html__('Please complete these fields','wprentals'),
                    'submit_redirect'           =>  wprentals_get_option('wp_estate_submit_redirect') ,
            )
        );

    }

    if ( is_user_logged_in() ) {
        $logged_in="yes";
    } else {
         $logged_in="no";
    }
        $early_discount='';
        $include_children_as_guests='';
        $include_booking_type='';
        if(isset($post->ID)){
            $early_discount             =   floatval(get_post_meta($post->ID, 'early_bird_percent', true));
            $include_booking_type       =   wprentals_return_booking_type($post->ID);
            $include_children_as_guests =   get_post_meta($post->ID,'children_as_guests',true);
        }
        
        $property_js_required=array('jquery','wpestate_control','fancybox');
        if($book_type==2 || $book_type==3  ){
            $property_js_required=array('jquery','wpestate_control','fullcalendar','fancybox');
        }

        wp_register_script('wpestate_property', trailingslashit( get_template_directory_uri() ).'js/property.js',$property_js_required, '1.0', true);

        wp_localize_script('wpestate_property', 'property_vars',
            array(  'plsfill'                 =>    esc_html__( 'Please fill all the forms!','wprentals'),
                    'sending'                 =>    esc_html__( 'Sending Request...','wprentals'),
                    'logged_in'               =>    $logged_in,
                    'notlog'                  =>    esc_html__( 'You need to log in order to book a listing!','wprentals'),
                    'viewless'                =>    esc_html__( 'View less','wprentals'),
                    'viewmore'                =>    esc_html__( 'View more','wprentals'),
                    'nostart'                 =>    esc_html__( 'Check-in date cannot be bigger than Check-out date','wprentals'),
                    'noguest'                 =>    esc_html__('Please select the number of guests','wprentals'),
                    'guestoverload'           =>    esc_html__('The number of guests is greater than the property capacity - ','wprentals'),
                    'guests'                  =>    esc_html__('guests','wprentals'),
                    'early_discount'          =>    $early_discount,
                    'rental_type'             =>    wprentals_get_option('wp_estate_item_rental_type'),
                    'book_type'               =>    $include_booking_type,
                    'reserved'                =>    esc_html__( 'reserved','wprentals'),
                    'use_gdpr'                =>    wprentals_get_option('wp_estate_use_gdpr'),
                    'gdpr_terms'              =>    esc_html__('You must agree to GDPR Terms','wprentals'),
                    'is_woo'                  =>    wprentals_get_option('wp_estate_enable_woo',''),
                    'allDayText'              =>    esc_html__('hours','wprentals'),
                    'clickandragtext'         =>    esc_html__('click and drag to select the hours','wprentals'),
                    'processing'              =>    esc_html__( 'Processing..','wprentals'),
                    'book_now'                =>    esc_html__( 'Book Now','wprentals'),
                    'instant_booking'         =>    esc_html__( 'Instant Booking','wprentals'),
                    'send_mess'               =>    esc_html__('Send Message','wprentals'),
                    'children_as_guests'      =>    $include_children_as_guests,
               )
        );

        
        
        
        if(     'estate_property' == get_post_type() 
            ||  'estate_agent' == get_post_type() 
            ||  $page_template == 'user_dashboard_invoices.php' 
            ||  $page_template == 'user_dashboard_my_reservations.php'  ){

        wp_enqueue_script('wpestate_property');

    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////file upload ajax - profile and user dashboard
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if( $page_template=='user_dashboard_profile.php' || $page_template=='user_dashboard_edit_listing.php'   ){

        $prop_id=0;
        if( isset($_GET['listing_edit']) && is_numeric($_GET['listing_edit'] ) ){
            $prop_id=intval($_GET['listing_edit']);

        }

    	$plup_url = add_query_arg( array(
            'action'    => 'wpestate_me_upload',
            'nonce'     =>  wp_create_nonce('aaiu_allow'),
            'propid'    =>  $prop_id,
        ), esc_url( admin_url('admin-ajax.php') ) );

        $max_images = intval   ( wprentals_get_option('wp_estate_prop_image_number','') );

        $uploader_js = 'ajax-profile-upload';

        $plupload_values = array(
                'runtimes'          => 'html5,flash,html4',
                'max_file_size'     => $max_file_size . 'b',
                'url'               => $plup_url,
                'file_data_name'    => 'aaiu_upload_file',
                'flash_swf_url'     => includes_url('js/plupload/plupload.flash.swf'),
                'filters'           => array(array('title' => esc_html__( 'Allowed Files','wprentals'), 'extensions' => "jpeg,jpg,gif,png,pdf")),
                'multipart'         => true,
                'urlstream_upload'  => true,
                'multipart_params'  => array('button_id'=>'none'),
        );

        if ($page_template=='user_dashboard_edit_listing.php' ) {
        	$tmp_plupload_values = array(
		        'browse_button'     => 'aaiu-uploader',
		        'container'         => 'aaiu-upload-container',
	        );

	        $plupload_values = wp_parse_args($plupload_values,$tmp_plupload_values);
	        $uploader_js = 'ajax-upload';
                $plupload_values['drop_element']      = 'drag-and-drop';
        }

        wp_enqueue_script('ajax-upload', trailingslashit( get_template_directory_uri() ).'js/'.$uploader_js.'.js',array('jquery','plupload-handlers'), '1.0', true);
        wp_localize_script('ajax-upload', 'ajax_vars',
            array(  'ajaxurl'           => esc_url( admin_url('admin-ajax.php') ),
                    'nonce'             => wp_create_nonce('aaiu_upload'),
                    'remove'            => wp_create_nonce('aaiu_remove'),
                    'number'            => 1,
                    'upload_enabled'    => true,
                    'warning'           =>  __('Image needs to be at least 500px height  x 500px wide!','wprentals'),
                    'max_images'        =>  $max_images,
                    'warning_max'      =>  __('You cannot upload more than','wprentals').' '.$max_images.' '.__('images','wprentals'),
                    'path'              =>  trailingslashit( get_template_directory_uri() ),
                    'confirmMsg'        => esc_html__( 'Are you sure you want to delete this?','wprentals'),
                    'plupload'         => $plupload_values
                )
        );
    }


    if( isset($post->ID) && wpestate_check_if_admin_page($post->ID) ){
      wp_enqueue_style( 'wprentals-dashboard_style',  trailingslashit( get_template_directory_uri() ) . '/dashboard/css/dashboard-style.css' );
    //  wp_enqueue_style( 'wprentals-dashboard-lato', "https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" );
    //  wp_enqueue_style( 'wprentals-dashboard-grotesque', "https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&display=swap" );
    wp_enqueue_style( 'wprentals-inter', "https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800;900&display=swap" );
    wp_enqueue_style( 'wprentals-media_dashboard_style',  trailingslashit( get_template_directory_uri() ) . '/dashboard/css/media_dashboard_style.css' );

    }

    if ( is_singular() && get_option( 'thread_comments' ) ){
        wp_enqueue_script( 'comment-reply' );
    }


    if( get_post_type() === 'estate_property' && !is_tax() ){
        wp_enqueue_script('wpestate_property',trailingslashit( get_template_directory_uri() ).'js/property.js',array('jquery'), '1.0', true);
    }

    $protocol = is_ssl() ? 'https' : 'http';
    $general_font = esc_html( wprentals_get_option('wp_estate_general_font', '') );

    $headings_font_subset   =   esc_html ( wprentals_get_option('wp_estate_headings_font_subset','') );
    if($headings_font_subset!=''){
        $headings_font_subset='&amp;subset='.$headings_font_subset;
    }

    // embed custom fonts from admin
    if($general_font && $general_font!='x'){
        $general_font =  str_replace(' ', '+', $general_font);
        wp_enqueue_style( 'wpestate-custom-font',"https://fonts.googleapis.com/css?family=$general_font:300,400,700,900$headings_font_subset");
    }else{
       // wp_enqueue_style( 'wpestate-railway', "https://fonts.googleapis.com/css?family=Raleway:500,600,400,700,800&amp;subset=latin,latin-ext" );
      // wp_enqueue_style( 'wpestate-opensans', "https://fonts.googleapis.com/css?family=Open+Sans:400,600,300&amp;subset=latin,latin-ext" );
         wp_enqueue_style( 'wpestate-roboto', "https://fonts.googleapis.com/css?family=Roboto:300,400,500,600,700,900&display=swap&subset=latin-ext" );
    }

    $headings_font = esc_html( wprentals_get_option('wp_estate_headings_font', '') );
    if($headings_font && $headings_font!='x'){
       $headings_font =  str_replace(' ', '+', $headings_font);
       wp_enqueue_style( 'wpestate-custom-secondary-font', "https://fonts.googleapis.com/css?family=$headings_font:400,500,300" );
    }

    wp_enqueue_style( 'fontello',  trailingslashit( get_template_directory_uri() ) .'css/fontello.css' );
    wp_enqueue_style( 'font-awesome.min',  trailingslashit( get_template_directory_uri() ) . 'css/fontawesome/css/font-awesome.min.css' );
    wp_enqueue_style( 'font-awesome5.min',  trailingslashit( get_template_directory_uri() ) . 'css/fontawesome/all.css' );

    if(!is_search() && !is_404() && !is_tax() && !is_category() && !is_tag()){

        //if( wpestate_check_if_admin_page($post->ID) || is_singular('estate_property') ){

                $wp_estate_book_down=wprentals_get_option('wp_estate_book_down', '');
                if($wp_estate_book_down==''){
                    $wp_estate_book_down=10;
                }
                $book_down_fixed_fee              =   floatval( wprentals_get_option('wp_estate_book_down_fixed_fee','') );
                $wp_estate_service_fee_fixed_fee  =   floatval( wprentals_get_option('wp_estate_service_fee_fixed_fee','') );
                $wp_estate_service_fee            =   floatval( wprentals_get_option('wp_estate_service_fee','') );

                if ( class_exists( 'WooCommerce' ) ) {
                    global $woocommerce;
                    $checkout_url = wc_get_checkout_url();
                }else{
                    $checkout_url='';
                }

                $bed_types= esc_html( wprentals_get_option('wp_estate_bed_list', '') );
                $bed_types_array= explode(',', $bed_types);
                $bed_types_array_names=array();
                foreach($bed_types_array as $key=>$value){
                    $bed_types_array_names[sanitize_key( wpestate_convert_cyrilic($value))]=$value;
                }


                $bed_types_array   =   array_map('wpestate_convert_cyrilic',$bed_types_array);
                $bed_types_array2   =   array_map('sanitize_key',$bed_types_array);

                wp_enqueue_script('jquery.chart.min', trailingslashit( get_template_directory_uri() ).'js/chart.bundle.min.js',array('jquery'), '1.0', true);

                wp_register_script('wpestate_dashboard-control', trailingslashit( get_template_directory_uri() ).'js/dashboard-control.js',array('jquery'), '1.0', true);
                wp_localize_script('wpestate_dashboard-control', 'dashboard_vars',
                    array(  'deleting'                  =>  esc_html__( 'deleting...','wprentals'),
                            'searchtext2'               =>  esc_html__( 'Search here...','wprentals'),
                            'currency_symbol'           =>  wpestate_curency_submission_pick(),
                            'where_currency_symbol'     =>  esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') ),
                            'book_down'                 =>  $wp_estate_book_down,
                            'book_down_fixed_fee'       =>  $book_down_fixed_fee,
                            'discount'                  =>  esc_html__( 'Discount','wprentals'),
                            'delete_inv'                =>  esc_html__( 'Delete Invoice','wprentals'),
                            'issue_inv'                 =>  esc_html__( 'Invoice Issued','wprentals'),
                            'confirmed'                 =>  esc_html__( 'Confirmed','wprentals'),
                            'issue_inv1'                =>  esc_html__( 'Issue invoice','wprentals'),
                            'sending'                   =>  esc_html__( 'sending message...','wprentals'),
                            'send_reply'                =>  esc_html__( 'Send Reply','wprentals'),
                            'plsfill'                   =>  esc_html__( 'Please fill in all the fields','wprentals'),
                            'datesb'                    =>  esc_html__( 'Dates are already booked. Please check the calendar for free days!','wprentals'),
                            'datepast'                  =>  esc_html__( 'You cannot select a date in the past! ','wprentals'),
                            'bookingstart'              =>  esc_html__( 'Start date cannot be greater than end date !','wprentals'),
                            'selectprop'                =>  esc_html__( 'Please select a property !','wprentals'),
                            'err_title'                 =>  esc_html__( 'Please submit a title !','wprentals'),
                            'err_category'              =>  esc_html__( 'Please pick a category !','wprentals'),
                            'err_type'                  =>  esc_html__( 'Please pick a typr !','wprentals'),
                            'err_guest'                 =>  esc_html__( 'Please select the guest no !','wprentals'),
                            'err_city'                  =>  esc_html__( 'Please pick a city !','wprentals'),
                            'sending'                   =>  esc_html__( 'sending...','wprentals'),
                            'doublebook'                =>  esc_html__( 'This period is already booked','wprentals'),
                            'deleted_feed'              =>  esc_html__( 'Delete imported dates','wprentals'),
                            'sent'                      =>  esc_html__( 'done','wprentals'),
                            'service_fee_fixed_fee'     =>  $wp_estate_service_fee_fixed_fee,
                            'service_fee'               =>  $wp_estate_service_fee,
                            'single_fee'                =>  esc_html__( 'Single Fee','wprentals'),
                            'per_hdnight'               =>  esc_html__( 'Per H/Day/Night','wprentals'),
                            'per_guest'                 =>  esc_html__( 'Per Guest','wprentals'),
                            'reserved'                  =>  esc_html__( 'reserved','wprentals'),
                            'checkout_url'              =>  esc_url($checkout_url),
                            'delete_mess'               =>  esc_html__('Are you sure you want to delete this?','wprentals'),
                            'bed_types'                 =>  $bed_types_array2,
                            'bed_types_names'           =>  $bed_types_array_names,
                            'bedroom'                   =>  esc_html__('Bedroom','wprentals'),
                            'cancel_mess'               =>  esc_html__('Are you sure you want to cancel?','wprentals'),
                            'processing'                =>  esc_html__( 'processing..','wprentals'),
                            'view_details'              =>  esc_html__( 'View Details','wprentals'),
                            'invoice_created'           =>  esc_html__( 'Invoice Created - Check & Pay','wprentals'),
                            'invoice_pay'               =>  esc_html__( 'Pay Invoice in Full','wprentals'),
                            'invoice_issue'             =>  esc_html__( 'Issue invoice','wprentals'),
                            'trip_details'              =>  esc_html__( 'Trip Details','wprentals'),
                    )
                );


        if( wpestate_check_if_admin_page($post->ID) || is_singular('estate_property') ){
            wp_enqueue_script('wpestate_dashboard-control');
        }
    }






    if(wprentals_get_option('wp_estate_use_captcha','')=='yes'){
        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?onload=wpestate_onloadCallback&render=explicit&hl=iw" async defer',array('jquery'), '1.0', true);
    }

}
endif; // end   wpestate_scripts







///////////////////////////////////////////////////////////////////////////////////////////
/////// Js & Css include on admin site
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_admin') ):

function wpestate_admin($hook_suffix) {
    global $post;
    global $pagenow;
    global $typenow;

    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_script('my-upload');
    wp_enqueue_style('thickbox');
    wp_enqueue_script("jquery-ui-sortable");  
    wp_enqueue_script("jquery-ui-autocomplete");
    wp_enqueue_style('wpestate_adminstyle', trailingslashit( get_template_directory_uri() ) . '/css/admin.css');
    wp_enqueue_script('wpestate_admin-control', trailingslashit( get_template_directory_uri() ).'/js/admin-control.js',array('jquery'), '1.0', true);
    wp_localize_script('wpestate_admin-control', 'admin_control_vars',
        array( 'ajaxurl'             => esc_url(admin_url('admin-ajax.php') ),
                'processing'         =>  esc_html__( 'processing..','wprentals'), )
    );

    wp_enqueue_style( 'font-awesome.min',  trailingslashit( get_template_directory_uri() ) . '/css/fontawesome/css/font-awesome.min.css' );
    wp_enqueue_style( 'font-awesome5.min',  trailingslashit( get_template_directory_uri() ) . '/css/fontawesome/all.css' );


    if($hook_suffix=='post-new.php' || $hook_suffix=='post.php'){
        wp_enqueue_script("jquery-ui-datepicker");
        wp_enqueue_style( 'font-awesome.min',  trailingslashit( get_template_directory_uri() ) . '/css/fontawesome/css/font-awesome.min.css' );
        wp_enqueue_style('jquery.ui.theme', trailingslashit( get_template_directory_uri() ). '/css/jquery-ui.min.css');
    }

    if (empty($typenow) && !empty($_GET['post'])) {
        $allowed_html   =   array();
        $post = get_post(wp_kses($_GET['post'],$allowed_html));
        $typenow = $post->post_type;
    }


    if (is_admin() &&  ( $pagenow=='post-new.php' || $pagenow=='post.php') && $typenow=='estate_property') {

        $what_map = intval( wprentals_get_option('wp_estate_kind_of_map') );

        $libraries='';

        if( intval( wprentals_get_option('wp_estate_kind_of_places') ) == 1 ){
            $libraries ='&libraries=places&amp;language=en';
        }


        if($what_map==1){
            $google_maps_link_ssl   =   'https://maps-api-ssl.google.com/maps/api/js?v=3.38'.$libraries.'&key='.esc_html(wprentals_get_option('wp_estate_api_key', '') );
            $google_maps_link       =   'http://maps.googleapis.com/maps/api/js?v=3.38'.$libraries.'&key='.esc_html(wprentals_get_option('wp_estate_api_key', '') );

            if ( is_ssl() ) {
                wp_enqueue_script('wpestate_googlemap',    $google_maps_link_ssl ,array('jquery'), '1.0', false);
            }else{
                wp_enqueue_script('wpestate_googlemap',   $google_maps_link   ,array('jquery'), '1.0', false);
            }

        }else if($what_map==2){
            wp_enqueue_script('leaflet',trailingslashit( get_template_directory_uri() ).'js/openstreet/leaflet.js',array('jquery'), '1.0', true);
            wp_enqueue_style('leaflet', trailingslashit( get_template_directory_uri() ).'js/openstreet/leaflet.css', array(), '1.0', 'all');
        }

        $what_place = intval( wprentals_get_option('wp_estate_kind_of_places') );
        if($what_place==2 || $what_place==3){
            wp_enqueue_script('places.js@1.13.0',trailingslashit( get_template_directory_uri() ).'js/openstreet/places.js@1.13.0',array('jquery'), '1.0', true);
            wp_enqueue_script('algoliasearch.min',trailingslashit( get_template_directory_uri() ).'js/openstreet/algoliasearch.min.js',array('jquery'), '1.0', true);
        }

        wp_enqueue_script('wpestate_admin_google',   trailingslashit( get_template_directory_uri() ).'js/google_js/admin_google.js',array('jquery'), '1.0', true);



        $wp_estate_general_latitude  = floatval(get_post_meta($post->ID, 'property_latitude', true));
        $wp_estate_general_longitude = floatval(get_post_meta($post->ID, 'property_longitude', true));

        if ($wp_estate_general_latitude=='' || $wp_estate_general_longitude=='' ){
            $wp_estate_general_latitude    = esc_html( wprentals_get_option('wp_estate_general_latitude','') ) ;
            $wp_estate_general_longitude   = esc_html( wprentals_get_option('wp_estate_general_longitude','') );

            if($wp_estate_general_latitude==''){
               $wp_estate_general_latitude ='40.781711';
            }

            if($wp_estate_general_longitude==''){
               $wp_estate_general_longitude='-73.955927';
            }
        }

        wp_localize_script('wpestate_admin_google', 'admin_google_vars',
        array(  'general_latitude'          =>  $wp_estate_general_latitude,
                'general_longitude'         =>  $wp_estate_general_longitude,
                'postId'                    =>  $post->ID,
                'geo_fails'                 =>  esc_html__( 'Geolocation was not successful for the following reason:','wprentals') ,
                'wprentals_map_type'        =>  wprentals_get_option('wp_estate_kind_of_map') ,
                'wprentals_places_type'     =>  wprentals_get_option('wp_estate_kind_of_places'),
                'wp_estate_mapbox_api_key'  =>  wprentals_get_option('wp_estate_mapbox_api_key'),
                'wp_estate_algolia_app_id'  =>  wprentals_get_option('wp_estate_algolia_app_id'),
                'wp_estate_algolia_api_key'  =>  wprentals_get_option('wp_estate_algolia_api_key'),

              )
        );
     }

    wp_enqueue_script('wpestate_admin', trailingslashit( get_template_directory_uri() ).'/js/admin.js',array('jquery','admin_colorpicker'), '1.0', true);
    wp_localize_script('wpestate_admin', 'admin_vars',
        array( 'ajaxurl'            =>esc_url( admin_url('admin-ajax.php')),
             'admin_url'                 =>  get_admin_url(),
                    'number'                    =>  1,
                    'warning'                   =>  __('Warning !','wprentals'),
                    'path'                      =>  get_theme_file_uri(),)
    );

    wp_enqueue_style ('colorpicker', trailingslashit( get_template_directory_uri() ).'/css/colorpicker.css', false, '1.0', 'all');
    wp_enqueue_script('admin_colorpicker', trailingslashit( get_template_directory_uri() ).'/js/admin_colorpicker.js',array('jquery'), '1.0', true);


    $admin_pages = array('appearance_page_libs/theme-admin','appearance_page_libs/theme-import','toplevel_page_libs/theme-admin');

    if(in_array($hook_suffix, $admin_pages)) {
         wp_enqueue_script('wpestate_config-property', trailingslashit( get_template_directory_uri() ).'/js/config-property.js',array('jquery'), '1.0', true);
    }

    $plup_url = add_query_arg( array(
        'action' => 'me_upload_demo',
        'nonce' => wp_create_nonce('aaiu_allow'),
    ), esc_url( admin_url('admin-ajax.php')) );
    $max_file_size  = 100 * 1000 * 1000;

    $upload_dir = wp_upload_dir();
    $destination = $upload_dir['baseurl'];
    $destination_path = $destination . '/estate_templates/';


}

endif; // end   wpestate_admin
?>
