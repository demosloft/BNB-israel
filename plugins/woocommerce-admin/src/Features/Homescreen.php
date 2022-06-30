<?php
/**
 * WooCommerce Homescreen.
 * NOTE: DO NOT edit this file in WooCommerce core, this is generated from woocommerce-admin.
 */

namespace Automattic\WooCommerce\Admin\Features;

use Automattic\WooCommerce\Admin\Loader;

/**
 * Contains backend logic for the homescreen feature.
 */
class Homescreen {
	/**
	 * Menu slug.
	 */
	const MENU_SLUG = 'wc-admin';

	/**
	 * Class instance.
	 *
	 * @var Homescreen instance
	 */
	protected static $instance = null;

	/**
	 * Get class instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook into WooCommerce.
	 */
	public function __construct() {
		add_filter( 'woocommerce_admin_get_user_data_fields', array( $this, 'add_user_data_fields' ) );
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		// In WC Core 5.1 $submenu manipulation occurs in admin_menu, not admin_head. See https://github.com/woocommerce/woocommerce/pull/29088.
		if ( version_compare( WC_VERSION, '5.1', '>=' ) ) {
			// priority is 20 to run after admin_menu hook for woocommerce runs, so that submenu is populated.
			add_action( 'admin_menu', array( $this, 'possibly_remove_woocommerce_menu' ) );
			add_action( 'admin_menu', array( $this, 'update_link_structure' ), 20 );
		} else {
			// priority is 20 to run after https://github.com/woocommerce/woocommerce/blob/a55ae325306fc2179149ba9b97e66f32f84fdd9c/includes/admin/class-wc-admin-menus.php#L165.
			add_action( 'admin_head', array( $this, 'update_link_structure' ), 20 );
		}
		add_filter( 'woocommerce_admin_preload_options', array( $this, 'preload_options' ) );

		add_filter( 'woocommerce_admin_shared_settings', array( $this, 'component_settings' ), 20 );
	}

	/**
	 * Adds fields so that we can store performance indicators, row settings, and chart type settings for users.
	 *
	 * @param array $user_data_fields User data fields.
	 * @return array
	 */
	public function add_user_data_fields( $user_data_fields ) {
		return array_merge(
			$user_data_fields,
			array(
				'homepage_layout',
				'homepage_stats',
				'task_list_tracked_started_tasks',
				'help_panel_highlight_shown',
			)
		);
	}

	/**
	 * Registers home page.
	 */
	public function register_page() {
		// Register a top-level item for users who cannot view the core WooCommerce menu.
		if ( ! self::is_admin_user() ) {
			wc_admin_register_page(
				array(
					'id'         => 'woocommerce-home',
					'title'      => __( 'WooCommerce', 'woocommerce-admin' ),
					'path'       => self::MENU_SLUG,
					'capability' => 'read',
				)
			);
			return;
		}

		wc_admin_register_page(
			array(
				'id'         => 'woocommerce-home',
				'title'      => __( 'Home', 'woocommerce-admin' ),
				'parent'     => 'woocommerce',
				'path'       => self::MENU_SLUG,
				'order'      => 0,
				'capability' => 'read',
			)
		);
	}

	/**
	 * Check if the user can access the top-level WooCommerce item.
	 *
	 * @return bool
	 */
	public static function is_admin_user() {
		if ( ! class_exists( 'WC_Admin_Menus', false ) ) {
			include_once WC_ABSPATH . 'includes/admin/class-wc-admin-menus.php';
		}
		if ( method_exists( 'WC_Admin_Menus', 'can_view_woocommerce_menu_item' ) ) {
			return \WC_Admin_Menus::can_view_woocommerce_menu_item() || current_user_can( 'manage_woocommerce' );
		} else {
			// We leave this line for WC versions <= 6.2.
			return current_user_can( 'edit_others_shop_orders' ) || current_user_can( 'manage_woocommerce' );
		}
	}

	/**
	 * Possibly remove the WooCommerce menu item if it was purely used to access wc-admin pages.
	 */
	public function possibly_remove_woocommerce_menu() {
		global $menu;

		if ( self::is_admin_user() ) {
			return;
		}

		foreach ( $menu as $key => $menu_item ) {
			if ( self::MENU_SLUG !== $menu_item[2] || 'read' !== $menu_item[1] ) {
				continue;
			}

			unset( $menu[ $key ] );
		}
	}

	/**
	 * Update the WooCommerce menu structure to make our main dashboard/handler
	 * the top level link for 'WooCommerce'.
	 */
	public function update_link_structure() {
		global $submenu;
		// User does not have capabilites to see the submenu.
		if ( ! current_user_can( 'manage_woocommerce' ) || empty( $submenu['woocommerce'] ) ) {
			return;
		}

		$wc_admin_key = null;
		foreach ( $submenu['woocommerce'] as $submenu_key => $submenu_item ) {
			if ( self::MENU_SLUG === $submenu_item[2] ) {
				$wc_admin_key = $submenu_key;
				break;
			}
		}

		if ( ! $wc_admin_key ) {
			return;
		}

		$menu = $submenu['woocommerce'][ $wc_admin_key ];

		// Move menu item to top of array.
		unset( $submenu['woocommerce'][ $wc_admin_key ] );
		array_unshift( $submenu['woocommerce'], $menu );
	}

	/**
	 * Preload options to prime state of the application.
	 *
	 * @param array $options Array of options to preload.
	 * @return array
	 */
	public function preload_options( $options ) {
		$options[] = 'woocommerce_default_homepage_layout';
		$options[] = 'woocommerce_admin_install_timestamp';

		return $options;
	}

	/**
	 * Add data to the shared component settings.
	 *
	 * @param array $settings Shared component settings.
	 */
	public function component_settings( $settings ) {
		$allowed_statuses = Loader::get_order_statuses( wc_get_order_statuses() );

		// Remove the Draft Order status (from the Checkout Block).
		unset( $allowed_statuses['checkout-draft'] );

		$status_counts                     = array_map( 'wc_orders_count', array_keys( $allowed_statuses ) );
		$product_counts                    = wp_count_posts( 'product' );
		$settings['orderCount']            = array_sum( $status_counts );
		$settings['publishedProductCount'] = $product_counts->publish;

		return $settings;
	}
}
