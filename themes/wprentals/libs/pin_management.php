<?php

   
////////////////////////////////////////////////////////////////////////////////
/// google map functions - contact pin array creation
////////////////////////////////////////////////////////////////////////////////  

if( !function_exists('wpestate_contact_pin') ):

function wpestate_contact_pin(){
        $place_markers=array();
       
        
        $company_name=esc_html( stripslashes( wprentals_get_option('wp_estate_company_name','') ) );
        if($company_name==''){
            $company_name='Company Name';
        }

        $place_markers[0]    =   $company_name;
        $place_markers[1]    =   '';
        $place_markers[2]    =   '';
        $place_markers[3]    =   1;
        $place_markers[4]    =   '';
        $place_markers[5]    =   '0';
        $place_markers[6]    =   'address';
        $place_markers[7]    =   'none';
        $place_markers[8]    =   '';
       /*  */
        return json_encode($place_markers);
}    

endif; // end   wpestate_contact_pin  



////////////////////////////////////////////////////////////////////////////////
/// google map functions - pin array creation
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_single_listing_pins') ):
function wpestate_single_listing_pins($prop_id){
    
    $counter=0;
    $wpestate_unit              =   wprentals_get_option('wp_estate_measure_sys','');
    $wpestate_currency          =   wprentals_get_option('wp_estate_currency_label_main','');
    $wpestate_where_currency    =   wprentals_get_option('wp_estate_where_currency_symbol', '');
    $cache                      =   get_option('wp_estate_cache','');
    $place_markers=$markers     =   array();

   
      
    $args = array(
                'post_type'     =>  'estate_property',
                'p'             =>  $prop_id,
            );	

    $prop_selection = new WP_Query($args);
    wp_reset_query(); 

   

    $has_slider             =   0; 

    while($prop_selection->have_posts()): $prop_selection->the_post();

        $the_id      =   get_the_ID();
        ////////////////////////////////////// gathering data for markups
        $wpestate_gmap_lat    =   floatval(get_post_meta($the_id, 'property_latitude', true));
       $wpestate_gmap_long    =   floatval(get_post_meta($the_id, 'property_longitude', true));

        //////////////////////////////////////  get property type
        $slug        =   array();
        $prop_type   =   array();
        $prop_city   =   array();
        $prop_area   =   array();
        $types       =   get_the_terms($the_id,'property_category' );
        $types_act   =   get_the_terms($the_id,'property_action_category' );
        $city_tax    =   get_the_terms($the_id,'property_city' );
        $area_tax    =   get_the_terms($the_id,'property_area' );




        $prop_type_name=array();
        if ( $types && ! is_wp_error( $types ) ) { 
             foreach ($types as $single_type) {
                $prop_type[]      = $single_type->slug;
                $prop_type_name[] = $single_type->name;
                $slug             = $single_type->slug;
               }

        $single_first_type= $prop_type[0];   
        $single_first_type_name= $prop_type_name[0]; 
        }else{
              $single_first_type='';
              $single_first_type_name='';
        }



        ////////////////////////////////////// get property action
        $prop_action        =   array();
        $prop_action_name   =   array();
        if ( $types_act && ! is_wp_error( $types_act ) ) { 
              foreach ($types_act as $single_type) {
                $prop_action[]      = $single_type->slug;
                $prop_action_name[] = $single_type->name;
                $slug=$single_type->slug;
               }
        $single_first_action        = $prop_action[0];
        $single_first_action_name   = $prop_action_name[0];
        }else{
            $single_first_action='';
            $single_first_action_name='';
        }


        /////////////////////////////////////////////////////////////////
       // add city
       if ( $city_tax && ! is_wp_error( $city_tax ) ) { 
               foreach ($city_tax as $single_city) {
                  $prop_city[] = $single_city->slug;
                 }

              $city= $prop_city[0];   
          }else{
                $city='';
          }

        ///////////////////////////////////////  //////////////////////// 
        //add area
         if ( $area_tax && ! is_wp_error( $area_tax ) ) { 
                 foreach ($area_tax as $single_area) {
                    $prop_area[] = $single_area->slug;
                   }

                $area= $prop_area[0];   
            }else{
                $area='';
            }     



            // composing name of the pin
            if($single_first_type=='' || $single_first_action ==''){
                  $pin                   =  sanitize_key(wpestate_limit54($single_first_type.$single_first_action));
            }else{
                  $pin                   =  sanitize_key(wpestate_limit27($single_first_type)).sanitize_key(wpestate_limit27($single_first_action));
            }
            $counter++;

            //// get price
            $clean_price    =   intval   ( get_post_meta($the_id, 'property_price', true) );
            $price          =   wpestate_show_price($the_id,$wpestate_currency,$wpestate_where_currency,1);
            $pin_price      =   '';
            if( wprentals_get_option('wp_estate_use_price_pins_full_price','')=='no'){
                $pin_price  =   wpestate_price_pin_converter($clean_price,$wpestate_where_currency,$wpestate_currency);

            }
                
            $rooms             =   get_post_meta($the_id, 'property_bedrooms', true);
            $wpestate_guest_no =   get_post_meta($the_id, 'guest_no', true);  
            $size              =   get_post_meta($the_id, 'property_size', true);  		
            if($size!=''){
               $size =  number_format(intval($size)) ;
            }

            $place_markers=array();

            $place_markers[]    = rawurlencode ( get_the_title() );//0
            $place_markers[]    = $wpestate_gmap_lat;//1
            $place_markers[]    = $wpestate_gmap_long;//2
            $place_markers[]    = $counter;//3
            
            $post_thumbnail_id = get_post_thumbnail_id($the_id);
            $post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id ,'wpestate_property_listings');

            //$place_markers[]    = rawurlencode ( get_the_post_thumbnail($the_id,'wpestate_property_listings') );////4
            $place_markers[]    = rawurlencode ( $post_thumbnail_url );////4
            
            $place_markers[]    = rawurlencode ( $price );//5
            $place_markers[]    = rawurlencode ( $single_first_type );//6
            $place_markers[]    = rawurlencode ( $single_first_action );//7
            $place_markers[]    = rawurlencode ( $pin );//8
            $place_markers[]    = rawurlencode ( esc_url (get_permalink() ) );//9
            $place_markers[]    = $the_id;//10
            $place_markers[]    = rawurlencode ( $city );//11
            $place_markers[]    = rawurlencode ( $area );//12
            $place_markers[]    = $clean_price;//13
            $place_markers[]    = $rooms;//14
            $place_markers[]    = $wpestate_guest_no;//15
            $place_markers[]    = $size;//16
            $place_markers[]    = rawurlencode ( $single_first_type_name );//17
            $place_markers[]    = rawurlencode ( $single_first_action_name );//18
            $place_markers[]    = rawurlencode( stripslashes ( wpestate_return_property_status($the_id,'pin') ) );//19
            $place_markers[]    = $pin_price;
            
            
            if( wprentals_get_option('wp_estate_custom_icons_infobox','')==='yes'){
                $place_markers[]  = wpestate_custom_infobox_values( wprentals_get_option( 'wp_estate_custom_infobox_fields'),$city,$area,$the_id); 
            }

            $markers[]=$place_markers;
                  

        endwhile; 
        wp_reset_query(); 

        return json_encode($markers);


}
endif;





