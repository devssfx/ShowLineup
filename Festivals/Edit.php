<?php
$pageTitle = "Festivals";
include "../include/db.php";
include "../include/dl_festival.php";
include "../include/header.php";
?>


<?php

$success = 0;

$path = "../";


$today = getdate();
$dateOpenYear = $today["year"];
$dateOpenMonth = $today["mon"];
$dateOpenDay = $today["mday"];

$dateCloseYear = $dateOpenYear;
$dateCloseMonth = $dateOpenMonth;
$dateCloseDay = $dateOpenDay;


//check for save
if(isset($_POST['FestivalName'])){

	$thisId = $_POST['FestivalId'];
	$thisName = $_POST['FestivalName'];
	$thisFriendly = UrlFriendlyName($thisName);
	$thisStatus = $_POST['StatusValueString'];
	$thisDesc = $_POST['FestivalDesc'];

	$thisDateOpen = (intval($_POST['DateOpenYear']) * 10000) + (intval($_POST['DateOpenMonth'])*100) + intval($_POST['DateOpenDay']);
	$thisDateClose = (intval($_POST['DateCloseYear']) * 10000) + (intval($_POST['DateCloseMonth'])*100) + intval($_POST['DateCloseDay']);
	
	//in case there is an error and need to repopulate the form
	$dateOpenYear = intval(substr($thisDateOpen,0,4));
	$dateOpenMonth = intval(substr($thisDateOpen,4,2));
	$dateOpenDay = intval(substr($thisDateOpen,6));
	
	$dateCloseYear = intval(substr($thisDateClose,0,4));
	$dateCloseMonth = intval(substr($thisDateClose,4,2));
	$dateCloseDay = intval(substr($thisDateClose,6));
	
	$success = DL_FestivalSave($thisId, $thisName, $thisFriendly, $thisStatus, $thisDateOpen, $thisDateClose, $thisDesc);

	if(is_string($success)){
		if(strlen($thisId) == 0)
			header('Location:' . $success);
		else
			header('Location:' . "../" . $success);
	}

}else{
	//check for edit
	if(isset($_GET["n"])){
		DL_FestivalLoad($_GET["n"]);
		$path = $path . "../";
		
		$dateOpenYear = intval(substr($thisDateOpen,0,4));
		$dateOpenMonth = intval(substr($thisDateOpen,4,2));
		$dateOpenDay = intval(substr($thisDateOpen,6));
		
		$dateCloseYear = intval(substr($thisDateClose,0,4));
		$dateCloseMonth = intval(substr($thisDateClose,4,2));
		$dateCloseDay = intval(substr($thisDateClose,6));
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
	<input id="FestivalId" name="FestivalId" type="hidden" value="<?php echo $thisId; ?>" />
	<div class="editBar">
		<input type="submit" value="Save" onclick="$('#divFestivalDesc').find('input')[0].value = CKEDITOR.instances['edFestivalDesc'].getData();" />
		<a href="<?php echo $path . "Festivals/" . $thisFriendly; ?>">Cancel</a>
	</div>
	<h2>Add Festival</h2>
	<table>
		<tr>
			<td><label for="FestivalName">Name</label></td>
			<td><input data-val="true" data-val-required="The Name field is required." id="FestivalName" name="FestivalName" type="text" value="<?php echo $thisName; ?>" />
			<span class="field-validation-valid" data-valmsg-for="FestivalName" data-valmsg-replace="true"></span></td>
		</tr>
		<tr>
			<td>
				<label for="DateOpenDay">Date Open</label>
			</td>
			<td>
				<input class="input-width-day" id="DateOpenDay" name="DateOpenDay" type="text" value="<?php echo $dateOpenDay; ?>" />
				<select id="DateOpenMonth" name="DateOpenMonth">
					<option <?php if($dateOpenMonth == 1) echo 'selected="selected" '; ?>value="1">Jan</option>
					<option <?php if($dateOpenMonth == 2) echo 'selected="selected" '; ?>value="2">Feb</option>
					<option <?php if($dateOpenMonth == 3) echo 'selected="selected" '; ?>value="3">Mar</option>
					<option <?php if($dateOpenMonth == 4) echo 'selected="selected" '; ?>value="4">Apr</option>
					<option <?php if($dateOpenMonth == 5) echo 'selected="selected" '; ?>value="5">May</option>
					<option <?php if($dateOpenMonth == 6) echo 'selected="selected" '; ?>value="6">Jun</option>
					<option <?php if($dateOpenMonth == 7) echo 'selected="selected" '; ?>value="7">Jul</option>
					<option <?php if($dateOpenMonth == 8) echo 'selected="selected" '; ?>value="8">Aug</option>
					<option <?php if($dateOpenMonth == 9) echo 'selected="selected" '; ?>value="9">Sep</option>
					<option <?php if($dateOpenMonth == 10) echo 'selected="selected" '; ?>value="10">Oct</option>
					<option <?php if($dateOpenMonth == 11) echo 'selected="selected" '; ?>value="11">Nov</option>
					<option <?php if($dateOpenMonth == 12) echo 'selected="selected" '; ?>value="12">Dec</option>
				</select>
				<input class="input-width-year" id="DateOpenYear" name="DateOpenYear" type="text" value="<?php echo $dateOpenYear; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="DateCloseDay">Date Close</label>
			</td>
			<td>
				<input class="input-width-day" id="DateCloseDay" name="DateCloseDay" type="text" value="<?php echo $dateCloseDay; ?>" />
				<select id="DateCloseMonth" name="DateCloseMonth">
					<option <?php if($dateCloseMonth == 1) echo 'selected="selected" '; ?>value="1">Jan</option>
					<option <?php if($dateCloseMonth == 2) echo 'selected="selected" '; ?>value="2">Feb</option>
					<option <?php if($dateCloseMonth == 3) echo 'selected="selected" '; ?>value="3">Mar</option>
					<option <?php if($dateCloseMonth == 4) echo 'selected="selected" '; ?>value="4">Apr</option>
					<option <?php if($dateCloseMonth == 5) echo 'selected="selected" '; ?>value="5">May</option>
					<option <?php if($dateCloseMonth == 6) echo 'selected="selected" '; ?>value="6">Jun</option>
					<option <?php if($dateCloseMonth == 7) echo 'selected="selected" '; ?>value="7">Jul</option>
					<option <?php if($dateCloseMonth == 8) echo 'selected="selected" '; ?>value="8">Aug</option>
					<option <?php if($dateCloseMonth == 9) echo 'selected="selected" '; ?>value="9">Sep</option>
					<option <?php if($dateCloseMonth == 10) echo 'selected="selected" '; ?>value="10">Oct</option>
					<option <?php if($dateCloseMonth == 11) echo 'selected="selected" '; ?>value="11">Nov</option>
					<option <?php if($dateCloseMonth == 12) echo 'selected="selected" '; ?>value="12">Dec</option>
				</select>
				<input class="input-width-year" id="DateCloseYear" name="DateCloseYear" type="text" value="<?php echo $dateCloseYear; ?>" />
			</td>
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
	<div id="divFestivalDesc">
		<label for="FestivalDesc">Description</label>
		<input id="FestivalDesc" name="FestivalDesc" type="hidden" value="" />
		<textarea name="edFestivalDesc" id="edFestivalDesc" rows="10" cols="10"><?php echo $thisDesc; ?></textarea>
		<script type="text/javascript">
			CKEDITOR.replace('edFestivalDesc');
		</script>
	</div>
	
</form>






<?php
include "../include/footer.php";
?>
