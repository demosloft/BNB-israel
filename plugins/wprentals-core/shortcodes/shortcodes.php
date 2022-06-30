<?php
require('places_functions.php');
require('grids_shortcode.php');
require('recent_items_list.php');
require('recent_items_list_slider.php');
/*
 * 
 * 
 *  Testimonial sloder function
 * 
 * 
 * 
 * 
 * */

function wpestate_testimonial_slider($settings){
    //wp_enqueue_script('owl_carousel');
    $return_string='';
    
    if( isset( $settings['list']) && is_array($settings['list'])){
        $items_list='';
        foreach($settings['list'] as $key=>$testimonial):
            $items_list.='<div class="item">';
                $items_list.='<div class="item_testimonial_content">
                    <div class="item_testimonial_title">'.trim($testimonial['testimonial_title']).'</div>
                    <div class="item_testimonial_text">'.trim($testimonial['testimonial_text']).'</div>
                    <div class="item_testimonial_stars">'.wpestate_starts_reviews_core( floatval( $testimonial['testimonial_stars']) ) .'</div>
                    <div class="item_testimonial_name">'.trim($testimonial['testimonial_name']).'</div>
                    <div class="item_testimonial_job">'.trim($testimonial['testimonial_job']).'</div>
            
                    </div>';
                $items_list.='<div class="item_testimonal_image" style="background-image:url('.$testimonial['testimonial_image']['url'].');"></div>';
                
            $items_list.='</div>';
        endforeach;
   
    
     $slider_id='wpestate_testimonial_slider_'.rand(1,99999);
        
        
    $return_string .='<div class="owl-carousel owl-theme wpestate_testimonial_slider" id="'.$slider_id.'" data-auto="0">'. $items_list .'</div>';


    $return_string.= '
         <script type="text/javascript">
             //<![CDATA[
             jQuery(document).ready(function(){
                wpestate_testimonial_slider("'.$slider_id.'");
             });
             //]]>
         </script>';
     }
    return $return_string;
}






/*
 *
 * Map shortcode
 *
 *
 *
 */


if( !function_exists('wpestate_full_map_shortcode') ):
    function wpestate_full_map_shortcode($attributes, $content = null){

        $attributes = shortcode_atts(
                array(
                    'map_shortcode_for'     =>'no',
                    'map_shorcode_show_contact_form'=>'yes',
                    'map_height'            =>  600,
                    'map_snazy'            =>  '',
                    'map_zoom'              => 10,
                    'category_ids'          =>  '',
                    'action_ids'            =>  '',
                    'city_ids'              =>  '',
                    'area_ids'              =>  '',
                    'state_ids'             =>  '',
                    'status_ids'            =>  '',
                    'is_elementor'          =>  0,
        ), $attributes) ;

        if ( isset($attributes['map_shortcode_for']) ){
            $map_shortcode_for=$attributes['map_shortcode_for'];
        }

        if ( isset($attributes['map_shorcode_show_contact_form']) ){
            $map_shorcode_show_contact_form=$attributes['map_shorcode_show_contact_form'];
        }

        if ( isset($attributes['map_height']) ){
            $map_height=$attributes['map_height'];
        }


        $map_style='';
        if ( isset($attributes['map_snazy']) ){
            $map_style=$attributes['map_snazy'];
        }

        if ( isset($attributes['map_zoom']) ){
            $map_zoom=$attributes['map_zoom'];
        }
        if ( isset($attributes['category_ids']) ){
            $category=$attributes['category_ids'];
        }

        if ( isset($attributes['action_ids']) ){
            $action=$attributes['action_ids'];
        }

        if ( isset($attributes['city_ids']) ){
            $city=$attributes['city_ids'];
        }

        if ( isset($attributes['area_ids']) ){
            $area=$attributes['area_ids'];
        }

        if ( isset($attributes['state_ids']) ){
            $state=$attributes['state_ids'];
        }

        if ( isset($attributes['status_ids']) ){
            $status=$attributes['status_ids'];
        }

        $category_array='';
        $action_array='';
        $city_array='';
        $area_array='';


        // build category array
        if($category!=''){
            $category_of_tax=array();
            $category_of_tax=  explode(',', $category);
            $category_array=array(
                            'taxonomy'  => 'property_category',
                            'field'     => 'term_id',
                            'terms'     => $category_of_tax
                            );
        }


        // build action array
        if($action!=''){
            $action_of_tax=array();
            $action_of_tax=  explode(',', $action);
            $action_array=array(
                            'taxonomy'  => 'property_action_category',
                            'field'     => 'term_id',
                            'terms'     => $action_of_tax
                            );
        }

        // build city array
        if($city!=''){
            $city_of_tax=array();
            $city_of_tax=  explode(',', $city);
            $city_array=array(
                            'taxonomy'  => 'property_city',
                            'field'     => 'term_id',
                            'terms'     => $city_of_tax
                            );
        }

        // build city array
        if($area!=''){
            $area_of_tax=array();
            $area_of_tax=  explode(',', $area);
            $area_array=array(
                            'taxonomy'  => 'property_area',
                            'field'     => 'term_id',
                            'terms'     => $area_of_tax
                            );
        }



         $args = array(
                'post_type'         => 'estate_property',
                'post_status'       => 'publish',
                'paged'             => 1,
                'fields'            =>    'ids',
                'posts_per_page'    => intval(wprentals_get_option('wp_estate_map_max_pins','')),
                'tax_query'         => array(
                                        $category_array,
                                        $action_array,
                                        $city_array,
                                        $area_array,

                                    ),


            );



        $selected_pins              =   wpestate_listing_pins('full_shortcode',1,$args,1,'','');//call the new pins




        $is_contact='yes';
        $map_style_encoded='';
        if(isset($attributes['is_elementor']) && $attributes['is_elementor']==1  ){
            $map_style_encoded= $map_style;

        }else{
            $map_style_encoded= rawurldecode( base64_decode($map_style));
        }


        ob_start();

            include( locate_template('templates/google_maps_base.php') );
            $return_string= ob_get_contents();
            $return_string.=  '<div id="wpestate_full_map_control_data"  data-zoom="'.$map_zoom.'"></div>';
        ob_end_clean();

        if ( !wp_script_is( 'googlemap', 'enqueued' )) {
            $is_map_shortcode=1;
            wpestate_load_google_map();
        }



        $map_shortcode_for='listing';
        if($map_shortcode_for=='contact'){
            $return_string .= '<script type="text/javascript">
                    //<![CDATA[
                    var is_map_shortcode=1;
                    var map_style_shortcode="";';
                    if($map_style_encoded!=''){
                        $return_string .=' map_style_shortcode='.$map_style_encoded.';';
                    }

                    $return_string .='jQuery(document).ready(function(){

                        if (typeof google === "object" && typeof google.maps === "object") {
                            google.maps.event.addDomListener(window, "load", wpresidence_initialize_map_contact);
                        }else{
                            wpresidence_initialize_map_contact_leaflet();
                        }
                    });
                    //]]>
                </script>';
        }else{
            $return_string .= '<script type="text/javascript">
                //<![CDATA[
                var is_map_shortcode=1;
                var map_style_shortcode="";';
                if($map_style_encoded!=''){
                    $return_string .=' map_style_shortcode='.$map_style_encoded.';';
                }

                $return_string .= 'jQuery(document).ready(function(){
                    googlecode_regular_vars.generated_pins="0";
                    mapfunctions_vars.open_close_status="1";
                    googlecode_regular_vars.markers='.json_encode($selected_pins).'
                    if (typeof google === "object" && typeof google.maps === "object") {
                        google.maps.event.addDomListener(window, "load", wprentals_initialize_map);
                    }else{
                        wprentals_initialize_map();
                        map.invalidateSize();
                    }
                });
                //]]>
            </script>';
        }


        return $return_string;
    }
