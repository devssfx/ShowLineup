<?php
if($_SERVER['HTTP_HOST'] == 'localhost'){
	$servername = "localhost";
	$username = "root";
	$password = "nsew^2000";
	$dbname = "evp2014";
}else{
	
	$servername = "localhost";
	$username = "id1758338_andjustin";
	$password = "East1999";
	$dbname = "id1758338_showlineup";
	
}

function DBConnect(){
	global $servername, $username, $password, $dbname;
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	return $conn;
}


function UrlFriendlyName($name){
	
	$rtn = preg_replace('/[^\da-z]/i', '-', $name);

	while (strpos($rtn, "--") !== FALSE)
	{
		$rtn = str_replace("--","-",$rtn);
	}

	if(substr($rtn, 0, 1) == "-")
		$rtn = substr($rtn, 1);
	if(substr($rtn, strlen($rtn) - 1, 1) == "-")
		$rtn = substr($rtn, 0, strlen($rtn)-1);

	return $rtn;
}


function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }
}

function ParseS($s){
	if($_SERVER['HTTP_HOST'] == 'localhost'){
		return "'" . str_replace("'", "\\'", $s) . "'";
	}else{
		return "'" . $s . "'";
	}
	
}



function LocationCheck(){
	
	if( strtolower( $_SERVER['PHP_SELF']) != "/location.php"){
		
		if(!isset($_SESSION["loccountry"])){
			$distShort = -1;
			//check cookies
			if(isset($_COOKIE["loccountry"]) && isset($_COOKIE["locregion"])){
				$locCountry = $_COOKIE['loccountry'];
				$locRegion = $_COOKIE['locregion'];
				if(isset($_COOKIE['locarea']))
					$locArea = $_COOKIE['locarea'];
				else 
					$locArea = "";
				
				$distShort = 0;
			}else{

				$user_ip = getenv('REMOTE_ADDR');
				if ($user_ip = '' || $user_ip == "::1")
					$user_ip = "110.175.244.228";

				$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=" . $user_ip));
				$countryCode = $geo["geoplugin_countryCode"];
				$country = $geo["geoplugin_countryName"];
				$city = $geo["geoplugin_city"];
				
				$lat = $geo["geoplugin_latitude"];
				$lng = $geo["geoplugin_longitude"];
				if($lat == '' || $lng == ''){ //default Sydney
					$lat = '-33.86';
					$lng = '151.2094';
					$countryCode = 'AU';
				}
		
				global $servername, $username, $password, $dbname;
				$sql = 'select evplocationarea.*, evplocationcountry.LocationCountryId from evplocationcountry'
					. ' inner join evplocationregion on evplocationcountry.LocationCountryId = evplocationregion.LocationCountryId'
					. ' inner join evplocationarea on evplocationregion.LocationRegionId = evplocationarea.LocationRegionId'
					. ' where LocationCountryCode = "' . $countryCode . '"';

				$conn = mysqli_connect($servername, $username, $password, $dbname);
				if (!$conn) {
					die("Connection failed: " . mysqli_connect_error());
				}

				
				$locArea = '';
				$locRegion = '';
				$locCountry = '';
				
				if($result = mysqli_query($conn, $sql)){
					while($row = mysqli_fetch_assoc($result)) {
						$locLat = $row['Latitude'];
						$locLng = $row['Longitude'];
						
						$lngDiff = $locLng - $lng;
						$dist = sin(deg2rad($locLat)) * sin(deg2rad($lat)) + cos(deg2rad($locLat)) * cos(deg2rad($lat)) * cos(deg2rad($lngDiff));
						$dist = acos($dist);
						$dist = rad2deg($dist);
						$dist = $dist * 60 * 1.1515;
						$dist = $dist * 1.609344; //convert to kilometers

						if($distShort == -1 || $dist < $distShort){
							$distShort = $dist;
							$locArea = $row['LocationAreaId'];
							$locRegion = $row['LocationRegionId'];
							$locCountry = $row['LocationCountryId'];
						}
					}
					mysqli_free_result($result);
				}
				mysqli_close($conn);
			}
			
			if($distShort != -1){
				$_SESSION['loccountry'] = $locCountry;
				$_SESSION['locregion'] = $locRegion;
				setcookie('loccountry',$locCountry,time() + (86400 * 2), '/');
				setcookie('locregion',$locRegion,time() + (86400 * 2), '/');
				
				if($locArea != ""){
					$_SESSION['locarea'] = $locArea;
					setcookie('locarea',$locArea,time() + (86400 * 2), '/');
				}
				
				unset($_SESSION["locdesc"]);
			}else{
				header('Location:/location.php');
			}
		
		}
	}
}


