<?php

namespace database {

function init() {
	global $CFG_DB_DSN;
	global $CFG_DB_USER;
	global $CFG_DB_PASS;

	$db = new \libAllure\Database($CFG_DB_DSN, $CFG_DB_USER, $CFG_DB_PASS);

	\libAllure\DatabaseFactory::registerInstance($db);
}

function deleteRepository($repo) {
	$sql = 'DELETE FROM repositories WHERE id = :id ';
	$stmt = stmt($sql);
	$stmt->bindValue(':id', $repo->id);
	$stmt->execute();
}

function getRepos() {
	$sql = 'SELECT r.id, r.name, max(p.uploaded) as uploaded, p.filename FROM repositories r LEFT JOIN packages p ON p.repo = r.id GROUP BY r.id';
	$stmt = stmt($sql);
	$stmt->execute();

	return $stmt->fetchAll();
}

function createRepository($name) {
	$sql = 'INSERT INTO repositories (name) VALUES (:name)';
	$stmt = stmt($sql);
	$stmt->bindValue(':name', $name);
	$stmt->execute();
}

function writePackageMetadata($filename, $repo) {
	$sql = 'INSERT INTO packages (filename, repo, uploaded) VALUES (:filename, :repo, now())';
	$stmt = stmt($sql);
	$stmt->bindValue(':filename', $filename);
	$stmt->bindValue(':repo', $repo->getId());
	$stmt->execute();
	
	logger("Wrote package metadata for $filename");
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




}

?>
