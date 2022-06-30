<?php
/*
 * generate data for total vistis graph
 *
 *
 *
 *
 */
if(!function_exists('wpestate_display_bookings_graph')):
  function wpestate_display_bookings_graph(){
    $wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
    $wpestate_currency              =   wpestate_curency_submission_pick();
    $current_user                   =   wp_get_current_user();
    $userID                         =   $current_user->ID;
    $template                       =   get_transient('wpestate_dashboard_booking_graph_'.$userID);


    $graph_data= array();
    $today     = new DateTime(); // today
    $begin     = $today->sub(new DateInterval('P30D')); //created 30 days interval back
    $end       = new DateTime();
    $end       = $end->modify('+1 day'); // interval generates upto last day
    $interval  = new DateInterval('P1D'); // 1d interval range
    $daterange = new DatePeriod($begin, $interval, $end); // it always runs forwards in date
    foreach ($daterange as $date) { // date object
        $graph_data [$date->format("m-d-Y")]=0; // your date
    }


    $template=false;

    if( $template===false ){
      $args = array(
          'post_type'         => 'wpestate_booking',
          'post_status'       => 'publish',
          'posts_per_page'    => -1,
          'order'             => 'DESC',
          'date_query' => array(
                        array(
                            'column' => 'post_date_gmt',
                            'after'  => '30 days ago',
                        )
          ),
          'meta_query' => array(

                  array(
                      'key' => 'owner_id',
                      'value' => $userID,
                      'compare' => '='
                  ),
                  array(
                      'key' => 'booking_status',
                      'value' => 'confirmed',
                      'compare' => '='
                  ),

          )
      );

      $prop_selection = new WP_Query($args);


      while ($prop_selection->have_posts()): $prop_selection->the_post();
          $booking_id     =   get_the_ID();
          $date           =   get_the_date('m-d-Y',$booking_id);
          $property_id    =   get_post_meta($booking_id, 'booking_id', true);
          $invoice_no     =   get_post_meta($booking_id, 'booking_invoice_no', true);
          $price          =   floatval(get_post_meta($invoice_no, 'item_price', true));
          if( isset($graph_data[$date]) ){
              $graph_data[$date]=$graph_data[$date]+$price;
          }else{
              $graph_data[$date]=$price;
          }
      endwhile;
      set_transient('wpestate_dashboard_booking_graph_'.$userID,$graph_data);
    }




    $labels = array_keys    ($graph_data);
    $values = array_values    ($graph_data);



    $return='<div class="col-md-12 visits_per_listing wpestate_widget_flex"><div class="wpestate_dashboard_content_wrapper user_dashboard_panel dashboad_widgets_wrapper">';
    $return.='<h4 class="user_dashboard_panel_title user_dashboard_panel_title_widget">'.esc_html__('Bookings - last 30 days', 'wprentals').'</h4>';
    $return.='<canvas id="myChart_widget_total"></canvas>';
    $return.='</div></div>';

    $return.= '<script type="text/javascript">
        //<![CDATA[
              jQuery(document).ready(function(){
                setTimeout(function(){
                  wpestate_chart_total_listings_widget('.json_encode($values).', '.json_encode($labels).', "'.esc_html__('Bookings value','wprentals').'" );
                }, 200);
                });

        //]]>
        </script>';


    return $return;
  }
endif;





/*
 *
 *
 *
 *
 *
 */




add_action( 'delete_post', 'wpestate_delete_history' );
function wpestate_delete_history($postId) {
  $current_user         =   wp_get_current_user();
  $userID               =   $current_user->ID;
  $recording_types      =   array(
                              'estate_property',
                              'estate_agent',
                              'wpestate_booking',
                              'wpestate_invoice',
                              'wpestate_search',
                           );

  $post_type=get_post_type($postId);
  $history_array=array();

  $history_array=get_user_meta($userID,'wpestate_delete_history',true);
  if($history_array==''){
    $history_array=array();
  }

  if(in_array($post_type,$recording_types )){
      $current_unix_timestamp=time();
      if(is_array($history_array)){
        foreach($history_array as $key=>$item){
           if( ($key+60*60*24*7) <= $current_unix_timestamp){
             unset($history_array[$key]);
           }
        }
      }

     $entry_date_label=date('F j, Y, g:i a',$current_unix_timestamp);
     $history_array[$current_unix_timestamp]=array(
        'date' => $entry_date_label,
        'label'=> wpestate_compose_history_entry($postId,'delete')
     );
     update_user_meta($userID,'wpestate_delete_history',$history_array);

  }

}


