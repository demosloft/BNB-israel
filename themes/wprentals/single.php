<?php
// Sigle - Blog post
// Wp Estate Pack
get_header();
$wpestate_options=wpestate_page_details($post->ID); 
global $more;
$more = 0;

if ( 'wpestate_message' == get_post_type() || 'wpestate_invoice' == get_post_type() || 'wpestate_booking' == get_post_type() ){
    exit();
}
?>

<?php if (! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('single')) { ?>
    <div itemscope itemtype="http://schema.org/Article" id="post" <?php post_class('row content-fixed');?>>
    <?php   include(locate_template('templates/breadcrumbs.php'));?>
    <div class=" <?php print esc_attr( $wpestate_options['content_class']); ?> ">
        <?php include(locate_template('templates/ajax_container.php')); ?>  
        <?php    
        $preview_img='';     
        $preview    =   wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_featured');
        $date       =   the_date('', '', '', FALSE);

        if(isset($preview[0])){
            $preview_img=$preview[0];
        }

        ?>
        <img itemprop="image"  src="<?php print esc_url($preview_img);?>" class="schema_div_noshow b-lazy  img-responsive" alt="<?php print esc_attr($title);?>" >
        <div itemprop="dateModified"  class="schema_div_noshow"><?php print esc_html( $date); ?></div>
        
        <?php
    
        $logo =  wprentals_get_option('wp_estate_logo_image', 'url');
        if( $logo==''){
            $logo =  get_template_directory_uri() . '/img/logo.png';  
        }

        ?>
        
        <div class="schema_div_noshow" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
            <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
              <img src="<?php print esc_url($logo);?>"/>
              <meta itemprop="url" content="<?php print esc_url($logo);?>">
            </div>
            <meta itemprop="name" content="MyCorp">
        </div>
        <link class="schema_div_noshow" itemprop="mainEntityOfPage" href="<?php echo esc_url ( get_permalink());?>" />
        
        
        
        
        <div class="single-content single-blog">
            <?php      
             
            while ( have_posts() ) : the_post();
            if (esc_html( get_post_meta($post->ID, 'post_show_title', true) ) != 'no') { ?>                
                <h1 itemprop="headline" class="entry-title single-title" ><?php the_title(); ?></h1> 
                <div class="meta-element-head"   itemprop="datePublished" > 
                    <?php print ''.esc_html__( 'Published on','wprentals').' '.esc_html($date).' '.esc_html__( 'by', 'wprentals').' <span itemprop="author">'.get_the_author().'</span>';  ?>
                </div>
        
            <?php 
            } 
            include(locate_template('templates/postslider.php'));
            if (has_post_thumbnail()){
                $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'wpestate_property_full_map');
            }
      
            the_content('Continue Reading');                     
            $args = array(
                'before'           => '<p>' . esc_html__( 'Pages:','wprentals'),
                'after'            => '</p>',
                'link_before'      => '',
                'link_after'       => '',
                'next_or_number'   => 'number',
                'nextpagelink'     => esc_html__( 'Next page','wprentals'),
                'previouspagelink' => esc_html__( 'Previous page','wprentals'),
                'pagelink'         => '%',
                'echo'             => 1
            ); 
            wp_link_pages( $args ); 
            ?>  
            
            <div class="meta-info"> 
                <div class="meta-element">
                    <?php print '<strong>'.esc_html__( 'Category','wprentals').': </strong>';the_category(', ')?>
                </div>
             
                <?php 
                echo wpestate_share_unit_desing($post->ID);
                ?>
            </div> 
        </div>    
     
            
        <!-- #related posts start-->    
        <?php  include(locate_template('templates/related_posts.php'));?>    
        <!-- #end related posts -->   
        
        <!-- #comments start-->
        <?php if ( get_comments_number(get_the_ID() ) !==0 ) :?>
            <div class="wrapper_content"><?php comments_template('', true);?> </div>
        <?php endif; ?>
        <!-- end comments -->   
        
        <?php endwhile; // end of the loop. ?>
    </div>
       
<?php  include(get_theme_file_path('sidebar.php')); ?>
</div>   
<?php } ?>
<?php get_footer(); ?>