<?php
global $current_user;
global $wpestate_propid;
global $post_attachments;
global $wpestate_options;
global $wpestate_where_currency;
global $wpestate_property_description_text;     
global $wpestate_property_details_text;  
global $wpestate_property_details_text;
global $wpestate_property_adr_text;  
global $wp_estate_sleeping_text;
global $wpestate_property_price_text;   
global $wpestate_property_pictures_text;    
global $wpestate_propid;
global $wpestate_gmap_lat;  
global $wpestate_gmap_long;
global $wpestate_unit;
global $wpestate_currency;
global $wpestate_use_floor_plans;
global $guests;
global $bedrooms;
global $bathrooms;
global $show_sim_two;
global $guest_list;
global $post_id;
include(locate_template('templates/listingslider_for_type3.php') ); 
$price              =   intval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label        =   esc_html ( get_post_meta($post->ID, 'property_label', true) );  
$property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
$post_id            =   $post->ID; 
$guest_no_prop      =   '';
if(isset($_GET['guest_no_prop'])){
    $guest_no_prop = intval($_GET['guest_no_prop']);
}
$guest_list= wpestate_get_guest_dropdown('noany');
?>
-->
<div  class="row content-fixed-listing listing_type_3">
    <div class=" <?php 
    if ( $wpestate_options['content_class']=='col-md-12' || $wpestate_options['content_class']=='none'){
        print 'col-md-8';
    }else{
        if(isset($wpestate_options['content_class'])){
            print esc_attr( $wpestate_options['content_class']); 
        }
    }?> ">
    
        <?php include(locate_template('templates/ajax_container.php')); ?>
        <?php
        while (have_posts()) : the_post();
            $image_id       =   get_post_thumbnail_id();
            $image_url      =   wp_get_attachment_image_src($image_id, 'wpestate_property_full_map');
            $full_img       =   wp_get_attachment_image_src($image_id, 'full');
            $image_url      =   $image_url[0];
            $full_img       =   $full_img [0];     
        ?>
        
        <div class="single-content listing-content">
            <div class="booking_form_mobile">
            <?php
            if ( wp_is_mobile() ) {
                include(locate_template ('templates/booking_form_template.php'));
            }
            ?>
            </div>
            
            <?php include(locate_template('templates/property_header3.php') ); ?>
            
                
            <?php
                wprentals_property_content_layout($post->ID);
            ?>
    
        <?php
        endwhile; // end of the loop
        $show_compare=1;
        ?>
        

        
        
        </div><!-- end single content -->
    </div><!-- end 8col container-->
    
    
    <div class="clearfix visible-xs"></div>
    <div class=" 
        <?php
        if($wpestate_options['sidebar_class']=='' || $wpestate_options['sidebar_class']=='none' ){
            print ' col-md-4 '; 
        }else{
            print esc_attr($wpestate_options['sidebar_class']);
        }
        ?> 
        widget-area-sidebar listingsidebar listing_type_3" id="primary" >
      <?php
            if ( !wp_is_mobile() ) {
                include(locate_template ('templates/booking_form_template.php' ) );
            }
            ?>
        <?php  include(get_theme_file_path('sidebar-listing.php')); ?>
    </div>
</div>   


<?php
$wprentals_hide_default_owner   =   wprentals_get_option('wprentals_hide_default_owner') ;
$wprentals_hide_similar_listing =   wprentals_get_option('wprentals_hide_similar_listing') ;
$wprentals_hide_default_map     =   wprentals_get_option('wprentals_hide_default_map') ;
?>


<div class="full_width_row">    
    <?php 
    if($wprentals_hide_default_owner=='no'){ 
    ?>
    <div class="owner-page-wrapper">
        <div class="owner-wrapper  content-fixed-listing row" id="listing_owner">
            <?php include(locate_template ('/templates/owner_area.php' ) ); ?>
        </div>
    </div>
    <?php } ?>


    <?php 
    if($wprentals_hide_default_map=='no'){ 
    ?>
        <div class="google_map_on_list_wrapper">    
                <div id="gmapzoomplus"></div>
                <div id="gmapzoomminus"></div>
                <?php 
                if( wprentals_get_option('wp_estate_kind_of_map')==1){ ?>
                    <div id="gmapstreet"></div>
                    <?php echo wpestate_show_poi_onmap();
                }
                ?>
            
            <div id="google_map_on_list" 
                data-cur_lat="<?php   print esc_attr($wpestate_gmap_lat);?>" 
                data-cur_long="<?php print esc_attr($wpestate_gmap_long); ?>" 
                data-post_id="<?php print intval($post->ID); ?>">
            </div>
        </div>
    <?php } ?>
    
    

    <?php 
    if($wprentals_hide_similar_listing=='no'){ 
        include(locate_template ('/templates/similar_listings.php') );
    }
    ?>

</div>

<?php get_footer(); ?>