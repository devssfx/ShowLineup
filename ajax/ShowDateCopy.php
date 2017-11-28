<?php
session_start();

$success = -1;
$rtn = "";
$memberId = $_SESSION["mi"];


include "../include/db.php";


if(isset($_POST["ShowId"])){
	
	$showId = $_POST["ShowId"];
	$showDateId = $_POST["ShowDateId"];

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
			
			//copy date
			$showDateIdNew = getGUID();
			$sql = "insert into evpshowdate"
				. " select " . ParseS($showDateIdNew) . " as ShowDateId, ShowId, ShowDate, ShowTime, ShowDateStatus, VenueId, VenueConfirmation, ShowLength from evpshowdate"
				. " where ShowDateId = " . ParseS($showDateId);

			if ($conn->query($sql) === TRUE) {
				$success = -4;
				$rtn = $showDateIdNew;
				
				$wasError = false;
				//get each performer id and copy records
				$sql = "select * from evpshowdateperformer where ShowDateId = " . ParseS($showDateId);

				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						$sdpIdOld = $row["ShowDatePerformerId"];
						$sdpIdNew = getGUID();
						
						$sql = "insert into evpshowdateperformer"
							. " select " . ParseS($sdpIdNew) . " as ShowDatePerformerId, " . ParseS($showDateIdNew) . " as ShowDateId, PerformerId, DisplayOrder, Title, 0 as PerformerConfirmed, PerformanceLength from evpshowdateperformer"
							. " where ShowDatePerformerId = " . ParseS($sdpIdOld);
						if ($conn->query($sql) === TRUE) {
							$rtn = $rtn . "," . $sdpIdOld . "," . $sdpIdNew;
						}else{
							$wasError = true;
						}
					}
				}
				mysqli_free_result($result);
				
				if($wasError === false){
					$success = 0;
				}
				
			}

		}
		
		mysqli_close($conn);
	}

}

if($success == 0)
	echo $rtn;
else
	echo $success;


?>