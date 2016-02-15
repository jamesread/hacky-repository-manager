<?php

require_once 'includes/common.php';

try {
	homepage();
} catch (Exception $e) {
	logger($e);
}

?>
