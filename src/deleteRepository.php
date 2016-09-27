<?php

require_once 'includes/widgets/header.php';

$name = san()->filterString('name');

$repo = getRepositoryByName($name);
$repo->delete();

require_once 'includes/widgets/footer.php';

?>
