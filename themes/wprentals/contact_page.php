<?php
// Template Name: Contact Page
// Wp Estate Pack
get_header();


$wpestate_options   =   wpestate_page_details($post->ID);
$company_name       =   esc_html( stripslashes ( wprentals_get_option('wp_estate_company_name', '') ) );
$company_picture    =   esc_html( wprentals_get_option('wp_estate_company_contact_image', 'url') );
$company_email      =   esc_html( wprentals_get_option('wp_estate_email_adr', '') );
$mobile_no          =   esc_html ( wprentals_get_option('wp_estate_mobile_no','') );
$telephone_no       =   esc_html( wprentals_get_option('wp_estate_telephone_no', '') );
$fax_ac             =   esc_html( wprentals_get_option('wp_estate_fax_ac', '') );
$skype_ac           =   esc_html( wprentals_get_option('wp_estate_skype_ac', '') );
$instagram_ac       =   esc_html( wprentals_get_option('wp_estate_instagram_ac', '') );
$youtube_ac         =   esc_html( wprentals_get_option('wp_estate_youtube_ac', '') );

if (function_exists('icl_translate') ){
    $co_address      =   icl_translate('wprentals','wp_estate_co_address_text', ( wprentals_get_option('wp_estate_co_address') ) );
}else{
    $co_address      =   ( wprentals_get_option('wp_estate_co_address', '') );
}

$facebook_link      =   esc_html( wprentals_get_option('wp_estate_facebook_link', '') );
$twitter_link       =   esc_html( wprentals_get_option('wp_estate_twitter_link', '') );
$google_link        =   esc_html( wprentals_get_option('wp_estate_google_link', '') );
$linkedin_link      =   esc_html ( wprentals_get_option('wp_estate_linkedin_link','') );
$pinterest_link     =   esc_html ( wprentals_get_option('wp_estate_pinterest_link','') );
$agent_email        =   $company_email;


?>



<div class="row">
    <?php   include(locate_template('templates/breadcrumbs.php'));?>
    <div class="<?php print esc_attr($wpestate_options['content_class']);?>">

        <?php include(locate_template('templates/ajax_container.php')); ?>

        <?php while (have_posts()) : the_post(); ?>
            <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h1 class="entry-title entry-contact"><?php the_title(); ?></h1>
            <?php } ?>
            <div class="contact-wrapper">



            <div class="col-md-4 contact_page_company_picture">
                <div class="company_headline ">
                    <h3><?php print esc_html($company_name);?></h3>

                    <?php
                    if ($telephone_no) {
                        print '<div class="agent_detail contact_detail"><i class="fas fa-phone"></i><a href="tel:' . esc_html($telephone_no) . '">'; print  esc_html($telephone_no).'</a></div>';
                    }

                     if ($mobile_no) {
                        print '<div class="agent_detail contact_detail"><i class="fas fa-mobile-alt"></i><a href="tel:' .esc_html( $mobile_no ). '">'; print  esc_html($mobile_no) . '</a></div>';
                    }

                    if ($company_email) {
                        print '<div class="agent_detail contact_detail"><i class="far fa-envelope"></i>'; print '<a href="mailto:'.esc_html($company_email).'">' .esc_html( $company_email) . '</a></div>';
                    }

                    if ($fax_ac) {
                        print '<div class="agent_detail contact_detail"><i class="fas fa-print"></i>';print   esc_html($fax_ac ). '</div>';
                    }

                    if ($skype_ac) {
                        print '<div class="agent_detail contact_detail"><i class="fab fa-skype"></i>';print  esc_html( $skype_ac ). '</div>';
                    }


                    ?>


                    <div class="header_social">
                        <?php
                        if($facebook_link!=''){
                            print ' <a href="'.esc_url( $facebook_link).'" target="_blank" class="share_facebook"><i class="fab fa-facebook-f"></i></a>';
                        }

                        if($twitter_link!=''){
                           print ' <a href="'.esc_url( $twitter_link).'" target="_blank" class="share_tweet"><i class="fab fa-twitter"></i></a>';
                        }

                        if($google_link!=''){
                            print ' <a href="'.esc_url(  $google_link).'" target="_blank" class="share_google"><i class="fab fa-google-plus-g"></i></a>';
                        }

                        if($linkedin_link!=''){
                            print ' <a href="'.esc_url( $linkedin_link).'" target="_blank" class="share_linkedin"><i class="fab fa-linkedin-in"></i></a>';
                        }

                        if($pinterest_link!=''){
                            print ' <a href="'.esc_url(  $pinterest_link).'" target="_blank" class="share_pinterest" ><i class="fab fa-pinterest-p"></i></a>';
                        }

                        if($instagram_ac!=''){
                            print ' <a href="'.esc_url(  $instagram_ac).'" target="_blank" class="share_instagram" ><i class="fab fa-instagram"></i></a>';
                        }
                        if($youtube_ac!=''){
                            print ' <a href="'.esc_url(  $youtube_ac).'" target="_blank" class="share_youtube" ><i class="fab fa-youtube"></i></a>';
                        }

                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8 contact_page_company_details">

                <div class="agent_contanct_form">

                    <div class="alert-box-contact-page error">
                        <div class="alert-message" id="alert-agent-contact"></div>
                    </div>


                    <p class="third-form  ">
                        <input type="text" id="contact_name" size="40" name="contact_name" class="form-control" placeholder="<?php esc_html_e('Full Name', 'wprentals'); ?>"  value="">
                    </p>

                    <p class="third-form  ">
                        <input type="text" id="contact_email" size="40" name="contact_email" class="form-control" placeholder="<?php esc_html_e('Email', 'wprentals'); ?>"  value="">
                    </p>

                    <p class="third-form last-third">
                        <input type="text" id="contact_website" size="40" name="contact_website" class="form-control" placeholder="<?php esc_html_e('Website', 'wprentals'); ?>"  value="">
                    </p>


                    <textarea id="agent_comment" name="comment" class="form-control" cols="45" rows="8" aria-required="true" placeholder="<?php esc_html_e('Your Message', 'wprentals'); ?>" ></textarea>

                    <?php
                    wpestate_check_gdpr_case();
                    ?>

                    <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button"  id="agent_submit_contact" value="<?php esc_html_e('Send Message', 'wprentals'); ?>">


                    <input type="hidden" name="contact_ajax_nonce" id="agent_property_ajax_nonce"  value="<?php echo wp_create_nonce( 'ajax-property-contact' );?>" />

                </div>
            </div>
            </div>

            <div class="single-content contact-content">
                <?php the_content(); ?>
            </div><!-- single content-->

        <?php endwhile; // end of the loop. ?>
    </div>
<?php  include(get_theme_file_path('sidebar.php')); ?>
</div>
<?php get_footer(); ?>
