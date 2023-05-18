<?php
require 'authentication.php'; // admin authentication check 

// auth check
if ($user_id == NULL || $security_key == NULL) {
	header('Location: index.php');
}

if (isset($_POST['change_password_btn'])) {
	$info = $obj_admin->change_password_for_employee($_POST);
}

$page_name = "Login";
include("include/login_header.php");

?>

<div class="row">
	<div class="col-md-4 col-md-offset-3">
		<div class="well" style="position:relative;top:20vh;">
			<form class="form-horizontal form-custom-login" action="" method="POST">
				<div class="form-heading" style="background: orange;">
					<h2 class="text-center">Please Change your password</h2>
				</div>
				<!-- <div class="login-gap"></div> -->
				<?php if (isset($info)) { ?>
					<h5 class="alert alert-danger"><?php echo $info; ?></h5>
				<?php } ?>

				<div class="input-group" id="show_hide_password">
					<input type="password" class="form-control" placeholder="Password" name="password" required />
					<div class="input-group-addon">
						<a href=""><i class="glyphicon glyphicon-eye-close"></i></a>
					</div>
				</div>
				<div class="input-group" id="show_hide_re_password">
					<input type="password" class="form-control" placeholder="Retype Password" name="re_password" required />
					<div class="input-group-addon">
						<a href=""><i class="glyphicon glyphicon-eye-close"></i></a>
					</div>
				</div>
				<input type="hidden" class="form-control" name="user_id" value="<?php echo $user_id; ?>" required />
				<button type="submit" name="change_password_btn" class="btn btn-default pull-right">Change Password</button>
			</form>
		</div>
	</div>
</div>


<?php
include("include/footer.php");

?>