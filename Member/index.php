<?php


$pageTitle = "Member Login";
include "../include/db.php";
include "../include/dl_member.php";
include "../include/header.php";


$success = 1;

if(isset($_POST["MemberLogin"])){
	
	$success = 0;
	
	$memberLogin = $_POST["MemberLogin"];
	$memberPassword = $_POST["MemberPassword"];
	
	$remember = false;
	if(isset($_POST["RememberMe"])){
		$remember = true;
	}

	$success = DL_MemberLogin($memberLogin, $memberPassword, $remember);

	if($success == 1){
		header('Location:' . "../");
	}
	
	
	
}


HeaderDraw();


?>





<script src="../Scripts/jquery.validate.min.js" type="text/javascript"></script>
<script src="../Scripts/jquery.validate.unobtrusive.min.js" type="text/javascript"></script>


<?php
	if($success == 0){
		echo '<div class="input-validation-error">Invalid Login.</div>';
	}
?>

<form action="." method="post">	<div>
		<label for="MemberLogin">Email or Phone</label>
	</div>
	<div>
		<input data-val="true" data-val-required="The Email or Phone field is required." id="MemberLogin" name="MemberLogin" type="text" value="<?php if($_SERVER['HTTP_HOST'] == 'localhost'){ echo 'justin@test.com'; } ?>" />
		<span class="field-validation-valid" data-valmsg-for="MemberLogin" data-valmsg-replace="true"></span>
	</div>
	<div>
		<label for="MemberPassword">Password</label>
	</div>
	<div>
		<input data-val="true" data-val-length="The Password must be at least 8 characters long." data-val-length-max="100" data-val-length-min="8" data-val-required="The Password field is required." id="MemberPassword" name="MemberPassword" type="password" value="<?php if($_SERVER['HTTP_HOST'] == 'localhost'){ echo 'testtest'; } ?>" />
		<span class="field-validation-valid" data-valmsg-for="MemberPassword" data-valmsg-replace="true"></span>
	</div>
    <div>
        <input data-val="true" data-val-required="The Remember me? field is required." id="RememberMe" name="RememberMe" type="checkbox" value="true" />
        <label for="RememberMe">Remember me?</label>
    </div>
    <div>
        <input type="submit" value="Log On" />
    </div>
</form>

<?php if(false){ ?>
<script src="../Scripts/Me/fb.js"></script>
<div id="divLoginAlt" style="height:22px;margin-top:3px;">
	<div id="divFacebookLogin">
		<fb:login-button scope="public_profile,email" redirect="http://showlineup.com/login?fb=1" onlogin="checkLoginState();"></fb:login-button>
	</div>
</div>
<?php } ?>
<div id="status"></div>



<?php
include "../include/footer.php";
?>
