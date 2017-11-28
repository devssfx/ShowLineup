<?php
$pageTitle = "Hosts";
include "../include/db.php";
include "../include/dl_host.php";
include "../include/header.php";


HeaderDraw();
?>


<div style="padding-top:10px;padding-bottom:10px;">
	
<?php 
DL_HostInvolvedAndList();
?>

</div>



<?php
include "../include/footer.php";
?>