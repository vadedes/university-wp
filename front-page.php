<?php get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg') ?>);"></div>
    <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome!</h1>
        <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
        <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re
            interested in?</h3>
        <a href="<?php echo get_post_type_archive_link('program') ?>" class="btn btn--large btn--blue">Find Your
            Major</a>
    </div>
</div>

<div class="full-width-split group">
    <div class="full-width-split__one">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>

            <?php 
            // set events query variables
            //this setup was meant to order the posts by date from ACF hence meta keys mentioned below
            //also it filter out dates that have already passed
            $today = date('Ymd'); //variable to get present date

            $homepageEvents = new WP_Query(array(
              'posts_per_page' => 2,
              'post_type' => 'event',
              //below line is needed when querying custom fields
              'meta_key' => 'event_date',
              'orderby' => 'meta_value_num',
              'order' => 'ASC',
              // purpose of below parameter is to only show content that have not yet passed
              'meta_query' => array(
                array(
                  'key' => 'event_date', //event date
                  'compare' => '>=', //condition symbol
                  'value' => $today, //get present date variable declared above
                  'type' => 'numeric' //specified type of output value
                )
              )
            ));
            //loop thru events and show only 2 events
            while($homepageEvents->have_posts()) {
              $homepageEvents->the_post(); ?>
            <div class="event-summary">
                <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                    <!-- can use the_time('M') M for month, d for day Y for year or use ACF -->
                    <span class="event-summary__month"><?php 
                      $eventDate = new DateTime(get_field('event_date'));
                      echo $eventDate->format('M');
                    ?></span>
                    <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a
                            href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <p><?php if(has_excerpt()){
                   echo get_the_excerpt();
                  } else {
                    echo wp_trim_words(get_the_content(), 18);
                  } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more
                        </a>
                    </p>
                </div>
            </div>
            <?php } wp_reset_postdata(); ?>
            <!-- can use <?php echo site_url('/events'); ?> but we want it to be dynamic and get the link based on the post type  hence code below-->
            <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event') ?>"
                    class="btn btn--blue">View All Events</a></p>
        </div>
    </div>

    <div class="full-width-split__two">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
            <!-- Query first 2 blog posts -->
            <!-- Run loop as long as there are posts -->
            <?php
            // create a variable first to specify where the data will come from 
            $homepagePosts = new WP_Query(array(
              'posts_per_page' => 2,
              // 'category_name' => 'awards' //only give us posts the are under awards category
              // 'post_type' => 'page', //if I want to output a list of site pages
            ));

            //use the variable in the loop by referencing the variable and accessing the methods within it as follows:
            while ($homepagePosts->have_posts()) {
              $homepagePosts->the_post(); ?>
            <div class="event-summary">
                <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                    <span class="event-summary__month"><?php the_time('M'); ?></span>
                    <span class="event-summary__day"><?php the_time('d'); ?></span>
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a
                            href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <!-- get the shortend version of content by trimming it to 18 words max -->
                    <p><?php if(has_excerpt()){
                   echo get_the_excerpt();
                  } else {
                    echo wp_trim_words(get_the_content(), 18);
                  } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a></p>
                </div>
            </div>
            <!-- always reset the post data after every query to avoid conflicts with other queries -->
            <?php } wp_reset_postdata(); ?>

            <p class="t-center no-margin"><a href="<?php echo site_url('/blog'); ?>" class="btn btn--yellow">View All
                    Blog Posts</a></p>
        </div>
    </div>
</div>

<div class="hero-slider">
    <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
            <div class="hero-slider__slide"
                style="background-image: url(<?php echo get_theme_file_uri('/images/bus.jpg') ?>);">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">Free Transportation</h2>
                        <p class="t-center">All students have free unlimited bus fare.</p>
                        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                    </div>
                </div>
            </div>
            <div class="hero-slider__slide"
                style="background-image: url(<?php echo get_theme_file_uri('/images/apples.jpg') ?>);">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">An Apple a Day</h2>
                        <p class="t-center">Our dentistry program recommends eating apples.</p>
                        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                    </div>
                </div>
            </div>
            <div class="hero-slider__slide"
                style="background-image: url(<?php echo get_theme_file_uri('/images/bread.jpg') ?>);">
                <div class="hero-slider__interior container">
                    <div class="hero-slider__overlay">
                        <h2 class="headline headline--medium t-center">Free Food</h2>
                        <p class="t-center">Fictional University offers lunch plans for those in need.</p>
                        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
    </div>
</div>

<?php get_footer();?>