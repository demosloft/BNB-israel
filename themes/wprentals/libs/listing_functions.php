<?php

/*
*
* Property default layout  
*
*/

function wprentals_get_default_layout(){
    
    $layout_version =  wprentals_get_option('wp_estate_listing_page_type') ;


    $layout_data=array(
        '1' =>array(    'enabled' =>    array(
                                        'description'       => 'Description',
                                        'price_details'     => 'Price Details',
                                        'sleeping'          => 'Sleeping Arrangements',
                                        'address'           => 'Address',
                                        'listing_details'   => 'Listing Details',
                                        'features'          => 'Features',
                                        'terms'             => 'Terms and Conditions',
                                        'nearby'            => 'What\'s Nearby',
                                        'virtual_tour'      => 'Virtual Tour',
                                        'availability'      => 'Availability',
                                        'reviews'           => 'Reviews',
                                        'map'               => 'Map',
                                        'similar'           => 'Similar Listings',
                                    )
        ),

        '2' =>array(    'enabled' =>    array(
                                 //   'description'       => 'Description',
                                    'gallery'           => 'Gallery',
                                    'price_details'     => 'Price Details',
                                    'sleeping'          => 'Sleeping Arrangements',
                                    'address'           => 'Address',
                                    'listing_details'   => 'Listing Details',
                                    'features'          => 'Features',
                                    'terms'             => 'Terms and Conditions',
                                    'nearby'            => 'What\'s Nearby',
                                    'virtual_tour'      => 'Virtual Tour',
                                    'availability'      => 'Availability',
                                    'reviews'           => 'Reviews',
                                  //  'owner'             =>  'Owner',
                                 //   'map'               => 'Map',
                                   // 'similar'           => 'Similar Listings',
                                )
        ),

        '3' =>array(    'enabled' =>    array(
                                    'description'       => 'Description',
                                    'gallery'           => 'Gallery',
                                    'price_details'     => 'Price Details',
                                    'sleeping'          => 'Sleeping Arrangements',
                                    'address'           => 'Address',
                                    'listing_details'   => 'Listing Details',
                                    'features'          => 'Features',
                                    'terms'             => 'Terms and Conditions',
                                    'nearby'            => 'What\'s Nearby',
                                    'virtual_tour'      => 'Virtual Tour',
                                    'availability'      => 'Availability',
                                    'reviews'           => 'Reviews',
                                    'owner'             =>  'Owner',
                                    'map'               => 'Map',
                                    'similar'           => 'Similar Listings',
                                )
        ),
        '4' =>array(    'enabled' =>    array(
                                   
                                    'price_details'     => 'Price Details',
                                    'sleeping'          => 'Sleeping Arrangements',
                                    'address'           => 'Address',
                                    'listing_details'   => 'Listing Details',
                                    'features'          => 'Features',
                                    'terms'             => 'Terms and Conditions',
                                    'nearby'            => 'What\'s Nearby',
                                    'virtual_tour'      => 'Virtual Tour',
                                    'availability'      => 'Availability',
                                    'reviews'           => 'Reviews',
                                   
                                )
        ),



    );


    return $layout_data[$layout_version];

}

/*
*
* Property content layout  
*
*/



if(!function_exists('wprentals_property_content_layout')):
    function wprentals_property_content_layout($postID){
       
        $enable_layout =  wprentals_get_option('wprentals_layout_manager') ;

        if($enable_layout =='no'){
            $layout=wprentals_get_default_layout();
        }else{
            $layout=wprentals_get_option('wprentals_property_layout_tabs') ;
        }

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


   

        foreach ($layout['enabled'] as $key=>$label):

            switch ($key) {
                case 'description':
                    echo wpestate_property_description_type_1($postID); 
                    break;
                case 'gallery':
                    echo wpestate_property_gallery_section($postID);   
                    break; 
                case 'price_details':
                    echo wpestate_property_price($postID,$wpestate_property_price_text);
                    break;
                case 'sleeping':
                    echo wpestate_sleeping_situation_wrapper($postID,$wp_estate_sleeping_text); 
                    break;
                case 'address':
                    echo wpestate_property_address_wrapper($postID,$wpestate_property_adr_text);
                    break;    
                case 'listing_details':
                    echo wpestate_property_details_wrapper($postID,$wpestate_property_details_text);
                    break; 
                case 'features':
                    echo wpestate_features_and_ammenities_wrapper($postID,$wpestate_property_features_text);
                    break;  
                case 'terms':
                    echo wpestate_listing_terms_wrapper($postID,$wp_estate_terms_text);
                    break;  
                case 'nearby':
                    echo wpestate_property_yelp_wrapper($postID);
                    break;  
                case 'availability':
                    echo wpestate_property_show_avalability($postID);
                    break;  
                case 'reviews':
                    echo wpestate_property_show_reviews($postID); 
                    break;  
                case 'virtual_tour':
                    wpestate_show_virtual_tour($postID);
                    break;  
                case 'map':
                    echo wpestate_property_show_map($postID); 
                    break;  
                case 'owner':
                    echo wprentals_get_owner_section($postID);
                    break;  
                case 'similar':
                    echo wprentals_get_similar_listing($postID);
                    break;  
            }


        endforeach;    



    }
endif;



/*
*
* Property ownert
*
*/


if(!function_exists('wprentals_get_owner_section')):
    function wprentals_get_owner_section($prop_id){


        $owner_id   =   wpsestate_get_author($prop_id);
        $agent_id   =   get_user_meta($owner_id, 'user_agent_id', true);
        
        $author_email   =   get_the_author_meta( 'user_email'  );
        $preview_img    =   '';
        $content        =   '';
        $agent_skype    =   '';
        $agent_phone    =   '';
        $agent_mobile   =   '';
        $agent_email    =   '';
        $agent_pitch    =   '';
        $link           =   '';
        $return_string  =   '<div class="owner-page-wrapper default-owner-page-wrapper panel-wrapper">
        <div class="owner-wrapper  row" id="listing_owner">';

        if ($agent_id!=0){        

            $args = array(
                'post_type' => 'estate_agent',
                'p' => $agent_id
            );
    
            $agent_selection = new WP_Query($args);
            $thumb_id       = '';
           
       
            $name           = esc_html__('No agent','wprentals');
    
            if( $agent_selection->have_posts() ){
       
                   while ($agent_selection->have_posts()): $agent_selection->the_post(); 
                        $agent_id = get_the_ID(); 
                        $thumb_id           = get_post_thumbnail_id($agent_id);
                        $preview            = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                        $preview_img         = $preview[0];
                        $agent_skype         = esc_html( get_post_meta($agent_id, 'agent_skype', true) );
                        $agent_phone         = esc_html( get_post_meta($agent_id, 'agent_phone', true) );
                        $agent_mobile        = esc_html( get_post_meta($agent_id, 'agent_mobile', true) );
                        $agent_email         = esc_html( get_post_meta($agent_id, 'agent_email', true) );
                        if($agent_email==''){
                            $agent_email=$author_email;
                        }
                        $agent_pitch         = esc_html( get_post_meta($agent_id, 'agent_pitch', true) );
                      
                        if (function_exists('icl_translate') ){
                            $agent_posit      =   icl_translate('wprentals','agent_position', esc_html( get_post_meta($agent_id, 'agent_position', true) ) );
                        }else{
                            $agent_posit        = esc_html( get_post_meta($agent_id, 'agent_position', true) );
                        }
                
                        $agent_facebook      = esc_html( get_post_meta($agent_id, 'agent_facebook', true) );
                        $agent_twitter       = esc_html( get_post_meta($agent_id, 'agent_twitter', true) );
                        $agent_linkedin      = esc_html( get_post_meta($agent_id, 'agent_linkedin', true) );
                        $agent_pinterest     = esc_html( get_post_meta($agent_id, 'agent_pinterest', true) );
                        $link                = esc_url ( get_permalink() );
                        $name                = get_the_title();
                        $content             = get_the_content();
                        $content             = apply_filters('the_content', $content);
                        $content             = str_replace(']]>', ']]&gt;', $content);
    
                 
                   endwhile;
                   wp_reset_query();
                  
            }else{// end if have posts
                //$agent_id
                $first_name             =   get_the_author_meta( 'first_name' , $owner_id );
                $last_name              =   get_the_author_meta( 'last_name' , $owner_id );
                $user_email             =   get_the_author_meta( 'user_email' , $owner_id );
                $name                   =   $first_name.' '.$last_name;
                $content                =   get_the_author_meta( 'description' , $owner_id );
                $link                   =   '';
                $preview_img            =   get_the_author_meta( 'custom_picture' , $owner_id );
           }
        }   // end if !=0


        if($preview_img==''){
            $preview_img    =   get_stylesheet_directory_uri().'/img/default_user.png';
        }
        $verified_class = ( wpestate_userid_verified($owner_id) ) ? ' verified' : '';


        $return_string.=' <div class="col-md-3 agentpic-wrapper '.esc_attr($verified_class).'">
                            <div class="owner_listing_image " style="background-image: url('.esc_url($preview_img).');">
                                '.wpestate_display_verification_badge($owner_id).'
                            </div>
                        </div>

        <div class="col-md-9 agentpic-wrapper">
            <h3 itemprop="agent">'.esc_html($name).'</h3>';
	     
            if($content!=''){                            
                $return_string.= '<div class="owner_area_description">'.trim($content).'</div>';     
            }
        
            if($link!=''){
                $return_string.='<a class="owner_read_more " href="'.esc_url($link).'">'. esc_html__('See Owner Profile','wprentals').'</a>';
        
            } 

        
            if( wprentals_get_option('wp_estate_replace_booking_form','') == 'no'){  
                $return_string.='<div  id="contact_me_long" class=" owner_read_more " data-postid="'. esc_attr($prop_id).'">
                    '.esc_html__('Contact Owner','wprentals').'
                </div>';
            }
            $return_string.='</div>';

        $return_string.=' </div>
        </div>';

         
        return $return_string;

    } //end function
endif;










/*
*
* Property Similar  
*
*/


if(!function_exists('wprentals_get_similar_listing')):
function wprentals_get_similar_listing($postID){
       
        $is_widget              = 0;
        $wpestate_full_page     = 1;
        $counter                = 0;
        $post_category          = get_the_terms($postID, 'property_category');
        $post_action_category   = get_the_terms($postID, 'property_action_category');
        $post_city_category     = get_the_terms($postID, 'property_city');
        $similar_no             = 3;
        $args                   = '';
        $items[]                = '';
        $items_actions[]        = '';
        $items_city[]           = '';
        $categ_array            = '';
        $action_array           = '';
        $city_array             = '';
        $not_in                 = array();
        $not_in[]               = $postID;
        $return_string          = '';
    
        $listing_page_type      =   wprentals_get_option('wp_estate_listing_page_type','');
    
        if($listing_page_type == 1){
            $wpestate_full_page=0;
        }
        ////////////////////////////////////////////////////////////////////////////
        /// compose taxomomy categ array
        ////////////////////////////////////////////////////////////////////////////
    
        if ($post_category!=''):
            foreach ($post_category as $item) {
                $items[] = $item->term_id;
            }
            $categ_array=array(
                    'taxonomy' => 'property_category',
                    'field' => 'id',
                    'terms' => $items
                );
        endif;
    
        ////////////////////////////////////////////////////////////////////////////
        /// compose taxomomy action array
        ////////////////////////////////////////////////////////////////////////////
    
        if ($post_action_category!=''):
            foreach ($post_action_category as $item) {
                $items_actions[] = $item->term_id;
            }
            $action_array=array(
                    'taxonomy' => 'property_action_category',
                    'field' => 'id',
                    'terms' => $items_actions
                );
        endif;
    
        ////////////////////////////////////////////////////////////////////////////
        /// compose taxomomy action city
        ////////////////////////////////////////////////////////////////////////////
    
        if ($post_city_category!=''):
            foreach ($post_city_category as $item) {
                $items_city[] = $item->term_id;
            }
            $city_array=array(
                    'taxonomy' => 'property_city',
                    'field' => 'id',
                    'terms' => $items_city
                );
        endif;
    
        ////////////////////////////////////////////////////////////////////////////
        /// compose wp_query
        ////////////////////////////////////////////////////////////////////////////
    
        if(isset($show_sim_two) && $show_sim_two==1){
            $similar_no=2;
        }
    
        $args=array(
            'showposts'             => $similar_no,      
            'ignore_sticky_posts'   => 0,
            'post_type'             => 'estate_property',
            'post_status'           => 'publish',
            'post__not_in'          => $not_in,
            'tax_query'             => array(
            'relation'              => 'AND',
                                    $categ_array,
                                    $action_array,
                                    $city_array
                                    )
        );
    
        global $wpestate_listing_type;
        $wpestate_listing_type   =   wprentals_get_option('wp_estate_listing_unit_type','');
        $compare_submit          =   wpestate_get_template_link('compare_listings.php');
        $my_query                =   new WP_Query($args);
       
        if ($my_query->have_posts()) {	
            $return_string.='<div class="similar_listings_wrapper similar_listings_layout_section">
                <div class="similar_listings">
                     
                    <h3 class="agent_listings_title_similar" >'. esc_html__('Similar Listings', 'wprentals').'</h3>  
                    <div class="similar_listings_wrapper_flex">';
                        
                        $wpestate_property_unit_slider= esc_html ( wprentals_get_option('wp_estate_prop_list_slider','') );    
                        ob_start();
                        while ($my_query->have_posts()):
                            $my_query->the_post();
                            include(locate_template('templates/property_unit.php') ); 
                        endwhile;
                        $listings = ob_get_contents();
                        ob_end_clean();
                    $return_string.=$listings.'    
                    </div>
                </div>
            </div>';
        } //endif have post
     
        wp_reset_query();

        return $return_string;
}
endif;
    
