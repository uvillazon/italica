<?php

App::import('Vendor', 'tcpdf');

class MYPDF extends TCPDF {

    public function Footer() {
        /* establecemos el color del texto */
        //$this->SetTextColor(0,0,200);
        /* insertamos numero de pagina y total de paginas */
        $this->Cell(0, 10, 'Pagina ' . $this->getAliasNumPage() .
                ' de ' .
                $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        $this->Cell(0, 10, 'Reporte Cambios - Italica ' . date('d-m-Y H:i:s'), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        //$this->SetDrawColor(255,0,0);
        /* dibujamos una linea roja delimitadora del pie de página */
        $this->Line(15, 287, 195, 287, array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0));
    }

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ITALICA');
$pdf->SetTitle('REPORTE CAMBIOS');
$pdf->SetSubject(date('d-m-Y'));
$pdf->SetKeywords('ITALICA, PDF, reporte,cliente cambios');

// set default header data
$pdf->SetHeaderData("logo4.png", PDF_HEADER_LOGO_WIDTH, 'REPORTE HISTORIAL CAMBIOS', 'ITALICA - ' . $sucursal);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array('helvetica', 'I', 7));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// set font
$pdf->SetFont('helvetica', 'B', 9);

// add a page
$pdf->AddPage();

$pdf->Write(0, $titulo, '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 5);

// -----------------------------------------------------------------------------

$tbl = '
<table cellspacing="0" cellpadding="1" border="1">
<tr style="background-color:#A9E2F3; font-size:25px; font-weight: bold">
<th align="center">FECHA</th>

<th align="center">PRODUCTOS<br>INGRESO</th>
<th align="center">PRODUCTOS<br>SALIDA</th>
<th align="center">CLIENTE</th>
<th align="center">VENDEDOR</th>
<th align="center">VENTA</th>
</tr>';



// -----------------------------------------------------------------------------

$datosHtml = "";
$dias = array("Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab");

foreach ($datos as $value) {

    if (strrpos($value['venta_cambio_fecha'], "TOTAL") === false)
        $fecha = $dias[date('w', strtotime($value['venta_cambio_fecha']))] . ' ' . date('d-m-Y H:i:s', strtotime($value['venta_cambio_fecha']));
    else
        $fecha = $value['venta_cambio_fecha'];
   if (strrpos($value['venta_cambio_fecha'], "TOTAL") === false)
         $vendedor = $value['venta_usuario_resp']."<br>(".$value['sucursal_nombre'].")";
    else
        $vendedor = "";
     if (strrpos($value['venta_cambio_fecha'], "TOTAL") === false)
        $venta="VEN-".$value['venta_id'];
    else
        $venta="";
     
    $cliente = $value['persona_nombres'] . ' ' . $value['persona_apellido1']. ' ' . $value['persona_apellido2'];
   
    $productosIngreso = $value['productosIngreso'];
    $productosSalida = $value['productosSalida'];
    
    if (strrpos($value['venta_cambio_fecha'], "TOTAL") === false) {
        $datosHtml.="<tr><td>$fecha</td>
       <td>$productosIngreso</td><td>$productosSalida</td><td>$cliente</td><td>$vendedor</td><td>$venta</td></tr>";
    } else {
        $datosHtml.='<tr style="font-size:25px; font-weight: bold">' . "<td>$fecha</td>
       <td>$productosIngreso</td><td>$productosSalida</td><td>$cliente</td><td>$vendedor</td><td>$venta</td></tr>";
    }
}

$tbl.=$datosHtml . "</table>";

$pdf->writeHTML($tbl, true, false, false, false, 'C');


//Close and output PDF document
$pdf->Output('ventas.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
?>