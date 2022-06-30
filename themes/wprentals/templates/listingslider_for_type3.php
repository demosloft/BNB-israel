<?php
global $post_attachments;
global $post;
$post_thumbnail_id       =   get_post_thumbnail_id( $post->ID );
$preview                 =   wp_get_attachment_image_src($post_thumbnail_id, 'full');
$wpestate_currency       =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_where_currency =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
$price                   =   intval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label             =   esc_html ( get_post_meta($post->ID, 'property_label', true) );

   echo wpestate_return_property_status($post->ID);
?>

<div class="listing_main_image" id="listing_main_image_photo_slider">


        <?php

        $hidden         =   '';
        $arguments      =   array(
                                'numberposts'   =>  -1,
                                'post_type'     =>  'attachment',
                                'post_mime_type'=>  'image',
                                'post_parent'   =>  $post->ID,
                                'post_status'   =>  null,
                                'orderby'         => 'menu_order',
                                'order'           => 'ASC'
                        );

        $post_attachments   = get_posts($arguments);
        foreach ($post_attachments as $attachment) {
            $full_prty          = wp_get_attachment_image_src($attachment->ID, 'wpestate_property_full_map');
            $full_prty_hidden          = wp_get_attachment_image_src($attachment->ID, 'full');
            print '<div class="listing_main_image_photo_slider_item" style="background-image:url('.esc_url($full_prty[0]).')">

                    <div class="price_unit_wrapper"></div></div>';

            $hidden.= ' <a href="'.esc_url($full_prty_hidden[0]).'" rel="data-fancybox-thumb" data-fancybox="website_rental_gallery"   title="'.esc_attr($attachment->post_excerpt).'"  data-caption="'.esc_attr($attachment->post_excerpt).'"  class="fancybox-thumb prettygalery listing_main_image" >
                        <img xxx src="'.esc_url($full_prty_hidden[0]).'" data-original="'.esc_attr($full_prty_hidden[0]).'" alt="'.esc_attr($attachment->post_excerpt).'" class="img-responsive " />
                    </a>';

        }
        ?>


</div> <div class="hidden_photos hidden_type3 vvv "><?php echo trim($hidden);?></div><!--
