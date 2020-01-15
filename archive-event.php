<?php get_header() ?>
<?php pageBanner(array(
    'title' => 'All events',
    'subtitle' => 'See what is going on in our world!'
  )); ?>

<?php 
        /* if(is_category()) {
            echo single_cat_title();
        } if(is_author()) {
            echo "Posts by "; the_author();
        } */
        ?>

  <div class="container container--narrow page-section">
  <?php 
    
    while( have_posts() ) :
      the_post(); 
      echo 'hola';
        get_template_part('template-parts/content-event');
      endwhile;  
    # En general la paginación en WP solo funciona fuera del box con la query por defecto en la current url
      echo paginate_links();
    ?>

      <hr class="section-break">
      
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events'); ?>">Check out our past events archive</a></p>
  </div>

<?php get_footer() ?>