[![Build Status](https://travis-ci.org/Automattic/_s.svg?branch=master)](https://travis-ci.org/Automattic/_s)

Project Box WP Theme
===

Hola. Esto es el tema `projectbox`, para Sumun, está basado en el starter theme Underscores.

Instalación
---------------

### Requirimientos

`projectbox` solo requiere estas dependencias:

- [Node.js](https://nodejs.org/)
- [Composer](https://getcomposer.org/)

### Cómo empezar

Clona o descarga este repositorio `$ git clone https://github.com/SumunComunicacionGlobal/projectbox` en tu carpeta de themes.

### Setup

Para empezar a usar todas las herramientas del tema `projectbox` necesitas instalar las dependencias de Node.js y Composer :

```sh
$ composer install
$ npm install
```

### Comandos CLI disponibles

`projectbox` está preparado para el desarollo de la plantilla para WordPress :

- `composer lint:wpcs` : comprueba todos los archivos PHP con [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
- `composer lint:php` : comprueba todos los archivos PHP en busca de errores de sintaxis.
- `composer make-pot` : gerena un archivo .pot en el directorio `languages/`.
- `npm run compile:css` : compila archivos SASS a css.
- `npm run compile:editorcss` : compila archivos SASS a css editor-styles.css.
- `npm run compile:rtl` : genera la hoja de estilo para RTL.
- `npm run watch` : vigila todos los archivos SASS y los vuelve a compilar en css cuando cambian.
- `npm run lint:scss` : comprueba todos los archivos SASS con [CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/).
- `npm run lint:js` : comprueba todos los archivos JavaScript con [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/).
- `npm run sync` : cada cambio en archivos css o php se muestran en el navegador.
- `npm run dev` : se ejecuta en paralelo `npm run watch` y `npm run sync`.
- `npm run bundle` : genera un archivo .zip para su distribución, excluyendo los archivos de desarrollo y del sistema.

¡Ahora estás listo para empezar! El siguiente paso es fácil de decir, pero más difícil de hacer: crea un tema de WordPress increíble. :)

¡Buena suerte!
