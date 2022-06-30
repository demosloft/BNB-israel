<?php


if( !function_exists('wpestate_safe_rewite') ):
function wpestate_safe_rewite(){
    $rewrites   =   get_option('wp_estate_url_rewrites',true);
    $return     =   array();

    if(is_array($rewrites)){
        foreach($rewrites as $key=>$value){
            $return[$key] = str_replace('/', '', $value);
        }
    }

    return $return;

}
endif;



if( !function_exists('wpestate_recaptcha_path') ):
function wpestate_recaptcha_path($secret,$captcha){
    return "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".esc_html($_SERVER['REMOTE_ADDR']);
}
endif;


/*
 * Check recapthca 
 * 
 * 
 * 
 * */

if( !function_exists('wpestate_return_recapthca') ):
function wpestate_return_recapthca($secret,$captcha){

    $remoteip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $post_data = http_build_query(
        array(
            'secret' => $secret,
            'response' => $captcha,
            'remoteip' => $remoteip
        ), '', '&'
    );

    $options=array(
        // If site has SSL then
        'ssl'=>array(
            //    'cafile'            => '/path/to/cacert.pem',
            'verify_peer'       => true,
            'verify_peer_name'  => true,
        ),

       'http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $post_data
        )
    );

    $context = stream_context_create( $options );

    $result_json = file_get_contents( $url, false, $context );
    $resulting = json_decode($result_json, true);
    return $resulting;
}
endif;





if( !function_exists('wpestate_disable_filtering') ):
function wpestate_disable_filtering($filter, $function){
    remove_filter($filter, $function);
}
endif;

if( !function_exists('wpestate_disable_filtering2') ):
function wpestate_disable_filtering2($filter, $function,$order='',$params=''){
    remove_filter($filter, $function,$order,$params);
}
endif;


if( !function_exists('wpestate_return_filtered_by_order') ):
function wpestate_return_filtered_by_order($args){
    add_filter( 'posts_orderby', 'wpestate_my_order' );
    $prop_selection = new WP_Query($args);
    remove_filter( 'posts_orderby', 'wpestate_my_order' );
    return $prop_selection;
}
endif;



if( !function_exists('wpestate_search_by_title_only_filter') ):
function wpestate_search_by_title_only_filter($args){
    add_filter( 'posts_search', 'wpestate_search_by_title_only', 500, 2 );
    $prop_selection =   new WP_Query($args);
    remove_filter( 'posts_search', 'wpestate_search_by_title_only', 500 );
    return $prop_selection;
}
endif;



if( !function_exists('wpestate_secondary_lic_plugin') ):
function wpestate_secondary_lic_plugin(){

    $theme_activated    =   get_option('is_theme_activated','');
    if($theme_activated==='is_active'){
        return true;
    }else{
        return false;
    }
}
endif;

if( !function_exists('wpestate_general_country_list') ):
    function wpestate_general_country_list($selected){


        $countries = wprentals_return_country_array();

        $country_select='<select id="general_country" style="width: 200px;" name="general_country">';

        foreach($countries as $key=>$country){
            $country_select.='<option value="'.$key.'"';
            if($selected==$key){
                $country_select.='selected="selected"';
            }
            $country_select.='>'.$country.'</option>';
        }

        $country_select.='</select>';
        return $country_select;
    }
endif; // end   wpestate_general_country_list




if( !function_exists('wprentals_return_sidebar_array') ):
    function wprentals_return_sidebar_array(){
        $return_array=array();


        foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
            $return_array[$sidebar['id']]=ucwords($sidebar['name']);
        }
        return $return_array;
    }
endif;




if ( ! function_exists( 'wprentals_update_option' ) ):
    function wprentals_update_option( $theme_option,  $value ,$option = false) {
        global $wprentals_admin;
        if($option){
            $option_array   =   array($option=>$value);
            Redux::setOption('wprentals_admin',$theme_option, $option_array);
        }else{
            Redux::setOption('wprentals_admin',$theme_option, $value);
        }
    }
endif;



if ( ! function_exists( 'wprentals_get_option' ) ):
    function wprentals_get_option( $theme_option,  $option = false ,$in_case_not = false) {

        global $wprentals_admin;


        if($theme_option=='wpestate_currency'){
            $return = wpestate_reverse_convert_redux_wp_estate_multi_curr();
            return $return;
        }else if($theme_option=='wpestate_custom_fields_list'){
            $return = wpestate_reverse_convert_redux_wp_estate_custom_fields();
             usort($return, function($a, $b) {
                return $a['3'] - $b['3'];
            });
            return $return;
        }else if($theme_option=='wp_estate_property_page_header'){
            $return = wpestate_reverse_convert_redux_wp_estate_property_page_header();
            return $return;
        }else if($theme_option=='wp_estate_custom_listing_fields'){
            $return = wpestate_reverse_convert_redux_wp_estate_custom_listing_fields();
            return $return;
        }else if($theme_option=='wp_estate_custom_infobox_fields'){
            $return = wpestate_reverse_convert_redux_wpestate_convert_redux_wp_estate_custom_infobox_fields();
            return $return;
        }


        if( isset( $wprentals_admin[$theme_option]) && $wprentals_admin[$theme_option]!='' ){
            $return=$wprentals_admin[$theme_option];
            if($option && isset($wprentals_admin[$theme_option][$option])){
                $return = $wprentals_admin[$theme_option][$option];
            }
        }else{
            $return=$in_case_not;
        }

        return $return;

    }
endif;



//////////////////////////////////////////////////////////////////////////////
/// Ajax adv search contact function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_agent_contact_page', 'wpestate_ajax_agent_contact_page' );
add_action( 'wp_ajax_wpestate_ajax_agent_contact_page', 'wpestate_ajax_agent_contact_page' );

