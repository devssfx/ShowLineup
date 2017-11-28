<?php
session_start();

$success = -1;
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
			
			//remove any performers
			$sql = "delete from evpshowdateperformer where ShowDateId = " . ParseS($showDateId);
			if ($conn->query($sql) === TRUE) {
				$success = -4;
				$sql = "delete from evpshowdate where ShowDateId = " . ParseS($showDateId);

				if ($conn->query($sql) === TRUE) {
					$success = 0;
				}
			}

		}
		
		mysqli_close($conn);
	}

}



echo $success;


?>