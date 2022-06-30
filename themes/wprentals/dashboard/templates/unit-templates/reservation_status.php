<?php

if ($booking_status=='confirmed'){
  if($booking_status_full=='confirmed'){
      print '<span class="wprentals_status_circle booking_confirmed_full_paid"></span><span class="tag-published booking_confirmed_full_paid">'.esc_html__( 'Confirmed & Paid','wprentals').'</span>';
  }else{
      print '<span class="wprentals_status_circle booking_confirmed_full_paid"></span><span class="tag-published booking_confirmed_not_full_paid">'.esc_html__( 'Confirmed','wprentals').'</span>';
  }
}else if( $booking_status=='waiting'){
  print '<span class="wprentals_status_circle waiting_payment_status"></span><span class="waiting_payment_status">'.esc_html__( 'Waiting','wprentals').'</span>';
}else{
  print '<span class="wprentals_status_circle waiting_payment_status_pending"></span><span class="waiting_payment_status_pending" >'.esc_html__( 'Request Pending','wprentals').'</span>';
}


?>
