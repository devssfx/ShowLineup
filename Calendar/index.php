<?php
$pageTitle = "Shows";
include "../include/db.php";
include "../include/header.php";


HeaderDraw();

function ClassTypeString($classType){
	$cType = "General";
	if($classType == "p"){ //performing
		$cType = "Performing";
	}else if( strlen($classType) == 2){
		if(substr($classType,1)=="a"){
			$cType = "Involved";
		}//else liked
	}

	return $cType;
	
}


class ShowInfo{
	public $ShowDateId;
	public $ShowName;
	public $ShowUrl;
	public $ShowDate;
	public $ShowTime;
	public $VenueId;
	public $FestivalId;
	public $ShowStatus;
	public $ShowDateStatus;
	public $ClassType;
}
class FestivalInfo{
	public $Id;
	public $Name;
}
class VenueInfo{
	public $Id;
	public $Name;
}


$dateStart = new DateTime();
if(isset($_POST["btnDateRangeUpdate"])){
	$monthStart = $_POST["selMonth"];
	$yearStart = $_POST["selYear"];
	$monthCount = $_POST["monthCount"];
	
	$dateStart = date_create($yearStart . "-" . $monthStart . "-01" );
	
}else{
	$monthCount = 2;
	$dateStart = date_create(date_format($dateStart, "Y") . "-" . date_format($dateStart, "m") . "-01" );
	
	$monthStart = date_format($dateStart, "m");
	$yearStart = date_format($dateStart, "Y");
	
}
$dateEnd = date_create(date_format($dateStart, "Y") . "-" . date_format($dateStart, "m") . "-01" );
$dateEnd->modify('+' . $monthCount . ' month');

while(date_format($dateStart, "w") != 1){
	$dateStart->modify('-1 day');
}
while(date_format($dateEnd, "w") != 0){
	$dateEnd->modify('+1 day');
}


if(isset($_POST["subby"])){
	echo "subby!!!!";
}

?>
<script type="text/javascript">

		function gotoSomething(ddl, something) {
			location.href = '/' + something + '/' + $(ddl).val();
		}

		function goingClick(btn, showDateId) {
		
			var actionUrl = '/ajax/GoingToShowDate';
			var changeToGoing = $(btn).val() != 'Going';

			$.post(actionUrl, {
				ShowDateId: showDateId,
				IsGoing: changeToGoing
			})
			.done(function (data) {
				var rtn = data[0].Success;

				var assoc = '';
				if (data.length > 1);
				assoc = data[1].Assoc;


				if (rtn == 1) {
					alert('There was a problem. Reload the page and try again.');
				} else if (rtn == 0) { //success
					if (changeToGoing) {
						$(btn).val('Going');
					} else {
						$(btn).val('Join');
					}

					if (assoc.length != 0) {
						var colourBar = $(btn).parent().parent().parent(); //get tr
						colourBar = $(colourBar[0]).prev();
						colourBar = colourBar.find('td');
						colourBar.attr('class', 'calShow' + assoc);
					}

				}
			})
			.fail(function () {
				alert('There was a problem. Reload the page and try again.');
			});
		}

		function addNew(a) {
			switch(a){
				case 'f': location.href = '/Festivals/Edit';
					break;
				case 's': location.href = '/Shows/Edit';
					break;
				case 'p': location.href = '/Performers/Edit';
					break;
				case 'v': location.href = '/Venues/Edit';
					break;
				case 'h': location.href = '/Hosts/Edit';
					break;
			}
		}


	</script>

