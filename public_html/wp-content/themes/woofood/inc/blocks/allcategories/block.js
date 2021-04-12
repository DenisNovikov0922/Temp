/**
 * BLOCK: WooFoof Accordion
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 *
 * Styles:
 *        editor.css — Editor styles for the block.
 *        style.css  — Editor & Front end styles for the block.
 */

( function() {
	var __ = wp.i18n.__; // The __() for internationalization.
	var el = wp.element.createElement; // The wp.element.createElement() function to create elements.
	var registerBlockType = wp.blocks.registerBlockType; // The registerBlockType() to register blocks.
	var RichText = wp.editor.RichText;
   var   apiFetch = wp.apiFetch;
   var   SelectControl = wp.components.SelectControl;

var AllCategoriesBlockIcon = el('svg', { width: 20, height: 20, version:1.1},

	el('g', { },
		el('image', { width:20, height:20,  href:"data:image/png;base64,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"  }) )

);


const postSelections = [];


jQuery.post(ajaxurl, {
	action: "wooofood_get_product_categories_rest_ajax",
}, function(response) {
	var data = JSON.parse(response);
	        postSelections.push({label: __( 'Select Product Category', 'woofood-plugin' ), value: ''});
	        postSelections.push({label: __( 'All Categories','woofood-plugin' ), value: ''});

jQuery.each( data, function( key, category ) {
	//console.log(category);
        postSelections.push({label: category.name, value: category.slug});
    });
});


	/**
	 * Register Basic Block.
	 *
	 * Registers a new block provided a unique name and an object defining its
	 * behavior. Once registered, the block is made available as an option to any
	 * editor interface where blocks are implemented.
	 *
	 * @param  {string}   name     Block name.
	 * @param  {Object}   settings Block settings.
	 * @return {?WPBlock}          The block, if it has been successfully
	 *                             registered; otherwise `undefined`.
	 */



	registerBlockType( 'woofood/accordion', { // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
		title: __( 'WooFood Categories Accordion', 'woofood-plugin' ), // Block title.
		icon: AllCategoriesBlockIcon, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
		category: 'woofood',
		attributes: {
		
		category_slug:{}
	},
 // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.

		// The "edit" property must be a valid function.
		edit: function( props ) {
			
			

			
	
			


		return	(
		wp.element.createElement( 'div', { className: props.className },

		
			wp.element.createElement(SelectControl, 
			{
							label: __('Select a Category'),
                            help: 'Select a product Categort or leave it empty.',
                            options: postSelections,
                            value: props.attributes.category_slug,
                            onChange: function( new_category_slug ) {
				props.setAttributes( { category_slug: new_category_slug } );
			}

			}),


		wp.element.createElement( wp.components.ServerSideRender, {
	block: 'woofood/accordion',
	attributes:props.attributes
})
		


		) //closing div
		); //end return
			

			
		},

		// The "save" property must be specified and must be a valid function.
		save: function( props ) {
		
			return null;
		},
	} );
})();
