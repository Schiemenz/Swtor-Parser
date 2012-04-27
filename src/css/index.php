<?php

/**
 * 	   Swtor Parser v2
 *	 featuring Highcharts
 * @author Frank Schiemenz
 */
	include("parser.php");	
	include("includes.php");
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Swtor Parser v2</title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
		<script type="text/javascript" src="js/grid.js"></script>
		<script type="text/javascript" src="js/plotter.js"></script>
		<script type="text/javascript" src="js/utility.js"></script>
		<script type="text/javascript" src="js/json2.js"></script>
		<link href="css/style.css" type="text/css" rel="stylesheet" /> 
	</head>
	<body>
	<div id="index_page">
		<div id="the_header">
			<p><strong>Swtor Parser v2</strong> written by F.Schiemenz</p>
			<p>Please choose a logfile below<a href="uploads/sample-log.zip"> (sample-log.zip)</a></p>
		</div>
		<div id="the_form">
			<form enctype="multipart/form-data" action="index.php" method="POST" name="form">
				<input type="file" name="file" />
				<input type="submit" value="Upload" name="uploadButton" onclick="checkFileUpload()" />
				<?php $uploadedFile = basename($_FILES['file']['name']);
					
					if(move_uploaded_file($_FILES['file']['tmp_name'], $GLOBALS["UPLOAD_PATH"] . $uploadedFile))
					{		
						$file_parts = pathinfo($uploadedFile);
						
						$file_extension = strtolower($file_parts['extension']); 
							
						switch($file_extension) {
							
							case "zip": {
									
								$zip = new ZipArchive;
									
								if($zip->open($GLOBALS["UPLOAD_PATH"] . $uploadedFile))
								{
									if($zip->getNameIndex(0) && $zip->extractTo($GLOBALS["UPLOAD_PATH"]))
									{
										$uploadedFile = $zip->getNameIndex(0);
									}
									else { echo 'Zip-Error'; }
									
									$zip->close();
								}
								else { echo 'Zip-Error'; }
								
								break;
							}
							
							case "txt" : {break;} 
							
							default    : {echo 'File extension "' . $file_extension . '" not supported'; return;}
						}
						
						echo '<input type="hidden" name="uploadFile" value="' . $uploadedFile . '">';
						echo '<select name="users" onchange="showPlot(this.value)">';
							echo '<option value="">Success! Please select . . .</option>';
							
							echo '<option value="1">' . getUserName($uploadedFile) . ' -> Healing -> Done By Ability </option>';
							echo '<option value="2">' . getUserName($uploadedFile) . ' -> Healing -> Done To Target </option>';
							echo '<option value="3">' . getUserName($uploadedFile) . ' -> Healing -> Received By Ability </option>';
							echo '<option value="4">' . getUserName($uploadedFile) . ' -> Healing -> Received By Source </option>';
							
							echo '<option value="5">' . getUserName($uploadedFile) . ' -> Damage -> Done By Ability </option>';
							echo '<option value="6">' . getUserName($uploadedFile) . ' -> Damage -> Done To Target </option>';
							echo '<option value="7">' . getUserName($uploadedFile) . ' -> Damage -> Done By Type </option>';
							echo '<option value="8">' . getUserName($uploadedFile) . ' -> Damage -> Attack Types </option>';
							//echo '<option value="9">' . getUserName($uploadedFile) . ' -> Damage -> DPS </option>';
							
							echo '<option value="10">' . getUserName($uploadedFile) . ' -> Tanking -> Avoidance </option>';
							echo '<option value="11">' . getUserName($uploadedFile) . ' -> Tanking -> Thread Done </option>';
							echo '<option value="12">' . getUserName($uploadedFile) . ' -> Tanking -> Damage Taken  </option>';
		
						echo '</select>';
					}
					else if (isset($_REQUEST['uploadButton'])) 
					{
						echo "There was an error uploading the file, please try again!";
					}
		
				?>
				</form>
			</div> <!-- THE FORM DIV -->
			<div id="highcharts_container"></div>
			<div id="another_highcharts_container"></div>
		</div> <!-- INDEX PAGE DIV -->
	</body>
</html>

