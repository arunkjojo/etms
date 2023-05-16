<?php

require 'authentication.php'; // admin authentication check 
// auth check

$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
$user_role = $_SESSION['user_role'];
if ($user_id == NULL || $security_key == NULL) {
  header('Location: index.php');
}

if($user_role != 1){
  if(isset($_GET['tId']) && $_GET['tId']!=''){
    $task_id = $_GET['tId'];
  }else{
    header("Location: /task-info.php");
  }
}





if (isset($_GET['delete_attendance'])) {
  $action_id = $_GET['aten_id'];
  $tId = $_GET['task_id'];

  $sql = "DELETE FROM `attendance_info` WHERE `aten_id` = :id";
  $sent_po = "attendance-info.php?tId=".$tId;
  $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}


if (isset($_POST['add_punch_in'])) {
  $info = $obj_admin->add_punch_in($_POST);
}

if (isset($_POST['add_punch_out'])) {
  $obj_admin->add_punch_out($_POST);
}


$page_name = "Attendance";
include("include/sidebar.php");

//$info = "Hello World";
?>
<?php if(isset($_COOKIE) && isset($_COOKIE['siteWillOpen']) && $_COOKIE['siteWillOpen'] == "open"){ ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">



<div class="row">
  <div class="col-md-12">
    <div class="well well-custom">
      <?php if($user_role != 1) {
        $sql = "SELECT * FROM `task_info` WHERE `t_end_time`>CURRENT_TIMESTAMP AND `task_id`=$task_id;";
        $info = $obj_admin->manage_all_info($sql);
        if ($info->rowCount() > 0) {?>
        <div class="row">
          <div class="col-md-8 ">
            <div class="btn-group">
              <?php
              
              $sql = "SELECT * FROM `attendance_info` WHERE `atn_user_id` = $user_id AND `task_id`=$task_id AND `out_time` IS NULL";
              $info = $obj_admin->manage_all_info($sql);
              $num_row = $info->rowCount();
              if ($num_row == 0) {
              ?>

                <div class="btn-group">
                  <form method="post" role="form" action="">
                    <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <button type="submit" name="add_punch_in" class="btn btn-primary btn-lg rounded">Clock In</button>
                  </form>

                </div>

              <?php } ?>

            </div>
          </div>
        </div>
      <?php } else { ?>
        <div>
          <h4 class='text-center bg-danger text-danger'>
            Sorry, the deadline for this task has passed. Please contact your 'Team Manager'.
          </h4>
        </div>
      <?php }
    } ?>
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
              <th>Status</th>
              <?php if ($user_role == 1) { ?>
                <th>Task</th>
                <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>

            <?php
            if ($user_role == 1) {
              $sql = "SELECT `a`.*, `b`.`fullname` FROM `attendance_info` `a` LEFT JOIN `tbl_admin` `b` ON(`a`.`atn_user_id` = `b`.`user_id`) ORDER BY `a`.`aten_id` DESC";
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
            ?>
              <tr>
                <td><?php echo $serial;
                    $serial++; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['in_time']; ?></td>
                <td><?php echo $row['out_time']; ?></td>
                <td><?php
                    if ($row['total_duration'] == null) {
                      $date = new DateTime('now', new DateTimeZone('Asia/Manila'));
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
                <?php if ($row['out_time'] == null) { ?>
                  <td>
                    <form method="post" role="form" action="">
                      <input type="hidden" name="punch_in_time" value="<?php echo $row['in_time']; ?>">
                      <input type="hidden" name="aten_id" value="<?php echo $row['aten_id']; ?>">
                      <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                      <textarea style="color: black !important;" name="task_update" id="updates" class="hidden"></textarea>
                      <button id="add_punch_out" class="btn btn-danger btn-xs rounded">Clock Out</button>
                      <button type="submit" id="submit" name="add_punch_out" class="btn btn-success btn-xs rounded hidden">Update</button>
                    </form>
                  </td>
                <?php } else { ?>
                  <td class="text-center">
                    ------
                  </td>
                <?php } ?>
                <?php if ($user_role == 1) { ?>
                  <td>
                    <?php 
                      $sql = "SELECT * FROM `task_info` WHERE `task_id`=".$row['task_id'].";";
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
}else{?>
  <div style="text-align: center; margin-top: 50%; margin-left: auto; margin-right: auto; color: red; background-color:black">
    <h2>Sorry ETMS Application not working on this system</h2>
    <button onclick="window.location.reload();">Please Reload</button>
  </div>
<?php }
include("include/footer.php");



?>

<!-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> -->
<script>
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
</script>