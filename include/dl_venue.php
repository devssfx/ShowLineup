<?php

$thisId = "";
$thisName = "";
$thisFriendly = "";
$thisStatus = 0;
$thisDesc = "";
$thisIsAdmin = false;
$thisAddress1 = "";
$thisAddress2 = "";
$thisSuburb = "";
$thisState = "";
$thisPostcode = "";
$thisIsDefault = false;
$thisLocationAreaId = "";

function DL_VenueSave($venueId, $name, $urlFriendlyName, $status, $desc, $address1, $address2, $suburb, $state, $postcode, $isDefault, $locationAreaId){
	$success = 0;
	
	$conn = DBConnect();
	if($conn){
		if(strlen($venueId)==0){
			$venueId = getGUID();
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
			$sql = "select v.VenueId from evpvenue v inner join evplocationarea la on v.LocationAreaId = la.LocationAreaId inner join evplocationregion lr on la.LocationRegionId = lr.LocationRegionId"
			. " where v.VenueName = ". ParseS($checkFriendly)." and lr.LocationCountryId = " . ParseS($countryId);
			if($saveType == "u"){
				$sql = $sql . " and v.VenueId <> ".ParseS($venueId);
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
				$sql = "insert into evpvenue (VenueId,VenueName,VenueStatus,VenueUrlFriendlyName,LocationAreaId,IsDefault,VenueDesc,Address1,Address2,AddressSuburb,AddressState,AddressPostcode)"
				. " values (" . ParseS($venueId) . "," . ParseS($name) . "," . $status . "," . ParseS($urlFriendlyName) . "," . ParseS($locationAreaId) . "," 
				. $isDefault . "," . ParseS($desc) . "," . ParseS($address1) . "," . ParseS($address2) . "," . ParseS($suburb) . "," . ParseS($state) . "," . ParseS($postcode) . ")";
			}else{
				$sql = "update evpvenue set VenueName = " . ParseS($name) . ",VenueStatus = " . $status . ",VenueUrlFriendlyName = " . ParseS($urlFriendlyName) 
				. ",LocationAreaId = " . ParseS($locationAreaId) 
				. ",IsDefault = " . $isDefault
				. ",VenueDesc = " . ParseS($desc)
				. ",Address1 = " . ParseS($address1)
				. ",Address2 = " . ParseS($address2)
				. ",AddressSuburb = " . ParseS($suburb)
				. ",AddressState = " .  ParseS($state)
				. ",AddressPostcode = " . ParseS($postcode)
				. " where VenueId = " . ParseS($venueId);
			}

			if ($conn->query($sql) !== TRUE) {
				$success = -1;
			}
			
			if($success == 0 && $saveType == "i"){
				$sql = "insert into evpvenueadmin (AdminForId, MemberId) values (" . ParseS($venueId) . "," . ParseS($_SESSION["mi"]) . ")";
				if ($conn->query($sql) !== TRUE) {
					$success = -2;
				}
			}
			
		}
		mysqli_close($conn);
	}
		
	
	if($success == 0){
		unset($_SESSION["ddVenue"]);
		return $urlFriendlyName;
	}else
		return $success;
	
	
}

