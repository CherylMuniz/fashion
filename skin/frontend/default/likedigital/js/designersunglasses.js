//--------------------------------------------
//--------------------------------------------
// © Like Digital Media 2011
// All rights reservered
// Unathorised use is strictly prohibited
// We are watching you...
//--------------------------------------------


document.observe("dom:loaded", function() {
  
	//hide the description   
	ddListFixed = $('product-options-wrapper').select('dd');
	
	var description = $(ddListFixed[0]);
	hideSection(description);
	
	
});

//---------------------------------------
// Hide a section
//---------------------------------------
function hideSection(in_section) {
	//hide this item
	$(in_section).hide();
	//hide the header for this section
	$(in_section).previous().hide();
}

