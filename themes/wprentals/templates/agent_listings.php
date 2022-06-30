<!-- GET AGENT LISTINGS-->
<?php
global $agent_id;
global $leftcompare;
global $prop_selection;
global $wp_query;
global $wpestate_curent_fav;
global $wpestate_full_page;
global $comments_data;
global $wpestate_listing_type;
global $wpestate_property_unit_slider;

$wpestate_listing_type         =   wprentals_get_option('wp_estate_listing_unit_type','');
$current_user                  =   wp_get_current_user();
$userID                        =   $current_user->ID;
$user_option                   =   'favorites'.$userID;
$wpestate_curent_fav           =   get_option($user_option);
$show_compare_link             =   'no';
$wpestate_currency             =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_where_currency       =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
$leftcompare                   =   1;
$owner_id                      =   get_post_meta($agent_id, 'user_agent_id', true);
$wpestate_property_unit_slider =   esc_html ( wprentals_get_option('wp_estate_prop_list_slider','') ); 


if ( $comments_data['prop_selection']!='' &&  $comments_data['prop_selection']->have_posts() ) {
    $show_compare   =   1;
    $compare_submit =   wpestate_get_template_link('compare_listings.php');
    ?>
    <div class="mylistings">
        <?php   
        $wpestate_full_page=1;
        print'<h3 id="other_listings">'.esc_html__( 'My Listings','wprentals').'</h3>';
        while ($comments_data['prop_selection']->have_posts()): $comments_data['prop_selection']->the_post();    
            include(locate_template('templates/property_unit.php') );
        endwhile;
        // Reset postdata
        wp_reset_postdata();
        ?>
        
    <?php 
        if(isset($_GET['pagelist'])){
            $paged=floatval($_GET['pagelist']);
        }else{
            $paged=1;
        }
     
        wprentals_second_loop_pagination($comments_data['prop_selection']->max_num_pages,$range =2,$paged,esc_url(get_permalink()));
    ?>     
    </div>
<?php        
} 
?>
   