<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Sumun
 */

get_header();


?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Página no encontrada', 'projectbox' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e( 'No hay nada aquí. Puede que sea una página privada o que se haya movido de ubicación.', 'projectbox' ); ?></p>

				<p><?php esc_html_e( '¿Quieres iniciar sesión?', 'projectbox' ); ?></p>

					<?php
					// login form
					if ( ! is_user_logged_in() ) {
						?>
						<div class="login-form">
							<?php
							wp_login_form(
								array(
									'echo'           => true,
									'redirect'       => home_url(),
									'form_id'        => 'loginform',
									'label_username' => __( 'Usuario' ),
									'label_password' => __( 'Contraseña' ),
									'label_remember' => __( 'Recordar' ),
									'label_log_in'   => __( 'Iniciar sesión' ),
									'id_username'    => 'user_login',
									'id_password'    => 'user_pass',
									'id_remember'    => 'rememberme',
									'id_submit'      => 'wp-submit',
									'remember'       => true,
									'value_username' => '',
									'value_remember' => false,
								)
							);
							?>
						</div>
						<?php
					}
					?>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
