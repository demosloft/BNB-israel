<?php




add_action('wp_ajax_nopriv_wprentals_mapbox_places_query', 'wprentals_mapbox_places_query');
add_action('wp_ajax_wprentals_mapbox_places_query', 'wprentals_mapbox_places_query');

if (!function_exists('wprentals_mapbox_places_query')):
    function wprentals_mapbox_places_query()
    {
        $search_text= urldecode($_POST['text']);
        $args=array(
                'method' => 'GET',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'sslverify' => false,
                'blocking' => true,


        );

        $url='https://api.mapbox.com/geocoding/v5/mapbox.places/'.$search_text.'.json?autocomplete=true&access_token='. wprentals_get_option('wp_estate_mapbox_api_key');

        $response = wp_remote_get($url, $args);


        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            die($error_message);
        } else {
            $body = wp_remote_retrieve_body($response);
            $jsonResponse = json_decode($body, true);
        }
        return $jsonResponse;
        die();
    }
endif;










add_action('wp_ajax_wpestate_send_valid_sms', 'wpestate_send_valid_sms');

if (!function_exists('wpestate_send_valid_sms')):
    function wpestate_send_valid_sms()
    {
        check_ajax_referer('wprentals_send_sms_nonce', 'security');

        $current_user = wp_get_current_user();
        if (!is_user_logged_in()) {
            exit('out pls');
        }


        $userID             =   $current_user->ID;
        $user_email         =   $current_user->user_email ;



        $pin = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
        $user_mobile            =   get_the_author_meta('mobile', $userID);

        if (trim($user_mobile)!='') {
            $arguments=array(
                'apincode'           =>  $pin
                );
            wpestate_select_sms_type($user_mobile, 'validation', $arguments, $user_email, $userID);


            update_user_meta($userID, 'validation_pin', $pin) ;
            esc_html_e('We sent a sms with a validation code.', 'wprentals');
        } else {
            esc_html_e('Please add your mobile phone number!', 'wprentals');
        }


        die();
    }
endif;



add_action('wp_ajax_wpestate_validate_mobile', 'wpestate_validate_mobile');

if (!function_exists('wpestate_validate_mobile')):
    function wpestate_validate_mobile()
    {
        check_ajax_referer('wprentals_send_sms_nonce', 'security');

        $current_user = wp_get_current_user();
        if (!is_user_logged_in()) {
            exit('out pls');
        }


        $userID             =   $current_user->ID;
        $user_email         =   $current_user->user_email ;

        $validate_phoneno=floatval($_POST['validate_phoneno']);
        $sent_pin=get_user_meta($userID, 'validation_pin', true);

        if ($sent_pin==$validate_phoneno &&  $sent_pin!='' ) {       
            esc_html_e('Mobile phone number is validated.', 'wprentals');
            update_user_meta($userID, 'check_phone_valid', 'yes') ;
        } else {
            esc_html_e('The entered code is not correct. The mobile phone number is not validated.', 'wprentals');
        }

        die();
    }
endif;





function wpestate_show_license_form()
{
    if (!function_exists('wpestate_rentals_functionality_loaded')) {
        return;
    }

    $theme_activated    =   get_option('is_theme_activated', '');
    $ajax_nonce         =   wp_create_nonce("my-check_ajax_license-string");

    $current_screen= get_current_screen();

    if ($current_screen->id!='toplevel_page_WpRentals') {
        if (get_option('wp_estate_disable_notice', '')=='yes') {
            return;
        }
    }



    $return =1;


    if ($theme_activated!='is_active') {
        $theme_active_time = get_option('activation_time', '');
        if ($theme_active_time=='') {
            update_option('activation_time', time());
        }

        print '<div class="license_check_wrapper" id="license_check_wrapper">';
        echo' <div class="activate_notice notice_here">'.__('Please validate your purchase code in order to use the theme! See this <a href="https://help.wprentals.org/article/where-is-my-purchase-code/" target="_blank">link</a> if you don\'t know how to get your license key. Thank you!', 'wprentals').'</div>';
        print '<div class="license_form">
                <input type="text" id="wpestate_license_key" name="wpestate_license_key">
                <input type="submit" name="submit" id="check_ajax_license" class="new_admin_submit" value="Check License">
                <input type="hidden" id="license_ajax_nonce" name="license_ajax_nonce" value="'.$ajax_nonce.'">';

        $ajax_nonce = wp_create_nonce("wprentals_activate_license_nonce");
        print'<input type="hidden" id="wprentals_activate_license_nonce" value="'.esc_html($ajax_nonce).'" />
            </div>';
        //  <div class="activate_notice" id="wpestate_close_notice">'.esc_html__('Close this notice','wprentals').'</div>';
        print '</div>';
    }
    return $return;
}


if (!function_exists('wpestate_secondary_lic_plugin')):
function wpestate_secondary_lic_plugin()
{
    $theme_activated    =   get_option('is_theme_activated', '');
    if ($theme_activated==='is_active') {
        return true;
    } else {
        return false;
    }
}
endif;


add_action('wp_logout', 'wpestate_go_home');
if (!function_exists('wpestate_go_home')):
    function wpestate_go_home()
    {
        wp_redirect(esc_html(home_url('/')));
        exit();
    }
endif;



////////////////////////////////////////////////////////////////////////////////
/// activate reservation fee
////////////////////////////////////////////////////////////////////////////////


add_action('wp_ajax_nopriv_wpestate_admin_activate_reservation_fee', 'wpestate_admin_activate_reservation_fee');
add_action('wp_ajax_wpestate_admin_activate_reservation_fee', 'wpestate_admin_activate_reservation_fee');

if (!function_exists('wpestate_admin_activate_reservation_fee')):
    function wpestate_admin_activate_reservation_fee()
    {
        if (isset($_POST['is_zero_instant']) && intval($_POST['is_zero_instant'])==1) {
            check_ajax_referer('wprentals_confirm_zero_instant_booking_nonce', 'security');
        } else {
            check_ajax_referer('wprentals_activate_pack_nonce', 'security');
        }

        $current_user = wp_get_current_user();
        if (!is_user_logged_in()) {
            exit('out pls');
        }

        $booking_id         =   intval($_POST['book_id']);
        $invoice_id         =   intval($_POST['invoice_id']);
        $owner_id           =   get_post_meta($invoice_id, 'buyer_id', true);
        $user               =   get_user_by('id', $owner_id);
        $user_email         =   $user->user_email;
        $depozit            =   floatval(get_post_meta($invoice_id, 'depozit_to_be_paid', true));

        wpestate_booking_mark_confirmed($booking_id, $invoice_id, $owner_id, $depozit, $user_email);



        $arguments=array();
        wpestate_select_email_type($user_email, 'purchase_activated', $arguments);
        die();
    }


endif;








add_action('wp_ajax_wpestate_direct_pay_booking', 'wpestate_direct_pay_booking');

if (!function_exists('wpestate_direct_pay_booking')):
    function wpestate_direct_pay_booking()
    {
        check_ajax_referer('wprentals_reservation_actions_nonce', 'security');
        $current_user = wp_get_current_user();
        if (!is_user_logged_in()) {
            exit('out pls');
        }


        $userID             =   $current_user->ID;
        $user_email         =   $current_user->user_email ;



        $wpestate_propid   =   intval($_POST['propid']);
        $invoice_no        =   intval($_POST['invoiceid']);
        $bookid            =   intval($_POST['book_id']);

        if (function_exists('icl_translate')) {
            $mes =  strip_tags(wprentals_get_option('wp_estate_direct_payment_details', ''));
            $payment_details      =   icl_translate('wprentals', 'wp_estate_property_direct_payment_text', $mes);
        } else {
            $payment_details =   wprentals_get_option('wp_estate_direct_payment_details', '') ;
        }


        $total_price = floatval($_POST['price_pack']);
        $wpestate_currency        =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency  =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));



        if ($total_price != 0) {
            if ($wpestate_where_currency == 'before') {
                $total_price = $wpestate_currency . ' ' . $total_price;
            } else {
                $total_price = $total_price . ' ' . $wpestate_currency;
            }
        }


        update_post_meta($invoice_no, 'pay_status', 0);
        update_post_meta($invoice_id, 'depozit_paid', $total_price);

        $arguments=array(
            'invoice_no'        =>  $invoice_no,
            'total_price'       =>  $total_price,
            'payment_details'   =>  $payment_details,
        );

        wpestate_select_email_type($user_email, 'new_wire_transfer', $arguments);
        $company_email      =  get_bloginfo('admin_email');
        wpestate_select_email_type($company_email, 'admin_new_wire_transfer', $arguments);
    }
endif;








////////////////////////////////////////////////////////////////////////////////
/// activate purchase
////////////////////////////////////////////////////////////////////////////////


add_action('wp_ajax_wpestate_activate_purchase_listing', 'wpestate_activate_purchase_listing');

if (!function_exists('wpestate_activate_purchase_listing')):
    function wpestate_activate_purchase_listing()
    {
        check_ajax_referer('wprentals_activate_pack_nonce', 'security');

        if (!is_user_logged_in()) {
            exit('out pls');
        }
        if (!current_user_can('administrator')) {
            exit('out pls');
        }

        $item_id            =   intval($_POST['item_id']);
        $invoice_id         =   intval($_POST['invoice_id']);
        $type               =   intval($_POST['type']);
        $owner_id           =   get_post_meta($invoice_id, 'buyer_id', true);

        $user               =   get_user_by('id', $owner_id);
        $user_email         =   $user->user_email;

        if ($type==1) { // Listing
            update_post_meta($item_id, 'pay_status', 'paid');
            $post = array(
                    'ID'            => $item_id,
                    'post_status'   => 'publish'
                    );
            $post_id =  wp_update_post($post);
        } elseif ($type==2) { //Upgrade to Featured
            update_post_meta($item_id, 'prop_featured', 1);
        } elseif ($type==3) { //Publish Listing with Featured
            update_post_meta($item_id, 'pay_status', 'paid');
            update_post_meta($item_id, 'prop_featured', 1);
            $post = array(
                    'ID'            => $item_id,
                    'post_status'   => 'publish'
                    );
            $post_id =  wp_update_post($post);
        }

        update_post_meta($invoice_id, 'pay_status', 'confirmed');
        update_post_meta($invoice_id, 'invoice_status', 'confirmed');
        $arguments=array();
        wpestate_select_email_type($user_email, 'purchase_activated', $arguments);
    }


endif;
////////////////////////////////////////////////////////////////////////////////
/// activate purchase per listing
////////////////////////////////////////////////////////////////////////////////


add_action('wp_ajax_wpestate_direct_pay_pack_per_listing', 'wpestate_direct_pay_pack_per_listing');

