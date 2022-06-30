<?php
/////////////////////////////////////////////////////////////////////////////////////////////////
//// add weekly interval
/////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'cron_schedules', 'wpestate_add_weekly_cron_schedule',1 );

if( !function_exists('wpestate_add_weekly_cron_schedule') ): 
    function wpestate_add_weekly_cron_schedule( $schedules ) {
        $schedules['weekly'] = array(
            'interval' => 604800, // 1 week in seconds
            'display'  => esc_html__(  'Once Weekly','wprentals'),
        );

        $schedules['hourlythree'] = array(
            'interval' => 10800, // 3 hours
            'display'  => esc_html__(  'Every 3 hours','wprentals'),
        );

      
	return $schedules;
    }
endif;






if(!function_exists('wpestate_create_auto_data')):
function wpestate_create_auto_data(){

    if ( !wp_next_scheduled( 'event_wp_estate_create_auto' ) ) {
        wp_schedule_event( time(), 'daily', 'event_wp_estate_create_auto');
    }
}
endif;


add_action( 'event_wp_estate_create_auto', 'wprentals_event_wp_estate_create_auto_function' );


if( !function_exists('wprentals_event_wp_estate_create_auto_function') ): 
function wprentals_event_wp_estate_create_auto_function(){
    $show_adv_search_general            =   wprentals_get_option('wp_estate_wpestate_autocomplete','');
    if($show_adv_search_general=='no'){
        $availableTags          =   '';
        $availableTags_array  =   array();
        
        $show_empty_city_status= esc_html ( wprentals_get_option('wp_estate_show_empty_city','') );
        
        if ( $show_empty_city_status=='no' ){
            $args = array(
                'orderby' => 'count',
                'hide_empty' => 1,
            ); 
        }else{
            $args = array(
                'orderby' => 'count',
                'hide_empty' => 0,
            ); 
        }
   

        $terms = get_terms( 'property_city', $args );
        foreach ( $terms as $term ) {
            $availableTags.= ' { label: "'.$term->name.'", category: "tax" },';
            $temp_array=array(
                'label'=>$term->name,
                'category'=>'tax'    
                );
            $availableTags_array[]=$temp_array;
        }

        $terms = get_terms( 'property_area', $args );
        foreach ( $terms as $term ) {
            $availableTags.= ' { label: "'.$term->name.'", category: "tax" },';
            $temp_array=array(
                  'label'=>$term->name,
                  'category'=>'tax'    
                  );
            $availableTags_array[]=$temp_array;
        }

        $country    = wpestate_get_meta_values('property_country');
        foreach ( $country as $term ) {
            $availableTags.= ' { label: "'.$term.'", category: "meta" },';
            $temp_array=array(
                  'label'=>$term,
                  'category'=>'meta'    
                  );
            $availableTags_array[]=$temp_array;
        }

        $state      = wpestate_get_meta_values('property_state');
        foreach ( $state as $term ) {
            $availableTags.= ' { label: "'.$term.'", category: "meta" },';
            $temp_array=array(
                  'label'=>$term,
                  'category'=>'meta'    
                  );
            $availableTags_array[]=$temp_array;
        }

        $conty      = wpestate_get_meta_values('property_county');
        foreach ( $conty as $term ) {
            $availableTags.= ' { label: "'.$term.'", category: "meta" },';
           $temp_array=array(
                  'label'=>$term,
                  'category'=>'meta'    
                  );
            $availableTags_array[]=$temp_array;
        }
    }
    
    update_option('wpestate_autocomplete_data',$availableTags);
    
    update_option('wpestate_autocomplete_data_select',$availableTags_array);
    
}
endif;


function wpestate_get_meta_values( $key = '', $type = 'estate_property', $status = 'publish' ) {
    global $wpdb;

    if( empty( $key ) )
        return;

    $r = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT  pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '%s' 
        AND p.post_status = '%s' 
        AND p.post_type = '%s'
    ", $key, $status, $type ) );
    return $r;
}













