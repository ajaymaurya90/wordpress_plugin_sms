
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
                
                
            </tbody>

        </table>
    </div>

</div>