endif;


/*
 *
 * avalability calendar shortcode
 *
 *
 *
 */

if( !function_exists('wpestate_availability_calendar') ):
function wpestate_availability_calendar($attributes, $content = null){
    $property_id            =   '';
    $attributes = shortcode_atts(
        array(
            'id'                       => 0,
        ), $attributes) ;

    if ( isset($attributes['id']) ){
        $property_id=$attributes['id'];
    }
    
    $return_string=  wpestate_property_show_avalability( $property_id );
     wp_enqueue_script('avalability_control_elementor');
    return $return_string;
     
}
endif;

/*
 *
 * Booking form shortcode
 *
 *
 *
 */
if( !function_exists('wpestate_booking_form') ):

function wpestate_booking_form($attributes, $content = null) {

    wp_enqueue_script('wpestate_property');
    wp_enqueue_script('wpestate_dashboard-control');
    wp_enqueue_script('fancybox', trailingslashit( get_template_directory_uri() ).'js/jquery.fancybox.pack.js',array('jquery'), '1.0', true);
    wp_enqueue_style('jquery.fancybox', trailingslashit( get_template_directory_uri() ).'css/jquery.fancybox.css', array(), '1.0', 'all');



    $property_id            =   '';
    $attributes = shortcode_atts(
        array(
            'id'                       => 0,
        ), $attributes) ;

    if ( isset($attributes['id']) ){
        $property_id=$attributes['id'];
    }
    $favorite_data  =   wpestate_generate_favorite_info($property_id);

     $return_string  =   wpestate_show_booking_form($property_id,'',$favorite_data['favorite_class'],$favorite_data['favorite_text'],1 );

     include(locate_template('templates/book_per_hour_form.php'));


    if( floatval(get_post_meta($property_id, 'instant_booking', true) )==1 ){
        wp_enqueue_script('stripe');
        wp_enqueue_script('wpestate-stripe');
    }



    $custom_price    =   json_encode(  wpml_custom_price_adjust($property_id));
    $booking_array   =   json_encode( get_post_meta($property_id, 'booking_dates',true  ));
    $default_price   =   get_post_meta($property_id,'property_price',true);
    $mega_details    =   json_encode( wpml_mega_details_adjust($property_id));

    $cleaning_fee_per_day           =   intval   ( get_post_meta($property_id, 'cleaning_fee_per_day', true) );
    $city_fee_per_day               =   intval   ( get_post_meta($property_id, 'city_fee_per_day', true) );
    $price_per_guest_from_one       =   intval   ( get_post_meta($property_id, 'price_per_guest_from_one', true) );
    $checkin_change_over            =   intval   ( get_post_meta($property_id, 'checkin_change_over', true) );
    $checkin_checkout_change_over   =   intval   ( get_post_meta($property_id, 'checkin_checkout_change_over', true) );
    $min_days_booking               =   intval   ( get_post_meta($property_id, 'min_days_booking', true) );
    $extra_price_per_guest          =   intval   ( get_post_meta($property_id, 'extra_price_per_guest', true) );
    $price_per_weekeend             =   intval   ( get_post_meta($property_id, 'price_per_weekeend', true) );
    $booking_start_hour             =   get_post_meta($property_id, 'booking_start_hour', true);
    if($booking_start_hour==''){
        $booking_start_hour='1:00';
    }
    $booking_end_hour               =   get_post_meta($property_id, 'booking_end_hour',true);
    if  ($booking_end_hour==''){
        $booking_end_hour='24:00';
    }
    $rtl_book_hours_calendar=false;
    if ( is_rtl() ){
        $rtl_book_hours_calendar        =   true;
    }

    wp_localize_script('wpestate_control', 'control_vars_property', array(
        'custom_price'          =>  $custom_price,
        'booking_array'         =>  $booking_array,
        'default_price'         =>  $default_price ,
        'mega_details'          =>  $mega_details,
            'cleaning_fee_per_day'              =>   $cleaning_fee_per_day,
            'city_fee_per_day'                  =>   $city_fee_per_day,
            'price_per_guest_from_one'          =>   $price_per_guest_from_one,
            'checkin_change_over'               =>   $checkin_change_over,
            'checkin_checkout_change_over'      =>   $checkin_checkout_change_over,
            'min_days_booking'                  =>   $min_days_booking,
            'booking_start_hour'                =>   $booking_start_hour,
            'booking_end_hour'                  =>   $booking_end_hour,
            'rtl_book_hours_calendar'           =>   $rtl_book_hours_calendar,
            'extra_price_per_guest'             =>   $extra_price_per_guest,
            'price_per_weekeend'                =>   $price_per_weekeend,
    ));


    $book_type= wprentals_return_booking_type($property_id);
    if($book_type==2 || $book_type==3  ){  }
        wp_enqueue_script('fullcalendar');
        wp_enqueue_style('fullcalendar');



    return $return_string;

}

endif; // end   wpestate_featured_agent

/*
 *
 * Term list shortcode
 *
 *
 *
 */

if( !function_exists('wpestate_simple_term_list') ):
function wpestate_simple_term_list($attributes, $content = null){

    $attributes     =   shortcode_atts(
    array(
            'term_list_category'             => 'property_action_category',
            'term_list_type'                 => 'horizontal',
            'term_show_count'                =>  'yes'
    ), $attributes) ;

    $terms = get_terms( array(
                    'taxonomy' => $attributes['term_list_category'],
                    'hide_empty' => false,
            ));


    $return_string='<ul class="wpestate_term_list type_'.$attributes['term_list_type'].'">';
    if(is_array($terms)){
        foreach($terms as $item){

            $return_string.='<li><a href="'.get_term_link($item->term_id).'">'.$item->name.'</a>';
            if($attributes['term_show_count']=='yes'){
                if($attributes['term_list_type'] == 'horizontal' ){
                     $return_string.='<span> ('.$item->count.') </span>';
                }else{
                    $return_string.='<span> '.$item->count.'</span>';
                }
            }
            $return_string.=  '</li>';

        }

    }
    $return_string.='</ul>';
    return $return_string;


}
endif;









////////////////////////////////////////////////////////////////////////////////////
/// wpestate_icon_container_function
////////////////////////////////////////////////////////////////////////////////////