/*
*
* Property Similar  
*
*/

if( !function_exists('wpestate_property_show_similar_listings')):
    function wpestate_property_show_similar_listings(){
        $return_string='';

        ob_start();
        include(locate_template ('/templates/similar_listings.php'));
        $similar=ob_get_contents();
        ob_end_clean();
    
        $return_string.=$similar;      
        return $return_string;
    }
endif;


/*
*
* Property Map 
*
*/

if( !function_exists('wpestate_property_show_map')):
    function wpestate_property_show_map($postid){
        $return_string='';
        $wpestate_gmap_lat          =   floatval( get_post_meta($postid, 'property_latitude', true));
        $wpestate_gmap_long         =   floatval( get_post_meta($postid, 'property_longitude', true));
        
        $return_string.='<div class="property_page_container google_map_type1"> 
        <h3 class="panel-title" id="on_the_map">'.esc_html__('On the Map','wprentals').'</h3>
        <div class="google_map_on_list_wrapper">  
          
                <div id="gmapzoomplus"></div>
                <div id="gmapzoomminus"></div>';
                
                if( wprentals_get_option('wp_estate_kind_of_map')==1){
                
                    $return_string.='<div id="gmapstreet"></div>';
                    $return_string.= wpestate_show_poi_onmap();
                }
                
            
                $return_string.='<div id="google_map_on_list" 
                data-cur_lat="'.esc_attr($wpestate_gmap_lat).'" 
                data-cur_long="'.esc_attr($wpestate_gmap_long ).'" 
                data-post_id="'.intval($postid).'">
                </div>
            </div>    
        </div>';

    return $return_string;

    }
endif;





/*
*
* Property reviews 
*
*/
if( !function_exists('wpestate_property_show_reviews')):
    function wpestate_property_show_reviews($postID ){
        $return_string='';

        $nr_of_reviews    = wp_count_comments( $postID );
        $cpaged           = ( get_query_var( 'rp' ) != '' ) ? get_query_var( 'rp' ) : 1;
        $reviews_per_page = 7;// set low for testing purposes
        $review_pages     = ceil( $nr_of_reviews->approved / $reviews_per_page );
        
        $args = array(
            'number'  => intval( $reviews_per_page ),
            'post_id' => $postID, // use post_id, not post_ID
            'paged'   => intval( $cpaged )
        );
        
        $comments   =   get_comments($args);
        $coments_no =   0;
        $stars_total=   0;
        $review_templates=' ';
        
        foreach($comments as $comment) :
            $coments_no++;
            
            $userId         =   $comment->user_id;
         
            if($userId == 1){
                $reviewer_name="admin";
                $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
            }else{
                $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
                $reviewer_name  =   get_the_title($userid_agent);    
                if($userid_agent==''){
                    $reviewer_name=   $comment->comment_author;
                }
                
            }
        
            if($userid_agent==''){
                $user_small_picture_id     =    get_the_author_meta( 'small_custom_picture' ,  $comment->user_id,true  );
                $preview                   =    wp_get_attachment_image_src($user_small_picture_id,'wpestate_user_thumb');
                $preview_img               =    '';
                if(isset($preview[0])){
                    $preview_img               =    $preview[0];
                }
              
            }else{
                $thumb_id           = get_post_thumbnail_id($userid_agent);
                $preview            = wp_get_attachment_image_src($thumb_id, 'thumbnail');
                $preview_img               =    '';
                if(isset($preview[0])){
                    $preview_img               =    $preview[0];
                }
            }
        
            if($preview_img==''){
                   $preview_img    =   get_stylesheet_directory_uri().'/img/default-user.png';
            }
            
            $rating= get_comment_meta( $comment->comment_ID , 'review_stars', true );
            $review_templates.='  
                 <div class="listing-review">
                             
        
                                <div class="col-md-8 review-list-content norightpadding">
                                    <div class="reviewer_image"  style="background-image: url('.esc_url($preview_img).');"></div>
                                  
                                    <div class="reviwer-name">'.esc_html($reviewer_name).'</div>
                                    
                                    <div class="review-date">
                                        '.esc_html__( 'Posted on ','wprentals' ). ' '. get_comment_date('j F Y',$comment->comment_ID).' 
                                    </div>
                                    
                                    <div class="property_ratings">';
        
                                    $review_templates .= wpestate_display_rating($rating, 'total');
                                    $total_rating = wpestate_get_star_total_rating($rating);
                                    $review_templates.=' <span class="ratings-star">( ' . $total_rating . ' ' . esc_html__( 'of','wprentals').' 5)</span>
                                    </div>
        
                                    <div class="review-content">
                                        '. $comment->comment_content .'                                
                                    </div>
                                </div>
                            </div>       ';
        
        endforeach;
        
        if($coments_no>0){
            $list_rating = get_post_meta($postID, 'property_stars', TRUE);
            if ( ! $list_rating ) {
                $list_rating = wpestate_calculate_property_rating( $postID );
            }
        
        $return_string.='    
        <div class="property_page_container for_reviews">
            <div class="listing_reviews_wrapper">
                    <div class="listing_reviews_container">
                        <h3 id="listing_reviews" class="panel-title">
                               '.sprintf( _n('%d Review', '%d Reviews', $coments_no, 'wprentals'), $nr_of_reviews->approved ).'                                                            
                        </h3>
                        
                        <div class="property_ratings">
                            '.wpestate_display_rating($list_rating, 'complete').'
                        </div>
        
                        '.trim($review_templates);
                        ob_start();
                        wpestate_review_pagination($review_pages, 2);
                        $pagination=ob_get_contents();
                        ob_end_clean();

                        $return_string.=$pagination.'
                </div>
              
            </div>
        </div>';
        } 
    
        return $return_string;
    }
endif;








/*
*
* Property Details show avalability  
*
*/

if( !function_exists('wpestate_property_show_avalability')):
    function wpestate_property_show_avalability($postID){
        $return_string          =   '';
        $start_reservation      =   '' ;
        $end_reservation        =   '';
        $reservation_class      =   '';
        $booking_type           =   wprentals_return_booking_type($postID);
        $mega_details           =   wpml_mega_details_adjust($postID);
        $price_per_weekeend     =   intval   ( get_post_meta($postID, 'price_per_weekeend', true) );


        $price_array=array(
            'price_per_weekeend'        =>      get_post_meta($postID, 'price_per_weekeend', true),
            'custom_price'              =>      wpml_custom_price_adjust($postID),
            'default_price'             =>      get_post_meta($postID,'property_price',true),
            'price_per_guest_from_one'  =>      get_post_meta($postID, 'price_per_guest_from_one', true) ,
            'extra_price_per_guest'     =>      get_post_meta($postID, 'extra_price_per_guest', true) ,
        );


   

        $return_string.= '<h3 class="panel-title" id="listing_calendar">'.esc_html__( 'Availability', 'wprentals').'</h3>';
        

        $return_string.='<div class="property_page_container wprentals_front_avalability">';
        if($booking_type==2 ){
            $return_string.='<div id="all-front-calendars_per_hour"></div>';
        }else{
            $return_string.='<div class="all-front-calendars"><div id="calendar-next"><i class="fas fa-chevron-right"></i></div>
            <div id="calendar-prev"><i class="fas fa-chevron-left"></i></div>
            <div class="separator"></div>';

            $reservation_array  = get_post_meta($postID, 'booking_dates',true  );


            if(!is_array($reservation_array)){
                $reservation_array=array();
            }
            ob_start();
                wpestate_get_calendar_custom_avalability ($reservation_array,$mega_details,$price_array,true,true);
                $calendar_custom_avalability=ob_get_contents();
            ob_end_clean();
            $return_string.=$calendar_custom_avalability;

            $return_string.='  <div class="calendar-legend">
                <div class="calendar-legend-past"></div> <span>'. esc_html__('past','wprentals').'</span>
                <div class="calendar-legend-today"></div> <span> '. esc_html__('today','wprentals').'</span>
                <div class="calendar-legend-reserved"></div> <span> '. esc_html__('booked','wprentals').'</span>
            </div>
        </div>';

        }
        $return_string.='</div>';    
        wp_reset_query();
        
        return $return_string;
    }
endif;







/*
*
* Property Details Section  type 1
*
*/

if(!function_exists('wpestate_property_description_type_1')):
    function wpestate_property_description_type_1($post_id){
        $return_string          =   '';

        $return_string  .='<div class="listing_description_wrapper panel-wrapper ">
        <div id="listing_description">';
            
                $content = get_the_content($post_id);
                $content = apply_filters('the_content', $content);
                $content = str_replace(']]>', ']]&gt;', $content);
                if($content!=''){   

                    $wpestate_property_description_text =  wprentals_get_option('wp_estate_property_description_text');
                    if (function_exists('icl_translate') ){
                        $wpestate_property_description_text     =   icl_translate('wprentals','wp_estate_property_description_text', esc_html( wprentals_get_option('wp_estate_property_description_text') ) );
                    }
                    $return_string  .= '<h4 class="panel-title-description">'.esc_html($wpestate_property_description_text).'</h4>';
                    $return_string  .= '<div class="panel-body" id="listing_description_content"  itemprop="description">'.$content;
                    ob_start();
                    get_template_part ('/templates/download_pdf');
                    $downloads=ob_get_contents();
                    ob_end_clean();
                    
                    $return_string  .= $downloads.'</div>'; //escpaed above      
                }
        $return_string  .='    
        </div>
        
       <div id="view_more_desc">'. esc_html__('View more','wprentals').'</div>
    </div>';

    return $return_string;
    }
endif;    


/*
*
* Property Details Section  type 1
*
*/

if(!function_exists('wpestate_property_description_type_2')):
    function wpestate_property_description_type_2($post_id){
        $return_string          =   '';

        $return_string  .='<div class="listing_description_wrapper panel-wrapper ">
        <div id="listing_description">';
            
                $content = get_the_content($post_id);
                $content = apply_filters('the_content', $content);
                $content = str_replace(']]>', ']]&gt;', $content);
                if($content!=''){   

                    $wpestate_property_description_text =  wprentals_get_option('wp_estate_property_description_text');
                    if (function_exists('icl_translate') ){
                        $wpestate_property_description_text     =   icl_translate('wprentals','wp_estate_property_description_text', esc_html( wprentals_get_option('wp_estate_property_description_text') ) );
                    }
                    $return_string  .= '<h4 class="panel-title-description">'.esc_html($wpestate_property_description_text).'</h4>';
                    $return_string  .= '<div class="panel-body" id="listing_description_content"  itemprop="description">'.$content;
                    ob_start();
                    get_template_part ('/templates/download_pdf');
                    $downloads=ob_get_contents();
                    ob_end_clean();
                    
                    $return_string  .= $downloads.'</div>'; //escpaed above      
                }
        $return_string  .='    
        </div>
        
       <div id="view_more_desc">'. esc_html__('View more','wprentals').'</div>
    </div>';

    return $return_string;
    }
