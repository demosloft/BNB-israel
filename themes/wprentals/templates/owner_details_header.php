<?php 
global $owner_id;
global $agent_id;
global $comments_data;
$current_user   =   wp_get_current_user();
$userID         =   $current_user->ID;

while (have_posts()) : the_post(); 
    $agent_id           =   get_the_ID();
    $owner_id           =   get_post_meta($agent_id, 'user_agent_id', true);
    $user_agent_id      =   wpestate_user_for_agent($agent_id);
    $thumb_id           =   get_post_thumbnail_id($post->ID);
    $preview            =   wp_get_attachment_image_src($thumb_id, 'wpestate_property_places');
    $preview_img        =   '';
    if(isset($preview[0])){
        $preview_img        =   $preview[0];
    }
  
    if ($preview_img==''){
        $preview_img    =   get_stylesheet_directory_uri().'/img/default_user.png';
    }
    $agent_skype        =   esc_html( get_post_meta($post->ID, 'agent_skype', true) );
    $agent_phone        =   esc_html( get_post_meta($post->ID, 'agent_phone', true) );
    $agent_mobile       =   esc_html( get_post_meta($post->ID, 'agent_mobile', true) );
    $agent_email        =   is_email( get_post_meta($post->ID, 'agent_email', true) );
    $agent_posit        =   esc_html( get_post_meta($post->ID, 'agent_position', true) );
    $agent_website      =   esc_url( get_post_meta($post->ID, 'agent_website', true) );
    
    if (function_exists('icl_translate') ){
        $agent_posit    =   icl_translate('wprentals','agent_position', esc_html( get_post_meta($post->ID, 'agent_position', true) ) );
    }else{
        $agent_posit    =   esc_html( get_post_meta($post->ID, 'agent_position', true) );
    }
    $agent_facebook     =   esc_html( get_post_meta($post->ID, 'agent_facebook', true) );
    $agent_twitter      =   esc_html( get_post_meta($post->ID, 'agent_twitter', true) );
    $agent_linkedin     =   esc_html( get_post_meta($post->ID, 'agent_linkedin', true) );
    $agent_pinterest    =   esc_html( get_post_meta($post->ID, 'agent_pinterest', true) );
    $agent_instagram    =   esc_html( get_post_meta($post->ID, 'agent_instagram', true) );
    $agent_youtube      =   esc_html( get_post_meta($post->ID, 'agent_youtube', true) );
    
    $name               =   get_the_title();
    $content            =   apply_filters( 'the_content', get_the_content() );
    $content            =   str_replace( ']]>', ']]&gt;', $content );
