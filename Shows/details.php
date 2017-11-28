<?php
$pageTitle = "Shows";
include "../include/db.php";
include "../include/dl_show.php";
include "../include/header.php";

if (isset($_GET["n"])) {
	HeaderDraw();
	
	$urlName = $_GET["n"];
	DL_ShowLoad($urlName,false);
	
	if(strlen($thisName) >  0){

if(isset($_SESSION["mi"])){	

	?>
	<script type="text/javascript">
	function GoingTo(btn, showDateId){
		var txtStatus = $(btn).parent().find('input')[1];
		
		if(txtStatus.value == '0'){
			txtStatus.value = '1';
			btn.value = 'Going';
		}else{
			txtStatus.value = '0';
			btn.value = 'Join';
		}
		
		var actionUrl = '/ajax/GoingToShow.php';
		$.post( actionUrl, {
			ShowDateId : showDateId,
			MemberId : '<?php echo $_SESSION["mi"]; ?>',
			Status : txtStatus.value,
		}).done(function( data ) {
			if(data != '0'){
				alert('There was a problem. Reload the page and try again. (' + data + ')');
				if(txtStatus.value == '0'){
					txtStatus.value = '1';
					btn.value = 'Going';
				}else{
					txtStatus.value = '0';
					btn.value = 'Join';
				}
			//}else{ //success
			//	alert('success');
			}
		}).fail(function(){
			alert('There was a problem. Reload the page and try again.');
			if(txtStatus.value == '0'){
				txtStatus.value = '1';
				btn.value = 'Going';
			}else{
				txtStatus.value = '0';
				btn.value = 'Join';
			}

		});
		

	}
	</script>
	<?php	
} //login check
	
		
		
		
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
		
		if(strlen($festivalName) > 0){
			echo '<div>Part of <a href="/Festivals/'. $festivalUrl .'">' . $festivalName . "</a></div>";
		}
		
		
		?>
		
		
		
		<h2><?php 
			echo $thisName;
			if($thisStatus != 1)
				echo ' (' . StatusToString($thisStatus) . ')';
		
		?></h2>
		
		<?php
		if(count($hostList) != 0){
			echo '<div>Hosted By: ';
			$firstH = true;
			foreach($hostList as $hostItem){
				if(!$firstH){
					echo ', ';
				}else{
					$firstH = false;
				}
				echo '<a href="/Hosts/' . $hostItem->HostUrlFriendlyName . '">' . $hostItem->HostName . '</a>';
			}
			echo '</div>';
		}

		$c = count($dateList);
		for($i = 0; $i < $c; $i++){
			$dateInfo = $dateList[$i];
			?>
			<div style="margin-top:10px;">
				<?php 
				echo DateToString($dateInfo->ShowDate) . " at " . TimeToString($dateInfo->ShowTime);
				if($dateInfo->ShowDateStatus != 1)
					echo ' (' . StatusToString($dateInfo->ShowDateStatus) . ')';
				echo "<br>";
				
				if(strlen($dateInfo->VenueId) != 0){
					echo 'at <a href="/Venues/' . $dateInfo->VenueUrlFriendlyName . '">' . $dateInfo->VenueName . '</a>';
				}
				$isFirst = true;
				if(count($performerList) > 0){
					foreach($performerList as $pItem){
						if($pItem->ShowDateId == $dateInfo->ShowDateId){
							if($isFirst){
								echo '</br>Featuring: ';
								$isFirst = false;
							}else{
								echo ', ';
							}
							echo '<a href="/Performers/'.$pItem->PerformerFriendlyUrl.'">' . $pItem->PerformerName . '</a>';;
							if($pItem->PerformerTitle != "")
								echo ' (' . $pItem->PerformerTitle . ')';
						}
					}
				}
				
				
				if(isset($_SESSION["mi"])){
					echo '<div>';
					echo '<input type="button" value="';
					if($dateInfo->GoingToStatus == 0)
						echo 'Join';
					else
						echo 'Going';
					echo '" onclick="GoingTo(this, ' . ParseS($dateInfo->ShowDateId) . ');" />';
					echo '<input type="hidden" value="'. $dateInfo->GoingToStatus.'" />';
					echo '</div>';
				}
				?>
			
			</div>
			<?php
		}

		?>		
		
		<div style="margin-top:10px;">
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
	<?php }

	
	
	
	
}


include "../include/footer.php";
?>
