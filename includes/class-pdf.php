<?php 

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
    function create_pdf($data){
        $this->AliasNbPages();
	    $this->AddPage();
	    $this->SetFont('Times','',12);
	    foreach($data as $key=>$value){
		    $this->Cell(40,10,$key);
		    $this->Ln();
		    $this->Cell(40,10,$value);
		    $this->Ln();
	    }
	    $this->Output('I');
    }
}
?>