<?php

require_once 'libAllure/util/shortcuts.php';

errorHandler()->beGreedy();

function writePackageMetadata($filename, $repo) {
	$sql = 'INSERT INTO packages (filename, repo, uploaded) VALUES (:filename, :repo, now())';
	$stmt = stmt($sql);
	$stmt->bindValue(':filename', $filename);
	$stmt->bindValue(':repo', $repo->getId());
	$stmt->execute();
	
	logger("Wrote package metadata for $filename");
}

function logger($message) {
	error_log('hacky-repository-manager: ' . $message, 0);
	echo $message . "\n";
}

class Repo {
	private $name;
	private $baseDir;
	private $id;

	public function __construct() {
	}

	public function fromDatabase($repo) {
		global $CFG_REPO_ROOT;

		$this->name = $repo['name'];

		$root = $CFG_REPO_ROOT;
		$prefix = $repo['name'];

		$ret = $root . $prefix;

		if (!is_dir($ret)) {
			logger("making dirs:" . $ret);

			if (!mkdir($ret, 0777, true)) {
				throw new Exception("could not make dirs" . $ret);
			}
		}

		checkValidPath($ret);

		$this->baseDir = $ret;
		$this->id = $repo['id'];
	}

	public function getBaseDir() {
		return $this->baseDir . '/';
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}
}

function getRepositoryByNameSoft($repoName) {
	$sql = 'SELECT r.id, r.name FROM repositories r WHERE r.name = :name';
	$stmt = stmt($sql);
	$stmt->bindValue(':name', $repoName);
	$stmt->execute();

	if ($stmt->numRows() == 0) {
		return null;
	} else {
		return $stmt->fetchRow();
	}
}

function createRepository($name) {
	$sql = 'INSERT INTO repositories (name) VALUES (:name) ';
	$stmt = stmt($sql);
	$stmt->bindValue(':name', $name);
	$stmt->execute();
}

function getRepositoryByName($name) {
	$repoFromDb = getRepositoryByNameSoft($name);

	if ($repoFromDb == null) {
		createRepository($name);
		$repoFromDb = getRepositoryByNameSoft($name);
	}

	if ($repoFromDb == null) {
		throw new Exception('Could not find repo by name: ' . $name);
	} else {
		$repo = new Repo();
		$repo->fromDatabase($repoFromDb);

		return $repo;
	}
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

function getRepository($filename) {
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
			return getRepositoryByName($route->destination);
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

function getSetting($key) {
	$sql = 'SELECT s.value FROM settings s WHERE s.`key` = :key ';
	$stmt = stmt($sql);
	$stmt->bindValue(':key', $key);
	$stmt->execute();

	try {
		$row = $stmt->fetchRow();
		$value = $row['value'];

		return $value;
	} catch (Exception $e) {
		return null; 
	}
}

function setSetting($key, $value) {
	$sql = 'INSERT INTO settings (`key`, `value`) VALUES (:key, :value1) ON DUPLICATE KEY UPDATE `value` = :value2';
	$stmt = stmt($sql);
	$stmt->bindValue(':key', $key);
	$stmt->bindValue(':value1', $value);
	$stmt->bindValue(':value2', $value);
	$stmt->execute();
}

function getRoutes() {
	$ret = array();

	foreach (explode("\n", getSetting('routes')) as $routeLine) {
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

class Route {
	public $type;
	public $source;
	public $operator;
	public $destination;
	public $parsed;
	public $line;
}

?>
