<?php
namespace Jet_Engine_Public_User_Stores\Dynamic_Tags;

use \Jet_Engine\Modules\Data_Stores\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Get_Store extends Store_Count {

	public function get_name() {
		return 'jet-public-data-store-get-store';
	}

	public function get_title() {
		return __( 'Public User Data Stores: Get Store Items', 'jet-engine' );
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
			return 'is-empty';
		} else {
			return implode( ',', $store_items );
		}

	}

}
