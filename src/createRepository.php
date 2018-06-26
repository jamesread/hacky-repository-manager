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

		$dir = $CFG_REPO_ROOT . DIRECTORY_SEPARATOR . $this->getElementValue('name');

		require_once 'includes/widgets/header.php';
		logger("Trying to create: $dir");

		@mkdir($dir);

		database\createRepository($this->getElementValue('name'));
		require_once 'includes/widgets/footer.php';

	}
}

$handler = new \libAllure\FormHandler('FormCreateRepository');
$handler->handle();
$handler->setRedirect('index.php');

?>
