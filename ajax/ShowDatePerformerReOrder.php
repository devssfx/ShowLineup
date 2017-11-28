<?php
session_start();

$success = -1;
$memberId = $_SESSION["mi"];


include "../include/db.php";


if(isset($_POST["ShowId"])){
	
	$showId = $_POST["ShowId"];
	$sdpId = $_POST["SDPId"];
	$dir = $_POST["Dir"];

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
			
			$order2 = 1;
			$sdpId2 = "";
			
			//get current order
			$sql = "select * from evpshowdateperformer where ShowDatePerformerId = " . ParseS($sdpId);
			
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				if($row = mysqli_fetch_assoc($result)) {
					$order = $row["DisplayOrder"];
					$showDateId = $row["ShowDateId"];
					
					$sql = "select * from evpshowdateperformer where ShowDateId = " . ParseS($showDateId);
					if($dir == 1){
						$sql = $sql . " and DisplayOrder > " . $order . " order by DisplayOrder";
					}else{
						$sql = $sql . " and DisplayOrder < " . $order . " order by DisplayOrder desc";
					}
					$sql = $sql . " limit 1";
					
					$result2 = mysqli_query($conn, $sql);
					if(mysqli_num_rows($result2) != 0){
						if($row2 = mysqli_fetch_assoc($result2)) {
							
							$order2 = $row2["DisplayOrder"];
							$sdpId2 = $row2["ShowDatePerformerId"];
							
						}
					}
					mysqli_free_result($result2);
				}
			}
			mysqli_free_result($result);
			
			if($sdpId2 != ""){
				$sql = "update evpshowdateperformer set DisplayOrder = " . $order2 . " where ShowDatePerformerId = " . ParseS($sdpId);
				
				if ($conn->query($sql) === TRUE) {
					
					$sql = "update evpshowdateperformer set DisplayOrder = " . $order . " where ShowDatePerformerId = " . ParseS($sdpId2);

					if ($conn->query($sql) === TRUE) {

						$success = $sdpId2; //return the swapped id once successful
					}

				}
			}

		}
		
		mysqli_close($conn);
	}

}



echo $success;


?>