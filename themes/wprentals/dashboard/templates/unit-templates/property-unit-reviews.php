<?php
if(wpestate_has_some_review($post->ID)!==0){
     print wpestate_display_property_rating( $post->ID );
 }else{
     print '<div class=rating_placeholder>'.esc_html__('No Reviews','wprentals').'</div>';
 }
 ?>