if (!function_exists('wpestate_direct_pay_pack_per_listing')):
    function wpestate_direct_pay_pack_per_listing()
    {
        check_ajax_referer('wprentals_payments_actions_nonce', 'security');
        $current_user = wp_get_current_user();
        if (!is_user_logged_in()) {
            exit('out pls');
        }


        $userID             =   $current_user->ID;
        $user_email         =   $current_user->user_email ;

        $listing_id         = intval($_POST['selected_pack']);
        $include_feat       = intval($_POST['include_feat']);
        $pay_status         = get_post_meta($listing_id, 'pay_status', true);
        $price_submission           =   floatval(wprentals_get_option('wp_estate_price_submission', ''));
        $price_featured_submission  =   floatval(wprentals_get_option('wp_estate_price_featured_submission', ''));



        $total_price=0;
        $time = time();
        $date = date('Y-m-d H:i:s', $time);

        if ($include_feat==1) {
            if ($pay_status=='paid') {
                $invoice_no = wpestate_insert_invoice('Upgrade to Featured', 'One Time', $listing_id, $date, $current_user->ID, 0, 1, '');
                wpestate_email_to_admin(1);
                $total_price    =   $price_featured_submission;
            } else {
                $invoice_no = wpestate_insert_invoice('Publish Listing with Featured', 'One Time', $listing_id, $date, $current_user->ID, 1, 0, '');
                wpestate_email_to_admin(0);
                $total_price    =   $price_submission + $price_featured_submission;
            }
        } else {
            $invoice_no = wpestate_insert_invoice('Listing', 'One Time', $listing_id, $date, $current_user->ID, 0, 0, '');
            wpestate_email_to_admin(0);
            $total_price    =   $price_submission;
        }

        $wpestate_currency          =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency    =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        if ($total_price != 0) {
            //$total_price = number_format($total_price);

            if ($where_currency == 'before') {
                $total_price = $wpestate_currency . ' ' . $total_price;
            } else {
                $total_price = $total_price . ' ' . $wpestate_currency;
            }
        }


        // send email
        /**/

        if (function_exists('icl_translate')) {
            $mes =  strip_tags(wprentals_get_option('wp_estate_direct_payment_details', ''));
            $payment_details      =   icl_translate('wprentals', 'wp_estate_property_direct_payment_text', $mes);
        } else {
            $payment_details =  strip_tags(wprentals_get_option('wp_estate_direct_payment_details', ''));
        }




        update_post_meta($invoice_no, 'pay_status', 0);
        update_post_meta($invoice_no, 'invoice_status', 'issued');

        $arguments=array(
            'invoice_no'        =>  $invoice_no,
            'total_price'       =>  $total_price,
            'payment_details'   =>  $payment_details,
        );
        wpestate_select_email_type($user_email, 'new_wire_transfer', $arguments);
        $company_email      =  get_bloginfo('admin_email');
        wpestate_select_email_type($company_email, 'admin_new_wire_transfer', $arguments);

        // wpestate_direct_wire_email($invoice_no,$total_price);
        die();
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// activate purchase
////////////////////////////////////////////////////////////////////////////////



add_action('wp_ajax_wpestate_activate_purchase', 'wpestate_activate_purchase');

if (!function_exists('wpestate_activate_purchase')):
    function wpestate_activate_purchase()
    {
        check_ajax_referer('wprentals_activate_pack_nonce', 'security');

        if (!is_user_logged_in()) {
            exit('out pls');
        }
        if (!current_user_can('administrator')) {
            exit('out pls');
        }


        $pack_id        =   intval($_POST['item_id']);
        $invoice_id     =   intval($_POST['invoice_id']);
        $userID         =   get_post_meta($invoice_id, 'buyer_id', true);

        if (wpestate_check_downgrade_situation($userID, $pack_id)) {
            wpestate_downgrade_to_pack($userID, $pack_id);
            wpestate_upgrade_user_membership_on_wiretransfer($userID, $pack_id, 1, '', 1);
        } else {
            wpestate_upgrade_user_membership_on_wiretransfer($userID, $pack_id, 1, '', 1);
        }
        update_post_meta($invoice_id, 'pay_status', 'confirmed');
        update_post_meta($invoice_id, 'invoice_status', 'confirmed');
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// direct pay issue invoice
////////////////////////////////////////////////////////////////////////////////



add_action('wp_ajax_wpestate_direct_pay_pack', 'wpestate_direct_pay_pack');

if (!function_exists('wpestate_direct_pay_pack')):

    function wpestate_direct_pay_pack()
    {
        check_ajax_referer('wprentals_payments_actions_nonce', 'security');
        $current_user = wp_get_current_user();

        if (!is_user_logged_in()) {
            exit('out pls');
        }

        $userID                   =   $current_user->ID;
        $user_email               =   $current_user->user_email ;
        $selected_pack            =   intval($_POST['selected_pack']);
        $total_price              =   get_post_meta($selected_pack, 'pack_price', true);
        $wpestate_currency        =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency  =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));

        if ($total_price != 0) {
            if ($wpestate_where_currency == 'before') {
                $total_price = $wpestate_currency . ' ' . $total_price;
            } else {
                $total_price = $total_price . ' ' . $wpestate_currency;
            }
        }


        // insert invoice
        $time = time();
        $date = date('Y-m-d H:i:s', $time);
        $is_featured = 0;
        $is_upgrade=0;
        $paypal_tax_id='';

        $invoice_no = wpestate_insert_invoice('Package', 'One Time', $selected_pack, $date, $userID, $is_featured, $is_upgrade, $paypal_tax_id);

        // send email
        $headers    = 'From: No Reply <noreply@'.esc_url(home_url('/')).'>' . "\r\n";
        $message    = __('Hi there,', 'wprentals') . "\r\n\r\n";

        if (function_exists('icl_translate')) {
            $mes =  strip_tags(wprentals_get_option('wp_estate_direct_payment_details', ''));
            $payment_details      =   icl_translate('wprentals', 'wp_estate_property_direct_payment_text', $mes);
        } else {
            $payment_details = (wprentals_get_option('wp_estate_direct_payment_details', ''));
        }

        update_post_meta($invoice_no, 'pay_status', 0);
        update_post_meta($invoice_no, 'invoice_status', 'issued');
        $arguments=array(
            'invoice_no'        =>  $invoice_no,
            'total_price'       =>  $total_price,
            'payment_details'   =>  $payment_details,
        );

        // email sending
        wpestate_select_email_type($user_email, 'new_wire_transfer', $arguments);
        $company_email      =  get_bloginfo('admin_email');
        wpestate_select_email_type($company_email, 'admin_new_wire_transfer', $arguments);


        //wpestate_direct_wire_email($invoice_no,$total_price);
    }

endif;




////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_filter_ondemand_listings_with_geo', 'wpestate_ajax_filter_ondemand_listings_with_geo');
add_action('wp_ajax_wpestate_ajax_filter_ondemand_listings_with_geo', 'wpestate_ajax_filter_ondemand_listings_with_geo');

if (!function_exists('wpestate_ajax_filter_ondemand_listings_with_geo')):

    function wpestate_ajax_filter_ondemand_listings_with_geo()
    {
        global $post;
        global $wpestate_options;
        global $wpestate_show_compare_only;
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $wpestate_listing_type;
        global $wpestate_property_unit_slider;
        global $wpestate_curent_fav;
        global $wpestate_full_page;
        global $wpestate_guest;
        global $wpestate_guest_no;
        global $wpestate_book_from;
        global $wpestate_book_to;

        $wpestate_property_unit_slider =   esc_html(wprentals_get_option('wp_estate_prop_list_slider', ''));
        $wpestate_listing_type         =   wprentals_get_option('wp_estate_listing_unit_type', '');
        $wpestate_show_compare_only    =   'no';
        $current_user                  =   wp_get_current_user();
        $userID                        =   $current_user->ID;
        $user_option                   =   'favorites'.$userID;
        $wpestate_curent_fav           =   get_option($user_option);
        $wpestate_currency             =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency       =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $area_array                    =   '';
        $city_array                    =   '';
        $action_array                  =   '';
        $categ_array                   =   '';
        global $post;
        $page_template='';
        if( isset($_POST['postid']) ){
            $page_template = get_post_meta( intval($_POST['postid']), '_wp_page_template', true );
        }
       
        $wpestate_options              =   wpestate_page_details(intval($_POST['postid']));
        $allowed_html                  =   array();

      

        if ($wpestate_options['content_class']=="col-md-12") {
            $wpestate_full_page=1;
        }
        if ( $page_template === 'property_list_half.php') {
            $wpestate_full_page=0;
        }
        $property_list_type_status =    esc_html(wprentals_get_option('wp_estate_property_list_type_adv', ''));
        if ( $page_template  === 'advanced_search_results.php' && $property_list_type_status==2) {
            $wpestate_full_page=0;
        }
        //////////////////////////////////////////////////////////////////////////////////////
        ///// category filters
        //////////////////////////////////////////////////////////////////////////////////////

        if (isset($_POST['category_values']) && trim($_POST['category_values']) != 'all') {
            $taxcateg_include   =   sanitize_title(wp_kses($_POST['category_values'], $allowed_html));
            $categ_array=array(
                'taxonomy'  => 'property_category',
                'field'     => 'slug',
                'terms'     => $taxcateg_include
            );
        }



        //////////////////////////////////////////////////////////////////////////////////////
        ///// action  filters
        //////////////////////////////////////////////////////////////////////////////////////

        if ((isset($_POST['action_values']) && trim($_POST['action_values']) != 'all')) {
            $taxaction_include   =   sanitize_title(wp_kses($_POST['action_values'], $allowed_html));
            $action_array=array(
                'taxonomy'  => 'property_action_category',
                'field'     => 'slug',
                'terms'     => $taxaction_include
            );
        }




        $meta_query = $rooms = $baths = $price = array();
        if (isset($_POST['advanced_rooms']) && is_numeric($_POST['advanced_rooms']) && intval($_POST['advanced_rooms']!=0)) {
            $rooms['key']   = 'property_rooms';
            $rooms['value'] = floatval($_POST['advanced_rooms']);
            $meta_query[]   = $rooms;
        }

        if (isset($_POST['advanced_bath']) && is_numeric($_POST['advanced_bath'])  && intval($_POST['advanced_bath']!=0)) {
            $baths['key']   = 'property_bathrooms';
            $baths['value'] = floatval($_POST['advanced_bath']);
            $meta_query[]   = $baths;
        }


        if (isset($_POST['advanced_beds']) && is_numeric($_POST['advanced_beds']) && intval($_POST['advanced_beds']!=0)) {
            $beds['key']   = 'property_bedrooms';
            $beds['value'] = floatval($_POST['advanced_beds']);
            $meta_query[]   = $beds;
        }

        if (isset($_POST['guest_no']) && is_numeric($_POST['guest_no']) && intval($_POST['guest_no'])!=0) {
            $wpestate_guest['key']   = 'guest_no';
            $wpestate_guest['value'] = floatval($_POST['guest_no']);
            $wpestate_guest['type']     = 'numeric';
            $wpestate_guest['compare']  = '>=';
            $meta_query[]   = $wpestate_guest;
            $wpestate_guest=$wpestate_guest_no=floatval($_POST['guest_no']);
        }



        //////////////////////////////////////////////////////////////////////////////////////
        ///// chekcers
        //////////////////////////////////////////////////////////////////////////////////////
        $all_checkers=explode(",", $_POST['all_checkers']);

        foreach ($all_checkers as $cheker) {
            if ($cheker!='') {
                $check_array    =   array();
                $check_array['key']   =   $cheker;
                $check_array['value'] =  1;
                $check_array['compare']     = 'CHAR';
                $meta_query[]   =   $check_array;
            }
        }

        //////////////////////////////////////////////////////////////////////////////////////
        ///// price filters
        //////////////////////////////////////////////////////////////////////////////////////
        $price_low ='';
        if (isset($_POST['price_low'])) {
            $price_low = intval($_POST['price_low']);
        }

        $price_max='';
        if (isset($_POST['price_max'])  && is_numeric($_POST['price_max'])) {
            $price_max          = intval($_POST['price_max']);
            $price['key']       = 'property_price';

            $custom_fields = wprentals_get_option('wpestate_currency', '');


            if (!empty($custom_fields) && isset($_COOKIE['my_custom_curr']) &&  isset($_COOKIE['my_custom_curr_pos']) &&  isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos']!=-1) {
                $i=intval($_COOKIE['my_custom_curr_pos']);
                if ($price_low != 0) {
                    $price_low      = $price_low / $custom_fields[$i][2];
                }
                if ($price_max != 0) {
                    $price_max      = $price_max / $custom_fields[$i][2];
                }
            }

            $price['value']     = array($price_low, $price_max);
            $price['type']      = 'numeric';
            $price['compare']   = 'BETWEEN';
            $meta_query[]       = $price;
        }

        //////////////////////////////////////////////////////////////////////////////////////
        ///// calendar filters
        //////////////////////////////////////////////////////////////////////////////////////

        $allowed_html       =   array();
        $wpestate_book_from =   '';
        $wpestate_book_to   =   '';
        if (isset($_POST['check_in'])) {
            $wpestate_book_from      =  sanitize_text_field(wp_kses($_POST['check_in'], $allowed_html));
        }
        if (isset($_POST['check_out'])) {
            $wpestate_book_to        =  sanitize_text_field(wp_kses($_POST['check_out'], $allowed_html));
        }

        //////////////////////////////////////////////////////////////////////////////////////
        ///// order details
        //////////////////////////////////////////////////////////////////////////////////////
        $meta_order='prop_featured';
        $meta_directions='DESC';
        if (isset($_POST['order'])) {
            $order=  wp_kses($_POST['order'], $allowed_html);
            switch ($order) {
                case 1:
                    $meta_order='property_price';
                    $meta_directions='DESC';
                    break;
                case 2:
                    $meta_order='property_price';
                    $meta_directions='ASC';
                    break;
                case 3:
                    $meta_order='property_size';
                    $meta_directions='DESC';
                    break;
                case 4:
                    $meta_order='property_size';
                    $meta_directions='ASC';
                    break;
                case 5:
                    $meta_order='property_bedrooms';
                    $meta_directions='DESC';
                    break;
                case 6:
                    $meta_order='property_bedrooms';
                    $meta_directions='ASC';
                    break;
            }
        }

        $paged      =   intval($_POST['newpage']);
        $prop_no    =   intval(wprentals_get_option('wp_estate_prop_no', ''));




        $ne_lat          = floatval($_POST['ne_lat']);
        $ne_lng          = floatval($_POST['ne_lng']);
        $sw_lat          = floatval($_POST['sw_lat']);
        $sw_lng          = floatval($_POST['sw_lng']);



        $long_array=array();
        $lat_array=array();

        $meta_query['relation'] = 'AND';


        $min_lat    =  $sw_lat;
        $max_lat    =  $ne_lat;

        if ($min_lat>$max_lat) {
            $min_lat    =  $ne_lat;
            $max_lat    =  $sw_lat ;
        }


        $min_lng    =   $sw_lng;
        $max_lng    =   $ne_lng;

        if ($min_lng>$max_lng) {
            $min_lng = $ne_lng;
            $max_lng = $sw_lng;
        }


        $long_array['key']       = 'property_longitude';
        $long_array['value']     =  array( $min_lng,$max_lng);
        $long_array['type']      = 'DECIMAL';
        $long_array['compare']   = 'BETWEEN';
        $meta_query[]            =  $long_array;


        $lat_array['key']       = 'property_latitude';
        $lat_array['value']     =  array( $min_lat,$max_lat);
        $lat_array['type']      = 'DECIMAL';
        $lat_array['compare']   = 'BETWEEN';
        $meta_query[]           =  $lat_array;



        ////////////////////////////////////////////////////////////////////////////
        // if we have check in and check out dates we need to double loop
        ////////////////////////////////////////////////////////////////////////////
        if ($wpestate_book_from!='' && $wpestate_book_from!='') {
            $args = array(
                'cache_results'           =>    false,
                'update_post_meta_cache'  =>    false,
                'update_post_term_cache'  =>    false,
                'post_type'               =>    'estate_property',
                'post_status'             =>    'publish',
                'posts_per_page'          =>    '-1',
                'meta_key'                =>    'prop_featured',
                'orderby'                 =>    'meta_value',
                'meta_query'              =>    $meta_query,
                'tax_query'               => array(
                                                'relation' => 'AND',
                                                $categ_array,
                                                $action_array,
                                                $city_array,
                                                $area_array
                                            )
                );
            $args1 = array(
                'cache_results'           =>    false,
                'update_post_meta_cache'  =>    false,
                'update_post_term_cache'  =>    false,
                'post_type'               =>    'estate_property',
                'post_status'             =>    'publish',
                'posts_per_page'          =>    '-1',
                'meta_key'                =>    'prop_featured',
                'orderby'                 =>    'meta_value',
                'meta_query'              =>    $meta_query,
                'tax_query'               => array(
                                                'relation' => 'AND',
                                                $categ_array,
                                                $action_array,
                                                $city_array,
                                                $area_array
                                            )
                );

            add_filter('get_meta_sql', 'wpestate_cast_decimal_precision');
            $prop_selection = new WP_Query($args);
            if (function_exists('wpestate_disable_filtering')) {
                wpestate_disable_filtering('get_meta_sql', 'wpestate_cast_decimal_precision');
            }

            $right_array=array();
            $right_array[]=0;
            while ($prop_selection->have_posts()): $prop_selection->the_post();
            // print '</br>we check '.$post->ID.'</br>';
            if (wpestate_check_booking_valability($wpestate_book_from, $wpestate_book_to, $post->ID)) {
                $right_array[]=$post->ID;
            }
            endwhile;

            wp_reset_postdata();
            $args = array(
                    'cache_results'           =>    false,
                    'update_post_meta_cache'  =>    false,
                    'update_post_term_cache'  =>    false,
                    'meta_key'                =>    'prop_featured',
                    'orderby'                 =>    'meta_value',
                    'post_type'               =>    'estate_property',
                    'post_status'             =>    'publish',
                    'paged'                   =>    $paged,
                    'posts_per_page'          =>    $prop_no,
                    'post__in'                =>    $right_array
                );


            add_filter('posts_orderby', 'wpestate_my_order');
            $prop_selection =   new WP_Query($args);
            if (function_exists('wpestate_disable_filtering')) {
                wpestate_disable_filtering('posts_orderby', 'wpestate_my_order');
            }
        } else {
            $args = array(
                'cache_results'           =>    false,
                'update_post_meta_cache'  =>    false,
                'update_post_term_cache'  =>    false,
                'post_type'               =>    'estate_property',
                'post_status'             =>    'publish',
                'paged'                   =>    $paged,
                'posts_per_page'          =>    $prop_no,
                'meta_key'                =>    'prop_featured',
                'orderby'                 =>    'meta_value',
                'meta_query'              =>    $meta_query,
                'tax_query'               => array(
                                                'relation' => 'AND',
                                                $categ_array,
                                                $action_array,
                                                $city_array,
                                                $area_array
                                            )
                );
            add_filter('get_meta_sql', 'wpestate_cast_decimal_precision');
            add_filter('posts_orderby', 'wpestate_my_order');
            $prop_selection =   new WP_Query($args);
            if (function_exists('wpestate_disable_filtering')) {
                wpestate_disable_filtering('posts_orderby', 'wpestate_my_order');
                wpestate_disable_filtering('get_meta_sql', 'wpestate_cast_decimal_precision');
            }
        }

        $counter          =     0;
        $compare_submit   =     wpestate_get_template_link('compare_listings.php');
        $markers          =     array();
        $return_string='';
        ob_start();

        print '<span id="scrollhere"></span>';



        $listing_unit_style_half    =   wprentals_get_option('wp_estate_listing_unit_style_half', '');


        if ($prop_selection->have_posts()) {
            while ($prop_selection->have_posts()): $prop_selection->the_post();
            if ($listing_unit_style_half == 1 &&
                    (  $page_template  == 'property_list_half.php' ||
                    (  $page_template  == 'advanced_search_results.php' && $property_list_type_status==2 ) )
                ){
                include(locate_template('templates/property_unit_wide.php'));
            } else {
                include(locate_template('templates/property_unit.php'));
            }
            $markers[]=wpestate_pin_unit_creation(get_the_ID(), $wpestate_currency, $wpestate_where_currency, $counter);
            endwhile;
            wprentals_pagination_ajax($prop_selection->max_num_pages, $range =2, $paged, 'pagination_ajax_search_home');
        } else {
            print '<span class="no_results">'. esc_html__("We didn't find any results", "wprentals").'</>';
        }
        // print '</div>';
        $templates = ob_get_contents();
        ob_end_clean();
     
        $return_string .=   '<div class="half_map_results">'.$prop_selection->found_posts.' '.esc_html__(' Results found!', 'wprentals').'</div>';
        $return_string .=   $templates;
        echo json_encode(array('added'=>true,'arguments'=>json_encode($args),'arg1'=>json_encode($args1), 'markers'=>json_encode($markers),'response'=>$return_string ));
        die();
    }

