<?php
global $property_price;
global $property_label;
global $cleaning_fee;
global $city_fee;
global $property_price_week;
global $property_price_month;
global $edit_id;
global $cleaning_fee_per_day;
global $city_fee_per_day;
global $min_days_booking;
global $extra_price_per_guest;
global $price_per_guest_from_one;
global $overload_guest;
global $price_per_weekeend;
global $checkin_change_over;
global $checkin_checkout_change_over;
global $edit_link_images;
global $city_fee_percent;
global $security_deposit;
global $property_price_after_label;
global $property_price_before_label;
global $extra_pay_options;
global $early_bird_percent;
global $early_bird_days;
global $property_taxes;
global $submission_page_fields;
global $local_booking_type;
global $booking_start_hour;
global $booking_end_hour;

$week_days=array(
    '0'=>esc_html__('All','wprentals'),
    '1'=>esc_html__('Monday','wprentals'),
    '2'=>esc_html__('Tuesday','wprentals'),
    '3'=>esc_html__('Wednesday','wprentals'),
    '4'=>esc_html__('Thursday','wprentals'),
    '5'=>esc_html__('Friday','wprentals'),
    '6'=>esc_html__('Saturday','wprentals'),
    '7'=>esc_html__('Sunday','wprentals')

    );
$wp_estate_currency_symbol = esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$setup_weekend_status= esc_html ( wprentals_get_option('wp_estate_setup_weekend','') );
$weekedn = array(
        0 => __("Sunday and Saturday","wprentals"),
        1 => __("Friday and Saturday","wprentals"),
        2 => __("Friday, Saturday and Sunday","wprentals")
        );


if( !function_exists('wpestate_dropdown_fee_select')):
    function wpestate_dropdown_fee_select($name, $selected,$rental_type,$booking_type){

    $options_array=array(
            0   =>  esc_html__('Single Fee','wprentals'),
            1   =>  ucfirst( wpestate_show_labels('per_night',$rental_type,$booking_type) ),
            2   =>  esc_html__('Per Guest','wprentals'),
            3   =>  ucfirst( wpestate_show_labels('per_night',$rental_type,$booking_type)).' '.esc_html__('per Guest','wprentals')
        );


        $return='<select class="select_submit_price" name="'.esc_attr($name).'" id="'.esc_attr($name).'" >';

        foreach($options_array as $key=>$option){
            $return.='<option value="'.esc_attr($key).'"';
            if($key==$selected){
            $return.=' selected ';
            }
            $return.='>'.esc_html($option).'</option>';
        }

        $return.='</select>';

        return $return;

    }
endif;

if( !function_exists('wpestate_get_calendar_price')):
    function wpestate_get_calendar_price($edit_id,$property_price,$price_per_guest_from_one,$extra_price_per_guest,$custom_price_array,$mega_details,$initial = true, $echo = true) {
        global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
        $daywithpost =array();
        // week_begins = 0 stands for Sunday

        $time_now  = current_time('timestamp');
        $now=date('Y-m-d');
        $date = new DateTime();

        $thismonth = gmdate('m', $time_now);
        $thisyear  = gmdate('Y', $time_now);

        $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
        $last_day = date('t', $unixmonth);

        $max_month_no   =   intval   ( wprentals_get_option('wp_estate_month_no_show','') );
        $month_no       =   1;
            while ($month_no<$max_month_no){

                wpestate_draw_month_price($edit_id,$property_price,$price_per_guest_from_one,$extra_price_per_guest,$month_no,$custom_price_array,$mega_details, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day);

                $date->modify( 'first day of next month' );
                $thismonth=$date->format( 'm' );
                $thisyear  = $date->format( 'Y' );
                $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
                $month_no++;
            }

    }
endif;


