<?php $logo = get_post_meta( $post->ID, '_tommusrhodus_testimonial_logo_id', 1 ); ?>

<div id="testimonial-<?php the_ID(); ?>" <?php post_class( 'col-md-6 col-lg-4' ); ?> data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $wp_query->current_post + 1 ); ?>00">
	<div class="card">
		
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'large', array( 'class' => 'card-img-top' ) ); ?>
        </a>
		
		<div class="card-body align-items-start">
			
			<?php if( !empty( $logo ) ) : ?>
			
				<div class="mb-3">
					<?php echo wp_get_attachment_image( $logo, 'large', 0, array( 'class' => 'icon icon-md' ) ); ?>
				</div>
				
			<?php endif; ?>
			
			<?php the_excerpt(); ?>
			
            <a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Story', 'uptime' ); ?></a>
			
		</div>
		
	</div>
</div>