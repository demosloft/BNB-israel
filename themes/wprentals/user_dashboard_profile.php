<?php
// Template Name: User Dashboard Profile Page
// Wp Estate Pack
global $wpestate_social_login;
$current_user = wp_get_current_user();
$dash_profile_link = wpestate_get_template_link('user_dashboard_profile.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//////////////////////////////////////////////////////////////////////////////////////////
// Paypal payments for membeship packages
//////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['token']) ){
    $allowed_html       =   array();
    $token              =   sanitize_text_field ( wp_kses ( esc_html($_GET['token']) ,$allowed_html)  );
    $token_recursive    =   sanitize_text_field ( wp_kses ( esc_html($_GET['token']) ,$allowed_html ) );

    // get transfer data
    $save_data              =   get_option('paypal_pack_transfer');
    $payment_execute_url    =   $save_data[$current_user->ID ]['paypal_execute'];
    $token                  =   $save_data[$current_user->ID ]['paypal_token'];
    $pack_id                =   $save_data[$current_user->ID ]['pack_id'];
    $recursive              =   0;


    if( isset($_GET['PayerID']) ){
        $payerId             =   wp_kses ( esc_html($_GET['PayerID']),$allowed_html );

        $payment_execute = array(
                       'payer_id' => $payerId
                      );
        $json = json_encode($payment_execute);
        $json_resp = wpestate_make_post_call($payment_execute_url, $json,$token);

        $save_data[$current_user->ID ]=array();
        update_option ('paypal_pack_transfer',$save_data);

        if($json_resp['state']=='approved' ){
            if( wpestate_check_downgrade_situation($current_user->ID,$pack_id) ){
                wpestate_downgrade_to_pack( $current_user->ID, $pack_id );
                wpestate_upgrade_user_membership($current_user->ID,$pack_id,1,'');
            }else{
                wpestate_upgrade_user_membership($current_user->ID,$pack_id,1,'');
            }
            wp_redirect( $dash_profile_link ); exit();
        }
    }else{
        $payment_execute = array();
        $json       = json_encode($payment_execute);
        $json_resp  = wpestate_make_post_call($payment_execute_url, $json,$token);

        if( isset($json_resp['state']) && $json_resp['state']=='Active'){
            if( wpestate_check_downgrade_situation($current_user->ID,$pack_id) ){
                wpestate_downgrade_to_pack( $current_user->ID, $pack_id );
                wpestate_upgrade_user_membership($current_user->ID,$pack_id,2,'');
            }else{
                wpestate_upgrade_user_membership($current_user->ID,$pack_id,2,'');
            }

            // canel curent agrement
            update_user_meta($current_user->ID,'paypal_agreement',$json_resp['id']);

            wp_redirect( $dash_profile_link );
            exit();

        }
    }

    update_option('paypal_pack_transfer','');
}




//////////////////////////////////////////////////////////////////////////////////////////
// 3rd party login code
//////////////////////////////////////////////////////////////////////////////////////////

if( ( isset($_SESSION['wpestate_is_fb'] )  && $_SESSION['wpestate_is_fb'] =   'ison'  && isset($_GET['code']) && isset($_GET['state']) ) ){
    $wpestate_social_login->facebook_authentificate_user();
} else if ( isset($_SESSION['wpestate_is_google'] )  && $_SESSION['wpestate_is_google'] =   'ison'  &&  isset($_GET['code'])){
    $wpestate_social_login->google_authentificate_user();
} else if( isset($_SESSION['wpestate_is_twet'] )  && $_SESSION['wpestate_is_twet'] =   'ison'  && isset($_REQUEST['oauth_verifier'])){
    $wpestate_social_login->twiter_authentificate_user();
} else{
    if ( !is_user_logged_in() ) {
        wp_redirect( esc_url(home_url('/')) );exit();
        exit();
    }
}
unset($_SESSION['token_tw']);
unset($_SESSION['token_secret_tw']);
unset($_SESSION['wpestate_is_twet']);
unset($_SESSION['wpestate_is_fb']);
unset($_SESSION['wpestate_is_google']);


$paid_submission_status         =   esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( wprentals_get_option('wp_estate_price_submission','') );
$submission_curency_status      =   esc_html( wprentals_get_option('wp_estate_submission_curency','') );
$edit_link                      =   wpestate_get_template_link('user_dashboard_add_step1.php');
$processor_link                 =   wpestate_get_template_link('processor.php');
$wpestate_options               =   wpestate_page_details($post->ID);
get_header();
?>


<div class="row is_dashboard">
    <?php
    if( wpestate_check_if_admin_page($post->ID) ){
        if ( is_user_logged_in() ) {
            include(locate_template('templates/user_menu.php' ) );
        }
    }
    ?>

    <div class=" dashboard-margin">
        <?php
          wprentals_dashboard_header_display();
          include(locate_template('dashboard/templates/user_profile.php') );
        ?>
    </div>
</div>



<?php get_footer(); ?>
