<form class="d-flex searchform" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	
	<div class="input-group input-group-lg mr-2">
		
		<input name="s" type="text" class="form-control" placeholder="<?php esc_attr_e( 'Search', 'uptime' ); ?>" aria-label="Search">
		
	</div>
	
	<button class="btn btn-primary btn-lg" type="submit"><?php esc_html_e( 'Search', 'uptime' ); ?></button>
	
</form>