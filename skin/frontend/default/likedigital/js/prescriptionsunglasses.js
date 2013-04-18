function Holder(number,childs){
	this.number = number;
	this.name = 'holder_'+number;
	this.elem = $(this.name);
	this.selectedChild = null;
	this.childs = childs;
	this.hide = function(){
		this.elem.hide();
	};
	this.show = function(){
		this.elem.show();
	};
	this.init = function(){
		this.selectedChild = null;
		var arr = optionValCollection.data[this.number];
		arr.each(function(o,o_index){
			o.show();
			o.uncheck();
		});				
	}
	this.uncheckChilds = function(){
		var arr = optionValCollection.data[this.number];
		arr.each(function(o,o_index){
			o.uncheck();
		});		
	}
	this.showFilteringChilds = function(aShowChild){
		var oChilds = this.childs;
		
		oChilds.each(function(childNumber){
			bHideChild = true;
			if(typeof aShowChild == "undefined"){
				bHideChild = false;				
			}
			else if(aShowChild.length==0){
				bHideChild = false;
			}
			
			if(bHideChild && bHideChild){
				aShowChild.each(function(showChildNumber){
					if(childNumber==showChildNumber){
						bHideChild = false;
					}					
				});				
			}
			
			var currOptionVal = optionValCollection.getOptionValByNumber(childNumber);
			
			if(bHideChild){
				currOptionVal.erase();
			}
			else{
				currOptionVal.show();
			}			
		});						
	}
	
	this.erase = function(){
		this.selectedChild = null;
		this.hide();
		var arr = optionValCollection.data[this.number];
		arr.each(function(o,o_index){
			o.erase();
		});
	}
}

//type : checkbox = 1; radio = 2;
function OptionVal(number,type,price,childHolder){
	this.number = number;
	this.name = 'option_'+number;
	this.container = 'option_'+number+'_c';
	this.elem = $(this.name);
	this.type = type;
	this.price = price;
	this.childHolderNumber = childHolder;
	this.checked = false;
	this.holderNumber = null;
	
	this.getPrice = function(){
		return this.price;
	};
	this.getHolderNumber = function(){
		if(this.holderNumber==null){
			arr = this.number.split('_');
			this.holderNumber = arr[0];
		}
		return this.holderNumber;
	}
	
	this.erase = function(){
		this.uncheck();
		this.hide();
	}
	
	this.uncheck = function(){
		if(this.type==1){
			$(this.name).checked = false;
		}
		else if(this.type==2){
			$(this.name).checked = '';
		}			
	}
	
	this.hide = function(){
		//alert('hide '+this.name);
		$(this.container).hide();
	};
	this.show = function(){
		//alert('show '+this.name);
		$(this.container).show();
	}
}


optionValCollection = new Object();
optionValCollection.data = new Array();
//return OptionVal object
optionValCollection.getOptionValByNumber = function(number){
	//alert(number);
	arr = number.split('_');		
	return optionValCollection.data[arr[0]][arr[1]];
}


holderCollection = new Object();
holderCollection.data = new Array();

//return Holder object
holderCollection.getHolderByNumber = function(number){
	return holderCollection.data[number];
}

function clickOptionVal(optionValNumber,recursiveHide){	
	var currOptionVal = optionValCollection.getOptionValByNumber(optionValNumber);
	var currHolder = holderCollection.getHolderByNumber(currOptionVal.getHolderNumber());
	var eraseAfterHolder = currOptionVal.getHolderNumber();
	
	if(currOptionVal.type == 1){
		if(optionValNumber == currHolder.selectedChild){
			currHolder.selectedChild = null;			
			initHolder(currOptionVal.getHolderNumber());
		}
		else{
			currHolder.selectedChild = optionValNumber;
			currHolder.showFilteringChilds([currHolder.selectedChild]);
		}
		
	}
	else if(currOptionVal.type == 2){
		currHolder.selectedChild = optionValNumber;	
	}
	
	if(currHolder.selectedChild!=null && currOptionVal.childHolderNumber!=null){
		nextHolderNumber = currOptionVal.childHolderNumber;
		// some exception (not show N\A option)
		if(currOptionVal.getHolderNumber()==1006){
			var hold_1005 = holderCollection.getHolderByNumber(1005);
			if(hold_1005.selectedChild != '1005_5'){
				nextHolderNumber = '1008';
			}						
		}		
		if(currOptionVal.getHolderNumber()==1005){			
			if(optionValNumber == '1005_8'){
				var holder_hide_1006 = holderCollection.getHolderByNumber(1006);
				var holder_hide_1007 = holderCollection.getHolderByNumber(1007);
				holder_hide_1006.erase();
				holder_hide_1007.erase();
				nextHolderNumber = '1008';
			}			
		}		
		// end exception		
		initHolder(nextHolderNumber);
		eraseAfterHolder = nextHolderNumber;
	}

	if(typeof recursiveHide == "undefined"){
		if(currOptionVal.childHolderNumber!=null){
			recursiveHideHolderAfter(eraseAfterHolder);
		}
	}
	//getAllPrice();
	changeGeneralPrice();
}

