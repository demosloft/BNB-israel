<?php

function rcapi_retrive_token(){
    $token_expiration = floatval( esc_html ( get_option('wp_estate_token_expire','') ) );
    $time= time();
    
    //print 'check ';
     $check= $token_expiration - $time + 3600;
    //print '</br>';
    
    
    if ( $check <= 0 || $token_expiration==0){
      //  print'regenerate 1';
        $token = rentals_club_get_token();
        update_option('wp_estate_token_expire',time());
        update_option('wp_estate_curent_token',$token);
    }else{
    //    print 'from db</br>';
        $token = esc_html ( get_option('wp_estate_curent_token','') );
    }
    
    if($token==''){
        //print'regenerate 2';
        $token = rentals_club_get_token();
        update_option('wp_estate_token_expire',time());
        update_option('wp_estate_curent_token',$token);
    }
  //  print 'we use '.$token.'</br>';
    
    return $token;
    
}

function rentals_club_get_token(){
    
    $client_id      = esc_html ( wprentals_get_option('wp_estate_rcapi_api_key','') );
    $client_secret  = esc_html ( wprentals_get_option('wp_estate_rcapi_api_secret_key','') );
    $username       = esc_html ( wprentals_get_option('wp_estate_rcapi_api_username','') );
    $password       = esc_html ( wprentals_get_option('wp_estate_rcapi_api_password','') );

    if ($client_id=='' || $client_secret=='' || $username=='' || $password==''){
        return;
    }
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => CLUBLINKSSL."://www.".CLUBLINK."/?oauth=token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "grant_type=password&username=".$username."&password=".$password,
    CURLOPT_HTTPHEADER => array(
        "authorization: Basic ". base64_encode( $client_id . ':' . $client_secret ),
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",
        "postman-token: 3d65984a-9f80-a881-5fe9-59717126687e"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
   /*
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    } */
    $response= json_decode($response);
    
   // print_r($response);
    
    if(isset($response->access_token)){
        return $response->access_token;
    }else{
        return;
    }
  
    //print_r($response);
}
        

function rentals_api_managment(){
    
    $rcapi_api_key= esc_html ( wprentals_get_option('wp_estate_rcapi_api_key','') );
    $rcapi_api_username = esc_html ( wprentals_get_option('wp_estate_rcapi_api_username','') );
    $rcapi_api_password = esc_html ( wprentals_get_option('wp_estate_rcapi_api_password','') );
    $rcapi_api_secret_key = esc_html ( wprentals_get_option('wp_estate_rcapi_api_secret_key','') );
     
  
    echo '<p class="admin-exp">RentalsClub API is a 3rd party system that offers exclusive extensions for WP Rentals theme. If you wish to use any of the extensions available, you will need to fill in the below info. For more information check here - <a href="http://www.rentalsclub.org/" target="_blank">http://www.rentalsclub.org/</a></p>';
    
    if( rcapi_check_api_status()=='true' ){
        print '<div class="apinotice">'.esc_html__('You are now connected to RentalsClub API!','wprentals-core').'</div>';
     
    }else{
        print '<div class="apinotice apierror">'.esc_html__('Failed connection to RentalsClub.org Api! Please check your user credentials and api keys!','wprentals-core').'</div>';

         
    }
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Rentals Club API Key','wprentals-core').'</div>
        <div class="option_row_explain">'.__('Rentals Club API Key','wprentals-core').'</div>    
            <input type="text" name="rcapi_api_key" id="rcapi_api_key" value="'.$rcapi_api_key.'"/>
    </div>';

    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Rentals Club API Secret Key','wprentals-core').'</div>
        <div class="option_row_explain">'.__('Rentals Club  API Secret Key','wprentals-core').'</div>    
            <input type="text"  name="rcapi_api_secret_key" id="rcapi_api_secret_key" value="'.$rcapi_api_secret_key.'"/>
    </div>';
    
    
   
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Rentals Club Username','wprentals-core').'</div>
        <div class="option_row_explain">'.__('Rentals Club Username','wprentals-core').'</div>    
            <input type="text"  name="rcapi_api_username" id="rcapi_api_secret_key" value="'.$rcapi_api_username.'"/>
    </div>';
    
    print'<div class="estate_option_row">
        <div class="label_option_row">'.__('Rentals Club Password','wprentals-core').'</div>
        <div class="option_row_explain">'.__('Rentals Club password','wprentals-core').'</div>    
             <input type="text" name="rcapi_api_password" id="rcapi_api_password" value="'.$rcapi_api_password.'"/>
    </div>';
    
    
    
    print ' <div class="estate_option_row_submit">
    <input type="submit" name="submit"  class="new_admin_submit " value="'.__('Save Changes','wprentals-core').'" />
    </div>';

    
}


function rcapi_check_api_status(){
    
    $token= rcapi_retrive_token();
    
    if($token==''){
        return;
    }
    
    // save sms functions 
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/status/?access_token=".$token;
    $arguments = array(
	'method' => 'GET',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
    $body=  wp_remote_retrieve_body($response);
    return $body;
 
}

function rcapi_udate_theme_options($theme_options){
    
    
    if( !wpestate_check_admin_role() ){
       exit();
    }
            
            
    $token= rcapi_retrive_token();
    $values_array=array(
        "theme_options"            =>  $theme_options,
    );
   
    // save sms functions 
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/save_theme_options/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
        'body' => $values_array,
	'cookies' => array(),
        
     
    );
    $response= wp_remote_post($url,$arguments);
    $body=  wp_remote_retrieve_body($response);
    return;
   
}
    

//function rcapi_save_booking($book_id,$booking_guest_no,$confirmed,$listing_edit,$rcapi_listing_id,$fromdate,$todate,$book_author,$extra_options,$security_depozit, $full_pay_invoice_id,$to_be_paid){
  
function rcapi_save_booking($book_id,$add_booking_details){
          
//return;
    $token= rcapi_retrive_token();
    
    $values_array=array(
        "booking_id"             => $book_id,
        "add_booking_details"    => $add_booking_details
    );
    
    // save sms content
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/booking/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
   

    $rcapi_booking_id=  json_decode( wp_remote_retrieve_body($response) ,true);
    update_post_meta($book_id,'rcapi_booking_id',$rcapi_booking_id);
 
}


function rcapi_edit_booking($booking_id,$rcapi_booking_id,$booking_details){
    //return;    

    
    $current_user = wp_get_current_user();
    $allowded_html      =   array();
    $userID             =   $current_user->ID;

    if ( !is_user_logged_in() ) {   
        exit('ko');
    }
    if($userID === 0 ){
        exit('out pls');
    }
    
    
    $token              =   rcapi_retrive_token();
    $rcapi_booking_id   =   get_post_meta($booking_id,'rcapi_booking_id',true);
    $values_array=array(
        "rcapi_booking_id"              =>      $rcapi_booking_id,
        "original_booking_id"           =>      $booking_id,
        "booking_details"               =>      $booking_details,
        "original_userID"               =>      $userID,
   
    );
 

    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/booking/edit/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
    //print_r(json_decode( wp_remote_retrieve_body($response) ,true));
    return;
  
   
    
}


function rcapi_delete_booking($booking_id,$rcapi_booking_id,$original_user_id,$is_user){
    //return;    
   
    $token              =   rcapi_retrive_token();
   // $rcapi_booking_id   =   get_post_meta($booking_id,'rcapi_booking_id',true);
    $values_array=array(
        "rcapi_booking_id"              =>      $rcapi_booking_id,
        "original_booking_id"           =>      $booking_id,
        "original_user_id"              =>      $original_user_id,
        "is_user"                       =>      $is_user,
   
    );
 
    //print_r($values_array);
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/booking/delete/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
    //print_r(json_decode( wp_remote_retrieve_body($response) ,true));
    return;
  
   
    
}









//function rcapi_invoice_booking($rcapi_booking_id,$invoice_id,$billing_for,$type, $pack_id,$date,$user_id,$is_featured,$is_upgrade, $paypal_tax_id,$details,$price,$to_be_paid, $submission_curency_status,$bookid, $author_id='' ){
  
function rcapi_invoice_booking($invoice_id, $invoice_details ){
    //return;   
    
    $token= rcapi_retrive_token();
    
    $values_array=array(
        "invoice_details"               =>  $invoice_details
    );
    
    // save sms content
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/invoice/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
   

    $rcapi_invoice_id =  (  json_decode( wp_remote_retrieve_body($response) ,true)  );
    update_post_meta($invoice_id,"rcapi_invoice_id",$rcapi_invoice_id);
        
}


function rcapi_edit_invoice($invoice_id,$rcapi_invoice_id,$invoice_details){
    //return;    

    
    $current_user       =   wp_get_current_user();
    $allowded_html      =   array();
    $userID             =   $current_user->ID;

    if ( !is_user_logged_in() ) {   
        exit('ko');
    }
    if($userID === 0 ){
        exit('out pls');
    }
    
    
    $token              =   rcapi_retrive_token();
    $rcapi_invoice_id   =   get_post_meta($invoice_id,'rcapi_invoice_id',true);
    $values_array=array(
        "rcapi_invoice_id"              =>      $rcapi_invoice_id,
        "original_invoice_id"           =>      $invoice_id,
        "invoice_details"               =>      $invoice_details,
        "original_userID"               =>      $userID,
   
    );
 

    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/invoice/edit/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
   // print_r(json_decode( wp_remote_retrieve_body($response) ,true));
    return;
  
   
    
}




function rcapi_update_invoice_as_paid($booking_id,$invoice_id,$booking_array){
        
    //return;
    $token= rcapi_retrive_token();
    
    $values_array=array(
        "original_booking_id"               =>   $booking_id,
        "rcapi_booking_id"                  =>   intval( get_post_meta($booking_id ,"rcapi_booking_id",true) ),
        "original_invoice_id"               =>   $invoice_id,
        "rcapi_invoice_id"                  =>   intval ( get_post_meta($invoice_id ,"rcapi_invoice_id",true) ),
        "booking_array"                     =>   $booking_array,
        
    );
    
    // save sms content
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/invoice/paid/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
   
   // return;
   // print_r(  json_decode( wp_remote_retrieve_body($response) ,true)  );
 
}

function rcapi_delete_invoice($invoice_id,$rcapi_invoice_id,$original_user_id){
    //return;    
   
    $token              =   rcapi_retrive_token();
   // $rcapi_booking_id   =   get_post_meta($booking_id,'rcapi_booking_id',true);
    $values_array=array(
        "rcapi_invoice_id"              =>      $rcapi_invoice_id,
        "original_invoice_id"           =>      $invoice_id,
        "original_user_id"              =>      $original_user_id,
       
   
    );
 
    //print_r($values_array);
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/invoice/delete/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
    //print_r(json_decode( wp_remote_retrieve_body($response) ,true));
    return;
  
   
    
}



function rcapi_create_new_user($user_id,$user_name,$password,$user_email){
    return;    
   
    $token= rcapi_retrive_token();
    
    $values_array=array(
        "original_user_id"  =>   $user_id,
        "user_name"         =>   $user_name,
        "user_email"        =>   $user_email,
    );
  
    // save sms content
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/user/add/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
   
   // return;
   // print_r(json_decode( wp_remote_retrieve_body($response) ,true));
    $rcapi_user_id =  json_decode( wp_remote_retrieve_body($response) ,true);
    
    update_user_meta($user_id,'rcapi_user_id',$rcapi_user_id);
    
}


function rcapi_update_user($user_id,$arguments){
    //return;    

    $token= rcapi_retrive_token();
    
    $values_array=array(
        "original_user_id"  =>   $user_id,
        "arguments"         =>   $arguments,
    );
   
    // save sms content
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/subuser/edit/?access_token=".$token;
    $arguments = array(
	'method'        => 'POST',
	'timeout'       => 45,
	'redirection'   => 5,
	'httpversion'   => '1.0',
	'blocking'      => true,
	'headers'       => array(),
	'body'          => $values_array,
	'cookies'       => array()
    );
    
    $response= wp_remote_post($url,$arguments);
    $rcapi_user_id =  json_decode( wp_remote_retrieve_body($response) ,true);
    update_user_meta($user_id,'rcapi_user_id',$rcapi_user_id);
}




function rcapi_create_new_listing($user_id,$listing_id,$submit_title,$property_description,$new_status,$prop_category_name,$prop_action_category_name,$property_city,$property_area,$guest_no,$property_admin_area,$property_country,$instant_booking){
   // return;    
   
    $token= rcapi_retrive_token();
    
    $values_array=array(
        "original_user_id"              =>      $user_id,
        "original_listing_id"           =>      $listing_id,
        "submit_title"                  =>      $submit_title,
        "property_description"          =>      $property_description,
        "new_status"                    =>      $new_status,
        "prop_category_name"            =>      $prop_category_name,
        "prop_action_category_name"     =>      $prop_action_category_name,
        "property_city"                 =>      $property_city,
        "property_area"                 =>      $property_area,
        "guest_no"                      =>      $guest_no,     
        "property_admin_area"           =>      $property_admin_area,
        "property_country"              =>      $property_country,
        "instant_booking"               =>      $instant_booking,
    );
  
    // save sms content
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/listing/add/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
   
   // return;
   // print_r(json_decode( wp_remote_retrieve_body($response) ,true));
    $rcapi_listing_id =  json_decode( wp_remote_retrieve_body($response) ,true);
    
    update_post_meta($listing_id,'rcapi_listing_id',$rcapi_listing_id);
    
}

function rcapi_update_listing($listing_id,$update_details){
    //return;    
   
    $token= rcapi_retrive_token();
    $rcapi_listing_id=  get_post_meta($listing_id,'rcapi_listing_id',true);
    $values_array=array(
        "rcapi_listing_id"              =>      $rcapi_listing_id,
        "original_listing_id"           =>      $listing_id,
        "update_details"                =>      $update_details
      
    );
  
  
    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/listing/edit/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
   // print_r(json_decode( wp_remote_retrieve_body($response) ,true));
   // return;
  
   
    
}

function rcapi_delete_listing($listing_id,$original_user_id){
    //return;    
   
    $token              =   rcapi_retrive_token();
    $rcapi_listing_id   =   get_post_meta($listing_id,'rcapi_listing_id',true);
    $values_array=array(
        "rcapi_listing_id"              =>      $rcapi_listing_id,
        "original_listing_id"           =>      $listing_id,
        "original_user_id"              =>      $original_user_id
    );

    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/listing/delete/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
   
    return;
   
}


function rcapi_payment_management_info(){

    if ( !current_user_can('administrator') ){
        exit('only admins');
    }
    $token              =   rcapi_retrive_token();
    if($token==''){
        return;
    }
    $values_array=array();

    $url="http://www.rentalsclub.org/wp-json/rcapi/v1/payments/list/?access_token=".$token;
    $arguments = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => $values_array,
	'cookies' => array()
    );
    $response= wp_remote_post($url,$arguments);
    $table=(json_decode( wp_remote_retrieve_body($response) ,true));
    if( isset( $table['data']['status'] ) &&  $table['data']['status']=='403' ){
        print '<div class="apinotice apierror">'.esc_html__('Failed connection to RentalsClub.org Api! Please check your user credentials and api keys!','wprentals-core').'</div>';
    }else{
        print_r($table);
    }
    return;
  
   
    
}