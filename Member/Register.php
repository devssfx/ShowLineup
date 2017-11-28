<?php
$pageTitle = "Register";
include "../include/db.php";
include "../include/dl_member.php";
include "../include/header.php";


$success = 1;

if(isset($_POST["MemberName"])){
	$memberName = $_POST["MemberName"];
	$memberEmail = $_POST["MemberLogin"];
	$memberPassword = $_POST["MemberPassword"];
	
	$success = DL_MemberCreate($memberName, $memberEmail, $memberPassword);

	if($success == 0){
		header('Location:../');
	}
	
}


HeaderDraw();
?>



		

<script src="../Scripts/jquery.validate.min.js" type="text/javascript"></script>
<script src="../Scripts/jquery.validate.unobtrusive.min.js" type="text/javascript"></script>

<?php
	if($success == 0){
		echo '<div class="input-validation-error">There was a problem creating your membership. Please try again. It this continues, contact us for help.</div>';
	}
?>

<form action="Register" method="post">
	<div>
		<label for="MemberName">Name</label>
	</div>
	<div>
		<input data-val="true" data-val-required="The Name field is required." id="MemberName" name="MemberName" type="text" value="" />
		<span class="field-validation-valid" data-valmsg-for="MemberName" data-valmsg-replace="true"></span>
	</div>
	<div>
		<label for="MemberLogin">Email or Phone</label>
	</div>
	<div>
		<input data-val="true" data-val-required="The Email or Phone field is required." id="MemberLogin" name="MemberLogin" type="text" value="" />
		<span class="field-validation-valid" data-valmsg-for="MemberLogin" data-valmsg-replace="true"></span>
	</div>
	<div>
		<label for="MemberPassword">Password</label>
	</div>
	<div>
		<input data-val="true" data-val-length="The Password must be at least 8 characters long." data-val-length-max="100" data-val-length-min="8" data-val-required="The Password field is required." id="MemberPassword" name="MemberPassword" type="password" />
		<span class="field-validation-valid" data-valmsg-for="MemberPassword" data-valmsg-replace="true"></span>
	</div>
	<div>
		<label for="ConfirmPassword">Confirm password</label>
	</div>
	<div>
		<input data-val="true" data-val-equalto="The password and confirmation password do not match." data-val-equalto-other="*.MemberPassword" id="ConfirmPassword" name="ConfirmPassword" type="password" />
		<span class="field-validation-valid" data-valmsg-for="ConfirmPassword" data-valmsg-replace="true"></span>
	</div>
	<div id="divRegAgreeTC" style="padding-top:3px;padding-bottom:5px;">
		<label for="TermsAndCond">You agree with the Terms and Conditions</label> found <a href="/Member/TandCs" onclick="window.open(this.href);return false;">here</a>.
		<input data-val="true" data-val-required="The You agree with the Terms and Conditions field is required." id="TermsAndCond" name="TermsAndCond" type="checkbox" value="true" /><input name="TermsAndCond" type="hidden" value="false" />
		<span class="field-validation-valid" data-valmsg-for="TermsAndCond" data-valmsg-replace="true"></span>
	</div>
    <div style="padding-top:5px;">
        <input type="submit" value="Register" />
    </div>
</form>

<?php if(false){ ?>
<script src="../Scripts/Me/fb.js"></script>
<div id="divLoginAlt" style="height:22px;margin-top:3px;">
	<div id="divFacebookLogin">
		<fb:login-button scope="public_profile,email" redirect="http://showlineup.com/login?fb=1" onlogin="checkLoginState();"></fb:login-button>
	</div>
</div>
<div id="status"></div>
<?php } ?>



<?php
include "../include/footer.php";
?>
