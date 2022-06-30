<?php

global $start_reservation;
global $end_reservation;
global $reservation_class;

$start_reservation  =   '' ;
$end_reservation    =   '';
$reservation_class  =   '';




global $post;

$booking_type           =   wprentals_return_booking_type($post->ID);
print '<h3 class="panel-title" id="listing_calendar">'.esc_html__( 'Availability', 'wprentals').'</h3>';

if($booking_type==2 ){
    print '<div id="all-front-calendars_per_hour"></div>';
}else{
?>

<div class="all-front-calendars">


    <div id="calendar-next"><i class="fas fa-chevron-right"></i></div>
    <div id="calendar-prev"><i class="fas fa-chevron-left"></i></div>
    <div class="separator"></div>
    <?php
    $reservation_array  = get_post_meta($post->ID, 'booking_dates',true  );

    if(!is_array($reservation_array)){
        $reservation_array=array();
    }
    wpestate_get_calendar_custom_avalability ($reservation_array,true,true);
    ?>

    <div class="calendar-legend">
        <div class="calendar-legend-past"></div> <span> <?php esc_html_e('past','wprentals')?></span>
        <div class="calendar-legend-today"></div> <span> <?php esc_html_e('today','wprentals')?></span>
        <div class="calendar-legend-reserved"></div> <span> <?php esc_html_e('booked','wprentals')?></span>
    </div>
</div>
<?php
}

