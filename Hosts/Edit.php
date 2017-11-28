<?php
$pageTitle = "Hosts";
include "../include/db.php";
include "../include/dl_host.php";
include "../include/header.php";
?>


<?php

$success = 0;

$path = "../";


//check for save
if(isset($_POST['HostName'])){

	$thisId = $_POST['HostId'];
	$thisName = $_POST['HostName'];
	$thisFriendly = UrlFriendlyName($thisName);
	$thisStatus = $_POST['StatusValueString'];
	$thisDesc = $_POST['HostDesc'];
	
	$success = DL_HostSave($thisId, $thisName, $thisFriendly, $thisStatus, $thisDesc);

	if(is_string($success)){
		if(strlen($thisId) == 0)
			header('Location:' . $success);
		else
			header('Location:' . "../" . $success);
	}

}else{
	//check for edit
	if(isset($_GET["n"])){
		DL_HostLoad($_GET["n"]);
		$path = $path . "../";
		
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
	}
?>


<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
	<input id="HostId" name="HostId" type="hidden" value="<?php echo $thisId; ?>" />
	<div class="editBar">
		<input type="submit" value="Save" onclick="$('#divHostDesc').find('input')[0].value = CKEDITOR.instances['edHostDesc'].getData();" />
		<a href="<?php echo $path . "Hosts/" . $thisFriendly; ?>">Cancel</a>
	</div>
	<h2>Add Host</h2>
	<table>
		<tr>
			<td><label for="HostName">Name</label></td>
			<td><input data-val="true" data-val-required="The Name field is required." id="HostName" name="HostName" type="text" value="<?php echo $thisName; ?>" />
			<span class="field-validation-valid" data-valmsg-for="HostName" data-valmsg-replace="true"></span></td>
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
	<div id="divHostDesc">
		<label for="HostDesc">Description</label>
		<input id="HostDesc" name="HostDesc" type="hidden" value="" />
		<textarea name="edHostDesc" id="edHostDesc" rows="10" cols="10"><?php echo $thisDesc; ?></textarea>
		<script type="text/javascript">
			CKEDITOR.replace('edHostDesc');
		</script>
	</div>
	
</form>






<?php
include "../include/footer.php";
?>
