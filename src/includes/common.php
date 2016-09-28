<?php

set_include_path(get_include_path() . PATH_SEPARATOR . 'vendor/jwread/lib-allure/src/main/php/');

require_once 'vendor/autoload.php';
require_once 'includes/functions.php';

@include_once '/etc/hrm/config.php';
@include_once 'includes/config.php';

$db = new \libAllure\Database($CFG_DB_DSN, $CFG_DB_USER, $CFG_DB_PASS);

$tpl = new \libAllure\Template('hackyRepositoryManager');

?>
