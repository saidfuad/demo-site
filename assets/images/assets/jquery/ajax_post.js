$(document).ready(function(){

  
  $("#login_submit").click( 
  
    function(){
    alert('login_submit');
        //var username=$("#username").val();
        //var password=$("#password").val(); 
      
        $.ajax({
        type: "POST",
        url: 
		"http://http://192.168.0.143/track/index.php/ajax_post/post_action",
        dataType: "json",
        //data: "username="+username+"&password="+password,
        cache:false,
        success: 
          function(data){
			alert(data);
            $("#form_message").html(data.message).css({'background-color' : data.bg_color}).fadeIn('slow'); 
          }
        
        });

      return false;

    });
  

});