<?php

/*
* sliding-box shortcode
*
*
*/
if( !function_exists('wpestate_sliding_box_shortcode') ):
function wpestate_sliding_box_shortcode($settings){

  
    ob_start();
    $items =count($settings['form_fields'])+1;
    
    print '<div class="wpestate_sliding_box_wrapper">';
    foreach ($settings['form_fields'] as $key=>$box):
    
        $image_url='';
        if(isset($box['image']['url'])){
            $image_url = $box['image']['url'];
        }
          
        $title='';
        if(isset($box['title'])){
            $title = $box['title'];
        }
        
        $read_me='';
        if(isset($box['read_me'])){
            $read_me = $box['read_me'];
        }
        
        $read_me_link='';
        if(isset($box['read_me_link'])){
            $read_me_link = $box['read_me_link'];
        }
        
        $content='';
        if(isset($box['content'])){
            $content = $box['content'];
        }
        
        
        $class_box='';
        if( isset( $box['show_open'] ) && $box['show_open'] =='yes' ){
            $class_box = 'active-element';
          
        }
        $class_box.=' slider_box_size_'.$items;
        
        
        /*  <img src ="<?php print esc_attr($image_url);?>" alt="<?php print esc_attr($title);?>">
         * 
         */
    ?>

        <div class="wpestate_sliding_box <?php echo esc_attr($class_box);?>"  style="" >
            <div class="sliding-image" style="background-image:url(<?php echo esc_attr($image_url);?>)">
              
            </div>

            <div class="sliding-content-wrapper" style="">
                <h4><?php echo esc_html($title);?></h4>
                <p><?php echo esc_html($content); ?></p>

                <div class="sliding-content-action">
                    <a href="<?php echo esc_html($read_me_link); ?>" ><?php echo esc_html($read_me); ?></a>
                </div>
            </div>
        </div>
   
    <?php
    endforeach;
    
    print '</div>';
    $retur= ob_get_contents();
    ob_end_clean();
    
    print $retur;
}
endif;




function  wprentals_elementor_search_helper($settings, $thisComponenet,$postID){ 
    require_once get_theme_file_path('/classes/rentalsSearch.php');
    $search_object              =   new WpRentalsSearch();
    $search_object->search_type =   'elementor';
    $search_object->postid      =   $postID;
    $render_output              =   trim($search_object->wpstate_display_search_form_elementor($settings, $thisComponenet,$postID));
    ob_start();
        include(locate_template('libs/internal_autocomplete_wpestate.php'));
        $render_output  =   $render_output.ob_get_contents();
    ob_end_clean();
  
    return $render_output;
}


/*
 * Show trip details
 *
 *
 *
 */

if (!function_exists('wprentals_card_link_autocomplete')):

function wpestate_generate_trip_details($property_id,$invoice_id,$bookid,$mode=''){
    $current_user               =   wp_get_current_user();
    $property_category          =   get_the_term_list($property_id, 'property_category', '', ', ', '');
    $property_action_category   =   get_the_term_list($property_id, 'property_action_category', '', ', ', '');
    $rental_type                =   esc_html(wprentals_get_option('wp_estate_item_rental_type', ''));
    $booking_type               =   wprentals_return_booking_type($property_id);
    $booking_type               =   wprentals_return_booking_type($property_id);
    $extra_options_array        =   '';
    $manual_expenses            ='  ';
    
    $mode_wrapper_trip_details_container='';
    $mode_wrapper_h2='';
    $mode_wrapper_h5='';
    $mode_wrapper_h6='';
    $mode_trip_owner_name="";
    if($mode=='email'){
        $mode_wrapper_trip_details_container= 'float: left;width: 500px;background-color: #fff;padding: 20px;margin-right: 20px;border: 2px solid #e8ebf0;position: relative;';
        $mode_wrapper_h2='';
        $mode_wrapper_h5='margin: 15px 0px;color: #222;font-size: 16px;line-height: 1em;color: #383f5b;font-weight: 600;';
        $mode_wrapper_h6='margin: 15px 0px;color: #222;font-size: 15px;line-height: 1em;color: #383f5b;font-weight: 600;';
        $mode_trip_owner_name='font-size: 17px;margin-top: 15px;font-weight: 600;';
    }
    
    
    print '<div class="trip_details_wrapper"><div class="trip_details_container" style="'.esc_attr($mode_wrapper_trip_details_container).'" >';
        
        if($mode=='show'){
            print '<div id="print_trip" data-booking="'.$bookid.'" data-property="'.$property_id.'" ><i class="fas fa-print" aria-hidden="true"></i></div>';
        }
    
        print '<h2>'.esc_html__('Your Trip Details','wprentals').'</h2>';
        printf (esc_html__('Hi %s','wprentals'),$current_user->user_login);print', ';
        print '</br>';
        print esc_html__('Here\'s your itinerary, including the address and check-in details.','wprentals');

    
  
    
        if (has_post_thumbnail($property_id)){
           $preview            =   wp_get_attachment_image_src(get_post_thumbnail_id($property_id), 'wpestate_blog_unit');
           $thumb_src=$preview[0];
        }else{
           $thumb_src =  get_stylesheet_directory_uri().'/img/defaultimage_prop.jpg';
        }
     
        
        $booking_from_date  =   get_post_meta($bookid, 'booking_from_date', true);
        $booking_to_date    =   get_post_meta($bookid, 'booking_to_date', true);
        $booking_from_date_unix  =   get_post_meta($bookid, 'booking_from_date_unix', true);
        $booking_to_date_unix    =   get_post_meta($bookid, 'booking_to_date_unix', true);
        $from_weekday = date('l', $booking_from_date_unix); 
        $to_weekday = date('l', $booking_to_date_unix); 
        
        
        $no_of_days         =   ( strtotime($booking_to_date)-strtotime($booking_from_date) ) / (60*60*24);
        $booking_status     =   get_post_meta($bookid, 'booking_status', true);
        $booking_status_full=   get_post_meta($bookid, 'booking_status_full', true);
        
        
        $booking_guests     =   get_post_meta($bookid, 'booking_guests', true);
        $booking_array = wpestate_booking_price($booking_guests, $invoice_id, $property_id, $booking_from_date, $booking_to_date, $bookid, $extra_options_array, $manual_expenses);

     

        print '<img  src="'. esc_url($thumb_src).'"  class="img-responsive trip-details-thumb" alt="'. esc_html__('image','wprentals').'" />';
        print '<h5 style="'.esc_attr($mode_wrapper_h5).'" >'.get_the_title($property_id).'</h5>';   
        print '<div class="trip_listed_in">';
            esc_html_e('Listed in','wprentals');echo ' ';
            print trim($property_action_category); 
            if( $property_action_category!='') {
               print' '.esc_html__( 'and','wprentals').' ';
             }
             print trim($property_category);
        print '</div>';     
          
        
        print '<h5 style="'.esc_attr($mode_wrapper_h5).'" >'.esc_html__('Dates','wprentals').'</h5>';

        print wpestate_show_labels('no_of_nights', $rental_type, $booking_type) ;
        print ': '.$booking_array['numberDays'];
        
        print '<br>';
        print '<strong>'.esc_html__('from ','wprentals').'</strong>'.$from_weekday.', '.wpestate_convert_dateformat_reverse($booking_from_date).' <strong>'.esc_html__( 'to','wprentals').'</strong> '.$to_weekday.', '.wpestate_convert_dateformat_reverse($booking_to_date);
       
        
        if($booking_guests>0){
            print '<h5 style="'.esc_attr($mode_wrapper_h5).'" >'.esc_html__('Guests','wprentals').'</h5>';
            printf( _n( '%s Guest', '%s Guests', $booking_guests, 'wprentals' ), number_format_i18n( $booking_guests ) );
            print wpestate_booking_guest_explanations($bookid);
        }
        
        
           
        $property_address   = esc_html(get_post_meta($property_id, 'property_address', true));
        $property_city      = strip_tags( get_the_term_list($property_id, 'property_city'));
        $property_area      = strip_tags( get_the_term_list($property_id, 'property_area'));
        $property_county    = (get_post_meta($property_id, 'property_county', true));
        $property_state     = (get_post_meta($property_id, 'property_state', true));
        $property_zip       = (get_post_meta($property_id, 'property_zip', true));
        $property_country   = (get_post_meta($property_id, 'property_country', true));
        
        
        print '<h5 style="'.esc_attr($mode_wrapper_h5).'" >'.esc_html__('Address','wprentals').'</h5>';
       
        $location_string_array = array();
        
        if( $property_address!='')  $location_string_array[]= wp_strip_all_tags( esc_html($property_address));
        if( $property_area!='')     $location_string_array[]= wp_strip_all_tags ( esc_html($property_area));
        if( $property_city!='')     $location_string_array[]= wp_strip_all_tags ( esc_html($property_city));
        if( $property_zip!='')      $location_string_array[]= wp_strip_all_tags (esc_html($property_zip));
        if( $property_county!='')   $location_string_array[]= wp_strip_all_tags ( esc_html($property_county));
        if( $property_state!='')    $location_string_array[]= wp_strip_all_tags ( esc_html($property_state));
        if( $property_country!='')  $location_string_array[]= wp_strip_all_tags ( esc_html($property_country));
        
        if(!empty($location_string_array)){
            print ucwords (strip_Tags( implode(', ',$location_string_array)));
        }
     
        
        print '<h5 style="'.esc_attr($mode_wrapper_h5).'" >'.esc_html__('Your Host','wprentals').'</h5>';
        
        $owner_details=wprentals_get_owner_on_trip_details($property_id);

 
        
        if ($owner_details['preview_img']!=''){
            print '<img src="'.$owner_details['preview_img'].'" class="trip_details_owner_thumb" alt="owner image">';
        }   
        print '<h5 style="'.esc_attr($mode_wrapper_h5).'" >';
            if(isset($owner_details['name'])) {
                print $owner_details['name'];
            }
        print '</h5>';
            
        if(isset($owner_details['agent_phone']) && $owner_details['agent_phone']!=''){
            print '<strong>'.esc_html__('Phone','wprentals').':</strong> ';
                if(isset($owner_details['agent_phone'])) {
                    print $owner_details['agent_phone'];
                }
            print '<br>';
        }
        if(isset($owner_details['agent_mobile']) && $owner_details['agent_mobile']!=''){
            print '<strong>'.esc_html__('Mobile','wprentals').':</strong> ';
                if(isset($owner_details['agent_mobile'])) {
                    print $owner_details['agent_mobile'];
                }
            print '<br>';
        }
        if(isset($owner_details['agent_email']) &&  $owner_details['agent_email']!=''){
            print '<strong>'.esc_html__('Email','wprentals').':</strong> ';
                if(isset($owner_details['agent_email'])) {
                    print $owner_details['agent_email'];
                }
            print '<br>';
        }
        if(isset($owner_details['agent_skype']) &&  $owner_details['agent_skype']!=''){
            print '<strong>'.esc_html__('Skype','wprentals').':</strong> ';
                if(isset($owner_details['agent_skype'])) {
                    print $owner_details['agent_skype'];
                }
            print '<br>';
        }
         
        $do_we_show         =   trim(wprentals_get_option('wp_estate_show_terms_conditions', ''));
        
        if($do_we_show=='yes'):
            print '<h5 style="'.esc_attr($mode_wrapper_h5).'" >'.esc_html__(' House Rules','wprentals').'</h5>';

            $cancellation_policy   = esc_html(get_post_meta($property_id, 'cancellation_policy', true));
            if(trim($cancellation_policy)!=''){
                print '<h6 style="'.esc_attr($mode_wrapper_h6).'" >'.esc_html__('Cancellation Policy','wprentals').'</h6>';
                print esc_html($cancellation_policy).'</br>';
            }

            $smoking_allowed   = esc_html(get_post_meta($property_id, 'smoking_allowed', true));
            if($smoking_allowed!=''){
                print '<h6 style="'.esc_attr($mode_wrapper_h6).'" >'.esc_html__('Smoking Allowed','wprentals').'</h6>';
                print esc_html($smoking_allowed).'</br>';
            }

            $pets_allowed  = esc_html(get_post_meta($property_id, 'pets_allowed', true));
            if($pets_allowed!=''){
                print '<h6 style="'.esc_attr($mode_wrapper_h6).'" >'.esc_html__('Pets Allowed','wprentals').'</h6>';
                print esc_html($pets_allowed).'</br>';
            }

            $party_allowed  = esc_html(get_post_meta($property_id, 'party_allowed', true));
            if($party_allowed!=''){
                print '<h6 style="'.esc_attr($mode_wrapper_h6).'" >'.esc_html__('Party Allowed','wprentals').'</h6>';
                print esc_html($party_allowed).'</br>';
            }

            $children_allowed   = esc_html(get_post_meta($property_id, 'children_allowed', true));
            if($children_allowed!=''){
                print '<h6 style="'.esc_attr($mode_wrapper_h6).'" >'.esc_html__('Children Allowed','wprentals').'</h6>';
                print esc_html($children_allowed).'</br>';
            }

            $other_rules   = esc_html(get_post_meta($property_id, 'other_rules', true));

            if(trim($other_rules)!=''){
                print '<h6 style="'.esc_attr($mode_wrapper_h6).'" >'.esc_html__('Other Rules','wprentals').'</h6>';
                print esc_html($other_rules).'</br>';
            }        
        endif;
        
        
        
       
        
    
    print '</div></div>';

    
}
endif;







if (!function_exists('wprentals_get_owner_on_trip_details')):
function wprentals_get_owner_on_trip_details($prop_id){
    $owner_id   =   wpsestate_get_author($prop_id);
    $agent_id   =   get_user_meta($owner_id, 'user_agent_id', true);
    $author_email=get_the_author_meta( 'user_email' ,$owner_id );
    $return_agent=array();
    
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
                    $thumb_id           = get_post_thumbnail_id($post->ID);
                    
                    $return_agent['name']               = get_the_title();
                    $preview                             = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                    $return_agent['preview_img']         = $preview[0];
                    $return_agent['agent_skype']         = esc_html( get_the_author_meta( 'skype' , $owner_id ) );
                    $return_agent['agent_phone']         = esc_html( get_the_author_meta( 'phone' , $owner_id ) );
                    $return_agent['agent_mobile']        = esc_html(get_the_author_meta( 'mobile' , $owner_id ) );
                    $return_agent['agent_email']         = esc_html( get_post_meta($post->ID, 'agent_email', true) );
                    


                    if($agent_email==''){
                        $return_agent['agent_email']=$author_email;
                    }
             
               endwhile;
               wp_reset_query();
              
        }else{// end if have posts
            //$agent_id
            $first_name             =   get_the_author_meta( 'first_name' , $owner_id );
            $last_name              =   get_the_author_meta( 'last_name' , $owner_id );
            $return_agent['name']  =   $first_name.' '.$last_name;
            $return_agent['agent_email']             =   get_the_author_meta( 'user_email' , $owner_id );
            $return_agent['preview_img']             =   get_the_author_meta( 'custom_picture' , $owner_id );
       }
    }   // end if !=0
    
    return $return_agent;
    
    
}
endif;










/*
 * Show contact form form
 *
 *
 *
 */


if (!function_exists('wprentals_card_link_autocomplete')):

    function wprentals_card_link_autocomplete($post_id, $link, $wprentals_is_per_hour) {
        global $wpestate_book_from;
        global $wpestate_book_to;
        if (isset($_REQUEST['check_in']) && isset($_REQUEST['check_out'])) {
            $check_out = sanitize_text_field($_REQUEST['check_out']);
            $check_in = sanitize_text_field($_REQUEST['check_in']);

            if ($wprentals_is_per_hour == 1) {
                if ($check_in != '') {
                    $link = add_query_arg('check_in_prop', (trim($check_in)), $link);
                }
                if ($check_out != '') {
                    $link = add_query_arg('check_out_prop', (trim($check_out)), $link);
                }
            }

            if (isset($_REQUEST['guest_no'])) {
                $wpestate_guest_no = intval($_REQUEST['guest_no']);
                $link = add_query_arg('guest_no_prop', $wpestate_guest_no, $link);
            }
            
             if (isset($_REQUEST['adults_fvalue'])) {
                $wpestate_guest_no = intval($_REQUEST['adults_fvalue']);
                $link = add_query_arg('adults_fvalue', $wpestate_guest_no, $link);
            }
            
             if (isset($_REQUEST['childs_fvalue'])) {
                $wpestate_guest_no = intval($_REQUEST['childs_fvalue']);
                $link = add_query_arg('childs_fvalue', $wpestate_guest_no, $link);
            }
            
             if (isset($_REQUEST['infants_fvalue'])) {
                $wpestate_guest_no = intval($_REQUEST['infants_fvalue']);
                $link = add_query_arg('infants_fvalue', $wpestate_guest_no, $link);
            }
            
            
            
            
            
            
            
        } else {
            if ($wpestate_book_from != '' && $wpestate_book_to != '') {
                $wpestate_book_from = sanitize_text_field($wpestate_book_from);
                $wpestate_book_to = sanitize_text_field($wpestate_book_to);
                if ($wprentals_is_per_hour == 2) {
                    $wpestate_book_from = $wpestate_book_from . ' ' . get_post_meta($post_id, 'booking_start_hour', true);
                    $wpestate_book_to = $wpestate_book_to . ' ' . get_post_meta($post_id, 'booking_end_hour', true);
                }

                $link = add_query_arg('check_in_prop', trim($wpestate_book_from), $link);
                $link = add_query_arg('check_out_prop', trim($wpestate_book_to), $link);

                if ($wpestate_guest_no != '') {
                    $link = add_query_arg('guest_no_prop', intval($wpestate_guest_no), $link);
                }
            }
        }

        return $link;
    }

endif;


/*
 * select property cards
 *
 *
 *
 */

if (!function_exists('wprentals_select_unit_cards')):

    function wprentals_select_unit_cards($attributes) {

        if (is_array($attributes) and isset($attributes['unit_type'])) {
            return intval($attributes['unit_type']);
        }

        $wpestate_listing_type = wprentals_get_option('wp_estate_listing_unit_type', '');
        return $wpestate_listing_type;
    }

endif;



/*
 * Show contact form form
 *
 *
 *
 */

if (!function_exists('wpestate_show_contact_form')):

    function wpestate_show_contact_form($post_id) {
        if (is_singular('estate_property')) {
            $agent_id = 0;
        } else {
            $agent_id = $post_id;
            $post_id = intval($post_id);
        }
        $favorite_data = wpestate_generate_favorite_info($post_id);


        print '<h3 id="contact_for_reservation">' . esc_html__('Check Availability', 'wprentals') . '</h3>';
        wpestate_show_contact_form_simple($agent_id, $post_id);

        print '<script type="text/javascript">
                //<![CDATA[
                    jQuery(document).ready(function(){
                        enable_actions_modal_contact();
                    });
                //]]>
                </script>';

        print '<div class="col-md-12 reservation_buttons favorite_in_contact">
                        <div id="add_favorites" class="' . esc_attr($favorite_data['favorite_class']) . '" a2 data-postid="' . esc_attr($post_id) . '">
                            ' . trim($favorite_data['favorite_text']) . '
                        </div>
                </div>';

        echo wpestate_share_unit_desing($post_id);
       // print '</div>';
    }

endif;

/*
 * Show booking form per hour dropdown
 *
 *
 *
 */
if (!function_exists('wprentals_show_booking_form_per_hour_dropdown')):

    function wprentals_show_booking_form_per_hour_dropdown($item_id, $label, $booking_start_hour, $booking_end_hour, $selected_hour = '') {

        $i = 0;
        $list = '';


        $selected_hour = '';
        if (isset($_GET[$item_id])) {
            $selected_hour = esc_html($_GET[$item_id]);
        }


        $booking_start_hour = intval($booking_start_hour);
        $booking_end_hour = intval($booking_end_hour);

        if ($booking_end_hour == 0) {
            $booking_end_hour = 24;
        }

        while ($i <= 23):
            if ($i >= $booking_start_hour && $i <= $booking_end_hour):
                $value = $i . ':00';
                $list .= '<li   role="presentation"  data-value="' . esc_attr($value) . '" ';
                if ($value == $selected_hour) {
                    $list .= ' selected="selected" ';
                }
                $list .= '>' . esc_html($value) . '</li>';
            endif;
            $i++;
        endwhile;

        $return_string = ' <div class=" has_calendar booking_clock_icon ' . esc_attr($item_id) . '">

        <div class="dropdown form-control">
            <div data-toggle="dropdown" id="' . esc_attr($item_id) . '_no_wrapper" class="filter_menu_trigger" data-value="';
        if (isset($_GET[$item_id]) && $_GET[$item_id] != '') {
            $return_string .= esc_html($_GET[$item_id]);
        } else {
            $return_string .= 'all';
        }
        $return_string .= '">
                <div class="text_selection">';

        if (isset($_GET[$item_id]) && $_GET[$item_id] != '') {
            $return_string .= esc_html($_GET[$item_id]);
        } else {
            $return_string .= $label;
        }

        $return_string .= '  </div>
                <span class="caret caret_filter"></span>
            </div>


            <input type="hidden" name="' . esc_attr($item_id) . '_input"  value="">
            <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="' . esc_attr($item_id) . '_no_wrapper" id="' . esc_attr($item_id) . '_wrapper_list">
                ' . trim($list) . '
            </ul>
        </div>

    </div>';

        return $return_string;
    }

endif;







/*
 * Show classic guest dropdown for booking form
 *
 *
 *
 */


if (!function_exists('wpestate_show_booking_form_guest_dropdown')):

    function wpestate_show_booking_form_guest_dropdown($guest_list) {

        $return = '<div class="dropdown form-control">
        <div data-toggle="dropdown" id="booking_guest_no_wrapper" class="filter_menu_trigger" data-value="';

        if (isset($_GET['guest_no_prop']) && $_GET['guest_no_prop'] != '') {
            $return .= esc_html($_GET['guest_no_prop']);
        } else {
            $return .= 'all';
        }

        $return .= '">

            <div class="text_selection">';

        if (isset($_GET['guest_no_prop']) && $_GET['guest_no_prop'] != '') {
            $return .= esc_html($_GET['guest_no_prop']) . ' ' . esc_html__('guests', 'wprentals');
        } else {
            $return .= esc_html__('Guests', 'wprentals');
        }

        $return .= '</div>
            <span class="caret caret_filter"></span>
        </div>


        <input type="hidden" name="booking_guest_no"  value="">
        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="booking_guest_no_wrapper" id="booking_guest_no_wrapper_list">
             ' . trim($guest_list) . '
        </ul>
    </div>';


        return $return;
    }

endif;





/*
 * Show advanced guest dropdown for booking form
 *
 *
 *
 */

if (!function_exists('wpestate_show_advanced_guest_form')):

    function wpestate_show_advanced_guest_form($label, $position = '',$property_id='') {

        $controls_array           =     wpestate_return_advanced_guests_fields();
        $extra_conrol_class       =     '';
        $property_id              =     intval($property_id);
        $overload_guest           =     floatval(get_post_meta($property_id, 'overload_guest', true));
        $max_guest                =     floatval(get_post_meta($property_id, 'guest_no', true));
        $max_extra_guest_no       =     floatval(get_post_meta($property_id, 'max_extra_guest_no', true) );
       
        
        
        
        if($property_id!='0' && get_post_type($property_id) =='estate_property'){
            $extra_conrol_class= ' on_booking_control'.  intval(get_post_meta($property_id,'children_as_guests',true)).' ';
        }
        
        if( isset( $_GET['guest_no_prop'] ) ){
            $guest_no_value=intval($_GET['guest_no_prop']);
            $label= sprintf( _n( '%s Guest', '%s Guests', $guest_no_value, 'wprentals' ), number_format_i18n( $guest_no_value ) );
        }
         $guest_no_value=0;
        if( isset( $_GET['guest_no'] ) ){
            $guest_no_value=intval($_GET['guest_no']);
            $label= sprintf( _n( '%s Guest', '%s Guests', $guest_no_value, 'wprentals' ), number_format_i18n( $guest_no_value ) );
        }
        
        
        
        $return = '<div class="wpestate_guest_no_control_wraper">';
        $return .= '<div class="wpestate_guest_no_control_info form-control">' . $label . '</div>';
        $return .= '<div class="wpestate_guest_no_buttons" data-max-guest="'.esc_attr($max_guest).'" data-max-extra-guest-no="'.esc_attr($max_extra_guest_no).'" data-overload-guest="'.esc_attr($overload_guest).'">';

        $return.='<div class="max_guest_notice"></div>';
        
        foreach ($controls_array as $key => $item):
            $return .= wpestate_display_guest_control_item($extra_conrol_class,$key, $item[0], $item[1]);
        endforeach;

        $return .= '<a class="close_guest_control" href="#">'.esc_html__('Close','wprentals').'</a>';
        $return .= '</div>';
        
       
        if( isset( $_GET['guest_no_prop'] ) ){
            $guest_no_value=intval($_GET['guest_no_prop']);
        }
        
        
        $return .= '<input type="hidden" name="guest_no" class="guest_no_hidden" value="'.$guest_no_value.'">';

        $return .= '</div>';
        $return .= '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                    wpestate_control_guest_no();
                });
                //]]>
                </script>';
        
     

        return $return;
    }

endif;











/**
 *  display guest no custom control item
 *
 *
 * @since    3.1
 * @access   public
 */
if (!function_exists('wpestate_display_guest_control_item')):

    function wpestate_display_guest_control_item($extra_conrol_class,$key, $label, $label_description) {
        $return = '';
        $return .= '<div class="wpestate_guest_no_buttons_item control_' . $key . '">';
        $return .= '<div class="wpestate_guest_no_buttons_labels">
                        <div class="wpestate_guest_no_buttons_title_labels">' . $label . '</div>
                        <div class="wpestate_guest_no_buttons_description_labels">' . $label_description . '</div>
                    </div>';
        $return .= '<div class="wpestate_guest_no_buttons_steppers steper_' . $key . '">
                        <button class="wpestate_guest_no_button_minus '.$extra_conrol_class.' wpestate_guest_no_button_control">
                            <svg width="13" height="2" viewBox="0 0 13 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0V2H13V0H0Z" fill="#5D6475"/>
                            </svg>
                        </button>
                        <div class="wpestate_guest_no_button_value '.$extra_conrol_class.' steper_value_' . $key . '">';
        $item_value=0;
        if( isset($_GET[$key .'_fvalue']) && is_numeric($_GET[$key .'_fvalue']) ){
            $item_value=intval($_GET[$key .'_fvalue']);
        }
        $return.=$item_value.'</div>
                        <button  class="wpestate_guest_no_button_plus '.$extra_conrol_class.' wpestate_guest_no_button_control '.$key.'_control_plus ">                            
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.6875 0V5.6875H0V7.3125H5.6875V13H7.3125V7.3125H13V5.6875H7.3125V0H5.6875Z" fill="#5D6475"/>
                            </svg>
                        </button >
                        <input type="hidden" class="placeholeder_search_val" name="'. $key .'_fvalue"  value="'.$item_value.'">
                    </div>';


        $return .= '</div>';

        return $return;
    }

endif;

















/*
 * 
 * 
 * Show booking form
 *
 *
 *
 */
if (!function_exists('wpestate_show_booking_form')):

    function wpestate_show_booking_form($post_id, $wpestate_options = '', $favorite_class = '', $favorite_text = '', $is_shortcode = '') {


        $rental_type = wprentals_get_option('wp_estate_item_rental_type');
        $guest_list = wpestate_get_guest_dropdown('noany');
        $container_class = " col-md-4 ";

        if (isset($wpestate_options['sidebar_class'])) {
            if ($wpestate_options['sidebar_class'] == '' || $wpestate_options['sidebar_class'] == 'none') {
                $container_class = ' col-md-4 ';
            } else {
                $container_class = esc_attr($wpestate_options['sidebar_class']);
            }
        }



        ob_start();
        ?>

        <div class="booking_form_request is_shortcode<?php echo intval($is_shortcode); ?> <?php echo esc_attr($container_class); ?>" id="booking_form_request">

        <?php
        if (wprentals_get_option('wp_estate_replace_booking_form', '') == 'yes') {
            print '<div id="booking_form_mobile_close">&times;</div>';
            wpestate_show_contact_form($post_id);
            
        }else{
        ?>


            <div id="booking_form_request_mess"></div>
            <div id="booking_form_mobile_close">&times;</div>

            <h3 ><?php esc_html_e('Book Now', 'wprentals'); ?></h3>

        <?php
        $book_type = wprentals_return_booking_type($post_id);
        ?>

            <div class="has_calendar calendar_icon">
                <input type="text" id="start_date" placeholder="<?php echo wpestate_show_labels('check_in', $rental_type); ?>"  class="form-control calendar_icon" size="40" name="start_date"
                       value="<?php
               if (isset($_GET['check_in_prop']) && $book_type == 1) {
                   echo sanitize_text_field($_GET['check_in_prop']);
               }
        ?>">
            </div>

            <?php
            if (wprentals_return_booking_type($post_id) == 2) {
                $booking_start_hour = get_post_meta($post_id, 'booking_start_hour', true);
                $booking_end_hour = get_post_meta($post_id, 'booking_end_hour', true);

                print wprentals_show_booking_form_per_hour_dropdown('start_hour', esc_html__('Start Hour', 'wprentals'), $booking_start_hour, $booking_end_hour, '');
                print wprentals_show_booking_form_per_hour_dropdown('end_hour', esc_html__('End Hour', 'wprentals'), $booking_start_hour, $booking_end_hour, '');
            } else {
                ?>

                <div class=" has_calendar calendar_icon">
                    <input type="text" id="end_date"  placeholder="<?php echo wpestate_show_labels('check_out', $rental_type); ?>" class="form-control calendar_icon" size="40" name="end_date"
                           value="<?php
                if (isset($_GET['check_out_prop'])) {
                    echo sanitize_text_field($_GET['check_out_prop']);
                }
                ?>">
                </div>
            <?php } ?>





            <?php
            if ($rental_type == 0) {
            ?>

                <div class=" has_calendar guest_icon ">
                    <?php 
                    if(wprentals_get_option('wp_estate_custom_guest_control','') =='yes'){
                          echo wpestate_show_advanced_guest_form(esc_html__('Guests','wprentals'), '',$post_id); 
                    }else{
                        echo wpestate_show_booking_form_guest_dropdown($guest_list);
                    }
                    ?>             
                </div>
                <?php
            } else {
                ?>
                <input type="hidden" name="booking_guest_no"  value="1">
                <?php
            }
            // show extra options
            wpestate_show_extra_options_booking($post_id)
            ?>

            <p class="full_form " id="add_costs_here"></p>
            <input type="hidden" id="listing_edit" name="listing_edit" value="<?php print intval($post_id); ?>" />


        <?php wpestate_show_booking_button($post_id); ?>


            <div class="third-form-wrapper">
                <div class="col-md-6 reservation_buttons">
                    <div id="add_favorites" class=" <?php print esc_attr($favorite_class); ?>"  data-postid="<?php echo esc_attr($post_id); ?>">
        <?php print trim($favorite_text); ?>
                    </div>
                </div>

                <div class="col-md-6 reservation_buttons">
                    <div id="contact_host" class="col-md-6"  data-postid="<?php esc_attr($post_id); ?>">
            <?php esc_html_e('Contact Owner', 'wprentals'); ?>
                    </div>
                </div>
            </div>

        <?php
        echo wpestate_share_unit_desing($post_id);
        ?>

        <?php } // end else?>

        </div>


        <?php
        /*
         * Only for shortcode
         *
         *
         * */
        if ($is_shortcode == 1) {

            $ajax_nonce = wp_create_nonce("wprentals_add_booking_nonce");
            print'<input type="hidden" id="wprentals_add_booking" value="' . esc_html($ajax_nonce) . '" />';
            ?>

            <div class="modal fade" id="instant_booking_modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h2 class="modal-title_big" ><?php esc_html_e('Confirm your booking', 'wprentals'); ?></h2>
                            <h4 class="modal-title" id="myModalLabel"><?php esc_html_e('Review the dates and confirm your booking', 'wprentals'); ?></h4>
                        </div>

                        <div class="modal-body"></div>

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <?php
            if (isset($_GET['check_in_prop']) && isset($_GET['check_out_prop'])) {

                print '<script type="text/javascript">
                      //<![CDATA[
                      jQuery(document).ready(function(){
                        setTimeout(function(){

                            jQuery("#end_date").trigger("change");
                        },1000);
                      });
                      //]]>
              </script>';
            }
            ?>

            <?php
        } // end for shortcode
        ?>


        <?php
        $return = ob_get_contents();
        ob_end_clean();
        return $return;
    }

endif;



/*
 * Show booking or affiliate button
 *
 *
 *
 */
if (!function_exists('wpestate_show_booking_button')):

    function wpestate_show_booking_button($post_id) {
        print '<div class="submit_booking_front_wrapper">';

        $overload_guest           =     floatval(get_post_meta($post_id, 'overload_guest', true));
        $price_per_guest_from_one =     floatval(get_post_meta($post_id, 'price_per_guest_from_one', true));
        $instant_booking          =     floatval(get_post_meta($post_id, 'instant_booking', true));
        $max_guest                =     floatval(get_post_meta($post_id, 'guest_no', true));
        $max_extra_guest_no       =     floatval(get_post_meta($post_id, 'max_extra_guest_no', true) );
            
        $affiliate_link = trim(get_post_meta($post_id, 'property_affiliate', true));

        if ($affiliate_link == '') {

            if ($instant_booking == 1) {
                ?>
                <div id="submit_booking_front_instant_wrap"><input type="submit" id="submit_booking_front_instant"  data-maxguest="<?php print esc_attr($max_guest); ?>" 
                        data-overload="<?php print esc_attr($overload_guest); ?>"  data-max-overlooad="<?php print esc_attr($max_extra_guest_no);?>"  
                        data-guestfromone="<?php print esc_attr($price_per_guest_from_one); ?>"  class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" 
                        value=" <?php esc_html_e('Instant Booking', 'wprentals'); ?>" />
                </div>
            <?php } else { ?>
                <input type="submit" id="submit_booking_front" data-maxguest="<?php print esc_attr($max_guest); ?>" data-overload="<?php print esc_attr($overload_guest); ?>"    
                       data-max-overlooad="<?php print esc_attr($max_extra_guest_no);?>"  data-guestfromone="<?php print esc_attr($price_per_guest_from_one); ?>" 
                       class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" value="<?php esc_html_e('Book Now', 'wprentals'); ?>" />
            <?php
            }
        } else {
            print '<a href="' . esc_url($affiliate_link) . '" id="submit_booking_front_link" target="_blank">' . esc_html__('Book Now', 'wprentals') . '</a>';
        }



        wp_nonce_field('booking_ajax_nonce', 'security-register-booking_front');
        print '</div>';
    }

endif;



/*
 * Show booking or affiliate button
 *
 *
 *
 */
if (!function_exists('wpestate_generate_favorite_info')):

    function wpestate_generate_favorite_info($post_id) {

        $current_user = wp_get_current_user();
        $userID = $current_user->ID;
        $user_option = 'favorites' . $userID;
        $wpestate_curent_fav = get_option($user_option);
        $favorite_class = 'isnotfavorite';
        $favorite_text = esc_html__('Add to Favorites', 'wprentals');


        if ($wpestate_curent_fav) {
            if (in_array($post_id, $wpestate_curent_fav)) {
                $favorite_class = 'isfavorite';
                $favorite_text = esc_html__('Favorite', 'wprentals') . '<i class="fas fa-heart"></i>';
            }
        }

        $favorite_data = array(
            'favorite_text' => $favorite_text,
            'favorite_class' => $favorite_class,
        );

        return $favorite_data;
    }

endif;


/**
 *  boooking guest explanations
 *
 *
 * @since    3.1
 * @access   public
 */
if (!function_exists('wpestate_booking_guest_explanations')):

    function wpestate_booking_guest_explanations($post_id) {
       
        $guests     =   get_post_meta($post_id, 'booking_adults', true);
        $childs     =   get_post_meta($post_id, 'booking_childs', true);
        $infants    =   get_post_meta($post_id, 'booking_infants', true);
    
        $return ='';
        if($guests!='' && wprentals_get_option('wp_estate_custom_guest_control','') =='yes' ){
            $return.=' (';

            $return.= sprintf( _n( '%s Guest', '%s Guests', $guests, 'wprentals' ), number_format_i18n( $guests ) );
            $return.= ', '.sprintf( _n( '%s Child', '%s Children', $childs, 'wprentals' ), number_format_i18n( $childs ) );
            $return.= ', '.sprintf( _n( '%s infant', '%s Infants', $infants, 'wprentals' ), number_format_i18n( $infants ) );
            $return.=')';
        }
        return $return;
    }
endif;



/*
 * Convert to cyrilic
 *
 *
 *
 */

if (!function_exists('wpestate_convert_cyrilic')):

    function wpestate_convert_cyrilic($text) {
        $cyr = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у',
            'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У',
            'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я');
        $lat = array('a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u',
            'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Zh',
            'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U',
            'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'Y', 'Yu', 'Ya');

        return str_replace($cyr, $lat, $text);
    }

endif;






if (!function_exists('wpestate_print_property_unit_slider')):

    function wpestate_print_property_unit_slider($post_id, $wpestate_property_unit_slider, $wpestate_listing_type, $wpestate_currency, $wpestate_where_currency, $link, $return_type = '') {
        //$link               =   esc_url ( get_permalink($post_id));
        $title = get_the_title($post_id);
        $preview = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'wpestate_property_listings');

        $booking_type = wprentals_return_booking_type($post_id);
        $rental_type = wprentals_get_option('wp_estate_item_rental_type');

        if(isset($preview[0])){
            $thumb_prop = '<img itemprop="image" src="' . esc_url($preview[0]) . '"   class="b-lazy img-responsive wp-post-image lazy-hidden" alt="' . esc_attr($title) . '" />';
       
        }else{
            $thumb_prop_default = get_stylesheet_directory_uri() . '/img/defaultimage_prop.jpg';
            $thumb_prop = '<img itemprop="image"  src="' . esc_url($thumb_prop_default) . '" class="b-lazy img-responsive wp-post-image  lazy-hidden" alt="' . esc_html__('image', 'wprentals') . '" />';
        }



        print '<div class="listing-unit-img-wrapper">';


        if ($wpestate_property_unit_slider == 'yes') {
            //slider
            $no_images = wprentals_get_option('wp_estate_image_no_slider');

            $arguments = array(
                'numberposts'       => ($no_images-1),
                'post_type'         => 'attachment',
                'post_mime_type'    => 'image',
                'post_parent'       => $post_id,
                'post_status'       => null,
                'exclude'           => get_post_thumbnail_id(),
                'orderby'           => 'menu_order',
                'order'             => 'ASC'
            );
            $post_attachments = get_posts($arguments);
            $slides = '';
            $no_slides = 0;

            foreach ($post_attachments as $attachment) {
                $no_slides++;
                $preview = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_listings');
                $slides .= '<div class="item lazy-load-item">
                                        <a href="' . esc_url($link) . '" target="' . esc_attr(wprentals_get_option('wp_estate_prop_page_new_tab', '')) . '"><img  data-lazy-load-src="' . esc_attr($preview[0]) . '" alt="' . esc_attr($attachment->title) . '" class="img-responsive" /></a>
                                    </div>';
            }// end foreach

            $unique_prop_id = uniqid();
            print '
                <div id="property_unit_carousel_' . esc_attr($unique_prop_id) . '" class="carousel property_unit_carousel slide  " data-ride="carousel" data-interval="false">
                    <div class="carousel-inner">
                        <div class="item active">
                            <a href="' . esc_url($link) . '" target="' . esc_attr(wprentals_get_option('wp_estate_prop_page_new_tab', '')) . '" >' . $thumb_prop . '</a>
                        </div>
                        ' . $slides . '
                    </div>'; // slides & thumb_prop are escaped above
            print '<a href="' . esc_url($link) . '"> </a>';

            if ($no_slides > 0) {
                print '<a class="left  carousel-control" href="#property_unit_carousel_' . esc_attr($unique_prop_id) . '" data-slide="prev">
                        <i class="icon-left-open-big"></i>
                    </a>

                    <a class="right  carousel-control" href="#property_unit_carousel_' . esc_attr($unique_prop_id) . '" data-slide="next">
                        <i class="icon-right-open-big"></i>
                    </a>';
            }
            print'</div>';
        } else {
            print '<a href="' . esc_url($link) . '" target="' . esc_attr(wprentals_get_option('wp_estate_prop_page_new_tab', '')) . '" >' . trim($thumb_prop) . '</a>';
        }

        print '<div class="price_unit_wrapper"> </div>';

        $price_per_guest_from_one = floatval(get_post_meta($post_id, 'price_per_guest_from_one', true));

        if ($price_per_guest_from_one == 1) {
            $price = floatval(get_post_meta($post_id, 'extra_price_per_guest', true));
        } else {
            $price = floatval(get_post_meta($post_id, 'property_price', true));
        }


        print'<div class="price_unit">';
        wpestate_show_price($post_id, $wpestate_currency, $wpestate_where_currency, 0);
        if ($price != 0) {
            echo '<span class="pernight"> ' . wpestate_show_labels('per_night2', $rental_type, $booking_type) . '</span>';
        }
        print '</div>';


        print '</div>';
    }

endif;




if (!function_exists('wpestate_return_property_status')):

    function wpestate_return_property_status($post_id, $return_type = '') {
        $property_status = get_the_terms($post_id, 'property_status');
        $to_return = '';


        if ($return_type == 'pin') {
            if (!empty($property_status)) {
                foreach ($property_status as $key => $term) {
                    $to_return .= esc_html($term->name) . ',';
                }
            }
            $to_return = substr($to_return, 0, -1);
            return $to_return;
        } else {
            if (!empty($property_status)) {
                foreach ($property_status as $key => $term) {
                    if ($term->slug != 'normal') {
                        $to_return .= '<div class="property_status status_' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</div>';
                    }
                }
            }

            return '<div class="property_status_wrapper">' . $to_return . '</div>';
        }
    }

endif;




if (!function_exists('wpestate_check_gdpr_case')):

    function wpestate_check_show_address_user_rent_property() {
        if (wprentals_get_option('wp_estate_show_map_location', '') == 'yes') {
            global $post;


            if (!is_singular('estate_property')) {
                return true;
            } else {
                if (!is_user_logged_in()) {
                    return false;
                } else {

                    $current_user = wp_get_current_user();
                    $userID = $current_user->ID;
                    $args = array(
                        'post_type' => 'wpestate_booking',
                        'post_status' => 'publish',
                        'posts_per_page' => 1,
                        'order' => 'DESC',
                        'author' => $userID,
                        'fields' => 'ids',
                        'meta_query' => array(
                            array(
                                'key' => 'booking_id',
                                'value' => $post->ID,
                                'type' => 'NUMERIC',
                                'compare' => '='
                            ),
                            array(
                                'key' => 'booking_status',
                                'value' => 'confirmed',
                                'compare' => '='
                            )
                        )
                    );


                    $selection = new WP_Query($args);
                    if ($selection->have_posts()) {
                        return true;
                    }
                }
            }
        } else {
            return true;
        }
    }

endif;










if (!function_exists('wpestate_check_gdpr_case')):

    function wpestate_check_gdpr_case($extra = '') {

        if (wprentals_get_option('wp_estate_use_gdpr', '') == 'yes') {

            print'<div class="gpr_wrapper"><input type="checkbox" id="wpestate_agree_gdpr' . $extra . '" class="wpestate_agree_gdpr" name="wpestate_agree_gdpr" />
            <label for="wpestate_agree_gdpr">' . esc_html__('I consent to the', 'wprentals') . ' <a target="_blank" href="' . wpestate_get_template_link('gdpr_terms.php') . '">' . esc_html__('GDPR Terms', 'wprentals') . '</a></label></div>
        ';
        }
    }

endif;





if (!function_exists('wpestate_share_unit_desing')):

    function wpestate_share_unit_desing($prop_id, $is_single = 1) {
        $protocol = is_ssl() ? 'https' : 'http';
        $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id($prop_id), 'wpestate_property_full_map');
        $link = get_permalink($prop_id);
        $title = get_the_title($prop_id);
        $twiter_status = urlencode($title . ' ' . $link);
        $email_link = 'subject=' . urlencode($title) . '&body=' . urlencode(esc_url($link));
        ob_start();
        ?>


        <div class="prop_social">
            <span class="prop_social_share"><?php esc_html_e('Share', 'wprentals'); ?></span>
            <a href="https://www.facebook.com/sharer.php?u=<?php echo esc_url($link); ?>&amp;t=<?php echo urlencode($title); ?>" target="_blank" class="share_facebook" rel ="noreferrer" ><i class="fab fa-facebook-f"></i></a>
            <a href="<?php print esc_html($protocol);?>://twitter.com/intent/tweet?text=<?php echo esc_html($twiter_status); ?>" class="share_tweet" target="_blank" rel ="noreferrer" ><i class="fab fa-twitter"></i></a>
         
            <a href="mailto:email@email.com?<?php echo trim(esc_html($email_link)); ?>"  class="share_email" target="_blank"  ><i class="far fa-envelope"></i></a>
        <?php if (isset($pinterest[0])) { ?>
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url($link); ?>&amp;media=<?php print esc_url($pinterest[0]); ?>&amp;description=<?php echo urlencode($title); ?>" target="_blank"  rel ="noreferrer" class="share_pinterest"> <i class="fab fa-pinterest-p fa-2"></i> </a>
        <?php } ?>
        </div>

        <?php
        $return = ob_get_contents();
        ob_end_clean();
        return $return;
    }

endif;





if (!function_exists('wpestate_request_transient_cache')):

    function wpestate_request_transient_cache($transient_name) {

        if (wprentals_get_option('wp_estate_disable_theme_cache') == 'yes') {
            return false;
        } else {
            return get_transient($transient_name);
        }
    }

endif;

function wpestate_set_transient_cache($transient_name, $value, $time) {
    if (wprentals_get_option('wp_estate_disable_theme_cache') !== 'yes') {
        set_transient($transient_name, $value, $time);
    }
}

if (!function_exists('wprentals_update_option')):

    function wprentals_update_option($theme_option, $value, $option = false) {
        global $wprentals_admin;
        if ($option) {
            $option_array = array($option => $value);
            Redux::setOption('wprentals_admin', $theme_option, $option_array);
        } else {
            Redux::setOption('wprentals_admin', $theme_option, $value);
        }
    }

endif;



if (!function_exists('wprentals_get_option')):

    function wprentals_get_option($theme_option, $option = false, $in_case_not = false) {
        $theme_option = trim($theme_option);
        global $wprentals_admin;

        if ($theme_option == 'wpestate_currency') {
            $return = wpestate_reverse_convert_redux_wp_estate_multi_curr();
            return $return;
        } else if ($theme_option == 'wpestate_custom_fields_list') {
            $return = wpestate_reverse_convert_redux_wp_estate_custom_fields();
            usort($return, function($a, $b) {
                return $a['3'] - $b['3'];
            });
            return $return;
        } else if ($theme_option == 'wp_estate_property_page_header') {
            $return = wpestate_reverse_convert_redux_wp_estate_property_page_header();
            return $return;
        } else if ($theme_option == 'wp_estate_custom_listing_fields') {
            $return = wpestate_reverse_convert_redux_wp_estate_custom_listing_fields();
            return $return;
        } else if ($theme_option == 'wp_estate_custom_infobox_fields') {
            $return = wpestate_reverse_convert_redux_wpestate_convert_redux_wp_estate_custom_infobox_fields();
            return $return;
        }


        if (isset($wprentals_admin[$theme_option]) && $wprentals_admin[$theme_option] != '') {
            $return = $wprentals_admin[$theme_option];
            if ($option) {
                $return = $wprentals_admin[$theme_option][$option];
            }
        } else {
            $return = $in_case_not;
        }

        return $return;
    }

endif;





add_action('customize_save_after', 'wprentals_customizer_save', 99);

function wprentals_customizer_save() {
    if (has_site_icon()) {
        $values = array();
        $values['id'] = get_option('site_icon');
        $values['url'] = get_site_icon_url();
        if (function_exists('wpestate_rentals_functionality_loaded')) {
            require_once WPESTATE_PLUGIN_PATH . 'admin/admin-init.php';
            Redux::init("wprentals_admin");
            Redux::setOption('wprentals_admin', 'wp_estate_favicon_image', $values); //front
        }
    }
}

add_action('redux/options/wprentals_admin/saved', 'wprentals_redux_on_save', 10, 2);

function wprentals_redux_on_save($value, $value2) {


    if (isset($value['wp_estate_favicon_image']['id'])) {
        update_option('site_icon', $value['wp_estate_favicon_image']['id']);
    }



    if (isset($value['wpestate_set_search']['adv_search_what'])) {
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_what', $value['wpestate_set_search']['adv_search_what']);
    }
    if (isset($value['wpestate_set_search']['adv_search_how'])) {
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_how', $value['wpestate_set_search']['adv_search_how']);
    }
    if (isset($value['wpestate_set_search']['adv_search_label'])) {
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_label', $value['wpestate_set_search']['adv_search_label']);
    }
    if (isset($value['wpestate_set_search']['search_field_label'])) {
        Redux::setOption('wprentals_admin', 'wp_estate_search_field_label', $value['wpestate_set_search']['search_field_label']);
    }


    if (isset($value['wpestate_set_search_half_map']['adv_search_what'])) {
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_what_half_map', $value['wpestate_set_search_half_map']['adv_search_what']);
    }
    if (isset($value['wpestate_set_search_half_map']['adv_search_how'])) {
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_how_half_map', $value['wpestate_set_search_half_map']['adv_search_how']);
    }
    if (isset($value['wpestate_set_search_half_map']['adv_search_label'])) {
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_label_half_map', $value['wpestate_set_search_half_map']['adv_search_label']);
    }
    if (isset($value['wpestate_set_search_half_map']['search_field_label'])) {
        Redux::setOption('wprentals_admin', 'wp_estate_search_field_label_half_map', $value['wpestate_set_search_half_map']['search_field_label']);
    }





    if (isset($value['wp_estate_adv_search_type']) && ( $value['wp_estate_adv_search_type'] == 'newtype' || $value['wp_estate_adv_search_type'] == 'oldtype')) {
        $adv_search_what = array('Location', 'check_in', 'check_out', 'guest_no');
        $adv_search_how = array('like', 'like', 'like', 'greater');


        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_what_classic', $adv_search_what);
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_how_classic', $adv_search_how);


        $adv_search_what_classic_half = array('Location', 'check_in', 'check_out', 'guest_no', 'property_rooms', 'property_category', 'property_action_category', 'property_bedrooms', 'property_bathrooms', 'property_price');
        $adv_search_how_classic_half = array('like', 'like', 'like', 'greater', 'greater', 'like', 'like', 'greater', 'greater', 'between');
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_what_half', $adv_search_what_classic_half);
        Redux::setOption('wprentals_admin', 'wp_estate_adv_search_how_half', $adv_search_how_classic_half);
    }




    if (isset($value['wp_estate_delete_orphan'])) {
        if ($value['wp_estate_delete_orphan'] == 'yes') {
            wpestate_setup_wp_estate_delete_orphan_lists();
        } else {
            wp_clear_scheduled_hook('prefix_wp_estate_delete_orphan_lists');
        }
    }

    if ($value['wp_estate_wpestate_autocomplete'] == 'no') {
        wprentals_event_wp_estate_create_auto_function();
    }

    if (isset($value['wp_estate_paid_submission'])) {
        if ($value['wp_estate_paid_submission'] == 'membership') {
            wprentals_schedule_user_check();
        } else {
            wp_clear_scheduled_hook('wpestate_check_for_users_event');
        }
    }

    if (isset($value['wpestate_autocomplete'])) {
        if ($value['wpestate_autocomplete'] == 'no') {
            wpestate_create_auto_data();
        } else {
            wp_clear_scheduled_hook('event_wp_estate_create_auto');
        }
    }


    if (isset($value['wp_estate_auto_curency'])) {
        if ($value['wp_estate_auto_curency'] == 'yes') {
            wpestate_enable_load_exchange();
        } else {
            wp_clear_scheduled_hook('wpestate_load_exchange_action');
        }
    }

    if (isset($value['wp_estate_book_down']) && floatval($value['wp_estate_book_down']) == 100) {
        Redux::setOption('wprentals_admin', 'wp_estate_include_expenses', 'yes');
    }




    if (isset($value['wpestate_custom_fields_list']) && $value['wpestate_custom_fields_list'] != '') {
        update_option('wpestate_custom_fields_list', $value['wpestate_custom_fields_list']);
    }



    if (isset($value['wp_estate_book_down']) && $value['wp_estate_book_down'] == '100') {
        Redux::setOption('wprentals_admin', 'wp_estate_include_expenses', 'yes');
    }


    if ($value['wp_estate_url_rewrites']) {
        update_option('wp_estate_url_rewrites', $value['wp_estate_url_rewrites']);
    }


    if (isset($value['wp_estate_theme_slider_manual']) && $value['wp_estate_theme_slider_manual'] != '') {
        $theme_slider = array();
        $new_ids = explode(',', $value['wp_estate_theme_slider_manual']);

        if (is_array($new_ids)) {
            foreach ($new_ids as $key => $value) {
                $theme_slider[] = $value;
            }
            Redux::setOption('wprentals_admin', 'wp_estate_theme_slider', $theme_slider);
        }
    }







    $api_options = array(
        'wp_estate_measure_sys',
        'wp_estate_date_lang',
        'wp_estate_setup_weekend',
        'wp_estate_separate_users',
        'wp_estate_publish_only',
        'wp_estate_prices_th_separator',
        'wp_estate_currency_symbol',
        'wp_estate_where_currency_symbol',
        'wp_estate_currency_label_main',
        'wp_estate_auto_curency',
        'wp_estate_where_currency_symbol',
        'wp_estate_status_list',
        'wp_estate_company_name',
        'wp_estate_email_adr',
        'wp_estate_telephone_no',
        'wp_estate_mobile_no',
        'wp_estate_fax_ac',
        'wp_estate_skype_ac',
        'wp_estate_co_address',
        'wp_estate_facebook_link',
        'wp_estate_twitter_link',
        'wp_estate_google_link',
        'wp_estate_pinterest_link',
        'wp_estate_linkedin_link',
        'wp_estate_facebook_login',
        'wp_estate_google_login',
        'wp_estate_yahoo_login',
        'wp_estate_social_register_on',
        'wp_estate_readsys',
        'wp_estate_enable_paypal',
        'wp_estate_enable_stripe',
        'wp_estate_admin_submission',
        'wp_estate_price_submission',
        'wp_estate_price_featured_submission',
        'wp_estate_submission_curency',
        'wp_estate_prop_image_number',
        'wp_estate_enable_direct_pay',
        'wp_estate_submission_curency_custom',
        'wp_estate_free_mem_list',
        'wp_estate_free_feat_list',
        'wp_estate_book_down',
        'wp_estate_book_down_fixed_fee',
        'wp_estate_service_fee_fixed_fee',
        'wp_estate_service_fee'
    );

    $theme_options_api_redux = array();
    foreach ($api_options as $key => $item_val) {
        if (isset($value[$item_val])) {
            $theme_options_api_redux[str_replace('wp_estate_', '', $item_val)] = $value[$item_val];
        }
    }







    return $value;
}

function wprentals_redux_advanced_exteded() {

    $return_array = array();
    $terms = get_terms(array(
        'taxonomy' => 'property_features',
        'hide_empty' => false,
            ));
    foreach ($terms as $key => $term) {
        $return_array[$term->slug] = $term->name;
    }



    return $return_array;
}

function wp_estate_redux_on_child_theme_customcss() {
    print '<textarea onclick="this.focus();this.select()" class="modal-content" style="height:250px;">';

    $general_font = esc_html(wprentals_get_option('wp_estate_general_font', ''));
    if ($general_font != '' && $general_font != 'x') {
        require_once get_theme_file_path('/libs/custom_general_font.php');
    }
    require_once get_theme_file_path('/libs/customcss.php');

    print '</textarea><span style="margin-left:30px;">';
}

if (!function_exists('wpestate_fields_type_select_redux')):

    function wpestate_fields_type_select_redux($name_drop, $real_value) {

        $select = '<select   name="' . $name_drop . '"   style="width:140px;">';
        $values = array('short text', 'long text', 'numeric', 'date', 'dropdown');

        foreach ($values as $option) {
            $select .= '<option value="' . $option . '"';
            if ($option == $real_value) {
                $select .= ' selected="selected"  ';
            }
            $select .= ' > ' . $option . ' </option>';
        }
        $select .= '</select>';
        return $select;
    }

endif;


if (!function_exists('wpestate_show_advanced_search_how_redux')):

    function wpestate_show_advanced_search_how_redux($adv_search_how_value) {
        $return_string = '';
        $curent_value = '';

        $admin_submission_how_array = array('equal',
            'greater',
            'smaller',
            'like',
            'date bigger',
            'date smaller');

        foreach ($admin_submission_how_array as $value) {
            $return_string .= '<option value="' . $value . '" ';
            if ($adv_search_how_value == $value) {
                $return_string .= ' selected="selected" ';
            }
            $return_string .= '>' . $value . '</option>';
        }
        return $return_string;
    }

endif; // end   wpestate_show_advanced_search_how

/*
 *  
 * 
 *  Array with search fields for elementor search form
 * 
 * 
 * 
 * 
 * 
 * */



if (!function_exists('wpestate_elementor_search_form_builder_items_array')):

    function wpestate_elementor_search_form_builder_items_array() {
        

        $admin_submission_array = array(
            'Location' => esc_html('Location', 'wprentals'),
            'check_in' => esc_html('check_in', 'wprentals'),
            'check_out' => esc_html('check_out', 'wprentals'),
            'property_category' => esc_html('First Category', 'wprentals'),
            'property_action_category' => esc_html('Second Category', 'wprentals'),
            'property_city' => esc_html('Cities', 'wprentals'),
            'property_area' => esc_html('Areas', 'wprentals'),
            'guest_no' => esc_html('guest_no', 'wprentals'),
            'property_price' => esc_html('Price', 'wprentals'),
            'property_size' => esc_html('Size', 'wprentals'),
            'property_rooms' => esc_html('Rooms', 'wprentals'),
            'property_bedrooms' => esc_html('Bedroms', 'wprentals'),
            'property_bathrooms' => esc_html('Bathrooms', 'wprentals'),
            'property_address' => esc_html('Adress', 'wprentals'),
            'property_county' => esc_html('County', 'wprentals'),
            'property_state' => esc_html('State', 'wprentals'),
            'property_zip' => esc_html('Zip', 'wprentals'),
            'property_country' => esc_html('Country', 'wprentals'),
        );

           

    

        $custom_fields = get_option('wpestate_custom_fields_list');
        $i=0;
        if (!empty($custom_fields)) {
            while ($i < count($custom_fields['add_field_name'])) {       
                $data = wprentals_prepare_non_latin($custom_fields['add_field_name'][$i], $custom_fields['add_field_label'][$i]);
                $admin_submission_array[$data['key']]=$data['label'];    
                $i++;
            }
        }

        return $admin_submission_array;
    }

endif; // end   wpestate_show_advanced_search_options


/*
 *  
 * 
 *  Dropdpwn fields for redux search component
 * 
 * 
 * 
 * 
 * 
 * */



if (!function_exists('wpestate_show_advanced_search_options_redux')):

    function wpestate_show_advanced_search_options_redux($adv_search_what_value) {
        $return_string = '';

        $admin_submission_array = array('Location' => esc_html('Location', 'wprentals'),
            'check_in' => esc_html('check_in', 'wprentals'),
            'check_out' => esc_html('check_out', 'wprentals'),
            'property_category' => esc_html('First Category', 'wprentals'),
            'property_action_category' => esc_html('Second Category', 'wprentals'),
            'property_city' => esc_html('Cities', 'wprentals'),
            'property_area' => esc_html('Areas', 'wprentals'),
            'guest_no' => esc_html('guest_no', 'wprentals'),
            'property_price' => esc_html('Price', 'wprentals'),
            'property_size' => esc_html('Size', 'wprentals'),
            'property_rooms' => esc_html('Rooms', 'wprentals'),
            'property_bedrooms' => esc_html('Bedroms', 'wprentals'),
            'property_bathrooms' => esc_html('Bathrooms', 'wprentals'),
            'property_address' => esc_html('Adress', 'wprentals'),
            'property_county' => esc_html('County', 'wprentals'),
            'property_state' => esc_html('State', 'wprentals'),
            'property_zip' => esc_html('Zip', 'wprentals'),
            'property_country' => esc_html('Country', 'wprentals'),
        );

        foreach ($admin_submission_array as $key => $value) {

            $return_string .= '<option value="' . $key . '" ';
            if ($adv_search_what_value == $key) {
                $return_string .= ' selected="selected" ';
            }
            $return_string .= '>' . $value . '</option>';
        }

        $i = 0;
    
//
        $custom_fields = get_option('wpestate_custom_fields_list');

        if (!empty($custom_fields)) {
            while ($i < count($custom_fields['add_field_name'])) {

                $data = wprentals_prepare_non_latin($custom_fields['add_field_name'][$i], $custom_fields['add_field_label'][$i]);


                $return_string .= '<option value="' . $data['key'] . '" ';
                if ($adv_search_what_value == $data['key']) {
                    $return_string .= ' selected="selected" ';
                }
                $return_string .= '>' . $data['label'] . '</option>';
                $i++;
            }
        }




        $slug = 'none';
        $name = 'none';
        $return_string .= '<option value="' . $slug . '" ';
        if ($adv_search_what_value == $slug) {
            $return_string .= ' selected="selected" ';
        }
        $return_string .= '>' . $name . '</option>';


        return $return_string;
    }

endif; // end   wpestate_show_advanced_search_options



if (!function_exists('wprentals_return_booking_type')):

    function wprentals_return_booking_type($edit_id) {
//        1 => __("Per Day","wprentals"),
//        2 => __("Per Hour","wprentals"),
//
        $global_type = wprentals_get_option('wp_estate_booking_type', '');
        if ($global_type == 3) {
            return intval(get_post_meta($edit_id, 'local_booking_type', true));
        } else {
            return $global_type;
        }
    }

endif;


if (!function_exists('wpestate_number_convert_currency')):

    function wpestate_number_convert_currency($price) {
        $custom_fields = wprentals_get_option('wpestate_currency', '');
        if (!empty($custom_fields) && isset($_COOKIE['my_custom_curr']) && isset($_COOKIE['my_custom_curr_pos']) && isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos'] != -1) {
            $i = floatval($_COOKIE['my_custom_curr_pos']);
            $custom_fields = wprentals_get_option('wpestate_currency', '');
            if ($price != 0) {
                $price = $price * $custom_fields[$i][2];
            }
        }
        return $price;
    }

endif;



if (!function_exists('wpestate_admin_display_verifications')) {

    /**
     * Display verification widget
     */
    function wpestate_admin_display_verifications() {
        global $current_user;
        if ('profile' == get_current_screen()->id) {
            $verifications = '';
            $verification_users = '';

            $v_users = wpestate_get_verification_users();

            foreach ($v_users as $user_o) {
                $verification_users .= wpestate_render_single_userid($user_o);
            }

            $verifications .= '<div class="user-verifications">' . PHP_EOL;
            $verifications .= $verification_users;
            $verifications .= '</div> <!-- end .user-verifications -->' . PHP_EOL;
            $ajax_nonce = wp_create_nonce("wprentals_user_verfication_nonce");
            $verifications .= '<input type="hidden" id="wprentals_user_verfication" value="' . esc_html($ajax_nonce) . '" />    ';


            print trim($verifications); //escaped above
        }
    }

    // display verification widget only for admin users on the admin user edit page
}




if (!function_exists('wpestate_category_labels_dropdowns')):

    function wpestate_category_labels_dropdowns($who,$label='') {
        $category_main_dropdown_label = stripslashes(esc_html(wprentals_get_option('wp_estate_category_main_dropdown', '')));
        $category_second_dropdown_label = stripslashes(esc_html(wprentals_get_option('wp_estate_category_second_dropdown', '')));


        if ($who == 'main') {
            if ($category_main_dropdown_label == '') {
                
                if($label!=''){
                    return $label;
                }else{
                    return esc_html__('All Types', 'wprentals');
                }
                
            } else {
                return $category_main_dropdown_label;
            }
        } else {
            if ($category_main_dropdown_label == '') {
       
                if($label!=''){
                    return $label;
                }else{
                    return esc_html__('All Sizes', 'wprentals');
                }
                
            } else {
                return $category_second_dropdown_label;
            }
        }
    }

endif;


if (!function_exists('wpestate_city_submit_dropdown')):

    function wpestate_city_submit_dropdown($tax, $value) {

        $args = array(
            'orderby' => 'name',
            'hide_empty' => 0,
        );

        $terms = get_terms($tax, $args);
        $list = '';
        foreach ($terms as $term) {

            $list .= '<option name="' . $term->name . '"';
            if ($value == $term->name) {
                $list .= ' selected = "selected" ';
            }
            $list .= '>' . $term->name . '</option>';
        }

        return $list;
    }

endif;

if (!function_exists('wpestate_delete_cache')):

    function wpestate_delete_cache() {
        global $wpdb;
        $sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
            FROM  $wpdb->options
            WHERE `option_name` LIKE %s
            ORDER BY `option_name`";


        $wild = '%';
        $find = 'transient_';
        $like = $wild . $wpdb->esc_like($find) . $wild;

        $results = $wpdb->get_results($wpdb->prepare($sql, $like));
        $transients = array();

        foreach ($results as $result) {
            if (0 === strpos($result->name, '_transient_wpestate')) {
                $transient_name = str_replace('_transient_', '', $result->name);
                delete_transient($transient_name);
            }
        }
    }

endif;








if (!function_exists('wpestate_price_pin_converter')):

    function wpestate_price_pin_converter($pin_price, $wpestate_where_currency, $wpestate_currency) {

        if (isset($_COOKIE['my_custom_curr']) && isset($_COOKIE['my_custom_curr_pos']) && isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos'] != -1) {
            $i = floatval($_COOKIE['my_custom_curr_pos']);
            $th_separator = wprentals_get_option('wp_estate_prices_th_separator', '');
            $custom_fields = wprentals_get_option('wpestate_currency', '');
            if ($pin_price != 0) {
                $pin_price = $pin_price * floatval($custom_fields[$i][2]);

                $pin_price = number_format($pin_price, 2, '.', $th_separator);
                $pin_price = wpestate_TrimTrailingZeroes($pin_price);


                $wpestate_currency = $custom_fields[$i][1];
            } else {
                $pin_price = '';
            }
        }

        $pin_price = floatval($pin_price);
        if (10000 < $pin_price && $pin_price < 1000000) {
            $pin_price = round($pin_price / 1000, 1);
            $pin_price = $pin_price . '' . __('K', 'wprentals');
        }
        if ($pin_price >= 1000000) {
            $pin_price = round($pin_price / 1000000, 1);
            $pin_price = $pin_price . '' . __('M', 'wprentals');
        }




        if ($wpestate_where_currency == 'before') {
            $pin_price = $wpestate_currency . ' ' . $pin_price;
        } else {
            $pin_price = $pin_price . ' ' . $wpestate_currency;
        }
        return $pin_price;
    }

endif;

/*
*
* SHOW MAP POI
*
*/


if (!function_exists('wpestate_show_poi_onmap')):

    function wpestate_show_poi_onmap($where = '') {
        global $post;
        if (!is_singular('estate_property')) {
            return;
        }
        $points = array(
            'transport' => __('Transport', 'wprentals'),
            'supermarkets' => __('Supermarkets', 'wprentals'),
            'schools' => __('Schools', 'wprentals'),
            'restaurant' => __('Restaurants', 'wprentals'),
            'pharma' => __('Pharmacies', 'wprentals'),
            'hospitals' => __('Hospitals', 'wprentals'),
        );

        $return_value = '<div class="google_map_poi_marker">';
        foreach ($points as $key => $value) {
            $return_value .= '<div class="google_poi' . $where . '" id="' . $key . '"><img src="' . get_template_directory_uri() . '/css/css-images/poi/' . $key . '_icon.png" class="dashboad-tooltip"  data-placement="right"  data-original-title="' . esc_attr($value) . '" ></div>';
        }
        $return_value .= '</div>';
        return $return_value;
    }

endif;


if (!function_exists('wpestate_splash_page_header')):

    function wpestate_splash_page_header() {

        $spash_header_type = wprentals_get_option('wp_estate_spash_header_type', '');

        if ($spash_header_type == 'image') {
            wpestate_header_image('');
        } else if ($spash_header_type == 'video') {
            wpestate_video_header();
        } else if ($spash_header_type == 'image slider') {
            wpestate_splash_slider();
        }
    }

endif;


if (!function_exists('wpestate_splash_slider')):

    function wpestate_splash_slider() {
        $splash_slider_gallery = esc_html(wprentals_get_option('wp_estate_splash_slider_gallery', ''));
        $splash_slider_transition = esc_html(wprentals_get_option('wp_estate_splash_slider_transition', ''));


        $splash_slider_gallery_array = explode(',', $splash_slider_gallery);
        $slider = '<div id="splash_slider_wrapper" class="carousel slide" data-ride="carousel" data-interval="' . esc_attr($splash_slider_transition) . '">';
        $i = 0;

        if (is_array($splash_slider_gallery_array)) {
            foreach ($splash_slider_gallery_array as $image_id) {

                if (is_numeric($image_id) && $image_id != '') {
                    $i++;
                    if ($i == 1) {
                        $class_active = ' active ';
                    } else {
                        $class_active = '  ';
                    }
                    $preview = wp_get_attachment_image_src($image_id, 'full');

                    if ($preview[0] != '') {
                        $slider .= '<div class="item splash_slider_item';
                        $slider .= $class_active . ' "  style="background-image:url(' . esc_url($preview[0]) . ');" >
                    </div>';
                    }
                }
            }
        }

        $slider .= '</div>';

        $page_header_overlay_val = esc_html(wprentals_get_option('wp_estate_splash_overlay_opacity', ''));
        $page_header_overlay_color = esc_html(wprentals_get_option('wp_estate_splash_overlay_color', ''));
        $wp_estate_splash_overlay_image = esc_html(wprentals_get_option('wp_estate_splash_overlay_image', 'url'));
        $page_header_title_over_image = stripslashes(esc_html(wprentals_get_option('wp_estate_splash_page_title', '')));
        $page_header_subtitle_over_image = stripslashes(esc_html(wprentals_get_option('wp_estate_splash_page_subtitle', '')));



        if ($page_header_overlay_color != '' || $wp_estate_splash_overlay_image != '') {
            $slider .= '<div class="wpestate_header_image_overlay" style="background-color:' . $page_header_overlay_color . ';opacity:' . $page_header_overlay_val . ';background-image:url(' . esc_url($wp_estate_splash_overlay_image) . ');"></div>';
        }

        if ($page_header_title_over_image != '') {

            $slider .= '<div class="heading_over_image_wrapper" >';
            $slider .= '<h1 class="heading_over_image">' . $page_header_title_over_image . '</h1>';

            if ($page_header_subtitle_over_image != '') {
                $slider .= '<div class="subheading_over_image">' . $page_header_subtitle_over_image . '</div>';
            }

            $slider .= '</div>';
        }


        print trim($slider); //escaped above
    }

endif;


if (!function_exists('wpestate_video_header')):

    function wpestate_video_header() {

        global $post;
        $page_template='';
        if(isset($post->ID)){
            $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        }

        $paralax_header = wprentals_get_option('wp_estate_paralax_header', '');
        if (isset($post->ID)) {
            if ( $page_template=='splash_page.php' ) {
                $page_custom_video = esc_html(wprentals_get_option('wp_estate_splash_video_mp4', 'url'));
                $page_custom_video_webm = esc_html(wprentals_get_option('wp_estate_splash_video_webm', 'url'));
                $page_custom_video_ogv = esc_html(wprentals_get_option('wp_estate_splash_video_ogv', 'url'));
                $page_custom_video_cover_image = esc_html(wprentals_get_option('wp_estate_splash_video_cover_img', 'url'));
                $img_full_screen = 'no';
                $page_header_title_over_video = stripslashes(esc_html(wprentals_get_option('wp_estate_splash_page_title', '')));
                $page_header_subtitle_over_video = stripslashes(esc_html(wprentals_get_option('wp_estate_splash_page_subtitle', '')));
                $page_header_video_height = '';
                $page_header_overlay_color_video = esc_html(wprentals_get_option('wp_estate_splash_overlay_color', ''));
                $page_header_overlay_val_video = esc_html(wprentals_get_option('wp_estate_splash_overlay_opacity', ''));
                $wp_estate_splash_overlay_image = esc_html(wprentals_get_option('wp_estate_splash_overlay_image', 'url'));
            } else {
                $page_custom_video = esc_html(get_post_meta($post->ID, 'page_custom_video', true));
                $page_custom_video_webm = esc_html(get_post_meta($post->ID, 'page_custom_video_webbm', true));
                $page_custom_video_ogv = esc_html(get_post_meta($post->ID, 'page_custom_video_ogv', true));
                $page_custom_video_cover_image = esc_html(get_post_meta($post->ID, 'page_custom_video_cover_image', true));
                $img_full_screen = esc_html(get_post_meta($post->ID, 'page_header_video_full_screen', true));
                $page_header_title_over_video = stripslashes(esc_html(get_post_meta($post->ID, 'page_header_title_over_video', true)));
                $page_header_subtitle_over_video = stripslashes(esc_html(get_post_meta($post->ID, 'page_header_subtitle_over_video', true)));
                $page_header_video_height = floatval(get_post_meta($post->ID, 'page_header_video_height', true));
                $page_header_overlay_color_video = esc_html(get_post_meta($post->ID, 'page_header_overlay_color_video', true));
                $page_header_overlay_val_video = esc_html(get_post_meta($post->ID, 'page_header_overlay_val_video', true));
                $wp_estate_splash_overlay_image = '';
            }


            if ($page_header_overlay_val_video == '') {
                $page_header_overlay_val_video = 1;
            }
            if ($page_header_video_height == 0) {
                $page_header_video_height = 580;
            }


            print '<div class="wpestate_header_video full_screen_' . $img_full_screen . ' parallax_effect_' . $paralax_header . '" style="';

            print ' height:' . $page_header_video_height . 'px; ';

            print '">';


            print '<video id="hero-vid" class="header_video" poster="' . $page_custom_video_cover_image . '" width="100%" height="100%" autoplay controls muted loop playsinline>
			<source src="' . esc_url($page_custom_video) . '" type="video/mp4" />
			<source src="' . esc_url($page_custom_video_webm) . '" type="video/webm" />
                        <source src="' . esc_url($page_custom_video_ogv) . '" type="video/ogg"/>

		</video>';

            if ($page_header_overlay_color_video != '' || $wp_estate_splash_overlay_image != '') {
                print '<div class="wpestate_header_video_overlay" style="background-color:' . $page_header_overlay_color_video . ';opacity:' . $page_header_overlay_val_video . ';background-image:url(' . esc_url($wp_estate_splash_overlay_image) . ');"></div>';
            }

            if ($page_header_title_over_video != '') {
                print '<div class="heading_over_video_wrapper" >';
                print '<h1 class="heading_over_video">' . $page_header_title_over_video . '</h1>';

                if ($page_header_subtitle_over_video != '') {
                    print '<div class="subheading_over_video">' . $page_header_subtitle_over_video . '</div>';
                }

                print '</div>';
            }


            print'</div>';
        }
    }

endif;






if (!function_exists('wpestate_calculate_new_mess')):

    function wpestate_increment_mess_mo($userID) {
        $unread = intval( intval(get_user_meta($userID, 'unread_mess', true)) + 1);
        update_user_meta($userID, 'unread_mess', $unread);
    }

endif;

add_action('wp_login', 'wpestate_calculate_new_mess');
if (!function_exists('wpestate_calculate_new_mess')):

    function wpestate_calculate_new_mess() {
        global $current_user;
        $current_user = wp_get_current_user();
        $userID = $current_user->ID;

        $args_mess = array(
            'post_type' => 'wpestate_message',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'order' => 'DESC',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'message_to_user',
                        'value' => $userID,
                        'compare' => '='
                    ),
                    array(
                        'key' => 'message_from_user',
                        'value' => $userID,
                        'compare' => '='
                    ),
                ),
//                                      array(
//                                          'key'       => 'first_content',
//                                          'value'     => 1,
//                                          'compare'   => '='
//                                      ),
                array(
                    'key' => 'delete_destination' . $userID,
                    'value' => 1,
                    'compare' => '!='
                ),
                array(
                    'key' => 'message_status' . $userID,
                    'value' => 'unread',
                    'compare' => '=='
                ),
            )
        );

        $args_mess_selection = new WP_Query($args_mess);

        update_user_meta($userID, 'unread_mess', $args_mess_selection->found_posts);
        //return $args_mess_selection->found_posts;
    }

endif;

if (!function_exists('wpestate_booking_mark_confirmed')):

    function wpestate_booking_mark_confirmed($booking_id, $invoice_id, $userId, $depozit, $user_email, $is_stripe = 0) {

        $is_full_pay = 0;
        $booking_details = array();
        $booking_status = get_post_meta($booking_id, 'booking_status', true);
        $is_full_instant_booking = get_post_meta($booking_id, 'is_full_instant', true);
        $is_full_instant_invoice = get_post_meta($invoice_id, 'is_full_instant', true);




        if ($booking_status != 'confirmed') {
            update_post_meta($booking_id, 'booking_status', 'confirmed');
            $booking_details['booking_status'] = 'confirmed';
        } else {
            // confirmed_paid_full
            update_post_meta($booking_id, 'booking_status_full', 'confirmed');
            $booking_details['booking_status_full'] = 'confirmed';
            $booking_details['balance'] = 0;
            update_post_meta($booking_id, 'balance', 0);
        }

        if ($is_full_instant_booking == 1) {
            update_post_meta($booking_id, 'booking_status_full', 'confirmed');
            $booking_details['booking_status_full'] = 'confirmed';
            $booking_details['balance'] = 0;
            update_post_meta($booking_id, 'balance', 0);
        }

        if ($is_stripe == 1) {
            $depozit = ($depozit / 100);
        }




        // reservation array
        $curent_listng_id = get_post_meta($booking_id, 'booking_id', true);
        $reservation_array = wpestate_get_booking_dates($curent_listng_id);
        update_post_meta($curent_listng_id, 'booking_dates', $reservation_array);




        $invoice_details = array();
        $invoice_status = get_post_meta($invoice_id, 'invoice_status', true);


        if ($invoice_status != 'confirmed') {
            update_post_meta($invoice_id, 'depozit_paid', $depozit);
            update_post_meta($invoice_id, 'invoice_status', 'confirmed');
            $invoice_details['invoice_status'] = 'confirmed';
        } else {
            update_post_meta($invoice_id, 'invoice_status_full', 'confirmed');
            $invoice_details['invoice_status_full'] = 'confirmed';
            $invoice_details['balance'] = 0;
            update_post_meta($invoice_id, 'balance', 0);
        }


        if ($is_full_instant_invoice == 1) {
            update_post_meta($invoice_id, 'invoice_status_full', 'confirmed');
            $invoice_details['invoice_status_full'] = 'confirmed';
            $invoice_details['balance'] = 0;
            update_post_meta($invoice_id, 'balance', 0);
        }

        // 100% deposit
        $wp_estate_book_down = floatval(get_post_meta($invoice_id, 'invoice_percent', true));
        $invoice_price = floatval(get_post_meta($invoice_id, 'item_price', true));

        if ($wp_estate_book_down == 100) {
            update_post_meta($booking_id, 'booking_status_full', 'confirmed');
            $booking_details['booking_status_full'] = 'confirmed';
            $booking_details['balance'] = 0;
            update_post_meta($booking_id, 'balance', 0);

            update_post_meta($invoice_id, 'invoice_status_full', 'confirmed');
            $invoice_details['invoice_status_full'] = 'confirmed';
            $invoice_details['balance'] = 0;
            update_post_meta($invoice_id, 'balance', 0);
        }
        // end 100% deposit



        $woo_double_check = intval(get_post_meta($booking_id, 'woo_double_check', true));

        if ($woo_double_check != 1) {

            wpestate_send_booking_email("bookingconfirmeduser", $user_email);

            ob_start();
            wpestate_generate_trip_details($curent_listng_id,$invoice_id,$booking_id,'email');
            $message= ob_get_contents();
            ob_end_clean();
            wpestate_send_emails($user_email, esc_html__('Your Trip Details','wprentals'), $message );
            
            
            $receiver_id = wpsestate_get_author($invoice_id);
            $receiver_email = get_the_author_meta('user_email', $receiver_id);
            $receiver_name = get_the_author_meta('user_login', $receiver_id);
            wpestate_send_booking_email("bookingconfirmed", $receiver_email);
            
           
            
            

            // add messages to inbox
            $subject = esc_html__('Booking Confirmation', 'wprentals');
            $description = esc_html__('A booking was confirmed', 'wprentals');
            wpestate_add_to_inbox($userId, $userId, $receiver_id, $subject, $description, 1);

            //marl as email sent for woo
            update_post_meta($booking_id, 'woo_double_check', 1);
        }


        // rcapi code
//        $booking_guests     =   floatval(get_post_meta($booking_id, 'booking_guests', true));
//        $booking_from_date  =   esc_html(get_post_meta($booking_id, 'booking_from_date', true));
//        $booking_to_date    =   esc_html(get_post_meta($booking_id, 'booking_to_date', true));
//        $booking_prop       =   esc_html(get_post_meta($booking_id, 'booking_id', true));
//        $booking_array      =   wpestate_booking_price($booking_guests,$invoice_id,$curent_listng_id, $booking_from_date, $booking_to_date);
//
    }

endif;


add_action('updated_post_meta', 'wpestate_wpml_after_post_meta', 10, 4);

function wpestate_wpml_after_post_meta($meta_id, $post_id, $meta_key, $meta_value) {
    if ('booking_dates' == $meta_key) {
        if (function_exists('icl_translate')) {
            wpestate_update_booking_dates_for_wpml($post_id, $meta_value);
        }
    }
}

if (!function_exists('wpestate_update_booking_dates_for_wpml')):

    function wpestate_update_booking_dates_for_wpml($listing_id, $reservation_array) {

        $trid = apply_filters('wpml_element_trid', NULL, $listing_id, 'post_page');
        $translations = apply_filters('wpml_get_element_translations', NULL, $trid, 'post_page');

        foreach ($translations as $key => $translate) {
            $lan_id = $translate->element_id;
            update_post_meta($lan_id, 'booking_dates', $reservation_array);
        }
    }

endif;



if (!function_exists('wpestate_check_reservation_period')):

    function wpestate_check_reservation_period($bookid) {
        $wpestate_book_from = get_post_meta($bookid, 'booking_from_date', true);
        $wpestate_book_to = get_post_meta($bookid, 'booking_to_date', true);
        $listing_id = get_post_meta($bookid, 'booking_id', true);
        $wprentals_is_per_hour = wprentals_return_booking_type($listing_id);
        $reservation_array = get_post_meta($listing_id, 'booking_dates', true);
        if ($reservation_array == '') {
            $reservation_array = wpestate_get_booking_dates($listing_id);
        }


        $from_date = new DateTime($wpestate_book_from);
        $from_date_unix = $from_date->getTimestamp();

        $to_date = new DateTime($wpestate_book_to);
        $to_date_unix_check = $to_date->getTimestamp();


        $to_date_unix = $to_date->getTimestamp();



        // checking booking avalability
        while ($from_date_unix < $to_date_unix) {

            // print'check '. $from_date_unix.'</br>';
            if (array_key_exists($from_date_unix, $reservation_array)) {
                //  print '</br> iteration from date'.$from_date_unix. ' / ' .date("Y-m-d", $from_date_unix);
                print '<div class="create_invoice_form">';
                print esc_html__('It seems that this period was booked after you made the initial request and you cannot pay & finalize this request.', 'wprentals');
                print '</div>';
                return false;
            }
            if ($wprentals_is_per_hour == 2) {
                $from_date->modify('+1 hour');
            } else {
                $from_date->modify('tomorrow');
            }

            $from_date_unix = $from_date->getTimestamp();
        }
        return true;
    }

endif;










if (!function_exists('wpestate_show_extra_options_booking')):

    function wpestate_show_extra_options_booking($post_id) {
        $wpestate_currency = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $extra_pay_options = ( get_post_meta($post_id, 'extra_pay_options', true) );

        if (is_array($extra_pay_options)) {
            foreach ($extra_pay_options as $key => $extra_options) {
                print'<div class="cost_row cost_row_extra wpestate_show_extra_options_booking"  data-value_add="' . esc_attr($extra_options[1]) . '" data-value_how="' . esc_attr($extra_options[2]) . '" data-value_name="' . $extra_options[0] . '" >';
                print '<div class="cost_explanation"> <input type="checkbox" data-key="' . esc_attr($key) . '" class="form-control" value="1" name="checkbox"  /> ' . $extra_options[0] . ' </div>';
                print '<div class="cost_value"><div class="cost_value_show">' . wpestate_show_price_booking($extra_options[1], $wpestate_currency, $wpestate_where_currency, 1) . '</div> ' . wpestate_extra_options_exp($extra_options[2], $post_id) . ' </div>';
                print '</div>';
            }
            print'<div class="space_extra_opt"><input type="hidden" id="extra_options_key" value=""></input></div>';
        }
    }

endif;


if (!function_exists('wpestate_curency_submission_pick')):

    function wpestate_curency_submission_pick() {
        $submission_curency = esc_html(wprentals_get_option('wp_estate_submission_curency_custom', ''));
        if ($submission_curency == '') {
            $submission_curency = esc_html(wprentals_get_option('wp_estate_submission_curency', ''));
        }
        return $submission_curency;
    }

endif;


if (!function_exists('wpml_custom_price_adjust')):

    function wpml_custom_price_adjust($post_id) {
        $return = get_post_meta($post_id, 'custom_price', true);

        if (!$return) {
            $return = get_post_meta($post_id, 'custom_price' . $post_id, true);
        }

        return $return;
    }

endif;


if (!function_exists('wpml_mega_details_adjust')):

    function wpml_mega_details_adjust($post_id) {
        $return = get_post_meta($post_id, 'mega_details', true);


        if (!$return) {
            $return = get_post_meta($post_id, 'mega_details' . $post_id, true);
        }

        return $return;
    }

endif;


if (!function_exists('wpml_custom_price_adjust_save')):

    function wpml_custom_price_adjust_save($post_id, $price_array) {
        $old_custom = get_post_meta($post_id, 'custom_price' . $post_id, true);
        if ($old_custom != '') {
            update_post_meta($post_id, 'custom_price' . $post_id, $price_array);
        }
    }

endif;


if (!function_exists('wpml_mega_details_adjust_save')):

    function wpml_mega_details_adjust_save($post_id, $price_array) {
        $old_custom = get_post_meta($post_id, 'mega_details' . $post_id, true);
        if ($old_custom != '') {
            update_post_meta($post_id, 'mega_details' . $post_id, $price_array);
        }
    }

endif;


if (!function_exists('wpestate_the_excerpt_max_charlength')):

    function wpestate_the_excerpt_max_charlength($charlength) {
        $excerpt = get_the_excerpt();
        $charlength++;
        $return = '';

        if (mb_strlen($excerpt) > $charlength) {
            $subex = mb_substr($excerpt, 0, $charlength - 5);
            $exwords = explode(' ', $subex);
            $excut = - ( mb_strlen($exwords[count($exwords) - 1]) );
            if ($excut < 0) {
                $return = mb_substr($subex, 0, $excut);
            } else {
                $return = $subex;
            }
            $return .= '[...]';
        } else {
            $return = $excerpt;
        }
        return $return;
    }

endif;

if (!function_exists('wpestate_strip_words')):

    function wpestate_strip_words($text, $words_no) {
        $temp = explode(' ', $text, ($words_no + 1));
        if (count($temp) > $words_no) {
            array_pop($temp);
        }
        return implode(' ', $temp);
    }

endif; // end   wpestate_strip_words





if (!function_exists('wpestate_show_product_type')):

    function wpestate_show_product_type($item_id) {
        return get_the_title($item_id);
    }

endif;


if (!function_exists('wpestate_custom_vimdeo_video')):

    function wpestate_custom_vimdeo_video($video_id) {
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string = '
            <div style="max-width:100%;" class="video">
               <iframe id="player_1" src="' . $protocol . '://player.vimeo.com/video/' . esc_html($video_id) . '?api=1&amp;player_id=player_1"      allowFullScreen></iframe>
            </div>';
    }

endif; // end


if (!function_exists('wpestate_custom_youtube_video')):

    function wpestate_custom_youtube_video($video_id) {
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string = '
        <div style="max-width:100%;" class="video">
            <iframe id="player_2" title="YouTube video player" src="' . $protocol . '://www.youtube.com/embed/' . esc_html($video_id) . '?wmode=transparent&amp;rel=0"  allowfullscreen></iframe>
        </div>';
    }

endif; // end


if (!function_exists('wprentals_get_video_thumb')):

    function wprentals_get_video_thumb($post_id) {
        $video_id = esc_html(get_post_meta($post_id, 'embed_video_id', true));
        $video_type = esc_html(get_post_meta($post_id, 'embed_video_type', true));
        $protocol = is_ssl() ? 'https' : 'http';
        $video_thumb = '';
        if ($video_type == 'vimeo') {
            $hash2 = ( wp_remote_get($protocol . "://vimeo.com/api/v2/video/$video_id.php") );

            if ($hash2['response']['code'] != '404') {
                $pre_tumb = (unserialize($hash2['body']) );
                $video_thumb = $pre_tumb[0]['thumbnail_medium'];
            }
        } else {
            $video_thumb = $protocol . '://img.youtube.com/vi/' . $video_id . '/0.jpg';
        }
        return $video_thumb;
    }

endif;




if (!function_exists('wpestate_review_composer')):

    function wpestate_review_composer($agent_id) {
        global $post;
        $prop_no = intval(wprentals_get_option('wp_estate_prop_no', ''));
        $owner_id = get_post_meta($agent_id, 'user_agent_id', true);
        if ($owner_id == 0) {
            $return_array['list_rating'] = 0;
            $return_array['coments_no'] = 0;
            $return_array['prop_selection'] = '';
            $return_array['templates'] = '';
            return $return_array;
        }

        $post_array = array();
        $post_array[] = 0;
        $return_array = array();
        $paged = 1;

        if (isset($_GET['pagelist'])) {
            $paged = intval($_GET['pagelist']);
        }


        $args = array(
            'post_type' => 'estate_property',
            'author' => $owner_id,
            'paged' => $paged,
            'posts_per_page' => $prop_no,
            'post_status' => 'publish'
        );




        $prop_selection = new WP_Query($args);

        $return_array['prop_selection'] = $prop_selection;
        wp_reset_postdata();
        wp_reset_query();


        $arg2_reviews = array(
            'post_type' => 'estate_property',
            'author' => $owner_id,
            'paged' => 1,
            'posts_per_page' => 100,
            'post_status' => 'publish'
        );
        $prop_selection_Reviews = new WP_Query($arg2_reviews);
        if ($prop_selection_Reviews->have_posts()) {
            while ($prop_selection_Reviews->have_posts()):
                $prop_selection_Reviews->the_post();

                $post_array[] = $post->ID;
            endwhile;
            wp_reset_postdata();
            wp_reset_query();

            $args = array(
                'number' => '15',
                'post__in' => $post_array,
            );


            $comments = get_comments($args);
            $coments_no = 0;
            $stars_total = 0;
            $review_templates = '';

            foreach ($comments as $comment) :
                $coments_no++;
                $userId = $comment->user_id;
                $userid_agent = get_user_meta($userId, 'user_agent_id', true);
                $reviewer_name = get_the_title($userid_agent);
                if ($userid_agent == '') {
                    $reviewer_name = $comment->comment_author;
                }


                if ($userid_agent == '') {
                    $user_small_picture_id = get_the_author_meta('small_custom_picture', $comment->user_id, true);
                    $preview = wp_get_attachment_image_src($user_small_picture_id, 'wpestate_user_thumb');
                    $preview_img='';
                    if(isset($preview[0])){
                        $preview_img = $preview[0];
                    }
                } else {
                    $thumb_id = get_post_thumbnail_id($userid_agent);
                    $preview = wp_get_attachment_image_src($thumb_id, 'thumbnail');
                    $preview_img = $preview[0];
                }


                if ($preview_img == '') {
                    $preview_img = get_stylesheet_directory_uri() . '/img/default_user.png';
                }

                $rating = get_comment_meta($comment->comment_ID, 'review_stars', true);
                $tmp_rating = json_decode($rating, TRUE);
                $rating = wpestate_get_star_total_value($tmp_rating);

                $stars_total += $rating;
                $review_templates .= '
                    <div class="listing-review">

                        <div class="col-md-12 review-list-content norightpadding">
                            <div class="reviewer_image"  style="background-image: url(' . $preview_img . ');"></div>
                            <div class="reviwer-name">' . $reviewer_name . '</div>
                            <div class="property_ratings">';
                $review_templates .= wpestate_display_rating($rating);
                $review_templates .= ' <span class="ratings-star">(' . wpestate_get_star_total_value(wpestate_get_star_total_rating($rating)) . ' ' . esc_html__('of', 'wprentals') . ' 5)</span>
                            </div>


                            <div class="review-content">
                                ' . $comment->comment_content . '

                                <div class="review-date">
                                ' . esc_html__('Posted on ', 'wprentals') . ' ' . get_comment_date('j F Y', $comment->comment_ID) . '
                                </div>
                            </div>



                        </div>
                    </div>   ';

            endforeach;

            $return_array['templates'] = $review_templates;
            $list_rating = 0;
            if ($coments_no > 0) {
                $list_rating = ceil($stars_total / $coments_no);
            }


            $return_array['list_rating'] = $list_rating;
            $return_array['coments_no'] = $coments_no;
        }// if has listings





        return $return_array;
    }

endif;






/////////////////////////////////////////////////////////////////////////////////
// header type
///////////////////////////////////////////////////////////////////////////////////


if (!function_exists('wpestate_show_media_header')):

    function wpestate_show_media_header($tip, $wpestate_global_header_type, $wpestate_header_type, $rev_slider, $custom_image) {

        $page_template='';
        if(isset($post->ID)){
            $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        }

        if ($page_template=='splash_page.php' ) {
            $wpestate_header_type = 20;
        } else

        if ($tip == 'global') {
            
            switch ($wpestate_global_header_type) {
                case 0://none
                    break;
                case 1://image
                    $global_header = wprentals_get_option('wp_estate_global_header', 'url');
                    if (is_tax()) {
                        $global_header = wprentals_get_option('wp_estate_header_taxonomy_image', 'url');
                    } else if (is_singular('estate_agent')) {
                        $global_header = wprentals_get_option('wp_estate_global_header_image_user', 'url');
                    }else if( is_singular('post') ){
                         $global_header = wprentals_get_option('wp_estate_header_single_post_image', 'url');
                    }


                    wpestate_header_image($global_header);
                    break;
                case 2://theme slider
                    wpestate_present_theme_slider();
                    break;
                case 3://revolutin slider
                    if (function_exists('putRevSlider')) {
                        $global_revolution_slider = wprentals_get_option('wp_estate_global_revolution_slider', '');
                        if (is_tax()) {
                            $global_revolution_slider = wprentals_get_option('wp_estate_header_taxonomy_revolution_slider', 'url');
                        } else if (is_singular('estate_agent')) {
                            $global_revolution_slider = wprentals_get_option('wp_estate_global_revolution_slider_user', '');
                        } else if( is_singular('post') ){
                         $global_header = wprentals_get_option('wp_estate_header_single_post_revolution_slider', 'url');
                        }
                        putRevSlider($global_revolution_slider);
                    }
                    break;
                case 4://google maps
                    include(locate_template('templates/google_maps_base.php') );
                    break;
                case 20:
                    wpestate_splash_page_header();
                    break;
            }
        } else { // is local
            switch ($wpestate_header_type) {
                case 1://none
                    break;
                case 2://image
                    wpestate_header_image($custom_image);
                    break;
                case 3://theme slider
                    wpestate_present_theme_slider();
                    break;
                case 4://revolutin slider
                    if (function_exists('putRevSlider')) {
                        putRevSlider($rev_slider);
                    }
                    break;
                case 5://google maps
                    include(locate_template('templates/google_maps_base.php') );
                    break;
                case 6:
                    wpestate_video_header();
                    break;

                case 20:
                    wpestate_splash_page_header();
                    break;
            }
        }
    }

endif;

function wprentals_increase_time_unit($wprentals_is_per_hour, $from_date) {
    if ($wprentals_is_per_hour == 2) {
        $from_date->modify('+1 hour');
    } else {
        $from_date->modify('tomorrow');
    }
    return $from_date;
}

function wprentals_compute_no_of_hours($start_date, $end_date, $listing_id) {
    $booking_start_hour_string = get_post_meta($listing_id, 'booking_start_hour', true);
    $booking_end_hour_string = get_post_meta($listing_id, 'booking_end_hour', true);
    $booking_start_hour = intval($booking_start_hour_string);
    $booking_end_hour = intval($booking_end_hour_string);

    $hour_count = 0;
    $from_date = new DateTime($start_date);
    $to_date = new DateTime($end_date);

    while ($from_date < $to_date) {
        $from_date->modify('+1 hour');
        $from_date_unix = $from_date->getTimestamp();
        $current_hour = $from_date->format('H');

        if ($booking_start_hour_string == '' && $booking_end_hour_string == '') {
            $hour_count++;
        } else {
            if ($booking_end_hour > $current_hour && $booking_start_hour <= $current_hour) {
                $hour_count++;
            }
        }
    }
    return $hour_count;
}

if (!function_exists('wprentals_return_standart_days_period')):

    function wprentals_return_standart_days_period() {
        $return = array();

        $week_days_no = intval(wprentals_get_option('wp_estate_week_days'));
        if ($week_days_no == 0) {
            $week_days_no = 7;
        }

        $month_days_no = intval(wprentals_get_option('wp_estate_month_days'));
        if ($month_days_no == 0) {
            $month_days_no == 30;
        }

        $return['week_days'] = $week_days_no;
        $return['month_days'] = $month_days_no;

        return $return;
    }

endif;

/////////////////////////////////////////////////////////////////////////////////
// datepcker_translate
///////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_booking_price')):

    function wpestate_booking_price($curent_guest_no, $invoice_id, $property_id, $from_date, $to_date, $bookid = '', $extra_options_array = '', $manual_expenses = '') {

        $wprentals_is_per_hour = wprentals_return_booking_type($property_id);


        $price_array = wpml_custom_price_adjust($property_id);
        $mega = wpml_mega_details_adjust($property_id);

        $cleaning_fee_per_day = floatval(get_post_meta($property_id, 'cleaning_fee_per_day', true));
        $city_fee_per_day = floatval(get_post_meta($property_id, 'city_fee_per_day', true));
        $price_per_weekeend = floatval(get_post_meta($property_id, 'price_per_weekeend', true));
        $setup_weekend_status = esc_html(wprentals_get_option('wp_estate_setup_weekend', ''));
        $include_expeses = esc_html(wprentals_get_option('wp_estate_include_expenses', ''));
        $booking_from_date = $from_date;
        $booking_to_date = $to_date;
        $total_guests = floatval(get_post_meta($bookid, 'booking_guests', true));

        $classic_period_days = wprentals_return_standart_days_period();




        $numberDays = 1;
        if ($invoice_id == 0) {
            $price_per_day = floatval(get_post_meta($property_id, 'property_price', true));
            $week_price = floatval(get_post_meta($property_id, 'property_price_per_week', true));
            $month_price = floatval(get_post_meta($property_id, 'property_price_per_month', true));
            $cleaning_fee = floatval(get_post_meta($property_id, 'cleaning_fee', true));
            $city_fee = floatval(get_post_meta($property_id, 'city_fee', true));
            $cleaning_fee_per_day = floatval(get_post_meta($property_id, 'cleaning_fee_per_day', true));
            $city_fee_per_day = floatval(get_post_meta($property_id, 'city_fee_per_day', true));
            $city_fee_percent = floatval(get_post_meta($property_id, 'city_fee_percent', true));
            $security_deposit = floatval(get_post_meta($property_id, 'security_deposit', true));
            $early_bird_percent = floatval(get_post_meta($property_id, 'early_bird_percent', true));
            $early_bird_days = floatval(get_post_meta($property_id, 'early_bird_days', true));
        } else {
            $price_per_day = floatval(get_post_meta($invoice_id, 'default_price', true));
            $week_price = floatval(get_post_meta($invoice_id, 'week_price', true));
            $month_price = floatval(get_post_meta($invoice_id, 'month_price', true));
            $cleaning_fee = floatval(get_post_meta($invoice_id, 'cleaning_fee', true));
            $city_fee = floatval(get_post_meta($invoice_id, 'city_fee', true));
            $cleaning_fee_per_day = floatval(get_post_meta($invoice_id, 'cleaning_fee_per_day', true));
            $city_fee_per_day = floatval(get_post_meta($invoice_id, 'city_fee_per_day', true));
            $city_fee_percent = floatval(get_post_meta($invoice_id, 'city_fee_percent', true));
            $security_deposit = floatval(get_post_meta($invoice_id, 'security_deposit', true));
            $early_bird_percent = floatval(get_post_meta($invoice_id, 'early_bird_percent', true));
            $early_bird_days = floatval(get_post_meta($invoice_id, 'early_bird_days', true));
        }



        $from_date = new DateTime($booking_from_date);
        $from_date_unix = $from_date->getTimestamp();
        $date_checker = strtotime(date("Y-m-d 00:00", $from_date_unix));
        $from_date_discount = $from_date->getTimestamp();
        $to_date = new DateTime($booking_to_date);
        $to_date_unix = $to_date->getTimestamp();
        $total_price = 0;
        $inter_price = 0;
        $has_custom = 0;
        $usable_price = 0;
        $has_wkend_price = 0;
        $cover_weekend = 0;
        $custom_period_quest = 0;

        $custom_price_array = array();
        $timeDiff = abs(strtotime($booking_to_date) - strtotime($booking_from_date));
        if ($wprentals_is_per_hour == 2) {
            //per h
            $count_days = wprentals_compute_no_of_hours($booking_from_date, $booking_to_date, $property_id);
        } else {
            //per day
            $count_days = $timeDiff / 86400;  // 86400 seconds in one day
        }

        $count_days = intval($count_days);

        //check extra price per guest
        ///////////////////////////////////////////////////////////////////////////
        $extra_price_per_guest = floatval(get_post_meta($property_id, 'extra_price_per_guest', true));
        $price_per_guest_from_one = floatval(get_post_meta($property_id, 'price_per_guest_from_one', true));
        $overload_guest = floatval(get_post_meta($property_id, 'overload_guest', true));
        $guestnumber = floatval(get_post_meta($property_id, 'guest_no', true));

        $booking_start_hour_string = get_post_meta($property_id, 'booking_start_hour', true);
        $booking_end_hour_string = get_post_meta($property_id, 'booking_end_hour', true);
        $booking_start_hour = intval($booking_start_hour_string);
        $booking_end_hour = intval($booking_end_hour_string);


        $has_guest_overload = 0;
        $total_extra_price_per_guest = 0;
        $extra_guests = 0;








        if ($price_per_guest_from_one == 0) {
            ///////////////////////////////////////////////////////////////
            //  per day math
            ////////////////////////////////////////////////////////////////
            //period_price_per_month,period_price_per_week
            //discoutn prices for month and week
            ///////////////////////////////////////////////////////////////////////////
            if ($count_days >= $classic_period_days['week_days'] && $week_price != 0) { // if more than 7 days booked
                $price_per_day = $week_price;
            }

            if ($count_days >= $classic_period_days['month_days'] && $month_price != 0) {
                $price_per_day = $month_price;
            }

            //custom prices - check the first day
            ///////////////////////////////////////////////////////////////////////////
            if (isset($price_array[$date_checker])) {
                $has_custom = 1;
                $custom_price_array [$date_checker] = $price_array[$date_checker];
            }

            if (isset($mega[$date_checker]) && isset($mega[$date_checker]['period_price_per_weekeend']) && $mega[$date_checker]['period_price_per_weekeend'] != 0) {
                $has_wkend_price = 1;
            }

            if ($overload_guest == 1) {  // if we allow overload
                if ($curent_guest_no > $guestnumber) {
                    $has_guest_overload = 1;
                    $extra_guests = $curent_guest_no - $guestnumber;
                    if (isset($mega[$date_checker]) && isset($mega[$date_checker]['period_price_per_weekeend'])) {
                        $total_extra_price_per_guest = $total_extra_price_per_guest + $extra_guests * $mega[$date_checker]['period_extra_price_per_guest'];
                        $custom_period_quest = 1;
                    } else {
                        $total_extra_price_per_guest = $total_extra_price_per_guest + $extra_guests * $extra_price_per_guest;
                    }
                }
            }

            if ($price_per_weekeend != 0) {
                $has_wkend_price = 1;
            }

            $usable_price = wpestate_return_custom_price($date_checker, $mega, $price_per_weekeend, $price_array, $price_per_day, $count_days);
            $total_price = $total_price + $usable_price;

            $inter_price = $inter_price + $usable_price;
            $custom_price_array [$date_checker] = $usable_price;

            $from_date_unix_first_day = $from_date->getTimestamp();




            $from_date = wprentals_increase_time_unit($wprentals_is_per_hour, $from_date);
            $from_date_unix = $from_date->getTimestamp();
            $date_checker = strtotime(date("Y-m-d 00:00", $from_date_unix));
            $weekday = date('N', $from_date_unix_first_day); // 1-7
            if (wpestate_is_cover_weekend($weekday, $has_wkend_price, $setup_weekend_status)) {
                $cover_weekend = 1;
            }


            // loop trough the dates
            //////////////////////////////////////////////////////////////////////////
            while ($from_date_unix < $to_date_unix) {

                $skip_a_beat = 1;
                if ($wprentals_is_per_hour == 2) { //is per h
                    $current_hour = $from_date->format('H');

                    if ($booking_start_hour_string == '' && $booking_end_hour_string == '') {
                        $skip_a_beat = 1;
                    } else {
                        if ($booking_end_hour > $current_hour && $booking_start_hour <= $current_hour) {
                            $skip_a_beat = 1;
                        } else {
                            $skip_a_beat = 0;
                        }
                    }
                }




                if ($skip_a_beat == 1) {
                    $numberDays++;

                    if (isset($price_array[$date_checker])) {
                        $has_custom = 1;
                    }

                    if (isset($mega[$date_checker]) && isset($mega[$date_checker]['period_price_per_weekeend']) && $mega[$date_checker]['period_price_per_weekeend'] != 0) {
                        $has_wkend_price = 1;
                    }

                    if ($overload_guest == 1) {  // if we allow overload
                        if ($curent_guest_no > $guestnumber) {
                            $has_guest_overload = 1;
                            $extra_guests = $curent_guest_no - $guestnumber;
                            if (isset($mega[$date_checker]) && isset($mega[$date_checker]['period_price_per_weekeend'])) {
                                $total_extra_price_per_guest = $total_extra_price_per_guest + $extra_guests * $mega[$date_checker]['period_extra_price_per_guest'];
                                $custom_period_quest = 1;
                            } else {
                                $total_extra_price_per_guest = $total_extra_price_per_guest + $extra_guests * $extra_price_per_guest;
                            }
                        }
                    }

                    if ($price_per_weekeend != 0) {
                        $has_wkend_price = 1;
                    }


                    $weekday = date('N', $from_date_unix); // 1-7
                    if (wpestate_is_cover_weekend($weekday, $has_wkend_price, $setup_weekend_status)) {
                        $cover_weekend = 1;
                    }

                    $usable_price = wpestate_return_custom_price($date_checker, $mega, $price_per_weekeend, $price_array, $price_per_day, $count_days);
                    $total_price = $total_price + $usable_price;

                    $inter_price = $inter_price + $usable_price;
                    $custom_price_array [$date_checker] = $usable_price;
                }//end skip a beat
                $from_date = wprentals_increase_time_unit($wprentals_is_per_hour, $from_date);
                $from_date_unix = $from_date->getTimestamp();
                $date_checker = strtotime(date("Y-m-d 00:00", $from_date_unix));
            }
        } else {
            $custom_period_quest = 0;

            ///////////////////////////////////////////////////////////////
            //  per guest math
            ////////////////////////////////////////////////////////////////

            if (isset($mega[$date_checker]['period_extra_price_per_guest'])) {

                $total_price = $curent_guest_no * $mega[$date_checker]['period_extra_price_per_guest'];
                $inter_price = $curent_guest_no * $mega[$date_checker]['period_extra_price_per_guest'];
                $custom_price_array [$date_checker] = $curent_guest_no * $mega[$date_checker]['period_extra_price_per_guest'];
                $custom_period_quest = 1;
            } else {

                $total_price = $curent_guest_no * $extra_price_per_guest;
                $inter_price = $curent_guest_no * $extra_price_per_guest;
            }



            $from_date = wprentals_increase_time_unit($wprentals_is_per_hour, $from_date);
            $from_date_unix = $from_date->getTimestamp();
            $date_checker = strtotime(date("Y-m-d 00:00", $from_date_unix));





            while ($from_date_unix < $to_date_unix) {
                $skip_a_beat = 1;
                if ($wprentals_is_per_hour == 2) { //is per h
                    $current_hour = $from_date->format('H');

                    if ($booking_start_hour_string == '' && $booking_end_hour_string == '') {
                        $skip_a_beat = 1;
                    } else {
                        if ($booking_end_hour > $current_hour && $booking_start_hour <= $current_hour) {
                            $skip_a_beat = 1;
                        } else {
                            $skip_a_beat = 0;
                        }
                    }
                }

                if ($skip_a_beat == 1) {
                    $numberDays++;



                    if (isset($mega[$date_checker]['period_extra_price_per_guest'])) {
                        $total_price = $total_price + $curent_guest_no * $mega[$date_checker]['period_extra_price_per_guest'];
                        $inter_price = $inter_price + $curent_guest_no * $mega[$date_checker]['period_extra_price_per_guest'];
                        $custom_price_array [$date_checker] = $curent_guest_no * $mega[$date_checker]['period_extra_price_per_guest'];


                        $custom_period_quest = 1;
                    } else {
                        $total_price = $total_price + $curent_guest_no * $extra_price_per_guest;
                        $inter_price = $inter_price + $curent_guest_no * $extra_price_per_guest;
                    }
                }



                $from_date = wprentals_increase_time_unit($wprentals_is_per_hour, $from_date);
                $from_date_unix = $from_date->getTimestamp();

                if ($wprentals_is_per_hour != 2) {
                    $date_checker = $from_date->getTimestamp();
                }
            }
        }// end per guest math

        $wp_estate_book_down = floatval(wprentals_get_option('wp_estate_book_down', ''));
        $wp_estate_book_down_fixed_fee = floatval(wprentals_get_option('wp_estate_book_down_fixed_fee', ''));



        if (!empty($extra_options_array)) {
            $extra_pay_options = ( get_post_meta($property_id, 'extra_pay_options', true) );

            foreach ($extra_options_array as $key => $value) {
                if (isset($extra_pay_options[$value][0])) {
                    $extra_option_value = wpestate_calculate_extra_options_value($count_days, $total_guests, $extra_pay_options[$value][2], $extra_pay_options[$value][1]);
                    $total_price = $total_price + $extra_option_value;
                }
            }
        }



        if (!empty($manual_expenses) && is_array($manual_expenses)) {
            foreach ($manual_expenses as $key => $value) {
                if (floatval($value[1]) != 0) {
                    $total_price = $total_price + floatval($value[1]);
                }
            }
        }

        // extra price per guest
        if ($has_guest_overload == 1 && $total_extra_price_per_guest > 0) {
            $total_price = $total_price + $total_extra_price_per_guest;
        }




        //early bird discount
        ///////////////////////////////////////////////////////////////////////////
        $early_bird_discount = wpestate_early_bird($property_id, $early_bird_percent, $early_bird_days, $from_date_discount, $total_price);

        if ($early_bird_discount > 0) {
            $total_price = $total_price - $early_bird_discount;
        }





        //security depozit - refundable
        ///////////////////////////////////////////////////////////////////////////
        if (intval($security_deposit) != 0) {
            $total_price = $total_price + $security_deposit;
        }

        $total_price_before_extra = $total_price;

        //cleaning or city fee per day
        ///////////////////////////////////////////////////////////////////////////

        $cleaning_fee = wpestate_calculate_cleaning_fee($property_id, $count_days, $curent_guest_no, $cleaning_fee, $cleaning_fee_per_day);
        $city_fee = wpestate_calculate_city_fee($property_id, $count_days, $curent_guest_no, $city_fee, $city_fee_per_day, $city_fee_percent, $inter_price);

        if ($cleaning_fee != 0 && $cleaning_fee != '') {
            $total_price = $total_price + $cleaning_fee;
        }

        if ($city_fee != 0 && $city_fee != '') {
            $total_price = $total_price + $city_fee;
        }


        if ($invoice_id == 0) {
            $price_for_service_fee = $total_price - $security_deposit - floatval($city_fee) - floatval($cleaning_fee);
            $service_fee = wpestate_calculate_service_fee($price_for_service_fee, $invoice_id);
        } else {
            $service_fee = get_post_meta($invoice_id, 'service_fee', true);
        }






        if ($include_expeses == 'yes') {
            $deposit = wpestate_calculate_deposit($wp_estate_book_down, $wp_estate_book_down_fixed_fee, $total_price);
        } else {
            $deposit = wpestate_calculate_deposit($wp_estate_book_down, $wp_estate_book_down_fixed_fee, $total_price_before_extra);
        }


        if (intval($invoice_id) == 0) {
            $you_earn = $total_price - $security_deposit - floatval($city_fee) - floatval($cleaning_fee) - $service_fee;
            update_post_meta($bookid, 'you_earn', $you_earn);
        } else {
            $you_earn = get_post_meta($bookid, 'you_earn', true);
        }



        $taxes = 0;

        if (intval($invoice_id) == 0) {
            $taxes_value = floatval(get_post_meta($property_id, 'property_taxes', true));
        } else {
            $taxes_value = floatval(get_post_meta($invoice_id, 'prop_taxed', true));
        }
        if ($taxes_value > 0) {
            $taxes = round($you_earn * $taxes_value / 100, 2);
        }


        if (intval($invoice_id) == 0) {
            update_post_meta($bookid, 'custom_price_array', $custom_price_array);
        } else {
            $custom_price_array = get_post_meta($bookid, 'custom_price_array', true);
        }

        $balance = $total_price - $deposit;
        $return_array = array();
        $return_array['book_type'] = $wprentals_is_per_hour;
        $return_array['default_price'] = $price_per_day;
        $return_array['week_price'] = $week_price;
        $return_array['month_price'] = $month_price;
        $return_array['total_price'] = $total_price;
        $return_array['inter_price'] = $inter_price;
        $return_array['balance'] = $balance;
        $return_array['deposit'] = $deposit;
        $return_array['from_date'] = $from_date;
        $return_array['to_date'] = $to_date;
        $return_array['cleaning_fee'] = $cleaning_fee;
        $return_array['city_fee'] = $city_fee;
        $return_array['has_custom'] = $has_custom;
        $return_array['custom_price_array'] = $custom_price_array;
        $return_array['numberDays'] = $numberDays;
        $return_array['count_days'] = $count_days;
        $return_array['has_wkend_price'] = $has_wkend_price;
        $return_array['has_guest_overload'] = $has_guest_overload;
        $return_array['total_extra_price_per_guest'] = $total_extra_price_per_guest;
        $return_array['extra_guests'] = $extra_guests;
        $return_array['extra_price_per_guest'] = $extra_price_per_guest;
        $return_array['price_per_guest_from_one'] = $price_per_guest_from_one;
        $return_array['curent_guest_no'] = $curent_guest_no;
        $return_array['cover_weekend'] = $cover_weekend;
        $return_array['custom_period_quest'] = $custom_period_quest;
        $return_array['security_deposit'] = $security_deposit;
        $return_array['early_bird_discount'] = $early_bird_discount;
        $return_array['taxes'] = $taxes;
        $return_array['service_fee'] = $service_fee;
        $return_array['youearned'] = $you_earn;
        return $return_array;
    }

endif;



if (!function_exists('wpestate_extra_options_exp')):

    function wpestate_extra_options_exp($extra_id, $edit_id = '') {
        $booking_type = wprentals_return_booking_type($edit_id);
        $rental_type = wprentals_get_option('wp_estate_item_rental_type', true);
        $options_array = array(
            0 => esc_html__('Single Fee', 'wprentals'),
            1 => ucfirst(wpestate_show_labels('per_night', $rental_type, $booking_type)),
            2 => esc_html__('Per Guest', 'wprentals'),
            3 => ucfirst(wpestate_show_labels('per_night', $rental_type, $booking_type)) . ' ' . esc_html__('per Guest', 'wprentals')
        );

        return $options_array[$extra_id];
    }

endif;


if (!function_exists('wpestate_early_bird')):

    function wpestate_early_bird($property_id, $early_bird_percent, $early_bird_days, $from_date_discount, $total_price) {

        $day_diffrence = ( $from_date_discount - time() ) / 60 / 60 / 24;
        $discount = 0;
        if ($day_diffrence >= $early_bird_days) {
            $discount = ( $total_price * $early_bird_percent ) / 100;
        }

        return $discount;
    }

endif;


if (!function_exists('wpestate_calculate_cleaning_fee')):

    function wpestate_calculate_cleaning_fee($property_id, $count_days, $guests_no, $cleaning_fee, $cleaning_fee_per_day) {
        $return_value = 0;

        $guests_no = intval($guests_no);
        if ($guests_no == 0) {
            $guests_no = 1;
        }
        switch ($cleaning_fee_per_day) {
            case 0:// single fee
                $return_value = $cleaning_fee;
                break;
            case 1://per night
                $return_value = $cleaning_fee * $count_days;
                break;
            case 2://per guest
                $return_value = $cleaning_fee * $guests_no;
                break;
            case 3://per guest and night
                $return_value = $cleaning_fee * $guests_no * $count_days;
                break;
        }
        return $return_value;
    }

endif;

if (!function_exists('wpestate_calculate_city_fee')):

    function wpestate_calculate_city_fee($property_id, $count_days, $guests_no, $city_fee, $city_fee_per_day, $city_fee_percent, $inter_fee) {
        $return_value = 0;
        $guests_no = intval($guests_no);
        if ($guests_no == 0) {
            $guests_no = 1;
        }
        if ($city_fee_percent == 0) {

            switch ($city_fee_per_day) {
                case 0:// single fee
                    $return_value = $city_fee;
                    break;
                case 1://per night
                    $return_value = $city_fee * $count_days;
                    break;
                case 2://per guest
                    $return_value = $city_fee * $guests_no;
                    break;
                case 3://per guest and night
                    $return_value = $city_fee * $guests_no * $count_days;
                    break;
            }
        } else {
            $return_value = $inter_fee * $city_fee / 100;
        }



        return $return_value;
    }

endif;




if (!function_exists('wpestate_is_cover_weekend')):

    function wpestate_is_cover_weekend($weekday, $has_wkend_price, $setup_weekend_status) {
        if ($setup_weekend_status == 0 && ( $weekday == 6 || $weekday == 7) && $has_wkend_price == 1) {
            return true;
        } else if ($setup_weekend_status == 1 && ( $weekday == 5 || $weekday == 6) && $has_wkend_price == 1) {
            return true;
        } else if ($setup_weekend_status == 2 && ( $weekday == 5 || $weekday == 6 || $weekday == 7) && $has_wkend_price == 1) {
            return true;
        } else {
            return false;
        }
        return false;
    }

endif;






if (!function_exists('wpestate_calculate_deposit')):

    function wpestate_calculate_deposit($wp_estate_book_down, $wp_estate_book_down_fixed_fee, $total_price) {

        if ($wp_estate_book_down_fixed_fee == 0) {

            if ($wp_estate_book_down == '' || $wp_estate_book_down == 0) {
                $deposit = 0;
            } else {

                $deposit = floatval($wp_estate_book_down * $total_price / 100);

                $deposit = round($deposit, 2);
            }
        } else {
            $deposit = $wp_estate_book_down_fixed_fee;
        }
        return $deposit;
    }

endif;


if (!function_exists('wpestate_calculate_weekedn_price')):

    function wpestate_calculate_weekedn_price($mega, $from_date_unix, $price_per_weekeend, $price_per_day, $price_array, $count_days) {
        $new_price = '';
        if (isset($mega[$from_date_unix]) && isset($mega[$from_date_unix]['period_price_per_weekeend']) && $mega[$from_date_unix]['period_price_per_weekeend'] != 0) {
            $new_price = $mega[$from_date_unix]['period_price_per_weekeend'];
        } else if ($price_per_weekeend != 0) {
            $new_price = $price_per_weekeend;
        } else {
            $new_price = wpestate_classic_price_return($price_per_day, $price_array, $from_date_unix, $count_days, $mega);
        }
        return $new_price;
    }

endif;



if (!function_exists('wpestate_return_custom_price')):

    function wpestate_return_custom_price($from_date_unix, $mega, $price_per_weekeend, $price_array, $price_per_day, $count_days) {
        $weekday = date('N', $from_date_unix);
        $setup_weekend_status = esc_html(wprentals_get_option('wp_estate_setup_weekend', ''));

        if ($setup_weekend_status == 0 && ( $weekday == 6 || $weekday == 7)) {
            $new_price = wpestate_calculate_weekedn_price($mega, $from_date_unix, $price_per_weekeend, $price_per_day, $price_array, $count_days);
        } else if ($setup_weekend_status == 1 && ( $weekday == 5 || $weekday == 6)) {
            $new_price = wpestate_calculate_weekedn_price($mega, $from_date_unix, $price_per_weekeend, $price_per_day, $price_array, $count_days);
        } else if ($setup_weekend_status == 2 && ( $weekday == 5 || $weekday == 6 || $weekday == 7)) {
            $new_price = wpestate_calculate_weekedn_price($mega, $from_date_unix, $price_per_weekeend, $price_per_day, $price_array, $count_days);
        } else {
            $new_price = wpestate_classic_price_return($price_per_day, $price_array, $from_date_unix, $count_days, $mega);
        }
        return $new_price;
    }

endif;



if (!function_exists('wpestate_classic_price_return')):

    function wpestate_classic_price_return($price_per_day, $price_array, $from_date_unix, $count_days, $mega) {

        $classic_period_days = wprentals_return_standart_days_period();

        if ($count_days >= $classic_period_days['week_days'] && $count_days < $classic_period_days['month_days'] &&
                isset($mega[$from_date_unix]['period_price_per_week']) && $mega[$from_date_unix]['period_price_per_week'] != 0) {

            return $mega[$from_date_unix]['period_price_per_week'];
        } else if ($count_days >= $classic_period_days['month_days'] && isset($mega[$from_date_unix]['period_price_per_month']) && $mega[$from_date_unix]['period_price_per_month'] != 0) {
            return $mega[$from_date_unix]['period_price_per_month'];
        } else if (isset($price_array[$from_date_unix])) {
            return $price_array[$from_date_unix];
        } else {
            return $price_per_day;
        }
    }

endif;






/////////////////////////////////////////////////////////////////////////////////
// datepcker_translate
///////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_date_picker_translation')):

    function wpestate_date_picker_translation($selector) {

        if ($selector !== 'check_in' && $selector !== 'check_out') {
            $date_lang_status = esc_html(wprentals_get_option('wp_estate_date_lang', ''));
            $dates_types = array(
                '0' => 'yy-mm-dd',
                '1' => 'yy-dd-mm',
                '2' => 'dd-mm-yy',
                '3' => 'mm-dd-yy',
                '4' => 'dd-yy-mm',
                '5' => 'mm-yy-dd',
            );

//        print '<script type="text/javascript">
//                    //<![CDATA[
//                    jQuery(document).ready(function(){
//                            jQuery("#'.$selector.'").datepicker({
//                                    dateFormat : "'.$dates_types[esc_html ( wprentals_get_option('wp_estate_date_format','') )].'"
//                            },jQuery.datepicker.regional["'.$date_lang_status.'"]).datepicker("widget").wrap(\'<div class="ll-skin-melon"/>\');
//                    });
//                    //]]>
//            </script>';
        }
    }

endif;

/////////////////////////////////////////////////////////////////////////////////
// show price
///////////////////////////////////////////////////////////////////////////////////
if (!function_exists('westate_display_corection')):

    function westate_display_corection($price) {
        $whole = floor($price);      // 1
        $fraction = $price - $whole;

        if ($fraction == 0) {
            $price = floatval($price);
        }
        return $price;
    }

endif;

if (!function_exists('wpestate_show_price')):

    function wpestate_show_price($post_id, $wpestate_currency, $wpestate_where_currency, $return = 0) {

        $price_label = '<span class="price_label">' . esc_html(get_post_meta($post_id, 'property_label', true)) . '</span>';
        $property_price_before_label = esc_html(get_post_meta($post_id, 'property_price_before_label', true));
        $property_price_after_label = esc_html(get_post_meta($post_id, 'property_price_after_label', true));

        $price_label = '';
        $price_per_guest_from_one = floatval(get_post_meta($post_id, 'price_per_guest_from_one', true));

        if ($price_per_guest_from_one == 1) {
            $price = floatval(get_post_meta($post_id, 'extra_price_per_guest', true));
        } else {
            $price = floatval(get_post_meta($post_id, 'property_price', true));
        }

        $th_separator = wprentals_get_option('wp_estate_prices_th_separator', '');
        $custom_fields = wprentals_get_option('wpestate_currency', '');



        if (!empty($custom_fields) && isset($_COOKIE['my_custom_curr']) && isset($_COOKIE['my_custom_curr_pos']) && isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos'] != -1) {
            $i = floatval($_COOKIE['my_custom_curr_pos']);
            $custom_fields = wprentals_get_option('wpestate_currency', '');
            if ($price != 0) {
                $price = $price * floatval($custom_fields[$i][2]);

                $price = number_format($price, 2, '.', $th_separator);
                $price = wpestate_TrimTrailingZeroes($price);


                $wpestate_currency = $custom_fields[$i][1];

                if ($custom_fields[$i][3] == 'before') {
                    $price = $wpestate_currency . ' ' . $price;
                } else {
                    $price = $price . ' ' . $wpestate_currency;
                }
            } else {
                $price = '';
            }
        } else {
            if ($price != 0) {
                //$price      = westate_display_corection($price);
                $price = number_format($price, 2, '.', $th_separator);
                $price = wpestate_TrimTrailingZeroes($price);
                if ($wpestate_where_currency == 'before') {
                    $price = $wpestate_currency . ' ' . $price;
                } else {
                    $price = $price . ' ' . $wpestate_currency;
                }
            } else {
                $price = '';
            }
        }



        if ($return == 0) {
            print trim($property_price_before_label . ' ' . $price . ' ' . $price_label . $property_price_after_label);
        } else {
            return trim($property_price_before_label . ' ' . $price . ' ' . $price_label . $property_price_after_label);
        }
    }

endif;


if (!function_exists('wpestate_TrimTrailingZeroes')):

    function wpestate_TrimTrailingZeroes($nbr) {
        if (strpos($nbr, '.') !== false) {
            return rtrim(rtrim($nbr, '0'), '.');
        } else {
            return $nbr;
        }
    }

endif;


/////////////////////////////////////////////////////////////////////////////////
// show price custom
///////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_show_price_custom')):

    function wpestate_show_price_custom($price) {
        $price_label = '';
        $wpestate_currency = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $th_separator = wprentals_get_option('wp_estate_prices_th_separator', '');
        $custom_fields = wprentals_get_option('wpestate_currency', '');

        if ($price != 0) {
            //$price  = westate_display_corection($price);
            $price = number_format(floatval($price), 2, '.', $th_separator);
            $price = wpestate_TrimTrailingZeroes($price);
            if ($wpestate_where_currency == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }
        } else {
            $price = '';
        }


        return $price . ' ' . $price_label;
    }

endif;







if (!function_exists('wpestate_show_price_custom_invoice')):

    function wpestate_show_price_custom_invoice($price) {
        $price_label = '';
        $wpestate_currency = wpestate_curency_submission_pick();
        $wpestate_where_currency = esc_html(get_option('wp_estate_where_currency_symbol', ''));
        $th_separator = wprentals_get_option('wp_estate_prices_th_separator', '');
        $custom_fields = wprentals_get_option('wpestate_currency', '');

        if ($price != 0) {
            //$price  = westate_display_corection($price);
            $price = number_format($price, 2, '.', $th_separator);
            $price = wpestate_TrimTrailingZeroes($price);
            if ($wpestate_where_currency == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }
        } else {
            $price = '';
        }


        return $price . ' ' . $price_label;
    }

endif;

/////////////////////////////////////////////////////////////////////////////////
// show price booking
///////////////////////////////////////////////////////////////////////////////////


if (!function_exists('wpestate_show_price_booking')):

    function wpestate_show_price_booking($price, $wpestate_currency, $wpestate_where_currency, $return = 0) {
        $price_label = '';
        $th_separator = wprentals_get_option('wp_estate_prices_th_separator', '');
        $custom_fields = wprentals_get_option('wpestate_currency', '');

        if (!empty($custom_fields) && isset($_COOKIE['my_custom_curr']) && isset($_COOKIE['my_custom_curr_pos']) && isset($_COOKIE['my_custom_curr_symbol']) && $_COOKIE['my_custom_curr_pos'] != -1) {
            $i = intval($_COOKIE['my_custom_curr_pos']);
            $custom_fields = wprentals_get_option('wpestate_currency', '');
            if ($price != 0) {
                $price = $price * $custom_fields[$i][2];
                $price = number_format(floatval($price), 2, '.', $th_separator);
                $price = wpestate_TrimTrailingZeroes($price);
                $wpestate_currency = $custom_fields[$i][1];

                if ($custom_fields[$i][3] == 'before') {
                    $price = $wpestate_currency . ' ' . $price;
                } else {
                    $price = $price . ' ' . $wpestate_currency;
                }
            } else {
                $price = '';
            }
        } else {
            if ($price != 0) {
                //$price      = westate_display_corection($price);
                $price = ( number_format(floatval($price), 2, '.', $th_separator) );
                $price = wpestate_TrimTrailingZeroes($price);

                if ($wpestate_where_currency == 'before') {
                    $price = $wpestate_currency . ' ' . $price;
                } else {
                    $price = $price . ' ' . $wpestate_currency;
                }
            } else {
                $price = '';
            }
        }


        if ($return == 0) {
            print trim($price . ' ' . $price_label);
        } else {
            return trim($price . ' ' . $price_label);
        }
    }

endif;




//////////////////////////////////////////////////////////////////////////////////////
// show price bookign for invoice - 1 currency only
///////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_show_price_booking_for_invoice')):

    function wpestate_show_price_booking_for_invoice($price, $wpestate_currency, $wpestate_where_currency, $has_data = 0, $return = 0) {


        $price_label = '';
        $th_separator = wprentals_get_option('wp_estate_prices_th_separator', '');
        $custom_fields = wprentals_get_option('wpestate_currency', '');


        if (floatval($price) != 0) {
            $price = $clear_price = floatval($price);
            //$price      = westate_display_corection($price);
            $price = number_format(($price), 2, '.', $th_separator);
            $price = wpestate_TrimTrailingZeroes($price);

            if ($has_data == 1) {
                $price = '<span class="inv_data_value" data-clearprice="' . esc_attr($clear_price) . '"> ' . $price . '</span>';
            }

            if ($wpestate_where_currency == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }
        } else {
            $price = 0;
            if ($wpestate_where_currency == 'before') {
                $price = $wpestate_currency . ' ' . $price;
            } else {
                $price = $price . ' ' . $wpestate_currency;
            }
        }


        if ($return == 0) {
            print trim($price . ' ' . $price_label);
        } else {
            return trim($price . ' ' . $price_label);
        }
    }

endif;

/////////////////////////////////////////////////////////////////////////////////
// show top bar
///////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_show_top_bar')):

    function wpestate_show_top_bar() {
        global $post;
        $is_top_bar = wprentals_get_option('wp_estate_show_top_bar_user_menu', '');

        if ($is_top_bar == "yes") {
            if (!is_tax() && !is_category() && !is_archive() && !is_404() && !is_tag()) {

                if (!wpestate_check_if_admin_page($post->ID)) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

endif;



/////////////////////////////////////////////////////////////////////////////////
// show create_booking_type
///////////////////////////////////////////////////////////////////////////////////


/*
*
*
* Check if admin
*
*
*/


if (!function_exists('wpestate_check_if_admin_page')):

    function wpestate_check_if_admin_page($page_id) {


        if (get_the_title($page_id) == 'Dashboard Add Activities') {
            return true;
        }
        global $post;
        $page_template='';
        if(isset($post->ID)){
            $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        }
       
      
        $dashboard_pages=array(
            'user_dashboard_main.php',
            'user_dashboard.php' ,
            'user_dashboard_add_step1.php',
            'user_dashboard_edit_listing.php',
            'user_dashboard_favorite.php',
            'user_dashboard_profile.php',
            'user_dashboard_my_bookings.php',
            'user_dashboard_my_reservations.php',
            'user_dashboard_favorite.php', 
            'user_dashboard_inbox.php',
            'user_dashboard_invoices.php',
            'user_dashboard_packs.php',
            'user_dashboard_searches.php',
            'user_dashboard_allinone.php',
        );

        if (in_array($page_template, $dashboard_pages) ) {
            return true;
        } else {
            return false;
        }
    }

endif;






if (!function_exists('wpestate_new_list_to_user')):

    function wpestate_new_list_to_user($newlist, $userid) {
        //scheck
        if (wpsestate_get_author($newlist) == 0) {
            $user_pack = get_the_author_meta('package_id', $userid);
            $remaining_listings = wpestate_get_remain_listing_user($userid, $user_pack);

            if ($remaining_listings === -1) {
                $remaining_listings = 11;
            }
            $paid_submission_status = esc_html(wprentals_get_option('wp_estate_paid_submission', ''));


            if ($paid_submission_status == 'membership' && $remaining_listings != -1 && $remaining_listings < 1) {
                $author_id = wpsestate_get_author($newlist);
                if ($author_id == 0) {
                    wp_delete_post($newlist);
                }
                return wpestate_get_template_link('user_dashboard_add_step1.php');
            } else {
                $new_post = array(
                    'ID' => $newlist,
                    'post_author' => $userid,
                );
                wp_update_post($new_post);
                $paid_submission_status = esc_html(wprentals_get_option('wp_estate_paid_submission', ''));
                if ($paid_submission_status == 'membership') { // update pack status
                    wpestate_update_listing_no($userid);
                }

                $edit_link = wpestate_get_template_link('user_dashboard_edit_listing.php');
                $edit_link_desc = esc_url_raw(add_query_arg('listing_edit', $newlist, $edit_link));
                $edit_link_desc = esc_url_raw(add_query_arg('action', 'description', $edit_link_desc));
                $edit_link_desc = esc_url_raw(add_query_arg('isnew', 1, $edit_link_desc));
                return $edit_link_desc;
            }
        }
    }

endif;



if (!function_exists('wpestate_email_to_admin')):

    function wpestate_email_to_admin($onlyfeatured) {
        $arguments = array();
        if ($onlyfeatured == 1) {
            $arguments = array();
            wpestate_select_email_type(wprentals_get_option('admin_email'), 'featured_submission', $arguments);
        } else {
            $arguments = array();
            wpestate_select_email_type(wprentals_get_option('admin_email'), 'paid_submissions', $arguments);
        }
    }

endif;




if (!function_exists('wpestate_show_stripe_form_upgrade')):

    function wpestate_show_stripe_form_upgrade($stripe_class, $post_id, $price_submission, $price_featured_submission) {

        $is_stripe_live = esc_html(wprentals_get_option('wp_estate_enable_stripe', ''));
        if ($is_stripe_live == 'yes') {


            print '<div class="stripe_upgrade">';
            $current_user = wp_get_current_user();
            $userID = $current_user->ID;
            $user_email = $current_user->user_email;
            $submission_curency_status = esc_html(wprentals_get_option('wp_estate_submission_curency', ''));
            $price_featured_submission = $price_featured_submission;

            global $wpestate_global_payments;
            $metadata = array(
                'listing_id' => $post_id,
                'user_id' => $userID,
                'featured_pay' => 0,
                'is_upgrade' => 1,
                'pay_type' => 2,
                'message' => esc_html__('Upgrade to Featured', 'wprentals')
            );

            $wpestate_global_payments->stripe_payments->wpestate_show_stripe_form($price_featured_submission, $metadata);
            print '</div>';
        }
    }

endif;




////////////////////////////////////////////////////////////////////////////////
/// show stripe form per listing
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_show_stripe_form_per_listing')):

    function wpestate_show_stripe_form_per_listing($stripe_class, $post_id, $price_submission, $price_featured_submission) {


        $processor_link = wpestate_get_template_link('stripecharge.php');
        $submission_curency_status = esc_html(wprentals_get_option('wp_estate_submission_curency', ''));
        $current_user = wp_get_current_user();
        $userID = $current_user->ID;
        $user_email = $current_user->user_email;

        $price_submission_total = $price_submission + $price_featured_submission;
        $price_submission_total = $price_submission_total;
        $price_submission = $price_submission;
        print '<div class="stripe-wrapper ' . $stripe_class . '" id="stripe_form_simple"> ';
        global $wpestate_global_payments;
        $metadata = array(
            'listing_id' => $post_id,
            'user_id' => $userID,
            'featured_pay' => 0,
            'is_upgrade' => 0,
            'pay_type' => 2,
            'message' => esc_html__('Pay Submission Fee', 'wprentals')
        );

        $wpestate_global_payments->stripe_payments->wpestate_show_stripe_form($price_submission, $metadata);
        print'

        </div>';
    }

endif;




////////////////////////////////////////////////////////////////////////////////
/// show stripe form membership
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_show_stripe_form_membership')):

    function wpestate_show_stripe_form_membership() {


        $current_user = wp_get_current_user();
        $userID = $current_user->ID;
        $user_login = $current_user->user_login;
        $user_email = get_the_author_meta('user_email', $userID);
        $is_stripe_live = esc_html(wprentals_get_option('wp_estate_enable_stripe', ''));
        if ($is_stripe_live == 'yes') {
            $stripe_secret_key = esc_html(wprentals_get_option('wp_estate_stripe_secret_key', ''));
            $stripe_publishable_key = esc_html(wprentals_get_option('wp_estate_stripe_publishable_key', ''));
        }
        $pay_ammout = '0';
        $pack_id = '0';

        $processor_link = wpestate_get_template_link('stripecharge.php');
        $submission_curency_status = esc_html(wprentals_get_option('wp_estate_submission_curency', ''));


        print '
        <form action="' . $processor_link . '" method="post" id="stripe_form">';
        wp_nonce_field('wpestate_stripe_payments', 'wpestate_stripe_payments_nonce');

        global $wpestate_global_payments;
        $metadata = array(
            'user_id' => $userID,
            'pay_type' => 3
        );
        $price_submission = '';


        $wpestate_global_payments->stripe_payments->wpestate_show_stripe_form($price_submission, $metadata);


        print'<input type="hidden" id="pack_id" name="pack_id" value="' . $pack_id . '">
            <input type="hidden" name="userID" value="' . $userID . '">
            <input type="hidden" id="pay_ammout" name="pay_ammout" value="' . $pay_ammout . '">';
        print'
        </form>';
    }

endif;








/////////////////////////////////////////////////////////////////////////////////
/// get the associated user for certain agent
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_user_for_agent')):

    function wpestate_user_for_agent($agent_id) {
        $args = array(
            'fields' => 'ID',
            'meta_query' => array(
                0 => array(
                    'key' => 'user_agent_id',
                    'value' => $agent_id,
                    'compare' => '='
                ),
            )
        );
        $user_query = new WP_User_Query($args);
        if (isset($user_query->results[0])) {
            $user_agent_id = $user_query->results[0];
        } else {
            $user_agent_id = 1;
        }

        return $user_agent_id;
    }

endif;



/////////////////////////////////////////////////////////////////////////////////
/// check user vs agent id
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_user_booked_from_agent')):

    function wpestate_user_booked_from_agent($userid, $agent_id) {
        $all_my_post = array();
        $args = array(
            'post_type' => 'wpestate_booking',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'author' => $userid,
            'meta_query' => array(
                array(
                    'key' => 'booking_status',
                    'value' => 'confirmed',
                    'compare' => '='
                )
            )
        );

        $prop_selection = new WP_Query($args);

        if ($prop_selection->have_posts()) {
            while ($prop_selection->have_posts()): $prop_selection->the_post();

                $prop_id = intval(get_post_meta(get_the_ID(), 'booking_id', true));
                if (intval(wpsestate_get_author($prop_id)) === intval($agent_id)) {
                    return 1;
                }

            endwhile; // end of the loop.
            return 0;
        } else {
            return 0;
        }
    }

endif;




/////////////////////////////////////////////////////////////////////////////////
/// check user vs agent id
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_send_booking_email')):

    function wpestate_send_booking_email($email_type, $receiver_email, $content = '') {
        $user_email = $receiver_email;

        if ($email_type == 'bookingconfirmeduser') {
            $arguments = array();
            wpestate_select_email_type($user_email, 'bookingconfirmeduser', $arguments);
        }if ($email_type == 'bookingconfirmed') {
            $arguments = array();
            wpestate_select_email_type($user_email, 'bookingconfirmed', $arguments);
        } else if ($email_type == 'bookingconfirmed_nodeposit') {
            $arguments = array();
            wpestate_select_email_type($user_email, 'bookingconfirmed_nodeposit', $arguments);
        } else if ($email_type == 'inbox') {
            $arguments = array('content' => $content);
            wpestate_select_email_type($user_email, 'inbox', $arguments);
        } else if ($email_type == 'newbook') {
            $property_id = intval($content);
            $arguments = array(
                'booking_property_link' => esc_url(get_permalink($property_id))
            );
            wpestate_select_email_type($user_email, 'newbook', $arguments);
        } else if ($email_type == 'mynewbook') {
            $property_id = intval($content);
            $arguments = array(
                'booking_property_link' => esc_url(get_permalink($property_id))
            );
            wpestate_select_email_type($user_email, 'mynewbook', $arguments);
        } else if ($email_type == 'newinvoice') {
            $arguments = array();
            wpestate_select_email_type($user_email, 'newinvoice', $arguments);
        } else if ($email_type == 'deletebooking') {
            $arguments = array();
            wpestate_select_email_type($user_email, 'deletebooking', $arguments);
        } else if ($email_type == 'deletebookinguser') {
            $arguments = array();
            wpestate_select_email_type($user_email, 'deletebookinguser', $arguments);
        } else if ($email_type == 'deletebookingconfirmed') {
            $arguments = array();
            wpestate_select_email_type($user_email, 'deletebookingconfirmed', $arguments);
        }
    }

endif;






////////////////////////////////////////////////////////////////////////////////
/// show hieracy area
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_get_guest_dropdown')):

    function wpestate_get_guest_dropdown($with_any = '', $selected = '') {
        $select_area_list = '';
        if ($with_any == '') {
            $select_area_list .= '<li role="presentation" data-value="0">' . esc_html__('any', 'wprentals') . '</li>';
        }

        $select_area_list .= '<li role="presentation" data-value="1"';
        if ($selected == 1) {
            $select_area_list .= ' selected="selected" ';
        }
        $select_area_list .= '>1 ' . esc_html__('guest', 'wprentals') . '</li>';

        $guest_dropdown_no = intval(wprentals_get_option('wp_estate_guest_dropdown_no', ''));
        for ($i = 2; $i <= $guest_dropdown_no; $i++) {
            $select_area_list .= '<li role="presentation" data-value="' . esc_attr($i) . '"';
            if ($selected != '' && $selected == $i) {
                $select_area_list .= ' selected="selected" ';
            }
            $select_area_list .= '>' . $i . ' ' . esc_html__('guests', 'wprentals') . '</li>';
        }

        return $select_area_list;
    }

endif;


if (!function_exists('wpestate_get_rooms_dropdown')):

    function wpestate_get_rooms_dropdown() {
        $select_area_list = '<li role="presentation" data-value="0">' . esc_html__('any', 'wprentals') . '</li>';
        $select_area_list .= '<li role="presentation" data-value="1">1 ' . esc_html__('room', 'wprentals') . '</li>';
        for ($i = 2; $i < 15; $i++) {
            $select_area_list .= '<li role="presentation" data-value="' . esc_attr($i) . '">' . $i . ' ' . esc_html__('rooms', 'wprentals') . '</li>';
        }

        return $select_area_list;
    }

endif;

if (!function_exists('wpestate_get_bedrooms_dropdown')):

    function wpestate_get_bedrooms_dropdown() {
        $select_area_list = '<li role="presentation" data-value="0">' . esc_html__('any', 'wprentals') . '</li>';
        $select_area_list .= '<li role="presentation" data-value="1">1 ' . esc_html__('bedroom', 'wprentals') . '</li>';
        for ($i = 2; $i < 15; $i++) {
            $select_area_list .= '<li role="presentation" data-value="' . esc_attr($i) . '">' . $i . ' ' . esc_html__('bedrooms', 'wprentals') . '</li>';
        }

        return $select_area_list;
    }

endif;

if (!function_exists('wpestate_get_baths_dropdown')):

    function wpestate_get_baths_dropdown() {
        $select_area_list = '<li role="presentation" data-value="0">' . esc_html__('any', 'wprentals') . '</li>';
        $select_area_list .= '<li role="presentation" data-value="1">1 ' . esc_html__('bath', 'wprentals') . '</li>';
        for ($i = 2; $i < 15; $i++) {
            $select_area_list .= '<li role="presentation" data-value="' . esc_attr($i) . '">' . $i . ' ' . esc_html__('baths', 'wprentals') . '</li>';
        }

        return $select_area_list;
    }

endif;



if (!function_exists('wpestate_insert_attachment')):

    function wpestate_insert_attachment($file_handler, $post_id, $setthumb = 'false') {

        // check to make sure its a successful upload
        if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK)
            __return_false();
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        $attach_id = media_handle_upload($file_handler, $post_id);

        if ($setthumb)
            update_post_meta($post_id, '_thumbnail_id', $attach_id);
        return $attach_id;
    }

endif;




/////////////////////////////////////////////////////////////////////////////////
// order by filter featured
///////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_measure_unit')):

    function wpestate_get_measure_unit() {
        $measure_sys = esc_html(wprentals_get_option('wp_estate_measure_sys', ''));

        if ($measure_sys == 'feet') {
            return 'ft<sup>2</sup>';
        } else {
            return 'm<sup>2</sup>';
        }
    }

endif;
/////////////////////////////////////////////////////////////////////////////////
// order by filter featured
///////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_my_order')):

    function wpestate_my_order($orderby) {
        global $wpdb;
        global $table_prefix;
        $orderby = $table_prefix . 'postmeta.meta_value DESC, ' . $table_prefix . 'posts.ID DESC';
        return $orderby;
    }

endif; // end   wpestate_my_order
////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination
/////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wprentals_pagination')):

    function wprentals_pagination($pages = '', $range = 2) {

        $showitems = ($range * 2) + 1;
        global $paged;
        if (empty($paged))
            $paged = 1;


        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }

        if (1 != $pages) {
            echo '<ul class="pagination pagination_nojax">';
            echo "<li class=\"roundleft\"><a href='" . get_pagenum_link($paged - 1) . "'><i class=\"fas fa-chevron-left\"></i></a></li>";

            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                    if ($paged == $i) {
                        print '<li class="active"><a href="' . esc_url(get_pagenum_link($i)) . '" >' . esc_html($i) . '</a><li>';
                    } else {
                        print '<li><a href="' . esc_url(get_pagenum_link($i)) . '" >' . esc_html($i) . '</a><li>';
                    }
                }
            }

            $prev_page = get_pagenum_link($paged + 1);
            if (($paged + 1) > $pages) {
                $prev_page = get_pagenum_link($paged);
            } else {
                $prev_page = get_pagenum_link($paged + 1);
            }


            echo "<li class=\"roundright\"><a href='" . $prev_page . "'><i class=\"fas fa-chevron-right\"></i></a><li></ul>";
        }
    }

endif; // end   wprentals_pagination
////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination Ajax
/////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wprentals_pagination_agent')):

    function wprentals_pagination_agent($pages = '', $range = 2) {
        $showitems = ($range * 2) + 1;
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        if (empty($paged))
            $paged = 1;

        if (1 != $pages) {
            $prev_pagex = str_replace('page/', '', get_pagenum_link($paged - 1));
            echo '<ul class="pagination pagination_nojax">';
            echo "<li class=\"roundleft\"><a href='" . $prev_pagex . "'><i class=\"fas fa-chevron-left\"></i></a></li>";

            for ($i = 1; $i <= $pages; $i++) {
                $cur_page = str_replace('page/', '', get_pagenum_link($i));
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                    if ($paged == $i) {
                        print '<li class="active"><a href="' . esc_url($cur_page) . '" >' . esc_html($i) . '</a><li>';
                    } else {
                        print '<li><a href="' . esc_url($cur_page) . '" >' . esc_html($i) . '</a><li>';
                    }
                }
            }

            $prev_page = str_replace('page/', '', get_pagenum_link($paged + 1));
            if (($paged + 1) > $pages) {
                $prev_page = str_replace('page/', '', get_pagenum_link($paged));
            } else {
                $prev_page = str_replace('page/', '', get_pagenum_link($paged + 1));
            }


            echo "<li class=\"roundright\"><a href='" . $prev_page . "'><i class=\"fas fa-chevron-right\"></i></a><li></ul>";
        }
    }

endif; // end   kriesi_pagination






if (!function_exists('wprentals_second_loop_pagination')):

    function wprentals_second_loop_pagination($pages, $range, $paged, $link) {
        $newpage = $paged - 1;
        if ($newpage < 1) {
            $newpage = 1;
        }
        $next_page = esc_url_raw(add_query_arg('pagelist', $newpage, esc_url($link)));
        $showitems = ($range * 2) + 1;
        if ($pages > 1) {
            print "<ul class='pagination pagination_nojax pagination_agent'>";
            echo "<li class=\"roundleft\"><a href='" . $next_page . "'><i class=\"fas fa-chevron-left\"></i></a></li>";


            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                    $newpage = $paged - 1;
                    $next_page = esc_url_raw(add_query_arg('pagelist', $i, esc_url($link)));
                    if ($paged == $i) {
                        echo "<li class='active'><a href='' >" . $i . "</a><li>";
                    } else {
                        echo "<li><a href='" . $next_page . "' >" . $i . "</a><li>";
                    }
                }
            }

            $prev_page = get_pagenum_link($paged + 1);
            if (($paged + 1) > $pages) {
                $prev_page = get_pagenum_link($paged);
                $newpage = $paged;
                $prev_page = esc_url_raw(add_query_arg('pagelist', $newpage, esc_url($link)));
            } else {
                $prev_page = get_pagenum_link($paged + 1);
                $newpage = $paged + 1;
                $prev_page = esc_url_raw(add_query_arg('pagelist', $newpage, esc_url($link)));
            }

            echo "<li class=\"roundright\"><a href='" . $prev_page . "'><i class=\"fas fa-chevron-right\"></i></a><li>";
            echo "</ul>\n";
        }
    }

endif;

////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination Custom
/////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wprentals_pagination_ajax')):

    function wprentals_pagination_ajax($pages , $range , $paged, $where) {
        $showitems = ($range * 2) + 1;

        if (1 != $pages) {
            echo '<ul class="pagination ' . $where . '">';
            if ($paged != 1) {
                $prev_page = $paged - 1;
            } else {
                $prev_page = 1;
            }
            echo "<li class=\"roundleft\"><a href='" . esc_url(get_pagenum_link($paged - 1)) . "' data-future='" . esc_attr($prev_page) . "'><i class=\"fas fa-chevron-left\"></i></a></li>";

            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                    if ($paged == $i) {
                        print '<li class="active"><a href="' . esc_url(get_pagenum_link($i)) . '" data-future="' . esc_attr($i) . '">' . esc_html($i) . '</a><li>';
                    } else {
                        print '<li><a href="' . esc_url(get_pagenum_link($i)) . '" data-future="' . esc_attr(esc_attr($i)) . '">' . esc_html($i) . '</a><li>';
                    }
                }
            }

            $prev_page = get_pagenum_link($paged + 1);
            if (($paged + 1) > $pages) {
                $prev_page = get_pagenum_link($paged);
                echo "<li class=\"roundright\"><a href='" . esc_url($prev_page) . "' data-future='" . esc_attr($paged) . "'><i class=\"fas fa-chevron-right\"></i></a><li>";
            } else {
                $prev_page = get_pagenum_link($paged + 1);
                echo "<li class=\"roundright\"><a href='" . esc_url($prev_page) . "' data-future='" . esc_attr($paged + 1) . "'><i class=\"fas fa-chevron-right\"></i></a><li>";
            }

            echo "</ul>\n";
        }
    }

endif; // end   kriesi_pagination
////////////////////////////////////////////////////////////////////////////////
/// force html5 validation -remove category list rel atttribute
////////////////////////////////////////////////////////////////////////////////

add_filter('wp_list_categories', 'wpestate_remove_category_list_rel');
add_filter('the_category', 'wpestate_remove_category_list_rel');

if (!function_exists('wpestate_remove_category_list_rel')):

    function wpestate_remove_category_list_rel($output) {
        // Remove rel attribute from the category list
        return str_replace(' rel="category tag"', '', $output);
    }

endif; // end   wpestate_remove_category_list_rel
////////////////////////////////////////////////////////////////////////////////
/// avatar url
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_avatar_url')):

    function wpestate_get_avatar_url($get_avatar) {
        preg_match("/src='(.*?)'/i", $get_avatar, $matches);
        return $matches[1];
    }

endif; // end   wpestate_get_avatar_url
////////////////////////////////////////////////////////////////////////////////
///  get current map height
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_current_map_height')):

    function wpestate_get_current_map_height($post_id) {

        if ($post_id == '' || is_home()) {
            $min_height = intval(wprentals_get_option('wp_estate_min_height', ''));
        } else {
            $min_height = intval((get_post_meta($post_id, 'min_height', true)));
            if ($min_height == 0) {
                $min_height = intval(wprentals_get_option('wp_estate_min_height', ''));
            }
        }
        return $min_height;
    }

endif; // end
////////////////////////////////////////////////////////////////////////////////
///  get  map open height
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_map_open_height')):

    function wpestate_get_map_open_height($post_id) {

        if ($post_id == '' || is_home()) {
            $max_height = intval(wprentals_get_option('wp_estate_max_height', ''));
        } else {
            $max_height = intval((get_post_meta($post_id, 'max_height', true)));
            if ($max_height == 0) {
                $max_height = intval(wprentals_get_option('wp_estate_max_height', ''));
            }
        }

        return $max_height;
    }

endif; // end
////////////////////////////////////////////////////////////////////////////////
///  get  map open/close status
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_map_open_close_status')):

    function wpestate_get_map_open_close_status($post_id) {
        if ($post_id == '' || is_home()) {
            $keep_min = esc_html(wprentals_get_option('wp_estate_keep_min', ''));
        } else {
            $keep_min = esc_html((get_post_meta($post_id, 'keep_min', true)));
        }

        if ($keep_min == 'yes') {
            $keep_min = 1; // map is forced at closed
        } else {
            $keep_min = 0; // map is free for resize
        }

        return $keep_min;
    }

endif; // end
////////////////////////////////////////////////////////////////////////////////
///  get  map  longitude
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_get_page_long')):

    function wpestate_get_page_long($post_id) {
        $wpestate_header_type = get_post_meta($post_id, 'header_type', true);
        if ($wpestate_header_type == 5) {
            $page_long = esc_html(get_post_meta($post_id, 'page_custom_long', true));
        } else {
            $page_long = esc_html(wprentals_get_option('wp_estate_general_longitude', ''));
        }
        return $page_long;
    }

endif; // end
////////////////////////////////////////////////////////////////////////////////
///  get  map  lattitudine
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_page_lat')):

    function wpestate_get_page_lat($post_id) {
        $wpestate_header_type = get_post_meta($post_id, 'header_type', true);
        if ($wpestate_header_type == 5) {
            $page_lat = esc_html(get_post_meta($post_id, 'page_custom_lat', true));
        } else {
            $page_lat = esc_html(wprentals_get_option('wp_estate_general_latitude', ''));
        }
        return $page_lat;
    }

endif; // end
////////////////////////////////////////////////////////////////////////////////
///  get  map  zoom
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_page_zoom')):

    function wpestate_get_page_zoom($post_id) {
        $wpestate_header_type = get_post_meta($post_id, 'header_type', true);
        if ($wpestate_header_type == 5) {
            $page_zoom = get_post_meta($post_id, 'page_custom_zoom', true);
        } else {
            $page_zoom = esc_html(wprentals_get_option('wp_estate_default_map_zoom', ''));
        }
        return $page_zoom;
    }

endif; // end
//wpestate_get_template_link('user_dashboard_my_reservations.php');
if (!function_exists('wpestate_get_template_link')):

    function wpestate_get_template_link($template_name, $bypass = 0) {

        $transient_name = $template_name;

        if (defined('ICL_LANGUAGE_CODE')) {
            $transient_name .= '_' . ICL_LANGUAGE_CODE;
        }


        $template_link = wpestate_request_transient_cache('wpestate_get_template_link_' . $transient_name);

        if ($template_link === false || $template_link === '' || $bypass == 1) {
           
            if (defined('ICL_LANGUAGE_CODE')) {
                $pages = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => $template_name,
                  
                ));
            }else{
                $pages = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => $template_name,
                    'number'    =>  1
                ));
            }
    
            if ($pages) {
                $template_link = esc_url(get_permalink($pages[0]->ID));
            } else {
                $template_link = '';
            }


            wpestate_set_transient_cache('wpestate_get_template_link_' . $transient_name, $template_link, 60 * 60 * 24);
        }



        return $template_link;
    }

endif; // end
///////////////////////////////////////////////////////////////////////////////////////////
// return video divs for sliders
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_custom_vimdeo_video')):

    function wpestate_custom_vimdeo_video($video_id) {
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string = '
            <div style="max-width:100%;" class="video">
               <iframe id="player_1" src=' . $protocol . '"://player.vimeo.com/video/' . $video_id . '?api=1&amp;player_id=player_1"      allowFullScreen ></iframe>
            </div>';
    }

endif; // end


if (!function_exists('wpestate_custom_youtube_video')):

    function wpestate_custom_youtube_video($video_id) {
        $protocol = is_ssl() ? 'https' : 'http';
        return $return_string = '
            <div style="max-width:100%;" class="video">
                <iframe id="player_2" title="YouTube video player" src="' . $protocol . '://www.youtube.com/embed/' . esc_html($video_id) . '?wmode=transparent&amp;rel=0"  allowfullscreen ></iframe>
            </div>';
    }

endif; // end


if (!function_exists('wpestate_get_video_thumb')):

    function wpestate_get_video_thumb($post_id) {
        $video_id = esc_html(get_post_meta($post_id, 'embed_video_id', true));
        $video_type = esc_html(get_post_meta($post_id, 'embed_video_type', true));
        $protocol = is_ssl() ? 'https' : 'http';
        if ($video_type == 'vimeo') {
            $hash2 = ( wp_remote_get($protocol . "://vimeo.com/api/v2/video/$video_id.php") );
            $pre_tumb = (unserialize($hash2['body']) );
            $video_thumb = $pre_tumb[0]['thumbnail_medium'];
        } else {
            $video_thumb = $protocol . '://img.youtube.com/vi/' . $video_id . '/0.jpg';
        }
        return $video_thumb;
    }

endif;



if (!function_exists('wpestate_generateRandomString')):

    function wpestate_generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

endif;




if (!function_exists('wpestate_show_extended_search')):

    function wpestate_show_extended_search($tip) {

        $label_array = array();

        $terms = get_terms(array(
            'taxonomy' => 'property_features',
            'hide_empty' => false,
                ));


        if (defined('ICL_SITEPRESS_VERSION') && !is_admin()) {
            $current_language = apply_filters('wpml_current_language', NULL);
            $default_language = apply_filters('wpml_default_language', NULL);
            if ($current_language != $default_language) {
                $original_terms = NULL;
                do_action('wpml_switch_language', $default_language);
                $original_terms = get_terms(array(
                    'taxonomy' => 'property_features',
                    'hide_empty' => false,
                        ));
                do_action('wpml_switch_language', $current_language);
                foreach ($terms as $key => $term) {
                    $term_name = $term->name;
                    $term_slug = $term->slug;
                    $original_term_id = apply_filters('wpml_object_id', $term->term_id, 'property_features', true, $default_language);
                    $original_term = $original_terms[array_search($original_term_id, array_column($original_terms, 'term_id'))];
                    $term_slug = $original_term->slug;
                    $label_array[$term_slug] = $term_name;
                }
            }
        }




        foreach ($terms as $key => $term) {
            $label_array[$term->slug] = $term->name;
        }
        print '<div class="extended_search_check_wrapper" id="extended_search_check_filter">';

        print '
        <div class="secondrow">


        </div>';
        print '<span id="adv_extended_close_adv"><i class="fas fa-times"></i></span>';

        $advanced_exteded = wprentals_get_option('wp_estate_advanced_exteded');
        if (is_array($advanced_exteded)) {
            foreach ($advanced_exteded as $checker => $value) {

                $input_name = $value;
                $label = $label_array[$input_name];


                if ($value != 'none' && $label != '') {
                    print '<div class="extended_search_checker"><input type="checkbox" id="' . $input_name . $tip . '" name="' . $input_name . '" ';
                    if (isset($_REQUEST[$input_name]) && $_REQUEST[$input_name] == 1) {
                        print ' checked = "checked" ';
                    }
                    print 'value="1" ><label for="' . $input_name . $tip . '">' . stripslashes($label) . '</label></div>';
                }
            }
        }

        print '</div>';
    }

endif;






////////////////////////////////////////////////////////////////////////////////
/// show hieracy categeg
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_hierarchical_category_childen')):

    function wpestate_hierarchical_category_childen($taxonomy, $cat, $args, $base = 1, $level = 1) {
        $level++;
        $args['parent'] = $cat;
        $children = get_terms($taxonomy, $args);
        $return_array = array();
        $total_main[$level] = 0;
        $children_categ_select_list = '';
        foreach ($children as $categ) {

            $area_addon = '';
            $city_addon = '';

            if ($taxonomy == 'property_city') {
                $string = wpestate_limit45(sanitize_title($categ->slug));
                $slug = sanitize_key($string);
                $city_addon = ' data-value2="' . esc_attr($slug) . '" ';
            }

            if ($taxonomy == 'property_area') {
                $term_meta = get_option("taxonomy_$categ->term_id");
                $string = wpestate_limit45(sanitize_title($term_meta['cityparent']));
                $slug = sanitize_key($string);
                $area_addon = ' data-parentcity="' . esc_attr($slug) . '" ';
            }

            $hold_base = $base;
            $base_string = '';
            $base++;
            $hold_base = $base;

            if ($level == 2) {
                $base_string = '-';
            } else {
                $i = 2;
                $base_string = '';
                while ($i <= $level) {
                    $base_string .= '-';
                    $i++;
                }
            }


            if ($categ->parent != 0) {
                $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id, $args, $base, $level);
            }


            $counter = $categ->count;
            if (isset($received['count'])) {
                $counter = $counter + $received['count'];
            }

            $children_categ_select_list .= '<li role="presentation" data-value="' . esc_attr($categ->slug) . '" ' . $city_addon . ' ' . $area_addon . ' > ' . $base_string . ' ' . ucwords(urldecode($categ->name)) . ' (' . $counter . ')' . '</li>';

            if (isset($received['html'])) {
                $children_categ_select_list .= $received['html'];
            }

            $total_main[$level] = $total_main[$level] + $counter;

            $return_array['count'] = $counter;
            $return_array['html'] = $children_categ_select_list;
        }
        //  return $children_categ_select_list;

        $return_array['count'] = $total_main[$level];


        return $return_array;
    }

endif;

////////////////////////////////////////////////////////////////////////////////
/// get select arguments
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_select_arguments')):

    function wpestate_get_select_arguments() {
        $args = array(
            'hide_empty' => true,
            'hierarchical' => false,
            'pad_counts ' => true,
            'parent' => 0
        );

        $show_empty_city_status = esc_html(wprentals_get_option('wp_estate_show_empty_city', ''));
        if ($show_empty_city_status == 'yes') {
            $args = array(
                'hide_empty' => false,
                'hierarchical' => false,
                'pad_counts ' => true,
                'parent' => 0
            );
        }
        return $args;
    }

endif;

////////////////////////////////////////////////////////////////////////////////
/// show hieracy action
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_action_select_list')):

    function wpestate_get_action_select_list($args) {
        $transient_appendix = '';
        $transient_appendix = wpestate_add_language_currency_cache($transient_appendix, 1);


        $categ_select_list = wpestate_request_transient_cache('wpestate_get_action_select_list_simple' . $transient_appendix);
        if ($categ_select_list === false) {
            $taxonomy = 'property_action_category';
            $categories = get_terms($taxonomy, $args);

            $categ_select_list = ' <li role="presentation" data-value="all">' . wpestate_category_labels_dropdowns('second') . '</li>';

            if (is_array($categories)) {
                foreach ($categories as $categ) {
                    $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id, $args);
                    $counter = $categ->count;
                    if (isset($received['count'])) {
                        $counter = $counter + $received['count'];
                    }

                    $categ_select_list .= '<li role="presentation" data-value="' . esc_attr($categ->slug) . '">' . ucwords(urldecode($categ->name)) . ' (' . $counter . ')' . '</li>';
                    if (isset($received['html'])) {
                        $categ_select_list .= $received['html'];
                    }
                }
            }

            wpestate_set_transient_cache('wpestate_get_action_select_list_simple' . $transient_appendix, $categ_select_list, 4 * 60 * 60);
        }
        return $categ_select_list;
    }

endif;


////////////////////////////////////////////////////////////////////////////////
/// show hieracy categ
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_get_category_select_list')):

    function wpestate_get_category_select_list($args) {
        $transient_appendix = '';
        $transient_appendix = wpestate_add_language_currency_cache($transient_appendix, 1);
        $categ_select_list = wpestate_request_transient_cache('wpestate_get_category_select_list_simple' . $transient_appendix);

        if ($categ_select_list === false) {

            $taxonomy = 'property_category';
            $categories = get_terms($taxonomy, $args);

            $categ_select_list = '<li role="presentation" data-value="all">' . wpestate_category_labels_dropdowns('main') . '</li>';
            if (is_array($categories)) {
                foreach ($categories as $categ) {
                    $counter = $categ->count;
                    $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id, $args);

                    if (isset($received['count'])) {
                        $counter = $counter + $received['count'];
                    }

                    $categ_select_list .= '<li role="presentation" data-value="' . esc_attr($categ->slug) . '">' . ucwords(urldecode($categ->name)) . ' (' . $counter . ')' . '</li>';
                    if (isset($received['html'])) {
                        $categ_select_list .= $received['html'];
                    }
                }
            }

            wpestate_set_transient_cache('wpestate_get_category_select_list_simple' . $transient_appendix, $categ_select_list, 4 * 60 * 60);
        }
        return $categ_select_list;
    }

endif;




////////////////////////////////////////////////////////////////////////////////
/// show hieracy city
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_get_city_select_list')):

    function wpestate_get_city_select_list($args) {
        $transient_appendix = '';
        $transient_appendix = wpestate_add_language_currency_cache($transient_appendix, 1);
        $categ_select_list = wpestate_request_transient_cache('wpestate_get_city_select_list_simple' . $transient_appendix);
        if ($categ_select_list === false) {

            $categ_select_list = '<li role="presentation" data-value="all" data-value2="all">' . __('All Cities', 'wprentals') . '</li>';
            $taxonomy = 'property_city';
            $categories = get_terms($taxonomy, $args);
            if (is_array($categories)) {
                foreach ($categories as $categ) {
                    $string = wpestate_limit45(sanitize_title($categ->slug));
                    $slug = sanitize_key($string);
                    $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id, $args);
                    $counter = $categ->count;
                    if (isset($received['count'])) {
                        $counter = $counter + $received['count'];
                    }

                    $categ_select_list .= '<li role="presentation" data-value="' . esc_attr($categ->slug) . '" data-value2="' . esc_attr($slug) . '">' . ucwords(urldecode($categ->name)) . ' (' . $counter . ')' . '</li>';
                    if (isset($received['html'])) {
                        $categ_select_list .= $received['html'];
                    }
                }
            }

            wpestate_set_transient_cache('wpestate_get_city_select_list_simple' . $transient_appendix, $categ_select_list, 4 * 60 * 60);
        }
        return $categ_select_list;
    }

endif;


////////////////////////////////////////////////////////////////////////////////
/// show hieracy area
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_get_area_select_list')):

    function wpestate_get_area_select_list($args) {
        $transient_appendix = '';
        $transient_appendix = wpestate_add_language_currency_cache($transient_appendix, 1);
        $categ_select_list = wpestate_request_transient_cache('wpestate_get_area_select_list_simple' . $transient_appendix);

        if ($categ_select_list === false) {
            $categ_select_list = '<li role="presentation" data-value="all">' . __('All Areas', 'wprentals') . '</li>';
            $taxonomy = 'property_area';
            $categories = get_terms($taxonomy, $args);
            if (is_array($categories)) {
                foreach ($categories as $categ) {
                    $term_meta = get_option("taxonomy_$categ->term_id");
                    $string='';
                    if(isset($term_meta['cityparent'])){
                        $string = wpestate_limit45(sanitize_title($term_meta['cityparent']));
                    }
                    $slug = sanitize_key($string);
                    $received = wpestate_hierarchical_category_childen($taxonomy, $categ->term_id, $args);
                    $counter = $categ->count;
                    if (isset($received['count'])) {
                        $counter = $counter + $received['count'];
                    }

                    $categ_select_list .= '<li role="presentation" data-value="' . esc_attr($categ->slug) . '" data-parentcity="' . esc_attr($slug) . '">' . ucwords(urldecode($categ->name)) . ' (' . $counter . ')' . '</li>';
                    if (isset($received['html'])) {
                        $categ_select_list .= $received['html'];
                    }
                }
            }

            wpestate_set_transient_cache('wpestate_get_area_select_list_simple' . $transient_appendix, $categ_select_list, 4 * 60 * 60);
        }
        return $categ_select_list;
    }

endif;




if (!function_exists('wpestate_get_area_select_list_area_tax')):

    function wpestate_get_area_select_list_area_tax($args, $parentcity = '') {
        $select_area_list = '<li role="presentation" data-value="all">' . esc_html__('All Areas', 'wprentals') . '</li>';
        $taxonomy = 'property_area';
        $tax_terms_area = get_terms($taxonomy, $args);
        if (is_array($tax_terms_area)) {
            foreach ($tax_terms_area as $tax_term) {

                $term_meta = get_option("taxonomy_$tax_term->term_id");
                $string = wpestate_limit45(sanitize_title($term_meta['cityparent']));
                $parentcity = wpestate_limit45(sanitize_title($parentcity));
                $slug = sanitize_key($string);

                if ($parentcity != '' && $parentcity == $string) {
                    $select_area_list .= '<li style="display:none;" role="presentation" data-value="' . esc_attr($tax_term->slug) . '" data-parentcity="' . esc_attr($slug) . '">' . ucwords(urldecode($tax_term->name)) . ' (' . $tax_term->count . ')' . '</li>';
                    $select_area_list .= wpestate_hierarchical_category_childen($taxonomy, $tax_term->term_id, $args);
                } else {
                    $select_area_list .= '<li role="presentation" data-value="' . esc_attr($tax_term->slug) . '" data-parentcity="' . esc_attr($slug) . '">' . ucwords(urldecode($tax_term->name)) . ' (' . $tax_term->count . ')' . '</li>';
                    $select_area_list .= wpestate_hierarchical_category_childen($taxonomy, $tax_term->term_id, $args);
                }
            }
        }
        return $select_area_list;
    }

endif;

////////////////////////////////////////////////////////////////////////////////
/// show name on saved searches
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_get_custom_field_name')):

    function wpestate_get_custom_field_name($query_name, $adv_search_what, $adv_search_label) {
        $i = 0;
        if (is_array($adv_search_what)) {
            foreach ($adv_search_what as $key => $term) {
                $term = str_replace(' ', '_', $term);
                $slug = wpestate_limit45(sanitize_title($term));
                $slug = sanitize_key($slug);

                if ($slug == $query_name) {
                    return $adv_search_label[$key];
                }
                $i++;
            }
        }
        return $query_name;
    }

endif;

////////////////////////////////////////////////////////////////////////////////
/// get author
////////////////////////////////////////////////////////////////////////////////


if (!function_exists('wpsestate_get_author')):

    function wpsestate_get_author($post_id = 0) {
        $post = get_post($post_id);

        if (isset($post->post_author)) {
            return $post->post_author;
        }
    }

endif;

function wpestate_convert_dateformat($date) {
    // date is yy-mm-dd, yy0,mm1,dd2
    if ($date == '') {
        return $date;
    }
    $format = wprentals_get_option('wp_estate_date_format', '');

    $dates_types = array(
        '0' => 'Y-m-d',
        '1' => 'Y-d-m',
        '2' => 'd-m-Y',
        '3' => 'm-d-Y',
        '4' => 'd-Y-m',
        '5' => 'm-Y-d',
    );


    if (strpos($date, ' ') !== false) {
        $formatIn = $dates_types[$format] . " H:i";
        $formatOut = 'Y-m-d H:i';
    } else {
        $formatIn = $dates_types[$format];
        $formatOut = 'Y-m-d';
    }


    $dateOut = DateTime::createFromFormat($formatIn, trim($date));
    //var_dump(DateTime::getLastErrors());
    return $dateOut->format($formatOut);
}

function wpestate_convert_dateformat_twodig($date) {
    // date is yy-mm-dd, yy0,mm1,dd2
    if ($date == '') {
        return $date;
    }
    $format = wprentals_get_option('wp_estate_date_format', '');

    $dates_types = array(
        '0' => 'Y-m-d',
        '1' => 'Y-d-m',
        '2' => 'd-m-Y',
        '3' => 'm-d-Y',
        '4' => 'd-Y-m',
        '5' => 'm-Y-d',
    );


    if (strpos($date, ' ') !== false) {
        $formatIn = $dates_types[$format] . " H:i";
        $formatOut = 'y-m-d H:i';
    } else {
        $formatIn = $dates_types[$format];
        $formatOut = 'y-m-d';
    }



    $dateOut = DateTime::createFromFormat($formatIn, $date);

    return $dateOut->format($formatOut);
}

function wpestate_convert_dateformat_remove_hours_twodig($date) {
    // date is yy-mm-dd, yy0,mm1,dd2
    if ($date == '') {
        return $date;
    }
    $format = wprentals_get_option('wp_estate_date_format', '');

    $dates_types = array(
        '0' => 'Y-m-d',
        '1' => 'Y-d-m',
        '2' => 'd-m-Y',
        '3' => 'm-d-Y',
        '4' => 'd-Y-m',
        '5' => 'm-Y-d',
    );


    if (strpos($date, ' ') !== false) {
        $formatIn = $dates_types[$format] . " H:i";
        $formatOut = 'y-m-d 00:00';
    } else {
        $formatIn = $dates_types[$format];
        $formatOut = 'y-m-d 00:00';
    }



    $dateOut = DateTime::createFromFormat($formatIn, $date);

    return $dateOut->format($formatOut);
}

function wpestate_convert_dateformat_reverse_wordpress($date_unix) {

    $formatOut = get_option('date_format');
    return date($formatOut, $date_unix);
}

function wpestate_convert_dateformat_reverse($date) {
    if ($date == '') {
        return $date;
    }
    $format = wprentals_get_option('wp_estate_date_format', '');

    $dates_types = array(
        '0' => 'y-m-d',
        '1' => 'y-d-m',
        '2' => 'd-m-y',
        '3' => 'm-d-y',
        '4' => 'd-y-m',
        '5' => 'm-y-d',
    );


    if (strpos($date, ' ') !== false) {
        $formatOut = $dates_types[$format] . " H:i";
        $formatIn = 'Y-m-d H:i';
    } else {
        $formatOut = $dates_types[$format];
        $formatIn = 'Y-m-d';
    }

    $dateOut = DateTime::createFromFormat($formatIn, $date);



    return $dateOut->format($formatOut);
}

////////////////////////////////////////////////////////////////////////////////
/// check avalability
////////////////////////////////////////////////////////////////////////////////

if (!function_exists('wpestate_check_booking_valability')):

    function wpestate_check_booking_valability($wpestate_book_from, $wpestate_book_to, $listing_id) {

        if ($wpestate_book_from == '' || $wpestate_book_to == '') {
            return true;
        }


        $wpestate_book_from = wpestate_convert_dateformat($wpestate_book_from);
        $wpestate_book_to = wpestate_convert_dateformat($wpestate_book_to);
        $wprentals_is_per_hour = wprentals_return_booking_type($listing_id);

        $days = ( strtotime($wpestate_book_to) - strtotime($wpestate_book_from) ) / (60 * 60 * 24);
        $reservation_array = wpestate_get_booking_dates_advanced_search($listing_id);
        $from_date = new DateTime($wpestate_book_from);
        $from_date_unix = $from_date->getTimestamp();
        $to_date = new DateTime($wpestate_book_to);
        $to_date->modify('yesterday');
        $to_date_unix = $to_date->getTimestamp();

        $mega_details = wpml_mega_details_adjust($listing_id);




        if ($from_date_unix === $to_date_unix) {
            if (array_key_exists($from_date_unix, $reservation_array)) {
                return false;
            }

            if ($wprentals_is_per_hour != 2 && is_array($mega_details)) { // if is not per hour
                if (array_key_exists($from_date_unix, $mega_details)) {
                    if (isset($mega_details[$from_date_unix]['period_min_days_booking']) && $mega_details[$from_date_unix]['period_min_days_booking'] > $days) {
                        return false;
                    }
                }
            }
        }


        while ($from_date_unix < $to_date_unix) {
            $from_date_unix = $from_date->getTimestamp();
            if (array_key_exists($from_date_unix, $reservation_array)) {
                return false;
            }

            if ($wprentals_is_per_hour != 2 && is_array($mega_details)) { // if is not per hour
                if (isset($mega_details[$from_date_unix]['period_min_days_booking']) && $mega_details[$from_date_unix]['period_min_days_booking'] > $days) {
                    return false;
                }
            }
            $from_date->modify('tomorrow');
        }


        $min_days_booking = intval(get_post_meta($listing_id, 'min_days_booking', true));

        if ($wprentals_is_per_hour != 2 && $min_days_booking != 0 && $min_days_booking > $days) { // if is not per hour
            return false;
        }

        return true;
    }

endif;



if (!function_exists('wpestate_get_booking_dates_advanced_search')):

    function wpestate_get_booking_dates_advanced_search($listing_id) {

        $reservation_array = get_post_meta($listing_id, 'booking_dates', true);
        if (!is_array($reservation_array) || $reservation_array == '') {
            $reservation_array = array();

            $args = array(
                'post_type' => 'wpestate_booking',
                'post_status' => 'any',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'booking_id',
                        'value' => $listing_id,
                        'type' => 'NUMERIC',
                        'compare' => '='
                    ),
                    array(
                        'key' => 'booking_status',
                        'value' => 'confirmed',
                        'compare' => '='
                    )
                )
            );

            $booking_selection = new WP_Query($args);
            foreach ($booking_selection->posts as $post) {
                $pid = get_the_ID();
                $fromd = esc_html(get_post_meta($post->ID, 'booking_from_date', true));
                $tod = esc_html(get_post_meta($post->ID, 'booking_to_date', true));

                $fromd = wpestate_convert_dateformat($fromd);
                $tod = wpestate_convert_dateformat($tod);


                $from_date = new DateTime($fromd);
                $from_date_unix = $from_date->getTimestamp();
                $to_date = new DateTime($tod);
                $to_date_unix = $to_date->getTimestamp();
                // $reservation_array[]=$from_date_unix;
                $reservation_array[$from_date_unix] = $pid;

                while ($from_date_unix < $to_date_unix) {
                    $from_date->modify('tomorrow');
                    $from_date_unix = $from_date->getTimestamp();
                    //$reservation_array[]=$from_date_unix;
                    $reservation_array[$from_date_unix] = $pid;
                }
            }
        }


        return $reservation_array;
    }

endif;

if (!function_exists('wpestate_yelp_details')):

    function wpestate_yelp_details($post_id) {

        $yelp_terms_array = array(
                    'active' => array('category' => __('Active Life', 'wprentals'),
                        'category_sign' => 'fas fa-motorcycle'),
                    'arts' => array('category' => __('Arts & Entertainment', 'wprentals'),
                        'category_sign' => 'fas fa-music'),
                    'auto' => array('category' => __('Automotive', 'wprentals'),
                        'category_sign' => 'fas fa-car'),
                    'beautysvc' => array('category' => __('Beauty & Spas', 'wprentals'),
                        'category_sign' => 'fas fa-female'),
                    'education' => array('category' => __('Education', 'wprentals'),
                        'category_sign' => 'fas fa-graduation-cap'),
                    'eventservices' => array('category' => __('Event Planning & Services', 'wprentals'),
                        'category_sign' => 'fas fa-birthday-cake'),
                    'financialservices' => array('category' => __('Financial Services', 'wprentals'),
                        'category_sign' => 'fas fa-money-bill'),
                    'food' => array('category' => __('Food', 'wprentals'),
                        'category_sign' => 'fas fa-utensils'),
                    'health' => array('category' => __('Health & Medical', 'wprentals'),
                        'category_sign' => 'fas fa-briefcase-medical'),
                    'homeservices' => array('category' => __('Home Services ', 'wprentals'),
                        'category_sign' => 'fas fa-wrench'),
                    'hotelstravel' => array('category' => __('Hotels & Travel', 'wprentals'),
                        'category_sign' => 'fas fa-bed'),
                    'localflavor' => array('category' => __('Local Flavor', 'wprentals'),
                        'category_sign' => 'fas fa-coffee'),
                    'localservices' => array('category' => __('Local Services', 'wprentals'),
                        'category_sign' => 'fas fa-dot-circle'),
                    'massmedia' => array('category' => __('Mass Media', 'wprentals'),
                        'category_sign' => 'fas fa-tv'),
                    'nightlife' => array('category' => __('Nightlife', 'wprentals'),
                        'category_sign' => 'fas fa-glass-martini-alt'),
                    'pets' => array('category' => __('Pets', 'wprentals'),
                        'category_sign' => 'fas fa-paw'),
                    'professional' => array('category' => __('Professional Services', 'wprentals'),
                        'category_sign' => 'fas fa-suitcase'),
                    'publicservicesgovt' => array('category' => __('Public Services & Government', 'wprentals'),
                        'category_sign' => 'fas fa-university'),
                    'realestate' => array('category' => __('Real Estate', 'wprentals'),
                        'category_sign' => 'fas fa-building'),
                    'religiousorgs' => array('category' => __('Religious Organizations', 'wprentals'),
                        'category_sign' => 'fas fa-cloud'),
                    'restaurants' => array('category' => __('Restaurants', 'wprentals'),
                        'category_sign' => 'fas fa-utensils'),
                    'shopping' => array('category' => __('Shopping', 'wprentals'),
                        'category_sign' => 'fas fa-shopping-bag'),
                    'transport' => array('category' => __('Transportation', 'wprentals'),
                        'category_sign' => 'fas fa-bus-alt')
        );

        $yelp_terms = wprentals_get_option('wp_estate_yelp_categories', '');
        $yelp_results_no = wprentals_get_option('wp_estate_yelp_results_no', '');
        $yelp_dist_measure = wprentals_get_option('wp_estate_yelp_dist_measure', '');

        $yelp_client_id = wprentals_get_option('wp_estate_yelp_client_id', '');
        $yelp_client_secret = wprentals_get_option('wp_estate_yelp_client_secret', '');
        if ($yelp_client_id == '' || $yelp_client_secret == '') {
            return;
        }

        //$location= "times square";
        $property_address = esc_html(get_post_meta($post_id, 'property_address', true));
        $property_city_array = get_the_terms($post_id, 'property_city');

        if (empty($property_city_array)) {
            return;
        }

        $property_city = $property_city_array[0]->name;
        $location = $property_address . ',' . $property_city;

        $start_lat = get_post_meta($post_id, 'property_latitude', true);
        $start_long = get_post_meta($post_id, 'property_longitude', true);


        $yelp_to_display = '';

        $stored_yelp = get_post_meta($post_id, 'stored_yelp', true);
        $stored_yelp_date = get_post_meta($post_id, 'stored_yelp_data', true);
        $now = time();



        $yelp_to_display = wpestate_request_transient_cache('wpestate_yelp_' . $post_id);

        if ($yelp_to_display === false) {

            foreach ($yelp_terms as $key => $term) {

                $category_name = $yelp_terms_array[$term]['category'];
                $category_icon = $yelp_terms_array[$term]['category_sign'];

                $args = array(
                    'term' => $term,
                    'limit' => $yelp_results_no,
                    'location' => $location
                );


                $details = wpestate_query_api($term, $location);


                if (isset($details->businesses)) {
                    $category = $details->businesses;



                    $yelp_to_display .= '<div class="yelp_bussines_wrapper"><div class="yelp_icon"><i class="' . $category_icon . '"></i></div> <h4 class="yelp_category">' . $category_name . '</h4>';
                    foreach ($category as $wpestate_unit) {


                        $yelp_to_display .= '<div class="yelp_unit">';
                        $yelp_to_display .= '<h5 class="yelp_unit_name">' . $wpestate_unit->name . '</h5>';

                        if (isset($wpestate_unit->coordinates->latitude) && isset($wpestate_unit->coordinates->longitude)) {
                            $yelp_to_display .= ' <span class="yelp_unit_distance"> ' . wpestate_calculate_distance_geo($wpestate_unit->coordinates->latitude, $wpestate_unit->coordinates->longitude, $start_lat, $start_long, $yelp_dist_measure) . '</span>';
                        }

                        $image_path = (string) $wpestate_unit->rating;
                        $image_path = str_replace('.5', '_half', $image_path);
                        $yelp_to_display .= '<img class="yelp_stars" src="' . get_template_directory_uri() . '/img/yelp_small/small_' . $image_path . '.png" alt="' . esc_attr($wpestate_unit->name) . '">';

                        $yelp_to_display .= '</div>';
                    }
                    $yelp_to_display .= '</div>';
                }
            }// end forearch
            wpestate_set_transient_cache('wpestate_yelp_' . $post_id, $yelp_to_display, 24 * 60 * 60);
        }

        return trim($yelp_to_display); //escaped above
    }

endif;


if (!function_exists('wpestate_calculate_distance_geo')):

    function wpestate_calculate_distance_geo($lat, $long, $start_lat, $start_long, $yelp_dist_measure) {

        $angle = $start_long - $long;
        $distance = sin(deg2rad($start_lat)) * sin(deg2rad($lat)) + cos(deg2rad($start_lat)) * cos(deg2rad($lat)) * cos(deg2rad($angle));
        $distance = acos($distance);
        $distance = rad2deg($distance);

        if ($yelp_dist_measure == 'miles') {
            $distance_miles = $distance * 60 * 1.1515;
            return '(' . round($distance_miles, 2) . ' ' . __('miles', 'wprentals') . ')';
        } else {
            $distance_miles = $distance * 60 * 1.1515 * 1.6;
            return '(' . round($distance_miles, 2) . ' ' . __('km', 'wprentals') . ')';
        }
    }

endif;

if (!function_exists('wpestate_has_some_review')) :

    function wpestate_has_some_review($property_id) {
        $total_stars = get_post_meta($property_id, 'property_stars', TRUE);
        $total_stars = json_decode($total_stars, TRUE);


        $total = 0;
        if (is_array($total_stars)) {
            foreach ($total_stars as $key => $value) {
                $total = $total + intval($value);
            }
        }
        return $total;
    }

endif;


if (!function_exists('wpestate_display_property_rating')) {

    /**
     * Simple property rating display function
     *
     * @param $proeprty_id
     * @param string $type (total|fields|complete), 'total' is default
     *
     * @return string
     */
    function wpestate_display_property_rating($proeprty_id, $type = 'total') {
        $star_rating = '';
        $total_stars = get_post_meta($proeprty_id, 'property_stars', TRUE);
        if (!$total_stars) {
            $total_stars = wpestate_calculate_property_rating($proeprty_id);
        }

        if ($total_stars != '') {
            $star_rating = sprintf('<div class="property-rating">%s</div>', wpestate_display_rating($total_stars, $type));
        }

        return $star_rating;
    }

}


if (!function_exists('wpestate_get_max_stars')) {

    /**
     * Set the max. nr. of stars for review
     *
     * @return int
     */
    function wpestate_get_max_stars() {
        return 5;
    }

}

if (!function_exists('wpestate_display_rating')) {

    /**
     * Display star rating
     *
     * @param $rating
     * @param string $type can be total|fields|complete
     *
     * @return string
     */
    function wpestate_display_rating($rating, $type = 'total') {
        $rating_fields = wpestate_get_review_fields();

        if (is_string($rating) && strlen($rating) > 3) {
            $tmp_rating = json_decode($rating, TRUE);
            switch ($type) {
                case 'total':
                    $stars_total = wpestate_get_star_total_value($tmp_rating);
                    $star_rating = wpestate_render_rating_stars($stars_total);
                    break;
                case 'fields':
                    $star_rating = wpestate_render_fields_rating($tmp_rating, $rating_fields['fields']);
                    break;
                case 'complete':
                    $star_rating = '<div class="property-rating">' . PHP_EOL;
                    $stars_total = wpestate_get_star_total_value($tmp_rating);
                    $star_rating .= wpestate_render_rating_stars($stars_total);
                    $star_rating .= wpestate_render_fields_rating($tmp_rating, $rating_fields['fields']);
                    $star_rating .= '</div> <!-- end .property-rating -->' . PHP_EOL;
                    break;
            }
        } else {
            $star_rating = wpestate_render_rating_stars($rating);
        }

        return $star_rating;
    }

}

if (!function_exists('wpestate_render_rating_stars')) {

    /**
     * Render rating fields
     *
     * @param $rating
     * @param $rating_fields
     *
     * @return string
     */
    function wpestate_render_fields_rating($rating, $rating_fields) {
        $star_rating = '';
        foreach ($rating_fields as $field_key => $field_value) {
            if (isset($rating[$field_key])) {
                $star_rating .= sprintf('<div class="%s">', esc_attr($field_key)) . PHP_EOL;
                $star_rating .= sprintf('<span class="rating_legend">%s</span>', esc_html($field_value)) . PHP_EOL;
                $star_rating .= wpestate_render_rating_stars(intval($rating[$field_key]));
                $star_rating .= sprintf('</div><!-- end .%s -->', esc_attr($field_key)) . PHP_EOL;
            }
        }

        return $star_rating;
    }

}

if (!function_exists('wpestate_render_rating_stars')) {

    /**
     * Renders the actual star rating
     *
     * @param $rating
     *
     * @return string
     */
    function wpestate_render_rating_stars($rating) {
        $max_rating = wpestate_get_max_stars();
        if (floor($rating) < $rating) { // add '&& 1 == 0' to disable half star rating
            $half_stars = '<i class="fas fa-star-half-alt"></i>';
        } else {
            $half_stars = '';
            $rating = ceil($rating); // to fix if half star rating is disabled
        }
        $full_stars = str_repeat('<i class="fas fa-star"></i>', $rating);
        $empty_stars = str_repeat('<i class="far fa-star"></i>', intval(( $max_rating - $rating)));

        return $full_stars . $half_stars . $empty_stars;
    }

}

if (!function_exists('wpestate_get_star_total_value')) {

    /**
     * Find the total rating value
     * (old and new rating system)
     *
     * @param $rating
     *
     * @return mixed
     */
    function wpestate_get_star_total_value($rating) {

        if (isset($rating['total'])) {
            $stars_total = $rating['total'];
        } else if (isset($rating['rating'])) {
            $stars_total = $rating['rating'];
        } else {
            $stars_total = $rating;
        }

        return $stars_total;
    }

}

if (!function_exists('wpestate_get_star_total_rating')) {

    /**
     * Returns the total rating
     *
     * @param $rating
     *
     * @return mixed
     */
    function wpestate_get_star_total_rating($rating) {
        $tmp_rating = json_decode($rating, TRUE);

        return wpestate_get_star_total_value($tmp_rating);
    }

}

if (!function_exists('wpestate_get_review_fields')) {

    /**
     * Define review fields
     *
     * @return array
     */
    function wpestate_get_review_fields() {
        $fields = array(
            'total' => esc_html__('Total', 'wprentals'),
            'fields' => array(
                'accuracy' => esc_html__('Accuracy', 'wprentals'),
                'communication' => esc_html__('Communication', 'wprentals'),
                'cleanliness' => esc_html__('Cleanliness', 'wprentals'),
                'location' => esc_html__('Location', 'wprentals'),
                'check_in' => esc_html__('Check-In', 'wprentals'),
                'value' => esc_html__('Value', 'wprentals'),
            )
        );

        return $fields;
    }

}

add_action('delete_comment', 'wpestate_delete_comment_admin');
add_action('trash_comment', 'wpestate_delete_comment_admin');
add_action('untrash_comment', 'wpestate_delete_comment_admin');

function wpestate_delete_comment_admin($comment_id) {
    $comment = get_comment($comment_id);
    $comment_post_id = $comment->comment_post_ID;
    wpestate_calculate_property_rating($comment_post_id);
}

if (!function_exists('wpestate_calculate_property_rating')) {

    /**
     * Calculates the property rating
     *
     * @param $property_id
     *
     * @return string|void
     */
    function wpestate_calculate_property_rating($property_id) {
        if (!$property_id) {
            return;
        }


        $reviews = get_comments(array('post_id' => $property_id));
        $category_fields = wpestate_get_review_fields();
        $count_old_reviews = 0;
        $count_new_reviews = 0;
        $sum_old_reviews = 0;
        $stars_in_fields = array();
        $stars_fields = array();
        $stars_averages = array();
        $store = array();

        foreach ($reviews as $review) {
            $raw_comment_rating = get_comment_meta($review->comment_ID, 'review_stars', TRUE);

            switch (TRUE) {
                // Old reviews
                case ( is_numeric($raw_comment_rating) ) :
                    $count_old_reviews ++;
                    $sum_old_reviews = $sum_old_reviews + intval($raw_comment_rating);
                    $stars_in_fields['total'][] = intval($raw_comment_rating);
                    break;

                // New reviews
                case ( is_string($raw_comment_rating) ):
                    $count_new_reviews ++;
                    $tmp_rating = json_decode($raw_comment_rating, TRUE);
                    $stars_in_fields['total'][] = $tmp_rating['rating'];

                    // gather all stars per field in an array
                    foreach ($category_fields['fields'] as $field_key => $field_value) {
                        $stars_in_fields[$field_key][] = $tmp_rating[$field_key];
                    }

                    break;
            }
        }

        // Sums per fields
        foreach ($category_fields['fields'] as $field_key => $field_value) {
            if (isset($stars_in_fields[$field_key])) {
                $stars_fields[$field_key] = array_sum($stars_in_fields[$field_key]);
                $tmp_round = round($stars_fields[$field_key] / count($stars_in_fields[$field_key]), 1);
                $stars_averages[$field_key] = wpestate_round_to_nearest_05($tmp_round);
                $store[] = sprintf('"%s": %s', $field_key, $stars_averages[$field_key]);
            }
        }

        // Calc total rating
        if (($count_new_reviews + $count_old_reviews) != 0) {
            $all_reviews_total = array_sum($stars_in_fields['total']) / ( ( $count_new_reviews + $count_old_reviews ) );
        } else {
            $all_reviews_total = 0;
        }

        $property_rating['total'] = wpestate_round_to_nearest_05($all_reviews_total);
        // Construct rating string for db
        $store[] = sprintf('"%s": %s', 'rating', $property_rating['total']);
        $star_rating = '{' . implode(',', $store) . '}';
        update_post_meta($property_id, 'property_stars', $star_rating);
        return $star_rating;
    }

}


if (!function_exists('wpestate_round_to_nearest_05')) {

    /**
     * Rounds a number to the nearest .5
     *
     * examples: 4.5 => 4.5; 4.3 => 4; 4.7 => 5;
     *
     * @param $round
     *
     * @return float
     */
    function wpestate_round_to_nearest_05($round) {
        $tmp_remainder = $round - floor($round);

        switch (TRUE) {
            case ( $tmp_remainder > .5 ):
                $rounded = ceil($round);
                break;
            case ( $tmp_remainder < .5 ):
                $rounded = floor($round);
                break;
            default:
                $rounded = $round;
                break;
        }

        return $rounded;
    }

}

if (!function_exists('wpestate_query_vars')) {

    /**
     * Add reviews paging
     *
     * @param $vars
     *
     * @return array
     */
    function wpestate_query_vars($vars) {
        $vars[] = 'rp';

        return $vars;
    }

}

add_filter('query_vars', 'wpestate_query_vars');

if (!function_exists('wpestate_get_current_url')) {

    /**
     * Get the current URL
     *
     * @return string
     */
    function wpestate_get_current_url() {
        global $wp;
        $current_url = add_query_arg($wp->query_string, '', esc_url(home_url($wp->request)));

        return $current_url;
    }

}

if (!function_exists('wpestate_review_paging_url')) {

    /**
     * Create a new URL depending on filter URL
     *
     * @param $param
     * @param string $add_rem
     *
     * @return string
     */
    function wpestate_review_paging_url($param, $add_rem = 'add') {

        // Add or remove parameters from URL
        if (!empty($param) && $add_rem == 'add') {
            if ($param !== 'rp') {
                $current_url = remove_query_arg('rp');
            }
            $new_url = add_query_arg($param, $current_url);
        } else if (!empty($param) && $add_rem == 'rem') {
            $query_keys = array_keys($param);
            $new_url = remove_query_arg($query_keys);
        } else {
            $new_url = $current_url;
        }

        $tmp_url = preg_replace('/\/page\/[0-9]{0,9}/', '', $new_url);
        $new_url = $tmp_url.'#wprentals_review_pagination';

        return $new_url;
    }

}


if (!function_exists('wpestate_review_pagination')) {

    /**
     * Display review pagination
     *
     * @param string $pages
     * @param int $range
     */
    function wpestate_review_pagination($pages = '', $range = 2) {
        global $post;
        $showitems = ( $range * 2 ) + 1;
        $cpaged = ( get_query_var('rp') != '' ) ? get_query_var('rp') : 1;

        if ($pages == '') {
            if (!$pages) {
                $pages = 1;
            }
        }

        if (1 != $pages) {
            echo '<ul class="pagination pagination_nojax" id="wprentals_review_pagination">';

            if (( $cpaged - 1 ) <= 1) {
                $prev_page = wpestate_review_paging_url(array('rp' => ''), 'rem');
            } else {
                $prev_page = wpestate_review_paging_url(array('rp' => $cpaged - 1));
            }

           
            printf('<li class="roundleft"><a href="%s"><i class="fas fa-chevron-left"></i></a></li>', esc_url($prev_page));

            for ($i = 1; $i <= $pages; $i ++) {
                if (1 != $pages && (!( $i >= $cpaged + $range + 1 || $i <= $cpaged - $range - 1 ) || $pages <= $showitems )) {
                    $active = ( $cpaged == $i ) ? ' class=active ' : '';
                    printf('<li %s><a href="%s" >%d</a><li>', esc_attr($active), esc_url(wpestate_review_paging_url(array('rp' => $i))), $i);
                }
            }

            $next_page = wpestate_review_paging_url(array('rp' => $cpaged + 1));
            if (( $cpaged + 1 ) > $pages) {
                $next_page = wpestate_review_paging_url(array('rp' => $cpaged));
            }
          
            printf('<li class="roundright"><a href="%s"><i class="fas fa-chevron-right"></i></a><li>', esc_url($next_page));

            echo "</ul>";
        }
    }

} // end   wpestate_review_pagination



if (!function_exists('wpestate_return_all_fields')):

    function wpestate_return_all_fields($is_mandatory = 0) {

        $all_submission_fields = $all_mandatory_fields = array(
             
            'title' => __('Title', 'wprentals'),
            'prop_category_submit' => __('Main Category', 'wprentals'),
            'prop_action_category_submit' => __('Second Category', 'wprentals'),
            'property_city_front' => __('Property/Item City', 'wprentals'),
            'property_area_front' => __('Property/Item Area', 'wprentals'),
            'property_description' => __('Description', 'wprentals'),
            'property_affiliate' => __('Affiliate link', 'wprentals'),
            'property_price' => __('Listing Price', 'wprentals'),
            'property_taxes' => __('Listing Taxes', 'wprentals'),
            'property_price_per_week' => __('Listing Price per day for 7 or more booked days', 'wprentals'),
            'property_price_per_month' => __('Listing Price per day for 30 or more booked days', 'wprentals'),
            'price_per_weekeend' => __('Listing Price per weekend', 'wprentals'),
            'cleaning_fee' => __('Cleaning Fee', 'wprentals'),
            'city_fee' => __('City Fee', 'wprentals'),
            'min_days_booking' => __('Minimum Days', 'wprentals'),
            'security_deposit' => __('Security Deposit', 'wprentals'),
            'early_bird_percent' => __('Early Bird Discount', 'wprentals'),
            'extra_price_per_guest' => __('Extra Price per Guest', 'wprentals'),
            'checkin_change_over' => __('Start check-in on ', 'wprentals'),
            'checkin_checkout_change_over' => __('Start/End check-in on ', 'wprentals'),
            'extra_options' => __('Extra Booking options', 'wprentals'),
            'custom_prices' => __('Custom Price', 'wprentals'),
            'attachid' => __('Property Media', 'wprentals'),
            'embed_video_id' => __('Embed Video Id', 'wprentals'),
            'embed_video_type' => __('Embed Video Type', 'wprentals'),
            'virtual_tour' => __('Virtual Tour', 'wprentals'),
            'property_size' => __('property Size', 'wprentals'),
            'property_rooms' => __('Property Rooms', 'wprentals'),
            'property_bedrooms' => __('Property Bedrooms', 'wprentals'),
            'property_bathrooms' => __('Property Bathrooms', 'wprentals'),
            'cancellation_policy' => __('Cancellation Policy', 'wprentals'),
            'smoking_allowed' => __('Smoking Allowed', 'wprentals'),
            'pets_allowed' => __('Pets Allowed', 'wprentals'),
            'party_allowed' => __('Party Allowed', 'wprentals'),
            'children_allowed' => __('Children Allowed', 'wprentals'),
            'other_rules' => __('Other Rules', 'wprentals'),
            'property_address' => __('Property Address', 'wprentals'),
            'property_zip' => __('Property Zip', 'wprentals'),
            'property_county' => __('Property County', 'wprentals'),
            'property_state' => __('Property State', 'wprentals'),
            'property_map' => __('Property Map', 'wprentals'),
            'property_latitude' => __('Property Latitude', 'wprentals'),
            'property_longitude' => __('Property Longitude', 'wprentals'),
            'google_camera_angle' => __('Google Camera Angle', 'wprentals'),
            'children_as_guests'=> __('Do Not Consider Children as Guests', 'wprentals'),
            'max_extra_guest_no'          =>  __('Maximum Extra guests above capacity','wprentals'),
        );
        if ($is_mandatory == 0) {
            unset($all_submission_fields['title']);
        
        } else {
   
            unset($all_submission_fields['property_map']);
            unset($all_submission_fields['avalability_calendar']);
            unset($all_mandatory_fields['extra_options']);
            unset($all_submission_fields['children_as_guests']);
        }

        $i = 0;

        $custom_fields = get_option('wpestate_custom_fields_list');




        if (!empty($custom_fields)) {
            while ($i < count($custom_fields['add_field_name'])) {
                $data = wprentals_prepare_non_latin($custom_fields['add_field_name'][$i], $custom_fields['add_field_label'][$i]);
                $all_submission_fields[$data['key']] = $data['label'];
                $i++;
            }
        }

//
//    $feature_list       =   esc_html( get_option('wp_estate_feature_list','') );
//    $feature_list_array =   explode( ',',$feature_list);
//
//    foreach ($feature_list_array as $key=>$checker) {
//        $data= wprentals_prepare_non_latin($checker,$checker);
//        $all_submission_fields[ $data['key'] ]=$data['label'];
//    }


        $terms = get_terms(array(
            'taxonomy' => 'property_features',
            'hide_empty' => false,
                ));
        foreach ($terms as $key => $term) {
            $all_submission_fields[$term->slug] = $term->name;
        }


        return $all_submission_fields;
    }

endif;

function wprentals_prepare_non_latin($key, $label) {

    $label = stripslashes($label);

    $slug = stripslashes($key);
    $slug = str_replace(' ', '-', $key);
    $slug = htmlspecialchars($slug, ENT_QUOTES);
    $slug = wpestate_limit45(sanitize_title($slug));
    $slug = sanitize_key($slug);


    $return = array();
    $return['key'] = trim($slug);
    $return['label'] = trim($label);
    return $return;
}

function wpestate_mandatory_array_non_latin($key) {
    return (sanitize_key($key));
}

if (!function_exists('wpestate_return_custom_unit_fields')):

    function wpestate_return_custom_unit_fields($selected_val, $for = '') {

        $all_fields = array(
            'none' => __('Leave Blank', 'wprentals'),
            'property_category' => __('Main Category', 'wprentals'),
            'property_action_category' => __('Second Category', 'wprentals'),
            'property_city' => __('Property/Item City', 'wprentals'),
            'property_area' => __('Property/Item Area', 'wprentals'),
            'property_price' => __('Property/Item Price', 'wprentals'),
            'guest_no' => __('Property/Item Guests Capacity', 'wprentals'),
            'property_taxes' => __('Property/Item Taxes', 'wprentals'),
            'property_price_per_week' => __('Property/Item Price per day for 7 or more booked days', 'wprentals'),
            'property_price_per_month' => __('Property/Item Price per day for 30 or more booked days', 'wprentals'),
            'price_per_weekeend' => __('Property/Item Price per weekend', 'wprentals'),
            'cleaning_fee' => __('Cleaning Fee', 'wprentals'),
            'city_fee' => __('City Fee', 'wprentals'),
            'min_days_booking' => __('Minimum Days', 'wprentals'),
            'security_deposit' => __('Security Deposit', 'wprentals'),
            'extra_price_per_guest' => __('Extra Price per Guest', 'wprentals'),
            'property_size' => __('Property Size', 'wprentals'),
            'property_rooms' => __('Property Rooms', 'wprentals'),
            'property_bedrooms' => __('Property Bedrooms', 'wprentals'),
            'property_bathrooms' => __('Property Bathrooms', 'wprentals'),
            'property_address' => __('Property Address', 'wprentals'),
            'property_zip' => __('Property Zip', 'wprentals'),
            'property_county' => __('Property County', 'wprentals'),
            'property_state' => __('Property State', 'wprentals'),
            'property_country' => __('Property Country', 'wprentals'),
        );
        if ($for == '_infobox') {
            unset($all_fields['property_category']);
            unset($all_fields['property_action_category']);
            unset($all_fields['property_price']);
            unset($all_fields['property_price_per_week']);
            unset($all_fields['property_price_per_month']);
            unset($all_fields['price_per_weekeend']);
            unset($all_fields['cleaning_fee']);
            unset($all_fields['city_fee']);
            unset($all_fields['extra_price_per_guest']);
        }

        if ($for == '_property') {
            unset($all_fields['property_price']);
            unset($all_fields['property_price_per_week']);
            unset($all_fields['property_price_per_month']);
            unset($all_fields['price_per_weekeend']);
            unset($all_fields['cleaning_fee']);
            unset($all_fields['city_fee']);
            unset($all_fields['extra_price_per_guest']);
        }


        $i = 0;
        $custom_fields = wprentals_get_option('wpestate_custom_fields_list', '');

        if (!empty($custom_fields)) {
            while ($i < count($custom_fields)) {
                $name = stripslashes($custom_fields[$i][0]);
                $slug = str_replace(' ', '-', $name);
                $label = stripslashes($custom_fields[$i][1]);
                $slug = htmlspecialchars($slug, ENT_QUOTES);

                $all_fields[strtolower($slug)] = $label;
                $i++;
            }
        }

        $return_options = '<select id="unit_field_value" name="unit_field_value' . $for . '[]" style="width:140px;">';
        foreach ($all_fields as $key => $checker) {
            $return_options .= '<option value="' . $key . '" ';
            if ($key === htmlspecialchars(stripslashes($selected_val), ENT_QUOTES)) {
                $return_options .= ' selected ';
            }
            $return_options .= '>' . $checker . '</option>';
        }
        $return_options .= '</select>';
        return $return_options;
    }

endif;



if (!function_exists('redux_wpestate_return_custom_unit_fields')):

    function redux_wpestate_return_custom_unit_fields($select_name, $selected_val, $for = '') {

        $all_fields = array(
            'none' => __('Leave Blank', 'wprentals'),
            'property_category' => __('Main Category', 'wprentals'),
            'property_action_category' => __('Second Category', 'wprentals'),
            'property_city' => __('Property/Item City', 'wprentals'),
            'property_area' => __('Property/Item Area', 'wprentals'),
            'property_price' => __('Property/Item Price', 'wprentals'),
            'guest_no' => __('Property/Item Guests Capacity', 'wprentals'),
            'property_taxes' => __('Property/Item Taxes', 'wprentals'),
            'property_price_per_week' => __('Property/Item Price per day for 7 or more booked days', 'wprentals'),
            'property_price_per_month' => __('Property/Item Price per day for 30 or more booked days', 'wprentals'),
            'price_per_weekeend' => __('Property/Item Price per weekend', 'wprentals'),
            'cleaning_fee' => __('Cleaning Fee', 'wprentals'),
            'city_fee' => __('City Fee', 'wprentals'),
            'min_days_booking' => __('Minimum Days', 'wprentals'),
            'security_deposit' => __('Security Deposit', 'wprentals'),
            'extra_price_per_guest' => __('Extra Price per Guest', 'wprentals'),
            'property_size' => __('Property Size', 'wprentals'),
            'property_rooms' => __('Property Rooms', 'wprentals'),
            'property_bedrooms' => __('Property Bedrooms', 'wprentals'),
            'property_bathrooms' => __('Property Bathrooms', 'wprentals'),
            'property_address' => __('Property Address', 'wprentals'),
            'property_zip' => __('Property Zip', 'wprentals'),
            'property_county' => __('Property County', 'wprentals'),
            'property_state' => __('Property State', 'wprentals'),
            'property_country' => __('Property Country', 'wprentals'),
            'max_extra_guest_no'=> __('Maximum extra guests above capacity', 'wprentals'),
        );
        if ($for == '_infobox') {
            unset($all_fields['property_category']);
            unset($all_fields['property_action_category']);
            unset($all_fields['property_price']);
            unset($all_fields['property_price_per_week']);
            unset($all_fields['property_price_per_month']);
            unset($all_fields['price_per_weekeend']);
            unset($all_fields['cleaning_fee']);
            unset($all_fields['city_fee']);
            unset($all_fields['extra_price_per_guest']);
        }

        if ($for == '_property') {
            unset($all_fields['property_price']);
            unset($all_fields['property_price_per_week']);
            unset($all_fields['property_price_per_month']);
            unset($all_fields['price_per_weekeend']);
            unset($all_fields['cleaning_fee']);
            unset($all_fields['city_fee']);
            unset($all_fields['extra_price_per_guest']);
        }


        $i = 0;
        $custom_fields = wprentals_get_option('wpestate_custom_fields_list', '');

        if (!empty($custom_fields)) {
            while ($i < count($custom_fields)) {
                $name = stripslashes($custom_fields[$i][0]);
                $slug = str_replace(' ', '-', $name);
                $label = stripslashes($custom_fields[$i][1]);
                $slug = htmlspecialchars($slug, ENT_QUOTES);

                $all_fields[strtolower($slug)] = $label;
                $i++;
            }
        }

        $return_options = '<select id="unit_field_value" name="' . $select_name . '" style="width:140px;">';
        foreach ($all_fields as $key => $checker) {
            $return_options .= '<option value="' . $key . '" ';
            if ($key === htmlspecialchars(stripslashes($selected_val), ENT_QUOTES)) {
                $return_options .= ' selected ';
            }
            $return_options .= '>' . $checker . '</option>';
        }
        $return_options .= '</select>';
        return $return_options;
    }

endif;


if (!function_exists('wpestate_strip_array')):

    function wpestate_strip_array($key) {
        $string = htmlspecialchars(stripslashes(($key)), ENT_QUOTES);
        return wp_specialchars_decode($string);
    }

endif;


if (!function_exists('wpestate_date_picker_translation_return')):

    function wpestate_date_picker_translation_return($selector) {
        $date_lang_status = esc_html(wprentals_get_option('wp_estate_date_lang', ''));
        return '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                        jQuery("#' . $selector . '").datepicker({
                                dateFormat : "yy-mm-dd"
                        },jQuery.datepicker.regional["' . $date_lang_status . '"]).datepicker("widget").wrap(\'<div class="ll-skin-melon"/>\');
                });
                //]]>
            </script>';
    }

endif;

if (!function_exists('wpestate_show_mandatory_fields')):

    function wpestate_show_mandatory_fields() {

        $mandatory_fields = ( wprentals_get_option('wp_estate_mandatory_page_fields', '') );

        if (is_array($mandatory_fields)) {
            $mandatory_fields = array_map("wpestate_strip_array", $mandatory_fields);
        }
        if (is_array($mandatory_fields) && !empty($mandatory_fields)) {
            $all_mandatory_fields = wpestate_return_all_fields(1);
            print '<div class="submit_mandatory col-md-12">';
            _e('These fields are mandatory: ', 'wprentals');
            $lastkey = count($mandatory_fields) - 1;
            foreach ($mandatory_fields as $key => $value) {
                if( isset($all_mandatory_fields[$value]) ){
                    print esc_html($all_mandatory_fields[$value]);
                }
                if ($key !== $lastkey) {
                    print ', ';
                };
            }
            print '</div>';
        }
    }

endif;


if (!function_exists('wpestate_show_labels')):

    function wpestate_show_labels($label, $type, $book_type = '') {
        $week_days_no = intval(wprentals_get_option('wp_estate_week_days'));
        $month_days_no = intval(wprentals_get_option('wp_estate_month_days'));
        $labels = array(
            'check_in' => array(
                '0' => esc_html__('Check-in', 'wprentals'),
                '1' => esc_html__('Start Date', 'wprentals'),
            ),
            'check_out' => array(
                '0' => esc_html__('Check-Out', 'wprentals'),
                '1' => esc_html__('End Date', 'wprentals'),
            ),
            'per night' => array(
                '0' => esc_html__('per night', 'wprentals'),
                '1' => esc_html__('per day', 'wprentals'),
                '2' => esc_html__('per hour', 'wprentals'),
            ),
            'per_night' => array(
                '0' => esc_html__('per night', 'wprentals'),
                '1' => esc_html__('per day', 'wprentals'),
                '2' => esc_html__('per hour', 'wprentals'),
            ),
            'per_night2' => array(
                '0' => esc_html__('/night', 'wprentals'),
                '1' => esc_html__('/day', 'wprentals'),
                '2' => esc_html__('/hour', 'wprentals'),
            ),
            'price_label' => array(
                '0' => esc_html__('Price per night', 'wprentals'),
                '1' => esc_html__('Price per day', 'wprentals'),
                '2' => esc_html__('Price per hour', 'wprentals'),
            ),
            'price_week_label' => array(
                '0' => sprintf(esc_html__('Price per night (%sd+)', 'wprentals'), $week_days_no),
                '1' => sprintf(esc_html__('Price per day (%sd+)', 'wprentals'), $week_days_no),
                '2' => sprintf(esc_html__('Price per hour (%sh+)', 'wprentals'), $week_days_no),
            ),
            'price_month_label' => array(
                '0' => sprintf(esc_html__('Price per night (%sd+)', 'wprentals'), $month_days_no),
                '1' => sprintf(esc_html__('Price per day (%sd+)', 'wprentals'), $month_days_no),
                '2' => sprintf(esc_html__('Price per hour (%sh+)', 'wprentals'), $month_days_no),
            ),
            'nights' => array(
                '0' => esc_html__('nights', 'wprentals'),
                '1' => esc_html__('days', 'wprentals'),
                '2' => esc_html__('hours', 'wprentals'),
            ),
            'night' => array(
                '0' => esc_html__('night', 'wprentals'),
                '1' => esc_html__('day', 'wprentals'),
                '2' => esc_html__('hour', 'wprentals'),
            ),
            'nights_custom_price' => array(
                '0' => esc_html__('nights with custom price', 'wprentals'),
                '1' => esc_html__('days with custom price', 'wprentals'),
                '2' => esc_html__('hours with custom price', 'wprentals'),
            ),
            'days_custom_price' => array(
                '0' => esc_html__('nights with weekend price', 'wprentals'),
                '1' => esc_html__('days with weekend price', 'wprentals'),
                '2' => esc_html__('hours with weekend price', 'wprentals'),
            ),
            'price_week_label_ext' => array(
                '0' => sprintf(esc_html__('Price per night if the item is rented for more than %s nights or more than %s nights', 'wprentals'), $week_days_no, $month_days_no),
                '1' => sprintf(esc_html__('Price per day if the item is rented for more than 1 week %s days or more than %s days', 'wprentals'), $week_days_no, $month_days_no),
                '2' => sprintf(esc_html__('Price per hour if the item is rented for more than %s hours or more than %s hours', 'wprentals'), $month_days_no, $week_days_no),
            ),
            'price_month_label_ext' => array(
                '0' => sprintf(esc_html__('Price per night (%sd+)', 'wprentals'), $month_days_no),
                '1' => sprintf(esc_html__('Price per day (%sd+)', 'wprentals'), $month_days_no),
                '2' => sprintf(esc_html__('Price per hour (%sh+)', 'wprentals'), $month_days_no),
            ),
            'min_unit' => array(
                '0' => esc_html__('Minimum nights of booking', 'wprentals'),
                '1' => esc_html__('Minimum days of booking', 'wprentals'),
                '2' => esc_html__('Minimum hours of booking', 'wprentals'),
            ),
            'early_bird' => array(
                '0' => esc_html__('Early Bird Discount - in % from the price per night', 'wprentals'),
                '1' => esc_html__('Early Bird Discount - in % from the price per day', 'wprentals'),
                '2' => esc_html__('Early Bird Discount - in % from the price per hour', 'wprentals'),
            ),
            'extra_price_per_guest' => array(
                '0' => esc_html__('Extra Price per guest per night in', 'wprentals'),
                '1' => esc_html__('Extra Price per guest per day in', 'wprentals'),
                '2' => esc_html__('Extra Price per guest per hour in', 'wprentals'),
            ),
            'no_of_nights' => array(
                '0' => esc_html__('No of nights', 'wprentals'),
                '1' => esc_html__('No of days', 'wprentals'),
                '2' => esc_html__('No of hours', 'wprentals'),
            ),
            'per_night_per_guest' => array(
                '0' => esc_html__('Per Night per Guest', 'wprentals'),
                '1' => esc_html__('Per Day per Guest', 'wprentals'),
                '2' => esc_html__('Per Hour per Guest', 'wprentals'),
            ),
        );

        $key = '';
        if ($type == 0) {
            $key = $type;
            if ($book_type == 2) {
                $key = $book_type;
            }
        } else {
            if ($book_type != '') {
                $key = $book_type;
            }
        }

        if (isset($labels[$label][$key])) {
            return $labels[$label][$key];
        } else {
            return $labels[$label][$type];
        }
    }

endif;


if (!function_exists('wpestate_header_image')):

    function wpestate_header_image($image) {
        global $post;
        $page_template='';
        if(isset($post->ID)){
            $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        }
        $paralax_header = wprentals_get_option('wp_estate_paralax_header', '');
        if (isset($post->ID)) {

            if ($page_template=='splash_page.php' ) {
                $wpestate_header_type = 20;
                $image = esc_html(wprentals_get_option('wp_estate_splash_image', 'url'));
                $img_full_screen = 'no';
                $img_full_back_type = '';
                $page_header_title_over_image = stripslashes(esc_html(wprentals_get_option('wp_estate_splash_page_title', '')));
                $page_header_subtitle_over_image = stripslashes(esc_html(wprentals_get_option('wp_estate_splash_page_subtitle', '')));
                $page_header_image_height = 600;
                $page_header_overlay_val = esc_html(wprentals_get_option('wp_estate_splash_overlay_opacity', ''));
                $page_header_overlay_color = esc_html(wprentals_get_option('wp_estate_splash_overlay_color', ''));
                $wp_estate_splash_overlay_image = esc_html(wprentals_get_option('wp_estate_splash_overlay_image', 'url'));
            } else {
                $img_full_screen = esc_html(get_post_meta($post->ID, 'page_header_image_full_screen', true));
                $img_full_back_type = esc_html(get_post_meta($post->ID, 'page_header_image_back_type', true));
                $page_header_title_over_image = stripslashes(esc_html(get_post_meta($post->ID, 'page_header_title_over_image', true)));
                $page_header_subtitle_over_image = stripslashes(esc_html(get_post_meta($post->ID, 'page_header_subtitle_over_image', true)));
                $page_header_image_height = floatval(get_post_meta($post->ID, 'page_header_image_height', true));
                $page_header_overlay_val = esc_html(get_post_meta($post->ID, 'page_header_overlay_val', true));
                $page_header_overlay_color = esc_html(get_post_meta($post->ID, 'page_header_overlay_color', true));
                $wp_estate_splash_overlay_image = '';
            }

            if ($page_header_overlay_val == '') {
                $page_header_overlay_val = 1;
            }
            if ($page_header_image_height == 0) {
                $page_header_image_height = 580;
            }

            print '<div class="wpestate_header_image full_screen_' . $img_full_screen . ' parallax_effect_' . $paralax_header . '" style="background-image:url(' . esc_url($image) . ');';
            if ($page_header_image_height != 0) {
                print ' height:' . $page_header_image_height . 'px; ';
            }
            if ($img_full_back_type == 'contain') {
                print '  background-size: contain; ';
            }
            print '">';

            if ($page_header_overlay_color != '' || $wp_estate_splash_overlay_image != '') {
                print '<div class="wpestate_header_image_overlay" style="background-color:' . $page_header_overlay_color . ';opacity:' . $page_header_overlay_val . ';background-image:url(' . esc_url($wp_estate_splash_overlay_image) . ');"></div>';
            }

            if ($page_header_title_over_image != '') {
                print '<div class="heading_over_image_wrapper" >';
                print '<h1 class="heading_over_image">' . $page_header_title_over_image . '</h1>';

                if ($page_header_subtitle_over_image != '') {
                    print '<div class="subheading_over_image">' . $page_header_subtitle_over_image . '</div>';
                }

                print '</div>';
            }


            print'</div>';
        } else {
            print '<div class="wpestate_header_image " style="background-image:url(' . esc_url($image) . ')"></div>';
        }
    }

endif;


if (!function_exists('wpestate_show_advanced_search')):

    function wpestate_show_advanced_search($post_id) {

        if (!wpestate_float_search_placement($post_id) && !wpestate_half_map_conditions($post_id)) {
            if (!wpestate_is_user_dashboard()) {
                include(locate_template('templates/advanced_search.php') );
            }
        }
    }

endif;



if (!function_exists('wpestate_float_search_placement')):

    function wpestate_float_search_placement($post_id) {
        global $post;
        $float_form_top_local = '';
        $float_search_form = esc_html(wprentals_get_option('wp_estate_use_float_search_form', ''));
        $search_float_type = 0;


        if (isset($post->ID)) {
            $search_float_type = intval(get_post_meta($post->ID, 'use_float_search_form_local_set', true));
        }

        if (wpestate_half_map_conditions($post_id)) {
            return false;
        }

        if ($search_float_type == 0 && $float_search_form == 'yes') {
            return true;
        } else if ($search_float_type == 2) {
            return true;
        } else {
            return false;
        }
    }

endif;

/*
*
*
* Check half map conditions
*
*
*/

if (!function_exists('wpestate_half_map_conditions')):

    function wpestate_half_map_conditions($pos_id) {
        global $post;
        $page_template='';
        if(isset($pos_id)){
            $page_template = get_post_meta( $pos_id, '_wp_page_template', true );
        }

        if (!is_category() && !is_tax() && $page_template == 'property_list_half.php' ) {
            return true;
        } else if (( is_tax('') ) && wprentals_get_option('wp_estate_property_list_type', '') == 2) {
            $taxonomy = get_query_var('taxonomy');
            if ($taxonomy == 'property_category_agent' ||
                    $taxonomy == 'property_action_category_agent' ||
                    $taxonomy == 'property_city_agent' ||
                    $taxonomy == 'property_area_agent' ||
                    $taxonomy == 'property_county_state_agent' ||
                    $taxonomy == 'category_agency' ||
                    $taxonomy == 'action_category_agency' ||
                    $taxonomy == 'city_agency' ||
                    $taxonomy == 'area_agency' ||
                    $taxonomy == 'county_state_agency' ||
                    $taxonomy == 'property_category_developer' ||
                    $taxonomy == 'property_action_developer' ||
                    $taxonomy == 'property_city_developer' ||
                    $taxonomy == 'property_area_developer' ||
                    $taxonomy == 'property_county_state_developer'
            ) {
                return false;
            } else {
                return true;
            }
        } else if ($page_template=='advanced_search_results.php' && wprentals_get_option('wp_estate_property_list_type_adv', '') == 2) {
            return true;
        } else {
            return false;
        }
    }

endif;

/*
*
*
* Check if user dashboard
*
*
*/

if (!function_exists('wpestate_is_user_dashboard')):

    function wpestate_is_user_dashboard() {

        $page_template='';
        if(isset($post->ID)){
            $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        }
        
        $dashboard_pages=array(
            'user_dashboard_main.php',
            'user_dashboard.php' ,
            'user_dashboard_add_step1.php',
            'user_dashboard_allinone.php',
            'user_dashboard_edit_listing.php',
            'user_dashboard_favorite.php',
            'user_dashboard_inbox.php',
            'user_dashboard_invoices',
            'user_dashboard_my_bookings',
            'user_dashboard_my_reservations',
            'user_dashboard_packs',
            'user_dashboard_profile',
        );

        if (in_array($page_template, $dashboard_pages) ) {
            return true;
        } else {
            return false;
        }
    }

endif;










/*
*
*
* show taxonomy header
*
*
*/

if (!function_exists('wpestate_show_tax_header')):

    function wpestate_show_tax_header() {
        $taxonmy = get_query_var('taxonomy');

//property_features
        $term = get_query_var('term');
        $term_data = get_term_by('slug', $term, $taxonmy);
        $place_id = $term_data->term_id;
        $category_attach_id = '';
        $category_tax = '';
        $category_featured_image = '';
        $category_name = '';
        $category_featured_image_url = '';
        $term_meta = get_option("taxonomy_$place_id");
        $category_tagline = '';
        $wpestate_page_tax = '';

        if (isset($term_meta['category_featured_image'])) {
            $category_featured_image = $term_meta['category_featured_image'];
        }

        if (isset($term_meta['category_attach_id'])) {
            $category_attach_id = $term_meta['category_attach_id'];
            $category_featured_image = wp_get_attachment_image_src($category_attach_id, 'full');
            $category_featured_image_url = $category_featured_image[0];
        }

        if (isset($term_meta['category_tax'])) {
            $category_tax = $term_meta['category_tax'];
            $term = get_term($place_id, $category_tax);
            $category_name = $term->name;
        }

        if (isset($term_meta['category_tagline'])) {
            $category_tagline = stripslashes($term_meta['category_tagline']);
        }

        if (isset($term_meta['page_tax'])) {
            $wpestate_page_tax = $term_meta['page_tax'];
        }
        if ($taxonmy == 'property_features') {
            $category_featured_image_url = '';
        }


        print '<div class="listing_main_image" id="listing_main_image_photo"  style="background-image: url(' . $category_featured_image_url . ')">';
        print '<h1 class="entry-title entry-tax">' . $term_data->name . '</h1>';
        print '<div class="tax_tagline">' . $category_tagline . '</div>';
        print '<div class="img-overlay"></div>';
        print '</div>';
    }

endif;


if (!function_exists('wpestate_font_awesome_list')):

    function wpestate_font_awesome_list() {
        print '<div class="iconpicker-items_wrapper">';
        print '<div class="iconpicker-items_wrapper_close">x</div>';
        print '<input type="text" id="icon_look_for" class="icon_look_for_class" value="" placeholder="' . esc_html__('Type to Search', 'wprentals') . '">';
        print '<div class="iconpicker-items"><a role="button" href="#" class="iconpicker-item" title=".fab fa-500px"><i class="fab fa-500px"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-accessible-icon" data-search-terms="accessibility wheelchair handicap person wheelchair-alt "><i class="fab fa-accessible-icon"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-accusoft"><i class="fab fa-accusoft"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-address-book"><i class="fas fa-address-book"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-address-book"><i class="far fa-address-book"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-address-card"><i class="fas fa-address-card"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-address-card"><i class="far fa-address-card"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-adjust" data-search-terms="contrast "><i class="fas fa-adjust"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-adn"><i class="fab fa-adn"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-adversal"><i class="fab fa-adversal"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-affiliatetheme"><i class="fab fa-affiliatetheme"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-algolia"><i class="fab fa-algolia"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-align-center" data-search-terms="middle text "><i class="fas fa-align-center"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-align-justify" data-search-terms="text "><i class="fas fa-align-justify"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-align-left" data-search-terms="text "><i class="fas fa-align-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-align-right" data-search-terms="text "><i class="fas fa-align-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-amazon"><i class="fab fa-amazon"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-amazon-pay"><i class="fab fa-amazon-pay"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-ambulance" data-search-terms="vehicle support help "><i class="fas fa-ambulance"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-american-sign-language-interpreting"><i class="fas fa-american-sign-language-interpreting"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-amilia"><i class="fab fa-amilia"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-anchor" data-search-terms="link "><i class="fas fa-anchor"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-android" data-search-terms="robot "><i class="fab fa-android"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-angellist"><i class="fab fa-angellist"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-angle-double-down" data-search-terms="arrows "><i class="fas fa-angle-double-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-angle-double-left" data-search-terms="laquo quote previous back arrows "><i class="fas fa-angle-double-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-angle-double-right" data-search-terms="raquo quote next forward arrows "><i class="fas fa-angle-double-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-angle-double-up" data-search-terms="arrows "><i class="fas fa-angle-double-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-angle-down" data-search-terms="arrow "><i class="fas fa-angle-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-angle-left" data-search-terms="previous back arrow "><i class="fas fa-angle-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-angle-right" data-search-terms="next forward arrow "><i class="fas fa-angle-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-angle-up" data-search-terms="arrow "><i class="fas fa-angle-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-angrycreative"><i class="fab fa-angrycreative"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-angular"><i class="fab fa-angular"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-app-store"><i class="fab fa-app-store"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-app-store-ios"><i class="fab fa-app-store-ios"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-apper"><i class="fab fa-apper"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-apple" data-search-terms="osx food "><i class="fab fa-apple"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-apple-pay"><i class="fab fa-apple-pay"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-archive" data-search-terms="box storage package "><i class="fas fa-archive"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-alt-circle-down" data-search-terms="download arrow-circle-o-down "><i class="fas fa-arrow-alt-circle-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-arrow-alt-circle-down" data-search-terms="download arrow-circle-o-down "><i class="far fa-arrow-alt-circle-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-alt-circle-left" data-search-terms="previous back arrow-circle-o-left "><i class="fas fa-arrow-alt-circle-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-arrow-alt-circle-left" data-search-terms="previous back arrow-circle-o-left "><i class="far fa-arrow-alt-circle-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-alt-circle-right" data-search-terms="next forward arrow-circle-o-right "><i class="fas fa-arrow-alt-circle-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-arrow-alt-circle-right" data-search-terms="next forward arrow-circle-o-right "><i class="far fa-arrow-alt-circle-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-alt-circle-up" data-search-terms="arrow-circle-o-up "><i class="fas fa-arrow-alt-circle-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-arrow-alt-circle-up" data-search-terms="arrow-circle-o-up "><i class="far fa-arrow-alt-circle-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-circle-down" data-search-terms="download "><i class="fas fa-arrow-circle-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-circle-left" data-search-terms="previous back "><i class="fas fa-arrow-circle-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-circle-right" data-search-terms="next forward "><i class="fas fa-arrow-circle-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-circle-up"><i class="fas fa-arrow-circle-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-down" data-search-terms="download "><i class="fas fa-arrow-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-left" data-search-terms="previous back "><i class="fas fa-arrow-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-right" data-search-terms="next forward "><i class="fas fa-arrow-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrow-up"><i class="fas fa-arrow-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrows-alt" data-search-terms="expand enlarge fullscreen bigger move reorder resize arrow arrows "><i class="fas fa-arrows-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrows-alt-h" data-search-terms="resize arrows-h "><i class="fas fa-arrows-alt-h"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-arrows-alt-v" data-search-terms="resize arrows-v "><i class="fas fa-arrows-alt-v"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-assistive-listening-systems"><i class="fas fa-assistive-listening-systems"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-asterisk" data-search-terms="details "><i class="fas fa-asterisk"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-asymmetrik"><i class="fab fa-asymmetrik"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-at" data-search-terms="email e-mail "><i class="fas fa-at"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-audible"><i class="fab fa-audible"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-audio-description"><i class="fas fa-audio-description"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-autoprefixer"><i class="fab fa-autoprefixer"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-avianex"><i class="fab fa-avianex"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-aviato"><i class="fab fa-aviato"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-aws"><i class="fab fa-aws"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-backward" data-search-terms="rewind previous "><i class="fas fa-backward"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-balance-scale"><i class="fas fa-balance-scale"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-ban" data-search-terms="delete remove trash hide block stop abort cancel ban prohibit "><i class="fas fa-ban"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-band-aid" data-search-terms="bandage ouch boo boo "><i class="fas fa-band-aid"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-bandcamp"><i class="fab fa-bandcamp"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-barcode" data-search-terms="scan "><i class="fas fa-barcode"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bars" data-search-terms="menu drag reorder settings list ul ol checklist todo list hamburger "><i class="fas fa-bars"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-baseball-ball"><i class="fas fa-baseball-ball"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-basketball-ball"><i class="fas fa-basketball-ball"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bath"><i class="fas fa-bath"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-battery-empty" data-search-terms="power status "><i class="fas fa-battery-empty"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-battery-full" data-search-terms="power status "><i class="fas fa-battery-full"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-battery-half" data-search-terms="power status "><i class="fas fa-battery-half"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-battery-quarter" data-search-terms="power status "><i class="fas fa-battery-quarter"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-battery-three-quarters" data-search-terms="power status "><i class="fas fa-battery-three-quarters"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bed" data-search-terms="travel "><i class="fas fa-bed"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-beer" data-search-terms="alcohol stein drink mug bar liquor "><i class="fas fa-beer"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-behance"><i class="fab fa-behance"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-behance-square"><i class="fab fa-behance-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bell" data-search-terms="alert reminder notification "><i class="fas fa-bell"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-bell" data-search-terms="alert reminder notification "><i class="far fa-bell"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bell-slash"><i class="fas fa-bell-slash"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-bell-slash"><i class="far fa-bell-slash"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bicycle" data-search-terms="vehicle bike gears "><i class="fas fa-bicycle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-bimobject"><i class="fab fa-bimobject"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-binoculars"><i class="fas fa-binoculars"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-birthday-cake"><i class="fas fa-birthday-cake"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-bitbucket" data-search-terms="git bitbucket-square "><i class="fab fa-bitbucket"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-bitcoin"><i class="fab fa-bitcoin"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-bity"><i class="fab fa-bity"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-black-tie"><i class="fab fa-black-tie"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-blackberry"><i class="fab fa-blackberry"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-blind"><i class="fas fa-blind"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-blogger"><i class="fab fa-blogger"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-blogger-b"><i class="fab fa-blogger-b"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-bluetooth"><i class="fab fa-bluetooth"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-bluetooth-b"><i class="fab fa-bluetooth-b"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bold"><i class="fas fa-bold"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bolt" data-search-terms="lightning weather "><i class="fas fa-bolt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bomb"><i class="fas fa-bomb"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-book" data-search-terms="read documentation "><i class="fas fa-book"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bookmark" data-search-terms="save "><i class="fas fa-bookmark"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-bookmark" data-search-terms="save "><i class="far fa-bookmark"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bowling-ball"><i class="fas fa-bowling-ball"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-box"><i class="fas fa-box"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-boxes"><i class="fas fa-boxes"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-braille"><i class="fas fa-braille"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-briefcase" data-search-terms="work business office luggage bag "><i class="fas fa-briefcase"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-btc"><i class="fab fa-btc"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bug" data-search-terms="report insect "><i class="fas fa-bug"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-building" data-search-terms="work business apartment office company "><i class="fas fa-building"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-building" data-search-terms="work business apartment office company "><i class="far fa-building"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bullhorn" data-search-terms="announcement share broadcast louder megaphone "><i class="fas fa-bullhorn"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bullseye" data-search-terms="target "><i class="fas fa-bullseye"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-buromobelexperte"><i class="fab fa-buromobelexperte"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-bus" data-search-terms="vehicle "><i class="fas fa-bus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-buysellads"><i class="fab fa-buysellads"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-calculator"><i class="fas fa-calculator"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-calendar" data-search-terms="date time when event calendar-o "><i class="fas fa-calendar"></i></a><a role="button" href="#" class="iconpicker-item iconpicker-selected bg-primary" title=".far fa-calendar" data-search-terms="date time when event calendar-o "><i class="far fa-calendar"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-calendar-alt" data-search-terms="date time when event calendar "><i class="fas fa-calendar-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-calendar-alt" data-search-terms="date time when event calendar "><i class="far fa-calendar-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-calendar-check" data-search-terms="ok "><i class="fas fa-calendar-check"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-calendar-check" data-search-terms="ok "><i class="far fa-calendar-check"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-calendar-minus"><i class="fas fa-calendar-minus"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-calendar-minus"><i class="far fa-calendar-minus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-calendar-plus"><i class="fas fa-calendar-plus"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-calendar-plus"><i class="far fa-calendar-plus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-calendar-times"><i class="fas fa-calendar-times"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-calendar-times"><i class="far fa-calendar-times"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-camera" data-search-terms="photo picture record "><i class="fas fa-camera"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-camera-retro" data-search-terms="photo picture record "><i class="fas fa-camera-retro"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-car" data-search-terms="vehicle "><i class="fas fa-car"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-caret-down" data-search-terms="more dropdown menu triangle down arrow "><i class="fas fa-caret-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-caret-left" data-search-terms="previous back triangle left arrow "><i class="fas fa-caret-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-caret-right" data-search-terms="next forward triangle right arrow "><i class="fas fa-caret-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-caret-square-down" data-search-terms="more dropdown menu caret-square-o-down "><i class="fas fa-caret-square-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-caret-square-down" data-search-terms="more dropdown menu caret-square-o-down "><i class="far fa-caret-square-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-caret-square-left" data-search-terms="previous back caret-square-o-left "><i class="fas fa-caret-square-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-caret-square-left" data-search-terms="previous back caret-square-o-left "><i class="far fa-caret-square-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-caret-square-right" data-search-terms="next forward caret-square-o-right "><i class="fas fa-caret-square-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-caret-square-right" data-search-terms="next forward caret-square-o-right "><i class="far fa-caret-square-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-caret-square-up" data-search-terms="caret-square-o-up "><i class="fas fa-caret-square-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-caret-square-up" data-search-terms="caret-square-o-up "><i class="far fa-caret-square-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-caret-up" data-search-terms="triangle up arrow "><i class="fas fa-caret-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cart-arrow-down" data-search-terms="shopping "><i class="fas fa-cart-arrow-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cart-plus" data-search-terms="add shopping "><i class="fas fa-cart-plus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-amazon-pay"><i class="fab fa-cc-amazon-pay"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-amex" data-search-terms="amex "><i class="fab fa-cc-amex"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-apple-pay"><i class="fab fa-cc-apple-pay"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-diners-club"><i class="fab fa-cc-diners-club"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-discover"><i class="fab fa-cc-discover"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-jcb"><i class="fab fa-cc-jcb"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-mastercard"><i class="fab fa-cc-mastercard"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-paypal"><i class="fab fa-cc-paypal"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-stripe"><i class="fab fa-cc-stripe"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cc-visa"><i class="fab fa-cc-visa"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-centercode"><i class="fab fa-centercode"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-certificate" data-search-terms="badge star "><i class="fas fa-certificate"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chart-area" data-search-terms="graph analytics area-chart "><i class="fas fa-chart-area"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chart-bar" data-search-terms="graph analytics bar-chart "><i class="fas fa-chart-bar"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-chart-bar" data-search-terms="graph analytics bar-chart "><i class="far fa-chart-bar"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chart-line" data-search-terms="graph analytics line-chart dashboard "><i class="fas fa-chart-line"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chart-pie" data-search-terms="graph analytics pie-chart "><i class="fas fa-chart-pie"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-check" data-search-terms="checkmark done todo agree accept confirm tick ok select "><i class="fas fa-check"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-check-circle" data-search-terms="todo done agree accept confirm ok select "><i class="fas fa-check-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-check-circle" data-search-terms="todo done agree accept confirm ok select "><i class="far fa-check-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-check-square" data-search-terms="checkmark done todo agree accept confirm ok select "><i class="fas fa-check-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-check-square" data-search-terms="checkmark done todo agree accept confirm ok select "><i class="far fa-check-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chess"><i class="fas fa-chess"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chess-bishop"><i class="fas fa-chess-bishop"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chess-board"><i class="fas fa-chess-board"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chess-king"><i class="fas fa-chess-king"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chess-knight"><i class="fas fa-chess-knight"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chess-pawn"><i class="fas fa-chess-pawn"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chess-queen"><i class="fas fa-chess-queen"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chess-rook"><i class="fas fa-chess-rook"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chevron-circle-down" data-search-terms="more dropdown menu arrow "><i class="fas fa-chevron-circle-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chevron-circle-left" data-search-terms="previous back arrow "><i class="fas fa-chevron-circle-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chevron-circle-right" data-search-terms="next forward arrow "><i class="fas fa-chevron-circle-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chevron-circle-up" data-search-terms="arrow "><i class="fas fa-chevron-circle-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chevron-down"><i class="fas fa-chevron-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chevron-left" data-search-terms="bracket previous back "><i class="fas fa-chevron-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chevron-right" data-search-terms="bracket next forward "><i class="fas fa-chevron-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-chevron-up"><i class="fas fa-chevron-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-child"><i class="fas fa-child"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-chrome" data-search-terms="browser "><i class="fab fa-chrome"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-circle" data-search-terms="dot notification circle-thin "><i class="fas fa-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-circle" data-search-terms="dot notification circle-thin "><i class="far fa-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-circle-notch" data-search-terms="circle-o-notch "><i class="fas fa-circle-notch"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-clipboard" data-search-terms="paste "><i class="fas fa-clipboard"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-clipboard" data-search-terms="paste "><i class="far fa-clipboard"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-clipboard-check"><i class="fas fa-clipboard-check"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-clipboard-list"><i class="fas fa-clipboard-list"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-clock" data-search-terms="watch timer late timestamp date "><i class="fas fa-clock"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-clock" data-search-terms="watch timer late timestamp date "><i class="far fa-clock"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-clone" data-search-terms="copy "><i class="fas fa-clone"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-clone" data-search-terms="copy "><i class="far fa-clone"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-closed-captioning" data-search-terms="cc "><i class="fas fa-closed-captioning"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-closed-captioning" data-search-terms="cc "><i class="far fa-closed-captioning"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cloud" data-search-terms="save "><i class="fas fa-cloud"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cloud-download-alt" data-search-terms="cloud-download "><i class="fas fa-cloud-download-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cloud-upload-alt" data-search-terms="cloud-upload "><i class="fas fa-cloud-upload-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cloudscale"><i class="fab fa-cloudscale"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cloudsmith"><i class="fab fa-cloudsmith"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cloudversify"><i class="fab fa-cloudversify"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-code" data-search-terms="html brackets "><i class="fas fa-code"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-code-branch" data-search-terms="git fork vcs svn github rebase version branch code-fork "><i class="fas fa-code-branch"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-codepen"><i class="fab fa-codepen"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-codiepie"><i class="fab fa-codiepie"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-coffee" data-search-terms="morning mug breakfast tea drink cafe "><i class="fas fa-coffee"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cog" data-search-terms="settings "><i class="fas fa-cog"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cogs" data-search-terms="settings gears "><i class="fas fa-cogs"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-columns" data-search-terms="split panes dashboard "><i class="fas fa-columns"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-comment" data-search-terms="speech notification note chat bubble feedback message texting sms conversation "><i class="fas fa-comment"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-comment" data-search-terms="speech notification note chat bubble feedback message texting sms conversation "><i class="far fa-comment"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-comment-alt" data-search-terms="speech notification note chat bubble feedback message texting sms conversation commenting commenting "><i class="fas fa-comment-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-comment-alt" data-search-terms="speech notification note chat bubble feedback message texting sms conversation commenting commenting "><i class="far fa-comment-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-comments" data-search-terms="speech notification note chat bubble feedback message texting sms conversation "><i class="fas fa-comments"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-comments" data-search-terms="speech notification note chat bubble feedback message texting sms conversation "><i class="far fa-comments"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-compass" data-search-terms="safari directory menu location "><i class="fas fa-compass"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-compass" data-search-terms="safari directory menu location "><i class="far fa-compass"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-compress" data-search-terms="collapse combine contract merge smaller "><i class="fas fa-compress"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-connectdevelop"><i class="fab fa-connectdevelop"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-contao"><i class="fab fa-contao"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-copy" data-search-terms="duplicate clone file files-o "><i class="fas fa-copy"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-copy" data-search-terms="duplicate clone file files-o "><i class="far fa-copy"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-copyright"><i class="fas fa-copyright"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-copyright"><i class="far fa-copyright"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cpanel"><i class="fab fa-cpanel"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-creative-commons"><i class="fab fa-creative-commons"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-credit-card" data-search-terms="money buy debit checkout purchase payment credit-card-alt "><i class="fas fa-credit-card"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-credit-card" data-search-terms="money buy debit checkout purchase payment credit-card-alt "><i class="far fa-credit-card"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-crop" data-search-terms="design "><i class="fas fa-crop"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-crosshairs" data-search-terms="picker gpd "><i class="fas fa-crosshairs"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-css3" data-search-terms="code "><i class="fab fa-css3"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-css3-alt"><i class="fab fa-css3-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cube" data-search-terms="package "><i class="fas fa-cube"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cubes" data-search-terms="packages "><i class="fas fa-cubes"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-cut" data-search-terms="scissors scissors "><i class="fas fa-cut"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-cuttlefish"><i class="fab fa-cuttlefish"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-d-and-d"><i class="fab fa-d-and-d"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-dashcube"><i class="fab fa-dashcube"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-database"><i class="fas fa-database"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-deaf"><i class="fas fa-deaf"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-delicious"><i class="fab fa-delicious"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-deploydog"><i class="fab fa-deploydog"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-deskpro"><i class="fab fa-deskpro"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-desktop" data-search-terms="monitor screen desktop computer demo device pc "><i class="fas fa-desktop"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-deviantart"><i class="fab fa-deviantart"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-digg"><i class="fab fa-digg"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-digital-ocean"><i class="fab fa-digital-ocean"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-discord"><i class="fab fa-discord"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-discourse"><i class="fab fa-discourse"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-dna" data-search-terms="double helix helix "><i class="fas fa-dna"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-dochub"><i class="fab fa-dochub"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-docker"><i class="fab fa-docker"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-dollar-sign" data-search-terms="usd price "><i class="fas fa-dollar-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-dolly"><i class="fas fa-dolly"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-dolly-flatbed"><i class="fas fa-dolly-flatbed"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-dot-circle" data-search-terms="target bullseye notification "><i class="fas fa-dot-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-dot-circle" data-search-terms="target bullseye notification "><i class="far fa-dot-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-download" data-search-terms="import "><i class="fas fa-download"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-draft2digital"><i class="fab fa-draft2digital"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-dribbble"><i class="fab fa-dribbble"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-dribbble-square"><i class="fab fa-dribbble-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-dropbox"><i class="fab fa-dropbox"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-drupal"><i class="fab fa-drupal"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-dyalog"><i class="fab fa-dyalog"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-earlybirds"><i class="fab fa-earlybirds"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-edge" data-search-terms="browser ie "><i class="fab fa-edge"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-edit" data-search-terms="write edit update pencil pen "><i class="fas fa-edit"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-edit" data-search-terms="write edit update pencil pen "><i class="far fa-edit"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-eject"><i class="fas fa-eject"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-elementor"><i class="fab fa-elementor"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-ellipsis-h" data-search-terms="dots "><i class="fas fa-ellipsis-h"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-ellipsis-v" data-search-terms="dots "><i class="fas fa-ellipsis-v"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-ember"><i class="fab fa-ember"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-empire"><i class="fab fa-empire"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-envelope" data-search-terms="email e-mail letter support mail message notification "><i class="fas fa-envelope"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-envelope" data-search-terms="email e-mail letter support mail message notification "><i class="far fa-envelope"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-envelope-open" data-search-terms="email e-mail letter support mail message notification "><i class="fas fa-envelope-open"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-envelope-open" data-search-terms="email e-mail letter support mail message notification "><i class="far fa-envelope-open"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-envelope-square" data-search-terms="email e-mail letter support mail message notification "><i class="fas fa-envelope-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-envira" data-search-terms="leaf "><i class="fab fa-envira"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-eraser" data-search-terms="remove delete "><i class="fas fa-eraser"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-erlang"><i class="fab fa-erlang"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-ethereum"><i class="fab fa-ethereum"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-etsy"><i class="fab fa-etsy"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-euro-sign" data-search-terms="eur eur "><i class="fas fa-euro-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-exchange-alt" data-search-terms="transfer arrows arrow exchange swap "><i class="fas fa-exchange-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-exclamation" data-search-terms="warning error problem notification notify alert danger "><i class="fas fa-exclamation"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-exclamation-circle" data-search-terms="warning error problem notification notify alert danger "><i class="fas fa-exclamation-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-exclamation-triangle" data-search-terms="warning error problem notification notify alert danger "><i class="fas fa-exclamation-triangle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-expand" data-search-terms="enlarge bigger resize "><i class="fas fa-expand"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-expand-arrows-alt" data-search-terms="enlarge bigger resize move arrows-alt "><i class="fas fa-expand-arrows-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-expeditedssl"><i class="fab fa-expeditedssl"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-external-link-alt" data-search-terms="open new external-link "><i class="fas fa-external-link-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-external-link-square-alt" data-search-terms="open new external-link-square "><i class="fas fa-external-link-square-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-eye" data-search-terms="show visible views "><i class="fas fa-eye"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-eye-dropper" data-search-terms="eyedropper "><i class="fas fa-eye-dropper"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-eye-slash" data-search-terms="toggle show hide visible visiblity views "><i class="fas fa-eye-slash"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-eye-slash" data-search-terms="toggle show hide visible visiblity views "><i class="far fa-eye-slash"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-facebook" data-search-terms="social network facebook-official "><i class="fab fa-facebook"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-facebook-f" data-search-terms="facebook "><i class="fab fa-facebook-f"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-facebook-messenger"><i class="fab fa-facebook-messenger"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-facebook-square" data-search-terms="social network "><i class="fab fa-facebook-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-fast-backward" data-search-terms="rewind previous beginning start first "><i class="fas fa-fast-backward"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-fast-forward" data-search-terms="next end last "><i class="fas fa-fast-forward"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-fax"><i class="fas fa-fax"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-female" data-search-terms="woman human user person profile "><i class="fas fa-female"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-fighter-jet" data-search-terms="fly plane airplane quick fast travel "><i class="fas fa-fighter-jet"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file" data-search-terms="new page pdf document "><i class="fas fa-file"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file" data-search-terms="new page pdf document "><i class="far fa-file"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-alt" data-search-terms="new page pdf document file-text "><i class="fas fa-file-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-alt" data-search-terms="new page pdf document file-text "><i class="far fa-file-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-archive"><i class="fas fa-file-archive"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-archive"><i class="far fa-file-archive"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-audio"><i class="fas fa-file-audio"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-audio"><i class="far fa-file-audio"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-code"><i class="fas fa-file-code"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-code"><i class="far fa-file-code"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-excel"><i class="fas fa-file-excel"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-excel"><i class="far fa-file-excel"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-image"><i class="fas fa-file-image"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-image"><i class="far fa-file-image"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-pdf"><i class="fas fa-file-pdf"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-pdf"><i class="far fa-file-pdf"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-powerpoint"><i class="fas fa-file-powerpoint"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-powerpoint"><i class="far fa-file-powerpoint"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-video"><i class="fas fa-file-video"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-video"><i class="far fa-file-video"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-file-word"><i class="fas fa-file-word"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-file-word"><i class="far fa-file-word"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-film" data-search-terms="movie "><i class="fas fa-film"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-filter" data-search-terms="funnel options "><i class="fas fa-filter"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-fire" data-search-terms="flame hot popular "><i class="fas fa-fire"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-fire-extinguisher"><i class="fas fa-fire-extinguisher"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-firefox" data-search-terms="browser "><i class="fab fa-firefox"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-first-aid"><i class="fas fa-first-aid"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-first-order"><i class="fab fa-first-order"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-firstdraft"><i class="fab fa-firstdraft"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-flag" data-search-terms="report notification notify "><i class="fas fa-flag"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-flag" data-search-terms="report notification notify "><i class="far fa-flag"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-flag-checkered" data-search-terms="report notification notify "><i class="fas fa-flag-checkered"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-flask" data-search-terms="science beaker experimental labs "><i class="fas fa-flask"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-flickr"><i class="fab fa-flickr"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-flipboard"><i class="fab fa-flipboard"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-fly"><i class="fab fa-fly"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-folder"><i class="fas fa-folder"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-folder"><i class="far fa-folder"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-folder-open"><i class="fas fa-folder-open"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-folder-open"><i class="far fa-folder-open"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-font" data-search-terms="text "><i class="fas fa-font"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-font-awesome" data-search-terms="meanpath "><i class="fab fa-font-awesome"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-font-awesome-alt"><i class="fab fa-font-awesome-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-font-awesome-flag"><i class="fab fa-font-awesome-flag"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-fonticons"><i class="fab fa-fonticons"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-fonticons-fi"><i class="fab fa-fonticons-fi"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-football-ball"><i class="fas fa-football-ball"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-fort-awesome" data-search-terms="castle "><i class="fab fa-fort-awesome"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-fort-awesome-alt" data-search-terms="castle "><i class="fab fa-fort-awesome-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-forumbee"><i class="fab fa-forumbee"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-forward" data-search-terms="forward next "><i class="fas fa-forward"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-foursquare"><i class="fab fa-foursquare"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-free-code-camp"><i class="fab fa-free-code-camp"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-freebsd"><i class="fab fa-freebsd"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-frown" data-search-terms="face emoticon sad disapprove rating "><i class="fas fa-frown"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-frown" data-search-terms="face emoticon sad disapprove rating "><i class="far fa-frown"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-futbol"><i class="fas fa-futbol"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-futbol"><i class="far fa-futbol"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-gamepad" data-search-terms="controller "><i class="fas fa-gamepad"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-gavel" data-search-terms="judge lawyer opinion hammer "><i class="fas fa-gavel"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-gem" data-search-terms="diamond "><i class="fas fa-gem"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-gem" data-search-terms="diamond "><i class="far fa-gem"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-genderless"><i class="fas fa-genderless"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-get-pocket"><i class="fab fa-get-pocket"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gg"><i class="fab fa-gg"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gg-circle"><i class="fab fa-gg-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-gift" data-search-terms="present "><i class="fas fa-gift"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-git"><i class="fab fa-git"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-git-square"><i class="fab fa-git-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-github" data-search-terms="octocat "><i class="fab fa-github"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-github-alt" data-search-terms="octocat "><i class="fab fa-github-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-github-square" data-search-terms="octocat "><i class="fab fa-github-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gitkraken"><i class="fab fa-gitkraken"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gitlab" data-search-terms="Axosoft "><i class="fab fa-gitlab"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gitter"><i class="fab fa-gitter"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-glass-martini" data-search-terms="martini drink bar alcohol liquor glass "><i class="fas fa-glass-martini"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-glide"><i class="fab fa-glide"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-glide-g"><i class="fab fa-glide-g"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-globe" data-search-terms="world planet map place travel earth global translate all language localize location coordinates country gps "><i class="fas fa-globe"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gofore"><i class="fab fa-gofore"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-golf-ball"><i class="fas fa-golf-ball"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-goodreads"><i class="fab fa-goodreads"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-goodreads-g"><i class="fab fa-goodreads-g"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-google"><i class="fab fa-google"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-google-drive"><i class="fab fa-google-drive"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-google-play"><i class="fab fa-google-play"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-google-plus" data-search-terms="google-plus-circle google-plus-official "><i class="fab fa-google-plus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-google-plus-g" data-search-terms="social network google-plus "><i class="fab fa-google-plus-g"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-google-plus-square" data-search-terms="social network "><i class="fab fa-google-plus-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-google-wallet"><i class="fab fa-google-wallet"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-graduation-cap" data-search-terms="learning school student "><i class="fas fa-graduation-cap"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gratipay" data-search-terms="heart like favorite love "><i class="fab fa-gratipay"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-grav"><i class="fab fa-grav"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gripfire"><i class="fab fa-gripfire"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-grunt"><i class="fab fa-grunt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-gulp"><i class="fab fa-gulp"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-h-square" data-search-terms="hospital hotel "><i class="fas fa-h-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-hacker-news"><i class="fab fa-hacker-news"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-hacker-news-square"><i class="fab fa-hacker-news-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-lizard"><i class="fas fa-hand-lizard"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-lizard"><i class="far fa-hand-lizard"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-paper" data-search-terms="stop "><i class="fas fa-hand-paper"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-paper" data-search-terms="stop "><i class="far fa-hand-paper"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-peace"><i class="fas fa-hand-peace"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-peace"><i class="far fa-hand-peace"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-point-down" data-search-terms="point finger hand-o-down "><i class="fas fa-hand-point-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-point-down" data-search-terms="point finger hand-o-down "><i class="far fa-hand-point-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-point-left" data-search-terms="point left previous back finger hand-o-left "><i class="fas fa-hand-point-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-point-left" data-search-terms="point left previous back finger hand-o-left "><i class="far fa-hand-point-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-point-right" data-search-terms="point right next forward finger hand-o-right "><i class="fas fa-hand-point-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-point-right" data-search-terms="point right next forward finger hand-o-right "><i class="far fa-hand-point-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-point-up" data-search-terms="point finger hand-o-up "><i class="fas fa-hand-point-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-point-up" data-search-terms="point finger hand-o-up "><i class="far fa-hand-point-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-pointer" data-search-terms="select "><i class="fas fa-hand-pointer"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-pointer" data-search-terms="select "><i class="far fa-hand-pointer"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-rock"><i class="fas fa-hand-rock"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-rock"><i class="far fa-hand-rock"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-scissors"><i class="fas fa-hand-scissors"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-scissors"><i class="far fa-hand-scissors"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hand-spock"><i class="fas fa-hand-spock"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hand-spock"><i class="far fa-hand-spock"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-handshake"><i class="fas fa-handshake"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-handshake"><i class="far fa-handshake"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hashtag"><i class="fas fa-hashtag"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hdd" data-search-terms="harddrive hard drive storage save "><i class="fas fa-hdd"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hdd" data-search-terms="harddrive hard drive storage save "><i class="far fa-hdd"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-heading" data-search-terms="header header "><i class="fas fa-heading"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-headphones" data-search-terms="sound listen music audio "><i class="fas fa-headphones"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-heart" data-search-terms="love like favorite "><i class="fas fa-heart"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-heart" data-search-terms="love like favorite "><i class="far fa-heart"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-heartbeat" data-search-terms="ekg vital signs "><i class="fas fa-heartbeat"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-hips"><i class="fab fa-hips"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-hire-a-helper"><i class="fab fa-hire-a-helper"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-history"><i class="fas fa-history"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hockey-puck"><i class="fas fa-hockey-puck"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-home" data-search-terms="main house "><i class="fas fa-home"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-hooli"><i class="fab fa-hooli"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hospital" data-search-terms="building medical center emergency room "><i class="fas fa-hospital"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hospital" data-search-terms="building medical center emergency room "><i class="far fa-hospital"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hospital-symbol"><i class="fas fa-hospital-symbol"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-hotjar"><i class="fab fa-hotjar"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hourglass"><i class="fas fa-hourglass"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-hourglass"><i class="far fa-hourglass"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hourglass-end"><i class="fas fa-hourglass-end"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hourglass-half"><i class="fas fa-hourglass-half"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-hourglass-start"><i class="fas fa-hourglass-start"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-houzz"><i class="fab fa-houzz"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-html5"><i class="fab fa-html5"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-hubspot"><i class="fab fa-hubspot"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-i-cursor"><i class="fas fa-i-cursor"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-id-badge"><i class="fas fa-id-badge"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-id-badge"><i class="far fa-id-badge"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-id-card"><i class="fas fa-id-card"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-id-card"><i class="far fa-id-card"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-image" data-search-terms="photo album picture picture "><i class="fas fa-image"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-image" data-search-terms="photo album picture picture "><i class="far fa-image"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-images" data-search-terms="photo album picture "><i class="fas fa-images"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-images" data-search-terms="photo album picture "><i class="far fa-images"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-imdb"><i class="fab fa-imdb"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-inbox"><i class="fas fa-inbox"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-indent"><i class="fas fa-indent"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-industry" data-search-terms="factory "><i class="fas fa-industry"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-info" data-search-terms="help information more details "><i class="fas fa-info"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-info-circle" data-search-terms="help information more details "><i class="fas fa-info-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-instagram"><i class="fab fa-instagram"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-internet-explorer" data-search-terms="browser ie "><i class="fab fa-internet-explorer"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-ioxhost"><i class="fab fa-ioxhost"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-italic" data-search-terms="italics "><i class="fas fa-italic"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-itunes"><i class="fab fa-itunes"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-itunes-note"><i class="fab fa-itunes-note"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-jenkins"><i class="fab fa-jenkins"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-joget"><i class="fab fa-joget"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-joomla"><i class="fab fa-joomla"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-js"><i class="fab fa-js"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-js-square"><i class="fab fa-js-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-jsfiddle"><i class="fab fa-jsfiddle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-key" data-search-terms="unlock password "><i class="fas fa-key"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-keyboard" data-search-terms="type input "><i class="fas fa-keyboard"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-keyboard" data-search-terms="type input "><i class="far fa-keyboard"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-keycdn"><i class="fab fa-keycdn"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-kickstarter"><i class="fab fa-kickstarter"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-kickstarter-k"><i class="fab fa-kickstarter-k"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-korvue"><i class="fab fa-korvue"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-language"><i class="fas fa-language"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-laptop" data-search-terms="demo computer device pc "><i class="fas fa-laptop"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-laravel"><i class="fab fa-laravel"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-lastfm"><i class="fab fa-lastfm"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-lastfm-square"><i class="fab fa-lastfm-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-leaf" data-search-terms="eco nature plant "><i class="fas fa-leaf"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-leanpub"><i class="fab fa-leanpub"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-lemon" data-search-terms="food "><i class="fas fa-lemon"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-lemon" data-search-terms="food "><i class="far fa-lemon"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-less"><i class="fab fa-less"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-level-down-alt" data-search-terms="level-down "><i class="fas fa-level-down-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-level-up-alt" data-search-terms="level-up "><i class="fas fa-level-up-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-life-ring" data-search-terms="support "><i class="fas fa-life-ring"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-life-ring" data-search-terms="support "><i class="far fa-life-ring"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-lightbulb" data-search-terms="idea inspiration "><i class="fas fa-lightbulb"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-lightbulb" data-search-terms="idea inspiration "><i class="far fa-lightbulb"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-line"><i class="fab fa-line"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-link" data-search-terms="chain "><i class="fas fa-link"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-linkedin" data-search-terms="linkedin-square "><i class="fab fa-linkedin"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-linkedin-in" data-search-terms="linkedin "><i class="fab fa-linkedin-in"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-linode"><i class="fab fa-linode"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-linux" data-search-terms="tux "><i class="fab fa-linux"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-lira-sign" data-search-terms="try turkish try "><i class="fas fa-lira-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-list" data-search-terms="ul ol checklist finished completed done todo "><i class="fas fa-list"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-list-alt" data-search-terms="ul ol checklist finished completed done todo "><i class="fas fa-list-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-list-alt" data-search-terms="ul ol checklist finished completed done todo "><i class="far fa-list-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-list-ol" data-search-terms="ul ol checklist list todo list numbers "><i class="fas fa-list-ol"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-list-ul" data-search-terms="ul ol checklist todo list "><i class="fas fa-list-ul"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-location-arrow" data-search-terms="map coordinates location address place where gps "><i class="fas fa-location-arrow"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-lock" data-search-terms="protect admin security "><i class="fas fa-lock"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-lock-open" data-search-terms="protect admin password lock open "><i class="fas fa-lock-open"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-long-arrow-alt-down" data-search-terms="long-arrow-down "><i class="fas fa-long-arrow-alt-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-long-arrow-alt-left" data-search-terms="previous back long-arrow-left "><i class="fas fa-long-arrow-alt-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-long-arrow-alt-right" data-search-terms="long-arrow-right "><i class="fas fa-long-arrow-alt-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-long-arrow-alt-up" data-search-terms="long-arrow-up "><i class="fas fa-long-arrow-alt-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-low-vision"><i class="fas fa-low-vision"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-lyft"><i class="fab fa-lyft"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-magento"><i class="fab fa-magento"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-magic" data-search-terms="wizard automatic autocomplete "><i class="fas fa-magic"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-magnet"><i class="fas fa-magnet"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-male" data-search-terms="man human user person profile "><i class="fas fa-male"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-map"><i class="fas fa-map"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-map"><i class="far fa-map"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-map-marker" data-search-terms="map pin location coordinates localize address travel where place gps "><i class="fas fa-map-marker"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-map-marker-alt" data-search-terms="map-marker gps "><i class="fas fa-map-marker-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-map-pin"><i class="fas fa-map-pin"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-map-signs"><i class="fas fa-map-signs"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mars" data-search-terms="male "><i class="fas fa-mars"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mars-double"><i class="fas fa-mars-double"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mars-stroke"><i class="fas fa-mars-stroke"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mars-stroke-h"><i class="fas fa-mars-stroke-h"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mars-stroke-v"><i class="fas fa-mars-stroke-v"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-maxcdn"><i class="fab fa-maxcdn"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-medapps"><i class="fab fa-medapps"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-medium"><i class="fab fa-medium"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-medium-m"><i class="fab fa-medium-m"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-medkit" data-search-terms="first aid firstaid help support health "><i class="fas fa-medkit"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-medrt"><i class="fab fa-medrt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-meetup"><i class="fab fa-meetup"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-meh" data-search-terms="face emoticon rating neutral "><i class="fas fa-meh"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-meh" data-search-terms="face emoticon rating neutral "><i class="far fa-meh"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mercury" data-search-terms="transgender "><i class="fas fa-mercury"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-microchip"><i class="fas fa-microchip"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-microphone" data-search-terms="record voice sound "><i class="fas fa-microphone"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-microphone-slash" data-search-terms="record voice sound mute "><i class="fas fa-microphone-slash"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-microsoft"><i class="fab fa-microsoft"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-minus" data-search-terms="hide minify delete remove trash hide collapse "><i class="fas fa-minus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-minus-circle" data-search-terms="delete remove trash hide "><i class="fas fa-minus-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-minus-square" data-search-terms="hide minify delete remove trash hide collapse "><i class="fas fa-minus-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-minus-square" data-search-terms="hide minify delete remove trash hide collapse "><i class="far fa-minus-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-mix"><i class="fab fa-mix"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-mixcloud"><i class="fab fa-mixcloud"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-mizuni"><i class="fab fa-mizuni"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mobile" data-search-terms="cell phone cellphone text call iphone number telephone "><i class="fas fa-mobile"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mobile-alt" data-search-terms="mobile "><i class="fas fa-mobile-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-modx"><i class="fab fa-modx"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-monero"><i class="fab fa-monero"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-money-bill-alt" data-search-terms="cash money buy checkout purchase payment price "><i class="fas fa-money-bill-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-money-bill-alt" data-search-terms="cash money buy checkout purchase payment price "><i class="far fa-money-bill-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-moon" data-search-terms="night darker contrast "><i class="fas fa-moon"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-moon" data-search-terms="night darker contrast "><i class="far fa-moon"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-motorcycle" data-search-terms="vehicle bike "><i class="fas fa-motorcycle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-mouse-pointer" data-search-terms="select "><i class="fas fa-mouse-pointer"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-music" data-search-terms="note sound "><i class="fas fa-music"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-napster"><i class="fab fa-napster"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-neuter"><i class="fas fa-neuter"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-newspaper" data-search-terms="press article "><i class="fas fa-newspaper"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-newspaper" data-search-terms="press article "><i class="far fa-newspaper"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-nintendo-switch"><i class="fab fa-nintendo-switch"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-node"><i class="fab fa-node"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-node-js"><i class="fab fa-node-js"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-npm"><i class="fab fa-npm"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-ns8"><i class="fab fa-ns8"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-nutritionix"><i class="fab fa-nutritionix"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-object-group" data-search-terms="design "><i class="fas fa-object-group"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-object-group" data-search-terms="design "><i class="far fa-object-group"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-object-ungroup" data-search-terms="design "><i class="fas fa-object-ungroup"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-object-ungroup" data-search-terms="design "><i class="far fa-object-ungroup"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-odnoklassniki"><i class="fab fa-odnoklassniki"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-odnoklassniki-square"><i class="fab fa-odnoklassniki-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-opencart"><i class="fab fa-opencart"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-openid"><i class="fab fa-openid"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-opera"><i class="fab fa-opera"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-optin-monster"><i class="fab fa-optin-monster"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-osi"><i class="fab fa-osi"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-outdent"><i class="fas fa-outdent"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-page4"><i class="fab fa-page4"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-pagelines" data-search-terms="leaf leaves tree plant eco nature "><i class="fab fa-pagelines"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-paint-brush"><i class="fas fa-paint-brush"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-palfed"><i class="fab fa-palfed"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-pallet"><i class="fas fa-pallet"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-paper-plane"><i class="fas fa-paper-plane"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-paper-plane"><i class="far fa-paper-plane"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-paperclip" data-search-terms="attachment "><i class="fas fa-paperclip"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-paragraph"><i class="fas fa-paragraph"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-paste" data-search-terms="copy clipboard "><i class="fas fa-paste"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-patreon"><i class="fab fa-patreon"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-pause" data-search-terms="wait "><i class="fas fa-pause"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-pause-circle"><i class="fas fa-pause-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-pause-circle"><i class="far fa-pause-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-paw" data-search-terms="pet "><i class="fas fa-paw"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-paypal"><i class="fab fa-paypal"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-pen-square" data-search-terms="write edit update pencil-square "><i class="fas fa-pen-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-pencil-alt" data-search-terms="write edit update pencil design "><i class="fas fa-pencil-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-percent"><i class="fas fa-percent"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-periscope"><i class="fab fa-periscope"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-phabricator"><i class="fab fa-phabricator"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-phoenix-framework"><i class="fab fa-phoenix-framework"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-phone" data-search-terms="call voice number support earphone telephone "><i class="fas fa-phone"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-phone-square" data-search-terms="call voice number support telephone "><i class="fas fa-phone-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-phone-volume" data-search-terms="telephone volume-control-phone "><i class="fas fa-phone-volume"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-php"><i class="fab fa-php"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-pied-piper"><i class="fab fa-pied-piper"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-pied-piper-alt"><i class="fab fa-pied-piper-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-pied-piper-pp"><i class="fab fa-pied-piper-pp"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-pills" data-search-terms="medicine drugs "><i class="fas fa-pills"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-pinterest"><i class="fab fa-pinterest"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-pinterest-p"><i class="fab fa-pinterest-p"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-pinterest-square"><i class="fab fa-pinterest-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-plane" data-search-terms="travel trip location destination airplane fly mode "><i class="fas fa-plane"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-play" data-search-terms="start playing music sound "><i class="fas fa-play"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-play-circle" data-search-terms="start playing "><i class="fas fa-play-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-play-circle" data-search-terms="start playing "><i class="far fa-play-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-playstation"><i class="fab fa-playstation"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-plug" data-search-terms="power connect "><i class="fas fa-plug"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-plus" data-search-terms="add new create expand "><i class="fas fa-plus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-plus-circle" data-search-terms="add new create expand "><i class="fas fa-plus-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-plus-square" data-search-terms="add new create expand "><i class="fas fa-plus-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-plus-square" data-search-terms="add new create expand "><i class="far fa-plus-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-podcast"><i class="fas fa-podcast"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-pound-sign" data-search-terms="gbp gbp "><i class="fas fa-pound-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-power-off" data-search-terms="on "><i class="fas fa-power-off"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-print"><i class="fas fa-print"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-product-hunt"><i class="fab fa-product-hunt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-pushed"><i class="fab fa-pushed"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-puzzle-piece" data-search-terms="addon add-on section "><i class="fas fa-puzzle-piece"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-python"><i class="fab fa-python"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-qq"><i class="fab fa-qq"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-qrcode" data-search-terms="scan "><i class="fas fa-qrcode"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-question" data-search-terms="help information unknown support "><i class="fas fa-question"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-question-circle" data-search-terms="help information unknown support "><i class="fas fa-question-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-question-circle" data-search-terms="help information unknown support "><i class="far fa-question-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-quidditch"><i class="fas fa-quidditch"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-quinscape"><i class="fab fa-quinscape"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-quora"><i class="fab fa-quora"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-quote-left"><i class="fas fa-quote-left"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-quote-right"><i class="fas fa-quote-right"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-random" data-search-terms="sort shuffle "><i class="fas fa-random"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-ravelry"><i class="fab fa-ravelry"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-react"><i class="fab fa-react"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-rebel"><i class="fab fa-rebel"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-recycle"><i class="fas fa-recycle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-red-river"><i class="fab fa-red-river"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-reddit"><i class="fab fa-reddit"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-reddit-alien"><i class="fab fa-reddit-alien"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-reddit-square"><i class="fab fa-reddit-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-redo" data-search-terms="forward repeat repeat "><i class="fas fa-redo"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-redo-alt" data-search-terms="forward repeat "><i class="fas fa-redo-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-registered"><i class="fas fa-registered"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-registered"><i class="far fa-registered"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-rendact"><i class="fab fa-rendact"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-renren"><i class="fab fa-renren"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-reply"><i class="fas fa-reply"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-reply-all"><i class="fas fa-reply-all"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-replyd"><i class="fab fa-replyd"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-resolving"><i class="fab fa-resolving"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-retweet" data-search-terms="refresh reload share swap "><i class="fas fa-retweet"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-road" data-search-terms="street "><i class="fas fa-road"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-rocket" data-search-terms="app "><i class="fas fa-rocket"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-rocketchat"><i class="fab fa-rocketchat"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-rockrms"><i class="fab fa-rockrms"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-rss" data-search-terms="blog "><i class="fas fa-rss"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-rss-square" data-search-terms="feed blog "><i class="fas fa-rss-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-ruble-sign" data-search-terms="rub rub "><i class="fas fa-ruble-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-rupee-sign" data-search-terms="indian inr "><i class="fas fa-rupee-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-safari" data-search-terms="browser "><i class="fab fa-safari"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-sass"><i class="fab fa-sass"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-save" data-search-terms="floppy floppy-o "><i class="fas fa-save"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-save" data-search-terms="floppy floppy-o "><i class="far fa-save"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-schlix"><i class="fab fa-schlix"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-scribd"><i class="fab fa-scribd"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-search" data-search-terms="magnify zoom enlarge bigger "><i class="fas fa-search"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-search-minus" data-search-terms="magnify minify zoom smaller "><i class="fas fa-search-minus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-search-plus" data-search-terms="magnify zoom enlarge bigger "><i class="fas fa-search-plus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-searchengin"><i class="fab fa-searchengin"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-sellcast" data-search-terms="eercast "><i class="fab fa-sellcast"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-sellsy"><i class="fab fa-sellsy"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-server"><i class="fas fa-server"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-servicestack"><i class="fab fa-servicestack"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-share"><i class="fas fa-share"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-share-alt"><i class="fas fa-share-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-share-alt-square"><i class="fas fa-share-alt-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-share-square" data-search-terms="social send "><i class="fas fa-share-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-share-square" data-search-terms="social send "><i class="far fa-share-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-shekel-sign" data-search-terms="ils ils "><i class="fas fa-shekel-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-shield-alt" data-search-terms="shield "><i class="fas fa-shield-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-ship" data-search-terms="boat sea "><i class="fas fa-ship"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-shipping-fast"><i class="fas fa-shipping-fast"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-shirtsinbulk"><i class="fab fa-shirtsinbulk"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-shopping-bag"><i class="fas fa-shopping-bag"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-shopping-basket"><i class="fas fa-shopping-basket"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-shopping-cart" data-search-terms="checkout buy purchase payment "><i class="fas fa-shopping-cart"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-shower"><i class="fas fa-shower"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sign-in-alt" data-search-terms="enter join log in login sign up sign in signin signup arrow sign-in "><i class="fas fa-sign-in-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sign-language"><i class="fas fa-sign-language"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sign-out-alt" data-search-terms="log out logout leave exit arrow sign-out "><i class="fas fa-sign-out-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-signal" data-search-terms="graph bars status "><i class="fas fa-signal"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-simplybuilt"><i class="fab fa-simplybuilt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-sistrix"><i class="fab fa-sistrix"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sitemap" data-search-terms="directory hierarchy organization "><i class="fas fa-sitemap"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-skyatlas"><i class="fab fa-skyatlas"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-skype"><i class="fab fa-skype"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-slack" data-search-terms="hashtag anchor hash "><i class="fab fa-slack"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-slack-hash" data-search-terms="hashtag anchor hash "><i class="fab fa-slack-hash"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sliders-h" data-search-terms="settings sliders "><i class="fas fa-sliders-h"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-slideshare"><i class="fab fa-slideshare"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-smile" data-search-terms="face emoticon happy approve satisfied rating "><i class="fas fa-smile"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-smile" data-search-terms="face emoticon happy approve satisfied rating "><i class="far fa-smile"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-snapchat"><i class="fab fa-snapchat"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-snapchat-ghost"><i class="fab fa-snapchat-ghost"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-snapchat-square"><i class="fab fa-snapchat-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-snowflake"><i class="fas fa-snowflake"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-snowflake"><i class="far fa-snowflake"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort" data-search-terms="order "><i class="fas fa-sort"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort-alpha-down" data-search-terms="sort-alpha-asc "><i class="fas fa-sort-alpha-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort-alpha-up" data-search-terms="sort-alpha-desc "><i class="fas fa-sort-alpha-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort-amount-down" data-search-terms="sort-amount-asc "><i class="fas fa-sort-amount-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort-amount-up" data-search-terms="sort-amount-desc "><i class="fas fa-sort-amount-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort-down" data-search-terms="arrow descending sort-desc "><i class="fas fa-sort-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort-numeric-down" data-search-terms="numbers sort-numeric-asc "><i class="fas fa-sort-numeric-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort-numeric-up" data-search-terms="numbers sort-numeric-desc "><i class="fas fa-sort-numeric-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sort-up" data-search-terms="arrow ascending sort-asc "><i class="fas fa-sort-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-soundcloud"><i class="fab fa-soundcloud"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-space-shuttle"><i class="fas fa-space-shuttle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-speakap"><i class="fab fa-speakap"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-spinner" data-search-terms="loading progress "><i class="fas fa-spinner"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-spotify"><i class="fab fa-spotify"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-square" data-search-terms="block box "><i class="fas fa-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-square" data-search-terms="block box "><i class="far fa-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-square-full"><i class="fas fa-square-full"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-stack-exchange"><i class="fab fa-stack-exchange"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-stack-overflow"><i class="fab fa-stack-overflow"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-star" data-search-terms="award achievement night rating score favorite "><i class="fas fa-star"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-star" data-search-terms="award achievement night rating score favorite "><i class="far fa-star"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-star-half" data-search-terms="award achievement rating score star-half-empty star-half-full "><i class="fas fa-star-half"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-star-half" data-search-terms="award achievement rating score star-half-empty star-half-full "><i class="far fa-star-half"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-staylinked"><i class="fab fa-staylinked"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-steam"><i class="fab fa-steam"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-steam-square"><i class="fab fa-steam-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-steam-symbol"><i class="fab fa-steam-symbol"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-step-backward" data-search-terms="rewind previous beginning start first "><i class="fas fa-step-backward"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-step-forward" data-search-terms="next end last "><i class="fas fa-step-forward"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-stethoscope"><i class="fas fa-stethoscope"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-sticker-mule"><i class="fab fa-sticker-mule"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sticky-note"><i class="fas fa-sticky-note"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-sticky-note"><i class="far fa-sticky-note"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-stop" data-search-terms="block box square "><i class="fas fa-stop"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-stop-circle"><i class="fas fa-stop-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-stop-circle"><i class="far fa-stop-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-stopwatch" data-search-terms="time "><i class="fas fa-stopwatch"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-strava"><i class="fab fa-strava"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-street-view" data-search-terms="map "><i class="fas fa-street-view"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-strikethrough"><i class="fas fa-strikethrough"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-stripe"><i class="fab fa-stripe"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-stripe-s"><i class="fab fa-stripe-s"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-studiovinari"><i class="fab fa-studiovinari"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-stumbleupon"><i class="fab fa-stumbleupon"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-stumbleupon-circle"><i class="fab fa-stumbleupon-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-subscript"><i class="fas fa-subscript"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-subway"><i class="fas fa-subway"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-suitcase" data-search-terms="trip luggage travel move baggage "><i class="fas fa-suitcase"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sun" data-search-terms="weather contrast lighter brighten day "><i class="fas fa-sun"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-sun" data-search-terms="weather contrast lighter brighten day "><i class="far fa-sun"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-superpowers"><i class="fab fa-superpowers"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-superscript" data-search-terms="exponential "><i class="fas fa-superscript"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-supple"><i class="fab fa-supple"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sync" data-search-terms="reload refresh refresh "><i class="fas fa-sync"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-sync-alt" data-search-terms="reload refresh "><i class="fas fa-sync-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-syringe" data-search-terms="immunizations needle "><i class="fas fa-syringe"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-table" data-search-terms="data excel spreadsheet "><i class="fas fa-table"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-table-tennis"><i class="fas fa-table-tennis"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tablet" data-search-terms="ipad device "><i class="fas fa-tablet"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tablet-alt" data-search-terms="tablet "><i class="fas fa-tablet-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tachometer-alt" data-search-terms="tachometer dashboard "><i class="fas fa-tachometer-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tag" data-search-terms="label "><i class="fas fa-tag"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tags" data-search-terms="labels "><i class="fas fa-tags"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tasks" data-search-terms="progress loading downloading downloads settings "><i class="fas fa-tasks"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-taxi" data-search-terms="vehicle "><i class="fas fa-taxi"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-telegram"><i class="fab fa-telegram"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-telegram-plane"><i class="fab fa-telegram-plane"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-tencent-weibo"><i class="fab fa-tencent-weibo"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-terminal" data-search-terms="command prompt code "><i class="fas fa-terminal"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-text-height"><i class="fas fa-text-height"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-text-width"><i class="fas fa-text-width"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-th" data-search-terms="blocks squares boxes grid "><i class="fas fa-th"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-th-large" data-search-terms="blocks squares boxes grid "><i class="fas fa-th-large"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-th-list" data-search-terms="ul ol checklist finished completed done todo "><i class="fas fa-th-list"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-themeisle"><i class="fab fa-themeisle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thermometer" data-search-terms="temperature fever "><i class="fas fa-thermometer"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thermometer-empty" data-search-terms="status "><i class="fas fa-thermometer-empty"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thermometer-full" data-search-terms="status "><i class="fas fa-thermometer-full"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thermometer-half" data-search-terms="status "><i class="fas fa-thermometer-half"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thermometer-quarter" data-search-terms="status "><i class="fas fa-thermometer-quarter"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thermometer-three-quarters" data-search-terms="status "><i class="fas fa-thermometer-three-quarters"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thumbs-down" data-search-terms="dislike disapprove disagree hand thumbs-o-down "><i class="fas fa-thumbs-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-thumbs-down" data-search-terms="dislike disapprove disagree hand thumbs-o-down "><i class="far fa-thumbs-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thumbs-up" data-search-terms="like favorite approve agree hand thumbs-o-up "><i class="fas fa-thumbs-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-thumbs-up" data-search-terms="like favorite approve agree hand thumbs-o-up "><i class="far fa-thumbs-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-thumbtack" data-search-terms="marker pin location coordinates thumb-tack "><i class="fas fa-thumbtack"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-ticket-alt" data-search-terms="ticket "><i class="fas fa-ticket-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-times" data-search-terms="close exit x cross "><i class="fas fa-times"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-times-circle" data-search-terms="close exit x "><i class="fas fa-times-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-times-circle" data-search-terms="close exit x "><i class="far fa-times-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tint" data-search-terms="raindrop waterdrop drop droplet "><i class="fas fa-tint"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-toggle-off" data-search-terms="switch "><i class="fas fa-toggle-off"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-toggle-on" data-search-terms="switch "><i class="fas fa-toggle-on"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-trademark"><i class="fas fa-trademark"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-train"><i class="fas fa-train"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-transgender" data-search-terms="intersex "><i class="fas fa-transgender"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-transgender-alt"><i class="fas fa-transgender-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-trash" data-search-terms="garbage delete remove hide "><i class="fas fa-trash"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-trash-alt" data-search-terms="garbage delete remove hide trash trash-o "><i class="fas fa-trash-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-trash-alt" data-search-terms="garbage delete remove hide trash trash-o "><i class="far fa-trash-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tree"><i class="fas fa-tree"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-trello"><i class="fab fa-trello"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-tripadvisor"><i class="fab fa-tripadvisor"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-trophy" data-search-terms="award achievement cup winner game "><i class="fas fa-trophy"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-truck" data-search-terms="shipping "><i class="fas fa-truck"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tty"><i class="fas fa-tty"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-tumblr"><i class="fab fa-tumblr"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-tumblr-square"><i class="fab fa-tumblr-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-tv" data-search-terms="display computer monitor television "><i class="fas fa-tv"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-twitch"><i class="fab fa-twitch"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-twitter" data-search-terms="tweet social network "><i class="fab fa-twitter"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-twitter-square" data-search-terms="tweet social network "><i class="fab fa-twitter-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-typo3"><i class="fab fa-typo3"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-uber"><i class="fab fa-uber"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-uikit"><i class="fab fa-uikit"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-umbrella"><i class="fas fa-umbrella"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-underline"><i class="fas fa-underline"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-undo" data-search-terms="back "><i class="fas fa-undo"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-undo-alt" data-search-terms="back "><i class="fas fa-undo-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-uniregistry"><i class="fab fa-uniregistry"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-universal-access"><i class="fas fa-universal-access"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-university" data-search-terms="bank institution "><i class="fas fa-university"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-unlink" data-search-terms="remove chain chain-broken "><i class="fas fa-unlink"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-unlock" data-search-terms="protect admin password lock "><i class="fas fa-unlock"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-unlock-alt" data-search-terms="protect admin password lock "><i class="fas fa-unlock-alt"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-untappd"><i class="fab fa-untappd"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-upload" data-search-terms="import "><i class="fas fa-upload"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-usb"><i class="fab fa-usb"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-user" data-search-terms="person man head profile account "><i class="fas fa-user"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-user" data-search-terms="person man head profile account "><i class="far fa-user"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-user-circle" data-search-terms="person man head profile account "><i class="fas fa-user-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-user-circle" data-search-terms="person man head profile account "><i class="far fa-user-circle"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-user-md" data-search-terms="doctor profile medical nurse job occupation "><i class="fas fa-user-md"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-user-plus" data-search-terms="sign up signup "><i class="fas fa-user-plus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-user-secret" data-search-terms="whisper spy incognito privacy "><i class="fas fa-user-secret"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-user-times"><i class="fas fa-user-times"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-users" data-search-terms="people profiles persons "><i class="fas fa-users"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-ussunnah"><i class="fab fa-ussunnah"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-utensil-spoon" data-search-terms="spoon "><i class="fas fa-utensil-spoon"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-utensils" data-search-terms="food restaurant spoon knife dinner eat cutlery "><i class="fas fa-utensils"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-vaadin"><i class="fab fa-vaadin"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-venus" data-search-terms="female "><i class="fas fa-venus"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-venus-double"><i class="fas fa-venus-double"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-venus-mars"><i class="fas fa-venus-mars"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-viacoin"><i class="fab fa-viacoin"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-viadeo"><i class="fab fa-viadeo"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-viadeo-square"><i class="fab fa-viadeo-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-viber"><i class="fab fa-viber"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-video" data-search-terms="film movie record camera video-camera "><i class="fas fa-video"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-vimeo"><i class="fab fa-vimeo"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-vimeo-square"><i class="fab fa-vimeo-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-vimeo-v" data-search-terms="vimeo "><i class="fab fa-vimeo-v"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-vine"><i class="fab fa-vine"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-vk"><i class="fab fa-vk"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-vnv"><i class="fab fa-vnv"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-volleyball-ball"><i class="fas fa-volleyball-ball"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-volume-down" data-search-terms="audio lower quieter sound music "><i class="fas fa-volume-down"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-volume-off" data-search-terms="audio mute sound music "><i class="fas fa-volume-off"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-volume-up" data-search-terms="audio higher louder sound music "><i class="fas fa-volume-up"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-vuejs"><i class="fab fa-vuejs"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-warehouse"><i class="fas fa-warehouse"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-weibo"><i class="fab fa-weibo"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-weight" data-search-terms="scale "><i class="fas fa-weight"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-weixin"><i class="fab fa-weixin"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-whatsapp"><i class="fab fa-whatsapp"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-whatsapp-square"><i class="fab fa-whatsapp-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-wheelchair" data-search-terms="handicap person "><i class="fas fa-wheelchair"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-whmcs"><i class="fab fa-whmcs"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-wifi"><i class="fas fa-wifi"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-wikipedia-w"><i class="fab fa-wikipedia-w"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-window-close"><i class="fas fa-window-close"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-window-close"><i class="far fa-window-close"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-window-maximize"><i class="fas fa-window-maximize"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-window-maximize"><i class="far fa-window-maximize"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-window-minimize"><i class="fas fa-window-minimize"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-window-minimize"><i class="far fa-window-minimize"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-window-restore"><i class="fas fa-window-restore"></i></a><a role="button" href="#" class="iconpicker-item" title=".far fa-window-restore"><i class="far fa-window-restore"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-windows" data-search-terms="microsoft "><i class="fab fa-windows"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-won-sign" data-search-terms="krw krw "><i class="fas fa-won-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-wordpress"><i class="fab fa-wordpress"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-wordpress-simple"><i class="fab fa-wordpress-simple"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-wpbeginner"><i class="fab fa-wpbeginner"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-wpexplorer"><i class="fab fa-wpexplorer"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-wpforms"><i class="fab fa-wpforms"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-wrench" data-search-terms="settings fix update spanner tool "><i class="fas fa-wrench"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-xbox"><i class="fab fa-xbox"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-xing"><i class="fab fa-xing"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-xing-square"><i class="fab fa-xing-square"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-y-combinator"><i class="fab fa-y-combinator"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-yahoo"><i class="fab fa-yahoo"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-yandex"><i class="fab fa-yandex"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-yandex-international"><i class="fab fa-yandex-international"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-yelp"><i class="fab fa-yelp"></i></a><a role="button" href="#" class="iconpicker-item" title=".fas fa-yen-sign" data-search-terms="jpy jpy "><i class="fas fa-yen-sign"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-yoast"><i class="fab fa-yoast"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-youtube" data-search-terms="video film youtube-play youtube-square "><i class="fab fa-youtube"></i></a><a role="button" href="#" class="iconpicker-item" title=".fab fa-youtube-square"><i class="fab fa-youtube-square"></i></a></div>';
        print '</div>';
    }

endif;


if (!function_exists('wprentals_return_country_array')):

    function wprentals_return_country_array() {
        $countries = array('Afghanistan' => esc_html__('Afghanistan', 'wprentals'),
            'Albania' => esc_html__('Albania', 'wprentals'),
            'Algeria' => esc_html__('Algeria', 'wprentals'),
            'American Samoa' => esc_html__('American Samoa', 'wprentals'),
            'Andorra' => esc_html__('Andorra', 'wprentals'),
            'Angola' => esc_html__('Angola', 'wprentals'),
            'Anguilla' => esc_html__('Anguilla', 'wprentals'),
            'Antarctica' => esc_html__('Antarctica', 'wprentals'),
            'Antigua and Barbuda' => esc_html__('Antigua and Barbuda', 'wprentals'),
            'Argentina' => esc_html__('Argentina', 'wprentals'),
            'Armenia' => esc_html__('Armenia', 'wprentals'),
            'Aruba' => esc_html__('Aruba', 'wprentals'),
            'Australia' => esc_html__('Australia', 'wprentals'),
            'Austria' => esc_html__('Austria', 'wprentals'),
            'Azerbaijan' => esc_html__('Azerbaijan', 'wprentals'),
            'Bahamas' => esc_html__('Bahamas', 'wprentals'),
            'Bahrain' => esc_html__('Bahrain', 'wprentals'),
            'Bangladesh' => esc_html__('Bangladesh', 'wprentals'),
            'Barbados' => esc_html__('Barbados', 'wprentals'),
            'Belarus' => esc_html__('Belarus', 'wprentals'),
            'Belgium' => esc_html__('Belgium', 'wprentals'),
            'Belize' => esc_html__('Belize', 'wprentals'),
            'Benin' => esc_html__('Benin', 'wprentals'),
            'Bermuda' => esc_html__('Bermuda', 'wprentals'),
            'Bhutan' => esc_html__('Bhutan', 'wprentals'),
            'Bolivia' => esc_html__('Bolivia', 'wprentals'),
            'Bosnia and Herzegowina' => esc_html__('Bosnia and Herzegowina', 'wprentals'),
            'Botswana' => esc_html__('Botswana', 'wprentals'),
            'Bouvet Island' => esc_html__('Bouvet Island', 'wprentals'),
            'Brazil' => esc_html__('Brazil', 'wprentals'),
            'British Indian Ocean Territory' => esc_html__('British Indian Ocean Territory', 'wprentals'),
            'Brunei Darussalam' => esc_html__('Brunei Darussalam', 'wprentals'),
            'Bulgaria' => esc_html__('Bulgaria', 'wprentals'),
            'Burkina Faso' => esc_html__('Burkina Faso', 'wprentals'),
            'Burundi' => esc_html__('Burundi', 'wprentals'),
            'Cambodia' => esc_html__('Cambodia', 'wprentals'),
            'Cameroon' => esc_html__('Cameroon', 'wprentals'),
            'Canada' => esc_html__('Canada', 'wprentals'),
            'Cape Verde' => esc_html__('Cape Verde', 'wprentals'),
            'Cayman Islands' => esc_html__('Cayman Islands', 'wprentals'),
            'Central African Republic' => esc_html__('Central African Republic', 'wprentals'),
            'Chad' => esc_html__('Chad', 'wprentals'),
            'Chile' => esc_html__('Chile', 'wprentals'),
            'China' => esc_html__('China', 'wprentals'),
            'Christmas Island' => esc_html__('Christmas Island', 'wprentals'),
            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands', 'wprentals'),
            'Colombia' => esc_html__('Colombia', 'wprentals'),
            'Comoros' => esc_html__('Comoros', 'wprentals'),
            'Congo' => esc_html__('Congo', 'wprentals'),
            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the', 'wprentals'),
            'Cook Islands' => esc_html__('Cook Islands', 'wprentals'),
            'Costa Rica' => esc_html__('Costa Rica', 'wprentals'),
            'Cote dIvoire' => esc_html__('Cote dIvoire', 'wprentals'),
            'Croatia (Hrvatska)' => esc_html__('Croatia (Hrvatska)', 'wprentals'),
            'Cuba' => esc_html__('Cuba', 'wprentals'),
            'Curacao' => esc_html__('Curacao', 'wprentals'),
            'Cyprus' => esc_html__('Cyprus', 'wprentals'),
            'Czech Republic' => esc_html__('Czech Republic', 'wprentals'),
            'Denmark' => esc_html__('Denmark', 'wprentals'),
            'Djibouti' => esc_html__('Djibouti', 'wprentals'),
            'Dominica' => esc_html__('Dominica', 'wprentals'),
            'Dominican Republic' => esc_html__('Dominican Republic', 'wprentals'),
            'East Timor' => esc_html__('East Timor', 'wprentals'),
            'Ecuador' => esc_html__('Ecuador', 'wprentals'),
            'Egypt' => esc_html__('Egypt', 'wprentals'),
            'El Salvador' => esc_html__('El Salvador', 'wprentals'),
            'Equatorial Guinea' => esc_html__('Equatorial Guinea', 'wprentals'),
            'Eritrea' => esc_html__('Eritrea', 'wprentals'),
            'Estonia' => esc_html__('Estonia', 'wprentals'),
            'Ethiopia' => esc_html__('Ethiopia', 'wprentals'),
            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)', 'wprentals'),
            'Faroe Islands' => esc_html__('Faroe Islands', 'wprentals'),
            'Fiji' => esc_html__('Fiji', 'wprentals'),
            'Finland' => esc_html__('Finland', 'wprentals'),
            'France' => esc_html__('France', 'wprentals'),
            'France Metropolitan' => esc_html__('France Metropolitan', 'wprentals'),
            'French Guiana' => esc_html__('French Guiana', 'wprentals'),
            'French Polynesia' => esc_html__('French Polynesia', 'wprentals'),
            'French Southern Territories' => esc_html__('French Southern Territories', 'wprentals'),
            'Gabon' => esc_html__('Gabon', 'wprentals'),
            'Gambia' => esc_html__('Gambia', 'wprentals'),
            'Georgia' => esc_html__('Georgia', 'wprentals'),
            'Germany' => esc_html__('Germany', 'wprentals'),
            'Ghana' => esc_html__('Ghana', 'wprentals'),
            'Gibraltar' => esc_html__('Gibraltar', 'wprentals'),
            'Greece' => esc_html__('Greece', 'wprentals'),
            'Greenland' => esc_html__('Greenland', 'wprentals'),
            'Grenada' => esc_html__('Grenada', 'wprentals'),
            'Guadeloupe' => esc_html__('Guadeloupe', 'wprentals'),
            'Guam' => esc_html__('Guam', 'wprentals'),
            'Guatemala' => esc_html__('Guatemala', 'wprentals'),
            'Guinea' => esc_html__('Guinea', 'wprentals'),
            'Guinea-Bissau' => esc_html__('Guinea-Bissau', 'wprentals'),
            'Guyana' => esc_html__('Guyana', 'wprentals'),
            'Haiti' => esc_html__('Haiti', 'wprentals'),
            'Heard and Mc Donald Islands' => esc_html__('Heard and Mc Donald Islands', 'wprentals'),
            'Holy See (Vatican City State)' => esc_html__('Holy See (Vatican City State)', 'wprentals'),
            'Honduras' => esc_html__('Honduras', 'wprentals'),
            'Hong Kong' => esc_html__('Hong Kong', 'wprentals'),
            'Hungary' => esc_html__('Hungary', 'wprentals'),
            'Iceland' => esc_html__('Iceland', 'wprentals'),
            'India' => esc_html__('India', 'wprentals'),
            'Indonesia' => esc_html__('Indonesia', 'wprentals'),
            'Iran (Islamic Republic of)' => esc_html__('Iran (Islamic Republic of)', 'wprentals'),
            'Iraq' => esc_html__('Iraq', 'wprentals'),
            'Ireland' => esc_html__('Ireland', 'wprentals'),
            'Israel' => esc_html__('Israel', 'wprentals'),
            'Italy' => esc_html__('Italy', 'wprentals'),
            'Island of Saba' => esc_html__('Island of Saba', 'wprentals'),
            'Jamaica' => esc_html__('Jamaica', 'wprentals'),
            'Japan' => esc_html__('Japan', 'wprentals'),
            'Jordan' => esc_html__('Jordan', 'wprentals'),
            'Kazakhstan' => esc_html__('Kazakhstan', 'wprentals'),
            'Kenya' => esc_html__('Kenya', 'wprentals'),
            'Kiribati' => esc_html__('Kiribati', 'wprentals'),
            'Korea, Democratic People Republic of' => esc_html__('Korea, Democratic People Republic of', 'wprentals'),
            'Korea, Republic of' => esc_html__('Korea, Republic of', 'wprentals'),
            'Kosovo' => esc_html__('Kosovo', 'wprentals'),
            'Kuwait' => esc_html__('Kuwait', 'wprentals'),
            'Kyrgyzstan' => esc_html__('Kyrgyzstan', 'wprentals'),
            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic', 'wprentals'),
            'Latvia' => esc_html__('Latvia', 'wprentals'),
            'Lebanon' => esc_html__('Lebanon', 'wprentals'),
            'Lesotho' => esc_html__('Lesotho', 'wprentals'),
            'Liberia' => esc_html__('Liberia', 'wprentals'),
            'Libyan Arab Jamahiriya' => esc_html__('Libyan Arab Jamahiriya', 'wprentals'),
            'Liechtenstein' => esc_html__('Liechtenstein', 'wprentals'),
            'Lithuania' => esc_html__('Lithuania', 'wprentals'),
            'Les Saintes' => esc_html__('Les Saintes', 'wprentals'),
            'Luxembourg' => esc_html__('Luxembourg', 'wprentals'),
            'Macau' => esc_html__('Macau', 'wprentals'),
            'Macedonia, The Former Yugoslav Republic of' => esc_html__('Macedonia, The Former Yugoslav Republic of', 'wprentals'),
            'Madagascar' => esc_html__('Madagascar', 'wprentals'),
            'Malawi' => esc_html__('Malawi', 'wprentals'),
            'Malaysia' => esc_html__('Malaysia', 'wprentals'),
            'Maldives' => esc_html__('Maldives', 'wprentals'),
            'Mali' => esc_html__('Mali', 'wprentals'),
            'Malta' => esc_html__('Malta', 'wprentals'),
            'Marie-Galante' => esc_html__('Marie-Galante', 'wprentals'),
            'Marshall Islands' => esc_html__('Marshall Islands', 'wprentals'),
            'Martinique' => esc_html__('Martinique', 'wprentals'),
            'Mauritania' => esc_html__('Mauritania', 'wprentals'),
            'Mauritius' => esc_html__('Mauritius', 'wprentals'),
            'Mayotte' => esc_html__('Mayotte', 'wprentals'),
            'Mexico' => esc_html__('Mexico', 'wprentals'),
            'Micronesia, Federated States of' => esc_html__('Micronesia, Federated States of', 'wprentals'),
            'Moldova, Republic of' => esc_html__('Moldova, Republic of', 'wprentals'),
            'Monaco' => esc_html__('Monaco', 'wprentals'),
            'Mongolia' => esc_html__('Mongolia', 'wprentals'),
            'Montserrat' => esc_html__('Montserrat', 'wprentals'),
            'Morocco' => esc_html__('Morocco', 'wprentals'),
            'Mozambique' => esc_html__('Mozambique', 'wprentals'),
            'Montenegro' => esc_html__('Montenegro', 'wprentals'),
            'Myanmar' => esc_html__('Myanmar', 'wprentals'),
            'Namibia' => esc_html__('Namibia', 'wprentals'),
            'Nauru' => esc_html__('Nauru', 'wprentals'),
            'Nepal' => esc_html__('Nepal', 'wprentals'),
            'Netherlands' => esc_html__('Netherlands', 'wprentals'),
            'Netherlands Antilles' => esc_html__('Netherlands Antilles', 'wprentals'),
            'New Caledonia' => esc_html__('New Caledonia', 'wprentals'),
            'New Zealand' => esc_html__('New Zealand', 'wprentals'),
            'Nicaragua' => esc_html__('Nicaragua', 'wprentals'),
            'Niger' => esc_html__('Niger', 'wprentals'),
            'Nigeria' => esc_html__('Nigeria', 'wprentals'),
            'Niue' => esc_html__('Niue', 'wprentals'),
            'Norfolk Island' => esc_html__('Norfolk Island', 'wprentals'),
            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands', 'wprentals'),
            'Norway' => esc_html__('Norway', 'wprentals'),
            'Oman' => esc_html__('Oman', 'wprentals'),
            'Pakistan' => esc_html__('Pakistan', 'wprentals'),
            'Palau' => esc_html__('Palau', 'wprentals'),
            'Panama' => esc_html__('Panama', 'wprentals'),
            'Papua New Guinea' => esc_html__('Papua New Guinea', 'wprentals'),
            'Paraguay' => esc_html__('Paraguay', 'wprentals'),
            'Peru' => esc_html__('Peru', 'wprentals'),
            'Philippines' => esc_html__('Philippines', 'wprentals'),
            'Pitcairn' => esc_html__('Pitcairn', 'wprentals'),
            'Poland' => esc_html__('Poland', 'wprentals'),
            'Portugal' => esc_html__('Portugal', 'wprentals'),
            'Puerto Rico' => esc_html__('Puerto Rico', 'wprentals'),
            'Qatar' => esc_html__('Qatar', 'wprentals'),
            'Reunion' => esc_html__('Reunion', 'wprentals'),
            'Romania' => esc_html__('Romania', 'wprentals'),
            'Russian Federation' => esc_html__('Russian Federation', 'wprentals'),
            'Rwanda' => esc_html__('Rwanda', 'wprentals'),
            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis', 'wprentals'),
            'Saint Lucia' => esc_html__('Saint Lucia', 'wprentals'),
            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines', 'wprentals'),
            'Saint Barthélemy' => esc_html__('Saint Barthélemy', 'wprentals'),
            'Saint Martin' => esc_html__('Saint Martin', 'wprentals'),
            'Sint Maarten' => esc_html__('Sint Maarten', 'wprentals'),
            'Samoa' => esc_html__('Samoa', 'wprentals'),
            'San Marino' => esc_html__('San Marino', 'wprentals'),
            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe', 'wprentals'),
            'Saudi Arabia' => esc_html__('Saudi Arabia', 'wprentals'),
            'Serbia' => esc_html__('Serbia', 'wprentals'),
            'Senegal' => esc_html__('Senegal', 'wprentals'),
            'Seychelles' => esc_html__('Seychelles', 'wprentals'),
            'Sierra Leone' => esc_html__('Sierra Leone', 'wprentals'),
            'Singapore' => esc_html__('Singapore', 'wprentals'),
            'Slovakia (Slovak Republic)' => esc_html__('Slovakia (Slovak Republic)', 'wprentals'),
            'Slovenia' => esc_html__('Slovenia', 'wprentals'),
            'Solomon Islands' => esc_html__('Solomon Islands', 'wprentals'),
            'Somalia' => esc_html__('Somalia', 'wprentals'),
            'South Africa' => esc_html__('South Africa', 'wprentals'),
            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands', 'wprentals'),
            'Spain' => esc_html__('Spain', 'wprentals'),
            'Sri Lanka' => esc_html__('Sri Lanka', 'wprentals'),
            'St. Helena' => esc_html__('St. Helena', 'wprentals'),
            'St. Pierre and Miquelon' => esc_html__('St. Pierre and Miquelon', 'wprentals'),
            'Sudan' => esc_html__('Sudan', 'wprentals'),
            'Suriname' => esc_html__('Suriname', 'wprentals'),
            'Svalbard and Jan Mayen Islands' => esc_html__('Svalbard and Jan Mayen Islands', 'wprentals'),
            'Swaziland' => esc_html__('Swaziland', 'wprentals'),
            'Sweden' => esc_html__('Sweden', 'wprentals'),
            'Switzerland' => esc_html__('Switzerland', 'wprentals'),
            'Syrian Arab Republic' => esc_html__('Syrian Arab Republic', 'wprentals'),
            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China', 'wprentals'),
            'Tajikistan' => esc_html__('Tajikistan', 'wprentals'),
            'Tanzania, United Republic of' => esc_html__('Tanzania, United Republic of', 'wprentals'),
            'Thailand' => esc_html__('Thailand', 'wprentals'),
            'Togo' => esc_html__('Togo', 'wprentals'),
            'Tokelau' => esc_html__('Tokelau', 'wprentals'),
            'Tonga' => esc_html__('Tonga', 'wprentals'),
            'Trinidad and Tobago' => esc_html__('Trinidad and Tobago', 'wprentals'),
            'Tunisia' => esc_html__('Tunisia', 'wprentals'),
            'Turkey' => esc_html__('Turkey', 'wprentals'),
            'Turkmenistan' => esc_html__('Turkmenistan', 'wprentals'),
            'Turks and Caicos Islands' => esc_html__('Turks and Caicos Islands', 'wprentals'),
            'Tuvalu' => esc_html__('Tuvalu', 'wprentals'),
            'Uganda' => esc_html__('Uganda', 'wprentals'),
            'Ukraine' => esc_html__('Ukraine', 'wprentals'),
            'United Arab Emirates' => esc_html__('United Arab Emirates', 'wprentals'),
            'United Kingdom' => esc_html__('United Kingdom', 'wprentals'),
            'United States' => esc_html__('United States', 'wprentals'),
            'United States Minor Outlying Islands' => esc_html__('United States Minor Outlying Islands', 'wprentals'),
            'Uruguay' => esc_html__('Uruguay', 'wprentals'),
            'Uzbekistan' => esc_html__('Uzbekistan', 'wprentals'),
            'Vanuatu' => esc_html__('Vanuatu', 'wprentals'),
            'Venezuela' => esc_html__('Venezuela', 'wprentals'),
            'Vietnam' => esc_html__('Vietnam', 'wprentals'),
            'Virgin Islands (British)' => esc_html__('Virgin Islands (British)', 'wprentals'),
            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)', 'wprentals'),
            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands', 'wprentals'),
            'Western Sahara' => esc_html__('Western Sahara', 'wprentals'),
            'Yemen' => esc_html__('Yemen', 'wprentals'),
            'Yugoslavia' => esc_html__('Yugoslavia', 'wprentals'),
            'Zambia' => esc_html__('Zambia', 'wprentals'),
            'Zimbabwe' => esc_html__('Zimbabwe', 'wprentals')
        );
        return $countries;
    }

endif;

function wprentals_return_theme_slider_list() {
    $return_array = array();
    $args = array('post_type' => 'estate_property',
        'post_status' => 'publish',
        'paged' => 0,
        'posts_per_page' => 50,
        'cache_results' => false,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );

    $recent_posts = new WP_Query($args);

    while ($recent_posts->have_posts()): $recent_posts->the_post();
        $theid = get_the_ID();
        $return_array[$theid] = get_the_title();

    endwhile;

    return $return_array;
}

function wprentals_add_pins_icons($pin_fields) {
    $taxonomy = 'property_action_category';
    $tax_terms = get_terms($taxonomy, 'hide_empty=0');



    if (is_array($tax_terms)) {
        foreach ($tax_terms as $tax_term) {
            $limit54 = $post_name = sanitize_key(wpestate_limit54($tax_term->slug));

            $name = 'wp_estate_' . $post_name;
            $pin_fields[] = array(
                'id' => $name,
                'type' => 'media',
                'required' => array('wp_estate_use_single_image_pin', '!=', 'yes'),
                'title' => esc_html__('For action ', 'wprentals') . '<strong>' . $tax_term->name,
                'subtitle' => esc_html__('Image size must be 44px x 50px. ', 'wprentals'),
                'default' => 'no',
            );
        }
    }
    $taxonomy_cat = 'property_category';
    $categories = get_terms($taxonomy_cat, 'hide_empty=0');

    if (is_array($categories)) {
        foreach ($categories as $categ) {
            $limit54 = $post_name = sanitize_key(wpestate_limit54($categ->slug));
            $name = 'wp_estate_' . $post_name;
            $pin_fields[] = array(
                'id' => $name,
                'type' => 'media',
                'required' => array('wp_estate_use_single_image_pin', '!=', 'yes'),
                'title' => esc_html__('For category ', 'wprentals') . '<strong>' . $categ->name,
                'subtitle' => esc_html__('Image size must be 44px x 50px. ', 'wprentals'),
                'default' => 'no',
            );
        }
    }
    if (is_array($tax_terms)) {
        foreach ($tax_terms as $tax_term) {
            if (is_array($categories)) {
                foreach ($categories as $categ) {
                    $limit54 = sanitize_key(wpestate_limit27($categ->slug)) . sanitize_key(wpestate_limit27($tax_term->slug));

                    $name = 'wp_estate_' . $limit54;
                    $pin_fields[] = array(
                        'id' => $name,
                        'type' => 'media',
                        'required' => array('wp_estate_use_single_image_pin', '!=', 'yes'),
                        'title' => __('For action', 'wprentals') . ' <strong>' . $tax_term->name . '</strong>, ' . __('category', 'wprentals') . ': <strong>' . $categ->name . '</strong>',
                        'subtitle' => esc_html__('Image size must be 44px x 50px. ', 'wprentals'),
                        'default' => 'no',
                    );
                }
            }
        }
    }

    $pin_fields[] = array(
        'id' => 'wp_estate_userpin',
        'type' => 'media',
        'title' => esc_html__('Userpin in geolocation', 'wprentals') . '<strong>',
        'subtitle' => esc_html__('Image size must be 44px x 50px. ', 'wprentals'),
        'default' => 'no',
    );

    return $pin_fields;
}

function wprentals_redux_font_google() {
    $return = array();
    $return[''] = '- original font -';

    $google_fonts_array = wprentals_return_google_fonts();
    foreach ($google_fonts_array as $key => $value) {
        $return[$key] = $value;
    }
    return $return;
}

function wprentals_redux_yelp() {
    $yelp_terms_array = array(
                'active' => array('category' => __('Active Life', 'wprentals'),
                    'category_sign' => 'fas fa-motorcycle'),
                'arts' => array('category' => __('Arts & Entertainment', 'wprentals'),
                    'category_sign' => 'fas fa-music'),
                'auto' => array('category' => __('Automotive', 'wprentals'),
                    'category_sign' => 'fas fa-car'),
                'beautysvc' => array('category' => __('Beauty & Spas', 'wprentals'),
                    'category_sign' => 'fas fa-female'),
                'education' => array('category' => __('Education', 'wprentals'),
                    'category_sign' => 'fas fa-graduation-cap'),
                'eventservices' => array('category' => __('Event Planning & Services', 'wprentals'),
                    'category_sign' => 'fas fa-birthday-cake'),
                'financialservices' => array('category' => __('Financial Services', 'wprentals'),
                    'category_sign' => 'fas fa-money-bill'),
                'food' => array('category' => __('Food', 'wprentals'),
                    'category_sign' => 'fas fa-utensils'),
                'health' => array('category' => __('Health & Medical', 'wprentals'),
                    'category_sign' => 'fas fa-briefcase-medical'),
                'homeservices' => array('category' => __('Home Services ', 'wprentals'),
                    'category_sign' => 'fas fa-wrench'),
                'hotelstravel' => array('category' => __('Hotels & Travel', 'wprentals'),
                    'category_sign' => 'fas fa-bed'),
                'localflavor' => array('category' => __('Local Flavor', 'wprentals'),
                    'category_sign' => 'fas fa-coffee'),
                'localservices' => array('category' => __('Local Services', 'wprentals'),
                    'category_sign' => 'fas fa-dot-circle'),
                'massmedia' => array('category' => __('Mass Media', 'wprentals'),
                    'category_sign' => 'fas fa-tv'),
                'nightlife' => array('category' => __('Nightlife', 'wprentals'),
                    'category_sign' => 'fas fa-glass-martini-alt'),
                'pets' => array('category' => __('Pets', 'wprentals'),
                    'category_sign' => 'fas fa-paw'),
                'professional' => array('category' => __('Professional Services', 'wprentals'),
                    'category_sign' => 'fas fa-suitcase'),
                'publicservicesgovt' => array('category' => __('Public Services & Government', 'wprentals'),
                    'category_sign' => 'fas fa-university'),
                'realestate' => array('category' => __('Real Estate', 'wprentals'),
                    'category_sign' => 'fas fa-building'),
                'religiousorgs' => array('category' => __('Religious Organizations', 'wprentals'),
                    'category_sign' => 'fas fa-cloud'),
                'restaurants' => array('category' => __('Restaurants', 'wprentals'),
                    'category_sign' => 'fas fa-utensils'),
                'shopping' => array('category' => __('Shopping', 'wprentals'),
                    'category_sign' => 'fas fa-shopping-bag'),
                'transport' => array('category' => __('Transportation', 'wprentals'),
                    'category_sign' => 'fas fa-bus-alt')
    );

    $return = array();
    foreach ($yelp_terms_array as $key => $term) {
        $return[$key] = $term['category'];
    }
    return $return;
}

if (!function_exists('wprentals_return_google_fonts')):

    function wprentals_return_google_fonts() {
        $google_fonts_array = array(
            "Abel" => "Abel",
            "Abril Fatface" => "Abril Fatface",
            "Aclonica" => "Aclonica",
            "Acme" => "Acme",
            "Actor" => "Actor",
            "Adamina" => "Adamina",
            "Advent Pro" => "Advent Pro",
            "Aguafina Script" => "Aguafina Script",
            "Aladin" => "Aladin",
            "Aldrich" => "Aldrich",
            "Alegreya" => "Alegreya",
            "Alegreya SC" => "Alegreya SC",
            "Alex Brush" => "Alex Brush",
            "Alfa Slab One" => "Alfa Slab One",
            "Alice" => "Alice",
            "Alike" => "Alike",
            "Alike Angular" => "Alike Angular",
            "Allan" => "Allan",
            "Allerta" => "Allerta",
            "Allerta Stencil" => "Allerta Stencil",
            "Allura" => "Allura",
            "Almendra" => "Almendra",
            "Almendra SC" => "Almendra SC",
            "Amaranth" => "Amaranth",
            "Amatic SC" => "Amatic SC",
            "Amethysta" => "Amethysta",
            "Andada" => "Andada",
            "Andika" => "Andika",
            "Angkor" => "Angkor",
            "Annie Use Your Telescope" => "Annie Use Your Telescope",
            "Anonymous Pro" => "Anonymous Pro",
            "Antic" => "Antic",
            "Antic Didone" => "Antic Didone",
            "Antic Slab" => "Antic Slab",
            "Anton" => "Anton",
            "Arapey" => "Arapey",
            "Arbutus" => "Arbutus",
            "Architects Daughter" => "Architects Daughter",
            "Arimo" => "Arimo",
            "Arizonia" => "Arizonia",
            "Armata" => "Armata",
            "Artifika" => "Artifika",
            "Arvo" => "Arvo",
            "Asap" => "Asap",
            "Asset" => "Asset",
            "Astloch" => "Astloch",
            "Asul" => "Asul",
            "Atomic Age" => "Atomic Age",
            "Aubrey" => "Aubrey",
            "Audiowide" => "Audiowide",
            "Average" => "Average",
            "Averia Gruesa Libre" => "Averia Gruesa Libre",
            "Averia Libre" => "Averia Libre",
            "Averia Sans Libre" => "Averia Sans Libre",
            "Averia Serif Libre" => "Averia Serif Libre",
            "Bad Script" => "Bad Script",
            "Balthazar" => "Balthazar",
            "Bangers" => "Bangers",
            "Basic" => "Basic",
            "Battambang" => "Battambang",
            "Baumans" => "Baumans",
            "Bayon" => "Bayon",
            "Belgrano" => "Belgrano",
            "Belleza" => "Belleza",
            "Bentham" => "Bentham",
            "Berkshire Swash" => "Berkshire Swash",
            "Bevan" => "Bevan",
            "Bigshot One" => "Bigshot One",
            "Bilbo" => "Bilbo",
            "Bilbo Swash Caps" => "Bilbo Swash Caps",
            "Bitter" => "Bitter",
            "Black Ops One" => "Black Ops One",
            "Bokor" => "Bokor",
            "Bonbon" => "Bonbon",
            "Boogaloo" => "Boogaloo",
            "Bowlby One" => "Bowlby One",
            "Bowlby One SC" => "Bowlby One SC",
            "Brawler" => "Brawler",
            "Bree Serif" => "Bree Serif",
            "Bubblegum Sans" => "Bubblegum Sans",
            "Buda" => "Buda",
            "Buenard" => "Buenard",
            "Butcherman" => "Butcherman",
            "Butterfly Kids" => "Butterfly Kids",
            "Cabin" => "Cabin",
            "Cabin Condensed" => "Cabin Condensed",
            "Cabin Sketch" => "Cabin Sketch",
            "Caesar Dressing" => "Caesar Dressing",
            "Cagliostro" => "Cagliostro",
            "Calligraffitti" => "Calligraffitti",
            "Cambo" => "Cambo",
            "Candal" => "Candal",
            "Cantarell" => "Cantarell",
            "Cantata One" => "Cantata One",
            "Cardo" => "Cardo",
            "Carme" => "Carme",
            "Carter One" => "Carter One",
            "Caudex" => "Caudex",
            "Cedarville Cursive" => "Cedarville Cursive",
            "Ceviche One" => "Ceviche One",
            "Changa One" => "Changa One",
            "Chango" => "Chango",
            "Chau Philomene One" => "Chau Philomene One",
            "Chelsea Market" => "Chelsea Market",
            "Chenla" => "Chenla",
            "Cherry Cream Soda" => "Cherry Cream Soda",
            "Chewy" => "Chewy",
            "Chicle" => "Chicle",
            "Chivo" => "Chivo",
            "Coda" => "Coda",
            "Coda Caption" => "Coda Caption",
            "Codystar" => "Codystar",
            "Comfortaa" => "Comfortaa",
            "Coming Soon" => "Coming Soon",
            "Concert One" => "Concert One",
            "Condiment" => "Condiment",
            "Content" => "Content",
            "Contrail One" => "Contrail One",
            "Convergence" => "Convergence",
            "Cookie" => "Cookie",
            "Copse" => "Copse",
            "Corben" => "Corben",
            "Cousine" => "Cousine",
            "Coustard" => "Coustard",
            "Covered By Your Grace" => "Covered By Your Grace",
            "Crafty Girls" => "Crafty Girls",
            "Creepster" => "Creepster",
            "Crete Round" => "Crete Round",
            "Crimson Text" => "Crimson Text",
            "Crushed" => "Crushed",
            "Cuprum" => "Cuprum",
            "Cutive" => "Cutive",
            "Damion" => "Damion",
            "Dancing Script" => "Dancing Script",
            "Dangrek" => "Dangrek",
            "Dawning of a New Day" => "Dawning of a New Day",
            "Days One" => "Days One",
            "Delius" => "Delius",
            "Delius Swash Caps" => "Delius Swash Caps",
            "Delius Unicase" => "Delius Unicase",
            "Della Respira" => "Della Respira",
            "Devonshire" => "Devonshire",
            "Didact Gothic" => "Didact Gothic",
            "Diplomata" => "Diplomata",
            "Diplomata SC" => "Diplomata SC",
            "Doppio One" => "Doppio One",
            "Dorsa" => "Dorsa",
            "Dosis" => "Dosis",
            "Dr Sugiyama" => "Dr Sugiyama",
            "Droid Sans" => "Droid Sans",
            "Droid Sans Mono" => "Droid Sans Mono",
            "Droid Serif" => "Droid Serif",
            "Duru Sans" => "Duru Sans",
            "Dynalight" => "Dynalight",
            "EB Garamond" => "EB Garamond",
            "Eater" => "Eater",
            "Economica" => "Economica",
            "Electrolize" => "Electrolize",
            "Emblema One" => "Emblema One",
            "Emilys Candy" => "Emilys Candy",
            "Engagement" => "Engagement",
            "Enriqueta" => "Enriqueta",
            "Erica One" => "Erica One",
            "Esteban" => "Esteban",
            "Euphoria Script" => "Euphoria Script",
            "Ewert" => "Ewert",
            "Exo" => "Exo",
            "Expletus Sans" => "Expletus Sans",
            "Fanwood Text" => "Fanwood Text",
            "Fascinate" => "Fascinate",
            "Fascinate Inline" => "Fascinate Inline",
            "Federant" => "Federant",
            "Federo" => "Federo",
            "Felipa" => "Felipa",
            "Fjord One" => "Fjord One",
            "Flamenco" => "Flamenco",
            "Flavors" => "Flavors",
            "Fondamento" => "Fondamento",
            "Fontdiner Swanky" => "Fontdiner Swanky",
            "Forum" => "Forum",
            "Francois One" => "Francois One",
            "Fredericka the Great" => "Fredericka the Great",
            "Fredoka One" => "Fredoka One",
            "Freehand" => "Freehand",
            "Fresca" => "Fresca",
            "Frijole" => "Frijole",
            "Fugaz One" => "Fugaz One",
            "GFS Didot" => "GFS Didot",
            "GFS Neohellenic" => "GFS Neohellenic",
            "Galdeano" => "Galdeano",
            "Gentium Basic" => "Gentium Basic",
            "Gentium Book Basic" => "Gentium Book Basic",
            "Geo" => "Geo",
            "Geostar" => "Geostar",
            "Geostar Fill" => "Geostar Fill",
            "Germania One" => "Germania One",
            "Give You Glory" => "Give You Glory",
            "Glass Antiqua" => "Glass Antiqua",
            "Glegoo" => "Glegoo",
            "Gloria Hallelujah" => "Gloria Hallelujah",
            "Goblin One" => "Goblin One",
            "Gochi Hand" => "Gochi Hand",
            "Gorditas" => "Gorditas",
            "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
            "Graduate" => "Graduate",
            "Gravitas One" => "Gravitas One",
            "Great Vibes" => "Great Vibes",
            "Gruppo" => "Gruppo",
            "Gudea" => "Gudea",
            "Habibi" => "Habibi",
            "Hammersmith One" => "Hammersmith One",
            "Handlee" => "Handlee",
            "Hanuman" => "Hanuman",
            "Happy Monkey" => "Happy Monkey",
            "Henny Penny" => "Henny Penny",
            "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
            "Holtwood One SC" => "Holtwood One SC",
            "Homemade Apple" => "Homemade Apple",
            "Homenaje" => "Homenaje",
            "IM Fell DW Pica" => "IM Fell DW Pica",
            "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
            "IM Fell Double Pica" => "IM Fell Double Pica",
            "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
            "IM Fell English" => "IM Fell English",
            "IM Fell English SC" => "IM Fell English SC",
            "IM Fell French Canon" => "IM Fell French Canon",
            "IM Fell French Canon SC" => "IM Fell French Canon SC",
            "IM Fell Great Primer" => "IM Fell Great Primer",
            "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
            "Iceberg" => "Iceberg",
            "Iceland" => "Iceland",
            "Imprima" => "Imprima",
            "Inconsolata" => "Inconsolata",
            "Inder" => "Inder",
            "Indie Flower" => "Indie Flower",
            "Inika" => "Inika",
            "Irish Grover" => "Irish Grover",
            "Istok Web" => "Istok Web",
            "Italiana" => "Italiana",
            "Italianno" => "Italianno",
            "Jim Nightshade" => "Jim Nightshade",
            "Jockey One" => "Jockey One",
            "Jolly Lodger" => "Jolly Lodger",
            "Josefin Sans" => "Josefin Sans",
            "Josefin Slab" => "Josefin Slab",
            "Judson" => "Judson",
            "Julee" => "Julee",
            "Junge" => "Junge",
            "Jura" => "Jura",
            "Just Another Hand" => "Just Another Hand",
            "Just Me Again Down Here" => "Just Me Again Down Here",
            "Kameron" => "Kameron",
            "Karla" => "Karla",
            "Kaushan Script" => "Kaushan Script",
            "Kelly Slab" => "Kelly Slab",
            "Kenia" => "Kenia",
            "Khmer" => "Khmer",
            "Knewave" => "Knewave",
            "Kotta One" => "Kotta One",
            "Koulen" => "Koulen",
            "Kranky" => "Kranky",
            "Kreon" => "Kreon",
            "Kristi" => "Kristi",
            "Krona One" => "Krona One",
            "La Belle Aurore" => "La Belle Aurore",
            "Lancelot" => "Lancelot",
            "Lato" => "Lato",
            "League Script" => "League Script",
            "Leckerli One" => "Leckerli One",
            "Ledger" => "Ledger",
            "Lekton" => "Lekton",
            "Lemon" => "Lemon",
            "Lilita One" => "Lilita One",
            "Limelight" => "Limelight",
            "Linden Hill" => "Linden Hill",
            "Lobster" => "Lobster",
            "Lobster Two" => "Lobster Two",
            "Londrina Outline" => "Londrina Outline",
            "Londrina Shadow" => "Londrina Shadow",
            "Londrina Sketch" => "Londrina Sketch",
            "Londrina Solid" => "Londrina Solid",
            "Lora" => "Lora",
            "Love Ya Like A Sister" => "Love Ya Like A Sister",
            "Loved by the King" => "Loved by the King",
            "Lovers Quarrel" => "Lovers Quarrel",
            "Luckiest Guy" => "Luckiest Guy",
            "Lusitana" => "Lusitana",
            "Lustria" => "Lustria",
            "Macondo" => "Macondo",
            "Macondo Swash Caps" => "Macondo Swash Caps",
            "Magra" => "Magra",
            "Maiden Orange" => "Maiden Orange",
            "Mako" => "Mako",
            "Marck Script" => "Marck Script",
            "Marko One" => "Marko One",
            "Marmelad" => "Marmelad",
            "Marvel" => "Marvel",
            "Mate" => "Mate",
            "Mate SC" => "Mate SC",
            "Maven Pro" => "Maven Pro",
            "Meddon" => "Meddon",
            "MedievalSharp" => "MedievalSharp",
            "Medula One" => "Medula One",
            "Megrim" => "Megrim",
            "Merienda One" => "Merienda One",
            "Merriweather" => "Merriweather",
            "Metal" => "Metal",
            "Metamorphous" => "Metamorphous",
            "Metrophobic" => "Metrophobic",
            "Michroma" => "Michroma",
            "Miltonian" => "Miltonian",
            "Miltonian Tattoo" => "Miltonian Tattoo",
            "Miniver" => "Miniver",
            "Miss Fajardose" => "Miss Fajardose",
            "Modern Antiqua" => "Modern Antiqua",
            "Molengo" => "Molengo",
            "Monofett" => "Monofett",
            "Monoton" => "Monoton",
            "Monsieur La Doulaise" => "Monsieur La Doulaise",
            "Montaga" => "Montaga",
            "Montez" => "Montez",
            "Montserrat" => "Montserrat",
            "Moul" => "Moul",
            "Moulpali" => "Moulpali",
            "Mountains of Christmas" => "Mountains of Christmas",
            "Mr Bedfort" => "Mr Bedfort",
            "Mr Dafoe" => "Mr Dafoe",
            "Mr De Haviland" => "Mr De Haviland",
            "Mrs Saint Delafield" => "Mrs Saint Delafield",
            "Mrs Sheppards" => "Mrs Sheppards",
            "Muli" => "Muli",
            "Mystery Quest" => "Mystery Quest",
            "Neucha" => "Neucha",
            "Neuton" => "Neuton",
            "News Cycle" => "News Cycle",
            "Niconne" => "Niconne",
            "Nixie One" => "Nixie One",
            "Nobile" => "Nobile",
            "Nokora" => "Nokora",
            "Norican" => "Norican",
            "Nosifer" => "Nosifer",
            "Nothing You Could Do" => "Nothing You Could Do",
            "Noticia Text" => "Noticia Text",
            "Nova Cut" => "Nova Cut",
            "Nova Flat" => "Nova Flat",
            "Nova Mono" => "Nova Mono",
            "Nova Oval" => "Nova Oval",
            "Nova Round" => "Nova Round",
            "Nova Script" => "Nova Script",
            "Nova Slim" => "Nova Slim",
            "Nova Square" => "Nova Square",
            "Numans" => "Numans",
            "Nunito" => "Nunito",
            "Odor Mean Chey" => "Odor Mean Chey",
            "Old Standard TT" => "Old Standard TT",
            "Oldenburg" => "Oldenburg",
            "Oleo Script" => "Oleo Script",
            "Open Sans" => "Open Sans",
            "Open Sans Condensed" => "Open Sans Condensed",
            "Orbitron" => "Orbitron",
            "Original Surfer" => "Original Surfer",
            "Oswald" => "Oswald",
            "Over the Rainbow" => "Over the Rainbow",
            "Overlock" => "Overlock",
            "Overlock SC" => "Overlock SC",
            "Ovo" => "Ovo",
            "Oxygen" => "Oxygen",
            "PT Mono" => "PT Mono",
            "PT Sans" => "PT Sans",
            "PT Sans Caption" => "PT Sans Caption",
            "PT Sans Narrow" => "PT Sans Narrow",
            "PT Serif" => "PT Serif",
            "PT Serif Caption" => "PT Serif Caption",
            "Pacifico" => "Pacifico",
            "Parisienne" => "Parisienne",
            "Passero One" => "Passero One",
            "Passion One" => "Passion One",
            "Patrick Hand" => "Patrick Hand",
            "Patua One" => "Patua One",
            "Paytone One" => "Paytone One",
            "Permanent Marker" => "Permanent Marker",
            "Petrona" => "Petrona",
            "Philosopher" => "Philosopher",
            "Piedra" => "Piedra",
            "Pinyon Script" => "Pinyon Script",
            "Plaster" => "Plaster",
            "Play" => "Play",
            "Playball" => "Playball",
            "Playfair Display" => "Playfair Display",
            "Podkova" => "Podkova",
            "Poiret One" => "Poiret One",
            "Poller One" => "Poller One",
            "Poly" => "Poly",
            "Pompiere" => "Pompiere",
            "Pontano Sans" => "Pontano Sans",
            "Port Lligat Sans" => "Port Lligat Sans",
            "Port Lligat Slab" => "Port Lligat Slab",
            "Prata" => "Prata",
            "Preahvihear" => "Preahvihear",
            "Press Start 2P" => "Press Start 2P",
            "Princess Sofia" => "Princess Sofia",
            "Prociono" => "Prociono",
            "Prosto One" => "Prosto One",
            "Puritan" => "Puritan",
            "Quantico" => "Quantico",
            "Quattrocento" => "Quattrocento",
            "Quattrocento Sans" => "Quattrocento Sans",
            "Questrial" => "Questrial",
            "Quicksand" => "Quicksand",
            "Qwigley" => "Qwigley",
            "Radley" => "Radley",
            "Raleway" => "Raleway",
            "Rammetto One" => "Rammetto One",
            "Rancho" => "Rancho",
            "Rationale" => "Rationale",
            "Redressed" => "Redressed",
            "Reenie Beanie" => "Reenie Beanie",
            "Revalia" => "Revalia",
            "Ribeye" => "Ribeye",
            "Ribeye Marrow" => "Ribeye Marrow",
            "Righteous" => "Righteous",
            "Rochester" => "Rochester",
            "Rock Salt" => "Rock Salt",
            "Rokkitt" => "Rokkitt",
            "Ropa Sans" => "Ropa Sans",
            "Rosario" => "Rosario",
            "Rosarivo" => "Rosarivo",
            "Rouge Script" => "Rouge Script",
            "Ruda" => "Ruda",
            "Ruge Boogie" => "Ruge Boogie",
            "Ruluko" => "Ruluko",
            "Ruslan Display" => "Ruslan Display",
            "Russo One" => "Russo One",
            "Ruthie" => "Ruthie",
            "Sail" => "Sail",
            "Salsa" => "Salsa",
            "Sancreek" => "Sancreek",
            "Sansita One" => "Sansita One",
            "Sarina" => "Sarina",
            "Satisfy" => "Satisfy",
            "Schoolbell" => "Schoolbell",
            "Seaweed Script" => "Seaweed Script",
            "Sevillana" => "Sevillana",
            "Shadows Into Light" => "Shadows Into Light",
            "Shadows Into Light Two" => "Shadows Into Light Two",
            "Shanti" => "Shanti",
            "Share" => "Share",
            "Shojumaru" => "Shojumaru",
            "Short Stack" => "Short Stack",
            "Siemreap" => "Siemreap",
            "Sigmar One" => "Sigmar One",
            "Signika" => "Signika",
            "Signika Negative" => "Signika Negative",
            "Simonetta" => "Simonetta",
            "Sirin Stencil" => "Sirin Stencil",
            "Six Caps" => "Six Caps",
            "Slackey" => "Slackey",
            "Smokum" => "Smokum",
            "Smythe" => "Smythe",
            "Sniglet" => "Sniglet",
            "Snippet" => "Snippet",
            "Sofia" => "Sofia",
            "Sonsie One" => "Sonsie One",
            "Sorts Mill Goudy" => "Sorts Mill Goudy",
            "Special Elite" => "Special Elite",
            "Spicy Rice" => "Spicy Rice",
            "Spinnaker" => "Spinnaker",
            "Spirax" => "Spirax",
            "Squada One" => "Squada One",
            "Stardos Stencil" => "Stardos Stencil",
            "Stint Ultra Condensed" => "Stint Ultra Condensed",
            "Stint Ultra Expanded" => "Stint Ultra Expanded",
            "Stoke" => "Stoke",
            "Sue Ellen Francisco" => "Sue Ellen Francisco",
            "Sunshiney" => "Sunshiney",
            "Supermercado One" => "Supermercado One",
            "Suwannaphum" => "Suwannaphum",
            "Swanky and Moo Moo" => "Swanky and Moo Moo",
            "Syncopate" => "Syncopate",
            "Tangerine" => "Tangerine",
            "Taprom" => "Taprom",
            "Telex" => "Telex",
            "Tenor Sans" => "Tenor Sans",
            "The Girl Next Door" => "The Girl Next Door",
            "Tienne" => "Tienne",
            "Tinos" => "Tinos",
            "Titan One" => "Titan One",
            "Trade Winds" => "Trade Winds",
            "Trocchi" => "Trocchi",
            "Trochut" => "Trochut",
            "Trykker" => "Trykker",
            "Tulpen One" => "Tulpen One",
            "Ubuntu" => "Ubuntu",
            "Ubuntu Condensed" => "Ubuntu Condensed",
            "Ubuntu Mono" => "Ubuntu Mono",
            "Ultra" => "Ultra",
            "Uncial Antiqua" => "Uncial Antiqua",
            "UnifrakturCook" => "UnifrakturCook",
            "UnifrakturMaguntia" => "UnifrakturMaguntia",
            "Unkempt" => "Unkempt",
            "Unlock" => "Unlock",
            "Unna" => "Unna",
            "VT323" => "VT323",
            "Varela" => "Varela",
            "Varela Round" => "Varela Round",
            "Vast Shadow" => "Vast Shadow",
            "Vibur" => "Vibur",
            "Vidaloka" => "Vidaloka",
            "Viga" => "Viga",
            "Voces" => "Voces",
            "Volkhov" => "Volkhov",
            "Vollkorn" => "Vollkorn",
            "Voltaire" => "Voltaire",
            "Waiting for the Sunrise" => "Waiting for the Sunrise",
            "Wallpoet" => "Wallpoet",
            "Walter Turncoat" => "Walter Turncoat",
            "Wellfleet" => "Wellfleet",
            "Wire One" => "Wire One",
            "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
            "Yellowtail" => "Yellowtail",
            "Yeseva One" => "Yeseva One",
            "Yesteryear" => "Yesteryear",
            "Zeyada" => "Zeyada",
        );
        return $google_fonts_array;
    }

endif;


if (!function_exists('wpestate_show_virtual_tour')) {

    function wpestate_show_virtual_tour($post_id) {
        $iframe = array('iframe' => array(
                'src' => array(),
                'width' => array(),
                'height' => array(),
                'frameborder' => array(),
                'style' => array(),
                'allowFullScreen' => array() // add any other attributes you wish to allow
            ));

        $virtual_tour = get_post_meta($post_id, 'virtual_tour', true);

        if ($virtual_tour == '') {
            return;
        }

        print '<div class="panel-wrapper virtual_tour_wrapper">';
        print '<a class="panel-title" id="virtual_tour_wrapper" data-toggle="collapse" data-parent="#virtual_tour_wrapper" href="#collapsevirtualtour"><span class="panel-title-arrow"></span>' . esc_html__('Virtual Tour', 'wprentals') . '</a>';
        print '<div id="collapsevirtualtour" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">';
        print wp_kses($virtual_tour, $iframe);
        print '</div>
                </div>
        </div>';
    }

}




/*
*
*
* Check if we need to load stripe
*
*
*/



if (!function_exists('wpestate_ready_to_load_stripe')):

    function wpestate_ready_to_load_stripe() {
        global $post;
        $page_template='';
        if(isset($post->ID)){
            $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        }
        $enable_stripe_status = esc_html(wprentals_get_option('wp_estate_enable_stripe', ''));
        if ($enable_stripe_status === 'yes') {
            if (isset($post->ID) 
                    && ( $page_template == 'user_dashboard_my_reservations.php' || 
                         $page_template == 'user_dashboard_packs.php' || 
                         $page_template == 'user_dashboard.php' )) {
                return true;
            } else if (isset($post->ID) && is_singular('estate_property') && floatval(get_post_meta($post->ID, 'instant_booking', true)) == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

endif;


if (!function_exists('wpestate_starts_reviews_core')):

    function wpestate_starts_reviews_core($stars) {
        $whole = floor($stars);
        $fraction = $stars - $whole;
        $return_string = '';

        for ($i = 1; $i <= $whole; $i++) {
            $return_string .= '<i class="fas fa-star"></i>';
        }
        if ($fraction > 0) {
            $return_string .= '<i class="fas fa-star-half"></i>';
        }
        return $return_string;
    }

endif;




if (!function_exists('wpestate_return_advanced_guests_fields')):

    function wpestate_return_advanced_guests_fields() {
        $controls_array = array(
            'adults' => array(
                esc_html__('Adults', 'wprentals'),
                esc_html__('Ages 13 or above', 'wprentals')
            ),
            'childs' => array(
                esc_html__('Children', 'wprentals'),
                esc_html__('Ages 2 to 12', 'wprentals')
            ),
            'infants' => array(
                esc_html__('Infants', 'wprentals'),
                esc_html__('Under 2 years', 'wprentals')
            ),
        );
        return $controls_array;
    }


endif;