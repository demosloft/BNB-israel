<?php
// Template Name: Terms and Conditions
// Wp Estate Pack
global $post;
get_header(); 
$wpestate_options=wpestate_page_details($post->ID); 
?>

<div class="row">
    <?php   include(locate_template('templates/breadcrumbs.php'));?>
    <div class="col-xs-12 <?php print esc_attr($wpestate_options['content_class']);?> ">
        
        <?php include(locate_template('templates/ajax_container.php'));?>        
        <?php while (have_posts()) : the_post(); ?>
            <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php } ?>
            <div class="single-content"><?php the_content();?></div><!-- single content-->
        <!-- #comments start-->
        <?php comments_template('', true);?> 	
        <!-- end comments -->   
        <?php endwhile; // end of the loop. ?>
    </div>
<?php  include(get_theme_file_path('sidebar.php')); ?>
</div>   
<?php get_footer(); ?>