function initHolder(holderNumber){	
	var currHolder = holderCollection.getHolderByNumber(holderNumber);
	var holder_1001 = holderCollection.getHolderByNumber(1001);
	var holder_1002 = holderCollection.getHolderByNumber(1002);
	var holder_1003 = holderCollection.getHolderByNumber(1003);
	var holder_1004 = holderCollection.getHolderByNumber(1004);
	var holder_1005 = holderCollection.getHolderByNumber(1005);
	var holder_1006 = holderCollection.getHolderByNumber(1006);
	var holder_1007 = holderCollection.getHolderByNumber(1007);
	
	var aShowChilds = [];
	if(holderNumber=='1001'){
		//currHolder.showFilteringChilds(aShowChilds);
	}
	if(holderNumber=='1002'){		
		//currHolder.showFilteringChilds(aShowChilds);
	}	
	if(holderNumber=='1003'){
		if(holder_1001.selectedChild == '1001_3'){
			aShowChilds = ['1003_1','1003_3','1003_5','1003_7'];
		}
		else if(holder_1001.selectedChild == '1001_2'){
			aShowChilds = ['1003_2','1003_4'];
		}
		else{
			aShowChilds = ['1003_2','1003_4','1003_6','1003_8'];
		}
		//currHolder.showFilteringChilds(aShowChilds);
	}
	if(holderNumber=='1004'){
		if(holder_1003.selectedChild == '1003_1'){
			aShowChilds = ['1004_1','1004_3','1004_7','1004_9'];
		}
		else if(holder_1003.selectedChild == '1003_3'){
			aShowChilds = ['1004_1','1004_4','1004_6','1004_9'];
		}
		else if(holder_1003.selectedChild == '1003_5'){
			aShowChilds = ['1004_2','1004_5','1004_8'];
		}
		else if(holder_1003.selectedChild == '1003_7'){
			aShowChilds = ['1004_6','1004_9'];
		}
		//currHolder.showFilteringChilds(aShowChilds);
	}
	if(holderNumber=='1005'){
		if(holder_1003.selectedChild == '1003_2'){
			aShowChilds = ['1005_3','1005_5','1005_6','1005_8'];
		}
		else if(holder_1003.selectedChild == '1003_4'){
			aShowChilds = ['1005_4','1005_5','1005_6','1005_7'];
		}
		else if(holder_1003.selectedChild == '1003_6'){
			aShowChilds = ['1005_2','1005_5','1005_6'];
		}
		else if(holder_1003.selectedChild == '1003_8'){
			aShowChilds = ['1005_5','1005_6'];
		}
		// for varifocal
		else if(holder_1003.selectedChild == '1003_1'){
			aShowChilds = ['1005_1','1005_5','1005_6','1005_8'];
		}
		else if(holder_1003.selectedChild == '1003_3'){
			aShowChilds = ['1005_1','1005_5','1005_6'];
		}
		else if(holder_1003.selectedChild == '1003_5'){
			aShowChilds = ['1005_1','1005_5','1005_6'];
		}
		else if(holder_1003.selectedChild == '1003_7'){
			aShowChilds = ['1005_5','1005_6'];
		}
		//currHolder.showFilteringChilds(aShowChilds);		
	}
	if(holderNumber=='1006'){
		if( (holder_1005.selectedChild == '1005_1') || (holder_1005.selectedChild == '1005_2') || (holder_1005.selectedChild == '1005_3')|| (holder_1005.selectedChild == '1005_4')){
			aShowChilds = ['1006_1','1006_2'];
		}
		else if(holder_1005.selectedChild == '1005_6'){
			aShowChilds = ['1006_1','1006_2'];
		}
		else if(holder_1005.selectedChild == '1005_5'){			
			if(holder_1003.selectedChild == '1003_8'){				
				aShowChilds = ['1006_1','1006_2'];
			}
			else{
				aShowChilds = ['1006_1','1006_2','1006_3'];
			}
		}
		else if(holder_1005.selectedChild == '1005_7'){
			aShowChilds = ['1006_1','1006_2'];
		}		
		else if(holder_1005.selectedChild == '1005_8'){
			aShowChilds = ['1006_4'];
		}		
		//currHolder.showFilteringChilds(aShowChilds);
	}
	if(holderNumber=='1007'){
		if(holder_1005.selectedChild == '1005_5'){
			aShowChilds = ['1007_1','1007_2','1007_3','1007_4'];
		}
		else{
			aShowChilds = ['1007_5'];
		}
		//currHolder.showFilteringChilds(aShowChilds);
	}
	if(holderNumber=='1008'){
		// 1,5 standard	
		if(holder_1003.selectedChild == '1003_2'){
			if(holder_1005.selectedChild == '1005_3'){
				aShowChilds = ['1008_1','1008_4'];
			}
			else{
				aShowChilds = ['1008_1','1008_5'];
			}
		}		
		// 1,6 thin
		else if(holder_1003.selectedChild == '1003_4'){
			if(holder_1005.selectedChild == '1005_4'){
				aShowChilds = ['1008_1','1008_3'];
			}
			else if(holder_1005.selectedChild == '1005_5'){
				aShowChilds = ['1008_1','1008_5'];
			}
			else if(holder_1005.selectedChild == '1005_6'){
				aShowChilds = ['1008_1','1008_6'];
			}
			else if(holder_1005.selectedChild == '1005_7'){
				aShowChilds = ['1008_1','1008_3'];
			}			
		}

		// 1,67
		else if(holder_1003.selectedChild == '1003_6'){
			if(holder_1005.selectedChild == '1005_2'){
				aShowChilds = ['1008_2'];
			}
			else{
				aShowChilds = ['1008_7','1008_11'];
			}		
		}

		// 1,74
		else if(holder_1003.selectedChild == '1003_8'){
				aShowChilds = ['1008_10'];	
		}

		// varifocal
		// 1,5		
		else if(holder_1003.selectedChild == '1003_1'){
			// Basic
			if(holder_1004.selectedChild == '1004_1'){
				if(holder_1005.selectedChild == '1005_1'){
					aShowChilds = ['1008_1','1008_3'];
				}
				else{
					aShowChilds = ['1008_1','1008_13','1008_8'];
				}			
			}
			// Advanced
			else if(holder_1004.selectedChild == '1004_3'){
				if(holder_1005.selectedChild == '1005_1'){
					aShowChilds = ['1008_1','1008_13'];
				}
				else{
					aShowChilds = ['1008_1','1008_14','1008_9'];
				}					
			}
			// Premium
			else if(holder_1004.selectedChild == '1004_7'){
				if(holder_1005.selectedChild == '1005_1'){
					aShowChilds = ['1008_1','1008_3'];
				}
				else{
					aShowChilds = ['1008_1','1008_14','1008_9'];
				}					
			}
			//Elite
			else if(holder_1004.selectedChild == '1004_9'){
				if(holder_1005.selectedChild == '1005_1'){
					aShowChilds = ['1008_1','1008_3'];
				}
				else{
					aShowChilds = ['1008_1','1008_13','1008_8'];
				}					
			}
		}
		// 1,6
		else if(holder_1003.selectedChild == '1003_3'){
			if(holder_1005.selectedChild == '1005_1'){
				aShowChilds = ['1008_1','1008_4'];
			}
			else{
				aShowChilds = ['1008_1','1008_13','1008_8'];
			}
		}
		// 1,67
		else if(holder_1003.selectedChild == '1003_5'){
			if(holder_1005.selectedChild == '1005_1'){
				aShowChilds = ['1008_4'];
			}
			else{
				aShowChilds = ['1008_12','1008_15'];
			}
		}		
		// 1,74
		else if(holder_1003.selectedChild == '1003_7'){
				aShowChilds = ['1008_12','1008_15'];
		}		

	}
	
	currHolder.showFilteringChilds(aShowChilds);
	currHolder.show();	
}

