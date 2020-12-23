<?php

class ASPSUB_plans {

	protected static $instance = null;
	protected $post_slug;
	var $is_edit     = false;
	var $is_variable = null;
	var $display_errors;

	function __construct() {
		self::$instance = $this;

		$this->post_slug = ASPSUB_main::$plans_slug;
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
			'name'               => _x( 'Subscription Plans', 'Post Type General Name', 'asp-sub' ),
			'singular_name'      => _x( 'Subscription Plan', 'Post Type Singular Name', 'asp-sub' ),
			'parent_item_colon'  => __( 'Parent Plan:', 'asp-sub' ),
			'all_items'          => __( 'Subscription Plans', 'asp-sub' ),
			'view_item'          => __( 'View Plan', 'asp-sub' ),
			'add_new_item'       => __( 'Add New Plan', 'asp-sub' ),
			'add_new'            => __( 'Add New Plan', 'asp-sub' ),
			'edit_item'          => __( 'Edit Plan', 'asp-sub' ),
			'update_item'        => __( 'Update Plan', 'asp-sub' ),
			'search_items'       => __( 'Search Plan', 'asp-sub' ),
			'not_found'          => __( 'No plans created yet.', 'asp-sub' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'asp-sub' ),
		);
		$args   = array(
			'label'               => __( 'plans', 'asp-sub' ),
			'description'         => __( 'Subscription Plans', 'asp-sub' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=' . ASPMain::$products_slug,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		);

		register_post_type( $this->post_slug, $args );

		$ASPSUB_main = ASPSUB_main::get_instance();
		include_once $ASPSUB_main->PLUGIN_DIR . 'inc/asp-sub-stripe-plans-class.php';

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
		add_action( 'save_post_' . $this->post_slug, array( $this, 'save_plan_handler' ), 10, 3 );
		add_action( 'before_delete_post', array( $this, 'delete_plan_handler' ) );
		add_filter( 'gettext', array( $this, 'change_publish_button' ), 10, 2 );

		//add custom columns for list view
		add_filter( 'manage_' . $this->post_slug . '_posts_columns', array( $this, 'manage_columns' ) );
		add_action( 'manage_' . $this->post_slug . '_posts_custom_column', array( $this, 'manage_custom_columns' ), 10, 2 );
		//set custom columns sortable
		add_filter( 'manage_edit-' . $this->post_slug . '_sortable_columns', array( $this, 'manage_sortable_columns' ) );
		//handle columns sorting and searching
		add_action( 'pre_get_posts', array( $this, 'manage_search_sort_queries' ) );
		//add_action( 'posts_results', array( $this, 'set_search_term' ), 10, 2 );
		//enqueue css file to style list table and edit product pages
		add_action( 'admin_head', array( $this, 'enqueue_products_style' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
	}

	function post_updated_messages( $messages ) {
		$post      = get_post();
		$post_type = get_post_type( $post );
		$slug      = $this->post_slug;
		if ( $post_type === $slug ) {
			$messages[ $slug ]     = $messages['post'];
			$messages[ $slug ][1]  = __( 'Plan updated.', 'asp-sub' );
			$messages[ $slug ][4]  = __( 'Plan updated.', 'asp-sub' );
			$messages[ $slug ][6]  = __( 'Plan created.', 'asp-sub' );
			$messages[ $slug ][10] = __( 'Plan draft updated.', 'asp-sub' );
		}
		return $messages;
	}

	function enqueue_products_style() {
		global $post_type;
		if ( $this->post_slug === $post_type ) {
			wp_enqueue_style( 'asp-admin-products-styles', plugins_url( '', __FILE__ ) . '/css/asp-sub-admin-plans.css', array() );
		}
	}

	function manage_columns( $columns ) {
		//      $columns   = array( 'cb' => $columns['cb'] );
		$columns = array(
			'title'    => __( 'Plan Name', 'asp-sub' ),
			'mode'     => __( 'Mode', 'asp-sub' ),
			'currency' => __( 'Currency', 'asp-sub' ),
			'price'    => __( 'Amount', 'asp-sub' ),
			'interval' => __( 'Interval', 'asp-sub' ),
			'duration' => __( 'Duration', 'asp-sub' ),
			'trial'    => __( 'Trial Days', 'asp-sub' ),
			'descr'    => __( 'Description', 'asp-sub' ),
		);
		//      $columns = array_merge( $columns, $c_columns );
		return $columns;
	}

	function manage_sortable_columns( $columns ) {
		$columns['mode']     = 'mode';
		$columns['title']    = 'title';
		$columns['currency'] = 'currency';
		$columns['price']    = 'price';
		$columns['duration'] = 'duration';
		$columns['trial']    = 'trial';
		return $columns;
	}

	function manage_search_sort_queries( $query ) {

		if ( ! is_admin() || ( empty( $query->query['post_type'] ) || $query->query['post_type'] !== $this->post_slug ) ) {
			return;
		}

		$orderby = $query->get( 'orderby' );
		switch ( $orderby ) {
			case 'currency':
				$query->set(
					'meta_query',
					array(
						'relation' => 'OR',
						array(
							'key'     => 'asp_sub_plan_currency',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key' => 'asp_sub_plan_currency',
						),
					)
				);
				$query->set( 'orderby', 'meta_value' );
				break;
			case 'price':
				$query->set(
					'meta_query',
					array(
						'relation' => 'OR',
						array(
							'key'     => 'asp_sub_plan_price',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key' => 'asp_sub_plan_price',
						),
					)
				);
				$query->set( 'orderby', 'meta_value_num' );
				break;
			case 'mode':
				$query->set(
					'meta_query',
					array(
						'relation' => 'OR',
						array(
							'key'     => 'asp_sub_plan_is_live',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key' => 'asp_sub_plan_is_live',
						),
					)
				);
				$query->set( 'orderby', 'meta_value_num' );
				break;
			case 'duration':
				$query->set( 'meta_key', 'asp_sub_plan_duration' );
				$query->set( 'orderby', 'meta_value' );
				break;
			case 'trial':
				$query->set( 'meta_key', 'asp_sub_plan_trial' );
				$query->set( 'orderby', 'meta_value' );
				break;
		}
	}

	function manage_custom_columns( $column, $post_id ) {
		$variables = get_post_meta( $post_id, 'asp_sub_plan_variables', true );
		switch ( $column ) {
			case 'mode':
				$is_live = get_post_meta( $post_id, 'asp_sub_plan_is_live', true );
				if ( $is_live ) {
					echo 'Live';
				} else {
					echo 'Test';
				}
				break;
			case 'currency':
				$curr = get_post_meta( $post_id, 'asp_sub_plan_currency', true );
				if ( isset( $variables['currency'] ) ) {
					echo sprintf( __( 'Variable (%s)', 'asp-sub' ), $curr );
				} else {
					echo $curr;
				}
				break;
			case 'price':
				$price = get_post_meta( $post_id, 'asp_sub_plan_price', true );
				if ( isset( $variables['price'] ) ) {
					echo __( 'Variable', 'asp-sub' );
				} else {
					if ( $price ) {
						echo AcceptStripePayments::formatted_price( $price, get_post_meta( $post_id, 'asp_sub_plan_currency', true ) );
					}
				}
				break;
			case 'duration':
				$duration = get_post_meta( $post_id, 'asp_sub_plan_duration', true );
				if ( ! $duration ) {
					$duration = '—';
				}
				echo $duration;
				break;
			case 'interval':
				echo get_post_meta( $post_id, 'asp_sub_plan_period', true ) . ' ' . get_post_meta( $post_id, 'asp_sub_plan_period_units', true );
				break;
			case 'trial':
				$trial = get_post_meta( $post_id, 'asp_sub_plan_trial', true );
				if ( ! $trial ) {
					$trial = '—';
				}
				echo $trial;
				break;
			case 'descr':
				echo ASPSUB_Utils::get_plan_descr( $post_id, true );
				break;
		}
	}

	function change_publish_button( $translation, $text ) {
		if ( $this->post_slug === get_post_type() && $text === 'Publish' ) {
			return 'Create Plan';
		}
		return $translation;
	}

	function add_meta_boxes( $post_type, $post ) {
		if ( $post_type !== $this->post_slug ) {
			return;
		}
		//let's remove ALL metaboxes but Publish
		global $wp_meta_boxes;
		if ( isset( $wp_meta_boxes[ $post_type ]['side']['core']['submitdiv'] ) ) {
			$submitdiv = $wp_meta_boxes[ $post_type ]['side']['core']['submitdiv'];
		}
		unset( $wp_meta_boxes[ $post_type ] );
		if ( isset( $submitdiv ) ) {
			$wp_meta_boxes[ $post_type ]['side']['core']['submitdiv'] = $submitdiv;
		}

		//check if Stripe plan was already created
		//if it was, some plan details can't be edited
		if ( get_post_meta( $post->ID, 'asp_sub_plan_created', true ) ) {
			$this->is_edit = true;
		}

		//let's also check if plan is variable
		$is_variable       = get_post_meta( $post->ID, 'asp_sub_plan_variables', true );
		$this->is_variable = empty( $is_variable ) ? false : true;

		//let's add our custom metaboxes for plan
		add_meta_box( 'asp_sub_plan_mode', __( 'Mode', 'asp-sub' ), array( $this, 'display_mode_meta_box' ), $this->post_slug, 'side', 'default' );
		add_meta_box( 'asp_sub_plan_price_and_currency', __( 'Price and Currency Settings', 'asp-sub' ), array( $this, 'display_plan_price_and_currency_meta_box' ), $this->post_slug, 'normal', 'default' );
		add_meta_box( 'asp_sub_plan_billing_interval_duration', __( 'Billing Interval and Duration Settings', 'asp-sub' ), array( $this, 'display_plan_billing_interval_duration_meta_box' ), $this->post_slug, 'normal', 'default' );
		add_meta_box( 'asp_sub_plan_trial', __( 'Trial Period Settings', 'asp-sub' ), array( $this, 'display_trial_meta_box' ), $this->post_slug, 'normal', 'default' );
		//hook to admin notices for errors display
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	function display_mode_meta_box( $post ) {
		if ( $this->is_edit || $this->is_variable ) {
			$is_plan_live = get_post_meta( $post->ID, 'asp_sub_plan_is_live', true );
			if ( $is_plan_live ) {
				echo __( 'Live mode', 'asp-sub' );
			} else {
				echo __( 'Test mode', 'asp-sub' );
			}
			echo sprintf( '<input type="hidden" name="asp_sub_plan_is_live" value="%d">', $is_plan_live ? 1 : 0 );
		} else {
			$ASPMain = AcceptStripePayments::get_instance();
			$is_live = $ASPMain->is_live;
			?>
<p>Select mode in which this plan will be created.</p>
<div style="font-size: 1.1em;font-weight: bold;">
	<label style="margin-right: 10px;"><input type="radio" name="asp_sub_plan_is_live" value="1"
			<?php echo $is_live ? ' checked' : ''; ?>> Live</label>
	<label><input type="radio" name="asp_sub_plan_is_live" value="0" <?php echo ! $is_live ? ' checked' : ''; ?>>
		Test</label>
</div>
			<?php
		}
		?>
<p><strong>Please note:</strong> Stripe doesn't let you edit "Mode" of an existing subscription plan. Create a new
	subscription plan if you need one for a different mode.</p>
		<?php
	}

	function display_plan_price_and_currency_meta_box( $post ) {
		//price
		$current_val = get_post_meta( $post->ID, 'asp_sub_plan_price', true );
		$variables   = get_post_meta( $post->ID, 'asp_sub_plan_variables', true );
		if ( empty( $variables ) ) {
			$variables = array();
		}
		if ( ! $current_val ) {
			$current_val = 0;
		}
		?>
<fieldset>
	<legend><?php echo __( 'Amount', 'asp-sub' ); ?></legend>
		<?php
		if ( ! $this->is_edit ) {
			?>
	<input type="text" name="asp_sub_plan_price" value="<?php echo $current_val; ?>">
	<p class="description">
			<?php echo __( 'Enter subscription price amount. This amount will be charged from the customers on every billing cycle.', 'asp-sub' ); ?>
		<br />
			<?php
			if ( ! $this->is_variable ) {
				echo __( 'Note: You won\'t be able to change this value once the plan is created and if plan is not variable.', 'asp-sub' );
			}
			?>
	</p>
	<label><input type="checkbox" name="asp_sub_plan_variables[price]" value="1"
			<?php echo isset( $variables['price'] ) ? ' checked' : ''; ?>>
			<?php _e( 'Make amount variable', 'asp-sub' ); ?></label>
	<p class="description"><?php _e( 'Enables your customers to enter their own amount.', 'asp-sub' ); ?></p>
			<?php
		} else {
			$curr = get_post_meta( $post->ID, 'asp_sub_plan_currency', true );
			echo AcceptStripePayments::formatted_price( $current_val, $curr );
		}
		?>
	<fieldset>
		<?php
		//currency
		$current_curr = get_post_meta( $post->ID, 'asp_sub_plan_currency', true );
		$variables    = get_post_meta( $post->ID, 'asp_sub_plan_variables', true );
		if ( empty( $variables ) ) {
			$variables = array();
		}

		?>
		<fieldset>
			<legend><?php echo __( 'Currency', 'asp-sub' ); ?></legend>
			<?php
			if ( ! $this->is_edit ) {
				?>
			<select name="asp_sub_plan_currency"
				id="asp_sub_plan_currency_select"><?php echo AcceptStripePayments_Admin::get_currency_options( $current_curr, false ); ?></select>
			<p class="description"><?php echo __( 'Select plan currency.', 'asp-sub' ); ?>
				<br />
				<?php
				if ( ! $this->is_variable ) {
					echo __( 'Note: You won\'t be able to change this value once the plan is created and if plan is not variable.', 'asp-sub' );
				}
				?>
			</p>
			<label><input type="checkbox" name="asp_sub_plan_variables[currency]" value="1"
					<?php echo isset( $variables['currency'] ) ? ' checked' : ''; ?>>
				<?php _e( 'Make currency variable', 'asp-sub' ); ?></label>
			<p class="description"><?php _e( 'Enables your customers to select currency.', 'asp-sub' ); ?></p>
				<?php
			} else {
				echo $current_curr;
			}
			?>
		</fieldset>
		<?php
		// setup fee
		?>
		<fieldset>
			<legend><?php echo __( 'Setup Fee', 'asp-sub' ); ?></legend>
			<?php
			//check if core plugin version >=2.0.23
			$core_min_ver = '2.0.23';
			if ( version_compare( WP_ASP_PLUGIN_VERSION, $core_min_ver . 't1' ) < 0 ) {
				//core version does not support setup fee
				// translators: %s is minimum core plugin version
				echo '<div style="color:red;">' . sprintf( esc_html__( 'Update Stripe Payments plugin to version %s that supports this functionality.', 'asp-sub' ), $core_min_ver ) . '</div>';
				return;
			}
				$current_val = get_post_meta( $post->ID, 'asp_sub_plan_setup_fee', true );
				$current_val = empty( $current_val ) ? 0 : $current_val;
			?>
			<input type="number" step="0.01" min="0" name="asp_sub_plan_setup_fee"
				value="<?php echo esc_attr( $current_val ); ?>">
			<p class="description">
				<?php echo __( 'Enter one-time setup fee which is paid during initial plan payment. Put "0" if you don\'t want to add setup fee.', 'asp-sub' ); ?>
			</p>
		</fieldset>
		<?php
	}

	function display_plan_billing_interval_duration_meta_box( $post ) {
		$current_period    = get_post_meta( $post->ID, 'asp_sub_plan_period', true );
		$curr_period_units = get_post_meta( $post->ID, 'asp_sub_plan_period_units', true );
		if ( ! $current_period ) {
			$current_period = 1;
		}
		$sel_opts = array(
			'days'   => __( 'days', 'asp-sub' ),
			'weeks'  => __( 'weeks', 'asp-sub' ),
			'months' => __( 'months', 'asp-sub' ),
		);
		?>
		<fieldset>
			<legend><?php echo __( 'Billing Interval', 'asp-sub' ); ?></legend>
			<?php
			if ( ! $this->is_edit && ! $this->is_variable ) {
				?>
			<input type="number" name="asp_sub_plan_period" style="vertical-align: middle; margin-right: 10px;"
				value="<?php echo $current_period; ?>">
			<select name="asp_sub_plan_period_units">
				<?php
				foreach ( $sel_opts as $val => $title ) {
					echo sprintf( '<option value="%s"%s>%s</option>', $val, ( $val === $curr_period_units ? ' selected' : '' ), $title );
				}
				?>
			</select>
			<p class="description">
				<?php echo __( 'Enter billing interval. Customers subscribed to this plan will be charged accordingly. For example: if you specify "30 days", it means the customer will be charged every 30 days.', 'asp-sub' ); ?>
				<?php echo __( 'Maximum billing interval cannot exceed 1 year (365 days or 52 weeks or 12 months).', 'asp-sub' ); ?>
				<br />
				<?php echo __( 'Note: You won\'t be able to change this value once the plan is created.', 'asp-sub' ); ?>
			</p>
				<?php
			} else {
				echo $current_period . ' ' . $curr_period_units;
			}
			?>
		</fieldset>
		<?php
		//duration
		?>
		<fieldset>
			<legend><?php echo __( 'Duration (Optional)', 'asp-sub' ); ?></legend>
			<?php
			$current_val = get_post_meta( $post->ID, 'asp_sub_plan_duration', true );
			if ( ! $current_val ) {
				$current_val = 0;
			}
			?>
			<input type="number" name="asp_sub_plan_duration" size="5" value="<?php echo $current_val; ?>">
			<p class="description">
				<?php echo __( 'Number of payments that will be charged before the customer\'s subscription is cancelled. Enter 0 if you want this plan\'s payment to continue until the subscription is canceled.', 'asp-sub' ); ?>
			</p>
		</fieldset>
		<?php
	}

	public function display_trial_meta_box( $post ) {
		$trial_period = get_post_meta( $post->ID, 'asp_sub_plan_trial', true );
		if ( ! $trial_period ) {
			$trial_period = 0;
		}
		$setup_fee = get_post_meta( $post->ID, 'asp_sub_plan_trial_setup_fee', true );
		if ( ! $setup_fee ) {
			$setup_fee = 0;
		}
		?>
		<fieldset>
			<legend><?php esc_html_e( 'Trial Duration', 'asp-sub' ); ?></legend>
			<input type="number" name="asp_sub_plan_trial" value="<?php echo esc_attr( $trial_period ); ?>">
			<span><?php esc_html_e( 'days', 'asp-sub' ); ?></span>
			<p class="description">
				<?php esc_html_e( 'If you include a trial period, the customers won\'t be charged until the trial period ends. Put "0" if you don\'t offer trial period for this plan.', 'asp-sub' ); ?>
			</p>
		</fieldset>
		<fieldset>
			<legend><?php esc_html_e( 'Trial Setup Fee', 'asp-sub' ); ?></legend>
			<?php
			//check if core plugin version >=2.0.25
			$core_min_ver = '2.0.25';
			if ( version_compare( WP_ASP_PLUGIN_VERSION, $core_min_ver . 't1' ) < 0 ) {
				//core version does not support setup fee
				// translators: %s is minimum core plugin version
				echo '<div style="color:red;">' . sprintf( esc_html__( 'Update Stripe Payments plugin to version %s that supports this functionality.', 'asp-sub' ), $core_min_ver ) . '</div>';
				return;
			} else {
				?>
			<input type="number" step="0.01" min="0" name="asp_sub_plan_trial_setup_fee"
				value="<?php echo esc_attr( $setup_fee ); ?>">
			<p class="description">
				<?php esc_html_e( 'Enter one-time setup fee which is paid on trial period start. Put "0" if you don\'t want to add setup fee for your trial period.', 'asp-sub' ); ?>
			</p>
				<?php
			}
			?>
		</fieldset>
		<?php
	}

	function save_plan_handler( $post_id, $post, $update ) {
		if ( ! isset( $_POST['action'] ) ) {
			//this is probably not edit or new post creation event
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( isset( $post_id ) ) {

			$is_live = intval( $_POST['asp_sub_plan_is_live'] ) === 1 ? true : false;

			$stripe_plans = new ASPSUB_stripe_plans( $is_live );
			$last_err     = $stripe_plans->get_last_error();
			if ( ! empty( $last_err ) ) {
				$this->display_errros[] = __( 'Stripe error occurred:', 'asp-sub' ) . ' ' . $last_err;
				return $this->has_errors( $post_id );
			}

			//check if this is variable plan
			$is_variable = false;

			if ( isset( $_POST['asp_sub_plan_variables'] ) ) {
				//this is variable plan
				$is_variable = true;
				$variables   = $_POST['asp_sub_plan_variables'];
				update_post_meta( $post_id, 'asp_sub_plan_is_variable', true );
				update_post_meta( $post_id, 'asp_sub_plan_variables', $_POST['asp_sub_plan_variables'] );
			}

			$trial = intval( $_POST['asp_sub_plan_trial'] );
			if ( ! is_int( $trial ) || ( $trial < 0 ) ) {
				$this->display_errros[] = __( 'Please specify valid trial interval.', 'asp-sub' );
			} else {
				update_post_meta( $post_id, 'asp_sub_plan_trial', $trial );
			}

			$duration = intval( $_POST['asp_sub_plan_duration'] );
			if ( ! is_int( $duration ) || ( $duration < 0 ) ) {
				$this->display_errros[] = __( 'Please specify valid duration.', 'asp-sub' );
			} else {
				update_post_meta( $post_id, 'asp_sub_plan_duration', $duration );
			}

			$setup_fee = FILTER_INPUT( INPUT_POST, 'asp_sub_plan_setup_fee', FILTER_SANITIZE_STRING );
			$setup_fee = floatval( $setup_fee );

			if ( isset( $setup_fee ) ) {
				update_post_meta( $post_id, 'asp_sub_plan_setup_fee', $setup_fee );
			}

			$trial_setup_fee = FILTER_INPUT( INPUT_POST, 'asp_sub_plan_trial_setup_fee', FILTER_SANITIZE_STRING );
			$trial_setup_fee = floatval( $trial_setup_fee );

			if ( isset( $trial_setup_fee ) ) {
				update_post_meta( $post_id, 'asp_sub_plan_trial_setup_fee', $trial_setup_fee );
			}

			// if this is post update, we won't be saving values below
			if ( ! get_post_meta( $post_id, 'asp_sub_plan_created', true ) ) {

				//let's check and validate values

				$title = get_the_title( $post_id );
				if ( empty( $title ) ) {
					$this->display_errros[] = __( 'Please specify plan title.', 'asp-sub' );
				}

				$currency = sanitize_text_field( $_POST['asp_sub_plan_currency'] );
				if ( empty( $currency ) ) {
					$this->display_errros[] = __( 'Please select plan currency.', 'asp-sub' );
				} else {
					update_post_meta( $post_id, 'asp_sub_plan_currency', $currency );
				}

				$price = floatval( $_POST['asp_sub_plan_price'] );
				if ( empty( $price ) || ! is_numeric( $price ) || $price < 0 ) {
					if ( ! isset( $variables['price'] ) ) {
						$this->display_errros[] = __( 'Please specify valid plan price.', 'asp-sub' );
					}
				} else {
					update_post_meta( $post_id, 'asp_sub_plan_price', $price );
				}

				$interval = filter_input( INPUT_POST, 'asp_sub_plan_period_units', FILTER_SANITIZE_STRING );
				if ( empty( $interval ) ) {
					if ( ! $is_variable ) {
						$this->display_errros[] = __( 'Please select valid interval.', 'asp-sub' );
					}
				} else {
					update_post_meta( $post_id, 'asp_sub_plan_period_units', $interval );
				}

				$interval_count = filter_input( INPUT_POST, 'asp_sub_plan_period', FILTER_SANITIZE_NUMBER_INT );
				$interval_count = absint( $interval_count );
				if ( empty( $interval_count ) || ! is_int( $interval_count ) || ( $interval_count < 0 ) ) {
					if ( ! $is_variable ) {
						$this->display_errros[] = __( 'Please specify valid interval.', 'asp-sub' );
					}
				} else {
					// translators: %1$d is number of days\weeks\months, %2$s is "days"\"weeks"\"years"
					$err_msg_tpl = __( 'Maximum billing interval cannot exceed %1$d %2$s', 'asp-sub' );
					switch ( $interval ) {
						case 'days':
							if ( $interval_count > 365 ) {
								$interval_error = sprintf( $err_msg_tpl, 365, __( 'days', 'asp-sub' ) );
							}
							break;
						case 'weeks':
							if ( $interval_count > 52 ) {
								$interval_error = sprintf( $err_msg_tpl, 52, __( 'weeks', 'asp-sub' ) );
							}
							break;
						case 'months':
							if ( $interval_count > 12 ) {
								$interval_error = sprintf( $err_msg_tpl, 12, __( 'months', 'asp-sub' ) );
							}
							break;
						default:
							break;
					}
					if ( isset( $interval_error ) ) {
						$this->display_errros[] = $interval_error;
					} else {
						update_post_meta( $post_id, 'asp_sub_plan_period', $interval_count );
					}
				}

				if ( ! $this->has_errors( $post_id ) ) {
					return false;
				}

				if ( ! $is_variable ) {

					//let's create pricing plan in Stripe
					$plan_opts = array(
						'currency'          => $currency,
						'interval'          => $interval,
						'interval_count'    => $interval_count,
						'amount'            => $price,
						'nickname'          => $title,
						'trial_period_days' => $trial,
					);

					$plan = $stripe_plans->create( $plan_opts, $post_id );

					if ( ! $plan ) {
						$this->display_errros[] = $stripe_plans->get_last_error();
						$this->has_errors( $post_id );
						return false;
					}
					update_post_meta( $post_id, 'asp_sub_plan_created', true );

					update_post_meta( $post_id, 'asp_sub_stripe_plan_id', $plan->id );
					update_post_meta( $post_id, 'asp_sub_stripe_plan_created', $plan->created );
					update_post_meta( $post_id, 'asp_sub_stripe_plan_prod_id', $plan->product );
				}
				update_post_meta( $post_id, 'asp_sub_plan_is_live', $is_live );
			} else {

				if ( ! $this->has_errors( $post_id ) ) {
					return false;
				}

				//update plan name and trial period
				$plan_opts = array(
					'nickname'          => get_the_title( $post_id ),
					'trial_period_days' => $trial,
				);

				$plan = $stripe_plans->update( $plan_opts, $post_id );

				if ( ! $plan ) {
					$this->display_errros[] = $stripe_plans->get_last_error();
					$this->has_errors( $post_id );
					return false;
				}
			}

			do_action( 'asp_sub_save_plan_handler', $post_id, $post, $update );
		}
	}

	function delete_plan_handler( $post_id ) {
		global $post_type;
		if ( $post_type === $this->post_slug && get_post_meta( $post_id, 'asp_sub_plan_created', true ) ) {
			$is_live      = get_post_meta( $post_id, 'asp_sub_plan_is_live', true );
			$stripe_plans = new ASPSUB_stripe_plans( $is_live );
			$stripe_plans->delete( $post_id );
		}
		return;
	}

	function admin_notices() {
		$errors = get_transient( 'asp_sub_err_msg' );
		if ( $errors && is_array( $errors ) ) {
			$class = 'notice notice-error';
			foreach ( $errors as $err ) {
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $err ) );
			}
			delete_transient( 'asp_sub_err_msg' );
			?>
		<style>
		div#message.updated.notice.notice-success.is-dismissible {
			display: none !important
		}
		</style>
			<?php
		}
	}

	function has_errors( $post_id = false ) {
		if ( isset( $this->display_errros ) ) {
			set_transient( 'asp_sub_err_msg', $this->display_errros );
			if ( $post_id ) {
				// unhook this function to prevent infinite loop
				remove_action( 'save_post_' . $this->post_slug, array( $this, 'save_plan_handler' ) );
				// update the post to change post status
				wp_update_post(
					array(
						'ID'          => $post_id,
						'post_status' => 'draft',
					)
				);
				// re-hook this function again
				add_action( 'save_post_' . $this->post_slug, array( $this, 'save_plan_handler' ), 10, 3 );
			}
			return false;
		}
		return true;
	}

}
