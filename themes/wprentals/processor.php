<?php
// Template Name: Paypal Processor
// Wp Estate Pack

$paid_submission_status     =   esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
$paypal_status              =   esc_html( wprentals_get_option('wp_estate_paypal_api','') );
$host                       =   'https://api.sandbox.paypal.com';
if($paypal_status == 'live'){
    $host='https://api.paypal.com';
}

$current_user = wp_get_current_user();   
$processor_link                 =   wpestate_get_template_link('processor.php');
$clientId                       =   esc_html( wprentals_get_option('wp_estate_paypal_client_id','') );
$clientSecret                   =   esc_html( wprentals_get_option('wp_estate_paypal_client_secret','') );
$price_submission               =   floatval( wprentals_get_option('wp_estate_price_submission','') );
$price_submission               =   number_format($price_submission, 2, '.', '');
$submission_curency_status      =   esc_html( wprentals_get_option('wp_estate_submission_curency','') );
$headers                        =   'From: My Name <myname@example.com>' . "\r\n";
$attachments                    =   '';
$allowed_html   =   array();






if (isset($_GET['token']) && isset($_GET['PayerID']) ){
        $token     =   sanitize_text_field ( wp_kses ( $_GET['token'],$allowed_html) );
        $payerId   =   sanitize_text_field ( wp_kses ( $_GET['PayerID'] ,$allowed_html) );

        // get transfer data
        $save_data=get_option('paypal_transfer');
        $payment_execute_url    =   $save_data[$current_user->ID ]['paypal_execute'];
        $token                  =   $save_data[$current_user->ID ]['paypal_token'];
        $listing_id             =   $save_data[$current_user->ID ]['listing_id'];
        $is_featured            =   $save_data[$current_user->ID ]['is_featured'];
        $is_upgrade             =   $save_data[$current_user->ID ]['is_upgrade'];
        $is_booking             =   '';
        $invoice_id             =   '';
        $booking_id             =   '';
        if(isset($save_data[$current_user->ID ]['is_booking'])){
            $is_booking             =   $save_data[$current_user->ID ]['is_booking'];
        }
        
        if(isset($save_data[$current_user->ID ]['invoice_id'])){
            $invoice_id             =   $save_data[$current_user->ID ]['invoice_id'];
        }
        
        if(isset($save_data[$current_user->ID ]['booking_id'])){
            $booking_id             =   $save_data[$current_user->ID ]['booking_id'];
        }
     
       
        
        $payment_execute = array(
                'payer_id' => $payerId
            );
        
        $json                           =   json_encode($payment_execute);
        $json_resp                      =   wpestate_make_post_call($payment_execute_url, $json,$token);
        $save_data[$current_user->ID ]  =   array();
        update_option ('paypal_transfer',$save_data);
        
        // update prop listing status
        if($json_resp['state']=='approved'){
            $time = time(); 
            $date = date('Y-m-d H:i:s',$time);
    
                if($is_booking==1){
                    $userID             =   $current_user->ID;
                    $user_email         =   $current_user->user_email;
                       
                    $booking_guests     =   floatval(get_post_meta($booking_id, 'booking_guests', true));
                    $booking_from_date  =   esc_html(get_post_meta($booking_id, 'booking_from_date', true));
                    $booking_prop       =   esc_html(get_post_meta($booking_id, 'booking_id', true));
                    $booking_to_date    =   esc_html(get_post_meta($booking_id, 'booking_to_date', true));   
                    $curent_listng_id   =   get_post_meta($booking_id,'booking_id',true);
                    $booking_array      =   wpestate_booking_price($booking_guests,$invoice_id,$curent_listng_id, $booking_from_date, $booking_to_date);
                    wpestate_booking_mark_confirmed($booking_id,$invoice_id,$userID,$booking_array['deposit'],$user_email);
                
          
                
              
                ////redirect catre bookng list
                $redirect=wpestate_get_template_link('user_dashboard_my_reservations.php');
                wp_redirect($redirect);
                exit();
    
            }else if($is_upgrade==1){
                update_post_meta($listing_id, 'prop_featured', 1);
                $invoice_id = wpestate_insert_invoice('Upgrade to Featured', esc_html__( 'One Time','wprentals'),$listing_id,$date,$current_user->ID,0,1,'' );
                $invoice_id =  update_post_meta($invoice_id, 'invoice_status', 'confirmed');
                wpestate_email_to_admin(1);
            }else{
               
                update_post_meta($listing_id, 'pay_status', 'paid');
                
                // if admin does not need to approve - make post status as publish
                $admin_submission_status = esc_html ( wprentals_get_option('wp_estate_admin_submission','') );
                $paid_submission_status  = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
              
                if($admin_submission_status=='no'  && $paid_submission_status=='per listing' ){
                      
                    $post = array(
                        'ID'            => $listing_id,
                        'post_status'   => 'publish'
                        );
                     $post_id =  wp_update_post($post ); 
                }
                // end make post publish
                
                
                if($is_featured==1){
                    update_post_meta($listing_id, 'prop_featured', 1);
                    $invoice_id = wpestate_insert_invoice('Publish Listing with Featured', esc_html__( 'One Time','wprentals'),$listing_id,$date,$current_user->ID,1,0,'' );
                    update_post_meta($invoice_id, 'invoice_status', 'confirmed');
                }else{
                    $invoice_id =  wpestate_insert_invoice('Listing', esc_html__( 'One Time','wprentals'),$listing_id,$date,$current_user->ID,0,0,'' );
                    update_post_meta($invoice_id, 'invoice_status', 'confirmed');
                }
                wpestate_email_to_admin(0);
               
            }  
         }   
    $redirect = wpestate_get_template_link('user_dashboard.php');
    wp_redirect($redirect);exit();
}
$token = '';



