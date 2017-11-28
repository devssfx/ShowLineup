<?php

$thisId = "";
$thisName = "";
$thisFriendly = "";
$thisStatus = 0;
$thisDesc = "";
$thisIsAdmin = false;
$thisLocationList = array();

function DL_PerformerSave($PerformerId, $name, $urlFriendlyName, $status, $desc, $thisLocationList){
	$success = 0;
	
	$conn = DBConnect();
	if($conn){
		if(strlen($PerformerId)==0){
			$PeformerId = getGUID();
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
			$sql = "select p.PerformerId from evpperformer p inner join evpperformerlocation pl on p.PerformerId = pl.PerformerId inner join evplocationarea la on pl.LocationAreaId = la.LocationAreaId inner join evplocationregion lr on la.LocationRegionId = lr.LocationRegionId"
			. " where p.PerformerName = ". ParseS($checkFriendly)." and lr.LocationCountryId = " . ParseS($countryId);
			if($saveType == "u"){
				$sql = $sql . " and p.PerformerId <> ".ParseS($PerformerId);
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
			
			if(strlen($PerformerId)==0){
				$PerformerId = getGUID();
				$saveType = "i"; //insert
			}else{
				$saveType = "u"; //update
			}

			if($saveType == "i"){
				$sql = "insert into evpperformer (PerformerId,PerformerName,PerformerStatus,PerformerUrlFriendlyName,PerformerDesc)"
				. " values (" . ParseS($PerformerId) . "," . ParseS($name) . "," . $status . "," . ParseS($urlFriendlyName) . "," . ParseS($desc) . ")";
			}else{
				$sql = "update evpperformer set PerformerName = " . ParseS($name) . ",PerformerStatus = " . $status . ",PerformerUrlFriendlyName = " . ParseS($urlFriendlyName) 
				. ",PerformerDesc = " . ParseS($desc)
				. " where PerformerId = " . ParseS($PerformerId);
			}

			if ($conn->query($sql) !== TRUE) {
				$success = -1;
			}
			
			if($success == 0 && $saveType == "i"){
				$sql = "insert into evpperformeradmin (AdminForId, MemberId) values (" . ParseS($PerformerId) . "," . ParseS($_SESSION["mi"]) . ")";
				if ($conn->query($sql) !== TRUE) {
					$success = -2;
				}
			}
			if($success == 0 && $saveType == "i"){
				$sql = "insert into evpperformermember (PerformerId, MemberId) values (" . ParseS($PerformerId) . "," . ParseS($_SESSION["mi"]) . ")";
				if ($conn->query($sql) !== TRUE) {
					$success = -3;
				}
			}

			if($success == 0){
				$sql = "delete from evpperformerlocation where PerformerId = " . ParseS($PerformerId);

				if ($conn->query($sql) === TRUE) {
					if(count($thisLocationList) != 0){
						foreach($thisLocationList as $loc){
							$sql = "insert into evpperformerlocation (PerformerId, LocationAreaId) values (". ParseS($PerformerId) .", ". ParseS($loc) . ");";

							if ($conn->query($sql) === FALSE) {
								$success = -4;
								break;
							}
						}
					}
				}
				
			}
		}
		mysqli_close($conn);
	}
	
	if($success == 0){
		unset($_SESSION["ddPerformer"]);
		return $urlFriendlyName;
	}else
		return $success;
	
	
}

function DL_PerformerInvolvedAndList(){
	
	$conn = DBConnect();
	if($conn){
		if(isset($_SESSION['mi'])){
			$sql = "select distinct p.PerformerName, p.PerformerUrlFriendlyName, p.PerformerStatus from evpperformer p inner join evpperformeradmin pa on p.PerformerId = pa.AdminForId"
			. " inner join evpperformermember pm on p.PerformerId = pm.PerformerId"
			. "	where p.PerformerStatus <> 2 and (pa.MemberId = " . ParseS($_SESSION["mi"]) . " or pm.MemberId = " . ParseS($_SESSION["mi"]) . ")";

			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				echo '<div class="involvedWithBox"><h3>Involved With</h3>';
				while($row = mysqli_fetch_assoc($result)) {
					echo '<div><a href="'. $row['PerformerUrlFriendlyName'] . '">'. $row['PerformerName'] . '</a>';
					if($row["PerformerStatus"] == 0)
						echo ' (Draft)';
					echo '</div>';
				}
				echo '</div>';
			}
			mysqli_free_result($result);

		} //isset session

		
		$sql = "select p.* from evpperformer p"
			." inner join evpperformerlocation pl on p.PerformerId = pl.PerformerId";
			
		if(isset($_SESSION["locarea"])){
			$sql = $sql . " where p.PerformerStatus = 1 and pl.LocationAreaId = " . ParseS($_SESSION["locarea"]);
		}else{
			$sql = "inner join evplocationarea la on pl.LocationAreaId = la.LocationAreaId"
				." inner join evplocationregion lr on la.LocationRegionId = lr.LocationRegionId"
				." where p.PerformerStatus = 0 and lr.LocationRegionId = " . ParseS($_SESSION["locregion"]);
		}
		$sql = $sql . " order by p.PerformerName";

		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			echo '<br />';
			while($row = mysqli_fetch_assoc($result)) {
				echo '<div><a href="' . $row['PerformerUrlFriendlyName'] .'">' . $row['PerformerName'] . '</a></div>';
			}
		}else{
			echo "<div>There are no Performers in this area.</div>";
		}
		mysqli_free_result($result);
		
		mysqli_close($conn);
	}
}




function DL_PerformerLoad($urlName){
	
	$conn = DBConnect();
	if($conn){

		global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisStatus, $thisLocationList;
		
		$thisIsAdmin = false;
	
		$sql = "select p.*, pa.MemberId from evpperformer p inner join evpperformeradmin pa on p.PerformerId = pa.AdminForId"
		. " inner join evpperformermember pm on p.PerformerId = pm.PerformerId"
		. " inner join evpperformerlocation pl on p.PerformerId = pl.PerformerId"		
		. " inner join evplocationarea la on pl.LocationAreaId = la.LocationAreaId inner join evplocationregion lr on la.LocationRegionId = lr.LocationRegionId"
		. " where p.PerformerUrlFriendlyName = " . ParseS($urlName)
		. " and lr.LocationCountryId = " . ParseS($_SESSION["loccountry"])
		. " and (p.PerformerStatus = 1";
		if(isset($_SESSION["mi"])){
			$sql = $sql . "  or pa.MemberId = " . ParseS($_SESSION["mi"]) . " or pm.MemberId = " . ParseS($_SESSION["mi"]);
		}
		$sql = $sql . ")";
//echo $sql;
		$thisId = "";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$thisId = $row["PerformerId"];
				$thisName = $row["PerformerName"];
				$thisFriendly = $row["PerformerUrlFriendlyName"];
				$thisDesc = $row["PerformerDesc"];
				$thisStatus = $row["PerformerStatus"];
				
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
		
		
		if(strlen($thisId) != 0){
			$sql = "select * from evpperformerlocation where PerformerId = " . ParseS($thisId);
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					$thisLocationList[] = $row["LocationAreaId"];
				}
			}
			mysqli_free_result($result);
		}
		
		
		mysqli_close($conn);

	}	
	
}

?>