endif;    

/*
*
* Property yelp Section
*
*/


if (!function_exists('wpestate_property_yelp_wrapper')):
function wpestate_property_yelp_wrapper($post_id)
{
    $return_string          =   '';
    $yelp_client_id         =   trim(wprentals_get_option('wp_estate_yelp_client_id', ''));
    $yelp_client_secret     =   trim(wprentals_get_option('wp_estate_yelp_client_secret', ''));

    if ($yelp_client_secret!=='' && $yelp_client_id!=='') {
        $return_string.='<div class="panel-wrapper yelp_wrapper">
                <a class="panel-title" id="yelp_details" data-toggle="collapse" data-parent="#yelp_details" href="#collapseFive"><span class="panel-title-arrow"></span>'.esc_html__('What\'s Nearby', 'wprentals').'</a>
                <div id="collapseFive" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">';
        $return_string.= wpestate_yelp_details($post_id);
        $return_string.='
                    </div>
                </div>

            </div>';
    }

    return $return_string;
}
endif;

/*
*
* Property gallery Section
*
*/


if (!function_exists('wpestate_property_gallery_section')):
    function wpestate_property_gallery_section($post_id){
        $return_string='<div class="panel-wrapper imagebody_wrapper">
           
        <div class="panel-body imagebody imagebody_new">';
             
            $arguments = array(
                'numberposts'       =>  -1,
                'post_type'         =>  'attachment',
                'post_parent'       =>  $post_id,
                'post_status'       =>  null, 
                'orderby'           =>  'menu_order',
                'post_mime_type'    =>  'image',
                'order'             =>  'ASC'
            );
          
            $post_attachments   = get_posts($arguments);

            $count=0;

            $video_id           = esc_html( get_post_meta($post_id, 'embed_video_id', true) );
            $video_type         = esc_html( get_post_meta($post_id, 'embed_video_type', true) );

            
            $total_pictures=count ($post_attachments);
            foreach ($post_attachments as $attachment) {
                $count++;  
            
                if($total_pictures<=4){
                    $new_total_pictures=$total_pictures;
                    if($total_pictures==4){
                        $new_total_pictures=3;
                    }
            
                    $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_listings_page');
                    if($count!=4){
                        if( $count <= $new_total_pictures-1){
                            $return_string.= '<div class="col-md-4 image_gallery" style="background-image:url('.esc_url($full_prty[0]).')"> <div class="img_listings_overlay" ></div> </div>';
                        }else{
                            $return_string.=  '<div class="col-md-4 image_gallery" style="background-image:url('.esc_url($full_prty[0]).')">
                           <div class="img_listings_overlay img_listings_overlay_last" ></div>
                           <span class="img_listings_mes">'.esc_html__( 'See all','wprentals').' '. $total_pictures .' '.esc_html__( 'photos','wprentals').'</span></div>';
                        }
                    }
                }
            
                if( $total_pictures> 4){
                    if($count <= 3 ){
                        $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_places');
                        $return_string.=  '<div class="col-md-4 image_gallery" style="background-image:url('.esc_url($full_prty[0]).')"> <div class="img_listings_overlay" ></div> </div>';
            
                    }
                    if($count ==4 ){
                        $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_places');
                        $return_string.=  '<div class="col-md-8 image_gallery" style="background-image:url('.esc_url($full_prty[0]).')  ">   <div class="img_listings_overlay" ></div></div>';
                    }
            
                    if($count ==5 ){
                        $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_listings_page');
                        $return_string.=  '<div class="col-md-4 image_gallery" style="background-image:url('.esc_url($full_prty[0]).')  ">
                            <div class="img_listings_overlay img_listings_overlay_last" ></div>
                            <span class="img_listings_mes">'.esc_html__( 'See all','wprentals').' '.esc_html($total_pictures).' '.esc_html__( 'photos','wprentals').'</span></div>';
                    }
                }   
            }
        
            $return_string.='</div>
        
        
            <div class="panel-body video-body">';
          
           
            if($video_id!=''){
                if($video_type=='vimeo'){
                    $return_string.= wpestate_custom_vimdeo_video($video_id);
                }else{
                    $return_string.= wpestate_custom_youtube_video($video_id);
                }    
            }
          
            $return_string.='</div>
 
        </div>';
    
        return $return_string;
    }
    endif;
    



/*
*
* Property Price Section
*
*/




if (!function_exists('wpestate_property_price')):
function wpestate_property_price($post_id, $wpestate_property_price_text){
    $return_string='';
    
    $return_string.='<div class="panel-wrapper" id="listing_price">
    <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseOne"> <span class="panel-title-arrow"></span>';
    
    if ($wpestate_property_price_text!='') {
        $return_string.= esc_html($wpestate_property_price_text);
    } else {
        $return_string.= esc_html__('Property Price', 'wprentals');
    }
    $return_string.='</a>';

    $return_string.='<div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body panel-body-border" itemprop="priceSpecification" >';
                            $return_string.=  estate_listing_price($post_id);
                            ob_start();
                            wpestate_show_custom_details($post_id);
                            wpestate_show_custom_details_mobile($post_id);
                            $details=ob_get_contents();
                            ob_end_clean();
                            
                            $return_string.=  $details;
                           
                    
        $return_string.='</div>
                </div>';

    $return_string.='</div>';

    return $return_string;
}
endif;











if (!function_exists('wpestate_property_address_wrapper')):
function wpestate_property_address_wrapper($post_id, $wpestate_property_adr_text)
{
    $return_string='
        <div class="panel-wrapper">
            <!-- property address   -->
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTwo">  <span class="panel-title-arrow"></span>';

    if ($wpestate_property_adr_text!='') {
        $return_string.=esc_html($wpestate_property_adr_text);
    } else {
        $return_string.=esc_html__('Property Address', 'wprentals');
    }

    $return_string.='
            </a>

            <div id="collapseTwo" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    '.estate_listing_address($post_id).'
                </div>

            </div>
        </div>';

    return $return_string;
}
endif;







if (!function_exists('wpestate_property_details_wrapper')):
function wpestate_property_details_wrapper($post_id, $wpestate_property_details_text)
{
    $return_string='
    <!-- property details   -->
        <div class="panel-wrapper">';

    if ($wpestate_property_details_text=='') {
        $return_string.='<a class="panel-title" id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html__('Property Details', 'wprentals').'  </a>';
    } else {
        $return_string.='<a class="panel-title"  id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html($wpestate_property_details_text).'</a>';
    }

    $return_string.='
            <div id="collapseTree" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">';
    $return_string.= estate_listing_details($post_id);
    $return_string.='
                </div>
            </div>
        </div>';

    return $return_string;
}
endif;









if (!function_exists('wpestate_features_and_ammenities_wrapper')):
function wpestate_features_and_ammenities_wrapper($post_id, $wpestate_property_features_text)
{
    $return_string='<div class="panel-wrapper features_wrapper">';


    $terms = get_terms(array(
                'taxonomy' => 'property_features',
                'hide_empty' => false,
            ));
    if (count($terms)!=0 && !count($terms)!=1) {
        if ($wpestate_property_features_text =='') {
            $return_string.= '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'.esc_html__('Amenities and Features', 'wprentals').'</a>';
        } else {
            $return_string.= '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'. $wpestate_property_features_text.'</a>';
        }
        $return_string.='
                <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        '.estate_listing_features($post_id).'
                    </div>
                </div>';
    } // end if are features and ammenties
    $return_string.='</div>';

    return $return_string;
}
endif;




if (!function_exists('wpestate_listing_terms_wrapper')):
function wpestate_listing_terms_wrapper($post_id, $wp_estate_terms_text)
{
    $do_we_show         =   trim(wprentals_get_option('wp_estate_show_terms_conditions', ''));
    if ($do_we_show=='no') {
        return;
    }

    $test = trim(esc_html(get_post_meta($post_id, 'smoking_allowed', true)));
    if ($test=='') {// terms were not saved until now - nothing to display
        return;
    }

    $return_string='<!-- property termd   -->
         <div class="panel-wrapper">

            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_terns" href="#collapseTerms">  <span class="panel-title-arrow"></span>';
    if ($wp_estate_terms_text!='') {
        $return_string.= esc_html($wp_estate_terms_text);
    } else {
        $return_string.= esc_html__('Terms and Conditions', 'wprentals');
    }
    $return_string.='
            </a>

            <div id="collapseTerms" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">';
    $return_string.= wpestate_listing_terms($post_id);
    $return_string.='
                </div>

            </div>
        </div>';

    return $return_string;
}
endif;









if (!function_exists('wpestate_sleeping_situation_wrapper')):
function wpestate_sleeping_situation_wrapper($post_id, $wp_estate_sleeping_text)
{
    $do_we_show         =   trim(wprentals_get_option('wp_estate_show_sleeping_arrangements', ''));

    if ($do_we_show=='no') {
        return;
    }

    $property_bedrooms  =   intval(get_post_meta($post_id, 'property_bedrooms', true));
    $return_string      =   '';
    $beds_options=get_post_meta($post_id, 'property_bedrooms_details', true);

    if (!is_array($beds_options)) {
        return '';
    }

    if ($property_bedrooms!=0) {
        $return_string.='
            <div class="panel-wrapper">
                <!-- property address   -->
                <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_sleepibg" href="#collapseSleep">  <span class="panel-title-arrow"></span>';

        if ($wp_estate_sleeping_text!='') {
            $return_string.= esc_html($wp_estate_sleeping_text);
        } else {
            $return_string.= esc_html__('Sleeping Situation', 'wprentals');
        }

        $return_string.='</a>

                <div id="collapseSleep" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">';
        $return_string.=wpestate_sleeping_situation($post_id);
        $return_string.='
                    </div>

                </div>
            </div>';
    }

    return $return_string;
}
endif;










if (!function_exists('wprentals_card_owner_image')):
function wprentals_card_owner_image($post_id)
{
    $author_id          =   wpsestate_get_author($post_id);
    $agent_id           =   get_user_meta($author_id, 'user_agent_id', true);
    $thumb_id_agent     =   get_post_thumbnail_id($agent_id);
    $preview_agent      =   wp_get_attachment_image_src($thumb_id_agent, 'wpestate_user_thumb');
    $preview_agent_img  =   '';
    if( isset($preview_agent[0]) ){
        $preview_agent_img  =   $preview_agent[0];
    }
    $agent_link         =   esc_url(get_permalink($agent_id));

    if ($preview_agent_img   ==  '') {
        $preview_agent_img    =   get_stylesheet_directory_uri().'/img/default_user_small.png';
    }


    if ($thumb_id_agent=='') {
        $preview_agent_img   = get_the_author_meta('custom_picture', $agent_id);
        return '<div class="owner_thumb" style="background-image: url('. esc_url($preview_agent_img).')"></div>';
    } else {
        return '<a href="'.esc_url($agent_link).'" class="owner_thumb" style="background-image: url('. esc_url($preview_agent_img).')"></a>';
    }
}
endif;






if (!function_exists('wprentals_icon_bar_design')):
function wprentals_icon_bar_design()
{
    global $post;
    $custom_listing_fields = wprentals_get_option('wp_estate_property_page_header', '');




    if (is_array($custom_listing_fields)) {
        foreach ($custom_listing_fields as   $key=>$field) {
            if ($field[2]=='property_category' || $field[2]=='property_action_category' ||  $field[2]=='property_city' ||  $field[2]=='property_area') {
                $value  =   get_the_term_list($post->ID, $field[2], '', ', ', '');
            } else {
                $slug       =   wpestate_limit45(sanitize_title($field[2]));
                $slug       =   sanitize_key($slug);
                $value      =   esc_html(get_post_meta($post->ID, $slug, true));
            }


            if ($value!='') {
                print '<span class="no_link_details custom_prop_header">';

                if ($field[0]!='') {
                    print '<strong>'.esc_html(stripslashes($field[0])).'</strong> ';
                } elseif ($field[3]!='') {
                    print '<img src="'.esc_url($field[3]).'" alt="'.esc_html__('icon', 'wprentals').'">';
                } elseif ($field[1]!='') {
                    print '<i class="'.esc_attr($field[1]).'"></i>';
                }
                print '<span>';
                $measure_sys        =   esc_html(wprentals_get_option('wp_estate_measure_sys', ''));
                if ($field[2]=='property_size') {
                    print number_format($value) . ' '.$measure_sys.'<sup>2</sup>';
                } else {
                    print trim($value);
                }

                print '</span>';

                print '</span>';
            }
        }
    }
}
endif;




