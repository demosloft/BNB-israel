<?php


function wpestate_convert_to_redux_framework_ammenities(){
  
        $feature_list                   =   esc_html(wprentals_get_option('wp_estate_feature_list','') );
        $feature_list_array             =   explode( ',',$feature_list);
        $features_to_check              =   array();
        
        foreach($feature_list_array as $key => $value){
       
            if (strpos($value, ' ') !== false) {
                $features_to_check[]=$value;
            }
        }
        
        $args = array(
            'post_type'         =>  'estate_property',
            'post_status'       =>  'published',
            'posts_per_page'    =>  -1,
        
        );

        $prop_selection =   new WP_Query($args);
        if ($prop_selection->have_posts()){  
            while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                $post_id=get_the_id();
           
                foreach($features_to_check as $key => $value){
                    $post_var_name1 = str_replace(' ','_', trim($value) ); 
                    $post_var_name =   str_replace(' ','_', trim($value) );
                    $post_var_name =   wpestate_limit45(sanitize_title( $post_var_name ));

    
                    $current_feature= get_post_meta($post_id, $post_var_name, true);
                    if($current_feature==1){
                        $data_feature   =   wprentals_prepare_non_latin($value,$value);  
                        $post_var_name  =   $data_feature['key'];
                        update_post_meta($post_id, $post_var_name, $current_feature);
                    }
                }

            endwhile;
        }
      
}