/*
 * return dashboard widget history
 *
 *
 *
 *
 */

if(!function_exists('wpestate_dashboard_widget_history')):
   function wpestate_dashboard_widget_history(){
       $current_user         =   wp_get_current_user();
       $userID               =   $current_user->ID;
       $history_array        =  get_user_meta($userID,'wpestate_delete_history',true);
       if($history_array==''){
         $history_array=array();
       }


       $args = array(
           'post_type'         =>  array(
                             'estate_property',
                             'estate_agent',
                             'wpestate_booking',
                             'wpestate_invoice',
                             'wpestate_search',
                                  ),
           'author'            =>  $userID,
           'paged'             =>  1,
           'posts_per_page'    =>  40,
           'orderby'           =>   array(
                                      'modified'=>'DESC',
                                      'date'=>'DESC'
                                    ),
           'order'             =>  'desc',
           'post_status'       =>  array('any'),
           'date_query' => array(
              array(
                  'column' => 'post_modified_gmt',
                  'after'  => '1 week ago',
              ),
          ),
         );
         //post_date
       $prop_selection = new WP_Query($args);
       while ($prop_selection->have_posts()): $prop_selection->the_post();
           $item_id=get_the_ID();
           $publish_date =  get_post_timestamp($item_id,'date');
           $modified_date=  get_post_timestamp($item_id,'modified');

           $entry_date  = $publish_date;
           $entry_date_label = get_the_date('F j, Y, g:i a');
           $action      = 'add';
           if($modified_date>$publish_date){
             $entry_date        = $modified_date;
             $entry_date_label  = get_the_modified_date('F j, Y, g:i a');
             $action            = 'edit';
           }

           $history_array[$entry_date]=array(
              'date' => $entry_date_label,
              'label'=> wpestate_compose_history_entry($item_id,$action)
           );
       endwhile;
       wp_reset_query();
       wp_reset_postdata();


       krsort($history_array,SORT_NUMERIC);
       array_slice($history_array, 0, 21);


       $return='<div class="col-md-12 wpestate_widget_flex"><div class="user_dashboard_panel dashboad_widgets_wrapper">';
       $return.='<h4 class="user_dashboard_panel_title user_dashboard_panel_title_widget">'.esc_html__('Account History (last 7 days)', 'wprentals').'</h4>';
       $return.='<div class="dashboard_history_wrapper">';
       if(!empty($history_array)){
         foreach ($history_array as $key => $entry) {
           $return.='<div class="wpestate_dash_history_unit">';
            $return.='<div class="wpestat_dash_history_date">'.$entry['date'].'</div>';
              $return.='<div class="wpestat_dash_history_label">'.$entry['label'].'</div>';
           $return.='</div>';
         }
       }else{
            $return.='<div class="wpestate_dash_history_unit">'.esc_html__('There has been no activity registered for the past 7 days. ','wprentals').'</div>';
       }
       $return.='</div></div></div>';


       return $return;
   }
endif;




/*
 * compose history label
 *
 *
 *
 *
 */
