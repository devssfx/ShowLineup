<?php

$adminFor = "";
$urlName = "";

if(isset($_GET["f"])){
	$adminFor = $_GET["f"];
	$adminFor = substr($adminFor, 0 , strlen($adminFor)-1);
}
if(isset($_GET["n"])){
	$urlName = $_GET["n"];
}

if(strlen($adminFor) == 0 || strlen($urlName) == 0){
	Header("Location:/");
}


$pageTitle = $adminFor . "s";
include "include/db.php";
include "include/dl_admin.php";
include "include/header.php";



DL_AdminPage($adminFor, $urlName);
	


include "include/footer.php";



?>
