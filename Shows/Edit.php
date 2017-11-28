<?php
$pageTitle = "Shows";
include "../include/db.php";
include "../include/dl_show.php";
include "../include/header.php";

$success = 0;
$loadShow = true;

$venueDefault = array();

if(isset($_POST["ShowName"])){

	if(isset($_POST["btnOpenAll"])){
		$thisId = $_POST["ShowId"];
		
		$success = DL_ShowOpenAll($thisId);
		
	}else{
		
		$thisId = $_POST["ShowId"];
		$thisName = $_POST["ShowName"];
		$thisStatus = $_POST["StatusValueString"];
		$thisFestivalId = $_POST["FestivalId"];
		$thisPriceRange = $_POST["PriceRange"];
		$thisDesc = $_POST["ShowDesc"];
		$thisFriendly = UrlFriendlyName($thisName);

		//if show id is filled, these are ignored as they are hidden
		if(isset($_POST['ShowDateYear'])){
			$showDate = (intval($_POST['ShowDateYear']) * 10000) + (intval($_POST['ShowDateMonth'])*100) + intval($_POST['ShowDateDay']);
			$showTime = (intval($_POST['ShowTimeHour']) * 100) + intval($_POST['ShowTimeMinute']);
			$timeAmPm = $_POST["ShowTimeAmPm"];
			if($timeAmPm == "1"){
				$showTime = $showTime + 1200;
				if($showTime > 2400){
					$showTime = $showTime - 2400;
				}
			}
			$showLength = $_POST["ShowLength"];
			$dateStatus = $_POST["DateStatus"];
			$venueId = $_POST["VenueId"];
		}else{
			$showDate = 0;
			$showTime = 0;
			$showLength = 0;
			$dateStatus = 0;
			$venueId = '';
		}

		//look for hosts
		$hostPost = array();
		foreach($_POST as $key => $value){
			if(strlen($key) > 7){
				if(substr($key,0,7) == "chkHost"){
					$hostPost[] = substr($key, 7);
				}
			}
		}
		
		$success = DL_ShowSave($thisId, $thisName, $thisFriendly, $thisFestivalId, $thisPriceRange, $thisStatus, $thisDesc
			,$showDate,$showTime,$showLength,$dateStatus,$venueId
			,$hostPost
		);

		if(is_string($success)){
			if(strlen($thisId) != 0)
				header('Location:' . "../" . $success);	
			else
				header('Location:Edit/' . $success); //reload page to enter dates

		}else{
			$loadShow = false;
		}
	}	
}

if($loadShow){
	
	if(isset($_GET["n"])){
		DL_ShowLoad($_GET["n"], true);
		
		if(strlen($thisId) == 0)
			header('Location:' . "../" . $thisFriendly);

	}else{
		DL_VenueDefault(false); //this gets loaded in DL_ShowLoad also
	}
	
}


HeaderDraw();

?>


		
<script src="/Scripts/jquery.validate.min.js" type="text/javascript"></script>
<script src="/Scripts/jquery.validate.unobtrusive.min.js" type="text/javascript"></script>
<script src="/ckeditor/ckeditor.js" type="text/javascript"></script>

<style type="text/css">

	#divShowEditBox table td
	{
		padding:3px;
	}
	
	#divShowDateList{
		border-left:solid 1px black;
		border-top:solid 1px black;
		margin-top:10px;
	}
	
	#divShowDateList th
	{
		background-color:#FFCAA8; /*#c0c0c0;*/
		padding:2px;
		border-right:solid 1px black;
		border-bottom:solid 1px black;
	}
	#divShowDateList td, #divShowDateList th
	{
		padding:5px;
	}
	#divShowDateList .show-edit-date-list-time
	{
		background-color:#FFE3D1;
	}
	#divShowDateList .show-edit-date-list-time td, #divShowDateList .show-date-edit-performers-list, #divShowDateList .show-date-edit-performers-add-button td{
		border-right:solid 1px black;
		border-bottom:solid 1px black;
	}
	
	#divShowDateList .tblShowDateEditPerformers{
		border-left:solid 1px #cecece;
		border-top:solid 1px #cecece;
	}
	
	#divShowDateList .tblShowDateEditPerformers th, .tblShowDateEditPerformers td
	{
		border-right:solid 1px #cecece;
		border-bottom:solid 1px #cecece;
	}
	#divShowDateList .tblShowDateEditPerformers th
	{
		background-color:#FFEB93; /*#565656;*/
		color:Black;
	}
	#divShowDateList .tblShowDateEditPerformers td
	{
		background-color:#FFF8D8; /*#c0c0c0;*/
	}
	
	.tblShowDateEditForm
	{
		/*display:none;*/
		
		background-color:#FFE3D1;
		border-right:solid 1px black;
		border-top:solid 1px black;
	}
	
	.tblShowDateEditForm tr th
	{
		background-color:#FFCAA8;
		color:Black;
	}
	.tblShowDateEditForm tr td, .tblShowDateEditForm tr th
	{
		padding:5px;
		border-left:solid 1px black;
		border-bottom:solid 1px black;
	}

	.edit-tab{
		background-color:grey;color:White;padding:5px;float:left;cursor:pointer;
		border-left:solid 1px black;
		border-right:solid 1px black;
		border-top:solid 1px black;
		display:inline;
	}
	.edit-tab-selected{
		background-color:black;
	}


	#divStatus{
		/*display:none;*/
		padding:5px;
	}
	#divStatus.general{
		background-color:white;
		border:solid 1px grey;
		display:inline-block;
	}
	#divStatus.good{
		background-color:#d2ffbf;
		border:solid 1px green;
		display:inline-block;
	}
	#divStatus.bad{
		background-color:#FF7070;
		border:solid 1px red;
		color:#ffffff;
		display:inline-block;
	}
	
</style>


<?php
	if($success < 0){
		echo '<div class="input-validation-error">There was a problem saving. Please try again. (' . $success . ')</div>';
	}
?>


