<?php
$pageTitle = "Venues";
include "../include/db.php";
include "../include/dl_venue.php";
include "../include/header.php";


HeaderDraw();
?>


<div style="padding-top:10px;padding-bottom:10px;">
	
<?php 
DL_VenueInvolvedAndList();
?>

</div>



<?php
include "../include/footer.php";
?>