endif; // end   ajax_filter_listings





if (!function_exists('wpestate_cast_decimal_precision')):

function wpestate_cast_decimal_precision($array)
{
    $array['where'] = str_replace('DECIMAL', 'DECIMAL(10,3)', $array['where']);

    return $array;
}

endif;






////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_wpestate_disable_listing', 'wpestate_disable_listing');

if (!function_exists('wpestate_disable_listing')):

    function wpestate_disable_listing()
    {
        check_ajax_referer('wprentals_property_actions_nonce', 'security');
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;
        $user_login                     =   $current_user->user_login;

        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }

        $prop_id=intval($_POST['prop_id']);
        if (!is_numeric($prop_id)) {
            exit();
        }

        $the_post= get_post($prop_id);

        if ($current_user->ID != $the_post->post_author) {
            exit('you don\'t have the right to delete this');
            ;
        }

        if ($the_post->post_status=='disabled') {
            $new_status='publish';
        } else {
            $new_status='disabled';
        }
        $my_post = array(
            'ID'           => $prop_id,
            'post_status'   => $new_status
        );


        wp_update_post($my_post);
        die();
    }
endif;
////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_wpestate_get_booking_data', 'wpestate_get_booking_data');

if (!function_exists('wpestate_get_booking_data')):

    function wpestate_get_booking_data()
    {
        check_ajax_referer('wprentals_allinone_nonce', 'security');
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;

        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }

        $internal_booking_id= intval($_POST['internal_booking_id']);
        if (!intval($internal_booking_id)) {
            exit();
        }

        $prop_id         =   get_post_meta($internal_booking_id, 'booking_id', true);
        $the_post= get_post($prop_id);

        if ($current_user->ID != $the_post->post_author) {
            exit('you don\'t have the right to see this');
        }


        $booking_from_date  =   get_post_meta($internal_booking_id, 'booking_from_date', true);
        $booking_to_date    =   get_post_meta($internal_booking_id, 'booking_to_date', true);
        $booking_guests     =   get_post_meta($internal_booking_id, 'booking_guests', true);
        $invoice_no         =   get_post_meta($internal_booking_id, 'booking_invoice_no', true);

        print __('Booking id', 'wprentals').': '.$internal_booking_id;
        print'<div class="allinone-booking-data">'.esc_html__('From', 'wprentals').' '.wpestate_convert_dateformat_reverse($booking_from_date).' '.__('To ', 'wprentals').' '.wpestate_convert_dateformat_reverse($booking_to_date).'</div>';
        if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
            print'<div class="allinone-booking-data-guests">'.esc_html__('Guests', 'wprentals').': '.$booking_guests.'</div>';
        }
        print'<div class="allinone-booking-data-invoice">'.esc_html__('Invoice', 'wprentals').': '.$invoice_no.'</div>';
        die();
    }


endif;
////////////////////////////////////////////////////////////////////////////////
/// cancel stripe
////////////////////////////////////////////////////////////////////////////////


add_action('wp_ajax_wpestate_cancel_stripe', 'wpestate_cancel_stripe');

if (!function_exists('wpestate_cancel_stripe')):
    function wpestate_cancel_stripe()
    {
        check_ajax_referer('wprentals_stripe_cancel_nonce', 'security');
        $current_user = wp_get_current_user();

        if (!is_user_logged_in()) {
            exit('ko');
        }


        global $wpestate_global_payments;
        $wpestate_global_payments->stripe_payments->cancel_stripe_recurring();
        exit();
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// filter invoices
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_wpestate_ajax_filter_invoices', 'wpestate_ajax_filter_invoices');

if (!function_exists('wpestate_ajax_filter_invoices')):
    function wpestate_ajax_filter_invoices()
    {
        check_ajax_referer('wprentals_invoice_pace_actions_nonce', 'security');

        $current_user =     wp_get_current_user();
        $userID       =     $current_user->ID;


        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }
        global $reservation_strings;
        $reservation_strings=array(
            'Upgrade to Featured'           => esc_html__('Upgrade to Featured', 'wprentals'),
            'Publish Listing with Featured' => esc_html__('Publish Listing with Featured', 'wprentals'),
            'Package'                       => esc_html__('Package', 'wprentals'),
            'Listing'                       => esc_html__('Listing', 'wprentals'),
            'Reservation fee'               => esc_html__('Reservation fee', 'wprentals')
        );

        $allowed_html = array();
        $userID                         =   $current_user->ID;

        $start_date       =   esc_html($_POST['start_date']);
        $end_date         =   esc_html($_POST['end_date']);
        $type             =   esc_html($_POST['type']);
        $status           =   esc_html($_POST['status']);


        $meta_query=array();

        if (isset($_POST['type']) &&  $_POST['type']!='') {
            $temp_arr             =   array();
            $type                 =   $reservation_strings[ wp_kses($_POST['type'], $allowed_html) ];
            $temp_arr['key']      =   'invoice_type';
            $temp_arr['value']    =   $type;
            $temp_arr['type']     =   'char';
            $temp_arr['compare']  =   'LIKE';
            $meta_query[]         =   $temp_arr;
        }


        if (isset($_POST['status']) &&  $_POST['status'] !='') {
            $temp_arr             =   array();
            $type                 =   wp_kses($_POST['status'], $allowed_html);
            $temp_arr['key']      =   'invoice_status';
            $temp_arr['value']    =   $type;
            $temp_arr['type']     =   'char';
            $temp_arr['compare']  =   'LIKE';
            $meta_query[]         =   $temp_arr;
        }

        $date_query=array();

        if (isset($_POST['start_date']) &&  $_POST['start_date'] !='') {
            $start_date =  wp_kses($_POST['start_date'], $allowed_html);
            $date_query ['after']  = $start_date;
        }

        if (isset($_POST['end_date']) &&  $_POST['end_date'] !='') {
            $end_date = wp_kses($_POST['end_date'], $allowed_html);
            $date_query ['before']  = $end_date;
        }
        $date_query ['inclusive'] = true;

        $args = array(
            'post_type'        => 'wpestate_invoice',
            'post_status'      => 'publish',
            'posts_per_page'   => -1 ,
            'author'           => $userID,
            'meta_query'       => $meta_query,
            'date_query'       => $date_query
        );



        $prop_selection = new WP_Query($args);
        $total_confirmed = 0;
        $total_issued=0;

        ob_start();
        $wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
        while ($prop_selection->have_posts()): $prop_selection->the_post();
            include(locate_template('dashboard/templates/invoice_listing_unit.php') );
            $inv_id =   get_the_ID();
            $status =   esc_html(get_post_meta($inv_id, 'invoice_status', true));
            $type   =   esc_html(get_post_meta($inv_id, 'invoice_type', true));
            $price  =   esc_html(get_post_meta($inv_id, 'item_price', true));

            if (trim($type) == 'Reservation fee' || trim($type) == esc_html__('Reservation fee', 'wprentals')) {
                if ($status == 'confirmed') {
                    $total_confirmed = $total_confirmed + $price;
                }
                if ($status == 'issued') {
                    $total_issued = $total_issued + $price;
                }
            } else {
                $total_issued='-';
                $total_confirmed = $total_confirmed + $price;
            }



        endwhile;
        $templates = ob_get_contents();
        ob_end_clean();

        echo json_encode(array('results'=>$templates,'invoice_issued'=>wpestate_show_price_custom_invoice($total_issued), 'invoice_confirmed'=> wpestate_show_price_custom_invoice($total_confirmed) ));

        die();
    }
endif;




////////////////////////////////////////////////////////////////////////////////
/// set cookie for multiple currency
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_set_cookie_multiple_curr', 'wpestate_set_cookie_multiple_curr');
add_action('wp_ajax_wpestate_set_cookie_multiple_curr', 'wpestate_set_cookie_multiple_curr');

if (!function_exists('wpestate_set_cookie_multiple_curr')):
    function wpestate_set_cookie_multiple_curr()
    {
        check_ajax_referer('wprentals_change_currency_nonce', 'security');
        $curr               =   esc_html($_POST['curr']);
        $pos                =   esc_html($_POST['pos']);
        $symbol             =   esc_html($_POST['symbol']);
        $coef               =   esc_html($_POST['coef']);
        $curpos             =   esc_html($_POST['curpos']);
        $symbol2            =   esc_html($_POST['symbol2']);

        setcookie("my_custom_curr", $curr, time()+3600, "/");
        setcookie("my_custom_curr_pos", $pos, time()+3600, "/");
        setcookie("my_custom_curr_symbol", $symbol, time()+3600, "/");
        setcookie("my_custom_curr_coef", $coef, time()+3600, "/");
        setcookie("my_custom_curr_cur_post", $curpos, time()+3600, "/");
        setcookie("my_custom_curr_symbol2", $symbol2, time()+3600, "/");

        wpestate_delete_cache();
    }
endif;





////////////////////////////////////////////////////////////////////////////////
/// Ajax  Register function
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_nopriv_wpestate_update_menu_bar', 'wpestate_update_menu_bar');
add_action('wp_ajax_wpestate_update_menu_bar', 'wpestate_update_menu_bar');