<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" method="post">
	<div class="editBar">
		<div id="divSaveShowButton" style="margin-right:10px;float:left;">
			<input type="submit" value="Save" name="btnSave" id="btnSave" onclick="return autoCompleteCheck();" /><!--  onclick="return savePage();" -->
			<input type="submit" value="Change all Draft to Open" name="btnOpenAll" id="btnOpenAll" />
		</div>

		<a href="<?php echo "../../Shows/" . $thisFriendly; ?>">Return</a>
		<div style="clear:left;"></div>
	</div>
	<h2><?php
		if(strlen($thisId) == 0)
			echo 'Create Show';
		else
			echo 'Edit ' . $thisName;
		?></h2>
	<div>
			
	</div>
	<div class="edit-tab" onclick="showEditBoxShowHide(1);" id="divTabShow">Show Details</div>
	<div class="edit-tab edit-tab-selected" onclick="showEditBoxShowHide(2);" id="divTabDate">Show Dates and Performers</div>
	</br>
	
	
	
	<div style="clear:left;border:solid 1px black;padding-top:5px;display:none;" id="divShowEditContainer">
		<div id="divShowEditBox" style="padding-left:3px;">

			<div style="float:left;">
	
			<input id="ShowId" name="ShowId" type="text" value="<?php echo $thisId; ?>" style="display:none;" />
			<input id="ShowUrlFriendlyName" name="ShowUrlFriendlyName" type="hidden" value="<?php echo $thisFriendly; ?>" />
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><label for="ShowName">Show Name</label></td>
					<td><input data-val="true" data-val-required="The Show Name field is required." id="ShowName" name="ShowName" type="text" value="<?php echo $thisName; ?>" />
					<br /><span class="field-validation-valid" data-valmsg-for="ShowName" data-valmsg-replace="true"></span></td>
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
					<td><label for="FestivalId">Festival</label></td>
					<td><select id="FestivalId" name="FestivalId"><option selected="selected" value="">-Select-</option><?php
						$conn = DBConnect();
						if($conn){
							$dateOpen = new DateTime();
							$sql = "select distinct f.* from evpfestival f inner join evpfestivaladmin fa on f.FestivalId = fa.AdminForId"
							. " where (f.LocationCountryId = " . ParseS($_SESSION['loccountry']) . " and f.FestivalStatus = 1)"
							. " or ( fa.MemberId = ". ParseS($_SESSION["mi"]) . ")"
							. " and f.FestivalDateClose >= " . date_format($dateOpen, 'Ymd') . " order by FestivalDateOpen, FestivalDateClose";
						
							if($result = mysqli_query($conn, $sql)){
								if (mysqli_num_rows($result) > 0) {
									while($row = mysqli_fetch_assoc($result)) {
										echo '<option value="' . $row['FestivalId'] . '"';
										if($thisFestivalId == $row["FestivalId"])
											echo ' selected="selected" ';
										echo ">" . $row['FestivalName'] . '</option>';
									}
								}
								mysqli_free_result($result);
							}
							mysqli_close($conn);							
							
							?>
						
						<?php } ?>
					</select></td>
				</tr>
				<tr>
					<td><label for="PriceRange">Price Range</label></td>
					<td><input id="PriceRange" name="PriceRange" type="text" value="<?php echo $thisPriceRange; ?>" /></td>
				</tr>
		
				<tr>
					<td>Hosts</td>
					<td id="tdHostList">
						<div>
							<?php
							if(isset($hostList)){
								foreach($hostList as $hostItem){
									echo '<div><input id="chkHost'. $hostItem->HostId .'" name="chkHost'. $hostItem->HostId .'" type="checkbox" value="'. $hostItem->HostId .'" checked="checked">';
									echo '<label for="chkHost'. $hostItem->HostId .'">'. $hostItem->HostName .'</label></div>';
								}
							}
							?>
							<input type="text" id="txtSearchHost" onkeyup="searchHost_keyup(event,this);" onkeydown="searchHost_keydown(event);" onfocus="if(this.value=='Search') this.value = '';$(this).css('font-style','');" onblur="autoCompleteHide();" autocomplete="false" value="Search" style="font-style:italic;" />
						</div>
					</td>
				</tr>

			</table>
	
			</div>
	
			<div style="float:left;">
				<div>
					<span>Picture (480 x 240)</span>
					<input id="PicSmall" name="PicSmall" type="hidden" value="" />
					<input id="PicLarge" name="PicLarge" type="hidden" value="" />
	    
					<div>
						<input type="file" name="filePic" />
						<br />
						<input type="checkbox" id="chkPicRemove" name="chkPicRemove" />
						<label for="chkPicRemove">Remove Picture</label>
				
					</div>

				</div>
			</div>
			<div style="clear:left;"></div>
	
<?php
	if(strlen($thisId)==0){
	$today = getdate();
	$dateYear = $today["year"];
	$dateMonth = $today["mon"];
	$dateDay = $today["mday"];

?>
			<table class="tblShowDateEditForm" cellspacing="0" cellpadding="0" border="0">
				<tbody><tr>
					<th>Date</th>
					<th>Time</th>
					<th>Length (min)</th>
					<th>Status</th>
					<th>Venue</th>
				</tr>
				<tr>
					<td>
						<input class="input-width-day" data-val="true" data-val-number="The field Day must be a number." data-val-required="The Day field is required." id="ShowDateDay" name="ShowDateDay" type="text" 
						value="<?php echo $dateDay; ?>">
						<select data-val="true" data-val-number="The field ShowDateMonth must be a number." data-val-required="The ShowDateMonth field is required." id="ShowDateMonth" name="ShowDateMonth">
								<option <?php if($dateMonth == 1) echo 'selected="selected" '; ?>value="1">Jan</option>
								<option <?php if($dateMonth == 2) echo 'selected="selected" '; ?>value="2">Feb</option>
								<option <?php if($dateMonth == 3) echo 'selected="selected" '; ?>value="3">Mar</option>
								<option <?php if($dateMonth == 4) echo 'selected="selected" '; ?>value="4">Apr</option>
								<option <?php if($dateMonth == 5) echo 'selected="selected" '; ?>value="5">May</option>
								<option <?php if($dateMonth == 6) echo 'selected="selected" '; ?>value="6">Jun</option>
								<option <?php if($dateMonth == 7) echo 'selected="selected" '; ?>value="7">Jul</option>
								<option <?php if($dateMonth == 8) echo 'selected="selected" '; ?>value="8">Aug</option>
								<option <?php if($dateMonth == 9) echo 'selected="selected" '; ?>value="9">Sep</option>
								<option <?php if($dateMonth == 10) echo 'selected="selected" '; ?>value="10">Oct</option>
								<option <?php if($dateMonth == 11) echo 'selected="selected" '; ?>value="11">Nov</option>
								<option <?php if($dateMonth == 12) echo 'selected="selected" '; ?>value="12">Dec</option>
						</select>
						<input class="input-width-year" data-val="true" data-val-number="The field Year must be a number." data-val-required="The Year field is required." id="ShowDateYear" name="ShowDateYear" type="text" 
						value="<?php echo $dateYear; ?>">
						<div>
							<div><span class="field-validation-valid" data-valmsg-for="ShowDateDay" data-valmsg-replace="true"></span></div>
							<div><span class="field-validation-valid" data-valmsg-for="ShowDateYear" data-valmsg-replace="true"></span></div>
						</div>
					</td>
					<td>
						<input class="input-width-time" data-val="true" data-val-number="The field Hour must be a number." data-val-required="The Hour field is required." id="ShowTimeHour" name="ShowTimeHour" type="text" value="8">
						<input class="input-width-time" data-val="true" data-val-number="The field Minute must be a number." data-val-required="The Minute field is required." id="ShowTimeMinute" name="ShowTimeMinute" type="text" value="00">
					
						<input id="ShowTimeAmPm" name="ShowTimeAmPm" type="radio" value="0"> 
						<label for="ShowTimeAmPm">am</label>
						<input checked="checked" id="ShowTimeAmPm" name="ShowTimeAmPm" type="radio" value="1">
						<label for="ShowTimeAmPm">pm</label>
						<div>
							<div><span class="field-validation-valid" data-valmsg-for="ShowTimeHour" data-valmsg-replace="true"></span></div>
							<div><span class="field-validation-valid" data-valmsg-for="ShowTimeMinute" data-valmsg-replace="true"></span></div>
						</div>
					</td>
					<td>
						<input id="ShowLength" name="ShowLength" style="width:30px;" type="text" value="">
					</td>
					<td>
						<select id="DateStatus" name="DateStatus">
							<option selected="selected" value="0">Draft</option>
							<option value="1">Open</option>
							<option value="2">Sold Out</option>
							<option value="3">Closed</option>
						</select>
					</td>
					<td>
						<div>
							<input type="text" onkeydown="searchVenue_keydown(event);" onkeyup="searchVenue_keyup(event,this)" onfocus="if(this.value=='Search') this.value = '';$(this).css('font-style','');" onblur="autoCompleteHide();" autocomplete="false" value="Search" style="font-style:italic;">
						</div>
						<label><?php if(count($venueDefault) == 2)
								echo $venueDefault[1];
							else
								echo "No Venue";
							?></label>
						<input type="button" value="X"  onclick="showEditVenueClear(this);" />
						<input type="text" id="VenueId" name="VenueId" value="<?php if(count($venueDefault) == 2) echo $venueDefault[0]; ?>" style="display:none;" />
						
						
						
						
					</td>
				</tr>
			</tbody></table>	
	<?php
	} //thisId is blank
	?>
	
	
	
			<div id="divShowDesc">
				<div style="margin-bottom:10px;"><label for="ShowDesc">Description</label></div>
				<textarea name="ShowDesc" id="ShowDesc" rows="10" cols="50"><?php echo $thisDesc; ?></textarea>
				<script type="text/javascript">
        			CKEDITOR.replace('ShowDesc');
				</script>
			</div>
			
			
			
		</div>
	</div>
