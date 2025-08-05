<?php 
ob_clean(); // Clears output buffer
header('Content-Type: application/json');

class StudentManagement
{
    private $status = "0";
    private $message = "";

    public function __construct()
    {
        add_action("admin_menu", array($this, "addAdminMenus"));
        add_action("admin_enqueue_scripts", (array($this, "addStudentPluginFiles")));
        add_filter("plugin_action_links_".SMS_PLUGIN_BASENAME, array($this, "plugin_settings_link"));
        add_action("admin_init", array($this, "sms_plugin_setting"));

        //To handle ajax request
        add_action("wp_ajax_sms_ajax_handler", array($this, "sms_ajax_handler"));
    }

    //To add plugin menus and submenus
    public function addAdminMenus(){
        //Add admin menus
        add_menu_page("Student Management System", "Student System", "manage_options", "student-system", array($this, "addStudentCallback"), "dashicons-admin-home");
        //Add sub menus
        add_submenu_page("student-system", "Add Student", "Add Student", "manage_options", "student-system",  array($this, "addStudentCallback"));
        add_submenu_page("student-system", "List Student", "List Student", "manage_options", "list-student", array($this, "listStudentCallback"));

        //Settings page submenu inside settings menu
        add_options_page("SMS Plugin Settings", "SMS Settings", "manage_options", "sms-plugin-settings", array($this, "sms_plugin_action_handle"));
        add_submenu_page("student-system", "SMS Settings", "Settings", "manage_options", "sms-plugin-settings", array($this, "sms_plugin_action_handle"));
    }

    //To add plugin files
    public function addStudentPluginFiles(){
        //style css files 
        wp_enqueue_style( "sms-datatable-css", SMS_PLUGIN_URL . "assets/css/dataTables.dataTables.min.css", array(), "1.0.0", "all");
        wp_enqueue_style( "sms-custom-css", SMS_PLUGIN_URL."assets/css/custom.css", array(), "1.0.0", "all");
        //To add js code as innline script, after or before of any js file.
        wp_enqueue_style( "sms-toaster-css", SMS_PLUGIN_URL."assets/css/toastr.min.css", array(), "1.0.0", "all");

        //Script files 
        wp_enqueue_media();
        wp_enqueue_script( "sms-datatable-js", SMS_PLUGIN_URL."assets/js/dataTables.min.js", array("jquery"), "1.0.0");
        wp_enqueue_script( "sms-validate-js", SMS_PLUGIN_URL."assets/js/jquery.validate.min.js", array("jquery"), "1.19.5");
        wp_enqueue_script( "sms-custom-js", SMS_PLUGIN_URL."assets/js/custom.js", array("jquery"), "1.0.0");
       
        //Message plugin, to show message after ajax form submission
        wp_enqueue_script( "sms-toaster-js", SMS_PLUGIN_URL."assets/js/toastr.min.js", array("jquery"), "1.0.0");

        //To add js code as innline script, after or before of any js file.
        $data= "var sms_ajax_url = '".admin_url('admin-ajax.php')."'";
        wp_add_inline_script('sms-custom-js', $data);
    }

    //For plugin settings links
    public function plugin_settings_link($links){
        $settings_links = "<a href='options-general.php?page=sms-plugin-settings'>Settings</a>";
        array_unshift($links, $settings_links);
        return $links;
    }

    //Plugin setting 
    public function sms_plugin_setting(){
        add_settings_section("form_validation_rule_section_1", "From Rule Validation Settings", "", "sms-plugin-settings" );
        
        //For name field 
        register_setting("sms_plugin_options", "sms_name_validation");
        add_settings_field("sms_name_field", "Name Field Validation", array($this, "sms_name_field_handle"), "sms-plugin-settings", "form_validation_rule_section_1");

        //For Email Field 
        register_setting("sms_plugin_options", "sms_email_validation");
        add_settings_field("sms_email_field", "Email Field Validation", array($this, "sms_email_field_handle"), "sms-plugin-settings", "form_validation_rule_section_1");

        //For Gender Field
        register_setting("sms_plugin_options", "sms_gender_validation");
        add_settings_field("sms_gender_field", "Gender Field Validation", array($this, "sms_gender_field_handle"), "sms-plugin-settings", "form_validation_rule_section_1");

        //For Phone Number Field
        register_setting("sms_plugin_options", "sms_phoneNo_validation");
        add_settings_field("sms_phoneNo_field", "Phone Number Validation", array($this, "sms_phoneNo_field_handle"), "sms-plugin-settings", "form_validation_rule_section_1");
    }