if (!function_exists('wpestate_update_menu_bar')):
    function wpestate_update_menu_bar()
    {
        check_ajax_referer('wprentals_ajax_filtering_nonce', 'security');
        $user_id= intval($_POST['newuser']);

        if ($user_id!=0 && $user_id!='') {
            $add_link               =   wpestate_get_template_link('user_dashboard_add_step1.php');
            $dash_profile           =   wpestate_get_template_link('user_dashboard_profile.php');
            $dash_favorite          =   wpestate_get_template_link('user_dashboard_favorite.php');
            $dash_link              =   wpestate_get_template_link('user_dashboard.php');

            $logout_url             =   wp_logout_url(wpestate_wpml_logout_url());
            $home_url               =   esc_html(home_url('/'));




            $menu='<div id="user_menu_open">';
            if ($home_url!=$dash_profile) {
                $menu.='  <a href="'.esc_url($dash_profile).'" ><i class="fas fa-cog"></i>'. esc_html__('My Profile', 'wprentals').'</a>';
            }

            if ($home_url!=$dash_link) {
                $menu.=' <a href="'.esc_url($dash_link).'" ><i class="fas fa-map-marker"></i>'. esc_html__('My Listings', 'wprentals').'</a>';
            }

            if ($home_url!=$add_link) {
                $menu.='<a href="'.esc_url($add_link).'" ><i class="fas fa-plus"></i>'. esc_html__('Add New Listing', 'wprentals').'</a>';
            }

            if ($home_url!=$dash_favorite) {
                $menu.='<a href="'.esc_url($dash_favorite).'" class="active_fav"><i class="fas fa-heart"></i>'. esc_html__('Favorites', 'wprentals').'</a>';
            }



            $menu.='<a href="'.wp_logout_url(wpestate_wpml_logout_url()).'" title="Logout" class="menulogout"><i class="fas fa-power-off"></i>'.esc_html__('Log Out', 'wprentals').'</a>';
            $menu.='</div>';


            $user_small_picture_id      =   get_the_author_meta('small_custom_picture', $user_id, true);
            if ($user_small_picture_id == '') {
                $user_small_picture=get_stylesheet_directory_uri().'/img/default_user_small.png';
            } else {
                $user_small_picture=wp_get_attachment_image_src($user_small_picture_id, 'wpestate_user_thumb');
            }

            $premenu='<a class="menu_user_tools dropdown" id="user_menu_trigger" data-toggle="dropdown">
                  <i class="fas fa-bars"></i></a>
                  <div class="menu_user_picture" style="background-image: url( '.$user_small_picture.' );"></div>';


            echo json_encode(array('picture'=>$user_small_picture[0], 'menu'=>$menu,'premenu'=>$premenu));
        }
        die();
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// New user notification
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_wp_new_user_notification')):

    function wpestate_wp_new_user_notification($user_id, $plaintext_pass = '')
    {
        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        $arguments=array(
            'user_login_register'      =>  $user_login,
            'user_email_register'      =>  $user_email
        );

        wpestate_select_email_type(get_option('admin_email'), 'admin_new_user', $arguments);


        if (empty($plaintext_pass)) {
            return;
        }

        $arguments=array(
           'user_login_register'      =>  $user_login,
           'user_email_register'      =>  $user_email,
           'user_pass_register'       => $plaintext_pass
       );
        wpestate_select_email_type($user_email, 'new_user', $arguments);
    }

 endif; // end   wpestate_wp_new_user_notification




////////////////////////////////////////////////////////////////////////////////
/// Ajax  Register function
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_register_form', 'wpestate_ajax_register_form');
add_action('wp_ajax_wpestate_ajax_register_form', 'wpestate_ajax_register_form');

if (!function_exists('wpestate_ajax_register_form')):

    function wpestate_ajax_register_form()
    {
        check_ajax_referer('wpestate_ajax_log_reg_nonce', 'security');

        $captcha = sanitize_text_field($_POST['capthca']);

        if (wprentals_get_option('wp_estate_use_captcha', '')=='yes') {
            if (!isset($_POST['capthca']) || $_POST['capthca']=='') {
                echo json_encode(array('register'=>false,'message'=>esc_html__('Please confirm you are not a robot!', 'wprentals')));
                exit();
            }

            $secret    = wprentals_get_option('wp_estate_recaptha_secretkey', '');

            global $wp_filesystem;
            if (empty($wp_filesystem)) {
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }

            $cappval    =    esc_html($_POST['capthca']) ;
            $response = wpestate_return_recapthca($secret, $cappval );
           
            if ($response['success'] == false) {
                echo json_encode(array('register'=>false,'message'=>esc_html__('Please confirm you are not a robot!', 'wprentals')));
                exit();
            }
        }


        $allowed_html   =   array();
        $user_email     =   trim(sanitize_text_field($_POST['user_email_register']));
        $user_name      =   trim(sanitize_text_field($_POST['user_login_register']));
        $user_phone     =   trim(sanitize_text_field($_POST['user_phone']));


        if (preg_match("/^[0-9A-Za-z_]+$/", $user_name) == 0) {
            echo json_encode(array('register'=>false,'message'=>esc_html__('Invalid username (do not use special characters or spaces)!', 'wprentals')));
            die();
        }


        if ($user_email=='' || $user_name=='') {
            echo json_encode(array('register'=>false,'message'=>esc_html__('Username and/or Email field is empty!', 'wprentals')));
            exit();
        }

        if (filter_var($user_email, FILTER_VALIDATE_EMAIL) === false) {
            echo json_encode(array('register'=>false,'message'=>esc_html__('The email doesn\'t look right!', 'wprentals')));
            exit();
        }

        $domain = substr(strrchr($user_email, "@"), 1);
        if (!checkdnsrr($domain)) {
            echo json_encode(array('register'=>false,'message'=>esc_html__('The email\'s domain doesn\'t look right!', 'wprentals')));
            exit();
        }

        $wp_estate_enable_user_phone= esc_html ( wprentals_get_option('wp_estate_enable_user_phone','') );
        if($wp_estate_enable_user_phone == 'yes' && $user_phone ==''){
            echo json_encode(array('register'=>false,'message'=>esc_html__('The phone number field is mandatory', 'wprentals')));
            exit();
        }


        $user_id     =   username_exists($user_name);
        if ($user_id) {
            echo json_encode(array('register'=>false,'message'=>esc_html__('Username already exists.  Please choose a new one.!', 'wprentals')));
            exit();
        }

        $enable_user_pass_status=   esc_html(wprentals_get_option('wp_estate_enable_user_pass', ''));
        if ($enable_user_pass_status=='yes') {
            $user_pass              =   trim(sanitize_text_field(wp_kses($_POST['user_pass'], $allowed_html)));
            $user_pass_retype       =   trim(sanitize_text_field(wp_kses($_POST['user_pass_retype'], $allowed_html)));

            if ($user_pass=='' || $user_pass_retype=='') {
                echo json_encode(array('register'=>false,'message'=>esc_html__('One of the password field is empty!', 'wprentals')));
                exit();
            }

            if ($user_pass !== $user_pass_retype) {
                echo json_encode(array('register'=>false,'message'=>esc_html__('Passwords do not match!', 'wprentals')));
                exit();
            }
        }


        if (!$user_id and email_exists($user_email) == false) {
            if ($enable_user_pass_status=='yes') {
                $random_password = $user_pass; // no so random now!
            } else {
                $random_password = wp_generate_password($length=12, $include_standard_special_chars=false);
            }

            $user_id         = wp_create_user($user_name, $random_password, $user_email);





            if (is_wp_error($user_id)) {
                // do nothing
            } else {
                if (isset($_POST['user_type'])) {
                    update_user_meta($user_id, 'user_type', intval($_POST['user_type']));
                }


                if (isset($_POST['user_phone'])) {
                    update_user_meta($user_id, 'mobile', sanitize_text_field($_POST['user_phone']));
                    $agent_id   =   intval(get_user_meta($user_id, 'user_agent_id', true));
                    if($agent_id!= 0){
                        update_post_meta( $agent_id, 'agent_mobile', sanitize_text_field($_POST['user_phone']) );
                    }
 
                }

                if ($enable_user_pass_status=='yes') {
                    echo json_encode(array('register'=>true,'message'=>esc_html__('The account was created. You can login now.', 'wprentals')));
                } else {
                    echo json_encode(array('register'=>true,'message'=>esc_html__('An email with the generated password was sent', 'wprentals')));
                }
                wpestate_update_profile($user_id);
                wpestate_wp_new_user_notification($user_id, $random_password) ;

                if (intval($_POST['user_type'])==0) {
                    wpestate_register_as_user($user_name, $user_id);
                }
            }
        } else {
            echo json_encode(array('register'=>false,'message'=>esc_html__('Email already exists.  Please choose a new one!', 'wprentals')));
        }
        die();
    }
endif; // end   wpestate_ajax_register_form

////////////////////////////////////////////////////////////////////////////////
/// register as agent
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_register_as_user')):
    function wpestate_register_as_user($user_name, $user_id, $first_name='', $last_name='')
    {
        $post = array(
            'post_title'	=> $user_name,
            'post_status'	=> 'publish',
            'post_type'         => 'estate_agent' ,
        );

        $post_id =  wp_insert_post($post);
        update_post_meta($post_id, 'user_meda_id', $user_id);
        update_post_meta($post_id, 'user_agent_id', $user_id) ;
        update_user_meta($user_id, 'user_agent_id', $post_id) ;



        if (esc_html(wprentals_get_option('wp_estate_separate_users', ''))=='yes') {
            $type=get_user_meta($user_id, 'user_type', true);
            update_post_meta($post_id, 'user_sub_type', $type) ;
        }

        if ($first_name!='') {
            update_user_meta($user_id, 'first_name', $first_name) ;
        }
        if ($last_name!='') {
            update_user_meta($user_id, 'last_name', $last_name) ;
        }
    }
endif;

 add_action('edit_user_profile_update', 'wpestate_update_extra_profile_fields');


if (!function_exists('wpestate_update_extra_profile_fields')):
 function wpestate_update_extra_profile_fields($user_id)
 {
     if (isset($_POST['user_type'])) {
         if ($_POST['user_type']==0) {
             if (get_user_meta($user_id, 'user_agent_id', true)==='') {
                 $user_info = get_userdata($user_id);
                 wpestate_register_as_user($user_info->user_login, $user_id);
             }
         }
     }
 }
endif;


////////////////////////////////////////////////////////////////////////////////
/// Ajax  Login function
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_loginx_form_topbar', 'wpestate_ajax_loginx_form_topbar');
add_action('wp_ajax_ajax_wpestate_ajax_loginx_form_topbar', 'wpestate_ajax_loginx_form_topbar');

if (!function_exists('wpestate_ajax_loginx_form_topbar')):

    function wpestate_ajax_loginx_form_topbar()
    {
        check_ajax_referer('wpestate_ajax_log_reg_nonce', 'security');

        if (is_user_logged_in()) {
            echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('You are already logged in! redirecting...', 'wprentals')));
            die();
        }
        check_ajax_referer('login_ajax_nonce_topbar', 'security');
        $allowed_html=array();
        $login_user  =  wp_kses($_POST['login_user'], $allowed_html) ;
        $login_pwd   =  wp_kses($_POST['login_pwd'], $allowed_html) ;


        if ($login_user=='' || $login_pwd=='') {
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Username and/or Password field is empty!', 'wprentals')));
            exit();
        }

        $vsessionid = session_id();
        if (empty($vsessionid)) {
            session_name('PHPSESSID');
            session_start();
        }


        wp_clear_auth_cookie();
        $info                   = array();
        $info['user_login']     = $login_user;
        $info['user_password']  = $login_pwd;
        $info['remember']       = false;

        $user_signon            = wp_signon($info, true);


        if (is_wp_error($user_signon)) {
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password!', 'wprentals')));
        } else {
            wp_set_current_user($user_signon->ID);
            do_action('set_current_user');
            global $current_user;
            $current_user = wp_get_current_user();

            echo json_encode(array('loggedin'=>true,'newuser'=>$user_signon->ID, 'message'=>esc_html__('Login successful, redirecting...', 'wprentals')));
            wpestate_update_old_users($user_signon->ID);
            wpestate_calculate_new_mess();
        }
        die();
    }
endif; // end   ajax_loginx_form


////////////////////////////////////////////////////////////////////////////////
/// Ajax  Login function
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_loginx_form', 'wpestate_ajax_loginx_form');
add_action('wp_ajax_wpestate_ajax_loginx_form', 'wpestate_ajax_loginx_form');

if (!function_exists('wpestate_ajax_loginx_form')):

    function wpestate_ajax_loginx_form()
    {
        check_ajax_referer('wpestate_ajax_log_reg_nonce', 'security');
        if (is_user_logged_in()) {
            echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('You are already logged in! redirecting...', 'wprentals')));
            exit();
        }




        $allowed_html   =  array();
        $login_user     =  sanitize_text_field($_POST['login_user']) ;
        $login_pwd      =  sanitize_text_field($_POST['login_pwd']) ;
        $ispop          =  intval($_POST['ispop']);
        $wpestate_propid=  intval($_POST['propid']);
        $redirect_url   =  '';

        if ($login_user=='' || $login_pwd=='') {
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Username and/or Password field is empty!', 'wprentals')));
            exit();
        }
        wp_clear_auth_cookie();
        $info                   = array();
        $info['user_login']     = $login_user;
        $info['user_password']  = $login_pwd;
        $info['remember']       = true;
        $user_signon            = wp_signon($info, true);


        if (is_wp_error($user_signon)) {
            echo json_encode(array('loggedin'=>false, 'message'=>esc_html__('Wrong username or password!', 'wprentals')   ));
        } else {
            global $current_user;
            wp_set_current_user($user_signon->ID);

            if (!wpestate_check_user_level()) {
                $redirect_url=  esc_html(wpestate_get_template_link('user_dashboard_profile.php'));
            } else {
                if ($wpestate_propid!=0) {
                    $redirect_url=wpestate_new_list_to_user($wpestate_propid, $user_signon->ID);
                }
            }

            do_action('set_current_user');
            $current_user = wp_get_current_user();


            echo json_encode(array('loggedin'=>true,'ispop'=>$ispop,'newuser'=>$user_signon->ID,'newlink'=>html_entity_decode($redirect_url), 'message'=>esc_html__('Login successful, redirecting...', 'wprentals')));
            wpestate_calculate_new_mess();
            wpestate_update_old_users($user_signon->ID);
        }
        die();
    }