//wp_clear_scheduled_hook('event_wp_estate_sync_ical');
if( !function_exists('wprentals_setup_event_wp_estate_sync_ical') ): 
    function wprentals_setup_event_wp_estate_sync_ical() {
            if ( ! wp_next_scheduled( 'event_wp_estate_sync_ical' ) ) {
                    wp_schedule_event( time(), 'hourlythree', 'event_wp_estate_sync_ical');
            }
    }
endif;
wprentals_setup_event_wp_estate_sync_ical();
add_action( 'event_wp_estate_sync_ical', 'wpestate_sync_ical' );



if( !function_exists('wpestate_sync_ical') ): 
function wpestate_sync_ical(){
    $args = array(
            'post_type'         =>  'estate_property',
            'post_status'       =>  'published',
            'posts_per_page'    =>  -1,
            'meta_query' => array(
                            
                                array(
                                    'key'       => 'property_icalendar_import_multi',
                                    'value'     => '',
                                    'compare'   => '!='
                                )
                           
                        )
        );

     
        $prop_selection =   new WP_Query($args);

        
      
        
        if ($prop_selection->have_posts()){  
            while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                $post_id=get_the_id();
                print '</br>SYNC FOR '.$post_id.' '.get_the_title() ;
                  wpestate_import_calendar_feed_listing_global($post_id);
            endwhile;
        }
}

endif;






if( !function_exists('wpestate_setup_wp_estate_delete_orphan_lists') ): 
    function wpestate_setup_wp_estate_delete_orphan_lists() {
            if ( ! wp_next_scheduled( 'prefix_wp_estate_delete_orphan_lists' ) ) {
                    wp_schedule_event( time(), 'daily', 'prefix_wp_estate_delete_orphan_lists');
            }
    }
endif;
//setup_wp_estate_delete_orphan_lists();
add_action( 'prefix_wp_estate_delete_orphan_lists', 'wpestate_delete_orphan_lists' );





if( !function_exists('wpestate_delete_orphan_lists') ): 
function wpestate_delete_orphan_lists(){
    //scheck
    $args = array(
            'post_type'         => 'estate_property',
            'post_status'       =>'any',
            'orderby'           => 'ID',
            'order'             => 'DESC',
             'author__in' => array( 0 ) 
            

        );
        $prop_selection =   new WP_Query($args);

        if ($prop_selection->have_posts()){  
            while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                $post_id=get_the_id();
                $author_id=wpsestate_get_author($post_id);
            
                if ( $author_id==0 ){
                     wp_delete_post($post_id);
                }
            endwhile;
        }
}

endif;





/////////////////////////////////////////////////////////////////////////////////////////////////
//// schedule user_checks
/////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wprentals_schedule_user_check') ): 
    function wprentals_schedule_user_check(){
        $paid_submission_status    = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
        if($paid_submission_status == 'membership' ){
            //  wpestate_check_user_membership_status_function();
            wp_clear_scheduled_hook('wpestate_check_for_users_event');
            wpestate_setup_daily_user_schedule();  
        }
    }
endif;

/////////////////////////////////////////////////////////////////////////////////////////////////
//// schedule daily USER check
/////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_setup_daily_user_schedule') ): 
    function  wpestate_setup_daily_user_schedule(){
        if ( ! wp_next_scheduled( 'wpestate_check_for_users_event' ) ) {
            //daily
            wp_schedule_event( time(), 'twicedaily', 'wpestate_check_for_users_event');
        }
    }
endif;
add_action( 'wpestate_check_for_users_event', 'wpestate_check_user_membership_status_function' );




/////////////////////////////////////////////////////////////////////////////////////////////////
//// schedule daily pin generation
/////////////////////////////////////////////////////////////////////////////////////////////////

//add_action( 'wp', 'wpestate_setup_cron_generate_pins_daily' );

