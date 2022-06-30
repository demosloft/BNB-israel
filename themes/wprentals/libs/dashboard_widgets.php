<?php

////////////////////////////////////////////////
//Personalize Your Website dashboard admin widget
/////////////////////////////////////////////////

function wpestate_add_welcome_widget(){

    $logo                     =   esc_html(  wprentals_get_option('wp_estate_logo_image', 'url') );
    print '<div class="dashboard_widget_exp">'.__('Your Current Logo','wprentals').'</div>';
    if ($logo != '') {
        print '<img class="dashboard_widget_logo admin_widget_logo" src="'.esc_url($logo).'" class="img-responsive retina_ready"  alt="'.esc_html__('logo','wprentals').'"/>';
    } else {
        print '<img class="img-responsive admin_widget_logo retina_ready" src="'.get_template_directory_uri() . '/img/logo.png" alt="'.esc_html__('logo','wprentals').'"/>';
    }
    print '<a class="wpestate_admin_button reverse_but" href="'.esc_url( home_url('/') ).'wp-admin/admin.php?page=WpRentals&tab=4" style="float:right;">'.__('Upload New Logo','wprentals').'</a>';



    print '<div class=" widget_content_wrapper" >';
        print '<div class="dashboard_widget_exp" style="margin-top:40px;">'.__('Your Current Colors','wprentals').'</div>';

        $main_color                     =   esc_html ( wprentals_get_option('wp_estate_main_color','') );
        $background_color               =   esc_html( wprentals_get_option('wp_estate_background_color', '') );
        $font_color                     =   esc_html( wprentals_get_option('wp_estate_content_font_color', '') );
        $header_color                   =   esc_html( wprentals_get_option('wp_estate_header_color', '') );
        $breadcrumbs_font_color         =   esc_html(wprentals_get_option('wp_estate_breadcrumbs_font_color', '') );
        $font_color                     =   esc_html(wprentals_get_option('wp_estate_font_color', '') );

        if($main_color==''){
            $main_color='b881fc';
        }
        if($background_color==''){
            $background_color='ffffff';
        }

        if($font_color==''){
            $font_color='8A8F9A';
        }

        print '<div class="dasboard_widget_color_wrapper">';
        print '<div class="dasboard_widget_color" style="background-color:'.$main_color.'"></div>';
        print '<div class="dasboard_widget_color" style="background-color:'.$background_color.'"></div>';
        print '<div class="dasboard_widget_color" style="background-color:'.$font_color.'"></div>';
        print '<div class="dasboard_widget_color" style="background-color:'.$main_color.'"></div>';
        print '<div class="dasboard_widget_color" style="background-color:'.$main_color.'"></div>';
        print '<div class="more_colors" style="background-color:#fff">...</div>';
        print '</div>' ;
        $my_theme = wp_get_theme();

        print '<a class="wpestate_admin_button reverse_but" href="'.esc_url( home_url('/') ).'wp-admin/admin.php?page='. str_replace(' ', '', $my_theme->get( 'Name' )).'&tab=28">'.__('Change Colors','wprentals').'</a>';
    print '</div>';
}


//End Personalize Your Website dashboard admin widget


//////////////////////////////////////////
//Add New Property dashboard admin widget
//////////////////////////////////////////
function wpestate_add_new_property_widget(){
    $current_user = wp_get_current_user();
    $userID                 =   $current_user->ID;
    $current_site_level     =   get_option('wpestate_mem_level',true);
    $levels                 =   wpestate_world_return_levels();
    $current_listings       =   wpestate_how_many_lisitings();
    $add_link               =   wpestate_get_template_link('user_dashboard_add_step1.php');

    print'<div class="dashboard_widget_exp">';
    printf(__('You have  %1$d listings published:','wprentals'),$current_listings);
    print '</div>';

    $listings = wpestate_my_lisitings();
    print '<ul class="dashboard-widget-list">';
    foreach($listings as $listing){
        print '<li><a href="'.esc_url($listing['url']).'" target="_blank">'.$listing['title'].'</a></li>';
    }
    print '<li>...</li>';
    print '</ul>';

    print '<a class="wpestate_admin_button reverse_but" style="margin-top:10px;" href="'.esc_url($add_link).'">'.__('Add New Property','wprentals').'</a>';


}

//End Add New Property dashboard admin widget