<form action="/Calendar/" method="post">
	<div>
		<select id="selMonth" name="selMonth">
			<option<?php if($monthStart == 1) echo ' selected="selected"'; ?> value="1">Jan</option>
			<option<?php if($monthStart == 2) echo ' selected="selected"'; ?> value="2">Feb</option>
			<option<?php if($monthStart == 3) echo ' selected="selected"'; ?> value="3">Mar</option>
			<option<?php if($monthStart == 4) echo ' selected="selected"'; ?> value="4">Apr</option>
			<option<?php if($monthStart == 5) echo ' selected="selected"'; ?> value="5">May</option>
			<option<?php if($monthStart == 6) echo ' selected="selected"'; ?> value="6">Jun</option>
			<option<?php if($monthStart == 7) echo ' selected="selected"'; ?> value="7">Jul</option>
			<option<?php if($monthStart == 8) echo ' selected="selected"'; ?> value="8">Aug</option>
			<option<?php if($monthStart == 9) echo ' selected="selected"'; ?> value="9">Sep</option>
			<option<?php if($monthStart == 10) echo ' selected="selected"'; ?> value="10">Oct</option>
			<option<?php if($monthStart == 11) echo ' selected="selected"'; ?> value="11">Nov</option>
			<option<?php if($monthStart == 12) echo ' selected="selected"'; ?> value="12">Dec</option>
		</select>
		<input class="input-width-year" id="selYear" name="selYear" type="text" value="<?php echo $yearStart; ?>" />

		Months:
		<input class="input-width-day" data-val="true" data-val-number="The field Int32 must be a number." data-val-required="The Int32 field is required." id="monthCount" name="monthCount" type="text" value="<?php echo $monthCount; ?>" />
		<input type="submit" value="Update" name="btnDateRangeUpdate" />
		<input type="submit" value="Today" name="btnToday" />
	</div>
</form>





<?php


	$sql = "select sl.*, v.VenueName, f.FestivalName from (";
if(isset($_SESSION["mi"])){	
	$sql = $sql . " select s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength, 'p' as ClassType from evpShowDate sd"
	. " inner join evpShow s on sd.ShowId = s.ShowId"
	. " inner join evpShowDatePerformer sdp on sd.ShowDateId = sdp.ShowDateId"
	. " inner join evpPerformerMember pm on sdp.PerformerId = pm.PerformerId"
	. " where sd.ShowDate >= " . date_format($dateStart, "Ymd") . " and sd.ShowDate <= " . date_format($dateEnd, "Ymd")
	. " and sd.ShowDateStatus <> 2"
	. " and pm.MemberId = " . ParseS($_SESSION["mi"])
	. " union"
	. " select s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength, 'pa' as ClassType from evpShowDate sd"
	. " inner join evpShow s on sd.ShowId = s.ShowId"
	. " inner join evpShowDatePerformer sdp on sd.ShowDateId = sdp.ShowDateId"
	. " inner join evpPerformerAdmin pa on sdp.PerformerId = pa.AdminForId"
	. " where sd.ShowDate >= " . date_format($dateStart, "Ymd") . " and sd.ShowDate <= " . date_format($dateEnd, "Ymd")
	. " and sd.ShowDateStatus <> 2"
	. " and pa.MemberId = " . ParseS($_SESSION["mi"])
	. " union"
	. " select s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength, 'sa' as ClassType from evpShowDate sd"
	. " inner join evpShow s on sd.ShowId = s.ShowId"
	. " inner join evpShowAdmin sa on s.ShowId = sa.AdminForId"
	. " where sd.ShowDate >= " . date_format($dateStart, "Ymd") . " and sd.ShowDate <= " . date_format($dateEnd, "Ymd")
	. " and sd.ShowDateStatus <> 2"
	. " and sa.MemberId = " . ParseS($_SESSION["mi"])
	. " union"
	. " select s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength, 'ha' as ClassType from evpShowDate sd"
	. " inner join evpShow s on sd.ShowId = s.ShowId"
	. " inner join evpShowHost sh on s.ShowId = sh.ShowId"
	. " inner join evpHostAdmin ha on sh.HostId = ha.AdminForId"
	. " where sd.ShowDate >= " . date_format($dateStart, "Ymd") . " and sd.ShowDate <= " . date_format($dateEnd, "Ymd")
	. " and sd.ShowDateStatus <> 2"
	. " and ha.MemberId = " . ParseS($_SESSION["mi"])
	. " union"
	. " select s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength, 'va' as ClassType from evpShowDate sd"
	. " inner join evpShow s on sd.ShowId = s.ShowId"
	. " inner join evpVenueAdmin va on sd.VenueId = va.AdminForId"
	. " where sd.ShowDate >= " . date_format($dateStart, "Ymd") . " and sd.ShowDate <= " . date_format($dateEnd, "Ymd")
	. " and sd.ShowDateStatus <> 2"
	. " and va.MemberId = " . ParseS($_SESSION["mi"])
	. " union"
	. " select s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength, 'fa' as ClassType from evpShowDate sd"
	. " inner join evpShow s on sd.ShowId = s.ShowId"
	. " inner join evpFestivalAdmin fa on s.FestivalId = fa.AdminForId"
	. " where sd.ShowDate >= " . date_format($dateStart, "Ymd") . " and sd.ShowDate <= " . date_format($dateEnd, "Ymd")
	. " and sd.ShowDateStatus <> 2"
	. " and fa.MemberId = " . ParseS($_SESSION["mi"])
	. " union";
}
	$sql = $sql . " select s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength, 'g' as ClassType from evpShowDate sd"
	. " inner join evpShow s on sd.ShowId = s.ShowId"
	. " where sd.ShowDate >= " . date_format($dateStart, "Ymd") . " and sd.ShowDate <= " . date_format($dateEnd, "Ymd")
	. " and sd.ShowDateStatus <> 0 and sd.ShowDateStatus <> 2"
	. " ) as sl"
	. " left outer join evpVenue v on sl.VenueId = v.VenueId"
	. " left outer join evpFestival f on sl.FestivalId = f.FestivalId"
	. " order by ShowDate, ShowTime, ShowDateId;";

	

