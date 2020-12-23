<?php $subtitle = get_post_meta( $post->ID, '_tommusrhodus_porfolio_item_subtitle', 1 ); ?>

<section class="bg-primary-3 has-divider text-light">

	<div class="container pb-md-0">
	
		<div class="row justify-content-center text-center mb-6">
			<div class="col-xl-8 col-lg-9 col-md-10">
				
				<?php 

				if( $subtitle ) {
					echo '<h5 class="mb-4">'. $subtitle .'</h5>';
				}

				the_title( '<h1 class="display-4 mb-4">', '</h1>' );
						
					if( has_excerpt() ){
						echo '<div class="lead">';
						the_excerpt();
						echo '</div>';
					}
				?>

			</div>
		</div>
		
		<?php if( has_post_thumbnail() ) : ?>
			
			<div class="row justify-content-center mb-lg-n7">
				<div class="col-xl-10 layer-1 text-center">
					<?php the_post_thumbnail( 'large', array( 'class' => 'rounded' ) ); ?>
				</div>
			</div>
		
		<?php endif; ?>
		
	</div>
	
	<div class="divider d-none d-md-block">
		<svg width="100%" height="96px" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none">
			<path d="M0,0 C16.6666667,66 33.3333333,99 50,99 C66.6666667,99 83.3333333,66 100,0 L100,100 L0,100 L0,0 Z"></path>
		</svg>
	</div>

</section>
    
<section class="pt-4 pb-0">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-xl-10">
				<article class="article article-portfolio">
					<?php
						the_content();
						wp_link_pages();
					?>
				</article>
			</div>
		</div>
	</div>
</section>

<?php 
	// Standard Comments and Sharing
	comments_template();