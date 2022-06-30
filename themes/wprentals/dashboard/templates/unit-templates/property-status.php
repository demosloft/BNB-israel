<?php
$pay_status                 =   '';
$is_pay_status              =   '';

$post_status                =   get_post_status($post_id);
if($post_status=='expired'){
    $status='<div class="wprentals_status wprentals_status_'.esc_attr($post_status).'"><div class="wprentals_status_circle"></div>'.esc_html__( 'Expired','wprentals').'</div>';
}else if($post_status=='publish'){
    $link= esc_url ( get_permalink() );
    $status='<div class="wprentals_status wprentals_status_'.esc_attr($post_status).'"><div class="wprentals_status_circle"></div>'.esc_html__( 'Published','wprentals').'</div>';
}else if($post_status=='disabled'){
    $link= '';
    $status='<div class="wprentals_status wprentals_status_'.esc_attr($post_status).'"><div class="wprentals_status_circle"></div>'.esc_html__( 'Disabled','wprentals').'</div>';
}else{
    $link='';
    $status='<div class="wprentals_status wprentals_status_'.esc_attr($post_status).'"><div class="wprentals_status_circle"></div>'.esc_html__( 'Waiting for approval','wprentals').'</div>';
}



$paid_submission_status     =   esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
if ($paid_submission_status=='per listing'){
    $pay_status    = get_post_meta(get_the_ID(), 'pay_status', true);
    if($pay_status=='paid'){
        $is_pay_status.='<div class="wprentals_status wprentals_status_'.esc_attr($pay_status).'"><div class="wprentals_status_circle"></div>'.esc_html__( 'Paid','wprentals').'</div>';
    }
    if($pay_status=='not paid'){
        $is_pay_status.='<div class="wprentals_status wprentals_status_'.esc_attr($pay_status).'"><div class="wprentals_status_circle"></div>'.esc_html__( 'Not Paid','wprentals').'</div>';
    }
}


?>

<?php
    print trim($status.$is_pay_status);
    if ( !isset($show_remove_fav) ) {
      if ( $paid_submission_status=='membership' && $user_pack=='') {
          print '<div class="wprentals_status wprentals_status_Expire">'.esc_html__('Expires on','wprentals').' '.date("Y-m-d",$expiration_date).'</div>';
      }
    }
?>
