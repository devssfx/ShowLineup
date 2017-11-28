<?php

$urlName = "";

if(isset($_GET["n"])){
	$urlName = $_GET["n"];
}

if(strlen($urlName) == 0){
	Header("Location:/");
}


$pageTitle = "Peformers";
include "/include/db.php";
//include "/include/dl_members.php";
include "/include/header.php";



	$search = "";
	if(isset($_POST["txtSearch"])){
		$search = $_POST["txtSearch"];
	}
	$addMemberId = "";
	if(isset($_POST["MemberAdd"])){
		$addMemberId = $_POST["txtMemberAdd"];
	}

	$itemIsPerformer = false;
	
	$conn = DBConnect();
	if($conn){
		$sql = "select o.PerformerId, o.PerformerName from evpperformer o inner join evpperformeradmin oa on o.PerformerId = oa.AdminForId where o.PerformerUrlFriendlyName = ". ParseS($urlName) ." and oa.MemberId = " . ParseS($_SESSION["mi"]);

		$result = mysqli_query($conn, $sql);
		if($result){
			if (mysqli_num_rows($result) > 0) {
				if($row = mysqli_fetch_assoc($result)) {
					$itemId = $row["PerformerId"];
					$itemName = $row["PerformerName"];
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
				$sql = "insert into evpperformermember (PerformerId, MemberId) VALUES (". ParseS($itemId) .",". ParseS($addMemberId) .");";

				if ($conn->query($sql) === TRUE) {
					$success = 1;
				}
				if($success == 0){
					$success = -2; //member not added
				}
			}
			
			if(isset($_POST["PerformerRemove"])){
				$sql = "delete from evpperformermember where PerformerId = ". ParseS($itemId) . " and MemberId = ". ParseS($_POST["txtMemberRemove"]) . ";";
				if ($conn->query($sql) === TRUE) {
					$success = 1;
				}
				if($success != 1){
					$success = -3; //member remove faild
				}else{
					if($_POST["txtMemberRemove"] == $_SESSION["mi"]){
						mysqli_close($conn);
						Header("Location:/Performers/" . $urlName);
					}
				}
			}
			
			HeaderDraw();
			
			if($success == -2){ //member add faild
				echo '<div class="input-validation-error">There was a problem adding this member as a Performer.</div>';
			}else if($success == -3){ //member remove faild
				echo '<div class="input-validation-error">There was a problem removing this member.</div>';
			}
			
			?>
			<div class="editBar">
				<a href="../<?php echo $urlName; ?>">Return</a>
			</div>

			<h2>Performer for <?php echo $itemName; ?></h2>
			<?php

				$sql = "select m.MemberId, m.MemberName from evpperformer f inner join evpperformermember fa on f.PerformerId = fa.PerformerId inner join evpmember m on fa.MemberId = m.MemberId"
					. " where f.PerformerId = " . ParseS($itemId);
					
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						?><div>
							<form action="<?php echo "/Performers/Members/" . $urlName; ?>" method="post">
								<input type="hidden" name="txtMemberRemove" value="<?php echo $row["MemberId"]; ?>" />
								<input type="submit" name="MemberRemove" value="Remove" />
								<?php echo $row["MemberName"]; ?>
							</form>
						</div><?php
					}
				}
				mysqli_free_result($result);

			?>
			<div style="padding-top:10px;">
				<form action="<?php echo "/Performers/Members/" . $urlName; ?>" method="post">
					<h3>Add New</h3>
					<input id="txtSearch" name="txtSearch" type="text" value="<?php echo $search; ?>" />
					<input type="submit" name="btnSearch" value="Search" />
				</form>
					<?php
					if(strlen($search) > 0){
						$sql = "select * from evpmember where LocationCountryId = " . ParseS($_SESSION["loccountry"])
							. "and MemberName like " . ParseS("%" . $search . "%") . " and MemberId not in (select MemberId from evpperformermember where MemberId = " . ParseS($itemId) . ")";

						$result = mysqli_query($conn, $sql);
						if (mysqli_num_rows($result) > 0) {
							echo '<h4>Add member to performer:</h4>';
							while($row = mysqli_fetch_assoc($result)) {
								?>
								<form action="<?php echo $urlName; ?>" method="post">
									<div><input type="hidden" name="txtMemberAdd" value="<?php echo $row["MemberId"]; ?>" /><input type="submit" name="MemberAdd" value="Add" /> <?php echo $row["MemberName"] ?></div>
								</form>
								<?php
							}
						}else{
							echo "<div>No matching names were found.</div>";
						}
						mysqli_free_result($result);
					
					}
					?>
				
			</div>
			<?php

			
		}
		mysqli_close($conn);
	}
	


include "/include/footer.php";



?>
