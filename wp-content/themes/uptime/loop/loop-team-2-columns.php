<div class="row mb-3">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<?php
			$job_title = get_post_meta( $post->ID, '_tommusrhodus_the_job_title', 1 );
			$job_label = get_post_meta( $post->ID, '_tommusrhodus_the_label', 1 );
			
			if( !empty( $job_title ) ){
				$job_title = '<span>'. $job_title .'</span>';
			}
			
			if( !empty( $job_label ) ){
				$job_label = '<span class="badge badge-primary badge-top">'. $job_label .'</span>';
			}
		?>
		
		<div id="team-<?php the_ID(); ?>" <?php post_class( 'col-xl-6 col-lg-6 col-md-6' ); ?> data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $wp_query->current_post + 1 ); ?>00">
			<div class="card card-lg card-body align-items-center">
				<?php 
					echo wp_kses_post( $job_label );
					the_post_thumbnail( 'large', array( 'class' => 'avatar avatar-xlg mb-3' ) );
					the_title( '<h5 class="mb-0">', '</h5>' . $job_title ); 
				?>
			</div>
		</div>
			
	<?php
		endwhile;	
		else : 
			
			get_template_part( 'loop/content', 'none' );
			
		endif;
	?>
</div>