<?php
$pageTitle = "Hosts";
include "../include/db.php";
include "../include/dl_host.php";
include "../include/header.php";


	if (isset($_GET["n"])) {
		HeaderDraw();
		$HostUrlName = $_GET["n"];
		DL_HostDetails($HostUrlName);
	}


include "../include/footer.php";
?>
