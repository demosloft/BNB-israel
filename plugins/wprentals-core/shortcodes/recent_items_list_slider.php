<?php

/*
*
*  Recent items list slider shortcode function
*
*
*
*/

if( !function_exists('wpestate_slider_recent_posts_pictures') ):

function wpestate_slider_recent_posts_pictures($attributes, $content = null) {
    global $wpestate_options ;
    global $align;
    global $align_class;
    global $post;
    global $wpestate_currency;
    global $wpestate_where_currency;
    global $is_shortcode;
    global $wpestate_show_compare_only;
    global  $wpestate_row_number_col;
    global $wpestate_curent_fav;
    global $current_user;
    global $wpestate_listing_type;
    global $wpestate_property_unit_slider;
    $wpestate_property_unit_slider       =   esc_html ( wprentals_get_option('wp_estate_prop_list_slider','') );

    $wpestate_listing_type           =  wprentals_select_unit_cards($attributes);

    $wpestate_options             =   wpestate_page_details($post->ID);
    $return_string      =   '';
    $pictures           =   '';
    $button             =   '';
    $class              =   '';
    $category=$action=$city=$area='';
    $title              =   '';
    $wpestate_currency           =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
    $wpestate_where_currency     =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
    $is_shortcode       =   1;
    $wpestate_show_compare_only  =   'no';
    $wpestate_row_number_col     =   '';
    $row_number         =   '';
    $show_featured_only =   '';
    $autoscroll         =   '';


    $current_user = wp_get_current_user();
    $userID                         =   $current_user->ID;
    $user_option        =   'favorites'.$userID;
    $wpestate_curent_fav         =   get_option($user_option);


    if ( isset($attributes['title']) ){
        $title=$attributes['title'];
    }

      $attributes = shortcode_atts(
                array(
                    'title'                 =>  '',
                    'type'                  => 'properties',
                    'category_ids'          =>  '',
                    'action_ids'            =>  '',
                    'city_ids'              =>  '',
                    'area_ids'              =>  '',
                    'number'                =>  4,
                    'show_featured_only'    =>  'no',
                    'random_pick'           =>  'no',
                    'extra_class_name'      =>  '',
                    'autoscroll'            =>  0,
                ), $attributes) ;


          

    if ( isset($attributes['category_ids']) ){
        $category=$attributes['category_ids'];
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

     if ( isset($attributes['show_featured_only']) ){
        $show_featured_only=$attributes['show_featured_only'];
    }
    if ( isset($attributes['autoscroll']) ){
        $autoscroll=intval ( $attributes['autoscroll'] );
    }

    $post_number_total = $attributes['number'];
    if ( isset($attributes['rownumber']) ){
        $row_number        = $attributes['rownumber'];
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
    $align_class='';
    if(isset($attributes['align']) && $attributes['align']=='horizontal'){
        $align="col-md-12";
        $align_class='the_list_view';
        $wpestate_row_number_col='12';
    }

    $attributes['type'] = 'properties';

    if ($attributes['type'] == 'properties') {
        $type = 'estate_property';

        $category_array =   '';
        $action_array   =   '';
        $city_array     =   '';
        $area_array     =   '';

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


           $meta_query=array();

            if($show_featured_only=='yes'){
                $compare_array=array();
                $compare_array['key']        = 'prop_featured';
                $compare_array['value']      = 1;
                $compare_array['type']       = 'numeric';
                $compare_array['compare']    = '=';
                $meta_query[]                = $compare_array;
            }

            $args = array(
                'post_type'         => $type,
                'post_status'       => 'publish',
                'paged'             => 0,
                'posts_per_page'    => $post_number_total,
                'meta_key'          => 'prop_featured',
                'orderby'           => 'ID',
                'order'             => 'DESC',
                'meta_query'        => $meta_query,
                'tax_query'         => array(
                                        $category_array,
                                        $action_array,
                                        $city_array,
                                        $area_array
                                    )

            );
            if($show_featured_only=='yes'){
                $args['meta_query'] =$meta_query;
                $args['orderby']    ='meta_value';
            }


    } else {
        $type = 'post';
        $args = array(
            'post_type'      => $type,
            'post_status'    => 'publish',
            'paged'          => 0,
            'posts_per_page' => $post_number_total,
            'cat'            => $category
        );
    }


    if ( isset($attributes['link']) && $attributes['link'] != '') {
        if ($attributes['type'] == 'properties') {
            $button .= '<div class="listinglink-wrapper">
               <a href="' . $attributes['link'] . '"> <span class="wpb_button  wpb_btn-info wpb_btn-large vc_button">'.esc_html__( 'More Listings','wprentals-core').' </span></a>
               </div>';
        } else {
            $button .= '<div class="listinglink-wrapper">
               <a href="' . $attributes['link'] . '"> <span class="wpb_button  wpb_btn-info wpb_btn-large vc_button">  '.esc_html__( 'More Articles','wprentals-core').' </span></a>
               </div>';
        }
    } else {
        $class = "nobutton";
    }


    $transient_name =   'wpestate_recent_posts_pictures_slider_query_' . $type . '_' . $category . '_' . $action . '_' . $city . '_' . $area.'_'.$post_number_total.'_'.$show_featured_only;
    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        $transient_name.='_'. ICL_LANGUAGE_CODE;
    }
    if ( isset($_COOKIE['my_custom_curr_symbol'] ) ){
        $transient_name.='_'.$_COOKIE['my_custom_curr_symbol'];
    }

    $recent_posts   =   false;
    if(function_exists('wpestate_request_transient_cache')){
        $recent_posts   =   wpestate_request_transient_cache( $transient_name);
    }

    if( $recent_posts===false){

        if ($attributes['type'] == 'properties') {
            add_filter( 'posts_orderby', 'wpestate_my_order' );
            $recent_posts = new WP_Query($args);
            remove_filter( 'posts_orderby', 'wpestate_my_order' );
            $count = 1;

        }else{
            $recent_posts = new WP_Query($args);
            $count = 1;
        }

        if(function_exists('wpestate_set_transient_cache')){
            wpestate_set_transient_cache( $transient_name, $recent_posts, 60*4*4 );
        }
    }



    $return_string .= '<div class="article_container slider_container bottom-'.$type.' '.$class.'" >';
    $return_string .= '<div class="slider_control_left"><i class="demo-icon icon-left-open-big"></i></div>
                       <div class="slider_control_right"><i class="demo-icon icon-right-open-big"></i></div>';

    if($title!=''){
         $return_string .= '<h2 class="shortcode_title title_slider">'.$title.'</h2>';
    }else{
        $return_string .= '<h2 class="shortcode_title no_title_slider"></h2>';
    }


    $is_autoscroll='';

        $is_autoscroll=' data-auto="'.$autoscroll.'" ';


    $return_string .= '<div class="shortcode_slider_wrapper" '.$is_autoscroll.'><ul class="shortcode_slider_list">';


    ob_start();
    while ($recent_posts->have_posts()): $recent_posts->the_post();
        print '<li>';
        if($type == 'estate_property'){
            get_template_part('templates/property_unit');
        } else {
            if(isset($attributes['align']) && $attributes['align']=='horizontal'){
                get_template_part('templates/blog_unit');
            }else{
                get_template_part('templates/blog_unit2');
            }

        }
        print '</li>';
    endwhile;

    $templates = ob_get_contents();
    ob_end_clean();
    $return_string .=$templates;
    $return_string .=$button;

    $return_string .= '</ul></div>';// end shrcode wrapper
    $return_string .= '</div>';
    wp_reset_query();

    wp_reset_postdata();
    $is_shortcode       =   0;



    return $return_string;


}
endif; // end   wpestate_recent_posts_pictures
