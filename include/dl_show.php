<?php

$thisId = "";
$thisName = "";
$thisFriendly = "";
$thisDesc = "";
$thisIsAdmin = false;
$dateOpen = "";
$thisStatus = 0;
$thisFestivalId = "";
$festivalName = "";
$festivalUrl = "";
$thisPriceRange = "";

$dateList = array();


class DateData{
	public $ShowDateId;
	public $ShowDate;
	public $ShowTime;
	public $ShowDateStatus;
	public $VenueId;
	public $VenueName;
	public $VenueUrlFriendlyName;
	public $VenueConfirmation;
	public $ShowLength;
	public $GoingToStatus;
}

class PerformerData{
	public $ShowDatePerformerId;
	public $ShowDateId;
	public $PerformerId;
	public $DisplayOrder;
	public $PerformerTitle;
	public $PerformerConfirmed;
	public $PerformanceLength;
	public $PerformerName;
	public $PerformerFriendlyUrl;
}
class HostData{
	public $HostId;
	public $HostName;
	public $HostUrlFriendlyName;
}

function DL_ShowSave($thisId, $thisName, $thisFriendly, $thisFestivalId, $thisPriceRange, $thisStatus, $thisDesc,$showDate,$showTime,$showLength,$dateStatus,$venueId, $hostList){
	$success = 0;


	$conn = DBConnect();
	if($conn){

	
		if(strlen($thisId) == 0){
			$thisId = getGUID();
			$saveType = "i"; //insert
		}else{
			$saveType = "u"; //update
		}
		
		
		//double check url friendly name
		$extra = 1;
		$checkFriendly = $thisFriendly;
		$keepGoing = true;

		while($keepGoing){
			$sql = "select * from evpshow where ShowUrlFriendlyName = ". ParseS($checkFriendly)." and CountryId = " . ParseS($_SESSION["loccountry"]);
			if($saveType == "u"){
				$sql = $sql . " and ShowId <> ".ParseS($thisId);
			}
			$result = mysqli_query($conn, $sql);
			if(mysqli_num_rows($result) == 0){
				$keepGoing = false;
			}else{
				$extra++;
				$checkFriendly = $thisFriendly . '-' . $extra;
			}
			mysqli_free_result($result);
		}
		$thisFriendly = $checkFriendly;
		
		
		

		if($saveType == "i"){
			$sql = "insert into evpshow (ShowId,ShowName,ShowUrlFriendlyName, PriceRange, ShowStatus,FestivalId,ShowDesc, CountryId) values "
				. " (" . ParseS($thisId) . "," . ParseS($thisName) . "," . ParseS($thisFriendly) . "," . ParseS($thisPriceRange) . "," . $thisStatus . "," . ParseS($thisFestivalId) . "," . ParseS($thisDesc) . "," . ParseS($_SESSION['loccountry']) . ")";
		}else{
			$sql = "update evpshow set ShowName = " . ParseS($thisName) . ",ShowUrlFriendlyName = " . ParseS($thisFriendly)  . ",ShowStatus = " . $thisStatus . ",FestivalId = " . ParseS($thisFestivalId)
				. ",PriceRange = " . ParseS($thisPriceRange)
				. ",ShowDesc = " . ParseS($thisDesc) . " where ShowId = " . ParseS($thisId);
		}

		if ($conn->query($sql) === TRUE) {

			if($saveType == "i"){ //create admin for member logged in
				$sql = "insert into evpshowadmin (AdminForId, MemberId) values (" . ParseS($thisId) . "," . ParseS($_SESSION["mi"]) . ")";
				if ($conn->query($sql) !== TRUE) {
					$success = -2;
				}else{ //save the date for new records
					if($showDate != 0){
				
						$ShowDateId = getGUID();
						$venueStatus = 0;
						$sql = "insert into evpshowdate (ShowDateId,ShowId,ShowDate,ShowTime,ShowDateStatus,VenueId,VenueConfirmation,ShowLength) values ("
							. ParseS($ShowDateId) . "," . ParseS($thisId) . "," . $showDate . "," . $showTime . "," . $dateStatus . "," . ParseS($venueId) . "," . $venueStatus . "," . ParseS($showLength) .")";

						if ($conn->query($sql) !== TRUE) {
							$success = -3;
						}
					}
				}
			}
			if($success == 0){
				//add hosts
				$sql = "delete from evpshowhost where ShowId = " . ParseS($thisId);
				if($conn->query($sql) === true){
					foreach($hostList as $hostId){
						if($conn->query("insert into evpshowhost (ShowId, HostId) values (". ParseS($thisId) . "," . ParseS($hostId) . ")") !== true){
							$success = -5;
						}
					}
				}else{
					$success = 4;
				}
			}
			
		}else{
			echo $sql . '<br>';
			$success = -1;
		}
		
		mysqli_close($conn);
	}
	
	if($success == 0){
		unset($_SESSION["ddShow"]);
		return $thisFriendly;
	}else
		return $success;
	
}