endif; // end   ajax_loginx_form



////////////////////////////////////////////////////////////////////////////////
/// Ajax  Forgot Pass function
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_forgot_pass', 'wpestate_ajax_forgot_pass');
add_action('wp_ajax_wpestate_ajax_forgot_pass', 'wpestate_ajax_forgot_pass');

if (!function_exists('wpestate_ajax_forgot_pass')):

    function wpestate_ajax_forgot_pass()
    {
        check_ajax_referer('wpestate_ajax_log_reg_nonce', 'security');
        global $wpdb;


        $allowed_html   =   array();
        $post_id        =   intval($_POST['postid']) ;
        $forgot_email   =   sanitize_text_field(wp_kses($_POST['forgot_email'], $allowed_html)) ;
        $type           =   intval($_POST['type']);



        if ($forgot_email=='') {
            echo esc_html_e('Email field is empty!', 'wprentals');
            exit();
        }

        //We shall SQL escape the input
        $user_input = trim($forgot_email);

        if (strpos($user_input, '@')) {
            $user_data = get_user_by('email', $user_input);
            if (empty($user_data) || isset($user_data->caps['administrator'])) {
                echo __('Invalid E-mail address!', 'wprentals');
                exit();
            }
        } else {
            $user_data = get_user_by('login', $user_input);
            if (empty($user_data) || isset($user_data->caps['administrator'])) {
                echo __('Invalid Username!', 'wprentals');
                exit();
            }
        }
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $user_mobile    =   get_the_author_meta('mobile', $user_data->ID);


        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
        if (empty($key)) {
            //generate reset key
            $key = wp_generate_password(20, false);
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        }

        //emailing password change request details to the user
        $arguments=array(
            'reset_link'            =>  wpestate_tg_validate_url($post_id, $type) . "action=reset_pwd&key=$key&login=" . rawurlencode($user_login),
            'forgot_username'       =>  $user_login,
            'forgot_email'          =>  $user_email
        );

        wpestate_select_email_type($user_email, 'password_reset_request', $arguments);

        echo '<div>'.esc_html__('We have just sent you an email with Password reset instructions.', 'wprentals').'</div>';

        die();
    }
endif; // end   wpestate_ajax_forgot_pass


if (!function_exists('wpestate_tg_validate_url')):
    function wpestate_tg_validate_url($post_id, $type)
    {
        $page_url = esc_html(home_url('/'));
        $urlget = strpos($page_url, "?");
        if ($urlget === false) {
            $concate = "?";
        } else {
            $concate = "&";
        }
        return $page_url.$concate;
    }
endif; // end   wpestate_tg_validate_url





////////////////////////////////////////////////////////////////////////////////
/// Ajax  Forgot Pass function
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_wpestate_ajax_update_profile', 'wpestate_ajax_update_profile');

if (!function_exists('wpestate_ajax_update_profile')):

    function wpestate_ajax_update_profile()
    {
        check_ajax_referer('wprentals_update_profile_nonce', 'security');

        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;
        $login_name     =   $current_user->display_name;
        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }

        check_ajax_referer('profile_ajax_nonce', 'security-profile');

        $firstname               = sanitize_text_field($_POST['firstname']);
        $secondname              = sanitize_text_field($_POST['secondname']);
        $useremail               = sanitize_text_field($_POST['useremail']);
        $userphone               = sanitize_text_field($_POST['userphone']);
        $usermobile              = sanitize_text_field($_POST['usermobile']);
        $userskype               = sanitize_text_field($_POST['userskype']);
        $about_me                = sanitize_text_field($_POST['description']);
        $profile_image_url_small = sanitize_text_field($_POST['profile_image_url_small']);
        $profile_image_url       = sanitize_text_field($_POST['profile_image_url']);
        $userfacebook            = sanitize_text_field($_POST['userfacebook']);
        $usertwitter             = sanitize_text_field($_POST['usertwitter']);
        $userlinkedin            = sanitize_text_field($_POST['userlinkedin']);
        $userpinterest           = sanitize_text_field($_POST['userpinterest']);
        $live_in                 = sanitize_text_field($_POST['live_in']);
        $i_speak                 = sanitize_text_field($_POST['i_speak']);
        $paypal_payments_to      = sanitize_text_field($_POST['paypal_payments_to']);
        $payment_info            = sanitize_text_field($_POST['payment_info']);
        $youtube                 = sanitize_text_field($_POST['youtube']);
        $instagram               = sanitize_text_field($_POST['instagram']);
        $userwebsite             = sanitize_text_field($_POST['userwebsite']);

        update_user_meta($userID, 'first_name', $firstname);
        update_user_meta($userID, 'last_name', $secondname);
        update_user_meta($userID, 'phone', $userphone);
        update_user_meta($userID, 'skype', $userskype);

        update_user_meta($userID, 'custom_picture', $profile_image_url);
        update_user_meta($userID, 'small_custom_picture', $profile_image_url_small);

        update_user_meta($userID, 'youtube', $youtube);
        update_user_meta($userID, 'instagram', $instagram);

        $old_mobile=get_user_meta($userID, 'mobile', true);
        if ($old_mobile!=$usermobile) {
            update_user_meta($userID, 'check_phone_valid', 'no') ;
        }

        update_user_meta($userID, 'mobile', $usermobile) ;

        update_user_meta($userID, 'facebook', $userfacebook) ;
        update_user_meta($userID, 'twitter', $usertwitter) ;
        update_user_meta($userID, 'linkedin', $userlinkedin) ;
        update_user_meta($userID, 'pinterest', $userpinterest) ;
        update_user_meta($userID, 'description', $about_me) ;
        update_user_meta($userID, 'live_in', $live_in) ;
        update_user_meta($userID, 'i_speak', $i_speak) ;
        update_user_meta($userID, 'paypal_payments_to', $paypal_payments_to) ;
        update_user_meta($userID, 'payment_info', $payment_info) ;
        update_user_meta($userID, 'website', $userwebsite) ;


        $agent_id   =   get_user_meta($userID, 'user_agent_id', true);

        wpestate_update_user_agent($agent_id, $firstname, $secondname, $useremail, $userphone, $userskype, $profile_image_url, $usermobile, $about_me, $profile_image_url_small, $userfacebook, $usertwitter, $userlinkedin, $userpinterest, $live_in, $i_speak, $login_name, $payment_info, $youtube, $instagram, $userwebsite) ;


        if ($current_user->user_email != $useremail) {
            $user_id=email_exists($useremail) ;
            if ($user_id) {
                esc_html_e('The email was not saved because it is used by another user. ', 'wprentals');
            } elseif ($useremail=='') {
                esc_html_e('The email field cannot be blank. ', 'wprentals');
            } elseif (filter_var($useremail, FILTER_VALIDATE_EMAIL) === false) {
                print esc_html__('The email doesn\'t look right !', 'wprentals');
            } else {
                $args = array(
                    'ID'         => $userID,
                    'user_email' => $useremail
                );

                wp_update_user($args);
            }
        }


        esc_html_e('Profile updated', 'wprentals');

        $arguments=array(
            'user_id'               =>  $userID,
            'user_email_profile'    =>  $current_user->user_email,
            'user_login'            =>  $current_user->user_login
        );



        $company_email      =  get_bloginfo('admin_email');
        wpestate_select_email_type($company_email, 'agent_update_profile', $arguments);



        //User ID verification
        if (isset($_POST['user_id_image_id']) && intval($_POST['user_id_image_id']!=0)) {

                // check if already saved user ID
            $old_user_id_image_id =     get_user_meta($userID, 'user_id_image_id', true);
            $user_id_image        =     esc_url($_POST['user_id_url']);
            $user_id_image_id     =     absint($_POST['user_id_image_id']);

            // Update User ID verification fields
            if (is_numeric($_POST['user_id_image_id']) && (absint($_POST['user_id_image_id']) != $old_user_id_image_id)) {
                update_user_meta($userID, 'user_id_image', $user_id_image);
                update_user_meta($userID, 'user_id_image_id', $user_id_image_id);

                // strip user verification if new ID image has been uploaded
                update_user_meta($userID, 'user_id_verified', 0);

                // Set up separate user verification email

                $owner_id   =   intval(get_user_meta($userID, 'user_agent_id', true));

                $arguments  =   array(
                        'user_login'          =>   $current_user->user_login,

                    );

                wpestate_select_email_type($company_email, 'new_user_id_verification', $arguments);
            }
        }


        die();
    }
endif; // end   wpestate_ajax_update_profile



//delete account wpestate_delete_profile
add_action('wp_ajax_wpestate_delete_profile', 'wpestate_delete_profile');
if (!function_exists('wpestate_delete_profile')):
    function wpestate_delete_profile()
    {
        // sclear
        check_ajax_referer('wprentals_update_profile_nonce', 'security');
        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;
        if (!is_user_logged_in()) {
            exit('ko');
        }

        $args = array(
                'post_type' => array('estate_property',
                                    'estate_agent',
                                    'post',
                                    'wpestate_message',
                                    'attachment'
                                    ),
                'author'           =>  $userID,
                'posts_per_page'    => -1,
            );


        $prop_selection = new WP_Query($args);


        while ($prop_selection->have_posts()): $prop_selection->the_post();
        wp_delete_post(get_the_ID());
        endwhile;

        //delete comments
        $args = array(
            'user_id' => $userID, // use user_id
        );

        $comments = get_comments($args);

        foreach ($comments as $comment) :
            wp_delete_comment($comment->comment_ID);
        endforeach;

        $agent_page =   get_user_meta($userID, 'user_agent_id', true) ;
        wp_delete_post($agent_page);

        wp_delete_user($userID);

        die();
    }
endif; // end   wpestate_delete_profile



if (! function_exists('wpestate_update_verification')) {
    function wpestate_update_verification()
    {
        //check_ajax_referer( 'wprentals_user_verfication_nonce', 'security' );
        $userid   = intval(sanitize_text_field($_POST['userid']));
        $verified = intval(sanitize_text_field($_POST['verified']));

        if (is_numeric($userid) && $userid != 0 && ($verified == 0 || $verified == 1)) {
            $r = update_user_meta($userid, 'user_id_verified', $verified);

            if ($r) {
                print 'ok';
            }
        }
        die();
    }
}
add_action('wp_ajax_wpestate_update_verification', 'wpestate_update_verification');

/////////////////////////////////////////////////// update user

if (!function_exists('wpestate_update_user_agent')):
    function wpestate_update_user_agent($agent_id, $firstname, $secondname, $useremail, $userphone, $userskype, $profile_image_url, $usermobile, $about_me, $profile_image_url_small, $userfacebook, $usertwitter, $userlinkedin, $userpinterest, $live_in, $i_speak, $login_name, $payment_info, $youtube, $instagram, $userwebsite)
    {
        if (intval($agent_id)==0) {
            return;
        }
        if ($firstname!=='' || $secondname!='') {
            $post = array(
                       'ID'            => $agent_id,
                       'post_title'    => $firstname.' '.$secondname,
                       'post_content'  => $about_me,
            );
            $post_id =  wp_update_post($post);
        }

        if (trim($firstname)=='' && trim($secondname)=='') {
            $post = array(
                       'ID'            => $agent_id,
                       'post_title'    => $login_name,
                       'post_content'  => $about_me,
            );
            $post_id =  wp_update_post($post);
        }


        update_post_meta($agent_id, 'agent_email', $useremail);
        update_post_meta($agent_id, 'agent_phone', $userphone);
        update_post_meta($agent_id, 'agent_mobile', $usermobile);
        update_post_meta($agent_id, 'agent_skype', $userskype);
        update_post_meta($agent_id, 'agent_facebook', $userfacebook);
        update_post_meta($agent_id, 'agent_twitter', $usertwitter);
        update_post_meta($agent_id, 'agent_linkedin', $userlinkedin);
        update_post_meta($agent_id, 'agent_pinterest', $userpinterest);
        update_post_meta($agent_id, 'i_speak', $i_speak);
        update_post_meta($agent_id, 'live_in', $live_in);
        update_post_meta($agent_id, 'payment_info', $payment_info);
        update_post_meta($agent_id, 'agent_youtube', $youtube);
        update_post_meta($agent_id, 'agent_instagram', $instagram);
        update_post_meta($agent_id, 'agent_website', $userwebsite);




        set_post_thumbnail($agent_id, $profile_image_url_small);
    }
endif; // end   ajax_update_profile

////////////////////////////////////////////////////////////////////////////////
/// Ajax  Forgot Pass function
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_wpestate_ajax_update_pass', 'wpestate_ajax_update_pass');

