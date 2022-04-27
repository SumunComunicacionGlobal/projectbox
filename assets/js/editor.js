/* eslint-disable indent */
/* eslint-disable no-undef */
wp.domReady( () => {
	wp.blocks.registerBlockStyle( 'core/list', [
		{
			name: 'default',
			label: 'Default',
			isDefault: true,
		},
		{
			name: 'arrow-list',
			label: 'List with arrow',
		},
		{
			name: 'numbers-list',
			label: 'List with numbers',
		},
	] );
	wp.blocks.registerBlockStyle( 'core/group', [
		{
			name: 'default',
			label: 'Default',
			isDefault: true,
		},
		{
			name: 'card',
			label: 'Card',
		},
		{
			name: 'card-link',
			label: 'Card with link',
		},
	] );
} );

/* ADD data space height to wp-block-spacer in gutenberg */
wp.data.subscribe( function() {
	setTimeout( function() {
		const spacerBlocks = document.querySelectorAll( '.wp-block.wp-block-spacer' );

		for ( let item = 0; item < spacerBlocks.length; item++ ) {
			const block = spacerBlocks[ item ];

			const style = getComputedStyle( block ),
				height = parseInt( style.height ) || 0;

			block.querySelector( '.components-resizable-box__container' ).setAttribute( 'data-spaceheight', height + 'px' );
		}
	}, 1 );
} );