endwhile; // end of the loop.   
wp_reset_postdata();
$comments_data      =   wpestate_review_composer($agent_id);    
wp_reset_postdata();
?>
        
    <div class="owner-page-wrapper">
        <div class="owner-page-wrapper-inside row">
            <div class="col-md-2 user_picture_owner_page">
                <div class="owner-image-container" style="background-image: url('<?php print esc_url($preview_img); ?>');"></div>
                <?php print wpestate_display_verification_badge($owner_id);?>
            </div>    
            <div class="col-md-10">
                <h1 class="entry-title-agent"><?php the_title(); ?></h1>
                <?php 
                if(isset($comments_data['list_rating']) ){ ?>
                    <div class="property_ratings_agent">
                         <?php 
                            $counter=0; 
                            while($counter<5){
                                $counter++;
                                if($counter<=$comments_data['list_rating'] ){
                                    print '<i class="fas fa-star"></i>';
                                }else{
                                    print '<i class="far fa-star"></i>'; 
                                }

                            }
                        ?>
                       <span class="owner_total_reviews">(<?php 
                            if ( isset($comments_data['coments_no']) ){
                                print intval($comments_data['coments_no']);
                            }
                            ?>)</span>
                    </div>
                <?php } ?>
                <div class="agent_menu">
                    
                    <div class="agent_general_details">
                        <div class="property_menu_item"><span class="contact_title"><i class="fas fa-map-marker-alt"></i></span>   
                            <?php 
                            $live_in = esc_html(get_post_meta($post->ID, 'live_in', true)); 
                            if($live_in == ''){
                                echo esc_html__( 'not set','wprentals');
                            }else{
                                print esc_html($live_in);
                            }
                            ?>
                        </div>

                        <div class="property_menu_item"><span class="contact_title"><i class="fas fa-globe"></i></span>
                            <?php  
                            $i_speak = esc_html(get_post_meta($post->ID, 'i_speak', true));
                            
                            if($i_speak == ''){
                                echo esc_html__( 'not set','wprentals');
                            }else{
                                print esc_html($i_speak);
                            }
                            ?>
                        </div>
                   
                    
                    <?php  
                  
                    $user_to_agent=    wpestate_user_booked_from_agent($userID,$user_agent_id);
                    $agent_to_user=    wpestate_user_booked_from_agent($user_agent_id,$userID);
                    if (is_user_logged_in() && ($user_to_agent==1 || $agent_to_user==1   || $userID == $user_agent_id ) ) {
                    

                        if ($agent_phone) {
                            print '<div class="property_menu_item"><span class="contact_title"><i class="fas fa-phone"></i></span> <a href="tel:' . esc_url($agent_phone). '">' .esc_html($agent_phone). '</a></div>';
                        }

                        if ($agent_mobile) {
                            print '<div class="property_menu_item"><span class="contact_title"><i class="fas fa-mobile-alt"></i></span> <a href="tel:' .esc_url( $agent_mobile) . '">'.esc_html($agent_mobile). '</a></div>';
                        }
                         if ($agent_email) {
                            print '<div class="property_menu_item "><span class="contact_title"><i class="far fa-envelope"></i></span> <a href="mailto:' .esc_html( $agent_email) . '">'.esc_html($agent_email). '</a></div>';
                        }

                        if ($agent_skype) {
                            print '<div class="property_menu_item"><span class="contact_title"><i class="fab fa-skype"></i></span>'.esc_html($agent_skype).'</div>';
                        }
                        if ($agent_website) {
                            print '<div class="property_menu_item"><span class="contact_title"><i class="fas fa-desktop"></i></span> <a href="' .esc_url( $agent_website) . '">'.esc_html($agent_website). '</a></div>';
                        }


                        
                        
                    }
                    ?>
                    </div>  
                </div>  
                
                <?php
             
                if ( is_user_logged_in() && (  $user_to_agent==1  || $agent_to_user==1  || $userID == $user_agent_id ) ) {
                ?>
                    <div class="property_menu_item social_icons_owner">
                        <?php 
                        if($agent_facebook!=''){
                            print ' <a href="'.esc_url($agent_facebook).'"><i class="fab fa-facebook-f"></i></a>';
                        }
                        if($agent_twitter!=''){
                            print ' <a href="'.esc_url($agent_twitter).'"><i class="fab fa-twitter"></i></a>';
                        }
                        if($agent_linkedin!=''){
                            print ' <a href="'.esc_url($agent_linkedin).'"><i class="fab fa-linkedin-in"></i></a>';
                        }
                        if($agent_pinterest!=''){
                            print ' <a href="'.esc_url($agent_pinterest).'"><i class="fab fa-pinterest-p"></i></a>';
                        }
                        
                        if($agent_instagram!=''){
                            print ' <a href="'.esc_url($agent_instagram).'"><i class="fab fa-instagram"></i></a>';
                        }
                         
                        if($agent_youtube!=''){
                            print ' <a href="'.esc_url($agent_youtube).'"><i class="fab fa-youtube"></i></a>';
                        }
                        ?>
                    </div>
                <?php 
                }
                ?>
                    
                <div class="agent_personal_details" id="about_me">
                    <?php print trim($content);?>
                </div>
                
             
                    <div id="contact_me_long_owner" class=" owner_read_more" data-postid="<?php print intval($agent_id);?>">
                        <?php esc_html_e('Contact Owner','wprentals');?>
                    </div>
                   <?php if( wprentals_get_option('wp_estate_replace_booking_form','') == 'no'){ ?>  <?php } ?>
                   
            </div>   
            
        </div> 
    </div> 

    <div class="owner-page-wrapper-reviews">
        <div class="owner-page-wrapper-inside">
            <?php include(locate_template('templates/agent_reviews.php') );   ?>
        </div>     
    </div>