<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Defiso Landningssidor
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'defiso-landningssidor' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'defiso-landningssidor' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'defiso-landningssidor' ), 'Defiso Landningssidor', '<a href="http://reduxframework.com/" rel="designer">Anton Kjörck Lindén</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
