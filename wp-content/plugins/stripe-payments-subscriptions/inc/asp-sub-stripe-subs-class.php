<?php

class ASPSUB_stripe_subs {

	protected static $instance = null;
	protected $last_error      = false;
	protected $post_slug;
	var $ASPMain;
	var $ProdName = 'Stripe Payments Subs Addon Product #%d (%s)';

	public function __construct( $is_live = false ) {
		self::$instance = $this;

		$this->post_slug = ASPSUB_main::$subs_slug;
		ASPMain::load_stripe_lib();
		$this->ASPMain = AcceptStripePayments::get_instance();
		if ( $is_live ) {
			$key = $this->ASPMain->APISecKeyLive;
		} else {
			$key = $this->ASPMain->APISecKeyTest;
		}
		try {
			\Stripe\Stripe::setApiKey( $key );
		} catch ( Exception $e ) {
			$this->last_error = $e->getMessage();
		}
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_last_error() {
		return $this->last_error;
	}

	public function insert( $sub, $cust, $plan_id, $prod_post_id, $additional_data = array() ) {
		$post                = array();
		$post['post_title']  = '';
		$post['post_status'] = 'publish';
		$post['content']     = '';
		$post['post_type']   = $this->post_slug;
		$post_id             = wp_insert_post( $post );

		$events = get_post_meta( $post_id, 'events', true );
		if ( ! $events ) {
			$events = array();
		}

		$events[] = array(
			'date'  => $sub->created,
			'descr' => __( 'Subscription created', 'asp-sub' ),
		);
		update_post_meta( $post_id, 'events', $events );

		update_post_meta( $post_id, 'sub_id', $sub->id );
		update_post_meta( $post_id, 'sub_date', $sub->created );
		update_post_meta( $post_id, 'sub_status', $sub->status );
		update_post_meta( $post_id, 'plan_id', $sub->plan->id );
		update_post_meta( $post_id, 'plan_post_id', $plan_id );
		update_post_meta( $post_id, 'prod_id', $sub->items->data[0]->plan->product );
		update_post_meta( $post_id, 'prod_post_id', $prod_post_id );
		update_post_meta( $post_id, 'cust_id', $sub->customer );
		$payments_made = get_post_meta( $post_id, 'payments_made', true );
		if ( empty( $payments_made ) ) {
			update_post_meta( $post_id, 'payments_made', 0 );
		}
		update_post_meta( $post_id, 'customer_email', $cust->email );
		update_post_meta( $post_id, 'is_live', $sub->livemode );
		if ( ! empty( $additional_data ) ) {
			update_post_meta( $post_id, 'sub_additional_data', $additional_data );
		}

		return $post_id;
	}

	public function cancel( $id, $ended = false ) {
		$sub_id = get_post_meta( $id, 'sub_id', true );
		if ( ! $sub_id ) {
			$this->last_error = __( 'Subscription not found', 'asp-sub' );
			return false;
		}
		try {
			$sub = \Stripe\Subscription::retrieve( $sub_id );
			$sub->cancel();
		} catch ( Exception $e ) {
			$this->last_error = $e->getMessage();
			return false;
		}
		if ( $ended ) {
			update_post_meta( $id, 'sub_ended', true );
		}
		update_post_meta( $id, 'sub_status', 'cancelling' );
		return $sub;
	}

	public function find_sub_by_token( $token ) {
		$sub = get_posts(
			array(
				'post_type'   => ASPSUB_main::$subs_slug,
				'meta_key'    => 'sub_token',
				'meta_value'  => $token,
				'post_status' => array( 'publish', 'pending' ),
			)
		);
		if ( $sub ) {
			return $sub[0];
		} else {
			return false;
		}
	}

	public function find_sub_by_id( $sub_id ) {
		$sub = get_posts(
			array(
				'post_type'   => ASPSUB_main::$subs_slug,
				'meta_key'    => 'sub_id',
				'meta_value'  => $sub_id,
				'post_status' => array( 'publish', 'pending' ),
			)
		);
		if ( $sub ) {
			return $sub[0];
		} else {
			return false;
		}
	}

	public function get_cancel_link( $sub_id ) {
		$sub = $this->find_sub_by_id( $sub_id );
		if ( ! $sub ) {
			$this->last_error = __( 'Subscription not found', 'asp-sub' );
			return false;
		}
		$sub_post_id = $sub->ID;
		//check if sub is active
		$status = get_post_meta( $sub_post_id, 'sub_status', true );
		if ( ! ASPSUB_Utils::is_sub_active( $status ) ) {
			$this->last_error = __( 'Subscription is not active', 'asp-sub' );
			return false;
		}
		//let's generate cancellation link
		$token = get_post_meta( $sub_post_id, 'sub_token', true );
		if ( empty( $token ) ) {
			$token = md5( $sub_post_id . $sub_id . uniqid() );
			update_post_meta( $sub_post_id, 'sub_token', $token );
		}
		$site_url = get_site_url( null, '/' );
		$url      = add_query_arg(
			array(
				'asp_sub_action' => 'cancel',
				'sub_token'      => $token,
			),
			$site_url
		);
		return $url;
	}

	public function get_update_cc_link( $sub_id ) {
		$sub = $this->find_sub_by_id( $sub_id );
		if ( ! $sub ) {
			$this->last_error = __( 'Subscription not found', 'asp-sub' );
			return false;
		}
		$sub_post_id = $sub->ID;
		//check if sub is active
		$status = get_post_meta( $sub_post_id, 'sub_status', true );
		if ( ! ASPSUB_Utils::is_sub_active( $status ) ) {
			$this->last_error = __( 'Subscription is not active', 'asp-sub' );
			return false;
		}
		//let's generate update CC link
		$token = get_post_meta( $sub_post_id, 'sub_token', true );
		if ( empty( $token ) ) {
			$token = md5( $sub_post_id . $sub_id . uniqid() );
			update_post_meta( $sub_post_id, 'sub_token', $token );
		}
		$site_url = get_site_url( null, '/' );
		$url      = add_query_arg(
			array(
				'asp_sub_action' => 'update',
				'sub_token'      => $token,
			),
			$site_url
		);
		return $url;
	}

}
