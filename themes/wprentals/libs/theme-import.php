<?php

function ocdi_plugin_intro_text( $default_text ) {
    $default_text = '<div class="ocdi__intro-text intro-text_wpestate notice notice-warning "> For speed purposes, demo images are not included in the import. '
            . 'Revolution Sliders are imported separately from demo_content/revolutions_sliders folder If the import doesn’t go through in 1-2 minutes, server limits are affecting the import. '
            . 'Please check the server requirements list <a href="https://help.wprentals.org/article/wordpress-server-requirements-to-install-and-use-wp-rentals/" target="_blank">here</a>.  '
            . 'For our assistance with this process, please contact us through client support <a href="http://support.wpestate.org/" target="_blank">here.</a></div>';

    return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'ocdi_plugin_intro_text' );

function ocdi_import_files() {
    $theme_activated    =   get_option('is_theme_activated','');
    if($theme_activated!=='is_active'){
       
        return;
    }
    return array(
        array(
            'import_file_name'             => 'Main Demo',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/main-demo/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/main-demo/widgets.wie',
        //  'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'wpestate_templates/main-demo/wprentals-export.dat', 
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/main-demo/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/main-demo/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://main.wprentals.org/',
        ),
        
        array(
            'import_file_name'             => 'Madeira Demo',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/madeira/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/madeira/widgets.wie',
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/madeira/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/madeira/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://madeira.wprentals.org/',
        ),
        
        array(
            'import_file_name'             => 'Demo1',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/demo1/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/demo1/widgets.wie',
           // 'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'wpestate_templates/demo1/wprentals-export.dat',  
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/demo1/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/demo1/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://demo1.wprentals.org/',
        ),
        
        array(
            'import_file_name'             => 'Santorini Demo',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/santorini/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/santorini/widgets.wie',
          //  'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'wpestate_templates/santorini/wprentals-export.dat',  
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/santorini/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/santorini/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://santorini.wprentals.org/',
        ),
        array(
            'import_file_name'             => 'Ibiza Demo',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/ibiza/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/ibiza/widgets.wie',
            //'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'wpestate_templates/ibiza/wprentals-export.dat',  
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/ibiza/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/ibiza/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://ibiza.wprentals.org/',
        ),
        
            array(
            'import_file_name'             => 'Rent a Yacht Elementor',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/rentayacht/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/rentayacht/widgets.wie',
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/rentayacht/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/rentayacht/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://rentayacht.wprentals.org/',
        ),
        
         array(
            'import_file_name'             => 'Rent a Boat Bakery',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/rentaboat/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/rentaboat/widgets.wie',
            //'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'wpestate_templates/rentaboat/wprentals-export.dat',  
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/rentaboat/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/rentaboat/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://rentaboat.wprentals.org/',
        ),
          array(
            'import_file_name'             => 'Ski Rentals',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/skirent/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/skirent/widgets.wie',
            //'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'wpestate_templates/skirent/wprentals-export.dat',  
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/skirent/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/skirent/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://skirent.wprentals.org',
        ),
        
       array(
            'import_file_name'             => 'Office Rentals',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/office/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/office/widgets.wie',
            //'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'wpestate_templates/office/wprentals-export.dat',  
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/office/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/office/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://office.wprentals.org',
        ),
    
            array(
            'import_file_name'             => 'Solo Owner Demo',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/solo/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/solo/widgets.wie',
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/solo/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/solo/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://solo.wprentals.org/',
        ),
        
        array(
            'import_file_name'             => 'Cancun Demo',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/cancun/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/cancun/widgets.wie',
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/cancun/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/cancun/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://cancun.wprentals.org/',
        ),
        
        array(
            'import_file_name'             => 'Hawaii Demo',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/hawaii/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/hawaii/widgets.wie',
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/hawaii/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/hawaii/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://hawaii.wprentals.org/',
        ),
        
        array(
            'import_file_name'             => 'Tuscany Demo',  
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'wpestate_templates/tuscany/theme_content.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'wpestate_templates/tuscany/widgets.wie',
            'local_import_redux'           => array(
                array(
                  'file_path'   => trailingslashit( get_template_directory() ) . 'wpestate_templates/tuscany/redux_options.json',
                  'option_name' => 'wprentals_admin',
                ),
            ),
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ) . 'wpestate_templates/tuscany/preview.jpg',
            'import_notice'                => esc_html__( 'Clear theme cache after demo import is complete!', 'wprentals' ),
            'preview_url'                  => 'https://tuscany.wprentals.org/',
        ),
      
  
    );
}

$theme_activated    =   get_option('is_theme_activated','');
if($theme_activated=='is_active'){
    add_filter( 'pt-ocdi/import_files', 'ocdi_import_files' );
}




function ocdi_after_import_setup() {
    
    $main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', array(
        'primary'       => $main_menu->term_id,
        'mobile'        => $main_menu->term_id,
        'footer_menu'   => $footer_menu->term_id,
        )
    );
  

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Homepage' );
  

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
    
    global $wprentals_admin;
  
    update_option('wpestate_custom_fields_list',$wprentals_admin['wpestate_custom_fields_list']);

    
    
    wpestate_delete_cache();

}
add_action( 'pt-ocdi/after_import', 'ocdi_after_import_setup' );

function wpestate_my_export_option_keys( $keys ) {
   
    $export_options = array(
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
        'wp_estate_wpestate_autocomplete_use_list',
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
        'wp_estate_wp_estate_custom_fields',
  
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
        'wp_estate_show_top_bar_user_menu',
        'wp_estate_service_fee_fixed_fee',
        'wp_estate_service_fee',
        'wp_estate_show_top_bar_mobile_menu'
    );
     
    foreach($export_options as $option){
         $keys[]=$option;
    }
   
    return $keys;
}

add_filter( 'cei_export_option_keys', 'wpestate_my_export_option_keys' );



?>