        //plugin setting name field handler
        public function sms_name_field_handle(){
        $saved_option = get_option('sms_name_validation');
        $checked = "";
        if($saved_option){
            $checked = "checked";
        }
        echo '<input type="checkbox" name="sms_name_validation" value="1"' .$checked.'/>';
        }

        //plugin setting email field handler
        public function sms_email_field_handle(){
        $saved_option = get_option('sms_email_validation');
        $checked = "";
        if($saved_option){
            $checked = "checked";
        }
        echo '<input type="checkbox" name="sms_email_validation" value="1" ' .$checked.'/>';
        }

        //plugin setting gender field handler
        public function sms_gender_field_handle(){
        $saved_option = get_option('sms_gender_validation');
        $checked = "";
        if($saved_option){
            $checked = "checked";
        }
        echo '<input type="checkbox" name="sms_gender_validation" value="1" ' .$checked.'/>';
        }

        //plugin setting phone number field handler
        public function sms_phoneNo_field_handle(){
        $saved_option = get_option('sms_phoneNo_validation');
        $checked = "";
        if($saved_option){
            $checked = "checked";
        }
        echo '<input type="checkbox" name="sms_phoneNo_validation" value="1"' .$checked.'/>';
        }

    //Ajax request handler
    public function sms_ajax_handler(){
        global $wpdb;  
        if(isset($_REQUEST['param'])){
            if($_REQUEST['param'] == "save_form"){
                if(isset($_POST['wp_nonce_add_student']) && wp_verify_nonce($_POST['wp_nonce_add_student'], "wp_nonce_add_student")){
                    $name = sanitize_text_field($_POST['name']);
                    $email = sanitize_text_field($_POST['email']);
                    $gender = sanitize_text_field($_POST['gender']);
                    $phone = sanitize_text_field($_POST['phoneNo']);
                    $profileImg = sanitize_text_field($_POST['profile_url']);
                    $createdAt = sanitize_text_field($_POST['created_at']);
                    
                    $wpdb->insert("{$wpdb->prefix}sms_students", array(
                    "name" => $name,
                    "email" => $email,
                    "gender" => $gender,
                    "phone_no" => $phone,
                    "profile_img" => $profileImg,
                    "created_at" => $createdAt
                    ));
                    
                    $last_inserted_id = $wpdb->insert_id;
                    if($last_inserted_id > 0) {
                        //Data created
                        ob_clean(); // clear output buffer if needed
                        header('Content-Type: application/json');
                        echo json_encode(array(
                            "status" => 1,
                            "message" => "Student saved succesfully",
                            "data" => []
                        ));
                    }else{
                        //Data not created
                        echo json_encode(array(
                            "status" => 0,
                            "message" => "Student not created",
                            "data" => []
                        ));
                    }
                    exit;
                }
            }elseif($_REQUEST['param'] == "load_students"){
                $students = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sms_students", ARRAY_A);
                if(count($students) > 0){
                    ob_clean(); // clear output buffer if needed
                    header('Content-Type: application/json');
                    echo json_encode(array(
                        "status" => 1,
                        "message" => "Student data",
                        "data" => $students,
                        "urlBase" => $_SERVER['PHP_SELF']
                        ));
                }else{
                    echo json_encode(array(
                        "status" => 0,
                        "message" => "No student found",
                        "data" => []
                    ));
                }
                exit;
            }
        }
    }

    //SMS plugin settings page action handler
    public function sms_plugin_action_handle (){
        echo "<h3>SMS plugin Settings</h3>";
        include_once SMS_PLUGIN_PATH.'pages/sms-plugin-settings.php';
    }

