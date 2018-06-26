<?php

require_once 'libAllure/util/shortcuts.php';

require_once 'includes/model/Route.php';
require_once 'includes/model/Repository.php';

errorHandler()->beGreedy();

function logger($message) {
	error_log('hacky-repository-manager: ' . $message, 0);
	echo $message . "\n";
}

function ruleEval($source, $route) {
		switch ($route->operator) {
			case '=':
				return $source == $route->source;
			case '~': // contains
				$fragments = explode('|', $source);

				foreach (explode('|', $route->source) as $fragment) {
					$noMatch = stripos($source, $fragment) === false;
					echo '- ' . $source . ' ' . $fragment . ' = ' . !$noMatch . "\n";

					if ($noMatch) {
						return false;
					}
				}

				return true;
			default: 
				return false;
				
		}
}

function getRepositoryByPackageFilename($filename) {
	foreach (getRoutes() as $route) {
		$source = null;

		switch ($route->type) {
			case 'jenkins':
				if (isset($_SERVER['HTTP_JOB_NAME'])) {
					$source = $_SERVER['HTTP_JOB_NAME'];
				}

				break;
			case 'filename':
				$source = $filename;
				break;
			default:
				continue;
		}

		if (ruleEval($source, $route)) {
			echo "Matched: $route->line\n";
			return database\getRepositoryByName($route->destination);
		} else {
			echo 'Rule does not match: ' . $route->line . "\n";
		}
	}
	
	throw new Exception('Could not determine which repo to use.');
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
	global $CFG_REPO_BASE;
	$name = basename($dir);

	$yumConf = '';
	$yumConf .= '[' . $name . ']' . "\n";
	$yumConf .= 'name=' . $name . "\n";
	$yumConf .= 'metadata_expire=0' . "\n";
	$yumConf .= 'enabled=1' . "\n";
	$yumConf .= 'gpgcheck=0' . "\n";
	$yumConf .= 'baseurl=' . $CFG_REPO_BASE . $name . "\n";

	file_put_contents($dir . '' . $name . '.repo', $yumConf);
	@symlink($dir . '' . $name, 'repo');
}

function updateRpmRepo($dir) {
	$originalDir = getcwd();

	logger("updating RPM repo: $dir");
	chdir($dir);
	logger(exec("createrepo . --deltas "));

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

function getRoutes() {
	$ret = array();

	foreach (explode("\n", \database\getSetting('routes')) as $routeLine) {
		$matches = null;
		preg_match_all('/(?<type>[\w-_]+) ?(?<operator>[=~]) ?(?<source>[\w-_\.\|]+)[ ]*?->(?<destination>.+)/i', $routeLine, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			$route = new Route();
			$route->parsed = $match;
			$route->line = $routeLine;

			if (isset($match['destination'])) {
				$route->destination = trim($match['destination']);
			}

			$route->operator = $match['operator'];

			if (isset($match['type'])) {
				$route->type = $match['type'];
			}

			if (isset($match['source'])) {
				$route->source = $match['source'];
			}


			$ret[] = $route;
		}
	}

	return $ret;
}

function getRepositoryByName($name) {
	$repoFromDb = database\getRepositoryFromDatabase($name);

	if ($repoFromDb == null) {
		createRepository($name);
		$repoFromDb = database\getRepositoryFromDatabase($name);
	}

	if ($repoFromDb == null) {
		throw new Exception('Could not find repo by name: ' . $name);
	} else {
		$repo = new model\Repository();
		$repo->fromDatabase($repoFromDb);

		return $repo;
	}
}


?>
