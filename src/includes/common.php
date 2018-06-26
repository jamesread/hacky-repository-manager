<?php

set_include_path('vendor/jwread/lib-allure/src/main/php/' . PATH_SEPARATOR . get_include_path());

require_once 'vendor/autoload.php';
require_once 'includes/functions.php';

@include_once '/etc/hrm/config.php';
@include_once 'includes/config.php';

if (isset($CFG_DATABASE) && $CFG_DATABASE == 'mysql') {
	require_once 'includes/database/mysql.php';
} else {
	require_once 'includes/database/mongodb.php';
}

database\init();

$tpl = new \libAllure\Template('hackyRepositoryManager');

?>
