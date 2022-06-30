<?php
// Template Name: User Dashboard Edit
// Wp Estate Pack

if ( !is_user_logged_in() ) {
     wp_redirect( esc_url( home_url('/') )  );exit();
}
if ( !wpestate_check_user_level()){
   wp_redirect(  esc_url( home_url('/') ) );exit();
}


global $show_err;
global $edit_id;
$current_user                   =   wp_get_current_user();
$userID                         =   $current_user->ID;
$user_pack                      =   get_the_author_meta( 'package_id' , $userID );
$status_values                  =   esc_html( wprentals_get_option('wp_estate_status_list','') );
$status_values_array            =   explode(",",$status_values);

$allowed_html                   =   array();
$submission_page_fields         =   ( wprentals_get_option('wp_estate_submission_page_fields','') );



if( isset( $_GET['listing_edit'] ) && is_numeric( $_GET['listing_edit'] ) ){
    ///////////////////////////////////////////////////////////////////////////////////////////
    /////// If we have edit load current values
    ///////////////////////////////////////////////////////////////////////////////////////////
    $edit_id                        =  intval ($_GET['listing_edit']);

    $the_post= get_post( $edit_id);
    if( $current_user->ID != $the_post->post_author ) {
        exit('You don\'t have the rights to edit this');
    }

    $show_err                       =   '';
    $action                         =   'edit';
    $submit_title                   =   get_the_title($edit_id);
    $submit_description             =   get_post_field('post_content', $edit_id);

    $action_array=array("description","location","price","details","images","amenities","calendar");

    if ( isset( $_GET['action'] ) && in_array( $_GET['action'],$action_array) ){

        $action =sanitize_text_field(  wp_kses ( $_GET['action'],$allowed_html) );

        if ($action == 'description'){
            ///////////////////////////////////////////////////////////////////////////////////////
            // action description
            ///////////////////////////////////////////////////////////////////////////////////////
            $prop_category_array            =   get_the_terms($edit_id, 'property_category');
            if(isset($prop_category_array[0])){
                 $prop_category_selected   =   $prop_category_array[0]->term_id;
            }

            $prop_action_category_array     =   get_the_terms($edit_id, 'property_action_category');
            if(isset($prop_action_category_array[0])){
                $prop_action_category_selected           =   $prop_action_category_array[0]->term_id;
            }


            $property_city_array            =   get_the_terms($edit_id, 'property_city');

            if(isset($property_city_array [0])){
                  $property_city                  =   $property_city_array [0]->name;
            }

            $property_area_array            =   get_the_terms($edit_id, 'property_area');
            if(isset($property_area_array [0])){
                  $property_area                  =   $property_area_array [0]->name;
            }

            $guestnumber            =  get_post_meta($edit_id, 'guest_no', true);
            $property_country       =  esc_html   ( get_post_meta($edit_id, 'property_country', true) );
            $property_admin_area    =  esc_html   ( get_post_meta($edit_id, 'property_admin_area', true) );
            $instant_booking        =  esc_html   ( get_post_meta($edit_id, 'instant_booking', true) );
            $children_as_guests       =  esc_html   ( get_post_meta($edit_id, 'children_as_guests', true) );
            $private_notes          =  esc_html   ( get_post_meta($edit_id, 'private_notes', true) );

            if($instant_booking==1){
                $instant_booking = 'checked';
            }
             if($children_as_guests==1){
                $children_as_guests = 'checked';
            }

            $submit_affiliate                 =   esc_html   ( get_post_meta($edit_id, 'property_affiliate', true) );
            
        
            $max_extra_guest_no             =   floatval   ( get_post_meta($edit_id, 'max_extra_guest_no', true) );
            $overload_guest                 =   floatval   ( get_post_meta($edit_id, 'overload_guest', true) );
            if($overload_guest==1){
                $overload_guest = 'checked';
            }
            ///////////////////////////////////////////////////////////////////////////////////////
            // action description
            ///////////////////////////////////////////////////////////////////////////////////////
        }else if ($action =='location'){
             ///////////////////////////////////////////////////////////////////////////////////////
            // action location
            ///////////////////////////////////////////////////////////////////////////////////////
            $property_country       =  esc_html   ( get_post_meta($edit_id, 'property_country', true) );
            $property_latitude      =  esc_html   ( get_post_meta($edit_id, 'property_latitude', true) );
            $property_longitude     =  esc_html   ( get_post_meta($edit_id, 'property_longitude', true) );
            $google_camera_angle    =  esc_html   ( get_post_meta($edit_id, 'google_camera_angle', true) );
            $property_address       =  esc_html   ( get_post_meta($edit_id, 'property_address', true) );
            $property_zip           =  esc_html   ( get_post_meta($edit_id, 'property_zip', true) );
            $property_state         =  esc_html   ( get_post_meta($edit_id, 'property_state', true) );
            $property_county        =  esc_html   ( get_post_meta($edit_id, 'property_county', true) );


            $property_city_array            =   get_the_terms($edit_id, 'property_city');
            if(isset($property_city_array [0])){
                  $property_city                  =   $property_city_array [0]->name;
            }
            ///////////////////////////////////////////////////////////////////////////////////////
            // action location
            ///////////////////////////////////////////////////////////////////////////////////////
        }else if ($action =='price'){


            ///////////////////////////////////////////////////////////////////////////////////////
            // action price
            ///////////////////////////////////////////////////////////////////////////////////////
            $local_booking_type             =   floatval   ( get_post_meta($edit_id, 'local_booking_type', true) );
            $property_price                 =   floatval   ( get_post_meta($edit_id, 'property_price', true) );
            $cleaning_fee                   =   floatval   ( get_post_meta($edit_id, 'cleaning_fee', true) );
            $city_fee                       =   floatval   ( get_post_meta($edit_id, 'city_fee', true) );
            $property_label                 =   esc_html   ( get_post_meta($edit_id, 'property_label', true) );
            $property_price_week            =   floatval   ( get_post_meta($edit_id, 'property_price_per_week', true) );
            $property_price_month           =   floatval   ( get_post_meta($edit_id, 'property_price_per_month', true) );

            $cleaning_fee_per_day           =   floatval   ( get_post_meta($edit_id,  'cleaning_fee_per_day', true) );
            $city_fee_per_day               =   floatval   ( get_post_meta($edit_id,  'city_fee_per_day', true) );
            $city_fee_percent               =   floatval   ( get_post_meta($edit_id,  'city_fee_percent', true) );

    $extra_price_per_guest          =   floatval   ( get_post_meta($edit_id, 'extra_price_per_guest', true) );

            $security_deposit               =   floatval   ( get_post_meta($edit_id,  'security_deposit', true) );
            $property_price_after_label     =   esc_html   ( get_post_meta($edit_id,  'property_price_after_label', true) );
            $property_price_before_label    =   esc_html   ( get_post_meta($edit_id,  'property_price_before_label', true) );
            $extra_pay_options              =   ( get_post_meta($edit_id,  'extra_pay_options', true) );
            $early_bird_percent             =   floatval   ( get_post_meta($edit_id,  'early_bird_percent', true) );
            $early_bird_days                =   floatval   ( get_post_meta($edit_id,  'early_bird_days', true) );
            $property_taxes                 =   floatval   ( get_post_meta($edit_id,  'property_taxes', true) );
            $price_per_guest_from_one       =   floatval   ( get_post_meta($edit_id, 'price_per_guest_from_one', true) );
           
            $checkin_change_over            =   floatval   ( get_post_meta($edit_id, 'checkin_change_over', true) );
            $checkin_checkout_change_over   =   floatval   ( get_post_meta($edit_id, 'checkin_checkout_change_over', true) );
            $min_days_booking               =   floatval   ( get_post_meta($edit_id, 'min_days_booking', true) );
          
            $price_per_weekeend             =   floatval   ( get_post_meta($edit_id, 'price_per_weekeend', true) );
            $booking_start_hour             =   floatval   ( get_post_meta($edit_id, 'booking_start_hour', true) );
            $booking_end_hour               =   floatval   ( get_post_meta($edit_id, 'booking_end_hour', true) );
 
            
            if($city_fee_percent==1){
                $city_fee_percent= 'checked';
            }

            if($price_per_guest_from_one==1){
                $price_per_guest_from_one = 'checked';
            }

         

            if($property_price==0){
                $property_price='';
            }

            if($cleaning_fee==0){
                $cleaning_fee='';
            }

            if($city_fee==0){
                $city_fee='';
            }

            if($property_label==0){
                $property_label='';
            }

            if($property_price_week==0){
                $property_price_week='';
            }

            if($property_price_month==0){
                $property_price_month='';
            }

            if($min_days_booking==0){
                $min_days_booking='';
            }

            if($price_per_weekeend==0){
                $price_per_weekeend='';
            }



            ///////////////////////////////////////////////////////////////////////////////////////
            // action price
            ///////////////////////////////////////////////////////////////////////////////////////

        }else if ($action =='details'){
            ///////////////////////////////////////////////////////////////////////////////////////
            // action details
            ///////////////////////////////////////////////////////////////////////////////////////
            $property_size      =   floatval   ( get_post_meta($edit_id, 'property_size', true) );
            if($property_size==0){
                $property_size='';
            }
            $property_rooms     =   floatval   ( get_post_meta($edit_id, 'property_rooms', true) );
            if($property_rooms==0){
                $property_rooms='';
            }
            $property_bedrooms  =   floatval   ( get_post_meta($edit_id, 'property_bedrooms', true) );
            if($property_bedrooms==0){
                $property_bedrooms='';
            }
            $property_bathrooms =   floatval   ( get_post_meta($edit_id, 'property_bathrooms', true) );
            if($property_bathrooms==0){
                $property_bathrooms='';
            }

            $custom_fields =    wprentals_get_option('wpestate_custom_fields_list','');

            $i=0;
            if( !empty($custom_fields)){
                while($i< count($custom_fields) ){
                   $name    =   $custom_fields[$i][0];
                   $type    =   $custom_fields[$i][2];
                   $slug    =   wpestate_limit45(sanitize_title( $name ));
                   $slug    =   sanitize_key($slug);

                   $custom_fields_array[$slug]=esc_html(get_post_meta($edit_id, $slug, true));
                   $i++;
                }
            }

            $extra_options =  get_post_meta($edit_id,'listing_extra_options',true);

            ///////////////////////////////////////////////////////////////////////////////////////
            // action details
            ///////////////////////////////////////////////////////////////////////////////////////

        }else if ($action =='images'){
            ///////////////////////////////////////////////////////////////////////////////////////
            // action images
            ///////////////////////////////////////////////////////////////////////////////////////
            $virtual_tour       =   get_post_meta($edit_id, 'virtual_tour', true);
            $embed_video_id     =   esc_html ( get_post_meta($edit_id, 'embed_video_id', true) );
            $option_video       =   '';
            $video_values       =   array('vimeo', 'youtube');
            $video_type         =   esc_html ( get_post_meta($edit_id, 'embed_video_type', true) );
            foreach ($video_values as $value) {
                $option_video.='<option value="' . $value . '"';
                if ($value == $video_type) {
                    $option_video.='selected="selected"';
                }
                $option_video.='>' . $value . '</option>';
            }
            ///////////////////////////////////////////////////////////////////////////////////////
            // action images
            ///////////////////////////////////////////////////////////////////////////////////////

        }else if ($action =='amenities'){

            $terms = get_terms( array(
                'taxonomy' => 'property_features',
                'hide_empty' => false,
            ) );
            foreach($terms as $key => $term){
                $post_var_name      =   wpestate_limit45($term->slug);

                if(isset( $_POST[$post_var_name])){
                    $feature_value  =   sanitize_text_field( $_POST[$post_var_name]);
                //    update_post_meta($edit_id, $post_var_name, $feature_value);
                    $moving_array[] =   $post_var_name;
                }
            }




        } else if ($action =='calendar'){
           $property_icalendar_import_multi =   get_post_meta($edit_id, 'property_icalendar_import_multi', true);
        }

    }else{
        exit();
    }

}

