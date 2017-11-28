<?php
//$tickCheck = round(microtime(true) * 1000);
//$tickCheck2 = round(microtime(true) * 1000);
//echo ($tickCheck2 - $tickCheck) . "<br>";
//$tickCheck = $tickCheck2;


$pageTitle = "Home";
include "include/db.php";
include "include/header.php";
include "include/dl_index.php";

HeaderDraw();

?>

<div style="padding-top:30px;padding-bottom:30px;">Welcome to Show Lineup. See what shows are on, find venues and become a fan of your favourite performers.</div>



<?php
DL_ListInfo();



include "include/footer.php";

?>
