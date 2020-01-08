<?php get_header() ?>
<?php pageBanner(array(
  'title' => 'Search Results',
  'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query(false)) . '&rdquo;'
)) ?>

  <div class="container container--narrow page-section">
  <?php 
    
    if ( have_posts() ) {
        while( have_posts() ) :
            the_post(); 
              get_template_part( 'template-parts/content', get_post_type() );
        endwhile;  
            echo paginate_links();
          
    } else {
        echo '<h2 class="headline headline--small-plus">No results match that search.</h2>';
        echo get_search_form(); // Enlaza a searchform.php y si no esta creada esta file, pinta form por defecto de WP
    }
    ?> 
  </div>

<?php get_footer() ?>