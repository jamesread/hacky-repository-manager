<?php

require_once 'includes/widgets/header.php';

?>

<h3>Latest versions</h3>
<?php

$tpl->assign('CFG_REPO_BASE', $CFG_REPO_BASE);
$tpl->assign('repos', database\getRepos());
$tpl->display('listRepos.tpl');

require_once 'includes/widgets/footer.php';

?>


