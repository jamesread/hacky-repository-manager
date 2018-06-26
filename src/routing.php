<?php

require_once 'includes/widgets/header.php';

use \libAllure\FormHandler;
use \libAllure\ElementTextbox;

class FormRouting extends \libAllure\Form {
	public function __construct() {
		parent::__construct('formRouting', 'Update Routing');

		$routes = database\getSetting('routes');

		$this->addElement(new ElementTextbox('routes', 'Routes', $routes));
		$this->addDefaultButtons();
	}

	public function process() {
		database\setSetting('routes', $this->getElementValue('routes'));
	}
}

$f = new FormRouting();

if ($f->validate()) {
	$f->process();
}

$tpl->assign('routes', getRoutes());
$tpl->display('routing.tpl');

$tpl->assignForm($f);
$tpl->display('form.tpl');

$tpl->display('routingHelp.tpl');

require_once 'includes/widgets/footer.php';

?>
