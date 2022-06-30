<?php
  $item_id  =  $key;
  $preview  =   wp_get_attachment_image_src(get_post_thumbnail_id($item_id), 'wpestate_slider_thumb');
  $link     =   get_permalink($item_id);
  $title    =   get_the_title($item_id);
 ?>

<div class="dashboard_widget_unit">
  <a class="dashbard_unit_image" href="<?php print esc_url($link); ?>"><img  src="<?php  print esc_url($preview[0]); ?>" /></a>

  <div class="property_dashboard_location_wrapper">
    <a class="dashbard_unit_title" href="<?php print esc_url($link); ?>"><?php echo esc_html($title); ?></a>

    <div class="property_dashboard_location">
        <?php
         print esc_html__('No of bookings','wprentals').': '.$property['book_no'].' |  ';

         $total_price_show    =   wpestate_show_price_booking ( $property['total_price'] ,$wpestate_currency,$wpestate_where_currency,1);
          print esc_html__('Total price','wprentals').': '.$total_price_show;
         ?>
    </div>
  </div>
</div>
