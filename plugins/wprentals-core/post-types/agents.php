<?php
// register the custom post type
add_action( 'after_setup_theme', 'wpestate_create_agent_type' ,20);

if( !function_exists('wpestate_create_agent_type') ):
    function wpestate_create_agent_type() {
      $rewrites   =   get_option('wp_estate_url_rewrites',true);
        $rewrites   =   wpestate_safe_rewite();
        if(isset($rewrites[7])){
            $slug=$rewrites[7];
        }else{
            $slug='owners';
        }
        register_post_type( 'estate_agent',
            array(
                'labels' => array(
                'name'                  => esc_html__(  'Owners','wprentals-core'),
                'singular_name'         => esc_html__(  'Owner','wprentals-core'),
                'add_new'               => esc_html__( 'Add New Owner','wprentals-core'),
                'add_new_item'          =>  esc_html__( 'Add Owner','wprentals-core'),
                'edit'                  =>  esc_html__( 'Edit' ,'wprentals-core'),
                'edit_item'             =>  esc_html__( 'Edit Owner','wprentals-core'),
                'new_item'              =>  esc_html__( 'New Owner','wprentals-core'),
                'view'                  =>  esc_html__( 'View','wprentals-core'),
                'view_item'             =>  esc_html__( 'View Owner','wprentals-core'),
                'search_items'          =>  esc_html__( 'Search Owner','wprentals-core'),
                'not_found'             =>  esc_html__( 'No Owners found','wprentals-core'),
                'not_found_in_trash'    =>  esc_html__( 'No Owners found','wprentals-core'),
                'parent'                =>  esc_html__( 'Parent Owner','wprentals-core')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => $slug),
            'supports' => array('title', 'editor', 'thumbnail','comments'),
            'can_export' => true,
            'register_meta_box_cb' => 'wpestate_add_agents_metaboxes',
            'menu_icon'=> WPESTATE_PLUGIN_DIR_URL.'/img/agents.png'    
            )
        );
    }
endif; // end   wpestate_create_agent_type  


////////////////////////////////////////////////////////////////////////////////////////////////
// Add agent metaboxes
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_add_agents_metaboxes') ):
    function wpestate_add_agents_metaboxes() {	
        add_meta_box(  'estate_agent-sectionid', esc_html__(  'Owner Details', 'wprentals-core' ), 'estate_agent', 'estate_agent' ,'normal','default');
    }
endif; // end   wpestate_add_agents_metaboxes  



////////////////////////////////////////////////////////////////////////////////////////////////
// Agent details
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_agent') ):
    function estate_agent( $post ) {
        wp_nonce_field( plugin_basename( __FILE__ ), 'estate_agent_noncename' );
        global $post;

        print'
        <p class="meta-options">
        <label for="agent_email">'.esc_html__( 'Email:','wprentals-core').' </label><br />
        <input type="text" id="agent_email" size="58" name="agent_email" value="'.  esc_html(get_post_meta($post->ID, 'agent_email', true)).'">
        </p>

        <p class="meta-options">
        <label for="agent_phone">'.esc_html__( 'Phone: ','wprentals-core').'</label><br />
        <input type="text" id="agent_phone" size="58" name="agent_phone" value="'.  esc_html(get_post_meta($post->ID, 'agent_phone', true)).'">
        </p>

        <p class="meta-options">
        <label for="agent_mobile">'.esc_html__( 'Mobile:','wprentals-core').' </label><br />
        <input type="text" id="agent_mobile" size="58" name="agent_mobile" value="'.  esc_html(get_post_meta($post->ID, 'agent_mobile', true)).'">
        </p>

        <p class="meta-options">
        <label for="agent_skype">'.esc_html__( 'Skype: ','wprentals-core').'</label><br />
        <input type="text" id="agent_skype" size="58" name="agent_skype" value="'.  esc_html(get_post_meta($post->ID, 'agent_skype', true)).'">
        </p>


        <p class="meta-options">
        <label for="agent_facebook">'.esc_html__( 'Facebook: ','wprentals-core').'</label><br />
        <input type="text" id="agent_facebook" size="58" name="agent_facebook" value="'.  esc_html(get_post_meta($post->ID, 'agent_facebook', true)).'">
        </p>

        <p class="meta-options">
        <label for="agent_twitter">'.esc_html__( 'Twitter: ','wprentals-core').'</label><br />
        <input type="text" id="agent_twitter" size="58" name="agent_twitter" value="'.  esc_html(get_post_meta($post->ID, 'agent_twitter', true)).'">
        </p>

        <p class="meta-options">
        <label for="agent_linkedin">'.esc_html__( 'Linkedin: ','wprentals-core').'</label><br />
        <input type="text" id="agent_linkedin" size="58" name="agent_linkedin" value="'.  esc_html(get_post_meta($post->ID, 'agent_linkedin', true)).'">
        </p>

        <p class="meta-options">
        <label for="agent_pinterest">'.esc_html__( 'Pinterest: ','wprentals-core').'</label><br />
        <input type="text" id="agent_pinterest" size="58" name="agent_pinterest" value="'.  esc_html(get_post_meta($post->ID, 'agent_pinterest', true)).'">
        </p>

        <p class="meta-options">
            <label for="live_in">'.esc_html__( 'I Live In: ','wprentals-core').'</label><br />
            <input type="text" id="live_in" size="58" name="live_in" value="'.  esc_html(get_post_meta($post->ID, 'live_in', true)).'">
        </p>

        <p class="meta-options">
            <label for="i_speak">'.esc_html__( 'I Speak: ','wprentals-core').'</label><br />
            <input type="text" id="i_speak" size="58" name="i_speak" value="'.  esc_html(get_post_meta($post->ID, 'i_speak', true)).'">
        </p>
        

        <p class="meta-options">
            <label for="payment_info">'.esc_html__( 'Payment Info/Hidden Field: ','wprentals-core').'</label><br />
            <textarea id="payment_info" name="payment_info" cols="70" rows="3">'.  esc_html(get_post_meta($post->ID, 'payment_info', true)).'</textarea>
        </p>

        <p class="meta-options">
        <label for="user_agent_id">'.esc_html__( 'user_agent_id: ','wprentals-core').'</label><br />
        <input type="text" id="user_agent_id" size="58" name="user_agent_id" value="'.  intval(get_post_meta($post->ID, 'user_agent_id', true)).'">
        </p>';            
    }
