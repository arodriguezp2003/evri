<?php
	get_header();
	the_post();
	
	$logo       = get_post_meta( $post->ID, '_tommusrhodus_testimonial_logo_id', 1 );
	$additional = get_post_meta( $post->ID, '_tommusrhodus_meta_repeat_group', true ); 
	
	// Reading Progress Counter
	get_template_part( 'inc/content-post', 'progress' );
?>

<section class="bg-primary-alt header-inner">

	<div class="container">
		
		<div class="row my-3">
			<div class="col">
				<?php echo get_tommusrhodus_breadcrumbs(); ?>
			</div>
		</div>
		
		<?php if( !empty( $logo ) ) : ?>
		
			<div class="row justify-content-center my-4">
				<div class="col-auto">
					<?php echo wp_get_attachment_image( $logo, 'large', 0, array( 'class' => 'icon icon-lg' ) ); ?>
				</div>
			</div>
		
		<?php endif; ?>
		
		<div class="row justify-content-center text-center">
			<div class="col-lg-9 col-xl-8">
				<?php the_title( '<h1>', '</h1>' ); ?>
			</div>
		</div>
		
		<div class="row my-6 justify-content-between">
			
			<div class="col-lg-7 col-xl-8 mb-3 mb-lg-0">
				<?php the_post_thumbnail( 'large', array( 'class' => 'rounded' ) ); ?>
			</div>
			
			<?php if( !empty( $additional ) ) : ?>
			
				<div class="col-lg-4 col-xl-3">
					<?php 
						foreach( $additional as $index => $item ){
						
							echo '<div class="mb-3">';
							
							if( isset( $item['meta_title'] ) ){
								echo '<h6 class="mb-1">'. wp_kses_post( $item['meta_title'] ) . '</h6>';
							}

							if( isset( $item['meta_detail'] ) ){
								echo wpautop( wp_kses_post( $item['meta_detail'] ) );
							}
								
							echo '</div>';
						}
					?>
				</div>
			
			<?php endif; ?>
			
		</div>
		
	</div>

	<?php echo tommusrhodus_svg_dividers_pluck( 'ramp', '' ); ?>

</section>
    
<?php 
	// Standard Post Content
	get_template_part( 'inc/content' ); 
	
	// Standard Comments and Sharing
	comments_template();
	
	// Testimonial Specific Related Posts
	get_template_part( 'inc/content-testimonial', 'related' );
	
	get_footer();