if (!function_exists('wpestate_ajax_update_pass')):
    function wpestate_ajax_update_pass()
    {
        $current_user = wp_get_current_user();
        $allowed_html   =   array();
        $userID         =   $current_user->ID;

        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }



        $oldpass        =   sanitize_text_field(wp_kses($_POST['oldpass'], $allowed_html));
        $newpass        =   sanitize_text_field(wp_kses($_POST['newpass'], $allowed_html));
        $renewpass      =   sanitize_text_field(wp_kses($_POST['renewpass'], $allowed_html));

        if ($newpass=='' || $renewpass=='') {
            esc_html_e('The new password is blank', 'wprentals');
            die();
        }

        if ($newpass != $renewpass) {
            esc_html_e('Passwords do not match', 'wprentals');
            die();
        }
        check_ajax_referer('pass_ajax_nonce', 'security-pass');

        $user = get_user_by('id', $userID);
        if ($user && wp_check_password($oldpass, $user->data->user_pass, $user->ID)) {
            wp_set_password($newpass, $user->ID);
            esc_html_e('Password Updated - You will need to logout and login again ', 'wprentals');
        } else {
            esc_html_e('Old Password is not correct', 'wprentals');
        }

        die();
    }
endif; // end   wpestate_ajax_update_pass




////////////////////////////////////////////////////////////////////////////////
/// Ajax  Upload   function
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_wpestate_ajax_add_fav', 'wpestate_ajax_add_fav');

if (!function_exists('wpestate_ajax_add_fav')):
    function wpestate_ajax_add_fav()
    {
        check_ajax_referer('wprentals_ajax_filtering_nonce', 'security');

        $current_user = wp_get_current_user();
        $userID         =   $current_user->ID;
        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }
        $user_option          =   'favorites'.$userID;
        $post_id              =   intval($_POST['post_id']);

        $wpestate_curent_fav  =   get_option($user_option);

        if ($wpestate_curent_fav=='') { // if empy / first time
            $fav=array();
            $fav[]=$post_id;
            update_option($user_option, $fav);
            echo json_encode(array('added'=>true, 'response'=>esc_html__('addded', 'wprentals')));
            die();
        } else {
            if (! in_array($post_id, $wpestate_curent_fav)) {
                $wpestate_curent_fav[]=$post_id;
                update_option($user_option, $wpestate_curent_fav);
                echo json_encode(array('added'=>true, 'response'=>esc_html__('addded', 'wprentals')));
                die();
            } else {
                if (($key = array_search($post_id, $wpestate_curent_fav)) !== false) {
                    unset($wpestate_curent_fav[$key]);
                }
                update_option($user_option, $wpestate_curent_fav);
                echo json_encode(array('added'=>false, 'response'=>esc_html__('removed', 'wprentals')));
                die();
            }
        }
        die();
    }
endif; // end   wpestate_ajax_add_fav



////////////////////////////////////////////////////////////////////////////////
/// Ajax  Show contact owner
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_show_contact_owner_form', 'wpestate_ajax_show_contact_owner_form');
add_action('wp_ajax_wpestate_ajax_show_contact_owner_form', 'wpestate_ajax_show_contact_owner_form');

if (!function_exists('wpestate_ajax_show_contact_owner_form')):

    function wpestate_ajax_show_contact_owner_form()
    {
        global $post;
        if (is_singular('estate_property')) {
            $post_id    =   $post->ID;
            $agent_id   =   0;
        } else {
            $agent_id   =   $post->ID;
            $post_id    =   0;
        }


        print'
                <!-- Modal -->
                <div class="modal  fade" id="contact_owner_modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">';





        print'
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h2 class="modal-title_big">'.esc_html__('Contact the owner', 'wprentals').'</h2>
                              <h4 class="modal-title" id="myModalLabel">'.esc_html__('Please complete the form below to contact owner.', 'wprentals').'</h4>
                            </div>

                            <div class="modal-body">';




        wpestate_show_contact_form_simple($agent_id, $post_id);

        print '</div><!-- /.modal-body -->


                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->';
    }
endif; // end   wpestate_ajax_show_login_form










if (!function_exists('wpestate_show_contact_form_simple')):
function wpestate_show_contact_form_simple($agent_id, $post_id)
{
    print '<div id="booking_form_request_mess_modal"></div> ';

    if (! is_user_logged_in()) {
        print ' <div class="">
            <input type="text" id="contact_u_name" size="40" name="contact_u_name" class="form-control" placeholder="'.esc_html__('Your Name', 'wprentals').'" value="">
        </div>';

        print ' <div class="">
            <input type="text" id="contact_u_email" size="40" name="contact_u_email" class="form-control" placeholder="'.esc_html__('Your Email', 'wprentals').'" value="">
        </div>';
    }

    print'
        <div class=" has_calendar calendar_icon first_calendar">
            <input type="text" id="booking_from_date" size="40" name="booking_from_date" class="form-control" placeholder="'.esc_html__('Check-In', 'wprentals').'" value="">
        </div>

        <div class=" has_calendar calendar_icon">
            <input type="text" id="booking_to_date" size="40" name="booking_to_date" class="form-control" placeholder="'.esc_html__('Check-Out', 'wprentals').'" value="">
        </div>

        <div class="">
            <select id="booking_guest_no"  name="booking_guest_no"  class="cd-select form-control booking_guest_no_select" >
                <option value="1">1 '.esc_html__('Guest', 'wprentals').'</option>';
    for ($i = 2; $i <=    intval(wprentals_get_option('wp_estate_guest_dropdown_no', '')); $i++) {
        print '<option value="'.$i.'">'.$i.' '.esc_html__('Guests', 'wprentals').'</option>';
    }
    print'
            </select>
        </div>

        <input type="hidden" id="property_id" name="property_id" value="'.$post_id.'" />
        <input name="prop_id" type="hidden"  id="agent_property_id" value="'.$post_id.'">
        <input name="agent_id" type="hidden"  id="agent_id" value="'.$agent_id .'">

        <div class="">
            <textarea id="booking_mes_mess" name="booking_mes_mess" cols="50" rows="6" placeholder="'. esc_html__('Your message', 'wprentals').'" class="form-control"></textarea>
        </div>';


    wpestate_check_gdpr_case();


    print'<button type="submit" id="submit_mess_front" class="wpb_button submit_mess_front_class wpb_btn-info  wpb_regularsize   wpestate_vc_button  vc_button">'.esc_html__('Send Message', 'wprentals').'</button>';

    $ajax_nonce = wp_create_nonce("wprentals_submit_mess_front_nonce");
    print'<input type="hidden" id="wprentals_submit_mess_front_nonce" value="'.esc_html($ajax_nonce).'" />';
}
endif;








////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_filter_listings', 'wpestate_ajax_filter_listings');
add_action('wp_ajax_wpestate_ajax_filter_listings', 'wpestate_ajax_filter_listings');
if (!function_exists('wpestate_ajax_filter_listings')):

    function wpestate_ajax_filter_listings()
    {
        check_ajax_referer('wprentals_ajax_filtering_nonce', 'security');
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $post;
        global $wpestate_options;
        global $wpestate_full_page;
        global $wpestate_curent_fav;
        global $wpestate_listing_type;
        global $wpestate_property_unit_slider;

        $wpestate_property_unit_slider     =   esc_html(wprentals_get_option('wp_estate_prop_list_slider', ''));
        $wpestate_listing_type             =   wprentals_get_option('wp_estate_listing_unit_type', '');
        $current_user                      =   wp_get_current_user();
        $userID                            =   $current_user->ID;
        $user_option                       =   'favorites'.$userID;
        $wpestate_curent_fav               =   get_option($user_option);
        $wpestate_currency                 =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency           =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $area_array                        =   '';
        $city_array                        =   '';
        $action_array                      =   '';
        $categ_array                       =   '';
        $show_compare                      =   1;

        if (isset($_POST['page_id'])) {
            $wpestate_options                  =   wpestate_page_details(intval($_POST['page_id']));
        }



        //////////////////////////////////////////////////////////////////////////////////////
        ///// category filters
        //////////////////////////////////////////////////////////////////////////////////////
        $allowed_html   =   array();
        if (isset($_POST['category_values']) && trim($_POST['category_values']) != esc_html__('All Types', 'wprentals')  && trim($_POST['category_values']) != 'All Types' && $_POST['category_values']!=''&& $_POST['category_values']!='all') {
            $taxcateg_include   =   sanitize_title(wp_kses($_POST['category_values'], $allowed_html));
            $categ_array=array(
                'taxonomy'  => 'property_category',
                'field'     => 'slug',
                'terms'     => $taxcateg_include
            );
        }



        //////////////////////////////////////////////////////////////////////////////////////
        ///// action  filters
        //////////////////////////////////////////////////////////////////////////////////////

        if ((isset($_POST['action_values']) && trim($_POST['action_values']) != esc_html__('All Sizes', 'wprentals')) && trim($_POST['action_values']) != 'All Sizes' && $_POST['action_values']!='' && $_POST['action_values']!='all') {
            $taxaction_include   =   sanitize_title(wp_kses($_POST['action_values'], $allowed_html));
            $action_array=array(
                'taxonomy'  => 'property_action_category',
                'field'     => 'slug',
                'terms'     => $taxaction_include
            );
        }



        //////////////////////////////////////////////////////////////////////////////////////
        ///// city filters
        //////////////////////////////////////////////////////////////////////////////////////

        if (isset($_POST['city']) && trim($_POST['city']) != esc_html__('All Cities', 'wprentals')&& trim($_POST['city']) != 'All Cities' && $_POST['city'] && trim($_POST['city']) != 'all') {
            $taxcity[] = sanitize_title(wp_kses($_POST['city'], $allowed_html));
            $city_array = array(
                'taxonomy'  => 'property_city',
                'field'     => 'slug',
                'terms'     => $taxcity
            );
        }


        //////////////////////////////////////////////////////////////////////////////////////
        ///// area filters
        //////////////////////////////////////////////////////////////////////////////////////

        if (isset($_POST['area']) && trim($_POST['area']) != esc_html__('All Areas', 'wprentals') && trim($_POST['area']) != 'All Areas' && $_POST['area'] && trim($_POST['area']) != 'all') {
            $taxarea[] = sanitize_title(wp_kses($_POST['area'], $allowed_html));
            $area_array = array(
                'taxonomy'  => 'property_area',
                'field'     => 'slug',
                'terms'     => $taxarea
            );
        }


        //////////////////////////////////////////////////////////////////////////////////////
        ///// order details
        //////////////////////////////////////////////////////////////////////////////////////
        if (isset($_POST['order'])) {
            $order=wp_kses($_POST['order'], $allowed_html);
        }
        switch ($order) {
             case 0:
               $meta_order='prop_featured';
               $meta_directions='DESC';
               break;
           case 1:
               $meta_order='property_price';
               $meta_directions='DESC';
               break;
           case 2:
               $meta_order='property_price';
               $meta_directions='ASC';
               break;
           case 3:
               $meta_order='property_size';
               $meta_directions='DESC';
               break;
           case 4:
               $meta_order='property_size';
               $meta_directions='ASC';
               break;
           case 5:
               $meta_order='property_bedrooms';
               $meta_directions='DESC';
               break;
           case 6:
               $meta_order='property_bedrooms';
               $meta_directions='ASC';
               break;
        }
        $paged      =   intval($_POST['newpage']);
        $prop_no    =   intval(wprentals_get_option('wp_estate_prop_no', ''));

        $args = array(
            'post_type'         => 'estate_property',
            'post_status'       => 'publish',
            'paged'             => $paged,
            'posts_per_page'    => $prop_no,
            'orderby'           => 'meta_value_num',
            'meta_key'          => $meta_order,
            'order'             => $meta_directions,
            'meta_query'        => array(),
            'tax_query'         => array(
                                'relation' => 'AND',
                                        $categ_array,
                                        $action_array,
                                        $city_array,
                                        $area_array
                                )
        );

        if ($wpestate_options['content_class']=="col-md-12") {
            $wpestate_full_page=1;
        }

        if ($order==0) {
            add_filter('posts_orderby', 'wpestate_my_order');
            $prop_selection = new WP_Query($args);
            if (function_exists('wpestate_disable_filtering')) {
                wpestate_disable_filtering('posts_orderby', 'wpestate_my_order');
            }
        } else {
            $prop_selection = new WP_Query($args);
        }

        print '<span id="scrollhere"></span>';
        $counter = 0;

        if ($prop_selection->have_posts()) {
            while ($prop_selection->have_posts()): $prop_selection->the_post();
            include(locate_template('templates/property_unit.php'));
            endwhile;
            wprentals_pagination_ajax($prop_selection->max_num_pages, $range =2, $paged, 'pagination_ajax');
        } else {
            print '<span class="no_results">'. esc_html__("We didn't find any results", "wprentals").'</>';
        }
        wp_reset_query();
        die();
    }

 endif; // end   ajax_filter_listings_search




////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_filter_listings_search', 'wpestate_ajax_filter_listings_search');
add_action('wp_ajax_wpestate_ajax_filter_listings_search', 'wpestate_ajax_filter_listings_search');

