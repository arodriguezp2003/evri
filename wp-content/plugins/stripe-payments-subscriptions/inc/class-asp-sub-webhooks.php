<?php

class ASP_SUB_Webhooks {

	use ASP_SUB_Process_Webhook;

	protected static $instance = null;
	protected $asp_main;
	protected $aspsub_main;

	private $last_sign_sec = false;
	private $last_status;
	private $last_msg;
	private $last_webhook;
	private $last_hide_btn;
	public $old_core_plugin_ver    = false;
	private $show_live_hook_notice = false;
	private $show_test_hook_notice = false;

	function __construct() {
		$this->aspsub_main = ASPSUB_main::get_instance();
		$this->asp_main    = AcceptStripePayments::get_instance();
		ASPMain::load_stripe_lib();

		//check if this is webhook post from Stripe
		$hook_type = filter_input( INPUT_GET, 'asp_hook', FILTER_SANITIZE_STRING );
		if ( ! empty( $hook_type ) ) {
			$this->process_hook( $hook_type );
		}

		$this->old_core_plugin_ver = version_compare( WP_ASP_PLUGIN_VERSION, '1.9.15t1' ) < 0 ? true : false;

		if ( ! $this->old_core_plugin_ver ) {
			//schedule event to check if webhooks are configured properly
			add_action( 'asp_sub_check_webhooks_status', array( $this, 'check_webhooks_status' ) );
			if ( ! wp_next_scheduled( 'asp_sub_check_webhooks_status' ) ) {
				wp_schedule_event( time(), 'twicedaily', 'asp_sub_check_webhooks_status' );
			}
		}

		if ( is_admin() ) {
			$this->show_live_hook_notice = get_option( 'asp_sub_show_live_webhook_notice' );
			$this->show_test_hook_notice = get_option( 'asp_sub_show_test_webhook_notice' );
			if ( $this->show_live_hook_notice || $this->show_test_hook_notice ) {
				add_action( 'admin_notices', array( $this, 'show_webhooks_admin_notice' ) );
			}

			if ( wp_doing_ajax() ) {
				$this->add_ajax_hooks();
			}
		}
	}

	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function process_deactivate_hook() {
		$timestamp = wp_next_scheduled( 'asp_sub_check_webhooks_status' );
		wp_unschedule_event( $timestamp, 'asp_sub_check_webhooks_status' );
	}

	public function show_webhooks_admin_notice() {
		$class        = 'notice notice-error';
		$settings_url = add_query_arg(
			array(
				'post_type' => 'asp-products',
				'page'      => 'stripe-payments-settings#sub',
			),
			get_admin_url() . 'edit.php'
		);
		// translators: %s is URL to settings page
		$message = sprintf( __( '<b>Stripe Payments Subscriptions Addon:</b> webhooks seem to be not configured properly. Please go to <a href="%s">settings page</a> to configure them.', 'asp-sub' ), $settings_url );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
	}

