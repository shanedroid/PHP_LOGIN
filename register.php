<?php
require_once 'core/init.php';

$user = new User();

if ($user->isLoggedIn()) {
	if (!$user->hasPermission('admin')) {
					Session::flash('home','Only Administrators can register new users!');
					Redirect::to('index.php');
	} else {?>
		<ul>
			<li><a href="logout.php">Log Out</a></li>
			<li><a href="changepass.php">Change Your Password</a></li>
		</ul><?php	
	}
	} else {
		Session::flash('home','You must first login!');
		Redirect::to('index.php');
	}

if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
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
			'campus' => array(
			'required' => true,
				'min' => 3,
				'max' => 3)));
		if ($validtion->passed()) {
			//Session::flash('success', 'User has been registered Succesfuly');
			//header('Location: index.php');
			$user = new User();
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
			} catch (Exception $e) {die($e->getMessage());} 
		} else {
			foreach ($validation->errors as $error) {
				echo $error, '<br>';
			}
		}
	}
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
<title>Registration Form</title>
</head> 
<body id="public">
<div id="container">
<form name="register" action="" method="post">
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">		
	<table width="510" border="0" align="center">
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