function wpestate_custom_infobox_values($input,$city,$area,$the_id){
    
    if( $input[0][1] == 'property_city'){
        $input[0][1]  = $city;
    }else if($input[0][1] =='property_area'){
        $input[0][1] =$area;
    }else{
        $slug           =   wpestate_limit45(sanitize_title( $input[0][1] ));
        $slug           =   sanitize_key($slug);
        $input[0][1]    =   get_post_meta($the_id, $slug ,'true');
    }
   
    if( $input[1][1]  == 'property_city'){
        $input[1][1] = $city;
   }else if( $input[1][1]=='property_area'){
       $input[1][1]=$area;
    }else{
        $slug           =   wpestate_limit45(sanitize_title(  $input[1][1]));
        $slug           =   sanitize_key($slug);
        $input[1][1]    =   get_post_meta($the_id, $slug ,'true');
    }
    
    return $input;
}










if( !function_exists('wpestate_otto_write_tofile') ):
    function wpestate_otto_write_tofile($path, $markers) {
        $form_fields = array ('save'); // this is a list of the form field contents I want passed along between page views
        $method = ''; 
	$url = wp_nonce_url('themes.php?page=otto');
        $creds = request_filesystem_credentials($url, $method, false, false, $form_fields);
        // now we have some credentials, try to get the wp_filesystem running
        if ( ! WP_Filesystem($creds) ) {
                // our credentials were no good, ask the user for them again
                request_filesystem_credentials($url, $method, true, false, $form_fields);
                return true;
        }


        // by this point, the $wp_filesystem global should be working, so let's use it to create a file
        global $wp_filesystem;
        if ( ! $wp_filesystem->put_contents( $path, $markers, FS_CHMOD_FILE) ) {
                echo "error saving file!";
        }


        return true;
    }
