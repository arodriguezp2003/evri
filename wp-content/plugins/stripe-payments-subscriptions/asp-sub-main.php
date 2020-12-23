<?php

/**
 * Plugin Name: Stripe Payments Subscriptions Addon
 * Plugin URI: https://s-plugins.com/
 * Description: Adds Stripe Subscriptions support to the core plugin.
 * Version: 2.0.27
 * Author: Tips and Tricks HQ, alexanderfoxc
 * Author URI: https://s-plugins.com/
 * Text Domain: asp-sub
 * Domain Path: /languages
 * License: GPL2
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; //Exit if accessed directly
}

require_once plugin_dir_path( __FILE__ ) . 'inc/legacy/asp-sub-main-legacy.php';

class ASPSUB_main {

	use ASP_SUB_Main_Legacy;

	protected static $instance = null;
	protected $plugin_uid      = false;
	public static $plans_slug  = 'asp_sub_plan';
	public static $subs_slug   = 'asp_sub_subs';

	const ADDON_VER = '2.0.27';

	public $helper;
	public $file;
	public $ADDON_SHORT_NAME  = 'Sub';
	public $ADDON_FULL_NAME   = 'Stripe Payments Subscriptions Addon';
	public $MIN_ASP_VER       = '1.9.12';
	public $SLUG              = 'stripe-payments-subscriptions';
	public $SETTINGS_TAB_NAME = 'sub';
	public $textdomain        = 'asp-sub';
	public $asp_main;
	public $PLUGIN_DIR;
	public $plan;

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		self::$instance = $this;

		$this->PLUGIN_DIR = plugin_dir_path( __FILE__ );
		$this->file       = __FILE__;

		require_once $this->PLUGIN_DIR . 'inc/class-asp-sub-plan.php';

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_filter( 'asp_ng_pp_product_item_override', array( $this, 'pp_product_item_override' ) );

		register_activation_hook( __FILE__, array( 'ASPSUB_main', 'activate' ) );
		register_deactivation_hook( __FILE__, array( 'ASPSUB_main', 'deactivate' ) );
	}

	public function plugins_loaded() {
		if ( class_exists( 'AcceptStripePayments' ) ) {

			$this->asp_main = AcceptStripePayments::get_instance();
			$this->helper   = new ASPAddonsHelper( $this );
			//check minimum required core plugin version
			if ( ! $this->helper->check_ver() ) {
				return false;
			}
			$this->helper->init_tasks();

			include_once plugin_dir_path( __FILE__ ) . 'admin/asp-sub-plans-post-type.php';
			$aspsub_plans = ASPSUB_plans::get_instance();
			add_action( 'init', array( $aspsub_plans, 'register_post_type' ), 100 );
			include_once plugin_dir_path( __FILE__ ) . 'admin/asp-sub-subs-post-type.php';
			$aspsub_subs = ASPSUB_subs::get_instance();
			add_action( 'init', array( $aspsub_subs, 'register_post_type' ), 100 );

			$this->asp_main = AcceptStripePayments::get_instance();

			include_once $this->PLUGIN_DIR . 'inc/asp-sub-utils-class.php';

			include_once $this->PLUGIN_DIR . 'inc/trait-asp-sub-process-webhook.php';
			include_once $this->PLUGIN_DIR . 'inc/class-asp-sub-webhooks.php';

			if ( wp_doing_ajax() ) {
				add_action( 'wp_ajax_asp_pp_confirm_token', array( $this, 'confirm_token' ) );
				add_action( 'wp_ajax_nopriv_asp_pp_confirm_token', array( $this, 'confirm_token' ) );
			}

			require_once $this->PLUGIN_DIR . 'inc/class-asp-sub-cancel-handler.php';
			require_once $this->PLUGIN_DIR . 'inc/class-asp-sub-update-cc-handler.php';

			add_filter( 'asp_ng_process_ipn_payment_data_item_override', array( $this, 'payment_data_item_override' ), 10, 2 );
			add_filter( 'asp_ng_process_ipn_product_item_override', array( $this, 'product_item_override' ) );

			add_filter( 'asp_sc_show_user_transactions_additional_data', array( $this, 'show_user_transactions_handler' ), 10, 3 );

			add_filter( 'asp_ng_payment_completed', array( $this, 'payment_completed' ), 10, 2 );
			add_filter( 'asp_order_before_insert', array( $this, 'order_before_insert' ), 10, 3 );

			if ( ! is_admin() ) {
				add_filter( 'asp-button-output-data-ready', array( $this, 'data_ready' ), 10, 2 );
				add_filter( 'asp_product_tpl_tags_arr', array( $this, 'product_tpl_tags' ), 10, 2 );
				add_filter( 'asp_process_charge', array( $this, 'process_charge' ), 10, 1 );
				add_filter( 'asp_before_payment_processing', array( $this, 'before_payment_processing' ), 10, 2 );
				add_filter( 'asp_ng_button_output_data_ready', array( $this, 'data_ready' ), 10, 2 );
				add_filter( 'asp_ng_pp_data_ready', array( $this, 'ng_data_ready' ), 10, 2 );
			} else {
				require_once plugin_dir_path( __FILE__ ) . 'admin/class-asp-sub-admin.php';
			}
		}
	}

	public function get_uid() {
		if ( ! empty( $this->plugin_uid ) ) {
			return $this->plugin_uid;
		}
		$uid = get_option( 'asp_sub_plugin_uid' );
		if ( ! $uid ) {
			$uid              = uniqid();
			$this->plugin_uid = $uid;
			update_option( 'asp_sub_plugin_uid', $uid );
		}
		return $uid;
	}

	public static function deactivate() {
		if ( class_exists( 'AcceptStripePayments' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'inc/class-asp-sub-webhooks.php';
			ASP_SUB_Webhooks::get_instance()->process_deactivate_hook();
		}
	}

	public static function activate() {
		$opt      = get_option( 'AcceptStripePayments-settings' );
		$defaults = array(
			'sub_expiry_email_enabled' => 0,
			'sub_expiry_email_from'    => get_bloginfo( 'name' ) . ' <sales@your-domain.com>',
			'sub_expiry_email_subj'    => __( 'Your credit card is going to expire soon', 'asp-sub' ),
			'sub_expiry_email_body'    => __( "Hello.\n\nYour credit card {card_brand} ending in {card_last_4} is going to expire on {card_exp_month}/{card_exp_year}.\n\nPlease click the link below to update it:\n{update_cc_url}", 'asp-sub' ),
		);
		$new_opt  = array_merge( $defaults, $opt );
		// unregister setting to prevent main plugin from sanitizing our new values
		unregister_setting( 'AcceptStripePayments-settings-group', 'AcceptStripePayments-settings' );
		update_option( 'AcceptStripePayments-settings', $new_opt );
	}

	public function pp_product_item_override( $item ) {
		$prod_id = $item->get_product_id();

		$sub_id = get_post_meta( $prod_id, 'asp_sub_plan_id', true );
		if ( empty( $sub_id ) ) {
			return $item;
		}

		require_once $this->PLUGIN_DIR . 'inc/class-asp-sub-product-item.php';

		$sub_item = new ASP_Sub_Product_Item( $prod_id );

		if ( $sub_item->get_last_error() ) {
			return $item;
		}

		$curr_plan = ASP_Sub_Plan::get_instance();

		$plan = $curr_plan->get_plan_obj();

		$setup_fee = $curr_plan->get_setup_fee();
		if ( empty( $plan->trial_period_days ) && ! empty( $setup_fee ) ) {
			$sub_item->add_item( __( 'Setup Fee', 'asp-sub' ), $setup_fee );
		}

		$plan = $curr_plan->get_plan_obj();

		$trial_setup_fee = $curr_plan->get_trial_setup_fee();
		if ( ! empty( $trial_setup_fee ) && ! empty( $plan->trial_period_days ) ) {
			$sub_item->add_item( __( 'Trial Setup Fee', 'asp-sub' ), $trial_setup_fee );
			$sub_item->set_price( 0 );
		}

		return $sub_item;
	}

	public function product_item_override( $item ) {
		$sub_id = ASP_Process_IPN_NG::get_instance()->get_post_var( 'asp_sub_id', FILTER_SANITIZE_STRING );
		if ( empty( $sub_id ) ) {
			return $item;
		}

		$prod_id = $item->get_product_id();
		require_once $this->PLUGIN_DIR . 'inc/class-asp-sub-product-item.php';

		$sub_item = new ASP_Sub_Product_Item( $prod_id );

		if ( $sub_item->get_last_error() ) {
			return $item;
		}

		$plan_id = $sub_item->get_plan_id();

		if ( ! $plan_id ) {
			return $item;
		}

		$plan = $this->get_plan_data( $plan_id );

		if ( isset( $plan->is_variable ) && $plan->is_variable ) {
			if ( isset( $plan->variables['price'] ) ) {
				$posted_amount = ASP_Process_IPN_NG::get_instance()->get_post_var( 'asp_amount', FILTER_SANITIZE_NUMBER_INT );
				if ( $posted_amount ) {
					$plan->amount = AcceptStripePayments::from_cents( $posted_amount, $plan->currency );
					$sub_item->set_price( $plan->amount );
				}
			}
		}

		$curr_plan = ASP_Sub_Plan::get_instance();

		$setup_fee = $curr_plan->get_setup_fee();
		if ( empty( $plan->trial_period_days ) && ! empty( $setup_fee ) ) {
			$sub_item->add_item( __( 'Setup Fee', 'asp-sub' ), $setup_fee );
		}

		$trial_setup_fee = $curr_plan->get_trial_setup_fee();
		if ( ! empty( $trial_setup_fee ) && ! empty( $plan->trial_period_days ) ) {
			$sub_item->add_item( __( 'Trial Setup Fee', 'asp-sub' ), $trial_setup_fee );
			$sub_item->set_price( 0 );
		}

		$this->log( 'Overriding product item.' );

		return $sub_item;
	}

	public function payment_data_item_override( $p_data, $pi ) {
		$sub_id = ASP_Process_IPN_NG::get_instance()->get_post_var( 'asp_sub_id', FILTER_SANITIZE_STRING );
		if ( empty( $sub_id ) ) {
			return $p_data;
		}

		require_once $this->PLUGIN_DIR . 'inc/class-asp-sub-payment-data.php';
		$t_p_data = new ASP_Sub_Payment_Data( $sub_id );
		if ( $pi !== $sub_id ) {
			$t_p_data->set_pid( $pi );
		} else {
			// we need to handle metadata as there is no PaymentIntent created for this subscription
			$this->sub_id = $sub_id;
			add_filter( 'asp_ng_handle_metadata', array( $this, 'handle_metadata' ) );
		}

		$this->log( 'Overriding payment data.' );

		return $t_p_data;
	}

	public function handle_metadata( $metadata ) {
		$this->log( 'Handling metadata.' );
		\Stripe\Subscription::update( $this->sub_id, array( 'metadata' => $metadata ) );
		return true;
	}

	public function confirm_token() {
		include_once $this->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';

		$out            = array();
		$out['success'] = false;

		$prod_id = filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT );
		if ( empty( $prod_id ) ) {
			$out['err'] = __( 'Invalid product ID provided.', 'asp-sub' );
			wp_send_json( $out );
		}

		$token = filter_input( INPUT_POST, 'asp_token_id', FILTER_SANITIZE_STRING );

		$pm_id = filter_input( INPUT_POST, 'asp_pm_id', FILTER_SANITIZE_STRING );

		if ( empty( $token ) && empty( $pm_id ) ) {
			$out['err'] = __( 'No payment source provided.', 'asp-sub' );
			wp_send_json( $out );
		}

		$cust_id = filter_input( INPUT_POST, 'cust_id', FILTER_SANITIZE_STRING );

		$plan_id = get_post_meta( $prod_id, 'asp_sub_plan_id', true );

		if ( ! $plan_id ) {
			$out['err'] = __( 'No plan ID specified.', 'asp-sub' );
			wp_send_json( $out );
		}

		require_once $this->PLUGIN_DIR . 'inc/class-asp-sub-product-item.php';

		$sub_item = new ASP_Sub_Product_Item( $prod_id );

		$error = $sub_item->get_last_error();

		if ( $error ) {
			$out['err'] = $err;
			wp_send_json( $out );
		}

		if ( version_compare( WP_ASP_PLUGIN_VERSION, '2.0.32t3' ) >= 0 ) {
			do_action( 'asp_ng_before_token_request', $sub_item );
		}

		$this->log( 'Starting pre-subscription process.' );

		$plan = $this->get_plan_data( $plan_id );

		//handle variable currency
		if ( isset( $plan->is_variable ) && $plan->is_variable && ! empty( $plan->variables['currency'] ) ) {
			$plan->currency = filter_input( INPUT_POST, 'currency', FILTER_SANITIZE_STRING );
		}

		$currency_code = strtoupper( $plan->currency );

		//handle coupon if needed
		$coupon_code = filter_input( INPUT_POST, 'coupon', FILTER_SANITIZE_STRING );

		if ( ! empty( $coupon_code ) ) {
			$coupon_valid = $sub_item->check_coupon( $coupon_code );
			if ( $coupon_valid ) {
				$coupon = $sub_item->get_coupon();
				if ( $coupon['discount_type'] === 'perc' ) {
					$coupon_params = array(
						'name'        => $coupon['code'],
						'percent_off' => $coupon['discount'],
					);
				} else {
					$coupon_params = array(
						'name'       => $coupon['code'],
						'amount_off' => AcceptStripePayments::is_zero_cents( $currency_code ) ? $coupon['discount'] : $coupon['discount'] * 100,
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
			}
		}

		//check if this is variable plan
		if ( isset( $plan->is_variable ) && $plan->is_variable ) {
			//we need to create a plan first
			include_once self::get_plugin_dir() . 'inc/asp-sub-stripe-plans-class.php';

			$stripe_plans = new ASPSUB_stripe_plans( $plan->livemode );
			//let's create pricing plan in Stripe
			$plan_opts = array(
				'currency'          => $plan->currency,
				'interval'          => $plan->interval,
				'interval_count'    => $plan->interval_count,
				'amount'            => $plan->amount,
				'nickname'          => $plan->nickname,
				'trial_period_days' => $plan->trial_period_days,
			);

			$posted_amount = filter_input( INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT );
			if ( $posted_amount ) {
				$plan_opts['amount'] = AcceptStripePayments::from_cents( $posted_amount, $plan->currency );
			}

			$plan = $stripe_plans->create( $plan_opts, $plan->post_id );
		}

		$items = array( 'plan' => $plan->id );

		$item_quantity = filter_input( INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $item_quantity ) ) {
			$item_quantity = $sub_item->get_quantity();
		}

		if ( $item_quantity ) {
			$items['quantity'] = $item_quantity;
		}

		//preload subscriptions post-related class
		$sub = new ASPSUB_stripe_subs( $plan->livemode );

		$post_billing_details = filter_input( INPUT_POST, 'billing_details', FILTER_SANITIZE_STRING );

		$post_shipping_details = filter_input( INPUT_POST, 'shipping_details', FILTER_SANITIZE_STRING );

		try {

			ASPMain::load_stripe_lib();

			\Stripe\Stripe::setApiKey( $plan->livemode ? $this->asp_main->APISecKeyLive : $this->asp_main->APISecKeyTest );

			if ( ! empty( $coupon_params ) ) {
				$coupon_hash      = md5( wp_json_encode( $coupon_params ) );
				$coupon_meta_name = sprintf( 'asp_coupon_%s_%s_', $plan->livemode ? 'live' : 'test', $coupon_hash );
				$stripe_coupon_id = get_post_meta( $coupon['id'], $coupon_meta_name, true );
				if ( ! empty( $stripe_coupon_id ) ) {
					try {
						$coupon_res = \Stripe\Coupon::retrieve( $stripe_coupon_id );
					} catch ( \Stripe\Exception\InvalidRequestException $e ) {
						$this->log( sprintf( 'Stripe Coupon "%s" can\'t be found.', $stripe_coupon_id ) );
					}
				}
				if ( empty( $coupon_res ) ) {
					$this->log( sprintf( 'Creating coupon in Stripe. Coupon parametes: %s', wp_json_encode( $coupon_params ) ) );
					$coupon_res = \Stripe\Coupon::create( $coupon_params );
					update_post_meta( $coupon['id'], $coupon_meta_name, $coupon_res->id );
				}
			}

			$customer_opts = array();

			if ( $token ) {
				$customer_opts['source'] = $token;
			}

			if ( $pm_id ) {
				$customer_opts['payment_method']                             = $pm_id;
				$customer_opts['invoice_settings']['default_payment_method'] = $pm_id;
			}

			if ( isset( $post_billing_details ) ) {
				$post_billing_details = html_entity_decode( $post_billing_details );

				$billing_details = json_decode( $post_billing_details );

				if ( $billing_details->name ) {
					$customer_opts['name'] = $billing_details->name;
				}

				if ( $billing_details->email ) {
					$customer_opts['email'] = $billing_details->email;
				}

				if ( isset( $billing_details->address ) && isset( $billing_details->address->line1 ) ) {
					//we have address
					$addr = array(
						'line1'   => $billing_details->address->line1,
						'city'    => isset( $billing_details->address->city ) ? $billing_details->address->city : null,
						'country' => isset( $billing_details->address->country ) ? $billing_details->address->country : null,
					);

					if ( isset( $billing_details->address->postal_code ) ) {
						$addr['postal_code'] = $billing_details->address->postal_code;
					}

					$customer_opts['address'] = $addr;
				}
			}

			if ( isset( $post_shipping_details ) ) {
				$post_shipping_details = html_entity_decode( $post_shipping_details );

				$shipping_details = json_decode( $post_shipping_details );

				$shipping = array();

				if ( $shipping_details->name ) {
					$shipping['name'] = $shipping_details->name;
				}

				if ( isset( $shipping_details->address ) && isset( $shipping_details->address->line1 ) ) {
					//we have address
					$addr = array(
						'line1'   => $shipping_details->address->line1,
						'city'    => isset( $shipping_details->address->city ) ? $shipping_details->address->city : null,
						'country' => isset( $shipping_details->address->country ) ? $shipping_details->address->country : null,
					);

					if ( isset( $shipping_details->address->postal_code ) ) {
						$addr['postal_code'] = $shipping_details->address->postal_code;
					}

					$shipping['address'] = $addr;

					if ( ! empty( $shipping['name'] ) ) {
						$customer_opts['shipping'] = $shipping;
					}
				}
			}

			$customer_opts = apply_filters( 'asp_ng_sub_confirm_token_customer_opts', $customer_opts );

			if ( empty( $cust_id ) ) {
				$customer = \Stripe\Customer::create( $customer_opts );
			} else {
				$customer = \Stripe\Customer::update( $cust_id, $customer_opts );
			}

			$cust_id = $customer->id;

			$sub_opts = array(
				'customer'                   => $cust_id,
				'enable_incomplete_payments' => true,
				'items'                      => array( $items ),
				'trial_from_plan'            => true,
				'expand'                     => array( 'latest_invoice', 'latest_invoice.payment_intent', 'pending_setup_intent' ),
			);

			$tax = get_post_meta( $prod_id, 'asp_product_tax', true );

			$curr_plan = ASP_Sub_Plan::get_instance();

			$setup_fee = $curr_plan->get_setup_fee();

			if ( ! empty( $setup_fee ) ) {
				$inv_items[] = array(
					'customer'     => $cust_id,
					'amount'       => AcceptStripePayments::is_zero_cents( $currency_code ) ? $setup_fee : $setup_fee * 100,
					'currency'     => $plan->currency,
					'description'  => __( 'Setup fee', 'asp-sub' ),
					'discountable' => true,
					'metadata'     => array( 'asp_type' => 'setup_fee' ),
				);
			}

			$trial_setup_fee = $curr_plan->get_trial_setup_fee();

			if ( ! empty( $trial_setup_fee ) ) {
				$trial_inv_items[] = array(
					'customer'     => $cust_id,
					'amount'       => AcceptStripePayments::is_zero_cents( $currency_code ) ? $trial_setup_fee : $trial_setup_fee * 100,
					'currency'     => $plan->currency,
					'description'  => __( 'Trial setup fee', 'asp-sub' ),
					'discountable' => false,
					'metadata'     => array( 'asp_type' => 'trial_setup_fee' ),
				);
			}

			$shipping = $sub_item->get_shipping( true );

			if ( empty( $shipping ) && ! empty( $trial_setup_fee ) ) {
				$shipping = $sub_item->get_product_shipping( true );
			}

			if ( ! empty( $shipping ) ) {

				if ( ! empty( $tax ) ) {

					$tax_rate = \Stripe\TaxRate::create(
						array(
							'display_name' => __( 'Shipping tax', 'stripe-payments' ),
							'description'  => __( 'Shipping tax', 'stripe-payments' ),
							'percentage'   => 0,
							'inclusive'    => false,
						)
					);

				}

				$inv_items[] = array(
					'customer'     => $cust_id,
					'amount'       => $shipping,
					'currency'     => $plan->currency,
					'description'  => apply_filters( 'asp_customize_text_msg', __( 'Shipping', 'stripe-payments' ), 'shipping_str' ),
					'tax_rates'    => isset( $tax_rate ) ? array( $tax_rate->id ) : null,
					'discountable' => false,
					'metadata'     => array( 'asp_type' => 'shipping' ),
				);

			}

			//add additional invoice items
			if ( ! empty( $inv_items ) && empty( $plan->trial_period_days ) ) {
				//this plan has no trial; let's add invoice items to current invoice
				foreach ( $inv_items as $inv_item ) {
					\Stripe\InvoiceItem::create( $inv_item );
				}
			}

			//add additional invoice items for trial plan
			if ( ! empty( $trial_inv_items ) && ! empty( $plan->trial_period_days ) ) {
				foreach ( $trial_inv_items as $trial_inv_item ) {
					\Stripe\InvoiceItem::create( $trial_inv_item );
				}
			}

			if ( ! empty( $tax ) ) {
				$sub_opts['tax_percent'] = floatval( $tax );
			}

			if ( ! empty( $coupon_res ) ) {
				$sub_opts['coupon'] = $coupon_res->id;
			}

			$subscription = \Stripe\Subscription::create( $sub_opts );

		} catch ( Exception $e ) {
			$this->log( 'Stripe API error occurred: ' . $e->getMessage(), false );
			$out['err'] = $e->getMessage();
			wp_send_json( $out );
		}

		$sub_post_id = $sub->insert( $subscription, $customer, $plan_id, $prod_id, array() );

		//add additional invoice items to next invoice
		if ( ! empty( $inv_items ) && ! empty( $plan->trial_period_days ) ) {
			//this plan has trial; let's add shipping invoice item to next invoice
			foreach ( $inv_items as $inv_item ) {
				\Stripe\InvoiceItem::create( $inv_item );
			}
		}

		try {
			if ( isset( $subscription->latest_invoice->payment_intent->id ) ) {
				\Stripe\PaymentIntent::update( $subscription->latest_invoice->payment_intent->id, array( 'description' => $sub_item->get_name() ) );
			}
		} catch ( Exception $e ) {
			//this is not fatal error, let's just log it
			$this->log( 'Error occurred during description update: ' . $e->getMessage(), false );
		}

		if ( 'incomplete' === $subscription->status ) {
			$out['pi_cs'] = $subscription->latest_invoice->payment_intent->client_secret;
		}

		if ( 'trialing' === $subscription->status ) {
			if ( $subscription->pending_setup_intent ) {
				$out['pi_cs']         = $subscription->pending_setup_intent->client_secret;
				$out['pi_id']         = $subscription->id;
				$out['do_card_setup'] = true;
			} else {
				$out['no_action_required'] = true;
				$out['pi_id']              = $subscription->id;
			}
		} else {
			$out['pi_id'] = $subscription->latest_invoice->payment_intent->id;
		}

		$out['sub_id'] = $subscription->id;

		$out['cust_id'] = $cust_id;

		// new orders support
		if ( class_exists( 'ASP_Order_Item' ) ) {
			$order = new ASP_Order_Item();
			if ( $order->can_create() ) {
				if ( false === $order->find( 'pi_id', $out['pi_id'] ) ) {
					//create new incomplete order for this payment
					$order->create( $prod_id, $out['pi_id'] );
				}
				update_post_meta( $order->get_id(), 'asp_sub_id', $out['sub_id'] );
			}
		}

		$this->log( sprintf( 'Pre-subscription completed for plan "%s" (%s)', $plan_id, $plan->nickname ) );

		$out['success'] = true;
		wp_send_json( $out );
	}

	public function payment_completed( $data, $prod_id ) {
		$plan_id = get_post_meta( $prod_id, 'asp_sub_plan_id', true );

		if ( ! $plan_id ) {
			// no plan id found, this is not subscriptions product
			// $this->log( 'No plan ID specified.', false );
			return $data;
		}

		$sub_id = ASP_Process_IPN_NG::get_instance()->get_post_var( 'asp_sub_id', FILTER_SANITIZE_STRING );

		if ( ! $sub_id ) {
			$this->log( 'No sub ID provided.', false );
			return $data;
		}

		$plan = $this->get_plan_data( $plan_id );

		include_once $this->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';

		$sub = new ASPSUB_stripe_subs( $plan->livemode );

		ASPMain::load_stripe_lib();

		\Stripe\Stripe::setApiKey( $plan->livemode ? $this->asp_main->APISecKeyLive : $this->asp_main->APISecKeyTest );

		$subscription = \Stripe\Subscription::retrieve( $sub_id );
		$customer     = \Stripe\Customer::retrieve( $subscription->customer );

		$currency_code = strtoupper( $plan->currency );

		$item_quantity = false;

		if ( isset( $data['item_quantity'] ) && ! empty( $data['item_quantity'] ) ) {
			$item_quantity = intval( $data['item_quantity'] );
		}

		//      $sub_post_id = $sub->insert( $subscription, $customer, $plan_id, $prod_id, array() );

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

		if ( isset( $data['addonName'] ) ) {
			$data['addonName'] .= '[SUB]';
		} else {
			$data['addonName'] = '[SUB]';
		}

		$data['is_live'] = $plan->livemode ? true : false;

		$curr_plan = ASP_Sub_Plan::get_instance();

		$setup_fee = $curr_plan->get_setup_fee();
		if ( empty( $plan->trial_period_days ) && ! empty( $setup_fee ) ) {
			$data['additional_items'][ __( 'Setup Fee', 'asp-sub' ) ] = $setup_fee;
		}

		$trial_setup_fee = $curr_plan->get_trial_setup_fee();
		if ( ! empty( $trial_setup_fee ) ) {
			$data['additional_items'][ __( 'Trial Setup Fee', 'asp-sub' ) ] = $trial_setup_fee;
		}

		return $data;
	}

	public function product_tpl_tags( $tags, $id ) {
		$plan_id = get_post_meta( $id, 'asp_sub_plan_id', true );
		if ( ! $plan_id ) {
			return $tags;
		}
		$plan = $this->get_plan_data( $plan_id );
		if ( ! $plan ) {
			return $tags;
		}
		$price = $plan->amount;

		if ( ! in_array( strtoupper( $plan->currency ), $this->asp_main->zeroCents ) ) {
			$price = $price / 100;
		}

		$tags['price'] = ASPSUB_Utils::get_plan_descr( $plan_id );

		$under_price_line = '';

		//setup fee
		$curr_plan = ASP_Sub_Plan::get_instance();
		$setup_fee = $curr_plan->get_setup_fee();

		if ( ! empty( $setup_fee ) ) {
			$setup_fee_line = AcceptStripePayments::formatted_price( $setup_fee, $plan->currency ) . ' (' . __( 'setup fee', 'asp-sub' ) . ')';
			if ( ! empty( $under_price_line ) ) {
				$under_price_line .= '<br>';
			}
			$under_price_line .= '<span class="asp_price_setup_fee_section">' . $setup_fee_line . '</span>';
		}

		//tag
		$tax = get_post_meta( $id, 'asp_product_tax', true );

		if ( $tax ) {
			$tax_amount = round( ( $price * $tax / 100 ), 2 );
			if ( ! empty( $under_price_line ) ) {
				$under_price_line .= '<br>';
			}
			$under_price_line .= '<span class="asp_price_tax_section">' . AcceptStripePayments::formatted_price( $tax_amount, $plan->currency ) . __( ' (tax per payment)', 'asp-sub' ) . '</span>';
		}

		//shipping
		$shipping = get_post_meta( $id, 'asp_product_shipping', true );

		if ( $shipping ) {
			$ship_str      = apply_filters( 'asp_customize_text_msg', __( 'Shipping', 'stripe-payments' ), 'shipping_str' );
			$shipping_line = AcceptStripePayments::formatted_price( $shipping, $plan->currency ) . ' (' . strtolower( $ship_str ) . ')';
			if ( ! empty( $under_price_line ) ) {
				$under_price_line .= '<br>';
			}
			$under_price_line .= '<span class="asp_price_shipping_section">' . $shipping_line . '</span>';
		}

		if ( ! empty( $under_price_line ) ) {
			//$under_price_line .= '<div class="asp_price_full_total">' . __( 'Total:', 'stripe-payments' ) . ' ' . AcceptStripePayments::formatted_price( $price + $tax_amount, $plan->currency ) . '</div>';
		}

		$tags['under_price_line'] = $under_price_line;

		return $tags;
	}

	public function get_plan_data( $plan_id ) {
		$plan     = new ASP_Sub_Plan( $plan_id );
		$last_err = $plan->get_last_error();
		if ( ! empty( $last_err ) ) {
			$this->log( $last_err, false );
		}
		$plan_obj = $plan->get_plan_obj();
		return $plan_obj;
	}

	public function ng_data_ready( $data, $atts ) {
		$prod_id = $atts['product_id'];
		$plan_id = get_post_meta( $prod_id, 'asp_sub_plan_id', true );

		if ( ! $plan_id ) {
			return $data;
		}

		$plan = $this->get_plan_data( $plan_id );

		if ( ! ( $plan ) ) {
			$data['fatal_error'] = __( 'Plan not found on Stripe account', 'asp-subs' );
			return $data;
		}

		//check if coupons enabled for the product
		if ( ! empty( $data['coupons_enabled'] ) ) {
			//check if core plugin version >=2.0.20
			if ( version_compare( WP_ASP_PLUGIN_VERSION, '2.0.20' ) < 0 ) {
				//core version does not support subs products coupons
				$data['coupons_enabled'] = false;
			}
		}

		//check if plan has trial setup
		$plan_inner = ASP_Sub_Plan::get_instance();
		if ( $plan->trial_period_days ) {
			$data['is_trial']   = true;
			$data['item_price'] = 0;
			$data['amount']     = 0;
			if ( ! empty( $plan_inner->get_trial_setup_fee() ) ) {
				//add filter to change payment button text
				add_filter( 'asp_ng_pp_pay_button_text', array( $this, 'ng_pp_pay_button_text' ) );
			}
		}

		//check if plan is live mode
		if ( $plan->livemode && ! $this->asp_main->is_live ) {
			$data['stripe_key'] = isset( $this->asp_main->APIPubKeyLive ) ? $this->asp_main->APIPubKeyLive : '';
		}

		return $data;
	}

	public function ng_pp_pay_button_text( $btn_text ) {
		$btn_text = __( 'Start trial for %s', 'asp-sub' ); //phpcs:ignore
		return $btn_text;
	}

	public function order_before_insert( $post, $order_details, $charge_details ) {
		if ( isset( $order_details['addonName'] ) && strpos( $order_details['addonName'], 'SUB' ) !== false ) {
			if ( isset( $order_details['is_trial'] ) && $order_details['is_trial'] ) {
				$post['post_title'] .= ' ' . esc_html__( '(trial)', 'asp-sub' );
			}
			$post['post_title'] = '[SUB]' . $post['post_title'];
		}
		return $post;
	}

	public function show_user_transactions_handler( $val, $order_data, $atts ) {
		if ( ! isset( $order_data['charge']->object ) || 'subscription' !== $order_data['charge']->object ) {
			//not a subscription
			return $val;
		}
		include_once $this->PLUGIN_DIR . 'inc/asp-subscription-class.php';

		$sub = new ASPSUB_subscription( $order_data['charge']->id );

		if ( ! $sub ) {
			return $val;
		}

		if ( $sub->is_active() ) {
			if ( ! empty( $atts['show_subscription_cancel'] ) ) {
				$cancel_url = $sub->get_cancel_link();

				$val[] = array(
					'Subscription status:' => sprintf(
						'%s. <a href="%s" target="_blank">%s</a>',
						ucfirst( $sub->status ),
						$cancel_url,
						__( 'Cancel subscription', 'asp-sub' )
					),
				);
			} else {
				$val[] = array( 'Subscription status:' => ucfirst( $sub->status ) );
			}
		} else {
			$val[] = array( 'Subscription status:' => ucfirst( $sub->status ) );
		}

		return $val;
	}

	public function log( $msg, $success = true ) {
		if ( method_exists( 'ASP_Debug_Logger', 'log' ) ) {
			//          $dbt    = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
			//          $caller = isset( $dbt[1]['function'] ) ? $dbt[1]['function'] : '';
			//          $class  = isset( $dbt[1]['class'] ) ? $dbt[1]['class'] : '';
			//          $msg    = sprintf( $msg . '%s::$s()', $class, $caller );
			ASP_Debug_Logger::log( $msg, $success, $this->ADDON_SHORT_NAME );
		}
	}

	public static function get_plugin_dir() {
		return plugin_dir_path( __FILE__ );
	}

	public function data_ready( $data, $atts ) {
		if ( ! isset( $data['product_id'] ) ) {
			return $data;
		}

		$id = $data['product_id'];

		$plan_id = get_post_meta( $id, 'asp_sub_plan_id', true );

		if ( ! $plan_id ) {
			return $data;
		}

		$plan = $this->get_plan_data( $plan_id );

		if ( ! ( $plan ) ) {
			return $data;
		}

		$amount = $plan->amount;

		if ( ! empty( $plan->trial_period_days ) ) {
			$data['is_trial'] = true;
		}

		$quantity = 1;

		if ( isset( $data['quantity'] ) ) {
			$quantity = intval( $data['quantity'] );
		}

		if ( $quantity ) {
			$amount = $amount * $quantity;
		}

		if ( ! empty( $data['tax'] ) ) {
			$amount = round( AcceptStripePayments::apply_tax( $amount, $data['tax'] ) );
		}

		//check if plan has variable currency
		if ( isset( $plan->is_variable ) && $plan->is_variable && ! empty( $plan->variables['currency'] ) ) {
			//it is
			$data['currency_variable'] = true;
		}

		$data['variable']        = false;
		$data['amount_variable'] = false;

		//check if plan has variable amount
		if ( isset( $plan->is_variable ) && $plan->is_variable && ! empty( $plan->variables['price'] ) ) {
			//it is
			$data['variable']        = true;
			$data['amount_variable'] = true;
		}

		if ( empty( $data['description'] ) ) {
			$data['description'] = ! empty( $plan->statement_descriptor ) ? $plan->statement_descriptor : '';
		}

		$data['amount']       = $amount;
		$data['item_price']   = $plan->amount;
		$data['price']        = $plan->amount;
		$data['currency']     = $plan->currency;
		$data['is_live']      = $plan->livemode ? true : false;
		$data['create_token'] = true;
		// we don't need one-off token for subscription product
		$data['token_not_required'] = true;
		// shipping not supported
		//      $data['shipping'] = 0;

		return $data;
	}

}

new ASPSUB_main();