//echo $sql;

$showList = array();
$venueList = array();
$festivalList = array();

$conn = DBConnect();
if($conn){
	if($result = mysqli_query($conn, $sql)){
		while($row = mysqli_fetch_assoc($result)){
			$found = false;
			foreach($showList as $item){
				if($item->ShowDateId == $row["ShowDateId"]){
					$found = true;
					break;
				}
			}
			if(!$found){
				$showItem = new ShowInfo();
				$showItem->ShowDateId = $row["ShowDateId"];
				$showItem->ShowName = $row["ShowName"];
				$showItem->ShowUrl = $row["ShowUrlFriendlyName"];
				$showItem->ShowDate = $row["ShowDate"];
				$showItem->ShowTime = $row["ShowTime"];
				$showItem->ShowStatus = $row["ShowStatus"];
				$showItem->ShowDateStatus = $row["ShowDateStatus"];
				$showItem->VenueId = $row["VenueId"];
				$showItem->FestivalId = $row["FestivalId"];
				$showItem->ClassType = $row["ClassType"];
				
				$showList[] = $showItem;
			
				//remember venues
				$found = false;
				foreach($venueList as $vItem){
					if($vItem->Id == $showItem->VenueId){
						$found = true;
						break;
					}
				}
				if(!$found){
					$vItem = new VenueInfo();
					$vItem->Id = $showItem->VenueId;
					$vItem->Name = $row["VenueName"];
					$venueList[] = $vItem;
				}
				//remember festivals
				$found = false;
				foreach($festivalList as $item){
					if($item->Id == $showItem->FestivalId){
						$found = true;
						break;
					}
				}
				if(!$found){
					$item = new VenueInfo();
					$item->Id = $showItem->FestivalId;
					$item->Name = $row["FestivalName"];
					$festivalList[] = $item;
				}
			}
			
		}
		mysqli_free_result($result);
	}
	mysqli_close($conn);
}


$altMonth = 2;
$monthValue = date_format($dateStart, "m");
$showIdx = 0;
$showCount = count($showList);

?>



