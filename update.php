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
		} catch (Exception $e) {die($e->getMessage());}		
	} else {
			foreach ($validation->errors as $error) {
				echo $error, '<br>';
			}
	}
?>
<form action="" method="post">
	<div class="field">
		<label for="first_name">First Name</label>
		<input type="text" name="name" value="<?php echo escape($user->data()->first_name); ?>">

		<input type="submit " value="Update">
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">	
	</div>
</form> 