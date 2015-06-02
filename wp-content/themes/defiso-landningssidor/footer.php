<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Defiso Landningssidor
 */
global $landingpage
?>

	</div><!-- #content -->
</div><!-- #page -->

<footer id="colophon" class="site-footer" role="contentinfo">
  <div class="footer-container">
    <?php dynamic_sidebar( 'footer-1' ); ?>

    <!-- added static site-info -->
    <div class="site-info">
      <div class="copyright">Copyright © 2015 Städhuset AB.</div>
      <div class="supplier-logo">Defiso Media</div>
    </div><!-- .site-info -->


  </div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>

  <?php if ($landingpage['switch-sticky-form']) { ?>
    <script type="text/javascript" src="http://landningssida.dev/wp-content/themes/defiso-landningssidor/js/jquery.sticky-kit.min.js"></script>
    <script type="text/javascript">
      $(".widget_ladningpage_contact_widget").stick_in_parent({
        parent: '#content',
        offset_top: 20
      });
    </script>
  <?php } ?>
  
  <?php if ($landingpage['slider-switch']) {?>
    <script>
      $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
          <?php if ($landingpage['switch-autoslide']) {?>
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
          <?php } ?>
          items: 1
        });
      });
    </script>
  <?php } ?>

  <?php if ($landingpage['text-UA']) { ?>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php echo $landingpage['text-UA'] ?>', 'auto');
      ga('send', 'pageview');

    </script>
  <?php } ?>

  <?php if ($landingpage['text-freespee']) { ?>
    <script type="text/javascript">
        var __fs_conf = __fs_conf || [];
        __fs_conf.push(['setAdv',{'id': '<?php echo $landingpage['text-freespee'] ?>'}]);
        __fs_conf.push(['numberDetection', true]);
    </script>
    <script type="text/javascript" src="//analytics.freespee.com/js/external/fs.js"></script>
  <?php } ?>

<?php if ($landingpage['switch-custom-js']) {
    echo '<script> '. $landingpage['ace-editor-custom-js'] .' </script>';
  }
?>

<script>
  $(document).ready(function() {

      $('form').ajaxForm(function() {
        $('#message').addClass('success').fadeIn(); 
        $('form').resetForm();
      });

      $('input:submit').click(function(){
        $(this).prop('value', 'Skickar formulär...');
      });

  });
</script>

</body>
</html>