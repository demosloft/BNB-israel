<?php

function wprentals_convert_features_status_to_tax(){
   
    if( get_option('wprentals_convert_features') == 'activate2'){
       return; 
    }
    $wprentals_admin=get_option('wprentals_admin');

    $feature_list_array =   array();
    if( isset($wprentals_admin['wp_estate_feature_list']) ){
        $feature_list       =   $wprentals_admin['wp_estate_feature_list'];
        $feature_list_array =   explode( ',',$feature_list);
    }
    
    
    foreach($feature_list_array as $key => $value){
       wp_insert_term( $value, 'property_features');
    }
    
    
    
    // loop trogh all listings
    $args = array(
        'post_type'        =>  'estate_property',
        'posts_per_page'   =>  -1,
        'post_status'      =>  array( 'any' )
    );

    $all_listings= new WP_Query($args);
    
    if( $all_listings->have_posts() ):
        while ($all_listings->have_posts()):$all_listings->the_post();
            $the_id =   get_the_Id();
            
            
            // loop trough filter and ammenites
            foreach($feature_list_array as $key => $value){
              
                $data               =   wprentals_prepare_non_latin_convert($value,$value);
                $input_name         =   $data['key'];
                $post_var_name      =   str_replace(' ','_', trim($value) );
        
                if (esc_html(get_post_meta($the_id, $input_name, true)) == 1) {
                    wp_set_object_terms($the_id,$value,'property_features',true); 
                }
                
                
            }
            
            // property status
            $prop_status  =  stripslashes( get_post_meta($the_id, 'property_status', true) );
            if($prop_status!='' && $prop_status!='normal'){
                wp_set_object_terms($the_id,$prop_status,'property_status',true); 
            }
                
        endwhile;
        wp_reset_postdata();
        update_option('wprentals_convert_features','activate2');
        
    endif;
    
}


function wprentals_prepare_non_latin_convert($key,$label){

    $label  =  stripslashes( $label);
    
    $slug   =   stripslashes($key);
    $slug   =   str_replace(' ','-',$key);
    $slug   =   htmlspecialchars ( $slug ,ENT_QUOTES);
    $slug   =   wpestate_limit45convert(sanitize_title( $slug ));
    $slug   =   sanitize_key($slug);
            
            
    $return=array();
    $return['key']=trim($slug);
    $return['label']=trim($label);
    return $return;
}


function wpestate_limit45convert($stringtolimit){ // 19
    return substr($stringtolimit,0,65);
} 