    //Add Student CallBack function
    public function addStudentCallback(){
        $student_detail = "";
        //to get selected student detail for view and edit action
        if(!empty($_GET) && $_GET['action'] == "view" || $_GET['action'] == "edit"){
            global $wpdb;
            $studentId = $_GET['id'];
            $student_detail = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}sms_students WHERE id = %d", $studentId),
            ARRAY_A);
        }

        //To Add / Update student data in the database
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            //Nonce Verification
            if(isset($_POST['wp_nonce_add_student']) && wp_verify_nonce($_POST['wp_nonce_add_student'], "wp_nonce_add_student")){
                $this->saveStudentFormData();  
            }else{
                $this->message = "Verification Failed";
                $this->status = 0;
            }
            
        }
        $displayMessage = $this->message;
        $displayStatus = $this->status;
        
        //Getting Add student page layout
        include_once SMS_PLUGIN_PATH."pages/add-student.php";
    }

    //List Student CallBack function
    public function listStudentCallback(){
        //To delete a student on delete action
        if(!empty($_GET) && $_GET['action']=='delete')
        {
            global $wpdb;
            $result = $wpdb->delete("{$wpdb->prefix}sms_students", array("id"=>intval($_GET['id'])));
            if(!empty($result) && $result > 0){
                $displayMessage = "Student has been deleted succesfully";
                $displayStatus = 1;
            }else{
                $displayMessage = "Student is not deleted, some problem occure";
                $displayStatus = 0;
            }
            
        }

        //Get all student to sho on the list page
        $students = $this->getAllStudents();
        include_once SMS_PLUGIN_PATH."pages/list-student.php";

    }
    //To create student table
    public function createStudentTable(){
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sms_students` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `email` varchar(100) NOT NULL,
                `gender` enum('Male','Female','Other') DEFAULT NULL,
                `phone_no` varchar(80) DEFAULT NULL,
                `profile_img` TEXT,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        include_once ABSPATH."wp-admin/includes/upgrade.php";
        dbDelta($sql);
    }

    //To drop student table
    public function dropStudentTable(){
        global $wpdb;
        //$prefix = $wpdb->prefix;
        $sql = "DROP TABLE IF EXISTS `{$wpdb->prefix}sms_students`";
        $wpdb->query($sql);
    }

    //function to save student form
    public function saveStudentFormData(){
        global $wpdb;
            //To form Submission
            $name = sanitize_text_field($_POST['name']);
            $email = sanitize_text_field($_POST['email']);
            $gender = sanitize_text_field($_POST['gender']);
            $phoneNo = sanitize_text_field($_POST['phoneNo']);
            $profileImg = sanitize_text_field($_POST['profile_url']);
            
            //Add operation
            if(isset($_POST['btn-add-student'])){
                $wpdb->insert("{$wpdb->prefix}sms_students", array(
                "name" => $name,
                "email" => $email,
                "gender" => $gender,
                "phone_no" => $phoneNo,
                "profile_img" => $profileImg
                
            ));
                $last_inserted_id = $wpdb->insert_id;

                if ($last_inserted_id > 0) {
                    $this->message = "Student saved succesfully";
                    $this->status = 1;
                } else {
                    $this->message = "Failed to save and Student";
                    $this->status = 0;
                }
            }
            //Update operation
            if(isset($_POST['btn-update-student'])){
                $result = $wpdb->update("{$wpdb->prefix}sms_students", array(
                "name" => $name,
                "email" => $email,
                "gender" => $gender,
                "phone_no" => $phoneNo,
                "profile_img" => $profileImg
                
                ), array( "id" => $_POST['id'])
                );

                if ($result > 0) {
                    $this->message = "Student data updated succesfully";
                    $this->status = 1;
                } else {
                    $this->message = "Failed to update Student data";
                    $this->status = 0;
                }
            }
    }

    public function getAllStudents(){
        //Get all student details
        global $wpdb;
        $result = $wpdb->get_results("SELECT * from {$wpdb->prefix}sms_students", ARRAY_A);
        return $result;
    }
    
}