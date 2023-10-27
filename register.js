function validateSignupForm(event){
	event.preventDefault();

	var fname = document.forms["signupForm"]["Fname"].value;
	var lname = document.forms["signupForm"]["Lname"].value;
	var username = document.forms["signupForm"]["username"].value;
    var email = document.forms["signupForm"]["email"].value;
    var password = document.forms["signupForm"]["password"].value;
    var confirmPassword = document.forms["signupForm"]["confirmPassword"].value;

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

	if(isValid){
		document.forms["signupForm"].submit();
	}

	if(!isValid) {
		var errorMessages = {
			emptyError: emptyError.innerHTML,
			nameError: nameError.innerHTML,
			idError: idError.innerHTML,
			emailError: emailError.innerHTML,
			passwordError: passwordError.innerHTML,
			matchError: matchError.innerHTML
		};

        // Send error messages to PHP script using AJAX
        var xhr = new XMLHttpRequest();
		var url = "register.php";
		var method = "POST";
        xhr.open("POST", "handle_errors.php", true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Display error messages returned by PHP script
                var response = JSON.parse(this.responseText);
                emptyError.innerHTML = response.emptyError;
                nameError.innerHTML = response.nameError;
                idError.innerHTML = response.idError;
                emailError.innerHTML = response.emailError;
				passwordError.innerHTML = response.passwordError;
				matchError.innerHTML = response.matchError;
            }
        };
        var json = JSON.stringify(errorMessages);
		xhr.send("errorMessages=" + json);
        return false;
	}
}

function validateEmail(email) {
	var re = /\S+@\S+\.\S+/;
	return re.test(email);
}