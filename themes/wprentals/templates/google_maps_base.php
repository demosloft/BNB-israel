<!-- Google Map -->
<?php
global $post;
$page_template='';
if(isset($post->ID)){
    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
}

$gmap_class="";

if( isset($post->ID) ){
    $wpestate_gmap_lat  =   floatval( get_post_meta($post->ID, 'property_latitude', true));
    $wpestate_gmap_long =   floatval( get_post_meta($post->ID, 'property_longitude', true));
    $property_add_on    =   ' data-post_id="'.esc_attr($post->ID).'" data-cur_lat="'.esc_attr($wpestate_gmap_lat).'" data-cur_long="'.esc_attr($wpestate_gmap_long).'" ';
    $closed_height      =   wpestate_get_current_map_height($post->ID);
    $open_height        =   wpestate_get_map_open_height($post->ID);
    $open_close_status  =   wpestate_get_map_open_close_status($post->ID);
    
}else{
    $wpestate_gmap_lat  =   esc_html( wprentals_get_option('wp_estate_general_latitude','') );
    $wpestate_gmap_long =   esc_html( wprentals_get_option('wp_estate_general_longitude','') );
    $property_add_on    =   ' data-post_id="" data-cur_lat="'.esc_attr($wpestate_gmap_lat).'" data-cur_long="'.esc_attr($wpestate_gmap_long).'" ';
    $closed_height      =   intval (wprentals_get_option('wp_estate_min_height',''));
    $open_height        =   wprentals_get_option('wp_estate_max_height','');
    $open_close_status  =   esc_html( wprentals_get_option('wp_estate_keep_min','' ) ); 
}




$display_contact=0;
if(isset($map_height) && intval($map_height)!==0 ){
    $closed_height=$map_height;
    $gmap_class.=' wpestate_full_map_shortcode ';
    $display_map_controls=1;
}

if($page_template=='contact_page.php' || 
        ( isset($map_shortcode_for) && $map_shortcode_for=='contact' && $map_shorcode_show_contact_form=='yes') ){
    $display_contact=1;
    $gmap_class.=" contact_map ";
    
}



?>

<div id="gmap_wrapper"  class="<?php print esc_attr($gmap_class);?>"   <?php print trim($property_add_on); ?> style="height:<?php print intval($closed_height);?>px"  >
    <span id="isgooglemap" data-isgooglemap="1"></span>       
   
    <div id="gmap-controls-wrapper">
        <div id="gmapzoomplus"></div>
        <div id="gmapzoomminus"></div>
    
        <div  id="geolocation-button"></div>
        <div  id="gmap-full"></div>
        <div  id="gmap-prev"></div>
        <div  id="gmap-next" ></div>
    </div>
    
    
    <?php
        $street_view_class=" ";
        if(  wprentals_get_option('wp_estate_show_g_search','') ==='yes'){
            $street_view_class=" lower_street ";
        }
    ?>

    <div id="googleMap"  style="height:<?php print intval($closed_height);?>px">   
    </div>    
  
   <div class="tooltip"> <?php esc_html_e('click to enable zoom','wprentals');?></div>   
    <?php
    if(  wprentals_get_option('wp_estate_kind_of_places')!=2){
    ?>
        <div id="gmap-loading"><?php esc_html_e('Loading Maps','wprentals');?>
             <div class="loader-inner ball-pulse"  id="listing_loader_maps">
                 <div class="double-bounce1"></div>
                 <div class="double-bounce2"></div>
             </div>
        </div>
    <?php 
    } 
    ?>
   
   <div id="gmap-noresult">
       <?php esc_html_e('We didn\'t find any results','wprentals');?>
   </div>
   
   <div class="gmap-controls">
        <?php
        // show or not the open close map button
        if( isset($post->ID) ){
            if (wpestate_get_map_open_close_status($post->ID) == 0 ){
                print ' <div id="openmap"><i class="fas fa-angle-down"></i>'.esc_html__( 'open map','wprentals').'</div>';
            }
        }else{
            if( esc_html( wprentals_get_option('wp_estate_keep_min','' ) )==0){
                print ' <div id="openmap"><i class="fas fa-angle-down"></i>'.esc_html__( 'open map','wprentals').'</div>';
            }
        }
        ?>
  
   </div>
</div>    
<!-- END Google Map --> 