<?php

$thisId = "";
$thisName = "";
$thisFriendly = "";
$thisStatus = 0;
$thisDesc = "";
$thisIsAdmin = false;


function DL_HostSave($hostId, $name, $thisFriendly, $status, $desc){
	
	$success = 0;
	
	if(strlen($hostId)==0){
		$hostId = getGUID();
		$saveType = "i"; //insert
	}else{
		$saveType = "u"; //update
	}
	
	$countryId = $_SESSION["loccountry"];
	$conn = DBConnect();
	if($conn){
		//double check url friendly name
		$extra = 1;
		$checkFriendly = $thisFriendly;
		$keepGoing = true;

		while($keepGoing){
			$sql = "select HostId from evphost where HostUrlFriendlyName = ". ParseS($checkFriendly)." and LocationCountryId = " . ParseS($countryId);
			if($saveType == "u"){
				$sql = $sql . " and HostId <> ".ParseS($hostId);
			}

			if($result = mysqli_query($conn, $sql)){
				if(mysqli_num_rows($result) == 0){
					$keepGoing = false;
				}else{
					$extra++;
					$checkFriendly = $thisFriendly . '-' . $extra;
				}
				mysqli_free_result($result);
			}else{
				$success = -98;
				break;
			}
		}
		
		if($success == 0){
			
			$thisFriendly = $checkFriendly;

			
			if($saveType == "i"){
				$sql = "insert into evphost (HostId,HostName,HostStatus,HostUrlFriendlyName,LocationCountryId,HostDesc) values "
				. "(" . ParseS($hostId) . "," . ParseS($name) . "," . $status . "," . ParseS($thisFriendly) . "," . ParseS($countryId) . "," . ParseS($desc) . ")";
			}else{
				$sql = "update evphost set HostName = " . ParseS($name) . ", HostStatus = " . $status . ",HostUrlFriendlyName = " . ParseS($thisFriendly) . ",HostDesc = " . ParseS($desc)
				." where HostId = " . ParseS($hostId);
			}

			if ($conn->query($sql) !== TRUE) {
				$success = -1;
			}
			
			if($success == 0 && $saveType == "i"){
				$sql = "insert into evphostadmin (AdminForId, MemberId) values (" . ParseS($hostId) . "," . ParseS($_SESSION["mi"]) . ")";
				if ($conn->query($sql) !== TRUE) {
					$success = -2;
				}
			}
		}
		mysqli_close($conn);
	}
	
	if($success == 0){
		unset($_SESSION["ddHost"]);
		return $thisFriendly;
	}else
		return $success;

	
}

function DL_HostInvolvedAndList(){
	
	$conn = DBConnect();
	if($conn){
		if(isset($_SESSION['mi'])){
			$sql = "select f.*, fa.* from evphost f inner join evphostadmin fa on f.HostId = fa.AdminForId inner join evplocationregion lr on f.LocationCountryId = lr.LocationCountryId";
			if(isset($_SESSION["locarea"])){
				$sql = $sql . " inner join evplocationarea la on lr.LocationRegionId = la.LocationRegionId where la.LocationAreaId = " . ParseS($_SESSION["locarea"]);
			}else{
				$sql = $sql . " where lr.LocationRegionId = " . ParseS($_SESSION["locregion"]);
			}
			$sql = $sql . " and fa.MemberId = " . ParseS($_SESSION["mi"]);
			$sql = $sql . " and f.HostStatus <> 2";
			
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				echo '<div class="involvedWithBox"><h3>Involved With</h3>';
				while($row = mysqli_fetch_assoc($result)) {
					echo '<div><a href="'. $row['HostUrlFriendlyName'] . '">'. $row['HostName'] . '</a>';
					if($row["HostStatus"] == 0)
						echo ' (Draft)';
					echo '</div>';
				}
				echo '</div>';
			}
			mysqli_free_result($result);

		} //isset session

		$sql = "select f.* from evphost f"
			. " where f.LocationCountryId = " . ParseS($_SESSION['loccountry'])
			. " and f.HostStatus = 1 order by HostName";
		
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			echo '<br />';
			while($row = mysqli_fetch_assoc($result)) {
				echo '<div><a href="' . $row['HostUrlFriendlyName'] .'">' . $row['HostName'] . '</a></div>';
			}
		}else{
			echo "<div>There are no Hosts.</div>";
		}
		mysqli_free_result($result);
		
		mysqli_close($conn);
	}
}






function DL_HostDetails($urlName){

	global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisStatus;

	DL_HostLoad($urlName);
		
	if(strlen($thisName) >  0){
		if($thisIsAdmin){?>
			<div class="editBar">
				<span>
					<a href="Edit/<?php echo $thisFriendly; ?>">Edit</a>
				</span>
				<span>
					<a href="Admin/<?php echo $thisFriendly; ?>">Admin</a>
				</span>
			</div>
		<?php				
		}
		?>
		<h2><?php 
			echo $thisName;
			if($thisStatus == 0)
				echo ' (Draft)';
		
		?></h2>
		<div style="margin-top:10px;">
			<div class="description-area">
				<div class="desc-tab">About</div>
				<div style="clear:left;"></div>
				<div class="desc-tab-bar"></div>
				<div class="desc-area"><?php
					echo $thisDesc;
				?></div>
			</div>
			<div style="clear:both;"></div>
		</div>
	<?php }
	
}


function DL_HostLoad($urlName){
	
	$conn = DBConnect();
	if($conn){

		global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisStatus;
		
		$thisIsAdmin = false;
	
		$sql = "select f.*, fa.* from evphost f"
			. " inner join evphostadmin fa on f.HostId = fa.AdminForId"
			. " where f.HostUrlFriendlyName = " . ParseS($urlName)
			. " and f.LocationCountryId = '" . $_SESSION['loccountry'] . "'"
			. " and (f.HostStatus = 1";
		if(isset($_SESSION["mi"])){
			$sql = $sql . " or (f.HostStatus = 0 and MemberId = ". ParseS($_SESSION["mi"]) .")";
		}
		$sql = $sql . ")";

		$thisId = "";
		$result = mysqli_query($conn, $sql);
		if($result !== false){
			if (mysqli_num_rows($result) > 0) {
				
				while($row = mysqli_fetch_assoc($result)) {
					$thisId = $row["HostId"];
					$thisName = $row["HostName"];
					$thisFriendly = $row["HostUrlFriendlyName"];
					$thisDesc = $row["HostDesc"];
					$thisStatus = $row["HostStatus"];

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