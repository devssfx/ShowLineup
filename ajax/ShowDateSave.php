<?php
session_start();

$success = -1;
$memberId = $_SESSION["mi"];


include "../include/db.php";


if(isset($_POST["ShowId"])){
	
	$showId = $_POST["ShowId"];
	$showDateId = $_POST["ShowDateId"];
	$showDate = $_POST["ShowDate"];
	$showTime = $_POST["ShowTime"];
	$dateLength = $_POST["DateLength"];
	$dateStatus = $_POST["DateStatus"];
	$dateVenue = $_POST["DateVenue"];

	$conn = DBConnect();
	if($conn){
		//check member is admin
		$success = -2;
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
				
				//save
				if(strlen($showDateId) == 0){ //insert
					$showDateId = getGUID();
					$sql = "insert into evpshowdate (ShowDateId, ShowId, ShowDate, ShowTime, ShowDateStatus, VenueId, VenueConfirmation, ShowLength) values "
						. " (". ParseS($showDateId).", ". ParseS($showId).", ". $showDate.", ". $showTime.", ". $dateStatus.", ". ParseS($dateVenue).", 0, ". ParseS($dateLength).")";
				}else{
					$sql = "update evpshowdate set ShowDate = ". $showDate.", ShowTime = ". $showTime.", ShowDateStatus = ". $dateStatus.", VenueId = ". ParseS($dateVenue).", VenueConfirmation = 0, ShowLength = ". ParseS($dateLength)
						. " where ShowDateId = " . ParseS($showDateId);
				}
				//echo $sql;
				if ($conn->query($sql) === TRUE) {
					$success = $showDateId;
				}

			}
//		}
		
		
		
		mysqli_close($conn);
	}

}



echo $success;


?>