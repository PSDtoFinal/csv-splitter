<?php

// Start by grabbing the uploaded file, and doing some checks...

// Make sure there's a file to check
if (!isset($_FILES['csv-file'])) {
	$error = 'No file was submitted';
	header("Location: ./index.php?error=".urlencode($error));
	exit();
}

// Make sure it's not empty
if (!$_FILES['csv-file']['size']) {
	$error = 'The CSV file appears to be empty';
	header("Location: ./index.php?error=".urlencode($error));
	exit();
}

// Check for upload errors
if ($_FILES['csv-file']['error']) {
	$error = 'There was an error while uploading the file';
	header("Location: ./index.php?error=".urlencode($error));
	exit();
}

// Rudamentary file format check
$safeish = array(
	'text/csv',
	'application/vnd.ms-excel',
);
if (!in_array($_FILES['csv-file']['type'],$safeish) || (stripos($_FILES['csv-file']['name'], '.csv') === FALSE)) {
	$error = 'The file does not appear to be a CSV';
	header("Location: ./index.php?error=".urlencode($error));
	exit();
}

// Check for a maximum row count
if (!isset($_POST['max-rows']) || ($_POST['max-rows'] < 1)) {
	$error = 'Could not determine how may rows per file';
	header("Location: ./index.php?error=".urlencode($error));
	exit();
}
?>
<html>
	<head>
		<title>CSV File Breakdown</title>
		<style>
			html, body { font-family: sans-serif; color:#333; }
			h1 { font-size:1.5em; text-align:center; }
			fieldset h2 { margin-top:0; padding-top:0; }
			fieldset, pre { border:0; margin:2em 0; padding:1em; background:#F2F2F2; }
			form, pre { width: 800px; max-width: 90%; position:relative; margin:0 auto; }
			pre { padding:2em 0; }
			code { font-size:1.25em; }
			label { display:inline-block; width: 10em; }
			.checkboxes label { width:auto; }
			.error-msg { background:#F2F2F2; border-left:4px solid red; padding:10px 0 10px 15px; }
			.error-msg * { padding:0; margin:0; }
			
			#formsubmit { float:right; padding:0.25em 1em; margin:0.5em 0; }
			#varname { margin-left:2em; padding:0.75em 1em; background:#FAFAFA; border:1px solid #EEE; }
		</style>
	</head>
	<body>
		<form enctype="multipart/form-data" action="run.php" method="post">
			<h1>CSV File Splitter</h1>
			<fieldset>
				<?php
				// OK, if we're here - it's likely the file is OK.
				error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
				$firstLineColName = TRUE;
				$breakAt = 50;
				
				$linenumber = 0;
				$filecount = 1;
				$colNames = "";
				$file = fopen($_FILES['csv-file']['tmp_name'],"r");
				while(!feof($file)) {
					$line = fgetcsv($file);
				
					if ($firstLineColName && !$linenumber) {
						echo 'Collected column names<br />';
						$colNames = $line;
						$linenumber++;
						continue;
					}
					
					if (!isset($fp)) {
						echo 'Creating <code>./Split Files/split-file-'.$filecount.'.csv</code><br />';
						if (!file_exists()) {
							mkdir('./Split Files',0655,TRUE);
						}
						
						$fp = fopen('./Split Files/split-file-'.$filecount.'.csv', 'w');
						if ($firstLineColName && strlen($colNames)) {
							fwrite($fp, $colNames);
						}
					}
					
					fwrite($fp, $line);
					$linenumber++;
					
					if (($breakAt * $filecount) == $linenumber) {
						fclose($fp);
						unset($fp);
						$filecount++;
					}
				}				
				?>
				<hr />
				<h3>Job Completed</h3>
				<p>You can find the split files in <code><?php echo dirname(__FILE__).DIRECTORY_SEPARATOR ?>Split Files</code></p>
			</fieldset>
		</form>
	</body>
</html>