function wpestate_compose_history_entry($item_id,$action='add'){
    $post_type=get_post_type($item_id);
    $return='';

    $action_string=esc_html__('Edited','wprentals');
    if($action=='add'){
      $action_string=esc_html__('Added','wprentals');
    }else if($action=='delete'){
      $action_string=esc_html__('Deleted','wprentals');
    }

    switch ($post_type) {
      case 'wpestate_booking':
          $return = sprintf( esc_html__('%s  %s','wprentals'),$action_string,get_the_title($item_id));
          break;
      case 'estate_property':
          $return = sprintf( esc_html__('%s property %s','wprentals'),$action_string,get_the_title($item_id));
          break;
      case 'estate_agent':
          $return = sprintf( esc_html__('%s agent %s','wprentals'),$action_string,get_the_title($item_id));
          break;
      case 'estate_agency':
          $return = sprintf( esc_html__('%s agency %s','wprentals'),$action_string,get_the_title($item_id));
          break;
      case 'estate_agency':
          $return = sprintf( esc_html__('%s developer %s','wprentals'),$action_string,get_the_title($item_id));
          break;
      case 'wpestate_invoice':
          $return = sprintf( esc_html__('Generated Invoice %s','wprentals'),get_the_title($item_id));
          if($action=='delete'){
            $return = sprintf( esc_html__('Deleted Invoice %s','wprentals'),get_the_title($item_id));
          }
          break;
      case 'wpestate_message':
          $return = sprintf( esc_html__('Write Message  %s','wprentals'),get_the_title($item_id));
          if($action=='delete'){
            $return = sprintf( esc_html__('Deleted Message  %s','wprentals'),get_the_title($item_id));
          }
          break;
      case 'wpestate_search':
          $return = sprintf( esc_html__('Saved Search  %s','wprentals'),get_the_title($item_id));
          if($action=='delete'){
            $return = sprintf( esc_html__('Deleted Search  %s','wprentals'),get_the_title($item_id));
          }
          break;
    }


    return $return;
}



/*
 * return dashboard widget to 10
 *
 *
 *
 *
 */
if(!function_exists('wpestate_dashboard_widget_top_ten_contacted')):
  function wpestate_dashboard_widget_top_ten_booked($userID){

    $wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
    $wpestate_currency              =   wpestate_curency_submission_pick();
    $template                       =   get_transient('wpestate_dashboard_widget_top_ten_contacted_'.$userID);

    if ($template === false) {
      $args = array(
          'post_type'         => 'wpestate_booking',
          'post_status'       => 'publish',
          'posts_per_page'    => -1,
          'order'             => 'DESC',
          'date_query' => array(
                        array(
                            'column' => 'post_date_gmt',
                            'after'  => '90 days ago',
                        )
          ),
          'meta_query' => array(

                  array(
                      'key' => 'owner_id',
                      'value' => $userID,
                      'compare' => '='
                  ),
                  array(
                      'key' => 'booking_status',
                      'value' => 'confirmed',
                      'compare' => '='
                  ),

          )
      );

      $prop_selection = new WP_Query($args);

      $property_selection=array();
      while ($prop_selection->have_posts()): $prop_selection->the_post();
          $booking_id=get_the_ID();
          $property_id    =   get_post_meta($booking_id, 'booking_id', true);
          $invoice_no     =   get_post_meta($booking_id, 'booking_invoice_no', true);
          $price          =   get_post_meta($invoice_no, 'item_price', true);


          if(isset($property_selection[$property_id]) ){
            $temp_element               = $property_selection[$property_id];
            $temp_element['book_no']    = $property_selection[$property_id]['book_no']+1;
            $temp_element['total_price']= $property_selection[$property_id]['total_price']+$price;
            $property_selection[$property_id]=   $temp_element ;
          }else{
            $property_selection[$property_id]=array(
                    'book_no'=>1,
                    'total_price' => $price,
                  );
          }

      endwhile;
        ob_start();
          uasort($property_selection, function($a, $b) {    return ($b['book_no']-$a['book_no']);  }  );


          if( !empty($property_selection) ){
              foreach ($property_selection as $key => $property) {
                include(locate_template('dashboard/templates/dashboard_unit_widget_popular.php'));
              }


          }else{
            print '<div class="wpestate_dashboard_no_listings">'.esc_html__('You don\'t have any listings or enough data!','wprentals').'</div>';
          }


        $template=ob_get_contents();
        ob_end_clean();
        wp_reset_query();
        wp_reset_postdata();
        set_transient('wpestate_dashboard_widget_top_ten_contacted_'.$userID, $template, 60*60*24);
    }

    $return='<div class="col-md-6 wpestate_widget_flex"><div class="user_dashboard_panel dashboad_widgets_wrapper">';
    $return.='<h4 class="user_dashboard_panel_title user_dashboard_panel_title_widget">'.esc_html__('Your most booked listings', 'wprentals').'</h4>';
    $return.=$template;
    $return.='</div></div>';

    return $return;
}
endif;






