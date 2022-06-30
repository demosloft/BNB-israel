<?php  if($to_be_paid>0 && $booking_status_full!='confirmed') { ?>
    <div class="user_dashboard_listed book_listing_user_unit_balance" >
       <span class="booking_details_title"><?php esc_html_e('Balance: ','wprentals');?> </span> <?php print esc_html($to_be_paid_show).' '.__('to be paid until ','wprentals').' '.wpestate_convert_dateformat_reverse($booking_from_date); ?>
    </div>
<?php } ?>