if( !function_exists('wpestate_ajax_agent_contact_page') ):

    function wpestate_ajax_agent_contact_page(){


        // check for POST vars
        $message='';
        $hasError = false;
        $allowed_html   =   array();
        $to_print='';
        if ( !wp_verify_nonce( $_POST['nonce'], 'ajax-property-contact')) {
            exit("No naughty business please");
        }
         $is_elementor_contact_builder =  intval($_POST['is_elementor']);

       if($is_elementor_contact_builder==0){
          if ( isset($_POST['name']) ) {
             if( trim($_POST['name']) =='' || trim($_POST['name']) ==esc_html__( 'Your Name','wprentals-core') ){
                 echo json_encode(array('sent'=>false, 'response'=>esc_html__( 'The name field is empty !','wprentals-core') ));
                 exit();
             }else {
                 $name = wp_kses( trim($_POST['name']),$allowed_html );
             }
          }


          //Check email
          if ( isset($_POST['email']) || trim($_POST['email']) ==esc_html__( 'Your Email','wprentals-core') ) {
                if( trim($_POST['email']) ==''){
                      echo json_encode(array('sent'=>false, 'response'=>esc_html__( 'The email field is empty','wprentals-core' ) ) );
                      exit();
                } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
                      echo json_encode(array('sent'=>false, 'response'=>esc_html__( 'The email doesn\'t look right !','wprentals-core') ) );
                      exit();
                } else {
                      $email = wp_kses( trim($_POST['email']),$allowed_html );
                }
          }

          //Check comments
          if ( isset($_POST['comment']) ) {
                if( trim($_POST['comment']) =='' || trim($_POST['comment']) ==esc_html__( 'Your Message','wprentals-core')){
                  echo json_encode(array('sent'=>false, 'response'=>esc_html__( 'Your message is empty !','wprentals-core') ) );
                  exit();
                }else {
                  $comment = wp_kses($_POST['comment'] ,$allowed_html );
                }
          }


          $message    .=  esc_html__('Client Name','wpresidence-core').": " . $name . PHP_EOL;
          $message    .=  esc_html__('Email','wpresidence-core').": " . $email . PHP_EOL;
          if(isset($_POST['website'])){
              $website = wp_kses( trim($_POST['website']),$allowed_html );
              $message    .=  esc_html__('Website','wpresidence-core').": " . $website . PHP_EOL;
          }

        }else{
            if ( isset($_POST['comment']) ) {
                $comment = wp_kses($_POST['comment'] ,$allowed_html );
                $comment = str_replace('/n',PHP_EOL,$comment);
            }
        }


        $subject =esc_html__( 'Contact form from ','wprentals-core') . esc_url( home_url('/') ) ;

        $receiver_email = esc_html( wprentals_get_option('wp_estate_email_adr', '') );;



        //  $message    .=  esc_html__('Phone','wpresidence-core').": " . $phone . PHP_EOL;
        //  $message    .=  esc_html__('Subject','wpresidence-core').": " . $subject . PHP_EOL;
        $message    .=  esc_html__('Message','wpresidence-core').": ".PHP_EOL." " . $comment. PHP_EOL;
        $message    .=  esc_html__('Message sent from contact page','wpresidence-core').$permalink. PHP_EOL;


        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";

        if(isset($_POST['elementor_email_subject'])){
          $subject        = sanitize_text_field( $_POST['elementor_email_subject']);
        }





        $mail = wp_mail($receiver_email, $subject, $message, $headers);

        $duplicate_email_adr        =   esc_html ( wprentals_get_option('wp_estate_duplicate_email_adr','') );
        if( $duplicate_email_adr!='' ){
            $message = $message.' '.__('Message was also sent to ','wprentals-core').$receiver_email;
            wp_mail($duplicate_email_adr, $subject, $message, $headers);
        }


        echo json_encode(array('sent'=>true, 'response'=>esc_html__( 'The message was sent !','wprentals-core') ) );
    die();
}

endif; // end   wpestate_ajax_agent_contact_form






/*
 *  Print invoice ajax function 
 * 
 * 
 * 
 * 
 * 
 **/


add_action( 'wp_ajax_ajax_create_print', 'ajax_create_print' );

  if( !function_exists('ajax_create_print') ):
  function ajax_create_print(){
    check_ajax_referer( 'wprentals_print_invoice_nonce', 'security' );
    if(!isset($_POST['invoice_id'])|| !is_numeric($_POST['invoice_id'])){
        exit('out pls1');
    }

    $invoice_id          = intval($_POST['invoice_id']);
    $the_post= get_post( $invoice_id);
    if($the_post->post_type!='wpestate_invoice' || $the_post->post_status!='publish'){
        exit('out pls2');
    }
    $title= esc_html__('Invoice','wprentals-core');

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    // end get agent details
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    print  '<html><head><title>'.$title.'</title><link href="'.get_stylesheet_uri().'" rel="stylesheet" type="text/css" />';


    if(is_child_theme()){
        print '<link href="'.get_template_directory_uri().'/style.css" rel="stylesheet" type="text/css" />';
    }

    if(is_rtl()){
        print '<link href="'.get_template_directory_uri().'/rtl.css" rel="stylesheet" type="text/css" />';
    }
    print '</head>';
    $protocol = is_ssl() ? 'https' : 'http';
    print  '<script src="'.$protocol.'://code.jquery.com/jquery-1.10.1.min.js"></script><script>$(window).load(function(){ print(); });</script>';
    print  '<body class="print_body" >';





     wpestate_super_invoice_details($invoice_id,'yes');

    print '</body></html>';
    die();
  }

endif;



/*
 *  Print trip ajax function 
 * 
 * 
 * 
 * 
 * 
 **/


add_action( 'wp_ajax_wprentals_ajax_create_print_trip', 'wprentals_ajax_create_print_trip' );

  if( !function_exists('wprentals_ajax_create_print_trip') ):
  function wprentals_ajax_create_print_trip(){
 
    $current_user = wp_get_current_user();
    $userID                         =   $current_user->ID;

        
    if(!isset($_POST['property_id'])|| !is_numeric($_POST['property_id'])){
        exit('out pls1');
    }
      if(!isset($_POST['booking_id'])|| !is_numeric($_POST['booking_id'])){
        exit('out pls1');
    }
    
    
    $bookid =intval($_POST['booking_id']);
    $property_id =intval($_POST['property_id']);
    
    
    $the_post= get_post( $bookid);
    $book_author=$the_post->post_author;
        
  
    if( $book_author!=$userID){
        exit('out pls');
    }

  
    
    $title= esc_html__('Your trip details','wprentals-core');

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    // end get agent details
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    print  '<html><head><title>'.$title.'</title><link href="'.get_stylesheet_uri().'" rel="stylesheet" type="text/css" />';


    if(is_child_theme()){
        print '<link href="'.get_template_directory_uri().'/style.css" rel="stylesheet" type="text/css" />';
    }

    if(is_rtl()){
        print '<link href="'.get_template_directory_uri().'/rtl.css" rel="stylesheet" type="text/css" />';
    }
    print '</head>';
    $protocol = is_ssl() ? 'https' : 'http';
    print  '<script src="'.$protocol.'://code.jquery.com/jquery-1.10.1.min.js"></script><script>$(window).load(function(){ print(); });</script>';
    print  '<body class="print_body" >';


    wpestate_generate_trip_details($property_id,'',$bookid,$mode='');

    print '</body></html>';
    die();
  }

endif;








if ( ! function_exists( 'wpestate_admin_bar_menu' ) ) {
	function wpestate_admin_bar_menu() {
            global $wp_admin_bar;
            $theme_data = wp_get_theme();


            if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
                return;
            }



             $wp_admin_bar->add_menu(array(
                    'id' => 'clear_cache',
                    'title' => __( 'Clear WpRentals Cache', 'wprentals-core' ),
                    'href' => wp_nonce_url( esc_url( admin_url( 'admin-post.php?action=wpestate_purge_cache') ) , 'theme_purge_cache' ),
            ));

	}
}
add_action( 'admin_bar_menu', 'wpestate_admin_bar_menu', 100 );


////////////////////////////////////////////////////////////////////////////////
/// Add new profile fields
////////////////////////////////////////////////////////////////////////////////

