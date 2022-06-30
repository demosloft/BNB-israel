<?php
get_header();
global $term;
global $taxonmy;
global $wpestate_options;
$wpestate_options        =   wpestate_page_details('');
$filtred                 =   0;
$show_compare            =   1;
$compare_submit          =   wpestate_get_template_link('compare_listings.php');
$current_user            =   wp_get_current_user();
$wpestate_currency       =   esc_html( wprentals_get_option('wp_estate_currency_label_main','') );
$wpestate_where_currency =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol','') );
$prop_no                 =   intval( wprentals_get_option('wp_estate_prop_no','') );
$userID                  =   $current_user->ID;
$user_option             =   'favorites'.$userID;
$wpestate_curent_fav     =   get_option($user_option);
$transient_appendix      =   'taxonomy_';
$taxonmy                 =  get_query_var('taxonomy');
$term                    =  get_query_var( 'term' );
$tax_array  = array(
                'taxonomy'  => $taxonmy,
                'field'     => 'slug',
                'terms'     => $term
            );
 

$paged= (get_query_var('paged')) ? get_query_var('paged') : 1;
    

$transient_appendix.=$taxonmy.'_'.$term.'_prop_'.$prop_no.'paged_'.$paged;
$args = array(
    'post_type'         => 'estate_property',
    'post_status'       => 'publish',
    'paged'             => $paged,
    'posts_per_page'    => $prop_no,
    'meta_key'          => 'prop_featured',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
    'tax_query'         => array(
                            'relation' => 'AND',
                            $tax_array
                        )
);	

$transient_appendix =   wpestate_add_language_currency_cache($transient_appendix);
$prop_selection     =   wpestate_request_transient_cache( 'wpestate_taxonomy_list'.$transient_appendix);

if($prop_selection==false){
    if(function_exists('wpestate_return_filtered_by_order')){
        $prop_selection = wpestate_return_filtered_by_order($args);
    }
    wpestate_set_transient_cache(  'wpestate_taxonomy_list'.$transient_appendix, $prop_selection, 60*4*4 );
}


$property_list_type_status =    esc_html(wprentals_get_option('wp_estate_property_list_type',''));

if ( $property_list_type_status == 2 ){
    include(locate_template('templates/half_map_core.php'));
}else{
    include(locate_template('templates/normal_map_core.php'));
}


if (wp_script_is( 'wpestate_googlecode_regular', 'enqueued' )) {
    $mapargs                    =   $args;
    $max_pins                   =   intval( wprentals_get_option('wp_estate_map_max_pins') );
    $mapargs['posts_per_page']  =   $max_pins;
    $mapargs['offset']          =   ($paged-1)*$prop_no;
    $mapargs['fields']          =   'ids';
    
    $transient_appendix.='_maxpins'.$max_pins.'_offset_'.($paged-1)*$prop_no;
    $selected_pins  =   wpestate_listing_pins($transient_appendix,1,$mapargs,1,1);//call the new pins  
    
    wp_localize_script('wpestate_googlecode_regular', 'googlecode_regular_vars2', 
                array('markers2'          =>  $selected_pins,
                      'taxonomy'          =>  $taxonmy,
                      'term'              =>  $term));

}

get_footer(); 
?>