function StatusToString($status){
	if($status == 0)
		return 'Draft';
	else if($status == 1)
		return 'Open';
	else if($status == 2)
		return 'Closed';
	else if($status == 3)
		return 'Sold Out';
	else
		return '';
	
}
function ConfirmedToString($status){
	if($status == 0)
		return 'Requested';
	else if($status == 1)
		return 'Accepted';
	else if($status == 2)
		return 'Cancelled';
	else
		return '';
	
}

function TimeToString($dateValue){
	if(strlen($dateValue) == 3)
		$dateValue = '0' . $dateValue;
	
	$hour = intval(substr($dateValue, 0, 2));
	$minute = substr($dateValue, 2, 2);
	

	if($hour >= 12){
		$ampm = " pm";
		if($hour > 12)
			$hour = $hour - 12;
	}else{
		$ampm = " am";
		if($hour == 0)
			$hour = 12;
	}
	
	
	return $hour . ":" . $minute . $ampm;
}


function DateToString($dateValue){
	$dateValueYear = substr($dateValue, 0, 4);
	$dateValueMonth = substr($dateValue, 4, 2);
	$dateValueDay = substr($dateValue, 6);

	$dateO = new DateTime();
	$dateO->setDate($dateValueYear, $dateValueMonth, $dateValueDay);
	
	return date_format($dateO, "j M Y");
}

function DateStringFromTo($dateOpen, $dateClose){
	$dateOpenYear = substr($dateOpen, 0, 4);
	$dateOpenMonth = substr($dateOpen, 4, 2);
	$dateOpenDay = substr($dateOpen, 6);

	$dateO = new DateTime();
	$dateO->setDate($dateOpenYear, $dateOpenMonth, $dateOpenDay);

	$dateCloseYear = substr($dateClose, 0, 4);
	$dateCloseMonth = substr($dateClose, 4, 2);
	$dateCloseDay = substr($dateClose, 6);

	$dateC = new DateTime();
	$dateC->setDate($dateCloseYear, $dateCloseMonth, $dateCloseDay);

	$dateString = "";
	if($dateOpenYear != $dateCloseYear){
		$dateString = date_format($dateO, "j M Y") . " to " . date_format($dateC, "j M Y");
	}else{
		if($dateOpenMonth != $dateCloseMonth){
			$dateString = date_format($dateO, "j M") . " to " . date_format($dateC, "j M Y");
		}else{
			$dateString = date_format($dateO, "j") . " to " . date_format($dateC, "j M Y");
		}
	}
	
	return $dateString;

}


