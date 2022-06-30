<div class="property_menu_wrapper">
    <div class="property_menu_wrapper_insider">
        <a class="property_menu_item" href="#listing_description"><?php esc_html_e('Description','wprentals')?></a>
        <a class="property_menu_item" href="#listing_price"><?php esc_html_e('Price','wprentals')?></a>
        <a class="property_menu_item" href="#listing_details"><?php esc_html_e('Details','wprentals')?></a>
        <a class="property_menu_item" href="#listing_ammenities"><?php esc_html_e('Ammenities','wprentals')?></a>
        <a class="property_menu_item" href="#listing_calendar"><?php esc_html_e('Avalability','wprentals')?></a>
        <?php
        $yelp_client_id         =   trim(wprentals_get_option('wp_estate_yelp_client_id',''));
        $yelp_client_secret     =   trim(wprentals_get_option('wp_estate_yelp_client_secret',''));
        if($yelp_client_secret!=='' && $yelp_client_id!==''  ){
        ?>
            <a class="property_menu_item" href="#yelp_details"><?php esc_html_e('Yelp','wprentals')?></a>
        <?php 
        } 
        ?>
        <a class="property_menu_item" href="#listing_reviews"><?php esc_html_e('Reviews','wprentals')?></a>
        <a class="property_menu_item" href="#google_map_on_list"><?php esc_html_e('Owner','wprentals')?></a>
        <a class="property_menu_item" href="#google_map_on_list"><?php esc_html_e('Map','wprentals')?></a>
    </div>
</div>
