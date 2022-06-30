<?php
// Template Name:User Dashboard Main
// Wp Estate Pack
global $wpestate_social_login;
$current_user = wp_get_current_user();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ( !is_user_logged_in() ) {
    wp_redirect(  esc_url( home_url('/') ) );exit();
}

if ( !wpestate_check_user_level()){
   wp_redirect(  esc_url( home_url('/') ) );exit();
}


$paid_submission_status         =   esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( wprentals_get_option('wp_estate_price_submission','') );
$submission_curency_status      =   esc_html( wprentals_get_option('wp_estate_submission_curency','') );
$edit_link                      =   wpestate_get_template_link('user_dashboard_add_step1.php');
$processor_link                 =   wpestate_get_template_link('processor.php');
$wpestate_options               =   wpestate_page_details($post->ID);
$current_user                   =   wp_get_current_user();
$userID                         =   $current_user->ID;
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
        ?>

        <div class="col-md-8 wpestate_dashboard_holder">
          <?php
             print wpestate_dashboard_account_summary($userID);
             print wpestate_display_bookings_graph();
             print wpestate_dashboard_widget_top_ten($userID);
             print wpestate_dashboard_widget_top_ten_booked($userID);
          ?>
        </div>

        <div class="col-md-4 wpestate_dashboard_holder account_history_widget">
          <?php print wpestate_dashboard_widget_history(); ?>
        </div>

    </div>
</div>



<?php get_footer(); ?>
