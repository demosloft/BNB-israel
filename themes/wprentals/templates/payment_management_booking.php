<?php
global $post;
global $wpestate_where_currency;
global $wpestate_currency;
$title              =   get_the_title($post->ID);
$link               =   esc_url(get_permalink());
$booking_status     =   get_post_meta($post->ID, 'booking_status', true);
$property_id        =   get_post_meta($post->ID, 'booking_id', true);
$property_title     =   get_the_title($property_id);
$property_url       =   esc_url ( get_permalink($property_id) );
$booking_from_date  =   get_post_meta($post->ID, 'booking_from_date', true);
$booking_to_date    =   get_post_meta($post->ID, 'booking_to_date', true);
$booking_guests     =   get_post_meta($post->ID, 'booking_guests', true);
$booking_status     =   get_post_meta($post->ID, 'booking_status', true);
$preview            =   wp_get_attachment_image_src(get_post_thumbnail_id($property_id), 'wpestate_blog_unit');
$author             =   get_the_author();
$author_id          =   get_the_author_meta('ID');
$userid_agent       =   get_user_meta($author_id, 'user_agent_id', true);
$security_deposit   =   floatval( get_post_meta(  $post->ID,'security_deposit',true) );
$invoice_no         =   get_post_meta($post->ID, 'booking_invoice_no', true);
$total_price        =   get_post_meta($invoice_no, 'item_price', true);
$to_be_paid         =   floatval ( get_post_meta($invoice_no, 'depozit_to_be_paid', true));
$full_pay_invoice_id=   floatval( get_post_meta(  $post->ID,'full_pay_invoice_id',true) );

if($invoice_no== 0){
    $invoice_no='-';
    $total_price='-';
}
if($full_pay_invoice_id==0){
    $full_pay_invoice_id='-';
}

$service_fee     =  get_post_meta($post->ID, 'service_fee', true);
$booking_taxes   =  get_post_meta($post->ID, 'booking_taxes', true);
$youearned       =  get_post_meta($post->ID, 'youearned', true);

?>

<div class="col-md-1">
    <?php print intval($post->ID); ?>
</div>
<div class="col-md-1">
    <?php print esc_html($booking_status); ?>
</div>
<div class="col-md-2">
    <?php echo '<a href="'.esc_url($property_url).'" target="_blank">'.esc_html($property_title).'</a>'; ?>
</div>

<div class="col-md-2">
    <?php print esc_html($booking_from_date.' - '.$booking_to_date); ?>
</div>

<div class="col-md-1">
    <?php print esc_html($booking_guests);?>
</div>
 
<div class="col-md-1">
    <?php print esc_html($invoice_no);?>
</div>

<div class="col-md-1">
    <?php print esc_html($total_price);?>
</div>

<div class="col-md-1">
    <?php print esc_html($to_be_paid);?>
</div>

<div class="col-md-1">
    <?php print esc_html($security_deposit);?>
</div>

<div class="col-md-1">
    <?php print esc_html($full_pay_invoice_id);?>
</div>

<div class="col-md-12 actions_row">
 
    <?php
        print esc_html__('service fee','wprentals').' '.esc_html($service_fee).' '.esc_html__('taxes:','wprentals').' '.esc_html($booking_taxes).' '.esc_html__('youearned','wprentals').esc_html($youearned);     
        $unix_start_book = strtotime($booking_from_date);   
        if( time() < $unix_start_book ){
            print '  <span class="pay_owner">'. esc_html__('Pay Owner- Disabled until ','wprentals').esc_html($booking_from_date).'</span>';
        }else{
            print '  <span class="pay_owner">'. esc_html__('Pay Owner','wprentals').'</span>';
        }
    ?>

    <?php
        $unix_stop_book = strtotime($booking_to_date);   
        if( time() < ( $unix_start_book+7*24*60*60 ) ){
            print '  <span class="refund_buyer">'. esc_html__('Refund Security Deposit- Disabled until ','wprentals').esc_html($booking_to_date).'</span>';
        }else{
            print '  <span class="refund_buyer">'. esc_html__('Refund Security Deposit','wprentals').'</span>';
        }
    ?>
    <span class="refund_buyer"><?php esc_html_e('Refund Renter','wprentals');?></span>
   <?php 
        if($to_be_paid>0){
            print ' <span class="issue_final_invoice">'.esc_html__('Issue Final Invoice','wprentals').'</span>';
        } 
    ?>
</div>