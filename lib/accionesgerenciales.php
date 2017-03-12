<?php
class acciongerencial{
    
    function __construct(){
        
    }
    
    function conexionBD() {
        return pg_connect("user=usraccion password=4cci0n dbname=clientservice host=localhost port=5432");
    }
    
    function crearaccion($token, $mensaje, $tipo, $vendedor){
        $conexion = $this->conexionBD();
		$query = "select crear_accion('".$token."'::text, '".$mensaje."'::text, ".$tipo."::smallint, 1::smallint, '".$vendedor."'::text)";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $venta = pg_fetch_all($resultado);
				if($venta[0]['crear_accion'] != -1){					
					return $venta[0]['crear_accion'];
				}else{
					return -1;
				}				                
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function cargarAcciones($vendedor){
        $conexion = $this->conexionBD();
		$query = "select id_accion, mensaje From accion_empleado where
	           id_empleado = '".$vendedor."'";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $acciones = pg_fetch_all($resultado);                
                return $acciones;				                
            }			
        }		
        pg_close($conexion);
		return false;
    }
}
?>