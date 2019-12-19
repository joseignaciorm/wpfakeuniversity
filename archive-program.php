<?php get_header() ?>
<?php pageBanner(array(
  'title' => 'All programs',
  'subtitle' => 'There is something for everyone. Have a look around.'
)) ?>

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