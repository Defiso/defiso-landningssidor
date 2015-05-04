<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Defiso Landningssidor
 */

get_header(); ?>

	<section class="jumbotron">
		<?php
			if(is_front_page() ) {

				if ($landingpage['slider-switch']) {

					if (isset($landingpage['slides-client-slider']) && !empty($landingpage['slides-client-slider'])) {
						echo '<div class="owl-carousel">';
							foreach($landingpage['slides-client-slider'] as $slide) {
								echo '<div>';
									echo '<img src="' . wp_get_attachment_image_src( $slide['attachment_id'], 'frontslider')[0] . '">';
									echo '<p> ' . $slide['title'] . '</p>';
								echo '</div>';
							}
						echo '</div>';
					}
				} else {
					echo '<div class="static-item">';
						echo '<img src="' . wp_get_attachment_image_src( $landingpage['media-client-jumbotron']['id'], 'frontslider')[0] . '">';
						echo '<p>' . $landingpage['text-client-jumbotron'] . '</p>';
					echo '</div>';
				}

			}
		?>
	</section>

	<section class="checkmarks">
		<?php if(is_front_page() ) {?>
			<ul>
				<?php foreach($landingpage['multi-text-checkmarks'] as $checkmark) {?>
					<li><?php echo $checkmark ?></li>
				<?php } ?>
			</ul>
		<?php }?>
	</section>

	<?php if(is_front_page() ) {?>
		<?php if ($landingpage['switch-front-boxes']) {?>
			<section class="intro-boxes">

				<div class="box">
					<img src="<?php echo $landingpage['media-front-box-1']['url']; ?>">
					<p><?php echo $landingpage['textarea-front-box-1']; ?></p>
					<?php if ($landingpage['switch-front-boxes-links']) { ?>
						<div class="buttonwrap">
							<a href="<?php echo $landingpage['text-front-box-1-link']; ?>" class="button">Läs mer</a>
						</div>
					<?php } ?>
				</div>

				<div class="box">
					<img src="<?php echo $landingpage['media-front-box-2']['url']; ?>">
					<p><?php echo $landingpage['textarea-front-box-2']; ?></p>
					<?php if ($landingpage['switch-front-boxes-links']) { ?>
							<div class="buttonwrap">	
								<a href="<?php echo $landingpage['text-front-box-2-link']; ?>" class="button">Läs mer</a>
							</div>
					<?php } ?>
				</div>

				<div class="box">
					<img src="<?php echo $landingpage['media-front-box-3']['url']; ?>">
					<p><?php echo $landingpage['textarea-front-box-3']; ?></p>
					<?php if ($landingpage['switch-front-boxes-links']) { ?>
						<div class="buttonwrap">	
							<a href="<?php echo $landingpage['text-front-box-3-link']; ?>" class="button">Läs mer</a>
						</div>
					<?php } ?>
				</div>

			</section>
		<?php }?>		
	<?php }?>
	<div class="row">
		<hr>
	</div>

	<div id="primary" class="content-area">
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

<?php get_sidebar(); ?>
<?php get_footer(); ?>
