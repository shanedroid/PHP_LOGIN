<?php
require_once 'core/init.php';

if (Session::exists('success')) {echo Session::flash('success');}
if (Session::exists('home')) {echo Session::flash('home');}
if (Session::exists('login')) {echo Session::flash('login');}

$user = new User();

if ($user->isLoggedIn()) {?>
	<ul>
		<li><a href="logout.php">Log Out</a></li>
	</ul><?php
} else {
	echo '<p> You must <a href="login.php">login</a> in or <a href="register.php">register</a> in order to access this page</p>';
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
<form name="home" action="login.php" method="post">	
	<table width="510" border="0" align="center">
		<tr><td colspan="2" align="center"><h1><img id="logo" src="images.jpg"></h1></td></tr>
		<tr><td colspan="2" align="center"><h1>Welcome to the Login System Test Page</h1></td></tr>
		<tr><td colspan="2" align="center"><h1><img id="logo" src="images/psy-gangnam-style-6.gif"></h1></td></tr>
		<tr>
			<td align="center" colspan="2" class="submitbutton"><input type="submit" name="button" id="button" value="LOGOUT" onClick=<?php session_destroy(); ?>/></td>
  		<tr>
	</table>
	
</form>
</body>
</html> 