/*
 * return dashboard widget to 10
 *
 *
 *
 *
 */
if(!function_exists('wpestate_dashboard_widget_top_ten')):
  function wpestate_dashboard_widget_top_ten($userID){

    $current_user     =   wp_get_current_user();
    $userID           =   $current_user->ID;
    $template         =   get_transient('wpestate_dashboard_widget_top_ten_'.$userID);

  $template=false;

    if ($template === false) {
        $args = array(
            'post_type'         =>  'estate_property',
            'author'            =>  $userID,
            'paged'             =>  1,
            'posts_per_page'    =>  5,
            'orderby'           => 'meta_value_num',
            'meta_key'          => 'wpestate_total_views',
            'order'             =>  'desc',
            'post_status'       =>  array('any')
          );
        $prop_selection = new WP_Query($args);
        ob_start();
        if($prop_selection->have_posts()){
          while ($prop_selection->have_posts()): $prop_selection->the_post();
            $action_status = get_post_meta(get_the_ID(),'wpestate_total_views',true).' '.esc_html__('Views','wprentals');
            include(locate_template('dashboard/templates/dashboard_unit_widget.php'));
          endwhile;
        }else{
          print '<div class="wpestate_dashboard_no_listings">'.esc_html__('You don\'t have any listings or enough data!','wprentals').'</div>';
        }


        $template = ob_get_contents();
        ob_end_clean();
        wp_reset_query();
        wp_reset_postdata();
        set_transient('wpestate_dashboard_widget_top_ten_'.$userID, $template, 60*60*24);
    }

    $return='<div class="col-md-6 wpestate_widget_flex"><div class=" user_dashboard_panel dashboad_widgets_wrapper">';
    $return.='<h4 class="user_dashboard_panel_title user_dashboard_panel_title_widget">'.esc_html__('Your most visited listings', 'wprentals').'</h4>';
    $return.=$template;
    $return.='</div></div>';

    return $return;
}
endif;


/*
 * account Summary
 *
 *
 *
 *
 */

