<?php
// Template Name: User Dashboard Favorite
// Wp Estate Pack

if ( !is_user_logged_in() ) {
     wp_redirect( esc_url(home_url('/')) );exit();
}

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
$wpestate_options=wpestate_page_details($post->ID);
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

        <?php       wprentals_dashboard_header_display(); ?>


        <div class="row  user_dashboard_panel dashboard_property_list favorite_dashboard_property_list">
          <div class="wpestate_dashboard_table_list_header row">
            <div class="col-md-5"><?php esc_html_e('Property','wprentals'); ?></div>
            <div class="col-md-2"><?php esc_html_e('Reviews','wprentals'); ?></div>
            <div class="col-md-1"><?php esc_html_e('Price','wprentals'); ?></div>
            <div class="col-md-2"><?php esc_html_e('Status','wprentals'); ?></div>
            <div class="col-md-2"><?php esc_html_e('Actions','wprentals'); ?></div>
          </div>
        <?php
        if( !empty($wpestate_curent_fav)){
            $args = array(
                'post_type'        => 'estate_property',
                'post_status'      => 'publish',
                'posts_per_page'   => -1 ,
                'post__in'         => $wpestate_curent_fav
            );


            $prop_selection = new WP_Query($args);
            $counter = 0;
            $wpestate_options['related_no']=4;

            while ($prop_selection->have_posts()): $prop_selection->the_post();
                include(locate_template('dashboard/templates/dashboard_listing_unit.php') );
            endwhile;

        }else{
            print '<h4 class="no_favorites">'.esc_html__( 'You don\'t have any favorite properties yet!','wprentals').'</h4>';
        }
        ?>
        </div>
    </div>
</div>

<?php
wp_reset_query();
get_footer();
?>
