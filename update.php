<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(Input::exists()) {
	if (Token::check(Input::get('token'))) {

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'first_name' => array(
				'required' => true,
				'min' => 2,
				'max' => 20)));
	}

	if ($validtion->passed()) {
		
		try {
			$user->update(array(
				'name' => Input::get('name')));
				//$user->update(array('name' => Input::get('name')),4); ---- this is where user id can be specified for admin use
			Session::flash('home','Your information has been updated.');
			Redirect::to('index.php');
		} catch (Exception $e) {die($e->getMessage());}		
	} else {
			foreach ($validation->errors as $error) {
				echo $error, '<br>';
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TEST PAGE</title>
</head>
<!-- CSS -->
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<body id="public">
<div id="container">
<form name="update" action="" method="post">	
	<table width="510" border="0" align="center">
		
		<tr><td colspan="2" align="center"><h1>Update Your Profile</h1></td></tr>	
		<tr>
			<td align="left"><input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off"></td>
		</tr>
		<label for="first_name"></label>
		<input type="text" name="name" value="<?php echo escape($user->data()->first_name); ?>">
		<input type="submit " value="Update">
		</table>
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">	
	</div>
</form>
</body>
</html>