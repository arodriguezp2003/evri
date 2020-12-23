<?php

class ASP_SUB_Cancel_Handler {

	protected $sub_main;

	public function __construct() {
		$this->sub_main = ASPSUB_main::get_instance();

		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_nopriv_asp_cancel_sub', array( $this, 'process_subs_cancel' ) );
			add_action( 'wp_ajax_asp_cancel_sub', array( $this, 'process_subs_cancel' ) );
		}

		$asp_action = filter_input( INPUT_GET, 'asp_sub_action', FILTER_SANITIZE_STRING );
		if ( 'cancel' === $asp_action ) {
			$this->cancel_url_handler();
		}
	}

	public function cancel_url_handler() {
		require_once $this->sub_main->PLUGIN_DIR . 'view/sub-cancel-tpl.php';
		$tpl                = new ASP_Sub_Cancel_Tpl();
		$vals['plugin_url'] = plugin_dir_url( $this->sub_main->file ) . 'view/';
		$token              = filter_input( INPUT_GET, 'sub_token', FILTER_SANITIZE_STRING );
		if ( empty( $token ) ) {
			//no token provided
			$vals['content'] = __( 'No token provided.', 'asp-sub' );
			$tpl->set_vals( $vals );
			$tpl->display_tpl( true );
			exit;
		}
		require_once $this->sub_main->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';
		$subs_class = ASPSUB_stripe_subs::get_instance();
		$sub        = $subs_class->find_sub_by_token( $token );
		if ( ! $sub ) {
			//no subscription found
			$vals['content'] = __( 'No subscription found.', 'asp-sub' );
			$tpl->set_vals( $vals );
			$tpl->display_tpl( true );
			exit;
		}
		$vals['sub_token'] = $token;
		$status            = get_post_meta( $sub->ID, 'sub_status', true );
		if ( ! ASPSUB_Utils::is_sub_active( $status ) ) {
			//sub not active
			$vals['content'] = __( 'Subscription is not active.', 'asp-sub' );
			$tpl->set_vals( $vals );
			$tpl->display_tpl( true );
			exit;
		}
		//display subs info
		$plan_title = '';
		$plan_id    = get_post_meta( $sub->ID, 'plan_post_id', true );
		$plan       = get_post( $plan_id );
		if ( $plan ) {
			$plan_title = $plan->post_title;
		}
		$vals['content'] = sprintf( __( "Are you sure want to cancel your subscription to <b>%s</b>?<p>Note this can't be undone.</p>", 'asp-sub' ), $plan_title );
		$tpl->set_vals( $vals );
		$tpl->display_tpl();
		exit;
	}

	public function process_subs_cancel() {
		$out['success'] = false;
		$token          = filter_input( INPUT_POST, 'subId', FILTER_SANITIZE_STRING );
		require_once $this->sub_main->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';
		$subs_class = ASPSUB_stripe_subs::get_instance();
		$sub        = $subs_class->find_sub_by_token( $token );
		if ( ! $sub ) {
			//no subscription found
			$out['msg'] = __( 'No subscription found.', 'asp-sub' );
			wp_send_json( $out );
			exit;
		}
		$status = get_post_meta( $sub->ID, 'sub_status', true );
		if ( ! ASPSUB_Utils::is_sub_active( $status ) ) {
			//sub not active
			$out['msg'] = __( 'Subscription is not active.', 'asp-sub' );
			wp_send_json( $out );
			exit;
		}
		$is_live    = get_post_meta( $sub->ID, 'is_live', true );
		$subs_class = new ASPSUB_stripe_subs( $is_live );
		$res        = $subs_class->cancel( $sub->ID );
		if ( ! $res ) {
			//error occurred during cancel
			$out['msg'] = $subs_class->get_last_error();
			wp_send_json( $out );
			exit;
		}
		$out['success'] = true;
		$out['msg']     = __( 'Subscription has been cancelled.', 'asp-sub' );
		wp_send_json( $out );
		exit;
	}

}

new ASP_SUB_Cancel_Handler();
