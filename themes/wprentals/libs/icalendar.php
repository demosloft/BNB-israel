<?php
require_once get_theme_file_path('/libs/resources/class.iCalReader.php');

function wpestate_clear_ical_imported($prop_id){
    $reservation_array = get_post_meta($prop_id, 'booking_dates',true);
    if(!is_array($reservation_array)){
        $reservation_array=array();
    }
    
    foreach($reservation_array as $key=>$value){
        if (is_numeric($value)==0){
            unset($reservation_array[$key]);
        }
    }
    update_post_meta($prop_id, 'booking_dates',$reservation_array);

}


function wpestate_import_calendar_feed_listing_global($prop_id){
    $property_icalendar_import_multi =   get_post_meta($prop_id, 'property_icalendar_import_multi', true);
    foreach( $property_icalendar_import_multi as $key=>$feed_data){
       wpestate_import_calendar_feed_listing($prop_id,$feed_data['feed'],$feed_data['name']);
    }
}

function wpestate_generate_feed_listing_for_delete($prop_id,$property_icalendar_import,$name){
   
  
    if(!intval($prop_id)){
        exit();
    }
   
    if($property_icalendar_import ==''){
        return;
    }
    if (filter_var($property_icalendar_import, FILTER_VALIDATE_URL) === FALSE) {
       return;
    }
    
    
    $ical   = new ICal($property_icalendar_import);
    $ical_timezone = $ical->timezone();
    $events = $ical->events();
    $date = $events[0]['DTSTART'];
  

    $data_to_insert =   array();
    //DTSTART which sets a starting time, and a DTEND which sets an ending time.
    foreach ($events as $event) {
        $unix_time_start    ='';
        $unix_time_end      ='';
        if( isset($event['UID']) ){
            $uid                =$event['UID'];
            
        }else{
            $uid=   esc_html__('external','wprentals');
        }
        
        
        $has_emebed_time=0;
        if( isset( $event['DTSTART_array'][0]['TZID'] ) &&  $event['DTSTART_array'][0]['TZID']!='' ){
            $has_emebed_time=1;        
        }
   
        
        if( isset($event['DTSTART']) ){
            $unix_time_start =$ical->iCalDateToUnixTimestamp($event['DTSTART']);
        }

        if( isset($event['DTEND']) ){
            $unix_time_end =$ical->iCalDateToUnixTimestamp($event['DTEND']);
       }

        $uid            =   $name;// update on 1.20 with multuple ical feed
        $temp_array     =   array();
       
        
        if( $unix_time_start!='' && $unix_time_end!='' && $uid !=''){

            if($ical_timezone==''){
                $ical_timezone= date_default_timezone_get();
            }

             $converted_start_date       =   gmdate("Y-m-d H:i:s", $unix_time_start);
             $convert_unix_time_start    =   strtotime($converted_start_date);

             $converted_end_date         =   gmdate("Y-m-d H:i:s", $unix_time_end);
             $converted_unix_end_date    =   strtotime($converted_end_date );


             $date = new DateTime($converted_start_date);
             $tz=timezone_open($ical_timezone);
             $timezone_offset= timezone_offset_get($tz,$date);
    
            if($has_emebed_time==0){
                $convert_unix_time_start=$convert_unix_time_start+$timezone_offset;
                $converted_unix_end_date=$converted_unix_end_date+$timezone_offset;
            }

            $temp_array  =   array(); 
            $temp_array['prop_id']          =   $prop_id;
            $temp_array['unix_time_start']  =   $convert_unix_time_start;
            $temp_array['unix_time_end']    =   $converted_unix_end_date;
            $temp_array['uid']              =   $uid;
            $temp_array['has_emebed_time']  =   $has_emebed_time;
          
            $data_to_insert[]               =   $temp_array;
        }
        
        
    }
    return $data_to_insert;
  
    
}



