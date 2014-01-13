<?php

require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'currentPassword' => array(
				'required' => true
			),
			'newPassword' => array(
				'required' => true,
				'min' => 6
			),
			'newPasswordAgain' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'newPassword'
			)
		));
		
		if ($validation->passed()) {
			if (Hash::make(Input::get('currentPassword'), $user->data()->salt) === $user->data()->password) {
				$salt = Hash::salt(32);
				try {
					$user->update( array(
						'password' => Hash::make(Input::get('newPassword'), $salt),
						'salt' => $salt));
					Session::flash('home','Your Password Has Been Changed Succesfuly');
					Redirect::to('index.php');
				} catch (Exception $e) {die($e->getMessage());} 
			} else {
				echo "Current Password is Incorrect!";
			}

		} else {
			$localErrors = array(); 
				foreach ($validation->getError() as $type => $error) {$localErrors[$type] = $error;}
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
<title>Password Change Form</title>
</head> 
<body id="public">
<div id="container">

<form name="changepass" action="" method="post">

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">		

	<table width="510" border="0" align="center">
		<tr><td colspan="2" align="center"><?php 
			if (isset($localErrors)) {
				echo '<p color ="red"><h2>User not Found!</h2><ul>';
				foreach ($localErrors as $type => $error) {print('<li>'.$error.'</li>');}
				echo '<ul></p>';
			}else {echo'&nbsp;';}?></td></tr>
		<tr>
		<tr>
			<td colspan="2" align="center"><p><h1><strong><?php echo escape($user->data()->username); ?></strong></h1></p></td>
		</tr>
		<tr>
			<td align="right">CURRENT PASSWORD:</td>
			<td align="left"><input type="password" name="currentPassword" id="currentPassword" /></td>
		</tr>
		<tr>
			<td align="right">NEW PASSWORD:</td>
			<td align="left"><input type="password" name="newPassword" id="newPassword" /></td>
		</tr>
		<tr>
			<td align="right">CONFRIM NEW PASSWORD:</td>
			<td align="left"><input type="password" name="newPasswordAgain" id="newPasswordAgain" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align="center" colspan="2" class="submitbutton"><input type="submit" name="button" id="button" value="SUBMIT" onClick="return confirm('Confirm Password Change');" /></td>
  		<tr>
	</table>
</form>
</body>
</html> 