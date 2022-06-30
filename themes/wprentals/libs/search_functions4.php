<?php

if( !function_exists('wpestate_show_search_field_new') ):
         
    function  wpestate_show_search_field_new($input,$position,$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key){
        $adv_search_what        =   wprentals_get_option('wp_estate_adv_search_what');
        $adv_search_label       =   wprentals_get_option('wp_estate_adv_search_label'); 
        $adv_search_how         =   wprentals_get_option('wp_estate_adv_search_how');
        $adv_search_icon        =   wprentals_get_option('wp_estate_search_field_label');
        $list_args              =   wpestate_get_select_arguments();
        $allowed_html           =   array();
        
        if($position=='mainform'){
            $appendix='';
        }else if($position=='sidebar') {
            $appendix='sidebar-';
        }else if($position=='shortcode') {
            $appendix='shortcode-';  
        }else if($position=='mobile') {
            $appendix='mobile-';
        }else if($position=='half') {
            $appendix='half-';
        }
     
        $return_string      =   '';
        $icons_css          =   '';
        $term_value         =   '';
        $search_field       =   sanitize_key($search_field);
        if( isset( $input[$search_field] ) ){
            $term_value = sanitize_text_field ( rawurldecode($input[$search_field]) );
        }
        
        $label                  =   $adv_search_label[$key];
        if (function_exists('icl_translate') ){
            $label     =   icl_translate('wprentals','wp_estate_custom_search_'.$label, $label ) ;
        }
        $label   =  wp_kses($label,$allowed_html);
        
        $return_string  .=  '<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
         
         
        if($search_field=='none'){
            $return_string=''; 
        }else if(   strtolower($search_field)=='location'   ){
            
           $return_string  .=  wpestate_search_location_field($label,$position);
            
        }else  if( rentals_is_tax_case($search_field) ){
            
          
            $dropdown_list      =   wpestate_get_action_select_list_4all($list_args,$search_field);
            $return_string      .=  wpestate_build_dropdown_adv_new($appendix,$search_field,$term_value,$dropdown_list,$label);

            
            
        }else {
            
            $return_string          =   '<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';          
            $show_dropdowns         =   wprentals_get_option('wp_estate_show_dropdowns','');              
            $label                  =   $adv_search_label[$key];
            if (function_exists('icl_translate') ){
                $label     =   icl_translate('wprentals','wp_estate_custom_search_'.$label, $label ) ;
            }
            
            
            if ($search_field=='property_country'){                    
                $return_string .=  wpestate_country_list_adv_search($appendix,$term_value,$label);
            }else if ( $search_field=='property_price'){
                $return_string = wpestate_price_form_adv_search($position,$search_field,$label);
            }else if ( $show_dropdowns=='yes' && ( $search_field=='property_rooms' ||  $search_field=='property_bedrooms' ||  $search_field=='property_bathrooms' ||  $search_field=='guest_no') ){
                $i=0;
                $rooms_select_list =   ' <li role="presentation" data-value="all">'.esc_html($label).'</li>';
                $max=10;
                if($search_field=='guest_no'){
                   $max =   intval   ( wprentals_get_option('wp_estate_guest_dropdown_no','') );
                }
                while($i < $max ){
                    $i++;
                    $rooms_select_list.='<li data-value="'.esc_attr($i).'"  value="'.esc_attr($i).'">'.esc_html($i).'</li>';
                }
                $return_string.= wpestate_build_dropdown_adv_new($appendix,$search_field,$term_value,$rooms_select_list,$label);
            }else{ 
                $custom_fields  =   wprentals_get_option('wpestate_custom_fields_list','');
                $i              =   0;
                $found_dropdown =   0;
                ///////////////////////////////// dropdown check
                if( !empty($custom_fields)){  
                    while($i< count($custom_fields) ){          
                        $name       =   $custom_fields[$i][0];
                        if( sanitize_key($name) == $search_field && $custom_fields[$i][2]=='dropdown' ){
                            $found_dropdown =   1;
                            $front_name     =   esc_html($adv_search_label[$key]);
                            if (function_exists('icl_translate') ){
                                $initial_key            =   apply_filters('wpml_translate_single_string', trim($front_name),'custom field value','custom_field_value_cc'.$front_name );
                                $action_select_list     =   '<li role="presentation" data-value="all"> '. stripslashes($initial_key) .'</li>';  
                            }else{
                                $action_select_list =   ' <li role="presentation" data-value="all">'. stripslashes( $front_name).'</li>';
                            }

                            $dropdown_values_array=explode(',',$custom_fields[$i][4]);

                            foreach($dropdown_values_array as $drop_key=>$value_drop){
                                $original_value_drop    =   $value_drop;
                                if (function_exists('icl_translate') ){
                                    $value_drop = apply_filters('wpml_translate_single_string', trim($value_drop),'custom field value','custom_field_value'.$value_drop );
                                }
                                $action_select_list .=   ' <li role="presentation" data-value="'.esc_attr($original_value_drop).'">'. stripslashes(trim($value_drop)).'</li>';
                            }
                            
                      
                          
                            $return_string      .=  wpestate_build_dropdown_adv_new($appendix,$search_field,$term_value,$action_select_list,$label);

                        }
                        $i++;
                    }
                }  
                ///////////////////// end dropdown check
                    
                    if($found_dropdown==0){
                        //////////////// regular field 
                        $field_id=sanitize_key($search_field);
                        if($adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller' || $search_field=='check_in' || $search_field=='check_out'){
                            if($position=='sidebar'){
                                $field_id=$search_field.'_widget';
                            }else if($position=='shortcode'){
                                $field_id=$search_field.'_shortcode';
                            }else if($position=='mobile'){
                                $field_id=$search_field.'_mobile';
                            }
                        }
                        
                        $return_string='';
                        $return_string.='<i class="custom_icon_class_icon '.$adv_search_icon[$key].'"></i>';
                        $return_string.='<input type="text"    id="'.$field_id.'"  name="'.sanitize_key($search_field).'"'
                                . ' placeholder="'. stripslashes(wp_kses($label,$allowed_html)).'" ';
                        if($search_field=='check_out'){
                            //$return_string.= ' disabled ';
                        }
                        $return_string.= ' class="advanced_select form-control custom_icon_class_input" value="';
                        if (isset($_GET[sanitize_key($search_field)])) {
                            $return_string.=  esc_attr( $_GET[sanitize_key($search_field)] );
                        }
                        
                        
                        
                        $return_string.='" />';
                        
                        ////////////////// apply datepicker if is the case
                        if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller' || $search_field=='check_in' || $search_field=='check_out'){
                            wpestate_date_picker_translation(sanitize_key($field_id));
                        }
                    }
                }
            } 
        
            
        return $return_string;      
    }
   
