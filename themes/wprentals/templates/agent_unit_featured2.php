<?php
global $wpestate_options;
global $notes;

$thumb_id   =   get_post_thumbnail_id($post->ID);
$preview    =   wp_get_attachment_image_src($thumb_id, 'wpestate_property_featured');

$name       =   get_the_title($post->ID);
$link       =   esc_url(get_permalink());
$col_class = 4;
if ($wpestate_options['content_class'] == 'col-md-12') {
    $col_class = 3;
}
$owner_id           =   get_post_meta($post->ID, 'user_agent_id', true);
$comments_data      =   wpestate_review_composer($post->ID); 

?>

<div class="featured_property  featured_agent featured_agent_type2" data-link="<?php print esc_url($link); ?>">  
        <?php print wpestate_display_verification_badge($owner_id,2);?>

        <div class="feature_agent_image_unit_wrapper_color" >
            <div class="feature_agent_image_unit_wrapper"  style="background-image:url(<?php  print esc_url($preview[0]); ?>)">
            </div>
        </div>
    
        <div class="category_name"> 
            <?php
             
            if(isset($comments_data['list_rating']) ){ ?>
                <div class="property_ratings_agent property_ratings_agent_featured2">
                     <?php 
                        $counter=0; 
                        while($counter<5){
                            $counter++;
                            if($counter<=$comments_data['list_rating'] ){
                                print '<i class="fas fa-star"></i>';
                            }else{
                                print '<i class="far fa-star"></i>'; 
                            }

                        }
                    ?>
                
                </div>
            <?php } ?>
            
            
            <a class="featured_listing_title" href="<?php print esc_url($link); ?>"> <?php print esc_html($name); ?> </a>
            <div class="category_tagline">
               <?php print esc_html($notes);?>
            </div>
                   
        </div>
     
</div>