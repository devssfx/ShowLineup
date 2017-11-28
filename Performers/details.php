<?php
$pageTitle = "Performers";
include "../include/db.php";
include "../include/dl_performer.php";
include "../include/header.php";


	if (isset($_GET["n"])) {
		HeaderDraw();
		$urlName = $_GET["n"];

		//global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisStatus;

		DL_PerformerLoad($urlName);

			
		if(strlen($thisId) == 0){
			header("Location:/Performers");
		}else{
			if($thisIsAdmin){?>
				<div class="editBar">
					<span>
						<a href="Edit/<?php echo $thisFriendly; ?>">Edit</a>
					</span>
					<span>
						<a href="Admin/<?php echo $thisFriendly; ?>">Admin</a>
					</span>
					<span>
						<a href="Members/<?php echo $thisFriendly; ?>">Members</a>
					</span>
				</div>
			<?php				
			}
			?>
			<h2><?php 
				echo $thisName;
				if($thisStatus == 0)
					echo ' (Draft)';
			
			?></h2>
			<div style="margin-top:10px">
				<div class="description-area">
					<div class="desc-tab">About</div>
					<div style="clear:left;"></div>
					<div class="desc-tab-bar"></div>
					<div class="desc-area"><?php
						echo $thisDesc;
					?></div>
				</div>
				<div style="float:left;padding-left:10px;">
					<h3>Upcoming Shows:</h3>
				<?php
					$conn = DBConnect();
					if($conn){
						$isPerformer = false;
						$sql = "select * from evpPerformerAdmin where MemberId = ". ParseS($_SESSION["mi"]) ." and AdminForId = " . ParseS($thisId);
						if($result = mysqli_query($conn, $sql)){
							if($row = mysqli_fetch_assoc($result)){
								if($row["AdminForId"] == ParseS($thisId)){
									$isPerformer = true;
								}
							}
							mysqli_free_result($result);
						}
					
						$sql = "select s.*, sd.ShowDateId, sd.ShowDate, sd.ShowTime, sd.ShowDateStatus, sd.VenueId, sd.VenueConfirmation, sd.ShowLength"
						. " from evpShow s inner join evpShowDate sd on s.ShowId = sd.ShowId inner join evpShowDatePerformer sdp on sd.ShowDateId = sdp.ShowDateId"
						. " where sdp.PerformerId = '203E2277-2D4B-E530-8C58-F13B85FBC803' and ("
						. " (ShowDate >= 20161120 and ShowDate <= 20161227) or (ShowDate >= 20161120 and ShowDate <= 20161227)"
						. " )";
						if($isPerformer)
							$sql = $sql . " and (s.ShowStatus <> 2 and sd.ShowDateStatus <> 2)";
						else
							$sql = $sql . " and (s.ShowStatus = 1 and sd.ShowDateStatus = 1)";
						
						$sql = $sql . " and CountryId = 'DB64AD06-4198-48B5-9812-3E40BD3F3225'"
						. " order by sd.ShowDate, sd.ShowTime";
						
						$showFound = false;
						if($result = mysqli_query($conn, $sql)){
							$showId = "";
							while($row = mysqli_fetch_assoc($result)){
								$showFound = true;
								if($showId != $row["ShowId"]){
									if($showId != "")
										echo '</div>';
									
									$showId = $row["ShowId"];
									echo '<div style="padding-bottom:10px;">';
									echo '<a href="/Shows/'.$row["ShowUrlFriendlyName"].'">' . $row["ShowName"] . '</a>';
								}
								if(!is_null($row["ShowDate"])){
									echo '<div>';
									echo DateToString($row["ShowDate"]);
									echo ' at ' . TimeToString($row["ShowTime"]);
									echo '</div>';
								}
							}
							if($showId != "")
								echo '</div>';
							
							mysqli_free_result($result);
						}
						
						mysqli_close($conn);
						
						if(!$showFound){
							echo '<div>There are no upcoming shows for this performer.</div>';
						}
						
					}
				?></div>
				<div style="clear:both;"></div>
			</div>
			<div style="clear:both;"></div>
		<?php }
		
	}


include "../include/footer.php";
?>
