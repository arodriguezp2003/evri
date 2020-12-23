<?php 
	$categories = get_the_category(); 
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'carousel-cell bg-dark text-light overlay min-vh-70 d-flex flex-column justify-content-end' ); ?>>

	<?php if( has_post_thumbnail() ) : ?>
		
		<?php the_post_thumbnail( 'large', array( 'class' => 'bg-image opacity-80' ) ); ?>

	<?php endif; ?>
	
	<div class="container">
		<div class="row pb-6">
			<div class="col">
				<a href="<?php the_permalink(); ?>">

					<?php
				        if( is_array( $categories ) ){
				          	foreach( $categories as $cat ){
				              	echo '<span class="badge badge-primary">'. $cat->name . '</span>';
				          	}
				        }
				    ?>
					
					<?php the_title( '<h1 class="mt-3">', '</h1>' ); ?>
					
					<div class="d-flex align-items-center">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, false, false, array( 'class' => 'avatar mr-2' ) ); ?>    
						
						<div>
							<div class="flex-shrink-0"><?php esc_html_e( 'by', 'uptime' ); ?> <?php echo get_the_author_meta( 'display_name' ); ?></div>
							
							<div class="text-small text-muted"><?php the_time( get_option( 'date_format' ) ); ?></div>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>

</div>