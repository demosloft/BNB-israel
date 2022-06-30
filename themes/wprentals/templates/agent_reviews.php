<?php 
global $agent_id;
global $prop_selection;
global $comments_data; 
global $post;     
$wpestate_options      =   wpestate_page_details($agent_id);
?>

    <div class=" <?php print esc_attr($wpestate_options['content_class']);?> ">
        <div class="listing-reviews-wrapper">

        <h3 id="listing_reviews" class="panel-title">
                <?php
                print esc_html_e('Reviews ', 'wprentals'). '<span class="owner_total_reviews">';
                if(isset($comments_data['coments_no'])) {
                    print '('.intval($comments_data['coments_no']).')';
                }
                print '</span>';
                ?>
                
        </h3>

    <?php    
        if(isset($comments_data['templates'])) {
            print trim($comments_data['templates']);
        }
        print '</div>';
        print '</div>';
        print '<div class=" '.esc_attr($wpestate_options['sidebar_class']).' widget-area-sidebar" id="primary" >
        <ul class="xoxo">';
            dynamic_sidebar('owner-page-widget-area');
        print'    
        </ul>
    </div>';