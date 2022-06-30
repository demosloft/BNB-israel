<?php
global $slider_size;
$video_id       =   '';
$video_thumb    =   '';
$video_alone    =   0;
$full_img       =   '';
$arguments      = array(
                    'numberposts' => -1,
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'post_parent' => $post->ID,
                    'post_status' => null,
                    'exclude' => get_post_thumbnail_id(),
                    'orderby'         => 'menu_order',
                    'order'           => 'ASC'
                );

$post_attachments   = get_posts($arguments);
$video_id           = esc_html( get_post_meta($post->ID, 'embed_video_id', true) );
$video_type         = esc_html( get_post_meta($post->ID, 'embed_video_type', true) );


$total_pictures=count ($post_attachments)+1;

 echo wpestate_return_property_status($post->ID);
if ($post_attachments || has_post_thumbnail() || get_post_meta($post->ID, 'embed_video_id', true)) {  ?>
    <div id="carousel-listing" class="carousel slide post-carusel carouselvertical" data-ride="carousel" data-interval="false">

        <?php
        $indicators='';
        $round_indicators='';
        $slides ='';
        $captions='';
        $counter=0;
        $has_video=0;
        if($video_id!=''){
            $has_video  =   1;
            $counter    =   1;
            $videoitem  =   'videoitem';
            if ($slider_size    ==  'full'){
                $videoitem  =  'videoitem_full';
            }


            $indicators.='<li data-target="#carousel-listing"  data-video_data="'.esc_attr($video_type).'" data-video_id="'.esc_attr($video_id).'"  data-slide-to="0" class="active video_thumb_force">
                         <img src= "'.wprentals_get_video_thumb($post->ID).'" alt="'.esc_html__('video thumb','wprentals').'" class="img-responsive"/>
                         <span class="estate_video_control"><i class="fas fa-play"></i> </span>
                         </li>';

            $round_indicators   .=  ' <li data-target="#carousel-listing" data-slide-to="0" class="active"></li>';

            $slides .= '<div class="item active '.esc_attr($videoitem).'">';

            if($video_type=='vimeo'){
               $slides .= wpestate_custom_vimdeo_video($video_id);
            }else{
               $slides.= wpestate_custom_youtube_video($video_id);
            }

            $slides   .= '</div>';

        }

        if( has_post_thumbnail() ){
              $counter++;
            $active='';
            if($counter==1 && $has_video!=1){
                $active=" active ";
            }else{
                $active=" ";
            }

            $post_thumbnail_id  = get_post_thumbnail_id( $post->ID );
            $preview            = wp_get_attachment_image_src($post_thumbnail_id, 'wpestate_slider_thumb');

            if ($slider_size=='full'){
                $full_img           = wp_get_attachment_image_src($post_thumbnail_id, 'wpestate_property_featured');
            }else{
                $full_img           = wp_get_attachment_image_src($post_thumbnail_id, 'wpestate_blog_unit2');
            }

            $full_prty          = wp_get_attachment_image_src($post_thumbnail_id, 'full');
            $attachment_meta    = wp_get_attachment($post_thumbnail_id);

            $indicators.= '<li data-target="#carousel-listing" data-slide-to="'.esc_attr($counter-1).'" class="'. $active.'">
                                <div class="img_listings_overlay img_listings_overlay_last" ></div>
                                <img itemprop="image"  src="'.esc_url($preview[0]).'"  alt="'.esc_html__('slider','wprentals').'" />
                           </li>';

            $round_indicators   .=  ' <li data-target="#carousel-listing" data-slide-to="'.esc_attr($counter-1).'" class="'. $active.'" ></li>';
            $slides .= '<div class="item '.esc_attr($active).' " data-fancybox="website_rental_gallery" data-src="'.esc_url($full_prty[0]).'" title="'.esc_attr($attachment_meta['caption']).'" data-caption="'.esc_attr($attachment_meta['caption']).'" rel="data-fancybox-thumb" data-fancybox="wprentals_fancy_gallery" >

                                <img xx src="'.esc_url($full_img[0]).'" data-original="'.esc_attr($full_prty[0]).'" alt="'.esc_attr($attachment_meta['alt']).'" class="img-responsive" />';


                            if($attachment_meta['caption']!=''){
                                    $slides .= '<div class="item_captions_text">'.esc_html($attachment_meta['caption']).'</div><div class="item_captions"></div>';
                            }

            $slides .=  '</div>';
        }

        foreach ($post_attachments as $attachment) {
            $counter++;
            $active='';
            if($counter==1 && $has_video!=1){
                $active=" active ";
            }else{
                $active=" ";
            }

            $preview            = wp_get_attachment_image_src($attachment->ID, 'wpestate_slider_thumb');
            if ($slider_size=='full'){
                $full_img           = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_featured');
            }else{
                $full_img           = wp_get_attachment_image_src($attachment->ID, 'wpestate_blog_unit2');
            }
            $full_prty          = wp_get_attachment_image_src($attachment->ID, 'full');
            $attachment_meta    = wp_get_attachment($attachment->ID);

            $indicators.= ' <li data-target="#carousel-listing" data-slide-to="'.esc_attr($counter-1).'" class="'. $active.'">
                                <div class="img_listings_overlay img_listings_overlay_last" ></div>
                                <img  src="'.esc_url($preview[0]).'"  alt="'.esc_html__('slider','wprentals').'" />
                            </li>';
            $round_indicators   .=  ' <li data-target="#carousel-listing" data-slide-to="'.esc_attr($counter-1).'" class="'. $active.'"></li>';

            $slides .= '<div class="item '.esc_attr($active).'"  data-src="'.esc_url($full_prty[0]).'" data-fancybox="website_rental_gallery" rel="data-fancybox-thumb" title="'.esc_attr($attachment_meta['caption']).'">
                          <img src="'.esc_url($full_img[0]).'" data-original="'.esc_url($full_prty[0]).'" alt="'.esc_attr($attachment_meta['alt']).'" class="img-responsive" />';
                          if($attachment_meta['caption']!=''){
                              $slides .= '<div class="item_captions_text">'.esc_html($attachment_meta['caption']).'</div><div class="item_captions"></div>';
                          }
            $slides .=  '</div>';
        }// end foreach
        ?>



    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <?php print trim($slides);//escpaped above?>
         <a class="left vertical carousel-control" href="#carousel-listing" data-slide="prev">
          <i class="icon-left-open-big"></i>
        </a>

        <a class="right vertical carousel-control" href="#carousel-listing" data-slide="next">
          <i class=" icon-right-open-big"></i>
        </a>
    </div>

    <div class="carousel-indicators-wrapper" >
        <ol  id="carousel-indicators-vertical">
          <?php print trim($indicators);//escaped above ?>
        </ol>
    </div>


    </div>

<?php
} // end if post_attachments
?>
