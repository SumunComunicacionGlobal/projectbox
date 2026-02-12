<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Agregar menú de opciones en el administrador
 */
function projectbox_admin_menu() {
    add_options_page(
        'Páginas Públicas', // Título de la página
        'Páginas Públicas', // Título del menú
        'manage_options',   // Capacidad requerida
        'paginas-publicas', // Slug del menú
        'projectbox_paginas_publicas_page' // Función que muestra la página
    );
}
add_action( 'admin_menu', 'projectbox_admin_menu' );

/**
 * Registrar configuración de páginas públicas
 */
function projectbox_register_settings() {
    register_setting(
        'paginas_publicas_settings', // Grupo de opciones
        'paginas_publicas_seleccionadas', // Nombre de la opción
        array(
            'type' => 'array',
            'sanitize_callback' => 'projectbox_sanitize_pages_array',
            'default' => array()
        )
    );
}
add_action( 'admin_init', 'projectbox_register_settings' );

/**
 * Función de sanitización para el array de páginas
 */
function projectbox_sanitize_pages_array( $input ) {
    if ( ! is_array( $input ) ) {
        return array();
    }
    
    // Sanitizar cada ID de página
    $sanitized = array();
    foreach ( $input as $page_id ) {
        $page_id = absint( $page_id );
        if ( $page_id > 0 && get_post( $page_id ) ) {
            $sanitized[] = $page_id;
        }
    }
    
    return $sanitized;
}

/**
 * Mostrar la página de opciones
 */
function projectbox_paginas_publicas_page() {
    // Verificar permisos
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Obtener páginas seleccionadas actuales
    $paginas_seleccionadas = get_option( 'paginas_publicas_seleccionadas', array() );
    
    // Obtener todas las páginas publicadas
    $pages = get_posts( array(
        'post_type' => 'page',
        'post_status' => 'any',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'numberposts' => -1
    ) );
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        
        <form method="post" action="options.php">
            <?php
            settings_fields( 'paginas_publicas_settings' );
            do_settings_sections( 'paginas_publicas_settings' );
            ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="paginas_publicas_seleccionadas">Seleccionar Páginas Públicas</label>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">Seleccionar páginas que serán públicas</legend>
                            
                            <?php if ( ! empty( $pages ) ) : ?>
                                <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fff;">
                                    <?php foreach ( $pages as $page ) : ?>
                                        <label style="display: block; margin-bottom: 5px;">
                                            <input 
                                                type="checkbox" 
                                                name="paginas_publicas_seleccionadas[]" 
                                                value="<?php echo esc_attr( $page->ID ); ?>"
                                                <?php checked( in_array( $page->ID, $paginas_seleccionadas ) ); ?>
                                            />
                                            <?php echo esc_html( $page->post_title ); ?>
                                            <small style="color: #666;"> (ID: <?php echo $page->ID; ?><?php if ( $page->post_status !== 'publish' ) { $status_obj = get_post_status_object( $page->post_status ); echo ' - <strong>' . esc_html( $status_obj->label ) . '</strong>'; } ?>)</small>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <p>No hay páginas disponibles para seleccionar.</p>
                            <?php endif; ?>
                            
                            <p class="description">
                                Selecciona las páginas que quieres marcar como públicas. 
                                Estas páginas estarán disponibles para uso especial. Recuerda que si marcas alguna página como pública y contiene información sensible, esta será accesible sin necesidad de iniciar sesión.
                                <strong>Además haremos pública automáticamente la página de inicio para permitir la navegación.</strong>
                            </p>
                        </fieldset>
                    </td>
                </tr>
            </table>
            
            <?php submit_button( 'Guardar Configuración' ); ?>
        </form>
        
        <?php if ( ! empty( $paginas_seleccionadas ) ) : ?>
            <div class="notice notice-info inline">
                <h3>Páginas Seleccionadas Actualmente:</h3>
                <ul>
                    <?php foreach ( $paginas_seleccionadas as $page_id ) : ?>
                        <?php $page = get_post( $page_id ); ?>
                        <?php if ( $page ) : ?>
                            <li>
                                <strong><?php echo esc_html( $page->post_title ); ?></strong> 
                                (ID: <?php echo $page_id; ?><?php if ( $page->post_status !== 'publish' ) { $status_obj = get_post_status_object( $page->post_status ); echo ' - <strong>' . esc_html( $status_obj->label ) . '</strong>'; } ?>) - 
                                <a href="<?php echo get_permalink( $page_id ); ?>" target="_blank">Ver página</a> |
                                <a href="<?php echo get_edit_post_link( $page_id ); ?>" target="_blank">Editar</a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    
    <style>
        .form-table th {
            width: 200px;
        }
        .form-table td fieldset label {
            font-weight: normal;
        }
        .form-table td fieldset label input[type="checkbox"] {
            margin-right: 8px;
        }
    </style>
    <?php
}

