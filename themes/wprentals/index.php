<?php
// Index
// Wp Estate Pack 
get_header();
$wpestate_options       =   wpestate_page_details($post->ID);
global $wpestate_row_number_col;
$unit_class             =   "col-md-6";
$wpestate_row_number_col=   6;

if($wpestate_options['content_class'] == "col-md-12"){
    $unit_class         =   "col-md-4";
    $wpestate_row_number_col=    4;
}
?>


<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) { ?>
    <div class="row content-fixed">
    <?php   include(locate_template('templates/breadcrumbs.php'));?>
    <div class=" <?php print esc_attr($wpestate_options['content_class']);?> ">
        <?php include(locate_template('templates/ajax_container.php')); ?>

        <div class="blog_list_wrapper row indexlist">
        <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 0;
            $args = array(
                'post_type' => 'post',
                'paged'     => $paged,
                'status'    =>'published'
            );

            $wpestate_blog_selection = new WP_Query($args);

            while ($wpestate_blog_selection->have_posts()): $wpestate_blog_selection->the_post();
                include(locate_template('templates/blog_unit.php'));
            endwhile;
            wp_reset_query();
        ?>


        </div>
        <?php wprentals_pagination($wpestate_blog_selection->max_num_pages, $range = 2); ?>
    </div><!-- end 8col container-->

<?php  include(get_theme_file_path('sidebar.php')); ?>
</div>
<?php } ?>
<?php get_footer(); ?>
