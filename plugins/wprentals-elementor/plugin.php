<?php

namespace ElementorWpRentals;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

    /**
     * Instance
     *
     * @since 1.2.0
     * @access private
     * @static
     *
     * @var Plugin The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.2.0
     * @access public
     *
     * @return Plugin An instance of the class.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * widget_scripts
     *
     * Load required plugin core files.
     *
     * @since 1.2.0
     * @access public
     */
    public function widget_scripts() {
        
    }

    /**
     * Include Widgets files
     *
     * Load widgets files
     *
     * @since 1.2.0
     * @access private
     */
    private function include_widgets_files() {

        require_once( __DIR__ . '/widgets/testimonial.php' );
        require_once( __DIR__ . '/widgets/featured-place.php' );
        require_once( __DIR__ . '/widgets/categories-list.php' );
        require_once( __DIR__ . '/widgets/recent-items-slider.php' );
        require_once( __DIR__ . '/widgets/icon-content-box.php' );
        require_once( __DIR__ . '/widgets/list_items_by_id.php' );
        require_once( __DIR__ . '/widgets/recent-items.php' );
        require_once( __DIR__ . '/widgets/featured-owner.php' );
        require_once( __DIR__ . '/widgets/featured-article.php' );
        require_once( __DIR__ . '/widgets/featured-listing.php' );
        require_once( __DIR__ . '/widgets/register-form.php' );
        require_once( __DIR__ . '/widgets/login-form.php' );
        require_once( __DIR__ . '/widgets/advanced-search.php' );
        require_once( __DIR__ . '/widgets/categories-slider.php' );
        require_once( __DIR__ . '/widgets/simple-term-list.php' );
        require_once( __DIR__ . '/widgets/booking-form.php' );
        require_once( __DIR__ . '/widgets/full_map.php' );
        require_once( __DIR__ . '/widgets/wpestate_tabs.php' );
        require_once( __DIR__ . '/widgets/wpestate_accordions.php' );

        require_once( __DIR__ . '/widgets/recent-items-v1.php' );
        require_once( __DIR__ . '/widgets/recent-items-v2.php' );
        require_once( __DIR__ . '/widgets/recent-items-v3.php' );

        require_once( __DIR__ . '/widgets/recent-items-slider-v1.php' );
        require_once( __DIR__ . '/widgets/recent-items-slider-v2.php' );
        require_once( __DIR__ . '/widgets/recent-items-slider-v3.php' );

        require_once( __DIR__ . '/widgets/wprentals-grids.php' );
        require_once( __DIR__ . '/widgets/contact_form_builder.php' );
        require_once( __DIR__ . '/widgets/search_form_builder.php' );
        require_once( __DIR__ . '/widgets/sliding-box.php' );
        
        require_once( __DIR__ . '/widgets/testimonial_slider.php' );
        require_once( __DIR__ . '/widgets/video_player.php' );
        
        require_once( __DIR__ . '/widgets/availability-calendar.php' );
  
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.2.0
     * @access public
     */
    public function register_widgets() {
        // Its is now safe to include Widgets files
        $this->include_widgets_files();

        // Register Widgets

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Testimonial());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Featured_Place());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Categories_List());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Recent_Items_Slider());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Icon_Content_Box());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_List_Items_By_Id());

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Featured_Owner());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Featured_Article());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Featured_Listing());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Register_Form());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Login_Form());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Advanced_Search());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Categories_Slider());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Simple_Term_list());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Booking_Form());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Full_Map());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\WpRentals_Tabs());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Accordions());

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Recent_Items());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Recent_Items_v1());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Recent_Items_v2());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Recent_Items_v3());

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Recent_Items_Slider_v1());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Recent_Items_Slider_v2());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Recent_Items_Slider_v3());

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Grids());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\WpRentals_Contact_Form_Builder());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Search_Form_Builder());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Sliding_Box());
        
        
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\WpRentals_Testimonial_Slider());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\WpRentals_Video_Player());
        
        
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wprentals_Avalability_Calendar());
    }

    /**
     *  Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.2.0
     * @access public
     */
    public function add_elementor_widget_categories($elements_manager) {
        $elements_manager->add_category(
                'wprentals', [
            'title' => __('WpRentals Widgets', 'rentals-elementor'),
            'icon' => 'fa fa-home',
                ]
        );
    }

    public function __construct() {

        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);

        // Register widgets
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);

        add_action('elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories']);
    }

}

// Instantiate Plugin Class
Plugin::instance();