function wpestate_convert_to_redux_framework(){
    global $global_values;
    $all_options = array(
        'wp_estate_twitter_access_secret',
        'wp_estate_twitter_consumer_secret',
        'wp_estate_twitter_consumer_key',
        'wp_estate_twitter_access_token',
        'wp_estate_twilio_phone_no',
        'wp_estate_twilio_api_key',
        'wp_estate_twilio_auth_token',
        'wp_estate_sms_verification',
 
        'wpestate_advanced_exteded',
        'wp_estate_stripe_secret_key',
        'wp_estate_stripe_publishable_key',
        'wp_estate_paypal_client_id',
        'wp_estate_paypal_client_secret',
        'wp_estate_paypal_rec_email',
        'wp_estate_include_expenses',
        'wp_estate_free_mem_list_unl',
        'wp_estate_direct_payment_details',
        'wp_estate_paypal_api',
        'wp_estate_prop_image_number',
        'wp_estate_item_description_label',
        'wp_estate_yelp_client_id',
        'wp_estate_yelp_client_secret',
        'wp_estate_yelp_results_no',
        'wp_estate_yelp_dist_measure',
        'wp_estate_yelp_categories',
        'wp_estate_api_key',
        'wp_estate_theme_slider_type',
        'wp_estate_theme_slider_manual',
        'wp_estate_month_no_show',
        'wp_estate_multi_curr',
        'wp_estate_booking_type',
        'wp_estate_show_adv_search_extended',
        'wp_estate_use_price_pins_full_price',
        'wp_estate_use_price_pins',
        'wp_estate_show_menu_dashboard',
        'wp_estate_adv_back_color',
        'wp_estate_adv_back_color_opacity',
        'wp_estate_adv_search_back_button',
        'wp_estate_adv_search_back_hover_button',
        'wp_estate_sticky_search',
        'wp_estate_search_on_start',
        'wp_estate_use_float_search_form',
        'wp_estate_float_form_top',
        'wp_estate_float_form_top_tax',
        'wp_estate_search_field_label',
        'wp_estate_adv_search_label',
        'wp_estate_adv_search_how',
        'wp_estate_adv_search_what',
        'wp_estate_search_fields_no_per_row',
        'wp_estate_adv_search_fields_no',
        'wp_estate_adv_search_label_for_form',
        'wp_estate_show_dropdowns',
        'wpestate_autocomplete_use_list',
        'wp_estate_custom_icons_infobox',
        'wp_estate_custom_infobox_fields',
        'wp_estate_category_main',
        'wp_estate_category_second',
        'wp_estate_mandatory_page_fields',
        'wp_estate_submission_page_fields',
        'wp_estate_item_rental_type',
        'wp_estate_show_slider_price',
        'wp_estate_category_main_dropdown',
        'wp_estate_category_second_dropdown',
        'wp_estate_show_city_drop_submit',
        'wp_estate_autocomplete_use_list',
        'wp_estate_custom_listing_fields',
        'wp_estate_initial_radius',
        'wp_estate_min_geo_radius',
        'wp_estate_max_geo_radius',
        'wp_estate_geo_radius_measure',
        'wp_estate_use_geo_location',
        'wp_estate_property_page_header',
        'wp_estate_use_custom_icon_area',
        'wp_estate_use_custom_icon_font_size',
        'wp_estate_adv_search_label_for_form',
        'wp_estate_search_fields_no_per_row',
        'wp_estate_show_guest_number',
        'wp_estate_date_format',
        'wp_estate_paralax_header',
        'wp_estate_spash_header_type',
        'wp_estate_splash_image',
        'wp_estate_splash_slider_gallery',
        'wp_estate_splash_slider_transition',
        'wp_estate_splash_video_mp4',
        'wp_estate_splash_video_webm',
        'wp_estate_splash_video_ogv',
        'wp_estate_splash_video_cover_img',
        'wp_estate_splash_overlay_image',
        'wp_estate_splash_overlay_color',
        'wp_estate_splash_overlay_opacity',
        'wp_estate_splash_page_title',
        'wp_estate_splash_page_subtitle',
        'wp_estate_splash_page_logo_link',  
        'wp_estate_full_invoice_reminder',       
        'wp_estate_full_invoice_reminder',
        'wp_estate_map_max_pins',
        'wp_estate_logo_header_type',
        'wp_estate_logo_header_align',
        'wp_estate_wide_header',
        'wp_estate_wide_footer',
        'wp_estate_header_height',
        'wp_estate_sticky_header_height',
        'wp_estate_border_bottom_header',
        'wp_estate_sticky_border_bottom_header',
        'wp_estate_border_bottom_header_color',
        'wp_estate_border_bottom_header_sticky_color',
        'wp_estate_menu_font_color',
        'wp_estate_top_menu_hover_font_color',
        'wp_estate_active_menu_font_color',
        'wp_estate_transparent_menu_font_color',
        'wp_estate_transparent_menu_hover_font_color',
        'wp_estate_sticky_menu_font_color',
        'wp_estate_menu_items_color',
        'wp_estate_menu_item_back_color',
        'wp_estate_menu_hover_font_color',
        'wp_estate_top_menu_font_size',
        'wp_estate_menu_item_font_size',
        'wp_estate_top_menu_hover_back_font_color',
        'wp_estate_top_menu_hover_type',
        'wp_estate_logo_image',
        'wp_estate_transparent_logo_image',
        'wp_estate_mobile_logo_image',
        'wp_estate_favicon_image',
        'wp_estate_logo_image_retina',
        'wp_estate_transparent_logo_image_retina',
        'wp_estate_mobile_logo_image_retina',
        'wp_estate_google_analytics_code',
        'wp_estate_general_country',
        'wp_estate_measure_sys',
        'wp_estate_date_lang',
        'wp_estate_show_submit',
        'wp_estate_setup_weekend',
        'wp_estate_enable_user_pass',
        'wp_estate_use_captcha',
        'wp_estate_recaptha_sitekey',
        'wp_estate_recaptha_secretkey',
        'wp_estate_delete_orphan',
        'wp_estate_separate_users',
        'wp_estate_publish_only',
        'wp_estate_wide_status',
        'wp_estate_show_top_bar_user_menu',
        'wp_estate_show_top_bar_user_login',
        'wp_estate_header_type',
        'wp_estate_user_header_type',
        'wp_estate_transparent_menu',
        'wp_estate_transparent_menu_listing',
        'wp_estate_prop_list_slider',
        'wp_estate_logo_margin',
        'wp_estate_global_revolution_slider',
        'wp_estate_global_header',
        'wp_estate_footer_background',
        'wp_estate_repeat_footer_back',
        'wp_estate_prop_no',
        'wp_estate_guest_dropdown_no', 
        'wp_estate_show_empty_city',
        'wp_estate_blog_sidebar',
        'wp_estate_blog_sidebar_name',
        'wp_estate_property_list_type',
        'wp_estate_listing_unit_type',
        'wp_estate_listing_unit_style_half',
        'wp_estate_listing_page_type',
        'wp_estate_property_list_type_adv',
        'wp_estate_use_upload_tax_page',
        'wp_estate_general_font',
        'wp_estate_headings_font_subset',
        'wp_estate_copyright_message',
        'wp_estate_prices_th_separator',
        'wp_estate_currency_symbol',
        'wp_estate_where_currency_symbol',
        'wp_estate_currency_label_main',
        'wp_estate_is_custom_cur',
        'wp_estate_auto_curency',
        'wp_estate_currency_name',
        'wp_estate_currency_label',
        'wp_estate_where_cur',
        'wp_estate_custom_fields',
        'wp_estate_show_no_features',
        'wp_estate_property_adr_text',
        'wp_estate_property_features_text',
        'wp_estate_property_description_text',
        'wp_estate_property_details_text',
        'wp_estate_property_price_text',
        'wp_estate_property_pictures_text',
        'wp_estate_status_list',
        'wp_estate_theme_slider',
        'wp_estate_slider_cycle',
        'wp_estate_company_name',
        'wp_estate_email_adr',
        'wp_estate_telephone_no',
        'wp_estate_mobile_no',
        'wp_estate_fax_ac',
        'wp_estate_skype_ac',
        'wp_estate_co_address',
        'wp_estate_facebook_link',
        'wp_estate_twitter_link',
        'wp_estate_google_link',
        'wp_estate_pinterest_link',
        'wp_estate_linkedin_link',
        'wp_estate_facebook_login',
        'wp_estate_google_login',
        'wp_estate_yahoo_login',
        'wp_estate_social_register_on',
        'wp_estate_readsys',
        'wp_estate_general_latitude',
        'wp_estate_general_longitude',
        'wp_estate_default_map_zoom',
        'wp_estate_ondemandmap',
        'wp_estate_pin_cluster',
        'wp_estate_zoom_cluster',
        'wp_estate_hq_latitude',
        'wp_estate_hq_longitude',
        'wp_estate_geolocation_radius',
        'wp_estate_min_height',
        'wp_estate_max_height',
        'wp_estate_keep_min',
        'wp_estate_map_style',
        'wp_estate_color_scheme',
        'wp_estate_on_child_theme',
        'wp_estate_main_color',
        'wp_estate_background_color',
        'wp_estate_content_back_color',
        'wp_estate_breadcrumbs_font_color',
        'wp_estate_font_color',
        'wp_estate_link_color',
        'wp_estate_headings_color',
        'wp_estate_footer_back_color',
        'wp_estate_footer_font_color',
        'wp_estate_footer_copy_color',
        'wp_estate_sidebar_widget_color',
        'wp_estate_sidebar_heading_boxed_color',
        'wp_estate_sidebar_heading_color',
        'wp_estate_sidebar2_font_color',
        'wp_estate_header_color',
        'wp_estate_menu_font_color',
        'wp_estate_menu_hover_back_color',
        'wp_estate_menu_hover_font_color',
        'wp_estate_top_bar_back',
        'wp_estate_top_bar_font',
        'wp_estate_box_content_back_color',
        'wp_estate_box_content_border_color',
        'wp_estate_hover_button_color',
        'wp_estate_custom_css',
        'wp_estate_adv_search_type',
        'wp_estate_show_adv_search_general',
        'wp_estate_wpestate_autocomplete',
        'wp_estate_show_adv_search_slider',
        'wp_estate_show_slider_min_price',
        'wp_estate_show_slider_max_price',
        'wp_estate_feature_list',
        'wp_estate_paid_submission',
        'wp_estate_enable_paypal',
        'wp_estate_enable_stripe',
        'wp_estate_admin_submission',
        'wp_estate_price_submission',
        'wp_estate_price_featured_submission',
        'wp_estate_submission_curency',
        'wp_estate_prop_image_number',
        'wp_estate_enable_direct_pay',
        'wp_estate_submission_curency_custom',
        'wp_estate_free_mem_list',
        'wp_estate_free_feat_list',
        'wp_estate_book_down',
        'wp_estate_book_down_fixed_fee',
        'wp_estate_free_feat_list_expiration',
        'wp_estate_new_user',                 
        'wp_estate_admin_new_user',         
        'wp_estate_password_reset_request',   
        'wp_estate_password_reseted',          
        'wp_estate_purchase_activated',       
        'wp_estate_approved_listing',       
        'wp_estate_admin_expired_listing',   
        'wp_estate_paid_submissions',         
        'wp_estate_featured_submission',      
        'wp_estate_account_downgraded',      
        'wp_estate_membership_cancelled',      
        'wp_estate_free_listing_expired',     
        'wp_estate_new_listing_submission',    
        'wp_estate_recurring_payment',       
        'wp_estate_membership_activated',    
        'wp_estate_agent_update_profile',    
        'wp_estate_bookingconfirmeduser',     
        'wp_estate_bookingconfirmed',         
        'wp_estate_bookingconfirmed_nodeposit',
        'wp_estate_inbox',                    
        'wp_estate_newbook',                 
        'wp_estate_mynewbook',                
        'wp_estate_newinvoice',              
        'wp_estate_deletebooking',            
        'wp_estate_deletebookinguser',       
        'wp_estate_deletebookingconfirmed',    
        'wp_estate_new_wire_transfer',        
        'wp_estate_admin_new_wire_transfer',  
        'wp_estate_subject_new_user',                 
        'wp_estate_subject_admin_new_user',         
        'wp_estate_subject_password_reset_request',   
        'wp_estate_subject_password_reseted',          
        'wp_estate_subject_purchase_activated',       
        'wp_estate_subject_approved_listing',       
        'wp_estate_subject_admin_expired_listing',   
        'wp_estate_subject_paid_submissions',         
        'wp_estate_subject_featured_submission',      
        'wp_estate_subject_account_downgraded',      
        'wp_estate_subject_membership_cancelled',      
        'wp_estate_subject_free_listing_expired',     
        'wp_estate_subject_new_listing_submission',    
        'wp_estate_subject_recurring_payment',       
        'wp_estate_subject_membership_activated',    
        'wp_estate_subject_agent_update_profile',    
        'wp_estate_subject_bookingconfirmeduser',     
        'wp_estate_subject_bookingconfirmed',         
        'wp_estate_subject_bookingconfirmed_nodeposit',
        'wp_estate_subject_inbox',                    
        'wp_estate_subject_newbook',                 
        'wp_estate_subject_mynewbook',                
        'wp_estate_subject_newinvoice',              
        'wp_estate_subject_deletebooking',            
        'wp_estate_subject_deletebookinguser',       
        'wp_estate_subject_deletebookingconfirmed',    
        'wp_estate_subject_new_wire_transfer',        
        'wp_estate_subject_admin_new_wire_transfer',  
        'wp_estate_advanced_exteded',
        'wp_estate_service_fee_fixed_fee',
        'wp_estate_service_fee',
        'wp_estate_show_top_bar_mobile_menu',
        'wp_estate_userpin',
    );
    
    
    $images_array=array(
        'wp_estate_favicon_image',
        'wp_estate_logo_image',
        'wp_estate_transparent_logo_image',
        'wp_estate_mobile_logo_image',
        'wp_estate_logo_image_retina',
        'wp_estate_transparent_logo_image_retina',
        'wp_estate_mobile_logo_image_retina',
        'wp_estate_global_header',
        'wp_estate_footer_background',
        'wp_estate_splash_image',
        'wp_estate_splash_overlay_image',
        'wp_estate_splash_video_cover_img',
        'wp_estate_splash_video_ogv',
        'wp_estate_splash_video_webm',
        'wp_estate_splash_video_mp4',
        'wp_estate_userpin'
        );
    
    $color_array=array(
  
        'wp_estate_border_bottom_header_color',
        'wp_estate_splash_overlay_color',
        'wp_estate_main_color',
        'wp_estate_background_color',
        'wp_estate_breadcrumbs_font_color',
        'wp_estate_font_color',
        'wp_estate_link_color',
        'wp_estate_headings_color',
        'wp_estate_footer_back_color',
        'wp_estate_footer_font_color',
        'wp_estate_footer_copy_color',
        'wp_estate_sidebar_widget_color',
        'wp_estate_sidebar_heading_color',
        'wp_estate_sidebar2_font_color',
        'wp_estate_header_color',
        'wp_estate_top_bar_back',
        'wp_estate_top_bar_font',
        'wp_estate_box_content_back_color',
        'wp_estate_box_content_border_color',
        'wp_estate_hover_button_color',
        'wp_estate_menu_font_color',
        'wp_estate_top_menu_hover_font_color',
        'wp_estate_active_menu_font_color',
        'wp_estate_transparent_menu_font_color',
        'wp_estate_transparent_menu_hover_font_color',
        'wp_estate_sticky_menu_font_color',
        'wp_estate_menu_items_color',
        'wp_estate_menu_item_back_color',
        'wp_estate_menu_hover_font_color',
        'wp_estate_adv_back_color',
        'wp_estate_adv_search_back_button',
        'wp_estate_adv_search_back_hover_button',
        'wp_estate_top_menu_hover_back_font_color'
    );
    
    $mandatory_fields           =   ( get_option('wp_estate_mandatory_page_fields','') );
    Redux::setOption('wprentals_admin','wp_estate_mandatory_page_fields', $mandatory_fields);
    
    foreach ($all_options as $option){
        $option_value = get_option( $option, '');
        

       
            
        if($option=='wp_estate_custom_fields'){
            $option_value = wpestate_convert_redux_wp_estate_custom_fields();
            Redux::setOption('wprentals_admin','wpestate_custom_fields_list', $option_value);
        }else if($option=='wp_estate_multi_curr'){
            $option_value = wpestate_convert_redux_wp_estate_multi_curr();
            Redux::setOption('wprentals_admin','wpestate_currency', $option_value);
        }else if($option=='wp_estate_property_page_header'){
            $option_value = wpestate_convert_redux_wp_estate_property_page_header();
            Redux::setOption('wprentals_admin','wp_estate_property_page_header', $option_value);
        }else if($option=='wp_estate_custom_listing_fields'){
            $option_value = wpestate_convert_redux_wp_estate_custom_listing_fields();
            Redux::setOption('wprentals_admin','wp_estate_custom_listing_fields', $option_value);
        }else if($option=='wp_estate_custom_infobox_fields'){
            $option_value = wpestate_convert_redux_wp_estate_custom_infobox_fields();
            Redux::setOption('wprentals_admin','wp_estate_custom_infobox_fields', $option_value);
            
        }else if(in_array($option, $images_array)){
            $option_value   =   get_option( $option, ''); 
            $option_array   =   array('url'=>$option_value);
            Redux::setOption('wprentals_admin',$option, $option_array);
        }else if(in_array($option, $color_array)){
            $option_value=  get_option( $option, '');
            if($option_value!=''){
                $option_value   =   '#'.get_option( $option, ''); 
            }
            Redux::setOption('wprentals_admin',$option, $option_value);
        }else{
            Redux::setOption('wprentals_admin',$option, $option_value);
        }
      
    }
    
    
    
    
    $adv_search_what    = get_option('wp_estate_adv_search_what','');
    $adv_search_how     = get_option('wp_estate_adv_search_how','');
    $adv_search_label   = get_option('wp_estate_adv_search_label','');
    $adv_search_icon    = get_option('wp_estate_search_field_label','');
    
    $wpestate_set_search_array=array();
    $wpestate_set_search_array['adv_search_what']=$adv_search_what;
    $wpestate_set_search_array['adv_search_how']=$adv_search_how;
    $wpestate_set_search_array['adv_search_label']=$adv_search_label;
    $wpestate_set_search_array['search_field_label']=$adv_search_icon;
    
    Redux::setOption('wprentals_admin','wpestate_set_search', $wpestate_set_search_array);
    
    
    

    
    
    /// pins management 
    $taxonomy = 'property_action_category';
    $tax_terms = get_terms($taxonomy,'hide_empty=0');

    if(is_array($tax_terms)){
        foreach ($tax_terms as $tax_term) { 

            $name                    =  sanitize_key( wpestate_limit64('wp_estate_'.$tax_term->slug) );
            $limit54                 =  sanitize_key( wpestate_limit54($tax_term->slug));
            $option_value            =  get_option( $name, '');
            $option_array            =  array('url'=>$option_value);
            Redux::setOption('wprentals_admin',$name, $option_array);
        }   
    }
    
    
    $taxonomy_cat = 'property_category';
    $categories = get_terms($taxonomy_cat,'hide_empty=0');
    if(is_array($categories)){
        foreach ($categories as $categ) {  
            $name                           =   sanitize_key ( wpestate_limit64('wp_estate_'.$categ->slug) );
            $limit54                        =   sanitize_key(wpestate_limit54($categ->slug));
            $option_value                   =   get_option( $name, '');
            $option_array                   =   array('url'=>$option_value);
            Redux::setOption('wprentals_admin',$name, $option_array);

        }
    }
    if(is_array($tax_terms)){
        foreach ($tax_terms as $tax_term) {
            if(is_array($categories)){
                foreach ($categories as $categ) {
                    $limit54                    =   sanitize_key ( wpestate_limit27($categ->slug)).sanitize_key(wpestate_limit27($tax_term->slug) );
                    $name                       =   'wp_estate_'.$limit54;
                    $option_value            =  get_option( $name, '');
                    $option_array            =  array('url'=>$option_value);
                    Redux::setOption('wprentals_admin',$name, $option_array);
               }
            }

        }
    }
    
    
    
    
    
    
    
    
}


