<?php
/*
 * Plugin Name:       Student Management System
 * Plugin URI:        "https://ayodoya.com/sms"
 * Description:       This is CRUD Student management system.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ajay Maurya 
 * Author URI:        https://ayodoya.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
*/

define("SMS_PLUGIN_PATH", plugin_dir_path( __FILE__ ));
define("SMS_PLUGIN_URL", plugin_dir_url( __FILE__ )); 
define("SMS_PLUGIN_BASENAME", plugin_basename(__FILE__));


include_once SMS_PLUGIN_PATH.'class/StudentManagement.php';

$StudentManagementObj = new StudentManagement();

//Plugin activation
//to create student table
register_activation_hook(__FILE__, array($StudentManagementObj, "createStudentTable"));

//Plugin de-activation
//to remove student table
register_deactivation_hook(__FILE__, array($StudentManagementObj, "dropStudentTable"));



