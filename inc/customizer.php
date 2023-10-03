<?php
/**
 * Sumun Theme Customizer
 *
 * @package Sumun
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sumun_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'sumun_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'sumun_customize_partial_blogdescription',
			)
		);
	}

    // Añade una nueva sección llamada "Ajustes del cliente"
	$wp_customize->add_section('cliente_settings_section', array(
        'title' => __('Ajustes del cliente', 'projectbox'), // Puedes cambiar 'tu-textdomain' por el dominio de tu tema o plugin
        'priority' => 200, // Puedes ajustar la prioridad según tus necesidades
    ));

	// Añade un campo de email de contacto dentro del panel
	$wp_customize->add_setting('cliente_email', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_email',
	));

	$wp_customize->add_control('cliente_email', array(
		'label' => __('Email de contacto', 'projectbox'), // Puedes cambiar 'tu-textdomain' por el dominio de tu tema o plugin
		'section' => 'cliente_settings_section',
		'type' => 'email',
	));

	$wp_customize->add_setting('cliente_font_script', array(
		'default' => '',
	));

	$wp_customize->add_control('cliente_font_script', array(
		'label' => __('Código de Google Fonts', 'projectbox'), // Puedes cambiar 'tu-textdomain' por el dominio de tu tema o plugin
		'description' => __( 'Pega aquí el código completo de inserción que te da Google Fonts (<link rel="preconnect">...', 'projectbox' ),
		'section' => 'cliente_settings_section',
		'type' => 'textarea',
	));

	$wp_customize->add_setting('cliente_font_main', array(
		'default' => '',
	));

	$wp_customize->add_control('cliente_font_main', array(
		'label' => __('Nombre de la tipografía de Google para el cuerpo', 'projectbox'), // Puedes cambiar 'tu-textdomain' por el dominio de tu tema o plugin
		'section' => 'cliente_settings_section',
		'type' => 'text',
	));

	$wp_customize->add_setting('cliente_font_heading', array(
		'default' => '',
	));
	
	$wp_customize->add_control('cliente_font_heading', array(
		'label' => __('Nombre de la tipografía de Google para los encabezados', 'projectbox'), // Puedes cambiar 'tu-textdomain' por el dominio de tu tema o plugin
		'section' => 'cliente_settings_section',
		'type' => 'text',
	));


}
add_action( 'customize_register', 'sumun_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function sumun_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function sumun_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sumun_customize_preview_js() {
	wp_enqueue_script( 'sumun-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'sumun_customize_preview_js' );