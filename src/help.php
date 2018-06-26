<?php

require_once 'includes/widgets/header.php';

$uploadUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/upload.php';

?>

<h3>POSTing/Uploading</h3>
<p>Make a HTTP POST request to upload.php this location with a file. Here's the location to upload to;</p>
<pre><?= $uploadUrl ?></pre>

<h4>by using CURL</h4>

<pre>
FILE=myfile.txt
curl -F "file=@$FILE;filename=$FILE" "http://ci.teratan.net/hacky-repository-manager/src/upload.php"
</pre>

<?php

require_once 'includes/widgets/footer.php';

?>

