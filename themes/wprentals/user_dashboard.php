<?php
// Template Name: User Dashboard
// Wp Estate Pack
if ( !is_user_logged_in() ) {
    wp_redirect(  esc_url( home_url('/') ) );exit();
}

if ( !wpestate_check_user_level()){
   wp_redirect(  esc_url( home_url('/') ) );exit();
}

$current_user = wp_get_current_user();
$userID                         =   $current_user->ID;
$user_login                     =   $current_user->user_login;
$user_pack                      =   get_the_author_meta( 'package_id' , $userID );
$user_registered                =   get_the_author_meta( 'user_registered' , $userID );
$user_package_activation        =   get_the_author_meta( 'package_activation' , $userID );
$paid_submission_status         =   esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( wprentals_get_option('wp_estate_price_submission','') );
$submission_curency_status      =   wpestate_curency_submission_pick();
$edit_link                      =   wpestate_get_template_link('user_dashboard_edit_listing.php');
$floor_link                     =   '';
$processor_link                 =   wpestate_get_template_link('processor.php');
$th_separator                   =   wprentals_get_option('wp_estate_prices_th_separator','');
if( isset( $_GET['delete_id'] ) ) {
    if( !is_numeric($_GET['delete_id'] ) ){
        exit('you don\'t have the right to delete this');
    }else{
        $delete_id= intval ( $_GET['delete_id']);
        $the_post= get_post( $delete_id);
        if( $current_user->ID != $the_post->post_author ) {
            exit('you don\'t have the right to delete this');
        }else{
            // delete attchaments
            $arguments = array(
                'numberposts'   => -1,
                'post_type'     => 'attachment',
                'post_parent'   => $delete_id,
                'post_status'   => null,
                'exclude'       => get_post_thumbnail_id(),
                'orderby'       => 'menu_order',
                'order'         => 'ASC'
            );
            $post_attachments = get_posts($arguments);

            foreach ($post_attachments as $attachment) {
                wp_delete_post($attachment->ID);
            }


            wp_delete_post( $delete_id );



            $dash_link              =   wpestate_get_template_link('user_dashboard.php');
            wp_redirect(  esc_html( $dash_link ) );exit();
        }
    }
}

get_header();
$wpestate_options=wpestate_page_details($post->ID);
$new_mess=0;

$title_search='';
if( isset($_POST['wpestate_prop_title']) ){
    if (  ! isset( $_POST['wpestate_dash_search_nonce'] )  || ! wp_verify_nonce( $_POST['wpestate_dash_search_nonce'], 'wpestate_dash_search' ) ) {
        esc_html_e('your nonce does not validated','wprentals');
        exit();
    }else{
        $title_search=sanitize_text_field($_POST['wpestate_prop_title']);
    }
}
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
        <?php       wprentals_dashboard_header_display(); ?>

        <div class="row  user_dashboard_panel dashboard_property_list">
        <?php   include(locate_template('dashboard/templates/property-list-search.php') ); ?>

        <div class="wpestate_dashboard_table_list_header row">
          <div class="col-md-5"><?php esc_html_e('Property','wprentals'); ?></div>
          <div class="col-md-2"><?php esc_html_e('Reviews','wprentals'); ?></div>
          <div class="col-md-1"><?php esc_html_e('Price','wprentals'); ?></div>
          <div class="col-md-2"><?php esc_html_e('Status','wprentals'); ?></div>
          <div class="col-md-2"><?php esc_html_e('Actions','wprentals'); ?></div>
        </div>

        <?php
        $prop_no      =   intval( wprentals_get_option('wp_estate_prop_no', '') );
        $paged        = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
                'post_type'        =>  'estate_property',
                'author'           =>  $current_user->ID,
                'paged'             => $paged,
                'posts_per_page'    => $prop_no,
                'post_status'      =>  array( 'any' )
            );


        if($title_search!=''){
            $args['s']= $title_search;
            if(function_exists('wpestate_search_by_title_only_filter')){
                $prop_selection =   wpestate_search_by_title_only_filter($args);
            }
            $new_mess=1;
        }else{
            $prop_selection = new WP_Query($args);
        }

        if( !$prop_selection->have_posts() ){
            if($new_mess==1){
                print '<h4 class="no_favorites">'.esc_html__( 'No results!','wprentals').'</h4>';
            }else{
                print '<h4 class="no_list_yet">'.esc_html__( 'You don\'t have any properties yet!','wprentals').'</h4>';
            }
         }

        while ($prop_selection->have_posts()): $prop_selection->the_post();
            include(locate_template('dashboard/templates/dashboard_listing_unit.php') );
        endwhile;

        if($prop_selection->have_posts()):
            wprentals_pagination($prop_selection->max_num_pages, $range =2);
        endif;
        ?>
        </div>

    </div>
</div>

<?php


$ajax_nonce = wp_create_nonce( "wprentals_property_actions_nonce" );
print'<input type="hidden" id="wprentals_property_actions" value="'.esc_html($ajax_nonce).'" />    ';
$ajax_nonce2 = wp_create_nonce( "wprentals_payments_actions_nonce" );
print'<input type="hidden" id="wprentals_payments_actions" value="'.esc_html($ajax_nonce2).'" />    ';

wp_reset_query();
get_footer();
?>