	private function add_ajax_hooks() {
		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'wp_ajax_asp_sub_check_webhooks', array( $this, 'ajax_check_webhooks' ) );
			add_action( 'wp_ajax_asp_sub_create_webhook', array( $this, 'ajax_create_webhook' ) );
			add_action( 'wp_ajax_asp_sub_delete_webhooks', array( $this, 'ajax_delete_webhooks' ) );
		}
	}

	public function ajax_check_webhooks() {
		$ret = array();
		//check core plugin version first
		if ( $this->old_core_plugin_ver ) {
			//core plugin version is too old for this functionality
			$msg                    = __( 'Stripe Payments core plugin version 1.9.15+ required for this functionality', 'asp-sub' );
			$ret['live']['status']  = 'warning';
			$ret['live']['msg']     = $msg;
			$ret['test']['status']  = 'warning';
			$ret['test']['msg']     = $msg;
			$ret['test']['hidebtn'] = true;
			$ret['live']['hidebtn'] = true;
			wp_send_json( $ret );
		}
		//check and create webhooks
		$this->check_webhook( 'test' );
		$ret['test']['status']  = $this->last_status;
		$ret['test']['msg']     = $this->last_msg;
		$ret['test']['hidebtn'] = $this->last_hide_btn;
		$ret['test']['signsec'] = $this->last_sign_sec;
		$this->check_webhook( 'live' );
		$ret['live']['status']  = $this->last_status;
		$ret['live']['msg']     = $this->last_msg;
		$ret['live']['hidebtn'] = $this->last_hide_btn;
		$ret['live']['signsec'] = $this->last_sign_sec;
		wp_send_json( $ret );
	}

	public function ajax_create_webhook() {
		$nonce = filter_input( INPUT_POST, 'nonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, 'asp-sub-create-webhook' ) ) {
			$ret['status'] = 'no';
			$ret['msg']    = __( 'Nonce check failed. Please refresh page and try again.', 'asp-sub' );
			wp_send_json( $ret );
		}

		$mode = filter_input( INPUT_POST, 'mode', FILTER_SANITIZE_STRING );
		if ( ! in_array( $mode, array( 'test', 'live' ), true ) ) {
			//invalid mode specified
			$ret['status'] = 'no';
			$ret['msg']    = __( 'Invalid mode specified', 'asp-sub' );
			wp_send_json( $ret );
		}
		$webhook = $this->check_webhook( $mode );
		if ( $webhook ) {
			$ret['status']  = 'yes';
			$ret['msg']     = __( 'Webhook exists', 'asp-sub' );
			$ret['hidebtn'] = true;
			wp_send_json( $ret );
		}
		if ( $this->create_webhook( $mode ) ) {
			//webhook created
			$ret['status']  = 'yes';
			$ret['signsec'] = $this->last_sign_sec;
			$ret['hidebtn'] = true;
			update_option( 'asp_sub_show_' . $mode . '_webhook_notice', false );
			$ret['msg'] = __( 'Webhook has been created', 'asp-sub' );
		} else {
			//error occurred during webhook creation
			$ret['status'] = 'no';
			$ret['msg']    = $this->last_msg;
		}
		wp_send_json( $ret );
	}

	public function ajax_delete_webhooks() {
		$nonce = filter_input( INPUT_POST, 'nonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, 'asp-sub-delete-webhooks' ) ) {
			$ret['success'] = false;
			$ret['msg']     = __( 'Nonce check failed. Please refresh page and try again.', 'asp-sub' );
			wp_send_json( $ret );
		}

		$this->check_webhook( 'test' );
		if ( false !== $this->last_webhook ) {
			$this->last_webhook->delete();
		}
		$this->check_webhook( 'live' );
		if ( false !== $this->last_webhook ) {
			$this->last_webhook->delete();
		}
		$ret['success'] = true;
		$ret['msg']     = __( 'Webhooks have been deleted.', 'asp-sub' );
		wp_send_json( $ret );
	}

	public function check_webhooks_status() {
		if ( $this->old_core_plugin_ver ) {
			return false;
		}
		$live = $this->check_webhook( 'live' );
		if ( ! $live ) {
			// no live webhook created
			update_option( 'asp_sub_show_live_webhook_notice', true );
		} else {
			update_option( 'asp_sub_show_live_webhook_notice', false );
		}
		$test = $this->check_webhook( 'test' );
		if ( ! $test ) {
			// no test webhook created
			update_option( 'asp_sub_show_test_webhook_notice', true );
		} else {
			update_option( 'asp_sub_show_test_webhook_notice', false );
		}
	}

	private function find_webhook( $webhooks, $url ) {
		$url = strtolower( $url );
		foreach ( $webhooks as $webhook ) {
			if ( strtolower( $webhook['url'] ) === $url ) {
				return $webhook;
			}
		}
		return false;
	}

	static function get_webhook_url( $mode ) {
		$webhook_url = add_query_arg(
			array(
				'asp_hook' => $mode,
			),
			get_home_url( null, '/', 'https' )
		);
		return $webhook_url;
	}

	public function check_webhook( $mode ) {
		$this->last_hide_btn = false;
		$this->last_webhook  = false;
		$key                 = 'live' === $mode ? $this->asp_main->APISecKeyLive : $this->asp_main->APISecKeyTest;
		try {
			\Stripe\Stripe::setApiKey( $key );
			$webhooks = \Stripe\WebhookEndpoint::all( array( 'limit' => 100 ) );
		} catch ( \Stripe\Error\Authentication $e ) {
			$this->last_status = 'no';
			// translators: %s is `live` or `test` mode
			$this->last_msg      = sprintf( __( 'Invalid API Key. Please check core plugins settings and enter valid key for %s mode.', 'asp-sub' ), $mode );
			$this->last_hide_btn = true;
			return false;
		} catch ( Exception $e ) {
			$this->last_status   = 'no';
			$this->last_msg      = $e->getMessage();
			$this->last_hide_btn = true;
			return false;
		}
		if ( ! empty( $webhooks->data[0] ) ) {
			//we have some webhooks set. Let's find if ours is there
			$webhook = $this->find_webhook( $webhooks->data, self::get_webhook_url( $mode ) );
			if ( $webhook !== false ) {
				//webhook already exists
				$this->last_status   = 'yes';
				$this->last_msg      = __( 'Webhook exists. If you still have issues with webhooks, try to delete them and create again.', 'asp-sub' );
				$this->last_hide_btn = true;
				$this->last_webhook  = $webhook;
				update_option( 'asp_sub_show_' . $mode . '_webhook_notice', false );
				return true;
			}
		}
		//webhook wasn't found
		$this->last_status   = 'no';
		$this->last_msg      = __( 'No webhook found. Use the button below to automatically create it.', 'asp-sub' );
		$this->last_sign_sec = '';

		return false;
	}

	private function create_webhook( $mode ) {
		try {
			$webhook = \Stripe\WebhookEndpoint::create(
				array(
					'url'            => self::get_webhook_url( $mode ),
					'enabled_events' => array( '*' ),
				)
			);
			//let's also update webhook signing secret
			unregister_setting( 'AcceptStripePayments-settings-group', 'AcceptStripePayments-settings' );
			$opts                              = get_option( 'AcceptStripePayments-settings' );
			$opts[ $mode . '_webhook_secret' ] = $webhook->secret;
			update_option( 'AcceptStripePayments-settings', $opts );
			$this->last_sign_sec = $webhook->secret;
			return true;
		} catch ( Exception $e ) {
			$this->last_msg = __( 'Error occurred during webhook creation:', 'asp-sub' ) . ' ' . $e->getMessage();
			return false;
		}
	}

}

ASP_SUB_Webhooks::get_instance();