</form>	
	
	
<!-- ------------------------------------- -->	
	
	
	
	<div style="clear:left;border:solid 1px black;padding-left:4px;padding-bottom:5px;padding-top:5px;display:none;" id="divShowDateContainer">
	
		
	<div id="divShowDateEdit">


	<div id="divStatus" class="general">Dates Listed</div>

	<table id="divShowDateList" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th><label for="ShowDateSingle_ShowDate">Date</label></th>
			<th><label for="ShowDateSingle_ShowTime">Time</label></th>
			<th><label for="ShowDateSingle_ShowLength">Length</label> (min)</th>
			<th><label for="ShowDateSingle_ShowDateStatus">Status</label></th>
			<th><label for="ShowDateSingle_VenueId">Venue</label></th>
			<th>&nbsp;</th>
		</tr>
		

<?php

	$dateCount = count($dateList);
	for($dateIdx = 0; $dateIdx <= $dateCount; $dateIdx++){
		if($dateIdx < $dateCount){
			$dateData = $dateList[$dateIdx];
		}else{
			$dateData = new DateData();
			if($dateCount != 0){
				$dateData->ShowDate = $dateList[$dateIdx-1]->ShowDate;
				$dateData->ShowTime = $dateList[$dateIdx-1]->ShowTime;
			}else{
				date_default_timezone_set("Australia/Sydney");
				$dateData->ShowDate = date("Ymd");
				$dateData->ShowTime = 2000; //date("Gi"),'','','','');
			}
		
			if(count($venueDefault) == 2){
				$dateData->VenueId = $venueDefault[0];
				$dateData->VenueName = $venueDefault[1];
			}

		}

		
?>		
	  <tr class="show-edit-date-list-time"<?php if($dateIdx == $dateCount) echo ' style="display:none;"'; ?>>
		<td>
			<span><?php echo DateToString($dateData->ShowDate); ?></span>
			<span style="display:none;">
				<input class="input-width-day" type="text" value="" />
				<select>
					<option value="1">Jan</option>
					<option value="2">Feb</option>
					<option value="3">Mar</option>
					<option value="4">Apr</option>
					<option value="5">May</option>
					<option value="6">Jun</option>
					<option value="7">Jul</option>
					<option value="8">Aug</option>
					<option value="9">Sep</option>
					<option value="10">Oct</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select>
				<input class="input-width-year" type="text" value="" />
			</span>
		</td>
		<td>
			<span><?php echo TimeToString($dateData->ShowTime); ?></span>
			<span style="display:none;">
				<input class="input-width-time" type="text" value="" />
				<input class="input-width-time" type="text" value="" />

				<input id="showTimeAm" name="ShowTimeAmPm" type="radio" value="am" /> 
				<label for="showTimeAm">am</label>
				<input id="showTimePm" name="ShowTimeAmPm" type="radio" value="pm" />
				<label for="showTimePm">pm</label>
			</span>
		</td>
		<td>
			<span><?php echo $dateData->ShowLength; ?></span>
			<span style="display:none;"><input type="text" /></span>
		</td>
		<td>
			<span><?php echo StatusToString($dateData->ShowDateStatus); ?></span>
			<span style="display:none;">
				<select>
					<option value="0">Draft</option>
					<option value="1">Open</option>
					<option value="2">Closed</option>
					<option value="3">Sold Out</option>
				</select>			
			</span>
		</td>
		<td>
			<span>
				<label><?php
					if(strlen($dateData->VenueName) == 0){
						echo "No Venue";
					}else{
						echo $dateData->VenueName;
					}
				?></label>
				<label style="display:none;"><?php echo $dateData->VenueId; ?></label>
			</span>
			<span style="display:none;">
				<div>
					<input type="text" 
						onkeydown="searchVenue_keydown(event);" 
						onkeyup="searchVenue_keyup(event,this)" 
						onfocus="if(this.value=='Search') this.value = '';$(this).css('font-style','');" 
						onblur="autoCompleteHide();" autocomplete="false" 
						value="Search" style="font-style:italic;" />
				</div>
				<label>No Venue</label>
				<input type="button" value="X"  onclick="showEditVenueClear(this);" />
				<input type="text" value="" style="display:none;" />
			</span>
		</td>
		<td>
			<input type="text" value="<?php echo $dateData->ShowDateId; ?>" style="display:none;" />
			<span>
				<input type="button" value="Edit" onclick="dateEditSaveCancel(this,1,0);" />
				<input type="button" value="Delete" onclick="dateDelete(this, '<?php echo $thisId; ?>');" />
				<input type="button" value="Copy" onclick="dateCopy(this, '<?php echo $thisId; ?>');" />
			</span>
			<span style="display:none;">
				<input type="button" value="Save" onclick="dateEditSaveCancel(this,3,0);" />
				<input type="button" value="Cancel" onclick="dateEditSaveCancel(this,2,0);" />
			</span>
			
		</td>
	</tr>


	  
<!-- performer list -->
	  <tr<?php if($dateIdx == $dateCount) echo ' style="display:none;"'; ?>>
		<td colspan="6" class="show-date-edit-performers-list">
			
			<div style="border:solid 1px black;float:left;padding:3px;cursor:pointer;" onclick="showHidePerformerInfo(this);">Performer List</div>
			<div style="clear:left;" />
			
			<table class="tblShowDateEditPerformers" border="0" cellspacing="0" cellpadding="0"<?php
			if($dateCount != 1){
				echo ' style="display:none;"';
			}
			?>>
				<tr>
					<th colspan="2">Name</th>
					<th>Title</th>
					<th>Length (min)</th>
					<th>Status</th>
					
					<th>&nbsp;</th>
				</tr>
<?php
	$perfCount = count($performerList);

	for($perfIdx = 0; $perfIdx <= $perfCount; $perfIdx++){
		if($perfIdx == $perfCount){
			$perfItem = new PerformerData();
		}else{
			$perfItem = $performerList[$perfIdx];
		}
		
		if($perfItem->ShowDateId == $dateData->ShowDateId || $perfIdx == $perfCount){

?>
				<tr<?php if($perfIdx == $perfCount) echo ' style="display:none;"' ?>>
					<td>
						<a href="." onclick="showDatePerformerReorder(this, '<?php echo $thisId; ?>', -1);return false;">/\</a>
						<a href="." onclick="showDatePerformerReorder(this, '<?php echo $thisId; ?>', 1);return false;">\/</a>
					</td>
					<td>
						<?php echo $perfItem->PerformerName; ?>
					</td>
					<td>
						<span><?php echo $perfItem->PerformerTitle; ?></span>
						<span style="display:none;"><input type="text" /></span>
					</td>
					<td>
						<span><?php echo $perfItem->PerformanceLength; ?></span>
						<span style="display:none;"><input type="text" style="width:30px;" /></span>
					</td>
					<td>
						<span><?php echo ConfirmedToString($perfItem->PerformerConfirmed); ?></span>
						<span style="display:none;"><select id="ddlPerformerConfirmed" name="ddlPerformerConfirmed">
							<option value="0">Requested</option>
							<option value="1">Accepted</option>
							<option value="2">Cancelled</option>
							</select>
						</span>
					</td>
					<td>
						<span>
							<input type="button" value="Edit" onclick="perfEdit(this,1);" />
							<input type="button" value="Remove" onclick="perfRemove(this,'<?php echo $thisId; ?>');" />
						</span>
						<span style="display:none;">
							<input type="button" value="Save" onclick="showDatePerformerEditSave(this, '<?php echo $thisId; ?>', '<?php echo $perfItem->ShowDateId; ?>');" />
							<input type="button" value="Cancel" onclick="perfEdit(this,2);" />
						</span>
						<input type="text" value="<?php echo $perfItem->ShowDatePerformerId; ?>" style="display:none;" />
					
<!-- was here -->						
					</td>
				</tr>
<?php
		} //ShowDateId ==
	} //for perfIdx
?>	
				<tr>
					<td colspan="2">
						<input type="text" onkeydown="performerAdd_keydown(event);" onkeyup="performerAdd_keyup(event,this);" onfocus="if(this.value=='Search') this.value = '';$(this).css('font-style','');" 
							onblur="autoCompleteHide();" autocomplete="false" value="Search" style="font-style:italic;" />
						<label style="display:none;"></label>
					</td>
					<td>
						<input type="text" />
					</td>
					<td>
						<input type="text" style="width:30px" />
					</td>
					<td>
						<select id="ddlPerformerConfirmed" name="ddlPerformerConfirmed">
							<option value="0">Requested</option>
							<option value="1">Accepted</option>
							<option value="2">Cancelled</option>
						</select>
					</td>
					
					<td>
						<span><!-- needed to match edit -->
							<input type="button" value="Add" onclick="return showDatePerformerEditSave(this, '<?php echo $thisId; ?>', '<?php echo $dateData->ShowDateId; ?>');" />
						</span>
					</td>
				</tr>
			</table>
		</td>
	  </tr>

<?php



	} //foreach dateItem
