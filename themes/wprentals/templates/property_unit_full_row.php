<?php
global $wpestate_curent_fav;
global $wpestate_currency;
global $wpestate_where_currency;
global $show_compare;
global $wpestate_show_compare_only;
global $show_remove_fav;
global $wpestate_options;
global $isdashabord;
global $align;
global $align_class;
global $is_shortcode;
global $row_number;
global $wpestate_book_from;
global $wpestate_book_to;
$pinterest          =   '';
$previe             =   '';
$compare            =   '';
$extra              =   '';
$property_size      =   '';
$property_bathrooms =   '';
$property_rooms     =   '';
$measure_sys        =   '';
$preview        =   array();
$preview[0]     =   '';
$favorite_class =   'icon-fav-off';
$fav_mes        =   esc_html__( 'add to favorites','wprentals');
if($wpestate_curent_fav){
    if ( in_array ($post->ID,$wpestate_curent_fav) ){
    $favorite_class =   'icon-fav-on';
    $fav_mes        =   esc_html__( 'remove from favorites','wprentals');
    }
}

$link                       =   esc_url ( get_permalink());
$wprentals_is_per_hour      =   wprentals_return_booking_type($post->ID);
$link                       =   wprentals_card_link_autocomplete($post->ID,$link,$wprentals_is_per_hour);

?>
<div class="places_wrapper   places_wrapper<?php print esc_attr($row_number);?>" data-link="<?php print esc_attr($link);?>">
    <div class="places<?php print esc_attr($row_number);?> places_listing"  data-listid="<?php print intval($post->ID);?>" ><?php

            $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_full_map');
            $preview   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_places');
            $compare   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_slider_thumb');
            $extra= array(
                'class'         =>  'b-lazy img-responsive',
            );
            if(isset($preview[0])){
                $extra['data-original']=$preview[0];
            }


            $thumb_prop             =   get_the_post_thumbnail($post->ID, 'wpestate_property_places',$extra);
            $thumb_prop             =   wp_get_attachment_image_src( get_post_thumbnail_id(), 'wpestate_property_featured');

            $wpestate_property_featured_img=get_stylesheet_directory_uri().'/img/defaultimage_prop1.jpg';
            if(isset( $thumb_prop[0]) ){
                $wpestate_property_featured_img=$thumb_prop[0];
            }

            $prop_stat              =   stripslashes ( esc_html( get_post_meta($post->ID, 'property_status', true) )  );
            $featured               =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );
            $property_rooms         =   get_post_meta($post->ID, 'property_bedrooms', true);

            if($property_rooms!=''){
                $property_rooms=intval($property_rooms);
            }

            $property_bathrooms     =   get_post_meta($post->ID, 'property_bathrooms', true) ;
            if($property_bathrooms!=''){
                $property_bathrooms=floatval($property_bathrooms);
            }

            $property_size          =   get_post_meta($post->ID, 'property_size', true) ;
            if($property_size){
                $property_size = number_format(intval($property_size));
            }

            $agent_id           =   intval( get_post_meta($post->ID, 'property_agent', true) );
            $thumb_id_agent     =   get_post_thumbnail_id($agent_id);
            $preview_agent      =   wp_get_attachment_image_src($thumb_id_agent, 'wpestate_user_thumb');
            $preview_agent_img  =   '';
            if(isset($preview_agent[0])){
                $preview_agent_img  =   $preview_agent[0];
            }
     

            $agent_link         =   esc_url( get_permalink($agent_id) );
            $measure_sys        =   esc_html ( wprentals_get_option('wp_estate_measure_sys','') );
            $price              =   intval( get_post_meta($post->ID, 'property_price', true) );
            $property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
            $property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
            $price_label        =   '<span class="price_label">'.esc_html ( get_post_meta($post->ID, 'property_label', true) ).'</span>';

            if ($price != 0) {
                $price = number_format($price);

                if ($wpestate_where_currency == 'before') {
                    $price = $wpestate_currency . ' ' . $price;
                } else {
                    $price = $price . ' ' . $wpestate_currency;
                }
            }else{
                $price='';
            }

            print   '<div class="listing-hover-gradient"></div><div class="listing-hover" ></div>';
            print   '<div class="listing-unit-img-wrapper shortcodefull" style="background-image:url('.esc_url($wpestate_property_featured_img).')"></div>';

            echo wpestate_return_property_status($post->ID);
            print '</div>';



            print   '<div class="category_name">';
            if(wpestate_has_some_review($post->ID)!==0){
                print wpestate_display_property_rating( $post->ID );
            }
            print '<a class="featured_listing_title" href="'.esc_url($link).'">';
            $title=get_the_title();
            echo mb_substr( html_entity_decode($title), 0, 40);
            if(strlen($title)>40){
                echo '...';
            }


            print   '</a><div class="category_tagline">';
            if ($property_area != '') {
                print trim($property_area).', ';
            }
            print trim($property_city).'</div>';

    ?>
</div>
</div>
