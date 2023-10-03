<?php

/**
 * Adds support for editor color palette.
 */
add_action('after_setup_theme', function () 
{  
    /**
     * Read our compiled theme CSS and extract the WP colour palette so we can register
     * it with Gutenberg.
     */
     
    // Update the css path to your plugin's css file.
    $compiled_css_path = get_template_directory() . '/style-editor.css';
    
    $cache_key = md5_file( $compiled_css_path );
    $cached = get_option( '__theme_cached_color_palette_version', false );
    if ( $cached == $cache_key )
    {
        $colour_palette = get_option( '__theme_cached_color_palette', [] );
    }
    else
    {
        $theme_css = file_get_contents( $compiled_css_path );
        preg_match_all( '/\.has-([^}]*)-background-color\s*{\s*background-color[^\S\r\n]*:[^\S\r\n]*([^};]*);?\s*}/', $theme_css, $matches );
        $colour_palette = [];
        $assigned = [];
        if ( is_array($matches) && isset($matches[0]) && isset($matches[1]) && isset($matches[2]) )
        {
            // $full_match = $matches[0]; // The full matched string
            $colour_slugs = $matches[1]; // The colour slug pulled from .has-(\S+)-background-color
            $colour_values = $matches[2]; // The colour value
            if ( is_array($colour_slugs) && is_array($colour_slugs) )
            {
                foreach( $colour_slugs as $index => $slug )
                {
                    if ( !isset($colour_values[$index]) ) continue;
                    $colour_val = trim( $colour_values[$index] ); // Important to trim whitespace from regex extraction.
                    if ( in_array($colour_val, $assigned) ) continue;
                    $assigned[] = $colour_val;
                    $colour_palette[] = [
                        'name' => ucwords(str_replace( ['-', '_'], ' ', $slug )),
                        'slug' => $slug,
                        'color' => $colour_val,
                    ];
                }
            }
            update_option( '__theme_cached_color_palette_version', $cache_key );
            update_option( '__theme_cached_color_palette', $colour_palette );
        }
    }
    if ( $colour_palette )
    {
        // add_theme_support( 'disable-custom-colors' );
        add_theme_support( 'editor-color-palette', $colour_palette );    
    }

}, 20);

// Disables custom colors in block color palette.
// add_theme_support( 'disable-custom-colors' );
// add_theme_support( 'disable-custom-gradients' );

// Add support for Block Styles.
add_theme_support( 'wp-block-styles' );

// Add support for editor styles.
add_theme_support( 'editor-styles' );

// Enqueue editor styles.
add_editor_style( 'style-editor.css' );