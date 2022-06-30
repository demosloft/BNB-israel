<?php



if(!function_exists('wpestate_add_feature_to_search')):
function wpestate_add_feature_to_search($input,$is_half=''){
    global $table_prefix;
    global $wpdb;
    $features=array();
    $searched           =   0;

    if($is_half===1 && isset( $input['all_checkers'])){
        // is half map ajax
        $all_checkers   =   explode(",", $input['all_checkers'] ); 
        foreach($all_checkers as $checker => $value){
             if($value!=''){
                $input_name=    $post_var_name  =   str_replace(' ','_', trim( esc_html($value)) );
                $features[]=$input_name;
            }
        }
      
    }else{
       
        $terms = get_terms( array(
            'taxonomy' => 'property_features',
            'hide_empty' => false,
        ) );
 
      
        
        foreach($terms as $key => $term){
            $input_name=$term->slug;
            if ( isset( $input[$input_name] ) && $input[$input_name]==1 ){
               $features[]=$input_name;
            }
        }
        

    } 
    
    
    if( !empty($features)){
        $features_array=array();
        $features_array['relation']='AND';
        
        foreach ($features as $term):
            $features_array[]=array(
                'taxonomy' => 'property_features',
                'field'    => 'slug',
                'terms'    => $term,
            );
        endforeach;
       
        return $features_array;
    }
}
endif;











if(!function_exists('get_ids_by_query')):
function wpestate_get_ids_by_query($query){
    global $wpdb;
    $data=$wpdb->get_results( $query,'ARRAY_A');
    $results=[];
  
    foreach($data as $entry){
        $results[]=$entry['post_id'];
    }

    return $results;
}
endif;









if( !function_exists('wpestate_title_filter') ):
function wpestate_title_filter( $where, $wp_query ){
    global $wpdb;
    global $keyword;
  //  $keyword= str_replace("'", " ", $keyword);
    $search_term = $wpdb->esc_like($keyword);
    $search_term = ' \'%' . $search_term . '%\'';
    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE '.$search_term;
 
    
    return $where;
}

endif;



function wpestate_search_type_inject($categ_select_list,$action_select_list,$where=''){
    $allowed_html=array();
    $col_class='col-md-2';
    if($where=="half"){
        $col_class='col-md-3';
    }
    
    ob_start();
    print'<div class="col-md-6">
                <i class="custom_icon_class_icon fas fa-keyboard"></i>

                <input type="text" id="keyword_search" class="form-control custom_icon_class_input" name="keyword_search"  placeholder="'. esc_html__('Type Keyword','wprentals').'" value="'; 

                if(isset($_GET['keyword_search'])){
                    print  esc_attr(stripslashes( wp_kses($_GET['keyword_search'], $allowed_html) ) );
                }
                print '"></div>';
       
       
                if( isset($_GET['property_category']) && $_GET['property_category']!=''&& $_GET['property_category']!='all'  ){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['property_category'],$allowed_html) ),'property_category');
                    $adv_categ_value= $adv_categ_value1=$full_name->name;
                    $adv_categ_value1 = mb_strtolower ( str_replace(' ', '-', $adv_categ_value1));
                }else{
                    $adv_categ_value    =  wpestate_category_labels_dropdowns('main');
                    $adv_categ_value1   ='all';
                }
        
                print '
                <div class="'.$col_class.'">
                   <i class="custom_icon_class_icon fas fa-clone"></i>
                    <div class="dropdown form-control custom_icon_class icon_categlist " >
                        <div data-toggle="dropdown" id="adv_categ" class="filter_menu_trigger     "  data-value="'.esc_attr( strtolower ( rawurlencode( $adv_categ_value1))).'"> 
                            '.$adv_categ_value.'               
                        <span class="caret caret_filter"></span> </div>           
                        <input type="hidden" id="property_category" name="property_category" value="';
                        if(isset($_GET['property_category'])){
                            echo strtolower ( esc_attr( $_GET['property_category'] ) );
                        }
                       echo'">
                        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                            '.$categ_select_list.'
                        </ul>
                    </div>    
                </div>';
       
                if(isset($_GET['property_action_category']) && $_GET['property_action_category']!='' && $_GET['property_action_category']!='all'){
                    $full_name = get_term_by('slug', esc_html( wp_kses( $_GET['property_action_category'],$allowed_html) ),'property_action_category');
                    $adv_actions_value=$adv_actions_value1= $full_name->name;
                    $adv_actions_value1 = mb_strtolower ( str_replace(' ', '-', $adv_actions_value1) );
                }else{
                    $adv_actions_value= wpestate_category_labels_dropdowns('second');
                    $adv_actions_value1='all';
                }

                print'
                <div class="'.$col_class.'">  
                    <i class="custom_icon_class_icon fas fa-boxes"></i>
                    <div class="dropdown form-control dropdown custom_icon_class icon_actionslist form-control " >
                        <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger  " data-value="'.esc_attr(strtolower ( rawurlencode ( $adv_actions_value1) )).'"> 
                            '.$adv_actions_value.' 
                        <span class="caret caret_filter"></span> </div>           
                        <input type="hidden" id="property_action_category" name="property_action_category" value="'; 
                        if(isset($_GET['property_action_category'])){
                             echo  strtolower( esc_attr($_GET['property_action_category']) );

                        }; 
                        echo '">
                        <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                            '.$action_select_list.'
                        </ul>        
                    </div>
                </div>';
                        
        $retur= ob_get_contents();
        ob_end_clean();
        return $retur;
     
}


