
<div class="sms-plugin list-student-card">
    <h2>List Student</h2>
    <!-- display message section -->
    <?php
            if (!empty($displayMessage)) {
                if ($displayStatus == 1) {
                    ?>
                 <div class="display-success">
                            <?php echo $displayMessage; ?>
                        </div>
                    <?php
                    }
                } 
                ?>
    <!-- end of display message -->
    <div class="table-container">
        <table class="student-table" id="tbl-student-table">
            <thead>
                <th>#ID</th>
                <th>#Profile Image</th>
                <th>#Name</th>
                <th>#Email</th>
                <th>#Gender</th>
                <th>#Phone No</th>
                <th>Created at</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php
                if(count($students) > 0){
                    foreach ($students as $student){
                        ?>
                        <tr>
                            <td><?php echo $student['id'] ?></td>
                            <td><img src="<?php echo $student['profile_img']; ?>" style="height:60px"></td>
                            <td><?php echo $student['name'] ?></td>
                            <td><?php echo $student['email'] ?></td>
                            <td><?php echo $student['gender'] ?></td>
                            <td><?php echo $student['phone_no'] ?></td>
                            <td><?php echo $student['created_at'] ?></td>
                            <td>
                                <a href="<?php echo $_SERVER['PHP_SELF'] . '?page=student-system&action=view&id='.$student["id"]; ?>" class="btn-view">View</a>
                                <a href="<?php echo $_SERVER['PHP_SELF'] . '?page=student-system&action=edit&id='.$student["id"]; ?>" class="btn-edit">Edit</a>
                                <a href="<?php echo $_SERVER['PHP_SELF'] . '?page=list-student&action=delete&id='.$student["id"]; ?>" class="btn-delete">Delete</a>
                            </td>
                </tr>

                        <?php

                    }
                    
                }
                ?>
                
            </tbody>

        </table>
    </div>

</div>