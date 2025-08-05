<?php
$qsaction = $_GET['action'];
?>
<div class="sms-plugin add-std-card">
    <?php if ($qsaction == "view") {
            ?><h2>View Student</h2><?php
        }elseif($qsaction == "edit"){
            ?><h2>Edit Student</h2><?php
        }else{
            $nonce = wp_create_nonce("wp_nonce_add_student");
            ?><h2>Add Student</h2><?php
        }
         ?>
    <!-- display message after form action -->
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
                if ($displayStatus === 0) {
                    ?>
                        <div class="display-failer">
                            <?php echo $displayMessage; ?>
                        </div>
                <?php
                    
                }
                ?>
    <!-- end of display message -->
    <!-- Add student for  --> 
     
                    
    <form 
        class=" add-student-form" 
        action="javascript:void(0);"
        id="frm_sms_form"
        method="post">
        <!-- nonce hidden filed -->
        <div class="form-group">
                        <input
                            type="hidden"
                            value="<?php if(isset($nonce)){ echo $nonce; }?>"
                            name="wp_nonce_add_student"
                            >
                    </div>
        <!-- id hidden filed -->
        <div class="form-group">
                        <input
                            type="hidden"
                            id="stdId"
                            value="<?php if ($qsaction == "view" || $qsaction == "edit") {
                                        echo $student_detail['id'];
                                    } ?>"
                            placeholder=""
                            name="id"
                            >
                    </div>
        <!-- Name -->
         <div class="form-group">
            <label for="name">Name</label>
            <input 
                type="text" 
                name="name" 
                value="<?php if ($qsaction == "view" || $qsaction == "edit") {
                                        echo $student_detail['name'];
                                    } ?>"
                id="name" 
                placeholder="Enter Name" 
                <?php if ($qsaction == "view") {
                    echo "readonly = 'readonly'";}?>
                <?php 
                    if(!empty(get_option('sms_name_validation'))){ echo "required"; } 
                ?>
                
                >
         </div>
         <!-- Email -->
         <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="text" 
                name="email" id="email" 
                value="<?php if ($qsaction == "view" || $qsaction == "edit") {
                                        echo $student_detail['email'];
                                    } ?>"
                placeholder="Enter email" 
                <?php if ($qsaction == "view") {
                    echo "readonly = 'readonly'";}?>
                <?php 
                    if(!empty(get_option('sms_email_validation'))){ echo "required"; } 
                ?>
                >
         </div>
         <!-- Gender -->
         <div class="form-group">
            <label for="gender">Gender</label>
            <select 
                name="gender" 
                <?php if($qsaction == "view"){ echo "disabled = true";} ?>
                id="gender"
                <?php 
                    if(!empty(get_option('sms_gender_validation'))){ echo "required"; } 
                ?>

                >
                <option value="">Select Gender</option>
                <option value="Male" 
                    <?php if(($qsaction == 'view' || $qsaction == 'edit') && $student_detail['gender'] == 'Male')
                            { echo 'selected';} ?>
                            >Male</option>
                <option value="female"
                    <?php if(($qsaction == 'view' || $qsaction == 'edit') && $student_detail['gender'] == 'Female')
                            { echo 'selected';} ?>
                >Female</option>
                <option value="other"
                    <?php if(($qsaction == 'view' || $qsaction == 'edit') && $student_detail['gender'] == 'Other')
                            { echo 'selected';} ?>
                >Other</option>
            </select>
         </div>
         <!-- Phone Number -->
         <div class="form-group">
            <label for="phoneNo">Phone Number</label>
            <input 
                type="text" 
                name="phoneNo" 
                id="phoneNo" 
                value="<?php if ($qsaction == "view" || $qsaction == "edit") {
                                        echo $student_detail['phone_no'];
                                    } ?>"
                placeholder="Enter phone number"
                <?php if ($qsaction == "view") {
                    echo "readonly = 'readonly'";}?> 
                <?php 
                    if(!empty(get_option('sms_phoneNo_validation'))){ echo "required"; } 
                ?>
                >
         </div>
         <!-- upload button -->
          <input type="text" name="profile_url" id="profile_url" readonly >
          <button class="btn-upload-profile" id="btn-upload-profile">Upload Profile Image</button>
         <?php
                    if ($qsaction == "view") {
                        //no button to view
                    } elseif ($qsaction == "edit") { ?>
                        
                    <?php } else { ?>
                        <button type="submit" id="btn_sms_form" name="btn-add-student">Submit</button>
                    <?php } ?>

         
    </form>

</div>