<?php

require_once 'includes/common.php';

try {
	if (count($_FILES) > 0) {
		logger('Uploading file to hackyrepositorymanager: ' . current($_FILES)['name']);

		$file = current($_FILES);
		checkUploadedFileProblems($file);

		$origin = $file['name'];

		try {
			$repo = getRepositoryByPackageFilename($origin);
		} catch (Exception $e) {
			$repo = getRepositoryByName('default');
		}

		$destin = $repo->getBaseDir() . $file['name'];

		logger("Writing $origin to $destin");

		$res = move_uploaded_file($file['tmp_name'], $destin);

		if (!$res) {
			throw new Exception("Could not move uploaded file");
		}

		database\writePackageMetadata($file['name'], $repo);

		@unlink($repo->getBaseDir() . '/latest');
		symlink($file['name'], $repo->getBaseDir() . '/latest');

		doPostActions($repo->getBaseDir(), $file['name']);

		logger('Package ' . $file['name'] . ' uploaded to repo: ' . $repo->getName());
	} else {
		logger("No files provided to upload");
	}
} catch (Exception $e) {
	logger($e);
}

?>
