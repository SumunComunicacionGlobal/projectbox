<?php

// Adds support for editor color palette.
add_theme_support( 'editor-color-palette', array(
	array(
		'name'  => __( 'Primary', 'sumun' ),
		'slug'  => 'primary',
		'color'	=> '#244985',
	),
	array(
		'name'  => __( 'Secondary', 'sumun' ),
		'slug'  => 'secondary',
		'color' => '#E45510',
	),
	array(
		'name'  => __( 'Ligth', 'sumun' ),
		'slug'  => 'ligth',
		'color' => '#F4F5F7',
       ),
    array(
		'name'  => __( 'Dark', 'sumun' ),
		'slug'  => 'dark',
		'color' => '#101820',
       ),
	array(
		'name'  => __( 'White', 'sumun' ),
		'slug'  => 'white',
		'color' => '#FFFFFF',
    ),   
) );

// Disables custom colors in block color palette.
add_theme_support( 'disable-custom-colors' );
add_theme_support( 'disable-custom-gradients' );

// Add support for Block Styles.
add_theme_support( 'wp-block-styles' );

// Add support for full and wide align images.
add_theme_support( 'align-wide' );

// Add support for editor styles.
add_theme_support( 'editor-styles' );

// Enqueue editor styles.
add_editor_style( 'style-editor.css' );