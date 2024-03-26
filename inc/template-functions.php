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

	$r = '';

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
		} elseif( is_front_page() ) {
			$args['post_parent'] = 0;
			$args['post__not_in'] = array( get_the_ID() );
		}

		// if ( is_front_page() ) {

		// 	$r .= '<div class="paginas-hijas">';

		// 		$r .= '<div class="main-navigation">';

		// 			$r .= '<p class="text-h2">' . __( 'Contenido:', 'sumun' ) . '</p>';

		// 			$r .= '<ul class="menu">';

		// 				$page_list_args = array(
		// 					'title_li'		=> null,
		// 					'echo'			=> 0,
		// 					'walker'		=> new Custom_Walker_Page(),
		// 				);

		// 				$r .= wp_list_pages( $page_list_args );

		// 			$r .= '</ul>';

		// 		$r .= '</div>';

		// 	$r .= '</div>';

		// 	return $r;
		// }

		$texto_ver_mas = '<p class="text-h2">' . __( 'Ver más:', 'sumun' ) . '</p>';


		$query = new WP_Query($args);

		if ($query->have_posts() ) {

			$r .= '<div class="mas-contenido paginas-hijas">';

				$r .= $texto_ver_mas;

				// $r .= '<div class="wp-block-columns is-layout-flex">';

				while($query->have_posts() ) { $query->the_post();


						// $r .= '<a class="btn btn-primary mr-2 mb-2 pagina-hija" href="'.get_permalink( get_the_ID() ).'" title="'.get_the_title().'" role="button" aria-pressed="false">'.get_the_title().'</a>';


						// $r .= '<div class="wp-block-columns is-layout-flex">';

							// $r .= '<div class="wp-block-column">';

								$r .= '<div class="wp-block-group is-style-card-link mb-3">';

									$r .= '<div class="wp-block-group__inner-container">';

										$r .= '<p class="text-h4"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></p>';

									$r .= '</div>';

								$r .= '</div>';

							// $r .= '</div>';

						// $r .= '</div>';

				}

				// $r .= '</div>';

			$r .= '</div>';

		} else {
			wp_reset_postdata();
			$current_post_id = get_the_ID();
			$args['post_parent'] = $post->post_parent;

			$query = new WP_Query($args);
			if ($query->have_posts() && $query->found_posts > 1 ) {

				$r .= '<div class="mas-contenido paginas-hermanas">';

				$r .= $texto_ver_mas;

				while($query->have_posts() ) {
					
					$query->the_post();
					if ($current_post_id == get_the_ID()) {
						$r .= '<span class="btn mr-2 mb-2" disabled>'.get_the_title().'</span>';
					} else {
						$r .= '<a class="btn has-primary-background-color has-white-color mr-2 mb-2" href="'.get_permalink( get_the_ID() ).'" title="'.get_the_title().'" role="button" aria-pressed="false">'.get_the_title().'</a>';
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

add_action('wp_footer', 'smn_pdf_links_new_tab');
function smn_pdf_links_new_tab(){
	?>

	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("a").each(function(){
				if(
					this.href.indexOf(location.hostname) == -1 ||
					this.href.indexOf(".pdf") !== -1
				) {
					$(this).attr({
						target: "_blank",
						title: "Nueva pestaña"
					});
				}
			})
		});
	</script>
	
	<?php
}

function smn_default_menu() {

	echo '<ul id="primary-menu" class="menu">';

		// show publish and private pages to logged in users
		if ( is_user_logged_in() ) {

			wp_list_pages( array(
				'depth'			=> 4,
				'title_li'		=> null,
				'walker'		=> new Custom_Walker_Page(),
				'post_status'	=> array( 'private', 'publish' ),
			));

		} else {

			wp_list_pages( array(
				'depth'			=> 4,
				'title_li'		=> null,
				'walker'		=> new Custom_Walker_Page(),
			));
		
		}

	echo '</ul>';

}

class Custom_Walker_Page extends Walker_Page {
    // Modificar la salida para cada elemento de página
    public function start_lvl(&$output, $depth = 0, $args = null) {
		$menu_class = 'sub-menu';
		$id = '';
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul ". $id ." class='". $menu_class ."'>\n";
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    public function start_el(&$output, $page, $depth = 0, $args = null, $current_page = 0) {
        if ( $depth ) {
            $indent = str_repeat("\t", $depth);
        } else {
            $indent = '';
        }

		$css_class = '';

		$children = get_pages('child_of=' . $page->ID);
		$ancestors = get_post_ancestors( $current_page );

        $css_class .= empty( $page->post_parent ) ? ' parent' : ' child';
		if ( $children ) $css_class .= ' menu-item-has-children';
		if ( $current_page == $page->ID ) $css_class .= ' current-menu-item';
		if ( in_array( $page->ID, $ancestors ) ) $css_class .= ' current-menu-ancestor';

        $output .= $indent . '<li class="menu-item' . $css_class . '"><a href="' . get_permalink( $page->ID ) . '">' . esc_html( $page->post_title ) . '</a>';
    }

    public function end_el(&$output, $page, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}


add_filter( 'render_block', 'list_block_wrapper', 10, 2 );
function list_block_wrapper( $block_content, $block ) {
    if ( $block['blockName'] === 'core/list' ) {
        $block_content = str_replace( 
            array( '<ul class="', '<ol class="'), 
            array( '<ul class="wp-block-list ', '<ol class="wp-block-list '), $block_content );

        $block_content = str_replace( 
            array( '<ul>', '<ol>'), 
            array( '<ul class="wp-block-list">', '<ol class="wp-block-list">'), $block_content );

    }

    return $block_content;
}

function smn_hide_admin_bar_for_subscribers() {
	if (current_user_can('subscriber') && !is_admin()) {
		show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'smn_hide_admin_bar_for_subscribers');
