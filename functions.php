<?php

require_once 'libAllure/util/shortcuts.php';

$baseUrl = 'http://ci.teratan.net/repositories/pub/';

errorHandler()->beGreedy();

function logger($message) {
	error_log('hacky-repository-manager: ' . $message, 0);
}

function getTargetDir($filename) {
	$root = 'pub/';
	$prefix = '';

	if (isset($_SERVER['HTTP_JOB_NAME'])) {
		$prefix = $_SERVER['HTTP_JOB_NAME'] . '/';
	}

	$ret = $root . $prefix;

	
	if (!is_dir($ret)) {
		logger("making dirs:" . $ret);

		if (!mkdir($ret, 0777, true)) {
			throw new Exception("could not make dirs" . $ret);
		}
	}

	checkValidPath($ret);

	return $ret;
}

function checkValidPath($path) {
	return true; // aherm.
}

function checkUploadedFileProblems($file) {
	switch ($file['error']) {
		case null: break;
		case 1: throw new Exception('File is too large.');
		default: throw new Exception('Unhandled PHP error uploading file:' . $file['error']);
	}
}

function updateRpmRepoYumConf($dir) {
	global $baseUrl;
	$name = basename($dir);

	$yumConf = '';
	$yumConf .= '[' . $name . ']' . "\n";
	$yumConf .= 'name=' . $name . "\n";
	$yumConf .= 'metadata_expire=0' . "\n";
	$yumConf .= 'enabled=1' . "\n";
	$yumConf .= 'gpgcheck=0' . "\n";
	$yumConf .= 'baseurl=' . $baseUrl . $name . "\n";

	file_put_contents($dir . '' . $name . '.repo', $yumConf);
}

function updateRpmRepo($dir) {
	$originalDir = getcwd();

	logger("updating RPM repo: $dir");
	chdir($dir);
	logger(exec("createrepo ."));

	chdir($originalDir);

	updateRpmRepoYumConf($dir);
}

function rotateOldFiles($dir) {
	
}

function doPostActions($dir, $filename) {
	logger("post actions on: $dir");
	rotateOldFiles($dir);

	if (strpos($dir, 'rpm') !== FALSE) {
		updateRpmRepo($dir);
	}
}

function homepage() {
	echo '<h1>Hacky Repository Manager</h1>';
	echo '<a href = "pub">Repositories</a>';
	echo '<h3>POSTing/Uploading</h3>';
	echo 'Make a HTTP POST request to upload.php this location with a file.';
}

?>
