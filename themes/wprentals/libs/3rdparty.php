<?php

add_action( 'wp_ajax_wpestate_update_cache_notice', 'wpestate_update_cache_notice' );

if( !function_exists('wpestate_update_cache_notice') ):
    function wpestate_update_cache_notice(){

        check_ajax_referer( 'wpestate_notice_nonce', 'security'  );

        $notice_type    =   esc_html($_POST['notice_type']);
        $notices        =   get_option('wp_estate_notices');
        
        if(! is_array($notices) ){
            $notices=array();
        }
        
        $notices[$notice_type]='yes';
        
        update_option('wp_estate_notices',$notices);

        die();
    }
endif;




////////////////////////////////////////////////////////////////////////////////
/// Twiter API v1.1 functions
////////////////////////////////////////////////////////////////////////////////


                
//convert links to clickable format
if( !function_exists('wpestate_convert_links') ):
    function wpestate_convert_links($status,$targetBlank=true,$linkMaxLen=250){
        // the target
        $target=$targetBlank ? " target=\"_blank\" " : "";

        // convert link to url
        $status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);

   
        
        // convert @ to follow
        $status = preg_replace("/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>",$status);

        // convert # to search
        $status = preg_replace("/(#([_a-z0-9\-]+))/i","<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>",$status);

        // return the status
        return $status;
    }
endif;
                

//convert dates to readable format	
if( !function_exists('wpestate_convert_links') ):
    function wpestate_convert_links($a) {
        //get current timestampt
    
        $b = strtotime("now"); 
        //get timestamp when tweet created
        $c = strtotime($a);
        //get difference
        $d = $b - $c;
        //calculate different time values
        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;

        if(is_numeric($d) && $d > 0) {
                //if less then 3 seconds
                if($d < 3) return "right now";
                //if less then minute
                if($d < $minute) return floor($d) . " seconds ago";
                //if less then 2 minutes
                if($d < $minute * 2) return "about 1 minute ago";
                //if less then hour
                if($d < $hour) return floor($d / $minute) . " minutes ago";
                //if less then 2 hours
                if($d < $hour * 2) return "about 1 hour ago";
                //if less then day
                if($d < $day) return floor($d / $hour) . " hours ago";
                //if more then day, but less then 2 days
                if($d > $day && $d < $day * 2) return "yesterday";
                //if less then year
                if($d < $day * 365) return floor($d / $day) . " days ago";
                //else return more than a year
                return "over a year ago";
        }
    }
endif;
 









///////////////////////////////////////////////////////////////////////////////////////////
// paypal functions - make post call
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_make_post_call') ):


    function wpestate_make_post_call($url, $postdata,$token) {
	
        $args=array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'sslverify' => false,
                'blocking' => true,
                'body' =>  $postdata,
                'headers' => [
                        'Authorization' =>'Bearer '.$token,
                        'Accept'        =>'application/json',
                        'Content-Type'  =>'application/json'
                ],
        );
        
        
        
        $response = wp_remote_post( $url, $args ); 
      
        
	if ( is_wp_error( $response ) ) {
	    $error_message = $response->get_error_message();
            die($error_message);
	} else {
	   
            $body = wp_remote_retrieve_body( $response );
            $jsonResponse = json_decode( $body, true );
        
        
	}

	return $jsonResponse;
    }

 

endif; // end   wpestate_make_post_call 


if( !function_exists('wpestate_make_get_call') ):
    function wpestate_make_get_call($url,$token) {
        
        $args=array(
                'method' => 'GET',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'sslverify' => false,
                'blocking' => true,
                'body' =>  $postdata,
                'headers' => [
                        'Authorization' =>'Bearer '.$token,
                        'Accept'        =>'application/json',
                        'Content-Type'  =>'application/json'
                ],
        );
        
        
        
        $response = wp_remote_get( $url, $args ); 
      
        
	if ( is_wp_error( $response ) ) {
	    $error_message = $response->get_error_message();
            die($error_message);
	} else {
	   
            $body = wp_remote_retrieve_body( $response );
            $jsonResponse = json_decode( $body, true );

	}
	return $jsonResponse;
    }

endif; // end   wpestate_make_post_call 





