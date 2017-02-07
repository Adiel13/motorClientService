<?php
require_once('lib/nusoap.php');
include_once('iniciosesion.php');
include_once('obtenerinfo.php');
include_once('motorventa.php');

$server = new nusoap_server();
$server->configureWSDL('Servicio ITG', 'urn:mi_ws1');

$server->wsdl->addComplexType(  'datos_persona_entrada', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('usuario'   => array('name' => 'nombre','type' => 'xsd:string'),
                                      'token'    => array('name' => 'email','type' => 'xsd:string'),
                                      'sistema' => array('name' => 'sistema','type' => 'xsd:string'))
);

$server->wsdl->addComplexType(  'datos_venta_entrada', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('token'   => array('name' => 'token','type' => 'xsd:string'),
                                      'sistema' => array('name' => 'sistema','type' => 'xsd:integer'))
);

$server->wsdl->addComplexType(  'datos_usuario', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('usuario'   => array('name' => 'nombre','type' => 'xsd:string'))
);


$server->wsdl->addComplexType(  'datos_persona_salidad', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('mensaje'   => array('name' => 'mensaje','type' => 'xsd:string'))
);


$server->wsdl->addComplexType(  'esactivo', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('mensaje'   => array('name' => 'acitvo','type' => 'xsd:integer'))
);

function inicio_sesion($datos) {
	$sesion = new iniciosesion();
	$res = $sesion->iniciarsesion($datos['usuario'], $datos['token'], $datos['sistema']);
	//return array('mensaje' =>  print_r($datos,true));
	$arr = array('token' => $sesion->tokenSesion, 'permisos' => $sesion->permisos);
	return array('mensaje' => json_encode($arr));
}

function esSesionActiva($datos){
	$sesion = new iniciosesion();
	$res = $sesion->esSesionActiva($datos['usuario'], $datos['token'], $datos['sistema']);
	return array('mensaje' => $res);	
}

function cerrarSesion($datos){
    $sesion = new iniciosesion();
    $res = $sesion->cerrarSesion($datos['usuario'], $datos['token'], $datos['sistema']);
    return array('mensaje' => $res);    
}

function obtenerDatosPersonales($datos){
    $info = new obtenerinfo();
    $res = $info->obtenerDatos($datos['usuario']);
    return array('mensaje' => $res);    
}

function crearVenta($datos){
    $venta = new motorVenta();
    $res = $venta->crearVenta($datos['token'], $datos['sistema']);
    return array('mensaje' => $res);    
}

function finalizarVenta($datos){
    $venta = new motorVenta();
    $res = $venta->finalizarVenta($datos['mensaje']);
    return array('mensaje' => $res);    
}

$server->register(  'finalizarVenta', // nombre del metodo o funcion
                    array('esactivo' => 'tns:esactivo'), // parametros de entrada
                    array('return' => 'tns:esactivo'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:hellowsdl2#calculo_edad', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'La siguiente funcion crea una venta' 
);

$server->register(  'crearVenta', // nombre del metodo o funcion
                    array('datos_venta_entrada' => 'tns:datos_venta_entrada'), // parametros de entrada
                    array('return' => 'tns:esactivo'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:hellowsdl2#calculo_edad', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'La siguiente funcion crea una venta' 
);

$server->register(  'inicio_sesion', // nombre del metodo o funcion
                    array('datos_persona_entrada' => 'tns:datos_persona_entrada'), // parametros de entrada
                    array('return' => 'tns:datos_persona_salidad'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:hellowsdl2#calculo_edad', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'La siguiente funcion recibe las credenciales e inicia sesion' 
);

$server->register(  'esSesionActiva', // nombre del metodo o funcion
                    array('datos_persona_entrada' => 'tns:datos_persona_entrada'), // parametros de entrada
                    array('return' => 'tns:esactivo'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:hellowsdl2#calculo_edad', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Funcion que verifica si es activa una sesión' 
);

$server->register(  'cerrarSesion', // nombre del metodo o funcion
                    array('datos_persona_entrada' => 'tns:datos_persona_entrada'), // parametros de entrada
                    array('return' => 'tns:esactivo'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:hellowsdl2#calculo_edad', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Funcion que cierra la sesion' 
);


$server->register(  'obtenerDatosPersonales', // nombre del metodo o funcion
                    array('datos_usuario' => 'tns:datos_usuario'), // parametros de entrada
                    array('return' => 'tns:datos_persona_salidad'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:hellowsdl2#calculo_edad', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'Funcion que obtiene los datos de un empleado' 
);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
