<?php
global $feature_list_array;
global $edit_id;
global $moving_array;
global $property_icalendar_import;
global $property_icalendar_import_multi;
global $submission_page_fields;

    $reservation_array                  =   get_post_meta($edit_id, 'booking_dates',true);
    $property_icalendar_import_multi    =   get_post_meta($edit_id, 'property_icalendar_import_multi', true);

    //update from singular feed to multifeed
    if($property_icalendar_import!=''){
        $tmp_feed_array=array();
        $all_feeds=array();
        $tmp_feed_array['feed'] =   esc_url_raw($value);
        $tmp_feed_array['name'] =   esc_html($_POST['array_labels'][$key]);
        $all_feeds[]            =   $tmp_feed_array;
        update_post_meta($edit_id, 'property_icalendar_import_multi',$all_feeds);
        update_post_meta($edit_id, 'property_icalendar_import','');
        wpestate_clear_ical_imported($edit_id);
        wpestate_import_calendar_feed_listing_global($edit_id);
    }
      $wprentals_is_per_hour  =   wprentals_return_booking_type($edit_id);

?>




<div class="col-md-12">

    <h4 class="user_dashboard_panel_title"><?php  esc_html_e('When is your listing available','wprentals');?></h4>

        <?php
        print'<div class="col-md-12" id="profile_message"></div>';
        if($wprentals_is_per_hour==2 ){
            print '<div id="all-front-calendars_per_hour_internal"></div>';
        }else{ ?>
            <div class="price_explaning"> <?php   esc_html_e('*Click to select the period you wish to mark as booked for visitors.','wprentals');?></div>
            <div class="col-md-12" id="profile_message"></div>

            <div class="booking-calendar-wrapper-in-wrapper booking-calendar-set">

                <?php
                    $reservation_array  = get_post_meta($edit_id, 'booking_dates',true  );
                    if(!is_array($reservation_array)){
                        $reservation_array=array();
                    }
                    wpestate_get_calendar_custom2 ($reservation_array,true,true);
                ?>


                <div id="calendar-prev-internal-set" class="internal-calendar-left"><i class="fas fa-chevron-left"></i></div>
                <div id="calendar-next-internal-set" class="internal-calendar-right"><i class="fas fa-chevron-right"></i></div>
                <div style="clear: both;"></div>
            </div>

            <div class="col-md-12 calendar-actions">
                <div class="calendar-legend-today"></div><span><?php  esc_html_e('Today','wprentals');?></span>
                <div class="calendar-legend-reserved"></div><span><?php esc_html_e('Dates Booked','wprentals');?></span>
            </div>

        <?php } ?>


        <h4 class="user_dashboard_panel_title"><?php esc_html_e('Import/Export iCalendar feeds','wprentals'); ?> </h4>
        <div class="export_ical">
        <strong> <?php esc_html_e('This is the listing iCalendar feed to export','wprentals'); ?> </strong>

        <?php
        $unique_code_ical = get_post_meta($edit_id, 'unique_code_ica',true  );
        if($unique_code_ical==''){
            $unique_code_ical= md5(uniqid(mt_rand(), true));
            update_post_meta($edit_id, 'unique_code_ica', $unique_code_ical);
        }

        $icalendar_feed=wpestate_get_template_link('ical.php');
        $icalendar_feed =  esc_url_raw ( add_query_arg( 'ical', $unique_code_ical, $icalendar_feed) ) ;
        print ': '. $icalendar_feed;

        ?>
        </div>

        <div class="import_ical">
             <div  id="profile_message2"></div>

            <div id="icalfeed_wrapper">
                <?php

                if(is_array($property_icalendar_import_multi)){
                    foreach($property_icalendar_import_multi as $key=>$feed_data){
                        print '
                        <div class="icalfeed">
                            <input type="text" class="form-control property_icalendar_import_name_new" size="40" width="200"  name="property_icalendar_import_name[]" value="'.esc_attr($feed_data['name']).'">
                            <input type="text"  class="form-control property_icalendar_import_feed_new" size="40" width="200" name="property_icalendar_import_feed[]" value="'.esc_attr($feed_data['feed']).'">
                            <span class="delete_imported_dates_singular" data-edit-id="'.esc_attr($edit_id).'" data-edit-id-key="'.esc_attr($key).'">'.esc_html__('delete imported dates','wprentals').'</span>
                        </div>';
                    }

                }else{
                    esc_html_e('There are no calendar feeds','wprentals');
                }

                ?>
            </div>

             <div class="icalfeed">
                <label for="property_icalendar_import"><?php esc_html_e('iCalendar import feeds (feed will be read every 3 hours and when you hit save)','wprentals');?></label>
                <input type="text" class="form-control property_icalendar_import_name_new" size="40" width="200"  id="property_icalendar_import_name_new" name="property_icalendar_import_name_new" value="" placeholder=" <?php esc_html_e('feed name','wprentals');?> ">
                <input type="text"  class="form-control property_icalendar_import_feed_new" size="40" width="200" id="property_icalendar_import_feed_new" name="property_icalendar_import_feed_new" value="" placeholder=" <?php esc_html_e('feed url','wprentals');?>">
                <span id="add_extra_feed"><?php esc_html_e('Add new feed','wprentals');?></span>

            </div>


        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_calendar" value="<?php esc_html_e('Save', 'wprentals') ?>">
        <?php
        $ajax_nonce = wp_create_nonce( "wprentals_edit_calendar_nonce" );
        print'<input type="hidden" id="wprentals_edit_calendar_nonce" value="'.esc_html($ajax_nonce).'" />    ';

        ?>

        </div>

    <div class="col-md-12" style="display: inline-block;">
        <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">
    </div>
