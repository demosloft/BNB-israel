<div class="mobilewrapper" id="mobilewrapper_links">
    <div class="snap-drawers">
        <!-- Left Sidebar-->
        <div class="snap-drawer snap-drawer-left">
            <div class="mobilemenu-close"><i class="fas fa-times"></i></div>
            <?php  wp_nav_menu( array(
                    'theme_location' => 'mobile',
                    'container' => false,
                    'menu_class'      => 'mobilex-menu',
                ) );?>

        </div>
  </div>
</div>
<?php global $wpestate_social_login;?>

<div class="mobilewrapper-user" id="mobilewrapperuser">
    <div class="snap-drawers">

    <!-- Right Sidebar-->
        <div class="snap-drawer snap-drawer-right">

        <div class="mobilemenu-close-user"><i class="fas fa-times"></i></div>
        <?php
        $current_user               =   wp_get_current_user();

        if ( 0 != $current_user->ID  && is_user_logged_in() ) {
            $username               =   $current_user->user_login ;
            $userID                 =   $current_user->ID;
            $dash_main              =   wpestate_get_template_link('user_dashboard_main.php');
            $add_link               =   wpestate_get_template_link('user_dashboard_add_step1.php');
            $dash_profile           =   wpestate_get_template_link('user_dashboard_profile.php');
            $dash_pack              =   wpestate_get_template_link('user_dashboard_packs.php');
            $dash_favorite          =   wpestate_get_template_link('user_dashboard_favorite.php');
            $dash_link              =   wpestate_get_template_link('user_dashboard.php');
            $dash_searches          =   wpestate_get_template_link('user_dashboard_searches.php');
            $dash_reservation       =   wpestate_get_template_link('user_dashboard_my_reservations.php');
            $dash_bookings          =   wpestate_get_template_link('user_dashboard_my_bookings.php');
            $dash_inbox             =   wpestate_get_template_link('user_dashboard_inbox.php');
            $dash_invoices          =   wpestate_get_template_link('user_dashboard_invoices.php');
            $logout_url             =   wp_logout_url(wpestate_wpml_logout_url());
            $home_url               =   esc_html( home_url('/') );
            $no_unread=  intval(get_user_meta($userID,'unread_mess',true));
            ?>
            <ul class="user_mobile_menu_list">
                 <?php
                    global $wpestate_global_payments;
                    if(  class_exists( 'WooCommerce' ) ){
                        $wpestate_global_payments->show_cart_icon_mobile();
                    }
                    ?>
                <?php
                if( wpestate_check_user_level() ){ ?>
                  <li><a href="<?php print esc_url($dash_main);?>" >   <i class="fas fa-chart-line"></i><?php esc_html_e('Dashboard','wprentals');?></a></li>
                <?php } ?>

                <li><a href="<?php print esc_url($dash_profile);?>"> <i class="fas fa-cog"></i><?php esc_html_e('My Profile','wprentals');?></a></li>
                <?php if( wpestate_check_user_level() ){ ?>
                    <li><a href="<?php print esc_url($dash_pack);?>">    <i class="fas fa-tasks"></i> <?php esc_html_e('My Subscription','wprentals');?></a></li>
                <?php } ?>


                <?php if( wpestate_check_user_level() ) { ?>
                    <li><a href="<?php print esc_url($dash_link);?>" ><i class="fas fa-map-marker"></i><?php esc_html_e('My Listings','wprentals');?></a></li>
                    <li><a href="<?php print esc_url($add_link);?>" ><i class="fas fa-plus"></i><?php esc_html_e('Add New Listing','wprentals');?></a></li>
                <?php } ?>
                <li><a href="<?php print esc_url($dash_favorite);?>" class="active_fav"><i class="fas fa-heart"></i><?php esc_html_e('Favorites','wprentals');?></a></li>
                <li><a href="<?php print esc_url($dash_reservation);?>" class="active_fav"><i class="fas fa-folder-open"></i><?php esc_html_e('Reservations','wprentals');?></a></li>
                <?php if( wpestate_check_user_level() ) { ?>
                    <li><a href="<?php print esc_url($dash_bookings);?>" class="active_fav"><i class="far fa-folder-open"></i><?php esc_html_e('Bookings','wprentals');?></a></li>
                <?php } ?>
                <li><a href="<?php print esc_url($dash_inbox);?>" class="active_fav"><div class="unread_mess_wrap_menu"><?php print intval($no_unread);?></div><i class="fas fa-inbox"></i><?php esc_html_e('Inbox','wprentals');?></a></li>
                <?php if( wpestate_check_user_level() ) { ?>
                    <li><a href="<?php print esc_url($dash_invoices);?>" class="active_fav"><i class="far fa-file"></i><?php esc_html_e('Invoices','wprentals');?></a></li>
                <?php } ?>

                <li><a href="<?php echo wp_logout_url(wpestate_wpml_logout_url());?>" title="Logout" class="menulogout"><i class="fas fa-power-off"></i><?php esc_html_e('Log Out','wprentals');?></a></li>

            </ul>

    <?php }else{

        $mess='';

        print '
        <div class="login_sidebar_mobile">
            <h3 class="widget-title-sidebar"  id="login-div-title-mobile">'.esc_html__( 'Login','wprentals').'</h3>
            <div class="login_form" id="login-div-mobile">
                <div class="loginalert" id="login_message_area_wd_mobile" >'.esc_html($mess).'</div>
                <input type="text" class="form-control" name="log" id="login_user_wd_mobile" placeholder="'.esc_html__( 'Username','wprentals').'"/>
                
                <div class="password_holder">
                    <input type="password" class="form-control" name="pwd" id="login_pwd_wd_mobile" placeholder="'.esc_html__( 'Password','wprentals').'"/>
                    <i class=" far fa-eye-slash show_hide_password"></i>
                </div>     
                
                <input type="hidden" name="loginpop" id="loginpop_mobile" value="0">
                <input type="hidden" id="security-login-mobile" name="security-login-mobile" value="'. estate_create_onetime_nonce( 'login_ajax_nonce_mobile' ).'">
                <button class="wpb_button  wpb_btn-info  wpb_regularsize   wpestate_vc_button  vc_button" id="wp-login-but-wd-mobile">'.esc_html__( 'Login','wprentals').'</button>
                <div class="login-links">
                    <a href="#" id="widget_register_mobile">'.esc_html__( 'Need an account? Register here!','wprentals').'</a>
                    <a href="#" id="forgot_pass_widget_mobile">'.esc_html__( 'Forgot Password?','wprentals').'</a>
                </div> ';
                if(class_exists('Wpestate_Social_Login')){
                    $wpestate_social_login->display_form('mobile',0);
                }
                print '
            </div>

              <h3 class="widget-title-sidebar"  id="register-div-title-mobile">'.esc_html__( 'Register','wprentals').'</h3>
                <div class="login_form" id="register-div-mobile">
                    <div class="loginalert" id="register_message_area_wd_mobile" ></div>
                    <input type="text" name="user_login_register" id="user_login_register_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Username','wprentals').'"/>';

            $enable_user_pass_status= esc_html ( wprentals_get_option('wp_estate_enable_user_pass','') );
            if($enable_user_pass_status == 'yes'){
                print   '<input type="text" name="user_email_register" id="user_email_register_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Email','wprentals').'"  />';
                
                print   '<div class="password_holder"><input type="password" name="user_password" id="user_password_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Password','wprentals').'" size="20" />   <i class=" far fa-eye-slash show_hide_password"></i></div>';
                print   '<div class="password_holder"><input type="password" name="user_password_retype" id="user_password_retype_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Retype Password','wprentals').'" size="20" />   <i class=" far fa-eye-slash show_hide_password"></i></div>';
            }else{
                print'    <input type="text" name="user_email_register" id="user_email_register_wd_mobile" class="form-control" placeholder="'.esc_html__( 'Email','wprentals').'"  />';
            }


            $wp_estate_enable_user_phone= esc_html ( wprentals_get_option('wp_estate_enable_user_phone','') );


            if($wp_estate_enable_user_phone=='yes'){
                print '<input type="text" name="user_phone_register" id="user_phone_register_wd_mobile" class="form-control" placeholder="'.esc_html__( '','wprentals').'" size="20" />';
            }


                    $separate_users_status= esc_html ( wprentals_get_option('wp_estate_separate_users','') );
                    if($separate_users_status=='yes'){
                        print'
                        <div class="acc_radio">
                        <input type="radio" name="acc_type" id="acctype0" value="1" checked required>
                        <div class="radiolabel" for="acctype0">'.esc_html__('I only want to book','wprentals').'</div><br>
                        <input type="radio" name="acc_type" id="acctype1" value="0" required>
                        <div class="radiolabel" for="acctype1">'.esc_html__('I want to rent my property','wprentals').'</div></div> ';
                    }

                    print'<input type="checkbox" name="terms" id="user_terms_register_wd_mobile"><label id="user_terms_register_wd_label_mobile" for="user_terms_register_wd_mobile">'.esc_html__( 'I agree with ','wprentals').'<a href="'.wpestate_get_template_link('terms_conditions.php').'" target="_blank" id="user_terms_register_topbar_link">'.esc_html__( 'terms & conditions','wprentals').'</a> </label>';
                    if($separate_users_status!=='yes'){
                        print '<p id="reg_passmail_mobile">'.esc_html__( 'A password will be e-mailed to you','wprentals').'</p>';
                    }

                    print'
                    <input type="hidden" id="security-register-mobile" name="security-register-mobile" value="'. estate_create_onetime_nonce( 'register_ajax_nonce_mobile' ).'">';

                    if( esc_html ( wprentals_get_option('wp_estate_use_captcha','') )=='yes'){
                        print'<div id="mobile_register_menu" style="float:left;transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;margin-top:10px;"></div>';
                    }

                    print'<button class="wpb_button  wpb_btn-info  wpb_regularsize  wpestate_vc_button  vc_button" id="wp-submit-register_wd_mobile">'.esc_html__( 'Register','wprentals').'</button>';

                    print'
                    <div class="login-links">
                        <a href="#" id="widget_login_sw_mobile">'.esc_html__( 'Back to Login','wprentals').'</a>
                    </div>';
                    $social_register_on  =   esc_html( wprentals_get_option('wp_estate_social_register_on','') );
            
                    if($social_register_on=='yes'){
                        print'
                        <div class="login-links" >';
                         if(class_exists('Wpestate_Social_Login')){
                            $wpestate_social_login->display_form('mobile',0);
                         }
                        print'
                        </div> <!-- end login links--> ';
                    }
                 print'
                 </div>
                </div>

            <div id="mobile_forgot_wrapper">
                <h3 class="widget-title-sidebar"  id="forgot-div-title_mobile">'. esc_html__( 'Reset Password','wprentals').'</h3>
                <div class="login_form" id="forgot-pass-div_mobile">
                    <div class="loginalert" id="forgot_pass_area_shortcode_wd_mobile"></div>
                    <div class="loginrow">
                            <input type="text" class="form-control" name="forgot_email" id="forgot_email_mobile" placeholder="'.esc_html__( 'Enter Your Email Address','wprentals').'" size="20" />
                    </div>';
                    wp_nonce_field( 'login_ajax_nonce_forgot_mobile', 'security-login-forgot_wd_mobile',true);
                    print'<input type="hidden" id="postid" value="0">
                    <button class="wpb_btn-info wpb_regularsize wpestate_vc_button  vc_button" id="wp-forgot-but_mobile" name="forgot" >'.esc_html__( 'Reset Password','wprentals').'</button>
                    <div class="login-links shortlog">
                    <a href="#" id="return_login_shortcode_mobile">'.esc_html__( 'Return to Login','wprentals').'</a>
                    </div>
                </div>
            </div>';

    } ?>

        </div>
    </div>
</div>
