<?php

namespace model;

class Repository {
	private $name;
	private $baseDir;
	private $id;

	public function fromDatabase($repo) {
		global $CFG_REPO_ROOT;

		$this->name = $repo['name'];

		$prefix = $repo['name'];

		$ret = $CFG_REPO_ROOT . $prefix;

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

	public function delete() {
		$this->rmdir($this->getBaseDir());

		\database\deleteRepository($this);
	}

	private function rmdir($dir) {
		$results = array_diff(scandir($dir), array('.', '..'));

		foreach ($results as $file) {
			$path = $dir . DIRECTORY_SEPARATOR . $file;

			if (is_dir($path)) {
				$this->rmdir($path);
			} else {
				unlink($path);
			}
		}

		return rmdir($dir);
		
	}
}

?>
