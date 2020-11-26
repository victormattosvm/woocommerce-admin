<?php
/**
 * Navigation Experience
 *
 * @package Woocommerce Admin
 */

namespace Automattic\WooCommerce\Admin\Features\Navigation;

use Automattic\WooCommerce\Admin\Loader;
use Automattic\WooCommerce\Admin\Features\Navigation\Screen;
use Automattic\WooCommerce\Admin\Features\Navigation\Menu;
use Automattic\WooCommerce\Admin\Features\Navigation\CoreMenu;

/**
 * Contains logic for the Navigation
 */
class Init {
	/**
	 * Hook into WooCommerce.
	 */
	public function __construct() {
		add_filter( 'woocommerce_admin_preload_options', array( $this, 'preload_options' ) );
		add_filter( 'woocommerce_admin_features', array( $this, 'maybe_remove_nav_feature' ), 0 );
		add_action( 'update_option_woocommerce_navigation_enabled', array( $this, 'reload_page_on_toggle' ), 10, 2 );

		if ( Loader::is_feature_enabled( 'navigation' ) ) {
			add_action( 'in_admin_header', array( __CLASS__, 'embed_navigation' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_scripts' ) );

			Menu::instance()->init();
			CoreMenu::instance()->init();
			Screen::instance()->init();
		}
	}

	/**
	 * Determine if sufficient versions are present to support Navigation feature
	 */
	public function is_nav_compatible() {
		$has_gutenberg     = is_plugin_active( 'gutenberg/gutenberg.php' );
		$gutenberg_version = $has_gutenberg ? get_plugin_data( ABSPATH . 'wp-content/plugins/gutenberg/gutenberg.php' )['Version'] : false;

		if ( $gutenberg_version && version_compare( $gutenberg_version, '9.0.0', '>=' ) ) {
			return true;
		}

		$current    = get_site_transient( 'update_core' );
		$wp_version = $current->version_checked;

		if ( version_compare( $wp_version, '5.6.0', '<' ) ) {
			return false;
		}
	}

	/**
	 * Overwrites the allowed features array using a local `feature-config.php` file.
	 *
	 * @param array $features Array of feature slugs.
	 */
	public function maybe_remove_nav_feature( $features ) {
		$has_feature_enabled = in_array( 'navigation', $features, true );
		$has_option_disabled = 'yes' !== get_option( 'woocommerce_navigation_enabled', 'no' );
		$is_not_compatible   = ! self::is_nav_compatible();

		if ( ( $has_feature_enabled && $has_option_disabled ) || $is_not_compatible ) {
			$features = array_diff( $features, array( 'navigation' ) );
		}
		return $features;
	}

	/**
	 * Preload options to prime state of the application.
	 *
	 * @param array $options Array of options to preload.
	 * @return array
	 */
	public function preload_options( $options ) {
		$options[] = 'woocommerce_navigation_enabled';

		return $options;
	}

	/**
	 * Set up a div for the navigation.
	 * The initial contents here are meant as a place loader for when the PHP page initialy loads.
	 */
	public static function embed_navigation() {
		if ( ! Screen::is_woocommerce_page() ) {
			return;
		}

		?>
		<div id="woocommerce-embedded-navigation"></div>
		<?php
	}

	/**
	 * Enqueue scripts on non-WooCommerce pages.
	 */
	public function maybe_enqueue_scripts() {
		if ( Screen::is_woocommerce_page() ) {
			return;
		}

		$rtl = is_rtl() ? '-rtl' : '';

		wp_enqueue_style(
			'wc-admin-navigation',
			Loader::get_url( "navigation/style{$rtl}", 'css' ),
			array(),
			Loader::get_file_version( 'css' )
		);
	}

	/**
	 * Reloads the page when the option is toggled to make sure all nav features are loaded.
	 *
	 * @param string $old_value Old value.
	 * @param string $value     New value.
	 */
	public static function reload_page_on_toggle( $old_value, $value ) {
		if ( $old_value === $value ) {
			return;
		}

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			wp_safe_redirect( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}
	}
}
