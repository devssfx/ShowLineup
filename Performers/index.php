<?php
$pageTitle = "Performers";
include "../include/db.php";
include "../include/dl_performer.php";
include "../include/header.php";


HeaderDraw();
?>


<div style="padding-top:10px;padding-bottom:10px;">
	
<?php 
DL_PerformerInvolvedAndList();
?>

</div>



<?php
include "../include/footer.php";
?>