<?php
$booking_from_date  =  wpestate_convert_dateformat_reverse($booking_from_date);
$booking_to_date    =  wpestate_convert_dateformat_reverse($booking_to_date);
print esc_html($booking_from_date).' <strong>'.esc_html__( 'to','wprentals').'</strong> '.esc_html($booking_to_date);
?>