add_filter('user_contactmethods', 'wpestate_modify_contact_methods');
if (!function_exists('wpestate_modify_contact_methods')):

    function wpestate_modify_contact_methods($profile_fields) {

        // Add new fields
        $profile_fields['facebook']                     = esc_html__('Facebook','wprentals-core');
        $profile_fields['twitter']                      = esc_html__('Twitter','wprentals-core');
        $profile_fields['linkedin']                     = esc_html__('Linkedin','wprentals-core');
        $profile_fields['pinterest']                    = esc_html__('Pinterest','wprentals-core');
        $profile_fields['instagram']                    = esc_html__('Instagram','wprentals-core');
        $profile_fields['youtube']                      = esc_html__('Youtube','wprentals-core');
        $profile_fields['phone']                        = esc_html__('Phone','wprentals-core');
        $profile_fields['mobile']                       = esc_html__('Mobile','wprentals-core');
        $profile_fields['check_phone_valid']            = esc_html__('Mobile Verified','wprentals-core');
        $profile_fields['skype']                        = esc_html__('Skype','wprentals-core');
        $profile_fields['website']                      = esc_html__('Website','wprentals-core');
        $profile_fields['title']                        = esc_html__('Title/Position','wprentals-core');
        $profile_fields['custom_picture']               = esc_html__('Picture Url','wprentals-core');
        $profile_fields['small_custom_picture']         = esc_html__('Small Picture Url','wprentals-core');
        $profile_fields['package_id']                   = esc_html__('Package Id','wprentals-core');
        $profile_fields['package_activation']           = esc_html__('Package Activation','wprentals-core');
        $profile_fields['package_listings']             = esc_html__('Listings available','wprentals-core');
        $profile_fields['package_featured_listings']    = esc_html__('Featured Listings available','wprentals-core');
        $profile_fields['paypal_agreement']             = esc_html__('Paypal Recurring Profile - rest api','wprentals-core');
        $profile_fields['profile_id']                   = esc_html__('Paypal Recurring Profile','wprentals-core');
        $profile_fields['user_agent_id']                = esc_html__('User Owner Id','wprentals-core');
        $profile_fields['stripe']                       = esc_html__( 'Stripe Consumer Profile','wprentals-core');
        $profile_fields['stripe_subscription_id']       = esc_html__( 'Stripe Subscription Id','wprentals-core');
        $profile_fields['has_stripe_recurring']         = esc_html__( 'Has Recurring Stripe ','wprentals-core');
        $profile_fields['i_speak']                      = esc_html__('I Speak','wprentals-core');
        $profile_fields['live_in']                      = esc_html__('Live In','wprentals-core');
        $profile_fields['payment_info']                 = esc_html__('Payment Info','wprentals-core');
        $profile_fields['user_type']                    = esc_html__('User Type(0-can rent and book / 1 can only book)','wprentals-core');
        return $profile_fields;
    }

endif; // end   wpestate_modify_contact_methods


function wpestate_core_add_to_footer(){
    $ga = esc_html(wprentals_get_option('wp_estate_google_analytics_code', ''));
    if ($ga != '') { ?>

    <script>
        //<![CDATA[
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php echo esc_html($ga); ?>', '<?php     echo esc_html($_SERVER['SERVER_NAME']); ?>');
      ga('send', 'pageview');
    //]]>
    </script>


    <?php }
}

