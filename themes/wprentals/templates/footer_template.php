<?php
global $post;
$page_template='';
if(isset($post->ID)){
    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
}
$footer_background          =   wprentals_get_option('wp_estate_footer_background','url');
$repeat_footer_back_status  =   wprentals_get_option('wp_estate_repeat_footer_back');
$footer_style               =   '';
$footer_back_class          =   '';
$wide_footer                =   wprentals_get_option('wp_estate_wide_footer');

if ($footer_background!=''){
    $footer_style='style=" background-image: url('.esc_url($footer_background).') "';
}

if( $repeat_footer_back_status=='repeat' ){
    $footer_back_class = ' footer_back_repeat ';
}else if( $repeat_footer_back_status=='repeat x' ){
    $footer_back_class = ' footer_back_repeat_x ';
}else if( $repeat_footer_back_status=='repeat y' ){
    $footer_back_class = ' footer_back_repeat_y ';
}else if( $repeat_footer_back_status=='no repeat' ){
    $footer_back_class = ' footer_back_repeat_no ';
}


if( !is_search() && !is_category() && !is_tax() &&  !is_tag() &&  !is_archive() && wpestate_check_if_admin_page($post->ID) ){
    // do nothing for now

} else if(!is_search() && !is_category() && !is_tax() &&  !is_tag() &&  !is_archive() && $page_template == 'property_list_half.php'){
    // do nothing for now

} else if( ( is_category() || is_tax() ) &&  wprentals_get_option('wp_estate_property_list_type')==2){
    // do nothing for now

} else if(  $page_template=='advanced_search_results.php' &&  wprentals_get_option('wp_estate_property_list_type_adv')==2){
    // do nothing for now

}else{


?>


<footer id="colophon" <?php print wp_kses_post($footer_style); ?> class=" <?php print esc_attr($footer_back_class);?> ">
    <?php
        $wide_footer_class='';
        if($wide_footer=='yes'){
            $wide_footer_class=" wide_footer ";
        }
        ?>

    <div id="footer-widget-area" class="row <?php print esc_attr($wide_footer_class);?>">
        <?php  get_sidebar('footer');?>
    </div><!-- #footer-widget-area -->

    <div class="sub_footer">
        <div class="sub_footer_content <?php print esc_attr($wide_footer_class);?>">
            <span class="copyright">
                <?php
                if (function_exists('icl_translate') ){
                   $property_copy_text      =   icl_translate('wprentals','wp_estate_property_copyright_text', stripslashes ( esc_html( wprentals_get_option('wp_estate_copyright_message') ) ) );
                   print trim($property_copy_text);
                }else{
                    print stripslashes ( esc_html (wprentals_get_option('wp_estate_copyright_message') ) );
                }
                ?>
            </span>

            <div class="subfooter_menu">
                <?php
                    if(wprentals_get_option('wp_estate_support','')=='yes'){

                        if( is_front_page() || is_tax() ){
                            print '<a class="wpestate_support_link" href="https://wpestate.org" target="_blank">WpEstate</a>';
                        }
                    }

                    wp_nav_menu( array(
                        'theme_location'    => 'footer_menu',
                        'depth'             => 1
                    ));
                ?>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->

<?php } // end property_list_half ?>