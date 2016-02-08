<?php

require_once 'functions.php';

try {
	homepage();
} catch (Exception $e) {
	logger($e);
}

?>
