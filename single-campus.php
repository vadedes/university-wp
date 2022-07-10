<?php
  
  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>



<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <!-- use this function for WP to automatically get the post type event link in case we change the post type slug later on from one place and all links should be updated automatically -->
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i
                    class="fa fa-home" aria-hidden="true"></i> All Campuses </a>
            <span class="metabox__main"><?php the_title() ?></span>
        </p>
    </div>

    <div class="generic-content">
        <?php the_content(); ?>
    </div>

    <div class='acf-map'>
        <?php 
            $mapLocation = get_field('map_location');
        ?>
        <div class='marker' data-lat='<?php echo $mapLocation['lat'] ?>' data-lng='<?php echo $mapLocation['lng'] ?>'>
            <h3><?php the_title(); ?></h3>
            <?php echo $mapLocation['address']; ?>
        </div>
    </div>

    <?php
    
            $relatedPrograms = new WP_Query(array(
                'posts_per_page' => -1, //-1 means pull all related professors
                'post_type' => 'program',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'related_campus',
                        'compare' => 'LIKE', //LIKE means 'contains' in php
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            ));
            if($relatedPrograms->have_posts()){
            echo '<hr class="section-break">';
            echo '<h3 class="headline headline--medium">Programs Available At This Campus</h3>';

        echo '<ul class="min-list link-list">';
                while($relatedPrograms->have_posts()) {
                    $relatedPrograms->the_post(); ?>
    <li>
        <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
    </li>
    <?php }
        echo '</ul>';
            }
            wp_reset_postdata();
        ?>

</div>

<?php }

  get_footer();

?>