if (!function_exists('wpestate_ajax_filter_listings_search')):

    function wpestate_ajax_filter_listings_search()
    {
        check_ajax_referer('wprentals_ajax_filtering_nonce', 'security');

        global $post;
        global $current_user;
        global $wpestate_options;
        global $wpestate_show_compare_only;
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $wpestate_full_page ;
        global $wpestate_listing_type;
        global $wpestate_property_unit_slider;

        $wpestate_property_unit_slider   =   esc_html(wprentals_get_option('wp_estate_prop_list_slider', ''));
        $wpestate_listing_type           =   wprentals_get_option('wp_estate_listing_unit_type', '');
        $wpestate_full_page              =   0;
        $wpestate_options                =   wpestate_page_details(intval($_POST['postid']));

        if ($wpestate_options['content_class']=="col-md-12") {
            $wpestate_full_page=1;
        }

        $wpestate_show_compare_only =   'no';
        $current_user               =   wp_get_current_user();
        $userID                     =   $current_user->ID;
        $user_option                =   'favorites'.$userID;
        $wpestate_curent_fav        =   get_option($user_option);
        $wpestate_currency          =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency    =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $area_array =
        $city_array =
        $action_array               = '';
        $categ_array                = '';
        $allowed_html               = array();
        //////////////////////////////////////////////////////////////////////////////////////
        ///// city filters
        //////////////////////////////////////////////////////////////////////////////////////

        if (isset($_POST['city']) and trim($_POST['city']) != 'all' and trim($_POST['city']) != '') {
            $taxcity[] = sanitize_title(wp_kses($_POST['city'], $allowed_html));
            $city_array = array(
                'taxonomy'  => 'property_city',
                'field'     => 'slug',
                'terms'     => $taxcity
            );
        }


        //////////////////////////////////////////////////////////////////////////////////////
        ///// area filters
        //////////////////////////////////////////////////////////////////////////////////////

        if (isset($_POST['area']) && trim($_POST['area']) != 'all' && trim($_POST['area']) != '') {
            $taxarea[] = sanitize_title(wp_kses($_POST['area'], $allowed_html));
            $area_array = array(
                'taxonomy' => 'property_area',
                'field'    => 'slug',
                'terms'    => $taxarea
            );
        }


        $meta_query     =   array();
        $guest_array    =   array();
        if (isset($_POST['guest_no'])  && is_numeric($_POST['guest_no']) && intval($_POST['guest_no'])!=0) {
            $wpestate_guest_no                = intval($_POST['guest_no']);
            $guest_array['key']      = 'guest_no';
            $guest_array['value']    = $wpestate_guest_no;
            $guest_array['type']     = 'numeric';
            $guest_array['compare']  = '>=';
            $meta_query[]            = $guest_array;
        }


        $country_array=array();
        if (isset($_POST['country'])  && $_POST['country']!='') {
            $country                     =   sanitize_text_field(wp_kses($_POST['country'], $allowed_html));
            $country                     =   str_replace('-', ' ', $country);
            $country_array['key']        =   'property_country';
            $country_array['value']      =   $country;
            $country_array['type']       =   'CHAR';
            $country_array['compare']    =   'LIKE';
            $meta_query[]                =   $country_array;
        }



        $allowed_html            =   array();
        $wpestate_book_from      =   '';
        $wpestate_book_to        =   '';
        if (isset($_POST['check_in'])) {
            $wpestate_book_from      =  sanitize_text_field(wp_kses($_POST['check_in'], $allowed_html));
        }
        if (isset($_POST['check_out'])) {
            $wpestate_book_to        =   sanitize_text_field(wp_kses($_POST['check_out'], $allowed_html));
        }

        $paged      =   intval($_POST['newpage']);
        $prop_no    =   intval(wprentals_get_option('wp_estate_prop_no', ''));
        $args = array(
            'post_type'         => 'estate_property',
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
            'meta_query'        => $meta_query,
            'tax_query'         => array(
                                    'relation' => 'AND',
                                    $categ_array,
                                    $action_array,
                                    $city_array,
                                    $area_array
                                    )
        );


        $prop_selection = new WP_Query($args);

        $counter          =   0;
        $compare_submit   =   wpestate_get_template_link('compare_listings.php');

        if ($prop_selection->have_posts()) {
            while ($prop_selection->have_posts()): $prop_selection->the_post();
            if (wpestate_check_booking_valability($wpestate_book_from, $wpestate_book_to, $post->ID)) {
                include(locate_template('templates/property_unit.php'));
            }
            endwhile;
        } else {
            print '<span class="no_results">'. esc_html__("We didn't find any results", "wprentals").'</>';
        }
        die();
    }
endif; // end   ajax_filter_listings






////////////////////////////////////////////////////////////////////////////////
/// wpestate_filter_query
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_filter_query')):


function wpestate_filter_query($orderby)
{
    $orderby = " DD.prop_featured  DESC ";
    return $orderby;
}
endif;
// end   wpestate_filter_query






 ////////////////////////////////////////////////////////////////////////////////
/// pay via paypal - per booking
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_booking_pay', 'wpestate_ajax_booking_pay');
add_action('wp_ajax_wpestate_ajax_booking_pay', 'wpestate_ajax_booking_pay');
if (!function_exists('wpestate_ajax_booking_pay')):
    function wpestate_ajax_booking_pay()
    {
        check_ajax_referer('wprentals_reservation_actions_nonce', 'security');

        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;


        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }

        global $current_user;
        $prop_id        =   intval($_POST['propid']);
        $book_id        =   intval($_POST['bookid']);
        $invoice_id     =   intval($_POST['invoice_id']);
        $deposit        =   floatval($_POST['depozit']);
        $current_user   =   wp_get_current_user();


        $paypal_status      =   esc_html(wprentals_get_option('wp_estate_paypal_api', ''));
        $host               =   'https://api.sandbox.paypal.com';
        $booking_guests     =   floatval(get_post_meta($book_id, 'booking_guests', true));
        $booking_from_date  =   esc_html(get_post_meta($book_id, 'booking_from_date', true));
        $booking_prop       =   esc_html(get_post_meta($book_id, 'booking_id', true));
        $booking_to_date    =   esc_html(get_post_meta($book_id, 'booking_to_date', true));
        $timeDiff           =   abs(strtotime($booking_to_date) - strtotime($booking_from_date));
        $numberDays         =   $timeDiff/86400;  // 86400 seconds in one day

        $booking_array      =   wpestate_booking_price($booking_guests, $invoice_id, $prop_id, $booking_from_date, $booking_to_date);



        // and you might want to convert to integer
        $numberDays         =   intval($numberDays);
        $price_per_day      =   floatval(get_post_meta($booking_prop, 'property_price', true));
        $price_per_option   =   floatval(get_post_meta($booking_prop, 'price_per', true));
        if ($price_per_option=='') {
            $price_per_option=1;
        }



        $total_price        =   number_format($booking_array['deposit'], 2);
        $total_price        =   number_format($deposit, 2);

        $submission_curency_status      =   esc_html(wprentals_get_option('wp_estate_submission_curency', ''));
        $pay_description                =   esc_html__('Deposit for Booking on ', 'wprentals').esc_html(home_url('/'));




        if ($paypal_status=='live') {
            $host='https://api.paypal.com';
        }

        $url                =   $host.'/v1/oauth2/token';
        $postArgs           =   'grant_type=client_credentials';
        $token              =   wpestate_get_access_token($url, $postArgs);
        $url                =   $host.'/v1/payments/payment';
        $dash_link          =   wpestate_get_template_link('user_dashboard.php');
        $processor_link     =   wpestate_get_template_link('processor.php');


        $payment = array(
                        'intent' => 'sale',
                        "redirect_urls"=>array(
                                "return_url"=>$processor_link,
                                "cancel_url"=>$dash_link
                            ),
                        'payer' => array("payment_method"=>"paypal"),
                    );


        $payment['transactions'][0] = array(
                                            'amount' => array(
                                                'total' => $total_price,
                                                'currency' => $submission_curency_status,
                                                'details' => array(
                                                    'subtotal' => $total_price,
                                                    'tax' => '0.00',
                                                    'shipping' => '0.00'
                                                    )
                                                ),
                                            'description' => $pay_description
                                           );
        // prepare individual items


        $payment['transactions'][0]['item_list']['items'][] = array(
                                                        'quantity' => '1',
                                                        'name' => esc_html__('Deposit for Booking Id', 'wprentals').' '.$book_id.' '.esc_html__('Invoice Id ', 'wprentals').$invoice_id,
                                                        'price' => $total_price,
                                                        'currency' => $submission_curency_status,
                                                        'sku' => 'Deposit for Booking',
                                                        );




        $json = json_encode($payment);
        $json_resp = wpestate_make_post_call($url, $json, $token);
        foreach ($json_resp['links'] as $link) {
            if ($link['rel'] == 'execute') {
                $payment_execute_url = $link['href'];
                $payment_execute_method = $link['method'];
            } elseif ($link['rel'] == 'approval_url') {
                $payment_approval_url = $link['href'];
                $payment_approval_method = $link['method'];
            }
        }





        $executor['paypal_execute']     =   $payment_execute_url;
        $executor['paypal_token']       =   $token;
        $executor['listing_id']         =   $prop_id;
        $executor['is_booking']         =   1;
        $executor['invoice_id']         =   $invoice_id;
        $executor['booking_id']         =   $book_id;
        $executor['is_featured']        =   0;
        $executor['is_upgrade']         =   0;

        $save_data[$current_user->ID]   =   $executor;
        update_option('paypal_transfer', $save_data);

        print trim($payment_approval_url);

        die();
    }
endif;







 ////////////////////////////////////////////////////////////////////////////////
/// pay via paypal - per listing
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_listing_pay', 'wpestate_ajax_listing_pay');
add_action('wp_ajax_wpestate_ajax_listing_pay', 'wpestate_ajax_listing_pay');

if (!function_exists('wpestate_ajax_listing_pay')):

    function wpestate_ajax_listing_pay()
    {
        check_ajax_referer('wprentals_payments_actions_nonce', 'security');
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;


        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }

        $is_featured    =   intval($_POST['is_featured']);
        $prop_id        =   intval($_POST['propid']);
        $is_upgrade     =   intval($_POST['is_upgrade']);


        $userID =   $current_user->ID;
        $post   =   get_post($prop_id);

        if ($post->post_author != $userID) {
            exit('get out of my cloud');
        }

        $paypal_status                  =   esc_html(wprentals_get_option('wp_estate_paypal_api', ''));
        $host                           =   'https://api.sandbox.paypal.com';
        $price_submission               =   floatval(wprentals_get_option('wp_estate_price_submission', ''));
        $price_featured_submission      =   floatval(wprentals_get_option('wp_estate_price_featured_submission', ''));
        $submission_curency_status      =   esc_html(wprentals_get_option('wp_estate_submission_curency', ''));
        $pay_description                =   esc_html__('Listing payment on ', 'wprentals').esc_html(home_url('/'));

        if ($is_featured==0) {
            $total_price =  number_format($price_submission, 2, '.', '');
        } else {
            $total_price = $price_submission + $price_featured_submission;
            $total_price = number_format($total_price, 2, '.', '');
        }


        if ($is_upgrade==1) {
            $total_price        =  number_format($price_featured_submission, 2, '.', '');
            $pay_description    =   esc_html__('Upgrade to featured listing on ', 'wprentals').esc_html(home_url('/'));
        }


        if ($paypal_status=='live') {
            $host='https://api.paypal.com';
        }

        $url                =   $host.'/v1/oauth2/token';
        $postArgs           =   'grant_type=client_credentials';
        $token              =   wpestate_get_access_token($url, $postArgs);
        $url                =   $host.'/v1/payments/payment';
        $dash_link          =   wpestate_get_template_link('user_dashboard.php');
        $processor_link     =   wpestate_get_template_link('processor.php');


        $payment = array(
                        'intent' => 'sale',
                        "redirect_urls"=>array(
                                "return_url"=>$processor_link,
                                "cancel_url"=>$dash_link
                            ),
                        'payer' => array("payment_method"=>"paypal"),
                    );


        $payment['transactions'][0] = array(
                                            'amount' => array(
                                                'total' => $total_price,
                                                'currency' => $submission_curency_status,
                                                'details' => array(
                                                    'subtotal' => $total_price,
                                                    'tax' => '0.00',
                                                    'shipping' => '0.00'
                                                    )
                                                ),
                                            'description' => $pay_description
                                           );
        // prepare individual items


        if ($is_upgrade==1) {
            $payment['transactions'][0]['item_list']['items'][] = array(
                                                'quantity' => '1',
                                                'name' => esc_html__('Upgrade to Featured Listing', 'wprentals'),
                                                'price' => $total_price,
                                                'currency' => $submission_curency_status,
                                                'sku' => 'Upgrade Featured Listing',
                                                );
        } else {
            if ($is_featured==0) {
                $payment['transactions'][0]['item_list']['items'][] = array(
                                                         'quantity' => '1',
                                                         'name' => esc_html__('Listing Payment', 'wprentals'),
                                                         'price' => $total_price,
                                                         'currency' => $submission_curency_status,
                                                         'sku' => 'Paid Listing',

                                                        );
            } else {
                $payment['transactions'][0]['item_list']['items'][] = array(
                                                         'quantity' => '1',
                                                         'name' => esc_html__('Listing Payment with Featured option', 'wprentals'),
                                                         'price' => $total_price,
                                                         'currency' => $submission_curency_status,
                                                         'sku' => 'Featured Paid Listing',
                                                         );
            } // end is featured
        } // end is upgrade




            $json = json_encode($payment);
        $json_resp = wpestate_make_post_call($url, $json, $token);
        foreach ($json_resp['links'] as $link) {
            if ($link['rel'] == 'execute') {
                $payment_execute_url = $link['href'];
                $payment_execute_method = $link['method'];
            } elseif ($link['rel'] == 'approval_url') {
                $payment_approval_url = $link['href'];
                $payment_approval_method = $link['method'];
            }
        }





        $executor['paypal_execute']     =   $payment_execute_url;
        $executor['paypal_token']       =   $token;
        $executor['listing_id']         =   $prop_id;
        $executor['is_featured']        =   $is_featured;
        $executor['is_upgrade']         =   $is_upgrade;
        $save_data[$current_user->ID]   =   $executor;
        update_option('paypal_transfer', $save_data);

        print trim($payment_approval_url);

        die();
    }
endif; // end   wpestate_ajax_listing_pay



