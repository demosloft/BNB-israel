<?php
global $wpestate_options;
global $unit_class;
global $design_class;
$thumb_id   =   get_post_thumbnail_id($post->ID);
$preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
$link       =   esc_url(get_permalink());
?>

<div class=" blog_featured <?php echo esc_attr($design_class); ?>">
    <div class="blog_unit places1" data-link="<?php print esc_url($link);?>"> 
        <?php 
        $title      =   get_the_title();
        $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_featured');
        
        if( trim($design_class) =='type_1_class'){
            print   '<div class="feature_agent_image_unit_wrapper_color">';
        }else{
            print   '<div class="listing-hover-gradient"></div><div class="listing-hover" ></div>';
        }
        
        print   '<div class="listing-unit-img-wrapper shortcodefull" style="background-image:url('.esc_url($preview[0]).')"></div>';
        if(trim($design_class)=='type_1_class'){
            print   '<div class="featured-article-date">'. get_the_date('M d, Y').'</div>'; 
            print   '</div>';// end feature_agent_image_unit_wrapper_color
        }
        ?>
    </div>
    <div class="blog-title">
        <?php
        if(trim($design_class)=='type_2_class'){
            print   '<div class="featured-article-date ">'. get_the_date('M d, Y').'</div>'; 
        }
        ?>
        <a href="<?php echo esc_url(get_permalink()); ?>" class="blog-title-link"><?php print get_the_title(); ?></a>
        <?php
            if( trim($design_class) =='type_1_class'){
        ?>
            <div class="featued_article_categories_list"> <?php print get_the_category_list(', ','',$post->ID);?></div>
        <?php }
        ?>
    </div>
</div>