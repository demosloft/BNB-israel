<?php
global $prop_id ;
global $agent_email;
$owner_id   =   wpsestate_get_author($prop_id);
$agent_id   =   get_user_meta($owner_id, 'user_agent_id', true);
$prop_id    =   $post->ID;  
$author_email=get_the_author_meta( 'user_email'  );
$preview_img    = '';
$content        = '';
$agent_skype    = '';
$agent_phone    = '';
$agent_mobile   = '';
$agent_email    = '';
$agent_pitch    = '';
$link           = '';
if ($agent_id!=0){        

        $args = array(
            'post_type' => 'estate_agent',
            'p' => $agent_id
        );

        $agent_selection = new WP_Query($args);
        $thumb_id       = '';
       
   
        $name           = esc_html__('No agent','wprentals');

        if( $agent_selection->have_posts() ){
   
               while ($agent_selection->have_posts()): $agent_selection->the_post();  
                    $thumb_id           = get_post_thumbnail_id($post->ID);
                    $preview            = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                    $preview_img         = $preview[0];
                    $agent_skype         = esc_html( get_post_meta($post->ID, 'agent_skype', true) );
                    $agent_phone         = esc_html( get_post_meta($post->ID, 'agent_phone', true) );
                    $agent_mobile        = esc_html( get_post_meta($post->ID, 'agent_mobile', true) );
                    $agent_email         = esc_html( get_post_meta($post->ID, 'agent_email', true) );
                    if($agent_email==''){
                        $agent_email=$author_email;
                    }
                    $agent_pitch         = esc_html( get_post_meta($post->ID, 'agent_pitch', true) );
                  
                    if (function_exists('icl_translate') ){
                        $agent_posit      =   icl_translate('wprentals','agent_position', esc_html( get_post_meta($post->ID, 'agent_position', true) ) );
                    }else{
                        $agent_posit        = esc_html( get_post_meta($post->ID, 'agent_position', true) );
                    }
            
                    $agent_facebook      = esc_html( get_post_meta($post->ID, 'agent_facebook', true) );
                    $agent_twitter       = esc_html( get_post_meta($post->ID, 'agent_twitter', true) );
                    $agent_linkedin      = esc_html( get_post_meta($post->ID, 'agent_linkedin', true) );
                    $agent_pinterest     = esc_html( get_post_meta($post->ID, 'agent_pinterest', true) );
                    $link                = esc_url ( get_permalink() );
                    $name                = get_the_title();
                    $content             = get_the_content();
                    $content             = apply_filters('the_content', $content);
                    $content             = str_replace(']]>', ']]&gt;', $content);

             
               endwhile;
               wp_reset_query();
              
        }else{// end if have posts
            //$agent_id
            $first_name             =   get_the_author_meta( 'first_name' , $owner_id );
            $last_name              =   get_the_author_meta( 'last_name' , $owner_id );
            $user_email             =   get_the_author_meta( 'user_email' , $owner_id );
            $name                   =   $first_name.' '.$last_name;
            $content                =   get_the_author_meta( 'description' , $owner_id );
            $link                   =   '';
            $preview_img            =   get_the_author_meta( 'custom_picture' , $owner_id );
       }
}   // end if !=0
if($preview_img==''){
    $preview_img    =   get_stylesheet_directory_uri().'/img/default_user.png';
}
$verified_class = ( wpestate_userid_verified($owner_id) ) ? ' verified' : '';


$listing_page_type      =   wprentals_get_option('wp_estate_listing_page_type','');


if($listing_page_type==2 || $listing_page_type==3 || $listing_page_type==4){
?>



<div class="col-md-2 agentpic-wrapper<?php print esc_attr($verified_class);?>">
    <div class="owner_listing_image " style="background-image: url('<?php print esc_url($preview_img);?>');">
        <?php print wpestate_display_verification_badge($owner_id);?>
    </div>
</div>

<div class="col-md-10 agentpic-wrapper">
    <h3 itemprop="agent"><?php print esc_html($name);?></h3>
	
    <?php
        if($content!=''){                            
            print '<div class="owner_area_description">'.trim($content).'</div>';     
        }
    ?>
    <?php 
        if($link!=''){?>
            <a class="owner_read_more " href="<?php print esc_url($link);?>"><?php esc_html_e('See Owner Profile','wprentals');?></a>
    <?php 
        } 
    ?>
        
    <?php if( wprentals_get_option('wp_estate_replace_booking_form','') == 'no'){ ?>    
        <div  id="contact_me_long" class=" owner_read_more " data-postid="<?php esc_attr(the_ID());?>" >
            <?php esc_html_e('Contact Owner','wprentals');?>
        </div>
    <?php } ?>
</div>

<?php }else{ ?>

    <div class="agentpic-wrapper<?php print esc_attr($verified_class);?>">
        <div class="owner_listing_image " style="background-image: url('<?php print esc_url($preview_img);?>');"></div>
        <?php print wpestate_display_verification_badge($owner_id);?>
        <h3 itemprop="agent"><?php print esc_html($name);?></h3>
        
        <?php 
        if($link!=''){?>
            <a class="owner_read_more " href="<?php print esc_url($link);?>"><?php esc_html_e('See Owner Profile','wprentals');?></a>
        <?php } ?>
    </div>

    <div class="agentpic-wrapper">
        <?php
            if($content!=''){                            
                print '<div class="owner_area_description owner_area_1">'.trim($content).'</div>';     
            }
        ?>
        
        <?php if( wprentals_get_option('wp_estate_replace_booking_form','') == 'no'){ ?>    
            <div  id="contact_me_long" class="  " data-postid="<?php esc_attr(the_ID());?>" ><?php esc_html_e('Contact Owner','wprentals');?></div>
        <?php } ?>
        
    </div>
<?php } ?>