function DL_VenueInvolvedAndList(){
	
	$conn = DBConnect();
	if($conn){
		if(isset($_SESSION['mi'])){
			$sql = "select f.*, fa.MemberId from evpvenue f inner join evpvenueadmin fa on f.VenueId = fa.AdminForId"
				. " inner join evplocationarea la on f.LocationAreaId = la.LocationAreaId inner join evplocationregion lr on la.LocationRegionId = lr.LocationRegionId"
				. " where lr.LocationCountryId = " . ParseS($_SESSION["loccountry"]) . " and fa.MemberId = " . ParseS($_SESSION["mi"]) . " and f.VenueStatus <> 2";

			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				echo '<div class="involvedWithBox"><h3>Involved With</h3>';
				while($row = mysqli_fetch_assoc($result)) {
					echo '<div><a href="'. $row['VenueUrlFriendlyName'] . '">'. $row['VenueName'] . '</a>';
					if($row["VenueStatus"] == 0)
						echo ' (Draft)';
					echo '</div>';
				}
				echo '</div>';
			}
			mysqli_free_result($result);

		} //isset session

		
		$sql = "select v.* from evpvenue v";
		if(!isset($_SESSION["locarea"])){
			$sql = $sql . " inner join evplocationarea la on v.LocationAreaId = la.LocationAreaId"
				. " where v.VenueStatus = 1 and la.LocationRegionId = " . ParseS($_SESSION["locregion"]);
		}else{
			$sql = $sql . " where v.VenueStatus = 1 and LocationAreaId = " . ParseS($_SESSION["locarea"]);
		}
		$sql = $sql . " order by v.VenueName";

		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			echo '<br />';
			while($row = mysqli_fetch_assoc($result)) {
				echo '<div><a href="' . $row['VenueUrlFriendlyName'] .'">' . $row['VenueName'] . '</a></div>';
			}
		}else{
			echo "<div>There are no Venues in this area.</div>";
		}
		mysqli_free_result($result);
		
		mysqli_close($conn);
	}
}






function DL_VenueDetails($urlName){

	global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisStatus;
	global $thisAddress1,$thisAddress2,$thisSuburb,$thisState,$thisPostcode;
	//global $thisIsDefault,$thisLocationAreaId;

	DL_VenueLoad($urlName);

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
		<div>
			<?php
			$addrSomething = false;
			
			if(strlen($thisAddress1) > 0){
//				if($addrSomething)
//					echo ",</br>";
				echo $thisAddress1;
				$addrSomething = true;
			}
			if(strlen($thisAddress2) > 0){
				if($addrSomething)
					echo ",</br>";
				echo $thisAddress2;
				$addrSomething = true;
			}
			if(strlen($thisSuburb) > 0){
				if($addrSomething)
					echo ",</br>";
				echo $thisSuburb;
				$addrSomething = true;
			}
			if(strlen($thisState) > 0){
				if($addrSomething)
					echo ",</br>";
				echo $thisState;
				$addrSomething = true;
			}
			if(strlen($thisPostcode) > 0){
				if($addrSomething)
					echo ",</br>";
				echo $thisPostcode;
				$addrSomething = true;
			}
			?>
		</div>
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




function DL_VenueLoad($urlName){
	
	$conn = DBConnect();
	if($conn){

		global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisStatus, $thisAddress1, $thisAddress2, $thisSuburb, $thisState, $thisPostcode, $thisIsDefault, $thisLocationAreaId;
		
		$thisIsAdmin = false;
	
		$sql = "select f.*, fa.MemberId from evpvenue f inner join evpvenueadmin fa on f.VenueId = fa.AdminForId inner join evplocationarea la on f.LocationAreaId = la.LocationAreaId"
			. " inner join evplocationregion lr on la.LocationRegionId = lr.LocationRegionId"
			. " where f.VenueUrlFriendlyName = " . ParseS($urlName) . " and lr.LocationCountryId = " . ParseS($_SESSION["loccountry"])
			. " and (f.VenueStatus = 1";
		if(isset($_SESSION["mi"])){
			$sql = $sql . " or fa.MemberId = " . ParseS($_SESSION["mi"]);
		}
		$sql = $sql . ")";

		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			
			while($row = mysqli_fetch_assoc($result)) {
				$thisId = $row["VenueId"];
				$thisName = $row["VenueName"];
				$thisFriendly = $row["VenueUrlFriendlyName"];
				$thisDesc = $row["VenueDesc"];
				$thisStatus = $row["VenueStatus"];
				$thisAddress1 = $row["Address1"];
				$thisAddress2 = $row["Address2"];
				$thisSuburb = $row["AddressSuburb"];
				$thisState = $row["AddressState"];
				$thisPostcode = $row["AddressPostcode"];
				$thisIsDefault = $row["IsDefault"];
				$thisLocationAreaId = $row["LocationAreaId"];
				
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
		
		mysqli_close($conn);

	}	
	
}

?>