//--------------------------------------------
//--------------------------------------------
// © Like Digital Media 2011
// All rights reservered
// Unathorised use is strictly prohibited
// We are watching you...
//--------------------------------------------

//general vars
var ddList;
var ddListFixed;
var allLoaded = "N";

//sections
var frameSizes;
var frameSizeHolder;
var lensTypes;
var lensTypesHolder;
var glassesFor;
var glassesForHolder;
var lensThickness;
var lensThicknessHolder;
var lensTints;
var lensTintsHolder;
var lensCoatings;
var lensCoatingsHolder;
var varifocals;
var varifocalsHolder;

//prescription
var right_sph;
var right_cyl;
var right_axis;
var right_near;
var left_sph;
var left_cyl;
var left_axis;
var left_near;
var pupil_distance;

//lens type
var singleVision;
var bifocal;
var varifocal;
var frameOnly;

//glasses for
var distance;
var reading;
var vdu;
var for_na;

//lens materials
var standard_0;
var standard_24;
var thin_34;
var thin_45;
var thin_104;
var thinner_54;
var thinner_71;
var thinnest_134;
var thinnest_135;
var thickness_na;

//varifocal
var basic_0;
var basic_44;
var advanced_74;
var advanced_88;
var advanced_94;
var advanced_114;
var advanced_140;
var premium_134;
var premium_138;
var premium_144;
var elite_188;
var elite_194;
var elite_210;
var elite_234;
var varifocals_na;

//lens tints
var brownPhoto_15;
var brownPhoto_29;
var brownPhoto_45;
var greyPhoto_15;
var greyPhoto_29;
var greyPhoto_45;
var brownTrans_25;
var brownTrans_35;
var brownTrans_38;
var brownTrans_45;
var brownTrans_49;
var brownTrans_55;
var brownTrans_65;
var brownTrans_95;
var greyTrans_25;
var greyTrans_35;
var greyTrans_38;
var greyTrans_45;
var greyTrans_49;
var greyTrans_55;
var greyTrans_65;
var greyTrans_95;
var tint_none;


//lens coating
var scratchResist_0;
var scratchResist_5
var scratchResist_10;
var scratchResist_15;
var scratchResist_20;
var scratchResist_25;
var economyAntiGlare_0;
var economyAntiGlare_11;
var economyAntiGlare_15;
var economyAntiGlare_20;
var economyAntiGlare_25;
var economyAntiGlare_30;
var economyAntiGlare_40;
var economyAntiGlare_50;
var premiumAntiGlare_10;
var premiumAntiGlare_14;
var premiumAntiGlare_16;
var premiumAntiGlare_20;
var premiumAntiGlare_30;
var premiumAntiGlare_31;
var premiumAntiGlare_35;
var premiumAntiGlare_40;
var premiumAntiGlare_50;
var premiumAntiGlare_55;
var premiumAntiGlare_60;
var premiumAntiGlare_70;
var eliteAntiGlare_0;
var eliteAntiGlare_15;
var eliteAntiGlare_19;
var eliteAntiGlare_20;
var eliteAntiGlare_26;
var eliteAntiGlare_30;
var eliteAntiGlare_33;
var eliteAntiGlare_34;
var eliteAntiGlare_36;
var eliteAntiGlare_40;
var eliteAntiGlare_45;
var eliteAntiGlare_46;
var eliteAntiGlare_50;
var eliteAntiGlare_55;
var eliteAntiGlare_65;
var eliteAntiGlare_70;
var eliteAntiGlare_80;
var coating_na;

