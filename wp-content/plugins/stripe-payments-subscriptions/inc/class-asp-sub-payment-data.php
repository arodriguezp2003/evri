<?php

class ASP_Sub_Payment_Data extends ASP_Payment_Data {
	protected $cust;
	protected $pid;
	protected $last_inv;

	protected function load_from_obj() {
		try {
			$obj = \Stripe\Subscription::retrieve(
				array(
					'id'     => $this->obj_id,
					'expand' => array( 'latest_invoice', 'latest_invoice.charge', 'customer' ),
				)
			);
		} catch ( Exception $e ) {
			$this->last_error     = $e->getMessage();
			$this->last_error_obj = $e;
			return false;
		}
		$this->obj = $obj;

		$this->cust     = $obj->customer;
		$this->last_inv = $obj->latest_invoice;
		if ( 'trialing' === $obj->status ) {
			$this->amount = 0;
		}

		$this->currency       = $obj->plan->currency;
		$this->charge_created = $obj->created;
		$this->charge_data    = $obj;
		$this->trans_id       = $this->obj_id;

		$this->tax = empty( $this->obj->default_tax_rates ) ? 0 : $this->obj->default_tax_rates[0]->percentage;

		$this->quantity = $this->obj->quantity;
	}

	public function get_billing_details() {
		if ( false !== $this->billing_details_obj ) {
			return $this->billing_details_obj;
		}
		$billing_addr        = new stdClass();
		$billing_addr->name  = $this->cust->name;
		$billing_addr->email = $this->cust->email;
		if ( isset( $this->cust->address ) ) {
			$bd                        = $this->cust;
			$billing_addr->line1       = isset( $bd->address->line1 ) ? $bd->address->line1 : '';
			$billing_addr->line2       = isset( $bd->address->line2 ) ? $bd->address->line2 : '';
			$billing_addr->postal_code = isset( $bd->address->postal_code ) ? $bd->address->postal_code : '';
			$billing_addr->city        = isset( $bd->address->city ) ? $bd->address->city : '';
			$billing_addr->state       = isset( $bd->address->state ) ? $bd->address->state : '';
			$billing_addr->country     = isset( $bd->address->country ) ? $bd->address->country : '';
		}

		$this->billing_details_obj = $billing_addr;
		return $this->billing_details_obj;
	}

	public function get_shipping_details() {
		if ( false !== $this->shipping_details_obj ) {
			return $this->shipping_details_obj;
		}
		$shipping_addr       = new stdClass();
		$shipping_addr->name = isset( $this->cust->shipping->name ) ? $this->cust->shipping->name : '';
		if ( isset( $this->cust->shipping->address ) ) {
			$sd                         = $this->cust->shipping->address;
			$shipping_addr->line1       = isset( $sd->line1 ) ? $sd->line1 : '';
			$shipping_addr->line2       = isset( $sd->line2 ) ? $sd->line2 : '';
			$shipping_addr->postal_code = isset( $sd->postal_code ) ? $sd->postal_code : '';
			$shipping_addr->city        = isset( $sd->city ) ? $sd->city : '';
			$shipping_addr->state       = isset( $sd->state ) ? $sd->state : '';
			$shipping_addr->country     = isset( $sd->country ) ? $sd->country : '';
		}

		$this->shipping_details_obj = $shipping_addr;
		return $this->shipping_details_obj;
	}

	public function set_pid( $pid ) {
		$this->pid = $pid;
	}

	public function get_price() {
		$this->price = $this->obj->plan->amount;
		if ( 'trialing' === $this->obj->status ) {
			$this->price = 0;
		}
		return $this->price;
	}

	public function get_amount( $with_tax = true ) {
		$price        = $this->get_price();
		$this->amount = $price * $this->quantity;

		if ( 0 !== $this->amount ) {
			//check if coupon applied
			if ( isset( $this->obj->discount ) ) {
				$coupon = $this->obj->discount->coupon;
			} elseif ( isset( $this->last_inv->discount ) ) {
				$coupon = $this->last_inv->discount->coupon;
			}
		}

		//get additional items
		if ( isset( $this->last_inv->lines->data ) ) {
			$lines = $this->last_inv->lines->data;
			foreach ( $lines as $line ) {
				if ( isset( $line->metadata ) && isset( $line->metadata['asp_type'] ) ) {
					if ( 'setup_fee' === $line->metadata['asp_type'] || 'trial_setup_fee' === $line->metadata['asp_type'] ) {
						$this->amount += $line->amount;
					}
				}
			}
		}

		if ( isset( $coupon ) ) {
			if ( isset( $coupon->amount_off ) ) {
				$this->amount = $this->amount - $coupon->amount_off;
			} else {
				$p_off        = isset( $coupon->percent_off_precise ) ? $coupon->percent_off_precise : $coupon->percent_off;
				$this->amount = $this->amount - round( ( $this->amount / 100 ) * $p_off );
			}
		}

		if ( $with_tax && $this->tax ) {
			$tax_amount   = round( AcceptStripePayments::get_tax_amount( $this->amount, $this->tax, AcceptStripePayments::is_zero_cents( $this->currency ) ) );
			$this->amount = $this->amount + $tax_amount;
		}

		//check if shipping applied
		if ( 'trialing' === $this->obj->status ) {
			//get shipping amount from product info as it's not available in invoice details
			$ipn_class = ASP_Process_IPN_NG::get_instance();
			if ( isset( $ipn_class->item ) ) {
				$shipping = $ipn_class->item->get_shipping( true );
				if ( ! empty( $shipping ) ) {
					$this->amount = $this->amount + $shipping;
				}
			}
		} else {
			if ( isset( $this->last_inv->lines->data ) ) {
				$lines = $this->last_inv->lines->data;
				foreach ( $lines as $line ) {
					if ( isset( $line->metadata ) && isset( $line->metadata['asp_type'] ) && 'shipping' === $line->metadata['asp_type'] ) {
						$this->amount += $line->amount;
					}
				}
			}
		}

		return $this->amount;
	}
}
