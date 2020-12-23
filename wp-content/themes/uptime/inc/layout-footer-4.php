<footer class="bg-primary-alt">
  <div class="container">

    <div class="row justify-content-between">

      <?php
        if( is_active_sidebar('short_footer_1') && !( is_active_sidebar('short_footer_2') ) ){
          echo '<div class="col">';
            dynamic_sidebar('short_footer_1');
          echo '</div>';
        }
          
        if( is_active_sidebar('short_footer_2') && !( is_active_sidebar('short_footer_3') ) && !( is_active_sidebar('short_footer_4') ) ){
          echo '<div class="col d-flex flex-column align-items-center align-items-md-start">';
            dynamic_sidebar('short_footer_1');
          echo '</div><div class="col-lg-5 col-md-6 mt-3 mt-lg-0">';
            dynamic_sidebar('short_footer_2');
          echo '</div><div class="clear"></div>';
        }
      ?>
      
    </div>

    <div class="row justify-content-between">

      <div class="col d-flex flex-column align-items-center align-items-md-start mt-2">
        <?php get_template_part( 'inc/content', 'copyright' );  ?>
      </div>

      <div class="d-flex justify-content-center justify-content-md-end">
        <?php get_template_part( 'inc/content', 'footer-social-bg-secondary' );  ?>
      </div>

    </div>

  </div>
</footer>