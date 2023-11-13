function validateSignup(){
	document.querySelector('form').addEventListener('submit', function(event) {
		event.preventDefault();

		var fname = document.getElementById("Fname").value;
		var lname = document.getElementById("Lname").value;
		var username = document.getElementById("username").value;
		var email = document.getElementById("email").value;
		var password = document.getElementById("password").value;
		var confirmPassword = document.getElementById("confirmPassword").value;


		var emptyError = document.getElementById("emptyError");
		var nameError = document.getElementById("nameError");
		var idError = document.getElementById("idError");
		var emailError = document.getElementById("emailError");
		var passwordError = document.getElementById("passwordError");
		var matchError = document.getElementById("matchError");

		emptyError.innerHTML = "";
		nameError.innerHTML = "";
		idError.innerHTML = "";
		emailError.innerHTML = "";
		passwordError.innerHTML = "";
		matchError.innerHTML = "";

		var isValid = true;
		if (fname == "" || lname == "" || username == "" || email == "" || password == "" || confirmPassword == "") {
			emptyError.innerHTML = "Please fill out all fields";
			isValid = false;
		}
		if(fname.length > 20 || lname.length > 20 || !isNaN(fname) || !isNaN(lname)){
			nameError.innerHTML = "First and last name must be less than 20 characters and cannot contain numbers";
			isValid = false;
		}
		if(isNaN(username) || username.length != 7){
			idError.innerHTML = "Invalid UH ID";
			isValid = false;
		}
		if(password != confirmPassword){
			matchError.innerHTML = "Passwords do not match";
			isValid = false;
		}
		if(password.length < 8){
			passwordError.innerHTML = "Password must be at least 8 characters";
			isValid = false;
		}
		if(validateEmail(email) == false){
			emailError.innerHTML = "Invalid email address";
			isValid = false;
		}
		if (isValid)
		{
			this.submit();
		}
	});
}

//send to insertUser.php
function insertUser() {
    var fname = document.getElementById("Fname").value;
    var lname = document.getElementById("Lname").value;
    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    var securityQuestion1 = document.getElementById("securityQuestion1").value;
    var securityQuestion2 = document.getElementById("securityQuestion2").value;
    var securityQuestion3 = document.getElementById("securityQuestion3").value;
    var securityAnswer1 = document.getElementById("securityAnswer1").value;
    var securityAnswer2 = document.getElementById("securityAnswer2").value;
    var securityAnswer3 = document.getElementById("securityAnswer3").value;

    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "register.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Construct the POST data
    var postData = "fname=" + encodeURIComponent(fname) + "&lname=" + encodeURIComponent(lname) + "&username=" + encodeURIComponent(username) + "&email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password) + "&confirmPassword=" + encodeURIComponent(confirmPassword) + "&securityQuestion1=" + encodeURIComponent(securityQuestion1) + "&securityQuestion2=" + encodeURIComponent(securityQuestion2) + "&securityQuestion3=" + encodeURIComponent(securityQuestion3) + "&securityAnswer1=" + encodeURIComponent(securityAnswer1) + "&securityAnswer2=" + encodeURIComponent(securityAnswer2) + "&securityAnswer3=" + encodeURIComponent(securityAnswer3);

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert("account created successfully");
            window.location.href = 'index.php';
        }
    };

    xhttp.send(postData);
}


function validateEmail(email) {
	var re = /\S+@\S+\.\S+/;
	return re.test(email);
}