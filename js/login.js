  //Create dialog
  $( "#register-dialog" ).dialog({
	autoOpen:false.
        draggable:false,
	modal:true,
	position: {my: "center", at:"center", of:window},
	resizable:false,
	title: "Login",
	
  });


  //Link to open dialog 
  $( "#registerlink" ).click(function(event) {
	$( "#register-dialog" ).dialog("open");
	event.preventDefault();
  });




