<?php

class ASP_Sub_Plan {
	protected $plan     = false;
	protected $last_err = '';
	protected $post_id  = 0;
	protected static $instance;

	public function __construct( $plan_id ) {
		self::$instance = $this;
		//get Stripe lib
		//it must be loaded before getting plan data from cache, otherwise cached plan data object would become __PHP_Incomplete_Class
		ASPMain::load_stripe_lib();

		$this->asp_main = AcceptStripePayments::get_instance();

		$this->post_id = $plan_id;

		$is_live     = get_post_meta( $plan_id, 'asp_sub_plan_is_live', true );
		$is_variable = get_post_meta( $plan_id, 'asp_sub_plan_is_variable', true );

		if ( $is_variable ) {
			//this is variable plan. Let's return its data
			$plan_post                  = get_post( $plan_id );
			$plan                       = new stdClass();
			$plan->currency             = get_post_meta( $plan_id, 'asp_sub_plan_currency', true );
			$plan->interval             = get_post_meta( $plan_id, 'asp_sub_plan_period_units', true );
			$plan->interval_count       = get_post_meta( $plan_id, 'asp_sub_plan_period', true );
			$plan->name                 = $plan_post->post_title;
			$plan->nickname             = $plan_post->post_title;
			$plan->statement_descriptor = null;
			$plan->is_variable          = true;
			$plan->livemode             = $is_live;
			$plan->post_id              = $plan_id;
			$plan->variables            = get_post_meta( $plan_id, 'asp_sub_plan_variables', true );
			$plan->trial_period_days    = get_post_meta( $plan_id, 'asp_sub_plan_trial', true );

			$p_amount     = filter_input( INPUT_POST, 'stripeAmount', FILTER_SANITIZE_STRING );
			$p_asp_amount = filter_input( INPUT_POST, 'asp_amount', FILTER_SANITIZE_STRING );

			if ( isset( $plan->variables['price'] ) && ( isset( $p_amount ) || isset( $p_asp_amount ) ) ) {
				if ( isset( $p_amount ) ) {
					$amt = floatval( $p_amount );
				}
				if ( empty( $amt ) && isset( $p_asp_amount ) ) {
					$amt = floatval( $p_asp_amount );
					if ( ! AcceptStripePayments::is_zero_cents( $plan->currency ) ) {
						$amt = round( $amt * 100 );
					}
				}
				$plan->amount = $amt;
			} else {
				if ( ! isset( $plan->variables['price'] ) ) {
					$plan->amount = get_post_meta( $plan_id, 'asp_sub_plan_price', true );
					if ( ! in_array( strtoupper( $plan->currency ), $this->asp_main->zeroCents ) ) {
						$plan->amount = $plan->amount * 100;
					}
				} else {
					$plan->amount = 0;
				}
			}
			$this->plan = $plan;
			return true;
		}

		//convert plan post ID to stripe plan ID
		$plan_id = get_post_meta( $plan_id, 'asp_sub_stripe_plan_id', true );

		if ( empty( $plan_id ) ) {
			$this->last_err = 'Stripe plan ID is empty';
			return false;
		}

		//let's check if we have plan data in cache
		$plans = get_option( 'asp_sub_plans_cache' );

		if ( $is_live ) {
			$key = $this->asp_main->APISecKeyLive;
		} else {
			$key = $this->asp_main->APISecKeyTest;
		}

		\Stripe\Stripe::setApiKey( $key );

		if ( ! $plans || ! isset( $plans[ $plan_id ] ) ) {
			//let's try to fetch plan by id
			try {
				$plan = \Stripe\Plan::retrieve( $plan_id );
			} catch ( EXCEPTION $e ) {
				$this->last_err = sprintf( 'Error occurred during plan retrieve: %s', $e->getMessage() );
				return false;
			}
			//store plan object in cache
			if ( ! $plans ) {
				$plans = array();
			}
			$plans[ $plan_id ] = $plan;
			update_option( 'asp_sub_plans_cache', $plans );
		}
		$this->plan = $plans[ $plan_id ];
		return true;

	}

	public static function get_instance() {
		return ! empty( self::$instance ) ? self::$instance : false;
	}

	public function get_setup_fee() {
		//check if core plugin version >=2.0.23
		if ( version_compare( WP_ASP_PLUGIN_VERSION, '2.0.23t1' ) < 0 ) {
			//core version does not support setup fee
			return 0;
		}
		$setup_fee = get_post_meta( $this->post_id, 'asp_sub_plan_setup_fee', true );
		return $setup_fee;
	}

	public function get_trial_setup_fee() {
		//check if core plugin version >=2.0.25
		if ( version_compare( WP_ASP_PLUGIN_VERSION, '2.0.25t1' ) < 0 ) {
			//core version does not support setup fee
			return 0;
		}
		$trial_setup_fee = get_post_meta( $this->post_id, 'asp_sub_plan_trial_setup_fee', true );
		return $trial_setup_fee;
	}

	public function get_last_error() {
		return $this->last_err;
	}

	public function get_plan_obj() {
		return $this->plan;
	}

}
