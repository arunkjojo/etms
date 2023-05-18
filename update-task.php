<?php

require 'authentication.php'; // admin authentication check 

// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
	header('Location: index.php');
}

// check admin
$user_role = $_SESSION['user_role'];

$task_id = $_GET['task_id'];



if (isset($_POST['add_punch_out'])) {
	$obj_admin->add_punch_out($_POST);
}

$page_name = "Update Task Comments";
include("include/sidebar.php");

$sql = "SELECT * FROM `task_info` WHERE `task_id`='$task_id' ";
$info = $obj_admin->manage_all_info($sql);
$row = $info->fetch(PDO::FETCH_ASSOC);

if ($user_role == 1) {
	$sql = "SELECT `a`.*, `b`.`fullname` FROM `attendance_info` `a` LEFT JOIN `tbl_admin` `b` ON(`a`.`atn_user_id` = `b`.`user_id`) WHERE `task_id`=$task_id ORDER BY `a`.`aten_id` DESC";
} else {
	$sql = "SELECT `a`.*, `b`.`fullname` FROM `attendance_info` `a` LEFT JOIN `tbl_admin` `b` ON(`a`.`atn_user_id` = `b`.`user_id`) WHERE `atn_user_id` = $user_id AND `task_id`=$task_id ORDER BY `a`.`aten_id` DESC";
}

$att_info = $obj_admin->manage_all_info($sql);
$num_row = $att_info->rowCount();
if ($num_row == 0) {
	echo '<tr><td colspan="7">No Data found</td></tr>';
}
$att_row = $att_info->fetch(PDO::FETCH_ASSOC);

?>

<!--modal for employee add-->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="row">
	<div class="col-md-12">
		<div class="well well-custom rounded-0">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="well rounded-0">
						<h3 class="text-center bg-primary" style="padding: 7px;">Comment Task Updates</h3><br>

						<div class="row">
							<div class="col-md-12">
								<form class="form-horizontal" role="form" action="" method="post" autocomplete="off">
									<div class="form-group">
										<label class="control-label text-p-reset">Task Title</label>
										<div class="">
											<input type="text" placeholder="Task Title" id="task_title" name="task_title" list="expense" class="form-control rounded-0" value="<?php echo $row['t_title']; ?>" <?php if ($user_role != 1) { ?> readonly <?php } ?> required>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label text-p-reset">Task Updates</label>
										<div class="">
											<textarea name="task_update" id="task_update" placeholder="Text Updates" class="form-control rounded-0" rows="5" cols="5" autofocus></textarea>
										</div>
									</div>
									<div class="form-group"></div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-1"></div>
										<div class="col-sm-4">
											<input type="hidden" name="punch_in_time" value="<?php echo $att_row['in_time']; ?>">
											<input type="hidden" name="aten_id" value="<?php echo $att_row['aten_id']; ?>">
											<input type="hidden" name="task_id" value="<?php echo $att_row['task_id']; ?>">

											<button type="submit" name="add_punch_out" class="btn btn-primary-custom">Update Now</button>
										</div>
									</div>

									<div class="form-group">
										<a title="Attendance" class="text-info" href="attendance-info.php?tId=<?php echo $task_id; ?>">Go Back</a>
									</div>
								</form>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
	flatpickr('#t_start_time', {
		enableTime: true
	});

	flatpickr('#t_end_time', {
		enableTime: true
	});
</script>


<?php
include("include/footer.php");

?>