if( !function_exists('wprentals_return_country_array') ):
function wprentals_return_country_array(){
    $countries = array(     'Afghanistan'           => esc_html__('Afghanistan','wprentals'),
                            'Albania'               => esc_html__('Albania','wprentals'),
                            'Algeria'               => esc_html__('Algeria','wprentals'),
                            'American Samoa'        => esc_html__('American Samoa','wprentals'),
                            'Andorra'               => esc_html__('Andorra','wprentals'),
                            'Angola'                => esc_html__('Angola','wprentals'),
                            'Anguilla'              => esc_html__('Anguilla','wprentals'),
                            'Antarctica'            => esc_html__('Antarctica','wprentals'),
                            'Antigua and Barbuda'   => esc_html__('Antigua and Barbuda','wprentals'),
                            'Argentina'             => esc_html__('Argentina','wprentals'),
                            'Armenia'               => esc_html__('Armenia','wprentals'),
                            'Aruba'                 => esc_html__('Aruba','wprentals'),
                            'Australia'             => esc_html__('Australia','wprentals'),
                            'Austria'               => esc_html__('Austria','wprentals'),
                            'Azerbaijan'            => esc_html__('Azerbaijan','wprentals'),
                            'Bahamas'               => esc_html__('Bahamas','wprentals'),
                            'Bahrain'               => esc_html__('Bahrain','wprentals'),
                            'Bangladesh'            => esc_html__('Bangladesh','wprentals'),
                            'Barbados'              => esc_html__('Barbados','wprentals'),
                            'Belarus'               => esc_html__('Belarus','wprentals'),
                            'Belgium'               => esc_html__('Belgium','wprentals'),
                            'Belize'                => esc_html__('Belize','wprentals'),
                            'Benin'                 => esc_html__('Benin','wprentals'),
                            'Bermuda'               => esc_html__('Bermuda','wprentals'),
                            'Bhutan'                => esc_html__('Bhutan','wprentals'),
                            'Bolivia'               => esc_html__('Bolivia','wprentals'),
                            'Bosnia and Herzegowina'=> esc_html__('Bosnia and Herzegowina','wprentals'),
                            'Botswana'              => esc_html__('Botswana','wprentals'),
                            'Bouvet Island'         => esc_html__('Bouvet Island','wprentals'),
                            'Brazil'                => esc_html__('Brazil','wprentals'),
                            'British Indian Ocean Territory'=> esc_html__('British Indian Ocean Territory','wprentals'),
                            'Brunei Darussalam'     => esc_html__('Brunei Darussalam','wprentals'),
                            'Bulgaria'              => esc_html__('Bulgaria','wprentals'),
                            'Burkina Faso'          => esc_html__('Burkina Faso','wprentals'),
                            'Burundi'               => esc_html__('Burundi','wprentals'),
                            'Cambodia'              => esc_html__('Cambodia','wprentals'),
                            'Cameroon'              => esc_html__('Cameroon','wprentals'),
                            'Canada'                => esc_html__('Canada','wprentals'),
                            'Cape Verde'            => esc_html__('Cape Verde','wprentals'),
                            'Cayman Islands'        => esc_html__('Cayman Islands','wprentals'),
                            'Central African Republic'  => esc_html__('Central African Republic','wprentals'),
                            'Chad'                  => esc_html__('Chad','wprentals'),
                            'Chile'                 => esc_html__('Chile','wprentals'),
                            'China'                 => esc_html__('China','wprentals'),
                            'Christmas Island'      => esc_html__('Christmas Island','wprentals'),
                            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands','wprentals'),
                            'Colombia'              => esc_html__('Colombia','wprentals'),
                            'Comoros'               => esc_html__('Comoros','wprentals'),
                            'Congo'                 => esc_html__('Congo','wprentals'),
                            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the','wprentals'),
                            'Cook Islands'          => esc_html__('Cook Islands','wprentals'),
                            'Costa Rica'            => esc_html__('Costa Rica','wprentals'),
                            'Cote dIvoire'          => esc_html__('Cote dIvoire','wprentals'),
                            'Croatia'               => esc_html__('Croatia','wprentals'),
                            'Cuba'                  => esc_html__('Cuba','wprentals'),
                            'Curacao'               => esc_html__('Curacao','wprentals'),
                            'Cyprus'                => esc_html__('Cyprus','wprentals'),
                            'Czech Republic'        => esc_html__('Czech Republic','wprentals'),
                            'Denmark'               => esc_html__('Denmark','wprentals'),
                            'Djibouti'              => esc_html__('Djibouti','wprentals'),
                            'Dominica'              => esc_html__('Dominica','wprentals'),
                            'Dominican Republic'    => esc_html__('Dominican Republic','wprentals'),
                            'East Timor'            => esc_html__('East Timor','wprentals'),
                            'Ecuador'               => esc_html__('Ecuador','wprentals'),
                            'Egypt'                 => esc_html__('Egypt','wprentals'),
                            'El Salvador'           => esc_html__('El Salvador','wprentals'),
                            'Equatorial Guinea'     => esc_html__('Equatorial Guinea','wprentals'),
                            'Eritrea'               => esc_html__('Eritrea','wprentals'),
                            'Estonia'               => esc_html__('Estonia','wprentals'),
                            'Ethiopia'              => esc_html__('Ethiopia','wprentals'),
                            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)','wprentals'),
                            'Faroe Islands'         => esc_html__('Faroe Islands','wprentals'),
                            'Fiji'                  => esc_html__('Fiji','wprentals'),
                            'Finland'               => esc_html__('Finland','wprentals'),
                            'France'                => esc_html__('France','wprentals'),
                            'France Metropolitan'   => esc_html__('France Metropolitan','wprentals'),
                            'French Guiana'         => esc_html__('French Guiana','wprentals'),
                            'French Polynesia'      => esc_html__('French Polynesia','wprentals'),
                            'French Southern Territories' => esc_html__('French Southern Territories','wprentals'),
                            'Gabon'                 => esc_html__('Gabon','wprentals'),
                            'Gambia'                => esc_html__('Gambia','wprentals'),
                            'Georgia'               => esc_html__('Georgia','wprentals'),
                            'Germany'               => esc_html__('Germany','wprentals'),
                            'Ghana'                 => esc_html__('Ghana','wprentals'),
                            'Gibraltar'             => esc_html__('Gibraltar','wprentals'),
                            'Greece'                => esc_html__('Greece','wprentals'),
                            'Greenland'             => esc_html__('Greenland','wprentals'),
                            'Grenada'               => esc_html__('Grenada','wprentals'),
                            'Guadeloupe'            => esc_html__('Guadeloupe','wprentals'),
                            'Guam'                  => esc_html__('Guam','wprentals'),
                            'Guatemala'             => esc_html__('Guatemala','wprentals'),
                            'Guinea'                => esc_html__('Guinea','wprentals'),
                            'Guinea-Bissau'         => esc_html__('Guinea-Bissau','wprentals'),
                            'Guyana'                => esc_html__('Guyana','wprentals'),
                            'Haiti'                 => esc_html__('Haiti','wprentals'),
                            'Heard and Mc Donald Islands'  => esc_html__('Heard and Mc Donald Islands','wprentals'),
                            'Holy See (Vatican City State)'=> esc_html__('Holy See (Vatican City State)','wprentals'),
                            'Honduras'              => esc_html__('Honduras','wprentals'),
                            'Hong Kong'             => esc_html__('Hong Kong','wprentals'),
                            'Hungary'               => esc_html__('Hungary','wprentals'),
                            'Iceland'               => esc_html__('Iceland','wprentals'),
                            'India'                 => esc_html__('India','wprentals'),
                            'Indonesia'             => esc_html__('Indonesia','wprentals'),
                            'Iran (Islamic Republic of)'  => esc_html__('Iran (Islamic Republic of)','wprentals'),
                            'Iraq'                  => esc_html__('Iraq','wprentals'),
                            'Ireland'               => esc_html__('Ireland','wprentals'),
                            'Israel'                => esc_html__('Israel','wprentals'),
                            'Italy'                 => esc_html__('Italy','wprentals'),
                            'Island of Saba'        => esc_html__('Island of Saba','wprentals'),
                            'Jamaica'               => esc_html__('Jamaica','wprentals'),
                            'Japan'                 => esc_html__('Japan','wprentals'),
                            'Jordan'                => esc_html__('Jordan','wprentals'),
                            'Kazakhstan'            => esc_html__('Kazakhstan','wprentals'),
                            'Kenya'                 => esc_html__('Kenya','wprentals'),
                            'Kiribati'              => esc_html__('Kiribati','wprentals'),
                            'Korea, Democratic People Republic of'  => esc_html__('Korea, Democratic People Republic of','wprentals'),
                            'Korea, Republic of'    => esc_html__('Korea, Republic of','wprentals'),
                            'Kosovo'                => esc_html__('Kosovo', 'wprentals'),
                            'Kuwait'                => esc_html__('Kuwait','wprentals'),
                            'Kyrgyzstan'            => esc_html__('Kyrgyzstan','wprentals'),
                            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic','wprentals'),
                            'Latvia'                => esc_html__('Latvia','wprentals'),
                            'Lebanon'               => esc_html__('Lebanon','wprentals'),
                            'Lesotho'               => esc_html__('Lesotho','wprentals'),
                            'Liberia'               => esc_html__('Liberia','wprentals'),
                            'Libyan Arab Jamahiriya'=> esc_html__('Libyan Arab Jamahiriya','wprentals'),
                            'Liechtenstein'         => esc_html__('Liechtenstein','wprentals'),
                            'Lithuania'             => esc_html__('Lithuania','wprentals'),
                            'Luxembourg'            => esc_html__('Luxembourg','wprentals'),
                            'Macau'                 => esc_html__('Macau','wprentals'),
                            'Macedonia, The Former Yugoslav Republic of'    => esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals'),
                            'Madagascar'            => esc_html__('Madagascar','wprentals'),
                            'Malawi'                => esc_html__('Malawi','wprentals'),
                            'Malaysia'              => esc_html__('Malaysia','wprentals'),
                            'Maldives'              => esc_html__('Maldives','wprentals'),
                            'Mali'                  => esc_html__('Mali','wprentals'),
                            'Malta'                 => esc_html__('Malta','wprentals'),
                            'Marshall Islands'      => esc_html__('Marshall Islands','wprentals'),
                            'Martinique'            => esc_html__('Martinique','wprentals'),
                            'Mauritania'            => esc_html__('Mauritania','wprentals'),
                            'Mauritius'             => esc_html__('Mauritius','wprentals'),
                            'Mayotte'               => esc_html__('Mayotte','wprentals'),
                            'Mexico'                => esc_html__('Mexico','wprentals'),
                            'Micronesia, Federated States of'    => esc_html__('Micronesia, Federated States of','wprentals'),
                            'Moldova, Republic of'  => esc_html__('Moldova, Republic of','wprentals'),
                            'Monaco'                => esc_html__('Monaco','wprentals'),
                            'Mongolia'              => esc_html__('Mongolia','wprentals'),
                            'Montserrat'            => esc_html__('Montserrat','wprentals'),
                            'Morocco'               => esc_html__('Morocco','wprentals'),
                            'Mozambique'            => esc_html__('Mozambique','wprentals'),
                            'Montenegro'            => esc_html__('Montenegro','wprentals'),
                            'Myanmar'               => esc_html__('Myanmar','wprentals'),
                            'Namibia'               => esc_html__('Namibia','wprentals'),
                            'Nauru'                 => esc_html__('Nauru','wprentals'),
                            'Nepal'                 => esc_html__('Nepal','wprentals'),
                            'Netherlands'           => esc_html__('Netherlands','wprentals'),
                            'Netherlands Antilles'  => esc_html__('Netherlands Antilles','wprentals'),
                            'New Caledonia'         => esc_html__('New Caledonia','wprentals'),
                            'New Zealand'           => esc_html__('New Zealand','wprentals'),
                            'Nicaragua'             => esc_html__('Nicaragua','wprentals'),
                            'Niger'                 => esc_html__('Niger','wprentals'),
                            'Nigeria'               => esc_html__('Nigeria','wprentals'),
                            'Niue'                  => esc_html__('Niue','wprentals'),
                            'Norfolk Island'        => esc_html__('Norfolk Island','wprentals'),
                            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands','wprentals'),
                            'Norway'                => esc_html__('Norway','wprentals'),
                            'Oman'                  => esc_html__('Oman','wprentals'),
                            'Pakistan'              => esc_html__('Pakistan','wprentals'),
                            'Palau'                 => esc_html__('Palau','wprentals'),
                            'Panama'                => esc_html__('Panama','wprentals'),
                            'Papua New Guinea'      => esc_html__('Papua New Guinea','wprentals'),
                            'Paraguay'              => esc_html__('Paraguay','wprentals'),
                            'Peru'                  => esc_html__('Peru','wprentals'),
                            'Philippines'           => esc_html__('Philippines','wprentals'),
                            'Pitcairn'              => esc_html__('Pitcairn','wprentals'),
                            'Poland'                => esc_html__('Poland','wprentals'),
                            'Portugal'              => esc_html__('Portugal','wprentals'),
                            'Puerto Rico'           => esc_html__('Puerto Rico','wprentals'),
                            'Qatar'                 => esc_html__('Qatar','wprentals'),
                            'Reunion'               => esc_html__('Reunion','wprentals'),
                            'Romania'               => esc_html__('Romania','wprentals'),
                            'Russian Federation'    => esc_html__('Russian Federation','wprentals'),
                            'Rwanda'                => esc_html__('Rwanda','wprentals'),
                            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis','wprentals'),
                            'Saint Lucia'           => esc_html__('Saint Lucia','wprentals'),
                            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines','wprentals'),
                            'Saint Barthélemy'      => esc_html__('Saint Barthélemy','wprentals'),
                            'Saint Martin'          => esc_html__('Saint Martin','wprentals'),
                            'Sint Maarten'          => esc_html__('Sint Maarten','wprentals'),
                            'Samoa'                 => esc_html__('Samoa','wprentals'),
                            'San Marino'            => esc_html__('San Marino','wprentals'),
                            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe','wprentals'),
                            'Saudi Arabia'          => esc_html__('Saudi Arabia','wprentals'),
                            'Serbia'                => esc_html__('Serbia','wprentals'),
                            'Senegal'               => esc_html__('Senegal','wprentals'),
                            'Seychelles'            => esc_html__('Seychelles','wprentals'),
                            'Sierra Leone'          => esc_html__('Sierra Leone','wprentals'),
                            'Singapore'             => esc_html__('Singapore','wprentals'),
                            'Slovakia (Slovak Republic)'=> esc_html__('Slovakia (Slovak Republic)','wprentals'),
                            'Slovenia'              => esc_html__('Slovenia','wprentals'),
                            'Solomon Islands'       => esc_html__('Solomon Islands','wprentals'),
                            'Somalia'               => esc_html__('Somalia','wprentals'),
                            'South Africa'          => esc_html__('South Africa','wprentals'),
                            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands','wprentals'),
                            'Spain'                 => esc_html__('Spain','wprentals'),
                            'Sri Lanka'             => esc_html__('Sri Lanka','wprentals'),
                            'St. Helena'            => esc_html__('St. Helena','wprentals'),
                            'St. Pierre and Miquelon'=> esc_html__('St. Pierre and Miquelon','wprentals'),
                            'Sudan'                 => esc_html__('Sudan','wprentals'),
                            'Suriname'              => esc_html__('Suriname','wprentals'),
                            'Svalbard and Jan Mayen Islands'    => esc_html__('Svalbard and Jan Mayen Islands','wprentals'),
                            'Swaziland'             => esc_html__('Swaziland','wprentals'),
                            'Sweden'                => esc_html__('Sweden','wprentals'),
                            'Switzerland'           => esc_html__('Switzerland','wprentals'),
                            'Syrian Arab Republic'  => esc_html__('Syrian Arab Republic','wprentals'),
                            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China','wprentals'),
                            'Tajikistan'            => esc_html__('Tajikistan','wprentals'),
                            'Tanzania, United Republic of'=> esc_html__('Tanzania, United Republic of','wprentals'),
                            'Thailand'              => esc_html__('Thailand','wprentals'),
                            'Togo'                  => esc_html__('Togo','wprentals'),
                            'Tokelau'               => esc_html__('Tokelau','wprentals'),
                            'Tonga'                 => esc_html__('Tonga','wprentals'),
                            'Trinidad and Tobago'   => esc_html__('Trinidad and Tobago','wprentals'),
                            'Tunisia'               => esc_html__('Tunisia','wprentals'),
                            'Turkey'                => esc_html__('Turkey','wprentals'),
                            'Turkmenistan'          => esc_html__('Turkmenistan','wprentals'),
                            'Turks and Caicos Islands'  => esc_html__('Turks and Caicos Islands','wprentals'),
                            'Tuvalu'                => esc_html__('Tuvalu','wprentals'),
                            'Uganda'                => esc_html__('Uganda','wprentals'),
                            'Ukraine'               => esc_html__('Ukraine','wprentals'),
                            'United Arab Emirates'  => esc_html__('United Arab Emirates','wprentals'),
                            'United Kingdom'        => esc_html__('United Kingdom','wprentals'),
                            'United States'         => esc_html__('United States','wprentals'),
                            'United States Minor Outlying Islands'  => esc_html__('United States Minor Outlying Islands','wprentals'),
                            'Uruguay'               => esc_html__('Uruguay','wprentals'),
                            'Uzbekistan'            => esc_html__('Uzbekistan','wprentals'),
                            'Vanuatu'               => esc_html__('Vanuatu','wprentals'),
                            'Venezuela'             => esc_html__('Venezuela','wprentals'),
                            'Vietnam'               => esc_html__('Vietnam','wprentals'),
                            'Virgin Islands (British)'=> esc_html__('Virgin Islands (British)','wprentals'),
                            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)','wprentals'),
                            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands','wprentals'),
                            'Western Sahara'        => esc_html__('Western Sahara','wprentals'),
                            'Yemen'                 => esc_html__('Yemen','wprentals'),
                            'Yugoslavia'            => esc_html__('Yugoslavia','wprentals'),
                            'Zambia'                => esc_html__('Zambia','wprentals'),
                            'Zimbabwe'              => esc_html__('Zimbabwe','wprentals')
    );
    return $countries;

}
endif;