if ( !function_exists("wpestate_icon_container_function") ):
function wpestate_icon_container_function($attributes, $content = null) {
    $return_string  =   '';
    $link           =   '';
    $title          =   '';
    $image          =   '';
    $content_box    =   '';
    $icon_type      =   '';
    $icon_class     =   '';
    $font_size      =   '';
    $font_size_style=   '';

    if(isset($attributes['title'])){
        $title=$attributes['title'] ;
    }

    $attributes = shortcode_atts(
        array(
            'title'                       => 'title',
            'image'                       => '',
            'content_box'                 => 'Content of the box goes here',
            'image_effect'                =>  'yes',
            'link'                        =>  '',
            'icon_type'                   =>  'left',
            'title_font_size'             =>    24,
        ), $attributes) ;


    if(isset($attributes['image'])){
        $image=$attributes['image'] ;
    }
    if(isset($attributes['content_box'])){
        $content_box=$attributes['content_box'] ;
    }

    if(isset($attributes['link'])){
        $link=$attributes['link'] ;
    }

    if(isset($attributes['icon_type'])){
        $icon_type=$attributes['icon_type'] ;
    }

    if(isset($attributes['title_font_size'])){
        $font_size=$attributes['title_font_size'] ;
    }


    $return_string .= '<div class="iconcol">';

    if($icon_type=='central'){
        $icon_class=" icon_central ";
    }

    if($font_size!=24){
        $font_size_style=' style="font-size:'.$font_size.'px; "';
    }




    if($image!=''){
        $return_string .= '<div class="icon_img '.$icon_class.'">';
        $return_string .= ' <img src="' .$image . '"  class="img-responsive" alt="thumb"/ >';
    }

    $return_string .= '<h3><a href="' . $link . '" '.$font_size_style.'>' . $title . '</a></h3></div>';
    $return_string .= '<p>' . do_shortcode($content_box) . '</p>';
    $return_string .= '</div>';

    return $return_string;
}
endif;












////////////////////////////////////////////////////////////////////////////////////
/// spacer
////////////////////////////////////////////////////////////////////////////////////

if ( !function_exists("wpestate_spacer_shortcode_function") ):
function wpestate_spacer_shortcode_function($attributes, $content = null) {
    $height =   '';
    $type   =   1;

    $attributes = shortcode_atts(
         array(
             'type'            => '1',
             'height'          => '40',
         ), $attributes) ;

    if(isset($attributes['type'])){
        $type=$attributes['type'] ;
    }

    if(isset($attributes['height'])){
        $height=$attributes['height'] ;
    }

    $return_string='';
    $return_string.= '<div class="spacer" style="height:' .$height. 'px;">';
    if($type==2){
         $return_string.='<span class="spacer_line"></span>';
    }
    $return_string.= '</div>';
    return $return_string;
}
endif;











///////////////////////////////////////////////////////////////////////////////////////////
// font awesome function
///////////////////////////////////////////////////////////////////////////////////////////
if ( !function_exists("wpestate_font_awesome_function") ):
function wpestate_font_awesome_function($attributes, $content = null){
        $icon = $attributes['icon'];
        $size = $attributes['size'];
        $return_string ='<i class="'.$icon.'" style="'.$size.'"></i>';
        return $return_string;
}
endif;





if ( !function_exists("wpestate_advanced_search_function") ):
function wpestate_advanced_search_function($attributes, $content = null){
    $title                  =   '';

    if ( isset($attributes['title']) ){
        $title=$attributes['title'];
    }

    $return_string          =   '';
    $search_object          =   new WpRentalsSearch();

    $return_string .='<div class="advanced_search_shortcode">';
        $return_string .= '<h2 class="shortcode_title_adv">'.$title.'</h2>';
        $return_string .= $search_object->wpstate_display_search_form('shortcode');
    $return_string .='</div>';

    return $return_string;
}
endif;




///////////////////////////////////////////////////////////////////////////////////////////
// advanced search function
///////////////////////////////////////////////////////////////////////////////////////////
if ( !function_exists("wpestate_advanced_search_function") ):
function wpestate_advanced_search_function_old($attributes, $content = null){
        $return_string          =   '';
        $random_id              =   '';
        $custom_advanced_search =   wprentals_get_option('wp_estate_custom_advanced_search','');
        $actions_select         =   '';
        $categ_select           =   '';
        $title                  =   '';

        if ( isset($attributes['title']) ){
            $title=$attributes['title'];
        }

        $args = wpestate_get_select_arguments();
        $action_select_list =   wpestate_get_action_select_list($args);
        $categ_select_list  =   wpestate_get_category_select_list($args);
        $select_city_list   =   wpestate_get_city_select_list($args);
        $select_area_list   =   wpestate_get_area_select_list($args);


        $adv_submit=wpestate_get_template_link('advanced_search_results.php');

        if($title!=''){

        }

        $return_string .= '<h2 class="shortcode_title_adv">'.$title.'</h2>';
        $return_string .= '<div class="advanced_search_shortcode" id="advanced_search_shortcode">
        <form role="search" method="get"   action="'.$adv_submit.'" >';
        if (function_exists('icl_translate') ){
            $return_string .=  do_action( 'wpml_add_language_form_field' );
        }
        $custom_advanced_search='no';

        $search_type    =   wprentals_get_option('wp_estate_adv_search_type','');
        if($search_type == 'oldtype' || $search_type=='newtype'){




                $return_string.='
                <div class="col-md-3 map_icon "> <!-- map_icon -->';
                        $return_string.=  wpestate_search_location_field(esc_html__('Where do you want to go ?','wprentals-core'),'shortcode');
                        $return_string.='
                </div>

                <div class="col-md-3 has_calendar calendar_icon ">  <!-- calendar_icon -->
                    <input type="text" id="check_in_shortcode" class="form-control " name="check_in" placeholder="'.esc_html__( 'Check-In','wprentals-core').'">
                </div>

                <div class="col-md-3 has_calendar calendar_icon checkout_sh ">  <!-- calendar_icon -->
                    <input type="text" id="check_out_shortcode" disabled class="form-control " name="check_out" placeholder="'.esc_html__( 'Check-Out','wprentals-core').'">
                </div>

                <div class="col-md-3 dropdown guest_form_sh_wr">
                <div class=" form-control guest_form">
                    <div data-toggle="dropdown" id="guest_no_shortcode" class="filter_menu_trigger" data-value="all"> '.esc_html__( 'Guests','wprentals-core').' <span class="caret caret_filter"></span> </div>
                    <input type="hidden" name="guest_no" id="guest_no_input_sh" value="">
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="guest_no_input_sh">'. wpestate_get_guest_dropdown().'
                    </ul>
                </div>
                </div>';
                get_template_part('libs/internal_autocomplete_wpestate');



                $wpestate_where_currency         =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
                $wpestate_currency               =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );

                $min_price_slider= ( floatval(wprentals_get_option('wp_estate_show_slider_min_price','')) );
                $max_price_slider= ( floatval(wprentals_get_option('wp_estate_show_slider_max_price','')) );

                if ($wpestate_where_currency == 'before') {
                     $price_slider_label = $wpestate_currency . number_format($min_price_slider).' '.esc_html__( 'to','wprentals-core').' '.$wpestate_currency . number_format($max_price_slider);
                } else {
                     $price_slider_label =  number_format($min_price_slider).$wpestate_currency.' '.esc_html__( 'to','wprentals-core').' '.number_format($max_price_slider).$wpestate_currency;
                }
                $return_string.='
                    <div class="col-md-9 adv_search_sh">
                        <p>
                            <label>'.esc_html__( 'Price range:','wprentals-core').'</label>
                            <span id="amount_sh"  style="border:0; font-weight:bold;">'.wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$wpestate_currency,$wpestate_where_currency).'</span>
                        </p>
                        <div id="slider_price_sh"></div>
                        <input type="hidden" id="price_low_sh"  name="price_low"  value="'.$min_price_slider.'" />
                        <input type="hidden" id="price_max_sh"  name="price_max"  value="'.$max_price_slider.'" />
                    </div>';



            $extended_search= wprentals_get_option('wp_estate_show_adv_search_extended','');
            if($extended_search=='yes'){
                ob_start();
                wpestate_show_extended_search('short');
                $templates = ob_get_contents();
                ob_end_clean();
                $return_string=$return_string.$templates;
            }




        }else{
            ob_start();
            if($search_type=='type4'){
                wpestate_search_type_inject($categ_select_list,$action_select_list,'half');
            }


            $adv_search_what            =   wprentals_get_option('wp_estate_adv_search_what');

            foreach($adv_search_what as $key=>$search_field){
                $search_col         =   3;
                $search_col_price   =   6;
                if($search_field=='property_price' ){
                    $search_col=$search_col_price;
                }
                if(strtolower($search_field)=='location' ){
                    $search_col=$search_col_price;
                }
                print '<div class="col-md-'.$search_col.' '.str_replace(" ","_", stripslashes($search_field) ).' ">';
                    print wpestate_show_search_field_new($_REQUEST,'shortcode',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key);

                print '</div>';

            }


            $templates = ob_get_contents();
            ob_end_clean();
            $return_string=$return_string.$templates;
        }






        $return_string.='<div class="col-md-3 adv_sh_but"><button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="advanced_submit_shorcode">'.esc_html__( 'Search','wprentals-core').'</button> </div>
            <input type="hidden" name="is_half" value="1" />
    </form>
