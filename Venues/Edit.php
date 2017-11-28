<?php
$pageTitle = "Venues";
include "../include/db.php";
include "../include/dl_venue.php";
include "../include/header.php";
?>


<?php

$success = 0;
$error = "";

$path = "../../";

//check for save
if(isset($_POST['VenueName'])){

	$thisId = $_POST['VenueId'];
	$thisName = $_POST['VenueName'];
	$thisFriendly = UrlFriendlyName($thisName);
	$thisStatus = $_POST['StatusValueString'];
	$thisDesc = $_POST['VenueDesc'];
	$thisAddress1 = $_POST['Address1'];
	$thisAddress2 = $_POST['Address2'];
	$thisSuburb = $_POST['AddressSuburb'];
	$thisState = $_POST['AddressState'];
	$thisPostcode = $_POST['AddressPostcode'];
	if(isset($_POST['IsDefault']))
		$thisIsDefault = 1;
	else
		$thisIsDefault = 0;


	$thisLocationAreaId = "";
	foreach ($_POST as $key => $val) {
		if(strlen($key) > 10){
			if(substr($key, 0, 10) == "chkLocArea"){
				$thisLocationAreaId = substr($key, 10);
			}
		}
	}
	if($thisLocationAreaId == ""){
		$error = "A location is required for a venue. Select from the list at the bottom of the form.";
	}else{
		$success = DL_VenueSave($thisId, $thisName, $thisFriendly, $thisStatus, $thisDesc, $thisAddress1, $thisAddress2, $thisSuburb, $thisState, $thisPostcode, $thisIsDefault, $thisLocationAreaId);

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
		DL_VenueLoad($_GET["n"]);
		
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
	<input id="VenueId" name="VenueId" type="hidden" value="<?php echo $thisId; ?>" />
	<div class="editBar">
		<input type="submit" value="Save" onclick="$('#divVenueDesc').find('input')[0].value = CKEDITOR.instances['edVenueDesc'].getData();" />
		<a href="<?php echo $path . "Venues/" . $thisFriendly; ?>">Cancel</a>
	</div>
	<h2>Add Venue</h2>
	<table>
		<tr>
			<td><label for="VenueName">Name</label></td>
			<td><input data-val="true" data-val-required="The Name field is required." id="VenueName" name="VenueName" type="text" value="<?php echo $thisName; ?>" />
			<span class="field-validation-valid" data-valmsg-for="VenueName" data-valmsg-replace="true"></span></td>
		</tr>

		<tr>
			<td><label for="Address1">Address</label></td>
			<td><input id="Address1" name="Address1" type="text" value="<?php echo $thisAddress1; ?>" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input id="Address2" name="Address2" type="text" value="<?php echo $thisAddress2; ?>" /></td>
		</tr>
		<tr>
			<td><label for="AddressSuburb">Suburb</label></td>
			<td><input id="AddressSuburb" name="AddressSuburb" type="text" value="<?php echo $thisSuburb; ?>" /></td>
		</tr>
		<tr>
			<td><label for="AddressState">State</label></td>
			<td><input id="AddressState" name="AddressState" type="text" value="<?php echo $thisState; ?>" /></td>
		</tr>
		<tr>
			<td><label for="AddressPostcode">Postcode</label></td>
			<td><input id="AddressPostcode" name="AddressPostcode" type="text" value="<?php echo $thisPostcode; ?>" /></td>
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
		<tr>
			<td><label for="IsDefault">Set as Default</label></td>
			<td><input id="IsDefault" name="IsDefault" type="checkbox" value="true" <?php if($thisIsDefault) echo 'checked="checked"'; ?> /></td>
		</tr>
		
	</table>
	<div id="divVenueDesc">
		<label for="VenueDesc">Description</label>
		<input id="VenueDesc" name="VenueDesc" type="hidden" value="" />
		<textarea name="edVenueDesc" id="edVenueDesc" rows="10" cols="10"><?php echo $thisDesc; ?></textarea>
		<script type="text/javascript">
			CKEDITOR.replace('edVenueDesc');
		</script>
	</div>
	<?php
	include "../include/LocationSelection.php";
	LocationSelectionDraw(true, array($thisLocationAreaId));
	?>
</form>






<?php
include "../include/footer.php";
?>