<div class="CalendarColours" style="padding-top:5px;margin-bottom:10px;">
	<div class="ledgend">Ledgend:</div>
	<?php
	$ledgend = ";";
	foreach($showList as $item){
		$cType = ClassTypeString($item->ClassType);
		

		
/*
Going
LikedPerformer
LikedVenue
LikedHost
*/
		if(strlen($cType) > 0){
			if(strpos($ledgend, ';' . $cType . ';') === false){
				$ledgend = $ledgend . $cType . ';';
				echo '<div class="divButton ledgend calShow'.$cType.'">';
				echo '<input type="checkbox" id="'.$cType.'" onchange="ledgendSelect(this);" checked="checked" />';
				echo '<label for="'.$cType.'">'.$cType.'</label>';
				echo '</div>';
			}
		}
	}
	?>
	<div style="clear:both;"></div>
</div>

<div>
	<div style="float:left;">Festival:</div>
	<div style="float:left;background-color:#DBDBDB;padding:2px;margin-left:5px;margin-bottom:5px;">
		<input type="checkbox" id="chkFestivalNone" onchange="festivalSelect(this);" checked="checked" />
		<label for="chkFestivalNone">None</label>
	</div><?php
	foreach($festivalList as $item){
		echo '<div style="float:left;background-color:#DBDBDB;padding:2px;margin-left:5px;margin-bottom:5px;">';
		echo '<input type="checkbox" id="'. $item->Id.'" onchange="festivalSelect(this);" checked="checked" />';
		echo '<label for="'. $item->Id.'">'. $item->Name.'</label>';
		echo '</div>';
	}?><div style="clear:left;"></div>
</div>


<div>
	<div style="float:left;">Venues:</div>
	<div style="float:left;background-color:#DBDBDB;padding:2px;margin-left:5px;margin-bottom:5px;">
		<input type="checkbox" id="chkVenueAll" onchange="venueSelect(this);" checked="checked" />
		<label for="chkVenueAll">All Venues</label>
	</div><?php 
	foreach($venueList as $vItem){
		echo '<div style="float:left;background-color:#DBDBDB;padding:2px;margin-left:5px;margin-bottom:5px;">';
		echo '<input type="checkbox" id="'. $vItem->Id .'" onchange="venueSelect(this);" checked="checked" />';
		echo '<label for="'.$vItem->Id.'">'. $vItem->Name . '</label>';
		echo '</div>';
	} ?>
	<div style="clear:left;"></div>
</div>




<table class="Calendar CalendarColours">
	<tr>
		<th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th>
	</tr>
	
	<?php
	while($dateStart < $dateEnd){
		echo "<tr>";
		for($dayIdx = 0; $dayIdx < 7; $dayIdx++){
			if($monthValue != date_format($dateStart, "m")){
				$monthValue = date_format($dateStart, "m");
				if($altMonth == 1)
					$altMonth = 2;
				else
					$altMonth = 1;
			}
		?>
			<td class="altMonth<?php echo $altMonth; ?>">
				<div class="calDate">
						<span><?php 
							echo date_format($dateStart, "j M"); 
							if(date_format($dateStart, "j") == 1 && date_format($dateStart, "m") == 1)
								echo ' ' . date_format($dateStart, "Y");
						?></span>
				</div>
				<div class="calDay">
				<?php
				if($showIdx < $showCount){
					$dateThis = date_format($dateStart, "Ymd");
					$showItem = $showList[$showIdx];
					
					while($showItem->ShowDate == $dateThis){ ?>
						<div style="max-width:200px;max-height:200px;overflow:hidden;" class="calShow calShow<?php echo ClassTypeString($showItem->ClassType); ?>"><a href="/Shows/<?php echo $showItem->ShowUrl; ?>"><?php 
							echo $showItem->ShowName; 
							if($showItem->ShowDateStatus != 1)
								echo ' (' . StatusToString($showItem->ShowDateStatus) . ')';
							?></a>
							<span class="calVenueHide" style="display:none;"><?php echo $showItem->VenueId; ?></span>
							<span class="calFestivalHide" style="display:none;"><?php echo $showItem->FestivalId; ?></span>
							<span class="calHideLedgend"></span>
							<span class="calHideVenue"></span>
							<span class="calHideFestival"></span>
						</div><?php
						
						$showIdx++;
						if($showIdx < $showCount){
							$showItem = $showList[$showIdx];
						}else{
							break;
						}
					}
				}
				?>
				</div>
				
			</td><?php
			
			$dateStart->modify('+1 day');
		}
		echo "</tr>";
	}
