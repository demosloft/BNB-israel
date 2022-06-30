<?php
// Template Name: User Dashboard Invoices
// Wp Estate Pack

if ( !is_user_logged_in() ) {
    wp_redirect( esc_url(home_url('/')) );exit();
}

if ( !wpestate_check_user_level()){
    wp_redirect( esc_url(home_url('/')) );exit();
}

$current_user = wp_get_current_user();
$paid_submission_status         =   esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
$price_submission               =   floatval( wprentals_get_option('wp_estate_price_submission','') );
$submission_curency_status      =   wpestate_curency_submission_pick();;
$userID                         =   $current_user->ID;
$show_remove_fav                =   1;
$show_compare                   =   1;
$wpestate_show_compare_only     =   'no';
$wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
get_header();
$wpestate_options=wpestate_page_details($post->ID);

$reservation_strings=array(
    'Upgrade to Featured'           => esc_html__( 'Upgrade to Featured','wprentals'),
    'Publish Listing with Featured' => esc_html__( 'Publish Listing with Featured','wprentals'),
    'Package'                       => esc_html__( 'Package','wprentals'),
    'Listing'                       => esc_html__( 'Listing','wprentals'),
    'Reservation fee'               => esc_html__( 'Reservation fee','wprentals')
);
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
        <?php  wprentals_dashboard_header_display(); ?>


        <div class="row admin-list-wrapper invoices-wrapper user_dashboard_panel">
        <?php
            $args = array(
                'post_type'        => 'wpestate_invoice',
                'post_status'      => 'publish',
                'posts_per_page'   => -1 ,
                'author'           => $userID,
                'meta_query'       => array(
                        array(
                            'key'       => 'invoice_type',
                            'value'     =>  esc_html__( 'Reservation fee','wprentals'),
                            'type'      =>  'char',
                            'compare'   =>  'LIKE'
                            )
                ),
            );



            $prop_selection = new WP_Query($args);
            $counter                =   0;
            $wpestate_options['related_no']  =   4;
            $total_confirmed        =   0;
            $total_issued           =   0;
            $templates              =   esc_html__( 'No invoices','wprentals');

            if( $prop_selection->have_posts() ){
                ob_start();
                $wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
                while ($prop_selection->have_posts()): $prop_selection->the_post();
                    include(locate_template('dashboard/templates/invoice_listing_unit.php') );
                    $status = esc_html(get_post_meta($post->ID, 'invoice_status', true));
                    $type   = esc_html(get_post_meta($post->ID, 'invoice_type', true));
                    $price  = esc_html(get_post_meta($post->ID, 'item_price', true));

                    if( trim($type) == 'Reservation fee' || trim($type) == esc_html__( 'Reservation fee','wprentals') ){
                        if($status == 'confirmed' ){
                            $total_confirmed = $total_confirmed + $price;
                        }
                        if($status == 'issued' ){
                            $total_issued = $total_issued + $price;
                        }
                    }else{
                        $total_issued='-';
                        $total_confirmed = $total_confirmed + $price;
                    }

                endwhile;
                $templates = ob_get_contents();
                ob_end_clean();
            }

               include(locate_template('dashboard/templates/invoice_filters.php' ) );




                print '<div class="col-md-12 invoice_unit_title">
                    <div class="col-md-2">
                        <strong> '.esc_html__( 'Title','wprentals').'</strong>
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__( 'Date','wprentals').'</strong>
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__( 'Invoice Type','wprentals').'</strong>
                    </div>

                    <div class="col-md-2">
                        <strong> '.esc_html__( 'Billing Type','wprentals').'</strong>
                    </div>

                    <div class="col-md-2">
                        <strong> '.esc_html__( 'Status','wprentals').'</strong>
                    </div>

                    <div class="col-md-2">
                         <strong> '.esc_html__( 'Price','wprentals').'</strong>
                    </div>
                </div>
                ';

                print '<div id="container-invoices">';
                print trim($templates);//escaped above
                print '</div>';

        ?>
        </div>
    </div>
</div>

<?php
$ajax_nonce = wp_create_nonce( "wprentals_invoice_pace_actions_nonce" );
print'<input type="hidden" id="wprentals_invoice_pace_actions" value="'.esc_html($ajax_nonce).'" />    ';

wp_reset_query();
get_footer();
?>