endif;


////////////////////////////////////////////////////////////////////////////////
/// google map functions - pin array creation
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_listing_pins') ):
function wpestate_listing_pins($transient_appendix='',$with_cache=1,$args='',$jump=0,$order=1){
    global $wp_filesystem;
    wp_suspend_cache_addition(true);
    
 
  //  set_time_limit (0);
    $counter                    =   0;
    $wpestate_unit              =   wprentals_get_option('wp_estate_measure_sys','');
    $wpestate_currency          =   wprentals_get_option('wp_estate_currency_label_main','');
    $wpestate_where_currency    =   wprentals_get_option('wp_estate_where_currency_symbol', '');
    $cache                      =   wprentals_get_option('wp_estate_cache','');
    $place_markers              =   array();
    $markers                    =   array();
    $max_pins                   =   intval( wprentals_get_option('wp_estate_map_max_pins') );
    
    if  ( $args==''){
        $args = array(
            'post_type'                 =>  'estate_property',
            'post_status'               =>  'publish',
            'posts_per_page'            =>  $max_pins,
            'cache_results'             =>  false,
            'update_post_meta_cache'    =>  false,
            'update_post_term_cache'    =>  false,
            'no_found_rows'             =>  true,
            'fields'                    => 'ids'
        );	
        $transient_appendix='default_pins';
    }
        
    if( ( isset($args['meta_key']) && $args['meta_key']=='prop_featured')){
        $transient_appendix.='_prop_featured';
    }
    
    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
      $transient_appendix.='_'. ICL_LANGUAGE_CODE;
    }
    
    if ( isset($_COOKIE['my_custom_curr_symbol'] ) ){
        $transient_appendix.='_'.$_COOKIE['my_custom_curr_symbol'];
    }
    
    if($with_cache==1){
        $markers = wpestate_request_transient_cache('wpestate_markers'.$transient_appendix);
    }else{
        $markers=false;
    }
    
    
    
    
    ///////////////////set transient
    if( $markers === false ) {
   
        global $keyword;
       

        if( ( isset($args['meta_key']) && $args['meta_key']=='prop_featured')){
            add_filter( 'posts_orderby', 'wpestate_my_order' );
            if($keyword!=''){
                add_filter( 'posts_where', 'wpestate_title_filter', 10, 2 );
            }
            
                $prop_selection = new WP_Query($args);
           
            if($keyword!=''){
                if(function_exists('wpestate_disable_filtering2')){
                    wpestate_disable_filtering2( 'posts_where', 'wpestate_title_filter', 10, 2 );
                }
            }
            if(function_exists('wpestate_disable_filtering')){
                wpestate_disable_filtering( 'posts_orderby', 'wpestate_my_order' );
            }
        }else{
            if($keyword!=''){
                add_filter( 'posts_where', 'wpestate_title_filter', 10, 2 );
            }
            
                $prop_selection = new WP_Query($args);
            
                if($keyword!=''){
                if(function_exists('wpestate_disable_filtering2')){
                    wpestate_disable_filtering2( 'posts_where', 'wpestate_title_filter', 10, 2 );
                }
            }
        }

        wp_reset_query(); 
    
     
     
        $has_slider             =   0; 


        // build markers array 
        foreach ($prop_selection->posts as $key=>$value){
            $counter++;
            $markers[]=wpestate_pin_unit_creation( $value,$wpestate_currency,$wpestate_where_currency,$counter );  
        }
        if($with_cache==1){
            wpestate_set_transient_cache('wpestate_markers'.$transient_appendix,$markers,4*60*60);
        }
    }
    
    
    wp_reset_query(); 
    wp_suspend_cache_addition(false);
    
    
    
    if (wprentals_get_option('wp_estate_readsys','')=='yes' && $jump==0){
        $path= wpestate_get_pin_file_path_write();
        wpestate_otto_write_tofile($path, json_encode($markers));
    } else{   
        return json_encode($markers);
    }
}
endif; // end   wpestate_listing_pins  














