(function ( api ) {
	
	function tr_customiser_change_view( section, URL ){
		
		var previousUrl, 
			clearPreviousUrl, 
			previewUrlValue;
		
       previewUrlValue = api.previewer.previewUrl;
		
       clearPreviousUrl = function() {
           previousUrl = null;
       };

       section.expanded.bind( function( isExpanded ) {
			
           if ( isExpanded ) {
				
               previousUrl = previewUrlValue.get();
				
               previewUrlValue.set( URL );
               previewUrlValue.bind( clearPreviousUrl );
				
           } else {
				
               previewUrlValue.unbind( clearPreviousUrl );
				
               if ( previousUrl ) {
                   previewUrlValue.set( previousUrl );
               }
				
           }
			
       } );
			   
	}
	
} ( wp.customize ) );