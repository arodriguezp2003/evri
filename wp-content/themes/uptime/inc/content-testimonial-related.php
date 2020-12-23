<?php 
	if(!( 'testimonial' == get_post_type() )){
		return false;
	}
	
	$related_query = new WP_Query( 
		array(
			'post_type' 	      => 'testimonial',
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => 1,
			'orderby'             => 'rand',
			'post__not_in'        => array( get_the_id() )
		) 
	);
	
	if( $related_query->found_posts == 0 ){
		return false;
	}
?> 

<section class="bg-primary-alt">
  	<div class="container">
    	<div class="row mb-4">
      		<div class="col">
        		<h3 class="h2"><?php esc_html_e( 'Related Stories', 'uptime' ); ?></h3>
      		</div>
		</div>

    	<div class="row">
	      	<?php 

		        if ( $related_query->have_posts() ) : while ( $related_query->have_posts() ) : $related_query->the_post(); 

		        	$logo = get_post_meta( $post->ID, '_tommusrhodus_testimonial_logo_id', 1 ); ?>
		          	
		          	<div class="col-md-6 col-lg-4 d-flex">
						<div class="card card-customer-1 card-body align-items-start">
							<div class="mb-4 mb-md-5">
								<?php if( !empty( $logo ) ) : ?>
									<?php echo wp_get_attachment_image( $logo, 'large', 0, array( 'class' => 'icon' ) ); ?>								
								<?php endif; ?>
							</div>
							
							<div class="mb-3 mb-md-4">
								<?php the_title( '<h4>', '</h4>' ); ?>
							</div>
		              		<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Story', 'uptime' ); ?></a></div>
					</div>

          		<?php
		          
		        endwhile; 
		        else : 
		          
		          /**
		           * Display no posts message if none are found.
		           */
		          get_template_part( 'loop/content', 'none' );
		          
		        endif;
		        wp_reset_postdata();

	      	?>
	    </div>
	</div>
</section>
