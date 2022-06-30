<?php
global $wpestate_options;
global $unit_class;
$thumb_id           = get_post_thumbnail_id($post->ID);
$preview            = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_blog_unit');
$name               = get_the_title();
$link               = esc_url(get_permalink());


if( !isset($preview[0]) || $preview[0]==''){
    $thumb_prop = '<img itemprop="image" src="'.get_stylesheet_directory_uri().'/img/default_user.png" class="b-lazy" alt="'.esc_html__('image','wprentals').'">';   
}else{
    $thumb_prop = '<img itemprop="image"  src="'.esc_url($preview[0]).'" alt="agent-images" class="b-lazy">';
}

$col_class=4;
if($wpestate_options['content_class']=='col-md-12'){
    $col_class=3;
}  

global $schema_flag;

 if( $schema_flag==1) {
    $schema_data='itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ';
 }else{
    $schema_data=' itemscope itemtype="http://schema.org/Product" ';
 }
?>

<div <?php print trim($schema_data);?> class="<?php print esc_attr($unit_class);?> agent-flex property_flex">
    <?php if( $schema_flag==1) {?>
        <meta itemprop="position" content="<?php print esc_attr($wpestate_agent_selection->current_post);?>" />
    <?php } ?>
        
    <div class="agent_unit" data-link="<?php print esc_url($link);?>">
        <div class="agent-unit-img-wrapper">
            <?php print trim($thumb_prop);//escaped above ?>
        </div>
        
        <div class="agent-title">
            <?php print '<h4> <a  itemprop="url" href="'.esc_url($link).'" class="agent-title-link"><span itemprop="name">' . esc_html($name). '</span></a></h4>';?>
        
            <div class="category_tagline">    
                <?php
                $where = esc_html(get_post_meta($post->ID, 'live_in', true));
                echo esc_html__( 'Lives in','wprentals');echo': ';
                if ($where==''){
                    esc_html_e('non disclosed','wprentals');
                }else{
                    print esc_html($where);
                }
                ?>
            </div> 

        </div>     
    </div>
</div>   