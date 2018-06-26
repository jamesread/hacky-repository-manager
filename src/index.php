<?php

require_once 'includes/widgets/header.php';

$uploadUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/upload.php';

?>

<h3>POSTing/Uploading</h3>
<p>Make a HTTP POST request to upload.php this location with a file. Here's the location to upload to;</p>
<pre><?= $uploadUrl ?></pre>

<h3>Latest versions</h3>
<?php

$tpl->assign('CFG_REPO_BASE', $CFG_REPO_BASE);
$tpl->assign('repos', database\getRepos());
$tpl->display('listRepos.tpl');

?>


