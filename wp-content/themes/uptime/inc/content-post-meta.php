<div class="mr-2">
	
	<?php 
		if( $categories = get_the_category() ){
			foreach( $categories as $cat ){
				echo '<span class="mr-2 post-category">'. esc_html( $cat->name ) .'</span>';
			}
		}
	?>
	
	<span class="text-muted">
		<?php the_time( get_option( 'date_format' ) ); ?>
	</span>

</div>


