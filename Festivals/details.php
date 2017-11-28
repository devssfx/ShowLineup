<?php
$pageTitle = "Festivals";
include "../include/db.php";
include "../include/dl_festival.php";
include "../include/header.php";
?>


<style type="text/css">
	.showListBox
	{
		height:240px;
		width:215px;
		float:left;
		margin-bottom:20px;
		overflow:hidden;
		font-size:90%;
		
	}
</style>

<?php

	if (isset($_GET["n"])) {
		HeaderDraw();
		$urlName = $_GET["n"];
		
		global $thisId, $thisName, $thisFriendly, $thisDesc, $thisIsAdmin, $thisDateOpen, $thisDateClose, $thisStatus;

		DL_FestivalLoad($urlName);
			
			
		if(strlen($thisId) == 0){
			header("Location:/Festivals");
		}else{
			if($thisIsAdmin){?>
				<div class="editBar">
					<span>
						<a href="Edit/<?php echo $thisFriendly; ?>">Edit</a>
					</span>
					<span>
						<a href="Admin/<?php echo $thisFriendly; ?>">Admin</a>
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
			
			<div><?php echo DateStringFromTo($thisDateOpen, $thisDateClose); ?></div>
			
			
				<div style="margin-top:10px;float:left;margin-right:10px;">
					<div class="description-area">
						<div class="desc-tab">About</div>
						<div style="clear:left;"></div>
						<div class="desc-tab-bar"></div>
						<div class="desc-area"><?php
							echo $thisDesc;
						?></div>
					</div>
					<div style="clear:both;"></div>
				</div>
				
				<?php
				class ShowInfo{
					public $ShowId;
					public $ShowName;
					public $ShowUrl;
					public $ShowDateFirst;
					public $ShowDateLast;
				}
				$showList = array();
				
				$showId = "";
				
					$conn = DBConnect();
					if($conn){
						$sql = "select s.ShowId, s.ShowName, s.ShowUrlFriendlyName, sd.* from evpShow s inner join evpShowDate sd on s.ShowId = sd.ShowId"
						. " inner join evpShowAdmin sa on s.ShowId = sa.AdminForId"
						. " where s.FestivalId = ". ParseS($thisId) ." and ((s.ShowStatus = 1 and sd.ShowDateStatus <> 0 and sd.ShowDateStatus <> 3)";
						if(isset($_SESSION["mi"])){
							$sql = $sql . " or sa.MemberId = " . ParseS($_SESSION["mi"]);
						}
						$sql = $sql . ") order by ShowDate, ShowTime";

						if($result = mysqli_query($conn, $sql)){
							while($row = mysqli_fetch_assoc($result)){
								$found = false;
								foreach($showList as $showItem){
									if($showItem->ShowId == $row["ShowId"]){
										if($showItem->ShowDateFirst > $row["ShowDate"]){
											$showItem->ShowDateFirst = $row["ShowDate"];
										}
										if($showItem->ShowDateLast < $row["ShowDate"]){
											$showItem->ShowDateLast = $row["ShowDate"];
										}
										$found = true;
										break;
									}
								}
								if($found == false){
									$showInfo = new ShowInfo();
									$showInfo->ShowId = $row["ShowId"];
									$showInfo->ShowName = $row["ShowName"];
									$showInfo->ShowUrl = $row["ShowUrlFriendlyName"];
									$showInfo->ShowDateFirst = $row["ShowDate"];
									$showInfo->ShowDateLast = $row["ShowDate"];
									$showList[] = $showInfo;
								}
								
							}
							mysqli_free_result($result);
							
							if(count($showList) > 0){
								?><div class="AssociationBorder" style="margin-top:10px;float:left;"><?php
								foreach($showList as $showItem){
									?>
									<div class="showListBox">
										<div class="Involved" style="float:left;height:100%;width:5px;margin-right:10px;"></div>

										<div style="padding:0px;font-size:110%;font-weight:bold;">
											<a href="/Shows/<?php echo $showItem->ShowUrl; ?>"><?php echo $showItem->ShowName; ?></a>
										</div>
										<div style="">
										</div>
										<div style="padding:0px;"><?php
											if($showItem->ShowDateFirst == $showItem->ShowDateLast)
												echo DateToString($showItem->ShowDateFirst);
											else
												echo DateStringFromTo($showItem->ShowDateFirst, $showItem->ShowDateLast);
										?></div>
									</div>
									<?php
								}
								echo '</div>';
							}
							
						}
						mysqli_close($conn);
					}
				?>
				<div style="clear:left;"></div>	
			</div>			
			
		<?php }

	}


include "../include/footer.php";
?>
