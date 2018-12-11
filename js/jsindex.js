
$(document).ready(function() {
	push();
	setInterval(function() {
             push();
           }, 5000);
		
		function push(){
				$.ajax({
				 url: 'demo_ajax_load.php',
				 cache: false,
				 dataType: 'html',
					 type:'GET',
				 data: { name: $('#name').val()},
				 error: function(xhr) {
				   alert('您好');
				 },
				 success: function(response) {
						   $('div#demo').html(response);
				 }
			 });
		}
   
    $('#btn_srh').click(function(){
		if( $('#txt_srh')!=null && $('#txt_srh').val()!=""){
			$.ajax({
				 url: 'demo_ajax_search.php',
				 cache: false,
				 dataType: 'html',
					 type:'GET',
				 data: { srh: $('#txt_srh').val()},
				 error: function(xhr) {
				   alert('Ajax request 發生錯誤');
				 },
				 success: function(response) {
						   $('div#contain').html(response);
				 }
			 });
		}
		
	});
	
});