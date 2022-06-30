<?php
$adv_submit             =   wpestate_get_template_link('advanced_search_results.php');
$guest_list             =   wpestate_get_guest_dropdown();
$local_args_search_map_list = wpestate_get_select_arguments();
$allowed_html = array();
$allowed_html_list =    array('li' => array(
                                        'data-value'        =>array(),
                                        'role'              => array(),
                                        'data-parentcity'   =>array(),
                                        'data-value2'       =>array()
                        ) );
$action_select_list =   wpestate_get_action_select_list($local_args_search_map_list);
$categ_select_list  =   wpestate_get_category_select_list($local_args_search_map_list);
$select_city_list   =   wpestate_get_city_select_list($local_args_search_map_list);
$select_area_list   =   wpestate_get_area_select_list($local_args_search_map_list);
$select_county_state_list = array();
$min_price_slider   =   floatval(wprentals_get_option('wp_estate_show_slider_min_price',''));
$max_price_slider   =   floatval(wprentals_get_option('wp_estate_show_slider_max_price',''));
$wpestate_where_currency     =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
$wpestate_currency  =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );

if(isset($_GET['price_max'])){
    $max_price_slider = floatval($_GET['price_max']);
}

if(isset($_GET['price_low'])){
    $min_price_slider = floatval($_GET['price_low']);
}


if ($wpestate_where_currency == 'before') {
    $price_slider_label = esc_html($wpestate_currency) . number_format($min_price_slider).' '.esc_html__( 'to','wprentals').' '.esc_html($wpestate_currency) . number_format($max_price_slider);
}else {
    $price_slider_label =  number_format($min_price_slider).esc_html($wpestate_currency).' '.esc_html__( 'to','wprentals').' '.number_format($max_price_slider).esc_html($wpestate_currency);
}

?>

<div id="advanced_search_map_list">
    <div class="advanced_search_map_list_container">
        <?php

        if( wprentals_get_option('wp_estate_use_geo_location','')=='yes'){

        $radius_measure = wprentals_get_option('wp_estate_geo_radius_measure','');
        $radius_value   = wprentals_get_option('wp_estate_initial_radius','');

        ?>
            <div class="col-md-12 radius_wrap">
                <input type="text" id="geolocation_search" class="form-control" name="geolocation_search" placeholder="<?php esc_html_e('Location','wprentals');?>" value="">
                <input type="hidden" id="geolocation_lat" name="geolocation_lat">
                <input type="hidden" id="geolocation_long" name="geolocation_lat">
            </div>

            <div class="col-md-3 slider_radius_wrap">
                <div class="label_radius"><?php esc_html_e('Radius:','wprentals');?> <span class="radius_value"><?php print esc_html($radius_value.' '.$radius_measure);?></span></div>
            </div>

            <div class="col-md-9 slider_radius_wrap">
                <div id="wpestate_slider_radius"></div>
                <input type="hidden" id="geolocation_radius" name="geolocation_radius" value="<?php print esc_html($radius_value);?>">
            </div>
        <?php
        }
        ?>

        <div class="advanced_search_map_list_container_trigger">


        <?php

            global $search_object;
            print  trim($search_object->wpstate_display_search_form('half')); 
            include(locate_template('libs/internal_autocomplete_wpestate.php'));

        ?>

        </div>
    </div>
</div>


<div id="advanced_search_map_list_hidden">
    <div class="col-md-2">
        <div class="show_filters" id="adv_extended_options_show_filters"><?php esc_html_e('Search Options','wprentals')?></div>
    </div>
</div>
