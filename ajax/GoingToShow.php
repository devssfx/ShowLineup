<?php
session_start();

$success = -1;
$memberId = $_SESSION["mi"];


include "../include/db.php";


if(isset($_POST["ShowDateId"])){
	
	$showDateId = $_POST["ShowDateId"];
	$status = $_POST["Status"]; //0=not going, 1=going, 2=?
	

	$conn = DBConnect();
	if($conn){
		if($status == 0){
			$sql = "delete from evpgoingto where ShowDateId = " . ParseS($showDateId) . " and MemberId = " . ParseS($memberId);
		}else{
				
			//check member is admin
			$found = false;
			$sql = "select ShowDateId from evpgoingto where ShowDateId = " . ParseS($showDateId) . " and MemberId = " . ParseS($memberId);
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					if($row["ShowDateId"] == $showDateId){
						$found = true;
					}
				}
			}
			mysqli_free_result($result);
			
			if($found){ //update
				$sql = "update evpgoingto set GoingToStatus = ". $status
					. " where ShowDateId = " . ParseS($showDateId) . " and MemberId = " . ParseS($memberId);
			}else{
				$sql = "insert into evpgoingto (ShowDateId, MemberId, GoingToStatus) values "
					. " (". ParseS($showDateId).", ". ParseS($memberId).", ". $status . ")";
			}
		}
		
		if ($conn->query($sql) === TRUE) {
			$success = 0;
		}
		
		mysqli_close($conn);
	}

}



echo $success;


?>