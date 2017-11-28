// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response, fromLogin) {
	console.log('statusChangeCallback');
	console.log(response);
	
	// The response object is returned with a status field that lets the
	// app know the current login status of the person.
	// Full docs on the response object can be found in the documentation
	// for FB.getLoginStatus().
	if (response.status === 'connected') {
		// Logged into your app and Facebook.
		if (fromLogin) {

			//var accessToken = response.authResponse.accessToken;
			//var userId = authResp.userID;

			LoginToWebsiteViaFacebook(response.authResponse.accessToken, response.authResponse.userID);
		}
		//document.getElementById('btnFacebookLogout').style.display = 'none';

	} else if (response.status === 'not_authorized') {
		// The person is logged into Facebook, but not your app.
		//document.getElementById('status').innerHTML = 'Please log into this app.';
	} else {
		console.log('No Dice!');
		// The person is not logged into Facebook, so we're not sure if they are logged into this app or not.
		//document.getElementById('status').innerHTML = 'Please log into Facebook.';
		//var divFBLogin = document.getElementById('divFacebookLogin');
		//if (divFBLogin) {
		//	divFBLogin.style.display = 'block';
		//}
	}
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function FBCheckLoginState() {
	FB.getLoginStatus(function (response) {
		statusChangeCallback(response, true);
	});
}

window.fbAsyncInit = function () {
	FB.init({
		appId: '1594933790763830',
		cookie: true,  // enable cookies to allow the server to access 
		// the session
		xfbml: true,  // parse social plugins on this page
		version: 'v2.2' // use version 2.2
	});

	// Now that we've initialized the JavaScript SDK, we call 
	// FB.getLoginStatus().  This function gets the state of the
	// person visiting this page and can return one of three states to
	// the callback you provide.  They can be:
	//
	// 1. Logged into your app ('connected')
	// 2. Logged into Facebook, but not your app ('not_authorized')
	// 3. Not logged into Facebook and can't tell if they are logged into
	//    your app or not.
	//
	// These three cases are handled in the callback function.

	FB.getLoginStatus(function (response) {
		statusChangeCallback(response, false);
	});

};

// Load the SDK asynchronously
(function (d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
} (document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function LoginToWebsiteViaFacebook(accessToken, userID) {
	console.log('Welcome!  Fetching your information.... ');


	var agreeTC = false;
	var chkTC = document.getElementById('divRegAgreeTC');
	if (chkTC) {
		chkTC = chkTC.getElementsByTagName('input');
		if (chkTC) {
			if (chkTC.length > 0) {
				if (chkTC[0].checked) {
					agreeTC = true;
				} else {
					alert('You must agree to the Terms and Conditions before registering.');
					return;
				}
			}
		}
	}


	FB.api('/me', function (response) {

	//var response = { id: '10152783835312353', name: 'test facebook', email: 'test@two.com' };	

		console.log('Doing login');
		console.log('Successful login for: ' + response.name);
		console.log(response);



		var actionUrl = '/ajax/LoginViaFacebook';
		$.post(actionUrl, {
			FacebookId: response.id,
			MemberName: response.name,
			MemberEmail: response.email,
			AccessToken: accessToken,
			UserID : userID,
			AgreeTC : agreeTC.toString()
		})
			.fail(function () {
				alert('There was a problem. Reload the page and try again.');
			})
			.done(function (data) {
				var success = data[0].Success;

				if (success == 1) {
					alert('There was a problem. Reload the page and try again.');
				} else if (success == 0) { //success
					location.href = '/';
				} else if (success == 2) { //didn't agree to t&c, ie: on login page or something went wrong with reg page
					alert('You must register using Facebook on the registration page before you can login with Facebok.');
				} else {
					location.href = '/';
				}
			});



		//document.getElementById('status').innerHTML = 'Thanks for logging in, ' + response.name + '!';
	});
}




//function LoginToWebsiteViaFacebookPart2() {
//	FB.login(function (response) {

//		if (response.status === 'connected') {
//			// Logged into your app and Facebook.

//			


//		} else if (response.status === 'not_authorized') {
//			// The person is logged into Facebook, but not your app.
//		} else {
//			// The person is not logged into Facebook, so we're not sure if
//			// they are logged into this app or not.
//		}


//	}, { scope: 'public_profile,email' });

//}

//function facebookLogout() {
//	FB.logout(function (response) {
//		location.href = location.href;
//	});
//}
