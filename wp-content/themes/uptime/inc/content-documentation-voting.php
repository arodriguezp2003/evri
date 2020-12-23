<?php
	$upvotes     = get_post_meta( $post->ID, 'tommusrhodus_docs_upvotes', 1 );
	$downvotes   = get_post_meta( $post->ID, 'tommusrhodus_docs_downvotes', 1 );
	
	if( false == $upvotes ){
		$upvotes = '0';
	}
	
	if( false == $downvotes ){
		$downvotes = '0';
	}

	$totalvotes = $upvotes + $downvotes;
?>

<section class="p-0" >
	<div class="container">
		
		<div class="pt-4 pb-3 text-center">
			
			<h6><?php esc_html_e( 'Did you find this article helpful?', 'uptime' ); ?></h6>
			
			<div class="d-flex justify-content-center mb-2">
				
				<a href="#" class="btn btn-outline-primary btn-upvote mx-1" data-id="<?php echo esc_attr( get_the_id() ); ?>">
					
					<svg class="icon bg-primary" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18.1206 5.4111C18.5021 4.92016 19.1753 4.86046 19.6241 5.27776C20.073 5.69506 20.1276 6.43133 19.746 6.92227L10.6794 18.5889C10.2919 19.0876 9.60523 19.1401 9.15801 18.7053L4.35802 14.0386C3.91772 13.6106 3.87806 12.8732 4.26944 12.3916C4.66082 11.91 5.33503 11.8666 5.77533 12.2947L9.76023 16.1689L18.1206 5.4111Z" fill="#212529"></path>
					</svg>
			
					<span class="btn-span"><?php esc_html_e( 'Yes', 'uptime' ); ?></span>
					
				</a>
				
				<a href="#" class="btn btn-outline-primary btn-downvote mx-1" data-id="<?php echo esc_attr( get_the_id() ); ?>">
					
					<svg class="icon bg-primary" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M16.2426 6.34311L6.34309 16.2426C5.95257 16.6331 5.95257 17.2663 6.34309 17.6568C6.73362 18.0473 7.36678 18.0473 7.75731 17.6568L17.6568 7.75732C18.0473 7.36679 18.0473 6.73363 17.6568 6.34311C17.2663 5.95258 16.6331 5.95258 16.2426 6.34311Z" fill="#212529"></path>
						<path d="M17.6568 16.2426L7.75734 6.34309C7.36681 5.95257 6.73365 5.95257 6.34313 6.34309C5.9526 6.73362 5.9526 7.36678 6.34313 7.75731L16.2426 17.6568C16.6331 18.0473 17.2663 18.0473 17.6568 17.6568C18.0474 17.2663 18.0474 16.6331 17.6568 16.2426Z" fill="#212529"></path>
					</svg>
			
					<span class="btn-span"><?php esc_html_e( 'No', 'uptime' ); ?></span>
				
				</a>
				
			</div>
			
			<div class="text-small text-muted mb-3 mb-md-6">
				<span data-js-upvote-count="<?php echo esc_attr( $upvotes ); ?>"><?php echo esc_html( $upvotes ); ?></span> <?php esc_html_e( 'out of', 'uptime' ); ?> 
				<span data-js-downvote-count="<?php echo esc_attr( $downvotes ); ?>" ><?php echo esc_html( $totalvotes ); ?></span> <?php esc_html_e( 'found this helpful', 'uptime' ); ?>
			</div>
			
			<div>
				<span><?php echo get_theme_mod( 'documentation_single_support_text', 'Still have questions? <a href="#">Open a Support Ticket</a>' ); ?></span>
			</div>
			
		</div>

	</div>
</section>