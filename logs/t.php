<?php
?>
<!DOCTYPE table PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>LOGs</title>
</head>
<body>
	<table>
		<tr>
			<td>
				<iframe id="site" src='../' onload="reload()"></iframe>
			</td>
		</tr>
		<tr>
			<td>				
				<iframe id="log" src='index.php'></iframe>
				<a href="javascript:void(0);" onclick="reload()" style="float:right; font-size:10px;">RELOAD</a>
			</td>
		</tr>
	</table>
</body>
<style>
html * {
	margin: 0;
	padding: 0;
	border: none;
}

table {
	width: 100%;
}

table td {
	
}

iframe {
	width: 100%;
}

iframe#site {
	height: 500px;
}

iframe#log {
	height: 300px;
	border-top: solid 1px #000;
	border-bottom: solid 1px #000;
}
</style>
<script type="text/javascript">
function reload(){
	var iframe = document.getElementById('log');
	iframe.src = iframe.src;
}
</script>
</html>