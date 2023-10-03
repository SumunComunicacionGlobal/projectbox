<?php 

function sumun_scripts() {

	$google_fonts = get_theme_mod( 'cliente_font_script' );
	
	if ( $google_fonts ) {
		echo $google_fonts;
		$font_main = get_theme_mod( 'cliente_font_main' );
		$font_heading = get_theme_mod( 'cliente_font_heading' );
		?>

		<style>
			:root {
				<?php if ( $font_main ) echo '--font__main: ' . $font_main; ?>
				<?php if ( $font_heading ) echo '--font__main: ' . $font_heading; ?>
			}

		</style>

		<?php
		
	}

	wp_enqueue_style( 'sumun-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version') );

	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'sumun-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'sumun-toc', get_template_directory_uri() . '/assets/js/toc.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sumun_scripts' );


/** 
* Gutenberg scripts and styles
 */
function sumun_gutenberg_scripts() {

	wp_enqueue_script(
		'be-editor', 
		get_stylesheet_directory_uri() . '/assets/js/editor.js', 
		array( 'wp-blocks', 'wp-dom' ), 
		filemtime( get_stylesheet_directory() . '/assets/js/editor.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'sumun_gutenberg_scripts' );