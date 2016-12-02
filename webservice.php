<?php
require_once('lib/nusoap.php');
include_once('iniciosesion.php');
$server = new nusoap_server();
$server->configureWSDL('Servicio ITG', 'urn:mi_ws1');

$server->wsdl->addComplexType(  'datos_persona_entrada', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('usuario'   => array('name' => 'nombre','type' => 'xsd:string'),
                                      'token'    => array('name' => 'email','type' => 'xsd:string'),
                                      'sistema' => array('name' => 'telefono','type' => 'xsd:string'))
);

$server->wsdl->addComplexType(  'datos_persona_salidad', 
                                'complexType', 
                                'struct', 
                                'all', 
                                '',
                                array('mensaje'   => array('name' => 'mensaje','type' => 'xsd:string'))
);

function inicio_sesion($datos) {
	
	$sesion = new iniciosesion();
	$res = $sesion->iniciarsesion($datos['usuario'], $datos['token'], $datos['sistema']);
	
	/*if($res){
		echo $sesion->tokenSesion;
		return array('mensaje' => 'hubo error');
	}*/
	return array('mensaje' => $sesion->tokenSesion);

    /*$edad_actual = date('Y') - $datos['ano_nac'];
    $msg = 'Hola, ' . $datos['nombre'] . '. Hemos procesado la siguiente informacion ' . $datos['email'] . ', telefono'. $datos['telefono'].' y su Edad actual es: ' . $edad_actual . '.'; 
    return array('mensaje' => $msg);*/
}

$server->register(  'inicio_sesion', // nombre del metodo o funcion
                    array('datos_persona_entrada' => 'tns:datos_persona_entrada'), // parametros de entrada
                    array('return' => 'tns:datos_persona_salidad'), // parametros de salida
                    'urn:mi_ws1', // namespace
                    'urn:hellowsdl2#calculo_edad', // soapaction debe ir asociado al nombre del metodo
                    'rpc', // style
                    'encoded', // use
                    'La siguiente funcion recibe las credenciales e inicia sesion' 
);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>