/**
 * Función auxiliar para obtener las páginas públicas seleccionadas
 */
function projectbox_get_paginas_publicas() {
    $paginas_publicas = get_option( 'paginas_publicas_seleccionadas', array() );
    if ( $paginas_publicas ) {
        $front_page_id = get_option( 'page_on_front' );
        if ( $front_page_id && ! in_array( $front_page_id, $paginas_publicas ) ) {
            $paginas_publicas[] = $front_page_id;
        }
    }
    return $paginas_publicas;
}

/**
 * Función auxiliar para verificar si una página está marcada como pública
 */
function projectbox_is_pagina_publica( $page_id = false ) {
    if ( ! $page_id ) {
        if ( ! is_singular() ) {
            return false;
        }
        $page_id = get_the_ID();
    }

    $paginas_publicas = projectbox_get_paginas_publicas();
    return in_array( absint( $page_id ), $paginas_publicas );
}

add_action( 'login_head', 'custom_login_css', 9999999999 );
function custom_login_css() {

    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    
    $logo_url = $stylesheet_directory_uri . '/assets/img/sumun-logo.png'; // Ruta por defecto del logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
    }
    
    ?>
    <style type="text/css">

        @font-face {
            font-family: 'BW Nista';
            src: url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-Md.eot");
            src: url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-Md.eot") format("embedded-opentype"),
            url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-Md.woff2") format("woff2"),
            url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-Md.woff") format("woff");
            display: swap;
            font-weight: 400;
            font-style: normal
        }

        @font-face {
            font-family: 'BW Nista';
            src: url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-Lt.eot");
            src: url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-Lt.eot") format("embedded-opentype"),
            url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-Lt.woff2") format("woff2"),
            url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-Lt.woff") format("woff");
            display: swap;
            font-weight: 300;
            font-style: normal
        }

        @font-face {
            font-family: 'BW Nista';
            src: url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-xBd.eot");
            src: url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-xBd.eot") format("embedded-opentype"),
            url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-xBd.woff2") format("woff2"),
            url("<?php echo esc_url( $stylesheet_directory_uri ); ?>/assets/fonts/BwNistaInt-xBd.woff") format("woff");
            display: swap;
            font-weight: 700;
            font-style: normal
        }

        body {
            background-color: white;
            font-family: 'BW Nista', sans-serif;
            font-weight: 300;
        }
        #login {
            width: 440px;
            max-width: 90%;
        }
        .login h1 a {
            position: relative;
            background-image: url(<?php echo esc_url( $logo_url ); ?>);
            background-position: center;
            background-size: contain;
            width: 100%;
            max-width: 256px;
            height: 100px;
            text-indent: 0;
            overflow: visible;
            margin-bottom: 32px;
        }
        .login form {
            border-radius: .5rem;
            background-color: #f1f1f1;
            border: 0;
        }
        .wp-core-ui .button {
            border-radius: .5rem;
        }
        .wp-core-ui .button-primary {
            background-color: black;
            border-color: black;
        }
        .wp-core-ui .button-primary:hover {
            background-color: #333;
            border-color: #333;
        }
        .login-logo-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            text-align: center;
            display: block;
        }
        .login .message {
            border-left: inherit;
            border-radius: .5rem;
            box-shadow: none;
            border: 1px solid #f1f1f1;
        }
        
    </style>
    <?php
}

add_filter( 'login_headerurl', function() {
    return home_url('/');
} );

function smn_login_logo_url_title() {
    return '<span class="login-logo-title">' . __( 'Project&nbsp;<b>Box</b>', 'projectbox' ) . '</span>';
}
add_filter( 'login_headertext', 'smn_login_logo_url_title' );

add_filter( 'login_message', function( $message ) {
    if ( empty( $message ) ) {
        $message = '<p class="message">' . __( 'Estás intentando acceder a un contenido protegido de tu Project&nbsp;Box. Por favor, inicia sesión para continuar.', 'projectbox' ) . '</p>';
    }
    return $message;
} );