function wpestate_import_calendar_feed_listing($prop_id,$property_icalendar_import,$name){

    if(!intval($prop_id)){
        exit();
    }
   
    if($property_icalendar_import ==''){
        return;
    }
    
   
    if (filter_var($property_icalendar_import, FILTER_VALIDATE_URL) === FALSE) {
       return;
    }
    
    
    $ical   = new ICal($property_icalendar_import);
    $ical_timezone = $ical->timezone();
    $events = $ical->events();
    $date = $events[0]['DTSTART'];
    
    $data_to_insert =   array();
  
    if(is_array($events)):
        foreach ($events as $event) {
            $unix_time_start    ='';
            $unix_time_end      ='';
            if( isset($event['UID']) ){
                $uid                =$event['UID'];
            }else{
                $uid=   esc_html__('external','wprentals');
            }

            $has_emebed_time=0;
            if( isset( $event['DTSTART_array'][0]['TZID'] ) &&  $event['DTSTART_array'][0]['TZID']!='' ){
                $has_emebed_time=1;        
            }

            if( isset($event['DTSTART']) ){
               $unix_time_start =$ical->iCalDateToUnixTimestamp($event['DTSTART']);
            }

            if( isset($event['DTEND']) ){
                $unix_time_end =$ical->iCalDateToUnixTimestamp($event['DTEND']);
            }

            $uid            =   $name;// update on 1.20 with multuple ical feed
            $temp_array     =   array();
            if( $unix_time_start!='' && $unix_time_end!='' && $uid !=''){
                $temp_array                     =   array(); 
                $temp_array['prop_id']          =   $prop_id;
                $temp_array['unix_time_start']  =   $unix_time_start;
                $temp_array['unix_time_end']    =   $unix_time_end;
                $temp_array['uid']              =   $uid;
                $temp_array['has_embed_time']   =   $has_emebed_time;
                $data_to_insert[]               =   $temp_array;
            }    
        }
    endif;
    

    $reservation_array  = get_post_meta($prop_id, 'booking_dates',true);
    if($reservation_array==''){
        $reservation_array=array();
    }
    $dates_with_uid     = array_keys( $reservation_array, $data_to_insert[0]['uid'] );
    
    foreach ($dates_with_uid as $key=>$timestamp){
        unset($reservation_array[$timestamp]);
    }
    update_post_meta($prop_id, 'booking_dates',$reservation_array);

    
    $wprentals_is_per_hour  =   wprentals_return_booking_type($prop_id);
    
   
    foreach ($data_to_insert as $key=>$to_insert){
        if($wprentals_is_per_hour==2){
          
            wpestate_insert_booking_external_event_per_hour($to_insert['prop_id'], $to_insert['unix_time_start'], $to_insert['unix_time_end'], $to_insert['uid'], $ical_timezone,$to_insert['has_embed_time']);
              
        }else{
            wpestate_insert_booking_external_event($to_insert['prop_id'], $to_insert['unix_time_start'], $to_insert['unix_time_end'], $to_insert['uid'] );
        }    
    }
    
}

function  wpestate_insert_booking_external_event_per_hour($prop_id, $unix_time_start,$unix_time_end,$uid,$ical_timezone,$has_emebed_time){
  
    if($ical_timezone==''){
       $ical_timezone= date_default_timezone_get();
    }
    
    $converted_start_date       =   gmdate("Y-m-d H:i:s", $unix_time_start);
    $convert_unix_time_start    =   strtotime($converted_start_date);
 
    $converted_end_date         =   gmdate("Y-m-d H:i:s", $unix_time_end);
    $converted_unix_end_date    =   strtotime($converted_end_date );
       
      
    $date               =   new DateTime($converted_start_date);
    $tz                 =   timezone_open($ical_timezone);
    $timezone_offset    =   timezone_offset_get($tz,$date);
 
    if($has_emebed_time==0){
        $convert_unix_time_start=$convert_unix_time_start+$timezone_offset;
        $converted_unix_end_date=$converted_unix_end_date+$timezone_offset;
    }
   
    
    $now=time();
    $daysago = $now-3*24*60*60;
    
 
    if ($convert_unix_time_start<$daysago){
        return;
    }


    
    $reservation_array  = get_post_meta($prop_id, 'booking_dates',true);
    if(!is_array($reservation_array)){
        $reservation_array=array();
    }
    $reservation_array[$convert_unix_time_start] =   $converted_unix_end_date;
    update_post_meta($prop_id, 'booking_dates',$reservation_array);

}



function  wpestate_insert_booking_external_event($prop_id, $unix_time_start,$unix_time_end,$uid){ 
    $converted_start_date       =   gmdate("Y-m-d 0:0:0", $unix_time_start);
    $convert_unix_time_start    =   strtotime($converted_start_date);
    $converted_end_date         =   gmdate("Y-m-d 0:0:0", $unix_time_end);
    $convert_unix_time_end      =   strtotime($converted_end_date);

    
    $unix_time_start=$convert_unix_time_start;
    $unix_time_end=$convert_unix_time_end;
     
    $now=time();
    $daysago = $now-3*24*60*60;
    
    if ($unix_time_end<$daysago){
        return;
    }
    
    $reservation_array  = get_post_meta($prop_id, 'booking_dates',true);
  
     
    if(!is_array($reservation_array)){
        $reservation_array=array();
    }
    
    
    $unix_time_start    = gmdate("Y-m-d\TH:i:s\Z", $unix_time_start);
    $unix_time_end      = gmdate("Y-m-d\TH:i:s\Z", $unix_time_end);
    
    $from_date      =   new DateTime($unix_time_start);
    $from_date_unix =   $from_date->getTimestamp();
    $to_date        =   new DateTime($unix_time_end);
    $to_date_unix   =   $to_date->getTimestamp();
            
    if( is_numeric($uid)){
        $uid=(string)$uid.' ';
    } 
    
  
    $reservation_array[$from_date_unix] =   $uid;
    $from_date_unix                     =   $from_date->getTimestamp();
    
    while ($from_date_unix < $to_date_unix){
        if( is_numeric($uid)){
            $uid=(string)$uid.' ';
        }
        $reservation_array[$from_date_unix]     =   $uid;
        $from_date->modify('tomorrow');
        $from_date_unix =   $from_date->getTimestamp();
    }
    
    
    update_post_meta($prop_id, 'booking_dates',$reservation_array);

}


function wpestate_update_calendar_missing_dates($reservation_array,$to_compare_array){
    $result = array_keys( $reservation_array, "air" );

}