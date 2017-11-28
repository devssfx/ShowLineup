<?php

function DL_ListInfo(){
	
	$conn = DBConnect();
	if($conn){

		//list any coming festivals
		$dateOpen = new DateTime();
		$dateClose = new DateTime();
		$dateClose->modify('+3 month');

		
		$sql = "select * from evpfestival where LocationCountryId = " . ParseS($_SESSION["loccountry"]) ." and FestivalStatus = 1 and ("
		. " (FestivalDateOpen >= ". date_format($dateOpen, 'Ymd') ." and FestivalDateOpen < ". date_format($dateClose, 'Ymd') .")"
		. " or (FestivalDateClose >= ". date_format($dateOpen, 'Ymd') ." and FestivalDateClose < ". date_format($dateClose, 'Ymd') ."))"
		. " order by FestivalDateOpen, FestivalDateClose";

		if($result = mysqli_query($conn, $sql)){
			if (mysqli_num_rows($result) > 0) {
				echo "<div><h3>Festivals:</h3>";
				while($row = mysqli_fetch_assoc($result)) {
					echo '<div><a href="/Festivals/' . $row['FestivalUrlFriendlyName'] .'">' . $row['FestivalName'] . '</a> from '.DateStringFromTo($row['FestivalDateOpen'], $row['FestivalDateClose']).'</div>';
				}
				echo "</div>";
			}
			mysqli_free_result($result);
		}
		
		mysqli_close($conn);
	}
}







?>