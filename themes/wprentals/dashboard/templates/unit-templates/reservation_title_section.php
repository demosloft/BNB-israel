<?php

if ( $booking_status=='confirmed'){
    $total_price        =   floatval( get_post_meta($post->ID, 'total_price', true) );
    $to_be_paid         =   floatval( get_post_meta($post->ID, 'to_be_paid', true) );
    $to_be_paid         =   $total_price-$to_be_paid;
    $to_be_paid_show    =   wpestate_show_price_booking ( $to_be_paid ,$wpestate_currency,$wpestate_where_currency,1);
}else{
    $to_be_paid         =   floatval( get_post_meta($post->ID, 'total_price', true) );
    $to_be_paid_show    =   wpestate_show_price_booking ( $to_be_paid ,$wpestate_currency,$wpestate_where_currency,1);
}

?>

<div class="prop-info">
    <h4 class="listing_title_book book_listing_user_unit_title">
        <?php
        echo esc_html__('Booking request','wprentals').' '.$post->ID;
        print ' <strong>'. esc_html__( 'for','wprentals').'</strong> <a href="'.esc_url ( get_permalink($booking_id)).'">'.get_the_title($booking_id).'</a>';
        ?>
    </h4>

    <div class="user_dashboard_listed book_listing_user_unit_invoice">
        <strong><?php esc_html_e('Invoice No: ','wprentals');?></strong> <span class="invoice_list_id"><?php print esc_html($invoice_no);?></span>
    </div>

    <div class="user_dashboard_listed book_listing_user_unit_guests">
        <?php if ($booking_guests!=0){?>
        <strong><?php esc_html_e('Guests: ','wprentals');?> </strong> <?php print esc_html($booking_guests).wpestate_booking_guest_explanations($post->ID); ?>
        <?php } ?>
    </div>

    <?php
    include(locate_template('dashboard/templates/unit-templates/balance_display.php') );
    ?>
</div>
