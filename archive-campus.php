<?php get_header() ?>
<?php pageBanner(array(
  'title' => 'Our Campus',
  'subtitle' => 'We have several conveniently located campuses.'
)) ?>

  <div class="container container--narrow page-section">
  <div class="acf-map">
  <ul class="link-list min-list">
    <?php 
        while( have_posts() ) :
        the_post(); ?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title();
          $mapLocation = get_field('map_location');
          var_dump($mapLocation);
          ?></a>
        </li>
        <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng']; ?>"></div>
        </div>
        <?php  endwhile;  
        # En general la paginación en WP solo funciona fuera del box con la query por defecto el la current url
        echo paginate_links();
        ?>
    </ul>
    </div>
  </div>

<?php get_footer() ?>