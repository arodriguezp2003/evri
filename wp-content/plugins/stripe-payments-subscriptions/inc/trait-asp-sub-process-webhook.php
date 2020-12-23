<?php

trait ASP_SUB_Process_Webhook {

	private $hook;
	private $is_live;
	private $input;

	function process_hook( $hook_type ) {
		$this->is_live = 'live' === $hook_type ? true : false;

		$this->input = file_get_contents( 'php://input' );
		if ( empty( $this->input ) ) {
			$this->aspsub_main->log( 'Stripe Subscription Webhook sent empty data or page was accessed directly. Aborting.', false );
			echo 'Empty Webhook data received.';
			die;
		}

		$key = $this->is_live ? $this->asp_main->APISecKeyLive : $this->asp_main->APISecKeyTest;
		\Stripe\Stripe::setApiKey( $key );

		$this->hook = json_decode( $this->input );

		//let's check what type of webhook we have received
		switch ( $this->hook->type ) {
			case 'customer.subscription.updated':
				$this->check_signature();
				$this->process_subs_updated();
				break;
			case 'invoice.payment_succeeded':
				$this->check_signature();
				$this->process_invoice_paid();
				break;
			case 'customer.subscription.deleted':
				$this->check_signature();
				$this->process_subs_deleted();
				break;
			case 'customer.source.expiring':
				$this->check_signature();
				$this->process_cust_source_expiring();
				break;
			default:
				//              $this->aspsub_main->log( "Received webhook type which doesn't need to be processed: " . $this->hook->type );
				break;
		}

		http_response_code( 200 );
		die;
	}

	function process_cust_source_expiring() {
		$this->aspsub_main->log( 'Processing "customer.source.expiring" hook.' );
		//check if this is card expiration event type
		if ( $this->hook->data->object->object !== 'card' ) {
			//the source isn't a card, we cannot update it
			$this->aspsub_main->log( 'Expiring source is not a card.', false );
			return;
		}
		//get customer id
		$customer_id = $this->hook->data->object->customer;
		//let's try to find subscription which this customer is subscribed to
		$sub = $this->find_sub_by_customer( $customer_id );
		if ( ! $sub ) {
			//we can't find this subscription, so we just ignore this hook
			$this->aspsub_main->log( 'Subscription not found. Aborting.', false );
			return;
		}
		//check sub status
		$status = get_post_meta( $sub->ID, 'sub_status', true );
		if ( ! ASPSUB_Utils::is_sub_active( $status ) ) {
			//subscription is not active
			$this->aspsub_main->log( 'Subscription is not active. Aborting.', false );
			return;
		}
		//let's flag this subscription as customer CC expiring
		update_post_meta( $sub->ID, 'customer_cc_expiring', true );

		//let's see if email notification for expiring CC is enabeld
		if ( ! $this->asp_main->get_setting( 'sub_expiry_email_enabled' ) ) {
			//it's disabled. Not sending email
			$this->aspsub_main->log( 'Expiring CC notification email is disabled in the settings. Aborting.', false );
			return;
		}
		//let's send email to customer
		$this->aspsub_main->log( 'Preparing customer email.' );
		$cust_email = get_post_meta( $sub->ID, 'customer_email', true );
		include_once $this->aspsub_main->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';
		$sub_class     = ASPSUB_stripe_subs::get_instance();
		$sub_id        = get_post_meta( $sub->ID, 'sub_id', true );
		$update_cc_url = $sub_class->get_update_cc_link( $sub_id );

		$tags    = array(
			'{card_brand}',
			'{card_last_4}',
			'{card_exp_month}',
			'{card_exp_year}',
			'{update_cc_url}',
		);
		$vals    = array(
			$this->hook->data->object->brand,
			$this->hook->data->object->last4,
			$this->hook->data->object->exp_month,
			$this->hook->data->object->exp_year,
			$update_cc_url,
		);
		$to      = $cust_email;
		$from    = $this->asp_main->get_setting( 'sub_expiry_email_from' );
		$headers = 'From: ' . $from . "\r\n";
		$subj    = $this->asp_main->get_setting( 'sub_expiry_email_subj' );
		$body    = $this->asp_main->get_setting( 'sub_expiry_email_body' );
		$body    = str_replace( $tags, $vals, $body );
		$this->aspsub_main->log( 'Sending email to ' . $cust_email );
		wp_mail( $to, $subj, $body, $headers );
		$this->aspsub_main->log( 'Completed customer.source.expiring processing.' );
	}

