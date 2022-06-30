<?php
$preview   =  wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'wpestate_slider_thumb',$extra);
$featured  =  intval  ( get_post_meta($post->ID, 'prop_featured', true) );
?>

<div class=" dashboard_imagine">
    <?php
    if($featured==1){
        print '<span class="label label-primary featured_div">'.esc_html__( 'featured','wprentals').'</span>';
    }
    if (has_post_thumbnail($post_id)){
    ?>
      <a href="<?php print esc_url($link); ?>"><img src="<?php  print esc_url($preview[0]); ?>" class="b-lazy dashboad-prop-img img-responsive " alt="<?php esc_html_e('image','wprentals');?>" /></a>
    <?php
    } else{
        $thumb_prop_default =  get_stylesheet_directory_uri().'/img/defaultimage_prop.jpg';?>
        <img src="<?php print esc_url($thumb_prop_default);?>"   class="b-lazy img-responsive dashboad-prop-img  wp-post-image " alt="<?php esc_html_e('image','wprentals');?>" />
    <?php
    }
    ?>
</div>
