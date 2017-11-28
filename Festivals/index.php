<?php
$pageTitle = "Festivals";
include "../include/db.php";
include "../include/dl_festival.php";
include "../include/header.php";


HeaderDraw();
?>


<div style="padding-top:10px;padding-bottom:10px;">
	
<?php 
DL_FestivalInvolvedAndList();
?>

</div>



<?php
include "../include/footer.php";
?>