if( !function_exists('wpestate_draw_month_price')):
    function    wpestate_draw_month_price($edit_id,$property_price,$price_per_guest_from_one,$extra_price_per_guest,$month_no,$custom_price_array,$mega_details, $unixmonth, $daywithpost,$thismonth,$thisyear,$last_day){
        global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
        $setup_weekend_status= esc_html ( wprentals_get_option('wp_estate_setup_weekend','') );
        if(!is_array($mega_details)){
            $mega_details=array();
        }

        $week_begins = intval(get_option('start_of_week'));
        $initial=true;
        $echo=true;

        $table_style='';
        if( $month_no>2 ){
            $table_style='style="display:none;"';
        }

        $calendar_output = '<div class="booking-calendar-wrapper-in-price booking-price col-md-6" data-mno="'.esc_attr($month_no).'" '.trim($table_style).'>
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
            $has_past_class='has_future';
            $is_reserved=0;


            $calendar_output .= '<td class="calendar-free '.esc_attr($has_past_class).'" data-curent-date="'.esc_attr($timestamp_java).'">';
            $calendar_output .= '<span class="day-label">'.esc_html($day).'</span>';


            $property_price_week            =   floatval   ( get_post_meta($edit_id, 'price_per_weekeend', true) );
            $weekday = date('N', $timestamp_java); // 1-7


            if( $setup_weekend_status ==0 && ($weekday ==6 || $weekday==7) ){
                $calendar_output.=wpestate_draw_weekend_day($timestamp_java,$mega_details,$property_price_week,$custom_price_array,$property_price,$price_per_guest_from_one,$extra_price_per_guest);
            }else if( $setup_weekend_status ==1 && ($weekday ==5 || $weekday==6) ){
                $calendar_output.=wpestate_draw_weekend_day($timestamp_java,$mega_details,$property_price_week,$custom_price_array,$property_price,$price_per_guest_from_one,$extra_price_per_guest);
            }else if($setup_weekend_status ==2 && ($weekday ==5 || $weekday ==6 || $weekday==7)){
                $calendar_output.=wpestate_draw_weekend_day($timestamp_java,$mega_details,$property_price_week,$custom_price_array,$property_price,$price_per_guest_from_one,$extra_price_per_guest);
            }else{
               // days during the week
                if( array_key_exists  ($timestamp_java,$custom_price_array) ){
                    // custom price
                    $calendar_output .= '<span class="custom_set_price">'.wpestate_show_price_custom (wpestate_display_price_simple($custom_price_array[$timestamp_java],$price_per_guest_from_one,$extra_price_per_guest)   ).'</span>';
                }else if( array_key_exists  ($timestamp_java,$mega_details) ){
                    // custom price
                    $extra_price_per_guest_custom=$extra_price_per_guest;
                    if(isset($mega_details[$timestamp_java]['period_extra_price_per_guest'])){
                      $extra_price_per_guest_custom=  $mega_details[$timestamp_java]['period_extra_price_per_guest'];
                    }
                    $calendar_output .= '<span class="custom_set_price">'.wpestate_show_price_custom ( wpestate_display_price_simple( $property_price,$price_per_guest_from_one,$extra_price_per_guest_custom )  ).'</span>';
                }else{
                    // default price
                    $calendar_output .= '<span class="price-day">'.wpestate_show_price_custom ( wpestate_display_price_simple($property_price,$price_per_guest_from_one,$extra_price_per_guest) ).'</span>';
                }

            }



            $calendar_output .='</td>';
            if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
                $newrow = true;
            }

            $pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
            if ( $pad != 0 && $pad != 7 ){
                $calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';
            }
            $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table></div>";

            if ( $echo ){
                echo apply_filters( 'get_calendar',  $calendar_output );
            }else{
                return apply_filters( 'get_calendar',  $calendar_output );
            }

    }
endif;




if( !function_exists('wpestate_display_price_simple')):
    function wpestate_display_price_simple($property_price,$price_per_guest_from_one,$extra_price_per_guest){

    if(trim($price_per_guest_from_one) == 'checked'){
        return $extra_price_per_guest;
    }else{
        return $property_price;
    }
}
endif;



if( !function_exists('wpestate_draw_weekend_day')):
    function wpestate_draw_weekend_day($timestamp_java,$mega_details,$property_price_week,$custom_price_array,$property_price,$price_per_guest_from_one,$extra_price_per_guest){
    $calendar_output='';
    // WEEKEND days
    if(( array_key_exists ($timestamp_java,$mega_details)) ){
        // we have custom price per weekend
        if(floatval( $mega_details[$timestamp_java]['period_price_per_weekeend']) !=0){
           $property_price_week_custom=  $mega_details[$timestamp_java]['period_price_per_weekeend'];
        }else{
            $property_price_week_custom=$property_price_week;
        }

        if(isset( $mega_details[$timestamp_java]['period_extra_price_per_guest'])){

            $extra_price_per_guest_custom= $mega_details[$timestamp_java]['period_extra_price_per_guest'];
        }else{
            $extra_price_per_guest_custom = $extra_price_per_guest;
        }

        $calendar_output .= '<span class="custom_set_price weekend_set_price">'.wpestate_show_price_custom (  wpestate_display_price_simple($property_price_week_custom  ,$price_per_guest_from_one,$extra_price_per_guest_custom) ).'</span>';
    }else if( $property_price_week!=0 ){
        // we have general price per weekend
        $calendar_output .= '<span class="custom_set_price weekend_set_price">'.wpestate_show_price_custom (  wpestate_display_price_simple($property_price_week ,$price_per_guest_from_one,$extra_price_per_guest) ).'</span>';
    }else if(( array_key_exists ($timestamp_java,$custom_price_array) && floatval( $custom_price_array[$timestamp_java]) !=0) ){
        $calendar_output .= '<span class="custom_set_price weekend_set_price">'. wpestate_display_price_simple($custom_price_array[$timestamp_java] ,$price_per_guest_from_one,$extra_price_per_guest) .'</span>';
    }else{
        // no weekedn price
        $calendar_output .= '<span class="price-day">';
        if( array_key_exists  ($timestamp_java,$custom_price_array) ){
            $calendar_output .= wpestate_show_price_custom (  wpestate_display_price_simple($custom_price_array[$timestamp_java] ,$price_per_guest_from_one,$extra_price_per_guest) );
        }else{
            $calendar_output .= wpestate_show_price_custom (  wpestate_display_price_simple($property_price,$price_per_guest_from_one,$extra_price_per_guest) );
        }

        $calendar_output .= '</span>';
    }
    return $calendar_output;
}
endif;
//price_panel
?>





