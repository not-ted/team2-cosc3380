function validateSignup(){

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
		insertUser();
	}
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

    // Construct URL with parameters
    var url = "insertUser.php";
    url += "?fname=" + encodeURIComponent(fname);
    url += "&lname=" + encodeURIComponent(lname);
    url += "&username=" + encodeURIComponent(username);
    url += "&email=" + encodeURIComponent(email);
    url += "&password=" + encodeURIComponent(password);
    url += "&confirmPassword=" + encodeURIComponent(confirmPassword);
    url += "&securityQuestion1=" + encodeURIComponent(securityQuestion1);
    url += "&securityQuestion2=" + encodeURIComponent(securityQuestion2);
    url += "&securityQuestion3=" + encodeURIComponent(securityQuestion3);
    url += "&securityAnswer1=" + encodeURIComponent(securityAnswer1);
    url += "&securityAnswer2=" + encodeURIComponent(securityAnswer2);
    url += "&securityAnswer3=" + encodeURIComponent(securityAnswer3);
    xhttp.open("GET", url);

	xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
			alert("account created successfuly");
            window.location.href = 'index.php';
        }
    };

    xhttp.send();
}


function validateEmail(email) {
	var re = /\S+@\S+\.\S+/;
	return re.test(email);
}