if (!function_exists('wprentals_icon_bar_classic')):
function wprentals_icon_bar_classic($property_action, $property_category, $rental_type, $guests, $bedrooms, $bathrooms)
{
    if ($property_action!='') {
        print '<div class="actions_icon category_details_wrapper_icon">'.trim($property_action).' <span class="property_header_separator">|</span></div>
        <div class="schema_div_noshow"  itemprop="actionStatus">'.strip_tags($property_action).'</div>';
    }

    if ($property_category!='') {
        print'<div class="types_icon category_details_wrapper_icon">'. trim($property_category).'<span class="property_header_separator">|</span></div>
        <div class="schema_div_noshow"  itemprop="additionalType">'. strip_tags($property_category).'</div>';
    }


    if ($rental_type==0) {
        if ($guests==0) {
            //nothing
        } elseif ($guests==1) {
            print '<div class="no_link_details category_details_wrapper_icon guest_header_icon">'.$guests.' '. esc_html__('Guest', 'wprentals').'</div>';
        } else {
            print '<div class="no_link_details category_details_wrapper_icon guest_header_icon">'.$guests.' '. esc_html__('Guests', 'wprentals').'</div>';
        }

        print '<span class="property_header_separator">|</span>';

        if ($bedrooms==1) {
            print  '<span class="no_link_details category_details_wrapper_icon bedrooms_header_icon">'.$bedrooms.' '.esc_html__('Bedroom', 'wprentals').'</span>';
        } else {
            print  '<span class="no_link_details category_details_wrapper_icon bedrooms_header_icon">'.$bedrooms.' '.esc_html__('Bedrooms', 'wprentals').'</span>';
        }
        print '<span class="property_header_separator">|</span>';

    }
}
endif;





function wp_get_attachment($attachment_id)
{
    $attachment = get_post($attachment_id);
    return array(
        'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'href' => esc_url(get_permalink($attachment->ID)),
        'src' => $attachment->guid,
        'title' => $attachment->post_title
    );
}
///////////////////////////////////////////////////////////////////////////////////////////
// List features and ammenities
///////////////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_build_terms_array')):
    function wpestate_build_terms_array()
    {
        $parsed_features = wpestate_request_transient_cache('wpestate_get_features_array');
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $parsed_features=false;
        }
        if ($parsed_features===false) {
            $parsed_features=array();
            $terms = get_terms(array(
                    'taxonomy' => 'property_features',
                    'hide_empty' => false,
                    'parent'=> 0

                ));


            foreach ($terms as $key=>$term) {
                $temp_array=array();
                $child_terms = get_terms(array(
                        'taxonomy' => 'property_features',
                        'hide_empty' => false,
                        'parent'=> $term->term_id
                    ));

                $children=array();
                if (is_array($child_terms)) {
                    foreach ($child_terms as $child_key=>$child_term) {
                        $children[]=$child_term->name;
                    }
                }

                $temp_array['name']=$term->name;
                $temp_array['childs']=$children;

                $parsed_features[]=$temp_array;
            }
            if ( !defined( 'ICL_LANGUAGE_CODE' ) ) {
                wpestate_set_transient_cache('wpestate_get_features_array', $parsed_features, 60*60*4);
            }
                
        }

        return $parsed_features;
    }
endif;





if (!function_exists('estate_listing_features')):
    function estate_listing_features($post_id)
    {
        $single_return_string   =   '';
        $multi_return_string    =   '';
        $show_no_features       =   esc_html(wprentals_get_option('wp_estate_show_no_features', ''));
        $property_features      =   get_the_terms($post_id, 'property_features');
        $parsed_features        =   wpestate_build_terms_array();



        if (is_array($parsed_features)) {
            foreach ($parsed_features as $key => $item) {
                if (count($item['childs']) >0) {
                    $multi_return_string_part=  '<div class="listing_detail col-md-12 feature_block_'.$item['name'].' ">';
                    $multi_return_string_part.=  '<div class="feature_chapter_name col-md-12">'.$item['name'].'</div>';

                    $multi_return_string_part_check='';
                    if (is_array($item['childs'])) {
                        foreach ($item['childs'] as $key_ch=>$child) {
                            $temp   = wpestate_display_feature($show_no_features, $child, $post_id, $property_features);
                            $multi_return_string_part .=$temp;
                            $multi_return_string_part_check.=$temp;
                        }
                    }
                    $multi_return_string_part.=  '</div>';

                    if ($multi_return_string_part_check!='') {
                        $multi_return_string.=$multi_return_string_part;
                    }
                } else {
                    $single_return_string .= wpestate_display_feature($show_no_features, $item['name'], $post_id, $property_features);
                }
            }
        }
        if (trim($single_return_string)!='') {
            $multi_return_string= $multi_return_string.'<div class="listing_detail col-md-12 feature_block_others "><div class="feature_chapter_name col-md-12">'.esc_html__('Other Features ', 'wprentals').'</div>'.$single_return_string.'</div>';
        }
        return $multi_return_string;
    }
endif; // end   estate_listing_features







if (!function_exists('wpestate_display_feature')):
    function wpestate_display_feature($show_no_features, $term_name, $post_id, $property_features)
    {
        $return_string  =   '';
        $term_object    =   get_term_by('name', $term_name, 'property_features');
        $term_meta      =   get_option("taxonomy_$term_object->term_id");
        $term_icon      =   '';
        $term_icon_wp   =   '';


        if ($term_meta!='') {
            $term_icon =  '<img class="property_features_svg_icon" src="'.$term_meta['category_featured_image'].'" >';
      
            if( $term_meta['category_featured_image'] !='' ){
                $term_icon_wp = wp_remote_get($term_meta['category_featured_image']);
              //  $term_icon='<svg width="18" height="18" style="color:#a8fc81;fill:#a8fc81;">       
               // <image xlink:href="'.esc_url($term_meta['category_featured_image'] ).'"  width="18" height="18"/>    
                //</svg>';
            }


            if (is_wp_error($term_icon_wp)) {
                $term_icon='';
            } else {
                $term_icon=wp_remote_retrieve_body($term_icon_wp);

               //$term_icon='<svg width="18" height="18">       
                //    <image xlink:href="'.esc_url($term_meta['category_featured_image']).'" src="yourfallback.png" width="18" height="18"/>    
                 //   </svg>';
            }

     
        }

        if ($show_no_features!='no') {
            if (is_array($property_features) && array_search($term_name, array_column($property_features, 'name')) !== false) {
                if ($term_icon=='') {
                    $term_icon='<i class="fas fa-check checkon"></i>';
                }

                $return_string .= '<div class="listing_detail col-md-6">'.$term_icon. trim($term_name) . '</div>';
            } else {
                if ($term_icon=='') {
                    $term_icon='<i class="fas fa-times"></i>';
                }
                $return_string  .=  '<div class="listing_detail not_present col-md-6">'.$term_icon. trim($term_name). '</div>';
            }
        } else {
            if (is_array($property_features) &&  array_search($term_name, array_column($property_features, 'name')) !== false) {
                if ($term_icon=='') {
                    $term_icon='<i class="fas fa-check checkon"></i>';
                }
                $return_string .=  '<div class="listing_detail col-md-6">'.$term_icon. trim($term_name) .'</div>';
            }
        }

        return $return_string;
    }
endif;





