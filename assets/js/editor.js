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
	] );
} );
