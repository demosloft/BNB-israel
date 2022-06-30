<!-- begin sidebar -->
<?php  
if( isset($wpestate_options['sidebar_class']) && ('no sidebar' != $wpestate_options['sidebar_class']) && ('' != $wpestate_options['sidebar_class'] ) && ('none' != $wpestate_options['sidebar_class']) ){
?>    
    <ul class="xoxo listingsidebar">
        <?php generated_dynamic_sidebar( $wpestate_options['sidebar_name'] ); ?>
    </ul>
<?php
}
?>
<!-- end sidebar -->