</div>';

 return $return_string;

}

endif;

if ( !function_exists("wpestate_advanced_search_function_bavk") ):
function wpestate_advanced_search_function_bavk($attributes, $content = null){
        $return_string          =   '';
        $random_id              =   '';
        $custom_advanced_search =   wprentals_get_option('wp_estate_custom_advanced_search','');
        $actions_select         =   '';
        $categ_select           =   '';
        $title                  =   '';

        if ( isset($attributes['title']) ){
            $title=$attributes['title'];
        }

        $args = wpestate_get_select_arguments();
        $action_select_list =   wpestate_get_action_select_list($args);
        $categ_select_list  =   wpestate_get_category_select_list($args);
        $select_city_list   =   wpestate_get_city_select_list($args);
        $select_area_list   =   wpestate_get_area_select_list($args);


        $adv_submit=wpestate_get_template_link('advanced_search_results.php');

        if($title!=''){

        }

        $return_string .= '<h2 class="shortcode_title_adv">'.$title.'</h2>';
        $return_string .= '<div class="advanced_search_shortcode" id="advanced_search_shortcode">
        <form role="search" method="get"   action="'.$adv_submit.'" >';
        if (function_exists('icl_translate') ){
            $return_string .=  do_action( 'wpml_add_language_form_field' );
        }
        $custom_advanced_search='no';

        if($custom_advanced_search=='yes'){

        }else{
             $return_string.='

                    <div class="col-md-3 map_icon "> <!-- map_icon -->';
                        $show_adv_search_general            =   wprentals_get_option('wp_estate_wpestate_autocomplete','');
                        $wpestate_internal_search           =   '';
                        if($show_adv_search_general=='no'){
                            $wpestate_internal_search='_autointernal';
                            $return_string.='<input type="hidden" class="stype" id="stype" name="stype" value="tax">';
                        }

                    $return_string.='
                    <input type="text" id="search_location_filter_shortcode'.$wpestate_internal_search.'" class="form-control search_location_city" name="search_location" placeholder="'.esc_html__( 'Type location','wprentals-core').'" value="" autocomplete="off">
                    <input type="hidden" id="advanced_city_shortcode"   class="form-control" name="advanced_city" data-value=""   value="" >
                    <input type="hidden" id="advanced_area_shortcode"   class="form-control" name="advanced_area"   data-value="" value="" >
                    <input type="hidden" id="advanced_country_shortcode"   class="form-control" name="advanced_country"   data-value="" value="" >
                    <input type="hidden" id="property_admin_area_shortcode" name="property_admin_area" value="">

                </div>

                <div class="col-md-3 has_calendar calendar_icon ">  <!-- calendar_icon -->
                    <input type="text" id="checkinshortcode" class="form-control " name="check_in" placeholder="'.esc_html__( 'Check-In','wprentals-core').'">
                </div>

                <div class="col-md-3 has_calendar calendar_icon checkout_sh ">  <!-- calendar_icon -->
                    <input type="text" id="checkoutshortcode" disabled class="form-control " name="check_out" placeholder="'.esc_html__( 'Check-Out','wprentals-core').'">
                </div>

                <div class="col-md-3 dropdown guest_form_sh_wr">
                <div class=" form-control guest_form">
                    <div data-toggle="dropdown" id="guest_no_shortcode" class="filter_menu_trigger" data-value="all"> '.esc_html__( 'Guests','wprentals-core').' <span class="caret caret_filter"></span> </div>
                    <input type="hidden" name="guest_no" id="guest_no_input_sh" value="">
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="guest_no_input_sh">'. wpestate_get_guest_dropdown().'
                    </ul>
                </div>
                </div>';
                get_template_part('libs/internal_autocomplete_wpestate');



                $wpestate_where_currency         =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
                $wpestate_currency               =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );

                $min_price_slider= ( floatval(wprentals_get_option('wp_estate_show_slider_min_price','')) );
                $max_price_slider= ( floatval(wprentals_get_option('wp_estate_show_slider_max_price','')) );

                if ($wpestate_where_currency == 'before') {
                     $price_slider_label = $wpestate_currency . number_format($min_price_slider).' '.esc_html__( 'to','wprentals-core').' '.$wpestate_currency . number_format($max_price_slider);
                } else {
                     $price_slider_label =  number_format($min_price_slider).$wpestate_currency.' '.esc_html__( 'to','wprentals-core').' '.number_format($max_price_slider).$wpestate_currency;
                }
                $return_string.='
                    <div class="col-md-9 adv_search_sh">
                        <p>
                            <label>'.esc_html__( 'Price range:','wprentals-core').'</label>
                            <span id="amount_sh"  style="border:0; font-weight:bold;">'.wpestate_show_price_label_slider($min_price_slider,$max_price_slider,$wpestate_currency,$wpestate_where_currency).'</span>
                        </p>
                        <div id="slider_price_sh"></div>
                        <input type="hidden" id="price_low_sh"  name="price_low"  value="'.$min_price_slider.'" />
                        <input type="hidden" id="price_max_sh"  name="price_max"  value="'.$max_price_slider.'" />
                    </div>';








        }
        $extended_search= wprentals_get_option('wp_estate_show_adv_search_extended','');
        if($extended_search=='yes'){
            ob_start();
            wpestate_show_extended_search('short');
            $templates = ob_get_contents();
            ob_end_clean();
            $return_string=$return_string.$templates;
        }

          $return_string.='<div class="col-md-3"></div><div class="col-md-3 adv_sh_but"><button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="advanced_submit_shorcode">'.esc_html__( 'Search','wprentals-core').'</button> </div>

    </form>
