<?php
/*  
set_time_limit(180);
ignore_user_abort();  */	
error_reporting(1);
require_once( dirname( __FILE__ ) . '/../wp-load.php' );
require_once( dirname( __FILE__ ) . '/../wp-admin/includes/image.php' );

global $wpdb;

function mergeboth($price_array, $price_array_pre)
{
	
	if(!empty($price_array))
	foreach($price_array as $key=>$value)
	{
		if(isset($price_array_pre[0]) && !empty($price_array_pre[0]))
		{
			$price_array_pre[0][$key] = $value;
		}
	}
	
	if(isset($price_array_pre[0]) && !empty($price_array_pre[0]))
	return $price_array_pre[0];
	else
	return $price_array;
	
}

function api_add_custom_price($pst){

	  $userID             =   1;
	  $new_custom_price   =   '';
	  $allowded_html      =   array();
 
	  if( isset($pst['new_price']) ){
		  $new_custom_price = floatval ( $pst['new_price'] ) ;
	  }
   
	  $property_id = intval( $pst['listing_id'] );
	  
	  $fromdate  =   wp_kses ( $pst['book_from'], $allowded_html );
	  $to_date   =   wp_kses ( $pst['book_to'], $allowded_html );
	
	  ////////////////// 
	  $period_min_days_booking                =   intval( $pst['period_min_days_booking'] );
	  $period_extra_price_per_guest           =   intval( $pst['period_extra_price_per_guest'] );
	  $period_price_per_weekeend              =   0;
	  $period_checkin_change_over             =   0;
	  $period_checkin_checkout_change_over    =   0;
	  $period_price_per_month                 =   0;
	  $period_price_per_week                  =   0;
	  
	  $mega_details_temp_array=array();
	  $mega_details_temp_array['period_min_days_booking']             =   $period_min_days_booking;
	  $mega_details_temp_array['period_extra_price_per_guest']        =   $period_extra_price_per_guest;
	  $mega_details_temp_array['period_price_per_weekeend']           =   $period_price_per_weekeend;
	  $mega_details_temp_array['period_checkin_change_over']          =   $period_checkin_change_over;
	  $mega_details_temp_array['period_checkin_checkout_change_over'] =   $period_checkin_checkout_change_over;
	  $mega_details_temp_array['period_price_per_month']              =   $period_price_per_month;
	  $mega_details_temp_array['period_price_per_week']               =   $period_price_per_week;

	  $price_array=array();
	  $mega_details_array=array();
	  
	  

	  ///////////////////////////////////////////////////
	  
	  //get mega_details
	  //get_custom_price
	  $price_array_pre = get_post_meta($property_id, 'custom_price');
	  $mega_details_array_pre = get_post_meta($property_id, 'mega_details');
	  
	 

	  $from_date      =   $fromdate;
	  $from_date_unix =   strtotime($fromdate);

	  $to_date        =   $to_date;
	  $to_date_unix   =   strtotime($to_date);
	  
	  if($new_custom_price!=0 && $new_custom_price!=''){
		  $price_array[(string)$from_date_unix]  =   $new_custom_price;
	  }
	  
	  $mega_details_array[(string)$from_date_unix]    =   $mega_details_temp_array;	  
	  
	  while ($from_date_unix <= $to_date_unix){
		  if($new_custom_price!=0 && $new_custom_price!=''){
			  $price_array[(string)$from_date_unix]           =   $new_custom_price;
		  }
		 
		  $mega_details_array[(string)$from_date_unix]    =   $mega_details_temp_array;
		  $from_date_unix =   strtotime(date("Y-m-d",$from_date_unix)." +1 days");
	  } 
	  
	  
	  /*echo "<pre>";
	  print_r($price_array);
	  print_r($price_array_pre);*/
	  $price_array = mergeboth($price_array, $price_array_pre);
	  $mega_details_array = mergeboth($mega_details_array, $mega_details_array_pre);
	  
	 
	  
	  
	  // clean price options from old data
	  $now=time() - 30*24*60*60;
	  foreach ($price_array as $key=>$value){
		  if( $key < $now ){
			  unset( $price_array[$key] );
			  unset( $mega_details_array[$key] );
		  } 
	  }
	  /*print_r($price_array);
	  print_r($mega_details_array);*/
	  // end clean
	  update_post_meta($property_id, 'custom_price',$price_array );
	  update_post_meta($property_id, 'mega_details',$mega_details_array ); 
} 

