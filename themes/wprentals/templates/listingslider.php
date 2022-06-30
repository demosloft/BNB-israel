<?php
global $post_attachments;
global $post;
$post_thumbnail_id       =   get_post_thumbnail_id( $post->ID );
$preview                 =   wp_get_attachment_image_src($post_thumbnail_id, 'full');
$wpestate_currency       =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_where_currency =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
$price                   =   intval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label             =   esc_html ( get_post_meta($post->ID, 'property_label', true) );
$property_city           =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area           =   get_the_term_list($post->ID, 'property_area', '', ', ', '');

 echo wpestate_return_property_status($post->ID);
?>

<div class="listing_main_image" id="listing_main_image_photo" style="background-image: url('<?php print esc_url($preview[0]);?>')">
    <div id="tooltip-pic"> <?php esc_html_e('click to see all images','wprentals');?></div>
    <h1 itemprop="name" class="entry-title entry-prop"><?php the_title(); ?>
        <span class="property_ratings listing_slider">
            <?php
                if(wpestate_has_some_review($post->ID)!==0){
                    print wpestate_display_property_rating( $post->ID );
                }
            ?>
        </span>
    </h1>

    <?php
    if(trim($property_area)!=''){
        $property_area=', '.$property_area;
    }
    ?>

    <div class="listing_main_image_location"  itemprop="location" itemscope itemtype="http://schema.org/Place">
        <?php print  trim($property_city.$property_area);//escaped above ?>
        <div  class="schema_div_noshow" itemprop="name"><?php echo strip_tags (  $property_city.$property_area); ?></div>
    </div>

    <div itemprop="price" class="listing_main_image_price">
        <?php

            $price_per_guest_from_one       =   floatval( get_post_meta($post->ID, 'price_per_guest_from_one', true) );

            $price          = floatval( get_post_meta($post->ID, 'property_price', true) );
            wpestate_show_price($post->ID,$wpestate_currency,$wpestate_where_currency,0);
            $rental_type        =   wprentals_get_option('wp_estate_item_rental_type');
            $booking_type       =   wprentals_return_booking_type($post->ID);

            if($price!=0){
                if( $price_per_guest_from_one == 1){
                    echo ' '.esc_html__( 'per guest','wprentals');
                }else{
                    echo ' '.wpestate_show_labels('per_night',$rental_type,$booking_type);
                }
            }

        ?>
    </div>

     <div class="listing_main_image_text_wrapper"></div>

    <div class="hidden_photos">
        <?php

        print ' <a href="'.esc_url($preview[0]).'"  rel="data-fancybox-thumb" data-fancybox="website_rental_gallery" data-caption="'.get_post($post_thumbnail_id)->post_excerpt.'" title="'.get_post($post_thumbnail_id)->post_excerpt.'" class="fancybox-thumb prettygalery listing_main_image" >
                    <img  itemprop="image" src="'.esc_url($preview[0]).'" data-original="'.esc_url($preview[0]).'"  class="img-responsive" alt="'.esc_html__('gallery','wprentals').'" />
                </a>';

        $arguments      = array(
                            'numberposts'   =>  -1,
                            'post_type'     =>  'attachment',
                            'post_mime_type'=>  'image',
                            'post_parent'   =>  $post->ID,
                            'post_status'   =>  null,
                            'exclude'       =>  $post_thumbnail_id,
                            'orderby'         => 'menu_order',
                            'order'           => 'ASC'

                        );

        $post_attachments   = get_posts($arguments);
        foreach ($post_attachments as $attachment) {
            $full_prty          = wp_get_attachment_image_src($attachment->ID, 'full');

            print ' <a href="'.esc_url($full_prty[0]).'" rel="data-fancybox-thumb" data-fancybox="website_rental_gallery" data-caption="'.esc_attr($attachment->post_excerpt).'" title="'.esc_attr($attachment->post_excerpt).'" class="fancybox-thumb prettygalery listing_main_image" >
                        <img  src="'.esc_url($full_prty[0]).'" xvc data-original="'.esc_attr($full_prty[0]).'" alt="'.esc_attr($attachment->post_excerpt).'" class="img-responsive " />
                    </a>';

        }
        ?>
    </div>

</div><!--