///////////////////////////////////////////////////////////////////////////////////////////
// dashboard price
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('estate_listing_price')):
    function estate_listing_price($post_id)
    {
        $return_string                  =   '';
        $property_price                 =   floatval(get_post_meta($post_id, 'property_price', true));
        $property_price_before_label    =   esc_html(get_post_meta($post_id, 'property_price_before_label', true));
        $property_price_after_label     =   esc_html(get_post_meta($post_id, 'property_price_after_label', true));
        $property_price_per_week        =   floatval(get_post_meta($post_id, 'property_price_per_week', true));
        $property_price_per_month       =   floatval(get_post_meta($post_id, 'property_price_per_month', true));
        $cleaning_fee                   =   floatval(get_post_meta($post_id, 'cleaning_fee', true));
        $city_fee                       =   floatval(get_post_meta($post_id, 'city_fee', true));
        $cleaning_fee_per_day           =   floatval(get_post_meta($post_id, 'cleaning_fee_per_day', true));
        $city_fee_percent               =   floatval(get_post_meta($post_id, 'city_fee_percent', true));
        $city_fee_per_day               =   floatval(get_post_meta($post_id, 'city_fee_per_day', true));
        $price_per_guest_from_one       =   floatval(get_post_meta($post_id, 'price_per_guest_from_one', true));
        $overload_guest                 =   floatval(get_post_meta($post_id, 'overload_guest', true));
        $checkin_change_over            =   floatval(get_post_meta($post_id, 'checkin_change_over', true));
        $checkin_checkout_change_over   =   floatval(get_post_meta($post_id, 'checkin_checkout_change_over', true));
        $min_days_booking               =   floatval(get_post_meta($post_id, 'min_days_booking', true));
        $extra_price_per_guest          =   floatval(get_post_meta($post_id, 'extra_price_per_guest', true));
        $price_per_weekeend             =   floatval(get_post_meta($post_id, 'price_per_weekeend', true));
        $security_deposit               =   floatval(get_post_meta($post_id, 'security_deposit', true));
        $early_bird_percent             =   floatval(get_post_meta($post_id, 'early_bird_percent', true));
        $early_bird_days                =   floatval(get_post_meta($post_id, 'early_bird_days', true));
        $rental_type                    =   esc_html(wprentals_get_option('wp_estate_item_rental_type'));
        $booking_type                   =   wprentals_return_booking_type($post_id);
        $max_extra_guest_no             =   floatval(get_post_meta($post_id, 'max_extra_guest_no', true));
        $week_days=array(
            '0'=>esc_html__('All', 'wprentals'),
            '1'=>esc_html__('Monday', 'wprentals'),
            '2'=>esc_html__('Tuesday', 'wprentals'),
            '3'=>esc_html__('Wednesday', 'wprentals'),
            '4'=>esc_html__('Thursday', 'wprentals'),
            '5'=>esc_html__('Friday', 'wprentals'),
            '6'=>esc_html__('Saturday', 'wprentals'),
            '7'=>esc_html__('Sunday', 'wprentals')

            );

        $wpestate_currency              = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency        = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));

        $th_separator   =   wprentals_get_option('wp_estate_prices_th_separator', '');
        $custom_fields  =   wprentals_get_option('wpestate_currency', '');

        $property_price_show                 =  wpestate_show_price_booking($property_price, $wpestate_currency, $wpestate_where_currency, 1);
        $property_price_per_week_show        =  wpestate_show_price_booking($property_price_per_week, $wpestate_currency, $wpestate_where_currency, 1);
        $property_price_per_month_show       =  wpestate_show_price_booking($property_price_per_month, $wpestate_currency, $wpestate_where_currency, 1);
        $cleaning_fee_show                   =  wpestate_show_price_booking($cleaning_fee, $wpestate_currency, $wpestate_where_currency, 1);
        $city_fee_show                       =  wpestate_show_price_booking($city_fee, $wpestate_currency, $wpestate_where_currency, 1);

        $price_per_weekeend_show             =  wpestate_show_price_booking($price_per_weekeend, $wpestate_currency, $wpestate_where_currency, 1);
        $extra_price_per_guest_show          =  wpestate_show_price_booking($extra_price_per_guest, $wpestate_currency, $wpestate_where_currency, 1);
        $extra_price_per_guest_show          =  wpestate_show_price_booking($extra_price_per_guest, $wpestate_currency, $wpestate_where_currency, 1);
        $security_deposit_show               =  wpestate_show_price_booking($security_deposit, $wpestate_currency, $wpestate_where_currency, 1);

        $setup_weekend_status= esc_html(wprentals_get_option('wp_estate_setup_weekend', ''));
        $weekedn = array(
            0 => __("Sunday and Saturday", "wprentals"),
            1 => __("Friday and Saturday", "wprentals"),
            2 => __("Friday, Saturday and Sunday", "wprentals")
        );





        if ($price_per_guest_from_one!=1) {
            if ($property_price != 0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night col-md-6"><span class="item_head">'.wpestate_show_labels('price_label', $rental_type, $booking_type).':</span> ' .$property_price_before_label.' '. $property_price_show.' '.$property_price_after_label. '</div>';
            }

            if ($property_price_per_week != 0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_7d col-md-6"><span class="item_head">'.wpestate_show_labels('price_week_label', $rental_type, $booking_type).':</span> ' . $property_price_per_week_show . '</div>';
            }

            if ($property_price_per_month != 0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_30d col-md-6"><span class="item_head">'.wpestate_show_labels('price_month_label', $rental_type, $booking_type).':</span> ' . $property_price_per_month_show . '</div>';
            }

            if ($price_per_weekeend!=0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_weekend col-md-6"><span class="item_head">'.esc_html__('Price per weekend ', 'wprentals').'('.$weekedn[$setup_weekend_status].') '.':</span> ' . $price_per_weekeend_show . '</div>';
            }

            if ($extra_price_per_guest!=0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_extra_guest col-md-6"><span class="item_head">'.esc_html__('Extra Price per guest', 'wprentals').':</span> ' . $extra_price_per_guest_show . '</div>';
            }
        } else {
            if ($extra_price_per_guest!=0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_extra_guest_price col-md-6"><span class="item_head">'.esc_html__('Price per guest', 'wprentals').':</span> ' . $extra_price_per_guest_show . '</div>';
            }
        }

        $options_array=array(
            0   =>  esc_html__('Single Fee', 'wprentals'),
            1   =>  ucfirst(wpestate_show_labels('per_night', $rental_type, $booking_type)),
            2   =>  esc_html__('Per Guest', 'wprentals'),
            3   =>  ucfirst(wpestate_show_labels('per_night', $rental_type, $booking_type)).' '.esc_html__('per Guest', 'wprentals')
        );

        if ($cleaning_fee != 0) {
            $return_string.='<div class="listing_detail list_detail_prop_price_cleaning_fee col-md-6"><span class="item_head">'.esc_html__('Cleaning Fee', 'wprentals').':</span> ' . $cleaning_fee_show ;
            $return_string .= ' '.$options_array[$cleaning_fee_per_day];

            $return_string.='</div>';
        }

        if ($city_fee != 0) {
            $return_string.='<div class="listing_detail list_detail_prop_price_tax_fee col-md-6"><span class="item_head">'.esc_html__('City Tax Fee', 'wprentals').':</span> ' ;
            if ($city_fee_percent==0) {
                $return_string .= $city_fee_show.' '.$options_array[$city_fee_per_day];
            } else {
                $return_string .= $city_fee.'%'.' '.__('of price per night', 'wprentals');
            }
            $return_string.='</div>';
        }

        if ($min_days_booking!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_price_min_nights col-md-6"><span class="item_head">'.esc_html__('Minimum no of', 'wprentals').' '.wpestate_show_labels('nights', $rental_type, $booking_type) .':</span> ' . $min_days_booking . '</div>';
        }

        if ($overload_guest!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_price_overload_guest col-md-6"><span class="item_head">'.esc_html__('Allow more guests than the capacity: ', 'wprentals').' </span>'.esc_html__('yes', 'wprentals').'</div>';
        }



        if ($checkin_change_over!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts col-md-6"><span class="item_head">'.esc_html__('Booking starts only on', 'wprentals').':</span> ' . $week_days[$checkin_change_over ]. '</div>';
        }

        if ($security_deposit!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts col-md-6"><span class="item_head">'.esc_html__('Security deposit', 'wprentals').':</span> ' . $security_deposit_show. '</div>';
        }

        if ($checkin_checkout_change_over!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-6"><span class="item_head">'.esc_html__('Booking starts/ends only on', 'wprentals').':</span> ' .$week_days[$checkin_checkout_change_over] . '</div>';
        }


        if ($early_bird_percent!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-6"><span class="item_head">'.esc_html__('Early Bird Discount', 'wprentals').':</span> '.$early_bird_percent.'% '.esc_html__('discount', 'wprentals').' '.esc_html__('for bookings made', 'wprentals').' '.$early_bird_days.' '.esc_html__('nights in advance', 'wprentals').'</div>';
        }
        
        if ($max_extra_guest_no!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-6"><span class="item_head">'.esc_html__('Maximum extra guests allowed', 'wprentals').':</span> ' .sprintf( _n( '%s Guest', '%s Guests', $max_extra_guest_no, 'wprentals' ), number_format_i18n( $max_extra_guest_no ) ).'</div>';
        }
        
        

        $extra_pay_options          =      (get_post_meta($post_id, 'extra_pay_options', true));

        if (is_array($extra_pay_options) && !empty($extra_pay_options)) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-12"><span class="item_head">'.esc_html__('Extra options', 'wprentals').':</span></div>';
            foreach ($extra_pay_options as $key=>$wpestate_options) {
                $return_string.='<div class="extra_pay_option"> ';
                $extra_option_price_show                       =  wpestate_show_price_booking($wpestate_options[1], $wpestate_currency, $wpestate_where_currency, 1);
                $return_string.= ''.$wpestate_options[0].': '. $extra_option_price_show.' '.$options_array[$wpestate_options[2]];

                $return_string.= '</div>';
            }
        }


        return $return_string;
    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// custom details
