<?php

class ASPSUB_subs {

	protected static $instance = null;
	protected $post_slug;
	var $PLUGIN_DIR;
	var $search_term = false;

	private $sub_status_tpl = '<span class="asp-sub-status%s">%s</span>';

	function __construct() {
		self::$instance = $this;

		$this->post_slug  = ASPSUB_main::$subs_slug;
		$this->PLUGIN_DIR = ASPSUB_main::get_plugin_dir();
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Subscriptions', 'Post Type General Name', 'asp-sub' ),
			'singular_name'      => _x( 'Subscription', 'Post Type Singular Name', 'asp-sub' ),
			'parent_item_colon'  => __( 'Parent Subscription:', 'asp-sub' ),
			'all_items'          => __( 'Subscriptions', 'asp-sub' ),
			'view_item'          => __( 'View Subscription', 'asp-sub' ),
			'add_new_item'       => __( 'Add New Subscription', 'asp-sub' ),
			'add_new'            => __( 'Add New Subscription', 'asp-sub' ),
			'edit_item'          => __( 'View Subscription', 'asp-sub' ),
			'update_item'        => __( 'Update Subscription', 'asp-sub' ),
			'search_items'       => __( 'Search Subscriptions', 'asp-sub' ),
			'not_found'          => __( 'No subscriptions yet.', 'asp-sub' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'asp-sub' ),
		);
		$args   = array(
			'label'               => __( 'subscriptions', 'asp-sub' ),
			'description'         => __( 'Subscriptions', 'asp-sub' ),
			'labels'              => $labels,
			'supports'            => array( '' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=' . ASPMain::$products_slug,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts'  => false, // Removes support for the "Add New" function
				'publish_posts' => false,
			),
			'map_meta_cap'        => true,
		);

		register_post_type( $this->post_slug, $args );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
		//
		//  //add custom columns for list view
		add_filter( 'manage_' . $this->post_slug . '_posts_columns', array( $this, 'manage_columns' ) );
		add_action( 'manage_' . $this->post_slug . '_posts_custom_column', array( $this, 'manage_custom_columns' ), 10, 2 );
		//set custom columns sortable
		add_filter( 'manage_edit-' . $this->post_slug . '_sortable_columns', array( $this, 'manage_sortable_columns' ) );
		//handle columns sorting and searching
		add_action( 'pre_get_posts', array( $this, 'manage_search_sort_queries' ) );
		add_action( 'posts_results', array( $this, 'set_search_term' ), 10, 2 );
		//  //enqueue css file to style list table and edit subs pages
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_subs_style' ) );
		//remove inline actions like Edit, Quick Edit etc.
		add_filter( 'post_row_actions', array( $this, 'remove_inline_actions' ), 10, 2 );

		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