</div>



 <!-- Modal -->
<div class="modal fade" id="owner_reservation_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
              <button type="button" id="close_reservation_internal" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h2 class="modal-title_big"><?php esc_html_e('Reserve a period','wprentals');?></h2>
              <h4 class="modal-title" id="myModalLabel"><?php esc_html_e('Mark dates as booked.','wprentals');?></h4>
            </div>

            <div class="modal-body">

                <div id="booking_form_request_mess_modal"></div>

                <label for="start_date_owner_book"><?php esc_html_e('Check-In','wprentals');?></label>
                <input type="text" id="start_date_owner_book" size="40" name="booking_from_date" class="form-control" value="" readonly>

                <label for="end_date_owner_book"><?php  esc_html_e('Check-Out','wprentals');?></label>
                <input type="text" id="end_date_owner_book" size="40" name="booking_to_date" class="form-control" value="" readonly>

                <?php
                $booking_type           =   wprentals_return_booking_type($edit_id);
                if($booking_type==2 ){
                ?>
                   <label for=""><?php  esc_html_e('Select Start and End Hours','wprentals');?></label>
                    <select id="start_date_owner_book_hour">
                        <option value=""><?php  esc_html_e('Start Hour','wprentals');?></option>
                        <?php
                             $i=0;

                             while($i<24):
                                 $i++;
                                 if( $i<10 ){
                                     $value = '0'.$i.':00';
                                 }else{
                                     $value = $i.':00';
                                 }

                                 print '<option value="'.esc_attr($value).'">'.esc_html($value).'</option>';
                             endwhile;
                        ?>
                    </select>

                    <select id="end_date_owner_book_hour">
                        <option value=""><?php  esc_html_e('End Hour','wprentals');?></option>
                        <?php
                             $i=0;
                             while($i<24):

                                 $i++;
                                 if( $i<10 ){
                                     $value = '0'.$i.':00';
                                 }else{
                                     $value = $i.':00';
                                 }
                                 print '<option value="'.esc_attr($value).'">'.esc_html($value).'</option>';
                             endwhile;
                        ?>
                    </select>
                <?php
                }
                ?>


                <input type="hidden" id="property_id" name="property_id" value="" />
                <input name="prop_id" type="hidden"  id="agent_property_id" value="">


                <p class="full_form">
                    <label for="coment"><?php esc_html_e('Your notes','wprentals');?></label>
                    <textarea id="book_notes" name="booking_mes_mess" cols="50" rows="6" class="form-control"></textarea>
                </p>
                <button type="submit" id="book_dates" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button"><?php esc_html_e('Book Period','wprentals');?></button>
                <?php
                $ajax_nonce = wp_create_nonce( "wprentals_add_booking_nonce" );
                print'<input type="hidden" id="wprentals_add_booking" value="'.esc_html($ajax_nonce).'" />    ';
                ?>
            </div><!-- /.modal-body -->


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<?php
global $start_reservation;
global $end_reservation;
global $reservation_class;

