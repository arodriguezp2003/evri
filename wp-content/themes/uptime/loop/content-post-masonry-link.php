<?php $link = get_post_meta( $post->ID, '_tommusrhodus_link_format_url', 1 ); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-6 col-lg-4' ); ?> data-isotope-item>
	<a href="<?php echo esc_url( $link ); ?>" class="card card-body justify-content-between bg-primary text-light">
	
		<div class="d-flex justify-content-between mb-3">
			
			<div class="text-small d-flex">
				<?php get_template_part( 'inc/content-post', 'meta' ); ?>
			</div>
			
		</div>
		
		<div>
			<?php 
				the_title( '<h2>', '</h2>' ); 
				echo '<span class="text-small opacity-70">'. esc_url( $link ) .'</span>';
			?>
		</div>
	
	</a>  
</div>