<?php
session_start();
include_once 'functii.php';
session_destroy();
header("Location: index.php");
exit;
?>
