jQuery.fn.printt=function(){if(1<this.length)this.eq(0).print();else if(this.length){var b="printer-"+(new Date).getTime(),c=$("<iframe name='"+b+"'>");c.css("width","1px").css("height","1px").css("position","absolute").css("left","-9999px").appendTo($("body:first"));var b=window.frames[b],a=b.document;$("<div>").append($("style").clone());a.open();a.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');a.write("<html>");
a.write("<head>");a.write("<title>");a.write(document.title);a.write("</title>");a.write("<style type='text/css'> .vehical{cursor : pointer;}; #bottomBigPaging span a{cursor: pointer; border-radius: 7px 7px 7px 7px ! important; padding: 2px 5px;}; #bottomBigPaging span a:hover{padding: 3px 5px;}; .paginDisabled{ cursor: default !important;\tbackground: none !important; padding: 2px 4px !important;  \ttext-decoration: none !important;}</style><link rel='stylesheet' href='"+url_main+"assets/dashboard/stylesheets/all_in_one.css'><link rel='stylesheet' href='"+
url_main+"assets/dashboard/stylesheets/layout.css'><link rel='stylesheet' href='"+url_main+"assets/style/css/style_all_min.css' /><\!--<link href='"+url_main+"assets/style/css/jquery-ui-timepicker.css' rel='stylesheet' type='text/css' />--\><\!-- <script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&v=3&libraries=geometry'><\/script>--\><\!--[if lt IE 9]><script src='http://html5shim.googlecode.com/svn/trunk/html5.js'><\/script><![endif]--\><link type='text/css' href='"+
url_main+"assets/jquery/ui-themes/redmond/jquery-ui-1.8.5.custom.css' rel='stylesheet' /><link rel='stylesheet' type='text/css' href='"+url_main+"assets/dash-lib/themes/default/dashboardui.css' />");a.write("</head>");a.write("<body>");a.write("<h3>");a.write($("#optdetail option:selected").html());a.write("</h3>");a.write(this.html());a.write("</body>");a.write("</html>");a.close();b.focus();b.print();setTimeout(function(){c.remove()},6E4)}};