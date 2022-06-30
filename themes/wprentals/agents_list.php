<?php
// Template Name: Owner list
// Wp Estate Pack
get_header();
$wpestate_options    =   wpestate_page_details($post->ID);  
$unit_class          =   "col-md-4";
if($wpestate_options['content_class'] == "col-md-12"){
    $unit_class      =   "col-md-3";    
}
global $wpestate_agent_selection;
global $schema_flag;
?>



<div class="row content-fixed">
    <?php   include(locate_template('templates/breadcrumbs.php'));?>
    <div class=" <?php print esc_attr($wpestate_options['content_class']);?> ">
        <?php include(locate_template('templates/ajax_container.php')); ?>
        <?php while (have_posts()) : the_post(); ?>
        <?php if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) == 'yes') { ?>
              <h1 class="entry-title title_prop"><?php the_title(); ?></h1>
        <?php } ?>
        <div class="single-content blog-list-content"><?php the_content();?></div>   
        <?php endwhile; // end of the loop.  ?>  

              
        <div class="blog_list_wrapper row agent_list" itemscope itemtype ="http://schema.org/ItemList">    
        <?php
            $separate_users_status  =   esc_html ( wprentals_get_option('wp_estate_separate_users','')); 
            $max_num_pages          =   0;
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 0;
            $prop_no                    =   intval( wprentals_get_option('wp_estate_prop_no', '') );
            
            
            if($separate_users_status =='no'){
         
                $args = array(
                    'post_type'         => 'estate_agent',
                    'paged'             => $paged,
                    'posts_per_page'    => $prop_no 
                );

            }else{
                $args = array(
                    'post_type'         =>  'estate_agent',
                    'paged'             =>  $paged,
                    'posts_per_page'    =>  $prop_no, 
                    'meta_query'        =>  array(
                                                array(
                                                    'key'     => 'user_sub_type',
                                                    'value'   => '1',
                                                    'compare' => 'NOT LIKE',
                                                ),    
                                            ) 
                    );
            }

        
            
                $wpestate_agent_selection = new WP_Query($args);

                while ($wpestate_agent_selection->have_posts()): $wpestate_agent_selection->the_post();
                    $schema_flag=1;
                    include(locate_template('templates/agent_unit.php'));               
                endwhile;
                wp_reset_query();
                $max_num_pages=$wpestate_agent_selection->max_num_pages;
                $schema_flag=0;
            
        ?>
        
           
        </div>
        <?php wprentals_pagination($max_num_pages, $range = 2); ?>    
    </div><!-- end 8col container-->
    
<?php  include(get_theme_file_path('sidebar.php')); ?>
</div>   
<?php get_footer(); ?>