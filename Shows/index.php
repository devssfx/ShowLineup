<?php
$pageTitle = "Shows";
include "../include/db.php";
include "../include/dl_show.php";
include "../include/header.php";


HeaderDraw();
?>


<style type="text/css">
	.showAgenda .dayHeading
	{
		background-color:Navy;
		color:White;
		padding:5px;
		margin-bottom:4px;
		margin-top:5px;
	}
	.showAgenda .showDetails
	{
		background-color:#e1e1e1;
		margin-bottom:3px;
		padding:3px;
	}
	.showAgenda .showDetails .showImage
	{
		float:right;
		margin-right:10px;
	}
</style>



<div style="padding-top:10px;padding-bottom:10px;" class="showAgenda">
	
<?php 
DL_ShowInvolvedAndList();
?>

</div>



<?php
include "../include/footer.php";
?>