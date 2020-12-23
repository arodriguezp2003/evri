<?php

class ASP_SUB_Update_CC_Handler {

	protected $sub_main;
	protected $asp_main;

	public function __construct() {
		$this->sub_main = ASPSUB_main::get_instance();
		$this->asp_main = AcceptStripePayments::get_instance();

		$asp_action = filter_input( INPUT_GET, 'asp_sub_action', FILTER_SANITIZE_STRING );
		if ( 'update' === $asp_action ) {
			$this->update_cc_url_handler();
		}

		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_asp_sub_create_si', array( $this, 'create_si' ) );
			add_action( 'wp_ajax_nopriv_asp_sub_create_si', array( $this, 'create_si' ) );
		}
	}

	public function update_cc_url_handler() {
		require_once $this->sub_main->PLUGIN_DIR . 'view/sub-update-cc-tpl.php';
		$tpl                = new ASP_Sub_Update_CC_Tpl();
		$vals['plugin_url'] = plugin_dir_url( $this->sub_main->file ) . 'view/';
		$vals['addon_ver']  = ASPSUB_main::ADDON_VER;
		$token              = filter_input( INPUT_GET, 'sub_token', FILTER_SANITIZE_STRING );
		if ( empty( $token ) ) {
			//no token provided
			$vals['content'] = __( 'No token provided.', 'stripe-paymets' );
			$tpl->set_vals( $vals );
			$tpl->display_tpl( true );
			exit;
		}
		include_once $this->sub_main->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';

		$subs_class = ASPSUB_stripe_subs::get_instance();
		$sub        = $subs_class->find_sub_by_token( $token );
		if ( ! $sub ) {
			//no subscription found
			$vals['content'] = __( 'No subscription found.', 'asp-sub' );
			$tpl->set_vals( $vals );
			$tpl->display_tpl( true );
			exit;
		}
		$status = get_post_meta( $sub->ID, 'sub_status', true );
		if ( ! ASPSUB_Utils::is_sub_active( $status ) ) {
			//sub not active
			$vals['content'] = __( 'Subscription is not active.', 'asp-sub' );
			$tpl->set_vals( $vals );
			$tpl->display_tpl( true );
			exit;
		}

		$use_old_api = $this->asp_main->get_setting( 'use_old_checkout_api1' );

		$is_live = get_post_meta( $sub->ID, 'is_live', true );

		$subs_class = new ASPSUB_stripe_subs( $is_live );

		$customer_id = get_post_meta( $sub->ID, 'cust_id', true );

		if ( empty( $customer_id ) ) {
			//no customer id found
			$vals['content'] = __( 'No customer ID found for this subscription.', 'asp-sub' );
			$tpl->set_vals( $vals );
			$tpl->display_tpl( true );
			exit;
		}

		$key = $is_live ? $this->asp_main->APIPubKey : $this->asp_main->APIPubKeyTest;

		try {
			$cu = \Stripe\Customer::Retrieve(
				array(
					'id'     => $customer_id,
					'expand' => array( 'default_source', 'invoice_settings' ),
				)
			);
		} catch ( Exception $e ) {
			$vals['content'] = __( 'Error occurred:', 'asp-sub' ) . ' ' . $e->getMessage();
			$tpl->set_vals( $vals );
			$tpl->display_tpl( true );
			exit;
		}

		if ( $use_old_api ) {

			$stripe_token = filter_input( INPUT_POST, 'stripeToken', FILTER_SANITIZE_STRING );

			if ( ! empty( $stripe_token ) ) {
				try {
					$cu = \Stripe\Customer::update(
						$customer_id,
						array( 'source' => $stripe_token )
					);
				} catch ( Exception $e ) {

					// Use the variable $error to save any errors
					// To be displayed to the customer later in the page
					$vals['content'] = __( 'Error occurred:', 'asp-sub' ) . ' ' . $e->getMessage();
					$tpl->set_vals( $vals );
					$tpl->display_tpl( true );
					exit;
				}
				//update was successful. Let's remove customer_cc_expiring flag
				update_post_meta( $sub->ID, 'customer_cc_expiring', false );
				$vals['content'] = __( 'Your card details have been updated!', 'asp-sub' );
				$tpl->set_vals( $vals );
				$tpl->display_tpl();
				exit;
			}
		} else {
			$pm_id = filter_input( INPUT_POST, 'pm_id', FILTER_SANITIZE_STRING );
			if ( ! empty( $pm_id ) ) {
				try {
					$payment_method = \Stripe\PaymentMethod::retrieve( $pm_id );
					$payment_method->attach( array( 'customer' => $customer_id ) );
					$cu = \Stripe\Customer::update(
						$customer_id,
						array(
							'invoice_settings' => array(
								'default_payment_method' => $pm_id,
							),
						)
					);
				} catch ( Exception $e ) {
					// Use the variable $error to save any errors
					// To be displayed to the customer later in the page
					$vals['content'] = __( 'Error occurred:', 'asp-sub' ) . ' ' . $e->getMessage();
					$tpl->set_vals( $vals );
					$tpl->display_tpl( true );
					exit;
				}
				//update was successful. Let's remove customer_cc_expiring flag
				update_post_meta( $sub->ID, 'customer_cc_expiring', false );
				$vals['content'] = __( 'Your card details have been updated!', 'asp-sub' );
				$tpl->set_vals( $vals );
				$tpl->display_tpl();
				exit;
			}
		}

		if ( $use_old_api ) {
			$def_brand = $cu->default_source->brand;
			$def_last4 = $cu->default_source->last4;
		} else {
			$pm_id     = isset( $cu->invoice_settings->default_payment_method ) ? $cu->invoice_settings->default_payment_method : $cu->default_source->id;
			$pm        = \Stripe\PaymentMethod::retrieve( $pm_id );
			$def_brand = $pm->card->brand;
			$def_last4 = $pm->card->last4;
		}

		$content = '<p>' . sprintf( __( 'Current card on file: %1$s ending in %2$d', 'asp-sub' ), $def_brand, $def_last4 ) . '</p>';

		$email = $cu->email;
		ob_start();

		if ( $use_old_api ) {
			?>
		<form action="" method="POST">
			<script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="<?php echo esc_attr( $key ); ?>" data-name="<?php echo esc_attr( __( 'Update Card Details', 'asp-sub' ) ); ?>" data-panel-label="<?php _e( 'Update Card Details', 'asp-sub' ); ?>" data-label="<?php _e( 'Update Card Details', 'asp-sub' ); ?>" data-allow-remember-me="false" data-locale="auto" data-email="<?php echo $email; ?>">
			</script>
		</form>
			<?php
		} else {
			$vars = array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'custId'  => $customer_id,
				'key'     => $key,
				'isLive'  => $is_live,
			);
			?>
			<form id="update-form" class="pure-form pure-form-stacked" action="" method="POST">
			<div id="error-cont"></div>
			<fieldset>
				<input type="hidden" name="new_api" value="1">
				<input type="hidden" id="pm_id" name="pm_id" value="">
				<label for="card-element"><?php esc_html_e( 'Credit or debit card', 'stripe-payments' ); ?></label>
				<div id="card-element"></div>
				<div id="card-errors" class="form-err" role="alert"></div>
			</fieldset>
				<button type="submit" id="submitBtn" class="pure-button pure-button-primary" disabled><?php esc_attr_e( 'Update Card Details', 'asp-sub' ); ?></button>
			</form>
			<script>var vars=<?php echo wp_json_encode( $vars ); ?>;</script>
			<script src="https://js.stripe.com/v3/"></script>
			<script src="<?php echo esc_url( plugin_dir_url( $this->sub_main->file ) ); ?>view/js/asp-sub-utils.js?ver=<?php echo esc_js( ASPSUB_main::ADDON_VER ); ?>"></script>
			<script src="<?php echo esc_url( plugin_dir_url( $this->sub_main->file ) ); ?>view/js/asp-sub-update-cc-handler.js?ver=<?php echo esc_js( ASPSUB_main::ADDON_VER ); ?>"></script>
			<?php
		}
		$content        .= ob_get_clean();
		$vals['content'] = $content;
		$tpl->set_vals( $vals );
		$tpl->display_tpl();
		exit;
	}

	public function create_si() {
		$out        = array();
		$out['err'] = '';
		$cust_id    = filter_input( INPUT_POST, 'cust_id', FILTER_SANITIZE_STRING );
		$token      = filter_input( INPUT_POST, 'token_id', FILTER_SANITIZE_STRING );
		$is_live    = filter_input( INPUT_POST, 'is_live', FILTER_SANITIZE_STRING );
		if ( empty( $cust_id ) ) {
			$out['err'] = 'Invalid customer ID provided';
			wp_send_json( $out );
			exit;
		}
		if ( empty( $token ) ) {
			$out['err'] = 'Invalid card token provided';
			wp_send_json( $out );
			exit;
		}

		ASPMain::load_stripe_lib();

		\Stripe\Stripe::setApiKey( $is_live ? $this->asp_main->APISecKeyLive : $this->asp_main->APISecKeyTest );

		try {
			$si = \Stripe\SetupIntent::create(
				array(
					'customer'            => $cust_id,
					'payment_method_data' => array(
						'type' => 'card',
						'card' => array( 'token' => $token ),
					),
				)
			);
		} catch ( Exception $e ) {
			$out['err'] = $e->getMessage();
			wp_send_json( $out );
		}

		$out['si_id'] = $si->id;
		$out['si_cs'] = $si->client_secret;
		//      $out['si'] = wp_json_encode($si);

		wp_send_json( $out );
	}

}

new ASP_SUB_Update_CC_Handler();