?>	

</table>


<script type="text/javascript">
	function ledgendSelect(chk) {
		var css = chk.id;

		var isHidden;
		var venueObj;
		var countObj;

		$('table.Calendar div.calShow').each(function () {
			if ($(this).attr('class').indexOf(css) != -1) {

				countObj = $(this).find('span.calHideLedgend');
				if (!chk.checked) {
					countObj.text('1'); //hide via ledgend
				} else {
					countObj.text(''); //show via ledgend if venue isn't hidden
				}
				isHidden = (countObj.text().length != 0);
				if (!isHidden) {
					countObj = $(this).find('span.calHideVenue');
					isHidden = (countObj.text().length != 0);
				}
				if (!isHidden) {
					countObj = $(this).find('span.calHideFestival');
					isHidden = (countObj.text().length != 0);
				}
				if (chk.checked && !isHidden) {
					$(this).css('display', '');
				} else {
					$(this).css('display', 'none');
				}
			}
		});

	}

	function venueSelect(chk) {
		var css = chk.id;
		var forAll = false;

		var disValue;
		if (chk.checked) {
			disValue = '';
		} else {
			disValue = 'none';
		}

		if (css == 'chkVenueAll') {
			forAll = true;
			var chkList = $(chk).parent().parent().find('input');
			for (var i = 1; i < chkList.length; i++) {
				chkList[i].checked = chk.checked;
			}
		}

		var isHidden;
		var venueObj;
		var countObj;
		$('table.Calendar div.calShow').each(function () {

			venueObj = $(this).find('span.calVenueHide');
			if (venueObj.length == 1) {
				if (forAll || venueObj[0].innerHTML == chk.id) {

					countObj = $(this).find('span.calHideVenue');
					if (!chk.checked) {
						countObj.text('1'); //hide via venue
					} else {
						countObj.text(''); //show via venue if ledgend isn't hidden
					}
					isHidden = (countObj.text().length != 0);

					if (!isHidden) {
						countObj = $(this).find('span.calHideLedgend');
						isHidden = (countObj.text().length != 0);
					}
					if (!isHidden) {
						countObj = $(this).find('span.calHideFestival');
						isHidden = (countObj.text().length != 0);
					}
					
					if (!chk.checked || (!isHidden && chk.checked)) {
						$(this).css('display', disValue);
					}

				}
			}
		});
	}


	function festivalSelect(chk) {
		
		var css = chk.id;

		var disValue;
		if (chk.checked) {
			disValue = '';
		} else {
			disValue = 'none';
		}

		var chkId;

		if (css == 'chkFestivalNone') {
			chkId = '00000000-0000-0000-0000-000000000000';
			//var chkList = $(chk).parent().parent().find('input');
			//for (var i = 1; i < chkList.length; i++) {
			//	chkList[i].checked = chk.checked;
			//}
		} else {
			chkId = chk.id;
		}
		
		var isHidden;
		var festivalObj;
		var countObj;
		$('table.Calendar div.calShow').each(function () {
			festivalObj = $(this).find('span.calFestivalHide');
			if (festivalObj.length == 1) {
				if (festivalObj[0].innerHTML == chkId) {
					countObj = $(this).find('span.calHideFestival');
					if (!chk.checked) {
						countObj.text('1'); //hide via festival
					} else {
						countObj.text(''); //show via festival if ledgend isn't hidden
					}
					isHidden = (countObj.text().length != 0);

					if (!isHidden) {
						countObj = $(this).find('span.calHideVenue');
						isHidden = (countObj.text().length != 0);
					}
					if (!isHidden) {
						countObj = $(this).find('span.calHideLedgend');
						isHidden = (countObj.text().length != 0);
					}
					if (!chk.checked || (!isHidden && chk.checked)) {
						$(this).css('display', disValue);
					}

				}
			}
		});
	}


</script>


<?php
include "../include/footer.php";
?>