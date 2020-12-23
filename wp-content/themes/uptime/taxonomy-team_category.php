<?php 
	get_header(); 
	
	// Team Specific Hero Area
	get_template_part( 'inc/content-team', 'hero' );	
?>

<section>
	<div class="container">
		<?php get_template_part( 'loop/loop-team', get_theme_mod( 'team_layout', '4-columns' ) ); ?>
	</div>
</section>

<section class="pt-0">
	<div class="container">
		<?php get_template_part( 'inc/content', 'pagination' ); ?>
	</div>
</section>

<?php get_footer();