function DL_ShowInvolvedAndList(){
	
	$conn = DBConnect();
	if($conn){
		if(isset($_SESSION['mi'])){
			$sql = "select distinct s.ShowId, s.ShowName, s.ShowUrlFriendlyName, s.ShowStatus from evpshow s"
			. " inner join evpshowadmin sa on s.ShowId = sa.AdminForId"
			. " left outer join evpshowdate sd on s.ShowId = sd.ShowId"
			. " left outer join evpshowdateperformer sdp on sd.ShowDateId = sdp.ShowDateId"
			. " left outer join evpperformermember pm on sdp.PerformerId = pm.PerformerId"
			. " left outer join evpperformeradmin pa on sdp.PerformerId = pa.AdminForId"
			. " left outer join evpvenueadmin va on sd.VenueId = va.AdminForId"
			. " left outer join evpshowhost sh on s.ShowId = sh.ShowId"
			. " left outer join evphostadmin ha on sh.HostId = ha.AdminForId"
			. " left outer join evpfestivaladmin fa on s.FestivalId = fa.AdminForId"
			. " where s.ShowStatus <> 2 and ("
			. " sa.MemberId = " . ParseS($_SESSION["mi"])
			. " or pm.MemberId = " . ParseS($_SESSION["mi"])
			. " or pa.MemberId = " . ParseS($_SESSION["mi"])
			. " or va.MemberId = " . ParseS($_SESSION["mi"])
			. " or ha.MemberId = " . ParseS($_SESSION["mi"])
			. " or fa.MemberId = " . ParseS($_SESSION["mi"])
			. " ) order by sd.ShowDate, sd.ShowTime";

			$idList = array();
			
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				echo '<div class="involvedWithBox"><h3>Involved With</h3>';
				while($row = mysqli_fetch_assoc($result)) {
					if(!in_array($row["ShowId"], $idList)){
						echo '<div><a href="'. $row['ShowUrlFriendlyName'] . '">'. $row['ShowName'] . '</a>';
						if($row["ShowStatus"] == 0)
							echo ' (Draft)';
						echo '</div>';
						
						$idList[] = $row["ShowId"];
					}
					
				}
				echo '</div>';
			}
			mysqli_free_result($result);

		} //isset session

		$dateOpen = new DateTime();
		$dateClose = new DateTime();
		$dateClose->modify('+2 month');

		$sql = "select s.*, sd.ShowDateId, p.*, sd.ShowDate, sd.ShowTime, v.VenueName, v.VenueUrlFriendlyName, f.FestivalName, sdp.Title"
		. " from evpshow s inner join evpshowdate sd on s.ShowId = sd.ShowId"
		. " inner join evpvenue v on v.VenueId = sd.VenueId inner join evplocationarea la on v.LocationAreaId = la.LocationAreaId"
		. " left outer join evpfestival f on s.FestivalId = f.FestivalId"
		. " left outer join evpshowdateperformer sdp on sd.ShowDateId = sdp.ShowDateId left outer join evpperformer p on sdp.PerformerId = p.PerformerId"
		. " where s.ShowStatus = 1 and (sd.ShowDate >= " . date_format($dateOpen, 'Ymd') . " and sd.ShowDate <= " . date_format($dateClose, 'Ymd') . ")";
		if(isset($_SESSION["locarea"])){
			$sql = $sql . " and la.LocationAreaId = " . ParseS($_SESSION["locarea"]);
		}else{
			$sql = $sql . " and la.LocationRegionId = " . ParseS($_SESSION["locregion"]);
		}
		$sql = $sql . " order by sd.ShowDate, sd.ShowTime, sdp.DisplayOrder";
//echo $sql;
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			echo '<h2>Coming Shows:</h2>';
			echo '<br />';
			$date = 0;
			$showDate = 0;
			$showDateId = "";
			$showName = "";
			$showUrl = "";
			$venueName = "";
			$venueUrl = "";
			$festivalName = "";
			$festivalUrl = "";
			$performerList = array();

			$finished = false;
			while(!$finished) {

				if($row = mysqli_fetch_assoc($result)){
					$finished = false;
				}else{
					$finished = true;
				}

				if($finished === true || $showDateId != $row["ShowDateId"]){ //need to do 1 last loop through when finished
					if(strlen($showDateId) > 0){
						if($date != $showDate){
							echo '<div class="dayHeading">' . DateToString($showDate) . '</div>';
							$date = $showDate;
						}
						
						?>
						<div class="showDetails">
							<div class="showImage">
							</div>

							<table border="0" cellspacing="0" cellpadding="0" class="CalendarColours"><tbody>
								<tr>
									<td class="calShowInvolved">&nbsp;</td>
									<td style="padding-left:5px;">
										
										<div><a href="/Shows/<?php echo $showUrl; ?>"><?php echo $showName; ?></a></div>

										<div>
											<?php 
											echo $showTime;
											if(strlen($venueName) > 0){
												echo ' <span>at <a href="/Venues/'.$venueUrl.'">'.$venueName.'</a></span>';
											}
											?>
										</div>
										
										<?php if(count($performerList) != 0){ ?>
										<div>Featuring: <?php
										for($pIdx = 0; $pIdx < count($performerList); $pIdx++){
											$pInfo = $performerList[$pIdx];
											if($pIdx != 0)
												echo ', ';
											echo '<a href="/Performers/' . $pInfo->PerformerFriendlyUrl . '">' . $pInfo->PerformerName . '</a>';
											if(strlen($pInfo->PerformerTitle) > 0){
												echo ' ('.$pInfo->PerformerTitle.')';
											}
										}?>
										</div>
										<?php }
										
										if(strlen($festivalName) > 0){
											echo '<div>Part of the <a href="/Festivals/'.$festivalUrl . '">'.$festivalName.'</a></div>';
										}
										?>
									
									</td>
								</tr>
							</tbody></table>
							
						</div><?php
					}
					if($finished === false){
						$showDateId = $row["ShowDateId"];
						
						$showDate = $row['ShowDate'];
						
						$showName = $row['ShowName'];
						$showUrl = $row['ShowUrlFriendlyName'];
						$showTime = TimeToString($row['ShowTime']);
						if(!is_null($row["VenueName"])){
							$venueName = $row["VenueName"];
							$venueUrl = $row["VenueUrlFriendlyName"];
						}else{
							$venueName = "";
						}
						if(!is_null($row["FestivalName"])){
							$festivalName = $row["FestivalName"];
							$festivalUrl = $row["FestivalUrlFriendlyName"];
						}else{
							$festivalName = "";
						}
						$performerList = array();
						if(!is_null($row["PerformerName"])){
							$perf = new PerformerData();
							$perf->PerformerName = $row["PerformerName"];
							$perf->PerformerFriendlyUrl = $row["PerformerUrlFriendlyName"];
							$perf->PerformerTitle = $row["Title"];
							$performerList[] = $perf;
						}
					}
				}else{
					$perf = new PerformerData();
					$perf->PerformerName = $row["PerformerName"];
					$perf->PerformerFriendlyUrl = $row["PerformerUrlFriendlyName"];
					$perf->PerformerTitle = $row["Title"];
					$performerList[] = $perf;
				}
			}
		}else{
			echo "<div>There are no shows coming up soon.</div>";
		}
		mysqli_free_result($result);
		
		mysqli_close($conn);
	}
}



