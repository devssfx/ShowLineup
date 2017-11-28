<?php
$pageTitle = "Performers";
include "../include/db.php";
include "../include/dl_performer.php";
include "../include/header.php";
?>


<?php

$success = 0;
$error = "";

$path = "../../";

//check for save
if(isset($_POST['PerformerName'])){

	$thisId = $_POST['PerformerId'];
	$thisName = $_POST['PerformerName'];
	$thisFriendly = UrlFriendlyName($thisName);
	$thisStatus = $_POST['StatusValueString'];
	$thisDesc = $_POST['PerformerDesc'];


	$thisLocationList = array();
	foreach ($_POST as $key => $val) {
		if(strlen($key) > 10){
			if(substr($key, 0, 10) == "chkLocArea"){
				$thisLocationList[] = substr($key, 10);
			}
		}
	}
	if(count($thisLocationList) == 0){
		$error = "You must select at least one location from the list at the bottom of the form.";
	}else{

		$success = DL_PerformerSave($thisId, $thisName, $thisFriendly, $thisStatus, $thisDesc, $thisLocationList);

		if(is_string($success)){
			if(strlen($thisId) == 0)
				header('Location:' . $success);
			else
				header('Location:' . "../" . $success);
		}

	}

}else{
	//check for edit
	if(isset($_GET["n"])){
		DL_PerformerLoad($_GET["n"]);
		
	}
	
}

HeaderDraw();



?>

<script src="<?php echo $path; ?>Scripts/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo $path; ?>Scripts/jquery.validate.unobtrusive.min.js" type="text/javascript"></script>
<script src="<?php echo $path; ?>ckeditor/ckeditor.js" type="text/javascript"></script>


<?php
	if($success < 0){
		echo '<div class="input-validation-error">There was a problem saving. Please try again. (' . $success . ')</div>';
	}else if(strlen($error) > 0){
		echo '<div class="input-validation-error">' . $error . '</div>';
	}
?>


<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<input id="PerformerId" name="PerformerId" type="hidden" value="<?php echo $thisId; ?>" />
	<div class="editBar">
		<input type="submit" value="Save" onclick="$('#divPerformerDesc').find('input')[0].value = CKEDITOR.instances['edPerformerDesc'].getData();" />
		<a href="<?php echo $path . "Performers/" . $thisFriendly; ?>">Cancel</a>
	</div>
	<h2>Add Performer</h2>
	<table>
		<tr>
			<td><label for="PerformerName">Name</label></td>
			<td><input data-val="true" data-val-required="The Name field is required." id="PerformerName" name="PerformerName" type="text" value="<?php echo $thisName; ?>" />
			<span class="field-validation-valid" data-valmsg-for="PerformerName" data-valmsg-replace="true"></span></td>
		</tr>
		<tr>
			<td><label for="StatusValueString">Status</label></td>
			<td>
				<select id="StatusValueString" name="StatusValueString">
					<option <?php if($thisStatus == 0) echo 'selected="selected" '; ?> value="0">Draft</option>
					<option <?php if($thisStatus == 1) echo 'selected="selected" '; ?> value="1">Open</option>
					<option <?php if($thisStatus == 2) echo 'selected="selected" '; ?> value="2">Closed</option>
				</select>
			</td>
		</tr>
		
	</table>
	<div id="divPerformerDesc">
		<label for="PerformerDesc">Description</label>
		<input id="PerformerDesc" name="PerformerDesc" type="hidden" value="" />
		<textarea name="edPerformerDesc" id="edPerformerDesc" rows="10" cols="10"><?php echo $thisDesc; ?></textarea>
		<script type="text/javascript">
			CKEDITOR.replace('edPerformerDesc');
		</script>
	</div>
	<?php
	include "../include/LocationSelection.php";
	
	LocationSelectionDraw(false, $thisLocationList);
	
	?>
</form>






<?php
include "../include/footer.php";
?>
