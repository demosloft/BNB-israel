<?php
$current_user           =   wp_get_current_user();
$userID                 =   $current_user->ID;
$user_login             =   $current_user->user_login;
$dash_profile_main      =   wpestate_get_template_link('user_dashboard_main.php');
$add_link               =   wpestate_get_template_link('user_dashboard_add_step1.php');
$dash_profile           =   wpestate_get_template_link('user_dashboard_profile.php');
$dash_pack              =   wpestate_get_template_link('user_dashboard_packs.php');
$dash_favorite          =   wpestate_get_template_link('user_dashboard_favorite.php');
$dash_link              =   wpestate_get_template_link('user_dashboard.php');
$dash_searches          =   wpestate_get_template_link('user_dashboard_searches.php');
$dash_inbox             =   wpestate_get_template_link('user_dashboard_inbox.php');
$dash_invoice           =   wpestate_get_template_link('user_dashboard_invoices.php');
$dash_my_bookings       =   wpestate_get_template_link('user_dashboard_my_bookings.php');
$dash_my_reservations   =   wpestate_get_template_link('user_dashboard_my_reservations.php');
$dash_allinone          =   wpestate_get_template_link('user_dashboard_allinone.php');
$activemain             =   '';
$activeprofile          =   '';
$activeedit             =   '';
$activedash             =   '';
$activeadd              =   '';
$activefav              =   '';
$activesearch           =   '';
$activemypack           =   '';
$activeallinone         =   '';
$activeedit             =   '';
$activeprice            =   '';
$activedetails          =   '';
$activeimages           =   '';
$activeamm              =   '';
$activecalendar         =   '';
$activemybookins        =   '';
$activemyreservations   =   '';
$activeinbox            =   '';
$activelocation         =   '';
$activeinvoice          =   '';
$user_pack              =   get_the_author_meta( 'package_id' , $userID );
$clientId               =   esc_html( wprentals_get_option('wp_estate_paypal_client_id','') );
$clientSecret           =   esc_html( wprentals_get_option('wp_estate_paypal_client_secret','') );
$user_registered        =   get_the_author_meta( 'user_registered' , $userID );
$user_package_activation=   get_the_author_meta( 'package_activation' , $userID );
$home_url               =   esc_html( home_url('/') );

$allowed_html           =   array();
global $post;
$page_template='';
if(isset($post->ID)){
    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
}

if ($page_template == 'user_dashboard_main.php' ){
    $activemain  =   'user_tab_active';
}else if ( $page_template == 'user_dashboard.php' ){
    $activedash  =   'user_tab_active';
}else if ( $page_template == 'user_dashboard_add_step1.php' ){
    $activeadd   =   'user_tab_active';
}else if ( $page_template == 'user_dashboard_edit_listing.php' ){

    $action = sanitize_text_field ( wp_kses ( $_GET['action'],$allowed_html) );
    if ($action == 'description'){
        $activeedit   =   'user_tab_active';
    }else if ($action =='location'){
        $activelocation   =   'user_tab_active';
        $activeedit       =   '';
    }else if ($action =='price'){
        $activeprice   =   'user_tab_active';
        $activeedit    =   '';
    }else if ($action =='details'){
        $activedetails   =   'user_tab_active';
        $activeedit      =   '';
    }else if ($action =='images'){
        $activeimages   =   'user_tab_active';
        $activeedit     =   '';
    }else if ($action =='amenities'){
        $activeamm   =   'user_tab_active';
        $activeedit  =   '';
    }else if ($action =='calendar'){
        $activecalendar   =   'user_tab_active';
        $activeedit       =   '';
    }


}else if ( $page_template == 'user_dashboard_profile.php' ){
    $activeprofile   =   'user_tab_active';
}else if ( $page_template == 'user_dashboard_favorite.php' ){
    $activefav   =   'user_tab_active';
}else if( $page_template == 'user_dashboard_searches.php' ){
    $activesearch  =   'user_tab_active';
}else if( $page_template == 'user_dashboard_inbox.php' ){
    $activeinbox  =   'user_tab_active';
}else if( $page_template == 'user_dashboard_invoices.php' ){
    $activeinvoice  =   'user_tab_active';
}else if( $page_template == 'user_dashboard_my_bookings.php' ){
    $activemybookins  =   'user_tab_active';
}else if( $page_template == 'user_dashboard_my_reservations.php' ){
    $activemyreservations  =   'user_tab_active';
}else if( $page_template == 'user_dashboard_edit_listing.php' ){
    $activemyreservations  =   'user_tab_active';
}else if( $page_template == 'user_dashboard_packs.php' ){
    $activemypack  =   'user_tab_active';
}else if( $page_template =='user_dashboard_allinone.php' ){
    $activeallinone =   'user_tab_active';
}

$user_title             =   get_the_author_meta( 'title' , $userID );
$user_custom_picture    =   get_the_author_meta( 'custom_picture' , $userID );