function getAllHoldersPrice(){
	var lensePrice = 0;	
	holderCollection.data.each(function(hold,index){		
		if(hold.selectedChild != null){
			lensePrice+=optionValCollection.getOptionValByNumber(hold.selectedChild).getPrice();
		}
	});		
	return lensePrice;

}

function changeGeneralPrice(){
	var taxdiv = 1.085;
	var dPrice = getAllHoldersPrice();
	dPrice += getPriceFromSelectsBlocks();
	dPrice = (parseFloat(dPrice) / taxdiv);
    try {								
		optionsPrice.changePrice('options', dPrice);
        optionsPrice.changePrice('optionsPriceInclTax', dPrice);
        optionsPrice.reload();
    } catch (e) {
    	alert('Error of Price update');
    }		
}

var aSelectIds = ['98702','98703','98704','98705','98706','98707','98708','98709','98710'];
function getPriceFromSelectsBlocks(){
	var dSelectPrice = 0;
	aSelectIds.each(function(selectId){
		if($('select_'+selectId).value!=''){
			dSelectPrice+=optionPriceConfig[selectId][$('select_'+selectId).value];
		}
	});
	return dSelectPrice;
}

function recursiveHideHolderAfter(holderNumber){	
	holderCollection.data.each(function(ho,index){
		//alert('ho.number='+ho.number+';holderNumber='+holderNumber);		
		if(ho.number>holderNumber){
			/*
			if(ho.selectedChild!=null){
				initHolder(ho.number);
			}
			else{
				ho.erase();
			}	
			*/
			ho.erase();
		}
	});
}

