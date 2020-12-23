<?php 
	$id  = get_the_author_meta( 'ID' ); 
	$url = get_author_posts_url( $id );
?>

<div class="d-flex align-items-center">
	
	<a href="<?php echo esc_url( $url ); ?>">
		<?php echo get_avatar( $id, 48, false, false, array( 'class' => 'avatar mr-2' ) ); ?>
	</a>
	
	<div>
		
		<div>
			<?php esc_html_e( 'by', 'uptime' ); ?> 
			<a href="<?php echo esc_url( $url ); ?>"><?php echo get_the_author(); ?></a>
		</div>
		
		<div class="text-small text-muted"><?php the_time( get_option( 'date_format' ) ); ?></div>
		
	</div>
	
</div>