<?php
session_start();

$success = -1;
$memberId = $_SESSION["mi"];


include "../include/db.php";


if(isset($_POST["ShowId"])){
	
	$showId = $_POST["ShowId"];
	$showDateId = $_POST["ShowDateId"];

	$sdpId = $_POST["sdpId"];
	$sdpTitle = $_POST["sdpTitle"];
	$sdpLength = $_POST["sdpLength"];
	$sdpConfirmed = $_POST["sdpConfirmed"];
	$sdpPerfId = $_POST["sdpPerfId"];

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
			
			//get max order
			$order = 1;
			$sql = "select max(DisplayOrder) as DisMax from evpshowdateperformer where ShowDateId = " . ParseS($showDateId);

			$result = mysqli_query($conn, $sql);
			if(mysqli_num_rows($result) > 0){
				if($row = mysqli_fetch_assoc($result)) {
					if(!is_null($row["DisMax"])){
						$order = (int)$row["DisMax"];
						$order = $order + 1;
					}
				}
			}

			
			//save
			if(strlen($sdpId) == 0){ //insert
				$sdpId = getGUID();
				$sql = "insert into evpshowdateperformer (ShowDatePerformerId, ShowDateId, PerformerId, DisplayOrder, Title, PerformerConfirmed, PerformanceLength) values "
					. " (". ParseS($sdpId).", ". ParseS($showDateId).", ". ParseS($sdpPerfId).", ". $order.", ". ParseS($sdpTitle).", ". $sdpConfirmed.",". ParseS($sdpLength).")";
			}else{
				$sql = "update evpshowdateperformer set Title = ". ParseS($sdpTitle).", PerformerConfirmed = ". $sdpConfirmed.", PerformanceLength = ". ParseS($sdpLength)
					. " where ShowDatePerformerId = ". ParseS($sdpId);
			}
			
			if ($conn->query($sql) === TRUE) {
				$success = $sdpId . '';
			}

		}

		
		
		
		mysqli_close($conn);
	}

}



echo $success;


?>