function wprentals_corect_item_name($value){
    $value= str_replace('_', '-', $value);
    return $value;
}


function wpestate_reverse_convert_redux_wpestate_convert_redux_wp_estate_custom_infobox_fields(){
    global $wprentals_admin;
    $final_array = array();
    if(isset($wprentals_admin['wp_estate_custom_infobox_fields']['infobox_field_icon'])){
        foreach( $wprentals_admin['wp_estate_custom_infobox_fields']['infobox_field_icon']  as $key=>$value){
            $temp_array=array();
            $temp_array[0]= $wprentals_admin['wp_estate_custom_infobox_fields']['infobox_field_icon'][$key];
            $temp_array[1]= $wprentals_admin['wp_estate_custom_infobox_fields']['unit_field_value'][$key];



            $final_array[]=$temp_array;
        }
    }
    return $final_array;
}

function wpestate_convert_redux_wp_estate_custom_infobox_fields(){
    $final=array();
    $units_fields             = get_option( 'wp_estate_custom_infobox_fields', ''); 
    $infobox_field_icon   =   array();
    $unit_field_value  =   array();
   
    if(is_array($units_fields)){
        foreach($units_fields as $field){
            $infobox_field_icon[]=$field[0];
            $unit_field_value[]=$field[1];
        }
    }
    
    $final['infobox_field_icon']=$infobox_field_icon;
    $final['unit_field_value']=$unit_field_value;
    return $final;
}



