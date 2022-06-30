<?php
global $post;
global $wpestate_where_currency;
global $wpestate_currency;
global $user_login;

$link               =   esc_url(get_permalink());
$booking_status     =   get_post_meta($post->ID, 'booking_status', true);
$booking_status_full=   get_post_meta($post->ID, 'booking_status_full', true);
$booking_id         =   get_post_meta($post->ID, 'booking_id', true);
$booking_from_date  =   get_post_meta($post->ID, 'booking_from_date', true);
$booking_to_date    =   get_post_meta($post->ID, 'booking_to_date', true);
$booking_guests     =   get_post_meta($post->ID, 'booking_guests', true);
$preview            =   wp_get_attachment_image_src(get_post_thumbnail_id($booking_id), 'wpestate_blog_unit');
$author             =   get_the_author();
$author_id          =   get_the_author_meta('ID');
$userid_agent       =   get_user_meta($author_id, 'user_agent_id', true);
$invoice_no         =   get_post_meta($post->ID, 'booking_invoice_no', true);
$booking_array      =   wpestate_booking_price($booking_guests,$invoice_no,$booking_id, $booking_from_date, $booking_to_date);
$invoice_no         =   get_post_meta($post->ID, 'booking_invoice_no', true);
$booking_pay        =   $booking_array['total_price'];
$booking_company    =   get_post_meta($post->ID, 'booking_company', true);
$no_of_days         =   $booking_array['numberDays'];
$property_price     =   $booking_array['default_price'];
$event_description  =   get_the_content();

if ( $booking_status=='confirmed'){
    $total_price        =   floatval( get_post_meta($post->ID, 'total_price', true) );
    $to_be_paid         =   floatval( get_post_meta($post->ID, 'to_be_paid', true) );
    $to_be_paid         =   $total_price-$to_be_paid;
    $to_be_paid_show    =   wpestate_show_price_booking ( $to_be_paid ,$wpestate_currency,$wpestate_where_currency,1);
}else{
    $to_be_paid         =   floatval( get_post_meta($post->ID, 'total_price', true) );
    $to_be_paid_show    =   wpestate_show_price_booking ( $to_be_paid ,$wpestate_currency,$wpestate_where_currency,1);
}

if($invoice_no== 0){
    $invoice_no='-';
}
$price_per_booking         =   wpestate_show_price_booking($booking_array['total_price'],$wpestate_currency,$wpestate_where_currency,1);

?>


<div class="col-md-12 dasboard-prop-listing">


  <div class="col-md-6 blog_listing_image my_bookings_image">
    <?php   include(locate_template('dashboard/templates/unit-templates/booking_image.php') );  ?>
    <?php   include(locate_template('dashboard/templates/unit-templates/booking_title_section.php') );  ?>
  </div>

  <div class="col-md-2 booking_unit_status">
      <?php   include(locate_template('dashboard/templates/unit-templates/booking_status.php') );  ?>
  </div>

  <div class="col-md-2 booking_unit_period">
          <?php   include(locate_template('dashboard/templates/unit-templates/booking_period.php') );  ?>
  </div>

  <div class="col-md-2 booking_unit_owner">
          <?php   include(locate_template('dashboard/templates/unit-templates/booking_owner.php') );  ?>
  </div>


    <div class="info-container_booking book_listing_user_confirmed">
        <?php
        if ($booking_status=='confirmed'){

            if( $author!= $user_login ){
                print '<span class="confirmed_booking" data-invoice-confirmed="'.esc_attr($invoice_no).'" data-booking-confirmed="'.esc_attr($post->ID).'">'.esc_html__( 'View Details','wprentals').'</span>';
                print '<span class="cancel_user_booking" data-listing-id="'.esc_attr($booking_id).'"  data-booking-confirmed="'.esc_attr($post->ID).'">'.esc_html__( 'Cancel booking','wprentals').'</span>';
            }else{
                print '<span class="cancel_own_booking" data-listing-id="'.esc_attr($booking_id).'"  data-booking-confirmed="'.esc_attr($post->ID).'">'.esc_html__( 'Cancel my own booking','wprentals').'</span>';
            }

        }else if( $booking_status=='waiting'){
            print '<span class="delete_invoice" data-invoiceid="'.esc_attr($invoice_no).'" data-bookid="'.esc_attR($post->ID).'">'.esc_html__( 'Delete Invoice','wprentals').'</span>';
            print '<span class="delete_booking" data-bookid="'.esc_attr($post->ID).'">'.esc_html__( 'Reject Booking Request','wprentals').'</span>';
        }else{
            print '<span class="generate_invoice" data-bookid="'.esc_attr($post->ID).'">'.esc_html__( 'Issue invoice','wprentals').'</span>';
            print '<span class="delete_booking" data-bookid="'.esc_attr($post->ID).'">'.esc_html__( 'Reject Booking Request','wprentals').'</span>';
        }


        if($to_be_paid>0 && $booking_status_full!='confirmed') { ?>
          <span class="full_invoice_reminder" data-invoiceid="<?php print esc_attr($invoice_no); ?>" data-bookid="<?php print esc_attr($post->ID);?>"><?php esc_html_e('Send reminder email!','wprentals');?></span>
        <?php }


        ?>

    </div>

 </div>
