var strTable;

jQuery.fn.Upper =
function()
{
	return this.each(function()
	{
		if($(this).is('textarea') || $(this).is('input:text')){
			$(this).keyup(function(e)
			{
			   $(this).val($(this).val().toUpperCase());
			})
		}
	})
};

jQuery.fn.EmailOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
			
            var key = e.charCode || e.keyCode || 0;
		    // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            return (
				key == 110 ||
				(key == 190 && e.shiftKey === false)||
				(key == 50 && e.shiftKey === true)||
				(key >= 65 && key <= 90 && e.ctrlKey === false) ||
				(key >= 48 && key <= 57 && e.shiftKey === false) ||
				(key == 109 && e.shiftKey === true) ||
                key == 46 ||
				key == 8 || 
                key == 9 ||
                key == 18 ||
				key == 27 ||
				key == 13 ||
                (key >= 35 && key <= 40) ||
                (key >= 96 && key <= 105));
        });
	  $(this).bind("cut copy paste",function(e) {
      e.preventDefault();
	  })
		
    })
};

jQuery.fn.UserName =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            return (
				key == 110 ||
				(key == 190 && e.shiftKey === false)||
				(key >= 65 && key <= 90 && e.ctrlKey === false) ||
				(key >= 48 && key <= 57 && e.shiftKey === false) ||
				(key == 109 && e.shiftKey === true) ||
                key == 46 ||
				key == 8 || 
                key == 9 ||
                key == 18 ||
				key == 27 ||
				key == 13 ||
                (key >= 35 && key <= 40) ||
                (key >= 96 && key <= 105));
        });
	  $(this).bind("cut copy paste",function(e) {
      e.preventDefault();
	  })
		
    })
};


jQuery.fn.Password =
function()
{
    return this.each(function()
    {
	    $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			if(key==32 || key==222 || e.ctrlKey === true)
			{
				return false;
			}
			return true;
        });
	  $(this).bind("cut copy paste",function(e) {
      e.preventDefault();
	  })
    })
};

jQuery.fn.blockCopyPaste =
function()
{
    return this.each(function()
    {
	  $(this).bind("cut copy paste",function(e) {
      e.preventDefault();
	  })
    })
};

jQuery.fn.NumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
            return (
                key == 8 || 
                key == 9 ||
                key == 46 ||
				key == 18 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57 && e.shiftKey === false) ||
                (key >= 96 && key <= 105));
        })
    })
};

jQuery.fn.Mobile_Comma_Only =
function()
{
	return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
		    return (
                key == 8 || 
                key == 9 ||
                key == 46 ||
				key == 18 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57 && e.shiftKey === false) ||
                (key >= 188 && e.shiftKey === false) ||
                (key >= 96 && key <= 105));
        })
    })
};
jQuery.fn.DecimalOnly =
function()
{
    return this.each(function()
    {
        
		$(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
			
			if(this.value.indexOf('.') != -1){
				var val = this.value.split('.');
				val = val[1];
				if(val.length == 2){
					return(key == 8 || key == 9 || key == 46 || key == 18);
				}else{
					return (key == 8 || key == 9 || key == 46 || key == 18 || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
				}
			}else{
			// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
				return (
					key == 110 || 
					key == 190 || 
					key == 8 || 
					key == 9 ||
					key == 46 ||
					key == 18 ||
					(key >= 35 && key <= 40) ||
					(key >= 48 && key <= 57) ||
					(key >= 96 && key <= 105));
			}
        })
    })
};

function checkEmailId(o){
	var regexp =/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
	if (!(regexp.test( o.val()))){
		return false;
	} else {
		return true;
	}
}
function checkFormValidation(arr)
{
	alert(arr.toSource());
}

function updateTips(t) {
	tips
		.text(t)
		.addClass('ui-state-highlight');
	setTimeout(function() {
		tips.removeClass('ui-state-highlight', 1500);
	}, 500);
}
function updateTipsErr(t,tipss) {
	tipss
		.text(t)
		.addClass('ui-state-highlight');
	setTimeout(function() {
		tipss.removeClass('ui-state-highlight', 1500);
	}, 500);
}

function checkNull(o,n){
	if(o.val().length == 0){
		o.addClass('ui-state-error');
		updateTips(n + " should not be blank.");
		return false;
	}
	else{
		return true;
	}
}

function checkFromTo(o,n,m){
	if(o.val() >= n.val()){
		n.addClass('ui-state-error');
		updateTips(m);
		return false;
	}
	else{
		return true;
	}
}
function checkNullOnly(o) {
	if(o.val().length == 0)
		return false;
	else
		return true;
}

