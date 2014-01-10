<?php
require_once 'core/init.php';

if (Input::exists()) {
	if ((Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'user name' => array('required' => true),
			'password' => array('required' => true)));

		if ($validtion->passed()) {
			$user = new User();

			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('username'),Input::get('password'), $remember);

			if ($login) {
				Session::flash('login','Thank you '.escape($user->data()->username).', for logging in');
				Redirect::to('index.php');
			}
		} else { 
			foreach ($validation->errors() as $error) {
				echo $error, '<br>';
			}

		}
	}
}
$username = $_POST['username'];
$password = $_POST['password'];

$errmsg = array();
$errflag = false;

$mysqlconn = new PDO('mysql:host=localhost;dbname=','','');

//initial checks for empty values
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
	
$qry = "SELECT id, username, password, salt
        FROM users
        WHERE username = :username";
 
$result = $mysqlconn->prepare($qry);
$result->bindParam(":username", $username);
$result->execute();

$number_of_rows = $result->rowCount();

// Check to make sure at least 1 user has been found with the given username
if (!$errflag){
	if($number_of_rows == 0) {
		$errmsg[] = 'User not found. Incorrect Credentials Entered.';
		$errflag = true;
	}
}

// check the password - needs to be performed seperately due to the salt & hash
$userData  = $result->fetch(PDO::FETCH_ASSOC);
//recreate same hash as was used when user was registered
$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );

if (!$errflag){	
	if($hash != $userData['password']) {
    	$errmsg[] = 'User not found. Incorrect Credentials Entered.';
		$errflag = true;
	}
}

//redirect if any errors were found
if ($errflag) {
	$_SESSION['ERRMSG'] = $errmsg;
	session_write_close();
	header("location: index.php");
	exit();
}


//redirect to home page if user is validated 
if ($hash == $userData['password']){ 
	session_regenerate_id();
	$_SESSION['sess_user_id'] = $userData['id'];
	$_SESSION['sess_username'] = $userData['username'];
	$_SESSION[ 'logged_in' ] = true;
	session_write_close();
	header('Location: home.php');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- CSS -->
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<title>OSA Login Form</title>
</head>
<body id="public">
<div id="container">
<form name="login" action="" method="post">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo Input::get('username'); ?>" autocomplete="off">
	</div>	

	<div class="field">
		<label for="password">Password</label>
		<input type="password" name="password" id="password" autocomplete="off" >
	</div>

	<div class="field">
		<label for="remember"><input type="checkbox" name="remember" id="remember" >Remember Me</label>
	</div>

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">	

	<table width="510" border="0" align="center">
		<tr><td>&nbsp;</td></tr>
		<tr><td colspan="2" align="center"><h1><img id="logo" src="images.jpg"></h1></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td colspan="2" align="center">
			<?php
				if( isset($_SESSION['ERRMSG']) && is_array($_SESSION['ERRMSG']) && count($_SESSION['ERRMSG']) >0 ) {
					echo '<ul style="padding:0; color:red;">';
					foreach($_SESSION['ERRMSG'] as $msg) {
						echo '<li>',$msg,'</li>'; 
					}
				echo '</ul>';
				unset($_SESSION['ERRMSG']);
				}
			?></td>
		</tr>
		<tr>
			<td align="right">USERNAME:</td>
			<td align="left"><input type="text" name="username" id="username" /></td>
		</tr>
		<tr>
			<td align="right">PASSWORD:</td>
			<td align="left"><input type="password" name="password" id="password" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center" colspan="2" class="submitbutton"><input type="submit" name="button" id="button" value="Log In" /></td>
  		<tr>
	</tr>
	</table>
</form>
</body>
</html>