document.observe("dom:loaded", function() {
  
  
  
	//get all selects into vars   
	ddList = $('product-options-wrapper-prescription').select('dd');

	//---------------------------------------
	// Frame Sizes:
	//---------------------------------------
	//frameSizeHolder = $(ddList[0]);
	//frameSizes = $(ddList[0]).select('li');

// 
//
//

	//---------------------------------------
	// Lens Types:
	//---------------------------------------
	lensTypesHolder = $(ddList[0]);
	lensTypes = $(ddList[0]).select('li');
	
	singleVision 		= lensTypes[0];
	bifocal 	= lensTypes[1];
	varifocal   = lensTypes[2];
	frameOnly  = lensTypes[3];

	//---------------------------------------
	// Glasses for:
	//---------------------------------------
	glassesForHolder = $(ddList[1]);
	glassesFor = $(ddList[1]).select('li');

	distance	= glassesFor[0];
	reading		= glassesFor[1];
	vdu			= glassesFor[2];
	for_na		= glassesFor[3];
	
	//---------------------------------------
	// Lens thickness:
	//---------------------------------------
	lensThicknessHolder = $(ddList[2]);
	lensThickness = $(ddList[2]).select('li');

	standard_0	= lensThickness[0];
	standard_24	= lensThickness[1];
	thin_34		= lensThickness[2];
	thin_45		= lensThickness[3];
	thin_104	= lensThickness[4];
	thinner_54	= lensThickness[5];
	thinner_71	= lensThickness[6];
	thinnest_134= lensThickness[7];
	thinnest_135= lensThickness[8];
	thickness_na= lensThickness[9];

	//---------------------------------------
	// Varifocals:
	//---------------------------------------
	varifocalsHolder = $(ddList[3]);
	varifocals = $(ddList[3]).select('li');
	
	basic_0			= varifocals[0];
	basic_44		= varifocals[1];
	advanced_74		= varifocals[2];
	advanced_88		= varifocals[3];
	advanced_94		= varifocals[4];
	advanced_114	= varifocals[5];
	advanced_140	= varifocals[6];
	premium_134		= varifocals[7];
	premium_138		= varifocals[8];
	premium_144		= varifocals[9];
	elite_188		= varifocals[10];
	elite_194		= varifocals[11];
	elite_210		= varifocals[12];
	elite_234		= varifocals[13];
	varifocals_na	= varifocals[14];

	//---------------------------------------
	// Lens tints:
	//---------------------------------------
	lensTintsHolder = $(ddList[4]);
	lensTints = $(ddList[4]).select('li');

	brownPhoto_15			= lensTints[0];
	brownPhoto_29			= lensTints[1];
	brownPhoto_45			= lensTints[2];
	greyPhoto_15			= lensTints[3];
	greyPhoto_29			= lensTints[4];
	greyPhoto_45			= lensTints[5];
	brownTrans_25			= lensTints[6];
	brownTrans_35			= lensTints[7];
	brownTrans_38			= lensTints[8];
	brownTrans_45			= lensTints[9];
	brownTrans_49			= lensTints[10];
	brownTrans_55			= lensTints[11];
	brownTrans_65			= lensTints[12];
	brownTrans_95			= lensTints[13];
	greyTrans_25			= lensTints[14];
	greyTrans_35			= lensTints[15];
	greyTrans_38			= lensTints[16];
	greyTrans_45			= lensTints[17];
	greyTrans_49			= lensTints[18];
	greyTrans_55			= lensTints[19];
	greyTrans_65			= lensTints[20];
	greyTrans_95			= lensTints[21];
	tint_none				= lensTints[22];
		
	//---------------------------------------
	// Lens coatings:
	//---------------------------------------
	lensCoatingsHolder = $(ddList[5]);
	lensCoatings = $(ddList[5]).select('li');

	scratchResist_0			= lensCoatings[0];
	scratchResist_5			= lensCoatings[1];
	scratchResist_10		= lensCoatings[2];
	scratchResist_15		= lensCoatings[3];
	scratchResist_20		= lensCoatings[4];
	scratchResist_25		= lensCoatings[5];
	economyAntiGlare_0		= lensCoatings[6];
	economyAntiGlare_11		= lensCoatings[7];
	economyAntiGlare_15		= lensCoatings[8];
	economyAntiGlare_20		= lensCoatings[9];
	economyAntiGlare_25		= lensCoatings[10];
	economyAntiGlare_30		= lensCoatings[11];
	economyAntiGlare_40		= lensCoatings[12];
	economyAntiGlare_50		= lensCoatings[13];
	premiumAntiGlare_10		= lensCoatings[14];
	premiumAntiGlare_14		= lensCoatings[15];
	premiumAntiGlare_16		= lensCoatings[16];
	premiumAntiGlare_20		= lensCoatings[17];
	premiumAntiGlare_30		= lensCoatings[18];
	premiumAntiGlare_31		= lensCoatings[19];
	premiumAntiGlare_35		= lensCoatings[20];
	premiumAntiGlare_40		= lensCoatings[21];
	premiumAntiGlare_50		= lensCoatings[22];
	premiumAntiGlare_55		= lensCoatings[23];
	premiumAntiGlare_60		= lensCoatings[24];
	premiumAntiGlare_70		= lensCoatings[25];
	eliteAntiGlare_0		= lensCoatings[26];
	eliteAntiGlare_15		= lensCoatings[27];
	eliteAntiGlare_19		= lensCoatings[28];
	eliteAntiGlare_20		= lensCoatings[29];
	eliteAntiGlare_26		= lensCoatings[30];
	eliteAntiGlare_30		= lensCoatings[31];
	eliteAntiGlare_33		= lensCoatings[32];
	eliteAntiGlare_34		= lensCoatings[33];
	eliteAntiGlare_36		= lensCoatings[34];
	eliteAntiGlare_40		= lensCoatings[35];
	eliteAntiGlare_45		= lensCoatings[36];
	eliteAntiGlare_46		= lensCoatings[37];
	eliteAntiGlare_50		= lensCoatings[38];
	eliteAntiGlare_55		= lensCoatings[39];
	eliteAntiGlare_65		= lensCoatings[40];
	eliteAntiGlare_70		= lensCoatings[41];
	eliteAntiGlare_80		= lensCoatings[42];
	coating_na				= lensCoatings[43];

	//---------------------------------------
	// Prescriptions:
	//---------------------------------------

	right_sph		 = $(ddList[6]);
	right_cyl		 = $(ddList[7]);
	right_axis		 = $(ddList[8]);
	right_near		 = $(ddList[9]);
	left_sph		 = $(ddList[10]);
	left_cyl		 = $(ddList[11]);
	left_axis		 = $(ddList[12]);
	left_near		 = $(ddList[13]);
	pupil_distance	 = $(ddList[14]);

	
	//set defaults:
	var right_sph_options = ddList[6].select('option');
	right_sph_options[33].selected = true;

	var left_sph_options = ddList[10].select('option');
	left_sph_options[33].selected = true;


	var right_cyl_options = ddList[7].select('option');
	right_cyl_options[25].selected = true;
	
	var left_cyl_options = ddList[11].select('option');
	left_cyl_options[25].selected = true;
	
	
	var right_axis_options = ddList[8].select('option');
	right_axis_options[1].selected = true;
	
	var left_axis_options = ddList[12].select('option');
	left_axis_options[1].selected = true;
	
	
	var right_near_options = ddList[9].select('option');
	right_near_options[1].selected = true;
	
	var left_near_options = ddList[13].select('option');
	left_near_options[1].selected = true;
	
	var pupil_distance_options = ddList[14].select('option');
	pupil_distance_options[12].selected = true;
	
	//if a tint is clicked, uncheck all coatings
	//tintClickUncheckCoatings(); 
	 
	allLoaded = "Y";	
	
	//if a tint is changed, uncheck all coatings
	var lastTint = "";

	//hide the description   
	ddListFixed = $('product-options-wrapper').select('dd');
	
	var description = $(ddListFixed[0]);
	hideSection(description);
	
	//image popups
	goPopupsVarifocals();
	goPopupsTints();
	goPopupsCoatings();
	goPopupsThickness();
	
	//hide both sections and show on button presses
	$('product-options-wrapper').hide();	
	$('product-options-wrapper-prescription').hide();	
	$('pd_info_hide').hide();
	$('pd_info').hide();
	$('coating_info_hide').hide();
	$('coating_info').hide();
	

	
	$$("#but_add_lenses").each(function(el) {
		el.observe("click", function(event) {
						
			
		$('product-options-wrapper').show();	
		$('product-options-wrapper-prescription').show();
		$$(".product-options-bottom").each(function(elmt) { $(elmt).show() });
		window.location.hash="but_add_lenses";
			Event.stop(event);
		});
		
	});
	
	$$("#but_no_lenses").each(function(el) {
		el.observe("click", function(event) {
			$('product-options-wrapper').show();	
			$('product-options-wrapper-prescription').hide();	
			$$(".product-options-bottom").each(function(elmt) { $(elmt).show() });
			window.location.hash="but_add_lenses";
			
			//reset all values
			uncheckSet(lensTypesHolder);
			uncheckSet(glassesForHolder);
			uncheckSet(lensThicknessHolder);
			uncheckSet(lensTintsHolder);
			uncheckSet(lensCoatingsHolder);
			uncheckSet(varifocalsHolder);
		
			Event.stop(event);
		});
	});		
	
	$$(".product-options-bottom").each(function(elmt) { $(elmt).hide() });

	
	$('pd_info_show').observe('click', function(e) {
			$('pd_info').show();
			$('pd_info_hide').show();
			$('pd_info_show').hide();
	});
	
	$('pd_info_hide').observe('click', function(e) {
			$('pd_info').hide();
			$('pd_info_hide').hide();
			$('pd_info_show').show();
	});
	
	
	$('coating_info_show').observe('click', function(e) {
			$('coating_info').show();
			$('coating_info_hide').show();
			$('coating_info_show').hide();
	});
	
	$('coating_info_hide').observe('click', function(e) {
			$('coating_info').hide();
			$('coating_info_hide').hide();
			$('coating_info_show').show();
	});
	
	
});




