<?php
require_once 'core/init.php';

if (Input::exists()) {

	if (Token::check(Input::get('token'))) {

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true),
			'password' => array(
				'required' => true)));


		if ($validation->passed()) {


			$user = new User();
			$remember = (Input::get('remember') === 'on') ? true : false;

			$login = $user->login(Input::get('username'),Input::get('password'), $remember);
			
			if ($login) {
				Session::flash('login','Thank you '.escape($user->data()->username).', for logging in');
				Redirect::to('index.php');
			} else {
				$localErrors = array(); 
				foreach ($validation->getError() as $type => $error) {$localErrors[$type] = $error;}				
				//Session::flash('error','User not found!');
				//Redirect::to('login.php');
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
<title>OSA Login Form</title>
</head>
<body id="public">
<div id="container">
<form name="login" action="" method="post">
	<table width="310" border="0" align="center">
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td colspan="2" align="center"><?php 
			if (isset($localErrors)) {
				echo '<p><h2>User not Found!</h2><ul>';
				foreach ($localErrors as $type => $error) {print('<li>'.$error.'</li>');}
				echo '<ul></p>';
			} else if (Session::exists('login')) {echo Session::flash('login');}
			else {echo'&nbsp;';}?></td></tr>
		<tr>
			<td align="right">USERNAME:</td>
			<td align="left"><input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off"></td>
		</tr>
		<tr>
			<td align="right">PASSWORD:</td>
			<td align="left"><input type="password" name="password" id="password" autocomplete="off"></td>
			<td></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td align="left"><label for="remember"><input type="checkbox" name="remember" id="remember" >Remember Me</label></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center" colspan="2" class="submitbutton"><input type="submit" name="button" id="button" value="LOG IN" /></td>
	</tr>
	</table>

<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">	

</form>
</body>
</html>