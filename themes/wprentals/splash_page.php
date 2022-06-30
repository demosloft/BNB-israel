<?php
// Template Name: Splash Page
// Wp Estate Pack 
global $post;
get_header(); 
$wpestate_options=wpestate_page_details($post->ID); 

?>

</div><!-- end content_wrapper started in header -->
</div> <!-- end class container -->
</div> <!-- end website wrapper -->


<?php 
if ( !is_user_logged_in() ) {  
    include(locate_template('templates/login_modal_form.php'));
}

$ajax_nonce = wp_create_nonce( "wprentals_ajax_filtering_nonce" );
print'<input type="hidden" id="wprentals_ajax_filtering" value="'.esc_html($ajax_nonce).'" />    ';

    
$ajax_nonce_log_reg = wp_create_nonce( "wpestate_ajax_log_reg_nonce" );
print'<input type="hidden" id="wpestate_ajax_log_reg" value="'.esc_html($ajax_nonce_log_reg).'" />    ';  


wp_footer(); 
?> 
</body>
</html>