<?php

namespace database;

require_once 'includes/model/Repository.php';

require_once 'MongoDB/autoload.php';

use MongoDB\Client;

$db = init()->hrm;

$reposCollection = $db->repos;

function init() {
	$client = new Client('mongodb://127.0.0.1');

	return $client;
}

function getRepos() {
	global $reposCollection;

	$res = $reposCollection->find();

	$ret = array();

	foreach ($res as $doc) {
		$doc['id'] = $doc['_id'];

		$r = new \model\Repository();
		$r->fromDatabase($doc);

		$ret[] = $r;
	}

	return $ret;
}

function createRepository($name) {
	global $reposCollection;

	$reposCollection->insertOne([
		'name' => $name
	]);

}

function getSetting($key) {
	global $db;

	$settings = $db->settings->findOne(['key' => $key]);

	if (empty($settings)) {
		return null;	
	} else {
		return $settings['value'];
	}
}

function setSetting($key, $value) {
	global $db;

	$db->settings->deleteMany([
		'key' => $key
	]);

	$db->settings->insertOne([
		'key' => $key,
		'value' => $value
	]);
}


function getRepositoryFromDatabase($repoName) {
	global $reposCollection;

	$ret = $reposCollection->findOne([
		'name' => $repoName
	]);

	$ret['id'] = $ret['_id'];

	return $ret;
}


function writePackageMetadata($filename, $repo) {
	global $db;

	$db->packages->insertOne([
		'filename' => $filename,
		'repo' => $repo,
		'uploaded' => '$currentDate'
	]);
}

function deleteRepository($repo) {
	global $reposCollection;

	$reposCollection->deleteOne(['_id' => $repo->getId()]);
}


