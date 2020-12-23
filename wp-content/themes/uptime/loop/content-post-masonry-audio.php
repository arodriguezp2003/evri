<?php $link = get_post_meta( $post->ID, '_tommusrhodus_link_format_url', 1 ); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-6 col-lg-4' ); ?> data-isotope-item>
	<div class="card card-body justify-content-between bg-primary-2 text-light">
	
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
				<?php echo tommusrhodus_svg_icons_pluck( 'Volume Full', 'icon' ); ?>
			</a>
			
			<div class="ml-3">
				<?php the_title( '<a href="'. get_permalink() .'"><h4 class="mb-1">', '</h4></a>' );  ?>
			</div>
		
		</div>
	
	</div>
</div>