?>

	  
  <tr class="show-edit-date-list-time">
				<td colspan="6">
					<input type="button" value="Add Date" onclick="btnAddDate_Click(this);" />
				</td>
			</tr>
	
  </tr>
  </table>
		
	
	</div>

	</div>


<script type="text/javascript">

function dateCopy(btn, showId){
	var showDateId = $(btn).parent().parent().find('input')[0].value;

	StatusUpdate('Copying date...','');

	var actionUrl = '/ajax/ShowDateCopy.php';
	$.post( actionUrl, {
		ShowId : showId,
		ShowDateId : showDateId,
		})
		.done(function( data ) {

			if(data.length < 5){
				StatusUpdate('There was a problem. Reload the page and try again. (' + data + ')','bad');
			}else{ //success
				//what is returned is the new ShowDateId, then old,new showdateperformer ids
				//alert(data);
				var idList = data.split(',');

				var tr = $(btn).parent().parent().parent();
				var trPerf = tr.next();
				
				var trNew = tr.clone();
				var trPerfNew = trPerf.clone();
				
				trNew.insertAfter(trPerf);
				trPerfNew.insertAfter(trNew);
				
				$(btn).parent().parent().find('input')[0].value = idList[0];
				trPerfNew = trPerfNew.find('table').find('tr');
				//first tr is title row
				for(var i = 1; i < trPerfNew.length-2; i++){
					
					var obj = $(trPerfNew[i]).find('input')[6];
					for(var j = 1; j < idList.length; j++){
						if(obj.value == idList[j]){
							obj.value = idList[j+1];
						}
					}
				}
				
				StatusUpdate('Copy successful.','good');
			}
			
			
		})
		.fail(function(){
			StatusUpdate('There was a problem. Reload the page and try again.','bad');
		});

}


