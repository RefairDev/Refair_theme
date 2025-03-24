<?php
/**
 * File defining Live_Queries class.
 *
 * @package Pixelscodex
 */

namespace PixelscodexCore;

/**
 * Live_Queries
 */
class Live_Queries {

	/**
	 * Name of the action.
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Class constructor where internal variables and action hook are set.
	 *
	 * @param  string  $action Name of the action to set.
	 * @param  string  $cb Callback for action internal management.
	 * @param  boolean $priv Is available on admin or public side.
	 */
	public function __construct( $action, $cb = '', $priv = false ) {

		$this->name = $action;

		if ( empty( $cb ) ) {
			$cb = array( $this, 'action_cb' );
		}

		add_action( 'admin_post_' . $action, $cb );

		if ( ! $priv ) {
			add_action( 'admin_post_nopriv_' . $action, $cb );
		}
	}

	/**
	 * Default Action callback on query management.
	 *
	 * @return void
	 */
	public function action_cb() {
		echo 'success2';
	}
}