function wpestate_convert_redux_wp_estate_custom_listing_fields(){
    $final=array();
    $units_fields             = get_option( 'wp_estate_custom_listing_fields', ''); 
    $unit_field_name   =   array();
    $unit_field_label  =   array();
    $unit_field_value  =   array();
   
    if(isset($units_fields) && is_array($units_fields)){
        foreach($units_fields as $field){

            $unit_field_name[]=$field[0];
            $unit_field_label[]=$field[1];
            $unit_field_value[]=$field[2];
        }
    }
    
    $final['unit_field_name']=$unit_field_name;
    $final['unit_field_label']=$unit_field_label;
    $final['unit_field_value']=$unit_field_value;
 
    return $final;
}

function wpestate_reverse_convert_redux_wp_estate_custom_listing_fields(){
    global $wprentals_admin;
    $final_array = array();
    if(isset($wprentals_admin['wp_estate_custom_listing_fields']['unit_field_name'] )){
        foreach( $wprentals_admin['wp_estate_custom_listing_fields']['unit_field_name']  as $key=>$value){
            $temp_array=array();
            $temp_array[0]= $wprentals_admin['wp_estate_custom_listing_fields']['unit_field_name'][$key];
            $temp_array[1]= $wprentals_admin['wp_estate_custom_listing_fields']['unit_field_label'][$key];
            $temp_array[2]= $wprentals_admin['wp_estate_custom_listing_fields']['unit_field_value'][$key];


            $final_array[]=$temp_array;
        }
    }
    return $final_array;
}




function wpestate_convert_redux_wp_estate_property_page_header(){
    $final=array();
    $rentals_fields             = get_option( 'wp_estate_property_page_header', ''); 
    $property_unit_field_name   =   array();
    $property_unit_field_image  =   array();
    $property_unit_field_label  =   array();
    $unit_field_value           =   array();
    
    if(is_array($rentals_fields)){
        foreach($rentals_fields as $field){

            $property_unit_field_name[]=$field[0];
            $property_unit_field_image[]=$field[3];
            $property_unit_field_label[]=$field[1];
            $unit_field_value[]=$field[2];
        }
    }
    
    $final['property_unit_field_name']=$property_unit_field_name;
    $final['property_unit_field_image']=$property_unit_field_image;
    $final['property_unit_field_label']=$property_unit_field_label;
    $final['unit_field_value']=$unit_field_value;
    return $final;
}





function wpestate_reverse_convert_redux_wp_estate_property_page_header(){
    global $wprentals_admin;
    $final_array = array();
    if( isset($wprentals_admin['wp_estate_property_page_header']['property_unit_field_name'] )){
        foreach( $wprentals_admin['wp_estate_property_page_header']['property_unit_field_name']  as $key=>$value){
            $temp_array=array();
            $temp_array[0]= $wprentals_admin['wp_estate_property_page_header']['property_unit_field_name'][$key];
            $temp_array[1]= $wprentals_admin['wp_estate_property_page_header']['property_unit_field_label'][$key];
            $temp_array[2]= $wprentals_admin['wp_estate_property_page_header']['unit_field_value'][$key];
            $temp_array[3]= $wprentals_admin['wp_estate_property_page_header']['property_unit_field_image'][$key];

            $final_array[]=$temp_array;
        }
    }
    return $final_array;
}

function wpestate_convert_redux_wp_estate_multi_curr(){
    $custom_fields = get_option( 'wp_estate_multi_curr', true);  
    $cur_code=array();
    $cur_label=array();
    $cur_value=array();
    $cur_positin=array();
    $redux_currency=array();
    
    if(is_array($custom_fields)){
        foreach($custom_fields as $field){
            $cur_code[]=$field[0];
            $cur_label[]=$field[1];
            $cur_value[]=$field[2];
            $cur_positin[]=$field[3];
        }
    }
    
    $redux_currency['add_curr_name']=$cur_code;
    $redux_currency['add_curr_label']=$cur_label;
    $redux_currency['add_curr_value']=$cur_value;  
    $redux_currency['add_curr_order']=$cur_positin;
   
    return $redux_currency;
}




