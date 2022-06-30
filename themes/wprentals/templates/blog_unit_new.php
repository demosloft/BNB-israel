<?php
global $wpestate_options;
global $unit_class;
global $row_number;
global $wpestate_row_number_col;
global $wpestate_full_row;

$wpestate_row_number_col=12;
$thumb_id   =   get_post_thumbnail_id($post->ID);
$preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
$link       =   esc_url(get_permalink());
$schema_data=   'itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ';

?>
<div <?php print trim($schema_data);?> class="col-md-<?php print esc_attr($wpestate_row_number_col);?> new_blog ">    
    <meta itemprop="position" content="<?php print esc_attr($wpestate_blog_selection->current_post);?>" />       
    <div class="blog_unit_back full_blog " data-link="<?php print esc_url($link);?>">
        <?php 
        $title      =   get_the_title();
        if( has_post_thumbnail($post->ID) ){
            if($wpestate_full_row==1){
                $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_featured');
            }else{
                $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_blog_unit2');
            }
            print '<div class="listing-unit-img-wrapper"> <div class="cross"></div><img src="'.esc_url($preview[0]).'" class=" b-lazy img-responsive" alt="'.esc_attr($title).'" ></div>';         
        }else{
            $preview_img = get_stylesheet_directory_uri().'/img/defaultimage_blog.jpg';
            print '<div class="listing-unit-img-wrapper"> <div class="cross"></div><img itemprop="image"  src="'.esc_url($preview_img).'" class=" b-lazy  img-responsive" alt="'.esc_attr($title).'" ></div>';
        }
       
        ?>
        <div class="blog-title">
            <a itemprop="url"  href="<?php echo esc_url(get_permalink()); ?>" class="blog-title-link">
                <span itemprop="name">
                    <?php
                    $title=get_the_title();
                    echo mb_substr( html_entity_decode($title),0,58); 
                    if(strlen($title)>58){
                        echo '...';   
                    }
                    ?>
                </span>
            </a>
            
            <div class="blog-unit-content"> 
                <?php echo  wp_strip_all_tags ( wpestate_the_excerpt_max_charlength(200) );?>
            </div>
            
            <div class="category_tagline">
                <span class="span_widemeta"> <?php print get_the_date('M d, Y');?></span>  
                <span class="span_widemeta"><?php esc_html_e('Category: ','wprentals');the_category(', '); ?></span> 
                <!-- <span class="span_widemeta span_comments"><i class="far fa-comment"></i> <?php //comments_number( '0','1','%');?></span>
                -->
                <span class="span_widemeta span_comments"><?php comments_number( '0','1','%');echo ' ';esc_html_e('Comments','wprentals');?></span>
            </div>
        </div>
    </div>    
</div>