<?php
  
  get_header();

  while(have_posts()) {
    the_post(); 
    // //option 1: default as per data from the page
    pageBanner();

    //option 2 - add args to page banner and specify dynamic elements
    // pageBanner(array(
    //     'title' => "I'm the title",
    //     'subtitle' => "I'm the subtitle",
    //     'photo' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1748&q=80'
    // ));
    ?>



<div class="container container--narrow page-section">

    <div class="generic-content">
        <div class="row group">
            <div class="one-third">
                <?php the_post_thumbnail('professorPortrait') ?>
            </div>
            <div class="two-thirds">
                <?php the_content() ?>
            </div>
        </div>
    </div>
    <?php 
        $relatedPrograms = get_field('related_programs');
        // print_r() method allows us to see the data contained in a variable
        // print_r($relatedPrograms);
        if($relatedPrograms) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
            echo '<ul class="link-list min-list" >';
            forEach($relatedPrograms as $program) { ?>
    <li><a href="<?php echo get_the_permalink($program) ?>"><?php echo get_the_title($program); ?></a></li>
    <?php }
            echo '</ul>';
        }   
    ?>
</div>

<?php }

  get_footer();

?>