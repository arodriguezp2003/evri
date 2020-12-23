<?php 
	$hero_layout = get_post_meta( $post->ID, '_tommusrhodus_post_single_hero_layout_override', 1 );
	if(!( '' == $hero_layout || false == $hero_layout || 'none' == $hero_layout )){
		$hero_layout = get_theme_mod( 'post_single_hero_layout' , 'basic' );
	}
	
	$format         = get_post_format(); 
	$video_provider = get_post_meta( $post->ID, '_tommusrhodus_video_provider', 1 );
	$video_url      = get_post_meta( $post->ID, '_tommusrhodus_video_url', 1 );
?>

<section class="p-0" data-reading-position>
	<div class="container">
		
		<?php if( 'video' == $format ) : ?>
			
			<div class="row justify-content-center position-relative">
				<div class="col-lg-10 col-xl-8">
					<div class="rounded o-hidden">
						<div class="plyr" data-plyr-provider="<?php echo esc_attr( $video_provider ); ?>" data-plyr-embed-id="<?php echo esc_attr( $video_url ); ?>"></div>
					</div>
				</div>
			</div>
			
		<?php elseif( has_post_thumbnail() && 'basic' == $hero_layout ) : ?>
		
			<div class="row justify-content-center position-relative post-thumbnail-wrapper">
				<div class="col-lg-10 col-xl-8">
					<?php the_post_thumbnail( 'large', array( 'class' => 'rounded' ) ); ?>
				</div>
			</div>
		
		<?php endif; ?>
		
		<div class="row justify-content-center">
			<div class="col-lg-10 col-xl-8">
				<article class="article">
					<?php
						the_content();
						wp_link_pages();
						the_tags( '', '', '' );
					?>
				</article>
			</div>
		</div>
		
	</div>
</section>