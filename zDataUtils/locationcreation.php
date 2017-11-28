<?php
include "../include/db.php";

$success = 0;

function insertData($conn, $sql){
	$rtn = -1;
	
	if($conn->query($sql) === true){
		$rtn = 0;
	}

	return $rtn;
}



$conn = DBConnect();
if($conn){
	$doInsert = false;
	$sql = "select count(LocationCountryId) as C from evplocationcountry";
	echo 'Checking...</br>';
	if($result = mysqli_query($conn, $sql)){
		echo 'Got a result.</br>';
		if($row = mysqli_fetch_assoc($result)){
			echo 'Fetch</br>';
			if(is_null($row["C"])){
				$doInsert = true;
				echo 'Was null</br>';
			}else if($row["C"] == 0){
				$doInsert = true;
				echo 'Was 1</br>';
			}else{
				echo 'Count was:' . $row["C"] . '</br>';
			}
		}
		mysqli_free_result($result);
	}
	
	echo 'Will I do insert:';
	if($doInsert){
		echo 'Yes.</br>';
		
insertData($conn, "insert into evplocationcountry (LocationCountryId,LocationCountryName,LocationCountryCode) values ('DB64AD06-4198-48B5-9812-3E40BD3F3225','Australia','AU');");
insertData($conn, "insert into evplocationcountry (LocationCountryId,LocationCountryName,LocationCountryCode) values ('C8499E66-C6BC-468B-84C3-74ED46684013','USA','US');");
insertData($conn, "insert into evplocationcountry (LocationCountryId,LocationCountryName,LocationCountryCode) values ('6B3FE13B-48AC-4F75-9CCE-751ED7CB8993','United Kingdom','UK');");
insertData($conn, "insert into evplocationcountry (LocationCountryId,LocationCountryName,LocationCountryCode) values ('F6104DBA-BBD2-4FE3-BC7A-DAA74E9F8D13','Canada','CA');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('672F15BB-18F9-421F-92FC-53577B07E066','Victoria','01','DB64AD06-4198-48B5-9812-3E40BD3F3225');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('9F57FFED-440C-465E-B881-4D367A132B36','South Australia','04','DB64AD06-4198-48B5-9812-3E40BD3F3225');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('7618CCC3-CFE6-4C41-B7A1-A4B97A40B3CC','New South Wales','02','DB64AD06-4198-48B5-9812-3E40BD3F3225');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('78688398-3016-49B2-93FF-C7CA205DD07F','Queensland','03','DB64AD06-4198-48B5-9812-3E40BD3F3225');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('44DEA8E0-DA12-4F9B-A43A-742493851347','Northern Territory','05','DB64AD06-4198-48B5-9812-3E40BD3F3225');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('7E7DDABE-E409-49F1-A1FC-0B57B26884B4','England','11','6B3FE13B-48AC-4F75-9CCE-751ED7CB8993');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('1A600016-62C4-4DEB-A277-E9E7E34D294F','Scotland','12','6B3FE13B-48AC-4F75-9CCE-751ED7CB8993');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('52A27E02-294F-40FA-9016-3B6AA7AD6DA4','Ontario','06','F6104DBA-BBD2-4FE3-BC7A-DAA74E9F8D13');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('462DC926-E756-497F-B97C-3C080DDC5C7A','British Columbia','07','F6104DBA-BBD2-4FE3-BC7A-DAA74E9F8D13');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('D500F218-85E2-4941-8108-8FDEDDA83943','Quebec','05','F6104DBA-BBD2-4FE3-BC7A-DAA74E9F8D13');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('86AF56BB-ED3C-46FB-99BD-6AEC2EDDB57D','New York','09','C8499E66-C6BC-468B-84C3-74ED46684013');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('D84A7E42-2E3A-4B4A-8063-8A63F00DFA33','California','08','C8499E66-C6BC-468B-84C3-74ED46684013');");
insertData($conn, "insert into evplocationregion (LocationRegionId,LocationRegionName,LocationRegionCode,LocationCountryId) values ('2A22AF0A-326B-4BA6-8D1A-A8895305414D','Floria','10','C8499E66-C6BC-468B-84C3-74ED46684013');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('4D82B3A5-0AF6-43B6-AEB6-2CCA306F8578','Wollongong','-34.4331','150.8831','7618CCC3-CFE6-4C41-B7A1-A4B97A40B3CC','03');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('A805345F-10A8-4243-BF0E-3596CA9690F6','Sydney','-33.86','151.2094','7618CCC3-CFE6-4C41-B7A1-A4B97A40B3CC','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('96A973B1-553E-4B1F-A3C4-ABDA997E41A1','Newcastle','-32.9167','151.75','7618CCC3-CFE6-4C41-B7A1-A4B97A40B3CC','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('96B5E6E6-99F2-4841-896D-9DCC19D46E47','Adelaide','-34.98','138.70','9F57FFED-440C-465E-B881-4D367A132B36','04');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('3F5B24F0-7958-4C9B-993F-7EAD4986FBDD','Brisbane','-27.40','153.00','78688398-3016-49B2-93FF-C7CA205DD07F','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('687F199B-9804-4C9E-9C15-50F003074C77','Gold Coast','-27.98','153.32','78688398-3016-49B2-93FF-C7CA205DD07F','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('1860B90F-F929-4FAC-A95A-E1421539EEEA','Melbourne','-37.86','145.06','672F15BB-18F9-421F-92FC-53577B07E066','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('1AA5C673-2E91-4426-9F47-1E6DBB9FDCD7','Darwin','-12.594','131','44DEA8E0-DA12-4F9B-A43A-742493851347','05');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('1D442646-EB9A-41C7-AFA8-3795D4DEDE0C','Liverpool','-32.9167','151.75','7E7DDABE-E409-49F1-A1FC-0B57B26884B4','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('655A80E9-E94E-467C-834F-02850E4884C7','London','-33.86','151.2094','7E7DDABE-E409-49F1-A1FC-0B57B26884B4','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('F1105111-57EB-4A50-A19F-D2847D70899D','Manchester','-34.4331','150.8831','7E7DDABE-E409-49F1-A1FC-0B57B26884B4','03');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('D796620C-65BA-41C5-B0DA-A2F0DEB2A527','Edinburgh','-33.86','151.2094','1A600016-62C4-4DEB-A277-E9E7E34D294F','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('F8CAE667-F7BE-4CCB-A2F0-47A8071BA6D9','Glasgow','-32.9167','151.75','1A600016-62C4-4DEB-A277-E9E7E34D294F','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('200AD640-A5B7-4823-9E5B-FC83D079E7AD','Vancouver','-33.86','151.2094','462DC926-E756-497F-B97C-3C080DDC5C7A','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('37CF7083-C0AC-4EA8-8B32-E384FD8C6047','Whistler','-32.9167','151.75','462DC926-E756-497F-B97C-3C080DDC5C7A','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('9A629C9D-F305-49FA-BD6D-0529639C0511','Winnipeg','-33.86','151.2094','52A27E02-294F-40FA-9016-3B6AA7AD6DA4','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('4B46B711-D6FF-497F-A6DC-170B8D69B89D','Thunder Bay','-32.9167','151.75','52A27E02-294F-40FA-9016-3B6AA7AD6DA4','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('43935331-2225-4640-A473-CBD8F6FF49C2','Montreal','-33.86','151.2094','D500F218-85E2-4941-8108-8FDEDDA83943','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('BEFC0A87-B5CB-4879-9CD3-2AC4007567FC','Ottawa','-32.9167','151.75','D500F218-85E2-4941-8108-8FDEDDA83943','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('CC3CDEFC-7790-480D-9FE9-81BA8BB96F43','New York','-33.86','151.2094','86AF56BB-ED3C-46FB-99BD-6AEC2EDDB57D','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('183649E2-EA40-4869-834F-D6E56D6F2CA8','Queens','-32.9167','151.75','86AF56BB-ED3C-46FB-99BD-6AEC2EDDB57D','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('5F19B8C1-5998-4FD2-9311-56F045FEBE5B','Manhattan','-34.4331','150.8831','86AF56BB-ED3C-46FB-99BD-6AEC2EDDB57D','03');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('F376ADC7-4F04-419D-936A-61EBAF27C93A','Los Angeles','-33.86','151.2094','D84A7E42-2E3A-4B4A-8063-8A63F00DFA33','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('204212CE-818C-438B-A9AB-A15B111546EE','San Diago','-32.9167','151.75','D84A7E42-2E3A-4B4A-8063-8A63F00DFA33','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('EAD04760-D224-40EC-8476-BCDC07FE67B9','San Francisco','-34.4331','150.8831','D84A7E42-2E3A-4B4A-8063-8A63F00DFA33','03');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('2098B0F6-2E1C-486C-8761-9B5B93AAE96D','Orlando','-33.86','151.2094','2A22AF0A-326B-4BA6-8D1A-A8895305414D','01');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('43AECC5A-5F89-463E-8DFD-348800176027','Tamper','-32.9167','151.75','2A22AF0A-326B-4BA6-8D1A-A8895305414D','02');");
insertData($conn, "insert into evplocationarea (LocationAreaId,LocationAreaName,Latitude,Longitude,LocationRegionId,LocationAreaCode) values ('F6D63C71-07CD-415F-AF54-695590B38E5F','Fort Myers','-34.4331','150.8831','2A22AF0A-326B-4BA6-8D1A-A8895305414D','03');");
		
	}else{
		echo 'No.</br>';
	}
	mysqli_close($conn);
	
	
	
}
?>
Check Finished.