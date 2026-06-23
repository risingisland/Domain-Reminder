document.addEventListener("DOMContentLoaded", function() {
	var loginForm = document.getElementById("login-form");
	loginForm.addEventListener("submit", function(event) {
		var rememberMeCheckbox = document.getElementById("remember_me");
		if (rememberMeCheckbox.checked) {
			var usernameInput = document.getElementById("username");
			var username = usernameInput.value;
			var expirationDate = new Date();
			expirationDate.setDate(expirationDate.getDate() + 90);
			document.cookie = "domain_reminder=" + encodeURIComponent(username) + "; expires=" + expirationDate.toUTCString() + "; path=/";
		}
	});
});