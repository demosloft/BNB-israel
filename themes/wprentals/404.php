<?php
// Wp Estate Pack
get_header();
global $more;
$more = 0;
$wide_class = '';
?>
<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { ?>
    <div id="post" <?php post_class('row  '.$wide_class); ?>>
        <?php   include(locate_template('templates/breadcrumbs.php'));?>
        <div class="notfound_pad">
            <div class="single-content content404">
                <h1 class="entry-title entry404"><?php esc_html_e('Page not found','wprentals');?></h1>
                <p>
                <?php esc_html_e( 'We\'re sorry. Your page could not be found, but you can check our latest listings & articles below. ', 'wprentals' ); ?>
                </p>

                <div class="list404">  
                    <h3><?php esc_html_e('Latest Listings', 'wprentals'); ?></h3>
                    <?php
                    $args = array(
                        'post_type' => 'estate_property',
                        'post_status' => 'publish',
                        'paged' => 0,
                        'posts_per_page' => 10,
                    );

                    $recent_posts = new WP_Query($args);
                    print '<ul>';
                    while ($recent_posts->have_posts()): $recent_posts->the_post();
                        print '<li><a href="'.esc_url( get_permalink() )  . '">' . get_the_title() . '</a></li>';
                    endwhile;
                    print '</ul>';
                    ?>
                </div>

                <div class="list404">  
                    <h3><?php esc_html_e('Latest Articles', 'wprentals'); ?></h3>
                    <?php
                    $args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'paged' => 0,
                        'posts_per_page' => 10,
                    );

                    $recent_posts = new WP_Query($args);
                    print '<ul>';
                    while ($recent_posts->have_posts()): $recent_posts->the_post();
                        print '<li><a href="' . esc_url( get_permalink() )  . '">' . get_the_title() . '</a></li>';
                    endwhile;
                    print '</ul>';
                    ?>
                </div>
            </div>

        </div> 

    </div>
<?php } ?>
<?php get_footer(); ?>