<?php
namespace Jet_Engine_Public_User_Stores\Dynamic_Tags;

use \Jet_Engine\Modules\Data_Stores\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Store_Count extends \Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'jet-public-data-store-store-count';
	}

	public function get_title() {
		return __( 'Public User Data Stores: Get Store Count', 'jet-engine' );
	}

	public function get_group() {
		return \Jet_Engine_Dynamic_Tags_Module::JET_GROUP;
	}

	public function get_categories() {
		return array(
			\Jet_Engine_Dynamic_Tags_Module::TEXT_CATEGORY,
			\Jet_Engine_Dynamic_Tags_Module::POST_META_CATEGORY,
		);
	}

	public function get_meta_stores() {

		$stores = Module::instance()->stores->get_stores();
		$options = array();

		foreach ( $stores as $store ) {

			if ( 'user-meta' === $store->get_type()->type_id() ) {
				$options[ $store->get_slug() ] = $store->get_name();
			}

		}

		return $options;

	}

	protected function _register_controls() {

		$stores = $this->get_meta_stores();

		if ( empty( $stores ) ) {
			$this->add_control(
				'empty_data_stores',
				array(
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw'  => 'This Dynamic Tag can work only with User Meta stores. Please create at least one User Meta store.'
				)
			);
		} else {

			$stores  = array( '' => __( 'Select...', 'jet-engine' ) ) + $stores;

			$this->add_control(
				'data_store',
				array(
					'label'   => __( 'Store', 'jet-engine' ),
					'type'    => \Elementor\Controls_Manager::SELECT,
					'options' => $stores,
				)
			);
		}

		$this->add_control(
			'user_context',
			array(
				'label'   => __( 'Context', 'jet-engine' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'current_user'        => __( 'Current user', 'jet-engine' ),
					'queried_user'        => __( 'Queried user', 'jet-engine' ),
					'current_post_author' => __( 'Current post author', 'jet-engine' ),
				),
			)
		);

	}

	public function get_user_by_context( $context ) {

		$user = false;

		switch ( $context ) {

			case 'queried_user':
				$user = jet_engine()->listings->data->get_queried_user_object();
				break;

			case 'current_post_author':

				global $post;

				if ( $post && $post->post_author ) {
					$user = get_user_by( 'id', $post->post_author );
				}

				break;

			default:
				if ( is_user_logged_in() ) {
					$user = wp_get_current_user();
				}
				break;
		}

		return $user;

	}

	public function get_value( array $options = array() ) {

		$store   = $this->get_settings( 'data_store' );
		$context = $this->get_settings( 'user_context' );

		if ( ! $store ) {
			return;
		}

		$user = $this->get_user_by_context( $context );

		if ( ! $user ) {
			return;
		}

		$store_items = get_user_meta( $user->ID, 'je_data_store_' . $store, true );

		if ( empty( $store_items ) ) {
			return 0;
		} else {
			return count( $store_items );
		}

	}

}
