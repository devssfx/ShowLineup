<?php
session_start();

//session_unset();  // remove all session variables
//session_destroy(); // destroy the session 

unset($_COOKIE['memme']);
setcookie('memme',"",time() - 1, '/');

unset($_SESSION["mi"]);
unset($_SESSION["mn"]);


header('Location:../');

?>
