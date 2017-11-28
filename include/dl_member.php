<?php

include 'PasswordHash.php';

function DL_MemberLogin($memberEmail, $memberPassword, $remember){
	global $servername, $username, $password, $dbname;
	
	$success = 0;
	
	$memberId = "";
	$memberName = "";
	$hash = "";
	
	$sql = "select MemberId, MemberName, MemberLogin, MemberPassword from evpmember where MemberLogin = " . ParseS($memberEmail);
	//db
	$conn = DBConnect();
	if($conn){
		if($result = mysqli_query($conn, $sql)){
			if($row = mysqli_fetch_assoc($result)) {
				if($row["MemberLogin"] == $memberEmail){
					$memberId = $row["MemberId"];
					$memberName = $row["MemberName"];
					$hash = $row["MemberPassword"];
				}
			}
		}
		mysqli_free_result($result);

		if(strlen($memberName) != 0){
			$t_hasher = new PasswordHash(8, FALSE);
			$check = $t_hasher->CheckPassword($memberPassword, $hash);
			if($check){
			//if(password_verify($memberPassword, $hash)) {
				SessionCreation($memberId, $memberName);
				$success = 1;
				
				if($remember){
					$memCode = str_replace('-','',getGUID());
					//$memCode = $memCode . substr($memberId,4,4) . substr($memberId,24,4);  stupid idea
					$sql = 'update evpmember set RememberCode = ' . ParseS($memCode) . ' where MemberId = ' . ParseS($memberId);
					if($conn->query($sql)){
						setcookie('memme',$memCode,time() + (86400 * 7), '/');
					}
				}
				
			}
		}
		mysqli_close($conn);
	}
	return $success;
	
}

function DL_MemberCreate($memberName, $memberEmail, $memberPassword){
	
	$success = -1;
	
	//$hashed = password_hash($memberPassword, PASSWORD_DEFAULT);
	$t_hasher = new PasswordHash(8, FALSE);
	$hashed = $t_hasher->HashPassword($memberPassword);
	
	$memberId = getGUID();
	
	$conn = DBConnect();
	if($conn){
		$sql = "insert into evpmember (MemberId,MemberName,MemberLogin,MemberPassword,MemberCookie,MemberEmail,LocationCountryId)"
		. " values (". ParseS($memberId) . ",". ParseS($memberName) . ",". ParseS($memberEmail) . ",". ParseS($hashed) . ",". ParseS("testcookie") . ",". ParseS($memberEmail) . ",". ParseS($_SESSION["loccountry"]) . ")";

		if($conn->query($sql)){
			SessionCreation($memberId, $memberName);
			$success = 0;
		}

	}
	return $success;
	
}



?>