	function process_subs_deleted() {
		$this->aspsub_main->log( 'Processing "customer.subscription.deleted" hook.' );
		//let's try to find subscription by id
		$sub_id = $this->hook->data->object->id;
		$this->aspsub_main->log( 'Searching for subscription ' . $sub_id );
		$sub = $this->find_sub( $sub_id );
		if ( ! $sub ) {
			$this->aspsub_main->log( 'Subscription not found. Aborting.', false );
			//we can't find this subscription, so we just ignore this hook
			return;
		}
		$this->aspsub_main->log( 'Subscription found. Updating data.' );
		$events = get_post_meta( $sub->ID, 'events', true );
		if ( ! $events ) {
			$events = array();
		}
		$ended = get_post_meta( $sub->ID, 'sub_ended', true );
		if ( $ended ) {
			$descr  = 'Subscription ended';
			$status = 'ended';
			do_action( 'asp_subscription_ended', $sub->ID, get_object_vars( $this->hook->data->object ) );
		} else {
			$descr  = 'Subscription canceled';
			$status = $this->hook->data->object->status;
			do_action( 'asp_subscription_canceled', $sub->ID, get_object_vars( $this->hook->data->object ) );
		}
		$events[] = array(
			'date'   => time(),
			'descr'  => $descr,
			'status' => $status,
		);
		update_post_meta( $sub->ID, 'events', $events );
		update_post_meta( $sub->ID, 'sub_status', $status );
		$this->aspsub_main->log( 'Subscription data updated.' );
		return;
	}

	function process_subs_updated() {
		$this->aspsub_main->log( 'Processing "customer.subscription.updated" hook.' );
		//let's try to find subscription by id
		$sub_id = $this->hook->data->object->id;
		$this->aspsub_main->log( 'Searching for subscription ' . $sub_id );
		$sub = $this->find_sub( $sub_id );
		if ( ! $sub ) {
			$this->aspsub_main->log( 'Subscription not found. Aborting.', false );
			//we can't find this subscription, so we just ignore this hook
			return;
		}
		$this->aspsub_main->log( 'Subscription found. Updating data.' );
		$events = get_post_meta( $sub->ID, 'events', true );
		if ( ! $events ) {
			$events = array();
		}
		$events[] = array(
			'date'   => time(),
			'descr'  => 'Subscription updated',
			'status' => $this->hook->data->object->status,
		);
		update_post_meta( $sub->ID, 'events', $events );
		update_post_meta( $sub->ID, 'sub_status', $this->hook->data->object->status );
		$this->aspsub_main->log( 'Subscription data updated.' );
		return;
	}