//////////////////////////////////////////
//Add New Page dashboard admin widget
//////////////////////////////////////////
function wpestate_add_new_page_widget(){

    $current_pages      =   wpestate_how_many_pages();

    print'<div class="dashboard_widget_exp">';
        printf(__('You have  %1$d pages published.','wprentals'),$current_pages);
    print '</div>';

    $pages_list = wpestate_get_all_page_templates();

    //////////////////////////////////////////////////////////////// initial showing
    $select_list    =   '';
    $example_list   =   '';

    foreach($pages_list as $key=>$page){
        $select_list    .=  '<option value="'.$page['wp_template'].'">'.$page['name'].'</value>';
    }


    print '<form action = "" method="post">';
        wp_nonce_field( 'wpestate_add_page_title', 'wpestate_add_page_title_nonce' );
        print ' <div class="add_form_wrapper">
                    <label class="new_page_title" for="new_page_title">'.__('Page Title','wprentals').'</label>
                    <input type="text" id="new_page_title" name="new_page_title" class="form-input-tip ">
                </div>';

        print ' <div class="add_form_wrapper">
                    <label class="new_page_title" for="new_page_title">'.__('Page Template','wprentals').'</label>
                    <select class="" id="widget_new_page_template" name="new_page_template">
                    '.$select_list.'
                    </select>
                </div>';
        print '<div class="see_pages_dash"><a href="'.esc_url( home_url('/') ).'wp-admin/edit.php?post_type=page">'.__('See All Your Pages','wprentals').'</a></div>';
        print ' <div class="">
                    <input type="submit" id="submit_new_page"  class="wpestate_admin_button reverse_but" Value="'.__('Add New Page','wprentals').'">
                </div>';

        print '</form>';

}


function wpestate_get_all_page_templates(){
    $pages_list = array();

    $pages_list[]=array(
        'name'          =>  'Advanced Search Results',
        'wp_template'   =>  'advanced_search_results.php',

    );
    $pages_list[]=array(
        'name'          =>  'All in One Calendar',
        'wp_template'   =>  'user_dashboard_allinone.php',

    );

       $pages_list[]=array(
        'name'          =>  'Blog list page',
        'wp_template'   =>  'blog_list.php',

    );

    $pages_list[]=array(
        'name'          =>  'Contact Page',
        'wp_template'   =>  'contact_page.php',

    );


    $pages_list[]=array(
        'name'          =>  'ICAL FEED',
        'wp_template'   =>  'ical.php',

    );

    $pages_list[]=array(
        'name'          =>  'Owner list',
        'wp_template'   =>  'agents_list.php',

    );

    $pages_list[]=array(
        'name'          =>  'Paypal Processor',
        'wp_template'   =>  'processor.php',

    );

    $pages_list[]=array(
        'name'          =>  'Properties list',
        'wp_template'   =>  'property_list.php',

    );

    $pages_list[]=array(
        'name'          =>  'Properties list half',
        'wp_template'   =>  'property_list_half.php',

    );

    $pages_list[]=array(
        'name'          =>  'RentalsClub API',
        'wp_template'   =>  'rentalsclub.php',

    );

    $pages_list[]=array(
        'name'          =>  'Splash Page',
        'wp_template'   =>  'splash_page.php',

    );

    $pages_list[]=array(
        'name'          =>  'Stripe Charge Page',
        'wp_template'   =>  'stripecharge.php',

    );

    $pages_list[]=array(
        'name'          =>  'Terms and Conditions',
        'wp_template'   =>  'terms_conditions.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard',
        'wp_template'   =>  'user_dashboard.php',

    );


    $pages_list[]=array(
        'name'          =>  'User Dashboard Submit - Step 1',
        'wp_template'   =>  'user_dashboard_add_step1.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard Edit',
        'wp_template'   =>  'user_dashboard_edit_listing.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard Favorite',
        'wp_template'   =>  'user_dashboard_favorite.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard Inbox',
        'wp_template'   =>  'user_dashboard_inbox.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard Invoices',
        'wp_template'   =>  'user_dashboard_invoices.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard My Reservations',
        'wp_template'   =>  'user_dashboard_my_reservations.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard My Bookings',
        'wp_template'   =>  'user_dashboard_my_bookings.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard Profile Page',
        'wp_template'   =>  'user_dashboard_profile.php',

    );

    $pages_list[]=array(
        'name'          =>  'User Dashboard Subscriptions',
        'wp_template'   =>  'user_dashboard_packs.php',

    );
    $pages_list[]=array(
        'name'          =>  'User Dashboard Main',
        'wp_template'   =>  'user_dashboard_main.php',

    );

    return $pages_list;


}



