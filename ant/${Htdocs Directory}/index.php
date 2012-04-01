<?php
	include("parser.php");	
	include("includes.php");
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title></title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
		<script type="text/javascript" src="js/plotter.js"></script>
	</head>
	<body>
	<form enctype="multipart/form-data" action="index.php" method="POST" name="form">
		Choose a log file to upload
		<input type="file" name="file" />
		<input type="submit" value="Upload Log" name="uploadButton" onclick="checkFileUpload()" /> <br />
		<?php
			$uploadedFile = basename($_FILES['file']['name']);

			if(move_uploaded_file($_FILES['file']['tmp_name'], $GLOBALS["UPLOAD_PATH"] . $uploadedFile))
			{		
				$file_parts = pathinfo($uploadedFile);
				
				$file_extension = strtolower($file_parts['extension']); 
					
				switch($file_extension) // TODO add rar and 7z-support
				{
					case "zip": {
							
						$zip = new ZipArchive;
							
						if ($zip->open($GLOBALS["UPLOAD_PATH"] . $uploadedFile))
						{
							if( $zip->getNameIndex(0) && $zip->extractTo($GLOBALS["UPLOAD_PATH"]) )
							{
								$uploadedFile = $zip->getNameIndex(0);
							}
							else
							{
								echo 'Zip-Error';
							}
							
							$zip->close();
						}
						else
						{
							echo 'Zip-Error';
						}
						break;
					}
					
					case "txt" : {break;} 
					
					default    : {echo 'File extension "' . $file_extension . '" not supported'; return;}
				}
				
				echo '<input type="hidden" name="uploadFile" value="' . $uploadedFile . '">';
				echo "The upload was successful  ";
				echo '<select name="users" onchange="showPlot(this.value)">';
				echo '<option value="">Please select...</option>';
				echo '<option value="3">' . getUserName($uploadedFile) . ': Healing done </option>';
				echo '<option value="4">' . getUserName($uploadedFile) . ': Healing received </option>';
				echo '<option value="1">' . getUserName($uploadedFile) . ': Damage done </option>';
				echo '<option value="2">' . getUserName($uploadedFile) . ': Damage taken </option>';
				echo '<option value="5">' . getUserName($uploadedFile) . ': Damage Types </option>';
				echo '<option value="6">' . getUserName($uploadedFile) . ': Attack Types </option>';
				echo '<option value="7">' . getUserName($uploadedFile) . ': Avoidance </option>';
				echo '<option value="8"> TEST </option>';
				echo '</select>';
			}
			else if (isset($_REQUEST['uploadButton'])) 
			{
				echo "There was an error uploading the file, please try again!";
			}

		?>
		</form>
		<div id="highchartsContainer" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
		<div id="highchartsContainer2" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
	</body>
</html>

