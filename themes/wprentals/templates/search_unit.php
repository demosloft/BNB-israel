<?php
exit();
global $custom_advanced_search;  
global $adv_search_what;
global $adv_search_label;
?>
<div class="search_unit_wrapper">
    <h4> <?php the_title(); ?> </h4>
    <a class="delete_search" data-searchid="<?php print intval($post->ID); ?>"><?php esc_html_e('delete search','wprentals');?></a>
    <?php  
    $search_arguments=  get_post_meta($post->ID, 'search_arguments', true) ;
    $search_arguments_decoded= json_decode($search_arguments);

    print '<div class="search_param"><strong>'.esc_html__( 'Search Parameters: ','wprentals').'</strong>';
    foreach($search_arguments_decoded->tax_query as $key=>$query ){
        
        if ( isset($query->taxonomy) && isset($query->terms[0]) && $query->taxonomy=='property_category'){
            $page = get_term_by( 'slug',$query->terms[0] ,'property_category');
            if( !empty($page) ){
                print '<strong>'.esc_html__( 'Category','wprentals').':</strong> '. esc_html($page->name) .', ';  
            }
        }
        
        if ( isset($query->taxonomy) && isset($query->terms[0]) && $query->taxonomy=='property_action_category'){
           $page = get_term_by( 'slug',$query->terms[0] ,'property_action_category');
            if( !empty($page) ){
                print '<strong>'.esc_html__( 'For','wprentals').':</strong> '.esc_html($page->name).', ';  
            }
            
        }
        
        if ( isset($query->taxonomy) && isset($query->terms[0]) && $query->taxonomy=='property_city'){
            $page = get_term_by( 'slug',urldecode($query->terms[0]) ,'property_city');
            if( !empty($page) ){
                print '<strong>'.esc_html__( 'City','wprentals').':</strong> '.esc_html($page->name).', ';  
            }
            
        }
        
        if ( isset($query->taxonomy) && isset($query->terms[0]) && $query->taxonomy=='property_area'){
            $page = get_term_by( 'slug',urldecode($query->terms[0] ),'property_area');
            if( !empty($page) ){
                print '<strong>'.esc_html__( 'Area','wprentals').':</strong> '.esc_html($page->name).', ';  
            }
                
        }
    }
 
    foreach($search_arguments_decoded->meta_query as $key=>$query ){
        if($custom_advanced_search==='yes'){
            
            $custm_name = wpestate_get_custom_field_name($query->key,$adv_search_what,$adv_search_label);
            if ( isset($query->compare) ){
           
                if ($query->compare=='CHAR'){
                    print esc_html__( 'has','wprentals').' <strong>'.str_replace('_',' ',$custm_name).'</strong>, ';       
                }else if ($query->compare=='<='){
                    print '<strong>'.esc_html($custm_name).'</strong> '.esc_html__( 'smaller than ','wprentals').' '.esc_html($query->value).', ';            
                }  else{
                    print '<strong>'.esc_html($custm_name).'</strong> '.esc_html__( 'bigger than','wprentals').' '.esc_html($query->value).', ';   
                }                
            }else{
                print '<strong>'.esc_html($custm_name).':</strong> '.esc_html($query->value).', ';
            } //end elese query compare
            
            
        }else{
            if ( isset($query->compare) ){
                if ($query->compare=='CHAR'){
                    print esc_html__( 'has','wprentals').' <strong>'.str_replace('_',' ',$query->key).'</strong>, ';       
                }else if ($query->compare=='<='){
                    print '<strong>'.str_replace('_',' ',esc_html($query->key)).'</strong> '.esc_html__( 'smaller than ','wprentals').' '.esc_html($query->value).', ';            
                } else{
                     print '<strong>'.str_replace('_',' ',esc_html($query->key)).'</strong> '.esc_html__( 'bigger than ','wprentals').' '.esc_html($query->value).', ';            
                }                 
            }else{
                print '<strong>'.str_replace('_',' ',esc_html($query->key)).':</strong> '.esc_html($query->value).', ';
            } //end elese query compare
       
        }//end else if custom adv search
       
    }   
    print '</div>';
    ?>
</div>