function wpestate_reverse_convert_redux_wp_estate_multi_curr(){
    global $wprentals_admin;
    $final_array = array();
    if(isset($wprentals_admin['wpestate_currency']['add_curr_name'])){
        foreach ( $wprentals_admin['wpestate_currency']['add_curr_name'] as $key=>$value ){
            $temp_array=array();
            $temp_array[0]= $wprentals_admin['wpestate_currency']['add_curr_name'][$key];
            $temp_array[1]= $wprentals_admin['wpestate_currency']['add_curr_label'][$key];
            $temp_array[2]= $wprentals_admin['wpestate_currency']['add_curr_value'][$key];
            $temp_array[3]= $wprentals_admin['wpestate_currency']['add_curr_order'][$key];

            $final_array[]=$temp_array;
        }
    }
    return $final_array;


}





function wpestate_convert_redux_wp_estate_custom_fields(){
    $custom_fields = get_option( 'wp_estate_custom_fields', true);  
    $add_field_name=array();
    $add_field_label=array();
    $add_field_order=array();
    $add_field_type=array();
    $add_dropdown_order=array();
      
    $redux_custom_fields=array();
    if(is_array($custom_fields)){
        foreach($custom_fields as $key=>$field){
            $add_field_name[]=$field[0];
            $add_field_label[]=$field[1];
            $add_field_type[]=$field[2];
            $add_field_order[]=$field[3];
            if(isset($field[4])){
            $add_dropdown_order[]=$field[4];
            }
        }
    }
    $redux_custom_fields['add_field_name']=$add_field_name;
    $redux_custom_fields['add_field_label']=$add_field_label;
    $redux_custom_fields['add_field_order']=$add_field_order;  
    $redux_custom_fields['add_field_type']=$add_field_type;
    $redux_custom_fields['add_dropdown_order']=$add_dropdown_order;
    update_option( 'wpestate_custom_fields_list', $redux_custom_fields);  
    return $redux_custom_fields;   
    
}

function wpestate_reverse_convert_redux_wp_estate_custom_fields(){
    global $wprentals_admin;
    $final_array=array();
   
    if(isset($wprentals_admin['wpestate_custom_fields_list']['add_field_name'])){
        foreach( $wprentals_admin['wpestate_custom_fields_list']['add_field_name'] as $key=>$value){
            $temp_array=array();
            $temp_array[0]= $wprentals_admin['wpestate_custom_fields_list']['add_field_name'][$key];
            $temp_array[1]= $wprentals_admin['wpestate_custom_fields_list']['add_field_label'][$key];
            $temp_array[3]= $wprentals_admin['wpestate_custom_fields_list']['add_field_order'][$key];
            $temp_array[2]= $wprentals_admin['wpestate_custom_fields_list']['add_field_type'][$key];
            if( isset(  $wprentals_admin['wpestate_custom_fields_list']['add_dropdown_order'][$key] ) ){
                $temp_array[4]= $wprentals_admin['wpestate_custom_fields_list']['add_dropdown_order'][$key];
            }
            $final_array[]=$temp_array;
        }
    }
    return $final_array;
}













///////////////////////////////////////////////////////////////////////////////////////////
/////// Theme Setup
///////////////////////////////////////////////////////////////////////////////////////////



