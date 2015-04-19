 jQuery( document ).ready(function() {
 jQuery( "#social-fellow-select-limit" ).change(function(){
 	var url_limit  =document.location.href + "&limit=" + jQuery( "#social-fellow-select-limit option:selected" ).val();
 	location.href = url_limit;
 });
  });