if( !function_exists('wpestate_setup_cron_generate_pins_daily') ): 
    function wpestate_setup_cron_generate_pins_daily() {
            if ( ! wp_next_scheduled( 'prefix_wpestate_cron_generate_pins_daily' ) ) {
                    wp_schedule_event( time(), 'daily', 'prefix_wpestate_cron_generate_pins_daily');
            }
    }
endif;
wpestate_setup_cron_generate_pins_daily();
add_action( 'prefix_wpestate_cron_generate_pins_daily', 'wpestate_cron_generate_pins' );



if( !function_exists('wpestate_cron_generate_pins') ): 
    function wpestate_cron_generate_pins(){
        if ( wprentals_get_option('wp_estate_readsys','') =='yes' ){

            $path=wpestate_get_pin_file_path_write();
            if ( file_exists ($path) && is_writable ($path) ){
                wpestate_listing_pins();
            }

        }
    }
endif;







/////////////////////////////////////////////////////////////////////////////////////////////////
//// schedule daily event
/////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_setup_daily_schedule') ): 
    function  wpestate_setup_daily_schedule(){
        $schedule =   wprentals_get_option('wpestate_cron_saved_search');
        if ( ! wp_next_scheduled( 'wpestate_check_for_new_listings' ) && $schedule!='daily'  ) {
            //daily
            wp_clear_scheduled_hook('wpestate_check_for_new_listings_event');
            wp_schedule_event( time(), 'daily', 'wpestate_check_for_new_listings_event');
            update_option('wpestate_cron_saved_search','daily');
        }
    }
endif;







/////////////////////////////////////////////////////////////////////////////////
// convert object to array
/////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_objectToArray') ): 
    function wpestate_objectToArray ($object) {
        if(!is_object($object) && !is_array($object))
            return $object;

        return array_map('objectToArray', (array) $object);
    }
endif;





function wpestate_enable_load_exchange(){
     if ( ! wp_next_scheduled( 'wpestate_load_exchange_action' ) ) {
        //daily
        wp_schedule_event( time(), 'daily', 'wpestate_load_exchange_action');
        }
}
add_action( 'wpestate_load_exchange_action', 'estate_parse_curency' );




function estate_parse_curency(){
    $base               =   esc_html( wprentals_get_option('wp_estate_currency_symbol') );
    $custom_fields      =   wprentals_get_option('wpestate_currency');    
    
    $i=0;
    if( !empty($custom_fields)){    
        while($i< count($custom_fields) ){
            $symbol=$custom_fields[$i][0];
            $custom_fields[$i][2]=  wpestate_currencyconverterapi_load_data($base, $symbol);
            
            $i++;
        }
    }
    
    $cur_code=array();
    $cur_label=array();
    $cur_value=array();
    $cur_positin=array();
    $redux_currency=array();
    
    
    foreach($custom_fields as $field){
        $cur_code[]=$field[0];
        $cur_label[]=$field[1];
        $cur_value[]=$field[2];
        $cur_positin[]=$field[3];
    }
    
    $redux_currency['add_curr_name']=$cur_code;
    $redux_currency['add_curr_label']=$cur_label;
    $redux_currency['add_curr_value']=$cur_value;  
    $redux_currency['add_curr_order']=$cur_positin;
    
    

    Redux::setOption('wprentals_admin','wpestate_currency', $redux_currency);
}

if( !function_exists('wpestate_currencyconverterapi_load_data') ): 
    function wpestate_currencyconverterapi_load_data($base, $symbol){
    global $wp_filesystem;
    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }
    
    $apikey= trim( wprentals_get_option('wp_estate_currencyconverterapi_api',''));
    
    $link='https://free.currencyconverterapi.com/api/v5/convert?q='.$base.'_'.$symbol.'&compact=y&apiKey='.$apikey;
    $data = (array)json_decode($wp_filesystem->get_contents($link));
 
    return( $data[$base.'_'.$symbol]->val);
}
endif;