function dateDelete(btn, showId){
	
	var showDateId = $(btn).parent().parent().find('input')[0].value;
	
	StatusUpdate('Deleteing date...','');
	
	var actionUrl = '/ajax/ShowDateDelete.php';
	$.post( actionUrl, {
		ShowId : showId,
		ShowDateId : showDateId,
		})
		.done(function( data ) {
			if(data != '0'){
				StatusUpdate('There was a problem. Reload the page and try again. (' + data + ')','bad');
			}else{ //success
				//remove my row and performer list row
				var tr = $(btn).parent().parent().parent();
				var trPerf = tr.next();
				
				trPerf.remove();
				tr.remove();
				
				StatusUpdate('Date deleted.','good');
			}
			
			
		})
		.fail(function(){
			alert('There was a problem. Reload the page and try again.');
		});
	
	
	
}


function showHidePerformerInfo(div){
	var tbl = $(div).parent().find('table');
	
	if(tbl.is(":visible"))
		tbl.hide();
	else
		tbl.show();
}


function btnAddDate_Click(btn){
	
	var tr = $(btn).parent().parent().prev();
	while(tr.length != 0 && tr.hasClass('show-edit-date-list-time') == false){
		tr = tr.prev();
	}
	
	$(tr.find('input')[11]).click();
	tr.show();
	
}

function StatusUpdate(info, status){
	if(status == '') 
		status = 'general';

	var divStatus = $('#divStatus');
	divStatus.removeClass();
	divStatus.addClass(status);
	divStatus.text(info);

}

var _btn;
function dateEditSaveCancel(btn,doing,loopIdx){ //1=start, 2=cancel, 3=save

	var showId = '<?php echo $thisId; ?>';
	var showDateId, dateDay, dateMonth, dateYear, timeHour, timeMinute, timeIsAm, dateLength, dateStatus, dateVenue;

	var tdList = $(btn).parent().parent().parent().find('td');
	
	for(var i = 0; i < tdList.length; i++){
		var spnList = $(tdList[i]).find('span');
		if(spnList.length == 2){
			
			if(doing == 1){ //get values from span
				var txt;
				if(i == 0){ //date
					var info = $(spnList[0]).text().split(' ');
					txt = $(spnList[1]).find('input');
					txt[0].value = info[0];
					txt[1].value = info[2];
					$(spnList[1]).find('select option')
						.each(function() { this.selected = (this.text == info[1]); });
				}else if(i == 1){ //time
					var info = $(spnList[0]).text().split(' ');
					var timeInfo = info[0].split(':');
					txt = $(spnList[1]).find('input'); //4
					txt[0].value = timeInfo[0];
					txt[1].value = timeInfo[1];
					if(info[1] == 'am')
						txt[2].checked = true;
					else
						txt[3].checked = true;
				}else if(i == 2){ //length
					txt = $(spnList[1]).find('input');
					txt.val($(spnList[0]).text());
				}else if(i == 3){ //status
					$(spnList[1]).find('select option')
						.each(function() { this.selected = (this.text == $(spnList[0]).text()); });
				}else if(i == 4){ //venue
					$(spnList[1]).find('input')[0].value = ''; //clear search
					
					var lblList = $(spnList[0]).find('label');
					var lbl = $(spnList[1]).find('label');
					txt = $(spnList[1]).find('input')[2];
					lbl.text(lblList[0].innerText);
					txt.value = lblList[1].innerText;
					
				}
			}else if(doing == 3){ //move values to span
				var txt;
				if(i == 0){ //date
					txt = $(spnList[1]).find('input');
					sel = $(spnList[1]).find('select option:selected');
					
					if(loopIdx == 0){
						dateDay = txt[0].value;
						dateMonth = sel.val();
						dateYear = txt[1].value;
					}else{
						$(spnList[0]).text(  txt[0].value + ' ' + sel.text() + ' ' + txt[1].value  );
					}
				}else if(i == 1){ //time
					txt = $(spnList[1]).find('input');
					
					if(loopIdx == 0){
						timeHour = txt[0].value;
						timeMinute = txt[1].value;
						timeIsAm = txt[2].checked;
					}else{
						var info = txt[0].value + ':' + txt[1].value;
						if(txt[2].checked)
							info += ' ' + txt[2].value;
						else
							info += ' ' + txt[3].value;
						$(spnList[0]).text(info);
					}
				}else if(i == 2){ //length
					txt = $(spnList[1]).find('input');
					
					if(loopIdx == 0){
						dateLength = txt.val();
					}else{
						$(spnList[0]).text(txt.val());
					}
				}else if(i == 3){ //status
					txt = $(spnList[1]).find('select option:selected');
					
					if(loopIdx == 0){
						dateStatus = txt.val();
					}else{
						$(spnList[0]).text(txt.text());
					}
				}else if(i == 4){ //venue
					txt = $(spnList[1]).find('input')[2];
					
					if(loopIdx == 0){
						dateVenue = txt.value;
					}else{
						var lbl = $(spnList[1]).find('label');
						var lblList = $(spnList[0]).find('label');
						$(lblList[0]).text(lbl.text()); //could be No Venue
						$(lblList[1]).text(txt.value); //could be No Venue
					}
				}
			}
			
			if(doing != 3 || loopIdx == 2){
				if(doing == 1){ //edit
					$(spnList[0]).hide();
					$(spnList[1]).show();
				}else if(doing == 2 || doing == 3){ //cancel/save
					$(spnList[0]).show();
					$(spnList[1]).hide();
				}
			}
		}
	}

	
	
	showDateId = $(btn).parent().parent().find('input')[0].value;;
	if(doing == 1){
		if(showDateId.length != 0)
			StatusUpdate('Editing date.','');
		else
			StatusUpdate('Adding date.','');
	}else if(doing == 2){ //cancel
		
		if(showDateId == ''){
			tdList.parent().hide();
		}
		StatusUpdate('Edit cancelled.','');
		
	}else if(doing == 3 && loopIdx == 0){ //try save
		
		
		dateDay = (parseInt(dateYear) * 10000) + (parseInt(dateMonth) * 100) + parseInt(dateDay);
		timeHour = parseInt(timeHour);
		
		if(timeIsAm){
			if(timeHour == 12)
				timeHour = 0;
		}else{
			if(timeHour < 12){
				timeHour += 12;
			}
		}
		timeHour = (parseInt(timeHour) * 100) + parseInt(timeMinute);
		
		_btn = btn;
		dateEditSave(showId, showDateId, dateDay, timeHour, dateLength, dateStatus, dateVenue);
	}

}
function dateEditSave(showId, showDateId, showDate, showTime, dateLength, dateStatus, dateVenue ){
	var actionUrl = '/ajax/ShowDateSave.php';

	StatusUpdate('Saving...','');

	$.post( actionUrl, {
		ShowId : showId,
		ShowDateId : showDateId,
		ShowDate : showDate,
		ShowTime : showTime,
		DateLength : dateLength,
		DateStatus : dateStatus,
		DateVenue : dateVenue,
		})
		.done(function( data ) {
			if(data.length < 5){
				StatusUpdate('There was a problem saving. Reload the page and try again. (' + data + ')','bad');
			}else{
				if(showDateId == ''){ //copy last tr, put showdateid into text field
					var trAdd = $(_btn).parent().parent().parent();
					var trPerf = trAdd.next();
					
					trNew = trAdd.clone();
					trNewPerf = trPerf.clone();
					
					trNew.insertAfter(trPerf); //put new edit after performer list
					trNewPerf.insertAfter(trNew);
					trPerf.show();
					
					trAdd.find('input')[10].value = data; //new show date id
					
					dateEditSaveCancel(trNew.find('input')[13], 2, 1); //cancel
				
				}
				dateEditSaveCancel(_btn, 3, 2); //move values into spans

				StatusUpdate('Save successful.','good');
				
			}
			
			
		})
		.fail(function(){
			alert('There was a problem. Reload the page and try again.');
		}
	);
}

	


