<?php
if ( isset($show_remove_fav) && $show_remove_fav==1 ) {
    print '<div class="info-container-payments favorite-wrapper"><span class="icon-fav icon-fav-on-remove" data-postid="'.esc_attr($post->ID).'"> '.esc_html($fav_mes).'</span></div>';
} else{
?>


    <div class="info-container">
        <a  data-original-title="<?php esc_attr_e('Edit property','wprentals');?>"   class="dashboad-tooltip" href="<?php  print esc_url($edit_link);?>"><i class="fa fa-edit editprop"></i></a>
        <a  data-original-title="<?php esc_attr_e('Delete property','wprentals');?>" class="dashboad-tooltip" onclick="return confirm(' <?php echo esc_html__( 'Are you sure you wish to delete ','wprentals').get_the_title(); ?>?')" href="<?php print esc_url ( add_query_arg( 'delete_id', $post_id, wpestate_get_template_link('user_dashboard.php') ) );?>"><i class="fas fa-trash-alt deleteprop"></i></a>
        <?php
        if( $post_status == 'expired' ){
            print'<span data-original-title="'.esc_attr__( 'Resend for approval','wprentals').'" class="dashboad-tooltip resend_pending" data-listingid="'.esc_attr($post_id).'"><i class="fas fa-arrow-up"></i></span>';
        }

        if($paid_submission_status=='per listing'){
            $pay_status    = get_post_meta($post_id, 'pay_status', true);
            $featured= intval(get_post_meta($post_id, 'prop_featured', true));
            if($pay_status=='paid' && $featured==1){
                //nothing
            }else{
                print '<span class="activate_payments">'.esc_html__( 'Publish or Upgrade','wprentals').'</span>';
            }
        }

        if( $post_status == 'publish' ){
            print ' <span  data-original-title="'.esc_attr__( 'Disable Listing','wprentals').'" class="dashboad-tooltip disable_listing" data-postid="'.esc_attr($post_id).'" ><i class="fas fa-eye-slash"></i></span>';
        }else if($post_status=='disabled') {
            print ' <span  data-original-title="'.esc_attr__( 'Enable Listing','wprentals').'" class="dashboad-tooltip disable_listing" data-postid="'.esc_attr($post_id).'" ><i class="far fa-eye"></i></span>';
        }

        if($paid_submission_status=='membership'){
            if ( intval(get_post_meta($post_id, 'prop_featured', true))!=1){
                print ' <span  data-original-title="'.esc_attr__( 'Set as featured','wprentals').'" class="dashboad-tooltip make_featured" data-postid="'.esc_attr($post_id).'" ><i class="fas fa-star favprop"></i></span>';
            }
        }

        ?>

    </div>

    <div class="info-container-payments">
        <?php $pay_status    = get_post_meta($post_id, 'pay_status', true);



            if( $post_status == 'expired' ){
            }else{

                if($paid_submission_status=='per listing'){


                        $enable_paypal_status   =   esc_html ( wprentals_get_option('wp_estate_enable_paypal','') );
                        $enable_stripe_status   =   esc_html ( wprentals_get_option('wp_estate_enable_stripe','') );
                        $enable_direct_pay      =   esc_html ( wprentals_get_option('wp_estate_enable_direct_pay','') );
                        if($pay_status!='paid' ){

                            print '<div class="listing_submit">
                            <button type="button"  class="close close_payments" data-dismiss="modal" aria-hidden="true">&times;</button>
                            '.esc_html__( 'Submission Fee','wprentals').': <span class="submit-price submit-price-no">'.esc_html($price_submission).'</span><span class="submit-price"> '.esc_html($wpestate_currency).'</span></br>';

                                global $wpestate_global_payments;
                                if($wpestate_global_payments->is_woo=='yes'){
                                        $wpestate_global_payments->show_button_pay($post_id,'','',$price_submission,2);
                                }else{

                                    $stripe_class='';
                                    if($enable_paypal_status==='yes'){
                                        $stripe_class=' stripe_paypal ';
                                        print ' <div class="listing_submit_normal label label-danger" data-listingid="'.esc_attr($post_id).'">'.esc_html__( 'Pay with Paypal','wprentals').'</div>';
                                    }

                                    if($enable_stripe_status==='yes'){
                                      wpestate_show_stripe_form_per_listing($stripe_class,$post_id,$price_submission,$price_featured_submission);
                                    }

                                    if($enable_direct_pay==='yes'){
                                        print '<div data-listing="'.esc_attr($post_id).'" class="label label-danger perpack">'.__('Wire Transfer','wprentals').'</div>';
                                    }
                                }

                            print  '</div>';

                        }else{
                            print '<div class="listing_submit">
                            <button type="button"  class="close close_payments" data-dismiss="modal" aria-hidden="true">&times;</button>';


                            if ( $featured ==1 ){
                                print ' <div class="listing_submit_spacer" style="height:118px;"><span class="label label-success  featured_label">'.esc_html__( 'Property is featured','wprentals').'</span>   </div>';
                            }else{
                                print'
                                <div class="listing_submit_spacer">
                                    '.esc_html__( 'Featured Fee','wprentals').': <span class="submit-price submit-price-featured">'.esc_html($price_featured_submission).'</span><span class="submit-price"> '.esc_html($wpestate_currency).'</span> </br>
                                </div>';

                                global $wpestate_global_payments;
                                if($wpestate_global_payments->is_woo=='yes'){
                                    $wpestate_global_payments->show_button_pay($post_id,'','',$price_featured_submission,3);
                                }else{
                                    $stripe_class='';
                                    if($enable_paypal_status==='yes'){
                                        print'<span class="listing_upgrade label label-danger" data-listingid="'.esc_attr($post_id).'">'.esc_html__( 'Set as Featured','wprentals').'</span>';
                                    }
                                    if($enable_stripe_status==='yes'){
                                        wpestate_show_stripe_form_upgrade($stripe_class,$post_id,$price_submission,$price_featured_submission);
                                    }
                                    if($enable_direct_pay==='yes'){
                                        print '<div data-listing="'.intval($post_id).'" data-isupgrade="1"  class="label label-danger perpack">'.__('Set as Featured - Wire','wprentals').'</div>';
                                    }
                                }
                            }
                            print '</div>';
                        }

                }

            }?>

    </div>
<?php
}
?>