function wpestate_add_geo_map_coordinates($input){
  
    $long_array                 =  array();
    $lat_array                  =  array();
    $return_array               =  array();
    $return_array['long']       =   '';
    $return_array['lat']        =   '';
    $return_array['has_corners']=   0;
    
    if( !isset($input['ne_lat']) || !isset( $input['ne_lng'] )|| !isset( $input['sw_lat']) || !isset( $input['sw_lng'] ) ){
        return $return_array;
    }
    
    $ne_lat                     =  floatval($input['ne_lat']);
    $ne_lng                     =  floatval($input['ne_lng']);
    $sw_lat                     =  floatval($input['sw_lat']);
    $sw_lng                     =  floatval($input['sw_lng']);
    
    
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
    $long_array['type']      = 'DECIMAL';
    $long_array['compare']   = 'BETWEEN';
    $return_array['long']    =  $long_array;

       
    $lat_array['key']       = 'property_latitude';
    $lat_array['value']     =  array( $min_lat,$max_lat);
    $lat_array['type']      = 'DECIMAL';
    $lat_array['compare']   = 'BETWEEN';
    $return_array['lat']    =  $lat_array;
    
    $return_array['has_corners']=1;
    return $return_array;
        
}


if(!function_exists('wpestate_geo_search_filter_function')):
function wpestate_geo_search_filter_function($args,$center_lat,$center_long,$radius){
    global $wpdb;
    $radius_measure = wprentals_get_option('wp_estate_geo_radius_measure','');
    $earth         = 3959;
    if( $radius_measure == 'km' ) {
       $earth = 6371;
    }
  

    $wpdb_query = $wpdb->prepare( "SELECT $wpdb->posts.ID,
            ( %s * acos(
                    cos( radians(%s) ) *
                    cos( radians( latitude.meta_value ) ) *
                    cos( radians( longitude.meta_value ) - radians(%s) ) +
                    sin( radians(%s) ) *
                    sin( radians( latitude.meta_value ) )
            ) )
            AS distance, latitude.meta_value AS latitude, longitude.meta_value AS longitude
            FROM $wpdb->posts
            INNER JOIN $wpdb->postmeta
                    AS latitude
                    ON $wpdb->posts.ID = latitude.post_id
            INNER JOIN $wpdb->postmeta
                    AS longitude
                    ON $wpdb->posts.ID = longitude.post_id
            WHERE 1=1

                    AND latitude.meta_key='property_latitude'
                    AND longitude.meta_key='property_longitude'
            HAVING distance < %s
            ORDER BY $wpdb->posts.menu_order ASC, distance ASC",
            $earth,
            $center_lat,
            $center_long,
            $center_lat,
            $radius
        );
        $listing_ids = $wpdb->get_results( $wpdb_query, OBJECT_K );
      
   
        if ( $listing_ids=='') {
            $listing_ids = array();
        }
        // return post ids for main wp_query
        
        $new_ids        =   array_keys(  $listing_ids );
        
        
        if(isset($args[ 'post__in' ])){
            $original_ids   =   $args[ 'post__in' ];
        }else{
            $original_ids   =   array();
        }

        if ( !empty($new_ids) ){
         
            if( empty(  $args[ 'post__in' ] ) ){ 
                $args[ 'post__in' ] = $new_ids;
            }else if( isset ($args[ 'post__in'][0]) && $args[ 'post__in' ][0]==0 ){// no items on coustom
                $args[ 'post__in' ]=array(0); 
            }else{
           
                $intersect   =   array_intersect ( $new_ids , $original_ids );
                if( empty($intersect) ){ 
                    $intersect=array(0);
                }
                    
                $args[ 'post__in' ] =$intersect;
         
 
            }
        }else{
            $args[ 'post__in' ]=array(0);
        }
        return $args;
    
}
endif;