function initAddLensesForm(){
	holderCollection.data[1001].init();
	recursiveHideHolderAfter(1001);	
}

document.observe("dom:loaded", function() {
	
	holderCollection.data[1001] = new Holder('1001',['1001_1','1001_2','1001_3']);
	holderCollection.data[1002] = new Holder('1002',['1002_1','1002_2','1002_3']);
	holderCollection.data[1003] = new Holder('1003',['1003_1','1003_2','1003_3','1003_4','1003_5','1003_6','1003_7','1003_8']);
	holderCollection.data[1004] = new Holder('1004',['1004_1','1004_2','1004_3','1004_4','1004_5','1004_6','1004_7','1004_8','1004_9']);
	holderCollection.data[1005] = new Holder('1005',['1005_1','1005_2','1005_3','1005_4','1005_5','1005_6','1005_7','1005_8']);
	holderCollection.data[1006] = new Holder('1006',['1006_1','1006_2','1006_3','1006_4']);
	holderCollection.data[1007] = new Holder('1007',['1007_1','1007_2','1007_3','1007_4','1007_5']);
	holderCollection.data[1008] = new Holder('1008',['1008_1','1008_2','1008_3','1008_4','1008_5','1008_6','1008_7','1008_8','1008_9','1008_10','1008_11','1008_12','1008_13','1008_14','1008_15']);
	
	optionValCollection.data[1001] = new Array();
	optionValCollection.data[1001][1] = new OptionVal('1001_1',1,0,1002);
	optionValCollection.data[1001][2] = new OptionVal('1001_2',1,0,1003);
	optionValCollection.data[1001][3] = new OptionVal('1001_3',1,0,1003);

	optionValCollection.data[1002] = new Array();
	optionValCollection.data[1002][1] = new OptionVal('1002_1',1,0,1003);
	optionValCollection.data[1002][2] = new OptionVal('1002_2',1,0,1003);
	optionValCollection.data[1002][3] = new OptionVal('1002_3',1,0,1003);
	
	optionValCollection.data[1003] = new Array();
	optionValCollection.data[1003][1] = new OptionVal('1003_1',1,25,1004);
	optionValCollection.data[1003][2] = new OptionVal('1003_2',1,35,1005);
	optionValCollection.data[1003][3] = new OptionVal('1003_3',1,70,1004);
	optionValCollection.data[1003][4] = new OptionVal('1003_4',1,90,1005);
	optionValCollection.data[1003][5] = new OptionVal('1003_5',1,96,1004);
	optionValCollection.data[1003][6] = new OptionVal('1003_6',1,150,1005);
	optionValCollection.data[1003][7] = new OptionVal('1003_7',1,160,1004);
	optionValCollection.data[1003][8] = new OptionVal('1003_8',1,224,1005);
	
	optionValCollection.data[1004] = new Array();
	optionValCollection.data[1004][1] = new OptionVal('1004_1',1,44,1005);
	optionValCollection.data[1004][2] = new OptionVal('1004_2',1,88,1005);
	optionValCollection.data[1004][3] = new OptionVal('1004_3',1,89,1005);
	optionValCollection.data[1004][4] = new OptionVal('1004_4',1,94,1005);
	optionValCollection.data[1004][5] = new OptionVal('1004_5',1,138,1005);
	optionValCollection.data[1004][6] = new OptionVal('1004_6',1,144,1005);
	optionValCollection.data[1004][7] = new OptionVal('1004_7',1,149,1005);
	optionValCollection.data[1004][8] = new OptionVal('1004_8',1,188,1005);
	optionValCollection.data[1004][9] = new OptionVal('1004_9',1,194,1005);

	optionValCollection.data[1005] = new Array();
	optionValCollection.data[1005][1] = new OptionVal('1005_1',2,25,1006);
	optionValCollection.data[1005][2] = new OptionVal('1005_2',2,50,1006);
	optionValCollection.data[1005][3] = new OptionVal('1005_3',2,54,1006);
	optionValCollection.data[1005][4] = new OptionVal('1005_4',2,55,1006);
	optionValCollection.data[1005][5] = new OptionVal('1005_5',2,0,1006);
	optionValCollection.data[1005][6] = new OptionVal('1005_6',2,5,1006);
	optionValCollection.data[1005][7] = new OptionVal('1005_7',2,75,1006);
	optionValCollection.data[1005][8] = new OptionVal('1005_8',2,10,1006);

	optionValCollection.data[1006] = new Array();
	optionValCollection.data[1006][1] = new OptionVal('1006_1',2,0,1007);
	optionValCollection.data[1006][2] = new OptionVal('1006_2',2,0,1007);
	optionValCollection.data[1006][3] = new OptionVal('1006_3',2,0,1007);
	optionValCollection.data[1006][4] = new OptionVal('1006_4',2,0,1007);

	optionValCollection.data[1007] = new Array();
	optionValCollection.data[1007][1] = new OptionVal('1007_1',2,0,1008);
	optionValCollection.data[1007][2] = new OptionVal('1007_2',2,0,1008);
	optionValCollection.data[1007][3] = new OptionVal('1007_3',2,0,1008);
	optionValCollection.data[1007][4] = new OptionVal('1007_4',2,0,1008);
	optionValCollection.data[1007][5] = new OptionVal('1007_5',2,0,1008);
	
	optionValCollection.data[1008] = new Array();
	optionValCollection.data[1008][1] = new OptionVal('1008_1',2,0,null);
	optionValCollection.data[1008][2] = new OptionVal('1008_2',2,0,null);
	optionValCollection.data[1008][3] = new OptionVal('1008_3',2,20,null);
	optionValCollection.data[1008][4] = new OptionVal('1008_4',2,30,null);
	optionValCollection.data[1008][5] = new OptionVal('1008_5',2,40,null);
	optionValCollection.data[1008][6] = new OptionVal('1008_6',2,50,null);
	optionValCollection.data[1008][7] = new OptionVal('1008_7',2,0,null);
	optionValCollection.data[1008][8] = new OptionVal('1008_8',2,40,null);
	optionValCollection.data[1008][9] = new OptionVal('1008_9',2,50,null);
	optionValCollection.data[1008][10] = new OptionVal('1008_10',2,0,null);
	optionValCollection.data[1008][11] = new OptionVal('1008_11',2,10,null);
	optionValCollection.data[1008][12] = new OptionVal('1008_12',2,0,null);
	optionValCollection.data[1008][13] = new OptionVal('1008_13',2,20,null);
	optionValCollection.data[1008][14] = new OptionVal('1008_14',2,30,null);
	optionValCollection.data[1008][15] = new OptionVal('1008_15',2,30,null);

	initAddLensesForm();
});

