<?php


function DL_AdminPage($adminFor, $urlName){

	//global $thisId, $thisName, $thisIsAdmin; //, $thisFriendly; //, $thisDesc, $thisDateOpen, $thisDateClose, $thisStatus;
	

	$search = "";
	if(isset($_POST["txtAdminSearch"])){
		$search = $_POST["txtAdminSearch"];
	}
	$addMemberId = "";
	if(isset($_POST["AdminAdd"])){
		$addMemberId = $_POST["txtMemberAdd"];
	}

	$itemIsAdmin = false;
	
	$conn = DBConnect();
	if($conn){
		$sql = "select o.".$adminFor."Id, o.".$adminFor."Name from evp".strtolower($adminFor)." o inner join evp".strtolower($adminFor)."admin oa on o.".$adminFor."Id = oa.AdminForId where o.".$adminFor."UrlFriendlyName = ". ParseS($urlName) ." and oa.MemberId = " . ParseS($_SESSION["mi"]);

		$result = mysqli_query($conn, $sql);
		if($result){
			if (mysqli_num_rows($result) > 0) {
				if($row = mysqli_fetch_assoc($result)) {
					$itemId = $row[$adminFor . "Id"];
					$itemName = $row[$adminFor . "Name"];
					$itemIsAdmin = true;
				}
			}
			mysqli_free_result($result);
		}
	


		if(!$itemIsAdmin){
			mysqli_close($conn);
			Header("Location:/");
		}else{
			$success = 0;
			if(strlen($addMemberId) > 0){
				$sql = "insert into evp". strtolower($adminFor) ."admin (AdminForId, MemberId) VALUES (". ParseS($itemId) .",". ParseS($addMemberId) .");";

				if ($conn->query($sql) === TRUE) {
					$success = 1;
				}
				if($success == 0){
					$success = -2; //admin not added
				}
			}
			
			if(isset($_POST["AdminRemove"])){
				$sql = "delete from evp". strtolower($adminFor) ."admin where AdminForId = ". ParseS($itemId) . " and MemberId = ". ParseS($_POST["txtAdminRemove"]) . ";";
				if ($conn->query($sql) === TRUE) {
					$success = 1;
				}
				if($success != 1){
					$success = -3; //admin remove faild
				}else{
					if($_POST["txtAdminRemove"] == $_SESSION["mi"]){
						mysqli_close($conn);
						Header("Location:/".$adminFor."s/" . $urlName);
					}
				}
			}
			
			HeaderDraw();
			
			if($success == -2){ //admin add faild
				echo '<div class="input-validation-error">There was a problem adding this member as administrator.</div>';
			}else if($success == -3){ //admin remove faild
				echo '<div class="input-validation-error">There was a problem removing this member as administrator.</div>';
			}
			
			?>
			<div class="editBar">
				<a href="../<?php echo $urlName; ?>">Return</a>
			</div>

			<h2>Admin for <?php echo $itemName; ?></h2>
			<?php

				$sql = "select m.MemberId, m.MemberName from evp".strtolower($adminFor)." f inner join evp".strtolower($adminFor)."admin fa on f.".$adminFor."Id = fa.AdminForId inner join evpmember m on fa.MemberId = m.MemberId"
					. " where f.".$adminFor."Id = " . ParseS($itemId);
					
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						?><div>
							<form action="<?php echo "/" . $adminFor . "s/Admin/" . $urlName; ?>" method="post">
								<input type="hidden" name="txtAdminRemove" value="<?php echo $row["MemberId"]; ?>" />
								<input type="submit" name="AdminRemove" value="Remove" />
								<?php echo $row["MemberName"]; ?>
							</form>
						</div><?php
					}
				}
				mysqli_free_result($result);

			?>
			<div style="padding-top:10px;">
				<form action="<?php echo "/" . $adminFor . "s/Admin/" . $urlName; ?>" method="post">
					<h3>Add New</h3>
					<input id="txtAdminSearch" name="txtAdminSearch" type="text" value="<?php echo $search; ?>" />
					<input type="submit" name="btnAdminSearch" value="Search" />
				</form>
					<?php
					if(strlen($search) > 0){
						$sql = "select * from evpmember where LocationCountryId = " . ParseS($_SESSION["loccountry"])
							. "and MemberName like " . ParseS("%" . $search . "%") . " and MemberId not in (select MemberId from evp".strtolower($adminFor)."admin where AdminForId = " . ParseS($itemId) . ")";

						if($result = mysqli_query($conn, $sql)){
							if (mysqli_num_rows($result) > 0) {
								echo '<h4>Add member as Admin</h4>';
								while($row = mysqli_fetch_assoc($result)) {
									?>
									<form action="<?php echo $urlName; ?>" method="post">
										<div><input type="hidden" name="txtMemberAdd" value="<?php echo $row["MemberId"]; ?>" /><input type="submit" name="AdminAdd" value="Add" /> <?php echo $row["MemberName"] ?></div>
									</form>
									<?php
								}
							}else{
								echo "<div>No matching names were found.</div>";
							}
							mysqli_free_result($result);
						}
					
					}
					?>
				
			</div>
			<?php

			
		}
		mysqli_close($conn);
	}
}

?>