function perfEdit(lnk,doing){ //1=start, 2=cancel, 3=save
	
	var sdpId, sdpTitle, sdpLength, sdpConfirmed;
	if(doing == 1){
		StatusUpdate('Edit performer.','');
	}else if(doing == 2){
		StatusUpdate('Edit performer cancelled.','');
	}
	
	var tdList = $(lnk).parent().parent().parent().find('td');
	for(var i = 0; i < tdList.length; i++){
		var spnList = $(tdList[i]).find('span');
		
		if(spnList.length == 2){
			
			if(doing == 1){ //get values from span
				var txt;
				if(i < 4){
					txt = $(spnList[1]).find('input');
					txt.val($(spnList[0]).text());
				}else if(i == 4){
					$(spnList[1]).find('select option')
						.each(function() { this.selected = (this.text == $(spnList[0]).text()); });
					
				}
			}else if(doing == 3){ //move values to span
				var txt;
				if(i < 4){
					txt = $(spnList[1]).find('input');
					$(spnList[0]).text(txt.val());
				}else if(i == 4){
					txt = $(spnList[1]).find('select option:selected');
					$(spnList[0]).text(txt.text());
				}
			}
			
			if(doing == 1){ //edit
				$(spnList[0]).hide();
				$(spnList[1]).show();
			}else if(doing == 2 || doing == 3){ //cancel/save
				$(spnList[0]).show();
				$(spnList[1]).hide();
			}
		}
	}
}


function perfRemove(btn, showId){
	
	StatusUpdate('Removing performer...','');
	
	var showDatePerfId = $(btn).parent().parent().find('input')[4].value;
	
	var actionUrl = '/ajax/ShowDatePerformerRemove.php';
	$.post( actionUrl, {
		ShowId : showId,
		sdpId : showDatePerfId,
		})
		.done(function( data ) {
			if(data != '0'){
				StatusUpdate('There was a problem. Reload the page and try again. (' + data + ')','bad');
				
			}else{ //success
				$(btn).parent().parent().parent().remove();
				StatusUpdate('Performer removed.','good');
			}
			
			
		})
		.fail(function(){
			alert('There was a problem. Reload the page and try again.');
		});
	
}

