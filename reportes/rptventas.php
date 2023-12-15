<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['ventas']==1)
{

//Inlcuímos a la clase PDF_MC_Table
require('PDF_MC_Table.php');

//Instanciamos la clase para generar el documento pdf
$pdf=new PDF_MC_Table();

//Agregamos la primera página al documento pdf
$pdf->AddPage();

//Seteamos el inicio del margen superior en 25 pixeles 
$y_axis_initial = 25;

//Seteamos el tipo de letra y creamos el título de la página. No es un encabezado no se repetirá
$pdf->SetFont('Arial','B',12);

$pdf->Cell(40,6,'',0,0,'C');
$pdf->Cell(120,8,utf8_decode('LISTA DE VENTAS'),1,0,'C'); 
$pdf->Ln(12);

//Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(22,6,'Fecha',1,0,'C',1); 
$pdf->Cell(38,6,'Cliente',1,0,'C',1);
$pdf->Cell(38,6,'Usuario',1,0,'C',1);
$pdf->Cell(19,6,'Tipo',1,0,'C',1);
$pdf->Cell(20,6,utf8_decode('Número'),1,0,'C',1);
$pdf->Cell(20,6,'Total',1,0,'C',1);
$pdf->Cell(18,6,'Impuesto',1,0,'C',1);
$pdf->Cell(17,6,'Estado',1,0,'C',1);
$pdf->Ln(10);

//Comenzamos a crear las filas de los registros según la consulta mysql
require_once "../modelos/Venta.php";
$venta = new Venta();

$rspta = $venta->listar();

//Implementamos las celdas de la tabla con los registros a mostrar
$pdf->SetWidths(array(22,38,38,19,20,20,18,17));
// La suma total del ancho es 192

while($reg= $rspta->fetch_object())
{  
    $fecha = $reg->fecha;
    $cliente = $reg->cliente;
    $usuario = $reg->usuario;
    $tipo_comprobante = $reg->tipo_comprobante;
    $num_comprobante = $reg->serie_comprobante.'-'.$reg->num_comprobante;
    $total_venta = $reg->total_venta;
    $impuesto = $reg->impuesto;
    $estado = $reg->estado;
 	
 	$pdf->SetFont('Arial','',10);
    $pdf->Row(array($fecha,utf8_decode($cliente),utf8_decode($usuario),$tipo_comprobante,$num_comprobante,$total_venta, $impuesto, $estado));
}

//Mostramos el documento pdf
$pdf->Output($dest='', $name='Lista de Ventas.pdf', $isUTF8=true);
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>