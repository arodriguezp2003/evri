<?php

class ASPSUB_stripe_plans {

	protected static $instance = null;
	protected $last_error      = false;
	protected $ASPMain;
	protected $ASPSub_main;
	public $ProdName = 'Stripe Payments Subs Addon Product #%d (%s)';

	public function __construct( $is_live ) {
		self::$instance = $this;

		ASPMain::load_stripe_lib();
		$this->ASPMain     = AcceptStripePayments::get_instance();
		$this->ASPSub_main = ASPSUB_main::get_instance();
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

	public function create( $opts, $post_id ) {
		update_option( 'asp_sub_plans_cache', '' );
		$metadata         = array(
			'created_by' => 'asp_sub_plugin',
			'plan_id'    => $post_id,
			'created_on' => time(),
			'uid'        => $this->ASPSub_main->get_uid(),
		);
		$opts['interval'] = rtrim( $opts['interval'], 's' );
		$opts['product']  = array(
			'name'     => html_entity_decode( get_the_title( $post_id ) ),
			'metadata' => $metadata,
		);
		if ( ! in_array( strtoupper( $opts['currency'] ), $this->ASPMain->zeroCents ) ) {
			$opts['amount'] = intval( round( $opts['amount'] * 100, 2 ) );
		}
		$opts['metadata'] = $metadata;
		try {
			$plan = \Stripe\Plan::create( $opts );
		} catch ( Exception $e ) {
			$this->last_error = $e->getMessage();
			return false;
		}
		return $plan;
	}

	public function update( $opts, $post_id ) {
		update_option( 'asp_sub_plans_cache', '' );
		try {
			$product       = \Stripe\Product::retrieve( get_post_meta( $post_id, 'asp_sub_stripe_plan_prod_id', true ) );
			$product->name = html_entity_decode( get_the_title( $post_id ) );
			$product->save();
		} catch ( Exception $e ) {
			$this->last_error = $e->getMessage();
			return false;
		}

		try {
			$plan                    = \Stripe\Plan::retrieve( get_post_meta( $post_id, 'asp_sub_stripe_plan_id', true ) );
			$plan->nickname          = $opts['nickname'];
			$plan->trial_period_days = $opts['trial_period_days'];
			$plan->save();
		} catch ( Exception $e ) {
			$this->last_error = $e->getMessage();
			return false;
		}
		return $plan;
	}

	public function delete( $post_id ) {
		update_option( 'asp_sub_plans_cache', '' );
		$plan_id = get_post_meta( $post_id, 'asp_sub_stripe_plan_id', true );
		try {
			$plan = \Stripe\Plan::retrieve( $plan_id );
			$plan->delete();
		} catch ( Exception $e ) {
			$this->last_error = $e->getMessage();
			return false;
		}
		return $plan;
	}

}
