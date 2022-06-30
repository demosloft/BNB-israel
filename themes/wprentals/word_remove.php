<?php

function wpestate_action_welcome_panel() { 
    $logo                       =   esc_html( wprentals_get_option('wp_estate_logo_image', 'url') );
    $theme_activated            =   get_option('is_theme_activated','');
    print' <div class="wpestate_admin_theme_back_wrapper">';
    print '<div class="wpestate_admin_theme_back"></div>';
    print '<div class="wpestate_admin_theme_gradient"></div>';
    print '<img class="img-responsive retina_ready dashboard_widget_logo" src="' . get_template_directory_uri() . '/img/logo_admin_white.png" alt="'.esc_html__('logo','wprentals').'"/>';
    
  
    print'<nav class="wpestate_admin_menu">';
         print'<ul class="menu_admin">';
            print'<li><a target="_blank "href="https://help.wprentals.org/article/how-to-update-the-theme-and-plugins/">'.__('Update the theme & plugins','wprentals').'</a></li>';
            print'<li><a target="_blank "href="https://1.envato.market/c/1252376/275988/4415?u=https%3A%2F%2Fthemeforest.net%2Fitem%2Fwp-rentals-booking-accommodation-wordpress-theme%2F12921802">'.__('Buy new license','wprentals').'</a></li>';
            print'<li><a target="_blank "href="https://help.wprentals.org/article-category/change-log/">'.__('Change log','wprentals').'</a></li>';
            print '<li><a href="#">'.__( 'Get help', 'wprentals' ).'<div alt="f347" class="dashicons dashicons-arrow-down-alt2"></div></a>';
                print'<ul class="wpestate_dropdown_admin">';
                    print '<li><a target="_blank" href="http://support.wpestate.org/">'.__( 'Open ticket', 'wprentals' ).'</a></li>';
                    print '<li><a target="_blank" href="https://help.wprentals.org/">'.__( 'Documentation', 'wprentals' ).'</a></li>';
                    print '<li><a target="_blank" href="https://themeforest.net/downloads">'.__( 'Rate us', 'wprentals' ).'</a></li>';
                print'</ul>';
            print '</li>';
        print '</ul>';
    print'</nav>';
    
    
    
    print'<div class="wpestate_admin_version">'; 
        print '<div class="theme_details_welcome">'.__('Current Version: ','wprentals') . wp_get_theme().'</div>';
        if($theme_activated=='is_active'){
            print'<div alt="f528" class="dashicons dashicons-unlock"></div>';
        }else{
            print'<div alt="f528" class="dashicons dashicons-lock"></div>';
        }
       
    print'</div>';
    
    print'<div class="wpestate_admin_theme_opt">'; 
        print'<div id="start_wprentals_setup" class="wpestate_admin_button">'.__('Start Now','wprentals') .'</div>';
        print'<div class="wpestate_theme_opt wpestate_admin_button"><a href="'.esc_url( home_url('/') ).'wp-admin/admin.php?page='.str_replace(' ','',wp_get_theme()).'">'.__('Site Settings ','wprentals') .'</a></div>';
    print'</div>';
    
    print'<div id="wpestate_start_wrapper">';
    
        print'<button type="button" class="wpestate_admin_button wpestate_start_wrapper_close" ><div alt="f158" class="dashicons dashicons-no"></div></button>';

        print'<div class="wpestate_admin_start_notice" id="wpestate_start">';
            print'<p>'.__('We recommend doing demo import first and then finishing this setup. Adding Demo import AFTER completing this setup changes your settings options to demo options. ','wprentals') .'</p>';
            print'<div class="wpestate_admin_start_notice_wpapper">';
                print'<div class="wpestate_admin_button" id="button_start_notice">'.esc_html__('Continue','wprentals') .'</div>'.esc_html__('OR','wprentals');
                print'<div class="wpestate_admin_button"><a href="'.esc_url( home_url('/') ).'wp-admin/admin.php?page=WpRentals&tab=34">'.__('Import Demo Content','wprentals') .'</a></div>';
            print'</div>';
        print '</div>';
        
         
        print'<div class="wpestate_admin_start_map" id="wpestate_start_map"><h1>'.__( 'Map Settings', 'wprentals' ).'</h1>';
            $cache_array       =   array('yes','no');
            
            print'<p>'.esc_html__('VERY IMPORTANT: For already published properties, switching from Google Places to Algolia Places/OpenStreet Places (or from Algolia/Openstreet to Google Places) may require adding properties City & address again. The 2 systems can have different names for city, area and country and search by location may not work.','wprentals') .'</p>';
                 
            $map_type_array       =    array(
                            2 => 'open street',
                            1  => 'google maps'  );
            $map_type_option ='';
            $map_type                       =   esc_html( wprentals_get_option('wp_estate_kind_of_map','') );
            foreach($map_type_array as $key=>$value){
                    $map_type_option.='<option value="'.$key.'"';
                    if ($map_type==$key){
                            $map_type_option.=' selected="selected" ';
                    }
                    $map_type_option.='>'.$value.'</option>';
            }   
            
            

            print'<div class="estate_start_setup">
              <div class="label_option_start">' . esc_html__('What Map System you want to use?', 'wprentals') . '</div>
              <div class="option_row_explain">' . esc_html__('What Map System you want to use?', 'wprentals') . '</div>    
                  <select id="map_type_option" name="map_type_option">
                      ' . $map_type_option . '
                  </select>            
            </div>';  
            
            
            $places_type_array       =    array( 
                            2 => 'algolia',
                            1  => 'google places' );
            $places_type_option ='';
            $places_type                       =   esc_html( wprentals_get_option('wp_estate_kind_of_places','') );
            

            foreach($places_type_array as $key=>$value){
                    $places_type_option.='<option value="'.$key.'"';
                    if ($places_type==$key){
                            $places_type_option.=' selected="selected" ';
                    }
                    $places_type_option.='>'.$value.'</option>';
            }   
            
                  
            print'<div class="estate_start_setup">
              <div class="label_option_start">' . esc_html__('What Places Api you want to use?', 'wprentals') . '</div>
              <div class="option_row_explain">' . esc_html__('Google Places work only with Google Maps activated.', 'wprentals') . '</div>    
                  <select id="places_type_option" name="places_type_option">
                      ' . $places_type_option . '
                  </select>            
            </div>';
            

            $api_key = esc_html(wprentals_get_option('wp_estate_api_key'));
            print'<div class="estate_start_setup">
                <div class="label_option_start">' . __('Google Maps API KEY', 'wprentals') . '</div>
                <div class="option_row_explain">' . __('The Google Maps JavaScript API v3 REQUIRES an API key to function correctly. Get an APIs Console key and post the code in Theme Options. You can get it from <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">here</a>', 'wprentals') . '</div>    
                    <input  type="text" id="api_key" name="api_key" class="regular-text" value="' . $api_key . '"/>
            </div>';
            
            $mapbox_api_key = esc_html(wprentals_get_option('wp_estate_mapbox_api_key'));
            print'<div class="estate_start_setup">
                <div class="label_option_start">' . __('MapBox API KEY -  used for tile server', 'wprentals') . '</div>
                <div class="option_row_explain">' . __('You can get it from here: https://www.mapbox.com/. If you leave it blank we will use the default openstreet server which can be slow', 'wprentals') . '</div>    
                    <input  type="text" id="mapbox_api_key" name="mapbox_api_key" class="regular-text" value="' . $mapbox_api_key . '"/>
            </div>';
            
            $algolia_app_id = esc_html(wprentals_get_option('wp_estate_algolia_app_id'));
            print'<div class="estate_start_setup">
                <div class="label_option_start">' . __('Algolia App Id', 'wprentals') . '</div>
                <div class="option_row_explain">' . __('You can get it from here:  https://community.algolia.com/places/', 'wprentals') . '</div>    
                    <input  type="text" id="algolia_app_id" name="algolia_app_id" class="regular-text" value="' . $algolia_app_id . '"/>
            </div>';
            
            
            $algolia_app_key = esc_html(wprentals_get_option('wp_estate_algolia_api_key'));
            print'<div class="estate_start_setup">
                <div class="label_option_start">' . __('Algolia API Key', 'wprentals') . '</div>
                <div class="option_row_explain">' . __('You can get it from here:  https://community.algolia.com/places/', 'wprentals') . '</div>    
                    <input  type="text" id="algolia_app_key" name="algolia_app_key" class="regular-text" value="' . $algolia_app_key . '"/>
            </div>';
            


        print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_map_prev" value="' . __('Previous Step', 'wprentals') . '"/>';
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_map_set" value="'.__('Next Step', 'wprentals').'"/>'; 
            $ajax_nonce = wp_create_nonce( "wprentals_map_set_nonce" );
            print'<input type="hidden" id="wprentals_map_set_nonce" value="'.esc_html($ajax_nonce).'" />    ';

        print'</div>';


        print'<div class="wpestate_admin_start_general_settings" id="wpestate_general_settings"><h1>'.__('General Settings', 'wprentals').'</h1>';

            $currency_symbol                =   esc_html( wprentals_get_option('wp_estate_currency_symbol') );
            $where_currency_symbol          =   '';
            $where_currency_symbol_array    =   array('before','after');
            $where_currency_symbol_status   =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol') );
            foreach($where_currency_symbol_array as $value){
                    $where_currency_symbol.='<option value="'.$value.'"';
                    if ($where_currency_symbol_status==$value){
                        $where_currency_symbol.=' selected="selected" ';
                    }
                    $where_currency_symbol.='>'.$value.'</option>';
            } 

            $date_lang_symbol='';
            $date_lang_status= esc_html ( wprentals_get_option('wp_estate_date_lang','') );
            $date_languages=array(  'xx'=> 'default',
                                    'af'=>'Afrikaans',
                                    'ar'=>'Arabic',
                                    'ar-DZ' =>'Algerian',
                                    'az'=>'Azerbaijani',
                                    'be'=>'Belarusian',
                                    'bg'=>'Bulgarian',
                                    'bs'=>'Bosnian',
                                    'ca'=>'Catalan',
                                    'cs'=>'Czech',
                                    'cy-GB'=>'Welsh/UK',
                                    'da'=>'Danish',
                                    'de'=>'German',
                                    'el'=>'Greek',
                                    'en-AU'=>'English/Australia',
                                    'en-GB'=>'English/UK',
                                    'en-NZ'=>'English/New Zealand',
                                    'eo'=>'Esperanto',
                                    'es'=>'Spanish',
                                    'et'=>'Estonian',
                                    'eu'=>'Karrikas-ek',
                                    'fa'=>'Persian',
                                    'fi'=>'Finnish',
                                    'fo'=>'Faroese',
                                    'fr'=>'French',
                                    'fr-CA'=>'Canadian-French',
                                    'fr-CH'=>'Swiss-French',
                                    'gl'=>'Galician',
                                    'he'=>'Hebrew',
                                    'hi'=>'Hindi',
                                    'hr'=>'Croatian',
                                    'hu'=>'Hungarian',
                                    'hy'=>'Armenian',
                                    'id'=>'Indonesian',
                                    'ic'=>'Icelandic',
                                    'it'=>'Italian',
                                    'it-CH'=>'Italian-CH',
                                    'ja'=>'Japanese',
                                    'ka'=>'Georgian',
                                    'kk'=>'Kazakh',
                                    'km'=>'Khmer',
                                    'ko'=>'Korean',
                                    'ky'=>'Kyrgyz',
                                    'lb'=>'Luxembourgish',
                                    'lt'=>'Lithuanian',
                                    'lv'=>'Latvian',
                                    'mk'=>'Macedonian',
                                    'ml'=>'Malayalam',
                                    'ms'=>'Malaysian',
                                    'nb'=>'Norwegian',
                                    'nl'=>'Dutch',
                                    'nl-BE'=>'Dutch-Belgium',
                                    'nn'=>'Norwegian-Nynorsk',
                                    'no'=>'Norwegian',
                                    'pl'=>'Polish',
                                    'pt'=>'Portuguese',
                                    'pt-BR'=>'Brazilian',
                                    'rm'=>'Romansh',
                                    'ro'=>'Romanian',
                                    'ru'=>'Russian',
                                    'sk'=>'Slovak',
                                    'sl'=>'Slovenian',
                                    'sq'=>'Albanian',
                                    'sr'=>'Serbian',
                                    'sr-SR'=>'Serbian-i18n',
                                    'sv'=>'Swedish',
                                    'ta'=>'Tamil',
                                    'th'=>'Thai',
                                    'tj'=>'Tajiki',
                                    'tr'=>'Turkish',
                                    'uk'=>'Ukrainian',
                                    'vi'=>'Vietnamese',
                                    'zh-CN'=>'Chinese',
                                    'zh-HK'=>'Chinese-Hong-Kong',
                                    'zh-TW'=>'Chinese Taiwan',
                );  

            foreach($date_languages as $key=>$value){
                    $date_lang_symbol.='<option value="'.$key.'"';
                    if ($date_lang_status==$key){
                            $date_lang_symbol.=' selected="selected" ';
                    }
                    $date_lang_symbol.='>'.$value.'</option>';
            }
            $prices_th_separator_set = esc_html( wprentals_get_option('wp_estate_prices_th_separator') );
            $currency_label_main = esc_html( wprentals_get_option('wp_estate_currency_label_main') );
            print ' <div class="estate_start_setup">
                   <div class="label_option_start">'.__('Price - thousands separator','wprentals').'</div>
                   <div class="option_row_explain">'.__('Set the thousand separator for price numbers.','wprentals').'</div>
                   <input type="text" name="prices_th_separator" id="prices_th_separator_set" value="'.$prices_th_separator_set.'"> 
            </div>';

            print' <div class="estate_start_setup">
                 <div class="label_option_start">'.__('Currency Symbol','wprentals').'</div>
                 <div class="option_row_explain">'.esc_html__( 'This is used for default property price currency symbol and default currency symbol in multi currency dropdown','wprentals').'</div>
                 <input  type="text" id="currency_label_main"  name="currency_label_main"   value="'. $currency_label_main.'" size="40"/>
             </div>';

            print'<div class="estate_start_setup">    
                 <div class="label_option_start">'.__('Where to show the currency symbol?','wprentals').'</div>
                 <div class="option_row_explain">'.esc_html__( 'Where to show the currency symbol?','wprentals').'</label></div>
                 <select id="where_currency_symbol" name="where_currency_symbol">
                     '.$where_currency_symbol.'
                 </select> 
             </div>';

            print'<div class="estate_start_setup">
                <div class="label_option_start">'.esc_html__( 'Language for datepicker','wprentals').'</div>
                <div class="option_row_explain">'.__('Select the language for booking form datepicker and search by date datepicker','wprentals').'</div>    
                    <select id="date_lang" name="date_lang">
                        '.$date_lang_symbol.'
                    </select>
            </div>';
            
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_general_prev" value="' . __('Previous Step', 'wprentals') . '"/>';  
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_general_set" value="'.__('Next Step', 'wprentals').'"/>'; 
            $ajax_nonce = wp_create_nonce( "wprentals_general_set_nonce" );
            print'<input type="hidden" id="wprentals_general_set_nonce" value="'.esc_html($ajax_nonce).'" />    ';

        print'</div>';


        print'<div class="wpestate_admin_start_booking_settings" id="wpestate_booking_settings"><h1>'.__('Booking Settings', 'wprentals').'</h1>';
            $date_format_symbol='';
            $date_format_status=esc_html ( wprentals_get_option('wp_estate_date_format','') );

            $dates_types=array(
                        '0' =>'yy-mm-dd',
                        '1' =>'yy-dd-mm',
                        '2' =>'dd-mm-yy',
                        '3' =>'mm-dd-yy',
                        '4' =>'dd-yy-mm',
                        '5' =>'mm-yy-dd',
            );
            foreach($dates_types as $key=>$value){
                    $date_format_symbol.='<option value="'.$key.'"';
                    if ($date_format_status==$key){
                            $date_format_symbol.=' selected="selected" ';
                    }
                    $date_format_symbol.='>'.$value.'</option>';
            } 
    
            $setup_weekend_symbol='';
            $setup_weekend_status= esc_html ( wprentals_get_option('wp_estate_setup_weekend','') );
            $weekedn = array( 
                    0 => __("Sunday and Saturday","wprentals"),
                    1 => __("Friday and Saturday","wprentals"),
                    2 => __("Friday, Saturday and Sunday","wprentals")
                    );

            foreach($weekedn as $key=>$value){
                    $setup_weekend_symbol.='<option value="'.$key.'"';
                    if ($setup_weekend_status==$key){
                            $setup_weekend_symbol.=' selected="selected" ';
                    }
                    $setup_weekend_symbol.='>'.$value.'</option>';
            }       


            print'<div class="estate_start_setup">
              <div class="label_option_start">' . esc_html__('Select Date Format for datepickers', 'wprentals') . '</div>
              <div class="option_row_explain">' . __('You can set a dateformat that will be applied for all your datepickers', 'wprentals') . '</div>    
                  <select id="date_format_set" name="date_format_set">
                      ' . $date_format_symbol . '
                  </select>            
            </div>';  

            print'<div class="estate_start_setup">
              <div class="label_option_start">' . esc_html__('Select Weekend days', 'wprentals') . '</div>
              <div class="option_row_explain">' . __('Users can set a different price per day for weekend days', 'wprentals') . '</div>    
                  <select id="setup_weekend" name="setup_weekend">
                      ' . $setup_weekend_symbol . '
                  </select>            
            </div>';
            
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_booking_prev" value="' . __('Previous Step', 'wprentals') . '"/>';  
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_booking_set" value="'.__('Next Step', 'wprentals').'"/>'; 
            $ajax_nonce = wp_create_nonce( "wprentals_date_format_nonce" );
            print'<input type="hidden" id="wprentals_date_format_nonce" value="'.esc_html($ajax_nonce).'" />    ';

        print'</div>';


        print'<div class="wpestate_admin_start_booking_payment" id="wpestate_booking_payment"><h1>'.__('Booking Payment Options', 'wprentals').'</h1>';
            $book_down                      =   esc_html( wprentals_get_option('wp_estate_book_down','') );
            $book_down_fixed_fee            =   esc_html( wprentals_get_option('wp_estate_book_down_fixed_fee','') );
            $service_fee                    =   esc_html( wprentals_get_option('wp_estate_service_fee','') );
            $service_fee_fixed_fee          =   esc_html( wprentals_get_option('wp_estate_service_fee_fixed_fee','') );
            $extra_expense_list             =   esc_html( wprentals_get_option('wp_estate_extra_expense_list','') );

            $merch_array=array('no','yes');
            $include_expeses_symbol='';
            $include_expeses_status= esc_html ( wprentals_get_option('wp_estate_include_expenses','') );

            foreach($merch_array as $value){
                    $include_expeses_symbol.='<option value="'.$value.'"';
                    if ($include_expeses_status==$value){
                            $include_expeses_symbol.=' selected="selected" ';
                    }
                    $include_expeses_symbol.='>'.$value.'</option>';
            }


            print'<div class="estate_start_setup">
               <div class="label_option_start">'.__('Include expenses when calculating deposit?', 'wprentals').'</div>
               <div class="option_row_explain">'.__('Include expenses when calculating deposit. The expenses are city fee and cleaning fee.', 'wprentals').'</div>    
                   <select id="include_expenses" name="include_expenses">
                           '.$include_expeses_symbol.'
                       </select>
            </div>';


            print'<div class="estate_start_setup">
                <div class="label_option_start">'.__('Deposit Fee - % booking fee', 'wprentals').'</div>
                <div class="option_row_explain">'.__('Expenses are included or not in the deposit amount according to the above option. If the value is set to 100 (100%) the "Include expenses when calculating deposit?" option will be auto set to "yes"! ', 'wprentals').'</div>    
                    <input  type="text" id="book_down" name="book_down" style="margin-right:20px;"    value="'.$book_down.'"/>  
            </div>';

            print'<div class="estate_start_setup">
                <div class="label_option_start">'.__('Deposit Fee - fixed value booking fee', 'wprentals').'</div>
                <div class="option_row_explain">'.__('Add the fixed fee as a number. If you use this option, leave blank Deposit Fee - % booking fee', 'wprentals').'</div>    
                  <input  type="text" id="book_down_fixed_fee" name="book_down_fixed_fee" style="margin-right:20px;"    value="'.$book_down_fixed_fee.'"/>
            </div>';

            print'<div class="estate_start_setup">
                <div class="label_option_start">'.__('Service Fee - % booking fee', 'wprentals').'</div>
                <div class="option_row_explain">'.__('Service Fee. Is the commision that goes to the admin and is deducted from the total booking value.', 'wprentals').'</div>    
                    <input  type="text" id="service_fee" name="service_fee" style="margin-right:20px;"    value="'.$service_fee.'"/>  
            </div>';

            print'<div class="estate_start_setup">
                <div class="label_option_start">'.__('Service Fee - fixed value service fee', 'wprentals').'</div>
                <div class="option_row_explain">'.__('Service Fee - fixed value service fee. If you use this option, leave blank Service Fee - % booking fee', 'wprentals').'</div>    
                  <input  type="text" id="service_fee_fixed_fee" name="service_fee_fixed_fee" style="margin-right:20px;"    value="'.$service_fee_fixed_fee.'"/>
            </div>';
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_booking_payment_prev" value="' . __('Previous Step', 'wprentals') . '"/>';  
            print'<input type="submit" class="wpestate_admin_button reverse_but" id="button_booking_payment" value="'.__('Next Step', 'wprentals').'"/>';
            
            $ajax_nonce = wp_create_nonce( "wprentals_booking_payment_nonce" );
            print'<input type="hidden" id="wprentals_booking_payment_nonce" value="'.esc_html($ajax_nonce).'" />    ';

        print'</div>';
        
       
        print'<div class="wpestate_admin_end_notice" id="wpestate_end">';
            print'<p>'.__('For further setup see our help files, knowledgebase articles and tutorials to help make this process easier and more enjoyable for you: ','wprentals') .'<a target="_blank" href="https://help.wprentals.org/">'.__( 'https://help.wprentals.org', 'wprentals' ).'</a></p>';
        print'</div>';
        
        print'</div>';
    print'</div>';
}
         
