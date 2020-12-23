<?php
/**
 * Content shown before job listings in `[jobs]` shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-listings-start.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.15.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="row no-gutters mb-3 d-none d-md-flex">
	<div class="col-xl-8 col-lg-7 col-md-6"><span class="text-small text-muted"><?php esc_html_e( 'Position', 'uptime' ); ?></span></div>
	
	<div class="col"><span class="text-small text-muted"><?php esc_html_e( 'Team', 'uptime' ); ?></span></div>
	
	<div class="col"><span class="text-small text-muted"><?php esc_html_e( 'Location', 'uptime' ); ?></span></div>
</div>

<div class="list-group list-group-flush job_listings">