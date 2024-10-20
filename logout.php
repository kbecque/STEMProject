<?php
session_start();
session_destroy(); // Vernietig de sessie
header("Location: login.php"); // Redirect naar de login-pagina
exit;
?>