$image_id               =   get_the_author_meta( 'small_custom_picture',$userID);
$user_small_picture     =   wp_get_attachment_image_src( $image_id, 'thumbnail');
$user_small_picture_img =   '';
if(isset($user_small_picture[0])){
    $user_small_picture_img =   $user_small_picture[0];
}
if($user_small_picture_img==''){
    $user_small_picture_img=get_stylesheet_directory_uri().'/img/default_user.png';
}

$about_me               =   get_the_author_meta( 'description' , $userID );
if($user_custom_picture==''){
    $user_custom_picture=get_stylesheet_directory_uri().'/img/default_user.png';
}
?>

<div id="user_tab_menu_trigger"><i class="fas fa-user"></i> <?php esc_html_e('User Menu','wprentals'); ?></div>
<div class="user_tab_menu col-md-3" id="user_tab_menu_container">
    <?php
        if ( wp_is_mobile() ) {
            print'<div class="user_tab_menu_close"><i class="fas fa-times"></i></div>';
        }
    ?>

    <div class="profile-image-wrapper">
        <div id="profile-image-menu"
            data-profileurl="<?php print esc_url($user_custom_picture);?>"
            data-smallprofileurl="<?php print esc_attr($image_id);?>"
            style="background-image: url('<?php print esc_url($user_small_picture_img); ?>');"></div>
        <div class="profile_wellcome"><?php print esc_html__('Hello,','wprentals').' '. esc_html($user_login);?></div>

    </div>


    <?php
    $paid_submission_status = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
    $user_pack              =   get_the_author_meta( 'package_id' , $userID );
    $user_registered        =   get_the_author_meta( 'user_registered' , $userID );
    $user_package_activation=   get_the_author_meta( 'package_activation' , $userID );
    $is_membership          =   0;
    if ($paid_submission_status == 'membership'  && wpestate_check_user_level()){
        wpestate_get_pack_data_for_user_top($userID,$user_pack,$user_registered,$user_package_activation);
        $is_membership=1;
    }


    if ( $is_membership==1){
        $stripe_profile_user    =   get_user_meta($userID,'stripe',true);
        $subscription_id        =   get_user_meta( $userID, 'stripe_subscription_id', true );
        $enable_stripe_status   =   esc_html ( wprentals_get_option('wp_estate_enable_stripe','') );
        if( $stripe_profile_user!='' && $subscription_id!='' && $enable_stripe_status==='yes'){
            echo '<span id="stripe_cancel" data-original-title="'.esc_attr__( 'subscription will be cancel at the end of current period','wprentals').'" data-stripeid="'.esc_attr($userID).'">'.esc_html__( 'cancel stripe subscription','wprentals').'</span>';
            $ajax_nonce = wp_create_nonce( "wprentals_stripe_cancel_nonce" );
            print'<input type="hidden" id="wprentals_stripe_cancel" value="'.esc_html($ajax_nonce).'" />    ';

        }
    }

    ?>


    <div class="user_dashboard_links">
          <?php
          if( $dash_profile_main!=$home_url && $dash_profile_main!=""  && wpestate_check_user_level() ){ ?>
              <a href="<?php print esc_url($dash_profile_main);?>"  class="<?php print esc_attr($activemain); ?>"><i class="fas fa-chart-line"></i><?php esc_html_e('Dashboard','wprentals');?></a>
          <?php } ?>

        <?php if( $dash_profile!=$home_url && $dash_profile!="" ){ ?>
            <a href="<?php print esc_url($dash_profile);?>"  class="<?php print esc_attr($activeprofile); ?>"><i class="fas fa-user"></i> <?php esc_html_e('My Profile','wprentals');?></a>
        <?php } ?>
        <?php if( $dash_pack!=$home_url && $dash_pack!="" && $paid_submission_status=='membership' && wpestate_check_user_level() ){ ?>
            <a href="<?php print esc_url($dash_pack);?>" class="<?php print esc_attr($activemypack); ?>"><i class="fas fa-tasks"></i> <?php esc_html_e('My Subscription','wprentals');?></a>
        <?php } ?>
        <?php if( $dash_link!=$home_url  && $dash_link!=""   && wpestate_check_user_level()){ ?>
            <a href="<?php print esc_url($dash_link);?>"     class="<?php print esc_attr($activedash); ?>"> <i class="fas fa-map-marker"></i> <?php esc_html_e('My Listings','wprentals');?></a>
        <?php } ?>
        <?php if( $add_link!=$home_url  && $add_link!=""  && wpestate_check_user_level()){
              $edit_class="";?>

            <a href="<?php print esc_url($add_link);?>"      class="<?php print esc_attr($activeadd); print esc_attr($edit_class); ?>"> <i class="fas fa-plus"></i> <?php esc_html_e('Add New Listing','wprentals');?></a>


            <?php

            if ( isset($_GET['listing_edit'] ) && is_numeric($_GET['listing_edit'])) {
                $edit_class         =   " edit_class ";
                $post_id            =   intval($_GET['listing_edit']);
                $edit_link          =   wpestate_get_template_link('user_dashboard_edit_listing.php');
                $edit_link          =   esc_url_raw ( add_query_arg( 'listing_edit', $post_id, $edit_link) ) ;
                $edit_link_desc     =   esc_url_raw ( add_query_arg( 'action', 'description', $edit_link) ) ;
                $edit_link_location =   esc_url_raw ( add_query_arg( 'action', 'location', $edit_link) ) ;
                $edit_link_price    =   esc_url_raw ( add_query_arg( 'action', 'price', $edit_link) ) ;
                $edit_link_details  =   esc_url_raw ( add_query_arg( 'action', 'details', $edit_link) ) ;
                $edit_link_images   =   esc_url_raw ( add_query_arg( 'action', 'images', $edit_link) ) ;
                $edit_link_amenities =  esc_url_raw ( add_query_arg( 'action', 'amenities', $edit_link) );
                $edit_link_calendar =   esc_url_raw ( add_query_arg( 'action', 'calendar', $edit_link) );
            ?>


            <div class=" property_edit_menu">
                <a href="<?php print esc_url($edit_link_desc);?>"        class="edit_listing_link <?php print esc_attr($activeedit);?>">        <?php esc_html_e('Description','wprentals');?></a>
                <a href="<?php print esc_url($edit_link_price);?>"       class="edit_listing_link <?php print esc_attr($activeprice);?>">       <?php esc_html_e('Price','wprentals');?></a>
                <a href="<?php print esc_url($edit_link_images);?>"      class="edit_listing_link <?php print esc_attr($activeimages);?>">      <?php esc_html_e('Images','wprentals');?></a>
                <a href="<?php print esc_url($edit_link_details);?>"     class="edit_listing_link <?php print esc_attr($activedetails);?>">     <?php esc_html_e('Details','wprentals');?></a>
                <a href="<?php print esc_url($edit_link_location);?>"    class="edit_listing_link <?php print esc_attr($activelocation);?>">    <?php esc_html_e('Location','wprentals');?></a>
                <a href="<?php print esc_url($edit_link_amenities);?>"   class="edit_listing_link <?php print esc_attr($activeamm);?>">         <?php esc_html_e('Amenities','wprentals');?></a>
                <a href="<?php print esc_url($edit_link_calendar);?>"    class="edit_listing_link <?php print esc_attr($activecalendar);?>">    <?php esc_html_e('Calendar','wprentals');?></a>
            </div>

        <?php
        } // secondary level
        ?>
        <?php } ?>


        <?php if( $dash_allinone!=$home_url && $dash_allinone!=""   && wpestate_check_user_level() ){ ?>
            <a href="<?php print esc_url($dash_allinone);?>" class="<?php print esc_attr($activeallinone); ?>"><i class="far fa-calendar-alt"></i> <?php esc_html_e('All In One Calendar','wprentals');?></a>
        <?php } ?>

        <?php if( $dash_favorite!=$home_url && $dash_favorite!=""){ ?>
            <a href="<?php print esc_url($dash_favorite);?>" class="<?php print esc_attr($activefav); ?>"><i class="fas fa-heart"></i> <?php esc_html_e('Favorites','wprentals');?></a>
        <?php } ?>

        <?php if( $dash_my_bookings!=$home_url && $dash_my_bookings!=""  && wpestate_check_user_level()){ ?>
            <a href="<?php print esc_url($dash_my_bookings);?>" class="<?php print esc_attr($activemybookins); ?>"><i class="far fa-folder-open"></i> <?php esc_html_e('My Bookings','wprentals');?></a>
        <?php } ?>
        <?php if( $dash_my_reservations!=$home_url  && $dash_my_reservations!="" ){ ?>
            <a href="<?php print esc_url($dash_my_reservations);?>" class="<?php print esc_attr($activemyreservations); ?>"><i class="fas fa-folder-open"></i> <?php esc_html_e('My Reservations','wprentals');?></a>
        <?php } ?>

        <?php if( $dash_inbox!=$home_url  && $dash_inbox!=""){
            $no_unread=  intval(get_user_meta($userID,'unread_mess',true));?>
            <a href="<?php print esc_url($dash_inbox);?>" class="<?php print esc_attr($activeinbox); ?>"><div class="unread_mess_wrap_menu"><?php print trim($no_unread);?></div><i class="fas fa-inbox"></i> <?php esc_html_e('My Inbox','wprentals');?></a>
        <?php } ?>

        <?php if( $dash_invoice!=$home_url && $dash_invoice!="" && wpestate_check_user_level()  ){ ?>
            <a href="<?php print esc_url($dash_invoice);?>" class="<?php print esc_attr($activeinvoice); ?>"><i class="far fa-file"></i> <?php esc_html_e('Invoices','wprentals');?></a>
        <?php } ?>

        <a href="<?php echo wp_logout_url(wpestate_wpml_logout_url());?>" title="Logout"><i class="fas fa-power-off"></i> <?php esc_html_e('Log Out','wprentals');?></a>
    </div>
</div>
