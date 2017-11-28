[
<?php

include "../include/db.php";


if(isset($_GET["la"]) && isset($_GET["s"])){

	$area = $_GET["la"];
	$search = $_GET["s"];

	$search = "%" . $search . "%";

	$conn = DBConnect();
	if($conn){
		$isFirst = true;
		
		$sql = "select VenueId, VenueName from evpvenue where VenueStatus = 1 and LocationAreaId = ".ParseS($area)." and VenueName like " . ParseS($search);

		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				if(!$isFirst)
					echo ",";
				else
					$isFirst = false;
				
				echo '{"VenueId":"'.$row["VenueId"].'","VenueName":"'.$row["VenueName"].'"}';
			}
		}
		mysqli_free_result($result);
		
		
		mysqli_close($conn);
	}

}



?>
]