//add_action( 'after_setup_theme', 'wp_estate_setup',99 );
if( !function_exists('wp_estate_setup') ):
function wp_estate_setup_older() {  
    global $pagenow;
   

    if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
     
    
         ////////////////////  insert sales and rental categories 
        $actions = array(   'Entire home',
                            'Private room',
                            'Shared room'
                        );

        foreach ($actions as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key)
            );
        

            if(!term_exists($key, 'property_action_category', $my_cat) ){
                $return =  wp_insert_term($key, 'property_action_category',$my_cat);
            }
        }

        ////////////////////  insert listings type categories 
        $actions = array(   'Apartment', 
                            'B & B', 
                            'Cabin', 
                            'Condos',
                            'Dorm',
                            'House',
                            'Condos',
                            'Villa',
                        );

        foreach ($actions as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key)
            );
        
            if(!term_exists($key, 'property_category') ){
                wp_insert_term($key, 'property_category');
            }
        }  
        
        
        
        $default_feature_list=array( 
                'Kitchen','Internet','Smoking Allowed','TV','Wheelchair Accessible','Elevator in Building','Indoor Fireplace','Heating',
            'Essentials','Doorman','Pool','Washer','Hot Tub','Dryer','Gym','Free Parking on Premises','Wireless Internet','Pets Allowed','Family/Kid Friendly','Suitable for Events',
            'Non Smoking','Phone (booth/lines)','Projector(s)','Bar / Restaurant','Air Conditioner','Scanner / Printer','Fax');
      
        
        foreach ($default_feature_list as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key)
            );
        
            if(!term_exists($key, 'property_features') ){
                wp_insert_term($key, 'property_features');
            }
        }  
        
        
        
        
        
        $page_check = get_page_by_title('Advanced Search');
        if (!isset($page_check->ID)) {
            $my_post = array(
                'post_title' => 'Advanced Search',
                'post_type' => 'page',
                'post_status' => 'publish',
            );
            $new_id = wp_insert_post($my_post);
            update_post_meta($new_id, '_wp_page_template', 'advanced_search_results.php');
        }
        
      
    }// end if activated
      
      
        add_option('wp_estate_show_top_bar_user_login','yes');
        
        add_option('wp_estate_show_top_bar_user_menu','no');
        add_option('wp_estate_show_adv_search_general','yes');
       
        add_option('wp_estate_currency_symbol', '$');
        add_option('wp_estate_where_currency_symbol', 'before');
        add_option('wp_estate_measure_sys','ft');
        add_option('wp_estate_facebook_login', 'no');
        add_option('wp_estate_google_login', 'no');
        add_option('wp_estate_yahoo_login', 'no');
        add_option('wp_estate_social_register_on','no');
        add_option('wp_estate_wide_status', 1);
        add_option('wp_estate_header_type', 4); 
        add_option('wp_estate_user_header_type', 0); 
        add_option('wp_estate_prop_no', '12');

        add_option('wp_estate_show_empty_city', 'no');
        add_option('wp_estate_blog_sidebar', 'right');
        add_option('wp_estate_blog_sidebar_name', 'primary-widget-area');
   
        add_option('wp_estate_general_latitude', '40.781711');
        add_option('wp_estate_general_longitude', '-73.955927');
        add_option('wp_estate_default_map_zoom', '15');
        add_option('wp_estate_cache', 'no');
        add_option('wp_estate_ondemandmap', 'no');
        add_option('wp_estate_show_adv_search_map_close', 'yes');
        add_option('wp_estate_pin_cluster', 'yes');
        add_option('wp_estate_zoom_cluster', 10);
        add_option('wp_estate_hq_latitude', '40.781711');
        add_option('wp_estate_hq_longitude', '-73.955927');
        add_option('wp_estate_geolocation_radius', 1000);
        add_option('wp_estate_min_height', 550);
        add_option('wp_estate_max_height', 650);
        add_option('wp_estate_keep_min', 'no');
        add_option('wp_estate_paid_submission', 'no');
        add_option('wp_estate_admin_submission', 'yes');
        add_option('wp_estate_price_submission', 0);
        add_option('wp_estate_price_featured_submission', 0);
        add_option('wp_estate_submission_curency', 'USD');
        add_option('wp_estate_paypal_api', 'sandbox');     
        add_option('wp_estate_free_mem_list', 0);
        add_option('wp_estate_free_feat_list', 0);
        add_option('show_adv_search_slider','yes');
        add_option('wp_estate_delete_orphan','no');
        $custom_fields=array(
                    array('Check-in hour','Check-in hour','short text',1),
                    array('Check-Out hour','Check-Out hour','short text',2),
                    array('Late Check-in','Late Check-in','short text',3),
                    array('Optional services','Optional services','short text',4),
                    array('Outdoor facilities','Outdoor facilities','short text',5),
                    array('Extra People','Extra People','short text',6),
                    array('Cancellation','Cancellation','short text',7),
                    );
        add_option( 'wp_estate_custom_fields', $custom_fields); 
        
        add_option('wp_estate_custom_advanced_search', 'no');
        add_option('wp_estate_adv_search_type', 1);
        add_option('wp_estate_show_adv_search', 'yes');
        add_option('wp_estate_show_adv_search_map_close', 'yes');
        add_option('wp_estate_cron_run', time());
       
        add_option('wp_estate_show_no_features', 'yes');
        add_option('wp_estate_property_features_text', 'Property Features');
        add_option('wp_estate_property_description_text', 'Property Description');
        add_option('wp_estate_property_details_text',  'Property Details ');
        $default_status_list='verified';
        add_option('wp_estate_status_list', $default_status_list);
        add_option('wp_estate_slider_cycle', 0); 
        add_option('wp_estate_show_save_search', 'no'); 
        add_option('wp_estate_search_alert',1);
        
        
        // colors option
        add_option('wp_estate_color_scheme', 'no');
   
        add_option('wp_estate_show_g_search', 'no');
        add_option('wp_estate_show_adv_search_extended', 'yes');
        add_option('wp_estate_readsys', 'no');
        add_option('wp_estate_enable_stripe','no');    
        add_option('wp_estate_enable_paypal','no');    
        add_option('wp_estate_enable_direct_pay','no'); 
        add_option('wp_estate_logo_margin',27);
        add_option('wp_estate_free_feat_list_expiration', 0);
        add_option('wp_estate_transparent_menu', 'no');
        add_option('wp_estate_transparent_menu_listing', 'no');
        add_option('wp_estate_date_lang','en-GB');
        
        add_option('wp_estate_show_slider_min_price',0);
        add_option('wp_estate_show_slider_max_price',2500);
        
        
        add_option('wp_estate_listing_unit_type',2);
        add_option('wp_estate_listing_page_type',1);
        add_option('wp_estate_adv_search_type','newtype');
        add_option('wp_estate_listing_unit_style_half',1);
        add_option('wp_estate_auto_curency','no');
        add_option('wp_estate_prop_list_slider','no');
        
        add_option('wp_estate_separate_users','no');
        add_option('wp_estate_publish_only','');
        add_option('wp_estate_show_adv_search_general','yes');
        
        add_option('wp_estate_show_submit','yes');
        add_option('wp_estate_setup_weekend',0);
         
        
        add_option('wp_estate_use_captcha_status','no');
        add_option('wp_estate_enable_user_pass','no');
        add_option('wp_estate_enable_direct_pay','no');   
        //  
        add_option('wp_estate_logo_header_type','type1');
        add_option('wp_estate_logo_header_align','left');
        add_option('wp_estate_wide_header','no');
        add_option('wp_estate_logo_header_select','type2'); 
        add_option('wp_estate_logo_margin',0);
        add_option('wp_estate_wide_footer','no');
        add_option('wp_estate_map_max_pins',30);
        
        add_option('wp_estate_guest_dropdown_no','15');
        add_option('wp_estate_month_no_show','12');
        add_option('wp_estate_theme_slider_type','type1');   
        add_option('wp_estate_date_format',0);
        
        add_option ('wp_estate_item_rental_type',0);
        add_option ('wp_estate_adv_search_fields_no',3);
        add_option ('wp_estate_search_fields_no_per_row',3);
        add_option ('wp_estate_use_custom_icon_area','no');
        
        add_option ('wp_estate_use_custom_icon_area','no');
        add_option ('wp_estate_use_price_pins_full_price','no');
        add_option ('wp_estate_show_adv_search_extended','yes');
        
        
        
        $adv_search_what_classic_half    =   array('Location','check_in','check_out','guest_no','property_rooms','property_category','property_action_category','property_bedrooms','property_bathrooms','property_price');
        $adv_search_how_classic_half     =   array('like','like','like','greater','greater','like','like','greater','greater','between');
            
        add_option('wp_estate_adv_search_what_half',$adv_search_what_classic_half);
        add_option('wp_estate_adv_search_how_half',$adv_search_how_classic_half);
        
        $adv_search_what    =   array('Location','check_in','check_out','guest_no');
        $adv_search_how     =   array('like','like','like','greater');
            
        add_option('wp_estate_adv_search_what_classic',$adv_search_what);
        add_option('wp_estate_adv_search_how_classic',$adv_search_how);
        add_option('wp_estate_show_menu_dashboard','yes');   
        add_option('wp_estate_show_city_drop_submit','no');  
        add_option('wp_estate_show_guest_number','yes');
            
        add_option('wp_estate_mandatory_page_fields','');
        $all_submission_fields_default=Array ( 
            'prop_category_submit',
            'prop_action_category_submit', 
            'property_city_front', 
            'property_area_front', 
            'property_description', 
            'property_price', 
            'property_taxes', 
            'property_price_per_week', 
            'property_price_per_month', 
            'price_per_weekeend', 
            'cleaning_fee', 
            'city_fee', 
            'min_days_booking', 
            'security_deposit', 
            'early_bird_percent', 
            'extra_price_per_guest', 
            'checkin_change_over', 
            'checkin_checkout_change_over', 
            'extra_options', 
            'custom_prices', 
            'attachid', 
            'embed_video_id', 
            'embed_video_type', 
            'property_size', 
            'property_rooms', 
            'property_bedrooms', 
            'property_bathrooms', 
            'property_address', 
            'property_zip', 
            'property_county', 
            'property_state', 
            'property_map', 
            'property_latitude', 
            'property_longitude', 
            'google_camera_angle', 
            'avalability_calendar', 
            'check-in-hour', 
            'check-out-hour', 
            'late-check-in', 
            'optional-services', 
            'outdoor-facilities', 
            'extra-people', 
            'cancellation', 
            'kitchen', 
            'internet', 
            'smoking_allowed', 
            'tv', 
            'wheelchair_accessible', 
            'elevator_in_building', 
            'indoor_fireplace', 
            'heating', 
            'essentials', 
            'doorman', 
            'pool', 
            'washer', 
            'hot_tub', 
            'dryer', 
            'gym', 
            'free_parking_on_premises', 
            'wireless_internet', 
            'pets_allowed', 
            'family-kid_friendly', 
            'suitable_for_events', 
            'non_smoking', 
            'phone_booth-lines', 
            'projectors', 
            'bar_-_restaurant', 
            'air_conditioner', 
            'scanner_-_printer', 
            'fax' );
        add_option('wp_estate_submission_page_fields',$all_submission_fields_default);
        
        add_option('wp_estate_use_float_search_form','yes');
        add_option('wp_estate_float_form_top','20%');
        add_option('wp_estate_float_form_top_tax','15%');
        
        add_option('wp_estate_search_on_start','no');
        
        add_option('wp_estate_booking_type',1);
        // defaul emails 
        

        // agent_update_profile
            
        $to_save=__('Profile Update','wprentals');
        add_option ('wp_estate_subject_agent_update_profile',$to_save);
        
        $to_save=__('A user updated his profile on %website_url.
Username: %user_login','wprentals');
        add_option ('wp_estate_agent_update_profile',$to_save);

		// User ID Verification
		$to_save = __( 'New User ID verification', 'wprentals' );
		add_option( 'wp_estate_subject_new_user_id_verification', $to_save );

		$to_save = __( 'A user added his User ID verification image on %website_url.
Username: %user_login.
You can check the verification status here: %user_profile_url', 'wprentals' );
		add_option( 'wp_estate_new_user_id_verification', $to_save );
        
         // password_reset_request
        $to_save=__('Password Reset Request','wprentals');
        add_option ('wp_estate_subject_password_reset_request',$to_save);
        
        $to_save=__('Someone requested that the password be reset for the following account:
%website_url 
Username: %forgot_username .
If this was a mistake, just ignore this email and nothing will happen. To reset your password, visit the following address:%reset_link,
Thank You!','wprentals');
        add_option ('wp_estate_password_reset_request',$to_save);
        
        
         // password_reseted
        $to_save=__('Your Password was Reset','wprentals');
        add_option ('wp_estate_subject_password_reseted',$to_save);
        
        $to_save=__('Your new password for the account at: %website_url: 
Username:%user_login, 
Password:%user_pass
You can now login with your new password at: %website_url','wprentals');
        add_option ('wp_estate_password_reseted',$to_save);
  
        // purchase_activated
        $to_save=__('Your purchase was activated','wprentals');
        add_option ('wp_estate_subject_purchase_activated',$to_save);
        
        $to_save=__('Hi there,
Your purchase on  %website_url is activated! You should go check it out.','wprentals');
        add_option ('wp_estate_purchase_activated',$to_save);
          
         // approved_listing
        $to_save=__('Your listing was approved','wprentals');
        add_option ('wp_estate_subject_approved_listing',$to_save);
        
        $to_save=__('Hi there,
Your listing, %property_title was approved on  %website_url ! The listing is: %property_url.
You should go check it out.','wprentals');
        add_option ('wp_estate_approved_listing',$to_save);
        
        
        $to_save=__('New User Registration','wprentals');
        add_option ('wp_estate_subject_admin_new_user',$to_save);
        
        $to_save=__('New user registration on %website_url.
Username: %user_login_register, 
E-mail: %user_email_register','wprentals');
        add_option ('wp_estate_admin_new_user',$to_save);
        
        
        $to_save=__('Your username and password on %website_url','wprentals');
        add_option ('wp_estate_subject_new_user',$to_save);
        
        $to_save=__('Hi there,
Welcome to %website_url ! You can login now using the below credentials:
Username:%user_login_register
Password: %user_pass_register
If you have any problems, please contact me.
Thank you!','wprentals');
        add_option ('wp_estate_new_user',$to_save);
        
        $to_save=__('Expired Listing sent for approval on %website_url','wprentals');
        add_option ('wp_estate_subject_admin_expired_listing',$to_save);
        
        $to_save=__('Hi there,
A user has re-submited a new property on %website_url ! You should go check it out.
This is the property title: %submission_title.','wprentals');
        add_option ('wp_estate_admin_expired_listing',$to_save);
        
        //Paid Submissions  
        $to_save=__('New Paid Submission on %website_url','wprentals');
        add_option ('wp_estate_subject_paid_submissions',$to_save);
        
        $to_save=__('Hi there,
You have a new paid submission on  %website_url ! You should go check it out.','wprentals');
        add_option ('wp_estate_paid_submissions',$to_save);
        
        
        
         //Paid Submissions  
        $to_save=__('New Feature Upgrade on  %website_url','wprentals');
        add_option ('wp_estate_subject_featured_submission',$to_save);
        
        $to_save=__('Hi there,
You have a new featured submission on  %website_url ! You should go check it out.','wprentals');
        add_option ('wp_estate_featured_submission',$to_save);
        
        
        //account_downgraded  
        $to_save=__('Account Downgraded on %website_url','wprentals');
        add_option ('wp_estate_subject_account_downgraded',$to_save);
        
        $to_save=__('Hi there,
You downgraded your subscription on %website_url. Because your listings number was greater than what the actual package offers, we set the status of all your listings to expired. You will need to choose which listings you want live and send them again for approval.
Thank you!','wprentals');
        add_option ('wp_estate_account_downgraded',$to_save);
        
        
        //Membership Cancelled
        $to_save=__('Membership Cancelled on %website_url','wprentals');
        add_option ('wp_estate_subject_membership_cancelled',$to_save);
        
        $to_save=__('Hi there,
Your subscription on %website_url was cancelled because it expired or the recurring payment from the merchant was not processed. All your listings are no longer visible for our visitors but remain in your account.
Thank you.','wprentals');
        add_option ('wp_estate_membership_cancelled',$to_save);
        
         // Membership Activated
        $to_save=__('Membership Activated on %website_url','wprentals');
        add_option ('wp_estate_subject_membership_activated',$to_save);
        
        $to_save=__('Hi there,
Your new membership on %website_url is activated! You should go check it out.','wprentals');
        add_option ('wp_estate_membership_activated',$to_save);


        
        //Free Listing expired
        $to_save=__('Free Listing expired on %website_url','wprentals');
        add_option ('wp_estate_subject_free_listing_expired',$to_save);
        
        $to_save=__('Hi there,
One of your free listings on  %website_url has expired. The listing is %expired_listing_url.
Thank you!','wprentals');
        add_option ('wp_estate_free_listing_expired',$to_save);

        //New Listing Submission
        $to_save=__('New Listing Submission on %website_url','wprentals');
        add_option ('wp_estate_subject_new_listing_submission',$to_save);
        
        $to_save=__('Hi there,
A user has submited a new property on %website_url ! You should go check it out.This is the property title %new_listing_title!','wprentals');
        add_option ('wp_estate_new_listing_submission',$to_save);

        //listing edit
        $to_save=__('Listing Edited on %website_url','wprentals');
        add_option ('wp_estate_subject_listing_edit',$to_save);
        
        $to_save=__('Hi there,
A user has edited one of his listings  on %website_url ! You should go check it out. The property name is : %editing_listing_title!','wprentals');
        add_option ('wp_estate_listing_edit',$to_save);
         

        //recurring_payment
        $to_save=__('Recurring Payment on %website_url','wprentals');
        add_option ('wp_estate_subject_recurring_payment',$to_save);
        
        $to_save=__('Hi there,
We charged your account on %merchant for a subscription on %website_url ! You should go check it out.','wprentals');
        add_option ('wp_estate_recurring_payment',$to_save);
        
        
        
        
        //bookingconfirmeduser
        $to_save=__('Booking Confirmed on %website_url','wprentals');
        add_option ('wp_estate_subject_bookingconfirmeduser',$to_save);
        
        $to_save=__('Hi there,
Your booking made on %website_url was confirmed! You can see all your reservations by logging in your account and visiting My Reservations page.','wprentals');
        add_option ('wp_estate_bookingconfirmeduser',$to_save);
        
        //bookingconfirmed
        $to_save=__('Booking Confirmed on %website_url','wprentals');
        add_option ('wp_estate_subject_bookingconfirmed',$to_save);
        
        $to_save=__('Hi there,
Somebody confirmed a booking on %website_url! You should go and check it out!Please remember that the confirmation is made based on the payment confirmation of a non-refundable fee of the total invoice cost, processed through %website_url and sent to website administrator. ','wprentals');
        add_option ('wp_estate_bookingconfirmed',$to_save);
        
         //bookingconfirmed_nodeposit
        $to_save=__('Booking Confirmed on %website_url','wprentals');
        add_option ('wp_estate_subject_bookingconfirmed_nodeposit',$to_save);
        
        $to_save=__('Hi there,
You confirmed a booking on %website_url! The booking was confirmed with no deposit!','wprentals');
        add_option ('wp_estate_bookingconfirmed_nodeposit',$to_save);
        
        
        
        //inbox
        $to_save=__('New Message on %website_url.','wprentals');
        add_option ('wp_estate_subject_inbox',$to_save);
        
        $to_save=__('Hi there,
You have a new message on %website_url! You should go and check it out!
The message is:
%content','wprentals');
        add_option ('wp_estate_inbox',$to_save);
        
        
        //newbook
        $to_save=__('New Booking Request on %website_url.','wprentals');
        add_option ('wp_estate_subject_newbook',$to_save);
        
        $to_save=__('Hi there,
You have received a new booking request on %website_url !  Go to your account in Bookings page to see the request, issue the invoice or reject it!
The property is: %booking_property_link','wprentals');
        add_option ('wp_estate_newbook',$to_save);
        
        //mynewbook
        $to_save=__('You booked a period on %website_url.','wprentals');
        add_option ('wp_estate_subject_mynewbook',$to_save);
        
        $to_save=__('Hi there,
You have booked a period for your own listing on %website_url !  The reservation will appear in your account, under My Bookings. 
The property is: %booking_property_link','wprentals');
        add_option ('wp_estate_mynewbook',$to_save);
        
        //newinvoice
        $to_save=__('New Invoice on %website_url.','wprentals');
        add_option ('wp_estate_subject_newinvoice',$to_save);
        
        $to_save=__('Hi there,
An invoice was generated for your booking request on %website_url !  A deposit will be required for booking to be confirmed. For more details check out your account, My Reservations page.','wprentals');
        add_option ('wp_estate_newinvoice',$to_save);
        
        
         //deletebooking
        $to_save=__('Booking Request Rejected on %website_url','wprentals');
        add_option ('wp_estate_subject_deletebooking',$to_save);
        
        $to_save=__('Hi there,
One of your booking requests sent on %website_url was rejected by the owner. The rejected reservation is automatically removed from your account. ','wprentals');
        add_option ('wp_estate_deletebooking',$to_save);
        
         //deletebookinguser
        $to_save=__('Booking Request Cancelled on %website_url','wprentals');
        add_option ('wp_estate_subject_deletebookinguser',$to_save);
        
        $to_save=__('Hi there,
One of the unconfirmed booking requests you received on %website_url  was cancelled! The request is automatically deleted from your account!','wprentals');
        add_option ('wp_estate_deletebookinguser',$to_save);
        
         //deletebookingconfirmed
        $to_save=__('Booking Period Cancelled on %website_url.','wprentals');
        add_option ('wp_estate_subject_deletebookingconfirmed',$to_save);
        
        $to_save=__('Hi there,
One of your confirmed bookings on %website_url  was cancelled by property owner. ','wprentals');
        add_option ('wp_estate_deletebookingconfirmed',$to_save);
        
        
           // new_wire_transfer
        $to_save=__('You ordered a new Wire Transfer','wprentals');
        add_option ('wp_estate_subject_new_wire_transfer',$to_save);
        
        $to_save=__('We received your Wire Transfer payment request on  %website_url !
Please follow the instructions below in order to start submitting properties as soon as possible.
The invoice number is: %invoice_no, Amount: %total_price. 
Instructions:  %payment_details.','wprentals');
        add_option ('wp_estate_new_wire_transfer',$to_save);
        
        $to_save=__('Somebody ordered a new Wire Transfer','wprentals');
        add_option ('wp_estate_subject_admin_new_wire_transfer',$to_save);
        
        $to_save=__('Hi there,
You received a new Wire Transfer payment request on %website_url.
The invoice number is:  %invoice_no,  Amount: %total_price.
Please wait until the payment is made to activate the user purchase.','wprentals');
        add_option ('wp_estate_admin_new_wire_transfer',$to_save);
        
        $to_save=__('Invoice payment reminder','wprentals');
        add_option ('wp_estate_subject_full_invoice_reminder',$to_save);
        
        $to_save=__('Hi there,
We remind you that you need to fully pay the invoice no %invoice_id until  %until_date. This invoice is for booking no %booking_id on property %property_title with the url %property_url.
Thank you.','wprentals');
        add_option ('wp_estate_full_invoice_reminder',$to_save);
}
endif; // end   wp_estate_setup  




?>