if(!function_exists('wpestate_dashboard_account_summary')):
  function wpestate_dashboard_account_summary($userID){

    $user_option                    =   'favorites'.$userID;
    $curent_fav                     =   get_option($user_option);
    $details='';

    $details.='<div class="dasboard_widget_row">'.esc_html__('Total Properties','wprentals').': '.wpestate_count_user_posts_by_status_query($userID).'</div>';
    $details.='<div class="dasboard_widget_row">'.esc_html__('Published Properties','wprentals').': '.count_user_posts($userID,'estate_property',true).'</div>';

    if(is_array($curent_fav)){
      $curent_fav_no= count($curent_fav);
    }else{
      $curent_fav_no=intval($curent_fav);
    }
    $details.='<div class="dasboard_widget_row">'.esc_html__('Favorite Properties','wprentals').': '.intval($curent_fav_no).'</div>';

    $return='<div class="col-md-12 "><div class="user_dashboard_panel dashboad_widgets_wrapper widget_summary">';

    $return.= '<div class="col-md-4">';
    $return.= '<h4 class="user_dashboard_panel_title user_dashboard_panel_title_widget">'.esc_html__('Account Summary', 'wprentals').'</h4>';
    $return.= $details.'</div>';

    $args = array(
        'post_type'         => 'wpestate_booking',
        'post_status'       => 'publish',
        'posts_per_page'    => 5,
        'paged'             => 1,
        'order'             => 'ASC',
        'orderby'   => 'meta_value_num',
        'meta_key'   => 'booking_from_date_unix',


        'meta_query' => array(

                array(
                    'key' => 'owner_id',
                    'value' => $userID,
                    'compare' => '='
                ),
                array(
                    'key' => 'booking_status',
                    'value' => 'confirmed',
                    'compare' => '='
                ),
                array(
                      'key' => 'booking_from_date_unix',
                      'compare' => 'EXISTS'
                ),
                 array(
                        'key' => 'booking_from_date_unix',
                        'value'=> time(),
                        'compare' => '<'
                    ),


        )
    );

    $prop_selection = new WP_Query($args);

    $next_bookings='';
    if( $prop_selection->have_posts() ){
      while ($prop_selection->have_posts()): $prop_selection->the_post();
        $the_id=get_the_ID();
        $propert_id         =   get_post_meta($the_id, 'booking_id', true);
        $booking_from_date  =   get_post_meta($the_id, 'booking_from_date', true);
        $booking_from_date  =   wpestate_convert_dateformat_reverse($booking_from_date);
        $booking_to_date    =   get_post_meta($the_id, 'booking_to_date', true);
        $booking_to_date    =   wpestate_convert_dateformat_reverse($booking_to_date);
        
 

        $next_bookings.='<div class="dasboard_widget_row"><a href="'.get_permalink($propert_id).'">'.get_the_title($propert_id).'</a>'.' '.esc_html__( 'on','wprentals').' '.$booking_from_date.' '.esc_html__('to','wprentals').' '.$booking_to_date.'</div>';

      endwhile;
    }else{
        $next_bookings.=esc_html__('You don\'t have any upcoming bookings','wprentals');
    }





    $return.='<div class="col-md-8">';
    $return.='<h4 class="user_dashboard_panel_title user_dashboard_panel_title_widget">'.esc_html__('Next Bookings', 'wprentals').'</h4>';
      $return.=$next_bookings;
    $return.='</div>';

    $return.='</div></div>';

    wp_reset_query();
    wp_reset_postdata();
    return $return;
}
endif;




/*
 * count user properties
 *
 *
 *
 *
 */



function wpestate_count_user_posts_by_status_query($userID){
    $args = array(
          'post_type'         =>  'estate_property',
          'author'            =>  $userID,
          'posts_per_page'    =>  -1,
          'post_status'       =>  array('any'),
          'fields'            =>  'ids'
          );
    $prop_selection = new WP_Query($args);
    wp_reset_query();
    wp_reset_postdata();
    return $prop_selection->post_count;
}



/*
 * dashboard header
 *
 *
 *
 *
 */


if( !function_exists('wprentals_dashboard_header_display') ):
  function wprentals_dashboard_header_display(){
    global $post;
    $current_user   =   wp_get_current_user();
    $userID         =   $current_user->ID;

    print '<div class="dashboard-header">';
    if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') {
        print '<h1 class="entry-title entry-title-profile">'.get_the_title($post->ID);
        if(isset($_GET['listing_edit']) && is_numeric($_GET['listing_edit'])  ){
            $listing_id =   intval($_GET['listing_edit']);
            $title_link =   get_permalink($listing_id);
            
            
            if( get_post_status($listing_id)=='disabled' ){
                print ' - '.get_the_title($listing_id);
            }else{
                print ' - '.'<a href="'.esc_url($title_link).'" target="_blank">'.get_the_title($listing_id).'</a>';
            }
            
          
        }
        print '</h1>';
    }
    if( is_user_logged_in() ){
      print '<div class="back_to_home">
          <a href="'. esc_url( home_url('/') ).'" title="home url">'. esc_html__('Front page','wprentals').'</a>
      </div>';

      $no_unread      =   intval(get_user_meta($userID, 'unread_mess', true));
      print '<div class="wpestate_bell_note"><a href="'.esc_url( wpestate_get_template_link('user_dashboard_inbox.php')).'">';
      include(locate_template('dashboard/dashboard-icons/bell.svg'));
      print '<div class="wpestate_bell_note_unread">'.intval($no_unread).'</div></a></div>';

    }
    print '</div>';
    print '<div class="single-content">'.get_the_content($post->ID).'</div>';

}
endif;
?>
