<?php

class ASP_SUB_Admin {

	private $sub_main;
	private $asp_main;

	public function __construct() {
		$this->sub_main = ASPSUB_main::get_instance();
		$this->asp_main = AcceptStripePayments::get_instance();
		require_once $this->sub_main->PLUGIN_DIR . 'admin/asp-sub-admin-menu.php';
		add_filter( 'asp_products_table_price_column', array( $this, 'products_table_price_column' ), 10, 4 );
	}

	public function products_table_price_column( $output, $o_price, $o_currency, $post_id ) {
		$plan_id = get_post_meta( $post_id, 'asp_sub_plan_id', true );
		if ( empty( $plan_id ) ) {
			return $output;
		}
		$plan = $this->sub_main->get_plan_data( $plan_id );
		if ( false === $plan ) {
			// translators: %s is plan id
			return sprintf( '<span style="color: red">' . __( "Can't find plan with ID %s</span>", 'asp-sub' ), $plan_id );
		}
		$str = __( 'Plan', 'asp-sub' ) . ': ' . '<br /><i>' . $plan->nickname . '</i>';

		$price = $plan->amount;

		if ( ! in_array( strtoupper( $plan->currency ), $this->asp_main->zeroCents, true ) ) {
			$price = $price / 100;
		}

		$str .= '<br/ >' . ASPSUB_Utils::get_plan_descr( $plan_id );

		return $str;
	}

}

new ASP_SUB_Admin();
