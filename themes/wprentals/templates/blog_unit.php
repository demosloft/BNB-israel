<?php
global $wpestate_options;
global $unit_class;
global $row_number;
global $wpestate_row_number_col;
$thumb_id   =   get_post_thumbnail_id($post->ID);
$preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
$link       =  esc_url(get_permalink());
?>
<div class="col-md-<?php print esc_attr($wpestate_row_number_col);?> property_flex">
    <div class="blog_unit_back " data-link="<?php print esc_url($link);?>">
        <?php 
        $title      =   get_the_title();
        if( has_post_thumbnail($post->ID) ){
            $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_blog_unit');
            print '<div class="listing-unit-img-wrapper"> <img src="'.esc_url($preview[0]).'" class=" b-lazy img-responsive" alt="'.esc_attr($title).'" ><div class="price_unit_wrapper"> </div></div>';
        }else{
            $preview_img = get_stylesheet_directory_uri().'/img/defaultimage.jpg';
            print '<div class="listing-unit-img-wrapper"> <img src="'.esc_url($preview_img).'" class=" b-lazy  img-responsive" alt="'.esc_attr($title).'" > <div class="price_unit_wrapper"> </div></div>';
        }
       
        ?>
        <div class="blog-title">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="blog-title-link">
            <?php 
            $title=get_the_title();
            echo mb_substr( html_entity_decode($title),0,28); 
            if(strlen($title)>28){
                echo '...';   
            }
            ?>
            </a>
            
            <div class="blog-unit-content">
                <?php print wpestate_strip_words( get_the_excerpt(),25).' ...'; ?>
            </div>
            
            <div class="category_tagline">
                <span class="span_widemeta"> <?php print get_the_date('M d, Y');?></span> , 
                <span class="span_widemeta span_comments"><i class="far fa-comment"></i> <?php comments_number( '0','1','%');?></span>
            </div>           
        </div>
    </div>    
</div>