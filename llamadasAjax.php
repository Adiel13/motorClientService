<?php
require_once('lib/nusoap.php');
include_once('../lib/obtenerinfo.php');

function sendDataWS($nombreParametro, $data, $nombreMetodo){
	$WSDL = "http://67.205.153.106/lib/webservice.php?wsdl";
	$param = array($nombreParametro => $data);
    $soapClient = new nusoap_client($WSDL, 'wsdl');
    $err = $soapClient->getError();
    if ($err) {
		return $err;
	}
    $response = $soapClient->call($nombreMetodo, $param);
    return $response;
}

if($_POST['iniciosesion']){
	$datos = array('usuario' => $_POST['usuariosesion'],
					'token' => md5($_POST['passesion']),
					'sistema' => 1);
	$respuesta = sendDataWS("datos_persona_entrada", $datos, 'inicio_sesion');	
	print_r (json_encode($respuesta));
}else if($_POST['verificarsesion']){
	$datos = array('usuario' => $_POST['usuariosesion'],
					'token' => $_POST['tokensesion'],
					'sistema' => $_POST['sistemasesion']);
	$respuesta = sendDataWS("datos_persona_entrada", $datos, 'esSesionActiva');	
	print_r (json_encode($respuesta));
}else if($_POST['cerrarSesion']){
	$datos = array('usuario' => $_POST['usuariosesion'],
					'token' => $_POST['tokensesion'],
					'sistema' => $_POST['sistemasesion']);
	$respuesta = sendDataWS("datos_persona_entrada", $datos, 'cerrarSesion');	
	print_r (json_encode($respuesta));
}else if($_POST['datospersonales']){
	$datos = array('usuario' => $_POST['usuariosesion']);
	$respuesta = sendDataWS("datos_usuario", $datos, 'obtenerDatosPersonales');	
	print_r (json_encode($respuesta));
}else if($_POST['sucursales']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerListadoSucursales($_POST['tokensesion']);
	print_r (json_encode($respuesta));
}else if($_POST['tablallegada']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerTablaLlegada($_POST['finicio'], $_POST['ffin'], $_POST['sucursal']);
	print_r (json_encode($respuesta));
}else if($_POST['graficollegada']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerPromedioLlegada($_POST['finicio'], $_POST['ffin'], $_POST['sucursal']);
	print_r (json_encode($respuesta));
}else if($_POST['graficoatencionvsventa']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerAtencionVsVenta($_POST['finicio'], $_POST['ffin'], $_POST['sucursal']);
	print_r (json_encode($respuesta));
}else if($_POST['obteneratendidos']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerNumeroAtendidos($_POST['finicio'], $_POST['ffin'], $_POST['sucursal']);
	print_r (json_encode($respuesta));
}else if($_POST['obtenertiempo']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerTiempoAtencion($_POST['finicio'], $_POST['ffin'], $_POST['sucursal']);
	print_r (json_encode($respuesta));
}else if($_POST['obtenerTablaVenta']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerTablaVenta($_POST['finicio'], $_POST['ffin'], $_POST['sucursal']);
	print_r (json_encode($respuesta));
}else if($_POST['obtenerVendedores']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerVendedores($_POST['sucursal']);
	print_r (json_encode($respuesta));
}else if($_POST['obtenerVentas']){
    $f = new obtenerinfo();
    $respuesta = $f->obtenerVentas($_POST['empleado']);
	print_r (json_encode($respuesta));
}
?>
