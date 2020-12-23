<?php
/**
 * Job listing in the loop.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @since       1.0.0
 * @version     1.27.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
?>

<a href="<?php the_job_permalink(); ?>" class="list-group-item list-group-item-action row no-gutters py-3" data-longitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_long ); ?>">
	
	<div class="col-xl-8 col-lg-7 col-md-6">
		<h5 class="mb-0"><?php wpjm_the_job_title(); ?></h5>
	</div>
	
	<div class="col-md">
		<span><?php if ( get_option( 'job_manager_enable_types' ) ) { ?>
			<?php $types = wpjm_get_the_job_types(); ?>
			<?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
				<?php echo esc_html( $type->name ); ?> 
			<?php endforeach; endif; ?>
		<?php } ?></span>
	</div>
	
	<div class="col-md">
		<span><?php the_job_location( false ); ?></span>
	</div>
	
</a>