get_header();
$wpestate_options   =   wpestate_page_details($post->ID);
$price_array        =   wpml_custom_price_adjust($edit_id);
$mega_details_array =   wpml_mega_details_adjust($edit_id);


///////////////////////////////////////////////////////////////////////////////////////////
/////// Html Form Code below
///////////////////////////////////////////////////////////////////////////////////////////
?>

<div id="cover"></div>
<div class="row is_dashboard">
    <?php
    if( wpestate_check_if_admin_page($post->ID) ){
        if ( is_user_logged_in() ) {
            include(locate_template('templates/user_menu.php'));
        }
    }
    ?>

    <div class="dashboard-margin">
        <?php       wprentals_dashboard_header_display(); ?>

        <div class="user_dashboard_panel">
            <?php include(locate_template('templates/submission_guide.php') );?>


            <div class="row ">
            <?php

                if (isset($_GET['isnew']) && ($_GET['isnew']==1 ) ){
                    print ' <div class="col-md-12 new-listing-alert">'.esc_html__( 'Congratulations, you have just added a new listing! Now go and fill in the rest of the details.','wprentals').'</div>';
                }

                if ($action == 'description'){
                    include(locate_template('templates/submit_templates/property_description.php') ) ;
                }else if ($action =='location'){
                    include(locate_template('templates/submit_templates/property_location.php') );
                }else if ($action =='price'){
                    include(locate_template('templates/submit_templates/property_price.php') );
                }else if ($action =='details'){
                    include(locate_template('templates/submit_templates/property_details.php') );
                }else if ($action =='images'){
                    include(locate_template('templates/submit_templates/property_images.php') );
                }else if ($action =='amenities'){
                    include(locate_template('templates/submit_templates/property_amenities.php') );
                }else if ($action =='calendar'){
                    include(locate_template('templates/submit_templates/property_calendar.php') );
                }
            ?>
            </div>
          </div>
    </div>
</div>
<?php get_footer();?>
