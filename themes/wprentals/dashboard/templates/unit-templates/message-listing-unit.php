<?php
global $post;
global $userID;
global $current_user;
$message_from_user      =   get_post_meta($post->ID, 'message_from_user', true);
$message_to_user        =   get_post_meta($post->ID, 'message_to_user', true);

if($message_from_user!=0){
    $user                   =   get_user_by( 'id', $message_from_user );
    if(isset($user->user_login)){
        $message_from_user_name =   $user->user_login;
    }else{
        $message_from_user_name='';
    }
}else{
    $message_from_user_name=__('Not Registered - DO NOT REPLY','wprentals');
}

$message_title          =   get_the_title($post->ID);
$message_content        =   get_the_content();
$original_mess          =   $post->ID;
$unread_replies         =   0;
$message_status         =   get_post_meta($post->ID, 'message_status'.$userID, true);
if($message_status=='unread'){
    $unread_replies++;
}

$list_of_relies= '<div class="mess_content-list-replies">';

    $args_child = array(
        'post_type'         => 'wpestate_message',
        'post_status'       => 'publish',
        'posts_per_page'    => -1,
        'order'             => 'ASC',
        'post_parent'       => $post->ID,
    );

    $message_selection_child = new WP_Query($args_child);

    while ($message_selection_child->have_posts()): $message_selection_child->the_post();
        $user = get_user_by( 'id', $post->post_author );

        $mes_to     =   get_post_meta($post->ID, 'message_to_user',true );
        $mess_from  =   get_post_meta($post->ID, 'message_from_user',true );

        $message_status         =   get_post_meta($post->ID, 'message_status'.$userID, true);
            if($message_status=='unread'){
                $unread_replies++;
            }

        $list_of_relies.= '<div class="mess_content-list-replies_unit" '.intval($post->ID).' data-mess-reply_user_id="'.esc_attr($mes_to).'" data-mess-reply_user_froom="'.esc_attr($mess_from).'" >';
        $list_of_relies.= '<h4><strong>'.esc_html__( 'From: ','wprentals').'</strong> '.esc_html($user->user_login).' - ' .get_the_title($post->ID).'</h4>';
        $list_of_relies.= nl2br(get_the_content()).'</div>';
    endwhile;

    wp_reset_query();
    wp_reset_postdata();

$list_of_relies.= '</div>';

?>


<div class="col-md-12 message_listing_wrapper">
    <div class="message_listing " data-messid="<?php print intval($original_mess); ?> "  >

        <div class="message_header">
            <div class="col-md-4">
                <?php
                if($unread_replies>0){
                    print '<span class="mess_unread mess_tooltip" data-original-title="'. esc_attr__( 'new message','wprentals').'"><i class="fas fa-exclamation-circle"></i></span>';
                }

                if($current_user->user_login == $message_from_user_name ){
                    print ' <span class="mess_from"><strong>'.esc_html__( 'Conversation started by you ','wprentals'). '</strong></span>';
                }else{
                    print ' <span class="mess_from"><strong>'.esc_html__( 'From','wprentals'). ': </strong>' .esc_html($message_from_user_name).'</span>';
                }

                ?>
             </div>

            <div class="col-md-4">
                <span class="mess_subject"> <strong><?php esc_html_e('Subject','wprentals');?>: </strong><?php print esc_html($message_title);?></span>
            </div>

            <div class="col-md-2">
                <span class="mess_date">    <?php echo wpestate_convert_dateformat_reverse ( get_the_date('Y-m-d',$original_mess) ); ?>   </span>
            </div>

            <div class=" message-action text-right" >
                <span data-original-title="<?php esc_attr_e('reply to message','wprentals');?>"  class="mess_reply mess_tooltip">
                    <i class="fas fa-reply"></i>
                </span>
                <div class="delete_wrapper">
                    <span data-original-title="<?php esc_attr_e('delete message','wprentals');?>"  class="mess_delete mess_tooltip">
                        <i class="fas fa-times "></i>
                    </span>
                </div>
            </div>


        </div>

        <div class="mess_content">
            <h4><?php print esc_html($message_title);?></h4>
            <div class="message_content">
                <?php
                $pieces= explode('||',$message_content);

                print nl2br($pieces[0]);
                if(isset($pieces[1])){
                    print '</br>';
                    print esc_html(nl2br($pieces[1]));
                }

                //replies list
                print trim($list_of_relies);
                ?>
            </div>
        </div>

        <?php
        $message_from_user      =   intval(get_post_meta($original_mess, 'message_from_user', true));
        $message_to_user        =   intval(get_post_meta($original_mess, 'message_to_user', true));


        if($userID === $message_from_user ){
            $reply_to_id = $message_to_user;

        }else{
            $reply_to_id=$message_from_user;
        }

        ?>

        <div class="mess_reply_form"   <?php echo get_post_meta($original_mess, 'delete_destination'.intval($reply_to_id), true).'  '.get_post_meta($original_mess, 'delete_source'.intval($reply_to_id), true).'fff';?> data-unread_replies="<?php print esc_attr($unread_replies);?>" >

                <h4><?php esc_html_e('Reply','wprentals');?></h4>
                <input type="text" class="subject_reply form-control" value="Re: <?php print esc_html($message_title); ?>">
                <textarea name="message_reply_content" class="message_reply_content form-control"></textarea>
                <span class=" mess_send_reply_button"
                    data-mess-reply_to_id="<?php print esc_attr($reply_to_id);?>"
                    data-unread_replies="<?php print esc_attr($unread_replies);?>" >
                    <?php esc_html_e('Send Reply','wprentals');?>
                </span>

        </div>


    </div>
</div>

<?php
wp_reset_query();
wp_reset_postdata();
