<?php
session_start();
session_destroy();
header("Location: login.php");
exit;

// session beendet , so dass keiner mehr angemolden ist
?>
