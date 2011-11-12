
 $(document).ready(function() {
 	$('#toggleLog').submit(function(e) {
			$('#register').toggle(500);
			$('#login').toggle(500);
			$('#regTab').toggle(500);
			$('#loginTab').toggle(500);			
			return false;
	});
 });
