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
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i
                    class="fa fa-home" aria-hidden="true"></i> All Programs </a>
            <span class="metabox__main"><?php the_title() ?></span>
        </p>
    </div>

    <div class="generic-content">
        <?php the_content(); ?>


        <!-- goal for below query is to show only events that has reference with the current program -->
        <?php
            //query and output for related professors
            $relatedProfessors = new WP_Query(array(
                'posts_per_page' => -1, //-1 means pull all related professors
                'post_type' => 'professor',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE', //LIKE means 'contains' in php
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            ));
            //set condition to only show related events if there are any
            if($relatedProfessors->have_posts()){
            echo '<hr class="section-break">';
            //headline for related events -> this is the method to concat strings with function in between
            echo '<h3 class="headline headline--medium">' . get_the_title() . ' Professors</h3>';

        echo '<ul class="professor-cards">';
            //loop thru events and show only 2 events
                while($relatedProfessors->have_posts()) {
                    $relatedProfessors->the_post(); ?>
        <li class="professor-card__list-item">
            <a class="professor-card" href="<?php the_permalink() ?>">
                <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
                <span class="professor-card__name"><?php the_title(); ?></span>
            </a>
        </li>
        <?php }
        echo '</ul>';
            }
            wp_reset_postdata();//this reset function is so important for multiple queries to work
            // query and output for related programs
            $today = date('Ymd'); //variable to get present date
            $homepageEvents = new WP_Query(array(
                'posts_per_page' => 2,
                'post_type' => 'event',
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'event_date', //event date
                        'compare' => '>=', //condition symbol
                        'value' => $today, //get present date variable declared above
                        'type' => 'numeric' //specified type of output value
                    ),
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE', //LIKE means 'contains' in php
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            ));
            //set condition to only show related events if there are any
            if($homepageEvents->have_posts()){
            echo '<hr class="section-break">';
            //headline for related events -> this is the method to concat strings with function in between
            echo '<h3 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h3>';

            //loop thru events and show only 2 events
            while($homepageEvents->have_posts()) {
                $homepageEvents->the_post();
                get_template_part('template-parts/content-event');
            } 
            }
            wp_reset_postdata(); //reset the query data first to get clean slate

            //get related campuses for this specific program
            $relatedCampuses = get_field('related_campus');

            if($relatedCampuses){
                echo '<hr class="section-break">';
                echo '<h3 class="headline headline--medium">' . get_the_title() . ' is Available At These Campuses: </h3>';

                echo '<ul class="min-list link-list">';
                foreach($relatedCampuses as $campus) {
                    ?> <li><a href="<?php echo get_the_permalink($campus)?>"><?php echo get_the_title($campus); ?></a>
        </li> <?php
                }

                echo '</ul>';
            }
        ?>
    </div>
</div>

<?php }

  get_footer();

?>