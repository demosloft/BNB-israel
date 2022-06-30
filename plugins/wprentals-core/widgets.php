<?php
require ('widgets/twiter.php');
require ('widgets/facebook.php');
require ('widgets/contact_widget.php');
require ('widgets/social_widget.php');
require ('widgets/featured_widget.php');
require ('widgets/footer_latest_widget.php');
require ('widgets/advanced_search.php');
require ('widgets/login_widget.php');
require ('widgets/social_widget_top_bar.php');
require ('widgets/multiple_currency.php');
require ('widgets/property_categories.php');


if( !function_exists('register_wpestate_widgets') ):
 
function register_wpestate_widgets() {    
    wpestate_widgets_init();
    register_widget('Wpestate_Tweet_Widget');
    register_widget('WPestate_Facebook_Widget');
    register_widget('Wpestate_Contact_widget');
    register_widget('Wpestate_Social_widget');
    register_widget('Wpestate_Featured_widget');
    register_widget('Wpestate_footer_latest_widget');
    register_widget('Wpestate_Advanced_Search_widget');
    register_widget('Wpestate_Login_widget');
    register_widget('Wpestate_Social_widget_top');
    register_widget('Wpestate_Multiple_currency_widget');
    register_widget('Wpestate_Property_Categories');
}  

endif; // end   register_wpestate_widgets  


if( !function_exists('register_wpestate_widgets_imported') ):
function register_wpestate_widgets_imported(){
    $data = get_option('estate_imported_sidebars');
   
    if($data){
        foreach($data as $sidebar_id){
            register_sidebar(array(
                    'id'    =>  sanitize_title($sidebar_id),
                    'name'  =>  $sidebar_id,
                    'before_widget' => '<li id="%1$s" class="widget widget-container sbg_widget '.$sidebar_id.' %2$s">',
                    'after_widget'  => '</li>',
                    'before_title'  => '<h3 class="widget-title-sidebar">',
                    'after_title'   => '</h3>',
            ));
         
        }
    }
}
endif;
?>