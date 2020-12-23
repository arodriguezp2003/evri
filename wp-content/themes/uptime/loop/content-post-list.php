<li class="row row-tight">

	<?php if( has_post_thumbnail() ) : ?>
	
		<a href="<?php the_permalink(); ?>" class="col-3 col-md-4">
			<?php the_post_thumbnail( 'tommusrhodus-square', array( 'class' => 'rounded' ) ); ?>
		</a>
	
	<?php endif; ?>

	<div class="col d-flex flex-column justify-content-between">
		<div>
			
			<?php the_title( '<a href="'. get_permalink() .'"><h6 class="mb-1">', '</h6></a>' ); ?>
			
			<div class="d-flex text-small">
				<?php the_category( ', ' ); ?>
				<span class="text-muted ml-1"><?php the_time( get_option( 'date_format' ) ); ?></span>
			</div>
			
		</div>
	</div>

</li>