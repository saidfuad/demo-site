 
// A line Overlay done without SVG or VML 

//colour as string e.g. "#00FF00",
//width in pixels
//opacity as 0.0 to 1.0
 
function LineOverlay(p1,p2,colour,width,opacity,title) {

	this.p1_ = p1;
	this.p2_ = p2;
	this.colour_ = colour || "";
	this.width_ = width || 1;
	this.opacity_ = opacity || 1.0;
	this.cntnr_ = null;
	this.ieOpacity_ = Math.round(opacity * 100);
    this.w2_ = Math.round(width / 2);
    this.title_ = title || "";

}
LineOverlay.prototype = new GOverlay();

LineOverlay.prototype.initialize = function(map) {
  this.map_ = map;
}

LineOverlay.prototype.remove = function() {

  if(this.cntnr_ != null){
	  var div = this.map_.getPane(G_MAP_MARKER_SHADOW_PANE);
	  div.removeChild(this.cntnr_);
	  this.cntnr_ = null;
  }

}

LineOverlay.prototype.copy = function() {
  return new LineOverlay(this.p1_,this.p2_,this.colour_,this.width_,this.opacity_);
}

// Redraw the line based on the current projection and zoom level
LineOverlay.prototype.redraw = function(force) {

  //clear old
  this.remove();

  this.cntnr_ = document.createElement("DIV");   
  if(this.title_.length > 0){
    this.cntnr_.title = this.title_;
    this.cntnr_.style.cursor = "help";
  }
    
  
  var p1 = this.map_.fromLatLngToDivPixel(this.p1_);
  var p2 = this.map_.fromLatLngToDivPixel(this.p2_);

  this.buildLine(p1,p2,this.cntnr_);
  
  //pane/layer to write on
  var mapDiv = this.map_.getPane(G_MAP_MARKER_SHADOW_PANE);
  mapDiv.insertBefore(this.cntnr_,null);
  
}

//Create a div holding a set of horz and vert line segments making up the line between p1 and p2
//Uses this.sx,sy,ex,ey,steep

LineOverlay.prototype.buildLine = function(p1,p2,cntnr) {

  var x0 = p1.x;
  var x1 = p2.x;
  var y0 = p1.y;
  var y1 = p2.y;
  
  //TODO nothing if line does not intersect view

  //Bresenham algorithm for line drawing see - wikipedia

  this.steep = false;
  if(Math.abs(y1 - y0) > Math.abs(x1 - x0))
	this.steep = true;
  
  this.uColour_ = this.colour_
  if(this.uColour_.length < 1)
      this.uColour_ = this.map_.getCurrentMapType().getTextColor();
  
  if (this.steep){
		 var t = x0;
		 x0 = y0;
		 y0 = t;
		 
		 t = x1;
		 x1 = y1;
		 y1 = t;
  }
  if (x0 > x1){
		 var t = x0;
		 x0 = x1;
		 x1 = t;

		 t = y0;
		 y0 = y1;
		 y1 = t;
  }

  var deltax = x1 - x0;
  var deltay = Math.abs(y1 - y0);
  var error = 0;
  var ystep;
  var y = y0;
  
  if (y0 < y1)
	ystep = 1; 
  else 
	ystep = -1;
	

  //start and end coords of each horz/vertical line segment
  //We want to use the min of divs
  this.sx;
  this.ex;
  this.sy;
  this.ey;

  //initial coord of first line segment  
  if (this.steep){ 
     this.sx = y0;
     this.sy = x0;
  } 
  else{ 
     this.sy = y0;
     this.sx = x0;
  }


  var div = null;//the div whose background is a horz or vert line segment
  var last = false;//force last plot 
      
  //main Bresenham loops
  if (this.steep){ 
      for( var x = x0; x <= x1; x++){
    		 
	     if (x == x1)
		    last = true;
			
         div = this.plotLinePoint(y,x,last); 

	     if(div != null)
		    cntnr.appendChild(div);
         //else point being held over until horz/vert segment ends 
    
         error = error + deltay;
         if ((2*error) >= deltax){
             y = y + ystep;
             error = error - deltax;
         }
      }  
  }
  else{
      for( var x = x0; x <= x1; x++){
    		 
	     if (x == x1)
		    last = true;
			
         div = this.plotLinePoint(x,y,last);

	     if(div != null)
		    cntnr.appendChild(div);
         //else point being held over until horz/vert segment ends 
    
         error = error + deltay;
         if ((2*error) >= deltax){
             y = y + ystep;
             error = error - deltax;
         }        
      }    
   }
}


//Plot a point that is part of a horizontal or vertical line
//only emit a div if a horz or vert segment has been completed.
//Uses this.sx,sy,ex,ey,steep

