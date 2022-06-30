<?php
global $edit_link;
global $token;
global $processor_link;
global $paid_submission_status;
global $submission_curency_status;
global $price_submission;
global $floor_link;
global $show_remove_fav;
global $wpestate_curent_fav;
global $th_separator;
global $user_pack;
global $userID;
$extra= array(
        'class'         =>  'lazyload img-responsive',
        );

$post_id                    =   get_the_ID();

$edit_link                  =   esc_url_raw ( add_query_arg( 'listing_edit', $post_id, $edit_link) ) ;
$edit_link                  =   esc_url_raw ( add_query_arg( 'action', 'description', $edit_link) ) ;
$floor_link                 =   esc_url_raw ( add_query_arg( 'floor_edit', esc_url($post_id), $floor_link) ) ;

$property_address           =   esc_html ( get_post_meta($post_id, 'property_address', true) );
$property_city              =   get_the_term_list($post_id, 'property_city', '', ', ', '') ;
$property_category          =   get_the_term_list($post_id, 'property_category', '', ', ', '');
$property_action_category   =   get_the_term_list($post_id, 'property_action_category', '', ', ', '');

$wpestate_currency          =   wpestate_curency_submission_pick();
$currency_title             =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_where_currency    =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
$status                     =   '';
$link                       =  esc_url ( get_permalink($post_id));


$price_submission           =   floatval( wprentals_get_option('wp_estate_price_submission','') );
$price_featured_submission  =   floatval( wprentals_get_option('wp_estate_price_featured_submission','') );



$fav_mes        =   esc_html__( 'add to favorites','wprentals');
if($wpestate_curent_fav){
    if ( in_array ($post->ID,$wpestate_curent_fav) ){
    $favorite_class =   'icon-fav-on';
    $fav_mes        =   esc_html__( 'remove from favorites','wprentals');
    }
}






$free_feat_list_expiration  =   intval ( wprentals_get_option('wp_estate_free_feat_list_expiration','') );
$pfx_date                   =   strtotime ( get_the_date("Y-m-d",  $post->ID ) );
$expiration_date            =   $pfx_date+$free_feat_list_expiration*24*60*60;
$user_pack                  =   get_the_author_meta( 'package_id' , $userID );
$favorite_pay_details       =   '';

if( is_page_template( 'user_dashboard_favorite.php' ) ){
    $favorite_pay_details='favorite pay_details';
}
?>

<div class="col-md-12 dasboard-prop-listing">

        <div class="col-md-5 blog_listing_image">
          <?php   include(locate_template('dashboard/templates/unit-templates/property-unit-image.php') );  ?>
          <?php   include(locate_template('dashboard/templates/unit-templates/property-unit-title.php') );  ?>
        </div>

        <div class="col-md-2 property_dashboard_reviews">
          <?php   include(locate_template('dashboard/templates/unit-templates/property-unit-reviews.php') );  ?>
        </div>

        <div class="col-md-1 property_dashboard_price">
          <?php   include(locate_template('dashboard/templates/unit-templates/property-unit-price.php') );  ?>
        </div>

        <div class="col-md-2 property_dashboard_status">
          <?php   include(locate_template('dashboard/templates/unit-templates/property-status.php') );  ?>
        </div>

        <div class="col-md-2 property_dashboard_actions <?php echo 'payment_sys_'.sanitize_key($paid_submission_status); ?>">
          <?php   include(locate_template('dashboard/templates/unit-templates/property-unit-actions.php') );  ?>
        </div>
 </div>
