<?php
require_once 'core/init.php';

if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'user name' => array(
				'required' => true,
				'min' => 2,
				'max' => 30,
				'unique' => 'users'),
			'password' => array(
				'required' => true,
				'min' => 6),
			'password2' => array(
			'required' => true,
				'matches' => 'password',),
			'first_name' => array(
				'required' => true,
				'min' => 2,
				'max' => 20),
			'last_name' => array(
				'required' => true,
				'min' => 2,
				'max' => 20),
			'email' => array(
				'required' => true,
				'min' => 10,
				'max' => 50), 
		if ($validtion->passed()) {
			//Session::flash('success', 'User has been registered Succesfuly');
			//header('Location: index.php');
			$user = new User()

			$salt = Hash::salt(32); 
			try {
			 	$user->create( array(
					'username' => Input::get('username'), 
					'password' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'first_name' => Input::get('first_name'),
					'last_name' => Input::get('last_name'),  
					'applications' => '1'
			 		));
			 	Session::flash('home','User has been registered Succesfuly and can now log in');
			 	Redirect::to('index.php');
			 } catch (Exception $e) {
			 	die($e->getMessage());
			 	//redirect to error page?
			 } 
		} else {
			foreach ($validation->errors as $error) {
				echo $error, '<br>';
			}
		}
	}
}
	$errmsg = array();
	$errflag = false;
	if(empty($_POST)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- CSS -->
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<title>OSA Registration Form</title>
</head> 
<body id="public">
<div id="container">
<form name="register" action="" method="post">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo Input::get('username'); ?>" autocomplete="off">
	</div>
	
	<div class="field">
		<label for="password">Password</label>
		<input type="password" name="password" id="password">
	</div>

	<div class="field">
		<label for="password2">Verify Password</label>
		<input type="password" name="password2" id="password2">
	</div>

	<div class="field">
		<label for="first_name">Fist Name</label>
		<input type="text" name="first_name" id="first_name" value="<?php echo escape(Input::get('first_name')); ?>">
	</div>

	<div class="field">
		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" id="last_name" value="<?php echo escape(Input::get('last_name')); ?>">
	</div>

	<div class="field">
		<label for="email">E-mail</label>
		<input type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>">>
	</div>

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">		

	<table width="510" border="0" align="center">
		<tr><td colspan="2" align="center"><h1><img id="logo" src="images/paceofficiallogo.jpg"></h1></td></tr>
		<tr>
			<td colspan="2" align="center"><p><strong>Registration Form</strong></p></td>
		</tr>
		<tr>
			<td align="right">USERNAME:</td>
			<td align="left"><input type="text" name="username" maxlength="20" /></td>
		</tr>
		<tr>
			<td align="right">PASSWORD:</td>
			<td align="left"><input type="password" name="password1" /></td>
		</tr>
		<tr>
			<td align="right">CONFIRM PASSWORD:</td>
			<td align="left"><input type="password" name="password2" /></td>
		</tr>
		<tr>
			<td align="right">EMAIL:</td>
			<td align="left"><input type="text" name="email" id="email" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center" colspan="2" class="submitbutton"><input type="submit" name="button" id="button" value="REGISTER" onClick="return confirm('Confirm Registration Information');" /></td>
  		<tr>
	</table>
</form>
</body>
</html>

<?php
} else {
	$mysqlconn = new PDO('mysql:host=localhost;dbname=', '', '');

	//retrieve DATA from POST
	$username = $_POST['username'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	$email = $_POST['email'];
 
    //check input for errors
	if (!$errflag){
		if($username == '' && $password == '') { 
			$errmsg[] = 'Both Username and Password must be entered';
			$errflag = true;
		}
	}

	if (!$errflag){
		if($username == '') { 
			$errmsg[] = 'Username must be entered';
			$errflag = true;
		}
	}

	if (!$errflag){	
		if($password == '') {
			$errmsg[] = 'Password must be entered';
			$errflag = true;
		}
	}
    
    if(!$errflag) {
		if($password1 != $password2) {
    	$errmsg[] = 'Passwords do not match';
		$errflag = true;
	}
 
	if(strlen($username) > 30)
    	header('Location: registration.html');
 
	$hash = hash('sha256', $password1);

	FUNCTION createSalt() {
    	$text = md5(uniqid(rand(), TRUE));
    	RETURN substr($text, 0, 3);
	}
 
	$salt = createSalt();
	$password = hash('sha256', $salt . $hash);
  
	$qry = $mysqlconn->PREPARE('INSERT INTO users (username, password, email, salt) VALUES (:username, :password, :email, :salt)');
	$result = $qry->EXECUTE(array(':username'=>$username,':password'=>$password,':email'=>$email,':salt:'=>$salt));

	if ($result) {
  	echo "<p>Thank you. User '$username' has been registered</p>";
	} else {
  	echo "<p>Error: there has been a problem registering the user.</p>";
	}
}}
?>