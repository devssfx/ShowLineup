<?php

$pageTitle = "Location Selection";
include "include/db.php";
include "include/header.php";
include "include/dl_index.php";


if(isset($_GET["c"]) && isset($_GET["r"])){
	$conn = DBConnect();
	if($conn){

		$c = $_GET["c"];
		$r = $_GET["r"];
		$a = "";
		if(isset($_GET["a"])){
			$a = $_GET["a"];
		}
		
		$sql = "select lc.LocationCountryId, lr.LocationRegionId, la.LocationAreaId from evplocationcountry lc inner join evplocationregion lr on lc.LocationCountryId = lr.LocationCountryId"
		. " inner join evplocationarea la on lr.LocationRegionId = la.LocationRegionId where lc.LocationCountryCode = ".ParseS($c) . " and lr.LocationRegionCode=". ParseS($r);
		if($a != ""){
			$sql = $sql . " and la.LocationAreaCode = " . ParseS($a);
		}
		
		$result = mysqli_query($conn, $sql);
		if($result !== false){
			//if (mysqli_num_rows($result) == 1) {
				if($row = mysqli_fetch_assoc($result)) {
					$_SESSION['loccountry'] = $row["LocationCountryId"];
					$_SESSION['locregion'] = $row["LocationRegionId"];
					setcookie('loccountry',$row["LocationCountryId"],time() + (86400 * 7), '/');
					setcookie('locregion',$row["LocationRegionId"],time() + (86400 * 7), '/');
					
					if($a == ""){
						unset($_SESSION['locarea']);
						unset($_COOKIE['locarea']);
					}else{
						$_SESSION['locarea'] = $row["LocationAreaId"];
						setcookie('locarea',$row["LocationAreaId"],time() + (86400 * 7), '/');
					}
					unset($_SESSION["locdesc"]);
					header("Location:/");
				}
			//}
			mysqli_free_result($result);
		}

		mysqli_close($conn);
	}
	
}


HeaderDraw();

?>
<h2 style="margin-top:10px;margin-bottom:10px;">Select which area you are in:</h2>

<?php

$countryId = "";
$regionId = "";

$conn = DBConnect();
if($conn){

	$sql = "select lc.*, lr.LocationRegionId, lr.LocationRegionName, lr.LocationRegionCode, la.LocationAreaId, la.LocationAreaName, la.LocationAreaCode"
	. " from evplocationcountry lc inner join evplocationregion lr on lc.LocationCountryId = lr.LocationCountryId inner join evplocationarea la on lr.LocationRegionId = la.LocationRegionId"
	. " order by lc.LocationCountryName, lr.LocationRegionName, la.LocationAreaName";

	$result = mysqli_query($conn, $sql);
	if($result !== false){
		while($row = mysqli_fetch_assoc($result)) {

			if($countryId != $row["LocationCountryId"]){
				if(strlen($countryId) != 0)
					echo '</div>';
				echo '<div style="float:left;padding-left:20px;">';
				
				echo '<div>' . $row["LocationCountryName"] . '</div>';
				$countryId = $row["LocationCountryId"];
			}
			if($regionId != $row["LocationRegionId"]){
				echo '<div style="padding-left:20px;"><a href="location.php?c='.$row["LocationCountryCode"].'&r='.$row["LocationRegionCode"].'">' . $row["LocationRegionName"] . '</a></div>';
				$regionId = $row["LocationRegionId"];
			}
			echo '<div style="padding-left:40px;"><a href="location.php?c='.$row["LocationCountryCode"].'&r='.$row["LocationRegionCode"].'&a='.$row["LocationAreaCode"].'">' . $row["LocationAreaName"] . '</a></div>';
		}
		echo '</div>';
		
		
		mysqli_free_result($result);
	}


	mysqli_close($conn);
}
echo '<div style="clear:left;"></div>';



include "include/footer.php";

?>
