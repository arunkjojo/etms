<?php

require 'authentication.php'; // admin authentication check 

// auth check
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
}

if (isset($_GET['leave_action']) && $_GET['leave_action'] == 'leave_approve') {
    $obj_admin->approve_leave($_GET['id']);
}
if (isset($_GET['leave_action']) && $_GET['leave_action'] == 'leave_reject') {
    $obj_admin->reject_leave($_GET['id']);
}
if (isset($_POST['add_to_leave'])) {
    $info = $obj_admin->add_to_leave($_POST);
}


$page_name = "Leave";
include("include/sidebar.php");

//$info = "Hello World";
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="row">
    <div class="col-md-12">
        <div class="well well-custom rounded-0">

            <div class="gap"></div>
            <button class="btn btn-success" id="toggle_leave_div">Show/Hide Leave Request Form</button>
            <div class="gap"></div>
            <div class="row" id="add_leave">
                <center>
                    <h3>Add Leave Request</h3>
                </center>
                <div class="gap"></div>
                <form method="post" role="form" action="">
                    <div class="col-12">
                        <label class="form-label" for="leave_for">Leave Type</label>
                        <select class="form-control" name="leave_for" id="leave_for" required>
                            <option value="" disabled selected>Select Leave for</option>
                            <option value="Medical Leave">Medical Leave</option>
                            <option value="Special Leave">Special Leave</option>
                            <option value="Company Leave">Company Suggested Leave</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="leave_time">Leave Time</label>
                        <select class="form-control" name="leave_time" id="leave_time" required>
                            <option value="" disabled selected>Select Leave Time</option>
                            <option value="Half Day Leave">Half Day Leave</option>
                            <option value="Full Day">Full Day Leave</option>
                            <option value="Multi Days">Multi Days Leave</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="leave_from">Leave From</label>
                        <input type="date" class="form-control" name="leave_from" id="leave_from" required />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="leave_to">Leave To(14 days max)</label>
                        <input type="date" class="form-control" name="leave_to" id="leave_to" required />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="leave_reason">Leave Reason</label>
                        <textarea rows="6" class="form-control" name="leave_reason" id="leave_reason" required></textarea>
                    </div>
                    <?php if ($user_role == 1) { ?>
                        <div class="col-12">
                            <label class="form-label" for="user_id">Employee</label>
                            <select class="form-control" name="user_id" id="user_id" required>
                                <option value="" disabled selected>Select Employee</option>
                                <?php
                                $info = $obj_admin->manage_all_info("SELECT * FROM `tbl_admin` ORDER BY `tbl_admin`.`user_role` DESC;");
                                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <option value="<?php echo $row['user_id']; ?>"><?php echo $row['fullname']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <?php } ?>
                    <hr />
                    <div class="col-12">

                        <button type="submit" name="add_to_leave" class="btn btn-warning btn-lg btn-block rounded">Add leave</button>
                    </div>
                </form>
            </div>

            <div class="gap"></div>
            <hr />
            <center>
                <h3>Leave Management Section</h3>
            </center>
            <div class="gap"></div>

            <div class="table-responsive">
                <table class="table table-codensed table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Leave For</th>
                            <th>Leave Type</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Leave Reason</th>
                            <th>Status</th>
                            <?php if ($user_role == 1) { ?>
                                <th>Create At</th>
                                <th>Employee</th>
                                <th>Approve</th>
                                <th>Reject</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if ($user_role == 1) {
                            $sql = "SELECT `id`, `user_id`, `leave_for`, `leave_time`, `leave_from`, `leave_to`, `leave_reason`, `leave_at`, `leave_approve` FROM `leaves` ORDER BY id ASC;";
                        } else {
                            $sql = "SELECT `id`, `user_id`, `leave_for`, `leave_time`, `leave_from`, `leave_to`, `leave_reason`, `leave_at`, `leave_approve` FROM `leaves` WHERE `user_id`=" . $user_id . ";";
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
                                <td><?php echo $row['leave_for']; ?></td>
                                <td><?php echo $row['leave_time']; ?></td>
                                <td><?php echo $row['leave_from']; ?></td>
                                <td><?php echo $row['leave_to']; ?></td>
                                <td><?php echo $row['leave_reason']; ?></td>
                                <td><?php echo ($row['leave_approve'] == 2 ? 'Rejected' : ($row['leave_approve'] == 1 ? 'Approved' : 'Pending')); ?></td>
                                <?php if ($user_role == 1) { ?>
                                    <td><?php echo $row['leave_at']; ?></td>
                                    <td><?php
                                        $user_sql = "SELECT * FROM `tbl_admin` WHERE `user_id`=" . $row['user_id'];
                                        $user_info = $obj_admin->manage_all_info($user_sql);
                                        $user_row = $user_info->fetch(PDO::FETCH_ASSOC);
                                        echo $user_row['fullname'];
                                        ?></td>
                                    <td>
                                        <a title="Approve" href="?leave_action=leave_approve&id=<?php echo $row['id']; ?>">Approve <span class="glyphicon glyphicon-ok-sign"></span></a>
                                    </td>
                                    <td>
                                        <a title="Delete" href="?leave_action=leave_reject&id=<?php echo $row['id']; ?>">Reject <span class="glyphicon glyphicon-trash"></span></a>
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
    $("#add_leave").hide();
    $("#toggle_leave_div").click(function() {
        var x = document.getElementById("add_leave");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    })
    flatpickr('#leave_from', {
        enableTime: true,
        // minDate: "today",
        maxDate: new Date().fp_incr(13) // 14 days from now
    });
    flatpickr('#leave_to', {
        enableTime: true,
        // minDate: "today",
        maxDate: new Date().fp_incr(14) // 14 days from now
    });
</script>