function DL_ShowLoad($urlName, $forEdit){

	$conn = DBConnect();
	if($conn){

		global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisStatus, $thisFestivalId, $thisPriceRange;
		global $festivalName, $festivalUrl;
		global $dateList;
		global $performerList;
		global $venueDefault;
		global $hostList;
		global $goingStatus;
		
		$thisIsAdmin = false;
		
		$sql = "select distinct s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength, f.FestivalName, f.FestivalUrlFriendlyName, v.VenueName, v.VenueUrlFriendlyName";
		if(isset($_SESSION["mi"])){
			$sql = $sql . ",sa.MemberId";
		}
		
		$sql = $sql . " from evpshow s left outer join evpshowdate sd on s.ShowId = sd.ShowId"
			. " left outer join evpvenue v on sd.VenueId = v.VenueId"
			. " left outer join evpfestival f on s.FestivalId = f.FestivalId";
		
		if(isset($_SESSION["mi"])){
			$sql = $sql . " inner join evpshowadmin sa on s.ShowId = sa.AdminForId"
			. " left outer join evpshowdateperformer sdp on sd.ShowDateId = sdp.ShowDateId"
			. " left outer join evpperformermember pm on sdp.PerformerId = pm.PerformerId"
			. " left outer join evpperformeradmin pa on sdp.PerformerId = pa.AdminForId"
			. " left outer join evpvenueadmin va on sd.VenueId = va.AdminForId"
			. " left outer join evpshowhost sh on s.ShowId = sh.ShowId"
			. " left outer join evphostadmin ha on sh.HostId = ha.AdminForId"
			. " left outer join evpfestivaladmin fa on s.FestivalId = fa.AdminForId";
		}

		$sql = $sql . " where s.ShowUrlFriendlyName = " . ParseS($urlName) . " and s.CountryId = " . ParseS($_SESSION['loccountry'])
			. " and (s.ShowStatus = 1";
		if(isset($_SESSION["mi"])){	
			$sql = $sql . " or (s.ShowStatus = 0 and ("
			. " sa.MemberId = ". ParseS($_SESSION["mi"])
			. " or pm.MemberId = ". ParseS($_SESSION["mi"])
			. " or pa.MemberId = ". ParseS($_SESSION["mi"])
			. " or va.MemberId = ". ParseS($_SESSION["mi"])
			. " or ha.MemberId = ". ParseS($_SESSION["mi"])
			. " or fa.MemberId = ". ParseS($_SESSION["mi"])
			. " )))";
		}else{
			$sql = $sql . ")";
		}
		$sql = $sql . " order by sd.ShowDate, sd.ShowTime, sd.ShowDateId";

		$thisId = "";
		$prevId = "";
		$prevShowDateId = "";

		$result = mysqli_query($conn, $sql);
		if($result !== false){
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					$thisId = $row["ShowId"];

					if($thisId != $prevId){
						
						$prevId = $thisId; //should only go in here once
						
						$thisName = $row["ShowName"];
						$thisFriendly = $row["ShowUrlFriendlyName"];
						$thisDesc = $row["ShowDesc"];
						$thisStatus = $row["ShowStatus"];
						$thisFestivalId = $row["FestivalId"];
						$festivalName = $row["FestivalName"];
						$festivalUrl = $row["FestivalUrlFriendlyName"];
						$thisPriceRange = $row["PriceRange"];
					}
					if(!$thisIsAdmin && isset($_SESSION["mi"])){
						if( $row["MemberId"] == $_SESSION["mi"] ){
							$thisIsAdmin = true;
						}
					}

					if($prevShowDateId != $row["ShowDateId"]){
						$prevShowDateId = $row["ShowDateId"];
						$dateData = new DateData();
						$dateData->ShowDateId = $prevShowDateId;
						$dateData->ShowDate = $row["ShowDate"];
						$dateData->ShowTime = $row["ShowTime"];
						$dateData->ShowDateStatus = $row["ShowDateStatus"];
						$dateData->VenueId = $row["VenueId"];
						if($dateData->VenueId != ''){
							$dateData->VenueName = $row["VenueName"];
							$dateData->VenueUrlFriendlyName = $row["VenueUrlFriendlyName"];
						}else{
							$dateData->VenueName = '';
						}
						$dateData->VenueConfirmation = $row["VenueConfirmation"];
						$dateData->ShowLength = $row["ShowLength"];
						$dateData->GoingToStatus = 0;
						if(isset($_SESSION["mi"])){
							if($rgResult = mysqli_query($conn, "select GoingToStatus from evpgoingto where ShowDateId = ".ParseS($dateData->ShowDateId) . " and MemberId = ".ParseS($_SESSION["mi"]))){
								if($rgRow = mysqli_fetch_assoc($rgResult)){
									$dateData->GoingToStatus = $rgRow["GoingToStatus"];
								}
								mysqli_free_result($rgResult);
							}
						}
						
						$dateList[] = $dateData;
					}
				
					
				}
			}else{
				mysqli_free_result($result);
				mysqli_close($conn);
				header('Location:/Shows/'); //return to list if no info found.
			}
			mysqli_free_result($result);
			
			//get performers
			$sql = "select sdp.*, p.* from evpshowdate sd"
			. " inner join evpshowdateperformer sdp on sd.ShowDateId = sdp.ShowDateId"
			. " inner join evpperformer p on sdp.PerformerId = p.PerformerId"
			. " where sd.ShowId = " . ParseS($thisId);
			if($thisIsAdmin == false){
				$sql = $sql . " and ShowDateStatus = 1 and sdp.PerformerConfirmed <> 2 and p.PerformerStatus = 1";
			}
			$sql = $sql . " order by sdp.ShowDateId, sdp.DisplayOrder";

			$result = mysqli_query($conn, $sql);
			if($result !== false){
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						$performerData = new PerformerData();

						$performerData->ShowDatePerformerId = $row["ShowDatePerformerId"];
						$performerData->ShowDateId = $row["ShowDateId"];
						$performerData->PerformerId = $row["PerformerId"];
						$performerData->DisplayOrder = $row["DisplayOrder"];
						$performerData->PerformerTitle = $row["Title"];
						$performerData->PerformerConfirmed = $row["PerformerConfirmed"];
						$performerData->PerformanceLength = $row["PerformanceLength"];
						$performerData->PerformerName = $row["PerformerName"];
						$performerData->PerformerFriendlyUrl = $row["PerformerUrlFriendlyName"];
						$performerList[] = $performerData;
					}
				}
			}
			
			//get hosts
			$sql = "select h.* from evpshow s inner join evpshowhost sh on s.ShowId = sh.ShowId inner join evphost h on sh.HostId = h.HostId"
			. " where s.ShowId = " . ParseS($thisId);
			if($thisIsAdmin == false){
				$sql = $sql . " and HostStatus = 1";
			}

			$result = mysqli_query($conn, $sql);
			if($result !== false){
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						$hostItem = new HostData();
						$hostItem->HostId = $row["HostId"];
						$hostItem->HostName = $row["HostName"];
						$hostItem->HostUrlFriendlyName = $row["HostUrlFriendlyName"];
						$hostList[] = $hostItem;
					}
				}
			}
			
		}
		
		if($forEdit){
			DL_VenueDefault($conn);
		}
			
			
		
		mysqli_close($conn);

	}	
	
}


