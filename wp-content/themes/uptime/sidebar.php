<?php if( is_active_sidebar( 'primary' ) ) : ?>
	<div class="col-md-4 col-lg-3 d-none d-md-block sidebar">
		<?php dynamic_sidebar( 'primary' ); ?>
	</div>
<?php endif;