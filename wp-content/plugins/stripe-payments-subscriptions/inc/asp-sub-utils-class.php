<?php

class ASPSUB_Utils {

	protected static $instance = null;
	private $sub_main;

	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	function __construct() {
		self::$instance = $this;

		$this->sub_main = ASPSUB_main::get_instance();

		ASPMain::load_stripe_lib();

		if ( wp_doing_ajax() && is_admin() ) {
			$this->add_ajax_hooks();
		}

		add_filter( 'asp_email_body_tags_vals_before_replace', array( $this, 'email_body_tags_vals_before_replace' ), 10, 2 );
		add_filter( 'asp_email_body_after_replace', array( $this, 'email_body_after_replace' ) );
	}

	private function add_ajax_hooks() {
		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'wp_ajax_asp_sub_clear_cache', array( $this, 'ajax_clear_plans_cache' ) );
		}
	}

	public function ajax_clear_plans_cache() {
		$nonce = filter_input( INPUT_POST, 'nonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, 'asp-sub-clear-cache' ) ) {
			$ret['success'] = false;
			$ret['msg']     = __( 'Nonce check failed. Please refresh page and try again.', 'asp-sub' );
			wp_send_json( $ret );
		}

		update_option( 'asp_sub_plans_cache', '' );
		$ret['msg'] = __( 'Cache has been cleared.', 'asp-sub' );
		wp_send_json( $ret );
	}

	public static function format_date( $date ) {
		$format   = get_option( 'date_format' ) . ', ' . get_option( 'time_format' );
		$tmp_date = date( 'Y-m-d H:i:s', $date ); //phpcs:ignore
		$ret      = get_date_from_gmt( $tmp_date, $format );
		return $ret;
	}

	public static function get_interval_str( $unit, $count = 0 ) {
		$period_units_str = array(
			'days'   => array( __( 'day', 'asp-sub' ), __( 'days', 'asp-sub' ) ),
			'weeks'  => array( __( 'week', 'asp-sub' ), __( 'weeks', 'asp-sub' ) ),
			'months' => array( __( 'month', 'asp-sub' ), __( 'months', 'asp-sub' ) ),
		);
		if ( ! isset( $period_units_str[ $unit ] ) ) {
			return $unit;
		}
		$ret = $unit;
		if ( $count == 1 ) {
			$ret = $period_units_str[ $unit ][0];
		} else {
			$ret = $period_units_str[ $unit ][1];
		}
		return $ret;
	}

	public static function get_plan_descr( $post_id, $need_total = false ) {
		$variables = get_post_meta( $post_id, 'asp_sub_plan_variables', true );
		$curr      = get_post_meta( $post_id, 'asp_sub_plan_currency', true );
		$price     = get_post_meta( $post_id, 'asp_sub_plan_price', true );
		$price_fmt = AcceptStripePayments::formatted_price( $price, $curr );
		if ( isset( $variables['price'] ) ) {
			$price_fmt = 'N';
		}
		$interval_count = get_post_meta( $post_id, 'asp_sub_plan_period', true );
		$interval       = get_post_meta( $post_id, 'asp_sub_plan_period_units', true );
		$duration       = get_post_meta( $post_id, 'asp_sub_plan_duration', true );
		$trial          = get_post_meta( $post_id, 'asp_sub_plan_trial', true );
		$str            = $price_fmt;
		$interval       = self::get_interval_str( $interval, $interval_count );
		if ( $interval_count == 1 ) {
			$str .= '/' . $interval;
		} else {
			$str .= ' ' . __( 'every', 'asp-sub' ) . ' ' . $interval_count . ' ' . $interval;
		}
		if ( $duration != 0 ) {
			$str .= ' X ' . $duration;
			if ( $need_total && ! isset( $variables['price'] ) ) {
				$str .= '<br />' . __( 'Total:', 'asp-sub' ) . ' ' . AcceptStripePayments::formatted_price( $duration * $price, $curr );
			}
		} else {
			$str .= '<span class="asp_subs_price_until_cancelled"> ' . __( 'until cancelled', 'asp-sub' ) . '</span>';
		}
		if ( $trial ) {
			$trial_interval_str = self::get_interval_str( 'days', $trial );
			// translators: %1$d is number, %2$s is days\weeks\months word
			$str = sprintf( __( 'Free for %1$d %2$s, then', 'asp-sub' ), $trial, $trial_interval_str ) . ' ' . $str;
		}
		return $str;
	}

	public function email_body_tags_vals_before_replace( $tags_vals, $post ) {
		if ( ! isset( $post['txn_id'] ) || substr( $post['txn_id'], 0, 4 ) !== 'sub_' ) {
			//not a subscription
			return $tags_vals;
		}
		$sub_id = $post['txn_id'];
		include_once $this->sub_main->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';
		$sub_class     = ASPSUB_stripe_subs::get_instance();
		$cancel_url    = $sub_class->get_cancel_link( $sub_id );
		$update_cc_url = $sub_class->get_update_cc_link( $sub_id );
		if ( $cancel_url ) {
			//let's add tags and corresponding vals
			$tags_vals['tags'][] = '{sub_cancel_url}';
			$tags_vals['vals'][] = $cancel_url;
			$tags_vals['tags'][] = '{sub_update_cc_url}';
			$tags_vals['vals'][] = $update_cc_url;
		}
		return $tags_vals;
	}

	public function email_body_after_replace( $body ) {
		//let's remove potential tags leftovers
		$body = preg_replace( array( '/\{sub_cancel_url\}/' ), array( '' ), $body );
		$body = preg_replace( array( '/\{sub_update_cc_url\}/' ), array( '' ), $body );
		return $body;
	}

	public static function is_sub_active( $status ) {
		return in_array( $status, array( 'active', 'trialing', 'incomplete', 'past_due' ), true );
	}

}

ASPSUB_Utils::get_instance();