if( !function_exists('wpestate_listing_pins_for_file') ):
function wpestate_listing_pins_for_file(){
    wp_suspend_cache_addition(true);
 
   // set_time_limit (0);
    $counter                    =   0;
    $wpestate_unit              =   wprentals_get_option('wp_estate_measure_sys','');
    $wpestate_currency          =   wprentals_get_option('wp_estate_currency_label_main','');
    $wpestate_where_currency    =   wprentals_get_option('wp_estate_where_currency_symbol', '');
    
    $place_markers=$markers     =   array();

    $args = array(
        'post_type'                 =>    'estate_property',
        'post_status'               =>    'publish',
        'posts_per_page'            =>    -1,
        'cache_results'             =>    false,
        'update_post_meta_cache'    =>    false,
        'update_post_term_cache'    =>    false,
       );	
 
    $prop_selection =   new WP_Query($args);
  
    while($prop_selection->have_posts()): $prop_selection->the_post();
        $counter++;
        $markers[]=wpestate_pin_unit_creation( get_the_ID(),$wpestate_currency,$wpestate_where_currency,$counter );
    endwhile;
    
    $path= wpestate_get_pin_file_path_write();
    wpestate_otto_write_tofile($path, json_encode($markers));
    
    wp_reset_query(); 
    wp_reset_postdata();
    wp_suspend_cache_addition(false);
}
endif;


if( !function_exists('wpestate_listing_pins_with_reservation') ):
function wpestate_listing_pins_with_reservation($args,$jump,$wpestate_book_from,$wpestate_book_to){
    wp_suspend_cache_addition(true);
    
    //set_time_limit (0);
    $counter                    =   0;
    $wpestate_unit              =   wprentals_get_option('wp_estate_measure_sys','');
    $wpestate_currency          =   wprentals_get_option('wp_estate_currency_label_main','');
    $wpestate_where_currency    =   wprentals_get_option('wp_estate_where_currency_symbol', '');
    $cache                      =   wprentals_get_option('wp_estate_cache','');
    $place_markers              =   array();
    $markers                    =   array();

    if($cache=='yes'){
        if(!wpestate_request_transient_cache('prop_list_cached')) { 
                
                if  ( $args==''){
                    $args = array(
                        'post_type'     =>  'estate_property',
                        'post_status'   =>  'publish',
                        'nopaging'      =>  'true',
                        'cache_results' => false,
                        'update_post_meta_cache'  =>   false,
                        'update_post_term_cache'  =>   false,
                     );
                }
               $prop_selection = new WP_Query($args);
               wpestate_set_transient_cache('prop_list_cached', $prop_selection, 60 * 60 * 3);//store data for 3h 
        }else{
              $prop_selection =wpestate_request_transient_cache('prop_list_cached');// retrive cached data
        }
        wp_reset_query(); 
    }
    else{  
        if  ( $args==''){
             $args = array(
                        'post_type'      =>  'estate_property',
                        'post_status'    =>  'publish',
                        'nopaging'       =>  'true',
                        'cache_results'  => false,
                        'update_post_meta_cache'  =>   false,
                        'update_post_term_cache'  =>   false,
                       );	
        }
        $prop_selection = new WP_Query($args);
        wp_reset_query(); 
    }//end cache
    
    
    
 
    $has_slider             =   0; 

    while($prop_selection->have_posts()): $prop_selection->the_post();
       $counter++;
        if( wpestate_check_booking_valability($wpestate_book_from,$wpestate_book_to,get_the_ID() ) ){
            $markers[]=wpestate_pin_unit_creation( get_the_ID(),$wpestate_currency,$wpestate_where_currency,$counter );
        }
    
    endwhile; 
    wp_reset_query(); 
    wp_suspend_cache_addition(false);
    if (wprentals_get_option('wp_estate_readsys','')=='yes' && $jump==0){
        $path= wpestate_get_pin_file_path_write();
        wpestate_otto_write_tofile($path, json_encode($markers));
        
    } else{   
        return json_encode($markers);
    }
}
endif; // end   wpestate_listing_pins  







