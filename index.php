<?php
/*
Plugin Name: Thesis Plugin
Plugin URI: 
Description: Database for WPForms Submissions and export to PDF
Author: MTT
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

function wpf_dev_process_complete( $fields, $entry, $form_data, $entry_id ) {
	
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . "wpforms_submissions"; 

	$sql_create = "CREATE TABLE $table_name (
  		id mediumint(9) NOT NULL AUTO_INCREMENT,
  		first varchar(50) NOT NULL,
		last varchar(50) NOT NULL,
  		aem int(255) NOT NULL,
  		PRIMARY KEY  (id)
	) $charset_collate;";

	maybe_create_table($table_name,$sql_create);

	$first =$fields[1]['first'];
	$last =  $fields[1]['last'];
	$aem = $fields[2]['value'];

	$sql_insert = $wpdb->prepare(("INSERT INTO $table_name (first, last, aem) VALUES ('$first', '$last', '$aem') "));
	$wpdb->query($sql_insert);

	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	for($i=1;$i<=40;$i++){
    	$pdf->Cell(0,10,'Printing line number '.$i,0,1);
	}
	$pdf->Output();

}
add_action( 'wpforms_process_complete', 'wpf_dev_process_complete', 10, 4 );



function wpf_dev_frontend_confirmation_message( ) {
    
    $message = '<button id="download-pdf-btn" type="button">Download the PDF!</button>';
    return $message;
      
}
add_filter( 'wpforms_frontend_confirmation_message', 'wpf_dev_frontend_confirmation_message', 10, 4 );