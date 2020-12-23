<?php

class ASPSUB_admin_menu {

	var $plugin_slug;
	var $ASPAdmin;
	var $ASPSubMain;

	function __construct() {
		$this->ASPAdmin    = AcceptStripePayments_Admin::get_instance();
		$this->ASPSubMain  = ASPSUB_main::get_instance();
		$this->plugin_slug = $this->ASPAdmin->plugin_slug;
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'asp-settings-page-after-styles', array( $this, 'after_styles' ) );
		add_action( 'asp-settings-page-after-tabs-menu', array( $this, 'after_tabs_menu' ) );
		add_action( 'asp-settings-page-after-tabs', array( $this, 'after_tabs' ) );
		add_filter( 'asp-admin-settings-addon-field-display', array( $this, 'field_display' ), 10, 2 );
		add_filter( 'apm-admin-settings-sanitize-field', array( $this, 'sanitize_settings' ), 10, 2 );
		add_filter( 'asp_get_email_tags_descr', array( $this, 'email_tags_descr' ) );

		add_action( 'asp_admin_add_edit_coupon', array( $this, 'add_edit_coupon' ) );
		add_action( 'asp_admin_save_coupon', array( $this, 'save_coupon' ), 10, 2 );

		add_action( 'asp_int_dont_use_stripe_php_sdk_option_desc', array( $this, 'dont_use_stripe_php_sdk_option_desc' ) );

		if ( version_compare( WP_ASP_PLUGIN_VERSION, '2.0.31t7' ) < 0 ) {
			add_action( 'asp_product_price_metabox_before_content', array( $this, 'output_product_settings_before' ), 5, 1 );
			add_action( 'asp_product_price_metabox_after_content', array( $this, 'output_product_settings_after' ), 5, 1 );
		} else {
			add_action( 'asp_product_edit_product_types', array( $this, 'add_product_type' ), 10, 2 );
			add_action( 'asp_product_edit_product_type_selected', array( $this, 'product_type_selected' ), 10, 2 );
			add_action( 'asp_product_edit_output_product_type_subscription', array( $this, 'product_type_output' ), 10, 1 );
		}

