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
	
	/**
	 * Redirect to blog page when opening the blog archive panel
	 */
    api.panel( 'blog_archive_view', function( section ) {
		tr_customiser_change_view( section, uptime_data.blog_url );
    } );
	
	/**
	 * Redirect to documentation page when opening the documentation archive panel
	 */
	api.panel( 'documentation_archive_view', function( section ) {
		tr_customiser_change_view( section, uptime_data.documentation_url );
	} );
	
	/**
	 * Redirect to portfolio page when opening the portfolio archive panel
	 */
	api.panel( 'portfolio_archive_view', function( section ) {
		tr_customiser_change_view( section, uptime_data.portfolio_url );
	} );
	
} ( wp.customize ) );