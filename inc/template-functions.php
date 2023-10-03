<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Sumun
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function sumun_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'sumun_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function sumun_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'sumun_pingback_header' );


function paginas_hijas( $atts ) {

	extract( shortcode_atts(
		array(
				'id' 				=> false,
				'layout'			=> false,
		), $atts)	
	);

	global $post;

	if ( is_post_type_hierarchical( $post->post_type ) /*&& '' == $post->post_content */) {

		$args = array(
			'post_type'			=> array($post->post_type),
			'posts_per_page'	=> -1,
			'post_status'		=> 'publish',
			'orderby'			=> 'menu_order',
			'order'				=> 'ASC',
			'post_parent'		=> $post->ID,
		);

		if ( $id ) {
			$args['post_parent'] = $id;
		}

		$r = '';

		$query = new WP_Query($args);

		if ($query->have_posts() ) {

			$r .= '<div class="paginas-hijas">';

				// $r .= '<div class="wp-block-columns is-layout-flex">';

				while($query->have_posts() ) { $query->the_post();


						// $r .= '<a class="btn btn-primary mr-2 mb-2 pagina-hija" href="'.get_permalink( get_the_ID() ).'" title="'.get_the_title().'" role="button" aria-pressed="false">'.get_the_title().'</a>';


						$r .= '<div class="wp-block-columns is-layout-flex">';

							$r .= '<div class="wp-block-column">';

								$r .= '<div class="wp-block-group is-style-card-link">';

									$r .= '<div class="wp-block-group__inner-container">';

										$r .= '<p class="text-h4"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></p>';

									$r .= '</div>';

								$r .= '</div>';

						$r .= '</div>';

						$r .= '</div>';

				}

				// $r .= '</div>';

			$r .= '</div>';

		} elseif( 0 != $post->post_parent ) {
			wp_reset_postdata();
			$current_post_id = get_the_ID();
			$args['post_parent'] = $post->post_parent;

			$query = new WP_Query($args);
			if ($query->have_posts() && $query->found_posts > 1 ) {

				$r .= '<div class="paginas-hijas">';

				while($query->have_posts() ) {
					
					$query->the_post();
					if ($current_post_id == get_the_ID()) {
						$r .= '<span class="btn btn-primary btn-sm mr-2 mb-2">'.get_the_title().'</span>';
					} else {
						$r .= '<a class="btn btn-primary btn-sm mr-2 mb-2" href="'.get_permalink( get_the_ID() ).'" title="'.get_the_title().'" role="button" aria-pressed="false">'.get_the_title().'</a>';
					}
				}

				$r .= '</div>';

			}
		}

		wp_reset_postdata();

		return $r;
	}
}
add_shortcode( 'paginas_hijas', 'paginas_hijas' );

add_filter('the_content', 'mostrar_paginas_hijas', 100);
function mostrar_paginas_hijas($content) {

	if (is_admin() || !is_singular() || !in_the_loop() ) return $content;

	global $post;

	if (has_shortcode( $post->post_content, 'paginas_hijas' )) return $content;

	return $content . paginas_hijas( array() );

}

function smn_breadcrumb() {

	if ( is_front_page() ) return false;

	if(function_exists('bcn_display')) {
		echo '<div class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">';
			echo '<div class="breadcrumb-inner">';
				bcn_display();
			echo '</div>';
		echo '</div>';
	} elseif ( function_exists( 'rank_math_the_breadcrumbs') ) {
		echo '<div class="breadcrumb">';
			echo '<div class="breadcrumb-inner">';
				rank_math_the_breadcrumbs(); 
			echo '</div>';
		echo '</div>';
		} elseif ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb( '<div id="breadcrumbs" class="breadcrumb"><div class="breadcrumb-inner">','</div></div>' );
	  }

}