endif; // end   estate_agent  




add_action('save_post', 'wpsx_5688_update_post', 1, 2);

if( !function_exists('wpsx_5688_update_post') ):
    function wpsx_5688_update_post($post_id,$post){

        if(!is_object($post) || !isset($post->post_type)) {
            return;
        }

        if($post->post_type!='estate_agent'){
           return;    
        }

        if( !isset($_POST['agent_email']) ){
            return;
        }

        $allowed_html   =   array();
        $user_id    = get_post_meta($post_id, 'user_meda_id', true);
        $email      = wp_kses($_POST['agent_email'],$allowed_html);
        $phone      = wp_kses($_POST['agent_phone'],$allowed_html);
        $skype      = wp_kses($_POST['agent_skype'],$allowed_html);
       
        $mobile     = wp_kses($_POST['agent_mobile'],$allowed_html);
        $desc       = wp_kses($_POST['content'],$allowed_html);
        $image_id   = get_post_thumbnail_id($post_id);
        $full_img   = wp_get_attachment_image_src($image_id, 'wpestate_property_places');           
        $facebook   = wp_kses($_POST['agent_facebook'],$allowed_html);
        $twitter    = wp_kses($_POST['agent_twitter'],$allowed_html);
        $linkedin   = wp_kses($_POST['agent_linkedin'],$allowed_html);
        $pinterest  = wp_kses($_POST['agent_pinterest'],$allowed_html);
        $i_speak    = wp_kses($_POST['i_speak'],$allowed_html);
        $live_in    = wp_kses($_POST['live_in'],$allowed_html);
        $payment_info= wp_kses($_POST['payment_info'],$allowed_html);

        update_user_meta( $user_id, 'aim', '/'.$full_img[0].'/') ;
        update_user_meta( $user_id, 'phone' , $phone) ;
        update_user_meta( $user_id, 'mobile' , $mobile) ;
        update_user_meta( $user_id, 'description' , $desc) ;
        update_user_meta( $user_id, 'skype' , $skype) ;
    
        update_user_meta( $user_id, 'custom_picture', $full_img[0]) ;
        update_user_meta( $user_id, 'facebook', $facebook) ;
        update_user_meta( $user_id, 'twitter', $twitter) ;
        update_user_meta( $user_id, 'linkedin', $linkedin) ;
        update_user_meta( $user_id, 'pinterest', $pinterest) ;
        update_user_meta( $user_id, 'small_custom_picture', $image_id) ;
        update_user_meta( $user_id, 'i_speak', $i_speak) ;
        update_user_meta( $user_id, 'live_in', $live_in) ;
        update_user_meta( $user_id, 'payment_info',$payment_info);
        
        
        $new_user_id    =   email_exists( $email ) ;
        if ( $new_user_id){
         //   esc_html_e('The email was not saved because it is used by another user.</br>','wprentals-core');
        } else{
            $args = array(
                 'ID'         => $user_id,
                 'user_email' => $email
            ); 
            wp_update_user( $args );
        } 

    }
endif;
?>