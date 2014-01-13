<?php
require_once 'core/init.php';

if (Session::exists('success')) {echo Session::flash('success');}
if (Session::exists('home')) {echo Session::flash('home');}
if (Session::exists('login')) {echo Session::flash('login');}

$user = new User();

if ($user->isLoggedIn()) {?>
	<ul>
		<li><a href="logout.php">Log Out</a></li>
		<li><a href="changepass.php">Change Your Password</a></li>
	</ul><?php
	if ($user->hasPermission('admin')) {
		echo 'Admin';
	}
} else {
		Session::flash('login','Please Login');
		Redirect::to('login.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HOME PAGE</title>
</head>
<!-- CSS -->
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />
<body id="public">
<div id="container">
<form name="home" action="login.php" method="post">	
	<table width="510" border="0" align="center">
		<tr><td colspan="2" align="center"><h1>Welcome to the Login System Test Page</h1></td></tr>
		<tr><td colspan="2" align="center"><h1><img id="logo" src="images/psy-gangnam-style-6.gif"></h1></td></tr>
	</table>	
</form>
</body>
</html> 