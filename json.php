<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Insert title here</title>
		<script>
			function name_function() {
				json_obj = {

					"fname" : "mark",

					"sname" : "ekisa",

					"jersey_number" : "10",
					
					"tricky":"<?php echo "ekisa"; ?>"

				};

				var concat = "";

				for(key in json_obj) {

					//alert(key + ":" + json_obj[key]);

					concat += json_obj[key];

				}

				document.getElementById("demo").innerHTML = concat;

			}
		</script>
	</head>
	<body>
		<div id="display">
			<p id="demo">
				mark
			</p>
			<button onclick = "name_function()">
				presss me
			</button>
		</div>
	</body>
</html>