$start_reservation  =   '' ;
$end_reservation    =   '';
$reservation_class  =   '';

    function wpestate_get_calendar_custom2($reservation_array,$initial = true, $echo = true) {
        global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
        $daywithpost =array();
        // week_begins = 0 stands for Sunday


        $time_now  = current_time('timestamp');
        $now=date('Y-m-d');
        $date = new DateTime();

        $thismonth  = gmdate('m', $time_now);
        $thisyear   = gmdate('Y', $time_now);
        $unixmonth  = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
        $last_day   = date('t', $unixmonth);

        $max_month_no   =   intval   ( wprentals_get_option('wp_estate_month_no_show','') );
        $month_no       =   1;
        while ($month_no < $max_month_no){

            wpestate_draw_month($month_no,$reservation_array, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day);

            $date->modify( 'first day of next month' );
            $thismonth=$date->format( 'm' );
            $thisyear  = $date->format( 'Y' );
            $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
            $month_no++;
        }

    }



    function    wpestate_draw_month($month_no,$reservation_array, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day){
            global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
            global $start_reservation;
            global $end_reservation;
            global $reservation_class;
            $week_begins = intval(get_option('start_of_week'));


            $initial=true;
            $echo=true;

            $table_style='';
            if( $month_no>1 ){
                   $table_style='style="display:none;"';
            }


            $calendar_output = '<div class="booking-calendar-wrapper-in col-md-12" data-mno="'.esc_attr($month_no).'" '.trim($table_style).'>
                <div class="month-title"> '. date_i18n("F", mktime(0, 0, 0, $thismonth, 10)).' '.esc_html($thisyear).' </div>
                <table class="wp-calendar booking-calendar">

            <thead>
            <tr>';

            $myweek = array();

            for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
                    $myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
            }

            foreach ( $myweek as $wd ) {
                    $day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
                    $wd = esc_attr($wd);
                    $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
            }

            $calendar_output .= '
            </tr>
            </thead>

            <tfoot>
            <tr>';

            $calendar_output .= '
            </tr>
            </tfoot>
            <tbody>
            <tr>';


            // See how much we should pad in the beginning
            $pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
            if ( 0 != $pad )
                    $calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

            $daysinmonth = intval(date('t', $unixmonth));
            for ( $day = 1; $day <= $daysinmonth; ++$day ) {
                    $timestamp = strtotime( $day.'-'.$thismonth.'-'.$thisyear).' | ';
                    $timestamp_java = strtotime( $day.'-'.$thismonth.'-'.$thisyear);
                    if ( isset($newrow) && $newrow ){
                        $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
                    }

                    $newrow = false;
                    $has_past_class='';
                    if($timestamp_java < (time()-24*60*60)  ){
                        $has_past_class="has_past";
                    }else{
                        $has_past_class="has_future";
                    }
                    $is_reserved=0;
                    $reservation_class='';

                    if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) ){
                        // if is today check for reservation
                        if(array_key_exists ($timestamp_java,$reservation_array) ){
                          $reservation_class  =   ' start_reservation';
                          $start_reservation  =   0;$end_reservation    =   1;

                            $calendar_output .= '<td class="calendar-reserved '.esc_attr($has_past_class).' '.esc_attr($reservation_class).'"     data-curent-date="'.esc_attr($timestamp_java).'">'. wpestate_draw_reservation($reservation_array[$timestamp_java]);
                        }else{
                            $calendar_output .= '<td class="calendar-today '.esc_attr($has_past_class).' "        data-curent-date="'.esc_attr($timestamp_java).'">';
                        }

                    }
                    else if(array_key_exists ($timestamp_java,$reservation_array) ){ // check for reservation
                        $end_reservation=1;

                        if($start_reservation == 1){
                            $reservation_class  =   ' start_reservation';
                            $start_reservation  =   0;
                        }


                        $calendar_output .= '<td class="calendar-reserved '.esc_attr($has_past_class.$reservation_class).' "     data-curent-date="'.esc_attr($timestamp_java).'">'. wpestate_draw_reservation($reservation_array[$timestamp_java]);
                    }
                    else{// is not today and no resrvation

                        $start_reservation=1;
                        if($end_reservation===1){
                            $reservation_class=' end_reservation ';
                            $end_reservation=0;
                        }

                        $calendar_output .= '<td class="calendar-free '.esc_attr($has_past_class.$reservation_class).'"          data-curent-date="'.esc_attr($timestamp_java).'">';
                    }


                    if ( in_array($day, $daywithpost) ) // any posts today?
                        $calendar_output .= '<a href="' .esc_url( get_day_link( $thisyear, $thismonth, $day )) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) . "\">$day</a>";
                    else
                        $calendar_output .= $day;
                    $calendar_output .= '</td>';

                    if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
                            $newrow = true;
            }

            $pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
            if ( $pad != 0 && $pad != 7 )
                    $calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';
            $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table></div>";

            if ( $echo ){
                echo apply_filters( 'get_calendar',  $calendar_output );
            }else{
                return apply_filters( 'get_calendar',  $calendar_output );
            }
    }



function wpestate_draw_reservation($reservation_note){
    if ( is_numeric($reservation_note)!=0){
        if($reservation_note>1505291773 ){
            return '<div class="rentals_reservation" >'.esc_html__('End Hour','wprentals').': '.esc_html($reservation_note).'/'.date('Y-m-d H:i',$reservation_note).'</div>';
        }else{
            return '<div class="rentals_reservation" >'.esc_html__('Booking id','wprentals').': '.esc_html($reservation_note).'</div>';
        }
    }else{
        return '<div class="rentals_reservation external_reservation">'.esc_html($reservation_note).'</div>';

    }

}




if (wp_script_is( 'wpestate_dashboard-control', 'enqueued' )) {
    $booking_array   =   json_encode(get_post_meta($edit_id, 'booking_dates',true  ));
    wp_localize_script('wpestate_dashboard-control', 'dashboard_vars2',
        array('booking_array'          =>  $booking_array));
}