function DL_HeaderNav(){

	$selFor = "";
	if(isset($_GET["f"]))
		$selFor = $_GET["f"];

	$selUrlName = "";
	if(isset($_GET["n"]))
		$selUrlName = $_GET["n"];


	if(isset($_SESSION["mi"])){
		$conn = DBConnect();
		if($conn){
			$doDropdown = true;
		}
	}else{
		$doDropdown = false;
	}
	
	$pageItems = array("Festival","Show","Performer","Venue","Host");
	
	if($doDropdown){
		$currMinute = (int)date("i");
		if(isset($_SESSION["ddTimeout"])){
			$sessMinute = (int)$_SESSION["ddTimeout"];
			if($currMinute < $sessMinute || $currMinute > $sessMinute + 0){
				for($navIdx = 0; $navIdx < 5; $navIdx++){
					unset($_SESSION["dd" . $pageItems[$navIdx]]);
				}
				$_SESSION["ddTimeout"] = $currMinute;
			}
		}else{
			$_SESSION["ddTimeout"] = $currMinute;
		}
	}
	for($navIdx = 0; $navIdx < 5; $navIdx++){
		?><div class="navItem"><a href="/<?php echo $pageItems[$navIdx] ?>s"><?php echo $pageItems[$navIdx] ?>s</a><?php
			if($doDropdown){
				if(isset($_SESSION["dd" . $pageItems[$navIdx]])){
					$list = $_SESSION["dd" . $pageItems[$navIdx]];
				}else{
					$list = array();
					
					$sql = "select distinct f." . $pageItems[$navIdx] . "Name, f." . $pageItems[$navIdx] . "Status, f." . $pageItems[$navIdx] . "UrlFriendlyName from evp" . strtolower($pageItems[$navIdx]) . " f inner join evp" . strtolower($pageItems[$navIdx]) . "admin fa on f." . $pageItems[$navIdx] . "Id = fa.AdminForId"
						. " where fa.MemberId = " . ParseS($_SESSION["mi"])
						. " and f." . $pageItems[$navIdx] . "Status <> 2";
					if($pageItems[$navIdx] == "Performer"){ //include members
						$sql = str_replace("where","inner join evpperformermember pm on f.PerformerId = pm.PerformerId where (", $sql);
						$sql = str_replace("and","or pm.MemberId = ". ParseS($_SESSION["mi"]) . " ) and", $sql);
					}

					$result = mysqli_query($conn, $sql);
					if($result !== false){
						while($row = mysqli_fetch_assoc($result)) {
							$list[] = $row[$pageItems[$navIdx] . 'UrlFriendlyName'] . ":" . $row[$pageItems[$navIdx] . 'Status'] . ":" . $row[$pageItems[$navIdx] . 'Name'];
						}
						$_SESSION["dd" . $pageItems[$navIdx]] = $list;
						
						mysqli_free_result($result);
					}
				}
				
				if (count($list) > 0) {
					?>
					<div>
						<select onchange="gotoSomething(this, '<?php echo $pageItems[$navIdx]; ?>s');" style="width:6em;">
							<option>-Admin-</option>
							<?php
								foreach($list as $item){
									$data = explode(":",$item);
									if(count($data) > 2){ //name may have a :
										for($i = 3; $i < count($data); $i++){
											$data[2] = $data[2] . ":" . $data[$i];
										}
									}
									echo '<option value="' . $data[0] . '"';
									if($pageItems[$navIdx] . "s" == $selFor){
										if($data[0] == $selUrlName){
											echo ' selected="selected"';
										}
									}
									echo '>' . $data[2];
									if($data[1] == 0)
										echo ' *';
									echo '</option>';
								}

							?>
						</select>
					</div>
					<?php
				}
			}
			?>
		</div>
	<?php
	}
	?>

	<div class="navItem"><a href="/Calendar">Calendar</a></div>	

	<?php

	if($doDropdown)
		mysqli_close($conn);

}

//session_save_path('/tmp');
if (!isset($_SESSION)) { session_start(); }


if(!isset($_SESSION["mi"])){
	if(isset($_COOKIE['memme'])){
		
		//check with db
		$conn = DBConnect();
		if($conn){
			$sql = "select MemberId, MemberName from evpmember where RememberCode = " . ParseS($_COOKIE['memme']);
			if($result = mysqli_query($conn, $sql)){
				if($row = mysqli_fetch_assoc($result)){
					$memberId = $row["MemberId"];
					$memberName = $row["MemberName"];
					//check against member id, which really is a stupid way so I am not going to do it.
					
					SessionCreation($memberId, $memberName);
				}
				mysqli_free_result($result);
			}
			mysqli_close($conn);
		}
	}
}


function SessionCreation($memberId, $memberName){
	$_SESSION["mi"] = $memberId;
	$_SESSION["mn"] = $memberName;
	
	unset($_SESSION["ddFestival"]);
	unset($_SESSION["ddShow"]);
	unset($_SESSION["ddPerformer"]);
	unset($_SESSION["ddVenue"]);
	unset($_SESSION["ddHost"]);
	
}


?>