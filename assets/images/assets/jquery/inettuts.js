/*
 * Script from NETTUTS.com [by James Padolsey]
 * @requires jQuery($), jQuery UI & sortable/draggable UI modules
 */
var state;
var msg;
var iNettuts = {
    
    jQuery : $,
    
    settings : {
        columns : '.column',
        widgetSelector: '.widget',
        handleSelector: '.widget-head',
        contentSelector: '.widget-content',
		indexSelector : '.widget-index',
        widgetDefault : {
            movable: true,
            removable: true,
            collapsible: true,
			editable: true,
            maximizable:true,
            colorClasses : ['color-yellow', 'color-red', 'color-blue', 'color-white', 'color-orange', 'color-green']
        },
        widgetIndividual : {
            intro : {
                movable: true,
                removable: false,
                collapsible: false,
				maximizable:false,
                editable: false
            },
            gallery : {
                colorClasses : ['color-yellow', 'color-red', 'color-white']
            }
        }
    },

    init : function () {
//        this.attachStylesheet('inettuts.js.css');
        this.addWidgetControls();
        this.makeSortable();
    },
    
    getWidgetSettings : function (id) {
		var $ = this.jQuery,
            settings = this.settings;
        return (id&&settings.widgetIndividual[id]) ? $.extend({},settings.widgetDefault,settings.widgetIndividual[id]) : settings.widgetDefault;
    },
    
    addWidgetControls : function () {
        var iNettuts = this,
            $ = this.jQuery,
            settings = this.settings;
            
        $(settings.widgetSelector, $(settings.columns)).each(function () {
            var thisWidgetSettings = iNettuts.getWidgetSettings(this.id);
			
            if (thisWidgetSettings.removable) {
				
                $('<a href="#" class="remove">CLOSE</a>').mousedown(function (e) {
                    e.stopPropagation();    
                }).click(function () {
					var widget_id = $(this).parents(settings.widgetSelector).find(settings.indexSelector).html();
                    if(confirm('This Report will be removed, ok?')) {
						$(this).parents(settings.widgetSelector).animate({
							opacity: 0    
						},function () {
							$(this).wrap('<div/>').parent().slideUp(function () {
								$.ajax({
									   type: "POST",
									   url: "dashboard/widgets/removeWidget",
									   data: {'widget' : widget_id},
									   success: function(msg){
		                               		$(this).remove();
									   }
								});
							});
                        });
                    }
                    return false;
                }).appendTo($(settings.handleSelector, this));
            }
			
			if (thisWidgetSettings.maximizable) {
                $('<a href="#" class="maximize">Max</a>').mousedown(function (e) {
                    e.stopPropagation();    
                }).click(function () {
						var filename = $(this).parents(settings.handleSelector).find('span');
						var dest = filename.html();
						//window.location = "index.php?file="+dest+"&menu=reports&add=no&dashboard=false&max=true";
						/*var content = $(this).parents(settings.widgetSelector).find(settings.contentSelector).html();
						content += "<br/>";
						content += "<span style='color:red; font-weight:bolder;'>Click X or Press ESC to close me</span>";
						$.modal(content);*/

			
					//alert($(this).parents(settings.widgetSelector).find(settings.contentSelector).html());
					return false;
                }).appendTo($(settings.handleSelector, this));
            }
            
            if (thisWidgetSettings.editable) {
				$('<a href="#" class="edit">EDIT</a>').mousedown(function (e) {
                    e.stopPropagation();    
                }).toggle(function () {
                    $(this).css({backgroundPosition: '-66px 0', width: '55px'})
                        .parents(settings.widgetSelector)
                            .find('.edit-box').show().find('input').focus();
                    return false;
                },function () {
                    $(this).css({backgroundPosition: '', width: ''})
                        .parents(settings.widgetSelector)
                            .find('.edit-box').hide();
                    return false;
                }).appendTo($(settings.handleSelector,this));
				
                $('<div class="edit-box" style="display:none;"/>')
                    //.append('<ul><li class="item"><label>Change the title?</label><input value="' + $('h3',this).text() + '"/></li>')
                    .append((function(){
                        var colorList = '<li class="item"><label>Available colors:</label><ul class="colors">';
                        $(thisWidgetSettings.colorClasses).each(function () {
                            colorList += '<li class="' + this + '"/>';
						});
                        return colorList + '</ul>';
					})())
                    .append('</ul>')
					.insertAfter($(settings.handleSelector,this));
            }
            
            if (thisWidgetSettings.collapsible) {
               	//get report id 
				var col_id = $(this).find(settings.indexSelector).html();
				//set id of a link like collapse_1
				$('<a href="#" class="collapse" id="collapse_'+col_id+'">COLLAPSE</a>').mousedown(function (e) {
                    e.stopPropagation();
					 
                }).toggle(function () {
                    
					$(this).css({backgroundPosition: '-38px 0'})
                        .parents(settings.widgetSelector)
                            .find(settings.contentSelector).hide();
							
					var id = $(this).parents(settings.widgetSelector).find(settings.indexSelector).html();
					$.ajax({
						   type: "POST",
						   url: "dashboard/widgets/updatesize",
						   data: {'widget' : id, 'size' : 'min'},
						   success: function(msg){
				    }});
                    return false;
                },function () {
                
					$(this).css({backgroundPosition: ''})
                        .parents(settings.widgetSelector)
                            .find(settings.contentSelector).show();
					var id = $(this).parents(settings.widgetSelector).find(settings.indexSelector).html();
					$.ajax({
						   type: "POST",
						   url: "dashboard/widgets/updatesize/",
						   data: {'widget' : id, 'size' : 'max'},
						   success: function(msg){
				    }});
                    return false;
                }).prependTo($(settings.handleSelector,this));
				
            }
			
			//sjadeja - collapse or uncolllapse , as per database value
			var id = $(this).find(settings.indexSelector).html();   //get cuurent id of widhet
			
			/*var th = $(this)
			$.ajax({
			   type: "POST",
			   url: "dashboard/widgets/state/",
			   data: {'widget' : id},
			   success: function(msg){			// get from database -  min or max	
					if(msg == "min"){
						$('#collapse_'+id+'').trigger('click');		//if min then trigger 		
					}   
			   }
			   
			});*/
			
			///////////////////////	
        });
       
		
        $('.edit-box').each(function () {
            $('input',this).keyup(function () {
                $(this).parents(settings.widgetSelector).find('h3').text( $(this).val().length>20 ? $(this).val().substr(0,20)+'...' : $(this).val() );
            });
            $('ul.colors li',this).click(function () {
                
                var colorStylePattern = /\bcolor-[\w]{1,}\b/,
                    thisWidgetColorClass = $(this).parents(settings.widgetSelector).attr('class').match(colorStylePattern);
                if (thisWidgetColorClass) {
                    $(this).parents(settings.widgetSelector)
                        .removeClass(thisWidgetColorClass[0])
                        .addClass($(this).attr('class').match(colorStylePattern)[0]);
;
						var id = $(this).parents(settings.widgetSelector).find(settings.indexSelector).html();
						var color = $(this).attr('class').match(colorStylePattern)[0];
						
						// Setting the Color for the Table in the Widget.
						$(this).parents(settings.widgetSelector).find('table')
							.removeClass(thisWidgetColorClass[0])
							.addClass($(this).attr('class').match(colorStylePattern)[0]);
						
//						alert('ID is:'+$(this).parents(settings.widgetSelector).find(settings.indexSelector).html()+'Color is:'+$(this).attr('class').match(colorStylePattern)[0]);
						$.ajax({
						   type: "POST",
						   url: "dashboard/widgets/updatecolor",
						   data: {'widget' : id, 'color' : color},
						   success: function(msg){
						}});
                }
                return false;
                
            });
        });
        
    },
    
    attachStylesheet : function (href) {
        var $ = this.jQuery;
        return $('<link href="' + href + '" rel="stylesheet" type="text/css" />').appendTo('head');
    },
    
    makeSortable : function () {
        var iNettuts = this,
            $ = this.jQuery,
            settings = this.settings,
            $sortableItems = (function () {
                var notSortable = '';
                $(settings.widgetSelector,$(settings.columns)).each(function (i) {
                    if (!iNettuts.getWidgetSettings(this.id).movable) {
                        if(!this.id) {
                            this.id = 'widget-no-id-' + i;
                        }
                        notSortable += '#' + this.id + ',';
                    }
                });
                return $('> li:not(' + notSortable + ')', settings.columns);
            })();
        
        $sortableItems.find(settings.handleSelector).css({
            cursor: 'move'
        }).mousedown(function (e) {
            $sortableItems.css({width:''});
            $(this).parent().css({
                width: $(this).parent().width() + 'px'
            });
        }).mouseup(function () {
            if(!$(this).parent().hasClass('dragging')) {
                $(this).parent().css({width:''});
            } else {
                $(settings.columns).sortable('disable');
            }
			//alert($(this).parents(settings.widgetSelector).find(settings.indexSelector).html());
        });

        $(settings.columns).sortable({
            items: $sortableItems,
            connectWith: $(settings.columns),
            handle: settings.handleSelector,
            placeholder: 'widget-placeholder',
            forcePlaceholderSize: true,
            revert: 300,
            delay: 100,
            opacity: 0.8,
            containment: 'document',
            start: function (e,ui) {
                $(ui.helper).addClass('dragging');
            },
            stop: function (e,ui) {
                $(ui.item).css({width:''}).removeClass('dragging');
                $(settings.columns).sortable('enable');
				iNettuts.savePreferences();
            }
        });
    }
  	,
    
    savePreferences : function () {
        var iNettuts = this,
            $ = this.jQuery,
            settings = this.settings,
            cookieString = '';
           
        /* Assemble the cookie string */
        $(settings.columns).each(function(i){
            cookieString += (i===0) ? '' : ';';
            $(settings.widgetSelector,this).each(function(i){
                cookieString += (i===0) ? '' : ',';
                /* ID of widget: */
				var id = $(this).find(settings.indexSelector).html();
                cookieString += id;
				
            });
			
        });
      	$.ajax({
			   type: "POST",
			   url: "dashboard/widgets/updatereportorder", // +,
			   data: {'rpt' : cookieString},
			   success: function(msg){
		}});
    }
};

iNettuts.init();