//--------------------------------------------
//--------------------------------------------
// updateCustomOptionsFrames
//--------------------------------------------
//--------------------------------------------
// This is the main function called by Magento
// on each field event change.
// All logic cascades in DOM order.
//--------------------------------------------
//--------------------------------------------

function updateCustomOptionsFrames() { 
     
	 //Ensure that this script is ready (as
	 //it is called externally by Magento)
	 if (allLoaded == "Y")
	 {
		//The custom options container is hidden in css,
		//so hide all non-required sections and then 
		//show the the container			
		hideSection(glassesForHolder);
		hideSection(lensThicknessHolder);
		hideSection(lensTintsHolder);
		hideSection(lensCoatingsHolder);
		hideSection(varifocalsHolder);
		
		//custom - hide frameOnly
		hideBox(frameOnly);
		//hideBox(varifocal);
		
		//Logic for Single Vision:
		if (boxChecked(singleVision))
		{
			
			//hide the other options in this section 			
			hideOthersInSection(lensTypesHolder, singleVision);
			
			//set varifocal to not applicable
			checkBox(varifocals_na);
			
			//show the next section
			showSection(glassesForHolder);
			hideBox(for_na);
			uncheckBox(for_na);
			
			if (boxChecked(distance) || boxChecked(reading) || boxChecked(vdu))
			{
				
				hideUncheckedInSection(glassesForHolder);
				
				//lens thickness options
				showSection(lensThicknessHolder);
				hideAllInSection(lensThicknessHolder);
				showBox(standard_0);
				showBox(thin_34);
				showBox(thinner_54);
				showBox(thinnest_134);
				
				if (boxChecked(standard_0))
				{
					hideOthersInSection(lensThicknessHolder, standard_0);

					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(scratchResist_0);
					showBox(brownPhoto_29);
					showBox(greyPhoto_29);
					showBox(brownTrans_38);
					showBox(greyTrans_38);
					
					/*if (boxChecked(scratchResist_0))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(brownPhoto_29, greyPhoto_29, brownTrans_38, greyTrans_38);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}*/
					if (boxChecked(brownPhoto_29))
					{
						uncheckOthersInSection(lensTintsHolder,brownPhoto_29);
						hideAllInSection(lensCoatingsHolder);
						showBox(brownPhoto_29);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownPhoto_29);
						chooseCheapestCoating(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0,economyAntiGlare_11);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyPhoto_29))
					{
						uncheckOthersInSection(lensTintsHolder,greyPhoto_29);
						hideAllInSection(lensCoatingsHolder);
						showBox(greyPhoto_29);

						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyPhoto_29);
						chooseCheapestCoating(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0, economyAntiGlare_11);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(brownTrans_38))
					{
						uncheckOthersInSection(lensTintsHolder,brownTrans_38);
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_38);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_38);
						chooseCheapestCoating(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0,premiumAntiGlare_16,eliteAntiGlare_26);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_38))
					{
						uncheckOthersInSection(lensTintsHolder,greyTrans_38);
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_38);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_38);
						chooseCheapestCoating(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0,premiumAntiGlare_16,eliteAntiGlare_26);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(scratchResist_0);

						var visibleArray = Array(scratchResist_0,economyAntiGlare_20,premiumAntiGlare_30,eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(thin_34))
				{
					hideOthersInSection(lensThicknessHolder, thin_34);

					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_45);
					showBox(greyTrans_45);
					
					if (boxChecked(brownTrans_45))
					{
						
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_45);
						chooseCheapestCoating(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0, premiumAntiGlare_20, eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}	
					else if (boxChecked(greyTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_45);
						
						chooseCheapestCoating(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0, premiumAntiGlare_20, eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_0);
						
						var visibleArray = Array(scratchResist_0,economyAntiGlare_0,premiumAntiGlare_10,eliteAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(thinner_54))
				{
					hideOthersInSection(lensThicknessHolder, thinner_54);

					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_95);
					showBox(greyTrans_95);
					
					if (boxChecked(brownTrans_95))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_95);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_95);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0, economyAntiGlare_0, premiumAntiGlare_10, eliteAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}	
					else if (boxChecked(greyTrans_95))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_95);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_95);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0, economyAntiGlare_0, premiumAntiGlare_10, eliteAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_0);
						
						var visibleArray = Array(scratchResist_0,economyAntiGlare_0,premiumAntiGlare_10,eliteAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
						
				}
				else if (boxChecked(thinnest_134))
				{
					hideOthersInSection(lensThicknessHolder, thinnest_134);

					//since there is not an option for tint, tick "none"
					checkBox(tint_none);
					
					chooseCheapestCoating(economyAntiGlare_0);
					
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showBox(scratchResist_0);
					showBox(economyAntiGlare_0);
					showBox(premiumAntiGlare_20);
					showBox(eliteAntiGlare_50);
										
				}
				else
				{
					resetSection(lensTintsHolder);
					resetSection(lensCoatingsHolder);
				}

			}
			else
			{
				showAllInSection(glassesForHolder);
				hideBox(for_na);
			}
		}
		else if (boxChecked(bifocal))
		{	
			//hide the other options in this section 			
			hideOthersInSection(lensTypesHolder, bifocal);
			
			//set varifocal and "for" to not applicable
			checkBox(varifocals_na);
			checkBox(for_na);
			
			//lens thickness options
			showSection(lensThicknessHolder);
			hideAllInSection(lensThicknessHolder);
			showBox(standard_24);
			showBox(thin_104);
			
			if (boxChecked(standard_24))
			{
				hideOthersInSection(lensThicknessHolder, standard_24);

				//show coatings
				showSection(lensCoatingsHolder);
				hideAllInSection(lensCoatingsHolder);
				enableSet(lensCoatingsHolder);
				showSection(lensTintsHolder);
				hideAllInSection(lensTintsHolder);
				enableSet(lensTintsHolder);
				showBox(scratchResist_5);
				showBox(brownTrans_55);
				showBox(greyTrans_55);
				
				if (boxChecked(scratchResist_5))
				{
					hideAllInSection(lensCoatingsHolder);
					showBox(scratchResist_5);
					//once final option is checked, show these items and only allow one to be checked:
					var visibleArray = Array(brownTrans_55, greyTrans_55);
					showItems(visibleArray);
					enableItems(visibleArray);
					
					visibleArray.each(function(item){
						if (boxChecked(item))
						{
							//disableItemsInArray(visibleArray);
							enableBox(item);
							
							//extra override for logic on this combination:
							showBox(scratchResist_10);
							checkBox(scratchResist_10);
							hideBox(scratchResist_5);
							uncheckBox(scratchResist_5);	
						}	
					});						
				}
				else if (boxChecked(brownTrans_55))
				{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_55);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_55);
						chooseCheapestCoating(scratchResist_10);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_10,economyAntiGlare_40,premiumAntiGlare_55,eliteAntiGlare_65);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
				else if (boxChecked(greyTrans_55))
				{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_55);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_55);
						chooseCheapestCoating(scratchResist_10);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_10,economyAntiGlare_40,premiumAntiGlare_55,eliteAntiGlare_65);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
				}
				else if (boxChecked(tint_none))
				{
					uncheckOthersInSection(lensTintsHolder,tint_none);
					hideAllInSection(lensCoatingsHolder);
						
					//if no coating selected, choose cheapest
					tintChangeUncheckCoatings(tint_none);
					chooseCheapestCoating(scratchResist_10);
						
					var visibleArray = Array(scratchResist_10,economyAntiGlare_30,premiumAntiGlare_40,eliteAntiGlare_50);
					showItems(visibleArray);
					enableItems(visibleArray);					
				}
			}
			else if (boxChecked(thin_104))
			{
				hideOthersInSection(lensThicknessHolder, thin_104);

				//since there is not an option for tint, tick "none"
				checkBox(tint_none);
					
				//show coatings
				showSection(lensCoatingsHolder);
				hideAllInSection(lensCoatingsHolder);
				enableSet(lensCoatingsHolder);
				showBox(economyAntiGlare_0);
				showBox(premiumAntiGlare_10);
				showBox(eliteAntiGlare_20);						
				
				/*
				hideOthersInSection(lensThicknessHolder, thin_104);

				//show coatings
				showSection(lensCoatingsHolder);
				hideAllInSection(lensCoatingsHolder);
				enableSet(lensCoatingsHolder);
				showSection(lensTintsHolder);
				hideAllInSection(lensTintsHolder);
				enableSet(lensTintsHolder);
				
				//once final option is checked, show these items and only allow one to be checked:
				var visibleArray = Array(economyAntiGlare_0, premiumAntiGlare_10, eliteAntiGlare_20);
				showItems(visibleArray);
				enableItems(visibleArray);
					
				visibleArray.each(function(item){
					if (boxChecked(item))
					{
						//disableItemsInArray(visibleArray);
						enableBox(item);	
					}	
				});		*/						
			}
			else
			{
				resetSection(lensTintsHolder);
				resetSection(lensCoatingsHolder);
			}
		}
		else if (boxChecked(varifocal))
		{
			//set varifocal to not applicable
			uncheckBox(varifocals_na);

			//set "for" to not applicable
			checkBox(for_na);
	
			//hide the other options in this section 			
			hideOthersInSection(lensTypesHolder, varifocal);
			
			//lens thickness options
			showSection(lensThicknessHolder);
			hideAllInSection(lensThicknessHolder);
			showBox(standard_0);
			showBox(thin_45);
			showBox(thinner_71);
			showBox(thinnest_135);
			
			if (boxChecked(standard_0))
			{
				hideOthersInSection(lensThicknessHolder, standard_0);

				//show varifocal
				showSection(varifocalsHolder);
				hideAllInSection(varifocalsHolder);
				enableSet(varifocalsHolder);
				showBox(basic_44);	
				showBox(advanced_74);
				showBox(premium_134);
				showBox(elite_194);

				if (boxChecked(basic_44))
				{
					hideOthersInSection(varifocalsHolder, basic_44);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(scratchResist_10);	
					showBox(brownPhoto_45);
					showBox(greyPhoto_45);
					
					/*if (boxChecked(scratchResist_10))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(scratchResist_10);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(brownPhoto_45, greyPhoto_45);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}*/
					if (boxChecked(brownPhoto_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownPhoto_45);
							//once final option is checked, show these items and only allow one to be checked:
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownPhoto_45);
						chooseCheapestCoating(scratchResist_10);
						
						var visibleArray = Array(scratchResist_10,economyAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyPhoto_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyPhoto_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyPhoto_45);
						chooseCheapestCoating(scratchResist_10);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_10, economyAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
									enableBox(item);	
								}	
							});						
					}
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(scratchResist_10);
						
						var visibleArray = Array(scratchResist_10,economyAntiGlare_30,premiumAntiGlare_40,eliteAntiGlare_50);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
					
				}
				else if (boxChecked(advanced_74))
				{
					hideOthersInSection(varifocalsHolder, advanced_74);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(scratchResist_10);
					showBox(brownTrans_65);
					showBox(greyTrans_65);
					
					/*if (boxChecked(scratchResist_10))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_45);
							
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(brownTrans_65,greyTrans_65);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}*/
					 if (boxChecked(brownTrans_65))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_65);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_65);
						chooseCheapestCoating(scratchResist_10);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_10,economyAntiGlare_40,premiumAntiGlare_60,eliteAntiGlare_80);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});
						
						
							//once final option is checked, show these items and only allow one to be checked:
						
						
						var visibleArray = Array(scratchResist_10,economyAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);
						
																	
					}
					else if (boxChecked(greyTrans_65))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_65);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_65);
						chooseCheapestCoating(scratchResist_10);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_10,economyAntiGlare_40,premiumAntiGlare_60,eliteAntiGlare_80);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(scratchResist_10);
						
						var visibleArray = Array(scratchResist_10,economyAntiGlare_40,premiumAntiGlare_60,eliteAntiGlare_80);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(premium_134))
				{
					hideOthersInSection(varifocalsHolder,premium_134);

					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(scratchResist_10);
					showBox(brownTrans_35);
					showBox(greyTrans_35);
					
					/*if (boxChecked(scratchResist_10))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(scratchResist_10);
							
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(brownTrans_45,brownTrans_45);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}*/
					if (boxChecked(brownTrans_35))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_35);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_35);
						chooseCheapestCoating(scratchResist_20);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_20, economyAntiGlare_50,premiumAntiGlare_60,eliteAntiGlare_80);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_35))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_35);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_35);
						chooseCheapestCoating(scratchResist_20);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_20, economyAntiGlare_50,premiumAntiGlare_60,eliteAntiGlare_80);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(scratchResist_10);
						
						var visibleArray = Array(scratchResist_10,economyAntiGlare_40,premiumAntiGlare_60,eliteAntiGlare_80);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(elite_194))
				{
					hideOthersInSection(varifocalsHolder,elite_194);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(scratchResist_0);
					showBox(brownTrans_25);
					showBox(greyTrans_25);
					
					if (boxChecked(scratchResist_0))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(scratchResist_0);
							
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(brownTrans_35,greyTrans_35);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(brownTrans_25))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_25);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_25);
						chooseCheapestCoating(scratchResist_10);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_10,economyAntiGlare_40,premiumAntiGlare_50,eliteAntiGlare_70);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_25))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_25);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_25);
						chooseCheapestCoating(scratchResist_10);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_10,economyAntiGlare_40,premiumAntiGlare_50,eliteAntiGlare_70);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(scratchResist_10);
						
						var visibleArray = Array(scratchResist_10,economyAntiGlare_30,premiumAntiGlare_50,eliteAntiGlare_70);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else
				{
					resetSection(lensTintsHolder);
					resetSection(lensCoatingsHolder);
				}
				
			}
			else if (boxChecked(thin_45))
			{
				hideOthersInSection(lensThicknessHolder, thin_45);

				//show varifocal
				showSection(varifocalsHolder);
				hideAllInSection(varifocalsHolder);
				enableSet(varifocalsHolder);
				showBox(basic_44);	
				showBox(advanced_94);
				showBox(premium_144);
				showBox(elite_194);

				if (boxChecked(basic_44))
				{
					hideOthersInSection(varifocalsHolder,basic_44);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(scratchResist_0);	
					showBox(brownPhoto_15);
					showBox(greyPhoto_15);
					
					/*if (boxChecked(scratchResist_0))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(brownPhoto_15, greyPhoto_15);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}*/
					if (boxChecked(brownPhoto_15))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownPhoto_15);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownPhoto_15);
						chooseCheapestCoating(scratchResist_0);
						
							//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0,economyAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyPhoto_15))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyPhoto_15);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyPhoto_15);
						chooseCheapestCoating(scratchResist_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(scratchResist_0, economyAntiGlare_20);
						showItems(visibleArray);
						enableItems(visibleArray);
						
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
									enableBox(item);	
								}	
							});						
						}
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);

						var visibleArray = Array(scratchResist_15,economyAntiGlare_25,premiumAntiGlare_35,eliteAntiGlare_65);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(advanced_94))
				{
					hideOthersInSection(varifocalsHolder,advanced_94);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_45);
					showBox(greyTrans_45);
					
					if (boxChecked(brownTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_14,eliteAntiGlare_34);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_14,eliteAntiGlare_34);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_0);
						
						var visibleArray = Array(scratchResist_0,economyAntiGlare_0,premiumAntiGlare_10,eliteAntiGlare_19);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(premium_144))
				{
					hideOthersInSection(varifocalsHolder,premium_144);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_45);
					showBox(greyTrans_45);
					
					if (boxChecked(brownTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_36);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_36);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_25);
						
						var visibleArray = Array(scratchResist_25,economyAntiGlare_25,premiumAntiGlare_35,eliteAntiGlare_55);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(elite_194))
				{
					hideOthersInSection(varifocalsHolder,elite_194);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_45);
					showBox(greyTrans_45);
					
					if (boxChecked(brownTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_10,eliteAntiGlare_30);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_10,eliteAntiGlare_30);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_0);
						
						var visibleArray = Array(scratchResist_0,economyAntiGlare_0,premiumAntiGlare_10,eliteAntiGlare_15);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else
				{
					resetSection(lensTintsHolder);
					resetSection(lensCoatingsHolder);
				}
				
			}
			else if (boxChecked(thinner_71))
			{
				hideOthersInSection(lensThicknessHolder, thinner_71);

				//show varifocal
				showSection(varifocalsHolder);
				hideAllInSection(varifocalsHolder);
				enableSet(varifocalsHolder);
				showBox(advanced_88);
				showBox(premium_138);
				showBox(elite_188);

				if (boxChecked(advanced_88))
				{
					hideOthersInSection(varifocalsHolder,advanced_88);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);	
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_45);
					showBox(greyTrans_45);
					
					if (boxChecked(brownTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
							//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_0);
						
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(premium_138))
				{
					hideOthersInSection(varifocalsHolder,premium_138);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);	
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_49);
					showBox(greyTrans_49);
					
					if (boxChecked(brownTrans_49))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_49);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_49);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_16,eliteAntiGlare_36);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_49))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_49);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_49);
						chooseCheapestCoating(economyAntiGlare_0);
						
							//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_16,eliteAntiGlare_36);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_15);
						
						var visibleArray = Array(scratchResist_15,economyAntiGlare_15,premiumAntiGlare_31,eliteAntiGlare_45);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(elite_188))
				{
					hideOthersInSection(varifocalsHolder,elite_188);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_49);
					showBox(greyTrans_49);
					
					if (boxChecked(brownTrans_49))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_49);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_49);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_14,eliteAntiGlare_34);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_49))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_49);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_49);
						chooseCheapestCoating(economyAntiGlare_0);
						
							//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_14,eliteAntiGlare_34);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_0);
						
						var visibleArray = Array(scratchResist_0,economyAntiGlare_0,premiumAntiGlare_14,eliteAntiGlare_33);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else
				{
					resetSection(lensTintsHolder);
					resetSection(lensCoatingsHolder);
				}

			}
			else if (boxChecked(thinnest_135))
			{
				hideOthersInSection(lensThicknessHolder, thinnest_135);

				/*
				//show varifocal
				showSection(varifocalsHolder);
				hideAllInSection(varifocalsHolder);
				enableSet(varifocalsHolder);
				showBox(elite_194);

				//show coatings
				showSection(lensCoatingsHolder);
				hideAllInSection(lensCoatingsHolder);
				enableSet(lensCoatingsHolder);
				showBox(eliteAntiGlare_0);
				*/
				
				//show varifocal
				showSection(varifocalsHolder);
				hideAllInSection(varifocalsHolder);
				enableSet(varifocalsHolder);
				showBox(advanced_94);
				showBox(premium_144);
				showBox(elite_194);
				
				if (boxChecked(advanced_94))
				{
					hideOthersInSection(varifocalsHolder,advanced_94);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);	
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_45);
					showBox(greyTrans_45);
					
					if (boxChecked(brownTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
							//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_0);
						
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(premium_144))
				{
					hideOthersInSection(varifocalsHolder,premium_144);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);	
					showSection(lensTintsHolder);
					hideAllInSection(lensTintsHolder);
					enableSet(lensTintsHolder);
					showBox(brownTrans_45);
					showBox(greyTrans_45);
					
					if (boxChecked(brownTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(brownTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(brownTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
						//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}
					else if (boxChecked(greyTrans_45))
					{
						hideAllInSection(lensCoatingsHolder);
						showBox(greyTrans_45);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(greyTrans_45);
						chooseCheapestCoating(economyAntiGlare_0);
						
							//once final option is checked, show these items and only allow one to be checked:
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);
							
						visibleArray.each(function(item){
							if (boxChecked(item))
							{
								//disableItemsInArray(visibleArray);
								enableBox(item);	
							}	
						});						
					}					
					else if (boxChecked(tint_none))
					{
						uncheckOthersInSection(lensTintsHolder,tint_none);
						hideAllInSection(lensCoatingsHolder);
						
						//if no coating selected, choose cheapest
						tintChangeUncheckCoatings(tint_none);
						chooseCheapestCoating(economyAntiGlare_0);
						
						var visibleArray = Array(economyAntiGlare_0,premiumAntiGlare_20,eliteAntiGlare_40);
						showItems(visibleArray);
						enableItems(visibleArray);					
					}
				}
				else if (boxChecked(elite_194))
				{
					hideOthersInSection(varifocalsHolder,elite_194);
						
					//show coatings
					showSection(lensCoatingsHolder);
					hideAllInSection(lensCoatingsHolder);
					enableSet(lensCoatingsHolder);
					showBox(eliteAntiGlare_0);
				}
			}
			else
			{
				resetSection(lensTintsHolder);
				resetSection(lensCoatingsHolder);
			}
						
		}
		else if (boxChecked(frameOnly))
		{	
			//set non-applicables
			checkBox(for_na);
			checkBox(varifocals_na);
			checkBox(tint_none);
			checkBox(thickness_na);
			checkBox(coating_na);
			
			//hide the other options in this section 			
			hideOthersInSection(lensTypesHolder, frameOnly);
			//set "not applicable" values for all
			
			hideSection(right_sph);
			hideSection(right_cyl);
			hideSection(right_axis);
			hideSection(right_near);
			hideSection(left_sph);
			hideSection(left_cyl);
			hideSection(left_axis);
			hideSection(left_near);
			hideSection(pupil_distance);
			
		}
		else
		{
			showAllInSection(lensTypesHolder);
			hideSection(glassesForHolder);
			resetSection(lensTintsHolder);
			resetSection(lensCoatingsHolder);
			hideBox(frameOnly);
			//hideBox(varifocal);
		}
		
		//set the last tint var to compare when the tint changes
		updateLastTint();
	}
}
  

