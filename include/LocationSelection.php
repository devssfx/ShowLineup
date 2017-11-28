<?php

function LocationSelectionDraw($singleSelection, $locationsSelected){

if(count($locationsSelected) == 0 
	|| (count($locationsSelected) == 1 && strlen($locationsSelected[0]) == 0)
	){
	if(isset($_SESSION["locarea"])){
		$locationsSelected[] = $_SESSION["locarea"];
	}
}

?>

<div>
	Located
	<div>
		<?php
		
		$cid = "";
		$rid = "";
		$aid = "";
		
		$conn = DBConnect();
		if($conn){
			$sql = "select lc.LocationCountryName, lc.LocationCountryId, lr.LocationRegionName, lr.LocationRegionId, la.* from evplocationcountry lc inner join evplocationregion lr on lc.LocationCountryId = lr.LocationCountryId inner join evplocationarea la on lr.LocationRegionId = la.LocationRegionId where lr.LocationCountryId = " . ParseS($_SESSION["loccountry"])
				. " order by lr.LocationRegionName, la.LocationAreaName";
				
			$result = mysqli_query($conn, $sql);
			if($result){
				if(mysqli_num_rows($result)){
					$currRegion = "";
					$regionUnchecked = false;
					$areaUnchecked = false;
					while($row = mysqli_fetch_assoc($result)){
						if($cid != $row["LocationCountryId"]){
							$cid = $row["LocationCountryId"];
							echo '<div class="ls-country-item" level="1">';
							echo '<input id="chkLocCountry'. $row["LocationCountryId"] . '" name="chkLocCountry'. $row["LocationCountryId"] . '" onclick="itemChecked(this);" type="checkbox" value="true"';
							if($singleSelection)
								echo ' disabled="disabled"';
							
							if(in_array($row["LocationCountryId"],$locationsSelected)){
								echo ' checked="checked"';
							}
							
							echo ' />';
							echo '<label for="chkLocCountry'. $row["LocationCountryId"] . '">'. $row["LocationCountryName"] . '</label></div>';
						}
						if($rid != $row["LocationRegionId"]){
							$rid = $row["LocationRegionId"];
							echo '<div class="ls-region-item" level="2">';
							echo '<input id="chkLocRegion'. $row["LocationRegionId"] . '" name="chkLocRegion'. $row["LocationRegionId"] . '" onclick="itemChecked(this);" type="checkbox" value="true"';
							if($singleSelection){
								echo ' disabled="disabled"';
							}else{
								if(in_array($row["LocationRegionId"],$locationsSelected)){
									echo ' checked="checked"';
								}
							}
							
							echo ' />';
							echo '<label for="chkLocRegion'. $row["LocationRegionId"] . '">'. $row["LocationRegionName"] . '</label></div>';
							
							$areaUnchecked = false;
						}
						if($aid != $row["LocationAreaId"]){
							echo '<div class="ls-area-item" level="3">';
							echo '<input id="chkLocArea'. $row["LocationAreaId"] . '" name="chkLocArea'. $row["LocationAreaId"] . '" onclick="itemChecked(this);" type="checkbox" value="true"';
							if(in_array($row["LocationAreaId"],$locationsSelected)){
								echo ' checked="checked"';
							}else{
								$areaUnchecked = true;
							}
							echo ' />';
							echo '<label for="chkLocArea'. $row["LocationAreaId"] . '">'. $row["LocationAreaName"] . '</label></div>';
						}
					}
				}
				mysqli_free_result($result);
			}

			mysqli_close($conn);
		}
		?>

	</div>
</div>
<script type="text/javascript">

	<?php
	if(!$singleSelection){ ?>
	var countryDiv = $('.ls-country-item').parent();
	var chkList = countryDiv.find('input');
	
	var areaFull = true;
	var regionFull = true;
	for(var i = chkList.length - 1; i >= 0; i--){
		var idChk = chkList[i].id;
		if(idChk.substring(0, 10) == 'chkLocArea'){
			if($(chkList[i]).attr('checked') == false){
				areaFull = false;
			}
		}else if(idChk.substring(0, 12) == 'chkLocRegion'){
			if(areaFull){
				$(chkList[i]).attr('checked', true);
			}else{
				areaFull = true;
				regionFull = false;
			}
		}else{ //country
			if(regionFull){
				$(chkList[i]).attr('checked', true);
			}
		}
	}
	<?php } ?>
	

	function itemChecked(ckb) {

		var isChecked = ckb.checked;
		var div = $(ckb).parent();
		var divChk;
		var startLevel = parseInt(div.attr('level'));

		var lookFor;

		if (startLevel != 1) {
			if (!isChecked) {
				lookFor = startLevel - 1;
				//go up, uncheck first 1 and 2 found
				divChk = div.prev('div');
				while (divChk.length != 0) {
					if (parseInt(divChk.attr('level')) == lookFor) {
						divChk.find('input').attr('checked', isChecked);
						lookFor--;
						if (lookFor == 0) break;
					}
					divChk = divChk.prev('div');
				}
			}else{
				if(<?php 
				if($singleSelection) 
					echo 'true'; 
				else 
					echo 'false'; 
				?>){ //uncheck all others
					$('.ls-area-item').find('input').attr("checked", false);
					ckb.checked = true;
				}
			}
		}

		if(startLevel != 3){
			lookFor = startLevel + 1;
			var thisLevel;
			divChk = div.next('div');
			while (divChk.length != 0) {
				thisLevel = parseInt(divChk.attr('level'));
				if (thisLevel <= startLevel)
					break;

				divChk.find('input').attr('checked', isChecked);

				divChk = divChk.next('div');
			}
		}

	}


</script>

<?php
}
?>