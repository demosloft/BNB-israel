<?php
$enable_layout =  wprentals_get_option('wprentals_layout_manager') ;

if($enable_layout =='no'){
    $layout_full=   wprentals_get_default_layout();
    $layout     =   $layout_full['enabled'];

    if(  wprentals_get_option('wprentals_hide_description') =='yes'){
        unset($layout['description']);
    }else{
        $layout['description']='Decription';
    }

    if(  wprentals_get_option('wprentals_hide_default_owner') =='yes'){
        unset($layout['owner']);
    }else{
        $layout['owner']='owner';
    }

    if(  wprentals_get_option('wprentals_hide_default_map') =='yes'){
        unset($layout['map']);
    }else{
        $layout['map']='map';
    }

  

}else{
    $layout_full=   wprentals_get_option('wprentals_property_layout_tabs') ;
    $layout     =   $layout_full['enabled'];
}




?>

<div class="property_menu_wrapper_hidden <?php echo 'prop_menu_search_stick_'.wprentals_get_option('wp_estate_sticky_search');?>">
    <div class="property_menu_wrapper_insider">
        <?php if ( array_key_exists('description',$layout) ) { ?>
            <a class="property_menu_item" href="#listing_description"><?php esc_html_e('Description','wprentals')?></a>
        <?php } ?>
        
        
        <?php if ( array_key_exists('price_details',$layout) ) { ?>
            <a class="property_menu_item" href="#listing_price"><?php esc_html_e('Price','wprentals')?></a>
        <?php } ?>
    
        <?php if ( array_key_exists('listing_details',$layout) ) { ?>
            <a class="property_menu_item" href="#listing_details"><?php esc_html_e('Details','wprentals')?></a>
        <?php } ?>

        <?php if ( array_key_exists('features',$layout) ) { ?>
            <a class="property_menu_item" href="#listing_ammenities"><?php esc_html_e('Amenities','wprentals')?></a>
        <?php } ?>

        <?php if ( array_key_exists('availability',$layout) ) { ?>
            <a class="property_menu_item" href="#listing_calendar"><?php esc_html_e('Availability','wprentals')?></a>
        <?php } ?>
        
        
        <?php if ( array_key_exists('nearby',$layout) ) { 
            $yelp_client_id         =   trim(wprentals_get_option('wp_estate_yelp_client_id',''));
            $yelp_client_secret     =   trim(wprentals_get_option('wp_estate_yelp_client_secret',''));
            if($yelp_client_secret!=='' && $yelp_client_id!==''  ){?>
                <a class="property_menu_item" href="#yelp_details"><?php esc_html_e('Yelp','wprentals')?></a>
            <?php }
        }?>
        

        <?php if ( array_key_exists('reviews',$layout) ) { ?>
            <a class="property_menu_item" href="#listing_reviews"><?php esc_html_e('Reviews','wprentals')?></a>
        <?php } ?>
        
        <?php if ( array_key_exists('owner',$layout) ) { ?>
            <a class="property_menu_item" href="#listing_owner"><?php esc_html_e('Owner','wprentals')?></a>
        <?php } ?>
        
        <?php if ( array_key_exists('map',$layout) ) { ?>
            <a class="property_menu_item" href="#google_map_on_list"><?php esc_html_e('Map','wprentals')?></a>
        <?php } ?>
    </div>
</div>
