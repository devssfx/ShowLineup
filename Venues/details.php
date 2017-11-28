<?php
$pageTitle = "Venues";
include "../include/db.php";
include "../include/dl_venue.php";
include "../include/header.php";


	if (isset($_GET["n"])) {
		HeaderDraw();
		$UrlName = $_GET["n"];
		DL_VenueDetails($UrlName);
	}


include "../include/footer.php";
?>
