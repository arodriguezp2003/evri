<?php 
	get_header(); 
	the_post();
?>

<section>
	<div class="container">
		<?php the_content(); ?>
	</div>
</section>

<?php get_footer();