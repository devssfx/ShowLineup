<?php
include "../include/db.php";

$info = "";

if(isset($_POST["btnDelete"])){
	if(isset($_POST["txtPassword"])){
		if($_POST["txtPassword"] == 'kidyom'){
			$info = $info . 'Delete Started.</br>';
			$conn = DBConnect();
			if($conn){
				$conn->query('delete from evpshowadmin');
				$conn->query('delete from evpshowdateperformer');
				$conn->query('delete from evpshowdate');
				$conn->query('delete from evpshowhost');
				$conn->query('delete from evpshow');
				$conn->query('delete from evpfestivaladmin');
				$conn->query('delete from evpfestival');
				$conn->query('delete from evphostadmin');
				$conn->query('delete from evphostliked');
				$conn->query('delete from evphost');
				$conn->query('delete from evpperformeradmin');
				$conn->query('delete from evpperformerliked');
				$conn->query('delete from evpperformerlocation');
				$conn->query('delete from evpperformermember');
				$conn->query('delete from evpperformer');
				$conn->query('delete from evpvenueadmin');
				$conn->query('delete from evpvenueliked');
				$conn->query('delete from evpvenue');	
				$info = $info . 'Delete Succesful.</br>';
				
				if(isset($_POST["chkDeleteMember"])){
					$conn->query('delete from evpMember');
					$info = $info . 'Member Delete Succesful.</br>';
				}
				
				mysqli_close($conn);	
			}
						
		}
	}
}
	
?>

<html>
	<head>
	<title>Del Data</title>
	</head>
	<body>
	<?php
	if(strlen($info) > 0){
		echo '<div>' . $info . '</div>';
	}
	?>
	
	
		<form action="deletedata.php" method="post">
			<h1>This will clear the data from the database. Click the button to proceed.</h1>
			<div>
				<input type="checkbox" value="1" name="chkDeleteMember" /> Delete Member info also?
			</div>
			<div>
				<input type="text" name="txtPassword" />
			</div>
			<div>
				<input type="submit" value="Delete Data" name="btnDelete" />
			</div>
		</form>
	</body>
</html>