		//let's check if this is Cancel Subscription requrest
		if ( isset( $_POST['asp_sub_cancel_sub_nonce'] ) ) {
			$this->process_sub_cancel();
		}
	}

	function post_updated_messages( $messages ) {
		$post      = get_post();
		$post_type = get_post_type( $post );
		$slug      = $this->post_slug;
		if ( $post_type === $slug ) {
			$messages[ $slug ]     = $messages['post'];
			$messages[ $slug ][1]  = __( 'Subscription updated.', 'asp-sub' );
			$messages[ $slug ][4]  = __( 'Subscription updated.', 'asp-sub' );
			$messages[ $slug ][6]  = __( 'Subscription created.', 'asp-sub' );
			$messages[ $slug ][10] = __( 'Subscription draft updated.', 'asp-sub' );
		}
		return $messages;
	}

	function set_search_term( $posts, $query ) {

		if ( ! is_admin() || ( empty( $query->query['post_type'] ) || $query->query['post_type'] !== $this->post_slug ) ) {
			return $posts;
		}

		if ( ! empty( $this->search_term ) ) {
			$query->set( 's', $this->search_term );
			$this->search_term = false;
		}
		return $posts;
	}

	function manage_search_sort_queries( $query ) {

		if ( ! is_admin() || ( empty( $query->query['post_type'] ) || $query->query['post_type'] !== $this->post_slug ) ) {
			return;
		}

		$search_term = $query->query_vars['s'];
		if ( ! empty( $search_term ) ) {
			$query->set( 's', '' );
			$this->search_term = $search_term;
			$custom_fields     = array(
				'customer_email',
				'sub_id',
			);
			$meta_query        = array( 'relation' => 'OR' );

			foreach ( $custom_fields as $custom_field ) {
				array_push(
					$meta_query,
					array(
						'key'     => $custom_field,
						'value'   => $search_term,
						'compare' => 'LIKE',
					)
				);
			}

			$query->set( 'meta_query', $meta_query );
		}

		$orderby = $query->get( 'orderby' );
		switch ( $orderby ) {
			case 'sub_status':
				$query->set( 'meta_key', 'sub_status' );
				$query->set( 'orderby', 'meta_value' );
				break;
			case 'customer':
				$query->set( 'meta_key', 'customer_email' );
				$query->set( 'orderby', 'meta_value' );
				break;
			case 'plan':
				$query->set( 'meta_key', 'plan_id' );
				$query->set( 'orderby', 'meta_value' );
				break;
			case 'sub_date':
				$query->set( 'meta_key', 'sub_date' );
				$query->set( 'orderby', 'meta_value_num' );
				break;
		}
	}

	function process_sub_cancel() {
		if ( ! wp_verify_nonce( $_REQUEST['asp_sub_cancel_sub_nonce'], 'asp_sub_cancel_sub' ) ) {
			return false;
		}
		$sub_id = intval( $_POST['post_ID'] );
		$status = get_post_meta( $sub_id, 'sub_status', true );
		if ( ! $status ) {
			return false;
		}
		if ( ASPSUB_Utils::is_sub_active( $status ) ) {
			//let's send cancel request via Stripe API
			$is_live = get_post_meta( $sub_id, 'is_live', true );
			include_once $this->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';
			$ASPSUB_subs = new ASPSUB_stripe_subs( $is_live );
			$res         = $ASPSUB_subs->cancel( $sub_id );
			if ( ! $res ) {
				echo $ASPSUB_subs->get_last_error();
				wp_die();
			}
			return true;
		}
		return false;
	}

	public function enqueue_subs_style( $hook ) {
		global $post_type;
		if ( $this->post_slug === $post_type ) {
			wp_enqueue_style( 'asp-admin-subs-styles', plugins_url( '', __FILE__ ) . '/css/asp-sub-admin-subs.css', array() );
		}
	}

	function manage_columns( $columns ) {
		unset( $columns );
		$columns = array(
			'sub_id'     => __( 'ID', 'asp-sub' ),
			'sub_date'   => __( 'Subscription Date', 'asp-sub' ),
			'plan'       => __( 'Subscription Plan', 'asp-sub' ),
			'customer'   => __( 'Customer', 'asp-sub' ),
			'sub_status' => __( 'Status', 'asp-sub' ),
		);
		return $columns;
	}

	function manage_sortable_columns( $columns ) {
		$columns['plan']       = 'plan';
		$columns['customer']   = 'customer';
		$columns['sub_date']   = 'sub_date';
		$columns['sub_status'] = 'sub_status';
		return $columns;
	}

	function manage_custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'sub_id':
				$sub_id = get_post_meta( $post_id, 'sub_id', true );
				echo sprintf( '<a href="post.php?post=%d&action=edit">%s</a>', $post_id, $sub_id );
				echo ' | ';
				echo sprintf( '<a href="post.php?post=%d&action=edit">%s</a>', $post_id, 'View Details' );
				break;
			case 'customer':
				echo get_post_meta( $post_id, 'customer_email', true );
				$status      = get_post_meta( $post_id, 'sub_status', true );
				$cc_expiring = get_post_meta( $post_id, 'customer_cc_expiring', true );
				if ( ASPSUB_Utils::is_sub_active( $status ) && $cc_expiring ) {
					echo '<br>';
					echo '<p style="color: red;"><span class="dashicons dashicons-warning" style="vertical-align:middle;"></span> ' . __( 'Credit card expires at the end of current month', 'asp-sub' ) . '</p>';
				}
				break;
			case 'sub_date':
				$date     = get_post_meta( $post_id, 'sub_date', true );
				$sub_date = ASPSUB_Utils::format_date( $date );
				echo $sub_date;
				break;
			case 'sub_status':
				$status = get_post_meta( $post_id, 'sub_status', true );
				if ( ! $status ) {
					$status = '—';
				}
				echo sprintf( $this->sub_status_tpl, ' ' . $status, self::get_status_str( $status ) );
				break;
			case 'plan':
				$plan_id = get_post_meta( $post_id, 'plan_post_id', true );
				if ( $plan_id ) {
					$plan = get_posts(
						array(
							'post_type' => ASPSUB_main::$plans_slug,
							'p'         => $plan_id,
						)
					);
					if ( $plan ) {
						$mode = get_post_meta( $plan_id, 'asp_sub_plan_is_live', true );
						$mode = ! empty( $mode ) ? '' : '[' . __( 'Test', 'asp-sub' ) . '] ';
						echo $mode . $plan[0]->post_title;
					} else {
						echo 'Plan not found';
					}
				} else {
					echo '-';
				}
				break;
		}
	}

	function remove_inline_actions( $actions, $post ) {
		if ( $post->post_type === $this->post_slug ) {
			unset( $actions['edit'] );
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	function add_meta_boxes( $post_type, $post ) {
		if ( $post_type !== $this->post_slug ) {
			return;
		}
		//let's remove ALL metaboxes
		global $wp_meta_boxes;
		unset( $wp_meta_boxes[ $post_type ] );

		//let's add our custom metaboxes for plan
		add_meta_box( 'asp_sub_subs_details', __( 'Subscription Details', 'asp-sub' ), array( $this, 'display_details_meta_box' ), $this->post_slug, 'normal', 'high' );
		add_meta_box( 'asp_sub_subs_events', __( 'Events', 'asp-sub' ), array( $this, 'display_events_meta_box' ), $this->post_slug, 'normal', 'default' );
		add_meta_box( 'asp_sub_subs_manage', __( 'Manage Subscription', 'asp-sub' ), array( $this, 'display_manage_meta_box' ), $this->post_slug, 'side', 'default' );
		$status = get_post_meta( $post->ID, 'sub_status', true );
		if ( ASPSUB_Utils::is_sub_active( $status ) ) {
			add_meta_box( 'asp_sub_subs_cancel_url', __( 'Cancellation URL', 'asp-sub' ), array( $this, 'display_cancel_url_meta_box' ), $this->post_slug, 'side', 'default' );
			add_meta_box( 'asp_sub_update_cc_url', __( 'Update CC details URL', 'asp-sub' ), array( $this, 'display_update_cc_url_meta_box' ), $this->post_slug, 'side', 'default' );
		}
	}

	function display_details_meta_box( $post ) {
		$sub_date   = get_post_meta( $post->ID, 'sub_date', true );
		$sub_date   = ASPSUB_Utils::format_date( $sub_date );
		$sub_id     = get_post_meta( $post->ID, 'sub_id', true );
		$sub_status = get_post_meta( $post->ID, 'sub_status', true );
		$plan_id    = get_post_meta( $post->ID, 'plan_post_id', true );
		$mode       = get_post_meta( $plan_id, 'asp_sub_plan_is_live', true );
		$sub_mode   = ! empty( $mode ) ? '' : '[' . __( 'Test', 'asp-sub' ) . '] ';
		$plan_name  = get_the_title( $plan_id );
		$plan_name  = sprintf( '<a href="post.php?post=%d&action=edit" target="_blank">%s</a>', $plan_id, $sub_mode . $plan_name );
		$cust_email = get_post_meta( $post->ID, 'customer_email', true );
		$payments   = get_post_meta( $post->ID, 'payments_made', true );
		$duration   = get_post_meta( $plan_id, 'asp_sub_plan_duration', true );

		$stripe_sub_url = sprintf( 'https://dashboard.stripe.com%s/subscriptions/%s', empty( $mode ) ? '/test' : '', $sub_id );

		$items = array(
			__( 'Status', 'asp-sub' )         => sprintf( $this->sub_status_tpl, ' ' . $sub_status, self::get_status_str( $sub_status ) ),
			__( 'Created', 'asp-sub' )        => $sub_date,
			__( 'ID', 'asp-sub' )             => '<a title="' . esc_attr__( 'View on Stripe', 'asp-sub' ) . '" target="_blank" href="' . $stripe_sub_url . '">' . $sub_id . '<span style="font-size: 1.2em; vertical-align:middle;" class="dashicons dashicons-external"></span></a>',
			__( 'Plan name', 'asp-sub' )      => $plan_name,
			__( 'Customer email', 'asp-sub' ) => $cust_email,
			__( 'Payments made', 'asp-sub' )  => sprintf( '%d of %d', $payments, $duration ),
		);
		echo '<table class="wp-list-table widefat fixed">';
		foreach ( $items as $name => $value ) {
			switch ( $value ) {
				case '---':
					echo '<hr />';
					break;
				case '***':
					echo '<h4>' . $name . '</h4>';
					break;
				default:
					echo sprintf( '<tr><td><b>%s:</b></td><td>%s<td></tr>', $name, $value );
					break;
			}
		}
		echo '</table>';
	}

	function display_manage_meta_box( $post ) {
		$status = get_post_meta( $post->ID, 'sub_status', true );
		if ( $status ) {
			if ( ASPSUB_Utils::is_sub_active( $status ) ) {
				?>
		<form method="POST">
			<p class="description"><?php _e( 'You can use the button below to cancel the subscription. The subscription is canceled immediately once you confirm the cancellation.', 'asp-sub' ); ?></p>
			<p style="text-align: center;"><button class="button" onclick="return confirm('<?php echo esc_js( __( 'Are you sure want to cancel this subscription? This can\'t be undone', 'asp-sub' ) ); ?>');" id="asp_sub_cancel_sub_btn" type="submit"><?php _e( 'Cancel Subscription', 'asp-sub' ); ?></button></p>
				<?php wp_nonce_field( 'asp_sub_cancel_sub', 'asp_sub_cancel_sub_nonce' ); ?>
		</form>
				<?php
			} else {
				echo __( 'Subscription is not active.', 'asp-sub' );
			}
		}
	}

	function display_cancel_url_meta_box( $post ) {
		$sub_id = get_post_meta( $post->ID, 'sub_id', true );
		include_once $this->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';
		$sub_class  = ASPSUB_stripe_subs::get_instance();
		$cancel_url = $sub_class->get_cancel_link( $sub_id );
		?>
	<p class="description"><?php _e( 'You can send this URL to your customer so he\she can cancel the subscription.', 'asp-sub' ); ?></p>
	<p style="text-align: center;">
		<textarea class="asp-select-on-click" style="width: 100%" rows="5" readonly><?php echo $cancel_url; ?></textarea>
	</p>
	<script>
		jQuery(document).ready(function () {
		jQuery('textarea.asp-select-on-click').click(function () {
			jQuery(this).select();
		});
		});
	</script>
		<?php
	}

	function display_update_cc_url_meta_box( $post ) {
		$sub_id = get_post_meta( $post->ID, 'sub_id', true );
		include_once $this->PLUGIN_DIR . 'inc/asp-sub-stripe-subs-class.php';
		$sub_class     = ASPSUB_stripe_subs::get_instance();
		$update_cc_url = $sub_class->get_update_cc_link( $sub_id );
		?>
	<p class="description"><?php _e( 'You can send this URL to your customer so he\she can update credit card details.', 'asp-sub' ); ?></p>
	<p style="text-align: center;">
		<textarea class="asp-select-on-click" style="width: 100%" rows="5" readonly><?php echo $update_cc_url; ?></textarea>
		<?php
	}

	function display_events_meta_box( $post ) {
		$events = get_post_meta( $post->ID, 'events', true );
		if ( ! $events ) {
			echo __( 'No events yet.', 'asp-sub' );
			return;
		}
		$tpl     = '<tr><td>%s</td><td>%s</td><td>%s</td></tr>';
		$out     = '';
		$plan_id = get_post_meta( $post->ID, 'plan_post_id', true );
		$curr    = get_post_meta( $plan_id, 'asp_sub_plan_currency', true );
		foreach ( array_reverse( $events, true ) as $event ) {
			$out .= sprintf( $tpl, ASPSUB_Utils::format_date( $event['date'] ), $event['descr'], ( ! empty( $event['amount'] ) ? AcceptStripePayments::formatted_price( $event['amount'], $curr, true ) : '—' ) );
		}
		?>
	<table class="wp-list-table widefat fixed striped">
		<thead>
		<tr>
			<th><?php _e( 'Date', 'asp-sub' ); ?></th>
			<th><?php _e( 'Event', 'asp-sub' ); ?></th>
			<th><?php _e( 'Amount', 'asp-sub' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php echo $out; ?>
		</tbody>
	</table>
		<?php
	}

	public static function get_status_str( $status ) {
		$status_str = array(
			'active'             => __( 'Active', 'asp-sub' ),
			'trialing'           => __( 'Trialing', 'asp-sub' ),
			'canceled'           => __( 'Canceled', 'asp-sub' ),
			'ended'              => __( 'Ended', 'asp-sub' ),
			'incomplete'         => __( 'Incomplete', 'asp-sub' ),
			'incomplete_expired' => __( 'Expired', 'asp-sub' ),
			'cancelling'         => __( 'Cancelling', 'asp-sub' ),
		);
		if ( isset( $status_str[ $status ] ) ) {
			return $status_str[ $status ];
		}
		return $status;
	}

}