// add the action 
add_action( 'welcome_panel', 'wpestate_action_welcome_panel', 11, 1 );



add_action( 'admin_init', 'wpestate_set_dashboard_meta_order' );
function wpestate_set_dashboard_meta_order() {
  $id = get_current_user_id(); //we need to know who we're updating
  $meta_value = array(
    'normal'  => 'wpestate_dashboard_welcome', //first key/value pair from the above serialized array
    'side'    => 'wpestate_dashboard_links', //second key/value pair from the above serialized array
    'column3' => 'wpestate_dashboard_new_property', //third key/value pair from the above serialized array
    'column4' => 'wpestate_set_payments', //last key/value pair from the above serialized array
  );
  update_user_meta( $id, 'meta-box-order_dashboard', $meta_value ); //update the user meta with the user's ID, the meta_key meta-box-order_dashboard, and the new meta_value
}


// remove_add new dashboard widgets
function wpestate_remove_dashboard_widgets() {
    $user = wp_get_current_user();

    if( current_user_can('administrator')){
        wp_add_dashboard_widget( 'wpestate_dashboard_welcome', __('Personalize Your Website','wprentals'), 'wpestate_add_welcome_widget' );
        wp_add_dashboard_widget( 'wpestate_dashboard_links', __('Add New Page','wprentals'), 'wpestate_add_new_page_widget' );
        wp_add_dashboard_widget( 'wpestate_set_payments', __('Payments','wprentals'), 'wpestate_add_payments_widget' );
        wp_add_dashboard_widget( 'wpestate_dashboard_new_property', __('Add New Property','wprentals'), 'wpestate_add_new_property_widget' );
    }
    global $wp_meta_boxes;
 
 
}
add_action( 'wp_dashboard_setup', 'wpestate_remove_dashboard_widgets' );






