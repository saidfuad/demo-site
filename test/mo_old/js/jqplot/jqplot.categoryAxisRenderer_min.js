(function(k){k.jqplot.CategoryAxisRenderer=function(){k.jqplot.LinearAxisRenderer.call(this);this.sortMergedLabels=!1};k.jqplot.CategoryAxisRenderer.prototype=new k.jqplot.LinearAxisRenderer;k.jqplot.CategoryAxisRenderer.prototype.constructor=k.jqplot.CategoryAxisRenderer;k.jqplot.CategoryAxisRenderer.prototype.init=function(d){this.groups=1;this.groupLabels=[];this._groupLabels=[];this._grouped=!1;this._barsPerGroup=null;k.extend(!0,this,{tickOptions:{formatString:"%d"}},d);for(var d=this._dataBounds, a=0;a<this._series.length;a++){var b=this._series[a];b.groups&&(this.groups=b.groups);for(var b=b.data,e=0;e<b.length;e++)if("xaxis"==this.name||"x2axis"==this.name){if(b[e][0]<d.min||null==d.min)d.min=b[e][0];if(b[e][0]>d.max||null==d.max)d.max=b[e][0]}else{if(b[e][1]<d.min||null==d.min)d.min=b[e][1];if(b[e][1]>d.max||null==d.max)d.max=b[e][1]}}this.groupLabels.length&&(this.groups=this.groupLabels.length)};k.jqplot.CategoryAxisRenderer.prototype.createTicks=function(){var d=this.ticks,a=this.name, b,e;if(d.length){if(1<this.groups&&!this._grouped){for(var g=d.length,f=parseInt(g/this.groups,10),h=0,a=f;a<g;a+=f)d.splice(a+h,0," "),h++;this._grouped=!0}this.min=0.5;this.max=d.length+0.5;g=this.max-this.min;this.numberTicks=2*d.length+1;for(a=0;a<d.length;a++)e=this.min+2*a*g/(this.numberTicks-1),b=new this.tickRenderer(this.tickOptions),b.showLabel=!1,b.showMark=!0,b.setTick(e,this.name),this._ticks.push(b),b=new this.tickRenderer(this.tickOptions),b.label=d[a],b.showLabel=!0,b.showMark=!1, b.showGridline=!1,b.setTick(e+0.5,this.name),this._ticks.push(b);b=new this.tickRenderer(this.tickOptions);b.showLabel=!1;b.showMark=!0;b.setTick(e+1,this.name);this._ticks.push(b)}else{b="xaxis"==a||"x2axis"==a?this._plotDimensions.width:this._plotDimensions.height;null!=this.min&&(null!=this.max&&null!=this.numberTicks)&&(this.tickInterval=null);null!=this.min&&(null!=this.max&&null!=this.tickInterval)&&parseInt((this.max-this.min)/this.tickInterval,10)!=(this.max-this.min)/this.tickInterval&&(this.tickInterval= null);d=[];e=0;for(var l=!1,a=0;a<this._series.length;a++){f=this._series[a];for(g=0;g<f.data.length;g++)h="xaxis"==this.name||"x2axis"==this.name?f.data[g][0]:f.data[g][1],-1==k.inArray(h,d)&&(l=!0,e+=1,d.push(h))}l&&this.sortMergedLabels&&d.sort(function(a,b){return a-b});this.ticks=d;for(a=0;a<this._series.length;a++){f=this._series[a];for(g=0;g<f.data.length;g++)h="xaxis"==this.name||"x2axis"==this.name?f.data[g][0]:f.data[g][1],h=k.inArray(h,d)+1,"xaxis"==this.name||"x2axis"==this.name?f.data[g][0]= h:f.data[g][1]=h}if(1<this.groups&&!this._grouped){g=d.length;f=parseInt(g/this.groups,10);h=0;for(a=f;a<g;a+=f+1)d[a]=" ";this._grouped=!0}a=e+0.5;null==this.numberTicks&&(this.numberTicks=2*e+1);g=a-0.5;this.min=0.5;this.max=a;h=0;a=parseInt(3+b/20,10);f=parseInt(e/a,10);null==this.tickInterval&&(this.tickInterval=g/(this.numberTicks-1));for(a=0;a<this.numberTicks;a++)e=this.min+a*this.tickInterval,b=new this.tickRenderer(this.tickOptions),a/2==parseInt(a/2,10)?(b.showLabel=!1,b.showMark=!0):(0< f&&h<f?(b.showLabel=!1,h+=1):(b.showLabel=!0,h=0),b.label=b.formatter(b.formatString,d[(a-1)/2]),b.showMark=!1,b.showGridline=!1),this.showTicks?this.showTickMarks||(b.showMark=!1):(b.showLabel=!1,b.showMark=!1),b.setTick(e,this.name),this._ticks.push(b)}};k.jqplot.CategoryAxisRenderer.prototype.draw=function(d){if(this.show){this.renderer.createTicks.call(this);this._elem&&this._elem.empty();this._elem=this._elem||k('<div class="jqplot-axis jqplot-'+this.name+'" style="position:absolute;"></div>'); "xaxis"==this.name||"x2axis"==this.name?this._elem.width(this._plotDimensions.width):this._elem.height(this._plotDimensions.height);this.labelOptions.axis=this.name;this._label=new this.labelRenderer(this.labelOptions);if(this._label.show){var a=this._label.draw(d);a.appendTo(this._elem)}if(this.showTicks)for(var b=this._ticks,e=0;e<b.length;e++)if(a=b[e],a.showLabel&&(!a.isMinorTick||this.showMinorTicks))a=a.draw(d),a.appendTo(this._elem);this._groupLabels=[];for(e=0;e<this.groupLabels.length;e++)a= k('<div style="position:absolute;" class="jqplot-'+this.name+'-groupLabel"></div>'),a.html(this.groupLabels[e]),this._groupLabels.push(a),a.appendTo(this._elem)}return this._elem};k.jqplot.CategoryAxisRenderer.prototype.set=function(){var d=0,a,b=0,e=0,g=null==this._label?!1:this._label.show;if(this.show&&this.showTicks){for(var f=this._ticks,h=0;h<f.length;h++)if(a=f[h],a.showLabel&&(!a.isMinorTick||this.showMinorTicks))a="xaxis"==this.name||"x2axis"==this.name?a._elem.outerHeight(!0):a._elem.outerWidth(!0), a>d&&(d=a);for(h=f=0;h<this._groupLabels.length;h++)a=this._groupLabels[h],a="xaxis"==this.name||"x2axis"==this.name?a.outerHeight(!0):a.outerWidth(!0),a>f&&(f=a);g&&(b=this._label._elem.outerWidth(!0),e=this._label._elem.outerHeight(!0));"xaxis"==this.name?this._elem.css({height:d+(f+e)+"px",left:"0px",bottom:"0px"}):"x2axis"==this.name?this._elem.css({height:d+(f+e)+"px",left:"0px",top:"0px"}):("yaxis"==this.name?this._elem.css({width:d+(f+b)+"px",left:"0px",top:"0px"}):this._elem.css({width:d+ (f+b)+"px",right:"0px",top:"0px"}),g&&this._label.constructor==k.jqplot.AxisLabelRenderer&&this._label._elem.css("width",b+"px"))}};k.jqplot.CategoryAxisRenderer.prototype.pack=function(d,a){var b=this._ticks,e=this.max,g=this.min,f=a.max,h=a.min,l=null==this._label?!1:this._label.show,j;for(j in d)this._elem.css(j,d[j]);this._offsets=a;var n=f-h,o=e-g;this.p2u=function(a){return(a-h)*o/n+g};this.u2p=function(a){return(a-g)*n/o+h};"xaxis"==this.name||"x2axis"==this.name?(this.series_u2p=function(a){return(a- g)*n/o},this.series_p2u=function(a){return a*o/n+g}):(this.series_u2p=function(a){return(a-e)*n/o},this.series_p2u=function(a){return a*o/n+e});if(this.show)if("xaxis"==this.name||"x2axis"==this.name){for(i=0;i<b.length;i++){var c=b[i];if(c.show&&c.showLabel){if(c.constructor==k.jqplot.CanvasAxisTickRenderer&&c.angle)switch(j="xaxis"==this.name?1:-1,c.labelPosition){case "auto":j=0>j*c.angle?-c.getWidth()+c._textRenderer.height*Math.sin(-c._textRenderer.angle)/2:-c._textRenderer.height*Math.sin(c._textRenderer.angle)/ 2;break;case "end":j=-c.getWidth()+c._textRenderer.height*Math.sin(-c._textRenderer.angle)/2;break;case "start":j=-c._textRenderer.height*Math.sin(c._textRenderer.angle)/2;break;case "middle":j=-c.getWidth()/2+c._textRenderer.height*Math.sin(-c._textRenderer.angle)/2;break;default:j=-c.getWidth()/2+c._textRenderer.height*Math.sin(-c._textRenderer.angle)/2}else j=-c.getWidth()/2;j=this.u2p(c.value)+j+"px";c._elem.css("left",j);c.pack()}}b=["bottom",0];l&&(c=this._label._elem.outerWidth(!0),this._label._elem.css("left", h+n/2-c/2+"px"),"xaxis"==this.name?(this._label._elem.css("bottom","0px"),b=["bottom",this._label._elem.outerHeight(!0)]):(this._label._elem.css("top","0px"),b=["top",this._label._elem.outerHeight(!0)]),this._label.pack());f=parseInt(this._ticks.length/this.groups,10);for(i=0;i<this._groupLabels.length;i++){for(var p=l=0,m=i*f;m<=(i+1)*f;m++)this._ticks[m]._elem&&" "!=this._ticks[m].label&&(c=this._ticks[m]._elem,j=c.position(),l+=j.left+c.outerWidth(!0)/2,p++);l/=p;this._groupLabels[i].css({left:l- this._groupLabels[i].outerWidth(!0)/2});this._groupLabels[i].css(b[0],b[1])}}else{for(i=0;i<b.length;i++)if(c=b[i],c.show&&c.showLabel){if(c.constructor==k.jqplot.CanvasAxisTickRenderer&&c.angle)switch(j="yaxis"==this.name?1:-1,c.labelPosition){case "auto":case "end":j=0>j*c.angle?-c._textRenderer.height*Math.cos(-c._textRenderer.angle)/2:-c.getHeight()+c._textRenderer.height*Math.cos(c._textRenderer.angle)/2;break;case "start":j=0<c.angle?-c._textRenderer.height*Math.cos(-c._textRenderer.angle)/ 2:-c.getHeight()+c._textRenderer.height*Math.cos(c._textRenderer.angle)/2;break;case "middle":j=-c.getHeight()/2;break;default:j=-c.getHeight()/2}else j=-c.getHeight()/2;j=this.u2p(c.value)+j+"px";c._elem.css("top",j);c.pack()}b=["left",0];l&&(c=this._label._elem.outerHeight(!0),this._label._elem.css("top",f-n/2-c/2+"px"),"yaxis"==this.name?(this._label._elem.css("left","0px"),b=["left",this._label._elem.outerWidth(!0)]):(this._label._elem.css("right","0px"),b=["right",this._label._elem.outerWidth(!0)]), this._label.pack());f=parseInt(this._ticks.length/this.groups,10);for(i=0;i<this._groupLabels.length;i++){p=l=0;for(m=i*f;m<=(i+1)*f;m++)this._ticks[m]._elem&&" "!=this._ticks[m].label&&(c=this._ticks[m]._elem,j=c.position(),l+=j.top+c.outerHeight()/2,p++);l/=p;this._groupLabels[i].css({top:l-this._groupLabels[i].outerHeight()/2});this._groupLabels[i].css(b[0],b[1])}}}})(jQuery);