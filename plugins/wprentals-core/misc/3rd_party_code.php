<?php


 

  



  
add_action( 'wp_ajax_wpestate_check_license_function', 'wpestate_check_license_function' );

if( !function_exists('wpestate_check_license_function') ):
    function wpestate_check_license_function(){
        if( !current_user_can('administrator') ){
            exit('out pls');
        }
        
        $wpestate_license_key = esc_html($_POST['wpestate_license_key']); 
        check_ajax_referer( 'my-check_ajax_license-string',  'security' );
        $data= array('license'=>$wpestate_license_key,'action'=>'wpestate_envato_lic');
            
        $args=array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'sslverify' => false,
                'blocking' => true,
                'body' =>  $data,
                'headers' => [
                      'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
                ],
        );
        
        
        $url="http://support.wpestate.org/theme_license_check_rentals.php";
        $response = wp_remote_post( $url, $args ); 
   
        if ( is_wp_error( $response ) ) {
	    $error_message = $response->get_error_message();
            die($error_message);
	} else {
	   
            $output = wp_remote_retrieve_body( $response );
          
            if($output==='ok'){
                update_option('is_theme_activated','is_active');
                print 'ok';
            }else{
                print 'nook';
            }
        
	}
        
        die();
    }
endif;
  


///////////////////////////////////////////////////////////////////////////////////////////
// paypal functions - get acces token
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_access_token') ):
    function wpestate_get_access_token($url, $postdata) {
        $clientId                       =   esc_html( wprentals_get_option('wp_estate_paypal_client_id','') );
        $clientSecret                   =   esc_html( wprentals_get_option('wp_estate_paypal_client_secret','') );
          
        $access_token='';
        $args=array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'sslverify' => false,
                'blocking' => true,
                'body' =>  'grant_type=client_credentials',
                'headers' => [
                      'Authorization' => 'Basic ' . base64_encode( $clientId . ':' . $clientSecret ),
                      'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
                ],
        );
        
        
        
        $response = wp_remote_post( $url, $args ); 
        
        
	if ( is_wp_error( $response ) ) {
	    $error_message = $response->get_error_message();
            die($error_message);
	} else {
	   
            $body = wp_remote_retrieve_body( $response );
            $body = json_decode( $body, true );
            $access_token = $body['access_token'];
        
	}

	return $access_token;
    }

endif; // end   wpestate_get_access_token 


