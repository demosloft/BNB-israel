<?php 
$the_id = get_the_ID();
?>

<div class="col-md-12 invoice_unit " data-booking-confirmed="<?php echo esc_attr(get_post_meta($the_id, 'item_id', true));?>" data-invoice-confirmed="<?php print intval($the_id); ?>">
    <div class="col-md-2 invoice_unit_title_wrapper">
         <?php echo get_the_title(); ?>
    </div>

    <div class="col-md-2">
        <?php echo get_the_date(); ?>
    </div>

    <div class="col-md-2">
        <?php
         $string= esc_html(get_post_meta($the_id, 'invoice_type', true));

        if($reservation_strings[ $string]!=''){
           print esc_html( $reservation_strings[ $string] );
        }else{
            print esc_html ($string );
        }
        ?>
    </div>

    <div class="col-md-2">
        <?php echo esc_html(get_post_meta($the_id, 'biling_type', true));?>
    </div>

    <div class="col-md-2">
        <?php
        $booking_status         =  esc_html(get_post_meta($the_id, 'invoice_status', true));
        $booking_status_full    = esc_html(get_post_meta($the_id, 'invoice_status_full', true));

        if($booking_status == 'canceled' && $booking_status_full== 'canceled'){
            esc_html_e('canceled','wprentals');
        }else if($booking_status == 'confirmed' && $booking_status_full== 'confirmed'){
            echo    esc_html__('confirmed','wprentals').' | ' .esc_html__('fully paid','wprentals');
        }else if($booking_status == 'confirmed' && $booking_status_full== 'waiting'){
            echo    esc_html__('deposit paid','wprentals').' | ' .esc_html__('waiting for full payment','wprentals');
        }else if($booking_status == 'refunded' ){
            esc_html_e('refunded','wprentals');
        }else if($booking_status == 'pending' ){
            esc_html_e('pending','wprentals');
        }else if($booking_status == 'waiting' ){
            esc_html_e('waiting','wprentals');
        }else if($booking_status == 'issued' ){
            esc_html_e('issued','wprentals');
        }else if($booking_status == 'confirmed' ){
            esc_html_e('confirmed','wprentals');
        }else if( trim($booking_status)=='confirmed/ booking canceled by user'){
            esc_html_e('confirmed/ booking canceled by user','wprentals');
        }
        ?>
    </div>

    <div class="col-md-2">
        <?php
        $price = get_post_meta($the_id, 'item_price', true);
        $wpestate_currency                   =   esc_html( get_post_meta($the_id, 'invoice_currency',true) );
        echo wpestate_show_price_booking_for_invoice($price,$wpestate_currency,$wpestate_where_currency,0,1) ?>
    </div>
</div>
