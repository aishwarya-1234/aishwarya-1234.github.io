<?PHP
 include("conexion.php");include('session.php');
 require_once('Mail.php');
 require_once('Mail/mime.php') ;
 // INCLUDE THE phpToPDF.php FILE
require("./scripts/phptopdf/phpToPDF.php"); 

// SET YOUR PDF OPTIONS
// FOR ALL AVAILABLE OPTIONS, VISIT HERE:  http://phptopdf.com/documentation/
$pdf_options = array(
  "source_type" => 'url',
  "source" => 'http://74.208.46.71:8080/eclipse/orden_compra_template.php?id=0',
  "action" => 'save',
  "save_directory" => 'compras',
  "file_name" => 'orden_compra_template.pdf',
  "page_size" => 'A4');

 $mysqli = new mysqli($servidor,$usrio,$contra,$base);
 $acentos = $mysqli->query("SET NAMES 'utf8'");
 date_default_timezone_set('America/Mexico_City');
 $fecha = date("Y-m-d");
 $hora = date("H:i:s");
if ($mysqli->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if(isset($_GET["id"])) $id = $_GET["id"]; else $id = 0;

if(isset($_GET["opcion"])) $opcion = $_GET["opcion"]; else $opcion = 0;

$querytxt = "SELECT tcompras_cliente.*,tproveedores.nombre as proveedor,tproveedores.direccion as direccionp,ttelefono.telefono,tclientes.idtclientes,tclientes.nombre as cliente,tclientes.direccion as direccionc,tclientes.colonia,tclientes.ciudad,tclientes.estado,tclientes.cp,tclientes.rfc as rfcc,tcontactos.nombre as contacto,tcontactos.correo as email,tusuarios.nombre as solicita,tusuarios.correo,tmonedas.tipo as moneda,SUM(pfinal) as subtotal FROM tcompras_cliente,tproveedores,tclientes,tcontactos,tusuarios,tmonedas,totales,ttelefono,estatus_compra WHERE tcompras_cliente.tmonedas_idtmonedas=tmonedas.idtmonedas AND tcompras_cliente.Tcontactos_idtcontactos=tcontactos.idtcontactos AND tcompras_cliente.Tusuarios_idTusuarios1=tusuarios.idTusuarios AND tcompras_cliente.Tclientes_idTclientes=tclientes.idTclientes AND tcompras_cliente.Tproveedores_idTproveedores=tproveedores.idTproveedores AND tcompras_cliente.estatus_compra_idestatus_compra=estatus_compra.idestatus_compra AND totales.tcompras_cliente_idtcompras_cliente=tcompras_cliente.idtcompras_cliente AND ttelefono.Tcontactos_idtcontactos=tcontactos.idtcontactos AND tcompras_cliente.idtcompras_cliente=".$id;
$query = $mysqli->query($querytxt);
$numrows = $query->num_rows;

if($numrows>0){
     $row = $query->fetch_array(MYSQLI_ASSOC);
	 $arreglo = explode("-",$row["orden_id"]);
	 $s=$arreglo[0];
	$anio = substr($s,0,4);
	$mes = substr($s,4,2);
	$dia = substr($s,6,2);
	$fecha_compra = $dia."/".$mes."/".$anio;
	$iva1 = $row["subtotal"]*0.16;
	$iva = number_format($iva1,2);
	$total = $iva1 + $row["subtotal"];
	$total = number_format($total,2);
}
$querytxt2 = "SELECT tusuarios.* FROM tusuarios WHERE Tclientes_idTclientes=".$row["idtclientes"];
$query2 = $mysqli->query($querytxt2);
$numrows2 = $query2->num_rows;
if($numrows2>0){
	$row2 = $query2->fetch_array(MYSQLI_ASSOC);
}
$querytxt3 = "SELECT tcompras.*,totales.pfinal FROM `tcompras`,totales WHERE totales.idTcompras=tcompras.idTcompras AND tcompras.`tcompras_cliente_idtcompras_cliente`=".$id." ORDER BY idTcompras";
$query3 = $mysqli->query($querytxt3);
$numrows3 = $query3->num_rows;
$i = 247;
$j = 26;

if($opcion==1){
   if($row["estatus_compra_idestatus_compra"]=='4'){
	$querytxt = "UPDATE tcompras_cliente SET estatus_compra_idestatus_compra='1' WHERE idtcompras_cliente=".$id;
	$mysqli->query($querytxt);
	
   }
   if($row["estatus_compra_idestatus_compra"]!='3'){
	   $pdf_options["source"] = "http://74.208.46.71:8080/eclipse/orden_compra_template.php?id=".$id;
	   $pdf_options["file_name"] = "orden_compra_".$row["orden_id"].".pdf";
	   // CALL THE phptopdf FUNCTION WITH THE OPTIONS SET ABOVE
   	   phptopdf($pdf_options);
	   $mensaje.="Compra guardada con éxito.";
   }

}
else if($opcion==2){
	if($row["estatus_compra_idestatus_compra"]=='1'||$row["estatus_compra_idestatus_compra"]=='5'){
		$querytxt = "UPDATE tcompras_cliente SET estatus_compra_idestatus_compra='2' WHERE idtcompras_cliente=".$id;
		$mysqli->query($querytxt);
	
		$from = "sco@eclipsemex.com"; 
		//$to = "Ramon Villagran <ramon.villagran@eclipsemex.com>,<maestreheader@gmail.com>"; 
		$to = "<guanabanaxxx@hotmail.com>,<lococomida@gmail.com>";
		$subject = "Orden de compra ".$row["orden_id"];
		$host = "mail.eclipsemex.com";
		$username = "sco@eclipsemex.com";
		$password = "SCOEclipse2015";
		$port=587;
		$text = 'Text version of email';
		$texto_correo = str_replace("á","&aacute;",$row["descripcion"]);
		$texto_correo = str_replace("é","&eacute;",$row["descripcion"]);
		$texto_correo = str_replace("í","&iacute;",$row["descripcion"]);
		$texto_correo = str_replace("ó","&oacute;",$row["descripcion"]);
		$texto_correo = str_replace("ú","&uacute;",$row["descripcion"]);
		$texto_correo = str_replace("Á","&Aacute;",$row["descripcion"]);
		$texto_correo = str_replace("É","&Eacute;",$row["descripcion"]);
		$texto_correo = str_replace("Í","&Iacute;",$row["descripcion"]);
		$texto_correo = str_replace("Ó","&Oacute;",$row["descripcion"]);
		$texto_correo = str_replace("Ú","&Uacute;",$row["descripcion"]);
		$texto_correo = str_replace("ñ","&ntilde;",$row["descripcion"]);
		$texto_correo = str_replace("Ñ","&Ntilde;",$row["descripcion"]);
		$html = '<html><head><META http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body><H1>'.$texto_correo.'<P style="margin-top:30px">Mensaje enviado por &eacute;l sistema administrativo de Eclipse</P></H1></body></html>';
		$file = "./compras/orden_compra_".$row["orden_id"].".pdf";
		$crlf = "\n";
		$hdrs = array(
					  'From'    => $from,
					  'To'      => $to,
					  'Subject' => $subject,


					  );

		$mime = new Mail_mime(array('eol' => $crlf));

		$mime->setTXTBody($text);
		$mime->setHTMLBody($html);
		$mime->addAttachment($file, 'text/plain');

		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);

		$mail =& Mail::factory('smtp',   array ('host' => $host,     'auth' => true,     'username' => $username,     'password' => $password, 'port' => $port, 'SMTPSecure' => "tls")); 
		if (PEAR::isError($mail->send($to, $hdrs, $body))) 
		{   
			//echo("<p>" . $mail->getMessage() . "</p>");  
			$mensaje = "No se ha podido enviar la orden de compra, revise el estatus e intente de nuevo.";

		} 
		else {   
			//echo("<p>Message successfully sent!</p>");  
			$mensaje = "La orden de compra ha sido enviada con éxito.";
			} 


	} else $mensaje = "Solo se pueden enviar ordenes de compra con estatus guardada o modificada.";
			
}  else if($opcion==3){
	if($row["estatus_compra_idestatus_compra"]=='3'){
		$querytxt = "INSERT INTO tcompras_cliente(`orden_id`,`proyecto`,`oc_cliente`,`deal_id`,`descripcion`,`tmonedas_idtmonedas`,`Tcontactos_idtcontactos`,`Tusuarios_idTusuarios`,`Tusuarios_idTusuarios1`,`Tclientes_idTclientes`,`Tproveedores_idTproveedores`,`estatus_compra_idestatus_compra`) SELECT `orden_id`,`proyecto`,`oc_cliente`,`deal_id`,`descripcion`,`tmonedas_idtmonedas`,`Tcontactos_idtcontactos`,`Tusuarios_idTusuarios`,`Tusuarios_idTusuarios1`,`Tclientes_idTclientes`,`Tproveedores_idTproveedores`,`estatus_compra_idestatus_compra` FROM `tcompras_cliente` WHERE `idtcompras_cliente`=".$id;
		$mysqli->query($querytxt);
		$last_id=$mysqli->insert_id;
		$querytxt = "UPDATE tcompras_cliente SET estatus_compra_idestatus_compra='4' WHERE idtcompras_cliente=".$last_id;
		$mysqli->query($querytxt);
		$arreglo = explode("-",$row["orden_id"]);
		$arreglo2 = explode("-",$fecha);
		$fechac = $arreglo2[0].$arreglo2[1].$arreglo2[2];
		$orden_compra_id = $fechac."-1"."-".$arreglo[2];
		$querytxt = "UPDATE tcompras_cliente SET orden_id='".$orden_compra_id."' WHERE idtcompras_cliente=".$last_id;
		$mysqli->query($querytxt);
		$querytxt ="INSERT INTO tcompras(`partida`,`numero_parte`,`descripcion`,`precio_lista`,`cantidad`,`descuento`,`hora_registro`,`fecha_registro`) SELECT `partida`,`numero_parte`,`descripcion`,`precio_lista`,`cantidad`,`descuento`,`hora_registro`,`fecha_registro` FROM `tcompras` WHERE `tcompras_cliente_idtcompras_cliente`=".$id;
		$mysqli->query($querytxt);
		$querytxt = "UPDATE tcompras SET tcompras_cliente_idtcompras_cliente='".$last_id."' WHERE tcompras_cliente_idtcompras_cliente=0";
		$mysqli->query($querytxt);
		$mensaje = "Se ha generado una orden de compra con la información de la orden cancelada.";
	}	
	
	
	
}


   else if($opcion==4){
	if($row["estatus_compra_idestatus_compra"]=!'3'){
		$querytxt = "UPDATE tcompras_cliente SET estatus_compra_idestatus_compra='3' WHERE idtcompras_cliente=".$id;
		$mysqli->query($querytxt);
	
		$from = "sco@eclipsemex.com"; 
		//$to = "Ramon Villagran <ramon.villagran@eclipsemex.com>,<maestreheader@gmail.com>"; 
		$to = "<guanabanaxxx@hotmail.com>,<lococomida@gmail.com>";
		$subject = "Orden de compra ".$row["orden_id"];
		$host = "mail.eclipsemex.com";
		$username = "sco@eclipsemex.com";
		$password = "SCOEclipse2015";
		$port=587;
		$text = 'Text version of email';
		$html = '<html><body><H1>'.$row["descripcion"].'</H1></body></html>';
		$file = "./compras/orden_compra_".$row["orden_id"].".pdf";
		$crlf = "\n";
		$hdrs = array(
					  'From'    => $from,
					  'To'      => $to,
					  'Subject' => $subject,


					  );

		$mime = new Mail_mime(array('eol' => $crlf));

		$mime->setTXTBody($text);
		$mime->setHTMLBody($html);
		$mime->addAttachment($file, 'text/plain');

		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);

		$mail =& Mail::factory('smtp',   array ('host' => $host,     'auth' => true,     'username' => $username,     'password' => $password, 'port' => $port, 'SMTPSecure' => "tls")); 
		if (PEAR::isError($mail->send($to, $hdrs, $body))) 
		{   
			//echo("<p>" . $mail->getMessage() . "</p>");  
			$mensaje = "No se ha podido cancelar la orden de compra, ocurrió un error";

		} 
		else {   
			//echo("<p>Message successfully sent!</p>");  
			$mensaje = "La orden de compra ha sido cancelada.";
			} 


	}
	
	
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!-- saved from url=(0014)about:internet -->
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=8">
<TITLE>ORDEN DE COMPRA <?PHP echo $row["orden_id"];?></TITLE>
<META name="generator" content="BCL easyConverter SDK 4.0.83">
<STYLE type="text/css">
.zoom {
    zoom: 1.7;
	-moz-transform: scale(1.7);
    -moz-transform-origin: 0 0;
}


