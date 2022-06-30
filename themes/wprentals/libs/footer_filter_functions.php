<?php

/*
*
* 
* * Add footer buttons, navigations and nounces
*
*
*/

add_action( 'wp_footer', 'wprentals_footer_includes',10 );
if(!function_exists('wprentals_footer_includes')):
    function wprentals_footer_includes(){
    
        include(locate_template('templates/footer_buttons.php'));
        if(is_singular('estate_property')){
            include(locate_template('templates/book_per_hour_form.php'));
        }
        
        wp_get_schedules();
        include(locate_template('templates/social_share.php'));
        
        
        
        if(is_singular('estate_property') ){
            ?>
            <!-- Modal -->
            <div class="modal fade" id="instant_booking_modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                   <div class="modal-content">
                       <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                           <h2 class="modal-title_big" ><?php esc_html_e( 'Confirm your booking','wprentals');?></h2>
                           <h4 class="modal-title" id="myModalLabel"><?php esc_html_e( 'Review the dates and confirm your booking','wprentals');?></h4>
                       </div>

                       <div class="modal-body"></div>

                       </div><!-- /.modal-content -->
                   </div><!-- /.modal-dialog -->
               </div><!-- /.modal -->
           </div>
        <?php
        }
        
           
           
        // trigger change in calendar if check in and checkout             
        if ( isset($_GET['check_in_prop']) && isset($_GET['check_out_prop'])   ){
            print '<script type="text/javascript">
                    //<![CDATA[
                    jQuery(document).ready(function(){
                      setTimeout(function(){
                          jQuery("#end_date").trigger("change");
                      },1000);
                    });
                    //]]>
            </script>';
        }
        
        
    }
endif;



/*
*
* 
* Header Function
*
*
 * 
*/


if(!function_exists('wprentals_show_header_wrapper')):
    function wprentals_show_header_wrapper($transparent_class,$wide_class,$header_map_class,$header_wide,$top_menu_hover_type,$wpestate_is_top_bar_class,$wpestate_header_type,$header_align){ ?>
        <div class="master_header <?php print 'master_'.trim($transparent_class) .' '.esc_attr($wide_class).' '.esc_attr($header_map_class).' master_'. esc_attr($header_wide).' hover_type_'.esc_attr($top_menu_hover_type); ?>">

            <?php

            global $post;
            $page_template='';
            if(isset($post->ID)){
                $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
            }


            if (wpestate_show_top_bar() && $page_template !=  'splash_page.php' ) {
                include(locate_template('templates/top_bar.php'));
            }
            ?>

            <?php include(locate_template('templates/mobile_menu_header.php')); ?>


                <div class="header_wrapper <?php print esc_attr($transparent_class . $wpestate_is_top_bar_class .' '. $wpestate_header_type .' '. $header_align .' '. $header_wide); ?>">
                    <div class="header_wrapper_inside">

                        <div class="logo">
                            <a href="<?php
                            $splash_page_logo_link = wprentals_get_option('wp_estate_splash_page_logo_link', '');
                            if ( $page_template=='splash_page.php' && $splash_page_logo_link != '') {
                                print esc_url($splash_page_logo_link);
                            } else {
                                echo esc_url(home_url('', 'login'));
                            }
                            ?>">

                            <?php
                            $logo='';
                            if( trim($transparent_class)!==''){
                                $logo = wprentals_get_option('wp_estate_transparent_logo_image', 'url');
                            }else{
                                $logo = wprentals_get_option('wp_estate_logo_image', 'url');
                            }

                            if ($logo != '') {
                                print '<img src="'.esc_url($logo).'" class="img-responsive retina_ready"  alt="'.esc_html__('logo','wprentals').'"/>';
                            } else {
                                print '<img class="img-responsive retina_ready" src="' . get_template_directory_uri() . '/img/logo.png" alt="'.esc_html__('logo','wprentals').'"/>';
                            }
                            ?>
                            </a>
                        </div>

                        <?php
                        if (esc_html(wprentals_get_option('wp_estate_show_top_bar_user_login', '')) == "yes") {
                            include(locate_template('templates/top_user_menu.php'));
                        }
                        ?>

                        <nav id="access">
                            <?php wp_nav_menu(array(
                                        'theme_location'    => 'primary',
                                        'container'         => false,
                                        'walker'            => new wpestate_custom_walker()
                                    ));
                            ?>
                        </nav><!-- #access -->
                    </div>
                </div>

            </div>
    <?php
    }
endif;