function checkLength(o,n,min,max) {

	if ( o.val().length > max || o.val().length < min ) {
		o.addClass('ui-state-error');
		updateTips("Length of " + n + " must be between "+min+" and "+max+".");
		return false;
	} else {
		return true;
	}

}
function checkMobileLength(o,n,min) {

	if ( o.val().length > min || o.val().length < min ) {
		o.addClass('ui-state-error');
		updateTips("Length of " + n + " must be "+min);
		return false;
	} else {
		return true;
	}

}
function checkMaxCredit(v1,v2,msg) {

	var crd = v1.val() - v2.val()
	if(crd > 0){
		v1.addClass('ui-state-error');
		updateTips(msg);
		return false;
	} else {
		return true;
	}

}
function checkZero(v1,msg) {
	var crd = v1.val();
	if(crd == 0){
		v1.addClass('ui-state-error');
		updateTips(msg);
		return false;
	} else {
		return true;
	}
}
function checkNegative(v,msg) {
	if(v.val() <= 0){
		v.addClass('ui-state-error');
		updateTips(msg);
		return false;
	} else {
		return true;
	}
}
function checkRegExp(o,regexp,n) {

	if ( !( regexp.test( o.val() ) ) ) {
		o.addClass('ui-state-error');
		updateTips(n);
		return false;
	} else {
		return true;
	}

}

function checkEmail(o){
	return checkRegExp (o,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"Wronge Email : eg info@devindia.net");
}

function checkPassword(o){
	return checkRegExp(o,/^([0-9a-zA-Z])+$/,"Password field only allow : a-z 0-9")
}
function checkNumber(o,n){
	return checkRegExp(o,/^([0-9])+$/,n+" field only allow : 0-9")
}
function checkDecimalNumber(o,n){
	return checkRegExp(o,/^([0-9.])+$/,n+" field only allow Number")
}
function checkName(o,n){
	return true;
	//return !checkRegExp(o,/([\s])+/i,n+" may consist of a-z, 0-9, underscores, begin with a letter.");
}

function getChildCombo(pValue,cCombo,strCmd,fileName){
	$.post( 
        "php/"+fileName+"?cmd="+strCmd+"&value="+pValue, 
        function(data){
			$(cCombo).html(data);
        } 
    );
}
function getChildComboEdit(pValue,cCombo,strCmd,fileName, selected){
	$.post( 
        "php_lib/"+fileName+"?cmd="+strCmd+"&value="+pValue, 
        function(data){
			$(cCombo).html(data);
			$(cCombo).val(selected);
        } 
    );
}
function edit(table){
	
	var sUrl = "php/general.php?cmd=select&table=" + table + "&key=id&value=" + editID;
	$("#"+frmPrifix+"id").val(editID);
	$.post(
		sUrl,
		function(msg){
			$.each(msg, function(key, value) { 
				try
				{		
					if($("#"+frmPrifix + key).attr("type") == 'checkbox'){
						$("#"+ frmPrifix + key).click(function(){
							if($(this).attr("checked") == true){
								$(this).val(1);
							}else{
								$(this).val(0);
							}
						});
						if(value == 1){
							$("#"+ frmPrifix + key).attr("checked","checked");
						}else{
							$("#"+ frmPrifix + key).attr("checked","");
						}
					}
					$("#"+frmId+" #"+ frmPrifix + key).val(value);
					
				}
				catch(e)
				{
				}
			});
			$(".date").each(
				function(){
					if($(this).val()!=""){
						var myDate = parse_date($(this).val());
						$(this).val(myDate.format('d.m.Y'));
					}
				});
			
		},"json"
	);
}
function addRecord(url, grid_div, form_div, grid, dialog, errorMsgP, all_Fields){
  $.post( 
        url, 
        $("#"+frmId).serialize(), 
        function(data){
			if(data.result == "true"){
				jQuery("#"+grid).trigger("reloadGrid");
				$("#"+dialog).html(data.msg);
				$("#"+dialog).dialog("open");
				jQuery("#"+grid_div).css("display","inline");
				jQuery("#"+form_div).css("display","none");	
				all_Fields.val('').removeClass('ui-state-error');
				jQuery("#"+errorMsgP).html("* Fields are mendatory");
			}
			else{
				updateTips(data.error);
				all_Fields.removeClass('ui-state-error');
				if(data.eid){	
					$("#" + frmPrifix+data.eid).focus();				
					$("#" + frmPrifix+data.eid).addClass('ui-state-error');
				}	
			}
        }, "json");
		
}

function editRecord(url, grid_div, form_div, grid, dialog, errorMsgP, all_Fields){
    $.post( 
        url, 
        $("#"+frmId).serialize(), 
        function(data){
			if(data.result == "true"){
				editID = "";
				jQuery("#"+grid).trigger("reloadGrid");
				jQuery("#"+grid_div).css("display","inline");
				jQuery("#"+form_div).css("display","none");			
				all_Fields.val('').removeClass('ui-state-error');
				jQuery("#"+errorMsgP).html("* Fields are mendatory");	
				$("#"+dialog).html(data.msg);
				$("#"+dialog).dialog("open");
			}
			else{
				updateTips(data.error);
				all_Fields.removeClass('ui-state-error');
				if(data.eid){					
					$("#" + frmPrifix+data.eid).focus();				
					$("#" + frmPrifix+data.eid).addClass('ui-state-error');
				}	
			}
        }, "json");
	
}
function generateLogin(v)
{
	$.post( 
			"php_lib/users.php?cmd=getUsername&ctrUsr="+v,
			function(data){
				jQuery("#"+frmPrifix+"Loginname").val(data);
			} 
		);	
}
function gridLoadComplete(xhr){
	if(xhr.result == "false"){
		alert(xhr.message);
	}
}