<div class="col-md-12">

    <h4 class="user_dashboard_panel_title"><?php esc_html_e('Listing Price','wprentals');?></h4>
      <?php wpestate_show_mandatory_fields();?>
      <div id="profile_message"></div>
    <div class="row">
    <?php
    $rental_type            =   esc_html(wprentals_get_option('wp_estate_item_rental_type', ''));
    $booking_type           =   wprentals_return_booking_type($edit_id);
    $global_type            =   wprentals_get_option('wp_estate_booking_type','');
    $service_fee_fixed_fee  =   floatval ( wprentals_get_option('wp_estate_service_fee_fixed_fee','') );
    $service_fee            =   floatval ( wprentals_get_option('wp_estate_service_fee','') );




    if($service_fee_fixed_fee!=0 || $service_fee!=0){
    ?>


        <div class="col-md-12 price_notification">
        <?php
            if($service_fee_fixed_fee!=0){
                $tax= $service_fee_fixed_fee.' '.esc_html($wp_estate_currency_symbol).' ';
            }else{
                $tax= $service_fee.'% ';
            }
            printf( esc_html__('There is a %s service fee that will be deducted from you earnings (earnings = total cost of the room (without security deposit, cleaning fee or city fee) + extra options)','wprentals'),$tax);
        ?>
        </div>

    <?php
    }
    ?>


    <?php
    if($global_type==3){//mixt
    ?>


    <div class="col-md-12">
           <div class="col-md-3 dashboard_chapter_label">
                <label  class="label_adjust" for="property_price">
                    <?php esc_html_e('Booking Type ','wprentals');?>
                 </label>
            </div>
            <div class="col-md-3">
                <label  class="label_adjust" for="property_price">
                    <?php
                    esc_html_e('Booking Type','wprentals');
                    ?>
                </label>

                <select id="local_booking_type" name="local_booking_type" class="select-submit2 select_submit_price">
                   <?php
                    $booking = array(
                    1 => __("Per Day/Night","wprentals"),
                    2 => __("Per Hour","wprentals"),

                    );

                    foreach($booking as $key=>$value){
                        print '   <option value="'.esc_attr($key).'"';
                        if( $key==$local_booking_type){
                            print ' selected="selected" ';
                        }
                        print '>'.esc_html($value).'</option>';
                    }
                   ?>

                </select>

            </div>
            <div class="col-md-3">
             </div>
            <div class="col-md-3">
            </div>
        </div>

    <?php }else{?>
    <input type="hidden" id="local_booking_type" name="local_booking_type" value="0">
    <?php }


    if(is_array($submission_page_fields) && in_array('property_price', $submission_page_fields)) {
    ?>

        <div class="col-md-12">
           <div class="col-md-3 dashboard_chapter_label">
                <label  class="label_adjust" for="property_price">
                <?php
                    print wpestate_show_labels('price_label',$rental_type,$booking_type).' ';
                    print esc_html($wp_estate_currency_symbol).' '; esc_html_e('(only numbers)','wprentals');
                ?>
                </label>
            </div>
            <div class="col-md-3">
                <label  class="label_adjust" for="property_price">
                    <?php
                        print wpestate_show_labels('price_label',$rental_type,$booking_type);
                    ?>
                </label>
                <input type="text" id="property_price" class="form-control" size="40" name="property_price" value="<?php print esc_html($property_price);?>">
            </div>
            <div class="col-md-3">
                <label  class="label_adjust" for="property_price_before_label"> <?php esc_html_e('Before Label ','wprentals');?></label>
                <input type="text" id="property_price_before_label" class="form-control" size="40" name="property_price_before_label" value="<?php print esc_html($property_price_before_label);?>">
            </div>
            <div class="col-md-3">
                <label  class="label_adjust" for="property_price_after_label"> <?php esc_html_e('After Label ','wprentals'); ?>  </label>
                <input type="text" id="property_price_after_label" class="form-control" size="40" name="property_price_after_label" value="<?php print esc_html($property_price_after_label);?>">
            </div>
        </div>


    <?php }

    if(is_array($submission_page_fields) && in_array('property_taxes', $submission_page_fields)) {
    ?>

        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label for="property_taxes"> <?php esc_html_e('Taxes in % (taxes are considered included in the price) ','wprentals'); ?>  </label>
            </div>
            <div class="col-md-3">
                <label for="property_taxes"> <?php esc_html_e('Value','wprentals'); ?>  </label>
                <input type="text" id="property_taxes" class="form-control" size="40" name="property_taxes" value="<?php print esc_html($property_taxes);?>">
            </div>
            <div class="col-md-3"> </div>
            <div class="col-md-3"> </div>
        </div>
    <?php } ?>



    <?php
    if(is_array($submission_page_fields) &&
        ( in_array('property_price_per_week', $submission_page_fields) ||
          in_array('property_price_per_month', $submission_page_fields) )
    ) { ?>

    <div class="col-md-12">
        <div class="col-md-3 dashboard_chapter_label">
            <label for="property_price_per_week">
                <?php
                print wpestate_show_labels('price_week_label_ext',$rental_type,$booking_type);
                ?>
            </label>
        </div>

        <?php
        if(is_array($submission_page_fields) && in_array('property_price_per_week', $submission_page_fields)) {
        ?>
            <div class="col-md-3">
                <label for="property_price_per_week">
                    <?php
                    print wpestate_show_labels('price_week_label',$rental_type,$booking_type);
                    ?>
                </label>
                <input type="text" id="property_price_per_week" class="form-control" size="40" name="property_price_per_week" value="<?php print esc_html($property_price_week);?>">
            </div>
        <?php } ?>


        <?php
        if(is_array($submission_page_fields) && in_array('property_price_per_month', $submission_page_fields)) {
        ?>
            <div class="col-md-3">
                <label for="property_price_per_month">
                    <?php
                    print wpestate_show_labels('price_month_label',$rental_type,$booking_type);
                    ?>
                 </label>
                 <input type="text" id="property_price_per_month" class="form-control" size="40" name="property_price_per_month" value="<?php print esc_html($property_price_month);?>">
            </div>
        <?php } ?>

        <div class="col-md-3"> </div>
    </div>

    <?php } ?>



    <?php
    if(is_array($submission_page_fields) && in_array('price_per_weekeend', $submission_page_fields)) {
    ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label  for="price_per_weekeend">
                    <?php
                    esc_html_e('Price per weekend (','wprentals');
                    print esc_html( $weekedn [$setup_weekend_status]);
                    echo esc_html__(') in ' ,'wprentals'). $wp_estate_currency_symbol.' '.esc_html__('(only numbers)','wprentals'); ?>
                </label>
            </div>
            <div class="col-md-3">
                <label for="price_per_weekeend" ><?php esc_html_e('Weekend Price','wprentals'); ?></label>
                <input type="text" id="price_per_weekeend" class="form-control" size="40" name="price_per_weekeend" value="<?php print esc_html($price_per_weekeend);?>">
            </div>
            <div class="col-md-3"> </div>
            <div class="col-md-3"> </div>
        </div>
    <?php } ?>


    
    
    <?php
    if(is_array($submission_page_fields) && in_array('extra_price_per_guest', $submission_page_fields)) {
    ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label>
                    <?php esc_html_e('Extra Guests ','wprentals');?>
                </label>
            </div>
            <div class="col-md-3">
                <label for="extra_price_per_guest">
                    <?php print wpestate_show_labels('extra_price_per_guest',$rental_type,$booking_type);print ' '.esc_html($wp_estate_currency_symbol).' ';  ?>
                </label>
                <input type="text" id="extra_price_per_guest" class="form-control" size="40" name="extra_price_per_guest" value="<?php print esc_html($extra_price_per_guest);?>">
            </div>
           

        </div>
        
    

        <div class="col-md-12">
            <div class="col-md-3"></div>
            <div class="col-md-6 wprentals_pay_by_guest_no">
                    <input  style="float:left;" type="checkbox" class="form-control" value="1"  id="price_per_guest_from_one" name="price_per_guest_from_one" <?php print esc_html($price_per_guest_from_one); ?> >
                    <label style="display:inline;" for="price_per_guest_from_one"><?php esc_html_e('Pay by the no of guests (room prices will NOT be used anymore and billing will be done by guest no only)','wprentals');?></label>
            </div>
        </div>
    <?php } ?>


    
    
    
    
    <?php
    if(is_array($submission_page_fields) && in_array('cleaning_fee', $submission_page_fields)) {
    ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label><?php esc_html_e('Cleaning Fee ','wprentals')?></label>
            </div>
            <div class="col-md-3">
                <label for="cleaning_fee" ><?php esc_html_e('Cleaning Fee in ','wprentals');print esc_html($wp_estate_currency_symbol).' '; esc_html_e('(only numbers)','wprentals'); ?></label>
                <input type="text" id="cleaning_fee" size="40" class="form-control"  name="cleaning_fee" value="<?php print esc_html($cleaning_fee); ?>">
            </div>
            <div class="col-md-3">
                <label style="float:left;" for="cleaning_fee_per_day"><?php esc_html_e('Cleaning Fee calculation','wprentals');?></label>
                <?php  echo wpestate_dropdown_fee_select("cleaning_fee_per_day",$cleaning_fee_per_day,$rental_type,$booking_type); ?>
            </div>
            <div class="col-md-3"> </div>
        </div>
    <?php } ?>


    <?php
    if(is_array($submission_page_fields) && in_array('city_fee', $submission_page_fields)) {
    ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label for="city_fee"><?php esc_html_e('City Fee in ','wprentals');print esc_html($wp_estate_currency_symbol).' '; esc_html_e('(only numbers)','wprentals'); ?></label>
            </div>
            <div class="col-md-3">
                <label for="city_fee"><?php esc_html_e('City Fee in ','wprentals');print esc_html($wp_estate_currency_symbol).' '; esc_html_e('(only numbers)','wprentals'); ?></label>
                <input type="text" id="city_fee" size="40" class="form-control"  name="city_fee" value="<?php print esc_html($city_fee);?>">
            </div>
            <div class="col-md-3">
                 <label style="float:left;min-width:150px;" for="city_fee_per_day"><?php esc_html_e('City Fee calculation','wprentals');?></label>
                <?php  echo wpestate_dropdown_fee_select("city_fee_per_day",$city_fee_per_day,$rental_type,$booking_type); ?>
            </div>
            <div class="col-md-3 city_fee_label">
                <input style="float:left;" type="checkbox" class="form-control" value="1" id="city_fee_percent" name="city_fee_per_day" <?php print esc_html($city_fee_percent); ?> >
                <label style="display: inline;" for="city_fee_percent"><?php esc_html_e('City Fee is a % of the daily fee','wprentals');?></label>
            </div>
        </div>

    <?php }


    if(is_array($submission_page_fields) && in_array('min_days_booking', $submission_page_fields)) {
    ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label for="min_days_booking">
                    <?php print wpestate_show_labels('min_unit',$rental_type,$booking_type); ?>
                </label>
            </div>
            <div class="col-md-3">
                <label for="min_days_booking">
                    <?php print wpestate_show_labels('min_unit',$rental_type,$booking_type); ?>
                </label>
                <input type="text" id="min_days_booking" class="form-control" size="40" name="min_days_booking" value="<?php print esc_html($min_days_booking);?>">
            </div>
            <div class="col-md-3"> </div>
            <div class="col-md-3"> </div>
        </div>
    <?php }


    if(is_array($submission_page_fields) && in_array('security_deposit', $submission_page_fields)) {
    ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label for="security_deposit"> <?php esc_html_e('Security Deposit in ','wprentals');print esc_html($wp_estate_currency_symbol).esc_html__(' - will be refunded if no complaints are received from the owner','wprentals'); ?>  </label>
            </div>
            <div class="col-md-3">
                <label for="security_deposit"> <?php esc_html_e('Security Deposit','wprentals'); ?>  </label>
                <input type="text" id="security_deposit" class="form-control" size="40" name="security_deposit" value="<?php print esc_html($security_deposit);?>">
            </div>
            <div class="col-md-3"> </div>
            <div class="col-md-3"> </div>
        </div>
    <?php } ?>

    <?php
    if(is_array($submission_page_fields) && in_array('early_bird_percent', $submission_page_fields)) {
    ?>
        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label for="">
                    <?php print wpestate_show_labels('early_bird',$rental_type,$booking_type); ?>
                </label>
            </div>
            <div class="col-md-3">
                <label for="early_bird_percent"> <?php esc_html_e('Value in %','wprentals'); ?>  </label>
                <input type="text" id="early_bird_percent" class="form-control" size="40" name="early_bird_percent" value="<?php print esc_html($early_bird_percent);?>">
            </div>
            <div class="col-md-3">
                <label for="early_bird_days"> <?php esc_html_e('Minimum no of days in advance','wprentals'); ?>  </label>
                <input type="text" id="early_bird_days" class="form-control" size="40" name="early_bird_days" value="<?php print esc_html($early_bird_days);?>">
            </div>
            <div class="col-md-3"> </div>
        </div>
    <?php } ?>




    <?php
    if(is_array($submission_page_fields) &&
        ( in_array('checkin_change_over', $submission_page_fields) ||
          in_array('checkin_checkout_change_over', $submission_page_fields) )
    ) { ?>

        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label>
                    <?php
                        if(is_array($submission_page_fields) &&      ( in_array('checkin_change_over', $submission_page_fields) &&        in_array('checkin_checkout_change_over', $submission_page_fields) ) ) {
                            esc_html_e('These options do not work together or on price per hour- choose only one and leave the other one on "All"','wprentals');
                        }else{
                            esc_html_e('Change Over days','wprentals');
                        }

                    ?>
                </label>

            </div>
            <?php
            if(is_array($submission_page_fields) && in_array('checkin_change_over', $submission_page_fields)) {?>
            <div class="col-md-3">
                <label for="checkin_change_over"><?php esc_html_e('Allow only bookings starting with the check-in on:','wprentals');?></label>
                <select id="checkin_change_over" name="checkin_change_over" class="select-submit2">
                   <?php
                    foreach($week_days as $key=>$value){
                        print '   <option value="'.esc_attr($key).'"';
                        if( $key==$checkin_change_over){
                            print ' selected="selected" ';
                        }
                        print '>'.esc_html($value).'</option>';
                    }
                   ?>

                </select>
            </div>

            <?php } ?>
            <?php
            if(is_array($submission_page_fields) && in_array('checkin_checkout_change_over', $submission_page_fields)) {?>
            <div class="col-md-3">
                <label for="checkin_checkout_change_over"><?php esc_html_e('Allow only bookings with the check-in/check-out on: ','wprentals');?></label>
                <select id="checkin_checkout_change_over" name="checkin_checkout_change_over" class="select-submit2">
                   <?php
                    foreach($week_days as $key=>$value){
                       print '   <option value="'.esc_attr($key).'"';
                        if( $key==$checkin_checkout_change_over){
                            print ' selected="selected" ';
                        }
                        print '>'.esc_html($value).'</option>';
                    }
                   ?>
                </select>
            </div>
            <?php } ?>
            <div class="col-md-3"> </div>
        </div>
    <?php }



    if($booking_type==3 || $booking_type==2){
    ?>

        <div class="col-md-12">
            <div class="col-md-3 dashboard_chapter_label">
                <label>
                    <?php
                        esc_html_e('Business hours  -  for per h booking','wprentals');
                    ?>
                </label>
            </div>


            <div class="col-md-3">
                <label for="booking_start_hour"><?php esc_html_e('Start Hour','wprentals');?></label>
                <select id="booking_start_hour" name="booking_start_hour" class="select_submit_price select-submit2">
                   <?php
                    $i=0;
                    if($booking_start_hour==''){
                        $booking_start_hour='0:00';
                    }
                    while($i<=23):

                        $value = $i.':00';
                        print '   <option value="'.esc_attr($value).'"';
                        if( $value==$booking_start_hour ){
                            print ' selected="selected" ';
                        } $i++;
                        print '>'.esc_html($value).'</option>';
                    endwhile;
                   ?>

                </select>
            </div>



            <div class="col-md-3">
                <label for="booking_end_hour"><?php esc_html_e('End Hour','wprentals');?></label>
                <select id="booking_end_hour" name="booking_end_hour" class="select_submit_price select-submit2">
                    <?php
                    $i=0;
                     if($booking_end_hour==''){
                        $booking_end_hour='24:00';
                    }
                    while($i<=23):
                        $i++;
                        $value = $i.':00';
                        print '   <option value="'.esc_attr($value).'"';
                        if( $value==$booking_end_hour){
                            print ' selected="selected" ';
                        }
                        print '>'.esc_html($value).'</option>';
                    endwhile;
                   ?>
                </select>
            </div>

            <div class="col-md-3"> </div>
        </div>

    <?php
     }

    if(is_array($submission_page_fields) && in_array('extra_options', $submission_page_fields)) {
    ?>
        <div class="col-md-12">

            <div class="col-md-3 dashboard_chapter_label">
                <label>
                    <?php esc_html_e('Extra Options','wprentals');?>
                </label>
            </div>
            <div class="col-md-6">
                <label><?php esc_html_e('Extra Options','wprentals');?></label>
                <div class="extra_pay_option_wrapper">
                    <?php


                    if(is_array($extra_pay_options)){
                        foreach($extra_pay_options as $key=>$wpestate_options){
                            print'<div class="extra_pay_option">';
                                print '<input type="text" class="add_option_input extra_option_name form-control" name="'.esc_attr($wpestate_options[0]).'" value="'.esc_attr($wpestate_options[0]).'">' ;
                                print '<input type="text" class="add_option_input extra_option_value form-control" name="'.esc_attr($wpestate_options[1]).'_value" value="'.esc_attr($wpestate_options[1]).'">' ;
                                print  wpestate_dropdown_fee_select($wpestate_options[0]."_type",$wpestate_options[2],$rental_type,$booking_type);
                                print '<span class="delete_extra_option">'.esc_html__('delete','wprentals').'</span>';
                            print '</div>';

                        }
                    }else{
                        print'<div class="no_extra_pay_option">'.esc_html__('no options','wprentals').'</div>';
                    }

                    print '</div>';

                print '<div class="add_option_wrapper"><input type="text" class="add_option_input form-control" id="add_option_name" name="add_option_name" value="" placeholder="'.esc_html__('name','wprentals').'">';
                    print '<input type="text" class="add_option_input form-control" id="add_option_value" name="add_option_value" value="" placeholder="'.esc_html__('value','wprentals').'">' ;
                    print  wpestate_dropdown_fee_select("add_option_type",'',$rental_type,$booking_type);
                    print '<span id="add_extra_option">'.esc_html__('Add Option','wprentals').'</span>';
                print'</div>';

                ?>

            </div>
            <div class="col-md-3"> </div>
        </div>

    <?php } ?>



    <div class="col-md-12">
    <div class="col-md-12" style="display: inline-block;">
        <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_price" value="<?php esc_html_e('Save', 'wprentals') ?>" />
        <a href="<?php echo  esc_url($edit_link_images);?>" class="next_submit_page"><?php esc_html_e('Go to Media settings.','wprentals');?></a>

        <?php
        $ajax_nonce = wp_create_nonce( "wprentals_edit_prop_price_nonce" );
        print'<input type="hidden" id="wprentals_edit_prop_price_nonce" value="'.esc_html($ajax_nonce).'" />    ';
        ?>

    </div>

    <?php
    if(is_array($submission_page_fields) && in_array('custom_prices', $submission_page_fields)) {
    ?>
        <h4 class="user_dashboard_panel_title" style="margin-top:20px;"><?php esc_html_e('Price Adjustments ','wprentals');?></h4>
        <?php echo '<div class="price_explaning" >'.esc_html__( ' *(click to select multiple days and modify price for a certain period)','wprentals').'</div>';?>
        <div class="col-md-12" id="profile_message"></div>

        <div class="booking-calendar-wrapper-in-wrapper" id="custom_price_wrapper">
            <?php
                $custom_price_array  =   wpml_custom_price_adjust($edit_id);
                $mega_details        =   wpml_mega_details_adjust($edit_id);
                if( !is_array($custom_price_array) ){
                    $custom_price_array=array();
                }

                wpestate_get_calendar_price ($edit_id,$property_price,$price_per_guest_from_one,$extra_price_per_guest,$custom_price_array,$mega_details,true,true);
            ?>
            <div id="calendar-prev-internal-price" class="internal-calendar-left"><i class="fas fa-chevron-left"></i></div>
            <div id="calendar-next-internal-price" class="internal-calendar-right"><i class="fas fa-chevron-right"></i></div>
        </div>

        <?php

        $mega=get_post_meta($edit_id, 'mega_details'.$edit_id,true );
        wpestate_show_custom_details($edit_id,1);

        ?>
    <?php } ?>
    </div>
  </div>
 </div>

 </div>
 <!-- Modal -->
