<?php 
global $post;
if( is_page() && wpestate_check_if_admin_page($post->ID) && is_user_logged_in()  ){  
}else{
$facebook_link      =   esc_html( wprentals_get_option('wp_estate_facebook_link', '') );
$twitter_link       =   esc_html( wprentals_get_option('wp_estate_twitter_link', '') );
$google_link        =   esc_html( wprentals_get_option('wp_estate_google_link', '') );
$linkedin_link      =   esc_html ( wprentals_get_option('wp_estate_linkedin_link','') );
$pinterest_link     =   esc_html ( wprentals_get_option('wp_estate_pinterest_link','') );
$instagram_ac       =   esc_html( wprentals_get_option('wp_estate_instagram_ac', '') );
$youtube_ac         =   esc_html( wprentals_get_option('wp_estate_youtube_ac', '') );
$map_position       =   wprentals_get_option('wp_estate_align_style_half', '') ;
$map_position_class =    '';

if($map_position==2){
    $map_position="social_share_half_map_on_right";
}
?>
<div class="social_share_wrapper <?php echo esc_attr($map_position); ?>">

    <?php if ($facebook_link!='' ){?>
    <a class="social_share share_facebook_side" href="<?php print esc_url($facebook_link);?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
    <?php } ?>
    
    <?php if ($twitter_link!='' ){?>
        <a class="social_share share_twiter_side" href="<?php print esc_url($twitter_link);?>" target="_blank"><i class="fab fa-twitter"></i></a>
    <?php } ?>
    
    <?php if ($google_link!='' ){?>
        <a class="social_share share_google_side" href="<?php print esc_url($google_link); ?>" target="_blank"><i class="fab fa-google-plus-g"></i></a>
    <?php } ?>
    
    <?php if ($linkedin_link!='' ){?>
        <a class="social_share share_linkedin_side" href="<?php print esc_url($linkedin_link); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
    <?php } ?>
    
    <?php if ($pinterest_link!='' ){?>
        <a class="social_share share_pinterest_side" href="<?php print esc_url($pinterest_link);?>" target="_blank"><i class="fab fa-pinterest-p"></i></a>
    <?php } ?>
        
    <?php if ($instagram_ac!='' ){?>
        <a class="social_share share_instagram_side" href="<?php print esc_url($instagram_ac);?>" target="_blank"><i class="fab fa-instagram"></i></a>
    <?php } ?>
        
    <?php if ($youtube_ac!='' ){?>
        <a class="social_share share_youtube_side" href="<?php print esc_url($youtube_ac);?>" target="_blank"><i class="fab fa-youtube"></i></a>
    <?php } ?>
           
        
</div>
<?php }