///////////////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_show_custom_details')):
    function wpestate_show_custom_details($edit_id, $is_dash=0){


        $week_days=array(
            '0'=>esc_html__('All', 'wprentals'),
            '1'=>esc_html__('Monday', 'wprentals'),
            '2'=>esc_html__('Tuesday', 'wprentals'),
            '3'=>esc_html__('Wednesday', 'wprentals'),
            '4'=>esc_html__('Thursday', 'wprentals'),
            '5'=>esc_html__('Friday', 'wprentals'),
            '6'=>esc_html__('Saturday', 'wprentals'),
            '7'=>esc_html__('Sunday', 'wprentals')

            );
        $price_per_guest_from_one       =   floatval(get_post_meta($edit_id, 'price_per_guest_from_one', true));
        $wpestate_currency              =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency        =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $mega                           =   wpml_mega_details_adjust($edit_id);
        $price_array                    =   wpml_custom_price_adjust($edit_id);
        $rental_type                    =   esc_html(wprentals_get_option('wp_estate_item_rental_type', ''));
        $booking_type                   =   wprentals_return_booking_type($edit_id);
        $permited_fields                =   wprentals_get_option('wp_estate_submission_page_fields', '');

        $table_fields= array('property_price',
            'property_price_per_week',
            'property_price_per_month',
            'min_days_booking',
            'extra_price_per_guest',
            'price_per_weekeend',
            'checkin_change_over',
            'checkin_checkout_change_over'
            );

        $fiels_no=0;

        foreach ($table_fields as $item) {
            if (in_array($item, $permited_fields)) {
                $fiels_no++;
            }
        }
        $size='';

        if ($fiels_no!=0) {
            $length=floatval(84/$fiels_no);
            if ($is_dash==1) {
                $length=floatval(68/$fiels_no);
            }
            $size= 'style="width:'.$length.'%;"';
        }


        if (is_array($mega)) {
            foreach ($mega as $key=>$Item) {
                $now_unix=time();
                if (($key+(24*60*60)) < $now_unix) {
                    unset($mega[$key]);
                }
            }
        }


        if (empty($mega) && empty($price_array)) {
            return;
        }

        $to_print_trigger   =   0;
        if (is_array($mega)) {
            // sort arry by key
            ksort($mega);


            $flag=0;
            $flag_price         ='';
            $flag_min_days      ='';
            $flag_guest         ='';
            $flag_price_week    ='';
            $flag_change_over   ='';
            $flag_checkout_over ='';

            $to_print           =   '';
            $to_print_trigger   =   0;

            $to_print.= '<div class="custom_day_wrapper';
            if ($is_dash==1) {
                $to_print.= ' custom_day_wrapper_dash ';
            }
            $to_print.= '">';

            $to_print.= '
            <div class="custom_day custom_day_header">
                <div class="custom_day_from_to">'.esc_html__('Period', 'wprentals').'</div>';



            if ($price_per_guest_from_one!=1) {
                if (in_array('property_price', $permited_fields)) {
                    $to_print.='<div class="custom_price_per_day custom_price_per_day_regular_night" '.$size.'>'.wpestate_show_labels('price_label', $rental_type, $booking_type).'</div>';
                }

                if (in_array('property_price_per_week', $permited_fields)) {
                    $to_print.='<div class="custom_price_per_day custom_price_per_day_regular_week" '.$size.'>'.wpestate_show_labels('price_week_label', $rental_type, $booking_type).'</div>';
                }

                if (in_array('property_price_per_month', $permited_fields)) {
                    $to_print.='<div class="custom_price_per_day custom_price_per_day_regular_month" '.$size.'>'.wpestate_show_labels('price_month_label', $rental_type, $booking_type).'</div>';
                }

                if (in_array('min_days_booking', $permited_fields)) {
                    $to_print.='<div class="custom_day_min_days" '.$size.'>'.wpestate_show_labels('min_unit', $rental_type, $booking_type).'</div>';
                }

                if (in_array('extra_price_per_guest', $permited_fields)) {
                    $to_print.='<div class="custom_day_name_price_per_guest" '.$size.'>'.esc_html__('Extra price per guest', 'wprentals').'</div>';
                }
                if (in_array('price_per_weekeend', $permited_fields)) {
                    $to_print.='<div class="custom_day_name_price_per_weekedn" '.$size.'>'.esc_html__('Price in weekends', 'wprentals').'</div>';
                }
            } else {
                $to_print.= '<div class="custom_day_name_price_per_guest" '.$size.'>'.esc_html__('Price per guest', 'wprentals').'</div>';
            }

            if (in_array('checkin_change_over', $permited_fields)) {
                $to_print.='<div class="custom_day_name_change_over" '.$size.'>'.esc_html__('Booking starts only on', 'wprentals').'</div>';
            }

            if (in_array('checkin_checkout_change_over', $permited_fields)) {
                $to_print.='<div class="custom_day_name_checkout_change_over" '.$size.'>'.esc_html__('Booking starts/ends only on', 'wprentals').'</div>';
            }


            if ($is_dash==1) {
                $to_print.= '<div class="delete delete_custom_period"></div>';
            }

            $to_print.='</div>';


            foreach ($mega as $day=>$data_day) {
                $checker            =   0;
                $from_date          =   new DateTime("@".$day);
                $to_date            =   new DateTime("@".$day);
                $tomorrrow_date     =   new DateTime("@".$day);

                $tomorrrow_date->modify('tomorrow');
                $tomorrrow_date     =   $tomorrrow_date->getTimestamp();

                //we set the flags
                //////////////////////////////////////////////////////////////////////////////////////////////
                if ($flag==0) {
                    $flag=1;
                    if (isset($price_array[$day])) {
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days                  =   $data_day['period_min_days_booking'];
                    $flag_guest                     =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week                =   $data_day['period_price_per_weekeend'];
                    $flag_change_over               =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over             =   $data_day['period_checkin_checkout_change_over'];

                    if (isset($data_day['period_price_per_month'])) {
                        $flag_period_price_per_month    =   $data_day['period_price_per_month'];
                    }

                    if (isset($data_day['period_price_per_week'])) {
                        $flag_period_price_per_week     =   $data_day['period_price_per_week'];
                    }

                    $from_date_unix     =   $from_date->getTimestamp();
                    $to_print.=' <div class="custom_day">';
                    $to_print.=' <div class="custom_day_from_to"> '.esc_html__('From', 'wprentals').' '. wpestate_convert_dateformat_reverse($from_date->format('Y-m-d'));
                    $to_print_trigger=1;
                }




                //we check period chane
                //////////////////////////////////////////////////////////////////////////////////////////////
                if (!array_key_exists($tomorrrow_date, $mega)) { // non consecutive days
                    $checker = 1;
                } else {
                    if (isset($price_array[$tomorrrow_date]) && $flag_price!=$price_array[$tomorrrow_date]) {
                        // IF PRICE DIFFRES FROM DAY TO DAY
                        $checker = 1;
                    }
                    if ($mega[$tomorrrow_date]['period_min_days_booking']                   !=  $flag_min_days ||
                        $mega[$tomorrrow_date]['period_extra_price_per_guest']              !=  $flag_guest ||
                        $mega[$tomorrrow_date]['period_price_per_weekeend']                 !=  $flag_price_week ||
                        (isset($mega[$tomorrrow_date]['period_price_per_month']) && $mega[$tomorrrow_date]['period_price_per_month']                    !=  $flag_period_price_per_month) ||
                        (isset($mega[$tomorrrow_date]['period_price_per_week']) && $mega[$tomorrrow_date]['period_price_per_week']                     !=  $flag_period_price_per_week) ||
                        $mega[$tomorrrow_date]['period_checkin_change_over']                !=  $flag_change_over ||
                        $mega[$tomorrrow_date]['period_checkin_checkout_change_over']       !=  $flag_checkout_over) {
                        // IF SOME DATA DIFFRES FROM DAY TO DAY

                        $checker = 1;
                    }
                }

                if ($checker == 0) {
                    // we have consecutive days, data stays the sa,e- do not print
                } else {
                    // no consecutive days - we CONSIDER print


                    if ($flag==1) {
                        $to_date_unix     =   $from_date->getTimestamp();
                        $to_print.= ' '.esc_html__('To', 'wprentals').' '. wpestate_convert_dateformat_reverse($from_date->format('Y-m-d')).'</div>';

                        if ($price_per_guest_from_one!=1) {
                            if (in_array('property_price', $permited_fields)) {
                                $to_print.='
                                    <div class="custom_price_per_day" '.$size.'>';
                                if (isset($price_array[$day])) {
                                    $to_print.=   wpestate_show_price_booking($price_array[$day], $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.='</div>';
                            }

                            if (in_array('property_price_per_week', $permited_fields)) {
                                $to_print.='
                                    <div class="custom_day_name_price_per_week custom_price_per_day" '.$size.'>';
                                if (isset($flag_period_price_per_week) && $flag_period_price_per_week!=0) {
                                    $to_print.=   wpestate_show_price_booking($flag_period_price_per_week, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }



                            if (in_array('property_price_per_month', $permited_fields)) {
                                $to_print.='<div class="custom_day_name_price_per_month custom_price_per_day" '.$size.'>';
                                if (isset($flag_period_price_per_month) && $flag_period_price_per_month!=0) {
                                    $to_print.=   wpestate_show_price_booking($flag_period_price_per_month, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }




                            if (in_array('min_days_booking', $permited_fields)) {
                                $to_print.='
                                    <div class="custom_day_min_days" '.$size.'>';
                                if ($flag_min_days!=0) {
                                    $to_print.= esc_html($flag_min_days);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }


                            if (in_array('extra_price_per_guest', $permited_fields)) {
                                $to_print.='<div class="custom_day_name_price_per_guest" '.$size.'>';
                                if ($flag_guest!=0) {
                                    $to_print.= wpestate_show_price_booking($flag_guest, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }

                            if (in_array('price_per_weekeend', $permited_fields)) {
                                $to_print.='<div class="custom_day_name_price_per_weekedn" '.$size.'>';
                                if ($flag_price_week!=0) {
                                    $to_print.=   wpestate_show_price_booking($flag_price_week, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }
                        } else {
                            $to_print.= '<div class="custom_day_name_price_per_guest">'.wpestate_show_price_booking($flag_guest, $wpestate_currency, $wpestate_where_currency, 1).'</div>';
                        }




                        if (in_array('checkin_change_over', $permited_fields)) {
                            $to_print.='
                                <div class="custom_day_name_change_over" '.$size.'>';
                            if (intval($flag_change_over) !=0) {
                                $to_print.= esc_html($week_days[ $flag_change_over ]);
                            } else {
                                $to_print.= esc_html__('All', 'wprentals');
                            }

                            $to_print.= '</div>';
                        }




                        if (in_array('checkin_checkout_change_over', $permited_fields)) {
                            $to_print.='<div class="custom_day_name_checkout_change_over" '.$size.'>';
                            if (intval($flag_checkout_over) !=0) {
                                $to_print.= esc_html($week_days[ $flag_checkout_over ]);
                            } else {
                                $to_print.=esc_html__('All', 'wprentals');
                            }

                            $to_print.= '</div>';
                        }


                        if ($is_dash==1) {
                            $to_print.= '<div class="delete delete_custom_period" data-editid="'.intval($edit_id).'"   data-fromdate="'.esc_attr($from_date_unix).'" data-todate="'.esc_attr($to_date_unix).'"><a href="#"> '.esc_html__('delete period', 'wprentals').'</a></div>';
                        }

                        $to_print.= '</div>';
                    }
                    $flag=0;
                    if (isset($price_array[$day])) {
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days      =   $data_day['period_min_days_booking'];
                    $flag_guest         =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week    =   $data_day['period_price_per_weekeend'];
                    $flag_change_over   =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over =   $data_day['period_checkin_change_over'];


                    $ajax_nonce = wp_create_nonce("wprentals_delete_custom_period_nonce");
                    $to_print.='<input type="hidden" id="wprentals_delete_custom_period_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                }
            }



            
            $to_print.= '</div>';
        }
        if ($to_print_trigger==1) {
            print trim($to_print);
        }
    }
endif;

if (!function_exists('wpestate_show_custom_details_mobile')):
    function wpestate_show_custom_details_mobile($edit_id, $is_dash=0)
    {
        $week_days=array(
            '0'=>esc_html__('All', 'wprentals'),
            '1'=>esc_html__('Monday', 'wprentals'),
            '2'=>esc_html__('Tuesday', 'wprentals'),
            '3'=>esc_html__('Wednesday', 'wprentals'),
            '4'=>esc_html__('Thursday', 'wprentals'),
            '5'=>esc_html__('Friday', 'wprentals'),
            '6'=>esc_html__('Saturday', 'wprentals'),
            '7'=>esc_html__('Sunday', 'wprentals')

            );
        $price_per_guest_from_one       =   floatval(get_post_meta($edit_id, 'price_per_guest_from_one', true));

        $wpestate_currency              = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency        = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));

        $mega           =   wpml_mega_details_adjust($edit_id);
        $price_array    =   wpml_custom_price_adjust($edit_id);
        $rental_type            =   esc_html(wprentals_get_option('wp_estate_item_rental_type', ''));
        $booking_type           =   wprentals_return_booking_type($edit_id);
        $permited_fields        =    wprentals_get_option('wp_estate_submission_page_fields', '');
        if (is_array($mega)) {
            foreach ($mega as $key=>$Item) {
                $now_unix=time();
                if (($key+(24*60*60)) < $now_unix) {
                    unset($mega[$key]);
                }
            }
        }


        if (empty($mega) && empty($price_array)) {
            return;
        }


        if (is_array($mega)) {
            // sort arry by key
            ksort($mega);


            $flag=0;
            $flag_price         ='';
            $flag_min_days      ='';
            $flag_guest         ='';
            $flag_price_week    ='';
            $flag_change_over   ='';
            $flag_checkout_over ='';

            print '<div class="custom_day_wrapper_mobile">';

            foreach ($mega as $day=>$data_day) {
                $checker            =   0;
                $from_date          =   new DateTime("@".$day);
                $to_date            =   new DateTime("@".$day);
                $tomorrrow_date     =   new DateTime("@".$day);

                $tomorrrow_date->modify('tomorrow');
                $tomorrrow_date     =   $tomorrrow_date->getTimestamp();

                //we set the flags
                //////////////////////////////////////////////////////////////////////////////////////////////
                if ($flag==0) {
                    $flag=1;
                    if (isset($price_array[$day])) {
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days                  =   $data_day['period_min_days_booking'];
                    $flag_guest                     =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week                =   $data_day['period_price_per_weekeend'];
                    $flag_change_over               =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over             =   $data_day['period_checkin_checkout_change_over'];

                    if (isset($data_day['period_price_per_month'])) {
                        $flag_period_price_per_month    =   $data_day['period_price_per_month'];
                    }

                    if (isset($data_day['period_price_per_week'])) {
                        $flag_period_price_per_week     =   $data_day['period_price_per_week'];
                    }

                    $from_date_unix     =   $from_date->getTimestamp();
                    print' <div class="custom_day"> ';
                    print' <div class="custom_day_from_to"><div class="custom_price_label">'.esc_html__('Period', 'wprentals').'</div> '.esc_html__('From', 'wprentals').' '. wpestate_convert_dateformat_reverse($from_date->format('Y-m-d'));
                }




                //we check period chane
                //////////////////////////////////////////////////////////////////////////////////////////////
                if (!array_key_exists($tomorrrow_date, $mega)) { // non consecutive days
                    $checker = 1;
                } else {
                    if (isset($price_array[$tomorrrow_date]) && $flag_price!=$price_array[$tomorrrow_date]) {
                        // IF PRICE DIFFRES FROM DAY TO DAY
                        $checker = 1;
                    }
                    if ($mega[$tomorrrow_date]['period_min_days_booking']                   !=  $flag_min_days ||
                        $mega[$tomorrrow_date]['period_extra_price_per_guest']              !=  $flag_guest ||
                        $mega[$tomorrrow_date]['period_price_per_weekeend']                 !=  $flag_price_week ||
                        (isset($mega[$tomorrrow_date]['period_price_per_month']) && $mega[$tomorrrow_date]['period_price_per_month']                    !=  $flag_period_price_per_month) ||
                        (isset($mega[$tomorrrow_date]['period_price_per_week']) && $mega[$tomorrrow_date]['period_price_per_week']                     !=  $flag_period_price_per_week) ||
                        $mega[$tomorrrow_date]['period_checkin_change_over']                !=  $flag_change_over ||
                        $mega[$tomorrrow_date]['period_checkin_checkout_change_over']       !=  $flag_checkout_over) {
                        // IF SOME DATA DIFFRES FROM DAY TO DAY

                        $checker = 1;
                    }
                }

                if ($checker == 0) {
                    // we have consecutive days, data stays the sa,e- do not print
                } else {
                    // no consecutive days - we CONSIDER print


                    if ($flag==1) {
                        $to_date_unix     =   $from_date->getTimestamp();
                        print ' '.esc_html__('To', 'wprentals').' '. wpestate_convert_dateformat_reverse($from_date->format('Y-m-d')).'</div>';

                        if ($price_per_guest_from_one!=1) {
                            if (in_array('property_price', $permited_fields)) {
                                print'
                                    <div class="custom_price_per_day">';
                                print '<div class="custom_price_label">'.wpestate_show_labels('price_label', $rental_type).'</div>';
                                if (isset($price_array[$day])) {
                                    echo   wpestate_show_price_booking($price_array[$day], $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }
                                print'</div>';
                            }

                            if (in_array('property_price_per_week', $permited_fields)) {
                                print'
                                    <div class="custom_day_name_price_per_week custom_price_per_day">';
                                print '<div class="custom_price_label">'.wpestate_show_labels('price_week_label', $rental_type).'</div>';
                                if (isset($flag_period_price_per_week) && $flag_period_price_per_week!=0) {
                                    echo   wpestate_show_price_booking($flag_period_price_per_week, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }

                                print '</div>';
                            }

                            if (in_array('property_price_per_month', $permited_fields)) {
                                print'<div class="custom_day_name_price_per_month custom_price_per_day">';
                                print '<div class="custom_price_label">'.wpestate_show_labels('price_month_label', $rental_type).'</div>';
                                if (isset($flag_period_price_per_month) && $flag_period_price_per_month!=0) {
                                    echo   wpestate_show_price_booking($flag_period_price_per_month, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }
                                print '</div>';
                            }


                            if (in_array('min_days_booking', $permited_fields)) {
                                print'
                                    <div class="custom_day_min_days">';
                                print '<div class="custom_price_label">'.wpestate_show_labels('min_unit', $rental_type, $booking_type).'</div>';
                                if ($flag_min_days!=0) {
                                    print esC_html($flag_min_days);
                                } else {
                                    echo '-';
                                }
                                print '</div>';
                            }

                            if (in_array('extra_price_per_guest', $permited_fields)) {
                                print'<div class="custom_day_name_price_per_guest">';
                                print '<div class="custom_price_label">'.esc_html__('Extra price per guest', 'wprentals').'</div>';
                                if ($flag_guest!=0) {
                                    echo wpestate_show_price_booking($flag_guest, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }
                                print '</div>';
                            }

                            if (in_array('price_per_weekeend', $permited_fields)) {
                                print '<div class="custom_day_name_price_per_weekedn">';
                                print '<div class="custom_price_label">'.esc_html__('Price in weekends', 'wprentals').'</div>';
                                if ($flag_price_week!=0) {
                                    echo   wpestate_show_price_booking($flag_price_week, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }
                                print '</div>';
                            }
                        } else {
                            print '<div class="custom_day_name_price_per_guest">';
                            print '<div class="custom_price_label">'.wpestate_show_labels('price_label', $rental_type, $booking_type).'</div>';
                            print wpestate_show_price_booking($flag_guest, $wpestate_currency, $wpestate_where_currency, 1).'</div>';
                        }

                        if (in_array('checkin_change_over', $permited_fields)) {
                            print'
                                <div class="custom_day_name_change_over">';
                            print '<div class="custom_price_label">'.esc_html__('Booking starts only on', 'wprentals').'</div>';
                            if (intval($flag_change_over) !=0) {
                                print esc_html($week_days[ $flag_change_over ]);
                            } else {
                                esc_html_e('All', 'wprentals');
                            }
                            print '</div>';
                        }

                        if (in_array('checkin_checkout_change_over', $permited_fields)) {
                            print'<div class="custom_day_name_checkout_change_over">';
                            print '<div class="custom_price_label">'.esc_html__('Booking starts/ends only on', 'wprentals').'</div>';
                            if (intval($flag_checkout_over) !=0) {
                                print esc_html($week_days[ $flag_checkout_over ]);
                            } else {
                                esc_html_e('All', 'wprentals');
                            }

                            print '</div>';
                        }

                        if ($is_dash==1) {
                            print '<div class="delete delete_custom_period" data-editid="'.esc_attr($edit_id).'"   data-fromdate="'.esc_attr($from_date_unix).'" data-todate="'.esc_attr($to_date_unix).'"><a href="#"> '.esc_html__('delete period', 'wprentals').'</a></div>';
                        }

                        print '</div>';
                    }
                    $flag=0;
                    if (isset($price_array[$day])) {
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days      =   $data_day['period_min_days_booking'];
                    $flag_guest         =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week    =   $data_day['period_price_per_weekeend'];
                    $flag_change_over   =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over =   $data_day['period_checkin_change_over'];

                    $ajax_nonce = wp_create_nonce("wprentals_delete_custom_period_nonce");
                    print'<input type="hidden" id="wprentals_delete_custom_period_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                }
            }
            print '</div>';
        }
    }
endif;










if (!function_exists('wpestate_sleeping_situation')):
    function wpestate_sleeping_situation($post_id)
    {
        $return_string='';
        $return_string_second='';
        $beds_options=get_post_meta($post_id, 'property_bedrooms_details', true);
        if ($beds_options=='') {
            $beds_options=array();
        }


        $bed_types      =   esc_html(wprentals_get_option('wp_estate_bed_list', ''));
        $bed_types_array=   explode(',', $bed_types);
        $no_bedroms     =   intval(get_post_meta($post_id, 'property_bedrooms', true));
        $step           =   1;

        $return_string.='<div class="wpestate_front_bedrooms_wrapper">';
        while ($step<=$no_bedroms) {
            $return_string.='<div class="wpestate_front_bedrooms">';
            $return_string_second='';
            $images='';
            $return_string_second.='<strong>'.esc_html__('Bedroom', 'wprentals').' '.($step).'</strong>';


            foreach ($bed_types_array as $key_bed_types=>$label) {
                if (isset($beds_options[sanitize_key(wpestate_convert_cyrilic($label))][$step-1]) &&  $beds_options[sanitize_key(wpestate_convert_cyrilic($label))][$step-1] >0) {
                    $return_string_second.='<div class="">'. $beds_options[sanitize_key(wpestate_convert_cyrilic($label))][$step-1].' '.$label.'</div>';
                }
            }

            $return_string.=$images.$return_string_second.'</div>';
            $step++;
        }
        $return_string.='</div>';

        return $return_string;
    }
endif;







if (!function_exists('wpestate_listing_terms')):
    function wpestate_listing_terms($post_id)
    {
        $cancellation_policy    =   esc_html(get_post_meta($post_id, 'cancellation_policy', true));
        $other_rules            =   esc_html(get_post_meta($post_id, 'other_rules', true));
        $return_string          =   '';

        $items=array(
            'smoking_allowed'   =>  esc_html__('Smoking Allowed', 'wprentals'),
            'pets_allowed'      =>  esc_html__('Pets Allowed', 'wprentals'),
            'party_allowed'     =>  esc_html__('Party Allowed', 'wprentals'),
            'children_allowed'  =>  esc_html__('Children Allowed', 'wprentals'),

        );



        foreach ($items as $key=>$name) {
            $value =    esc_html(get_post_meta($post_id, $key, true));
            if ($value!='') {
                $dismiss_class="";
                $icon = ' <i class="fas fa-check checkon"></i>';
                if ($value=='no') {
                    $dismiss_class=" not_present  ";
                    $icon = ' <i class="fas fa-times"></i> ';
                }
                $return_string.='<div class="listing_detail  col-md-6 '.$key.' '.$dismiss_class.'">'.$icon. $name.'</div>';
            }
        }


        if (trim($cancellation_policy)!='') {
            $return_string.='<div class="listing_detail  col-md-12 cancelation_policy"><label>'.esc_html__('Cancellation Policy', 'wprentals').'</label>'. $cancellation_policy.'</div>';
        }

        if (trim($other_rules)!='') {
            $return_string.='<div class="listing_detail  col-md-12 other_rules"><label>'.esc_html__('Other Rules', 'wprentals').'</label>'. $other_rules.'</div>';
        }
        return $return_string;
    }
endif;







if (!function_exists('estate_listing_address')):
    function estate_listing_address($post_id)
    {
        $property_address   = esc_html(get_post_meta($post_id, 'property_address', true));
        $property_city      = get_the_term_list($post_id, 'property_city', '', ', ', '');
        $property_area      = get_the_term_list($post_id, 'property_area', '', ', ', '');
        $property_county    = esc_html(get_post_meta($post_id, 'property_county', true));
        $property_state     = esc_html(get_post_meta($post_id, 'property_state', true));
        $property_zip       = esc_html(get_post_meta($post_id, 'property_zip', true));
        $property_country   = esc_html(get_post_meta($post_id, 'property_country', true));
        $property_country_tr   = wpestate_return_country_list_translated(strtolower($property_country)) ;

        if ($property_country_tr!='') {
            $property_country=$property_country_tr;
        }

        $return_string='';

        if ($property_address != '') {
            $return_string.='<div class="listing_detail list_detail_prop_address col-md-6"><span class="item_head">'.esc_html__('Address', 'wprentals').':</span> ';
            if (wpestate_check_show_address_user_rent_property()) {
                $return_string.= $property_address;
            } else {
                $return_string.=esc_html__('Exact location information is provided after a booking is confirmed.', 'wprentals');
            }
            $return_string.='</div>';
        }
        if ($property_city != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('City', 'wprentals').':</span> ' .$property_city. '</div>';
        }
        if ($property_area != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_area col-md-6"><span class="item_head">'.esc_html__('Area', 'wprentals').':</span> ' .$property_area. '</div>';
        }
        if ($property_county != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_county col-md-6"><span class="item_head">'.esc_html__('County', 'wprentals').':</span> ' . $property_county . '</div>';
        }
        if ($property_state != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_state col-md-6"><span class="item_head">'.esc_html__('State', 'wprentals').':</span> ' . $property_state . '</div>';
        }
        if ($property_zip != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_zip col-md-6"><span class="item_head">'.esc_html__('Zip', 'wprentals').':</span> ' . $property_zip . '</div>';
        }
        if ($property_country != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_contry col-md-6"><span class="item_head">'.esc_html__('Country', 'wprentals').':</span> ' . $property_country . '</div>';
        }

        return  $return_string;
    }
endif; // end   estate_listing_address



if (!function_exists('estate_listing_address_print_topage')):
    function estate_listing_address_print_topage($post_id)
    {
        $property_address   = esc_html(get_post_meta($post_id, 'property_address', true));
        $property_city      = strip_tags(get_the_term_list($post_id, 'property_city', '', ', ', ''));
        $property_area      = strip_tags(get_the_term_list($post_id, 'property_area', '', ', ', ''));
        $property_county    = esc_html(get_post_meta($post_id, 'property_county', true));
        $property_state     = esc_html(get_post_meta($post_id, 'property_state', true));
        $property_zip       = esc_html(get_post_meta($post_id, 'property_zip', true));
        $property_country   = esc_html(get_post_meta($post_id, 'property_country', true));

        $return_string='';

        if ($property_address != '') {
            $return_string.='<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('Address', 'wprentals').':</span> ' . $property_address . '</div>';
        }
        if ($property_city != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('City', 'wprentals').':</span> ' .$property_city. '</div>';
        }
        if ($property_area != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('Area', 'wprentals').':</span> ' .$property_area. '</div>';
        }
        if ($property_county != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('County', 'wprentals').':</span> ' . $property_county . '</div>';
        }
        if ($property_state != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('State', 'wprentals').':</span> ' . $property_state . '</div>';
        }
        if ($property_zip != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('Zip', 'wprentals').':</span> ' . $property_zip . '</div>';
        }
        if ($property_country != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('Country', 'wprentals').':</span> ' . $property_country . '</div>';
        }

        return  $return_string;
    }
endif; // end   estate_listing_address



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////




if (!function_exists('estate_listing_details')):
    function estate_listing_details($post_id)
    {
        $wpestate_currency          =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency    =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $measure_sys                =   esc_html(wprentals_get_option('wp_estate_measure_sys', ''));
        $property_size              =   get_post_meta($post_id, 'property_size', true); 
        $property_lot_size          = get_post_meta($post_id, 'property_lot_size', true);
        $property_rooms             = get_post_meta($post_id, 'property_rooms', true);
        $property_bedrooms          = get_post_meta($post_id, 'property_bedrooms', true);
        $property_bathrooms         = get_post_meta($post_id, 'property_bathrooms', true);
        $property_status            = wpestate_return_property_status($post_id, 'pin');

        $return_string='';

        $property_status = apply_filters('wpml_translate_single_string', $property_status, 'wprentals', 'property_status_'.$property_status);
        if ($property_status != '' && $property_status != 'normal') {
            if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
                $return_string.= '<div class="listing_detail list_detail_prop_status col-md-6"><span class="item_head">'.esc_html__('Property Status', 'wprentals').':</span> ' .' '. $property_status . '</div>';
            } else {
                $return_string.= '<div class="listing_detail list_detail_prop_status col-md-6"><span class="item_head">'.esc_html__('Listing Status', 'wprentals').': </span> ' . $property_status . '</div>';
            }
        }
        if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
            $return_string.= '<div  class="listing_detail list_detail_prop_id col-md-6"><span class="item_head">'.esc_html__('Property ID', 'wprentals').': </span> ' . $post_id . '</div>';
        } else {
            $return_string.= '<div  class="listing_detail list_detail_prop_id col-md-6"><span class="item_head">'.esc_html__('Listing ID', 'wprentals').': </span> ' . $post_id . '</div>';
        }
        if ($property_size != '') {
            $property_size  = number_format($property_size) . ' '.$measure_sys.'<sup>2</sup>';
            if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
                $return_string.= '<div class="listing_detail list_detail_prop_size col-md-6"><span class="item_head">'.esc_html__('Property Size', 'wprentals').':</span> ' . $property_size . '</div>';
            } else {
                $return_string.= '<div class="listing_detail list_detail_prop_size col-md-6"><span class="item_head">'.esc_html__('Listing Size', 'wprentals').':</span> ' . $property_size . '</div>';
            }
        }
        if ($property_lot_size != '') {
            $property_lot_size = number_format($property_lot_size) . ' '.$measure_sys.'<sup>2</sup>';

            if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
                $return_string.= '<div class="listing_detail list_detail_prop_lot_size  col-md-6"><span class="item_head">'.esc_html__('Property Lot Size', 'wprentals').':</span> ' . $property_lot_size . '</div>';
            } else {
                $return_string.= '<div class="listing_detail list_detail_prop_lot_size  col-md-6"><span class="item_head">'.esc_html__('Listing Lot Size', 'wprentals').':</span> ' . $property_lot_size . '</div>';
            }
        }
        if ($property_rooms != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_rooms col-md-6"><span class="item_head">'.esc_html__('Rooms', 'wprentals').':</span> ' . floatval( $property_rooms ) . '</div>';
        }
        if ($property_bedrooms != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_bedrooms col-md-6"><span class="item_head">'.esc_html__('Bedrooms', 'wprentals').':</span> ' .floatval( $property_bedrooms ). '</div>';
        }
        if ($property_bathrooms != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_bathrooms col-md-6"><span class="item_head">'.esc_html__('Bathrooms', 'wprentals').':</span> ' . floatval( $property_bathrooms) . '</div>';
        }


        // Custom Fields


        $i=0;
        $custom_fields = wprentals_get_option('wpestate_custom_fields_list', '');

        if (!empty($custom_fields)) {
            while ($i< count($custom_fields)) {
                $name =   $custom_fields[$i][0];
                $label=   $custom_fields[$i][1];
                $type =   $custom_fields[$i][2];
                //    $slug =   sanitize_key ( str_replace(' ','_',$name) );
                $slug         =   wpestate_limit45(sanitize_title($name));
                $slug         =   sanitize_key($slug);

                $value=esc_html(get_post_meta($post_id, $slug, true));
                if (function_exists('icl_translate')) {
                    $label     =   icl_translate('wprentals', 'wp_estate_property_custom_'.$label, $label) ;
                    $value     =   icl_translate('wprentals', 'wp_estate_property_custom_'.$value, $value) ;
                }

                $label = stripslashes($label);

                if ($label!='' && $value!='') {
                    $return_string.= '<div class="listing_detail list_detail_prop_'.(strtolower(str_replace(' ', '_', $label))).' col-md-6"><span class="item_head">'.ucwords($label).':</span> ';
                    $return_string.= stripslashes($value);
                    $return_string.='</div>';
                }
                $i++;
            }
        }

        //END Custom Fields
        $i=0;
        $custom_details = get_post_meta($post_id, 'property_custom_details', true);

        if (!empty($custom_details)) {
            foreach ($custom_details as $label=>$value) {
                if (function_exists('icl_translate')) {
                    $label     =   icl_translate('wprentals', 'wp_estate_property_custom_'.$label, $label) ;
                    $value     =   icl_translate('wprentals', 'wp_estate_property_custom_'.$value, $value) ;
                }

                $label = stripslashes($label);

                if ($value!='') {
                    $return_string.= '<div class="listing_detail list_detail_prop_'.(strtolower(str_replace(' ', '_', $label))).' col-md-6"><span class="item_head">'.ucwords($label).':</span> ';
                    $return_string.= stripslashes($value);
                    $return_string.='</div>';
                }
                $i++;
            }
        }
        //END Custom Details

        return $return_string;
    }
endif; // end   estate_listing_details



/*
*
* Property avalability calendar
*
*/
if(!function_exists('wpestate_get_calendar_custom_avalability')):
function wpestate_get_calendar_custom_avalability($reservation_array,$mega_details,$price_array,$initial = true, $echo = true) {
    global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
    $daywithpost =array();
    // week_begins = 0 stands for Sunday


    $time_now  = current_time('timestamp');
    $now=date('Y-m-d');
    $date = new DateTime();

    $thismonth = gmdate('m', $time_now);
    $thisyear  = gmdate('Y', $time_now);

    $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
    $last_day = date('t', $unixmonth);

    $month_no   =   1;
    $max_month_no   =   intval   ( wprentals_get_option('wp_estate_month_no_show','') );
        while ($month_no <= $max_month_no){

            wpestate_draw_month_front($month_no,$reservation_array,$mega_details,$price_array, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day);

            $date->modify( 'first day of next month' );
            $thismonth=$date->format( 'm' );
            $thisyear  = $date->format( 'Y' );
            $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
            $month_no++;
        }


}
endif;

/*
*
* Property draw month calendar 
*
*/

if(!function_exists('wpestate_draw_month_front')):
function    wpestate_draw_month_front($month_no,$reservation_array,$mega_details,$price_array, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day){
        global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
        global $start_reservation;
        global $end_reservation;
        global $reservation_class;

        $week_begins = intval(get_option('start_of_week'));


        $initial=true;
        $echo=true;

        $table_style='';
        if( $month_no>2 ){
                $table_style='style="display:none;"';
        }

        $calendar_output = '<div class="booking-calendar-wrapper" data-mno="'.esc_attr($month_no).'" '.trim($table_style).'>
            <div class="month-title"> '. date_i18n("F", mktime(0, 0, 0, $thismonth, 10)).' '.esc_html($thisyear).' </div>
            <table class="wp-calendar booking-calendar">
    <thead>
    <tr>';

    $myweek = array();

    for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
        $myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
    }

    foreach ( $myweek as $wd ) {
        $day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
        $wd = esc_attr($wd);
        $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
    }

    $calendar_output .= '
    </tr>
    </thead>
        <tbody>
    <tr>';


    // See how much we should pad in the beginning
    $pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
    if ( 0 != $pad )
        $calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

    $daysinmonth = intval(date('t', $unixmonth));

        $reserverd_first    =   '';
        $reserved_last      =   '';


    for ( $day = 1; $day <= $daysinmonth; ++$day ) {
                $timestamp = strtotime( $day.'-'.$thismonth.'-'.$thisyear).' | ';
                $timestamp_java = strtotime( $day.'-'.$thismonth.'-'.$thisyear);
        if ( isset($newrow) && $newrow ){
                    $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
                }

        $newrow = false;
                $has_past_class='';
                if($timestamp_java < (time()-24*60*60)  ){
                    $has_past_class="has_past";
                }else{
                    $has_past_class="has_future";
                }
                $is_reserved=0;
                $reservation_class='';

        if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) ){
                    // if is today check for reservation
                    if(array_key_exists ($timestamp_java,$reservation_array) ){
                        $reservation_class  =   ' start_reservation ';
                        $start_reservation  =   0;          
                        $end_reservation    =   1;

                        $calendar_output .= '<td class="calendar-reserved calendar-today '.esc_attr($has_past_class).'  '.esc_attr($reservation_class).' " data-curent-date="'.esc_attr($timestamp_java).'">';
                    }else{
                        $calendar_output .= '<td class="calendar-today '.esc_attr($has_past_class).' " data-curent-date="'.esc_attr($timestamp_java).'">';
                    }

                }
        else if(array_key_exists ($timestamp_java,$reservation_array) ){ // check for reservation

                    $end_reservation=1;
                    if($start_reservation == 1){
                        $reservation_class  =   ' start_reservation';
                        $start_reservation  =   0;
                    }
                    $calendar_output .= '<td class="calendar-reserved '.esc_attr($has_past_class.$reservation_class).'"  data-curent-date="'.esc_attr($timestamp_java).'">';
                }
                else{// is not today and no resrvation

                    $start_reservation=1;
                    if($end_reservation===1){
                        $reservation_class=' end_reservation ';
                        $end_reservation=0;
                    }
                    $calendar_output .= '<td class="calendar-free '.esc_attr($has_past_class.$reservation_class).'" data-curent-date="'.esc_attr($timestamp_java).'">';
                }

        if ( in_array($day, $daywithpost) ) // any posts today?
                    $calendar_output .= '<a href="' . esc_url( get_day_link( $thisyear, $thismonth, $day ) ). '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) . "\">$day</a>";
        else
                    $calendar_output .= $day;
        $calendar_output .= wprentals_front_calendar_price($timestamp_java,$mega_details,$price_array).'</td>';

        if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
                    $newrow = true;
    }

    $pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
    if ( $pad != 0 && $pad != 7 )
        $calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

    $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table></div>";

    if ( $echo ){
            echo apply_filters( 'get_calendar',  $calendar_output );
        }else{
            return apply_filters( 'get_calendar',  $calendar_output );
        }
}
endif;


/*
*
* Front Calendar - display day price
*
*/

function wprentals_front_calendar_price($timestamp_java,$mega_details,$price_array){

    

    $weekday                =   intval(date('N', $timestamp_java));
    $setup_weekend_status   =   intval(wprentals_get_option('wp_estate_setup_weekend',''));
    $display_price ='';

    // print 'wpestate_return_weekeend_price'.$setup_weekend_status.'/'.$weekday.'*';

    if ( intval($setup_weekend_status) === 0 && ( $weekday==6 ||  $weekday==7) ){
    
        $display_price = wpestate_return_weekeend_price( $weekday,$timestamp_java,$mega_details,$price_array);
    
    }else if($setup_weekend_status === 1  && ( $weekday==5 ||  $weekday==6) ){
    
        $display_price = wpestate_return_weekeend_price( $weekday,$timestamp_java,$mega_details,$price_array);
    
    }else if($setup_weekend_status === 2 && ( $weekday==5 ||  $weekday==6 ||  $weekday==7)){
       
        $display_price = wpestate_return_weekeend_price( $weekday,$timestamp_java,$mega_details,$price_array);
    
    }






    $display_price =      wpestate_booking_calendat_get_price($timestamp_java,$display_price,$price_array,$mega_details) ;



    $wpestate_currency = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
    $wpestate_where_currency = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
    $display_price =  wpestate_show_price_booking($display_price, $wpestate_currency, $wpestate_where_currency, 1);

    $return_string  =   '<div class="wprentals_front_calendar_price" '.$timestamp_java.'/'.$setup_weekend_status.'/'.$weekday.'>'.$display_price.'</div>';

    return $return_string;

}

/*
*
* Front Calendar - return weekend price
*
*/


function  wpestate_return_weekeend_price($weekday,$unixtime,$mega_details,$price_array){
    
    $display_price      =   '';
    $price_per_weekeend =   $price_array['price_per_weekeend'];

    if( isset( $mega_details[$unixtime]) ) {
        if (  floatval ($mega_details[$unixtime]['period_price_per_weekeend']) !==0 ){
            $display_price = floatval ( $mega_details[$unixtime]['period_price_per_weekeend'] );
        }
    }else if( floatval ($price_per_weekeend) !== 0 ){
        $display_price = floatval($price_per_weekeend);
    }

    return $display_price;

}
/*
*
* Front Calendar - return  price
*
*/

function wpestate_booking_calendat_get_price($unixtime, $display_price,$price_array,$mega_details) {

        $curency                    =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
        $where_curency              =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
        $price_per_guest_from_one   =   $price_array['price_per_guest_from_one'];
       

        if( !isset( $price_array['custom_price'][$unixtime]) ){ // no custom price
            if ($display_price === '' || intval($display_price)==0 ) { // we DONT have weekend price
                $display_price = $price_array['default_price'];
            }
            if($price_per_guest_from_one==1){
                $display_price = $price_array['extra_price_per_guest'];
            }


        }else{
            if ($display_price === '' || intval($display_price)==0 ) { // we DONT have weekend price
                $display_price = $price_array['custom_price'][$unixtime] ;
            }
            if($price_per_guest_from_one==1){
                $display_price = $mega_details[$unixtime]['period_extra_price_per_guest'];
            }
        }

       
       

       
  /*     


    if (parseFloat(price_per_guest_from_one, 10) === 1) {
        if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) {
            var to_show = parseFloat(extra_price_per_guest, 10) * my_custom_curr_coef;
            if (my_custom_curr_cur_post === 'before') {
                display_price = control_vars.from + " " + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label)) + ' ' + to_show.toFixed(0);
            } else {
                display_price = control_vars.from + " " + to_show.toFixed(0) + wpestate_replace_plus(decodeURIComponent(my_custom_curr_label));
            }
        } else {
            if (control_vars.where_curency === 'before') {
                display_price = control_vars.from + " " + wpestate_replace_plus(decodeURIComponent(control_vars.curency)) + parseFloat(extra_price_per_guest, 10);
            } else {
                display_price = control_vars.from + " " + parseFloat(extra_price_per_guest, 10) + wpestate_replace_plus(decodeURIComponent(control_vars.curency));
            }

        }

    }
*/




    return $display_price;
}
