<?php

	$input_form = <<<END
<html>
<head><title>API-Scraper</title></head>
<body>
	<h1>OpenOffice.org API-Scraper 2014</h1>
	<form method="POST" action="asMain.php">
		<table>
			<colgroup>
				<col width='200'>
				<col width='800'>
			</colgroup>
			<tr><td>API-Basis Address:</td>
				<td><input style='width: 500pt' type='text' name='rooturl' value='http://www.thomasgsell.ch/public/projekte/apiscraper/oodocuments/docs/common/ref/' /></td></tr>
			<tr><td>Implementationname:</td>
				<td><input style='width: 500pt' type='text' name='implementationname' value='ScTableSheetObj' /></td></tr>
			<tr><td>Supported Servicename 1:</td>
				<td><input style='width: 500pt' type='text' name='servicename1' value='com.sun.star.beans.PropertySet' /></td></tr>
			<tr><td>Supported Servicename 2:</td>
				<td><input style='width: 500pt' type='text' name='servicename2' value='com.sun.star.sheet.Spreadsheet' /></td></tr>
			<tr><td>Supported Servicename 3:</td>
				<td><input style='width: 500pt' type='text' name='servicename3' value='com.sun.star.sheet.SheetCellRange' /></td></tr>
			<tr><td>Supported Servicename 4:</td>
				<td><input style='width: 500pt' type='text' name='servicename4' value='com.sun.star.table.CellRange' /></td></tr>
			<tr><td>Supported Servicename 5:</td>
				<td><input style='width: 500pt' type='text' name='servicename5' value='com.sun.star.table.CellProperties' /></td></tr>
			<tr><td>Supported Servicename 6:</td>
				<td><input style='width: 500pt' type='text' name='servicename6' value='com.sun.star.style.CharacterProperties' /></td></tr>
			<tr><td>Supported Servicename 7:</td>
				<td><input style='width: 500pt' type='text' name='servicename7' value='com.sun.star.style.ParagraphProperties' /></td></tr>
			<tr><td>Supported Servicename 8:</td>
				<td><input style='width: 500pt' type='text' name='servicename8' value='com.sun.star.document.LinkTarget' /></td></tr>
			<tr><td>Supported Servicename 9:</td>
				<td><input style='width: 500pt' type='text' name='servicename9' value='' /></td></tr>
			<tr><td>Supported Servicename 10:</td>
				<td><input style='width: 500pt' type='text' name='servicename10' value='' /></td></tr>
			<tr><td>Supported Servicename 11:</td>
				<td><input style='width: 500pt' type='text' name='servicename11' value='' /></td></tr>
			<tr><td>Supported Servicename 12:</td>
				<td><input style='width: 500pt' type='text' name='servicename12' value='' /></td></tr>
			<tr><td>Supported Servicename 13:</td>
				<td><input style='width: 500pt' type='text' name='servicename13' value='' /></td></tr>
			<tr><td>Supported Servicename 14:</td>
				<td><input style='width: 500pt' type='text' name='servicename14' value='' /></td></tr>
			<tr><td>Supported Servicename 15:</td>
				<td><input style='width: 500pt' type='text' name='servicename15' value='' /></td></tr>
			<tr><td>Supported Servicename 16:</td>
				<td><input style='width: 500pt' type='text' name='servicename16' value='' /></td></tr>
			<tr><td>Supported Servicename 17:</td>
				<td><input style='width: 500pt' type='text' name='servicename17' value='' /></td></tr>
			<tr><td>Supported Servicename 18:</td>
				<td><input style='width: 500pt' type='text' name='servicename18' value='' /></td></tr>
			<tr><td>Supported Servicename 19:</td>
				<td><input style='width: 500pt' type='text' name='servicename19' value='' /></td></tr>
			<tr><td>Supported Servicename 20:</td>
				<td><input style='width: 500pt' type='text' name='servicename20' value='' /></td></tr>
			<tr><td>Supported Servicename 21:</td>
				<td><input style='width: 500pt' type='text' name='servicename21' value='' /></td></tr>
			<tr><td>Supported Servicename 22:</td>
				<td><input style='width: 500pt' type='text' name='servicename22' value='' /></td></tr>
			<tr><td>Supported Servicename 23:</td>
				<td><input style='width: 500pt' type='text' name='servicename23' value='' /></td></tr>
			<tr><td>Supported Servicename 24:</td>
				<td><input style='width: 500pt' type='text' name='servicename24' value='' /></td></tr>
			<tr><td>Supported Servicename 25:</td>
				<td><input style='width: 500pt' type='text' name='servicename25' value='' /></td></tr>
			<tr><td></td>			
				<td></td></tr>
			<tr>
				<td colspan='2' align='center'>
				<input type='submit' name='command' value='Execute' />
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
END;



?>