if( !function_exists('wpestate_pin_unit_creation') ):
function wpestate_pin_unit_creation($the_id,$wpestate_currency,$wpestate_where_currency,$counter){
 
    ////////////////////////////////////// gathering data for markups
    $wpestate_gmap_lat    =   esc_html(get_post_meta($the_id, 'property_latitude', true));
    $wpestate_gmap_long   =   esc_html(get_post_meta($the_id, 'property_longitude', true));

    //////////////////////////////////////  get property type
    $slug        =   array();
    $prop_type   =   array();
    $prop_city   =   array();
    $prop_area   =   array();
    $types       =   get_the_terms($the_id,'property_category' );
    $types_act   =   get_the_terms($the_id,'property_action_category' );
    $city_tax    =   get_the_terms($the_id,'property_city' );
    $area_tax    =   get_the_terms($the_id,'property_area' );
        
         
    $prop_type_name=array();
    if ( $types && ! is_wp_error( $types ) ) { 
         foreach ($types as $single_type) {
            $prop_type[]      = $single_type->slug;
            $prop_type_name[] = $single_type->name;
            $slug             = $single_type->slug;
           }

    $single_first_type= $prop_type[0];   
    $single_first_type_name= ucwords( $prop_type_name[0] ); 
    }else{
          $single_first_type='';
          $single_first_type_name='';
    }



    ////////////////////////////////////// get property action
    $prop_action        =   array();
    $prop_action_name   =   array();
    if ( $types_act && ! is_wp_error( $types_act ) ) { 
          foreach ($types_act as $single_type) {
            $prop_action[]      = $single_type->slug;
            $prop_action_name[] = $single_type->name;
            $slug=$single_type->slug;
           }
    $single_first_action        = $prop_action[0];
    $single_first_action_name   = ucwords ( $prop_action_name[0] );
    }else{
        $single_first_action='';
        $single_first_action_name='';
    }


    /////////////////////////////////////////////////////////////////
    // add city
    if ( $city_tax && ! is_wp_error( $city_tax ) ) { 
        foreach ($city_tax as $single_city) {
           $prop_city[] = $single_city->slug;
        }

       $city= $prop_city[0];   
    }else{
        $city='';
    }

    ///////////////////////////////////////  //////////////////////// 
    //add area
    if ( $area_tax && ! is_wp_error( $area_tax ) ) { 
        foreach ($area_tax as $single_area) {
           $prop_area[] = $single_area->slug;
        }

        $area= $prop_area[0];   
    }else{
        $area='';
    }     



    // composing name of the pin
    if($single_first_type=='' || $single_first_action ==''){
        $pin    =  sanitize_key(wpestate_limit54($single_first_type.$single_first_action));
    }else{
        $pin    =  sanitize_key(wpestate_limit27($single_first_type)).sanitize_key(wpestate_limit27($single_first_action));
    }
 

    //// get price
    $price          =   intval   ( get_post_meta($the_id, 'property_price', true) );
    $pin_price='';
    if( wprentals_get_option('wp_estate_use_price_pins_full_price','')=='no'){
        $pin_price  =   wpestate_price_pin_converter($price,$wpestate_where_currency,$wpestate_currency);
     }
    $price_label    =   esc_html ( get_post_meta($the_id, 'property_label', true) );
    $clean_price    =   intval   ( get_post_meta($the_id, 'property_price', true) );
    $price          =   wpestate_show_price($the_id,$wpestate_currency,$wpestate_where_currency,1);
    
    
                    
    $rooms               =   get_post_meta($the_id, 'property_bedrooms', true);
    $wpestate_guest_no   =   get_post_meta($the_id, 'guest_no', true);  
    $size                =   get_post_meta($the_id, 'property_size', true);  		
    if($size!=''){
       $size =  number_format(intval($size)) ;
    }

    $place_markers=array();

    $place_markers[]    = rawurlencode ( get_the_title($the_id) );//0
    $place_markers[]    = $wpestate_gmap_lat;//1
    $place_markers[]    = $wpestate_gmap_long;//2
    $place_markers[]    = $counter;//3
    $post_thumbnail_id  = get_post_thumbnail_id($the_id);
    $post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id , 'wpestate_property_listings'); 
    $post_thumbnail_url_item='';
    if(isset($post_thumbnail_url[0])){
        $post_thumbnail_url_item=$post_thumbnail_url[0];
    }
    $place_markers[]    = rawurlencode ( $post_thumbnail_url_item );////4
    $place_markers[]    = rawurlencode ( $price );//5
    $place_markers[]    = rawurlencode ( $single_first_type );//6
    $place_markers[]    = rawurlencode ( $single_first_action );//7
    $place_markers[]    = rawurlencode ( $pin );//8
    $place_markers[]    = rawurlencode ( esc_url( get_permalink($the_id)) );//9
    $place_markers[]    = $the_id;//10
    $place_markers[]    = rawurlencode ( $city );//11
    $place_markers[]    = rawurlencode ( $area );//12
    $place_markers[]    = $clean_price;//13
    $place_markers[]    = $rooms;//14
    $place_markers[]    = $wpestate_guest_no;//15
    $place_markers[]    = $size;//16
    $place_markers[]    = rawurlencode ( $single_first_type_name );//17
    $place_markers[]    = rawurlencode ( $single_first_action_name );//18
  
    $place_markers[]    = rawurlencode( wpestate_return_property_status($the_id,'pin') );//19
    $place_markers[]    = $pin_price;//20
    if( wprentals_get_option('wp_estate_custom_icons_infobox','')==='yes'){
        $place_markers[]  =wpestate_custom_infobox_values( wprentals_get_option( 'wp_estate_custom_infobox_fields'),$city,$area,$the_id); 
    }
    
    return  $place_markers;
}
endif;













