$("document").ready(function(){
$("a#logout").click(function(){$("#profile").slideToggle();});$("#profile").hover(function(){$(".profile_change").show()},function(){$(".profile_change").hide()});$("#close").click(function(){$("#profile").hide()});
});