body {margin-top: 0px;margin-left: 0px;}
#menu {margin-left:  10%;width: 100%;}
#page_1 {position:relative; overflow: hidden;margin: 32px 0px 32px 38px;padding: 0px;border: none;width: 778px;}
#page_1 #id_1 {border:none;margin: 13px 0px 0px 2px;padding: 0px;border:none;width: 776px;overflow: hidden;}
#page_1 #id_1 #id_1_1 {float:left;border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 315px;overflow: hidden;}
#page_1 #id_1 #id_1_2 {float:left;border:none;margin: 23px 0px 0px 0px;padding: 0px;border:none;width: 461px;overflow: hidden;}
#page_1 #id_2 {border:none;margin: 8px 0px 0px 0px;padding: 0px;border:none;width: 778px;overflow: hidden;}
#page_1 #id_3 {border:none;margin: 300px 0px 0px 35px;padding: 0px;border:none;width: 670px;overflow: hidden;}

#page_1 #p1dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:729px;height:238px;}
#page_1 #p1dimg1 #p1img1 {width:729px;height:238px;}




.dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}

.ft0{font: bold 10px 'Arial';line-height: 12px;}
.ft1{font: 8px 'Arial';line-height: 10px;}
.ft2{font: 8px 'Arial';text-decoration: underline;color: #0000ff;line-height: 10px;}
.ft3{font: 20px 'Arial';line-height: 23px;}
.ft4{font: bold 10px 'Arial';color: #ffffff;line-height: 12px;}
.ft5{font: bold 7px 'Arial';line-height: 7px;}
.ft6{font: 7px 'Arial';line-height: 7px;}
.ft7{font: 7px 'Arial';text-decoration: underline;line-height: 7px;}
.ft8{font: 1px 'Arial';line-height: 1px;}
.ft9{font: 6px 'Arial';line-height: 6px;}
.ft10{font: bold 6px 'Arial';line-height: 6px;}
.ft11{font: 6px 'Arial';text-decoration: underline;line-height: 6px;}
.ft12{font: bold 7px 'Arial';color: #ffffff;line-height: 7px;}
.ft13{font: bold 6px 'Arial';color: #ffffff;line-height: 6px;}
.ft14{font: 8px 'Arial';line-height: 9px;}

.p0{text-align: left;margin-top: 0px;margin-bottom: 0px;}
.p1{text-align: left;margin-top: 12px;margin-bottom: 0px;}
.p2{text-align: left;padding-right: 208px;margin-top: 3px;margin-bottom: 0px;}
.p3{text-align: left;padding-left: 340px;margin-top: 0px;margin-bottom: 0px;}
.p4{text-align: left;padding-left: 17px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p5{text-align: left;padding-left: 2px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p6{text-align: left;padding-left: 6px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p7{text-align: left;padding-left: 21px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p8{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p9{text-align: left;padding-left: 5px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p10{text-align: left;padding-left: 20px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p11{text-align: right;padding-right: 6px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p12{text-align: left;padding-left: 22px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p13{text-align: left;padding-left: 29px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p14{text-align: left;padding-left: 41px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p15{text-align: left;padding-left: 332px;margin-top: 0px;margin-bottom: 0px;}
.p16{text-align: left;padding-left: 1px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p17{text-align: right;padding-right: 66px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p18{text-align: right;padding-right: 17px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p19{text-align: center;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p20{text-align: left;padding-left: 11px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p21{text-align: right;padding-right: 50px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p22{text-align: left;padding-left: 36px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p23{text-align: left;padding-left: 89px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p24{text-align: right;padding-right: 12px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p25{text-align: right;padding-right: 41px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p26{text-align: left;padding-left: 4px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p27{text-align: right;padding-right: 1px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p28{text-align: right;padding-right: 4px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p29{text-align: right;padding-right: 2px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p30{text-align: center;padding-left: 1px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p31{text-align: right;padding-right: 19px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p32{text-align: right;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p33{text-align: right;padding-right: 7px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p34{text-align: right;padding-right: 5px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}

.td0{padding: 0px;margin: 0px;width: 496px;vertical-align: bottom;}
.td1{padding: 0px;margin: 0px;width: 70px;vertical-align: bottom;}
.td2{padding: 0px;margin: 0px;width: 161px;vertical-align: bottom;}
.td3{padding: 0px;margin: 0px;width: 1px;vertical-align: bottom;}
.td4{padding: 0px;margin: 0px;width: 58px;vertical-align: bottom;}
.td5{padding: 0px;margin: 0px;width: 86px;vertical-align: bottom;}
.td6{padding: 0px;margin: 0px;width: 100px;vertical-align: bottom;}
.td7{padding: 0px;margin: 0px;width: 47px;vertical-align: bottom;}
.td8{padding: 0px;margin: 0px;width: 214px;vertical-align: bottom;}
.td9{padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;}
.td10{padding: 0px;margin: 0px;width: 23px;vertical-align: bottom;}
.td11{padding: 0px;margin: 0px;width: 29px;vertical-align: bottom;}
.td12{padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;}
.td13{padding: 0px;margin: 0px;width: 4px;vertical-align: bottom;}
.td14{padding: 0px;margin: 0px;width: 105px;vertical-align: bottom;}
.td15{padding: 0px;margin: 0px;width: 31px;vertical-align: bottom;}
.td16{padding: 0px;margin: 0px;width: 50px;vertical-align: bottom;}
.td17{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 1px;vertical-align: bottom;}
.td18{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 58px;vertical-align: bottom;}
.td19{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 86px;vertical-align: bottom;}
.td20{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 100px;vertical-align: bottom;}
.td21{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 47px;vertical-align: bottom;}
.td22{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 214px;vertical-align: bottom;}
.td23{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 31px;vertical-align: bottom;}
.td24{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 29px;vertical-align: bottom;}
.td25{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;}
.td26{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 4px;vertical-align: bottom;}
.td27{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;}
.td28{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 105px;vertical-align: bottom;}
.td29{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 1px;vertical-align: bottom;background: #000000;}
.td30{border-right: #538dd5 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 57px;vertical-align: bottom;background: #538dd5;}
.td31{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 85px;vertical-align: bottom;}
.td32{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 99px;vertical-align: bottom;}
.td33{border-right: #538dd5 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;background: #538dd5;}
.td34{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 213px;vertical-align: bottom;}
.td35{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 28px;vertical-align: bottom;}
.td36{border-right: #538dd5 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 45px;vertical-align: bottom;background: #538dd5;}
.td37{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 104px;vertical-align: bottom;}
.td38{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 23px;vertical-align: bottom;}
.td39{border-right: #000000 1px solid;border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 57px;vertical-align: bottom;background: #538dd5;}
.td40{border-right: #000000 1px solid;border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 85px;vertical-align: bottom;background: #538dd5;}
.td41{border-right: #538dd5 1px solid;border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 99px;vertical-align: bottom;background: #538dd5;}
.td42{border-right: #000000 1px solid;border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;background: #538dd5;}
.td43{border-right: #000000 1px solid;border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 213px;vertical-align: bottom;background: #538dd5;}
.td44{border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;background: #538dd5;}
.td45{border-right: #000000 1px solid;border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 51px;vertical-align: bottom;background: #538dd5;}
.td46{border-right: #000000 1px solid;border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 45px;vertical-align: bottom;background: #538dd5;}
.td47{border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 4px;vertical-align: bottom;background: #538dd5;}
.td48{border-right: #000000 1px solid;border-bottom: #538dd5 1px solid;padding: 0px;margin: 0px;width: 104px;vertical-align: bottom;background: #538dd5;}
.td49{padding: 0px;margin: 0px;width: 1px;vertical-align: bottom;background: #000000;}
.td50{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 57px;vertical-align: bottom;}
.td51{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 85px;vertical-align: bottom;}
.td52{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;}
.td53{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 213px;vertical-align: bottom;}
.td54{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 45px;vertical-align: bottom;}
.td55{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 104px;vertical-align: bottom;}
.td56{border-right: #000000 1px solid;border-bottom: #000000 1px solid;border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 57px;vertical-align: bottom;}
.td57{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;}
.td58{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 28px;vertical-align: bottom;}
.td59{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 45px;vertical-align: bottom;}
.td60{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 50px;vertical-align: bottom;background: #538dd5;}
.td61{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;background: #538dd5;}
.td62{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 104px;vertical-align: bottom;background: #538dd5;}
.td63{padding: 0px;margin: 0px;width: 270px;vertical-align: bottom;}
.td64{padding: 0px;margin: 0px;width: 357px;vertical-align: bottom;}
.td65{padding: 0px;margin: 0px;width: 43px;vertical-align: bottom;}

.tr0{height: 9px;}
.tr1{height: 8px;}
.tr2{height: 10px;}
.tr3{height: 7px;}
.tr4{height: 11px;}
.tr5{height: 13px;}
.tr6{height: 12px;}
.tr7{height: 20px;}

.t0{width: 727px;margin-left: 1px;font: bold 7px 'Arial';}
.t1{width: 729px;font: 6px 'Arial';}
.t2{width: 670px;font: 7px 'Arial';}

</STYLE>
<script language="JavaScript">
function first(uno)
{
	var one = document.getElementById(uno);
	for(var two = 0; two < one.childNodes.length; ++two)
	{
		if(one.childNodes[two].tagName == "TBODY")
		{
			one = one.childNodes[two];
			break;
		}
	}
	return one;
}

function second(dos)
{
	var one2 = document.createElement("tr");
	if(dos >= 0)
		one2.height = dos + "px";
	return one2;
}

function third(arg1, arg2, arg3, arg4)
{
	var one3 = document.createElement("td");
	if(arg1 != 1)
		one3.rowSpan = arg1;
	if(arg2 != 1)
		one3.colSpan = arg2;
	if(arg3 != "")
		one3.className = arg3;
	if(arg4 >= 0)
		one3.width = arg4 + "px";
	return one3;
}

function fourth()
{   var columnas=Array();
    var renglones=Array();
	var a1 = first("t1");
	var a2 = first("t2");
	var a3 = first("t3");
	columnas[0] = third(1, 1, "tr7 td6", -1);
	columnas[1] = third(1, 1, "tr0 td0", -1);
	columnas[2] = third(1, 2, "tr0 td15", -1);
	renglones[0] = second(-1);
	columnas[3] = third(1, 1, "tr1 td7", -1);
	columnas[4] = third(1, 1, "tr2 td34", -1);
	columnas[5] = third(1, 1, "tr3 td17", -1);
	columnas[6] = third(1, 1, "tr0 td2", -1);
	columnas[7] = third(1, 1, "tr0 td4", -1);
	renglones[1] = second(-1);
	columnas[8] = third(1, 1, "tr2 td7", -1);
	columnas[9] = third(1, 1, "tr4 td29", -1);
	columnas[10] = third(1, 1, "tr3 td17", -1);
	columnas[11] = third(1, 1, "tr5 td4", -1);
	columnas[12] = third(1, 1, "tr3 td53", -1);
	renglones[2] = second(-1);
	columnas[13] = third(1, 1, "tr4 td35", -1);
	columnas[14] = third(1, 1, "tr5 td7", -1);
	columnas[15] = third(1, 1, "tr5 td10", -1);
	columnas[16] = third(1, 1, "tr1 td6", -1);
	columnas[17] = third(1, 1, "tr7 td35", -1);
	columnas[18] = third(1, 1, "tr3 td55", -1);
	columnas[19] = third(1, 1, "tr0 td3", -1);
	columnas[20] = third(1, 2, "tr1 td15", -1);
	columnas[21] = third(1, 1, "tr1 td22", -1);
	columnas[22] = third(1, 1, "tr4 td42", -1);
	columnas[23] = third(1, 1, "tr3 td27", -1);
	columnas[24] = third(1, 2, "tr6 td60", -1);
	columnas[25] = third(1, 1, "tr3 td22", -1);
	columnas[26] = third(1, 1, "tr2 td9", -1);
	columnas[27] = third(1, 1, "tr3 td50", -1);
	columnas[28] = third(1, 2, "tr0 td15", -1);
	columnas[29] = third(1, 1, "tr7 td9", -1);
	columnas[30] = third(1, 1, "tr1 td58", -1);
	columnas[31] = third(1, 1, "tr5 td6", -1);
	columnas[32] = third(1, 1, "tr3 td6", -1);
	renglones[3] = second(-1);
	columnas[33] = third(1, 1, "tr2 td5", -1);
	columnas[34] = third(1, 1, "tr0 td1", -1);
	renglones[4] = second(-1);
	columnas[35] = third(1, 1, "tr2 td27", -1);
	columnas[36] = third(1, 1, "tr7 td3", -1);
	columnas[37] = third(1, 1, "tr3 td49", -1);
	columnas[38] = third(1, 1, "tr4 td9", -1);
	renglones[5] = second(-1);
	columnas[39] = third(1, 1, "tr0 td2", -1);
	columnas[40] = third(1, 1, "tr6 td61", -1);
	columnas[41] = third(1, 1, "tr2 td8", -1);
	columnas[42] = third(1, 1, "tr0 td4", -1);
	columnas[43] = third(1, 1, "tr3 td13", -1);
	columnas[44] = third(1, 1, "tr2 td33", -1);
	columnas[45] = third(1, 1, "tr2 td3", -1);
	columnas[46] = third(1, 1, "tr2 td30", -1);
	columnas[47] = third(1, 1, "tr6 td27", -1);
	columnas[48] = third(1, 1, "tr1 td9", -1);
	columnas[49] = third(1, 1, "tr2 td26", -1);
	columnas[50] = third(1, 1, "tr5 td4", -1);
	columnas[51] = third(1, 1, "tr1 td8", -1);
	columnas[52] = third(1, 1, "tr0 td7", -1);
	columnas[53] = third(1, 1, "tr1 td63", -1);
	columnas[54] = third(1, 1, "tr3 td28", -1);
	columnas[55] = third(1, 1, "tr3 td9", -1);
	columnas[56] = third(1, 1, "tr3 td26", -1);
	columnas[57] = third(1, 2, "tr7 td16", -1);
	columnas[58] = third(1, 1, "tr0 td4", -1);
	columnas[59] = third(1, 1, "tr4 td46", -1);
	renglones[6] = second(-1);
	renglones[7] = second(-1);
	columnas[60] = third(1, 1, "tr2 td13", -1);
	columnas[61] = third(1, 1, "tr0 td11", -1);
	columnas[62] = third(1, 1, "tr5 td8", -1);
	columnas[63] = third(1, 1, "tr1 td29", -1);
	columnas[64] = third(1, 1, "tr0 td2", -1);
	columnas[65] = third(1, 1, "tr3 td27", -1);
	columnas[66] = third(1, 1, "tr1 td28", -1);
	renglones[8] = second(-1);
	columnas[67] = third(1, 1, "tr3 td18", -1);
	columnas[68] = third(1, 1, "tr5 td3", -1);
	columnas[69] = third(1, 1, "tr1 td18", -1);
	columnas[70] = third(1, 1, "tr0 td11", -1);
	columnas[71] = third(1, 1, "tr1 td13", -1);
	columnas[72] = third(1, 1, "tr0 td5", -1);
	renglones[9] = second(-1);
	columnas[73] = third(1, 1, "tr2 td10", -1);
	renglones[10] = second(-1);
	columnas[74] = third(1, 1, "tr0 td9", -1);
	columnas[75] = third(1, 1, "tr6 td26", -1);
	columnas[76] = third(1, 1, "tr3 td9", -1);
	columnas[77] = third(1, 2, "tr0 td16", -1);
	columnas[78] = third(1, 1, "tr3 td35", -1);
	columnas[79] = third(1, 1, "tr1 td11", -1);
	columnas[80] = third(1, 1, "tr7 td8", -1);
	columnas[81] = third(1, 1, "tr5 td35", -1);
	columnas[82] = third(1, 1, "tr0 td3", -1);
	columnas[83] = third(1, 1, "tr5 td3", -1);
	columnas[84] = third(1, 1, "tr3 td21", -1);
	columnas[85] = third(1, 1, "tr0 td1", -1);
	columnas[86] = third(1, 1, "tr4 td32", -1);
	columnas[87] = third(1, 1, "tr0 td8", -1);
	columnas[88] = third(1, 1, "tr0 td35", -1);
	columnas[89] = third(1, 1, "tr0 td3", -1);
	columnas[90] = third(1, 1, "tr6 td28", -1);
	columnas[91] = third(1, 1, "tr1 td65", -1);
	columnas[92] = third(1, 1, "tr3 td50", -1);
	renglones[11] = second(-1);
	columnas[93] = third(1, 1, "tr1 td9", -1);
	columnas[94] = third(1, 1, "tr3 td19", -1);
	columnas[95] = third(1, 1, "tr5 td11", -1);
	columnas[96] = third(1, 1, "tr4 td44", -1);
	columnas[97] = third(1, 1, "tr0 td2", -1);
	columnas[98] = third(1, 1, "tr3 td53", -1);
	renglones[12] = second(-1);
	columnas[99] = third(1, 1, "tr3 td6", -1);
	columnas[100] = third(1, 1, "tr1 td12", -1);
	columnas[101] = third(1, 1, "tr4 td10", -1);
	columnas[102] = third(1, 1, "tr2 td29", -1);
	columnas[103] = third(1, 1, "tr3 td18", -1);
	columnas[104] = third(1, 1, "tr0 td9", -1);
	columnas[105] = third(1, 1, "tr1 td11", -1);
	columnas[106] = third(1, 1, "tr1 td27", -1);
	columnas[107] = third(1, 1, "tr6 td25", -1);
	columnas[108] = third(1, 1, "tr1 td38", -1);
	columnas[109] = third(1, 1, "tr5 td8", -1);
	columnas[110] = third(1, 2, "tr1 td16", -1);
	columnas[111] = third(1, 1, "tr1 td9", -1);
	renglones[13] = second(-1);
	columnas[112] = third(1, 1, "tr4 td41", -1);
	columnas[113] = third(1, 1, "tr1 td3", -1);
	columnas[114] = third(1, 1, "tr0 td7", -1);
	columnas[115] = third(1, 1, "tr2 td4", -1);
	columnas[116] = third(1, 1, "tr3 td13", -1);
	columnas[117] = third(1, 1, "tr3 td22", -1);
	columnas[118] = third(1, 1, "tr3 td19", -1);
	columnas[119] = third(1, 1, "tr2 td9", -1);
	columnas[120] = third(1, 1, "tr3 td38", -1);
	columnas[121] = third(1, 1, "tr2 td55", -1);
	columnas[122] = third(1, 1, "tr0 td2", -1);
	columnas[123] = third(1, 1, "tr2 td9", -1);
	columnas[124] = third(1, 1, "tr1 td9", -1);
	columnas[125] = third(1, 1, "tr0 td5", -1);
	columnas[126] = third(1, 1, "tr1 td10", -1);
	columnas[127] = third(1, 1, "tr3 td49", -1);
	columnas[128] = third(1, 1, "tr0 td0", -1);
	columnas[129] = third(1, 1, "tr1 td10", -1);
	columnas[130] = third(1, 1, "tr3 td52", -1);
	columnas[131] = third(1, 1, "tr3 td25", -1);
	columnas[132] = third(1, 1, "tr2 td9", -1);
	columnas[133] = third(1, 1, "tr5 td6", -1);
	columnas[134] = third(1, 1, "tr7 td10", -1);
	columnas[135] = third(1, 1, "tr2 td31", -1);
	columnas[136] = third(1, 1, "tr5 td9", -1);
	columnas[137] = third(1, 1, "tr2 td3", -1);
	columnas[138] = third(1, 1, "tr3 td21", -1);
	columnas[139] = third(1, 1, "tr0 td14", -1);
	renglones[14] = second(-1);
	columnas[140] = third(1, 1, "tr1 td31", -1);
	columnas[141] = third(1, 1, "tr2 td13", -1);
	columnas[142] = third(1, 1, "tr1 td3", -1);
	columnas[143] = third(1, 1, "tr2 td8", -1);
	columnas[144] = third(1, 1, "tr4 td48", -1);
	columnas[145] = third(1, 1, "tr1 td6", -1);
	columnas[146] = third(1, 1, "tr2 td14", -1);
	columnas[147] = third(1, 1, "tr3 td25", -1);
	columnas[148] = third(1, 1, "tr1 td17", -1);
	columnas[149] = third(1, 1, "tr3 td51", -1);
	columnas[150] = third(1, 1, "tr0 td1", -1);
	columnas[151] = third(1, 1, "tr0 td8", -1);
	columnas[152] = third(1, 1, "tr3 td54", -1);
	columnas[153] = third(1, 1, "tr0 td1", -1);
	columnas[154] = third(1, 1, "tr0 td7", -1);
	columnas[155] = third(1, 1, "tr1 td5", -1);
	renglones[15] = second(-1);
	columnas[156] = third(1, 1, "tr5 td10", -1);
	columnas[157] = third(1, 1, "tr3 td51", -1);
	columnas[158] = third(1, 1, "tr1 td20", -1);
	columnas[159] = third(1, 1, "tr7 td55", -1);
	renglones[16] = second(-1);
	columnas[160] = third(1, 1, "tr4 td44", -1);
	columnas[161] = third(1, 1, "tr1 td2", -1);
	columnas[162] = third(1, 1, "tr0 td0", -1);
	columnas[163] = third(1, 1, "tr1 td64", -1);
	columnas[164] = third(1, 1, "tr1 td6", -1);
	columnas[165] = third(1, 1, "tr3 td10", -1);
	columnas[166] = third(1, 1, "tr1 td27", -1);
	columnas[167] = third(1, 1, "tr1 td14", -1);
	columnas[168] = third(1, 1, "tr1 td1", -1);
	columnas[169] = third(1, 1, "tr2 td10", -1);
	columnas[170] = third(1, 1, "tr0 td2", -1);
	columnas[171] = third(1, 1, "tr7 td7", -1);
	renglones[17] = second(-1);
	columnas[172] = third(1, 1, "tr5 td5", -1);
	renglones[18] = second(-1);
	columnas[173] = third(1, 1, "tr5 td5", -1);
	columnas[174] = third(1, 1, "tr1 td0", -1);
	columnas[175] = third(1, 1, "tr3 td54", -1);
	columnas[176] = third(1, 1, "tr5 td9", -1);
	columnas[177] = third(1, 1, "tr2 td36", -1);
	columnas[178] = third(1, 1, "tr1 td20", -1);
	columnas[179] = third(1, 1, "tr2 td5", -1);
	renglones[19] = second(-1);
	columnas[180] = third(1, 1, "tr0 td6", -1);
	columnas[181] = third(1, 1, "tr3 td35", -1);
	columnas[182] = third(1, 1, "tr6 td62", -1);
	columnas[183] = third(1, 1, "tr1 td26", -1);
	columnas[184] = third(1, 1, "tr1 td19", -1);
	columnas[185] = third(1, 2, "tr0 td16", -1);
	columnas[186] = third(1, 1, "tr1 td56", -1);
	columnas[187] = third(1, 1, "tr2 td37", -1);
	columnas[188] = third(1, 2, "tr0 td16", -1);
	columnas[189] = third(1, 1, "tr4 td40", -1);
	columnas[190] = third(1, 1, "tr2 td6", -1);
	columnas[191] = third(1, 1, "tr2 td6", -1);
	columnas[192] = third(1, 1, "tr1 td25", -1);
	columnas[193] = third(1, 1, "tr2 td12", -1);
	columnas[194] = third(1, 1, "tr1 td59", -1);
	columnas[195] = third(1, 1, "tr3 td52", -1);
	columnas[196] = third(1, 1, "tr0 td0", -1);
	columnas[197] = third(1, 1, "tr3 td27", -1);
	columnas[198] = third(1, 1, "tr2 td12", -1);
	columnas[199] = third(1, 1, "tr1 td57", -1);
	columnas[200] = third(1, 1, "tr1 td4", -1);
	columnas[201] = third(1, 1, "tr1 td11", -1);
	renglones[20] = second(-1);
	renglones[21] = second(-1);
	columnas[202] = third(1, 1, "tr4 td43", -1);
	columnas[203] = third(1, 1, "tr1 td7", -1);
	columnas[204] = third(1, 1, "tr0 td6", -1);
	columnas[205] = third(1, 1, "tr0 td5", -1);
	columnas[206] = third(1, 1, "tr1 td24", -1);
	columnas[207] = third(1, 1, "tr1 td34", -1);
	columnas[208] = third(1, 1, "tr0 td14", -1);
	columnas[209] = third(1, 1, "tr1 td26", -1);
	columnas[210] = third(1, 1, "tr1 td27", -1);
	columnas[211] = third(1, 1, "tr1 td4", -1);
	columnas[212] = third(1, 1, "tr1 td8", -1);
	columnas[213] = third(1, 1, "tr4 td47", -1);
	columnas[214] = third(1, 1, "tr0 td9", -1);
	columnas[215] = third(1, 1, "tr2 td11", -1);
	columnas[216] = third(1, 1, "tr0 td8", -1);
	columnas[217] = third(1, 1, "tr4 td39", -1);
	columnas[218] = third(1, 1, "tr2 td4", -1);
	columnas[219] = third(1, 1, "tr1 td14", -1);
	columnas[220] = third(1, 1, "tr0 td10", -1);
	columnas[221] = third(1, 1, "tr3 td24", -1);
	renglones[22] = second(-1);
	columnas[222] = third(1, 1, "tr3 td26", -1);
	columnas[223] = third(1, 1, "tr3 td20", -1);
	columnas[224] = third(1, 1, "tr2 td35", -1);
	columnas[225] = third(1, 2, "tr4 td45", -1);
	columnas[226] = third(1, 1, "tr2 td7", -1);
	columnas[227] = third(1, 1, "tr1 td37", -1);
	columnas[228] = third(1, 2, "tr1 td23", -1);
	columnas[229] = third(1, 1, "tr5 td7", -1);
	columnas[230] = third(1, 1, "tr0 td1", -1);
	columnas[231] = third(1, 1, "tr3 td10", -1);
	columnas[232] = third(1, 1, "tr1 td21", -1);
	columnas[233] = third(1, 1, "tr0 td0", -1);
	renglones[23] = second(-1);
	columnas[234] = third(1, 1, "tr7 td5", -1);
	columnas[235] = third(1, 1, "tr3 td9", -1);
	columnas[236] = third(1, 1, "tr0 td9", -1);
	columnas[237] = third(1, 1, "tr7 td9", -1);
	columnas[238] = third(1, 1, "tr3 td55", -1);
	columnas[239] = third(1, 1, "tr0 td55", -1);
	columnas[240] = third(1, 1, "tr0 td0", -1);
	renglones[24] = second(-1);
	columnas[241] = third(1, 1, "tr7 td4", -1);
	renglones[25] = second(-1);
	columnas[242] = third(1, 1, "tr0 td1", -1);
	columnas[243] = third(1, 1, "tr3 td28", -1);
	columnas[244] = third(1, 1, "tr0 td6", -1);
	columnas[245] = third(1, 1, "tr1 td5", -1);
	columnas[246] = third(1, 1, "tr3 td9", -1);
	<?PHP
	$k=247;
	for($l=0;$l<$numrows3;$l++){
	?>
	renglones[<?PHP echo $j++;?>] = second(-1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td29", -1);
    columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td56", -1);
    columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td31", -1);
    columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td20", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td57", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td34", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td27", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td38", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td58", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td59", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td26", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td27", -1);
	columnas[<?PHP echo $k++;?>] = third(1, 1, "tr1 td37", -1);
    <?PHP
	}
	?>
	columnas[231].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[199].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[80].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[44].innerHTML = "<P class=\"p20 ft12\">Deal ID</P>";
	columnas[228].innerHTML = "<P style='font-size:7px' class=\"p16 ft10\">Telefono:</P>";
	columnas[110].innerHTML = "<P class=\"p16 ft9\"><?PHP echo substr($row['colonia'],0,30);?></P>";
	columnas[170].innerHTML = "<P class=\"p8 ft6\"><?PHP echo $row['solicita'];?></P>";
	columnas[106].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[51].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[195].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[1].innerHTML = "<P class=\"p7 ft6\"><SPAN class=\"ft5\">Contacto : </SPAN><?PHP echo $row['contacto'];?></P>";
	columnas[237].innerHTML = "<P class=\"p28 ft1\">$</P>";
	columnas[66].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[11].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[146].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[73].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[238].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[167].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[100].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[31].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[206].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[222].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[39].innerHTML = "<P class=\"p8 ft7\"><A href=\"mailto:<?PHP echo $row['correo'];?>\"><?PHP echo $row['correo'];?></A></P>";
	columnas[5].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[141].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[92].innerHTML = "<P class=\"p19 ft9\">1</P>";
	columnas[229].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[90].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[196].innerHTML = "<P class=\"p12 ft6\"><SPAN class=\"ft5\">Proyecto : </SPAN><?PHP echo $row['proyecto'];?></P>";
	columnas[81].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[37].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[61].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[137].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[135].innerHTML = "<P class=\"p16 ft6\"><NOBR><?PHP echo $row['oc_cliente'];?></NOBR></P>";
	columnas[203].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[79].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[140].innerHTML = "<P class=\"p14 ft9\">1</P>";
	columnas[125].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[22].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[235].innerHTML = "<P class=\"p26 ft9\">$</P>";
	columnas[60].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[240].innerHTML = "<P class=\"p4 ft6\"><SPAN class=\"ft5\">Compañía : </SPAN><?PHP echo $row['proveedor'];?></P>";
	columnas[114].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[89].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[215].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[99].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[119].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[209].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[151].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[69].innerHTML = "<P  class=\"p16 ft10\"><NOBR style='font-size:7px;'>e-mail :</NOBR></P>";
	columnas[76].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[123].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[58].innerHTML = "<P class=\"p16 ft5\">Ciudad :</P>";
	columnas[14].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[152].innerHTML = "<P class=\"p19 ft9\">45</P>";
	columnas[18].innerHTML = "<P class=\"p29 ft9\">1,867.25</P>";
	columnas[12].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[212].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[56].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[148].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[35].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[112].innerHTML = "<P class=\"p22 ft13\">No. Parte</P>";
	columnas[127].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[29].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[232].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[78].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[236].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[130].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[174].innerHTML = "<P class=\"p12 ft6\"><SPAN class=\"ft5\">Teléfono : </SPAN><?PHP echo $row['telefono'];?></P>";
	columnas[91].innerHTML = "<P class=\"p8 ft9\">Pagína 1 de 1</P>";
	columnas[94].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[132].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[34].innerHTML = "<P class=\"p8 ft5\">Fecha Elaboración:</P>";
	columnas[188].innerHTML = "<P class=\"p33 ft14\">Subtotal :</P>";
	columnas[42].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[41].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[220].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[149].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[166].innerHTML = "<P class=\"p32 ft9\">$</P>";
	columnas[194].innerHTML = "<P class=\"p19 ft9\">45</P>";
	columnas[25].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[108].innerHTML = "<P class=\"p31 ft9\">$</P>";
	columnas[121].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[82].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[16].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[217].innerHTML = "<P class=\"p19 ft13\">Partida</P>";
	columnas[7].innerHTML = "<P class=\"p16 ft5\">C.P. :</P>";
	columnas[128].innerHTML = "<P class=\"p13 ft6\"><NOBR><SPAN class=\"ft5\">e-mail :</SPAN></NOBR><SPAN class=\"ft5\"> </SPAN><NOBR><A href=\"mailto:<?PHP echo $row['email'];?>\"><SPAN class=\"ft7\"><?PHP echo $row['email'];?></SPAN></A></NOBR></P>";
	columnas[163].innerHTML = "<P class=\"p8 ft6\">Eclipse Telecomunicaciones S.A. de C.V.</P>";
	columnas[176].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[186].innerHTML = "<P class=\"p30 ft9\">4.6</P>";
	columnas[55].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[30].innerHTML = "<P class=\"p27 ft9\">-</P>";
	columnas[6].innerHTML = "<P style=\"padding-left:0px\" class=\"p6 ft6\"><NOBR><?PHP echo $row['orden_id'];?></NOBR></P>";
	columnas[131].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[113].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[207].innerHTML = "<P class=\"p16 ft9\">UCM 9X/10X PAK</P>";
	columnas[36].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[219].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[105].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[13].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[71].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[0].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[160].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[3].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[117].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[216].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[26].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[165].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[230].innerHTML = "<P class=\"p14 ft5\"><NOBR>E-Mail:</NOBR></P>";
	columnas[46].innerHTML = "<P class=\"p19 ft12\">OC Cliente</P>";
	columnas[63].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[187].innerHTML = "<P class=\"p21 ft5\"><?PHP echo $row['moneda'];?></P>";
	columnas[84].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[221].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[48].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[32].innerHTML = "<P class=\"p16 ft10\"><NOBR>C2901-CME-SRST/K9</NOBR></P>";
	columnas[10].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[65].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[47].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[208].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[175].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[225].innerHTML = "<P class=\"p24 ft13\">Precio Lista</P>";
	columnas[83].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[122].innerHTML = "<P style='padding-left:0px' class=\"p9 ft6\"><NOBR><?PHP echo $fecha_compra;?></NOBR></P>";
	columnas[52].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[139].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[201].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[234].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[38].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[72].innerHTML = "<P class=\"p16 ft9\"><?PHP echo $row['ciudad'];?></P>";
	columnas[87].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[150].innerHTML = "<P class=\"p11 ft5\">Solicita:</P>";
	columnas[243].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[53].innerHTML = "<P class=\"p8 ft6\">Confidencial</P>";
	columnas[129].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[54].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[75].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[28].innerHTML = "<P class=\"p16 ft5\">Estado:</P>";
	columnas[183].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[97].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[227].innerHTML = "<P class=\"p29 ft9\">-</P>";
	columnas[96].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[177].innerHTML = "<P class=\"p19 ft12\">Moneda</P>";
	columnas[2].innerHTML = "<P class=\"p16 ft5\">RFC :</P>";
	columnas[218].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[57].innerHTML = "<P class=\"p33 ft1\">IVA 16% :</P>";
	columnas[59].innerHTML = "<P class=\"p19 ft13\">Descuento</P>";
	columnas[20].innerHTML = "<P class=\"p16 ft5\">Colonia:</P>";
	columnas[15].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[169].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[8].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[246].innerHTML = "<P class=\"p28 ft9\">$</P>";
	columnas[204].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[85].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[133].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[43].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[168].innerHTML = "<P class=\"p11 ft5\"><NOBR>E-Mail:</NOBR></P>";
	columnas[180].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[239].innerHTML = "<P class=\"p28 ft14\"><?PHP echo number_format($row['subtotal'],2);?></P>";
	columnas[143].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[181].innerHTML = "<P class=\"p27 ft9\">3,395.00</P>";
	columnas[116].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[223].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[242].innerHTML = "<P class=\"p5 ft5\">Orden Compra ID :</P>";
	columnas[118].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[17].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[101].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[226].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[155].innerHTML = "<P class=\"p16 ft9\"><?PHP echo substr($row['direccionc'],0,30);?></P>";
	columnas[64].innerHTML = "<P class=\"p8 ft6\"><?PHP echo $login_nombre;?></P>";
	columnas[45].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[191].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[77].innerHTML = "<P class=\"p16 ft9\">D.F.</P>";
	columnas[171].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[157].innerHTML = "<P class=\"p14 ft9\">1</P>";
	columnas[102].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[162].innerHTML = "<P class=\"p10 ft6\"><SPAN class=\"ft5\">Dirección : </SPAN><?PHP echo $row['direccionp'];?></P>";
	columnas[126].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[107].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[86].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[67].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[138].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[70].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[210].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[202].innerHTML = "<P class=\"p23 ft13\">Descripción</P>";
	columnas[88].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[27].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[120].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[193].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[103].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[211].innerHTML = "<P class=\"p16 ft5\">Contacto :</P>";
	columnas[49].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[147].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[245].innerHTML = "<P class=\"p16 ft6\"><?PHP echo $row2['nombre'];?></P>";
	columnas[24].innerHTML = "<P class=\"p33 ft4\">Total :</P>";
	columnas[190].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[145].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[154].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[197].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[62].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[68].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[189].innerHTML = "<P class=\"p13 ft13\">Cantidad</P>";
	columnas[173].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[4].innerHTML = "<P class=\"p16 ft6\"><NOBR><?PHP echo $row['deal_id'];?></NOBR></P>";
	columnas[224].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[198].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[19].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[185].innerHTML = "<P class=\"p16 ft9\"><?PHP echo $row['rfcc'];?></P>";
	columnas[159].innerHTML = "<P class=\"p28 ft1\"><?PHP echo $iva;?></P>";
	columnas[192].innerHTML = "<P class=\"p16 ft9\">5284 6200</P>";
	columnas[241].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[74].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[182].innerHTML = "<P class=\"p34 ft4\"><?PHP echo $total;?></P>";
	columnas[161].innerHTML = "<P class=\"p8 ft7\"><A href=\"mailto:<?PHP echo $login_correo;?>\"><?PHP echo $login_correo;?></A></P>";
	columnas[244].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[23].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[98].innerHTML = "<P class=\"p16 ft9\">2901 Voice Bundle <NOBR>w/PVDM3-16</NOBR> <NOBR>FL-CME-SRST-25</NOBR> UC Lic <NOBR>FL-CUBE10</NOBR></P>";
	columnas[214].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[95].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[184].innerHTML = "<P class=\"p16 ft11\"><A style='font-size:7px' href=\"mailto:<?PHP echo $row2['correo'];?>\"><?PHP echo $row2['correo'];?></A></P>";
	columnas[179].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[153].innerHTML = "<P class=\"p11 ft5\">Elabora:</P>";
	columnas[172].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[124].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[9].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[136].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[213].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[144].innerHTML = "<P class=\"p25 ft13\">Precio Final</P>";
	columnas[134].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[158].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[205].innerHTML = "<P style='padding-right:100px' class=\"p17 ft9\"><?PHP echo $row['cp'];?></P>";
	columnas[21].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[200].innerHTML = "<P class=\"p16 ft5\">Calle y No.:</P>";
	columnas[50].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[178].innerHTML = "<P class=\"p16 ft9\"><NOBR>UCM-PAK</NOBR></P>";
	columnas[115].innerHTML = "<P class=\"p16 ft5\">Razon Social :</P>";
	columnas[111].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[164].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[142].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[104].innerHTML = "<P class=\"p28 ft14\">$</P>";
	columnas[109].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[33].innerHTML = "<P class=\"p16 ft9\"><?PHP echo $row['cliente'];?></P>";
	columnas[156].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[93].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	columnas[40].innerHTML = "<P class=\"p29 ft4\">$</P>";
	columnas[233].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
	<?PHP
	while($row3 = $query3->fetch_array(MYSQLI_ASSOC)){
		//$row3 = $query3->fetch_array(MYSQLI_ASSOC);
	?>
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p30 ft9\"><?PHP echo $row3['partida'];?></P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p14 ft9\"><?PHP echo $row3['cantidad'];?></P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p16 ft9\"><NOBR><?PHP echo $row3['numero_parte'];?></NOBR></P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p16 ft9\"><?PHP echo $row3['descripcion'];?></P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p31 ft9\">$</P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p27 ft9\"><?PHP echo $row3['precio_lista'];?></P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p19 ft9\"><?PHP echo $row3['descuento'];?></P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p8 ft8\">&nbsp;</P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p32 ft9\">$</P>";
		columnas[<?PHP echo $i++;?>].innerHTML = "<P class=\"p29 ft9\"><?PHP echo number_format($row3['pfinal'],2);?></P>";
		
	<?PHP } 
	?>
	renglones[22].appendChild(columnas[240]);
	renglones[22].appendChild(columnas[242]);
	renglones[22].appendChild(columnas[6]);
	a3.appendChild(renglones[22]);
	renglones[1].appendChild(columnas[1]);
	renglones[1].appendChild(columnas[34]);
	renglones[1].appendChild(columnas[122]);
	a3.appendChild(renglones[1]);
	renglones[9].appendChild(columnas[162]);
	renglones[9].appendChild(columnas[153]);
	renglones[9].appendChild(columnas[64]);
	a3.appendChild(renglones[9]);
	renglones[12].appendChild(columnas[174]);
	renglones[12].appendChild(columnas[168]);
	renglones[12].appendChild(columnas[161]);
	a3.appendChild(renglones[12]);
	renglones[4].appendChild(columnas[128]);
	renglones[4].appendChild(columnas[150]);
	renglones[4].appendChild(columnas[170]);
	a3.appendChild(renglones[4]);
	renglones[11].appendChild(columnas[196]);
	renglones[11].appendChild(columnas[230]);
	renglones[11].appendChild(columnas[39]);
	a3.appendChild(renglones[11]);
	renglones[18].appendChild(columnas[233]);
	renglones[18].appendChild(columnas[85]);
	renglones[18].appendChild(columnas[97]);
	a3.appendChild(renglones[18]);
	renglones[10].appendChild(columnas[45]);
	renglones[10].appendChild(columnas[115]);
	renglones[10].appendChild(columnas[33]);
	renglones[10].appendChild(columnas[190]);
	renglones[10].appendChild(columnas[8]);
	renglones[10].appendChild(columnas[143]);
	renglones[10].appendChild(columnas[26]);
	renglones[10].appendChild(columnas[73]);
	renglones[10].appendChild(columnas[215]);
	renglones[10].appendChild(columnas[198]);
	renglones[10].appendChild(columnas[141]);
	renglones[10].appendChild(columnas[123]);
	renglones[10].appendChild(columnas[146]);
	a2.appendChild(renglones[10]);
	renglones[7].appendChild(columnas[113]);
	renglones[7].appendChild(columnas[200]);
	renglones[7].appendChild(columnas[155]);
	renglones[7].appendChild(columnas[16]);
	renglones[7].appendChild(columnas[203]);
	renglones[7].appendChild(columnas[51]);
	renglones[7].appendChild(columnas[20]);
	renglones[7].appendChild(columnas[201]);
	renglones[7].appendChild(columnas[110]);
	renglones[7].appendChild(columnas[111]);
	renglones[7].appendChild(columnas[219]);
	a2.appendChild(renglones[7]);
	renglones[24].appendChild(columnas[89]);
	renglones[24].appendChild(columnas[58]);
	renglones[24].appendChild(columnas[72]);
	renglones[24].appendChild(columnas[204]);
	renglones[24].appendChild(columnas[154]);
	renglones[24].appendChild(columnas[151]);
	renglones[24].appendChild(columnas[28]);
	renglones[24].appendChild(columnas[61]);
	renglones[24].appendChild(columnas[77]);
	renglones[24].appendChild(columnas[214]);
	renglones[24].appendChild(columnas[208]);
	a2.appendChild(renglones[24]);
	renglones[14].appendChild(columnas[19]);
	renglones[14].appendChild(columnas[7]);
	renglones[14].appendChild(columnas[205]);
	renglones[14].appendChild(columnas[180]);
	renglones[14].appendChild(columnas[52]);
	renglones[14].appendChild(columnas[87]);
	renglones[14].appendChild(columnas[2]);
	renglones[14].appendChild(columnas[70]);
	renglones[14].appendChild(columnas[185]);
	renglones[14].appendChild(columnas[236]);
	renglones[14].appendChild(columnas[139]);
	a2.appendChild(renglones[14]);
	renglones[17].appendChild(columnas[142]);
	renglones[17].appendChild(columnas[211]);
	renglones[17].appendChild(columnas[245]);
	renglones[17].appendChild(columnas[145]);
	renglones[17].appendChild(columnas[3]);
	renglones[17].appendChild(columnas[212]);
	renglones[17].appendChild(columnas[93]);
	renglones[17].appendChild(columnas[129]);
	renglones[17].appendChild(columnas[79]);
	renglones[17].appendChild(columnas[100]);
	renglones[17].appendChild(columnas[71]);
	renglones[17].appendChild(columnas[124]);
	renglones[17].appendChild(columnas[167]);
	a2.appendChild(renglones[17]);
	renglones[19].appendChild(columnas[148]);
	renglones[19].appendChild(columnas[69]);
	renglones[19].appendChild(columnas[184]);
	renglones[19].appendChild(columnas[158]);
	renglones[19].appendChild(columnas[232]);
	renglones[19].appendChild(columnas[21]);
	renglones[19].appendChild(columnas[228]);
	renglones[19].appendChild(columnas[206]);
	renglones[19].appendChild(columnas[192]);
	renglones[19].appendChild(columnas[209]);
	renglones[19].appendChild(columnas[210]);
	renglones[19].appendChild(columnas[66]);
	a2.appendChild(renglones[19]);
	renglones[20].appendChild(columnas[5]);
	renglones[20].appendChild(columnas[67]);
	renglones[20].appendChild(columnas[118]);
	renglones[20].appendChild(columnas[164]);
	renglones[20].appendChild(columnas[138]);
	renglones[20].appendChild(columnas[117]);
	renglones[20].appendChild(columnas[48]);
	renglones[20].appendChild(columnas[126]);
	renglones[20].appendChild(columnas[105]);
	renglones[20].appendChild(columnas[147]);
	renglones[20].appendChild(columnas[56]);
	renglones[20].appendChild(columnas[23]);
	renglones[20].appendChild(columnas[54]);
	a2.appendChild(renglones[20]);
	renglones[15].appendChild(columnas[102]);
	renglones[15].appendChild(columnas[46]);
	renglones[15].appendChild(columnas[135]);
	renglones[15].appendChild(columnas[86]);
	renglones[15].appendChild(columnas[44]);
	renglones[15].appendChild(columnas[4]);
	renglones[15].appendChild(columnas[38]);
	renglones[15].appendChild(columnas[101]);
	renglones[15].appendChild(columnas[13]);
	renglones[15].appendChild(columnas[177]);
	renglones[15].appendChild(columnas[49]);
	renglones[15].appendChild(columnas[35]);
	renglones[15].appendChild(columnas[187]);
	a2.appendChild(renglones[15]);
	renglones[23].appendChild(columnas[10]);
	renglones[23].appendChild(columnas[103]);
	renglones[23].appendChild(columnas[94]);
	renglones[23].appendChild(columnas[223]);
	renglones[23].appendChild(columnas[84]);
	renglones[23].appendChild(columnas[25]);
	renglones[23].appendChild(columnas[65]);
	renglones[23].appendChild(columnas[120]);
	renglones[23].appendChild(columnas[221]);
	renglones[23].appendChild(columnas[131]);
	renglones[23].appendChild(columnas[222]);
	renglones[23].appendChild(columnas[197]);
	renglones[23].appendChild(columnas[243]);
	a2.appendChild(renglones[23]);
	renglones[8].appendChild(columnas[9]);
	renglones[8].appendChild(columnas[217]);
	renglones[8].appendChild(columnas[189]);
	renglones[8].appendChild(columnas[112]);
	renglones[8].appendChild(columnas[22]);
	renglones[8].appendChild(columnas[202]);
	renglones[8].appendChild(columnas[160]);
	renglones[8].appendChild(columnas[225]);
	renglones[8].appendChild(columnas[59]);
	renglones[8].appendChild(columnas[213]);
	renglones[8].appendChild(columnas[96]);
	renglones[8].appendChild(columnas[144]);
	a2.appendChild(renglones[8]);
	/*
	renglones[25].appendChild(columnas[37]);
	renglones[25].appendChild(columnas[27]);
	renglones[25].appendChild(columnas[149]);
	renglones[25].appendChild(columnas[99]);
	renglones[25].appendChild(columnas[195]);
	renglones[25].appendChild(columnas[12]);
	renglones[25].appendChild(columnas[235]);
	renglones[25].appendChild(columnas[231]);
	renglones[25].appendChild(columnas[181]);
	renglones[25].appendChild(columnas[175]);
	renglones[25].appendChild(columnas[116]);
	renglones[25].appendChild(columnas[246]);
	renglones[25].appendChild(columnas[18]);
	a2.appendChild(renglones[25]);
    
	renglones[13].appendChild(columnas[127]);
	renglones[13].appendChild(columnas[92]);
	renglones[13].appendChild(columnas[157]);
	renglones[13].appendChild(columnas[32]);
	renglones[13].appendChild(columnas[130]);
	renglones[13].appendChild(columnas[98]);
	renglones[13].appendChild(columnas[76]);
	renglones[13].appendChild(columnas[165]);
	renglones[13].appendChild(columnas[78]);
	renglones[13].appendChild(columnas[152]);
	renglones[13].appendChild(columnas[43]);
	renglones[13].appendChild(columnas[55]);
	renglones[13].appendChild(columnas[238]);
	a2.appendChild(renglones[13]);
	
	
	renglones[2].appendChild(columnas[63]);
	renglones[2].appendChild(columnas[186]);
	renglones[2].appendChild(columnas[140]);
	renglones[2].appendChild(columnas[178]);
	renglones[2].appendChild(columnas[199]);
	renglones[2].appendChild(columnas[207]);
	renglones[2].appendChild(columnas[106]);
	renglones[2].appendChild(columnas[108]);
	renglones[2].appendChild(columnas[30]);
	renglones[2].appendChild(columnas[194]);
	renglones[2].appendChild(columnas[183]);
	renglones[2].appendChild(columnas[166]);
	renglones[2].appendChild(columnas[227]);
	a2.appendChild(renglones[2]);*/
	<?PHP 
		$j1 = 26;
	    $i1 = 247;
		while($i1<$i){
	?>
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	renglones[<?PHP echo $j1;?>].appendChild(columnas[<?PHP echo $i1++;?>]);
	a2.appendChild(renglones[<?PHP echo $j1++;?>]);
	<?PHP } ?>
	renglones[5].appendChild(columnas[83]);
	renglones[5].appendChild(columnas[11]);
	renglones[5].appendChild(columnas[172]);
	renglones[5].appendChild(columnas[133]);
	renglones[5].appendChild(columnas[229]);
	renglones[5].appendChild(columnas[62]);
	renglones[5].appendChild(columnas[176]);
	renglones[5].appendChild(columnas[15]);
	renglones[5].appendChild(columnas[95]);
	renglones[5].appendChild(columnas[107]);
	renglones[5].appendChild(columnas[75]);
	renglones[5].appendChild(columnas[47]);
	renglones[5].appendChild(columnas[90]);
	a2.appendChild(renglones[5]);
	renglones[16].appendChild(columnas[82]);
	renglones[16].appendChild(columnas[42]);
	renglones[16].appendChild(columnas[125]);
	renglones[16].appendChild(columnas[244]);
	renglones[16].appendChild(columnas[114]);
	renglones[16].appendChild(columnas[216]);
	renglones[16].appendChild(columnas[74]);
	renglones[16].appendChild(columnas[220]);
	renglones[16].appendChild(columnas[88]);
	renglones[16].appendChild(columnas[188]);
	renglones[16].appendChild(columnas[104]);
	renglones[16].appendChild(columnas[239]);
	a2.appendChild(renglones[16]);
	renglones[0].appendChild(columnas[36]);
	renglones[0].appendChild(columnas[241]);
	renglones[0].appendChild(columnas[234]);
	renglones[0].appendChild(columnas[0]);
	renglones[0].appendChild(columnas[171]);
	renglones[0].appendChild(columnas[80]);
	renglones[0].appendChild(columnas[29]);
	renglones[0].appendChild(columnas[134]);
	renglones[0].appendChild(columnas[17]);
	renglones[0].appendChild(columnas[57]);
	renglones[0].appendChild(columnas[237]);
	renglones[0].appendChild(columnas[159]);
	a2.appendChild(renglones[0]);
	renglones[21].appendChild(columnas[137]);
	renglones[21].appendChild(columnas[218]);
	renglones[21].appendChild(columnas[179]);
	renglones[21].appendChild(columnas[191]);
	renglones[21].appendChild(columnas[226]);
	renglones[21].appendChild(columnas[41]);
	renglones[21].appendChild(columnas[132]);
	renglones[21].appendChild(columnas[169]);
	renglones[21].appendChild(columnas[224]);
	renglones[21].appendChild(columnas[193]);
	renglones[21].appendChild(columnas[60]);
	renglones[21].appendChild(columnas[119]);
	renglones[21].appendChild(columnas[121]);
	a2.appendChild(renglones[21]);
	renglones[3].appendChild(columnas[68]);
	renglones[3].appendChild(columnas[50]);
	renglones[3].appendChild(columnas[173]);
	renglones[3].appendChild(columnas[31]);
	renglones[3].appendChild(columnas[14]);
	renglones[3].appendChild(columnas[109]);
	renglones[3].appendChild(columnas[136]);
	renglones[3].appendChild(columnas[156]);
	renglones[3].appendChild(columnas[81]);
	renglones[3].appendChild(columnas[24]);
	renglones[3].appendChild(columnas[40]);
	renglones[3].appendChild(columnas[182]);
	a2.appendChild(renglones[3]);
	renglones[6].appendChild(columnas[53]);
	renglones[6].appendChild(columnas[163]);
	renglones[6].appendChild(columnas[91]);
	a1.appendChild(renglones[6]);
}

</script>

<script>
<!--- Hide from tired old browsers
// preload images:

     bt_051158_1 = new Image();
     bt_051158_2 = new Image();
     bt_051158_2.src="./botones_files/enviar_1.gif";
     bt_051158_1.src="./botones_files/enviar_2.gif";

function bt_051158_in() 
{
  bt_051158.src = bt_051158_2.src;
}

function bt_051158_out() 
{
  bt_051158.src = bt_051158_1.src;
}

// end hiding --->
</script>
<script>
<!--- Hide from tired old browsers
// preload images:

     bt_043407_1 = new Image();
     bt_043407_2 = new Image();
     bt_043407_2.src="./botones_files/modificar_1.gif";
     bt_043407_1.src="./botones_files/modificar_2.gif";

function bt_043407_in() 
{
  bt_043407.src = bt_043407_2.src;
}

function bt_043407_out() 
{
  bt_043407.src = bt_043407_1.src;
}

// end hiding --->
</script>
<script>
<!--- Hide from tired old browsers
// preload images:

     bt_guardar_1 = new Image();
     bt_guardar_2 = new Image();
     bt_guardar_2.src="./botones_files/guardar_1.gif";
     bt_guardar_1.src="./botones_files/guardar_2.gif";

function bt_guardar_in() 
{
  bt_guardar.src = bt_guardar_2.src;
}

function bt_guardar_out() 
{
  bt_guardar.src = bt_guardar_1.src;
}

// end hiding --->
</script>
             
<script>
<!--- Hide from tired old browsers
// preload images:

     bt_cancelar_1 = new Image();
     bt_cancelar_2 = new Image();
     bt_cancelar_2.src="./botones_files/cancelar_1.GIF";
     bt_cancelar_1.src="./botones_files/cancelar_2.GIF";

function bt_cancelar_in() 
{
  bt_cancelar.src = bt_cancelar_2.src;
}

function bt_cancelar_out() 
{
  bt_cancelar.src = bt_cancelar_1.src;
}

// end hiding --->
</script>
<meta http-equiv="Refresh" content="1200;url=logout.php">

</HEAD>

<BODY onload="fourth()">
  <div id="menu">
  <?PHP if($row["estatus_compra_idestatus_compra"]!='3'){?>
  <a href="orden_compra_opciones.php?id=<?PHP echo $id?>&opcion=1"><img name="bt_guardar" onmouseover="bt_guardar_in()" onmouseout="bt_guardar_out()" border="0" alt="Guardar" src="./botones_files/guardar_2.gif" align="center" width="250" height="90"></a>
  
  <a href="orden_compra_opciones.php?id=<?PHP echo $id?>&opcion=2"><img name="bt_051158" onmouseover="bt_051158_in()" onmouseout="bt_051158_out()" border="0" alt="Enviar" src="./botones_files/enviar_2.gif" align="center" width="250" height="90"></a>
  <a href="compras_clientes.php?id=<?PHP echo $id?>"><img name="bt_043407" onmouseover="bt_043407_in()" onmouseout="bt_043407_out()" border="0" alt="Modificar" src="./botones_files/modificar_2.gif" align="center" width="250" height="90"></a>
  <a href="orden_compra_opciones.php?id=<?PHP echo $id?>&opcion=4"><img name="bt_cancelar" onmouseover="bt_cancelar_in()" onmouseout="bt_cancelar_out()" border="0" alt="Cancelar" src="./botones_files/cancelar_2.GIF" align="center" width="250" height="90"></a>
  <?PHP } else {?>
  
  <a href="orden_compra_opciones.php?id=<?PHP echo $id?>&opcion=3"><img name="bt_043407" onmouseover="bt_043407_in()" onmouseout="bt_043407_out()" border="0" alt="Modificar" src="./botones_files/modificar_2.gif" align="center" width="250" height="90"></a>
  
  <?PHP } ?>
  </div>
  <div>
  <p style="font-size:30px;text-align:center;"><?PHP echo $mensaje;?>
  </p>
  </div>
              
<DIV id="page_1" class="zoom">
<DIV id="p1dimg1">
<IMG src="orden_compra_template_images/orden_compra_template1x1.jpg" id="p1img1">
</DIV>


<DIV class="dclr"></DIV>
<DIV id="id_1">
<DIV id="id_1_1">
<P class="p0 ft0">Eclipse Telecomunicaciones S.A. de C.V.</P>
<P class="p0 ft1">Insurgentes Sur # 105, Piso <NOBR>14-B</NOBR></P>
<P class="p1 ft1">Col. Juarez, México D.F., C.P. 06600</P>
<P class="p2 ft1">ETE 081217 AU4 <A href="mailto:facturacion@eclipsemex.com"><SPAN class="ft2">facturacion@eclipsemex.com</SPAN></A></P>
<P class="p0 ft1">Tel. (52) 55 5532 2480 / 55 3626 2939 / 55 6721 8589</P>
</DIV>
<DIV id="id_1_2">
<P class="p0 ft3">Orden de Compra</P>
<?PHP if($row["estatus_compra_idestatus_compra"]=='3'){?>
<P style="color:red" class="p0 ft3">CANCELADA</P>
<?PHP } ?>
</DIV>
</DIV>
<DIV id="id_2">
<P class="p3 ft4">Proveedor</P>
<TABLE id="t3" cellpadding=0 cellspacing=0 class="t0"></TABLE>
<P style="margin-top:2px" class="p15 ft4">Usuario Final</P>
<TABLE id="t2" cellpadding=0 cellspacing=0 class="t1"></TABLE>
</DIV>
<DIV id="id_3">
<TABLE id="t1" cellpadding=0 cellspacing=0 class="t2"></TABLE>
</DIV>
</DIV>
</BODY>
</HTML>
<?PHP 
$mysqli->close();
?>
