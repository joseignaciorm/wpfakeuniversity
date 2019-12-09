<?php get_header() ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);">
    </div>
    <div class="page-banner__content container container--narrow">

      <h1 class="page-banner__title">
        All programs
        <?php 
        /* if(is_category()) {
            echo single_cat_title();
        } if(is_author()) {
            echo "Posts by "; the_author();
        } */
        ?>

      </h1>

      <div class="page-banner__intro">
        <p>There is something for everyone. Have a look around.</p>
      </div>
    </div>  
  </div>

  <div class="container container--narrow page-section">
  <ul class="link-list min-list">
    <?php 
        while( have_posts() ) :
        the_post(); ?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php  endwhile;  
        # En general la paginación en WP solo funciona fuera del box con la query por defecto el la current url
        echo paginate_links();
        ?>
    </ul>
  </div>

<?php get_footer() ?>