$(document).ready(function () {

	$(document).click(function () {
		autoCompleteHide();
	});


	//if new show, display date edit table but hide save date button

	var showId = $('#ShowId').val();
	if(showId.length == 0){
		$('#divShowEditContainer').show();
		//$('#divShowDateContainer').hide();
		$('#divTabDate').hide();
		$('#btnOpenAll').hide();
		
		$('#divTabShow').addClass('edit-tab-selected');
	}else{
		//$('#divShowEditContainer').hide();
		$('#divShowDateContainer').show();
		
		$('#btnSave').hide();
	}

/*
	var pTableList = $('.tblShowDateEditPerformers');
	var pTableCount = pTableList.length - 2; //ignore add
	alert(pTableCount);
	pTableList.each(function(idx){
		if(idx != pTableList.length){
			alert(idx);
		}
	});
*/	

	

});

	function autoCompleteHide(){
		setTimeout('autoCompleteHideDo();',200);
	}
	function autoCompleteHideDo(){
		$('#autoComplete').hide();
		_acIdx = -1;
	}
	
	
	function showEditVenueClear(lnk){
		$(lnk).prev().text('No Venue');
		$(lnk).next().val('');
	}
	
	
	
	function searchVenue_keydown(e){
		autoComplete_Tab(e);
	}
	function searchVenue_keyup(e,txt){
		if(txt.value.length == 0) return;
					
		var unicode;

		if(e){
			unicode = e.keyCode ? e.keyCode : e.charCode;
		}else{
			unicode = -1;
		}
		
		//38=up, 40=down
		if(unicode == 38 || unicode == 40){
			var acDivList = $('#autoComplete div');

			if(unicode == 38){ //up
				if(_acIdx > 0)
					_acIdx--;
			}else{ //down
				if(_acIdx < acDivList.length-1){
					_acIdx++;
				}
			}
			
			acDivList.removeClass('selected');
			if(_acIdx <= acDivList.length){
				$(acDivList[_acIdx]).addClass('selected');
			}
			txt.selectionStart = _acPos; txt.selectionEnd = _acPos;
		}else if(unicode == 13){
			venueSelect();
		}else{
			//_acIdx = -1;
			_acPos = txt.selectionStart;
			_txt = txt;

			var actionUrl = "/ajax/venuelist.php?la=A805345F-10A8-4243-BF0E-3596CA9690F6";
			actionUrl = actionUrl + '&s=' + encodeURI(txt.value);
			$.getJSON(actionUrl, searchVenueResult);

		}
	}




	function searchVenueResult(response) {
		var div = $('#autoComplete');
		div.empty();
		
		if(_acIdx >= response.length){
			_acIdx = response.length-1;
		}
		var selClass = '';
		
		if (response != null && response.length > 0) {
			for (var i = 0; i < response.length; i++) {
				if(_acIdx == i)
					selClass = ' selected';
				else
					selClass = '';
				
				div.append('<div class="item' + selClass + '" id=\'vs' + response[i].VenueId + '\' onclick=\'venueSelect(this);\' >' + response[i].VenueName + '</div>');
			}
		} else {
			_acIdx = -1;			
			div.append('<span>Nothing found.</span>');
		}


		$(div).show();
		//position div under textbox
		var search = $(_txt);
		var xy = search.offset();
		div.offset({ left: xy.left, top: xy.top + search.outerHeight() });
		div.width(search.outerWidth());

	}

	function venueSelect(divSelected) {
		if(divSelected == null){
			if(_acIdx > -1){
				var acDiv = $('#autoComplete').find('div');
				if(_acIdx < acDiv.length){
					divSelected = acDiv[_acIdx];
				}
			}
		}

		if(divSelected != null){
		
			var val = divSelected.id.substring(2);
			var txt = divSelected.innerText;
			
			_txt.value = txt;
			
			var spn = $(_txt).parent().parent();
			spn.find('label').text(txt);
			spn.find('input')[2].value = val;
			
			
			autoCompleteHide();
		}		
	}
	
	function autoComplete_Tab(e){
		if(e){
			var unicode = e.keyCode ? e.keyCode : e.charCode;
			if(unicode == 9){
				if(_acIdx != -1){
					var autoComplete = $('#autoComplete div');
					if(_acIdx < autoComplete.length){
						$(autoComplete[_acIdx]).trigger('click');
					}
				}
			}
		}
	}	

	var _acPos = 0;
	var _acIdx = -1;
	function autoCompleteMouseMove(e){
		var acDivList = $('#autoComplete div');
		acDivList.removeClass('selected');

		_acIdx = -1;
		acDivList.each(function(){
			var xy = $(this).offset();
			var w = $(this).outerWidth();
			var h = $(this).outerHeight();

			_acIdx++;
			if(e.pageX > xy.left && e.pageX < xy.left + w){
				if(e.pageY > xy.top && e.pageY < xy.top + h){
					$(this).addClass('selected');
					return false;
				}
			}
		});
	}

	function showEditBoxShowHide(idx){
		
		if(idx == 1){
			$('#divShowEditContainer').show();
			$('#divShowDateContainer').hide();
			
			$('#divTabShow').addClass('edit-tab-selected');
			$('#divTabDate').removeClass('edit-tab-selected');
			
			$('#btnSave').show();
			
		}else{
			$('#divShowEditContainer').hide();
			$('#divShowDateContainer').show();

			$('#divTabShow').removeClass('edit-tab-selected');
			$('#divTabDate').addClass('edit-tab-selected');
			
			$('#btnSave').hide();
		}
		
	}
	
	function performerAdd_keydown(e){
        autoComplete_Tab(e);
	}

	var _performerAdd_keyupDelay = 0;
	var _performerAdd_loading = false;
	function performerAdd_keyup(e,txt){
		if(txt.value.length == 0) return;

		var unicode;

		if(e){
			unicode = e.keyCode ? e.keyCode : e.charCode;
		}else{
			unicode = -1;
		}

		//38=up, 40=down
		if(unicode == 38 || unicode == 40){
			var acDivList = $('#autoComplete div');

			if(unicode == 38){ //up
				if(_acIdx > 0)
					_acIdx--;
			}else{ //down
				if(_acIdx < acDivList.length-1){
					_acIdx++;
				}
			}
			
			acDivList.removeClass('selected');
			if(_acIdx <= acDivList.length){
				$(acDivList[_acIdx]).addClass('selected');
			}
			txt.selectionStart = _acPos; txt.selectionEnd = _acPos;
		}else if(unicode == 13){
			performerSelect();
		}else{
			//_acIdx = -1;
			_acPos = txt.selectionStart;

			_performerAdd_keyupDelay++;
			_txt = txt;
			setTimeout(performerAdd_keyupDelay, 300);
		}
	}

	function performerAdd_keyupDelay(){
		_performerAdd_keyupDelay--;
		if(_performerAdd_keyupDelay == 0){
			if(_performerAdd_loading == false){
				_performerAdd_loading = true;

				var actionUrl = '/ajax/performerlist.php?la=A805345F-10A8-4243-BF0E-3596CA9690F6';
				actionUrl = actionUrl + '&s=' + encodeURI(_txt.value);
				$.getJSON(actionUrl, performerAdd_SearchResult);
			}else{
				_performerAdd_keyupDelay++;
				setTimeout(performerAdd_keyupDelay, 500);
			}

		}

	}

	function performerAdd_SearchResult(response){
		var div = $('#autoComplete');
		div.empty();
		if (response != null && response.length > 0) {
			if(_acIdx >= response.length){
				_acIdx = response.length-1;
			}
			var selClass = '';
			if(_acIdx == i)
				selClass = ' selected';
			else
				selClass = '';
			
			for (var i = 0; i < response.length; i++) {
				div.append('<div class="item' + selClass + '" id=\'vs' + response[i].PerformerId + '\' onclick=\'performerSelect(this);\' >' + response[i].PerformerName + '</div>');
			}
		} else {
			_acIdx = -1;			
			div.append('<span>Nothing found.</span>');
		}


		$(div).show();
		//position div under textbox
		var search = $(_txt);
		var xy = search.offset();
		div.offset({ left: xy.left, top: xy.top + search.outerHeight() });
		div.width(search.outerWidth());

		_performerAdd_loading = false;
	}

	function performerSelect(divSelected){
		
		if(divSelected == null){
			if(_acIdx > -1){
				var acDiv = $('#autoComplete').find('div');
				if(_acIdx < acDiv.length){
					divSelected = acDiv[_acIdx];
				}
			}
		}
		
		if(divSelected != null){
			var perfId = divSelected.id.substring(2);
			var perfName = $(divSelected).text();

			
			$(_txt).val(perfName);
			
			spnPerfId = $(_txt).parent().find('label')
			spnPerfId.text(perfId);

			autoCompleteHide();
		}
	}
	
	function showDatePerformerEditSave(btn, showId, showDateId){
		var isAdd;
		var perfId, perfName, perfTitle, perfLength, perfConfirmed, perfConfirmedText;

		var showDatePerformerId = '';
		var tdAdj;
		
		if(btn.value == 'Add'){
			isAdd = true;
			tdAdj = 0;
		}else{
			isAdd = false;
			tdAdj = 1; //for extra td for /\ and \/
		}
		
		
		var tdList = $(btn).parent().parent().parent().find('td');
		tdList.each(function(idx){
			
			if(idx == 0 + tdAdj){
				if(isAdd){
					perfId = $(this).find('label').text();
					perfName = $(this).find('input').val();
				}else{
					perfId = '';
				}
			}else if(idx == 1 + tdAdj){
				perfTitle = $(this).find('input').val();
			}else if(idx == 2 + tdAdj){
				perfLength = $(this).find('input').val();
			}else if(idx == 3 + tdAdj){
				perfConfirmed = $(this).find('select option:selected').val();
				perfConfirmedText = $(this).find('select option:selected').text();
			}else if(idx == 4 + tdAdj){
				var inputList = $(this).find('input');
				if(inputList.length != 1){
					showDatePerformerId = inputList[4].value;
				}
			}
		});


		if(showDatePerformerId == '' && perfId.length == 0){
			alert('Select a performer before saving.');
		}else{
			StatusUpdate('Saving performer...','');
			
			if(showDateId == ''){ //must be from a new/copied show date, look up id
				var obj = $(btn)

				while(obj.hasClass('show-date-edit-performers-list') == false){
					obj = obj.parent();
				}
				obj = obj.parent().prev();
				obj = obj.find('input')[10];
				showDateId = obj.value;
			}
		
			var actionUrl = '/ajax/ShowDatePerformerSave.php';
			$.post( actionUrl, {
				ShowId : showId,
				ShowDateId : showDateId,
				sdpId : showDatePerformerId,
				sdpTitle : perfTitle,
				sdpLength : perfLength,
				sdpConfirmed : perfConfirmed,
				sdpPerfId : perfId
				})
				.done(function( data ) {
					if(data.length < 5){
						StatusUpdate('There was a problem. Reload the page and try again. (' + data + ')','bad');
					}else{ //success, returns spdId
						if(showDatePerformerId == ''){
							var tr = $(btn).parent().parent().parent();
							var trBlank = tr.prev();
							var trCopy = trBlank.clone();
							trCopy.insertBefore(trBlank);
							
							var tdList = trCopy.find('td');
							$(tdList[1]).text(perfName);
							$($(tdList[2]).find('span')[0]).text(perfTitle);
							$($(tdList[3]).find('span')[0]).text(perfLength);
							$($(tdList[4]).find('span')[0]).text(perfConfirmedText);
							$($(tdList[5]).find('input')[4]).val(data);
							
							trCopy.show();
							
							//clear add row
							var tdList = tr.find('td');
							tdList.each(function(idx){
								if(idx == 0 + tdAdj){
									$(this).find('label').text('');
									$(this).find('input').val('');
								}else if(idx == 1 + tdAdj || idx == 2 + tdAdj){
									$(this).find('input').val('');
								}else if(idx == 3 + tdAdj){
									$(this).find('select').val('0');
								}
							});
							
							
						}else{
							perfEdit(btn, 3);
						}
						StatusUpdate('Performer saved.','good');
						
					}
					
					
				})
				.fail(function(){
					alert('There was a problem. Reload the page and try again.');
				});
		}
		return false;
	}