</div>';

 return $return_string;

}

endif;



///////////////////////////////////////////////////////////////////////////////////////////
// list items by ids function
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_list_items_by_id_function') ):

function wpestate_list_items_by_id_function($attributes, $content = null) {
    global $post;
    global $align;
    global $wpestate_show_compare_only;
    global $wpestate_currency;
    global $wpestate_where_currency;
    global $col_class;
    global $is_shortcode;
    global $wpestate_row_number_col;
    global $wpestate_listing_type;
    global $wpestate_property_unit_slider;
    $wpestate_property_unit_slider       =   esc_html ( wprentals_get_option('wp_estate_prop_list_slider','') );
    $wpestate_listing_type   =   wprentals_get_option('wp_estate_listing_unit_type','');
    $wpestate_currency           =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
    $wpestate_where_currency     =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
    $wpestate_show_compare_only  =   'no';
    $return_string      =   '';
    $pictures           =   '';
    $button             =   '';
    $class              =   '';
    $rows               =   1;
    $ids                =   '';
    $ids_array          =   array();
    $post_number        =   1;
    $title              =   '';
    $is_shortcode       =   1;
    $row_number         =   '';


    $title              =   '';
    if ( isset($attributes['title']) ){
        $title=$attributes['title'];
    }

    $attributes = shortcode_atts(
        array(
            'title'                 => '',
            'type'                  => 'properties',
            'ids'                   =>  '',
            'number'                =>  3,
            'rownumber'             =>  4,
            'align'                 =>  'vertical',
            'link'                  =>  '#',
            'extra_class_name'      =>  ''
        ), $attributes) ;
    $transient_ids='';
    if ( isset($attributes['ids']) ){
        $ids = $transient_ids=$attributes['ids'];
        $ids_array=explode(',',$ids);
    }

    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        $transient_ids.='_'. ICL_LANGUAGE_CODE;
    }

    if ( isset($_COOKIE['my_custom_curr_symbol'] ) ){
        $transient_ids.='_'.$_COOKIE['my_custom_curr_symbol'];
    }

    if ( isset($attributes['title']) ){
        $title=$attributes['title'];
    }

    $post_number_total = $attributes['number'];


    if ( isset($attributes['rownumber']) ){
        $row_number        = $attributes['rownumber'];
    }

    // max 4 per row
    if($row_number>4){
        $row_number=4;
    }

    if( $row_number == 4 ){
        $wpestate_row_number_col = 3; // col value is 3
    }else if( $row_number==3 ){
        $wpestate_row_number_col = 4; // col value is 4
    }else if ( $row_number==2 ) {
        $wpestate_row_number_col =  6;// col value is 6
    }else if ($row_number==1) {
        $wpestate_row_number_col =  12;// col value is 12
    }


    $align='';
    if(isset($attributes['align']) && $attributes['align']=='horizontal'){
        $align="col-md-12";
    }



    if ($attributes['type'] == 'properties') {
       $type = 'estate_property';
    } else {
       $type = 'post';
    }

    if ($attributes['link'] != '') {
        if ($attributes['type'] == 'properties') {
            $button .= '<div class="listinglink-wrapper">
                           <a href="' . $attributes['link'] . '"> <span class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button more_list">'.esc_html__( ' More Listings','wprentals-core').' </span></a>
                       </div>';
        } else {
            $button .= '<div class="listinglink-wrapper">
                           <a href="' . $attributes['link'] . '"> <span class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button more_list">'.esc_html__( ' More Articles','wprentals-core').'</span></a>
                        </div>';
        }
    } else {
        $class = "nobutton";
    }





   $args = array(
        'post_type'         => $type,
        'post_status'       => 'publish',
        'posts_per_page'    => $post_number_total,
        'orderby'           => 'post__in',
        'post__in'          => $ids_array,
    );

    $recent_posts   =   false;
    if(function_exists('wpestate_request_transient_cache')){
        $recent_posts = wpestate_request_transient_cache( 'wpestate_list_items_by_id_'.$transient_ids);
    }

    if( $recent_posts === false ) {
        $recent_posts = new WP_Query($args);
        if(function_exists('wpestate_set_transient_cache')){
            wpestate_set_transient_cache( 'wpestate_list_items_by_id_'.$transient_ids,$recent_posts,4*60*60);
        }
    }


    $return_string .= '<div class=" bottom-estate_property nobutton "><div class=" items_shortcode_wrapper">';
    if($title!=''){
        $return_string .= '<h2 class="shortcode_title">'.$title.'</h2>';
    }

    ob_start();
    while ($recent_posts->have_posts()): $recent_posts->the_post();
        if($type == 'estate_property'){
            if(isset($attributes['align']) && $attributes['align']=='horizontal'){
               $col_class='col-md-12';
            }
            get_template_part('templates/property_unit');

        } else {

              get_template_part('templates/blog_unit');

        }
    endwhile;

    $templates = ob_get_contents();
    ob_end_clean();
    $return_string .=$templates;
    $return_string .=$button;
    $return_string .= '</div></div>';
    wp_reset_query();
    $is_shortcode       =   0;
    return $return_string;
}
endif; // end   wpestate_list_items_by_id_function






///////////////////////////////////////////////////////////////////////////////////////////
// login form  function
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_login_form_function') ):