<div class="modal fade" id="owner_price_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog custom_price_dialog">
        <div class="modal-content">

            <div class="modal-header">
              <button type="button" id="close_custom_price_internal" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h2 class="modal-title_big"><?php esc_html_e('Custom Price','wprentals');?></h2>
              <h4 class="modal-title" id="myModalLabel"><?php esc_html_e('Set custom price for selected period','wprentals');?></h4>
            </div>

            <div class="modal-body">

                <div id="booking_form_request_mess_modal"></div>

                    <div class="col-md-6">
                        <label for="start_date_owner_book"><?php esc_html_e('Start Date','wprentals');?></label>
                        <input type="text" id="start_date_owner_book" size="40" name="booking_from_date" class="form-control" value="">
                    </div>

                    <div class="col-md-6">
                        <label for="end_date_owner_book"><?php  esc_html_e('End Date','wprentals');?></label>
                        <input type="text" id="end_date_owner_book" size="40" name="booking_to_date" class="form-control" value="">
                    </div>


                    <input type="hidden" id="property_id" name="property_id" value="" />
                    <input name="prop_id" type="hidden"  id="agent_property_id" value="">

                    <?php

                    if(is_array($submission_page_fields) && in_array('property_price', $submission_page_fields)) {
                    ?>
                        <div class="col-md-6">
                            <label for="coment"><?php echo esc_html__( 'New Price in ','wprentals').' '.esc_html($wp_estate_currency_symbol);?></label>
                            <input type="text" id="new_custom_price" size="40" name="new_custom_price" class="form-control" value="">
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    if(is_array($submission_page_fields) && in_array('extra_price_per_guest', $submission_page_fields)) {
                    ?>
                        <div class="col-md-6">
                            <label for="period_extra_price_per_guest"><?php echo wpestate_show_labels('extra_price_per_guest',$rental_type,$booking_type).' '.esc_html($wp_estate_currency_symbol);?></label>
                            <input type="text" id="period_extra_price_per_guest" size="40" name="period_extra_price_per_guest" class="form-control" value="0">
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    if(is_array($submission_page_fields) && in_array('property_price_per_week', $submission_page_fields)) {
                    ?>
                        <div class="col-md-6">
                            <label for="period_week_price"><?php  echo wpestate_show_labels('price_week_label',$rental_type,$booking_type);?></label>
                            <input type="text" id="period_week_price" size="40" name="period_week_price" class="form-control" value="">
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    if(is_array($submission_page_fields) && in_array('property_price_per_month', $submission_page_fields)) {
                    ?>
                        <div class="col-md-6">
                            <label for="period_month_price"><?php echo wpestate_show_labels('price_month_label',$rental_type,$booking_type);?></label>
                            <input type="text" id="period_month_price" size="40" name="period_month_price" class="form-control" value="">
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    if(is_array($submission_page_fields) && in_array('price_per_weekeend', $submission_page_fields)) {
                    ?>
                        <div class="col-md-6">
                            <label for="period_price_per_weekeend"><?php echo esc_html__( 'Price per weekend in ','wprentals').' '.esc_html($wp_estate_currency_symbol);?></label>
                            <input type="text" id="period_price_per_weekeend" size="40" name="period_price_per_weekeend" class="form-control" value="">
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    if(is_array($submission_page_fields) && in_array('min_days_booking', $submission_page_fields)) {
                    ?>
                        <div class="col-md-6">
                            <label for="period_min_days_booking"><?php echo esc_html__( 'Minimum days of booking','wprentals');?></label>
                            <input type="text" id="period_min_days_booking" size="40" name="period_min_days_booking" class="form-control" value="1">
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    if(is_array($submission_page_fields) && in_array('checkin_change_over', $submission_page_fields)) {
                    ?>
                        <div class="col-md-6">
                            <label for="period_checkin_change_over"><?php echo esc_html__( 'Allow only bookings starting with the check-in on changeover days','wprentals');?></label>
                            <select id="period_checkin_change_over" name="period_checkin_change_over" class="select-submit2">
                                <?php
                                foreach($week_days as $key=>$value){
                                    print '   <option value="'.esc_attr($key).'">'.esc_html($value).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    if(is_array($submission_page_fields) && in_array('checkin_checkout_change_over', $submission_page_fields)) {
                    ?>
                        <div class="col-md-6">
                            <label for="period_checkin_checkout_change_over"><?php echo esc_html__( 'Allow only bookings with the check-in/check-out (changeover) days/nights','wprentals');?></label>
                            <select id="period_checkin_checkout_change_over" name="period_checkin_checkout_change_over" class="select-submit2">
                                <?php
                                foreach($week_days as $key=>$value){
                                    print '<option value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    <?php
                    }
                    ?>


                    <?php
                    $ajax_nonce = wp_create_nonce( "wprentals_custom_price_nonce" );
                    print'<input type="hidden" id="wprentals_custom_price" value="'.esc_html($ajax_nonce).'" />    ';
                    ?>

                    <button type="submit" id="set_price_dates" class="wpb_button  wpb_btn-info  wpb_regularsize   wpestate_vc_button  vc_button"><?php esc_html_e('Set price for period','wprentals');?></button>

            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