////////////////////////////////////////////////////////////////////////////////
/// pay via paypal - per listing
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_resend_for_approval', 'wpestate_ajax_resend_for_approval');
add_action('wp_ajax_wpestate_ajax_resend_for_approval', 'wpestate_ajax_resend_for_approval');

if (!function_exists('wpestate_ajax_resend_for_approval')):

    function wpestate_ajax_resend_for_approval()
    {
        check_ajax_referer('wprentals_property_actions_nonce', 'security');
        $prop_id        =   intval($_POST['propid']);
        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;


        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }
        $userID =   $current_user->ID;
        $post   =   get_post($prop_id);

        if ($post->post_author != $userID) {
            exit('get out of my cloud');
        }

        $free_list=get_user_meta($userID, 'package_listings', true);

        if ($free_list>0 ||  $free_list==-1) {
            $paid_submission_status = esc_html(wprentals_get_option('wp_estate_paid_submission', ''));
            $new_status             = 'pending';

            $admin_submission_status= esc_html(wprentals_get_option('wp_estate_admin_submission', ''));
            if ($admin_submission_status=='no' && $paid_submission_status!='per listing') {
                $new_status='publish';
            }

            $prop = array(
               'ID'            => $prop_id,
               'post_type'     => 'estate_property',
               'post_status'   => $new_status
            );
            wp_update_post($prop);
            update_post_meta($prop_id, 'prop_featured', 0);

            if ($free_list!=-1) { // if !unlimited
                update_user_meta($userID, 'package_listings', $free_list-1);
            }

            if ($new_status== 'publish') {
                print '<span class="info-container_status">'.esc_html__('Published!', 'wprentals').'</span>';
            } else {
                print '<span class="sent_approval">'.esc_html__('Sent for approval', 'wprentals').'</span>';
                $submit_title   =   get_the_title($prop_id);
                $arguments=array(
                    'submission_title'        =>    $submit_title,
                    'submission_url'          =>   esc_url(get_permalink($prop_id))
                );

                wpestate_select_email_type(get_option('admin_email'), 'admin_expired_listing', $arguments);
            }
        } else {
            print '<span class="info-container_status">'.esc_html__('no listings available', 'wprentals').'</span>';
        }
        die();
    }

 endif; // end   wpestate_ajax_resend_for_approval







////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_wpestate_ajax_paypal_pack_generation', 'wpestate_ajax_paypal_pack_generation');

if (!function_exists('wpestate_ajax_paypal_pack_generation')):

    function wpestate_ajax_paypal_pack_generation()
    {
        check_ajax_referer('wprentals_payments_actions_nonce', 'security');
        $current_user = wp_get_current_user();
        $userID                         =   $current_user->ID;


        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }



        $allowed_html   =   array();
        $packName   =   wp_kses($_POST['packName'], $allowed_html);
        $pack_id    =   intval($_POST['packId']);
        if (!is_numeric($pack_id)) {
            exit();
        }


        $is_pack = get_posts('post_type=membership_package&p='.$pack_id);


        if (!empty($is_pack)) {
            $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
            $submission_curency_status      =   esc_html(wprentals_get_option('wp_estate_submission_curency', ''));
            $paypal_status                  =   esc_html(wprentals_get_option('wp_estate_paypal_api', ''));

            $host                           =   'https://api.sandbox.paypal.com';
            if ($paypal_status=='live') {
                $host   =   'https://api.paypal.com';
            }

            $url        = $host.'/v1/oauth2/token';
            $postArgs   = 'grant_type=client_credentials';
            $token      = wpestate_get_access_token($url, $postArgs);
            $url        = $host.'/v1/payments/payment';


            $dash_profile_link = wpestate_get_template_link('user_dashboard_profile.php');


            $payment = array(
                                'intent' => 'sale',
                                "redirect_urls"=>array(
                                    "return_url"=>$dash_profile_link,
                                    "cancel_url"=>$dash_profile_link
                                    ),
                                'payer' => array("payment_method"=>"paypal"),

                    );


            $payment['transactions'][0] = array(
                                            'amount' => array(
                                                'total' => $pack_price,
                                                'currency' => $submission_curency_status,
                                                'details' => array(
                                                    'subtotal' => $pack_price,
                                                    'tax' => '0',
                                                    'shipping' => '0'
                                                    )
                                                ),
                                            'description' => $packName.' '.esc_html__('membership payment on ', 'wprentals').esc_html(home_url('/'))
                                           );

            //
            // prepare individual items
            $payment['transactions'][0]['item_list']['items'][] = array(
                                                                'quantity' => '1',
                                                                'name' => esc_html__('Membership Payment', 'wprentals'),
                                                                'price' => $pack_price,
                                                                'currency' => $submission_curency_status,
                                                                'sku' => $packName.' '.esc_html__('Membership Payment', 'wprentals'),
                                                               );


            $json = json_encode($payment);
            $json_resp = wpestate_make_post_call($url, $json, $token);

            foreach ($json_resp['links'] as $link) {
                if ($link['rel'] == 'execute') {
                    $payment_execute_url = $link['href'];
                    $payment_execute_method = $link['method'];
                } elseif ($link['rel'] == 'approval_url') {
                    $payment_approval_url = $link['href'];
                    $payment_approval_method = $link['method'];
                }
            }



            $executor['paypal_execute']     =   $payment_execute_url;
            $executor['paypal_token']       =   $token;
            $executor['pack_id']            =   $pack_id;
            $save_data[$current_user->ID ]  =   $executor;
            update_option('paypal_pack_transfer', $save_data);
            print trim($payment_approval_url);
        }
        die();
    }

endif; // end   ajax_paypal_pack_generation  - de la ajax_upload




////////////////////////////////////////////////////////////////////////////////
/// Ajax  Package Paypal function - recuring payments REST API
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_wpestate_ajax_paypal_pack_recuring_generation_rest_api', 'wpestate_ajax_paypal_pack_recuring_generation_rest_api');

if (!function_exists('wpestate_ajax_paypal_pack_recuring_generation_rest_api')):

    function wpestate_ajax_paypal_pack_recuring_generation_rest_api()
    {
        check_ajax_referer('wprentals_payments_actions_nonce', 'security');

        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;

        if (!is_user_logged_in()) {
            exit('ko');
        }
        if ($userID === 0) {
            exit('out pls');
        }

        $allowed_html   =   array();
        $packName       =   wp_kses($_POST['packName'], $allowed_html);
        $pack_id        =   intval($_POST['packId']);
        if (!is_numeric($pack_id)) {
            exit();
        }


        $is_pack = get_posts('post_type=membership_package&p='.$pack_id);
        if (!empty($is_pack)) {
            $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
            $billing_period                 =   get_post_meta($pack_id, 'biling_period', true);
            $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
            $pack_name                      =   get_the_title($pack_id);
            $submission_curency_status      =   esc_html(wprentals_get_option('wp_estate_submission_curency', ''));

            $host                           =   'https://api.sandbox.paypal.com';
            $paypal_status                  =   esc_html(wprentals_get_option('wp_estate_paypal_api', ''));
            if ($paypal_status=='live') {
                $host   =   'https://api.paypal.com';
            }
            $url        = $host.'/v1/oauth2/token';
            $postArgs   = 'grant_type=client_credentials';

            $token      = wpestate_get_access_token($url, $postArgs);


            $payment_plan = get_post_meta($pack_id, 'paypal_payment_plan_'.$paypal_status, true);


            if (!is_array($payment_plan) || $payment_plan=='') {
                wpestate_create_paypal_payment_plan($pack_id, $token);
                $payment_plan = get_post_meta($pack_id, 'paypal_payment_plan_'.$paypal_status, true);
            }

            $url        = $host.'/v1/payments/billing-plans/'.$payment_plan['id'];

            $json_resp  = wpestate_make_get_call($url, $token);




            if ($json_resp['state']!='ACTIVE') {
                wpestate_activate_paypal_payment_plan($json_resp['id'], $token);
            }

            echo wpestate_create_paypal_payment_agreement($pack_id, $token);
            die();
        }
    }


endif;







////////////////////////////////////////////////////////////////////////////////
/// Ajax  Filters
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_wpestate_ajax_filter_listings_search_on_main_map', 'wpestate_ajax_filter_listings_search_on_main_map');
add_action('wp_ajax_wpestate_ajax_filter_listings_search_on_main_map', 'wpestate_ajax_filter_listings_search_on_main_map');

if (!function_exists('wpestate_ajax_filter_listings_search_on_main_map')):

    function wpestate_ajax_filter_listings_search_on_main_map()
    {
        global $post;
        global $wpestate_options;
        global $wpestate_show_compare_only;
        global $wpestate_currency;
        global $wpestate_where_currency;
        $wpestate_show_compare_only =   'no';
        $current_user               =   wp_get_current_user();
        $userID                     =   $current_user->ID;
        $user_option                =   'favorites'.$userID;
        $wpestate_curent_fav        =   get_option($user_option);
        $wpestate_currency          =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency    =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $area_array                 =   '';
        $city_array                 =   '';
        $action_array               =   '';
        $categ_array                =   '';
        $allowed_html               =   array();
        $meta_query                 =   array();


        //////////////////////////////////////////////////////////////////////////////////////
        ///// city filters
        //////////////////////////////////////////////////////////////////////////////////////

        if (isset($_POST['city']) && trim($_POST['city']) != 'all' && $_POST['city']!='') {
            $taxcity[] = sanitize_title(wp_kses($_POST['city'], $allowed_html));
            $city_array = array(
                'taxonomy'  => 'property_city',
                'field'     => 'slug',
                'terms'     => $taxcity
            );
        }


        if (isset($_POST['area']) && trim($_POST['area']) != 'all' && $_POST['area']!='') {
            $taxarea[] = sanitize_title(wp_kses($_POST['area'], $allowed_html));
            $area_array = array(
                'taxonomy'  => 'property_area',
                'field'     => 'slug',
                'terms'     => $taxarea
            );
        }


        $guest_array=array();
        if (isset($_POST['guest_no'])  && is_numeric($_POST['guest_no'])) {
            $wpestate_guest_no                = intval($_POST['guest_no']);
            $guest_array['key']      = 'guest_no';
            $guest_array['value']    = $wpestate_guest_no;
            $guest_array['type']     = 'numeric';
            $guest_array['compare']  = '>=';
            $meta_query[]            = $guest_array;
        }

        $country_array=array();
        if (isset($_POST['country'])  && $_POST['country']!='') {
            $country                     =   sanitize_text_field(wp_kses($_POST['country'], $allowed_html));
            $country                     =   str_replace('-', ' ', $country);
            $country_array['key']        =   'property_country';
            $country_array['value']      =   $country;
            $country_array['type']       =   'CHAR';
            $country_array['compare']    =   'LIKE';
            $meta_query[]                =   $country_array;
        }

        if (isset($_POST['city']) && $_POST['city']=='' && isset($_POST['property_admin_area']) && $_POST['property_admin_area']!='') {
            $admin_area_array=array();
            $admin_area                     =   sanitize_text_field(wp_kses($_POST['property_admin_area'], $allowed_html));
            $admin_area                     =   str_replace(" ", "-", $admin_area);
            $admin_area                     =   str_replace("\'", "", $admin_area);
            $admin_area_array['key']        =   'property_admin_area';
            $admin_area_array['value']      =   $admin_area;
            $admin_area_array['type']       =   'CHAR';
            $admin_area_array['compare']    =   'LIKE';
            $meta_query[]                   =   $admin_area_array;
        }



        //////////////////////////////////////////////////////////////////////////////////////
        ///// calendar filters
        //////////////////////////////////////////////////////////////////////////////////////


        $wpestate_book_from      =   '';
        $wpestate_book_to        =   '';
        if (isset($_POST['check_in'])) {
            $wpestate_book_from  =   sanitize_text_field(wp_kses($_POST['check_in'], $allowed_html));
        }
        if (isset($_POST['check_out'])) {
            $wpestate_book_to    =   sanitize_text_field(wp_kses($_POST['check_out'], $allowed_html));
        }

        //////////////////////////////////////////////////////////////////////////////////////
        ///// order details
        //////////////////////////////////////////////////////////////////////////////////////





        $args = array(
            'post_type'         => 'estate_property',
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
            'meta_query'        => $meta_query,
            'tax_query'         => array(
                                    'relation' => 'AND',
                                    $categ_array,
                                    $action_array,
                                    $city_array,
                                    $area_array

                                    )
        );


        $prop_selection = new WP_Query($args);
        $counter    =   0;
        $markers    =   array();
        while ($prop_selection->have_posts()): $prop_selection->the_post();
        if ($wpestate_book_from!='' && $wpestate_book_to!='') {
            if (wpestate_check_booking_valability($wpestate_book_from, $wpestate_book_to, $post->ID)) {
                $counter++;
                $markers[]=wpestate_pin_unit_creation(get_the_ID(), $wpestate_currency, $wpestate_where_currency, $counter);
            }
        } else {
            $counter++;
            $markers[]=wpestate_pin_unit_creation(get_the_ID(), $wpestate_currency, $wpestate_where_currency, $counter);
        }


//        if( wpestate_check_booking_valability($wpestate_book_from,$wpestate_book_to,$post->ID) ){
//            $counter++;
//            $markers[]=wpestate_pin_unit_creation( get_the_ID(),$wpestate_currency,$wpestate_where_currency,$counter );
//
//        }
        endwhile;
        //print 'resutls '.$counter;
        echo json_encode(array('added'=>true,'arguments'=>json_encode($args), 'markers'=>json_encode($markers),'counter'=>$counter ));
        die();
    }

endif; // end   ajax_filter_listings  x
