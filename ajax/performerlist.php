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
		
		$sql = "select p.* from evpperformer p inner join evpperformerlocation pl on p.PerformerId = pl.PerformerId where pl.LocationAreaId = ".ParseS($area)." and p.PerformerStatus = 1"
			. " and p.PerformerName like " . ParseS($search);

		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				if(!$isFirst)
					echo ",";
				else
					$isFirst = false;
				
				echo '{"PerformerId":"'.$row["PerformerId"].'","PerformerName":"'.$row["PerformerName"].'"}';
			}
		}
		mysqli_free_result($result);
		
		
		mysqli_close($conn);
	}

}



?>
]