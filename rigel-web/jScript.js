
 $(document).ready(function() {
 	$('#regReq').submit(function(e) {
			e.preventDefault();
			$("#regReq").css('display', 'none');
			$("#register").css('display', 'block');
	});
 });
