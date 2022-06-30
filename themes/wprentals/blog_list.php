<?php
// Template Name: Blog list page
// Wp Estate Pack
get_header();
global $wpestate_row_number_col;       
global $wpestate_full_row;
global $wpestate_blog_selection;
$wpestate_options            =   wpestate_page_details($post->ID);
$unit_class                  =   "col-md-6";
$wpestate_row_number_col     =   6;
$wpestate_full_row           =   0;

if($wpestate_options['content_class'] == "col-md-12"){
    $unit_class              =   "col-md-4";    
    $wpestate_row_number_col =   4;
    $wpestate_full_row       =   1;
}
?>


<div class="row content-fixed">
    <?php   include(locate_template('templates/breadcrumbs.php'));?>
    <div class=" <?php print esc_attr($wpestate_options['content_class']);?> ">
        <?php include(locate_template('templates/ajax_container.php'));?>
        <?php while (have_posts()) : the_post(); ?>
        <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) == 'yes') { ?>
              <h1 class="entry-title title_prop"><?php the_title(); ?></h1>
        <?php } ?>
        <div class="single-content blog-list-content"><?php the_content();?></div>   
        <?php endwhile; // end of the loop.  ?>  

              
        <div class="blog_list_wrapper row" itemscope itemtype ="http://schema.org/ItemList">    
        <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 0;
            $args = array(
                'post_type' => 'post',
                'paged'     => $paged,
                'status'    =>'published'
            );

            $wpestate_blog_selection = new WP_Query($args);
            
            while ($wpestate_blog_selection->have_posts()): $wpestate_blog_selection->the_post();
                include(locate_template('templates/blog_unit_new.php'));
            endwhile;
            wp_reset_query();
        ?>
        
           
        </div>
        <?php wprentals_pagination($wpestate_blog_selection->max_num_pages, $range = 2); ?>    
    </div><!-- end 8col container-->
    
<?php  include(get_theme_file_path('sidebar.php')); ?>
</div>   

<?php get_footer(); ?>