function api_add_booking($pst){
      		
	$userID             =   1;
	$comment   			=   '';
	$allowded_html      =   array();
	$status    			=   'confirmed';
	
	if( isset($pst['comment']) ){
		$comment  =    wp_kses ( $pst['comment'],$allowded_html ) ;
	}
	
	
	$booking_guest_no    =   '0';     
	
	$property_id        =   intval( $pst['listing_id'] );        
	$instant_booking    =   floatval   ( get_post_meta($property_id, 'instant_booking', true) );
	$owner_id           =   wpsestate_get_author($property_id);
	$fromdate           =   wp_kses ( $pst['fromdate'], $allowded_html );
	$to_date            =   wp_kses ( $pst['todate'], $allowded_html );
	
	$fromdate   = date("d-m-y",strtotime($fromdate));
	$to_date    = date("d-m-y",strtotime($to_date));
	//print 'converted $fromdate'.$fromdate.' / '.$to_date;
	 
	$event_name         =   esc_html__( 'Booking Request','wprentals');
	$extra_options      =   '';
	$post = array(
		'post_title'	=> $event_name,
		'post_content'	=> $comment,
		'post_status'	=> 'publish', 
		'post_type'         => 'wpestate_booking' ,
		'post_author'       => $userID
	);
	$post_id = $bookid  =   $booking_id = wp_insert_post($post );  
	
	$post = array(
		'ID'                => $post_id,
		'post_title'	=> $event_name.' '.$post_id
	);
	wp_update_post( $post );

	update_post_meta($post_id, 'booking_status', $status);
	update_post_meta($post_id, 'booking_id', $property_id);
	update_post_meta($post_id, 'owner_id', $owner_id);
	update_post_meta($post_id, 'booking_from_date', $fromdate);
	update_post_meta($post_id, 'booking_to_date', $to_date);
	update_post_meta($post_id, 'booking_invoice_no', '0');
	update_post_meta($post_id, 'booking_pay_ammount', '0');
	update_post_meta($post_id, 'booking_guests', $booking_guest_no);
	update_post_meta($post_id, 'extra_options', $extra_options);
	
	$security_deposit= get_post_meta(  $property_id,'security_deposit',true);
	update_post_meta($post_id, 'security_deposit', $security_deposit);

	$full_pay_invoice_id ='0';
	update_post_meta($post_id, 'full_pay_invoice_id', $full_pay_invoice_id);
	
	$to_be_paid ='0';
	update_post_meta($post_id, 'to_be_paid', $to_be_paid);
	
	// build the reservation array 
	$reservation_array = wpestate_get_booking_dates($property_id);      
	update_post_meta($property_id, 'booking_dates', $reservation_array); 
	
	$extra_options_array=array();

	$invoice_id='';
	$booking_array  =   wpestate_booking_price($booking_guest_no,$invoice_id, $property_id, $fromdate, $to_date,$booking_id,$extra_options_array);
	update_post_meta($booking_id, 'custom_price_array',$booking_array['custom_price_array']);

} 

require_once "ru_class.php";
$rentalsUnited = new rentalsUnited();

$owner = $rentalsUnited->getOwners();
$ownderId = 0;

if($owner)
{
	if($owner->Status=="Success")
	foreach($owner->Owners->Owner as $Ownr)
	{
		$att = $Ownr->attributes();
		$ownderId = $att['OwnerID'];
		break;
	}
}
  echo "<pre>";$p = 	$rentalsUnited->getRealtimeRates('3113137','2022-05-27','2022-06-27');
 
 print_r( $rentalsUnited->getProperty('3113137'));
 echo "<pre>"; die();  
	 
if($ownderId) 
{ 
	$properties = $rentalsUnited->getOwnerProperties($ownderId);	

	if($properties)
	{
		$i=1;$a =1;
		if($properties->Status=="Success")/*    echo "<pre>"; print_r($properties);  echo "<pre>"; die();   */
		foreach($properties->Properties->Property as $property)
		{ 
			$pid = (string)$property->ID;
		 //if($pid =='3113137'){
			//get property
			$prpty = $rentalsUnited->getProperty($pid);
			$rates = $rentalsUnited->getRates($pid);
			$calendar = $rentalsUnited->getCalendar($pid);
			$minstay = $rentalsUnited->getMinstay($pid);
			$realrates = $rentalsUnited->getRealtimeRates($pid,date('Y-m-d'),date('Y-m-d',strtotime("+7 days")));
			
			
			$title = $prpty->Property->Name;
			$cleaningPrice = (float)$prpty->Property->CleaningPrice;
			$title_arr = explode("#",$title);
			$listingProp = $wpdb->get_results("SELECT * FROM `wp_posts` WHERE `post_title` like '%#".$title_arr[count($title_arr)-1]."' limit 1");
			
			$listing_post_id = $listingProp[0]->ID;
			
			//cleaning Fee
			//update_post_meta( '32057', 'cleaning_fee', '100' );
			
			//min stay
			$minsty = 0;
			if(isset($minstay->PropertyMinStay))
			{
				$minsty = (int)$minstay->PropertyMinStay->MinStay[0];
				update_post_meta( $listing_post_id, 'min_days_booking', $minsty );
			}
			
			//base price
			if(isset($realrates->PropertyPrices->PropertyPrice))
			{
				 $property_price = (float)$realrates->PropertyPrices->PropertyPrice[0];
				 update_post_meta( $listing_post_id, 'property_price', $property_price );
			}
			
			
			//season price
			if(isset($rates->Prices->Season))
			{
				foreach($rates->Prices->Season as $season)
				{
					$price = (float)$season->Price;
					$extra = (float)$season->Extra;
					$att = $season->attributes();
					
					$post = array();
					$post['listing_id'] = $listing_post_id;
					$post['new_price'] = $price;
					$post['book_from'] = $att['DateFrom'];
					$post['book_to'] =  $att['DateTo'];
					$post['period_min_days_booking'] = $minsty;
					$post['period_extra_price_per_guest'] = $extra;
					$currentseason = date("Y-m-d");
					if($currentseason == $att['DateFrom'] OR $att['DateFrom'] <= $att['DateTo']){ 
					update_post_meta( $listing_post_id, 'property_price', $price );
					}
					api_add_custom_price($post);
					 
					
					
				}
			}
			
			$allprebookings = $wpdb->get_results("SELECT post_id FROM `wp_postmeta` WHERE `meta_key`='booking_id' and meta_value='".$listing_post_id."'");
			if($allprebookings)
			foreach($allprebookings as $allprebooking)
			{
				wp_delete_post( $allprebooking->post_id, true);
			}
			
			if(isset($calendar->PropertyBlock->Block))
			{
				foreach($calendar->PropertyBlock->Block as $calndr)
				{
					$post = array();
					$post['comment'] = '';
					$post['listing_id'] = $listing_post_id;
					$post['fromdate'] = (string)$calndr->DateFrom;
					$post['todate'] = (string)$calndr->DateTo;
					
					api_add_booking($post);
					
				}
			}
			// } 
		 }
	}
}
