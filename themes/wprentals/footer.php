</div><!-- end content_wrapper started in header or full_width_row from prop list -->

<?php
global $post;   
$page_template='';
if(isset($post->ID)){
    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
}

if( !is_search() && !is_category() && !is_tax() &&  !is_tag() &&  !is_archive() && wpestate_check_if_admin_page($post->ID) ){
    // do nothing for now

} else if(!is_search() && !is_category() && !is_tax() &&  !is_tag() &&  !is_archive() && $page_template== 'property_list_half.php'){
    // do nothing for now

} else if( ( is_category() || is_tax() ) &&  wprentals_get_option('wp_estate_property_list_type')==2){
    // do nothing for now

} else if(  $page_template=='advanced_search_results.php' &&  wprentals_get_option('wp_estate_property_list_type_adv')==2){
    // do nothing for now

}else{


?>

<?php
isset($post->ID) ? $post_id =$post->ID : $post_id='';
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
    get_template_part( 'templates/footer_template','', array() );
}

?>


<?php } // end property_list_half?>



</div> <!-- end class container -->



</div> <!-- end website wrapper -->


<?php wp_footer();  ?>
</body>
</html>