		add_action( 'asp_save_product_handler', array( $this, 'save_product' ), 10, 3 );
	}

	public function dont_use_stripe_php_sdk_option_desc( $desc ) {
			$desc .= '<br>' . __( '<b>Warning</b>: this option is currently not supported by Subscriptions add-on. When enabled, Stripe PHP SDK would still be used by the add-on.', 'asp-sub' );
			return $desc;
	}

	function save_coupon( $coupon_id, $coupon ) {
		$sub_applied_for = isset( $coupon['sub_applied_for'] ) ? $coupon['sub_applied_for'] : false;
		update_post_meta( $coupon_id, 'asp_sub_applied_for', $sub_applied_for );
		$sub_applied_for_months = isset( $coupon['sub_applied_for_months'] ) ? intval( $coupon['sub_applied_for_months'] ) : 1;
		update_post_meta( $coupon_id, 'asp_sub_applied_for_months', $sub_applied_for_months );
	}

	function add_edit_coupon( $coupon_id ) {
		$sub_applied_for        = get_post_meta( $coupon_id, 'asp_sub_applied_for', true );
		$sub_applied_for_months = get_post_meta( $coupon_id, 'asp_sub_applied_for_months', true );
		$sub_applied_for_months = empty( $sub_applied_for_months ) ? 1 : $sub_applied_for_months;
		?>
	<tr>
		<th scope="row"><?php _e( 'Subscriptions Settings', 'asp-sub' ); ?></th>
		<td>
		<p>Coupon discount applied for:</p>
		<label><input type="radio" name="asp_coupon[sub_applied_for]" value="forever"<?php echo ! $coupon_id || ! $sub_applied_for || $sub_applied_for === 'forever' ? ' checked' : ''; ?>> <?php _e( 'All plan payments', 'asp-sub' ); ?></label>
		<br />
		<label><input type="radio" name="asp_coupon[sub_applied_for]" value="once"<?php echo $sub_applied_for === 'once' ? ' checked' : ''; ?>> <?php _e( 'First plan payment only', 'asp-sub' ); ?></label>
		<br />
		<label><input type="radio" name="asp_coupon[sub_applied_for]" value="repeating"<?php echo $sub_applied_for === 'repeating' ? ' checked' : ''; ?>> <?php echo sprintf( __( 'All plan payments for %s month(s)', 'asp-sub' ), '<input type="number" name="asp_coupon[sub_applied_for_months]" style="width: 4em;" value="' . $sub_applied_for_months . '">' ); ?></label>
		<p class="description"><?php _e( 'Specify how coupon discount should be applied to subscription payments.', 'asp-sub' ); ?></p>
		</td>
	</tr>
		<?php
	}

	function save_product( $post_id, $post, $update ) {
		if ( isset( $_POST['asp_sub_plan_id'] ) ) {
			update_post_meta( $post_id, 'asp_sub_plan_id', sanitize_text_field( $_POST['asp_sub_plan_id'] ) );
		}
	}

	function sanitize_settings( $output, $input ) {
		$output['live_webhook_secret'] = sanitize_text_field( $input['live_webhook_secret'] );
		$output['test_webhook_secret'] = sanitize_text_field( $input['test_webhook_secret'] );

		$output['sub_expiry_email_enabled'] = isset( $input['sub_expiry_email_enabled'] ) ? true : false;
		$output['sub_expiry_email_from']    = $input['sub_expiry_email_from'];
		$output['sub_expiry_email_subj']    = sanitize_text_field( $input['sub_expiry_email_subj'] );
		$output['sub_expiry_email_body']    = sanitize_textarea_field( $input['sub_expiry_email_body'] );

		return $output;
	}

	function field_display( $field, $field_value ) {
		$ret = array();
		switch ( $field ) {
			case 'sub_expiry_email_enabled':
				$ret['field']      = 'custom';
				$ret['field_name'] = $field;
				$ret['field_data'] = "<input type='checkbox' name='AcceptStripePayments-settings[{$field}]' value='1' " . ( $field_value ? 'checked=checked' : '' ) . ' />';
				break;
			case 'sub_expiry_email_body':
				$ret['field']      = 'custom';
				$ret['field_name'] = $field;
				$ret['field_data'] = '<textarea name="AcceptStripePayments-settings[' . $field . ']" rows="10" cols="70">' . $field_value . '</textarea>';
				break;
			case 'live_webhook_status':
			case 'test_webhook_status':
				$ret['field']       = 'custom';
				$ret['field_name']  = $field;
				$mode               = $field == 'test_webhook_status' ? 'test' : 'live';
				$ret['field_data']  = '<span class="asp-sub-' . $mode . '-webhook-status"><span class="dashicons dashicons-update"></span> ' . __( 'Checking...', 'asp-sub' ) . '</span>';
				$ret['field_data'] .= '<p><button style="display: none" class="button asp-sub-create-webhook-btn" data-hook-mode="' . $mode . '">Create Webhook</button></p>';
				break;
			case 'live_webhook_url':
				$ret['field']      = 'custom';
				$ret['field_name'] = $field;
				$ret['field_data'] = '<input id="asp_live_webhook_url" class="asp-select-on-click" type="text" size="75" value="' . ASP_SUB_Webhooks::get_webhook_url( 'live' ) . '" readonly>';
				break;
			case 'test_webhook_url':
				$ret['field']      = 'custom';
				$ret['field_name'] = $field;
				$ret['field_data'] = '<input id="asp_test_webhook_url" class="asp-select-on-click" type="text" size="75" value="' . ASP_SUB_Webhooks::get_webhook_url( 'test' ) . '" readonly>';
				break;
			case 'clear_cache':
				$ret['field']      = 'custom';
				$ret['field_name'] = $field;
				$ret['field_data'] = '<button type="button" class="button" id="asp_sub_clear_cache_btn">Clear Cache</button>';
				break;
			case 'delete_webhooks':
				$ret['field']      = 'custom';
				$ret['field_name'] = $field;
				if ( ! ASP_SUB_Webhooks::get_instance()->old_core_plugin_ver ) {
					$ret['field_data'] = '<button type="button" class="button" id="asp-sub-delete-webhooks-btn">Delete Webhooks</button>';
				} else {
					$ret['field_data'] = 'Stripe Core plugin version 1.9.15+ required for this functionality';
				}
				break;
		}
		if ( ! empty( $ret ) ) {
			return $ret;
		} else {
			return $field;
		}
	}

	function register_settings() {
		add_settings_section( 'AcceptStripePayments-sub-section', __( 'Subscriptions', 'asp-sub' ), null, $this->plugin_slug . '-sub' );

		add_settings_field(
			'live_webhook_status',
			__( 'Live Webhook Status', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'live_webhook_status',
				'desc'  => '',
			)
		);

		add_settings_field(
			'live_webhook_url',
			__( 'Live Webhook URL', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'live_webhook_url',
				'size'  => 50,
				'desc'  => __( 'This is live webhook URL.', 'asp-sub' ),
			)
		);

		add_settings_field(
			'live_webhook_secret',
			__( 'Live Webhook Signing Secret', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'live_webhook_secret',
				'size'  => 50,
				'desc'  => __( 'Live webhook signing secret from your Stripe account.', 'asp-sub' ),
			)
		);

		add_settings_field(
			'test_webhook_status',
			__( 'Test Webhook Status', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'test_webhook_status',
				'desc'  => '',
			)
		);

		add_settings_field(
			'test_webhook_url',
			__( 'Test Webhook URL', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'test_webhook_url',
				'size'  => 50,
				'desc'  => __( 'This is test webhook URL.', 'asp-sub' ),
			)
		);

		add_settings_field(
			'test_webhook_secret',
			__( 'Webhook Signing Secret', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'webhook_secret',
				'size'  => 50,
				'desc'  => __( 'Webhook signing secret from your Stripe account.', 'asp-sub' ),
			)
		);

		add_settings_field(
			'test_webhook_secret',
			__( 'Test Webhook Signing Secret', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'test_webhook_secret',
				'size'  => 50,
				'desc'  => __( 'Test webhook signing secret from your Stripe account.', 'asp-sub' ),
			)
		);

		add_settings_field(
			'delete_webhooks',
			__( 'Delete Webhooks', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'delete_webhooks',
				'desc'  => __( 'Use this button if you have issues with webhooks or want to uninstall the addon.', 'asp-sub' ),
			)
		);

		add_settings_field(
			'clear_cache',
			__( 'Clear Cache', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section',
			array(
				'field' => 'clear_cache',
				'desc'  => __( 'Clear plans cache. Useful if you have updated plan details in your Stripe Dashboard and want the changes to be reflected on the site.', 'asp-sub' ),
			)
		);

		add_settings_section( 'AcceptStripePayments-sub-section-email', __( 'Email Settings', 'asp-sub' ), null, $this->plugin_slug . '-sub' );

		add_settings_field(
			'sub_expiry_email_enabled',
			__( 'Send Email On CC Expire', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section-email',
			array(
				'field' => 'sub_expiry_email_enabled',
				'desc'  => __( 'If enabled, email is sent to customer when his\her credit card on file is going to expire soon.', 'asp-sub' ),
			)
		);
		add_settings_field(
			'sub_expiry_email_from',
			__( 'From Email Address', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section-email',
			array(
				'field' => 'sub_expiry_email_from',
				'desc'  => __( 'Enter email From address.', 'asp-sub' ),
			)
		);
		add_settings_field(
			'sub_expiry_email_subj',
			__( 'Email Subject', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section-email',
			array(
				'field' => 'sub_expiry_email_subj',
				'desc'  => __( 'Enter email subject.', 'asp-sub' ),
			)
		);

		$email_tags = array(
			'{card_brand}'     => __( "Current card's brand. Example: VISA, MasterCard.", 'asp-sub' ),
			'{card_last_4}'    => __( "Current card's last 4 digits. Example: 1234", 'asp-sub' ),
			'{card_exp_month}' => __( "Current card's expiry month. Example: 8", 'asp-sub' ),
			'{card_exp_year}'  => __( "Current card's expiry year. Example: 2020", 'asp-sub' ),
			'{update_cc_url}'  => __( 'URL that customer should visit to update credit card details.', 'asp-sub' ),
		);

		$email_tags_hint = '';
		if ( method_exists( 'AcceptStripePayments_Admin', 'get_email_tags_descr_out' ) ) {
			$email_tags_hint = AcceptStripePayments_Admin::get_email_tags_descr_out( $email_tags, false );
		}

		add_settings_field(
			'sub_expiry_email_body',
			__( 'Email Body', 'asp-sub' ),
			array( &$this->ASPAdmin, 'settings_field_callback' ),
			$this->plugin_slug . '-sub',
			'AcceptStripePayments-sub-section-email',
			array(
				'field' => 'sub_expiry_email_body',
				'desc'  => __( 'Enter email body. You can use the following email tags in this email body field:' . $email_tags_hint, 'asp-sub' ),
			)
		);
	}

	function add_product_type( $product_types, $post ) {
		$product_types['subscription'] = __( 'Subscription', 'asp-sub' );
		return $product_types;
	}

	function product_type_selected( $product_type, $post ) {
		$is_sub = get_post_meta( $post->ID, 'asp_sub_plan_id', true );
		return $is_sub ? 'subscription' : $product_type;
	}

	function product_type_output( $post ) {
		$current_val = get_post_meta( $post->ID, 'asp_sub_plan_id', true );
		$plans       = get_posts(
			array(
				'post_type'      => ASPSUB_main::$plans_slug,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);
		$str         = '<option value="">' . __( '(No plan)', 'asp-sub' ) . '</option>';
		if ( ! empty( $plans ) ) {
			foreach ( $plans as $plan ) {
				$mode     = get_post_meta( $plan->ID, 'asp_sub_plan_is_live', true ) ? '' : __( '[Test]', 'asp-sub' );
				$selected = '';
				if ( $current_val == $plan->ID ) {
					$selected = ' selected';
				}
				$str .= sprintf( '<option value="%d"%s>%s</option>', $plan->ID, $selected, $mode . ' ' . $plan->post_title );
			}
			$str = '<select name="asp_sub_plan_id" id="asp_sub_plan_id">' . $str . '</select>';
		} else {
			$str = __( 'No subscription plans created yet. You need to <a href="post-new.php?post_type=asp_sub_plan">create one</a> first.', 'asp-sub' );
		}
		?>
		<label><?php _e( 'Plan ID:', 'asp-sub' ); ?></label><br/>
		<?php echo $str; ?>
		<p class="description"><?php _e( 'Specify Stripe plan ID for this product. Leave it blank if you don\'t want to use subscription for the product.', 'asp-sub' ); ?></p>
		<?php
	}

	function output_product_settings_before( $post ) {
		$is_sub = get_post_meta( $post->ID, 'asp_sub_plan_id', true );
		if ( $is_sub ) {
			echo '<style>div#asp_sub_container_price {display: none}</style>';
			?>
			<?php
		} else {
			echo '<style>div#asp_sub_container_plans {display: none}</style>';
		}
		?>
	<p>
		<label>
		<input type="radio" class="asp_sub_type_radio" name="asp_sub_type_radio" value="price"<?php echo ! $is_sub ? ' checked' : ''; ?>> <?php _e( 'One-time payment', 'asp-sub' ); ?>
		</label>
		<label>
		<input type="radio" class="asp_sub_type_radio" name="asp_sub_type_radio" value="plans"<?php echo $is_sub ? ' checked' : ''; ?>> <?php _e( 'Subscription', 'asp-sub' ); ?>
		</label>
		<script>
		var asp_sub_curr_plan_val = '<?php echo get_post_meta( $post->ID, 'asp_sub_plan_id', true ); ?>';
		jQuery('input.asp_sub_type_radio').change(function () {
			val = jQuery(this).val();
			jQuery('div.asp_sub_price_plan_cont').hide();
			jQuery('div#asp_sub_container_' + val).show();
			if (val === 'price') {
			asp_sub_curr_plan_val = jQuery('#asp_sub_plan_id').val();
			jQuery('#asp_sub_plan_id').val('');
//			jQuery('#asp_shipping_cost_container').show();
			} else {
			jQuery('#asp_sub_plan_id').val(asp_sub_curr_plan_val);
//			jQuery('#asp_shipping_cost_container').hide();
			}
		});
		</script>
	</p>
		<?php
		echo '<div id="asp_sub_container_price" class="asp_sub_price_plan_cont">';
	}

	function output_product_settings_after( $post ) {
		$current_val = get_post_meta( $post->ID, 'asp_sub_plan_id', true );
		$plans       = get_posts(
			array(
				'post_type'      => ASPSUB_main::$plans_slug,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);
		$str         = '<option value="">' . __( '(No plan)', 'asp-sub' ) . '</option>';
		if ( ! empty( $plans ) ) {
			foreach ( $plans as $plan ) {
				$mode     = get_post_meta( $plan->ID, 'asp_sub_plan_is_live', true ) ? '' : __( '[Test]', 'asp-sub' );
				$selected = '';
				if ( $current_val == $plan->ID ) {
					$selected = ' selected';
				}
				$str .= sprintf( '<option value="%d"%s>%s</option>', $plan->ID, $selected, $mode . ' ' . $plan->post_title );
			}
			$str = '<select name="asp_sub_plan_id" id="asp_sub_plan_id">' . $str . '</select>';
		} else {
			$str = __( 'No subscription plans created yet. You need to <a href="post-new.php?post_type=asp_sub_plan">create one</a> first.', 'asp-sub' );
		}
		?>
	</div>
	<div id="asp_sub_container_plans" class="asp_sub_price_plan_cont">
		<label><?php _e( 'Plan ID:', 'asp-sub' ); ?></label><br/>
		<?php echo $str; ?>
		<p class="description"><?php _e( 'Specify Stripe plan ID for this product. Leave it blank if you don\'t want to use subscription for the product.', 'asp-sub' ); ?></p>
	</div>
		<?php
	}

	function after_styles() {
		?>
	<style>
		.asp-sub-live-webhook-status span.dashicons-no,
		.asp-sub-test-webhook-status span.dashicons-no {
		color:red;
		}
		.asp-sub-live-webhook-status span.dashicons-warning,
		.asp-sub-test-webhook-status span.dashicons-warning {
		color:sandybrown;
		}
		.asp-sub-live-webhook-status span.dashicons-yes,
		.asp-sub-test-webhook-status span.dashicons-yes {
		color:green;
		}
	</style>
		<?php
	}

	function after_tabs_menu() {
		?>
	<a href="#sub" data-tab-name="sub" class="nav-tab"><?php echo __( 'Subscriptions', 'asp-sub' ); ?></a>
		<?php
		wp_register_script( 'asp_sub_admin_script', plugin_dir_url( $this->ASPSubMain->file ) . '/admin/js/asp-sub-admin.js', array(), ASPSUB_main::ADDON_VER, true );
	}

	function after_tabs() {
		?>
	<div class="wp-asp-tab-container asp-sub-container" data-tab-name="sub">
		<?php do_settings_sections( $this->plugin_slug . '-sub' ); ?>
	</div>
		<?php
		wp_localize_script(
			'asp_sub_admin_script',
			'aspSUBData',
			array(
				'nonce_delete_webhooks' => wp_create_nonce( 'asp-sub-delete-webhooks' ),
				'nonce_clear_cache'     => wp_create_nonce( 'asp-sub-clear-cache' ),
				'nonce_create_webhook'  => wp_create_nonce( 'asp-sub-create-webhook' ),
				'str'                   => array(
					'clearing'        => __( 'Clearing...', 'asp-sub' ),
					'errorOccured'    => __( 'Error occurred:', 'asp-sub' ),
					'creatingWebhook' => __( 'Creating webhook...', 'asp-sub' ),
					'deleting'        => __( 'Deleting...', 'asp-sub' ),
				),
			)
		);
			wp_enqueue_script( 'asp_sub_admin_script' );
	}

	public function email_tags_descr( $email_tags ) {
		$email_tags['Subscriptions Addon tags'] = '';
		$email_tags['{sub_cancel_url}']         = __( 'Displays subscription cancellation URL', 'asp-sub' );
		$email_tags['{sub_update_cc_url}']      = __( 'Displays update credit card URL', 'asp-sub' );
		return $email_tags;
	}

}

new ASPSUB_admin_menu();
