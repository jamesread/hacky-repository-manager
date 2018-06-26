<?php

require_once 'includes/widgets/header.php';

?>

<h2>Environment config</h2>
<p>post_max_size: <?php echo ini_get('post_max_size'); ?></p>
<p>upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?></p>
<p>CFG_REPO_BASE: <?php echo $CFG_REPO_BASE; ?></p>
<p>CFG_REPO_ROOT: <?php echo $CFG_REPO_ROOT; ?></p>
<p>SELinux, <?php echo `getsebool httpd_unified`; ?>
