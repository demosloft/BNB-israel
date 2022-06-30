<?php
// Single property
// Wp Estate Pack
$status = get_post_status($post->ID);

if ( !is_user_logged_in() ) {
    if($status==='expired'){
        wp_redirect(  esc_url( home_url('/') ) );exit;
    }
}else{
    if(!current_user_can('administrator') ){
        if(  $status==='expired'){
            wp_redirect(  esc_url( home_url('/') ) );exit;
        }
    }
}


get_header();


global $wpestate_propid ;
global $post_attachments;
global $wpestate_options;
global $wpestate_where_currency;
global $wpestate_property_description_text;
global $wpestate_property_details_text;
global $wpestate_property_details_text;
global $wpestate_property_adr_text;
global $wpestate_property_price_text;
global $wpestate_property_pictures_text;
global $wpestate_propid;
global $wpestate_gmap_lat;
global $wpestate_gmap_long;
global $wpestate_unit;
global $wpestate_currency;
global $wpestate_use_floor_plans;

$current_user               =   wp_get_current_user();
$wpestate_propid            =   $post->ID;
$wpestate_options           =   wpestate_page_details($post->ID);
$wpestate_gmap_lat          =   floatval( get_post_meta($post->ID, 'property_latitude', true));
$gmap_long                  =   floatval( get_post_meta($post->ID, 'property_longitude', true));
$wpestate_unit              =   esc_html( wprentals_get_option('wp_estate_measure_sys', '') );
$wpestate_currency          =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_use_floor_plans   =   intval( get_post_meta($post->ID, 'use_floor_plans', true) );


if (function_exists('icl_translate') ){
    $wpestate_where_currency                      =   icl_translate('wprentals','wp_estate_where_currency_symbol', esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') ) );
    $wpestate_property_description_text           =   icl_translate('wprentals','wp_estate_property_description_text', esc_html( wprentals_get_option('wp_estate_property_description_text') ) );
    $wpestate_property_details_text               =   icl_translate('wprentals','wp_estate_property_details_text', esc_html( wprentals_get_option('wp_estate_property_details_text') ) );
    $wpestate_property_features_text              =   icl_translate('wprentals','wp_estate_property_features_text', esc_html( wprentals_get_option('wp_estate_property_features_text') ) );
    $wpestate_property_adr_text                   =   icl_translate('wprentals','wp_estate_property_adr_text', esc_html( wprentals_get_option('wp_estate_property_adr_text') ) );
    $wpestate_property_price_text                 =   icl_translate('wprentals','wp_estate_property_price_text', esc_html( wprentals_get_option('wp_estate_property_price_text') ) );
    $wpestate_property_pictures_text              =   icl_translate('wprentals','wp_estate_property_pictures_text', esc_html( wprentals_get_option('wp_estate_property_pictures_text') ) );
    $wp_estate_sleeping_text                      =   icl_translate('wprentals','wp_estate_sleeping_text', esc_html( wprentals_get_option('wp_estate_sleeping_text') ) );
    $wp_estate_terms_text                         =   icl_translate('wprentals','wp_estate_terms_text', esc_html( wprentals_get_option('wp_estate_terms_text') ) );
}else{
    $wpestate_where_currency                      =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
    $property_description_text                    =   esc_html( wprentals_get_option('wp_estate_property_description_text') );
    $wpestate_property_details_text               =   esc_html( wprentals_get_option('wp_estate_property_details_text') );
    $wpestate_property_features_text              =   esc_html( wprentals_get_option('wp_estate_property_features_text') );
    $wpestate_property_adr_text                   =   stripslashes ( esc_html( wprentals_get_option('wp_estate_property_adr_text') ) );
    $wpestate_property_price_text                 =   esc_html( wprentals_get_option('wp_estate_property_price_text') );
    $wpestate_property_pictures_text              =   esc_html( wprentals_get_option('wp_estate_property_pictures_text') );
    $wp_estate_sleeping_text                      =   esc_html( wprentals_get_option('wp_estate_sleeping_text') );
    $wp_estate_terms_text                         =   esc_html( wprentals_get_option('wp_estate_terms_text') );
}


$agent_id                   =   '';
$content                    =   '';
$userID                     =   $current_user->ID;
$user_option                =   'favorites'.$userID;
$wpestate_curent_fav        =   get_option($user_option);
$favorite_class             =   'isnotfavorite';
$favorite_text              =   esc_html__( 'Add to Favorites','wprentals');
$pinteres                   =   array();
$property_city              =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area              =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
$property_category          =   get_the_term_list($post->ID, 'property_category', '', ', ', '') ;
$property_category_terms    =   get_the_terms( $post->ID, 'property_category' );

if(is_array($property_category_terms) ){
    $temp                       =   array_pop($property_category_terms);
    $property_category_terms_icon =   $temp->slug;
    $place_id                   =   $temp->term_id;
    $term_meta                  =   get_option( "taxonomy_$place_id");
    if( isset($term_meta['category_icon_image']) && $term_meta['category_icon_image']!='' ){
        $property_category_terms_icon=$term_meta['category_icon_image'];
    }else{
        $property_category_terms_icon =  get_template_directory_uri().'/img/'.esc_html($temp->slug).'-ico.png';
    }
}



$property_action            =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');
$property_action_terms      =   get_the_terms( $post->ID, 'property_action_category' );

if(is_array($property_action_terms) ){
    $temp                       =   array_pop($property_action_terms);
    $place_id                   =   $temp->term_id;
    $term_meta                  =   get_option( "taxonomy_$place_id");
    if( isset($term_meta['category_icon_image']) && $term_meta['category_icon_image']!='' ){
        $property_action_terms_icon=$term_meta['category_icon_image'];
    }else{
        $property_action_terms_icon =  get_template_directory_uri().'/img/'.esc_html($temp->slug).'-ico.png';
    }
}

$slider_size                =   'small';
$guests                     =   floatval( get_post_meta($post->ID, 'guest_no', true));
$bedrooms                   =   floatval( get_post_meta($post->ID, 'property_bedrooms', true));
$bathrooms                  =   floatval( get_post_meta($post->ID, 'property_bathrooms', true));



if($wpestate_curent_fav){
    if ( in_array ($post->ID,$wpestate_curent_fav) ){
        $favorite_class =   'isfavorite';
        $favorite_text  =   esc_html__( 'Favorite','wprentals').'<i class="fas fa-heart"></i>';
    }
}

if (has_post_thumbnail()){
    $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'wpestate_property_full_map');
}


if($wpestate_options['content_class']=='col-md-12'){
    $slider_size='full';
}


 $listing_page_type    =   wprentals_get_option('wp_estate_listing_page_type','');


 if($listing_page_type == 4){
    include(locate_template('templates/listing_page_4.php'));
 }else if($listing_page_type == 3){
    include(locate_template('templates/listing_page_3.php'));
 }else if($listing_page_type == 2){
    include(locate_template('templates/listing_page_2.php'));
 }else{
    include(locate_template('templates/listing_page_1.php'));
 }

  include(locate_template('templates/mobile_booking.php'));

$ajax_nonce = wp_create_nonce( "wprentals_add_booking_nonce" );
print'<input type="hidden" id="wprentals_add_booking" value="'.esc_html($ajax_nonce).'" />';
wp_estate_count_page_stats($post->ID);
?>
