function getIndice()   {
	request("GET", "indice.php"  , false, setData);
	if (document.getElementById("day"))   {
		var my_day		= document.getElementById("day").value;
		var my_month	= document.getElementById("month").value;
		var my_year		= document.getElementById("year").value;
		var my_hour		= document.getElementById("hour").value;
		var my_minute	= document.getElementById("minute").value;
		var my_second	= document.getElementById("second").value;
		jQuery(document).ready(function() {
					$('#countdown_dashboard').countDown({
						targetDate: {
							'day': 		my_day		,
							'month': 	my_month	,
							'year': 	my_year		,
							'hour': 	my_hour		,
							'min': 		my_minute	,
							'sec': 		my_second
						} /*,omitWeeks: true */
					});
				});
	}
}