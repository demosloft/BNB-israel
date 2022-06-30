<?php
$price_label                =   esc_html ( get_post_meta($post_id, 'property_label', true) );
$price                      =   intval( get_post_meta($post->ID, 'property_price', true) );

if ($price != 0) {
   $price =   number_format($price,0,'.',$th_separator);

   if ($wpestate_where_currency == 'before') {
       $price_title =   $currency_title . ' ' . $price;
       $price       =   $wpestate_currency . ' ' . $price;
   } else {
       $price_title = $price . ' ' . $currency_title;
       $price       = $price . ' ' . $wpestate_currency;
   }
}else{
    $price='';
    $price_title='';
}


if($price_title!=''){
    print  '<span class="price_label"> '. esc_html($price_title).' '.esc_html($price_label).'</span>' ;
}

?>
