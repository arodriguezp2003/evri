<?php

class ASPSUB_subscription {

	public $status         = false;
	private $last_error    = false;
	protected $sub_id      = false;
	protected $sub_post_id = false;
	protected $sub_post    = false;
	private $cancel_url    = false;

	private $active_statuses = array( 'incomplete', 'trialing', 'active', 'past_due' );

	public function __construct( $sub_id ) {
		$sub = $this->find_sub_by_id( $sub_id );
		if ( ! $sub ) {
			$this->last_error = __( 'Subscription not found', 'asp-sub' );
			return false;
		}

		$this->sub_id      = $sub_id;
		$this->sub_post_id = $sub->ID;
		$this->sub_post    = $sub;
		$this->status      = get_post_meta( $this->sub_post_id, 'sub_status', true );
	}

	private function find_sub_by_id( $sub_id ) {
		$sub = get_posts(
			array(
				'post_type'  => ASPSUB_main::$subs_slug,
				'meta_key'   => 'sub_id',
				'meta_value' => $sub_id,
			)
		);
		if ( $sub ) {
			return $sub[0];
		} else {
			return false;
		}
	}

	public function is_active() {
		return in_array( $this->status, $this->active_statuses, true );
	}

	public function get_cancel_link() {
		if ( $this->cancel_url ) {
			return $this->cancel_url;
		}
		if ( $this->is_active() ) {
			$this->last_error = __( 'Subscription is not active', 'asp-sub' );
			return false;
		}
		//let's generate cancellation link
		$token = get_post_meta( $this->sub_post_id, 'sub_token', true );
		if ( empty( $token ) ) {
			$token = md5( $this->sub_post_id . $this->sub_id . uniqid() );
			update_post_meta( $this->sub_post_id, 'sub_token', $token );
		}
		$home_url         = get_home_url();
		$url              = add_query_arg(
			array(
				'asp_sub_action' => 'cancel',
				'sub_token'      => $token,
			),
			$home_url
		);
		$this->cancel_url = $url;
		return $this->cancel_url;
	}

}