function showDatePerformerReorder(lnk, showId, dir){
	StatusUpdate('Re-ordering...','');
	
	sdpId = $($(lnk).parent().parent().find('td').get(5)).find('input')[4].value;
		
	var actionUrl = '/ajax/ShowDatePerformerReOrder.php';
	$.post( actionUrl, {
		ShowId : showId,
		SDPId : sdpId,
		Dir : dir
		})
		.done(function( data ) {

			if(data.length < 5){
				if(data != '0'){
					StatusUpdate('There was a problem. Reload the page and try again. (' + data + ')','bad');
				}else{
					StatusUpdate('Re-order successful.','good');
				}
			}else{ //success
				
				var tr1 = $(lnk).parent().parent();
				var tr2;
				if(dir == 1){
					tr2 = tr1.next();
					tr1.insertAfter(tr2);
				}else{
					tr2 = tr1.prev();
					tr2.insertAfter(tr1);
				}
				
				
				StatusUpdate('Re-order successful.','good');
			}
			
			
		})
		.fail(function(){
			alert('There was a problem. Reload the page and try again.');
		});
	

	
}


	function searchHost_keydown(e){
		autoComplete_Tab(e);
	}


	function searchHost_keyup(e,txt){
		if(txt.value.length == 0) return;

		var unicode;

		if(e){
			unicode = e.keyCode ? e.keyCode : e.charCode;
		}else{
			unicode = -1;
		}

		//38=up, 40=down
		if(unicode == 38 || unicode == 40){
			var acDivList = $('#autoComplete div');

			if(unicode == 38){ //up
				if(_acIdx > 0)
					_acIdx--;
			}else{ //down
				if(_acIdx < acDivList.length-1){
					_acIdx++;
				}
			}
			
			acDivList.removeClass('selected');
			if(_acIdx <= acDivList.length){
				$(acDivList[_acIdx]).addClass('selected');
			}
			txt.selectionStart = _acPos; txt.selectionEnd = _acPos;
		}else if(unicode == 13){
			hostSelect();
		}else{
			//_acIdx = -1;
			_acPos = txt.selectionStart;

			var search = txt.value;
			var actionUrl = '/ajax/SearchHost.php?la=A805345F-10A8-4243-BF0E-3596CA9690F6';
			actionUrl = actionUrl + '&s=' + encodeURI(search);
			$.getJSON(actionUrl, searchHostResponse);


		}
	}
	function searchHostResponse(response){
		var div = $('#autoComplete');
		div.empty();
		if (response != null && response.length > 0) {
			if(_acIdx >= response.length){
				_acIdx = response.length-1;
			}
			var selClass = '';
			for (var i = 0; i < response.length; i++) {
				if(_acIdx == i)
					selClass = ' selected';
				else
					selClass = '';
				div.append('<div class="item' + selClass + '" id=\'vs' + response[i].HostId + '\' onclick=\'hostSelect(this);\' >' + response[i].HostName + '</div>');
			}
		} else {
			_acIdx = -1;
			div.append('<span>Nothing found.</span>');
		}

		$(div).show();
		//position div under textbox
		var search = $('#txtSearchHost');
		var xy = search.offset();
		div.offset({ left: xy.left, top: xy.top + search.outerHeight() });
		div.width(search.outerWidth());
	}
	function hostSelect(divSelected) {
		if(divSelected == null){
			if(_acIdx > -1){
				var acDiv = $('#autoComplete').find('div');
				if(_acIdx < acDiv.length){
					divSelected = acDiv[_acIdx];
				}
			}
		}

		if(divSelected != null){
			var val = divSelected.id.substring(2);
			var name = $(divSelected).text();

			var tdHostList = $('#tdHostList');
			var id;
			var found = false;
			tdHostList.find('input').each(function(idx){
				id = this.id;
				if(id.length > 7){
					if(id.substring(0,7) == 'chkHost'){
						id = id.substring(7);
						if(id == val){
							found = true;
							this.checked = true;
							return false;
						}
					}
				}
			});
			if(!found){
				var lastDiv = tdHostList.find('div').last();
				lastDiv.before('<div><input id="chkHost'+val+'" name="chkHost'+val+'" type="checkbox" value="'+val+'" checked="checked" />'
				//+'<input name="chkHost'+val+'" type="hidden" value="false" />'
				+'<label for="chkHost'+val+'">'+name+'</label></div>');
			}
			
			$('#txtSearchHost').val('');
			autoCompleteHide();
		}

	}

	function autoCompleteCheck(){
		if(_acIdx == -1)
			return true;
		else
			return false;
	}
	
</script>

<div id="autoComplete" onmousemove="autoCompleteMouseMove(event);" style="display:none;"></div>





<?php
include "../include/footer.php";
?>
<div id="autoComplete" onmousemove="autoCompleteMouseMove(event);" style="display:none;"></div>