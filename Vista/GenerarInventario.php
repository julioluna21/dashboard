<?php
session_start();
require('../public/PDF/fpdf.php');
require "../Modelo/ConfiguracionModelo.php";
require_once "../Modelo/ConsultasAnidadas.php";//Utilizará este archivo
if(isset($_SESSION['IdUsuarios'])){
class PDF extends FPDF
{

   
var $tablewidths;
var $footerset;   
var $tamaño=0;
var $pagina=1;    

function beginpage($orientation, $size) {
 $this->page++;
 
// Resuelve el problema de sobrescribir una página si ya existe.
 if(!isset($this->pages[$this->page]))   
  $this->pages[$this->page] = '';
 $this->state  =2;
 $this->x = $this->lMargin;
 $this->y = $this->tMargin;
 $this->FontFamily = '';

 // Compruebe el tamaño y la orientación.
 if($orientation=='')
  $orientation = $this->DefOrientation;
 else
  $orientation = strtoupper($orientation[0]);
 if($size=='')
  $size = $this->DefPageSize;
 else
  $size = $this->_getpagesize($size);
 if($orientation!=$this->CurOrientation || $size[0]!=$this->CurPageSize[0] || $size[1]!=$this->CurPageSize[1])
 {

  // Nuevo tamaño o la orientación
  if($orientation=='P')
  {
   $this->w = $size[0];
   $this->h = $size[1];
     
  }
  else
  {
      
   $this->w = $size[1];
   $this->h = $size[0];
  }
  $this->wPt = $this->w*$this->k;
  $this->hPt = $this->h*$this->k;
  $this->PageBreakTrigger = $this->h-$this->bMargin;
  $this->CurOrientation = $orientation;
  $this->CurPageSize = $size;
 }
 if($orientation!=$this->DefOrientation || $size[0]!=$this->DefPageSize[0] || $size[1]!=$this->DefPageSize[1])
  $this->PageSizes[$this->page] = array($this->wPt, $this->hPt);
}

function Footer() {

 // Compruebe si pie de página de esta página ya existe ( lo mismo para Header ( ) )
 if(!isset($this->footerset[$this->page])) {     
  $this->SetY(-15);

  // Numero de Pagina
  $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
  // Conjunto Footerset
  $this->footerset[$this->page] = true;
 }
}

function morepagestable($datas, $lineheight=5) {
 // Algunas cosas para establecer y ' recuerdan '
    
 $l = $this->lMargin;
 if($this->tamaño==0){
 $startheight = $h = $this->GetY();    
 }else{
     if($this->pagina!=$this->page){
        $this->pagina=$this->page; 
        $this->tamaño=40; 
        $startheight = $h = $this->tamaño; 
     }else{
        $startheight = $h = $this->tamaño;
       $this->tamaño=0;    
     }
     
    
 }
 
 $startpage = $currpage = $maxpage = $this->page;
 // Calcular todo el ancho
 $fullwidth = 0;
 foreach($this->tablewidths AS $width) {
  $fullwidth += $width;
 }

 // Ahora vamos a empezar a escribir la tabla
 foreach($datas AS $row => $data) {
  $this->page = $currpage;
  
  // Escribir los bordes horizontales
       
  $this->Line($l,$h,$fullwidth+$l,$h);
     
  // Escribir el contenido y recordar la altura de la más alta columna
  foreach($data AS $col => $txt) {

   $this->page = $currpage;  
   $this->SetXY($l,$h);        
   $this->MultiCell($this->tablewidths[$col],$lineheight,$txt,0,'C');      
   $l += $this->tablewidths[$col];
   if(!isset($tmpheight[$row.'-'.$this->page])){
    $tmpheight[$row.'-'.$this->page] = 0;  
      
   } 
   if($tmpheight[$row.'-'.$this->page] < $this->GetY()) {
    $tmpheight[$row.'-'.$this->page] = $this->GetY();
    
   }

    if($this->tamaño<$this->GetY()){
    $this->tamaño=$this->GetY();
    }  
      
   if($this->page > $maxpage){
    $maxpage = $this->page;     
   }
    
      
  }

  // Obtener la altura estábamos en la última página utilizada
  $h = $tmpheight[$row.'-'.$maxpage];
  //Establecer el "puntero " al margen izquierdo
  $l = $this->lMargin;

  // Establecer el "$currpage en la ultima pagina
  $currpage = $maxpage;
 }

 // Dibujar las fronteras
 // Empezamos a añadir una línea horizontal en la última página
 $this->page = $maxpage;    
 $this->Line($l,$h,$fullwidth+$l,$h);
 // Ahora empezamos en la parte superior del documento
 for($i = $startpage; $i <= $maxpage; $i++) {
     
  $this->page = $i;     
  $l = $this->lMargin;
  $t  = ($i == $startpage) ? $startheight : $this->tMargin;
  $lh = ($i == $maxpage)   ? $h : $this->h-$this->bMargin; 

     
  $this->Line($l,$t,$l,$lh);
  foreach($this->tablewidths AS $width) {
   $l += $width;  
   $this->Line($l,$t,$l,$lh);
  }
 }
 // Establecerlo en la última página , si no que va a causar algunos problemas
 $this->page = $maxpage;
}
     
function pasopagina(){

    while($this->page==2){
     $this->Ln(10);    
    }
}    
     
        
// Cabecera de página*/
function Header()
{
   // Logo

   
}
	
function getImage($dataURI){
  $img = explode(',',$dataURI,2);
  $pic = 'data://text/plain;base64,'.$img[1];
  $type = explode("/", explode(':', substr($dataURI, 0, strpos($dataURI, ';')))[1])[1]; // get the image type
  if ($type=="png"||$type=="jpeg"||$type=="gif") return array($pic, $type);
  return false;
}	

function texto($texto)
{

$this->SetFont('Arial','',11);
$this->MultiCell(185,5,utf8_decode($texto));
}
   
}


// Creación del objeto de la clase heredada
$inventario = new consultas();
$idinventario=isset($_POST["idinventario"])? limpiarCadena($_POST["idinventario"]):"";
$placa=isset($_POST["placa"])? limpiarCadena($_POST["placa"]):"";	

	
$pdf = new PDF();
#Establecemos los márgenes izquierda, arriba y derecha
$pdf->Ln(20);	
$pdf->SetMargins(13, 20 , 20);		
#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,25); 
$pdf->AliasNbPages();
$pdf->AddPage();	
$pdf->Image('../public/img/logo_acta.jpg',18,10,27);	
$pdf->Ln(10);		
$pdf->SetFont('Arial','B',11);	
$pdf->Multicell(0,2,utf8_decode("INVENTARIO DE ELEMENTOS VEHICULO ".$placa),0,"C");
		
	
$pdf->Line(13, 34 , 190, 34);  //Horizontal
	
$pdf->Ln(10);	

unset ($data);
    $pdf->SetFont('Arial', 'B', 9);
    $data[] = array(utf8_decode("ITEM"),utf8_decode("PRODUCTO"),utf8_decode("CANTIDAD"),utf8_decode("ESTADO"),utf8_decode("FOTO"),utf8_decode("OBSERVCIÓN"));
    $pdf->tablewidths = array(11,44,20,25,45,45);
    $pdf->morepagestable($data,5);


$pdf->SetFont('Arial', '', 7);
$rspta=$inventario->MostrarInventario($idinventario);//Carga la rspta con lista de articulos
$contador=0;
$yPos = $pdf->GetY();	
while ($reg = $rspta->fetch_object()) { // mientras exista objeto en la respuesta
    unset($data);
    $contador = $contador + 1;

    // Obtener la imagen en Base64
    $base64Image = $reg->IMAGEN_REFERENCIA; // Suponiendo que el campo de la imagen en Base64 se llama FOTO_BASE64


    // Establecer los datos de la fila sin la imagen
    $data[] = array(
        utf8_decode($contador),
        utf8_decode(strtoupper($reg->NOMBRE_ELEMENTO)),
        utf8_decode($reg->CANTIDADINV),
        utf8_decode($reg->ESTADOINV),'                                                                                                                                                                                                                                                                        ', // Dejar la celda de la imagen vacía
        utf8_decode($reg->OBSERVACIONINV)
    );
    $pdf->tablewidths = array(11, 44, 20, 25, 45, 45);
    $pdf->morepagestable($data);
	
	if(!empty($base64Image)){
    $pic =$pdf->getImage($base64Image);
    if ($pic!==false){
	// Posicionar la imagen manualmente en la celda de "FOTO"
	if($contador>1){
	 $xPos = $pdf->GetX()+110; // Ajustar el valor de acuerdo a la posición de la celda
     $yPos = $pdf->GetY()-3; // Ajustar el valor de acuerdo a la posición de la celda	
	}else{
	$xPos = $pdf->GetX()+110; // Ajustar el valor de acuerdo a la posición de la celda
    $yPos = 50 ; // Ajustar el valor de acuerdo a la posición de la celda	
	}	
  
	$pdf->Image($pic[0],$xPos,$yPos,20,20, $pic[1]);
      
	} 
     }
}	

$pdf->Ln(9);
    
    

$pdf->Output();
}
?>