	function process_invoice_paid() {
		$this->aspsub_main->log( 'Processing "invoice.payment_succeeded" hook.' );
		//let's try to find subscription by id
		$sub_id = $this->hook->data->object->subscription;
		$this->aspsub_main->log( 'Searching for subscription ' . $sub_id );
		$sub = $this->find_sub( $sub_id );
		if ( ! $sub ) {
			$this->aspsub_main->log( 'Subscription not found. Aborting.', false );
			//we can't find this subscription, so we just ignore this hook
			return;
		}
		$this->aspsub_main->log( sprintf( 'Subscription found: %s. Updating data.', $sub->ID ) );

		$events = get_post_meta( $sub->ID, 'events', true );
		if ( ! $events ) {
			$events = array();
		}

		$descr = __( 'Regular payment made', 'asp-sub' );

		$regular_payment = true;

		$amount = $this->hook->data->object->total;

		if ( 0 === $amount ) {
			//this is most likely trial period zero amount payment
			$regular_payment = false;
			$descr           = __( 'Trial period started', 'asp-sub' );
		} else {
			//check if this is paid trial period
			if ( in_array( $this->hook->data->object->billing_reason, array( 'subscription_update', 'subscription_create' ), true ) ) {
				if ( 'list' === $this->hook->data->object->lines->object && is_array( $this->hook->data->object->lines->data ) ) {
					foreach ( $this->hook->data->object->lines->data as $line_item ) {
						if ( isset( $line_item->metadata->asp_type ) && 'trial_setup_fee' === $line_item->metadata->asp_type ) {
							//this is paid trial period
							$regular_payment = false;
							$descr           = __( 'Paid trial period started', 'asp-sub' );
							break;
						}
					}
				}
			}

			if ( $regular_payment ) {
				//this is regular payment
				$payments_made = get_post_meta( $sub->ID, 'payments_made', true );
				$payments_made ++;
				update_post_meta( $sub->ID, 'payments_made', $payments_made );
			}
		}

		$date = isset( $this->hook->data->object->date ) ? $this->hook->data->object->date : $this->hook->data->object->created;

		$events[] = array(
			'date'   => $date,
			'descr'  => $descr,
			'amount' => $amount,
		);
		update_post_meta( $sub->ID, 'events', $events );
		$this->aspsub_main->log( 'Event data updated.' );
		//let's fire a hook that contains subscription and payment details
		do_action( 'asp_subscription_invoice_paid', $sub->ID, get_object_vars( $this->hook->data->object ) );
		//let's try to find corresponding plan
		$plan_id = get_post_meta( $sub->ID, 'plan_post_id', true );
		$plan    = $this->find_plan( $plan_id );
		if ( ! $plan ) {
			//we can't find plan - ignoring duration check
			return;
		}
		$duration = get_post_meta( $plan->ID, 'asp_sub_plan_duration', true );
		if ( empty( $duration ) ) {
			//no duration set - ignoring duration check
			return;
		}
		if ( isset( $payments_made ) ) {
			$this->aspsub_main->log( 'Plan has duration set. Checking if enough payments have been made to end the plan.' );
			$this->aspsub_main->log( sprintf( 'Duration: %d, payments pade: %d', $duration, $payments_made ) );

			if ( $payments_made >= $duration ) {
				//enough payments have been made. It's time to end user's subscription.
				//let's send cancel request via Stripe API
				$this->aspsub_main->log( 'Subscription has ended.' );
				include_once ASPSUB_main::get_plugin_dir() . 'inc/asp-sub-stripe-subs-class.php';
				$aspsub_subs = new ASPSUB_stripe_subs( $this->is_live );
				$res         = $aspsub_subs->cancel( $sub->ID, true );
				if ( ! $res ) {
					$this->aspsub_main->log( 'Error occurred: ' . $aspsub_subs->get_last_error(), false );
				}
			} else {
				//there are more payments to go
				$this->aspsub_main->log( sprintf( 'Subscription is not expired yet. Number of payments left: %d', $duration - $payments_made ) );
			}
		}
		return;
	}

	function check_signature() {
		//$this->aspsub_main->log( $this->input );

		$webhook_sec = $this->asp_main->get_setting( ( $this->is_live ? 'live_' : 'test_' ) . 'webhook_secret' );
		if ( ! empty( $webhook_sec ) ) {
			//Webhook signing secret set. Let's check received data signature
			$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
			$event      = null;

			try {
				$event = \Stripe\Webhook::constructEvent(
					$this->input,
					$sig_header,
					$webhook_sec
				);
			} catch ( \UnexpectedValueException $e ) {
				// Invalid payload
				$this->aspsub_main->log( 'Webhook signature check failed: Invalid payload.', false );
				http_response_code( 400 );
				die;
			} catch ( \Stripe\Exception\SignatureVerificationException $e ) {
				// Invalid signature
				$debug_data = array(
					'mode'           => $this->is_live ? 'live' : 'test',
					'signing_secret' => $webhook_sec,
				);
				$this->aspsub_main->log( 'Webhook signature check failed: Invalid signature. Debug data: ' . json_encode( $debug_data ), false );
				http_response_code( 400 );
				die;
			}
			$this->aspsub_main->log( 'Webhook signature successfully checked.' );
		}
	}

	function find_sub( $sub_id ) {
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

	function find_plan( $plan_id ) {
		$plan = get_posts(
			array(
				'post_type' => ASPSUB_main::$plans_slug,
				'p'         => $plan_id,
			)
		);
		if ( $plan ) {
			return $plan[0];
		} else {
			return false;
		}
	}

	function find_sub_by_customer( $customer_id ) {
		$sub = get_posts(
			array(
				'post_type'   => ASPSUB_main::$subs_slug,
				'meta_key'    => 'cust_id',
				'meta_value'  => $customer_id,
				'post_status' => array( 'publish', 'pending' ),
			)
		);
		if ( $sub ) {
			return $sub[0];
		} else {
			return false;
		}
	}

}
