<?php



/*
* Select SMS Type
 *
 *
 *
 *
*/


if (!function_exists('wpestate_select_sms_type')):
    function wpestate_select_sms_type($user_mobile,$type,$arguments,$user_email, $user_data_id){
        $current_user = wp_get_current_user();
        $userID                 =   $current_user->ID;

        $sms_verification =esc_html( wprentals_get_option('wp_estate_sms_verification',''));
        if($sms_verification!=='yes'){
            return;
        }



        if($user_data_id!=0 && $type!='validation'){
            $roles=array('administrator');
            if( array_intersect($roles, $current_user->roles )){
               //is admin - do not check
            }else{
                $check_phone = get_the_author_meta( 'check_phone_valid' , $user_data_id);
                if($check_phone!='yes'){
                    return;
                }
            }
        }

        $value          =  wprentals_get_option('wp_estate_sms_'.$type,'');
        if (function_exists('icl_translate') ){
            $value          =  icl_translate('wpestate','wp_estate_sms_'.$value, $value ) ;
        }

        wpestate_sms_filter_replace($user_mobile,$value,$arguments,$user_email);




    }
endif;


/*
* Compose sms Message / replace
 *
 *
 *
 *
*/


if( !function_exists('wpestate_sms_filter_replace')):
    function  wpestate_sms_filter_replace($user_phone_no,$message,$arguments,$user_email){
        $arguments ['website_url'] = get_option('siteurl');
        $arguments ['website_name'] = get_option('blogname');
        $arguments ['user_email'] = $user_email;
        $user= get_user_by('email',$user_email);
        $arguments ['username'] = $user-> user_login;

        foreach($arguments as $key_arg=>$arg_val){
            $to_replace =   trim('%'.$key_arg);
            $message    =   str_replace($to_replace, $arg_val, $message);
        }

        $message=wp_strip_all_tags($message);

       wpstate_call_twilio_sms($user_phone_no,$message);
       //print_r($response);
    }
endif;






/*
* Send the actula SMS
 *
 *
 *
 *
*/



function wpstate_call_twilio_sms($user_phone_no,$message){



    $account_sid         =  wprentals_get_option('wp_estate_twilio_api_key','');
    $auth_token          =  wprentals_get_option('wp_estate_twilio_auth_token','');
    $twilio_phone_no     =  wprentals_get_option('wp_estate_twilio_phone_no','');

    $ch = curl_init();
    $post_data=array(
        "To"    =>  $user_phone_no,
        "From"  =>  $twilio_phone_no,
        "Body"  =>  $message
    );


    curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/".$account_sid."/Messages.json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_USERPWD, $account_sid. ":" . $auth_token);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close ($ch);
}
