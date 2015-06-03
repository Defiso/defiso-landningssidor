<?php
/*
Template Name: Kontaktsida
*/

get_header(); ?>

  <div id="primary" class="content-area contact-page">
    <main id="main" class="site-main" role="main">

      <?php while ( have_posts() ) : the_post(); ?>

        <?php get_template_part( 'content', 'page' ); ?>

        <?php
          // If comments are open or we have at least one comment, load up the comment template
          if ( comments_open() || get_comments_number() ) :
            comments_template();
          endif;
        ?>

      <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
  </div><!-- #primary -->

<?php if ( is_active_sidebar( 'sidebar-contact' ) ) : ?>
  <div id="secondary" class="widget-area contact-page" role="complementary">
    <?php dynamic_sidebar( 'sidebar-contact' ); ?>
  </div><!-- #primary-sidebar -->
<?php endif; ?>
<?php get_footer(); ?>
