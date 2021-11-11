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

class PDF extends FPDF
{
	function Header(){
		// Select Arial bold 15
		$this->SetFont('Arial','B',15);
		// Move to the right
		$this->Cell(80);
		// Framed title
		$this->Cell(40,10,'Aristotle University of Thessaloniki',0,0,'C');
		// Line break
		$this->Ln(20);
	}

	function Footer(){

	}
}
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

	$statement = "INSERT INTO {$table_name} (";
	foreach($fields as $key=>$value){
		if(array_key_last($fields)==$key){
			$statement = $statement.sanitize_column_name($value['name']).")";
		}else{
			$statement = $statement.sanitize_column_name($value['name']).",";
		}
		//echo "{$value['name']}->{$value['value']}";
	}
	$statement = $statement." VALUES (";
	foreach($fields as $key=>$value){
		if(array_key_last($fields)==$key){
			$statement = $statement."'{$value['value']}')";
		}else{
			$statement = $statement."'{$value['value']}',";
		}
	}
	$sql_insert = $wpdb->prepare($statement);
	#return true if row was inserted
	if($wpdb->query($sql_insert)!=false){
		return true;
	}else{
		return false;
	};
}

function wpf_dev_process_complete( $fields, $entry, $form_data, $entry_id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "wpforms_submissions_".$form_data['id']; 
	create_entries_table($fields,$table_name);
	insert_entry_to_table($fields,$table_name);
	echo "ID of last inserted record is: " . mysql_insert_id();



}
add_action( 'wpforms_process_complete', 'wpf_dev_process_complete', 10, 4 );



function wpf_dev_frontend_confirmation_message( ) {
    
    $message = '<form method="post"><input type="submit" name="submit-download-pdf" id="download-pdf-btn"  value="Download the PDF!" ></form>';
    return $message;
      
}
add_filter( 'wpforms_frontend_confirmation_message', 'wpf_dev_frontend_confirmation_message', 10, 4 );

function generate_pdf($id,$table){
	global $wpdb;

	$query = "SELECT * FROM {$table} WHERE id = {$id}";
	$result = $wpdb -> query($query);
	echo var_dump($result);

	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	for($i=1;$i<=40;$i++){
    	$pdf->Cell(0,10,'Printing line number '.$i,0,1);
	}
	//$pdf->Output('D');
}

if(isset($_POST["submit-download-pdf"])){
	generate_pdf();

}

