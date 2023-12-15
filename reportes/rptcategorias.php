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
if ($_SESSION['almacen']==1)
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
$pdf->Cell(100,8,utf8_decode('LISTA DE CATEGORÍAS'),1,0,'C');
$pdf->Ln(12);

//Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
$pdf->SetFillColor(232,232,232); 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(81,6,'Nombre',1,0,'C',1); 
$pdf->Cell(111,6,utf8_decode('Descripción'),1,0,'C',1);
$pdf->Ln(10);

//Comenzamos a crear las filas de los registros según la consulta mysql
require_once "../modelos/Categoria.php";
$categoria = new Categoria();

$rspta = $categoria->listar();

//Implementamos las celdas de la tabla con los registros a mostrar
$pdf->SetWidths(array(81,111));
// La suma total del ancho es 192

while($reg= $rspta->fetch_object())
{  
    $nombre = $reg->nombre;
    $descripcion =$reg->descripcion;
 	
 	$pdf->SetFont('Arial','',10);
    $pdf->Row(array(utf8_decode($nombre),utf8_decode($descripcion)));
}

//Mostramos el documento pdf
$pdf->Output($dest='', $name='Lista de Categorías.pdf', $isUTF8=true);
}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>