function DL_VenueDefault($conn){
	
	global $venueDefault;

	$killConnect = false;
	if($conn === false){
		$conn = DBConnect();
		$killConnect = true;
	}
	
	$sql = "select v.VenueId, v.VenueName from evpvenue v inner join evpvenueadmin va on v.VenueId = va.AdminForId";
	if(!isset($_SESSION["locarea"])){
		$sql = $sql . " inner join evplocationarea la on v.LocationAreaId = la.LocationAreaId";
		$sql = $sql . " where la.LocationRegionId = " . ParseS($_SESSION["locregion"]);
	}else{
		$sql = $sql . " where v.LocationAreaId =  " . ParseS($_SESSION["locarea"]);
	}
	$sql = $sql . " and va.MemberId = " . ParseS($_SESSION["mi"]) . " and v.IsDefault = 1";
//echo $sql;
	$result = mysqli_query($conn, $sql);
	if($result !== false){
		if($row = mysqli_fetch_assoc($result)) {
			$venueDefault[] = $row["VenueId"];
			$venueDefault[] = $row["VenueName"];
		}
		mysqli_free_result($result);
	}

	if($killConnect){
		mysqli_close($conn);
	}
	
	
}

function DL_ShowOpenAll($showId){
	$success = -1;
	
	$conn = DBConnect();
	
	//check member is admin
	$success = -2;
	
	$memberId = $_SESSION["mi"];
	$sql = "select count(AdminForId) as IsAdmin from evpshowadmin where AdminForId = " . ParseS($showId) . " and MemberId = " . ParseS($memberId);
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			if($row["IsAdmin"] == "1"){
				$success = 0;
			}
		}
	}
	mysqli_free_result($result);
		
	if($success == 0){
		$success = -3;
		$sql = "update evpshowdate set ShowDateStatus = 1 where ShowId = ". ParseS($showId) ." and ShowDateStatus = 0";

		if($conn->query($sql) === true){
			$success = -4;
			
			$sql = "update evpshow set ShowStatus = 1 where ShowId = ". ParseS($showId) ." and ShowStatus = 0";

			if($conn->query($sql) === true){
				$success = 0;
			}
				
			
		}
	}	
	
	mysqli_close($conn);

	return $success;
	
}



?>