function wpestate_admin_login_logo() { 
         ?> <style type="text/css"> 
        body.login div#login h1 a {
            background-image: url(<?php 
                             $logo       =   esc_html(  wprentals_get_option('wp_estate_logo_image', 'url') );
                            if ($logo != '') {
                                    print  esc_url($logo);
                                } else {
                                    print get_template_directory_uri() . '/img/logo.png';
                                };
                                ?>);  //Add your own logo image in this url 
            padding-bottom: 30px; 
            background-position: center center;
            background-repeat: no-repeat;
            color: #444;
            height: 85px;
            width: 161px;
            margin: 0px auto;
            margin-top: 10px;
            background-size: contain;
        }
        body.login {
           background: linear-gradient(43deg, rgba(20,28,21,1) 0%, rgba(184, 129 ,253,1) 100%);
        }
        
        #login {
            padding: 0% 0 0;
            margin: auto;
            background-color: #fff;
            position: absolute;
            padding-bottom: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,.13);
            top: 50%;
            left: 50%;
            margin-left: -160px;
            margin-top: -235px;
        }  
        .login form{
            box-shadow: none;
            padding: 26px 24px 26px;
            margin-top: 0px;
        }
        .interim-login #login {
            margin-left: -160px;
            margin-top: -235px;
            margin-bottom: 0px;
            top: 56%;
        }
        #wp-auth-check-wrap #wp-auth-check {
            max-height: 515px!important;
        }
        .interim-login #login_error, 
        .interim-login.login .message {
            margin: 0px;
        }

</style><?php 
   
 }
 
 add_action('login_head', 'wpestate_admin_login_logo');
 
 
add_action( 'wp_ajax_wpestate_disable_licence_notifications', 'wpestate_disable_licence_notifications' );  
if( !function_exists('wpestate_disable_licence_notifications') ):
    function wpestate_disable_licence_notifications(){ 
        check_ajax_referer( 'wprentals_activate_license_nonce', 'security' );
        if(current_user_can('administrator')){
            update_option('wp_estate_disable_notice','yes'); 
            print 'disable';
        }
        die();
    }   
endif;