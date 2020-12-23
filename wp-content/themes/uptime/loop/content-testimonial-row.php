<?php $logo = get_post_meta( $post->ID, '_tommusrhodus_testimonial_logo_id', 1 ); ?>

<div id="testimonial-<?php the_ID(); ?>" <?php post_class( 'col layer-1' ); ?>>
	<a href="<?php the_permalink(); ?>" class="card card-article-wide flex-md-row no-gutters hover-shadow-3d">
		
		<div class="col-md-5 col-lg-6">
			<?php the_post_thumbnail( 'large', array( 'class' => 'card-img-top' ) ); ?>
		</div>
		
		<div class="card-body d-flex flex-column justify-content-between col-auto p-4 p-lg-5">
			
			<div>
				<?php 
					if( !empty( $logo ) ){
						echo wp_get_attachment_image( $logo, 'large', 0, array( 'class' => 'icon icon-md mb-4' ) );
					}

					the_title( '<div class="h3">', '</div>' ); 
				?>
			</div>
			
			<p class="lead mb-0 text-primary font-weight-bold"><?php esc_html_e( 'Read Story', 'uptime' ); ?></p>
			
		</div>
		
	</a>
</div>