if( !function_exists('estate_get_currency_values') ): 
    function estate_get_currency_values(){
    $custom_fields =wprentals_get_option('wpestate_currency','');     
    $i=0;
    $currency_list='(';    
    $currency_symbol                =   esc_html( wprentals_get_option('wp_estate_currency_symbol') );
   
    if( !empty($custom_fields)){    
        while($i< count($custom_fields) ){
            $currency_list.= '"'.$currency_symbol.$custom_fields[$i][0].'",';
             
            $i++;
        }
    }
    $currency_list= rtrim($currency_list, ",");
    $currency_list.=")";
 
    
  
    $link='http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.xchange where pair in '.$currency_list;
    $link.='&env=store://datatables.org/alltableswithkeys';
    return $link;
}
endif;


if( !function_exists('setup_wpestate_full_invoice_payment_reminder') ): 
    function setup_wpestate_full_invoice_payment_reminder() {
        if ( ! wp_next_scheduled( 'setup_wpestate_full_invoice_payment_reminder' ) ) {
                wp_schedule_event( time(), 'daily', 'setup_wpestate_full_invoice_payment_reminder');
        }
    }
endif;

setup_wpestate_full_invoice_payment_reminder();
add_action( 'setup_wpestate_full_invoice_payment_reminder', 'wpestate_full_invoice_payment_reminder_function' );


if( !function_exists('wpestate_full_invoice_payment_reminder_function') ): 
function wpestate_full_invoice_payment_reminder_function(){
    $args = array(
           'cache_results'            =>    false,
            'update_post_meta_cache'  =>    false,
            'update_post_term_cache'  =>    false,
            'post_type'         => 'wpestate_booking',
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
            'order'             => 'DESC',
                    'meta_query' => array(
                        
                            array(
                                'key'       => 'booking_status',
                                'value'     => 'confirmed',
                                'compare'   => '='
                            ),
                            
                            array(
                                'key'       => 'booking_status_full',
                                'value'     => 'confirmed',
                                'compare'   => '!='
                            ),
                        
                        
                           array(
                                'key'       => 'total_price',
                                'type'      =>  'numeric',
                                'meta_value_num'     => '0',
                                'compare'   => '>'
                            ),
                        
                      
            )
    );
        
    $prop_selection = new WP_Query($args);     
    while ($prop_selection->have_posts()): $prop_selection->the_post();      
        $post_id                =   get_the_iD();
        $booking_from_date      =   strtotime ( get_post_meta($post_id, 'booking_from_date', true) );
        
        print '</br>'.intval($post_id).' title '.get_the_title($post_id).' / '.esc_html($booking_from_date).'';
             
        if( ( time() + (60*60*24*3+60)  ) > $booking_from_date){
            $sent_no = intval ( get_post_meta($post_id,'no_sent_reminder_email',true) );
          
            if($sent_no<3){
                wpestate_send_reminder_email_cron($post_id);
                $sent_no++;
                update_post_meta($post_id,'no_sent_reminder_email',$sent_no);
            }
        }
      
     
    endwhile;
    
    wp_reset_postdata();
    wp_reset_query();
    

}
endif;


if( !function_exists('wpestate_send_reminder_email_cron') ): 
    function wpestate_send_reminder_email_cron($bookid){
     
        $property_id        =   get_post_meta($bookid, 'booking_id', true);
        $booking_from_date  =   get_post_meta($bookid, 'booking_from_date', true);
        $invoice_id         =   get_post_meta($bookid, 'booking_invoice_no', true);
        
        $the_post       =   get_post( $bookid);
        $book_author    =   $the_post->post_author;
        $user           =   get_user_by('id',$book_author); 
        $user_email     =   $user->user_email;
         
        $arguments=array(
            'invoice_id'        =>  $invoice_id,
            'booking_id'        =>  $bookid,
            'property_url'      =>  esc_url ( get_permalink($property_id) ),
            'property_title'    =>  get_the_title($property_id),
            'until_date'        =>   wpestate_convert_dateformat_reverse($booking_from_date)
        );
           
        
        wpestate_select_email_type($user_email,'full_invoice_reminder',$arguments);    
}
endif;

?>