function parse_date(string) {
    var date = new Date();
    var parts = String(string).split(/[- :]/);

    date.setFullYear(parts[0]);
    date.setMonth(parts[1] - 1);
    date.setDate(parts[2]);
    date.setHours(parts[3]);
    date.setMinutes(parts[4]);
    date.setSeconds(parts[5]);
    date.setMilliseconds(0);

    return date;
}
// Simulates PHP's date function
Date.prototype.format = function(format) {
	var returnStr = '';
	var replace = Date.replaceChars;
	for (var i = 0; i < format.length; i++) {
		var curChar = format.charAt(i);
		if (replace[curChar]) {
			returnStr += replace[curChar].call(this);
		} else {
			returnStr += curChar;
		}
	}
	return returnStr;
};
Date.replaceChars = {
	shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
	longMonths: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
	shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
	longDays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
	
	// Day
	d: function() { return (this.getDate() < 10 ? '0' : '') + this.getDate(); },
	D: function() { return Date.replaceChars.shortDays[this.getDay()]; },
	j: function() { return this.getDate(); },
	l: function() { return Date.replaceChars.longDays[this.getDay()]; },
	N: function() { return this.getDay() + 1; },
	S: function() { return (this.getDate() % 10 == 1 && this.getDate() != 11 ? 'st' : (this.getDate() % 10 == 2 && this.getDate() != 12 ? 'nd' : (this.getDate() % 10 == 3 && this.getDate() != 13 ? 'rd' : 'th'))); },
	w: function() { return this.getDay(); },
	z: function() { return "Not Yet Supported"; },
	// Week
	W: function() { return "Not Yet Supported"; },
	// Month
	F: function() { return Date.replaceChars.longMonths[this.getMonth()]; },
	m: function() { return (this.getMonth() < 9 ? '0' : '') + (this.getMonth() + 1); },
	M: function() { return Date.replaceChars.shortMonths[this.getMonth()]; },
	n: function() { return this.getMonth() + 1; },
	t: function() { return "Not Yet Supported"; },
	// Year
	L: function() { return (((this.getFullYear()%4==0)&&(this.getFullYear()%100 != 0)) || (this.getFullYear()%400==0)) ? '1' : '0'; },
	o: function() { return "Not Supported"; },
	Y: function() { return this.getFullYear(); },
	y: function() { return ('' + this.getFullYear()).substr(2); },
	// Time
	a: function() { return this.getHours() < 12 ? 'am' : 'pm'; },
	A: function() { return this.getHours() < 12 ? 'AM' : 'PM'; },
	B: function() { return "Not Yet Supported"; },
	g: function() { return this.getHours() % 12 || 12; },
	G: function() { return this.getHours(); },
	h: function() { return ((this.getHours() % 12 || 12) < 10 ? '0' : '') + (this.getHours() % 12 || 12); },
	H: function() { return (this.getHours() < 10 ? '0' : '') + this.getHours(); },
	i: function() { return (this.getMinutes() < 10 ? '0' : '') + this.getMinutes(); },
	s: function() { return (this.getSeconds() < 10 ? '0' : '') + this.getSeconds(); },
	// Timezone
	e: function() { return "Not Yet Supported"; },
	I: function() { return "Not Supported"; },
	O: function() { return (-this.getTimezoneOffset() < 0 ? '-' : '+') + (Math.abs(this.getTimezoneOffset() / 60) < 10 ? '0' : '') + (Math.abs(this.getTimezoneOffset() / 60)) + '00'; },
	P: function() { return (-this.getTimezoneOffset() < 0 ? '-' : '+') + (Math.abs(this.getTimezoneOffset() / 60) < 10 ? '0' : '') + (Math.abs(this.getTimezoneOffset() / 60)) + ':' + (Math.abs(this.getTimezoneOffset() % 60) < 10 ? '0' : '') + (Math.abs(this.getTimezoneOffset() % 60)); },
	T: function() { var m = this.getMonth(); this.setMonth(0); var result = this.toTimeString().replace(/^.+ \(?([^\)]+)\)?$/, '$1'); this.setMonth(m); return result;},
	Z: function() { return -this.getTimezoneOffset() * 60; },
	// Full Date/Time
	c: function() { return this.format("Y-m-d") + "T" + this.format("H:i:sP"); },
	r: function() { return this.toString(); },
	U: function() { return this.getTime() / 1000; }
};

