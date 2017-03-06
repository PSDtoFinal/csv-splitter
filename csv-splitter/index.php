<!DOCTYPE html>
<?php
$prefs = file('last-run.txt');
$numRows = (isset($prefs[0]) && strlen($prefs[0]) ? trim($prefs[0]) : 800);
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
			<?php if (isset($_GET['error']) && strlen($_GET['error'])) : ?>
				<div class="error-msg">
					<p>
						<strong>Error:</strong>
						<?php echo urldecode($_GET['error']) ?>
					</p>
				</div>
			<?php endif; ?>
			<p>This script attempts to break large CSV files into smaller parts To get started:</p>
			<ol>
				<li>Upload a <strong>CSV</strong> file using the form below</li>
				<li>Once complete, collect your files from <code><?php echo dirname(__FILE__).DIRECTORY_SEPARATOR ?>Split Files</code></li>	
			</ol>
			<fieldset>
				<h2>Options</h2>
				<p>
					<label for="csv-file">CSV File to Split</label>
					<input type="file" name="csv-file" id="csv-file" />
				</p>
				<p>
					<label for="max-rows">Rows per File</label>
					<input type="number" name="max-rows" id="max-rows" step="1" min="1" required="required" value="<?php echo $numRows; ?>" />
				</p>
				<p>
					<input type="submit" name="formsubmit" id="formsubmit" value="Start Splitting" />
				</p>
			</fieldset>
		</form>
	</body>
</html>