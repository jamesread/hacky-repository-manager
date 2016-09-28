<?php

set_include_path('vendor/jwread/lib-allure/src/main/php/' . PATH_SEPARATOR . get_include_path());

require_once 'vendor/autoload.php';
require_once 'includes/functions.php';

@include_once '/etc/hrm/config.php';
@include_once 'includes/config.php';

$db = new \libAllure\Database($CFG_DB_DSN, $CFG_DB_USER, $CFG_DB_PASS);

$tpl = new \libAllure\Template('hackyRepositoryManager');

?>