function selectOptionTest() {
	//var options = $$('select#select_59 option');
	
	var optionList = $$('#product-options-wrapper select').first()
		
	optionList[1].selected = true;
}

function selectOptionTest2() {
	var optionList = $('product-options-wrapper').select('select');
	
	optionList.each(function(node){
      alert("First : " + node.nodeName + ': ' + node.innerHTML);
   });
	
	//options[3].selected = true;
}


//--------------------------------------------
//--------------------------------------------
// Utils
//--------------------------------------------
//--------------------------------------------


//---------------------------------------
// Check a checkbox
//---------------------------------------
function checkBox(in_item) {
	$(in_item).down('input').checked = true;
}

//---------------------------------------
// Uncheck a checkbox
//---------------------------------------
function uncheckBox(in_item) {
	$(in_item).down('input').checked = false;
}

//---------------------------------------
// Disable a checkbox
//---------------------------------------
function disableBox(in_item) {
	Field.disable($(in_item).down('input'));	
}

//---------------------------------------
// Enable a checkbox
//---------------------------------------
function enableBox(in_item) {
	Field.enable($(in_item).down('input'));	
}

//---------------------------------------
// Hide a checkbox
//---------------------------------------
function hideBox(in_item) {
	$(in_item).hide();
}

//---------------------------------------
// Show a checkbox
//---------------------------------------
function showBox(in_item) {
	$(in_item).show();
}

