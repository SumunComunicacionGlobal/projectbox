/* eslint-disable no-undef */
// Get ToC div
toc = document.getElementById( 'ToC' );

// Create a list for the ToC entries
tocList = document.createElement( 'ul' );
tocList.className = 'is-style-arrow-list';

// Get the h3 tags - ToC entries
headers = document.getElementsByTagName( 'h3' );

// For each h3
for ( i = 0; i < headers.length; i++ ) {
// Create an id
	name = 'h' + i;
	headers[ i ].id = name;

	//a list item for the entry
	tocListItem = document.createElement( 'li' );
	// a link for the h3
	tocEntry = document.createElement( 'a' );
	tocEntry.setAttribute( 'href', '#' + name );
	tocEntry.innerText = headers[ i ].innerText;
	tocListItem.appendChild( tocEntry );
	tocList.appendChild( tocListItem );
}
toc.appendChild( tocList );
