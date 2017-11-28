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
		
		$sql = "select h.HostId, h.HostName from evphost h inner join evplocationregion lr on h.LocationCountryId = lr.LocationCountryId inner join evplocationarea la on lr.LocationRegionId = la.LocationRegionId"
			. " where h.HostStatus = 1 and la.LocationAreaId = ".ParseS($area)." and h.HostName like " . ParseS($search);

		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				if(!$isFirst)
					echo ",";
				else
					$isFirst = false;
				
				echo '{"HostId":"'.$row["HostId"].'","HostName":"'.$row["HostName"].'"}';
			}
		}
		mysqli_free_result($result);
		
		
		mysqli_close($conn);
	}

}



?>
]