function wpestate_create_paypal_payment_plan($pack_id,$token){
    $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
    $submission_curency_status      =   esc_html( wprentals_get_option('wp_estate_submission_curency','') );
    $paypal_status                  =   esc_html( wprentals_get_option('wp_estate_paypal_api','') );
    $billing_period                 =   get_post_meta($pack_id, 'biling_period', true);
    $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
    $pack_name                      =   get_the_title($pack_id);
            
    $host   =   'https://api.sandbox.paypal.com';
    if($paypal_status=='live'){
        $host   =   'https://api.paypal.com';
    }
            
    $url        = $host.'/v1/oauth2/token'; 
    $postArgs   = 'grant_type=client_credentials';
  //  $token      = wpestate_get_access_token($url,$postArgs);
    $url        = $host.'/v1/payments/billing-plans/';
    $dash_profile_link = wpestate_get_template_link('user_dashboard_profile.php');
            
            
    $billing_plan = array(
        'name'                  =>  $pack_name ,
        'type'                  =>  'INFINITE',
        'description'           =>    $pack_name.esc_html__( ' package on ','wprentals').get_bloginfo('name'),
    );

    $billing_plan  [ 'payment_definitions']= array( 
                    array( 
                        'name'                  =>  $pack_name.esc_html__( ' package on ','wprentals').get_bloginfo('name'),
                        'type'                  =>  'REGULAR',
                        'frequency'             =>  $billing_period,
                        'frequency_interval'    =>  $billing_freq,
                        'amount'                =>  array(
                                                        'value'     =>  $pack_price,
                                                        'currency'  =>  $submission_curency_status,
                                                    ),
                        'cycles'     =>'0' 
                        )
            );

                                            
    $billing_plan  [ 'merchant_preferences']   =  array(
                                        'return_url'        =>  $dash_profile_link,
                                        'cancel_url'        =>  $dash_profile_link,
                                        'auto_bill_amount'  =>  'yes'
                                    );
            
    $json       = json_encode($billing_plan);
    $json_resp  = wpestate_make_post_call($url, $json,$token);
                

    
    
    if( $json_resp['state']!='ACTIVE'){
        if( wpestate_activate_paypal_payment_plan( $json_resp['id'],$token) ){
            $to_save = array();
            $to_save['id']          =   $json_resp['id'];
            $to_save['name']        =   $json_resp['name'];
            $to_save['description'] =   $json_resp['description'];
            $to_save['type']        =   $json_resp['type'];
            $to_save['state']       =   "ACTIVE";
            $paypal_status                  =   esc_html( wprentals_get_option('wp_estate_paypal_api','') );
            update_post_meta($pack_id,'paypal_payment_plan_'.$paypal_status,$to_save);
            
            return true;
        }
    }
    
   
    
    
    
  
   
}


function wpestate_activate_paypal_payment_plan($paypal_plan_id,$token){
    $paypal_status                  =   esc_html( wprentals_get_option('wp_estate_paypal_api','') );
    $host   =   'https://api.sandbox.paypal.com';
    if($paypal_status=='live'){
        $host   =   'https://api.paypal.com';
    }
  
  
    $args=array(
            'method' => 'PATCH',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => false,
            'blocking' => true,
            'body' =>"[{\n    \"op\": \"replace\",\n    \"path\": \"/\",\n    \"value\": {\n        \"state\": \"ACTIVE\"\n    }\n}]",
            'headers' => [
                  'Authorization' => 'Bearer '.$token,
                  'Content-Type' => 'application/json'
            ],
    );
        
    $url = $host."/v1/payments/billing-plans/".$paypal_plan_id."/";  
        
        
        
        
    $response = wp_remote_post( $url, $args ); 
 
        
    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        die($error_message);
        return false;
    } else {
        return true;
    }
        
        
  
                
}



add_action( 'wp_ajax_wpestate_update_cache_notice', 'wpestate_update_cache_notice' );

if( !function_exists('wpestate_update_cache_notice') ):
    function wpestate_update_cache_notice(){
        update_option('wp_estate_cache_notice','yes');
        die();
    }
endif;






function wpestate_create_paypal_payment_agreement($pack_id,$token){
    $current_user = wp_get_current_user();
    $paypal_status                  =   esc_html( wprentals_get_option('wp_estate_paypal_api','') );
    $payment_plan                   =   get_post_meta($pack_id, 'paypal_payment_plan_'.$paypal_status, true);
    $pack_price                     =   get_post_meta($pack_id, 'pack_price', true);
    $submission_curency_status      =   esc_html( wprentals_get_option('wp_estate_submission_curency','') );
    $paypal_status                  =   esc_html( wprentals_get_option('wp_estate_paypal_api','') );
    $billing_period                 =   get_post_meta($pack_id, 'biling_period', true);
    $billing_freq                   =   intval(get_post_meta($pack_id, 'billing_freq', true));
    $pack_name                      =   get_the_title($pack_id);
            
    $host   =   'https://api.sandbox.paypal.com';
    if($paypal_status=='live'){
        $host   =   'https://api.paypal.com';
    }
            
    $url        = $host.'/v1/oauth2/token'; 
    $postArgs   = 'grant_type=client_credentials';
    
    
    $url        = $host.'/v1/payments/billing-agreements/';
    $dash_profile_link = wpestate_get_template_link('user_dashboard_profile.php');
    $billing_agreement = array(
                        'name'          => __('PayPal payment agreement','wprentals'),
                        'description'   => __('PayPal payment agreement','wprentals'),
                        'start_date'    =>  gmdate("Y-m-d\TH:i:s\Z", time()+100 ),

        
    );
    
    $billing_agreement['payer'] =   array(
                        'payment_method'=>'paypal',
                        'payer_info'    => array('email'=>'payer@example.com'),
    );
     
    $billing_agreement['plan'] = array(
                        'id'            =>  $payment_plan['id'],
    );
    

    
    
    $json       = json_encode($billing_agreement);
    $json_resp  = wpestate_make_post_call($url, $json,$token);
        

  
    foreach ($json_resp['links'] as $link) {
            if($link['rel'] == 'execute'){
                    $payment_execute_url = $link['href'];
                    $payment_execute_method = $link['method'];
            } else 	if($link['rel'] == 'approval_url'){
                            $payment_approval_url = $link['href'];
                            $payment_approval_method = $link['method'];
                             print trim($link['href']);
                    }
    }



    $executor['paypal_execute']     =   $payment_execute_url;
    $executor['paypal_token']       =   $token;
    $executor['pack_id']            =   $pack_id;
    $save_data[$current_user->ID ]  =   $executor;
    update_option('paypal_pack_transfer',$save_data);
}