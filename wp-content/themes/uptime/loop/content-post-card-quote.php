<?php $author = get_post_meta( $post->ID, '_tommusrhodus_quote_format_author', 1 ); ?>

<div class="col-md-6 col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $wp_query->current_post + 1 ); ?>00">
	<div class="card card-body justify-content-between bg-primary-2 text-light o-hidden">
	
		<div class="d-flex justify-content-between mb-3">
			<div class="text-small d-flex">
				<?php get_template_part( 'inc/content-post', 'meta' ); ?>
			</div>
		</div>
		
		<div>
			<?php 
				the_title( '<h2>', '</h2>' ); 
				echo '<span class="text-small opacity-70">'. esc_html( $author ) .'</span>'; 
			?>
		</div>
	
	</div>
</div>