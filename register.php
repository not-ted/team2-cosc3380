
<!DOCTYPE html>
<html lang = "en">

<head>
	<link rel = "stylesheet" href = "index.css">
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, initial-scale=1.0">
	<title>Register</title>
	<script src = "register.js"></script>
</head>

<body>

<div class = "container">

	<h2>Register</h2>

    <?php if (isset($message)) { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>
	
	<?php if(!isset($message)){ ?>
	<p class = "error" id = "emptyError"></p>

	<label for = "Fname">First Name</label><br>
	<input type = "text" name = "Fname" id = "Fname" required><br><br>
	<p class = "error" id = "nameError"></p>

	<label for = "Lname">Last Name</label><br>
	<input type = "text" name = "Lname" id = "Lname" required><br><br>
	<p class = "error" id = "nameError"></p>

	<label for = "username">UH ID</label><br>
	<input type = "text" name = "username" id = "username" required><br><br>
	<p class = "error" id = "idError"></p>

	<label for = "email">Email</label><br>
	<input type = "email" name = "email" id = "email" required><br><br>	
	<p class = "error" id = "emailError"></p>

	<label for="securityQuestion1">Security Question 1</label><br>
	<select name="securityQuestion1" id="securityQuestion1" required>
		<option value="">--Select a security question--</option>
		<option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
		<option value="What is the name of your first pet?">What is the name of your first pet?</option>
		<option value="What is your favorite color?">What is your favorite color?</option>
	</select><br>
	<p class="error" id="securityQuestion1Error"></p><br>

	<label for="securityAnswer1">Answer</label><br>
	<input type="text" name="securityAnswer1" id="securityAnswer1" required><br>
	<p class="error" id="securityAnswer1Error"></p><br>

	<label for="securityQuestion2">Security Question 2</label><br>
	<select name="securityQuestion2" id="securityQuestion2" required>
		<option value="">--Select a security question--</option>
		<option value="What is your favorite food?">What is your favorite food?</option>
		<option value="What high school did you attend?">What high school did you attend?</option>
		<option value="What was the make of your first car?">What was the make of your first car?</option>
	</select><br>
	<p class="error" id="securityQuestion2Error"></p><br>

	<label for="securityAnswer2">Answer</label><br>
	<input type="text" name="securityAnswer2" id="securityAnswer2" required><br>
	<p class="error" id="securityAnswer2Error"></p><br>

	<label for="securityQuestion3">Security Question 3</label><br>
	<select name="securityQuestion3" id="securityQuestion3" required>
		<option value="">--Select a security question--</option>
		<option value="What city were you born in?">What city were you born in?</option>
		<option value="What was the first concert you attended?">What was the first concert you attended?</option>
		<option value="What year was your mother born?">What year was your mother born?</option>
	</select><br>
	<p class="error" id="securityQuestion3Error"></p><br>

	<label for="securityAnswer3">Answer</label><br>
	<input type="text" name="securityAnswer3" id="securityAnswer3" required><br>
	<p class="error" id="securityAnswer3Error"></p><br>

	<label for = "password">Password</label><br>
	<input type = "password" name = "password" id = "password" required><br><br>
	<p class = "error" id = "passwordError"></p>

	<label for = "confirmPassword">Confirm Password</label><br>
	<input type = "password" name = "confirmPassword" id = "confirmPassword" required><br><br>
	<p class = "error" id = "matchError"></p>

	<input type = "submit" value = "Register" onclick = 'validateSignup()'><br><br>
	<?php } ?>

	<a href = "index.php">Login</a>

</div>

</body>