if(!function_exists('wprentals_location_custom_dropwdown')):
    function wprentals_location_custom_dropwdown($search_location,$label){
        $return_string = '
        <div class="dropdown form-control">
            <div data-toggle="dropdown" id="search_location"  class="filter_menu_trigger "  data-value="'; 
                if(isset($search_location['search_location'])){
                    $return_string.= esc_attr($search_location['search_location']);
                }else{
                    $return_string.= 'all';
                }
            $return_string.='">';
                
            if(isset($_GET['search_location']) && $_GET['search_location']!=''&& $_GET['search_location']!='0' ){
                $return_string.= esc_attr($search_location['search_location']);
            }else{
                $return_string.= $label;
            }
                    
            $return_string.= '<span class="caret caret_filter"></span> </div>           
            <input type="hidden" name="search_location" id="search_location_autointernal"  value="'; 
                if(isset($search_location['search_location'])){
                    $return_string.= esc_attr($search_location['search_location']);
                }   
                $wpestate_internal_search='';
            $return_string.='">
            <ul  class="dropdown-menu filter_menu search_location_autointernal_list"  id="search_location-select" role="menu" aria-labelledby="search_location'.$wpestate_internal_search.'">
                '. wprentals_places_search_select().'
            </ul>        
        </div>';
                
        return $return_string;
    }
endif;













if(!function_exists('wprentals_places_search_select')):
    function wprentals_places_search_select($with_any='',$selected=''){

        $availableTags_array    =   get_option('wpestate_autocomplete_data_select',true);
      
        
        sort($availableTags_array);
        $select_area_list       =   '';
        if($with_any==''){
            $select_area_list.='<li role="presentation" data-value="0"';
            if($selected=='0' || $selected==0){
                $select_area_list .=' selected="selected" ';
            }
            $select_area_list.='>'.esc_html__( 'any','wprentals').$selected.'</li>';
        }

        if(is_array($availableTags_array)){
            foreach($availableTags_array as $key=>$item){
                
                if( $item['label']!='' && $item['label']!='0' ){
                    $select_area_list .=   '<li role="presentation" data-tax="'.esc_attr($item['category']).'" data-value="'.esc_attr($item['label']).'"';
                    
                    if($selected!='' && $selected==$item['label']){
                        $select_area_list .=' selected="selected" ';
                    }
                    $select_area_list .= '>'. $item['label'].'</li>';
                }
            }
        }

        return $select_area_list;
    }
endif;