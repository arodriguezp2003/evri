<?php

class ASP_Sub_Product_Item extends ASP_Product_Item {

	protected $plan_id  = false;
	protected $plan     = false;
	protected $is_trial = false;

	public function __construct( $post_id = false ) {
		$this->asp_main = AcceptStripePayments::get_instance();
		if ( false !== $post_id ) {
			//let's try to load item from product
			$this->post_id = $post_id;
			$this->load_from_product();
			if ( ! $this->last_error ) {
				//check if this is subs product
				$plan_id = get_post_meta( $post_id, 'asp_sub_plan_id', true );
				if ( ! $plan_id ) {
					$this->last_error = 'Not subscription product.';
				} else {
					$this->plan_id = $plan_id;
					$sub_main      = ASPSUB_main::get_instance();
					$this->plan    = $sub_main->get_plan_data( $plan_id );
					if ( $this->plan ) {
						if ( ! isset( $this->plan->is_variable ) || ( isset( $this->plan_is_variable ) && ! $this->plan->is_variable ) ) {
							$this->zero_cent = AcceptStripePayments::is_zero_cents( $this->get_currency() );
							$plan_inner      = ASP_Sub_Plan::get_instance();
							if ( $this->plan->trial_period_days ) {
								$this->set_price( 0 );
								$this->set_shipping( 0 );
								$this->is_trial = true;
								add_action( 'asp_ng_coupon_discount_str', array( $this, 'change_coupon_discount_str' ), 10, 2 );
							} else {
								$this->set_price( $this->zero_cent ? $this->plan->amount : $this->plan->amount / 100 );
							}
						}
					}
				}
			}
		}
	}

	public function get_items_total( $in_cents = false, $with_discount = false ) {
		if ( ! empty( $this->is_trial ) ) {
			$with_discount = false;
		}
		return parent::get_items_total( $in_cents, $with_discount );
	}

	public function get_price( $in_cents = false, $price_with_discount = false ) {
		if ( ! empty( $this->is_trial ) ) {
			$price_with_discount = false;
		}
		if ( ! empty( $this->plan->trial_period_days ) && ! empty( $this->plan->is_variable ) ) {
			return 0;
		}
		return parent::get_price( $in_cents, $price_with_discount );
	}

	public function get_discount_amount( $total, $in_cents = false ) {
		if ( ! empty( $this->is_trial ) ) {
			return 0;
		}
		return parent::get_discount_amount( $total, $in_cents );
	}

	public function apply_discount_to_amount( $amount, $in_cents = false ) {
		if ( ! empty( $this->is_trial ) ) {
			return $amount;
		}
		return parent::apply_discount_to_amount( $amount, $in_cents );
	}

	public function get_product_shipping( $in_cents = false ) {
		$shipping         = $this->shipping;
		$this->shipping   = null;
		$product_shipping = $this->get_shipping( $in_cents );
		$this->shipping   = $shipping;
		return $product_shipping;
	}

	public function get_plan_id() {
		return $this->plan_id;
	}

	public function get_currency() {
		$curr            = get_post_meta( $this->plan_id, 'asp_sub_plan_currency', true );
		$this->zero_cent = AcceptStripePayments::is_zero_cents( $curr );
		return $curr;
	}

	public function is_variable() {
		return get_post_meta( $this->plan_id, 'asp_sub_plan_is_variable', true );
	}

	public function change_coupon_discount_str( $str, $coupon ) {
		if ( ! empty( $this->is_trial ) ) {
			$str = 0;
		}
		return $str;

	}

}