function wpestate_login_form_function($attributes, $content = null) {
     // get user dashboard link
        global $wpdb;
        $redirect='';
        $mess='';
        $allowed_html   =   array();
        $attributes = shortcode_atts(
            array(
                'register_label'                  => '',
                'register_url'                =>  '',

            ), $attributes) ;

       if(isset($_GET['key']) && $_GET['action'] == "reset_pwd") {
            $reset_key  = sanitize_text_field ( wp_kses($_GET['key'],$allowed_html) );
            $user_login = sanitize_text_field ( wp_kses($_GET['login'],$allowed_html) );
            $user_data  = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users
                    WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));


            if(!empty($user_data)){
                    $user_login = $user_data->user_login;
                    $user_email = $user_data->user_email;

                    if(!empty($reset_key) && !empty($user_data)) {
                            $new_password = wp_generate_password(7, false);
                            wp_set_password( $new_password, $user_data->ID );
                            //mailing the reset details to the user
                            $message = esc_html__( 'Your new password for the account at:','wprentals-core') . "\r\n\r\n";
                            $message .= get_bloginfo('name') . "\r\n\r\n";
                            $message .= sprintf(esc_html__( 'Username: %s','wprentals-core'), $user_login) . "\r\n\r\n";
                            $message .= sprintf(esc_html__( 'Password: %s','wprentals-core'), $new_password) . "\r\n\r\n";
                            $message .= esc_html__( 'You can now login with your new password at: ','wprentals-core') . get_option('siteurl')."/" . "\r\n\r\n";

                            $arguments=array(
                                'user_pass'        =>  $new_password,
                            );
                            wpestate_select_email_type($user_email,'password_reseted',$arguments);

                            $mess= '<div class="login-alert">'.esc_html__( 'A new password was sent via email!','wprentals-core').'</div>';

                    }
                    else {
                        exit('Not a Valid Key.');
                    }
            }// end if empty
        }

    $post_id=get_the_ID();
    //$login_nonce=wp_nonce_field( 'login_ajax_nonce', 'security-login',true,false );
    $security_nonce=wp_nonce_field( 'forgot_ajax_nonce', 'security-forgot',true,false );
    $return_string='<div class="login_form shortcode-login" id="login-div">
        <div class="loginalert" id="login_message_area_sh" >'.$mess.'</div>

                <div class="loginrow">
                    <input type="text" class="form-control" name="log" id="login_user_sh" placeholder="'.esc_html__( 'Username','wprentals-core').'" size="20" />
                </div>

                <div class="loginrow password_holder">
                    <input type="password" class="form-control" name="pwd" id="login_pwd_sh"  placeholder="'.esc_html__( 'Password','wprentals-core').'" size="20" />
                    <i class=" far fa-eye-slash show_hide_password"></i>
                </div>

                <input type="hidden" name="loginpop" id="loginpop" value="0">
                <input type="hidden" id="security-login_sh" name="security-login" value="'. estate_create_onetime_nonce( 'login_ajax_nonce' ).'">


                <button id="wp-login-but_sh" class="wpb_button  wpb_btn-info  wpb_btn-small   wpestate_vc_button  vc_button">'.esc_html__( 'Login','wprentals-core').'</button>
                <div class="login-links shortlog">';


                if(isset($attributes['register_label']) && $attributes['register_label']!=''){
                     $return_string.='<a href="'.$attributes['register_url'].'">'.$attributes['register_label'].'</a> | ';
                }
                $return_string.='<a href="#" id="forgot_pass">'.esc_html__( 'Forgot Password?','wprentals-core').'</a>
                </div>';
                global $wpestate_social_login;
                if(class_exists('Wpestate_Social_Login')){
                    $return_string.=$wpestate_social_login->display_form('short',1);
                }
        $return_string.='
        </div>
        <div class="login_form  shortcode-login" id="forgot-pass-div">
            <div class="loginalert" id="forgot_pass_area"></div>
            <div class="loginrow">
                    <input type="text" class="form-control" name="forgot_email" id="forgot_email" placeholder="'.esc_html__( 'Enter Your Email Address','wprentals-core').'" size="20" />
            </div>
            '. $security_nonce.'
            <input type="hidden" id="postid" value="'.$post_id.'">
            <button class="wpb_button  wpb_btn-info  wpb_btn-small   wpestate_vc_button  vc_button" id="wp-forgot-but" name="forgot" >'.esc_html__( 'Reset Password','wprentals-core').'</button>
            <div class="login-links shortlog">
            <a href="#" id="return_login">'.esc_html__( 'Return to Login','wprentals-core').'</a>
            </div>
        </div>';
    return  $return_string;
}
endif; // end   wpestate_login_form_function



///////////////////////////////////////////////////////////////////////////////////////////
// register form  function
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_register_form_function') ):

function wpestate_register_form_function($attributes, $content = null) {

    $attributes = shortcode_atts(
    array(
        'type'                 => '_sh',
    ), $attributes) ;

    if ( isset($attributes['type']) ){
        $type=$attributes['type'];
    }


    $register_nonce=wp_nonce_field( 'register_ajax_nonce', 'security-register',true,false );
    $return_string='
        <div class="login_form shortcode-login">
                <div class="loginalert" id="register_message_area'.$type.'" ></div>

                <div class="loginrow">
                    <input type="text" name="user_login_register" id="user_login_register'.$type.'" class="form-control" placeholder="'.esc_html__( 'Username','wprentals-core').'" size="20" />
                </div>';

        $enable_user_pass_status= esc_html ( wprentals_get_option('wp_estate_enable_user_pass','') );
        $wp_estate_enable_user_phone= esc_html ( wprentals_get_option('wp_estate_enable_user_phone','') );
        
        if($enable_user_pass_status == 'yes'){
            $return_string.='
            <div class="loginrow">
                <input type="text" name="user_email_register" id="user_email_register'.$type.'" class="form-control" placeholder="'.esc_html__( 'Email','wprentals-core').'" size="20" />
            </div>

            <div class="loginrow password_holder">
                <input type="password" name="user_password" id="user_password'.$type.'" class="form-control" placeholder="'.esc_html__( 'Password','wprentals-core').'" size="20" />
                <i class=" far fa-eye-slash show_hide_password"></i>
            </div>';

            $return_string.='
            <div class="loginrow password_holder">
                <input type="password" name="user_password_retype" id="user_password_retype'.$type.'" class="form-control" placeholder="'.esc_html__( 'Retype Password','wprentals-core').'" size="20" />
                <i class=" far fa-eye-slash show_hide_password"></i>
            </div>';

        }else{
            $return_string.='
            <div class="loginrow ">
                <input type="text" name="user_email_register" id="user_email_register'.$type.'" class="form-control" placeholder="'.esc_html__( 'Email','wprentals-core').'" size="20" />

            </div>';
        }


        if($wp_estate_enable_user_phone=='yes'){
            $return_string.=' <div class="loginrow ">
                <input type="text" name="user_phone_register" id="user_phone_register'.$type.'" class="form-control" placeholder="'.esc_html__( 'Phone Number','wprentals-core').'" size="20" />

            </div>';
        }
        

    $separate_users_status= esc_html ( wprentals_get_option('wp_estate_separate_users','') );

    if($separate_users_status=='yes'){
        $return_string.='
            <div class="acc_radio">
            <input type="radio" name="acc_type'.$type.'" id="acctype0" value="1" checked required>
            <div class="radiolabel" for="acctype0">'.esc_html__('I only want to book','wprentals-core').'</div><br>
            <input type="radio" name="acc_type'.$type.'" id="acctype1" value="0" required>
            <div class="radiolabel" for="acctype1">'.esc_html__('I want to rent my property','wprentals-core').'</div></div> ';
        }

    $return_string.='
        <input type="checkbox" name="terms" id="user_terms_register_sh'.$type.'">
        <label id="user_terms_register_sh_label" for="user_terms_register_sh">'.esc_html__( 'I agree with ','wprentals-core').'<a href="'.wpestate_get_template_link('terms_conditions.php').'" target="_blank" id="user_terms_register_topbar_link">'.esc_html__( 'terms & conditions','wprentals-core').'</a> </label>';

        if( esc_html ( wprentals_get_option('wp_estate_use_captcha','') )=='yes'){
            if($type=='_sh'){
                $return_string.='<div id="capthca_register'.$type.'" style="margin:10px 0px;float:left;transform:scale(1.02);-webkit-transform:scale(1.02);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>';
            }else{
                $return_string.='<div id="capthca_register'.$type.'" style="float:left;transform:scale(1.02);-webkit-transform:scale(1.02);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>';
            }
        }

        $return_string.='<button id="wp-submit-register'.$type.'"  style="margin-top:10px;" class="wpb_button  wpb_btn-info  wpb_btn-small wpestate_vc_button  vc_button">'.esc_html__( 'Create an account','wprentals-core').'</button>';


        if($enable_user_pass_status != 'yes'){
            $return_string.='<p id="reg_passmail">'.esc_html__( '*A password will be e-mailed to you','wprentals-core').'</p>';
        }

        $return_string.='
        <input type="hidden" id="security-register'.$type.'" name="security-register" value="'. estate_create_onetime_nonce( 'register_ajax_nonce' ).'">';

        $social_register_on  =   esc_html( wprentals_get_option('wp_estate_social_register_on','') );
        if($social_register_on=='yes'){

            global $wpestate_social_login;
            if(class_exists('Wpestate_Social_Login')){
                $return_string.=$wpestate_social_login->display_form('short_reg',1);
            }
       }

    $return_string.='</div>';
    return  $return_string;
}
endif; // end   wpestate_register_form_function



