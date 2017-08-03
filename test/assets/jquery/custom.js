//NAVIGATION

jQuery(document).ready(function() {
	 
	//navigation BUTTON ACTION (ON CLICK DO THE FOLLOWING)
	jQuery('.navigationButton').click(function() {

		//REMOVE THE ON CLASS FROM ALL BUTTONS
		jQuery('.navigationButton').removeClass('on');
		  
		//NO MATTER WHAT WE CLOSE ALL OPEN SLIDES
	 	jQuery('.navigationContent').slideUp('normal');
   
		//IF THE NEXT SLIDE WASN'T OPEN THEN OPEN IT
		if(jQuery(this).next().is(':hidden') == true) {
			
			//ADD THE ON CLASS TO THE BUTTON
			jQuery(this).addClass('on');
			  
			//OPEN THE SLIDE
			jQuery(this).next().slideDown('normal');
		 } 
		  
	 });
	  
	
	jQuery('#navigation li').hover(
	       function () {
	           //show its submenu
	           jQuery('ul', this).stop().slideDown(400);
	
	       }, 
	       function () {
	           //hide its submenu
	           jQuery('ul', this).stop().fadeOut(100);          
	       }
	   );
	
	
	/********************************************************************************************************************
	CLOSES ALL S ON PAGE LOAD
	********************************************************************************************************************/	
	jQuery('.navigationContent').hide();

});

// PORTFOLIO FILTER

jQuery(document).ready(function() {  
    jQuery('ul#filter a').click(function() {  
        jQuery(this).css('outline','none');  
        jQuery('ul#filter .current').removeClass('current');  
        jQuery(this).parent().addClass('current');  
          
        var filterVal = jQuery(this).text().toLowerCase().replace(' ','-');  
                  
        if(filterVal == 'all') {  
            jQuery('ul#portfolio li.hidden').fadeIn('slow').removeClass('hidden');  
        } else {  
            jQuery('ul#portfolio li').each(function() {  
                if(!jQuery(this).hasClass(filterVal)) {  
                    jQuery(this).fadeOut('normal').addClass('hidden');  
                } else {  
                    jQuery(this).fadeIn('slow').removeClass('hidden');  
                }  
            });  
        }  
          
        return false;  
    });  
});

// ACCORDION

jQuery(document).ready(function() {
	 
	//ACCORDION BUTTON ACTION (ON CLICK DO THE FOLLOWING)
	jQuery('.accordionButton').click(function() {

		//REMOVE THE ON CLASS FROM ALL BUTTONS
		jQuery('.accordionButton').removeClass('on');
		  
		//NO MATTER WHAT WE CLOSE ALL OPEN SLIDES
	 	jQuery('.accordionContent').slideUp('normal');
   
		//IF THE NEXT SLIDE WASN'T OPEN THEN OPEN IT
		if(jQuery(this).next().is(':hidden') == true) {
			
			//ADD THE ON CLASS TO THE BUTTON
			jQuery(this).addClass('on');
			  
			//OPEN THE SLIDE
			jQuery(this).next().slideDown('normal');
		 } 
		  
	 });
	  
	
	/*** REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
	
	//ADDS THE .OVER CLASS FROM THE STYLESHEET ON MOUSEOVER 
	jQuery('.accordionButton').mouseover(function() {
		jQuery(this).addClass('over');
		
	//ON MOUSEOUT REMOVE THE OVER CLASS
	}).mouseout(function() {
		jQuery(this).removeClass('over');										
	});
	
	/*** END REMOVE IF MOUSEOVER IS NOT REQUIRED ***/
	
	
	/********************************************************************************************************************
	CLOSES ALL S ON PAGE LOAD
	********************************************************************************************************************/	
	jQuery('.accordionContent').hide();

});


// TABS 

jQuery(document).ready(function(){
	jQuery('#tabs div').hide();
	jQuery('#tabs div:first').show();
	jQuery('#tabs ul li:first').addClass('active');
 
	jQuery('#tabs ul li a').click(function(){
		jQuery('#tabs ul li').removeClass('active');
		jQuery(this).parent().addClass('active');
		var currentTab = jQuery(this).attr('href');
		jQuery('#tabs div').hide();
		jQuery(currentTab).show();
		return false;
		});
});


// CONTACT FORM


//RESPONSIVE VIDEO

jQuery(document).ready(function(){
    // Target your .container, .wrapper, .post, etc.
    //jQuery('.video.entry').fitVids();
  });
  
//PLUGINS

jQuery(document).ready(function() {

/*
	jQuery('#tweets').tweetable({
			username: 'aetherdesign_', // Set your twitter username here
			time: true,
			limit: 2,
			position: 'append'
				
		});
		 
		$('#flickrs').jflickrfeed({
			limit: 6,
			qstrings: {
				id: '52617155@N08' // Set your flickr ID here
			},
			itemTemplate: 
			'<li>' +
				'<a class="screen-roll" href="{{image_b}}"><span></span><img src="{{image_s}}" alt="{{title}}" /></a>' +
			'</li>'});
	*/
		
});  