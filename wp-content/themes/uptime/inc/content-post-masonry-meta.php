<div class="d-flex justify-content-between mb-3">
	
	<div class="text-small d-flex">
		<?php
			if( is_array( $categories ) ){
				foreach( $categories as $cat ){
					echo '<div class="mr-2">'. $cat->name . '</div>';
				}
			}
		?>
        <span class="opacity-70"><?php the_time( get_option( 'date_format' ) ); ?></span>
	</div>
	
</div>