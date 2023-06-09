<?php

require 'authentication.php'; // admin authentication check 

// auth check
if ($user_id == NULL || $security_key == NULL) {
  header('Location: index.php');
}
if (isset($_GET['tId']) && $_GET['tId'] != '') {
  $task_id = $_GET['tId'];
} else {
  header("Location: /task-info.php");
}
// if ($user_role != 1) {
//   if (isset($_GET['tId']) && $_GET['tId'] != '') {
//     $task_id = $_GET['tId'];
//   } else {
//     header("Location: /task-info.php");
//   }
// }

if (isset($_GET['delete_attendance'])) {
  $action_id = $_GET['aten_id'];
  $tId = $_GET['task_id'];

  $sql = "DELETE FROM `attendance_info` WHERE `aten_id` = :id";
  $sent_po = "attendance-info.php?tId=" . $tId;
  $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}


if (isset($_POST['add_punch_in'])) {
  $info = $obj_admin->add_punch_in($_POST);
}

// if (isset($_POST['add_punch_out'])) {
//   $obj_admin->add_punch_out($_POST);
// }


$page_name = "Attendance";
include("include/sidebar.php");

$sql_time = "SELECT * FROM `task_info` WHERE `task_id`=$task_id";

$sql = $sql_time . " AND `t_end_time`>CURRENT_TIMESTAMP;";
$task_info = $obj_admin->manage_all_info($sql);
$task_count = $task_info->rowCount();

$time_task_info = $obj_admin->manage_all_info($sql_time);
$time_task_count = $time_task_info->rowCount();
if ($time_task_count > 0) {
  $taskData = $time_task_info->fetch(PDO::FETCH_ASSOC);
  $t_title = $taskData['t_title'];
  $t_description = $taskData['t_description'];
  $totalTime = $obj_admin->time_elapsed_string($taskData['t_start_time'], $taskData['t_end_time'], true);

  $datetime1 = new DateTime($taskData['t_start_time']);
  $datetime2 = new DateTime($taskData['t_end_time']);
  $diff = $datetime1->diff($datetime2);
  $hours = $diff->h + ($diff->days * 24);
  $minutes = $diff->i;
  $seconds = $diff->s;
  $totalHoursMinutesSeconds = ($hours > 0 ? ($hours < 10 ? '0' . $hours : $hours) : '00') . ':' . ($minutes > 0 ? ($minutes < 10 ? '0' . $minutes : $minutes) : '00') . ':' . ($seconds > 0 ? ($seconds < 10 ? '0' . $seconds : $seconds) : '00');
}

//$info = "Hello World";
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">



