<?php
global $post;

$nr_of_reviews    = wp_count_comments( $post->ID );
$cpaged           = ( get_query_var( 'rp' ) != '' ) ? get_query_var( 'rp' ) : 1;
$reviews_per_page = 7;// set low for testing purposes
$review_pages     = ceil( $nr_of_reviews->approved / $reviews_per_page );

$args = array(
	'number'  => intval( $reviews_per_page ),
	'post_id' => $post->ID, // use post_id, not post_ID
	'paged'   => intval( $cpaged )
);

$comments   =   get_comments($args);
$coments_no =   0;
$stars_total=   0;
$review_templates=' ';

foreach($comments as $comment) :
    $coments_no++;
    
    $userId         =   $comment->user_id;
 
    if($userId == 1){
        $reviewer_name="admin";
        $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
    }else{
        $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
        $reviewer_name  =   get_the_title($userid_agent);    
        if($userid_agent==''){
            $reviewer_name=   $comment->comment_author;
        }
        
    }

    if($userid_agent==''){
        $user_small_picture_id     =    get_the_author_meta( 'small_custom_picture' ,  $comment->user_id,true  );
        $preview                   =    wp_get_attachment_image_src($user_small_picture_id,'wpestate_user_thumb');
        $preview_img               =    '';
        if(isset($preview[0])){
            $preview_img               =    $preview[0];
        }
      
    }else{
        $thumb_id           = get_post_thumbnail_id($userid_agent);
        $preview            = wp_get_attachment_image_src($thumb_id, 'thumbnail');
        $preview_img               =    '';
        if(isset($preview[0])){
            $preview_img               =    $preview[0];
        }
    }

    if($preview_img==''){
           $preview_img    =   get_stylesheet_directory_uri().'/img/default-user.png';
    }
    
    $rating= get_comment_meta( $comment->comment_ID , 'review_stars', true );
    $review_templates.='  
         <div class="listing-review">
                     

                        <div class="col-md-8 review-list-content norightpadding">
                            <div class="reviewer_image"  style="background-image: url('.esc_url($preview_img).');"></div>
                          
                            <div class="reviwer-name">'.esc_html($reviewer_name).'</div>
                            
                            <div class="review-date">
                                '.esc_html__( 'Posted on ','wprentals' ). ' '. get_comment_date('j F Y',$comment->comment_ID).' 
                            </div>
                            
                            <div class="property_ratings">';

	                        $review_templates .= wpestate_display_rating($rating, 'total');
	                        $total_rating = wpestate_get_star_total_rating($rating);
                            $review_templates.=' <span class="ratings-star">( ' . $total_rating . ' ' . esc_html__( 'of','wprentals').' 5)</span>
                            </div>

                            <div class="review-content">
                                '. $comment->comment_content .'                                
                            </div>
                        </div>
                    </div>       ';

endforeach;

if($coments_no>0){
    $list_rating = get_post_meta($post->ID, 'property_stars', TRUE);
    if ( ! $list_rating ) {
        $list_rating = wpestate_calculate_property_rating( $post->ID );
    }

?>
<div class="property_page_container for_reviews">
    <div class="listing_reviews_wrapper">
            <div class="listing_reviews_container">
                <h3 id="listing_reviews" class="panel-title">
                        <?php
                        printf( _n('%d Review', '%d Reviews', $coments_no, 'wprentals'), $nr_of_reviews->approved );
                        ?>
                      
                </h3>
                  <div class="property_ratings">
                            <?php  print wpestate_display_rating($list_rating, 'complete'); ?>
                        </div>

                <?php print trim($review_templates); ?>
                <?php wpestate_review_pagination($review_pages, 2);?>
        </div>
	  
    </div>
</div>
<?php } ?>