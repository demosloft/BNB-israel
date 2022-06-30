<!-- begin sidebar -->
<div class="clearfix visible-xs"></div>
<?php  
$sidebar_class='';

if( is_front_page() ){
    $sidebar_class.= '  '; 
}

if( is_singular('post')){
    $sidebar_class.= ' sidebar_post ';
}

if( ('no sidebar' != $wpestate_options['sidebar_class']) && ('' != $wpestate_options['sidebar_class'] ) && ('none' != $wpestate_options['sidebar_class']) ){ ?>    
    <div class="col-xs-12 <?php print esc_attr($wpestate_options['sidebar_class']).' '.esc_attr($sidebar_class ); ?> widget-area-sidebar" id="primary" >
        
        <?php  
            if ( class_exists( 'WooCommerce' ) &&  is_checkout() ) {
                global $wpestate_global_payments;
                $wpestate_global_payments->show_checkout();
              
            }else{
        ?>
        
            <ul class="xoxo">
                <?php generated_dynamic_sidebar( $wpestate_options['sidebar_name'] ); ?>
            </ul>
        
        <?php 
            }
        ?>
        
    </div>   
<?php
}
?>
<!-- end sidebar -->