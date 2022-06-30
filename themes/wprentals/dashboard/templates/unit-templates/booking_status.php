<?php

if ($booking_status=='confirmed'){
    if($booking_status_full=="confirmed"){
       print '<span class="wprentals_status_circle booking_confirmed_full_paid"></span><span class="tag-published booking_confirmed_full_paid">'.esc_html__( 'Confirmed & Fully Paid','wprentals').'</span>';
    }else{
        print '<span class="wprentals_status_circle booking_confirmed_full_paid"></span><span class="tag-published booking_confirmed_not_full_paid">'.esc_html__( 'Confirmed / Not Fully Paid','wprentals').'</span>';
    }
}else if( $booking_status=='waiting'){
  print '<span class="wprentals_status_circle waiting_payment_status"></span><span class="waiting_payment_status" data-bookid="'.esc_attr($post->ID).'">'.esc_html__( 'Invoice Issued ','wprentals').'</span>';
}else if( $booking_status=='pending' ||  $booking_status=='request' ){
  print '<span class="wprentals_status_circle waiting_payment_status_pending"></span><span class="waiting_payment_status_pending" >'.esc_html__( 'Pending','wprentals').'</span>';
}
?>
