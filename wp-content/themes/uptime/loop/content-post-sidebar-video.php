<?php $author = get_post_meta( $post->ID, '_tommusrhodus_quote_format_author', 1 ); ?>

<div class="pr-lg-4">
	<div class="card card-body justify-content-between bg-primary-3 text-light">
	 	
	 	<?php 
	 		if( has_post_thumbnail() ){
	 			the_post_thumbnail( 'large', array( 'class' => 'bg-image opacity-20' ) );
	 		}
	 	?>
		
		<div class="d-flex justify-content-between mb-3 position-relative">
			<div class="text-small d-flex">
				<?php get_template_part( 'inc/content-post', 'meta' ); ?>
          	</div>
      	</div>		
	  	
		<div class="d-flex position-relative">
		 	
		 	<a href="<?php the_permalink(); ?>" class="btn btn-primary btn-round btn-lg flex-shrink-0">
				<?php echo tommusrhodus_svg_icons_pluck( 'Play', 'icon bg-white' ); ?>	
			</a>
			
			<div class="ml-3">
				<?php 
				the_title( '<h4 class="mb-1">', '</h4>' ); 
					echo '<span class="text-small opacity-70">'. $author .'</span>'; 
				?>
			</div>
			
		</div>
		
	</div>
</div>
