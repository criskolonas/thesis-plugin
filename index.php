<?php
/*
Plugin Name: Thesis Plugin
Plugin URI: 
Description: Database for WPForms Submissions and export to PDF
Author: Chris Kolonas
Author URI: http://https://github.com/criskolonas
Version: 0.1
*/



require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require(ABSPATH . 'wp-content/plugins/thesis-plugin/libs/fpdf184/fpdf.php');
require(ABSPATH . 'wp-content/plugins/thesis-plugin/includes/class-pdf.php');
require(ABSPATH . 'wp-content/plugins/thesis-plugin/includes/form-submission.php');

if(is_admin()){
	require(ABSPATH . 'wp-content/plugins/thesis-plugin/includes/admin.php');
}