if(!function_exists('wprentals_return_google_fonts')):
function wprentals_return_google_fonts(){
    $google_fonts_array = array(
            "Abel" => "Abel",
            "Abril Fatface" => "Abril Fatface",
            "Aclonica" => "Aclonica",
            "Acme" => "Acme",
            "Actor" => "Actor",
            "Adamina" => "Adamina",
            "Advent Pro" => "Advent Pro",
            "Aguafina Script" => "Aguafina Script",
            "Aladin" => "Aladin",
            "Aldrich" => "Aldrich",
            "Alegreya" => "Alegreya",
            "Alegreya SC" => "Alegreya SC",
            "Alex Brush" => "Alex Brush",
            "Alfa Slab One" => "Alfa Slab One",
            "Alice" => "Alice",
            "Alike" => "Alike",
            "Alike Angular" => "Alike Angular",
            "Allan" => "Allan",
            "Allerta" => "Allerta",
            "Allerta Stencil" => "Allerta Stencil",
            "Allura" => "Allura",
            "Almendra" => "Almendra",
            "Almendra SC" => "Almendra SC",
            "Amaranth" => "Amaranth",
            "Amatic SC" => "Amatic SC",
            "Amethysta" => "Amethysta",
            "Andada" => "Andada",
            "Andika" => "Andika",
            "Angkor" => "Angkor",
            "Annie Use Your Telescope" => "Annie Use Your Telescope",
            "Anonymous Pro" => "Anonymous Pro",
            "Antic" => "Antic",
            "Antic Didone" => "Antic Didone",
            "Antic Slab" => "Antic Slab",
            "Anton" => "Anton",
            "Arapey" => "Arapey",
            "Arbutus" => "Arbutus",
            "Architects Daughter" => "Architects Daughter",
            "Arimo" => "Arimo",
            "Arizonia" => "Arizonia",
            "Armata" => "Armata",
            "Artifika" => "Artifika",
            "Arvo" => "Arvo",
            "Asap" => "Asap",
            "Asset" => "Asset",
            "Astloch" => "Astloch",
            "Asul" => "Asul",
            "Atomic Age" => "Atomic Age",
            "Aubrey" => "Aubrey",
            "Audiowide" => "Audiowide",
            "Average" => "Average",
            "Averia Gruesa Libre" => "Averia Gruesa Libre",
            "Averia Libre" => "Averia Libre",
            "Averia Sans Libre" => "Averia Sans Libre",
            "Averia Serif Libre" => "Averia Serif Libre",
            "Bad Script" => "Bad Script",
            "Balthazar" => "Balthazar",
            "Bangers" => "Bangers",
            "Basic" => "Basic",
            "Battambang" => "Battambang",
            "Baumans" => "Baumans",
            "Bayon" => "Bayon",
            "Belgrano" => "Belgrano",
            "Belleza" => "Belleza",
            "Bentham" => "Bentham",
            "Berkshire Swash" => "Berkshire Swash",
            "Bevan" => "Bevan",
            "Bigshot One" => "Bigshot One",
            "Bilbo" => "Bilbo",
            "Bilbo Swash Caps" => "Bilbo Swash Caps",
            "Bitter" => "Bitter",
            "Black Ops One" => "Black Ops One",
            "Bokor" => "Bokor",
            "Bonbon" => "Bonbon",
            "Boogaloo" => "Boogaloo",
            "Bowlby One" => "Bowlby One",
            "Bowlby One SC" => "Bowlby One SC",
            "Brawler" => "Brawler",
            "Bree Serif" => "Bree Serif",
            "Bubblegum Sans" => "Bubblegum Sans",
            "Buda" => "Buda",
            "Buenard" => "Buenard",
            "Butcherman" => "Butcherman",
            "Butterfly Kids" => "Butterfly Kids",
            "Cabin" => "Cabin",
            "Cabin Condensed" => "Cabin Condensed",
            "Cabin Sketch" => "Cabin Sketch",
            "Caesar Dressing" => "Caesar Dressing",
            "Cagliostro" => "Cagliostro",
            "Calligraffitti" => "Calligraffitti",
            "Cambo" => "Cambo",
            "Candal" => "Candal",
            "Cantarell" => "Cantarell",
            "Cantata One" => "Cantata One",
            "Cardo" => "Cardo",
            "Carme" => "Carme",
            "Carter One" => "Carter One",
            "Caudex" => "Caudex",
            "Cedarville Cursive" => "Cedarville Cursive",
            "Ceviche One" => "Ceviche One",
            "Changa One" => "Changa One",
            "Chango" => "Chango",
            "Chau Philomene One" => "Chau Philomene One",
            "Chelsea Market" => "Chelsea Market",
            "Chenla" => "Chenla",
            "Cherry Cream Soda" => "Cherry Cream Soda",
            "Chewy" => "Chewy",
            "Chicle" => "Chicle",
            "Chivo" => "Chivo",
            "Coda" => "Coda",
            "Coda Caption" => "Coda Caption",
            "Codystar" => "Codystar",
            "Comfortaa" => "Comfortaa",
            "Coming Soon" => "Coming Soon",
            "Concert One" => "Concert One",
            "Condiment" => "Condiment",
            "Content" => "Content",
            "Contrail One" => "Contrail One",
            "Convergence" => "Convergence",
            "Cookie" => "Cookie",
            "Copse" => "Copse",
            "Corben" => "Corben",
            "Cousine" => "Cousine",
            "Coustard" => "Coustard",
            "Covered By Your Grace" => "Covered By Your Grace",
            "Crafty Girls" => "Crafty Girls",
            "Creepster" => "Creepster",
            "Crete Round" => "Crete Round",
            "Crimson Text" => "Crimson Text",
            "Crushed" => "Crushed",
            "Cuprum" => "Cuprum",
            "Cutive" => "Cutive",
            "Damion" => "Damion",
            "Dancing Script" => "Dancing Script",
            "Dangrek" => "Dangrek",
            "Dawning of a New Day" => "Dawning of a New Day",
            "Days One" => "Days One",
            "Delius" => "Delius",
            "Delius Swash Caps" => "Delius Swash Caps",
            "Delius Unicase" => "Delius Unicase",
            "Della Respira" => "Della Respira",
            "Devonshire" => "Devonshire",
            "Didact Gothic" => "Didact Gothic",
            "Diplomata" => "Diplomata",
            "Diplomata SC" => "Diplomata SC",
            "Doppio One" => "Doppio One",
            "Dorsa" => "Dorsa",
            "Dosis" => "Dosis",
            "Dr Sugiyama" => "Dr Sugiyama",
            "Droid Sans" => "Droid Sans",
            "Droid Sans Mono" => "Droid Sans Mono",
            "Droid Serif" => "Droid Serif",
            "Duru Sans" => "Duru Sans",
            "Dynalight" => "Dynalight",
            "EB Garamond" => "EB Garamond",
            "Eater" => "Eater",
            "Economica" => "Economica",
            "Electrolize" => "Electrolize",
            "Emblema One" => "Emblema One",
            "Emilys Candy" => "Emilys Candy",
            "Engagement" => "Engagement",
            "Enriqueta" => "Enriqueta",
            "Erica One" => "Erica One",
            "Esteban" => "Esteban",
            "Euphoria Script" => "Euphoria Script",
            "Ewert" => "Ewert",
            "Exo" => "Exo",
            "Expletus Sans" => "Expletus Sans",
            "Fanwood Text" => "Fanwood Text",
            "Fascinate" => "Fascinate",
            "Fascinate Inline" => "Fascinate Inline",
            "Federant" => "Federant",
            "Federo" => "Federo",
            "Felipa" => "Felipa",
            "Fjord One" => "Fjord One",
            "Flamenco" => "Flamenco",
            "Flavors" => "Flavors",
            "Fondamento" => "Fondamento",
            "Fontdiner Swanky" => "Fontdiner Swanky",
            "Forum" => "Forum",
            "Francois One" => "Francois One",
            "Fredericka the Great" => "Fredericka the Great",
            "Fredoka One" => "Fredoka One",
            "Freehand" => "Freehand",
            "Fresca" => "Fresca",
            "Frijole" => "Frijole",
            "Fugaz One" => "Fugaz One",
            "GFS Didot" => "GFS Didot",
            "GFS Neohellenic" => "GFS Neohellenic",
            "Galdeano" => "Galdeano",
            "Gentium Basic" => "Gentium Basic",
            "Gentium Book Basic" => "Gentium Book Basic",
            "Geo" => "Geo",
            "Geostar" => "Geostar",
            "Geostar Fill" => "Geostar Fill",
            "Germania One" => "Germania One",
            "Give You Glory" => "Give You Glory",
            "Glass Antiqua" => "Glass Antiqua",
            "Glegoo" => "Glegoo",
            "Gloria Hallelujah" => "Gloria Hallelujah",
            "Goblin One" => "Goblin One",
            "Gochi Hand" => "Gochi Hand",
            "Gorditas" => "Gorditas",
            "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
            "Graduate" => "Graduate",
            "Gravitas One" => "Gravitas One",
            "Great Vibes" => "Great Vibes",
            "Gruppo" => "Gruppo",
            "Gudea" => "Gudea",
            "Habibi" => "Habibi",
            "Hammersmith One" => "Hammersmith One",
            "Handlee" => "Handlee",
            "Hanuman" => "Hanuman",
            "Happy Monkey" => "Happy Monkey",
            "Henny Penny" => "Henny Penny",
            "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
            "Holtwood One SC" => "Holtwood One SC",
            "Homemade Apple" => "Homemade Apple",
            "Homenaje" => "Homenaje",
            "IM Fell DW Pica" => "IM Fell DW Pica",
            "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
            "IM Fell Double Pica" => "IM Fell Double Pica",
            "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
            "IM Fell English" => "IM Fell English",
            "IM Fell English SC" => "IM Fell English SC",
            "IM Fell French Canon" => "IM Fell French Canon",
            "IM Fell French Canon SC" => "IM Fell French Canon SC",
            "IM Fell Great Primer" => "IM Fell Great Primer",
            "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
            "Iceberg" => "Iceberg",
            "Iceland" => "Iceland",
            "Imprima" => "Imprima",
            "Inconsolata" => "Inconsolata",
            "Inder" => "Inder",
            "Indie Flower" => "Indie Flower",
            "Inika" => "Inika",
            "Irish Grover" => "Irish Grover",
            "Istok Web" => "Istok Web",
            "Italiana" => "Italiana",
            "Italianno" => "Italianno",
            "Jim Nightshade" => "Jim Nightshade",
            "Jockey One" => "Jockey One",
            "Jolly Lodger" => "Jolly Lodger",
            "Josefin Sans" => "Josefin Sans",
            "Josefin Slab" => "Josefin Slab",
            "Judson" => "Judson",
            "Julee" => "Julee",
            "Junge" => "Junge",
            "Jura" => "Jura",
            "Just Another Hand" => "Just Another Hand",
            "Just Me Again Down Here" => "Just Me Again Down Here",
            "Kameron" => "Kameron",
            "Karla" => "Karla",
            "Kaushan Script" => "Kaushan Script",
            "Kelly Slab" => "Kelly Slab",
            "Kenia" => "Kenia",
            "Khmer" => "Khmer",
            "Knewave" => "Knewave",
            "Kotta One" => "Kotta One",
            "Koulen" => "Koulen",
            "Kranky" => "Kranky",
            "Kreon" => "Kreon",
            "Kristi" => "Kristi",
            "Krona One" => "Krona One",
            "La Belle Aurore" => "La Belle Aurore",
            "Lancelot" => "Lancelot",
            "Lato" => "Lato",
            "League Script" => "League Script",
            "Leckerli One" => "Leckerli One",
            "Ledger" => "Ledger",
            "Lekton" => "Lekton",
            "Lemon" => "Lemon",
            "Lilita One" => "Lilita One",
            "Limelight" => "Limelight",
            "Linden Hill" => "Linden Hill",
            "Lobster" => "Lobster",
            "Lobster Two" => "Lobster Two",
            "Londrina Outline" => "Londrina Outline",
            "Londrina Shadow" => "Londrina Shadow",
            "Londrina Sketch" => "Londrina Sketch",
            "Londrina Solid" => "Londrina Solid",
            "Lora" => "Lora",
            "Love Ya Like A Sister" => "Love Ya Like A Sister",
            "Loved by the King" => "Loved by the King",
            "Lovers Quarrel" => "Lovers Quarrel",
            "Luckiest Guy" => "Luckiest Guy",
            "Lusitana" => "Lusitana",
            "Lustria" => "Lustria",
            "Macondo" => "Macondo",
            "Macondo Swash Caps" => "Macondo Swash Caps",
            "Magra" => "Magra",
            "Maiden Orange" => "Maiden Orange",
            "Mako" => "Mako",
            "Marck Script" => "Marck Script",
            "Marko One" => "Marko One",
            "Marmelad" => "Marmelad",
            "Marvel" => "Marvel",
            "Mate" => "Mate",
            "Mate SC" => "Mate SC",
            "Maven Pro" => "Maven Pro",
            "Meddon" => "Meddon",
            "MedievalSharp" => "MedievalSharp",
            "Medula One" => "Medula One",
            "Megrim" => "Megrim",
            "Merienda One" => "Merienda One",
            "Merriweather" => "Merriweather",
            "Metal" => "Metal",
            "Metamorphous" => "Metamorphous",
            "Metrophobic" => "Metrophobic",
            "Michroma" => "Michroma",
            "Miltonian" => "Miltonian",
            "Miltonian Tattoo" => "Miltonian Tattoo",
            "Miniver" => "Miniver",
            "Miss Fajardose" => "Miss Fajardose",
            "Modern Antiqua" => "Modern Antiqua",
            "Molengo" => "Molengo",
            "Monofett" => "Monofett",
            "Monoton" => "Monoton",
            "Monsieur La Doulaise" => "Monsieur La Doulaise",
            "Montaga" => "Montaga",
            "Montez" => "Montez",
            "Montserrat" => "Montserrat",
            "Moul" => "Moul",
            "Moulpali" => "Moulpali",
            "Mountains of Christmas" => "Mountains of Christmas",
            "Mr Bedfort" => "Mr Bedfort",
            "Mr Dafoe" => "Mr Dafoe",
            "Mr De Haviland" => "Mr De Haviland",
            "Mrs Saint Delafield" => "Mrs Saint Delafield",
            "Mrs Sheppards" => "Mrs Sheppards",
            "Muli" => "Muli",
            "Mystery Quest" => "Mystery Quest",
            "Neucha" => "Neucha",
            "Neuton" => "Neuton",
            "News Cycle" => "News Cycle",
            "Niconne" => "Niconne",
            "Nixie One" => "Nixie One",
            "Nobile" => "Nobile",
            "Nokora" => "Nokora",
            "Norican" => "Norican",
            "Nosifer" => "Nosifer",
            "Nothing You Could Do" => "Nothing You Could Do",
            "Noticia Text" => "Noticia Text",
            "Nova Cut" => "Nova Cut",
            "Nova Flat" => "Nova Flat",
            "Nova Mono" => "Nova Mono",
            "Nova Oval" => "Nova Oval",
            "Nova Round" => "Nova Round",
            "Nova Script" => "Nova Script",
            "Nova Slim" => "Nova Slim",
            "Nova Square" => "Nova Square",
            "Numans" => "Numans",
            "Nunito" => "Nunito",
            "Odor Mean Chey" => "Odor Mean Chey",
            "Old Standard TT" => "Old Standard TT",
            "Oldenburg" => "Oldenburg",
            "Oleo Script" => "Oleo Script",
            "Open Sans" => "Open Sans",
            "Open Sans Condensed" => "Open Sans Condensed",
            "Orbitron" => "Orbitron",
            "Original Surfer" => "Original Surfer",
            "Oswald" => "Oswald",
            "Over the Rainbow" => "Over the Rainbow",
            "Overlock" => "Overlock",
            "Overlock SC" => "Overlock SC",
            "Ovo" => "Ovo",
            "Oxygen" => "Oxygen",
            "PT Mono" => "PT Mono",
            "PT Sans" => "PT Sans",
            "PT Sans Caption" => "PT Sans Caption",
            "PT Sans Narrow" => "PT Sans Narrow",
            "PT Serif" => "PT Serif",
            "PT Serif Caption" => "PT Serif Caption",
            "Pacifico" => "Pacifico",
            "Parisienne" => "Parisienne",
            "Passero One" => "Passero One",
            "Passion One" => "Passion One",
            "Patrick Hand" => "Patrick Hand",
            "Patua One" => "Patua One",
            "Paytone One" => "Paytone One",
            "Permanent Marker" => "Permanent Marker",
            "Petrona" => "Petrona",
            "Philosopher" => "Philosopher",
            "Piedra" => "Piedra",
            "Pinyon Script" => "Pinyon Script",
            "Plaster" => "Plaster",
            "Play" => "Play",
            "Playball" => "Playball",
            "Playfair Display" => "Playfair Display",
            "Podkova" => "Podkova",
            "Poiret One" => "Poiret One",
            "Poller One" => "Poller One",
            "Poly" => "Poly",
            "Pompiere" => "Pompiere",
            "Pontano Sans" => "Pontano Sans",
            "Port Lligat Sans" => "Port Lligat Sans",
            "Port Lligat Slab" => "Port Lligat Slab",
            "Prata" => "Prata",
            "Preahvihear" => "Preahvihear",
            "Press Start 2P" => "Press Start 2P",
            "Princess Sofia" => "Princess Sofia",
            "Prociono" => "Prociono",
            "Prosto One" => "Prosto One",
            "Puritan" => "Puritan",
            "Quantico" => "Quantico",
            "Quattrocento" => "Quattrocento",
            "Quattrocento Sans" => "Quattrocento Sans",
            "Questrial" => "Questrial",
            "Quicksand" => "Quicksand",
            "Qwigley" => "Qwigley",
            "Radley" => "Radley",
            "Raleway" => "Raleway",
            "Rammetto One" => "Rammetto One",
            "Rancho" => "Rancho",
            "Rationale" => "Rationale",
            "Redressed" => "Redressed",
            "Reenie Beanie" => "Reenie Beanie",
            "Revalia" => "Revalia",
            "Ribeye" => "Ribeye",
            "Ribeye Marrow" => "Ribeye Marrow",
            "Righteous" => "Righteous",
            "Rochester" => "Rochester",
            "Rock Salt" => "Rock Salt",
            "Rokkitt" => "Rokkitt",
            "Ropa Sans" => "Ropa Sans",
            "Rosario" => "Rosario",
            "Rosarivo" => "Rosarivo",
            "Rouge Script" => "Rouge Script",
            "Ruda" => "Ruda",
            "Ruge Boogie" => "Ruge Boogie",
            "Ruluko" => "Ruluko",
            "Ruslan Display" => "Ruslan Display",
            "Russo One" => "Russo One",
            "Ruthie" => "Ruthie",
            "Sail" => "Sail",
            "Salsa" => "Salsa",
            "Sancreek" => "Sancreek",
            "Sansita One" => "Sansita One",
            "Sarina" => "Sarina",
            "Satisfy" => "Satisfy",
            "Schoolbell" => "Schoolbell",
            "Seaweed Script" => "Seaweed Script",
            "Sevillana" => "Sevillana",
            "Shadows Into Light" => "Shadows Into Light",
            "Shadows Into Light Two" => "Shadows Into Light Two",
            "Shanti" => "Shanti",
            "Share" => "Share",
            "Shojumaru" => "Shojumaru",
            "Short Stack" => "Short Stack",
            "Siemreap" => "Siemreap",
            "Sigmar One" => "Sigmar One",
            "Signika" => "Signika",
            "Signika Negative" => "Signika Negative",
            "Simonetta" => "Simonetta",
            "Sirin Stencil" => "Sirin Stencil",
            "Six Caps" => "Six Caps",
            "Slackey" => "Slackey",
            "Smokum" => "Smokum",
            "Smythe" => "Smythe",
            "Sniglet" => "Sniglet",
            "Snippet" => "Snippet",
            "Sofia" => "Sofia",
            "Sonsie One" => "Sonsie One",
            "Sorts Mill Goudy" => "Sorts Mill Goudy",
            "Special Elite" => "Special Elite",
            "Spicy Rice" => "Spicy Rice",
            "Spinnaker" => "Spinnaker",
            "Spirax" => "Spirax",
            "Squada One" => "Squada One",
            "Stardos Stencil" => "Stardos Stencil",
            "Stint Ultra Condensed" => "Stint Ultra Condensed",
            "Stint Ultra Expanded" => "Stint Ultra Expanded",
            "Stoke" => "Stoke",
            "Sue Ellen Francisco" => "Sue Ellen Francisco",
            "Sunshiney" => "Sunshiney",
            "Supermercado One" => "Supermercado One",
            "Suwannaphum" => "Suwannaphum",
            "Swanky and Moo Moo" => "Swanky and Moo Moo",
            "Syncopate" => "Syncopate",
            "Tangerine" => "Tangerine",
            "Taprom" => "Taprom",
            "Telex" => "Telex",
            "Tenor Sans" => "Tenor Sans",
            "The Girl Next Door" => "The Girl Next Door",
            "Tienne" => "Tienne",
            "Tinos" => "Tinos",
            "Titan One" => "Titan One",
            "Trade Winds" => "Trade Winds",
            "Trocchi" => "Trocchi",
            "Trochut" => "Trochut",
            "Trykker" => "Trykker",
            "Tulpen One" => "Tulpen One",
            "Ubuntu" => "Ubuntu",
            "Ubuntu Condensed" => "Ubuntu Condensed",
            "Ubuntu Mono" => "Ubuntu Mono",
            "Ultra" => "Ultra",
            "Uncial Antiqua" => "Uncial Antiqua",
            "UnifrakturCook" => "UnifrakturCook",
            "UnifrakturMaguntia" => "UnifrakturMaguntia",
            "Unkempt" => "Unkempt",
            "Unlock" => "Unlock",
            "Unna" => "Unna",
            "VT323" => "VT323",
            "Varela" => "Varela",
            "Varela Round" => "Varela Round",
            "Vast Shadow" => "Vast Shadow",
            "Vibur" => "Vibur",
            "Vidaloka" => "Vidaloka",
            "Viga" => "Viga",
            "Voces" => "Voces",
            "Volkhov" => "Volkhov",
            "Vollkorn" => "Vollkorn",
            "Voltaire" => "Voltaire",
            "Waiting for the Sunrise" => "Waiting for the Sunrise",
            "Wallpoet" => "Wallpoet",
            "Walter Turncoat" => "Walter Turncoat",
            "Wellfleet" => "Wellfleet",
            "Wire One" => "Wire One",
            "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
            "Yellowtail" => "Yellowtail",
            "Yeseva One" => "Yeseva One",
            "Yesteryear" => "Yesteryear",
            "Zeyada" => "Zeyada",
    );
    return $google_fonts_array;
}
endif;