endif; 




if(!function_exists('wpestate_add_meta_post_to_search')):
function wpestate_add_meta_post_to_search($meta_array){
    global $table_prefix;
    global $wpdb;
    global $query_meta;
    $meta_array_return=$meta_array;

    foreach($meta_array as $key=> $value){
     
        if( isset($value['compare'])){
            $query_meta=1;
            switch ($value['compare']) {
                case '=':
                        $potential_ids[$key]=
                            wpestate_get_ids_by_query(
                                $wpdb->prepare("
                                    SELECT post_id
                                    FROM ".$table_prefix."postmeta
                                    WHERE meta_key = %s
                                    AND CAST(meta_value AS UNSIGNED) = %f
                                ",array($value['key'],$value['value']) )
                        );

                    break;
                case '>=':
                    if($value['type']=='DATE'){
                        $potential_ids[$key]=
                            wpestate_get_ids_by_query(
                                $wpdb->prepare("
                                    SELECT post_id
                                    FROM ".$table_prefix."postmeta
                                    WHERE meta_key = %s
                                    AND CAST(meta_value AS DATE) >= %s
                                ",array($value['key'],wpestate_convert_dateformat( $value['value'] )) )
                        );
                        
                    }else{
                        $potential_ids[$key]=
                            wpestate_get_ids_by_query(
                                $wpdb->prepare("
                                    SELECT post_id
                                    FROM ".$table_prefix."postmeta
                                    WHERE meta_key = %s
                                    AND CAST(meta_value AS UNSIGNED) >= %f
                                ",array($value['key'],$value['value']) )
                            );
                    }
                    break;
                case '<=':
                    if($value['type']=='DATE'){
                        $potential_ids[$key]=
                            wpestate_get_ids_by_query(
                                $wpdb->prepare("
                                    SELECT post_id
                                    FROM ".$table_prefix."postmeta
                                    WHERE meta_key = %s
                                    AND CAST(meta_value AS DATE) <= %s
                                ",array($value['key'],wpestate_convert_dateformat($value['value'])) )
                            );
                        
                    }else{ 
                        $potential_ids[$key]=
                            wpestate_get_ids_by_query(
                                $wpdb->prepare("
                                    SELECT post_id
                                    FROM ".$table_prefix."postmeta
                                    WHERE meta_key = %s
                                    AND CAST(meta_value AS UNSIGNED) <= %f
                                ",array($value['key'],$value['value']) )
                            );
                        
                    }

                    break;
                case 'LIKE':
                    
                    $wild = '%';
                    $find = $value['value'];
                    $like = $wild . $wpdb->esc_like( $find ) . $wild;
                    $potential_ids[$key]=wpestate_get_ids_by_query(
                    $wpdb->prepare("
                        SELECT post_id
                        FROM ".$table_prefix."postmeta
                        WHERE meta_key =%s AND meta_value LIKE %s
                    ",array($value['key'],$like) ) );
                     
                    break;
                
                
                
                case 'BETWEEN':
                    if(isset($value['type']) && $value['type']=='DECIMAL' ){
                        $potential_ids[$key]=
                            wpestate_get_ids_by_query(
                                $wpdb->prepare("
                                    SELECT post_id
                                    FROM ".$table_prefix."postmeta
                                    WHERE meta_key = '%s'
                                    AND CAST(meta_value AS DECIMAL)  BETWEEN '%f' AND '%f'
                                ",array($value['key'],$value['value'][0],$value['value'][1]) )
                            );
                        
                    } if(isset($value['type']) && $value['type']=='DECIMAL3' ){
                        $potential_ids[$key]=
                            wpestate_get_ids_by_query(
                                $wpdb->prepare("
                                    SELECT post_id
                                    FROM ".$table_prefix."postmeta
                                    WHERE meta_key = '%s'
                                    AND CAST(meta_value AS DECIMAL(10,3))  BETWEEN '%f' AND '%f'
                                ",array($value['key'],$value['value'][0],$value['value'][1]) )
                            );
                        
                    }else{
                        $potential_ids[$key]=
                            wpestate_get_ids_by_query(
                                $wpdb->prepare("
                                    SELECT post_id
                                    FROM ".$table_prefix."postmeta
                                    WHERE meta_key = '%s'
                                    AND CAST(meta_value AS SIGNED)  BETWEEN '%f' AND '%f'
                                ",array($value['key'],$value['value'][0],$value['value'][1]) )
                        );
                    }
                   
                    break;
            }
            $potential_ids[$key]=  array_unique($potential_ids[$key]);
            unset($meta_array_return[$key]);
        }
        
      
     
    }
    
  
    
    $ids=[];
    if(!empty($potential_ids)){
         
        foreach(reset($potential_ids) as $elements){
            $ids[]=$elements;
        }
   
        foreach($potential_ids as $key=>$temp_ids){
            $ids = array_intersect($ids,$temp_ids);
        }
    }
    
    $ids=  array_unique($ids);
    
    if(empty($ids)){
        $ids[]=0;
    }
 
    $return_array       =   array();
    $return_array[0]    =   $ids;
    $return_array[1]    =   $meta_array_return;

    return $return_array;
    
    
}
endif;

add_action( 'wp_ajax_nopriv_wpestate_custom_ondemand_pin_load_new', 'wpestate_custom_ondemand_pin_load_new' );  
add_action( 'wp_ajax_wpestate_custom_ondemand_pin_load_new', 'wpestate_custom_ondemand_pin_load_new' );

if( !function_exists('wpestate_custom_ondemand_pin_load_new') ):
    
    function wpestate_custom_ondemand_pin_load_new(){
       // check_ajax_referer( 'wprentals_ajax_filtering_nonce', 'security' );
    
        wp_suspend_cache_addition(false);
        global $keyword;
        global $wpestate_currency;
        global $wpestate_where_currency;
        global $wpestate_listing_type;
        $wpestate_currency      =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
        $wpestate_where_currency=   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
        $counter                =   0;
        $paged                  =   intval($_POST['newpage']);
        $wpestate_listing_type  =   wprentals_get_option('wp_estate_listing_unit_type','');
     
        ob_start(); 
        
        foreach ($_REQUEST['all_fields'] as $fields){
            if(isset($fields['value']) && $fields['value']!=''){
                $_REQUEST[$fields['element']]=$fields['value'];
            }
        }
        unset($_REQUEST['all_fields']);
        
        $compute            =   wpestate_argumets_builder($_REQUEST,1);   
        
    
        
        
        $prop_selection     =   $compute[0];
        $args               =   $compute[1];
        $compare_submit     =   wpestate_get_template_link('compare_listings.php');
        $markers            =   array();
        $return_string      =   '';
        

            print '<span id="scrollhere"></span>';
            $listing_unit_style_half    =   wprentals_get_option('wp_estate_listing_unit_style_half','');

    
     
            if( $prop_selection->have_posts() ){
           
                while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                    if($listing_unit_style_half == 1){
                        include(locate_template('templates/property_unit_wide.php') );
                    }else{
                        include(locate_template('templates/property_unit.php') );
                    }
              
                    $markers[]=wpestate_pin_unit_creation( get_the_ID(),$wpestate_currency,$wpestate_where_currency,$counter );
                endwhile;
                wprentals_pagination_ajax($prop_selection->max_num_pages, $range =2,$paged,'pagination_ajax_search_home'); 
            }else{
                print '<span class="no_results">'. esc_html__( "We didn't find any results","wprentals").'</>';
            }
      
            $templates = ob_get_contents();
        ob_end_clean(); 
        
        $return_string .=   '<div class="half_map_results">'.$prop_selection->found_posts.' '.esc_html__( ' Results found!','wprentals').'</div>';
        $return_string .=   $templates;
        
        echo json_encode(array('added'=>true,'arguments'=>json_encode($args), 'markers'=>json_encode($markers),'response'=>$return_string ));
        die();

       wp_suspend_cache_addition(false);     
       die();
  }
  
 endif; // end   ajax_filter_listings 
 
 
 
if(!function_exists('wpestate_map_pan_filtering')):
    function wpestate_map_pan_filtering($input,$meta_array){
        $ne_lat          =  esc_html($input['pan_ne_lat']);
        $ne_lng          =  esc_html($input['pan_ne_long']);
        $sw_lat          =  esc_html($input['pan_sw_lat']);
        $sw_lng          =  esc_html($input['pan_sv_long']);
        $long_array      =  array();
        $lat_array       =  array();
      
        $min_lat    =  $sw_lat;
        $max_lat    =  $ne_lat;
        
        if($min_lat>$max_lat){
            $min_lat    =  $ne_lat;
            $max_lat    =  $sw_lat ;
        }
        
       
        $min_lng    =   $sw_lng;
        $max_lng    =   $ne_lng;
                
        if($min_lng>$max_lng){
            $min_lng = $ne_lng;
            $max_lng = $sw_lng;
        } 
        
        $long_array['key']       = 'property_longitude';
        $long_array['value']     =  array( $min_lng,$max_lng);
        $long_array['type']      = 'DECIMAL3';
        $long_array['compare']   = 'BETWEEN';
     
       
        $lat_array['key']       = 'property_latitude';
        $lat_array['value']     =  array( $min_lat,$max_lat);
        $lat_array['type']      = 'DECIMAL3';
        $lat_array['compare']   = 'BETWEEN';
        
        if ( isset( $args['meta_query'][0]['relation']) && $args['meta_query'][0]['relation']==='OR'){
            
        }else{
            $meta_array[]=$long_array;
            $meta_array[]=$lat_array;
        }
        
        return $meta_array;
    }
endif;