///////////////////////////////////////////////////////////////////////////////////////////
/// featured article
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_featured_article') ):


function wpestate_featured_article($attributes, $content = null) {
    global $design_class;
    global $post;
    $return_string  =   '';
    $article        =   0;
    $second_line    =   '';
    $design_class   =   '';
    $type           =   '';

    $attributes = shortcode_atts(
        array(
            'id'     => 0,
            'type'  => "type1",
        ), $attributes) ;



    if(isset($attributes['id'])){
        $article = intval($attributes['id']);
    }

    if(isset($attributes['type'])){

         $type = ($attributes['type']);
    }

    if( isset($attributes['second_line'] )){
        $second_line = $attributes['second_line'];
    }

    $args = array(
            'post_type' => 'post',
            'p'         => $article
        );


    if ( isset($attributes['type']) && $attributes['type']=='type1' ){
        $design_class=' type_1_class ';
    }else if ( isset($attributes['type']) && $attributes['type']=='type2' ){
        $design_class=' type_2_class ';
    }


    if ( isset($attributes['type']) && $attributes['type']=='type3' ){
            $title      =   esc_html(get_the_title($article));
            $link       =   esc_url (get_permalink($article));
            $preview = wp_get_attachment_image_src(get_post_thumbnail_id($article), 'full');
            $return_string.= '<div class="featured_article_type2">
                       <div class="featured_img_type2" style="background-image:url(' . $preview[0] . ')">
                            <div class="featured_article_type2_cover">
                            </div>
                            <div class="featured_article_type2_title_wrapper">
                                <div class="featured_article_label">' . __('Featured Article', 'wprentals-core') . '</div>
                                <a href="'.$link.'"><h2>' . $title . '</h2></a>
                                <div class="featured_read_more"><a href="' . $link . '">' . __('read more', 'wprentals-core') . '</a> <i class="fas fa-chevron-right"></i></div>
                            </div>
                        </div>
                    </div>';
            return $return_string;
        }



    $my_query = new WP_Query($args);

    ob_start();
    while ($my_query->have_posts() ): $my_query->the_post();
        get_template_part('templates/blog_unit_featured');
    endwhile;

    $return_string .= ob_get_contents();
    ob_end_clean();


    wp_reset_query();
    return $return_string;
}
endif; // end   featured_article


if( !function_exists('wpestate_get_avatar_url') ):

function wpestate_get_avatar_url($get_avatar) {
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}
endif; // end   wpestate_get_avatar_url




////////////////////////////////////////////////////////////////////////////////////
/// featured property
////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_featured_property') ):

function wpestate_featured_property($attributes, $content = null) {
    global $wpestate_property_unit_slider;
    global $type;
    $wpestate_property_unit_slider       =   esc_html ( wprentals_get_option('wp_estate_prop_list_slider','') );
    $return_string  =   '';
    $prop_id        =   '';
    $sale_line      =   '';
    $desgin_class   =   '';
    $type           =   '';

    $attributes = shortcode_atts(
        array(
            'id'                  => '',
            'type'                     => "type1",
        ), $attributes) ;

    if( isset($attributes['id'])){
        $prop_id=$attributes['id'];
    }

    if( isset($attributes['type'])){
        $type=$attributes['type'];
    }

    if ( isset($attributes['sale_line'])){
        $sale_line =  $attributes['sale_line'];
    }

    $args = array('post_type'   => 'estate_property',
                  'post_status' => 'publish',
                  'p'           => $prop_id
                );


    if( $type =='type1' ){
        $desgin_class ='type_1_class';
    }

    if ( isset($attributes['type']) && $attributes['type']=='type3' ){

    $title                          =   esc_html( get_the_title($prop_id) );
    $link                           =   esc_url ( get_permalink($prop_id) );
    $preview                        =   wp_get_attachment_image_src(get_post_thumbnail_id($prop_id), 'full');
    $wpestate_currency              =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
    $wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
    $price_per_guest_from_one       =   floatval( get_post_meta($prop_id, 'price_per_guest_from_one', true) );
    $rental_type                    =   wprentals_get_option('wp_estate_item_rental_type');
    $booking_type                   =   wprentals_return_booking_type($prop_id);

    if($price_per_guest_from_one==1){
        $featured_propr_price=  wpestate_show_price($prop_id,$wpestate_currency,$wpestate_where_currency,1).' <div class="featured_price_label">'.esc_html__( 'per guest','wprentals-core').'</div>';
    }else{
        $featured_propr_price=  wpestate_show_price($prop_id,$wpestate_currency,$wpestate_where_currency,1).' <div class="featured_price_label"><span class="pernight">'.wpestate_show_labels('per_night2',$rental_type,$booking_type).'</span></div>';
    }
    $featured_propr_stars='';

    if(wpestate_has_some_review($prop_id)!==0){
        $featured_propr_stars=  wpestate_display_property_rating($prop_id);
    }


        $return_string.= '<div class="featured_property_type3">
                <div class="featured_prop_img_type3" style="background-image:url(' . $preview[0] . ')">
                    <div class="featured_propery_type3_cover"></div>
                         <div class="featured_propery_type3_title_wrapper">
                            <div class="featured_property_price">' . $featured_propr_price . '</div>
                            <div class="featured_property_stars">'. $featured_propr_stars .'</div>
                            <a href="'.$link.'" target="'.esc_attr(wprentals_get_option('wp_estate_prop_page_new_tab','')).'" ><h2>' . $title . '</h2></a>

                            <div class="featured_read_more"><a href="' . $link . '" target="'.esc_attr(wprentals_get_option('wp_estate_prop_page_new_tab','')).'" >' . __('discover more', 'wprentals-core') . '</a> <i class="fas fa-chevron-right"></i></div>
                         </div>
                    </div>
                 </div>';
        return $return_string;

     }


    $return_string= '<div class="featured_property '.$desgin_class.'">';
    $my_query = new WP_Query($args);
    ob_start();
    while ($my_query->have_posts() ): $my_query->the_post();
         get_template_part('templates/property_unit_featured');
    endwhile;
    $return_string .= ob_get_contents();
    ob_end_clean();
    wp_reset_query();
    $return_string.='</div>';
    return $return_string;
}
endif; // end   wpestate_featured_property



