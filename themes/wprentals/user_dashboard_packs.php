<?php
// Template Name: User Dashboard Subscriptions
// Wp Estate Pack

if ( !is_user_logged_in() ) {
    wp_redirect( esc_url(home_url('/')) );exit();
}
if ( !wpestate_check_user_level()){
    wp_redirect( esc_url(home_url('/')) );exit();
}

global $current_user;
$current_user = wp_get_current_user();
$paid_submission_status         =   esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( wprentals_get_option('wp_estate_price_submission','') );
$submission_curency_status      =   wpestate_curency_submission_pick();
$userID                         =   $current_user->ID;
$user_option                    =   'favorites'.$userID;
$wpestate_curent_fav            =   get_option($user_option);
$show_remove_fav                =   1;
$show_compare                   =   1;
$wpestate_show_compare_only     =   'no';
$wpestate_currency              =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
get_header();
$wpestate_options               =   wpestate_page_details($post->ID);
?>

<div class="row is_dashboard">
    <?php
    if( wpestate_check_if_admin_page($post->ID) ){
        if ( is_user_logged_in() ) {
            include(locate_template('templates/user_menu.php') );
        }
    }
    ?>

    <div class=" dashboard-margin">
      <div class="wprentals_dashboard_container">
        <?php wprentals_dashboard_header_display(); ?>
        <div class="row">



        <?php
        $paid_submission_status = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
        if ($paid_submission_status == 'membership'){
        ?>

        <div class="col-md-12">
            <div class="user_dashboard_panel change_your_package">
            <h4 class="user_dashboard_panel_title"><?php esc_html_e('Change your Package','wprentals');?></h4>
                <div class="col-md-4">
                    <?php wpestate_display_packages(); ?>
                    <input type="checkbox" name="pack_recuring" id="pack_recuring" value="1" />
                    <label for="pack_recurring"><?php esc_html_e('make payment recurring ','wprentals');?></label>
                </div>

                <?php
                    $enable_paypal_status   =   esc_html ( wprentals_get_option('wp_estate_enable_paypal','') );
                    $enable_stripe_status   =   esc_html ( wprentals_get_option('wp_estate_enable_stripe','') );
                    $enable_direct_pay      =   esc_html ( wprentals_get_option('wp_estate_enable_direct_pay','') );

                    print '<div class="col-md-8 payments_buttons_wrapper pay_disabled">';
                    if($enable_paypal_status==='yes'){
                        print '<div id="pick_pack">'.esc_html__( 'Pay with Paypal','wprentals').'</div>';
                    }
                    if($enable_stripe_status==='yes'){
                        wpestate_show_stripe_form_membership();
                    }

                    if($enable_direct_pay==='yes'){
                        print '<div id="direct_pay">'.esc_html__( 'Pay via Wire transfer','wprentals').'</div>';
                    }

                    print '</div>';
                ?>

            </div>
        </div>


        <?php
        $wpestate_currency  =   wpestate_curency_submission_pick();
        $args = array(
            'post_type'         => 'membership_package',
            'posts_per_page'    => -1,
            'meta_query'        =>  array(
                                        array(
                                        'key' => 'pack_visible',
                                        'value' => 'yes',
                                        'compare' => '=',
                                    )
                                )
        );

        $pack_selection = new WP_Query($args);

            print '<div class="pack-wrapper">';
                while($pack_selection->have_posts() ){
                    $pack_selection->the_post();
                    include(locate_template('dashboard/templates/dashboard_pack_unit.php') );
                }
            print '</div>';
        }
        ?>
      </div>
</div>
<?php
$ajax_nonce = wp_create_nonce( "wprentals_payments_actions_nonce" );
print'<input type="hidden" id="wprentals_payments_actions" value="'.esc_html($ajax_nonce).'" />    ';

wp_reset_query();
get_footer(); ?>
