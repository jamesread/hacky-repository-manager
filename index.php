<?php

require_once 'includes/widgets/header.php';

?>

<h3>POSTing/Uploading</h3>
<p>Make a HTTP POST request to upload.php this location with a file.</p>

<h3>Latest versions</h3>
<?php

$sql = 'SELECT r.id, r.name, max(p.uploaded) as uploaded, p.filename FROM repositories r LEFT JOIN packages p ON p.repo = r.id GROUP BY r.id';
$stmt = stmt($sql);
$stmt->execute();

$tpl->assign('CFG_REPO_BASE', $CFG_REPO_BASE);
$tpl->assign('repos', $stmt->fetchAll());
$tpl->display('listRepos.tpl');

?>

<h3>Config</h3>
<p>post_max_size: <?php echo ini_get('post_max_size'); ?></p>
<p>upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?></p>

