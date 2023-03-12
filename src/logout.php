<?php
session_start();
require_once('session_manager.php');
require_once('config.php');

$session_manager = new SessionManager();

// Unset all session variables
$session_manager->destroy();

// Redirect the user to the login page
header('Location: '. PAGE_MAIN);
exit;
?>
