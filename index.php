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

echo '<table>';
echo '<tr><th>repo</th><th>updated</th><th>package</th></tr>';
foreach ($stmt->fetchAll() as $repo) {
	echo '<tr><td><a href = "pub/' . $repo['name'] . '">' . $repo['name'] . '</a></td><td><a href = "pub/' . $repo['name'] . '/' . $repo['filename'] . '">' . $repo['uploaded'] . '</a></td><td>' . $repo['filename'] . '</td></tr>';
}
echo '</table>';

?>

<h3>Config</h3>
<p>post_max_size: <?php echo ini_get('post_max_size'); ?></p>
<p>upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?></p>

