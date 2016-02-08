<?php

require_once 'functions.php';

try {
	if (count($_FILES) > 0) {
		$file = current($_FILES);
		checkUploadedFileProblems($file);

		$origin = $file['name'];
		$destin = getTargetDir($origin) . $file['name'];
//		$destin = '/pub';

		logger("Writing $origin to $destin");

		$res = move_uploaded_file($file['tmp_name'], $destin);

		if (!$res) {
			throw new Exception("Could not move uploaded file");
		}

		@unlink(getTargetDir($origin) . '/latest');
		symlink($file['name'], getTargetDir($origin) . '/latest');

		doPostActions(getTargetDir($origin), $file['name']);

		logger("done");
	} else {
		logger("No files provided to upload");
	}
} catch (Exception $e) {
	logger($e);
}



?>
