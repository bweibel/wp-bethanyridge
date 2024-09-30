<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>
<div class="cart-wrap">
	<div class="cart">
			<img class="donkey" src="<?php echo get_theme_file_uri( '/assets/images/donkeycart.png' ); ?>" alt="" width="300px">
			<img class="wheel" src="<?php echo get_theme_file_uri( '/assets/images/cartwheel.png' ); ?>" alt="" width="70px">
		</div>
</div>
	<footer id="colophon" class="site-footer torn-top">
		<?php get_template_part( 'template-parts/footer/info' ); ?>
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
