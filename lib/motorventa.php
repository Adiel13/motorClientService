<?php

class motorventa{
    
    function __construct(){
        
    }

    function conexionBD() {
        return pg_connect("user=usrventa password=v3nt4 dbname=clientservice host=localhost port=5432");
    }

    function crearVenta($token, $sistema){
        $conexion = $this->conexionBD();
		$query = "select crear_venta('".$token."'::text, ".$sistema."::smallint)";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $venta = pg_fetch_all($resultado);
               // return $venta[0]['crear_venta'];
				if($venta[0]['crear_venta'] != -1){					
					return $venta[0]['crear_venta'];
				}else{
					return -1;
				}				                
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function finalizarVenta($idventa){
        $conexion = $this->conexionBD();
		$query = "select finalizar_venta('".$idventa."'::smallint)";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $venta = pg_fetch_all($resultado);
                
				if($venta[0]['finalizar_venta'] == 1){					
					return $venta[0]['finalizar_venta'];
				}else{
					return -1;
				}				                
            }			
        }		
        pg_close($conexion);
		return false;
    }
}

/*$obj = new motorventa();
$idventa= $obj->crearVenta("3f2b1341b13a49aacedb7da8380451b2", 1);
echo $obj->finalizarVenta($idventa);*/

?>