<?php
namespace Jet_Engine_Public_User_Stores\Macros;

use \Jet_Engine\Modules\Data_Stores\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Get_Store extends \Jet_Engine_Base_Macros {

	/**
	 * Returns macros tag
	 *
	 * @return string
	 */
	public function macros_tag() {
		return 'public_stores_get_store';
	}

	/**
	 * Returns macros name
	 *
	 * @return string
	 */
	public function macros_name() {
		return __( 'Public User Meta Stores: Get Store', 'jet-engine' );
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

	/**
	 * Callback function to return macros value
	 *
	 * @return string
	 */
	public function macros_callback( $args = array() ) {

		$store   = $args['store'];
		$context = $args['context'];

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

	public function get_meta_stores() {

		$stores = Module::instance()->stores->get_stores();
		$options = array( '' => 'Select...' );

		foreach ( $stores as $store ) {

			if ( 'user-meta' === $store->get_type()->type_id() ) {
				$options[ $store->get_slug() ] = $store->get_name();
			}

		}

		return $options;

	}

	/**
	 * Optionally return custom macros attributes array
	 *
	 * @return array
	 */
	public function macros_args() {

		return array(
			'store' => array(
				'label'   => __( 'Select Meta Store', 'jet-engine' ),
				'type'    => 'select',
				'options' => $this->get_meta_stores(),
				'default' => '',
			),
			'context' => array(
				'label'   => __( 'Context', 'jet-engine' ),
				'type'    => 'select',
				'options' => array(
					'current_user'        => __( 'Current user', 'jet-engine' ),
					'queried_user'        => __( 'Queried user', 'jet-engine' ),
					'current_post_author' => __( 'Current post author', 'jet-engine' ),
				),
			),
		);

	}

}
