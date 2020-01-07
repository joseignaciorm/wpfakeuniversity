<?php get_header(); 
    while(have_posts()) {
        the_post(); 

        pageBanner();
        ?>
  <div class="container container--narrow page-section">
    <?php

        # get_the_ID(); // Devuelve el ID de página actual. Current page ID.
        # wp_get_post_parent_id(); // Devuelve el ID del padre de la page id pasada como parámetro.
        # Devuelve 0 si página actual no tiene padre, es decir, no es una child page.
        $theParent = wp_get_post_parent_id(get_the_ID());
        if( $theParent ) : ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
            </div>
        <?php endif; ?>

    <?php 
      # get_pages() a diferencia de wp_list_pages(), returna las páginas en memoria. En cambio wp_list_pages() las saca en pantalla.
      $testArray = get_pages(array(
        'child_of' => get_the_ID(), // Id de los hijos de current page. Si no tiene hijos, returnará valor 0 or not or false
      ));
      if( $theParent or $testArray) : ?>

      <div class="page-links">
            <?php 
              # Variable $theParent es igual a 0 cuando es la current page.
            ?>
        <h2 class="page-links__title"><a href="<?php echo get_the_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
        <ul class="min-list">
          <?php 
            if ( $theParent ) : // Si current page tiene padre, obtener el id
              $findChildrenOf = $theParent;
            else : // Entonces esta en página padre y guardamos id de current parent page
              $findChildrenOf = get_the_ID();
            endif; 
            wp_list_pages(array(
              'title_li'  => NULL,
              'child_of'  => $findChildrenOf, // Obtiene id de los hijos del id padre pasado como valor
              'sort_column' => 'menu_order'
            ));          
          ?>
        </ul>
      </div>

    <?php endif; ?>

    <div class="generic-content">
        <form class="search-form" method="get" action="<?php echo esc_url(site_url('/')); ?>">
            <label class="headline headline--medium" for="s">Performe a new Search</label>
            <div class="search-form-row">
              <input placeholder="What are you looking for?" class="s" id="s" type="search" name="s">
              <input class="search-submit" type="submit" value="Search">
            </div>
      </form>
    </div>

  </div>

    <?php } ?>



<?php get_footer(); ?>