function pageInit() {
	
	//hide both sections and show on button presses
	$('product-options-wrapper').hide();	
	$('product-options-wrapper-prescription').hide();	
	
	
	$('pd_info_hide').hide();
	$('pd_info').hide();
	
	//$('coating_info_hide').hide();
	//$('coating_info').hide();

	//hide the description   
	aListDD = $('product-options-wrapper').select('dd');	
	$(aListDD[0]).hide();
	aListDT = $('product-options-wrapper').select('dt');
	$(aListDT[0]).hide();
	
	
	$$("#but_add_lenses").each(function(el) {
		el.observe("click", function(event) {					
			
		$('product-options-wrapper').show();	
		$('product-options-wrapper-prescription').show();
		$$(".product-options-bottom").each(function(elmt) { $(elmt).show() });
		//window.location.hash="but_add_lenses";
			Event.stop(event);
		});
		
	});
	
	$$("#but_no_lenses").each(function(el) {
		el.observe("click", function(event) {
			$('product-options-wrapper').show();	
			$('product-options-wrapper-prescription').hide();	
			$$(".product-options-bottom").each(function(elmt) { $(elmt).show() });
		
			initAddLensesForm();
			changeGeneralPrice.defer();
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
	
		
	$$(".i_info").each(function(elmt) {		
		var popup =  $(elmt).next('.option_popup');
		$(popup).hide();
		  
		$(elmt).observe('mouseover', function(e) {
		  var popup =  $(elmt).next('.option_popup');
		  $(popup).show();
		});
		
		$(elmt).observe('mouseout', function(e) {
		  var popup =  $(elmt).next('.option_popup');
		  $(popup).hide();
		});
	});
	/*
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
	*/
}
