<?php

trait ASP_SUB_Main_Legacy {

	public function process_charge( $data ) {
		require_once $this->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';

		$this->log( 'Starting subscription process.' );

		if ( ! isset( $this->plan ) ) {

			if ( empty( $data['product_id'] ) ) {
				$this->log( 'No product ID specified.', false );
				return $data;
			}
			$id = $data['product_id'];

			$plan_id = get_post_meta( $id, 'asp_sub_plan_id', true );

			if ( ! $plan_id ) {
				$this->log( 'No plan ID specified.', false );
				return $data;
			}

			$plan = $this->get_plan_data( $plan_id );

			if ( ! $plan ) {
				$this->log( 'Can\'t get plan data.', false );
				return $data;
			}
		} else {
			$plan    = $this->plan;
			$id      = $data['product_id'];
			$plan_id = get_post_meta( $id, 'asp_sub_plan_id', true );
		}

		//handle variable currency
		if ( isset( $plan->is_variable ) && $plan->is_variable && ! empty( $plan->variables['currency'] ) ) {
			$plan->currency = sanitize_text_field( $_POST['stripeCurrency'] );
		}

		$currency_code = strtoupper( $plan->currency );

		//check if we need to apply coupon
		if ( ! empty( $data['coupon'] ) ) {
			$coupon = $data['coupon'];
			if ( $coupon['valid'] ) {
				if ( $coupon['discountType'] === 'perc' ) {
					$coupon_params = array(
						'name'        => $coupon['code'],
						'percent_off' => $coupon['discount'],
					);
				} else {
					$coupon_params = array(
						'name'       => $coupon['code'],
						'amount_off' => AcceptStripePayments::is_zero_cents( $currency_code ) ? $coupon['discountAmount'] : $coupon['discountAmount'] * 100,
						'currency'   => $currency_code,
					);
				}
				//check and set subscription-related coupon parameters
				$sub_applied_for        = get_post_meta( $coupon['id'], 'asp_sub_applied_for', true );
				$sub_applied_for        = empty( $sub_applied_for ) ? 'forever' : $sub_applied_for;
				$sub_applied_for_months = get_post_meta( $coupon['id'], 'asp_sub_applied_for_months', true );
				$sub_applied_for_months = empty( $sub_applied_for_months ) ? 1 : $sub_applied_for_months;

				$coupon_params['duration'] = $sub_applied_for;
				if ( $sub_applied_for === 'repeating' ) {
					$coupon_params['duration_in_months'] = $sub_applied_for_months;
				}
				$this->log( 'Following coupon parameters applied: ' . json_encode( $coupon_params ) );
			} else {
				unset( $coupon );
			}
		}

		//check if this is variable plan
		if ( isset( $plan->is_variable ) && $plan->is_variable ) {
			//we need to create a plan first
			$ASPSUB_main = self::get_instance();
			include_once $ASPSUB_main->PLUGIN_DIR . 'inc/asp-sub-stripe-plans-class.php';

			$stripe_plans = new ASPSUB_stripe_plans( $plan->livemode );
			//let's create pricing plan in Stripe
			$plan_opts = array(
				'currency'          => $plan->currency,
				'interval'          => $plan->interval,
				'interval_count'    => $plan->interval_count,
				'amount'            => $plan->amount,
				'nickname'          => $plan->nickname,
				'trial_period_days' => 0,
			);

			$plan = $stripe_plans->create( $plan_opts, $plan->post_id );
		}

		\Stripe\Stripe::setApiKey( $plan->livemode ? $this->ASPMain->APISecKeyLive : $this->ASPMain->APISecKeyTest );

		$items = array( 'plan' => $plan->id );

		$item_quantity = false;

		if ( isset( $data['item_quantity'] ) && ! empty( $data['item_quantity'] ) ) {
			$item_quantity = intval( $data['item_quantity'] );
		}

		if ( $item_quantity ) {
			$items['quantity'] = $item_quantity;
		}

		//preload subscriptions post-related class
		$sub = new ASPSUB_stripe_subs( $plan->livemode );

		try {
			if ( ! empty( $coupon_params ) ) {
				$coupon_res = \Stripe\Coupon::create( $coupon_params );
			}

			$customer_opts = array(
				'email'  => $_POST['stripeEmail'],
				'source' => $_POST['stripeToken'],
			);

			//let's try to get customer name if available
			$name = filter_input( INPUT_POST, 'stripeBillingName', FILTER_SANITIZE_STRING );

			if ( ! empty( $name ) ) {
				$customer_opts['name'] = $name;
			}

			//let's try to get customer billing address if available
			$addr_line1 = filter_input( INPUT_POST, 'stripeBillingAddressLine1', FILTER_SANITIZE_STRING );

			if ( ! empty( $addr_line1 ) ) {
				//we have address
				$addr = array( 'line1' => $addr_line1 );

				$addr_vars = array(
					'stripeBillingAddressCity'    => 'city',
					'stripeBillingAddressZip'     => 'postal_code',
					'stripeBillingAddressState'   => 'state',
					'stripeBillingAddressCountry' => 'country',
					'stripeBillingAddressApt'     => 'line2',
				);

				foreach ( $addr_vars as $a_in => $a_out ) {
					$var = filter_input( INPUT_POST, $a_in, FILTER_SANITIZE_STRING );
					if ( ! empty( $var ) ) {
						$addr[ $a_out ] = $var;
					}
				}

				$customer_opts['address'] = $addr;
			}

			$customer = \Stripe\Customer::create( $customer_opts );

			$sub_opts = array(
				'customer'        => $customer->id,
				'items'           => array( $items ),
				'trial_from_plan' => true,
			);

			$tax = get_post_meta( $id, 'asp_product_tax', true );

			if ( ! empty( $tax ) ) {
				$sub_opts['tax_percent'] = floatval( $tax );
			}

			if ( ! empty( $coupon_res ) ) {
				$sub_opts['coupon'] = $coupon_res->id;
			}

			$subscription = \Stripe\Subscription::create( $sub_opts );
		} catch ( Exception $e ) {
			$this->log( 'Stripe API error occurred: ' . $e->getMessage(), false );
			return $data;
		}

		$sub_id = $sub->insert( $subscription, $customer, $plan_id, $id, array() );

		if ( ! empty( $coupon_res ) ) {
			$coupon_res->delete();
		}

		$this->log( sprintf( 'Customer has been successfully subscribed to plan "%s" (%s)', $plan_id, $plan->nickname ) );

		$additional_data = array();

		//check if there are cookies set by WP Affiliate Platform. If there are, we need to store them in subscription data
		if ( ! empty( $_COOKIE['ap_id'] ) ) {
			$additional_data['ap_id'] = $_COOKIE['ap_id'];
		}
		if ( ! empty( $_COOKIE['wpam_id'] ) ) {
			$additional_data['wpam_id'] = $_COOKIE['wpam_id'];
		}

		//let's check if Membership Level is set for this product
		$level_id = get_post_meta( $data['product_id'], 'asp_product_emember_level', true );
		if ( ! empty( $level_id ) ) {
			//check if we have eMember member id available
			if ( class_exists( 'Emember_Auth' ) ) {
				//Check if the user is logged in as a member.
				$emember_auth = Emember_Auth::getInstance();
				$emember_id   = $emember_auth->getUserInfo( 'member_id' );

				if ( ! empty( $emember_id ) ) {
					$additional_data['emember_id'] = $emember_id;
				}
			}
		}

		if ( ! empty( $additional_data ) ) {
			update_post_meta( $sub_id, 'sub_additional_data', $additional_data );
		}

		$data['charge'] = $subscription;

		$amount = $plan->amount;

		if ( ! empty( $plan->trial_period_days ) ) {
			$amount           = 0;
			$data['is_trial'] = true;
		}

		$item_price = $amount;

		if ( $amount > 0 ) {

			$discount_amount = ! empty( $coupon['discountAmount'] ) ? AcceptStripePayments::is_zero_cents( $currency_code ) ? $coupon['discountAmount'] : $coupon['discountAmount'] * 100 : 0;

			if ( ! empty( $discount_amount ) ) {
				$amount = $amount - $discount_amount;
			}
		}

		if ( ! empty( $tax ) ) {
			$amount = round( AcceptStripePayments::apply_tax( $amount, $tax ), 0 );
		}

		$amount = ! AcceptStripePayments::is_zero_cents( $currency_code ) ? $amount / 100 : $amount;

		if ( $item_quantity ) {
			$amount = $amount * $item_quantity;
		}

		$data['currency_code'] = strtoupper( $plan->currency );
		$data['item_price']    = ! AcceptStripePayments::is_zero_cents( $currency_code ) ? $item_price / 100 : $item_price;
		$data['paid_amount']   = $amount;

		if ( isset( $data['addonName'] ) ) {
			$data['addonName'] .= '[SUB]';
		} else {
			$data['addonName'] = '[SUB]';
		}

		$data['is_live'] = $plan->livemode ? true : false;

		return $data;
	}

	public function before_payment_processing( $res, $post ) {
		$this->log( 'Starting pre-subscription process.' );

		if ( empty( $post['stripeProductId'] ) ) {
			$this->log( 'No product ID specified.', false );
			return;
		}
		$id = $post['stripeProductId'];

		$plan_id = get_post_meta( $id, 'asp_sub_plan_id', true );

		if ( ! $plan_id ) {
			$this->log( 'No plan ID specified.', false );
			return;
		}

		$plan = $this->get_plan_data( $plan_id );

		if ( ! $plan ) {
			$this->log( 'Can\'t get plan data.', false );
			return;
		}
		$amount = ! AcceptStripePayments::is_zero_cents( $plan->currency ) ? $plan->amount / 100 : $plan->amount;

		$_POST['stripeAmount'] = $amount;

		$this->plan = $plan;
	}

}