//---------------------------------------
// Hide a section
//---------------------------------------
function hideSection(in_section) {
	//hide this item
	$(in_section).hide();
	//hide the header for this section
	$(in_section).previous().hide();
}

//---------------------------------------
// Show a section
//---------------------------------------
function showSection(in_section) {
	//hide this item
	$(in_section).show();
	//hide the header for this section
	$(in_section).previous().show();
	
	if (in_section == lensTintsHolder)
	{
		//preselect "none" if no box ticked
		if (!anyCheckedInSection(lensTintsHolder))
		{
			checkBox(tint_none);
		}
	}
}

//---------------------------------------
// Disable all checkboxes in a set
//---------------------------------------
function disableSet(in_set) {
	var itemsArray = $(in_set).select('li');
	itemsArray.each(function(item){
		disableBox(item);
	});
}

//---------------------------------------
// Enable all checkboxes in a set
//---------------------------------------
function enableSet(in_set) {
	var itemsArray = $(in_set).select('li');
	itemsArray.each(function(item){
		enableBox(item);
	});
}

//---------------------------------------
// Uncheck all options in a set all checkboxes in a set
//---------------------------------------
function uncheckSet(in_set) {
	var itemsArray = $(in_set).select('li');
	itemsArray.each(function(item){
		uncheckBox(item);
	});
}

