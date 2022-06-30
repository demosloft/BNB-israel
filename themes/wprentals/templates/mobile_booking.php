<div class="mobile_booking_wrapper">
  <div class="mobile_booking_wrapper_flex">
    <div class="property_ratings">
        <?php
        if(wpestate_has_some_review($post->ID)!==0){
            $args = array(
                'post_id' => $post->ID, // use post_id, not post_ID
            );
            $comments   =   get_comments($args);
            $coments_no =   0;
            $stars_total=   0;


            foreach($comments as $comment) :
                $coments_no++;

            endforeach;

            if($coments_no>0){
                print wpestate_display_property_rating( $post->ID );
                print '<div class="rating_no">('.esc_html($coments_no).')</div>';
            }
        }
        ?>
    </div>
    
    <div  itemprop="price"  class="listing_main_image_price">
        <?php
        $price_per_guest_from_one       =   floatval( get_post_meta($post->ID, 'price_per_guest_from_one', true) );
        $price                          =   floatval( get_post_meta($post->ID, 'property_price', true) );
        wpestate_show_price($post->ID,$wpestate_currency,$wpestate_where_currency,0);
        print '<span class="pernight_label">';
        if($price!=0){
            if( $price_per_guest_from_one == 1){
                echo ' '.esc_html__( 'per guest','wprentals');
            }else{
                echo ' '.wpestate_show_labels('per_night',$rental_type,$booking_type);
            }
        }
        print '</span>';
        ?>
    </div>




  </div>



  <button class="wpb_btn-info wpb_regularsize wpestate_vc_button  vc_button" id="mobile_booking_triger">
    <?php esc_html_e('Make a reservation','wprentals');?>
  </button>
</div>
