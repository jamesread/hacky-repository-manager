<?php

require_once 'includes/common.php';

use \libAllure\ElementInput;

class FormCreateRepository extends libAllure\Form {
	public function __construct() {
		parent::__construct('formCreateRepo', 'Create Repository');
		$this->addElement(new ElementInput('name', 'Name'));
		$this->addDefaultButtons();
	}

	public function process() {
		global $CFG_REPO_ROOT;
		mkdir($CFG_REPO_ROOT . DIRECTORY_SEPARATOR . $this->getElementValue('name'));

		$sql = 'INSERT INTO repositories (name) VALUES (:name)';
		$stmt = stmt($sql);
		$stmt->bindValue(':name', $this->getElementValue('name'));
		$stmt->execute();
	}
}

$handler = new \libAllure\FormHandler('FormCreateRepository');
$handler->handle();

?>