//---------------------------------------
// Return the bool of a checkbox
//---------------------------------------
function boxChecked(in_field) {
	return $(in_field).down('input').checked;
}

//---------------------------------------
// Hide the other items in this section
//---------------------------------------
function hideOthersInSection(in_section, in_item) {
	var itemsArray = $(in_section).select('li');
	itemsArray.each(function(item){
		hideBox(item);	
	});
	//reactiveate this one...
	showBox(in_item);	
}



//---------------------------------------
// Hide the unchecked items in this section
//---------------------------------------
function hideUncheckedInSection(in_section) {
	var itemsArray = $(in_section).select('li');
	itemsArray.each(function(item){
		if (!boxChecked(item))
		{
			hideBox(item);
		}
	});
}

//---------------------------------------
// Show the other items in this section
//---------------------------------------
function showAllInSection(in_section) {
	var itemsArray = $(in_section).select('li');
	itemsArray.each(function(item){
		showBox(item);	
	});
}

//---------------------------------------
// Hide the items in a section
//---------------------------------------
function hideAllInSection(in_section) {
	var itemsArray = $(in_section).select('li');	
	itemsArray.each(function(item){
		hideBox(item);	
	});
	
	if (in_section == lensTintsHolder)
	{
		showBox(tint_none);
	}
}