////////////////////////////////////////////////////////////////////////////////////
/// featured agent
////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_featured_agent') ):

function wpestate_featured_agent($attributes, $content = null) {
    global $notes;
    $return_string='';
    $notes  =   '';
    $agent_id   =   $attributes['id'];

    $attributes = shortcode_atts(
        array(
            'id'            =>  0,
            'notes'         =>  '',
            'design_type'   =>  1,
        ), $attributes) ;

    if ( isset($attributes['notes']) ){
        $notes=$attributes['notes'];
    }

    if ( isset($attributes['design_type']) ){
        $design_type=$attributes['design_type'];
    }

    $args = array(
        'post_type' => 'estate_agent',
        'p' => $agent_id
    );




    $my_query = new WP_Query($args);
    ob_start();
    while ($my_query->have_posts() ): $my_query->the_post();
        if($design_type==2){
            get_template_part('templates/agent_unit_featured2');
        }else{
            get_template_part('templates/agent_unit_featured');
        }
    endwhile;
    $return_string = ob_get_contents();
    ob_end_clean();
    wp_reset_query();
    return $return_string;
}

endif; // end   wpestate_featured_agent












if( !function_exists('wpestate_limit_words') ):

function wpestate_limit_words($string, $max_no) {
    $words_no = explode(' ', $string, ($max_no + 1));

    if (count($words_no) > $max_no) {
        array_pop($words_no);
    }

    return implode(' ', $words_no);
}
endif; // end   wpestate_limit_words







////////////////////////////////////////////////////////////////////////////////////////////////////////////////..
///  shortcode - testimonials
////////////////////////////////////////////////////////////////////////////////////////////////////////////////..


if( !function_exists('wpestate_testimonial_function') ):
function wpestate_testimonial_function($attributes, $content = null) {
    $return_string='';
    $title_client='';
    $client_name='';
    $imagelinks='';
    $testimonial_text='';
    $attributes = shortcode_atts(
            array(
                'client_name'                  => 'Name Here',
                'title_client'                 => "happy client",
                'imagelinks'                   => '',
                'testimonial_text'             => '',
                'extra_class_name'             => '',
                'testimonial_type'              =>  1,
                'stars_client'                 =>  '5',

            ), $attributes) ;


    if ( $attributes['client_name'] ){
     $client_name   =   $attributes['client_name'];
    }

    if( $attributes['title_client'] ){
        $title_client   =   $attributes['title_client'] ;
    }

    if( $attributes['imagelinks'] ){
        $imagelinks   =   $attributes['imagelinks']  ;
    }

    if( $attributes['testimonial_text'] ){
        $testimonial_text   =   $attributes['testimonial_text']  ;
    }

    if( $attributes['testimonial_type'] ){
        $testimonial_type   =   $attributes['testimonial_type']  ;
    }

    if( $attributes['stars_client'] ){
        $stars_client   =  floatval($attributes['stars_client'])  ;
    }


    $return_string .= ' <div class="testimonial-container testimonial_type_'.$testimonial_type.'">';

    if($testimonial_type==1){

        $return_string .= '     <div class="testimonial-text">'.$testimonial_text.'</div>';
        $return_string .= '     <div class="testimonial-image" style="background-image:url(' .$imagelinks . ')"></div>';
        $return_string .= '     <div class="testimonial-author-line"><span class="testimonial-author">' . $client_name .'</span> '.$title_client.' </div>';

    }else  if($testimonial_type==2){
        $return_string .= '     <div class="testimonial-image" style="background-image:url(' .$imagelinks . ')"></div>';
        $return_string .= '     <div class="testimonial-author-line"><span class="testimonial-author">' . $client_name .'</span><div class="testimonial-clas-line">'.$title_client.'</div></div>';
        $return_string .= '     <div class="testimonial-text">'.$testimonial_text.'</div>';
        $return_string .= '     <div class="testimmonials_starts">'.wpestate_starts_reviews_core($stars_client).'</div>';


    }
    $return_string .= ' </div>';

    return $return_string;
}
endif; // end   wpestate_testimonial_function



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///  shortcode - reccent post function
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_recent_posts_function') ):


function wpestate_recent_posts_function($attributes, $heading = null) {
    $return_string='';
    extract(shortcode_atts(array(
        'posts' => 1,
                    ), $attributes));

    query_posts(array('orderby' => 'date', 'order' => 'DESC', 'showposts' => $posts));
    $return_string = '<div id="recent_posts"><ul><h3>' . $heading . '</h3>';
    if (have_posts()) :
        while (have_posts()) : the_post();
            $return_string .= '<li><a href="' . esc_url( get_permalink() ). '">' . get_the_title() . '</a></li>';
        endwhile;
    endif;

    $return_string.='</div></ul>';
    wp_reset_query();

    return $return_string;
}
endif;//end   wpestate_recent_posts_function






if(!function_exists('wpestate_places_slider') ):

function wpestate_places_slider($attributes, $content = null){

    global $wpestate_full_page;
    global $is_shortcode;
    global $wpestate_row_number_col;
    global $place_id;
    global $place_per_row;

    $is_shortcode       = 1;
    $place_list         = '';
    $return_string      = '';
    $extra_class_name   = '';



    $attributes = shortcode_atts(
                    array(
                    'place_list'        => '',
                    'place_per_row'     => 3,
                    'extra_class_name'  => '',
                     ),
        $attributes);

    $post_number_total = $attributes['place_per_row'];


    if ( isset($attributes['place_list']) ){
        $place_list = $attributes['place_list'];
    }

    if ( isset($attributes['place_per_row']) ){
        $place_per_row = $attributes['place_per_row'];
    }

    if($place_per_row>5){
        $place_per_row = 5;
    }

    if( isset($attributes['extra_class_name'])){
        $extra_class_name = $attributes['extra_class_name'];
    }


    $all_places_array = explode(',', $place_list);
    $slide_cont = '';




    foreach( $all_places_array as $single_term){
        $place_id = intval( $single_term );

        if($place_id!=0){

            ob_start();

            get_template_part('templates/places_unit');
            $slide_cont_tmp = ob_get_clean();

            if( $slide_cont_tmp && trim($slide_cont_tmp) != '' ){
                $slide_cont .= '<div class="single_slide_container">';
                $slide_cont .= $slide_cont_tmp;
                $slide_cont .= '</div>';
            }
        }

    }

    $return_string = '<div class="estate_places_slider '.$extra_class_name.'"  data-items-per-row="'.$place_per_row.'" data-auto="0" >'. $slide_cont.'</div>';
    //ob_end_clean();
    $is_shortcode = 0;
    return $return_string;




    }

endif; // end wpestate_places_slider
?>