////////////////////////////////////////////////////////////////////////////////
/// google map functions - pin Images array creation
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_pin_images') ):
 
function wpestate_pin_images(){
    
    $pins = wpestate_request_transient_cache('wpestate_pin_images');
    
    if($pins===false){
        $pins       =   array();
        $taxonomy   =   'property_action_category';
        $tax_terms  =   get_terms($taxonomy);
        $taxonomy_cat   =   'property_category';
        $categories     =   get_terms($taxonomy_cat);
        if(wprentals_get_option('wp_estate_use_single_image_pin','')!='yes'){
            if(is_array($tax_terms)){
                foreach ($tax_terms as $tax_term) {
                    $name                    =  sanitize_key( wpestate_limit64('wp_estate_'.$tax_term->slug) );
                    $limit54                 =  sanitize_key( wpestate_limit54($tax_term->slug));
                    $pins[$limit54]          =  esc_html( wprentals_get_option($name,'url') ); 
                }
            }

            if(is_array($tax_terms)){
                foreach ($categories as $categ) {
                    $name                           =   sanitize_key ( wpestate_limit64('wp_estate_'.$categ->slug) );
                    $limit54                        =   sanitize_key(wpestate_limit54($categ->slug));
                    $pins[$limit54]                 =   esc_html( wprentals_get_option($name,'url') );
                }
            }

            if(is_array($tax_terms) && is_array($categories) ){
                foreach ($tax_terms as $tax_term) {
                    foreach ($categories as $categ) {           
                        $limit54                    =   sanitize_key ( wpestate_limit27($categ->slug)).sanitize_key(wpestate_limit27($tax_term->slug) );
                        $name                       =   'wp_estate_'.$limit54;
                        $pins[$limit54]              =   esc_html( wprentals_get_option($name,'url') ) ;  
                    }
                }
            }
        }
     
        $pins['single_pin'] =   esc_html( wprentals_get_option('wp_estate_single_pin','url') );  
        if( $pins['single_pin']=='' ){
            $pins['single_pin'] = get_theme_file_uri('/css/css-images').'/sale.png';
        } 
        
        $pins['cloud_pin']  =   esc_html( wprentals_get_option('wp_estate_cloud_pin','url') );  
        if( $pins['cloud_pin']=='' ){
            $pins['cloud_pin'] = get_theme_file_uri('/css/css-images').'/cloud.png';
        }
        
        $pins['userpin']    =   esc_html( wprentals_get_option('wp_estate_userpin','url') );  
        if( $pins['userpin']=='' ){
            $pins['userpin'] = get_theme_file_uri('/css/css-images').'/userpin.png';
        }
        
        
        wpestate_set_transient_cache('wpestate_pin_images',$pins,4*60*60);
    }

    return json_encode($pins);
}
endif; // end   wpestate_pin_images 






function wpestate_limit64($stringtolimit){
    return substr($stringtolimit,0,64);
}

function wpestate_limit54($stringtolimit){
    return substr($stringtolimit,0,54);
}

function wpestate_limit50($stringtolimit){ // 14
    return substr($stringtolimit,0,50);
}

function wpestate_limit45($stringtolimit){ // 19
    return substr($stringtolimit,0,65);
}                                   

function wpestate_limit27($stringtolimit){ // 27
    return substr($stringtolimit,0,27);
}    
      
?>