//---------------------------------------
// Disable the other items in this section
//---------------------------------------
function disableOthersInSection(in_section, in_item) {
	var itemsArray = $(in_section).select('li');
	itemsArray.each(function(item){
		disableBox(item);	
	});
	//reactiveate this one...
	enableBox(in_item);
}

//---------------------------------------
// Uncheck the other items in this section
//---------------------------------------
function uncheckOthersInSection(in_section, in_item) {
	var itemsArray = $(in_section).select('li');
	itemsArray.each(function(item){
		uncheckBox(item);
	});
	//reactiveate this one...
	checkBox(in_item);	
}

//---------------------------------------
// Determine if any element in the section is checked
//---------------------------------------
function anyCheckedInSection(in_section) {
	var itemsArray = $(in_section).select('li');
	var anyChecked = false;
	itemsArray.each(function(item){
		if (boxChecked(item))
		{
			anyChecked = true;
		}
	});
	return anyChecked;
}

//---------------------------------------
// Disable the other items in this section
//---------------------------------------
function disableItemsInArray(in_items) {
	in_items.each(function(item){
		disableBox(item);	
	});
}

//---------------------------------------
// Show all incoming items in array 
//---------------------------------------
function showItems(in_items) {
	in_items.each(function(item){
		showBox(item);
	});
}

