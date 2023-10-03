<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Sumun
 */

get_header();
$email = get_theme_mod( 'cliente_email', 'sumun@sumun.net' );
?>

	<main id="primary" class="site-main mb-6">
		<div class="row main-content">
			<header class="col-xs-12 dflex between-xs middle-xs mt-3 mb-10">
				<div><a href="https://sumun.net/"><img src="<?php echo get_template_directory_uri()?>/assets/img/logo-sumun.png"></a></div>
				<div class="header-contact"><p><?php echo __( '¿Tienes algunda duda?', 'projectbox' ); ?> <span>|</span> <strong><a href="mailto:<?php echo $email; ?>"><?php echo __( 'Consúltanos', 'projectbox' ); ?></a></strong></p></div>
			</header>


			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

			endwhile; // End of the loop.
			?>

			<aside class="col-xs-12 col-md-3 col-md-offset-1">
				<div><strong><small><?php echo __( 'Tabla de contenido', 'projectbox' ); ?></small></strong></div>
				<div id="ToC"></div>
			</aside>

		</div><!-- .main-content -->
	</main><!-- #main -->

<?php
get_footer();