function wpestate_create_new_page($title,$slug){

    $my_post = array(
        'post_title'    => $title,
        'post_type'     => 'page',
        'post_status'   => 'publish',
    );


    $new_id = wp_insert_post($my_post);
    if($slug!=''){
        update_post_meta($new_id, '_wp_page_template',$slug);
    }

    update_post_meta($new_id, 'sidebar_option','none');
    update_post_meta($new_id, 'page_show_title','no');

    return $new_id;

}




add_action ('wp_loaded', 'wpestate_newpage_create_and_redirect');
function wpestate_newpage_create_and_redirect(){
    $pages_list = wpestate_get_all_page_templates();


    if(  isset($_POST)  && isset($_POST['new_page_title']) && $_POST['new_page_title']!='' ) {

        if (    ! isset( $_POST['wpestate_add_page_title_nonce'] )  || ! wp_verify_nonce( $_POST['wpestate_add_page_title_nonce'], 'wpestate_add_page_title' ) ) {
            exit();
        }
        $title          =   sanitize_text_field($_POST['new_page_title']);
        $slug           =   sanitize_text_field($_POST['new_page_template']);
        $new_page_id    =   wpestate_create_new_page($title,$slug);
        wp_redirect(  get_edit_post_link($new_page_id,'x') );
        exit();
    }
}




function wpestate_add_payments_widget(){


    $paypal_status                  =   esc_html( wprentals_get_option('wp_estate_paypal_api','') );
    print '<div class="dashboard_widget_exp">';
    if($paypal_status=='sandbox'){
        print esc_html__('Your Payment system is in SANDBOX mode.','wprentals');
    }else{
        print esc_html__('Your Payment system is in LIVE mode.','wprentals');
    }
    print '</div>';



    $submission_curency             =   esc_html( wprentals_get_option('wp_estate_submission_curency','') );
    $currency_label_main            =   esc_html( wprentals_get_option('wp_estate_currency_label_main','') );

    print '<div class="dashboard_widget_exp">';
    printf( __('Payments will be procesed in %1$s. Prices are displayed in %2$s','wprentals'),'<strong>'.$submission_curency.'</strong>','<strong>'.$currency_label_main.'</strong>');
    print'</div>';

    $paypal_client_id               =   esc_html( wprentals_get_option('wp_estate_paypal_client_id','') );
    $paypal_client_secret           =   esc_html( wprentals_get_option('wp_estate_paypal_client_secret','') );
    $paypal_rec_email               =   esc_html( wprentals_get_option('wp_estate_paypal_rec_email','') );

    if($paypal_client_id=='' || $paypal_client_secret=='' || $paypal_rec_email=='' ){
        print '<div class="dashboard_widget_exp">'.__('You did not add your <strong>Paypal Details</strong>. No Paypal payment will be processed','wprentals').'</div>';
    }else{
        print '<div class="dashboard_widget_exp">'.__('Paypal Api Keys are added.Payments will be processed.','wprentals').'</div>';
    }

    $stripe_secret_key              =   esc_html( wprentals_get_option('wp_estate_stripe_secret_key','') );
    $stripe_publishable_key         =   esc_html( wprentals_get_option('wp_estate_stripe_publishable_key','') );

    if($stripe_secret_key=='' || $stripe_publishable_key=='' ){
        print '<div class="dashboard_widget_exp">'.__('You did not add your <strong>Stripe Details</strong>. No Stripe payment will be processed','wprentals').'</div>';
    }else{
        print '<div class="dashboard_widget_exp">'.__('Stripe Api Keys are added.Payments will be processed.','wprentals').'</div>';
    }

    $my_theme=wp_get_theme();
    print '<a class="wpestate_admin_button reverse_but" href="'.esc_url( home_url('/') ).'wp-admin/admin.php?page='.str_replace(' ', '', $my_theme->get( 'Name' )).'&tab=39" >'.__('Edit Payment Details','wprentals').'</a>';

}

//End Add New Page dashboard admin widge