//---------------------------------------
// Enable all incoming items in array 
//---------------------------------------
function enableItems(in_items) {
	in_items.each(function(item){
		enableBox(item);
	});
}

//---------------------------------------
// Reset all elements in section
//---------------------------------------
function resetSection(in_section) {
	//uncheck and enable all items in section
	var itemsArray = $(in_section).select('li');
	itemsArray.each(function(item){
		uncheckBox(item);
		enableBox(item);
	});
}

//---------------------------------------
// Reset all elements in section
//---------------------------------------
function tintChangeUncheckCoatings(in_tint) {	
	//console.log ("	comparing " + in_tint + " to " + this.lastTint);
	if (in_tint != this.lastTint)
	{
		//console.log ("--tints do not match");
		uncheckSet(lensCoatingsHolder);
		//console.log ("unchecking lens coatings");
	}
	else
	{
		//console.log ("++tints match!!");
	}
}

//---------------------------------------
// Update the last tint
//---------------------------------------
function updateLastTint() {
	var itemsArray = $(lensTintsHolder).select('li');
	itemsArray.each(function(item){
		if (boxChecked(item))
		{
			this.lastTint = item;
		}
	});
}

//---------------------------------------
// Choose the cheapset coating
//---------------------------------------

function chooseCheapestCoating(in_coating) {
	if (!anyCheckedInSection(lensCoatingsHolder))
	{
		checkBox(in_coating);
	}
}

//---------------------------------------
// Show tooltip popups
//---------------------------------------
function goPopupsVarifocals() {
	
	$$(".varifocals_image").each(function(elmt) {
		
		var popup =  $(elmt).next();
		$(popup).hide();
		  
		$(elmt).observe('mouseover', function(e) {
		  var popup =  $(elmt).next();
		  $(popup).show();
		});
		
		$(elmt).observe('mouseout', function(e) {
		  var popup =  $(elmt).next();
		  $(popup).hide();
		});

	});
}

//---------------------------------------
// Show tooltip popups
//---------------------------------------
function goPopupsTints() {
	
	$$(".tints_image").each(function(elmt) {
		
		var popup =  $(elmt).next();
		$(popup).hide();
		  
		$(elmt).observe('mouseover', function(e) {
		  var popup =  $(elmt).next();
		  $(popup).show();
		});
		
		$(elmt).observe('mouseout', function(e) {
		  var popup =  $(elmt).next();
		  $(popup).hide();
		});

	});
}

//---------------------------------------
// Show tooltip popups
//---------------------------------------
function goPopupsCoatings() {
	
	$$(".coating_image").each(function(elmt) {
		
		var popup =  $(elmt).next();
		$(popup).hide();
		  
		$(elmt).observe('mouseover', function(e) {
		  var popup =  $(elmt).next();
		  $(popup).show();
		});
		
		$(elmt).observe('mouseout', function(e) {
		  var popup =  $(elmt).next();
		  $(popup).hide();
		});

	});
}


//---------------------------------------
// Show tooltip popups
//---------------------------------------
function goPopupsThickness() {
	
	$$(".thickness_image").each(function(elmt) {
		
		var popup =  $(elmt).next();
		$(popup).hide();
		  
		$(elmt).observe('mouseover', function(e) {
		  var popup =  $(elmt).next();
		  $(popup).show();
		});
		
		$(elmt).observe('mouseout', function(e) {
		  var popup =  $(elmt).next();
		  $(popup).hide();
		});

	});
}


