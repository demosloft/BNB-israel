<?php
global $post_attachments;
global $post;
$post_thumbnail_id       =   get_post_thumbnail_id( $post->ID );
$preview                 =   wp_get_attachment_image_src($post_thumbnail_id, 'full');
$wpestate_currency       =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_where_currency =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
$price                   =   intval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label             =   esc_html ( get_post_meta($post->ID, 'property_label', true) );


?>

<div class="listing_main_image header_masonry panel-body imagebody imagebody_new" id="">


        <?php

        echo wpestate_return_property_status($post->ID);


        $hidden         =   '';
        $arguments      =   array(
                                'numberposts'   =>  -1,
                                'post_type'     =>  'attachment',
                                'post_mime_type'=>  'image',
                                'post_parent'   =>  $post->ID,
                                'post_status'   =>  null,
                                'orderby'         => 'menu_order',
                                'order'           => 'ASC',
                                 'exclude'      =>get_post_thumbnail_id(),
                        );

        $post_attachments   = get_posts($arguments);
        $count=0;

        $total_pictures=count ($post_attachments);

            if($count == 0 ){
                $full_prty          = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_featured');
                $full_prty_0='';
                if(isset($full_prty[0])){
                   $full_prty_0= $full_prty[0];
                }
                $full_prty_hidden_0='';
                $full_prty_hidden          = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                
                if(isset($full_prty_hidden[0])){
                    $full_prty_hidden_0= $full_prty_hidden[0];
                }
                
                print '<div class="col-md-6 image_gallery lightbox_trigger special_border" data-slider-no="1" style="background-image:url('.esc_attr($full_prty_0).')  ">   <div class="img_listings_overlay" ></div></div>';
                $hidden.= ' <a href="'.esc_url($full_prty_hidden_0).'" rel="data-fancybox-thumb" data-fancybox="website_rental_gallery" title="'.esc_attr('featured image','wprentals').'" data-caption="'.esc_attr('featured image','wprentals').'" class="fancybox-thumb prettygalery listing_main_image" >
                        <img  src="'.esc_url($full_prty_hidden_0).'" data-original="'.esc_attr($full_prty_hidden_0).'" alt="'.esc_attr('featured image','wprentals').'" class="img-responsive " />
                    </a>';
            }

            foreach ($post_attachments as $attachment) {
                $count++;
                $special_border='  ';
                if($count==0){
                    $special_border=' special_border ';
                }

                if($count>=1 && $count<=2){
                    $special_border=' special_border_top ';
                }



                if($count <= 3 && $count !=0){
                    $full_prty          = wp_get_attachment_image_src($attachment->ID, 'listing_full_slider');
                    print '<div class="col-md-3 image_gallery  '.esc_attr($special_border).' " data-slider-no="'.esc_attr($count+1).'" style="background-image:url('.esc_attr($full_prty[0]).')"> <div class="img_listings_overlay" ></div> </div>';
                }

                if($count == 4 ){
                    $full_prty          = wp_get_attachment_image_src($attachment->ID, 'listing_full_slider');
                    print '<div class="col-md-3 image_gallery" data-slider-no="'.esc_attr($count+1).'" style="background-image:url('.esc_attr($full_prty[0]).')  ">
                        <div class="img_listings_overlay img_listings_overlay_last" ></div>
                        <span class="img_listings_mes">'.esc_html__( 'See all','wprentals').' '.esc_html($total_pictures).' '.esc_html__( 'photos','wprentals').'</span></div>';
                }

                $full_prty_hidden          = wp_get_attachment_image_src($attachment->ID, 'full');
                $hidden.= ' <a  href="'.esc_url($full_prty_hidden[0]).'" rel="data-fancybox-thumb" data-fancybox="website_rental_gallery" title="'.esc_attr($attachment->post_excerpt).'" data-caption="'.esc_attr($attachment->post_excerpt).'" class="fancybox-thumb prettygalery listing_main_image" >
                        <img  src="'.esc_url($full_prty_hidden[0]).'" data-original="'.esc_attr($full_prty_hidden[0]).'" alt="'.esc_attr($attachment->post_excerpt).'" class="img-responsive " />
                    </a>';
            }

        ?>


</div> <div class="hidden_photos hidden_type3"><?php echo trim($hidden);?></div><!--