LineOverlay.prototype.plotLinePoint = function(x,y,last) {
	     
		 this.ey = y;//end coords of line segment
		 this.ex = x;

		 if((x == this.sx)&&(!last))
			return null;//same x coord as last point
		 if((y == this.sy)&&(!last))
			return null;//same y coord as last point

		 		 
		 //swap start and end to get +ve width and height
		 var ox = this.ex;
		 if(this.ex < this.sx){
			var t = this.ex;
			this.ex = this.sx;
			this.sx = t;
		 } 		 

		 var oy = this.ey;
		 if(this.ey < this.sy){
			var t = this.ey;
			this.ey = this.sy;
			this.sy = t;
		 } 	
		 
		 //establish width/height of div
		 var w = this.ex - this.sx;
		 var h = this.ey - this.sy;
		 
		 var x = this.sx;
		 var y = this.sy;
         if(this.steep){
            w = this.width_;
            x -= this.w2_;//centre the line segment according to the line width
            }
         else{
            h = this.width_;
            y -= this.w2_;//centre the line segment according to the line width
            }

	     this.sx = ox;//restore original bresenham coords of segment start
	     this.sy = oy;
		 
         //actually make the div		 		 
		 var div = document.createElement("DIV");
	     var ds = div.style;
	     
	     ds.position = "absolute";
	     ds.overflow = "hidden";
	     ds.backgroundColor = this.uColour_;
	     ds.left = x + "px";
	     ds.top = y + "px";
	     ds.width = w + "px";
	     ds.height = h + "px";
	     	     
	     if(this.opacity_ != 1.0){
	        ds.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + this.ieOpacity_ +")";
	        ds.opacity = this.opacity_;
	     }
	     	     
	     return div;
}


function ArrowMarker(point, rotation, colour, width, opacity, title) {

  this.point_ = point;
  this.rotation_ = rotation;
  var r = rotation + 90;//compass to math
  this.dx = Math.round(20*Math.cos(r*Math.PI/180));//length is 20 pixels, could be another param
  this.dy = Math.round(20*Math.sin(r*Math.PI/180));
  this.hdx1 = Math.round(10*Math.cos((r+45)*Math.PI/180));//length of head is 10 pixels, could be another param
  this.hdy1 = Math.round(10*Math.sin((r+45)*Math.PI/180));
  this.hdx2 = Math.round(10*Math.cos((r-45)*Math.PI/180));
  this.hdy2 = Math.round(10*Math.sin((r-45)*Math.PI/180));


  //fields common with LineOverlay
  
  this.colour_ = colour || "";
  this.width_ = width || 1;
  this.opacity_ = opacity || 1.0;
  this.title_ = title || "";
  this.ieOpacity_ = Math.round(opacity * 100);
  this.w2_ = Math.round(width / 2);
  
  this.cntnr_ = null;
  this.map_ = null;

  
}
ArrowMarker.prototype = new GOverlay();

ArrowMarker.prototype.initialize = function(map) {
  this.map_ = map;
}

// Remove the main DIV from the map pane
ArrowMarker.prototype.remove = function() {
  if(this.cntnr_ != null){
	  var div = this.map_.getPane(G_MAP_MARKER_SHADOW_PANE);
	  div.removeChild(this.cntnr_);
	  this.cntnr_ = null;
  }
}

// Copy our data to a new ArrowMarker
ArrowMarker.prototype.copy = function() {
  return new ArrowMarker(this.point_, this.rotation_, this.color_, this.title_);
}


// Redraw the arrow based on the current projection and zoom level
ArrowMarker.prototype.redraw = function(force) {


  this.remove();

  var p1 = this.map_.fromLatLngToDivPixel(this.point_);
  var p2 = new GPoint(p1.x + this.dx, p1.y + this.dy);
  
  this.cntnr_ = document.createElement("DIV");   
  if(this.title_.length > 0){
    this.cntnr_.title = this.title_;
    this.cntnr_.style.cursor = "help";
  }

  this.buildLine(p1,p2,this.cntnr_);
  
  if(this.rotation_ > 0)
  {

    p2 = new GPoint(p1.x + this.hdx1, p1.y + this.hdy1);
    this.buildLine(p1,p2,this.cntnr_);

    p2 = new GPoint(p1.x + this.hdx2, p1.y + this.hdy2);
    this.buildLine(p1,p2,this.cntnr_);
  
  }
  
  //pane/layer to write on
  var mapDiv = this.map_.getPane(G_MAP_MARKER_SHADOW_PANE);
  mapDiv.insertBefore(this.cntnr_,null);

}

ArrowMarker.prototype.buildLine = function(p1,p2,cntnr) {
    LineOverlay.prototype.buildLine.call(this,p1,p2,cntnr); 
}

ArrowMarker.prototype.plotLinePoint = function(x,y,last) {
    return LineOverlay.prototype.plotLinePoint.call(this,x,y,last);
}

