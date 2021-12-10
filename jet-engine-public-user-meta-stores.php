<?php
/**
 * Plugin Name: JetEngine - public user meta stores
 * Plugin URI:
 * Description: Make user meta stores from Data Stores module publicy queryable and countable
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

define( 'JET_EPUDS__FILE__', __FILE__ );
define( 'JET_EPUDS_PLUGIN_BASE', plugin_basename( JET_EPUDS__FILE__ ) );
define( 'JET_EPUDS_PATH', plugin_dir_path( JET_EPUDS__FILE__ ) );

class Jet_Engine_Public_User_Stores {

	public function __construct() {
		add_action( 'jet-engine/elementor-views/dynamic-tags/register', array( $this, 'register_dynamic_tags' ), 20 );
		add_action( 'jet-engine/register-macros', array( $this, 'register_macros' ) );
	}

	/**
	 * Register relations related macros
	 *
	 * @return [type] [description]
	 */
	public function register_macros() {

		require_once JET_EPUDS_PATH . 'macros/get-store.php';

		new \Jet_Engine_Public_User_Stores\Macros\Get_Store();

	}

	/**
	 * Register dynamic tags
	 *
	 * @return [type] [description]
	 */
	public function register_dynamic_tags( $tags_module ) {

		require_once JET_EPUDS_PATH . 'dynamic-tags/store-count.php';
		require_once JET_EPUDS_PATH . 'dynamic-tags/get-store.php';

		$tags_module->register_tag( new \Jet_Engine_Public_User_Stores\Dynamic_Tags\Store_Count() );
		$tags_module->register_tag( new \Jet_Engine_Public_User_Stores\Dynamic_Tags\Get_Store() );

	}

	/**
	 * Check if data stroes module is enabled
	 *
	 * @return [type] [description]
	 */
	public function data_stores_enabled() {
		return class_exists( '\Jet_Engine\Modules\Data_Stores' );
	}

}

new Jet_Engine_Public_User_Stores();
