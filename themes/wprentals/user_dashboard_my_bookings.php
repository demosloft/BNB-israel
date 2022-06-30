<?php
// Template Name: User Dashboard My Bookings
// Wp Estate Pack
if ( !is_user_logged_in() ) {
        wp_redirect( esc_url(home_url('/')) );exit();
}
if ( !wpestate_check_user_level()){
        wp_redirect( esc_url(home_url('/')) );exit();
}


global $user_login;
$current_user = wp_get_current_user();
$userID                         =   $current_user->ID;
$user_login                     =   $current_user->user_login;
$user_pack                      =   get_the_author_meta( 'package_id' , $userID );
$user_registered                =   get_the_author_meta( 'user_registered' , $userID );
$user_package_activation        =   get_the_author_meta( 'package_activation' , $userID );
$paid_submission_status         =   esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( wprentals_get_option('wp_estate_price_submission','') );
$submission_curency_status      =   wpestate_curency_submission_pick();
$edit_link                      =   wpestate_get_template_link('user_dashboard_edit_listing.php');
$processor_link                 =   wpestate_get_template_link('processor.php');
$wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
$wpestate_currency              =   wpestate_curency_submission_pick();
get_header();
$wpestate_options               =   wpestate_page_details($post->ID);
?>


<div class="row is_dashboard">
    <?php
    if( wpestate_check_if_admin_page($post->ID) ){
        if ( is_user_logged_in() ) {
            include(locate_template('templates/user_menu.php' ) );
        }
    }
    ?>

    <div class=" dashboard-margin">
        <?php       wprentals_dashboard_header_display(); ?>



        <div class="row dashboard_property_list user_dashboard_panel">
          <?php      include(locate_template('dashboard/templates/search_booking_list.php' ) ); ?>


          <div class="wpestate_dashboard_table_list_header my_booking_header row">
            <div class="col-md-6"><?php esc_html_e('Property','wprentals'); ?></div>
            <div class="col-md-2"><?php esc_html_e('Status','wprentals'); ?></div>
            <div class="col-md-2"><?php esc_html_e('Period','wprentals'); ?></div>
            <div class="col-md-2"><?php esc_html_e('Request by','wprentals'); ?></div>
          </div>


        <?php
        $book_selection='';
        $all_my_post=array();
        $new_mess=0;


        $args = array(
            'post_type'         => 'estate_property',
            'posts_per_page'    => -1,
            'author'           =>  $userID
        );

        $title_search='';
        if( isset($_POST['wpestate_prop_title']) ){
            if (  ! isset( $_POST['wpestate_dash_book_search_nonce'] )  || ! wp_verify_nonce( $_POST['wpestate_dash_book_search_nonce'], 'wpestate_dash_book_search' ) ) {
                esc_html_e('your nonce does not validated','wprentals');
                exit();
            }else{
                $title=sanitize_text_field($_POST['wpestate_prop_title']);
                $args['s']=$title;
                $new_mess=1;
            }
        }


        if(function_exists('wpestate_search_by_title_only_filter')){
            $prop_selection =   wpestate_search_by_title_only_filter($args);
        }

        while ($prop_selection->have_posts()): $prop_selection->the_post();
           $all_my_post[]=$post->ID;
        endwhile;
        wp_reset_query();


        $search_listing_array=array();


        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            if( !empty($all_my_post) ){
                $args = array(
                    'post_type'         => 'wpestate_booking',
                    'post_status'       => 'publish',
                    'paged'             => $paged,
                    'posts_per_page'    => 30,
                    'order'             => 'DESC',
                    'meta_query' => array(

                            array(
                                'key' => 'owner_id',
                                'value' => $userID,
                                'compare' => '='
                            ),

                    )
                );

            if( isset($_POST['wpestate_prop_title']) ){
                $search_listing_array = array(
                            'key'     => 'booking_id',
                            'value'   => $all_my_post,
                            'compare' => 'IN',
                            );
                $args['meta_query'][]=$search_listing_array;

            }


            $book_selection = new WP_Query($args);
            while ($book_selection->have_posts()): $book_selection->the_post();
                include(locate_template('dashboard/templates/book-listing-unit.php') ) ;
            endwhile;
            wp_reset_query();

            wprentals_pagination($book_selection->max_num_pages, $range =2);

            }else{
                if($new_mess==1){
                    print '<h4 class="no_favorites">'.esc_html__( 'No results!','wprentals').'</h4>';
                }else{
                    print '<h4 class="no_favorites">'.esc_html__( 'You don\'t have any booking requests yet!','wprentals').'</h4>';
                }

            }
        ?>
        </div>
    </div>
</div>

<?php
$ajax_nonce = wp_create_nonce( "wprentals_bookings_actions_nonce" );
print'<input type="hidden" id="wprentals_bookings_actions" value="'.esc_html($ajax_nonce).'" />    ';

$ajax_nonce_book = wp_create_nonce( "wprentals_booking_confirmed_actions_nonce" );
print'<input type="hidden" id="wprentals_booking_confirmed_actions" value="'.esc_html($ajax_nonce_book).'" />    ';

wp_reset_query();
get_footer();
?>
