
 $(document).ready(function() {
	$('#regTab').click(function() {
		$('#login').fadeOut(500);
		$('#register').delay(510).fadeIn(500);
	});
	$('#loginTab').click(function() {
		$('#register').fadeOut(500);
		$('#login').delay(510).fadeIn(500);
	});
 });