<div class="row">
  <div class="col-md-12">
    <div class="well well-custom">
      <?php if ($user_role != 1 && $task_count > 0) { ?>
        <div class="row">
          <?php
          $sql = "SELECT * FROM `attendance_info` WHERE `atn_user_id` = $user_id AND `task_id`=$task_id AND `out_time` IS NULL";
          $info = $obj_admin->manage_all_info($sql);
          $num_row = $info->rowCount();
          if ($num_row == 0) {
          ?>
            <div class="col-lg-1 only-desktop">
              <form method="post" role="form" action="">
                <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button type="submit" name="add_punch_in" class="btn btn-success btn-lg rounded">Start</button>
              </form>
            </div>
          <?php } else { ?>
            <div class="col-lg-1 only-desktop">
              <a title="Update Task Comment" class="btn btn-danger btn-lg rounded " href="update-task.php?task_id=<?php echo $task_id; ?>">Stop</a>
            </div>
          <?php } ?>

          <div class="col-12 col-lg-10 task_dtl">
            <?php if ($task_count > 0 && $t_title != '') { ?>
              <b class="text-danger mt-0 mb-0"><?php echo $t_title; ?></b>
              <p style="margin: 0px !important;">
                Description: <?php echo $t_description; ?>
                <br />Total Hours: <b class="text-danger"><?php echo $totalTime ?></b>
              </p>
            <?php } ?>
          </div>
        </div>
      <?php } else if ($user_role != 1) { ?>
        <div class="task_dtl">
          <h4 class='text-center bg-danger text-danger'>
            Sorry, the deadline for this task has passed. Please contact your 'Team Manager'.
          </h4>
        </div>
      <?php }  ?>
      <?php if ($time_task_count != 0 && $user_role == 1) { ?>
        <div class="task_dtl">
          <b class="text-danger mt-0 mb-0"><?php echo $t_title; ?></b>
          <p>
            Description: <b><?php echo $t_description; ?></b>
            <br />Stating: <b><?php echo $taskData['t_start_time']; ?></b> - Ending: <b><?php echo $taskData['t_end_time']; ?></b>
            <br /> Total Hours: <b class="text-danger"><?php echo $totalTime ?></b>
          </p>
        </div>
      <?php }

      $currentDate = date("Y-m-d");

      $todayWorkSql = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(total_duration))) AS todayWork FROM `attendance_info` WHERE `in_time` LIKE '" . $currentDate . "%' AND out_time LIKE '" . $currentDate . "%' AND  `task_id`=$task_id";

      $totalWorkSql = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(total_duration))) AS totalWork FROM `attendance_info` WHERE `task_id`=$task_id";

      if ($user_role != 1) {
        $todayWorkSql .= " AND atn_user_id=$user_id;";
        $totalWorkSql .= " AND atn_user_id=$user_id;";
        // } else {
        // $todayWorkSql .= ";";
        // $totalWorkSql .= ";";
      }
      // echo "<hr />" . $todayWorkSql . "<hr />" . $totalWorkSql . "<hr />";
      $todayWorkInfo = $obj_admin->manage_all_info($todayWorkSql);
      $totalWorkInfo = $obj_admin->manage_all_info($totalWorkSql);

      $todayWorkRow = $todayWorkInfo->rowCount();
      $totalWorkRow = $totalWorkInfo->rowCount();

      if ($totalWorkRow > 0) {
        $totalWork = $totalWorkInfo->fetch(PDO::FETCH_ASSOC);
      }
      if ($todayWorkRow > 0) {
        $todayWork = $todayWorkInfo->fetch(PDO::FETCH_ASSOC);
      }
      ?>
      <div class="row">
        <div class="col-12">
          <h4 class="text-center text-info">
            Total Estimate Hours: <b class="text-danger">
              <?php echo (isset($totalHoursMinutesSeconds) && $totalHoursMinutesSeconds != '' ? $totalHoursMinutesSeconds : '00:00:00'); ?> </b>
          </h4>
        </div>
        <div class="col-12 col-lg-6">
          <h4 class="text-center text-info">
            Total Works Hours: <b class="text-danger">
              <?php echo (isset($totalWork) && $totalWork['totalWork'] != '' ? $totalWork['totalWork'] : '00:00:00'); ?> </b>
          </h4>
        </div>
        <div class="col-12 col-lg-6">
          <h4 class="text-center text-info">
            Today Total Works: <b class="text-danger">
              <?php echo (isset($todayWork) && $todayWork['todayWork'] != '' ? $todayWork['todayWork'] : '00:00:00'); ?> </b>
          </h4>
        </div>
      </div>
      <hr />
      <div class="">
        <h3 class="text-center">Manage Attendance</h3>
      </div>
      <div class="gap"></div>

      <div class="gap"></div>

      <div class="table-responsive">
        <table class="table table-responsive table-codensed table-custom">
          <thead>
            <tr>
              <th>S.N.</th>
              <th>Name</th>
              <th>In Time</th>
              <th>Out Time</th>
              <th>Total Duration</th>
              <th>Updates</th>
              <!-- <th>Status</th> -->
              <?php if ($user_role == 1) { ?>
                <th>Task</th>
                <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>

            <?php
            if ($user_role == 1) {
              $sql = "SELECT `a`.*, `b`.`fullname` FROM `attendance_info` `a` LEFT JOIN `tbl_admin` `b` ON(`a`.`atn_user_id` = `b`.`user_id`) WHERE `task_id`=$task_id ORDER BY `a`.`aten_id` DESC";
            } else {
              $sql = "SELECT `a`.*, `b`.`fullname` FROM `attendance_info` `a` LEFT JOIN `tbl_admin` `b` ON(`a`.`atn_user_id` = `b`.`user_id`) WHERE `atn_user_id` = $user_id AND `task_id`=$task_id ORDER BY `a`.`aten_id` DESC";
            }


            $info = $obj_admin->manage_all_info($sql);
            $serial  = 1;
            $num_row = $info->rowCount();
            if ($num_row == 0) {
              echo '<tr><td colspan="7">No Data found</td></tr>';
            }
            while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
              $task_id = $row['task_id'];
            ?>
              <tr>
                <td><?php echo $serial;
                    $serial++; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['in_time']; ?></td>
                <td><?php echo $row['out_time']; ?></td>
                <td><?php
                    if ($row['total_duration'] == null) {
                      $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
                      $current_time = $date->format('d-m-Y H:i:s');

                      $dteStart = new DateTime($row['in_time']);
                      $dteEnd   = new DateTime($current_time);
                      $dteDiff  = $dteStart->diff($dteEnd);
                      echo $dteDiff->format("%H:%I:%S");
                    } else {
                      echo $row['total_duration'];
                    }


                    ?></td>

                <td><?php echo $row['atn_updates']; ?></td>
                <?php // if ($row['out_time'] == null) { 
                ?>
                <!-- <td>
                    <form method="post" role="form" action="">
                      <input type="hidden" name="punch_in_time" value="<?php // echo $row['in_time']; 
                                                                        ?>">
                      <input type="hidden" name="aten_id" value="<?php // echo $row['aten_id']; 
                                                                  ?>">
                      <input type="hidden" name="task_id" value="<?php // echo $task_id; 
                                                                  ?>">
                      <textarea style="color: black !important;" name="task_update" id="updates" class="hidden"></textarea>
                      <button id="add_punch_out" class="btn btn-danger btn-xs rounded">Clock Out</button>
                      <button type="submit" id="submit" name="add_punch_out" class="btn btn-success btn-xs rounded hidden">Update</button>
                    </form>
                  </td> -->
                <?php // } else { 
                ?>
                <!-- <td class="text-center">
                    ------
                  </td> -->
                <?php // } 
                ?>
                <?php if ($user_role == 1) { ?>
                  <td>
                    <?php
                    $sql = "SELECT * FROM `task_info` WHERE `task_id`=" . $row['task_id'] . ";";
                    $taskInfo = $obj_admin->manage_all_info($sql);
                    $taskData = $taskInfo->fetch(PDO::FETCH_ASSOC);
                    echo $taskData['t_title'];
                    ?>
                  </td>

                  <td>
                    <a title="Delete" href="?delete_attendance=delete_attendance&aten_id=<?php echo $row['aten_id']; ?>&task_id=<?php echo $row['task_id']; ?>" onclick=" return check_delete();"><span class="glyphicon glyphicon-trash"></span></a>
                  </td>
                <?php } else { ?>
                  <td>
                  </td>
                <?php } ?>
              </tr>
            <?php } ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<?php
include("include/footer.php");



?>

<!-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->
<!-- <script>
  $(document).ready(function(){
    $("#updates").hide();
    $("#updates").removeAttr('required');
    
    $("#add_punch_out").click(function(){
      $("#updates").removeClass('hidden');
      $("#add_punch_out").hide();
      $("#updates").show();
      $("#updates").css('color: black');
      $("#updates").attr('required', 'true');
      $("#submit").removeClass('hidden');
    });
  });
</script> -->