//////////////////////////////////////////////////////////////////////////////////////
/// Process messages from Paypal IPN
//////////////////////////////////////////////////////////////////////////////////////
define('DEBUG',0);
ob_start();
global $wp_filesystem;
if (empty($wp_filesystem)) {
    require_once (ABSPATH . '/wp-admin/includes/file.php');
    WP_Filesystem();
}
            
//$raw_post_data  = file_get_contents('php://input');
$raw_post_data =  $wp_filesystem->get_contents('php://input');


$raw_post_array = explode('&', $raw_post_data);
$myPost         = array();

foreach ($raw_post_array as $keyval) {
        $keyval = explode ('=', $keyval);
        if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
   $get_magic_quotes_exists = true;
} 

foreach ($myPost as $key => $value) {        
    if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
        $value = urlencode(stripslashes($value)); 
    } else {
        $value = urlencode($value);
    }
    $req .= "&$key=$value";
}
 

// Step 2: POST IPN data back to PayPal to validate
$paypal_status  =   esc_html( wprentals_get_option('wp_estate_paypal_api','') );
if($paypal_status == 'live'){
    $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
} else {
    $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}  
 
$args=array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'sslverify' => false,
        'blocking' => true,
        'body' =>  $req,

);
  //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        
$response   =   wp_remote_post( $paypal_url, $args ); 
$res        =   '';

if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
    die($error_message);
} else {
    $res = wp_remote_retrieve_body( $response );
 }       
        

  
//if ( $isres == "VERIFIED" ) {
if (strcmp ($res, "VERIFIED") == 0) {
  
    $allowed_html   =   array();
    $payment_status         =   wp_kses ( $_POST['payment_status'],$allowed_html );
    $txn_id                 =   wp_kses ( $_POST['txn_id'],$allowed_html );
    $txn_type               =   wp_kses ( $_POST['txn_type'],$allowed_html );   
    $paypal_rec_email       =   esc_html( wprentals_get_option('wp_estate_paypal_rec_email','') );
    $receiver_email         =   wp_kses ( $_POST['receiver_email'],$allowed_html );
    $payer_id               =   wp_kses ( $_POST['payer_id'],$allowed_html );

    $payer_email            =   wp_kses ( $_POST['payer_email'] ,$allowed_html);
    $amount                 =   wp_kses ( $_POST['amount'],$allowed_html );
    $recurring_payment_id   =   wp_kses ( $_POST['recurring_payment_id'],$allowed_html );
 
     

    foreach ($_POST as $key => $value){
        $key    =   sanitize_key($key);
        $value  =   wp_kses($value,$allowed_html);
    }  

    $user_id                =   wpestate_retrive_user_by_recurring_payment_id($recurring_payment_id);
    $pack_id                =   get_user_meta($user_id, 'package_id',true);
    $price                  =   get_post_meta($pack_id, 'pack_price', true);

    //$mailm='';
    
   
    if( $payment_status=='Completed' ){

        if(wpestate_retrive_invoice_by_taxid($txn_id)){// payment already processd
            exit();
        }

        if( $user_id==0 ){ // no user with such profile id
            exit();
        }

        if( $amount != $price){ // received payment diffrent than pack value
            exit(); 
        }

        wpestate_upgrade_user_membership($user_id,$pack_id,2,$txn_id);
        
    }else{ // payment not completed
        if($txn_type!='recurring_payment_profile_created'){
            wpestate_downgrade_to_free($user_id);
        }
    }
 
}else{
   exit('not right');
} 



/////////////////////////////////////////////////////////////////////////////////////
// get user by paypal recurring profile
/////////////////////////////////////////////////////////////////////////////////////

function wpestate_retrive_user_by_profile($recurring_payment_id){   
    $recurring_payment_id=  str_replace('-', 'xxx', $recurring_payment_id);
    $arg=array(
        'role'         => 'subscriber',
        'meta_key'     => 'profile_id',
        'meta_value'   => $recurring_payment_id,
        'meta_compare' => '='
    );
    $userid=0;
    $blogusers = get_users($arg);
    foreach ($blogusers as $user) {
       $userid=$user->ID;
    }
    return $userid;
}


function wpestate_retrive_user_by_recurring_payment_id($recurring_payment_id){   
    $arg=array(
        'meta_key'     => 'paypal_agreement',
	'meta_value'   => trim($recurring_payment_id),
        'meta_compare' => '=',
    );
    $userid=0;
    $blogusers = get_users($arg);
    
    foreach ($blogusers as $user) {
       $userid=$user->ID;
    }
    return $userid;
}



/////////////////////////////////////////////////////////////////////////////////////
// Invoice by tax id
/////////////////////////////////////////////////////////////////////////////////////


function wpestate_retrive_invoice_by_taxid($tax_id){
    $args = array(
        'post_type' => 'wpestate_invoice',
        'meta_query' => array(
            array(
                'key' => 'txn_id',
                'value' => $tax_id,
                'compare' => '='
            )
        )
    );

    $query = new WP_Query( $args );
    if( $query->have_posts() ){
        return true;
    }else{
        return false;
    }
}

?>