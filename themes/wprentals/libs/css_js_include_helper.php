<?php
if(!function_exists('wpestate_load_google_map')):
    function wpestate_load_google_map(){
        $what_map = intval( wprentals_get_option('wp_estate_kind_of_map') );
        if($what_map==1){
            if (!wp_script_is( 'wpestate_googlemap', 'enqueued' )) {
                $libraries='';

                if( intval( wprentals_get_option('wp_estate_kind_of_places') ) == 1 ){
                    $libraries ='&libraries=places&amp;language=en';
                }
                $google_maps_link_ssl   =   'https://maps-api-ssl.google.com/maps/api/js?v=quarterly'.$libraries.'&key='.esc_html(wprentals_get_option('wp_estate_api_key', '') );
                $google_maps_link       =   'http://maps.googleapis.com/maps/api/js?v=quarterly'.$libraries.'&key='.esc_html(wprentals_get_option('wp_estate_api_key', '') );


                if ( is_ssl() ) {
                    wp_enqueue_script('wpestate_googlemap', $google_maps_link_ssl ,array('jquery'), '1.0', false);        
                }else{
                    wp_enqueue_script('wpestate_googlemap', $google_maps_link ,array('jquery'), '1.0', false);        
                }
                wp_enqueue_script('wpestate_infobox',  trailingslashit( get_template_directory_uri() ) .'js/infobox.js',array('jquery'), '1.0', true); 

                wp_enqueue_script('wpestate_markerclusterer', trailingslashit( get_template_directory_uri() ).'js/google_js/markerclusterer.js',array('jquery'), '1.0', true);  
                wp_enqueue_script('wprentals_pin',trailingslashit( get_template_directory_uri() ).'js/google_js/rentals_pin.js',array('jquery'), '1.0', true);  
                wp_enqueue_script('wpestate_oms.min',trailingslashit( get_template_directory_uri() ).'js/google_js/oms.min.js',array('jquery'), '1.0', true);   

            }
        }
    }
endif;



if( !function_exists('wpestate_check_google_maps_avalability') ):
    function wpestate_check_google_maps_avalability($wpestate_header_type,$wpestate_global_header_type,$postid=''){
        global $post;
        $page_template='';
        if(isset($post->ID)){
            $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        }

        
        $wpestate_header_type        =   intval($wpestate_header_type);
        $wpestate_global_header_type =   intval($wpestate_global_header_type);
        $to_return                   =   false; // no g maps
        $use_google_on_search        =   wprentals_get_option('wp_estate_wpestate_autocomplete','');
        $taxonomy_list_type          =  wprentals_get_option('wp_estate_property_list_type','');
     
        $search_type    =   wprentals_get_option('wp_estate_adv_search_type','');
        if ($search_type=='type5'){
            return true;
        }
        
        if(  wpestate_check_if_admin_page($postid)  ){

            if(  (  $page_template=='user_dashboard_edit_listing.php' || 
                    $page_template=='user_dashboard_add_step1.php'  ) ){
                $to_return=true; 
            }else{
                $to_return=false; 
            }
            
        }else if( $page_template=='splash_page.php' ){
            if(        wprentals_use_google_places()  ){
                $to_return          =   true;
            }else{
                $to_return          =   false;
            }
        }else  if( is_tax()  ){
       
          
            $tax_header= wprentals_get_option('wp_estate_header_type_taxonomy','');
           
            if($tax_header==4 || $taxonomy_list_type==2 ){
                $to_return=true; 
            }else{
                $to_return=false; 
            }
            
        }else if(  is_singular('estate_property') ){
            $to_return=true; 
            
        }else if( is_singular('estate_agent') ){
            if(intval(wprentals_get_option('wp_estate_use_upload_tax_page',''))==4 ){
                $to_return=true; 
            }else{
                $to_return=false; 
            }
        }else if( (is_category() || is_archive() )&& $wpestate_global_header_type==4 ){
            $to_return=true; 
        }else if( 
            $wpestate_header_type==5 ||                                      // if local header type 
            ( $wpestate_header_type==0 && $wpestate_global_header_type==4 )  ||        //  if  local is set to global and global is google
            $page_template=='user_dashboard_edit_listing.php' ||           //  if add property page
            $page_template=='property_list_half.php' ||           //  for half map 
          
            wprentals_use_google_places()        
        ){  
            
            $to_return=true; // we have g maps
        }

        return $to_return;


    }
endif;


if( !function_exists('wprentals_use_google_places') ):
    function wprentals_use_google_places(){
        global $post;
        $adv_search_type        =   wprentals_get_option('wp_estate_adv_search_type','');
        $use_google_on_search   =   wprentals_get_option('wp_estate_wpestate_autocomplete','');
        
        
        if ( ($adv_search_type=='newtype' || $adv_search_type=='oldtype') && $use_google_on_search=='yes'){
            return true;
        }else if ( ($adv_search_type=='type3' || $adv_search_type=='type4') && $use_google_on_search=='yes'){
            $adv_search_what                    =   wprentals_get_option('wp_estate_adv_search_what','');
            if( in_array('Location', $adv_search_what)  ){
                return true;
            }
        }
        return false;
    }
endif;