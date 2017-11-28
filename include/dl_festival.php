<?php

$thisId = "";
$thisName = "";
$thisFriendly = "";
$thisStatus = 0;
$thisDesc = "";
$thisIsAdmin = false;
$dateOpen = "";
$dateClose = "";

//global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisDateOpen, $thisDateClose, $thisStatus;

function DL_FestivalSave($festivalId, $name, $urlFriendlyName, $status, $dateOpen, $dateClose, $desc){
	$success = 0;
	
	$conn = DBConnect();
	if($conn){

		if(strlen($festivalId)==0){
			$festivalId = getGUID();
			$saveType = "i"; //insert
		}else{
			$saveType = "u"; //update
		}
		
		$countryId = $_SESSION["loccountry"];

		//double check url friendly name
		$extra = 1;
		$checkFriendly = $urlFriendlyName;
		$keepGoing = true;

		while($keepGoing){
			$sql = "select * from evpfestival where FestivalUrlFriendlyName = ". ParseS($checkFriendly)." and LocationCountryId = " . ParseS($countryId);
			if($saveType == "u"){
				$sql = $sql . " and FestivalId <> ".ParseS($festivalId);
			}
			if($result = mysqli_query($conn, $sql)){
				if(mysqli_num_rows($result) == 0){
					$keepGoing = false;
				}else{
					$extra++;
					$checkFriendly = $urlFriendlyName . '-' . $extra;
				}
				mysqli_free_result($result);
			}else{
				$success = -98;
				break;
			}
		}
		if($success == 0){
			$urlFriendlyName = $checkFriendly;

			if($saveType == "i"){
				$sql = "insert into evpfestival (FestivalId,FestivalName,FestivalStatus,FestivalUrlFriendlyName,FestivalDateOpen,FestivalDateClose,LocationCountryId,FestivalDesc)"
				. " values (" . ParseS($festivalId) . "," . ParseS($name) . "," . $status . "," . ParseS($urlFriendlyName) . "," . $dateOpen . "," . $dateClose . "," . ParseS($countryId) . "," . ParseS($desc) . ")";
			}else{
				$sql = "update evpfestival set FestivalName = " . ParseS($name) . ",FestivalStatus = " . $status . ",FestivalUrlFriendlyName = " . ParseS($urlFriendlyName) 
				. ",FestivalDateOpen = " . $dateOpen . ",FestivalDateClose = " . $dateClose . ",LocationCountryId = " . ParseS($countryId) . ",FestivalDesc = " . ParseS($desc)
				. " where FestivalId = " . ParseS($festivalId);
			}

			if ($conn->query($sql) !== TRUE) {
				$success = -1;
			}
			
			if($success == 0 && $saveType == "i"){
				$sql = "insert into evpfestivaladmin (AdminForId, MemberId) values (" . ParseS($festivalId) . "," . ParseS($_SESSION["mi"]) . ")";
				if ($conn->query($sql) !== TRUE) {
					$success = -2;
				}
			}
			
		}
		mysqli_close($conn);
	}
	if($success == 0){
		unset($_SESSION["ddFestival"]);
		return $urlFriendlyName;
	}else
		return $success;

	
}

function DL_FestivalInvolvedAndList(){
	
	$conn = DBConnect();
	if($conn){
		if(isset($_SESSION['mi'])){
			$sql = "select f.*, fa.* from evpfestival f inner join evpfestivaladmin fa on f.FestivalId = fa.AdminForId inner join evplocationregion lr on f.LocationCountryId = lr.LocationCountryId";
			if(isset($_SESSION["locarea"])){
				$sql = $sql . " inner join evplocationarea la on lr.LocationRegionId = la.LocationRegionId where la.LocationAreaId = " . ParseS($_SESSION["locarea"]);
			}else{
				$sql = $sql . " where lr.LocationRegionId = " . ParseS($_SESSION["locregion"]);
			}
			$sql = $sql . " and fa.MemberId = " . ParseS($_SESSION["mi"]);
			$sql = $sql . " and f.FestivalStatus <> 2";
			
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				echo '<div class="involvedWithBox"><h3>Involved With</h3>';
				while($row = mysqli_fetch_assoc($result)) {
					echo '<div><a href="'. $row['FestivalUrlFriendlyName'] . '">'. $row['FestivalName'] . '</a>';
					if($row["FestivalStatus"] == 0)
						echo ' (Draft)';
					echo '</div>';
				}
				echo '</div>';
			}
			mysqli_free_result($result);

		} //isset session

		$dateOpen = new DateTime();
		$dateClose = new DateTime();
		$dateClose->modify('+3 months');
		//date_add($dateClose, date_interval_create_from_date_string('3 months'));
		
		$sql = "select f.* from evpfestival f"
			. " where f.LocationCountryId = " . ParseS($_SESSION['loccountry'])
			. " and f.FestivalStatus = 1"
			. " and f.FestivalDateOpen < " . date_format($dateClose, 'Ymd') . " and f.FestivalDateClose >= " . date_format($dateOpen, 'Ymd')
			. " order by FestivalDateOpen, FestivalDateClose";
		
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			echo '<br />';
			while($row = mysqli_fetch_assoc($result)) {
				$dateString = DateStringFromTo($row['FestivalDateOpen'], $row['FestivalDateClose']);
				echo '<div><a href="' . $row['FestivalUrlFriendlyName'] .'">' . $row['FestivalName'] . '</a> from ' . $dateString . '</div>';
			}
		}else{
			echo "<div>There are no festivals coming up soon.</div>";
		}
		mysqli_free_result($result);
		
		mysqli_close($conn);
	}
}






function DL_FestivalLoad($urlName){
	
	$conn = DBConnect();
	if($conn){

		global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisDateOpen, $thisDateClose, $thisStatus;
		
		$thisIsAdmin = false;
	
		$sql = "select f.*, fa.* from evpfestival f"
			. " inner join evpfestivaladmin fa on f.FestivalId = fa.AdminForId"
			. " where f.FestivalUrlFriendlyName = " . ParseS($urlName)
			. " and f.LocationCountryId = '" . $_SESSION['loccountry'] . "'"
			. " and (f.FestivalStatus = 1";
		if(isset($_SESSION["mi"])){
			$sql = $sql . " or (f.FestivalStatus = 0 and MemberId = ". ParseS($_SESSION["mi"]) .")";
		}
		$sql = $sql . ")";

		$thisId = "";
		$result = mysqli_query($conn, $sql);
		if($result !== false){
			if (mysqli_num_rows($result) > 0) {
				
				while($row = mysqli_fetch_assoc($result)) {
					$thisId = $row["FestivalId"];
					$thisName = $row["FestivalName"];
					$thisFriendly = $row["FestivalUrlFriendlyName"];
					$thisDesc = $row["FestivalDesc"];
					$thisDateOpen = $row["FestivalDateOpen"];
					$thisDateClose = $row["FestivalDateClose"];
					$thisStatus = $row["FestivalStatus"];

					if(isset($_SESSION["mi"])){
						if( $row["MemberId"] == $_SESSION["mi"] ){
							$thisIsAdmin = true;
							break;
						}
					}else{
						break;
					}
				}
			}
			mysqli_free_result($result);
		}
		mysqli_close($conn);
		
		if(strlen($thisId) == 0){
			//Header("Location:/");
		}

	}	
	
}

?>