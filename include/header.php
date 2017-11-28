<?php

LocationCheck();

function HeaderDraw(){
?>
<!DOCTYPE html>
<html>
<head>
    <title>Show Lineup</title>
	<link href="/Content/Site.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link href="/Content/SiteScreen.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/png" href="/favicon.png" />

	<script src="/Scripts/jquery-1.5.1.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		<?php
		/*
			$rootPath = $_SERVER['REQUEST_URI'];
			$pathCount = substr_count($rootPath, "/") - 2; //ignore first and last /
			$rootPath = "";
			while($pathCount > 0){ 
				$rootPath = $rootPath . "../";
				$pathCount--;
			}
		*/
		$rootPath = "/";
		?>
		function gotoSomething(ddl, something) {
			location.href = '<?php echo $rootPath; ?>' + something + '/' + $(ddl).val();
		}
	
	
/*		function doSearch(e) {
			var unicode = e.keyCode ? e.keyCode : e.charCode
			if (unicode == 13) {
				location.href = '/search?s=' + encodeURIComponent($('#txtSearchGlobal').val());
			}
		}*/
		
		function addNew(a) {
			switch(a){
				case 'f': location.href = '/Festivals/Edit';
					break;
				case 's': location.href = '/Shows/Edit';
					break;
				case 'p': location.href = '/Performers/Edit';
					break;
				case 'v': location.href = '/Venues/Edit';
					break;
				case 'h': location.href = '/Hosts/Edit';
					break;
			}
		}

		
	</script>
</head>

<body>
	<div id="fb-root"></div>
	<div class="header">

		<div class="backgroundColor" style="padding:5px;">
			
			
			<div class="headerLocationAndSearch">
				<div>
					<input type="text" id="txtSearchGlobal" value="" onkeyup="doSearch(event);" />
					<input type="submit" value="Search" name="btnSearchGlobal" onclick="location.href='/search?s=' + encodeURIComponent($(this).prev('input').val());" />
				</div>

				<div><a href="/location.php"><?php
					if(!isset($_SESSION["locdesc"])){
						if(!isset($_SESSION["locregion"])){
							$_SESSION["locdesc"] = "";
						}else{
							$conn = DBConnect();
							if($conn){
								if(isset($_SESSION["locarea"])){
									$sql = "select lr.LocationRegionName, la.LocationAreaName from evplocationregion lr inner join evplocationarea la on lr.LocationRegionId = la.LocationRegionId where la.LocationAreaId=" . ParseS($_SESSION["locarea"]);
								}else{
									$sql = "select lr.LocationRegionName from evplocationregion lr where lr.LocationRegionId=" . ParseS($_SESSION["locregion"]);
								}
								if($result = mysqli_query($conn, $sql)){
									if (mysqli_num_rows($result) == 1) {
										while($row = mysqli_fetch_assoc($result)) {
											if(isset($_SESSION["locarea"])){
												$_SESSION["locdesc"] = $row["LocationAreaName"] . ", " . $row["LocationRegionName"];
											}else{
												$_SESSION["locdesc"] = $row["LocationRegionName"];
											}
										}
									}
									mysqli_free_result($result);
								}
								mysqli_close($conn);
							}
						}
					}
					echo $_SESSION["locdesc"];
				?></a></div>

			</div>

			<span><?php
			if(isset($_SESSION["mn"])){
				echo $_SESSION["mn"];
			}else{
				echo "Guest";
			}
			?></span><br />
			
			<?php
			if(isset($_SESSION["mn"])){
				?>
				<span><a href="/Member/Logout">Logout</a></span>
				<?php
			}else{
				?>
				<span><a href="/Member">Logon</a></span> <span>or</span>
				<span><a href="/Member/Register">Register</a></span>
				<?php
			}
			?>

			<div style="clear:both;"></div>

		</div>
				
		<div style="padding:5px;" class="backgroundColorAlt">
		
			<div class="navImage">
				<a href="/"><img src="/Images/icon.png" alt="Show Lineup" /></a>
			</div>


			<div class="navigation" style="padding-top:2px;">
				<div id="divNavLinks">
					<?php
					DL_HeaderNav();
					
					if(isset($_SESSION["mi"])){ ?>
						<div class="navItem">
							<div class="addSomething">
								<select onchange="addNew(this.value);">
									<option value="">-Add-</option>
									<option value="f">Festival</option>
									<option value="s">Show</option>
									<option value="p">Performer</option>
									<option value="v">Venue</option>
									<option value="h">Host</option>
								</select>
							</div>
						</div><?php
					}
					?>
					<div style="clear:both;"></div>
				</div>
			
			</div>
			<div style="clear:both;"></div>
		</div>

		
		<div class="headerMemberStuff"></div>
		<div style="clear:both;"></div>
	</div>
	<div class="backgroundColor pageTitle"><?php if(isset($pageTitle)) echo $pageTitle; ?></div>
	<div class="mainContent">
		
<?php
}

?>