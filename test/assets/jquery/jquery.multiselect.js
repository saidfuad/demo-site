var multipleSelectFilter = new Class({
    Implements: [Options],
    options: {
    initLength:'100',
    initialTxt:'type here...',
    charWidth:'8',
    minChars:'0',
    iconClose:'<img src="images/icon-delete.png" alt="reset">'
    },
    initialize: function(element,options){
    this.setOptions(options);
    this.selectBox = $(element);
    this.selectOptions = this.selectBox.getElements('option');
    // create array of original options
    this.optionsArray=this.getOptions();
    // find longest item and make array of options for checking later
    var optionLength = 0;
    this.selectOptions.each(function(option) {
    optText=option.get('text');
    lng=optText.length;
    if(lng>optionLength) optionLength=lng;
    });
    this.stretchWidth=optionLength*this.options.charWidth;
    // make select box expandable
    if(this.stretchWidth>this.options.initLength){
    this.selectBox.addEvents({
    'mouseover' :function(){ this.expand(this.selectBox,this.stretchWidth);}.bind(this),
    'mouseout': function(){ this.expand(this.selectBox,this.options.initLength);}.bind(this)
    });
    }
    // add filter
    this.addFilter();
    // wrap select list in relative layer to flow "over" items near it rather than pushing them away
    // NOTE - do this after we have added the filter
    this.addWrapper();
    },
    addWrapper: function(){
    // wrap select list in relative layer to flow "over" items near it rather than pushing them away
    // this also prevents any "jumping" when the filter is activted
    var wrapper = new Element('div', {
    'styles': {
    'position': 'relative',
    'width':''+this.options.initLength+'px',
    'z-index':'1'
    }
    });
    wrapper.wraps(this.selectBox);
    },
    expand:function(el,newWidth){
    el.tween('width', ''+newWidth+'px');
    },
    // clone the original select box with all it's selected options etc
    getOptions:function(){
    var arr=[];
    this.selectOptions.each(function(option, index){
    arr.include(option.get('text').toLowerCase());
    });
    return arr;
    },
    // add filter box
    addFilter:function(){
    var initialTxt = this.options.initialTxt;
    var initLength = this.options.initLength;
    var optionsArray = this.optionsArray;
    var selectBox = this.selectBox;
    var selectOptions = this.selectOptions;
    var selectBoxName = this.selectBox.get('name');
    var maxLength = 0;
    var charWidth = this.options.charWidth;
    var stretchWidth = this.stretchWidth;
    var wrapperWidth = initLength.toInt()+20;
    var minChars = this.options.minChars;
    // get select box height for filter list
    var selectBoxHeight=this.selectBox.getSize().y;
    // filter wrapper to hold list and text box
    var filterWrapper = new Element('div',{
    'class' : 'filterWrapper',
    styles:{
    'width':wrapperWidth+'px'
    }
    })
    // create ul to hold items
    var filterList= new Element('ul',{
    styles:{
    height:selectBoxHeight+'px',
    width:initLength+'px'
    }
    }).inject(filterWrapper,'bottom').hide();
    // add text box
    var filterTextbox = new Element('input',{
    'class':'search',
    'value':initialTxt,
    events: {
    'focus':function(){
    if(this.value==''+initialTxt+'') this.value="";
    // get options to create li list for filter
    var counter=0;
    var listItems="";
    selectOptions.each(function(option,index){
    // define select option value and text value
    //optValue=option.get('value'); // NOT USED
    optText =option.get('text');
    optLength=optText.length;
    // define length of longest item
    if(optLength>maxLength) maxLength=optLength;
    // check if item is selected
    if(option.get('selected')) selected='class="checked"';
    else selected="";
    // add option to list if value isn't empty
    listItems += '<li id="opt_'+selectBoxName+'_'+counter+'" '+selected+'="">'+optText+'</li>';
    // increase index counter
    counter++;
    });
    // define stretch width
    var stretchWidth=maxLength*charWidth;
    // check that stretch width is not less than initial length
    if(stretchWidth<=initLength) var stretchWidth=initLength;
    // add items to ul
    filterList.set('html',listItems);
    // add events for li items
    filterWrapper.getElements('li').addEvents({
    'mouseover':function(event){
    if(event.shift){
    optId=this.id.replace('opt_'+selectBoxName+'_','');
    if(this.hasClass('checked')){
    // remove from original select box
    selectOptions[optId].setProperty('selected', '');
    // remove class
    this.removeClass('checked');
    }else{
    // mark as selected in original select box
    selectOptions[optId].setProperty('selected', 'selected');
    // add class to show as selected
    this.addClass('checked');
    }
    }else{
    bgColor=this.getStyle('backgroundColor');
    this.highlight('#FFCC00',bgColor);
    }
    },
    'click':function(){
    optId=this.id.replace('opt_'+selectBoxName+'_','');
    if(this.hasClass('checked')){
    // remove from original select box
    selectOptions[optId].setProperty('selected', '');
    // remove class
    this.removeClass('checked');
    }else{
    // add class to show as selected
    this.addClass('checked');
    // mark as selected in original select box
    selectOptions[optId].setProperty('selected', 'selected');
    }
    }
    }).setStyle('cursor','pointer');
    },
    'blur':function(){
    // reset deafault text
    if(this.value=='') this.value=initialTxt;
    },
    'keyup':function(){
    searchStr=this.value.toLowerCase();
    if(searchStr.length>minChars){
    // show list once we have at least X chars (option - default 0)
    // hide select box for IE
    selectBox.set('opacity',0);
    // show filter select list and expand to widest item
    filterList.reveal().tween('width', ''+stretchWidth+'px');
    }else{
    //filterList.tween('width', ''+initLength+'px').dissolve();
    //selectBox.show();
    }
    // loop through options array and remove items that aren't in it
    //var results=0;
    optionsArray.each(function(item, index){
    if(!item.contains(searchStr)){
    // hide item from list
    if($('opt_'+selectBoxName+'_'+index+''))$('opt_'+selectBoxName+'_'+index+'').hide();
    }else{
    // show option (incase it has been hidden by previous typing)
    if($('opt_'+selectBoxName+'_'+index+''))$('opt_'+selectBoxName+'_'+index+'').show();
    //results++;
    }
    });
    }
    }
    }).inject(filterWrapper,'top');
    // reset button to hide filter list
    var btReset = new Element('div',{
    'class':'refresh',
    'title':'Close Filter',
    'html':this.options.iconClose,
    'events':{
    'click':function(){
    // hide the temp list - do it quickly to avoid errors ;)
    filterList.hide();
    filterWrapper.hide();
    selectBox.set('opacity',1);
    filterTextbox.value=initialTxt;
    }
    }
    }).inject(filterTextbox,'after');
    var clearLayer = new Element('div',{
    styles:{
    border:'1px dashed red',
    clear:'both',
    height:'10px'
    }
    }).inject(filterWrapper,'after');
    // hide filter box
    filterWrapper.inject(this.selectBox,'before').hide();
    selectBox.addEvents({
    mouseover:function(){
    filterWrapper.reveal();
    }
    });
    }
    });