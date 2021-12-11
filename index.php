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
require(ABSPATH . 'wp-content/plugins/thesis-plugin/includes/admin.php');
require(ABSPATH . 'wp-content/plugins/thesis-plugin/includes/class-pdf.php');



$table_name;


 #Replace spaces and make lowercase for column names
function sanitize_column_name($name){
	$name = str_replace(' ', '_', $name);
	$name = strtolower($name);
	return $name;
}

function create_entries_table($fields,$table_name){
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$statement = "id mediumint(9) NOT NULL AUTO_INCREMENT,";
	foreach($fields as $key =>$value){
		$statement = $statement.sanitize_column_name($fields[$key]['name'])."
		varchar(255) NOT NULL,";
	}
	$statement = $statement."PRIMARY KEY  (id)";
	
	$sql_create = "CREATE TABLE $table_name ($statement)$charset_collate;";
	#true if table was created
	if(maybe_create_table($table_name,$sql_create)){
		return true;
		}
		else{
			return false;
	};

}

function insert_entry_to_table($fields,$table_name){
	global $wpdb;
	$insert_array = array();

	foreach($fields as $key=>$value){
		$k = sanitize_column_name($value['name']);
		$v = $value['value'];
		$insert_array[$k] = $v;

	}

	$wpdb->insert($table_name,$insert_array);

	return $wpdb->insert_id;

}

function wpf_dev_process_complete( $fields, $entry, $form_data, $entry_id) {
	global $wpdb;
	$table_name = $wpdb->prefix . "submissions_table_".$form_data['id']; 
	create_entries_table($fields,$table_name);
	$last_id=insert_entry_to_table($fields,$table_name);
	generate_pdf($last_id,$table_name);


}
add_action( 'wpforms_process_complete', 'wpf_dev_process_complete', 10, 4);


function generate_pdf($id,$table){
	global $wpdb;
	$query = "SELECT * FROM {$table} WHERE id = {$id}";
	$result = $wpdb -> get_results($query)[0];
	$pdf = new PDF();
	$pdf->create_pdf($result);
}


if(isset($_POST["submit-download-pdf"])){
	generate_pdf($wpdb->insert_id,$table_name);

}
