<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function rentals_gutenberg_cgb_block_assets() { // phpcs:ignore
	// Styles.
	wp_enqueue_style(
		'rentals_gutenberg-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);


        
        
}






// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'rentals_gutenberg_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function rentals_gutenberg_cgb_editor_assets() { 
	// Scripts.
	wp_enqueue_script(
		'rentals_gutenberg-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'rentals_gutenberg-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
        
        
        $property_category_agent                =   '';
        $property_action_category_agent         =   '';
        $property_city_agent                    =   '';
        $property_area_agent                    =   '';
        $property_category_agent_label          =   '';
        $property_action_category_agent_label   =   '';
        $property_city_agent_label              =   '';
        $property_area_agent_label              =   '';
                
        if(isset($out_agent_tax_array['property_category_agent'])){
            $property_category_agent=$out_agent_tax_array['property_category_agent'];
        }
        if(isset($out_agent_tax_array['property_action_category_agent'])){
            $property_action_category_agent=$out_agent_tax_array['property_action_category_agent'];
        }
        if(isset($out_agent_tax_array['property_city_agent'])){
            $property_city_agent=$out_agent_tax_array['property_city_agent'];
        }
        if(isset($out_agent_tax_array['property_area_agent'])){
            $property_area_agent=$out_agent_tax_array['property_area_agent'];
        }

        if(isset($out_agent_tax_array['property_category_agent_label'])){
            $property_category_agent_label=$out_agent_tax_array['property_category_agent_label'];
        }
        if(isset($out_agent_tax_array['property_action_category_agent_label'])){
            $property_action_category_agent_label=$out_agent_tax_array['property_action_category_agent_label'];
        }
        if(isset($out_agent_tax_array['property_city_agent_label'])){
            $property_city_agent_label=$out_agent_tax_array['property_city_agent_label'];
        }
        if(isset($out_agent_tax_array['property_area_agent_label'])){
            $property_area_agent_label=$out_agent_tax_array['property_area_agent_label'];
        }
        
        global $all_tax;
        global $wprentals_property_category_values;
        global $wprentals_all_tax_labels;
        global $wprentals_property_action_category_values;
        global $wprentals_property_city_values;
        global $wprentals_property_area_values;
        
        
        
        wp_localize_script('rentals_gutenberg-block-js', 'rentals_gutenberg_vars', 
            array(  
                'plugin_dir_path'           =>  plugin_dir_url(__DIR__),
             
                'all_tax'                   =>  json_encode( $all_tax ),
                'category_list'             =>  json_encode( $wprentals_property_category_values ),
                'category_labels'           =>  json_encode( $wprentals_all_tax_labels ),
                
                'action_list'               =>  json_encode( $wprentals_property_action_category_values ),
                'action_labels'             =>  json_encode( $wprentals_all_tax_labels ),
                
                'city_list'                 =>  json_encode( $wprentals_property_city_values ),
                'city_labels'               =>  json_encode( $wprentals_all_tax_labels ),
                
                'area_list'                 =>  json_encode( $wprentals_property_area_values ),
                'area_labels'               =>  json_encode( $wprentals_all_tax_labels ),
            )
        );
	
         wp_enqueue_style(
		'rentals_gutenberg_blocks-editor-common', // Handle.
		plugins_url( 'common.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
	);
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'rentals_gutenberg_cgb_editor_assets' );


if ( function_exists( 'register_block_type' ) ) {
    
    add_filter( 'render_block', function ( $block_content, $block ) {
        
        $plugins =array(
                    'rentals-gutenberg-block/slider-items',
                    'rentals-gutenberg-block/recent-items',
                    'rentals-gutenberg-block/places-list',
                    'rentals-gutenberg-block/places-slider',
                    'rentals-gutenberg-block/list-items-by-id',
                    'rentals-gutenberg-block/featured-agent',
                    'rentals-gutenberg-block/featured-article',
                    'rentals-gutenberg-block/featured-property',
                    'rentals-gutenberg-block/login-form',
                    'rentals-gutenberg-block/register-form',
                    'rentals-gutenberg-block/advanced-search',
                    'rentals-gutenberg-block/advanced-search',
                    'rentals-gutenberg-block/featured-category',
            );    

        if (in_array($block['blockName'], $plugins) ) {
            remove_filter( 'the_content', 'wpautop' );
        }

        return $block_content;
    }, 10, 2 );
    
    
    
    register_block_type( 'rentals-gutenberg-block/slider-items', array(
        'render_callback' => 'wpestate_slider_recent_posts_pictures',
    ) );

    register_block_type( 'rentals-gutenberg-block/recent-items', array(
        'render_callback' => 'wpestate_recent_posts_pictures',
    ) );
    
    register_block_type( 'rentals-gutenberg-block/places-list', array(
        'render_callback' => 'wpestate_places_list_function_wrapper',
    ) );

    register_block_type( 'rentals-gutenberg-block/places-slider', array(
        'render_callback' => 'wpestate_places_slider',
    ) );
     
    register_block_type( 'rentals-gutenberg-block/list-items-by-id', array(
        'render_callback' => 'wpestate_list_items_by_id_function',
    ) );
    
    register_block_type( 'rentals-gutenberg-block/featured-agent', array(
        'render_callback' => 'wpestate_featured_agent',
    ) );
     
    register_block_type( 'rentals-gutenberg-block/featured-article', array(
        'render_callback' => 'wpestate_featured_article',
    ) );
    
    register_block_type( 'rentals-gutenberg-block/featured-property', array(
        'render_callback' => 'wpestate_featured_property',
    ) );
    
    register_block_type( 'rentals-gutenberg-block/login-form', array(
        'render_callback' => 'wpestate_login_form_function',
    ) );
    
    register_block_type( 'rentals-gutenberg-block/register-form', array(
        'render_callback' => 'wpestate_register_form_function',
    ) );
    
    register_block_type( 'rentals-gutenberg-block/advanced-search', array(
        'render_callback' => 'wpestate_advanced_search_function',
    ) );
       
    register_block_type( 'rentals-gutenberg-block/featured-category', array(
        'render_callback' => 'wpestate_featured_place',
    ) );
       
       
}

function wpestate_places_list_function_wrapper($args){
    return '<div class="wpestate_places_list_function_wrapper">'.wpestate_places_list_function($args).'</div>';
}


add_filter( 'block_categories', function( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'WpRentals',
				'title' => __( 'WpRentals Blocks', 'wprentals-gutenberg' ),
			),
		)
	);
}, 10, 2 );