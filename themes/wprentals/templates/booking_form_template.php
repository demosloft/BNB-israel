<?php
global $post;
global $current_user;
global $feature_list_array;
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
global $favorite_text;
global $favorite_class;
global $property_action_terms_icon;
global $property_action;
global $property_category_terms_icon;
global $property_category;
global $guests;
global $bedrooms;
global $bathrooms;
global $show_sim_two;
global $guest_list;
global $post_id;
$rental_type        =   wprentals_get_option('wp_estate_item_rental_type','');
$booking_type       =   wprentals_return_booking_type($post->ID);
?>

<div  itemprop="price"  class="listing_main_image_price">
    <?php
    $price_per_guest_from_one       =   floatval( get_post_meta($post->ID, 'price_per_guest_from_one', true) );
    $price                          =   floatval( get_post_meta($post->ID, 'property_price', true) );
    wpestate_show_price($post->ID,$wpestate_currency,$wpestate_where_currency,0); 
    print '<span class="pernight_label">';
    if($price!=0){
        if( $price_per_guest_from_one == 1){
            echo ' '.esc_html__( 'per guest','wprentals');
        }else{
            echo ' '.wpestate_show_labels('per_night',$rental_type,$booking_type);
        }
    }
    print '</span>';
    ?>
</div>


<?php echo wpestate_show_booking_form($post_id,$wpestate_options,$favorite_class,$favorite_text); ?>
